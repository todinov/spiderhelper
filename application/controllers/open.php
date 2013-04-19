<?php

class Open extends Controller {
	public function index() {
		return;
	}

	public function xml($file) {
		if (isset($file[0])) {
			$path = '/var/www/spiderman/data/'.$file[0];
			header("Content-type: text/xml; charset=utf-8");
			echo file_get_contents($path);
		}
	}

	public function json($file) {
		if (isset($file[0])) {
			$path = '/var/www/spiderman/data/json/'.$file[0];
			header("Content-type: text/json; charset=utf-8");
			echo file_get_contents($path);
		}
	}
}