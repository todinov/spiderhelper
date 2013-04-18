<?php

$dir = '/var/www/spiderman/data/*.xml';

$files = array();
foreach (glob($dir) as $file) {
	$info = pathinfo($file);
	$path = explode('/', $info['dirname']);
	$name = explode('_', $info['basename']);
	$name = str_replace('.xml', '', $name[0]);

	if (!isset($files[$name])) {
		$files[$name] = array();
	}

	$testparsecmd = './www/protected/yiic app Testparser';
	$testparsecmd .= ' --infile='.$file;
	$testparsecmd .= ' --outfile=./data/'.$name.'.xml';

	$files[$name][] = array( 
		'date' => date('d.m.Y H:i:s',filemtime($file)), 
		'name' => $info['basename'], 
		'path' => $file,
		'size' => round(filesize($file) / 1024, 1) .'KB',
		'testparsecmd' => $testparsecmd
	);
}

foreach ($files as $key => $val) {
	usort($files[$key], function($a, $b) { return ($a['date'] > $b['date']); });
}

include 'templates/header.tpl.php';
include 'templates/dataxml.tpl.php';
include 'templates/footer.tpl.php';