<?php

if (isset($_GET['file'])) {
	$file = $_GET['file'];
}

if(!empty($file)) {
	$cmd = '/var/www/spiderman/www/protected/yiic jl analyze --infile='.$file;
	$data = shell_exec($cmd);
	//$data = nl2br($data);

	$data = str_replace('      ', '::', $data);

	$results = array();

	preg_match('/\[\'CategoryStruct\'\]: (?P<digit>\d+)/', $data, $matches);
	$results['categorycount'] = $matches['digit'];

	preg_match('/\[\'ProductItem\'\]: (?P<digit>\d+)/', $data, $matches);
	$results['productcount'] = $matches['digit'];

	preg_match_all('/::(?<name>.+) Products: (?<prodcount>\d+) Pages: (?<pagecount>\d+)/', $data, $matches);

	$allprod = 0;
	$allpage = 0;
	
	$results['categories'] = array();
	foreach ($matches['name'] as $key => $value) {
		
		$allprod += $matches['prodcount'][$key];
		$allpage += $matches['pagecount'][$key];

		$results['categories'][] = array(
			'name' => $value,
			'products' => $matches['prodcount'][$key],
			'pages' => $matches['pagecount'][$key]
		);
	}
}

include 'templates/header.tpl.php';
include 'templates/analyze.tpl.php';
include 'templates/footer.tpl.php';