<?php

if (isset($_GET['path'])) {
	$file = $_GET['path'];
}

if(!empty($file)) {
	$content = file_get_contents($file);
	header("Content-type: text/xml; charset=utf-8");
	echo $content;
}