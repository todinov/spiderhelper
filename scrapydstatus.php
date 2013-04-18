<?php

$ch = curl_init();
$url = 'http://localhost:6800/listjobs.json?project=initialbot';
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER , 1);
$data = json_decode(curl_exec($ch), true);
curl_close($ch);

unset($data['status']);



include 'templates/header.tpl.php';
include 'templates/scrapydstatus.tpl.php';
include 'templates/footer.tpl.php';