<?php

function countWords($endPoint, $key, $double = false)
{
  $response = $endPoint;

  $result = preg_replace("/[\r\n|\n|\r]+/", " ", $response->collect()->implode($key, ' '));
  $resultArray = explode(" ", $result);
  $numberOfWords = collect($resultArray)->countBy();
  if ($double) {
    $values = $numberOfWords->map(function ($word) {
      return $word * 2;
    });

    return $values;
  }

  return $numberOfWords;
}

function exportFileCSV($file, $headers, $list) {
  $handle = fopen($file . '.csv', 'w');
  fputcsv($handle, $headers, ';');
  foreach ($list as $row) {
      fputcsv($handle, $row, ';');
  }
}
