#!/usr/bin/env php
<?php

$date = date('Y-m-d');
$days = 55;
$baseUrl = 'https://api.nasa.gov/planetary/apod?';
$start = (int)strtotime($date);
$saveToDir = __DIR__.'/../images';

if(file_exists($saveToDir) === false) {
    mkdir($saveToDir);
}

$data = [
    'api_key' => 'ZWjejc3G6J11hHyviv5tRHYocS8fmUmeZKxFAC0T',
    'hd' => 'false',
    'date' => date('Y-m-d', $start),
];

for($day=0;$day<$days;$day++) {
    $start -= 86400;
    $data['date'] = date('Y-m-d', $start);
    $queryParameter = http_build_query($data);
    $curl = curl_init($baseUrl.$queryParameter);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $responseJson = curl_exec($curl);
    $resArr = json_decode($responseJson, true);
    if(empty($resArr['title'])) {
        echo 'The '.$baseUrl.'is error when doing requesting.'.PHP_EOL;
        continue;
    }
    curl_close($curl);
    saveToImg($saveToDir, $resArr['url'], $data['date']);
}

function saveToImg($saveToDir, $imgUrl, $date) {
    $fileName = $saveDir.'/'.$date.'.jpg';
    exec('wget -O '.$fileName.' '.$imgUrl, $out, $code);
}

