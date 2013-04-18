<?php

try {
	$db = new PDO('mysql:host=localhost;dbname=spiderman', 'spiderman', '123456');
}
catch (Exception $e) {
	echo $e->getMessage();
}