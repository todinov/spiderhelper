<?php

include 'db.php';

$sites = array();
foreach ($db->query('SELECT * FROM site') as $site) {
	$sites[$site['botname']] = $site;
}

$dir = '/var/lib/scrapyd/items/initialbot/*/*';

$files = array();
foreach (glob($dir) as $file) {
	$info = pathinfo($file);
	$path = explode('/', $info['dirname']);
	$name = $path[count($path)-1];

	if (!isset($files[$name])) {
		$files[$name] = array();
	}

	$parsecmd  = '/var/www/spiderman/www/protected/yiic app Parser';
	$parsecmd .= ' --siteid='.$sites[$name]['id'];
	$parsecmd .= ' --botname='.$name;
	$parsecmd .= ' --parser='.$sites[$name]['parsername'];
	$parsecmd .= ' --jobid='.$info['filename'];

	$testparsecmd  = '/var/www/spiderman/www/protected/yiic app Testparser';
	$testparsecmd .= ' --infile='.$file;
	$testparsecmd .= ' --outfile=./data/'.$name.'.xml';

	$files[$name][] = array( 
		'date' => filemtime($file),
		'name' => $info['basename'],
		'path' => $file,
		'size' => round(filesize($file) / 1024, 1) .'KB',
		'parsecmd' => $parsecmd,
		'testparsecmd' => $testparsecmd
	);
}

foreach ($files as $key => $val) {
	usort($files[$key], function($a, $b) { return ($a['date'] > $b['date']); });
}

include 'templates/header.tpl.php';
include 'templates/index.tpl.php';
include 'templates/footer.tpl.php';