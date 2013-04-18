<?php

if (isset($_GET['file'])) {
	$file = $_GET['file'];
}

if(!empty($file)) {
	$content = file_get_contents($file);
	header("Content-type: text; charset=utf-8");
	echo $content;
}