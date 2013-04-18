<?php

class Tasks {
	public function __construct() {
		try {
			$this->db = new PDO('mysql:host=localhost;dbname=tasks', 'spiderman', '123456');
		}
		catch (Exception $e) {
			echo $e->getMessage();
		}
	}

	public function index() {
		$query = $this->db->query('SELECT * FROM todo ORDER BY state, createdate DESC');
		$tasks = $query->fetchAll();

		include 'templates/header.tpl.php';
		include 'templates/todo.tpl.php';
		include 'templates/footer.tpl.php';
	}

	public function add() {
		$stmt = $this->db->prepare('INSERT INTO todo (name, description) VALUES (:name, :description)');
		$stmt->bindValue(':name', $_GET['taskname']);
		$stmt->bindValue(':description', $_GET['taskdesc']);
		$stmt->execute();
		header('location:todo.php');
		exit;
	}
}

$tasks = new Tasks();

if (isset($_GET['taskname']) && isset($_GET['taskdesc'])) {
	$tasks->add();
}

$tasks->index();