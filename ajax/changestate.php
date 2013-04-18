<?php

try {
	$db = new PDO('mysql:host=localhost;dbname=tasks', 'spiderman', '123456');
}
catch (Exception $e) {
	echo $e->getMessage();
}

$state = 0;
if ($_POST['state'] == 'true') {
	$state = 1;
}

$stmt = $db->prepare('UPDATE todo SET state=:state WHERE id=:id');
$stmt->bindValue(':state', $state, PDO::PARAM_INT);
$stmt->bindValue(':id', $_POST['id'], PDO::PARAM_INT);
$stmt->execute();