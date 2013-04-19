<?php

class Scraped extends Controller {
	public function index()
	{
		$sitemodel = $this->model('sites');
		$sites = $sitemodel->getAllByBotname();

		$dir = '/var/lib/scrapyd/items/initialbot/*/*';
		$files = array();
		foreach (glob($dir) as $file)
		{
			$info = pathinfo($file);
			$path = explode('/', $info['dirname']);
			$name = $path[count($path)-1];

			if (!isset($files[$name]))
			{
				$files[$name] = array();
			}

			$parsecmd  = '/var/www/spiderman/www/protected/yiic app Parser';
			$parsecmd .= ' --siteid='.isset($sites[$name]['id'])?$sites[$name]['id']:'';
			$parsecmd .= ' --botname='.$name;
			$parsecmd .= ' --parser='.isset($sites[$name]['parsername'])?$sites[$name]['parsername']:'';
			$parsecmd .= ' --jobid='.$info['filename'];

			$testparsecmd  = '/var/www/spiderman/www/protected/yiic app Testparser';
			$testparsecmd .= ' --infile='.$file;
			$testparsecmd .= ' --outfile=./data/'.$name.'.xml';

			$files[$name][] = array( 
				'date' => filemtime($file),
				'name' => $info['basename'],
				'url' => 'http://spiderman.loc:6800/items/initialbot/'.$name.'/'.$info['basename'],
				'path' => $file,
				'size' => round(filesize($file) / 1024, 1) .'KB',
				'parsecmd' => $parsecmd,
				'testparsecmd' => $testparsecmd
			);
		}

		foreach ($files as $key => $val)
		{
			usort($files[$key], function($a, $b) { return ($a['date'] > $b['date']); });
		}

		$this->assign('sites', $sites);
		$this->assign('files', $files);
		$this->view('scraped.tpl');
	}
}