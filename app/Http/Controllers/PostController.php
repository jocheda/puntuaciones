<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class PostController
{
    private $endPoint = 'https://jsonplaceholder.typicode.com/';
    private $headers = ["Id Usuario", "Nombre usuario", "Valoracion de usuario", "Id Post", "Valoracion de post"];

    public function rating()
    {
        $response = Http::get($this->endPoint . 'posts');

        $wordsInTitle = countWords($response, 'title', true);
        $wordsInBody = countWords($response, 'body');

        $allValues = $wordsInBody->mergeRecursive($wordsInTitle);

        $this->ratings = $allValues->map(function ($word) {
            return collect($word)->reduce(function ($carry, $value) {
                return $carry + $value;
            });
        });

        $posts = $response->collect()->jsonserialize();

        foreach ($posts as $key => $post) {
            $resultArray = explode(" ", preg_replace('/[\n\r\t]+/', ' ', $post['body']));
            $numberOfWords = collect($resultArray)->countBy();
            $value = $numberOfWords->reduce(function ($carry, $value, $key) {
                return $carry + ($value * $this->ratings->collect()->get($key));
            });

            $posts[$key]['valoration'] = $value;
        }

        $response = Http::get($this->endPoint . 'users');
        $users = $response->collect()->jsonserialize();


        foreach ($users as $keyUser => $user) {
            $value = 0;
            foreach ($posts as $key => $post) {
                if ($user['id'] == $post["userId"]) {
                    $value +=  $post['valoration'];
                    $users[$keyUser]['valoration'] = $value;
                }
            }
        }

        foreach ($users as $keyUser => $user) {
            foreach ($posts as $key => $post) {
                if ($user['id'] == $post["userId"]) {
                    $name = $user['name'];
                    $valor =  array(
                        'Id Usuario' => $post["userId"],
                        'Nombre usuario' => $name,
                        'Valoracion de usuario' => $user['valoration'],
                        'Id Post' => $post['id'],
                        'Valoracion de post' => $post['valoration']
                    );
                    $list[$key] = $valor;
                }
            }
        }

        $sorted = collect($list)->sortBy([
            ['Valoracion de usuario', 'desc'],
            ['Valoracion de post', 'desc'],
        ]);

        exportFileCSV("valorations", $this->headers, $sorted->values()->all());

        return $sorted->values()->all();
    }
}
