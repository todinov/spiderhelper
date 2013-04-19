<?php

class Sites extends Model {
	public function getAllByBotname()
	{
		$sites = array();
		foreach ($this->db->query('SELECT * FROM site') as $site)
		{
			$sites[$site['botname']] = $site;
		}
		return $sites;
	}
}