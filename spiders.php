<?php

$dir = '/var/www/spiderman/test/initialbot/initialbot/spiders/*.py';

$files = array();
foreach (glob($dir) as $file) {
	$info = pathinfo($file);
	$path = explode('/', $info['dirname']);
	$name =$info['filename'];
	if ($name == '__init__') continue;

	$data = file_get_contents($file);
	if (strpos($data, '(BaseSpider)')) {
		$baseclass = 'BaseSpider';
	}
	else if (strpos($data, '(CrawlSpider)')){
		$baseclass = '<b>CrawlSpider</b>';
	}
	else {
		$baseclass = '<i>Custom</i>';
	}

	$files[$name] = array( 
		'date' => date('d.m.Y H:i:s',filemtime($file)), 
		'name' => $info['basename'], 
		'path' => $file,
		'size' => round(filesize($file) / 1024, 1) .'KB',
		'class' => $baseclass
	);
}
if (isset($_GET['sort'])) {
	if ($_GET['sort'] == 'class') {
		usort($files, function($a, $b) { return ($a['class'] < $b['class']); });
	}
}

include 'templates/header.tpl.php';
include 'templates/spiders.tpl.php';
include 'templates/footer.tpl.php';