<?php

class Scrapydstatus extends Controller {
	public function index()
	{
		$ch = curl_init();
		$url = 'http://localhost:6800/listjobs.json?project=initialbot';
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER , 1);
		$data = json_decode(curl_exec($ch), true);
		curl_close($ch);

		unset($data['status']);

		$this->assign('data', $data);
		$this->assign('logurl', 'http://spiderman.loc:6800/logs/initialbot/');
		$this->assign('jsonurl', 'http://spiderman.loc:6800/items/initialbot/');
		$this->view('scrapydstatus.tpl');
	}
}