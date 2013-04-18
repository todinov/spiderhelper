<?php

$cmd = '/var/www/spiderman/www/protected/yiic app AutomapColours';
$data = shell_exec($cmd);

preg_match_all('/::(?<from>.+) >>> (?<to>.+)/', $data, $matches);

$results = array();

$no_color = 0;
foreach ($matches['from'] as $key => $value) {
	if ($matches['to'][$key] == 'No Colour') {
		++$no_color;
	}
	$results[] = array(
		'from' => $value,
		'to' => $matches['to'][$key] == 'No Colour'? '<b>'.$matches['to'][$key].'</b>': $matches['to'][$key]
	);
}

//var_dump($results);

include 'templates/header.tpl.php';
include 'templates/colourmapping.tpl.php';
include 'templates/footer.tpl.php';