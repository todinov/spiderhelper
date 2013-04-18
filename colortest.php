<?php

class ColorTest{
	public function __construct() {
		try {
			$this->db = new PDO('mysql:host=localhost;dbname=spiderman', 'spiderman', '123456');
		}
		catch (Exception $e) {
			echo $e->getMessage();
		}
	}

	public function load_colors() {
		$con = $this->db;

		$cmd = $con->prepare('SELECT m.id as id, name, parentid, neutral FROM mappingcat AS m LEFT JOIN mappingcatcolours AS c ON m.id=c.catid WHERE groupid=2');
		$cmd->execute();
		$res = $cmd->fetchAll();

		$this->colorname = array();

		$this->neutral = array();
		$this->parents = array();
		$this->masterc = array();
		$this->synonym = array();

		foreach ($res as $color) {
			$this->colorname[$color['id']] = $color['name'];

			if (!$color['parentid']) {

				if ($color['neutral']) $this->neutral[$color['id']] = true;

				switch ($color['name']) {
					case 'No Colour':
						$this->nocolour = $color['id'];
						break;
					case 'Multi':
						$this->multi = $color['id'];
						break;
				}

				$this->masterc[strtolower($color['name'])] = $color['id'];
			}
			else {
				$this->parents[$color['id']] = $color['parentid'];
				if ( $this->is_neutral($color['parentid']) ) {
					$this->neutral[$color['id']] = true;
				}
				$this->synonym[strtolower($color['name'])] = $color['id'];
			}
		}
	}

	public function run_colour_mapping(){
		$con = $this->db;

		$this->load_colors();

		$cmd = $con->prepare('SELECT id, fromcat FROM newcolortest');
		$cmd->execute();
		$res = $cmd->fetchAll();

		$con->beginTransaction();

		$update = $con->prepare('UPDATE newcolortest SET cwm=:cwm, wwm=:wwm WHERE id=:id');

		foreach($res as $key => $value){
			$update->bindParam(':id', $value['id'], PDO::PARAM_INT);
			$update->bindParam(':cwm', $this->colorname[$this->map_colour_wwm($value['fromcat'])], PDO::PARAM_STR);
			$update->bindParam(':wwm', $this->colorname[$this->map_colour_wwm($value['fromcat'])], PDO::PARAM_STR);
			$update->execute();
		}

		$con->commit();
	}

	public function is_neutral($id) {
		return isset($this->neutral[$id]);
	}

	// return the one not neutral color in the array 
	// or false if there are more or none
	public function one_not_neutral($colors) {
		$id = -1;
		foreach ($colors as $c) {
			if ( !$this->is_neutral($c) ) {
				if ($id != -1) return false;
				$id = $c;
			}
		}
		if ($id != -1) {
			return $id;
		}
		else {
			return false;
		}
	}

	// take only synonyms for which we haven't taken the master color
	public function filter_synonym($master, $synonym) {
		if (is_array($synonym)) {
			if (is_array($master)) {
				$ret = array();
				foreach ($synonym as $s) {
					if (!in_array($this->parents[$s], $master)) $ret[] = $s;
				}
				return $ret;
			}
			else {
				return $synonym;
			}
		}
		else {
			return false;
		}
	}

	public function find_master_cwm ($color) {
		$ret = array();
		foreach ($this->masterc as $c => $id) {
			if (stripos($color, $c) !== false) {
				$ret[] = $id;
			}
		}
		if (empty($ret)) return false;
		return $ret;
	}

	public function find_synonym_cwm ($color) {
		$ret = array();
		foreach ($this->synonym as $c => $id) {
			if (stripos($color, $c) !== false) {
				$ret[$this->parents[$id]] = $id;
			}
		}
		if (empty($ret)) return false;
		return $ret;
	}

	// return the id of the colour, that we want to map to
	public function map_colour_cwm($rc){

		$foundmaster = $this->find_master_cwm($rc);
		$foundsynonym =$this->filter_synonym( $foundmaster, $this->find_synonym_cwm($rc) );

		// if ff master colour is in the retailer colour
		if ($foundmaster) {
			//if there is only one ff master in the retailer colour
			if (count($foundmaster) == 1) {
				$foundmaster = array_pop($foundmaster);
				//retailer colour has 0 synonyms

				if (!$foundsynonym) {
					//map to ff master
					return $foundmaster;
				}
				// if the master colour is neutral
				if ($this->is_neutral($foundmaster)) {

					//retailer colour has 1 synonym
					if (count($foundsynonym) == 1) {
						$foundsynonym = array_pop($foundsynonym);

						// synonym is neutral
						if ( $this->is_neutral($foundsynonym) ) {
							//two neutrals found = Multi
							return $this->multi;
						}
						// synonym is not neutral
						else {
							// synonym takes precedent as master colour was neutral
							// this might be wrong, as synonyms of neutral colors are also neutral
							return $this->parents[$foundsynonym];
						}
					}
					// if there are multiple synonyms
					else if (count($foundsynonym) > 1) { 
						// if all are synonyms, less neutral synonyms leaves one colour
						if ($t = $this->one_not_neutral($foundsynonym)) {
							return $this->parents[$t];
						}
						// otherwise there are multiple coloured synonyms or all synonyms are neutral
						else {
							return $this->multi;
						}
					}
				}
				// if master colour is not neutral
				else {
					// retailer has one $foundsynonymonym
					if (count($foundsynonym) == 1) {
						$foundsynonym = array_pop($foundsynonym);
						// synonym is neutral
						if ($this->is_neutral($foundsynonym)) {
							// map to ff master as it is not neutral
							return $foundmaster;
						}
						else {
							// two not neutral colours found = Multi
							return $this->multi;
						}
					}
					else if (count($foundsynonym) > 1) {
						return $this->multi;
					}
				}
			}
			// trere are multiple master colours in retailer colour
			else {
				if ($t = $this->one_not_neutral($foundmaster)) {
					return $t;
				}
				//otherwise there are multiple coloured synonyms or all synonyms are neutral
				else {
					return $this->multi;
				}
			}
		}
		//retailer colour contains synonyms but not ff master colours
		else if ($foundsynonym) {
			//only one synonym in retailer colour
			if (count($foundsynonym) == 1) {
				$foundsynonym = array_pop($foundsynonym);
				// map to the master colour for that $foundsynonymonym
				return $this->parents[$foundsynonym];
			}
			// more than one $foundsynonymonym in retailer colour
			else if (count($foundsynonym) > 1) {
				//if all synonyms less neutral synonyms leaves one colour
				if ($t = $this->one_not_neutral($foundsynonym)) {
					return $this->parents[$t];
				}
				//otherwise there are multiple coloured synonyms or all synonyms are neutral
				else {
					return $this->multi;
				}
			}
		}
		return $this->nocolour;
	}

	// find the master colors in the retailer color
	public function find_master_wwm($color) {
		return array_intersect_key($this->masterc, $color);
	}

	// take one synonym per master color from the retailer color
	public function find_synonym_wwm($color) {
		$found = array_intersect_key($this->synonym, $color);
		$ret = array();
		foreach ($found as $key => $value) {
			$ret[$this->parents[$value]] = $value;
		}
		return $ret;
	}

	// return the id of the colour, that we want to map to
	public function map_colour_wwm($rc){

		// split the string to clean words
		$words = preg_split('~[^\p{L}\']+~u', $rc);
		$words = array_map('strtolower', $words);
		$words = array_filter($words); // remove empty values
		$words = array_flip($words); // in order to intersect by key

		$foundmaster = $this->find_master_wwm($words);
		$foundsynonym =$this->filter_synonym( $foundmaster, $this->find_synonym_wwm($words) );

		// if ff master colour is in the retailer colour
		if ($foundmaster) {
			//if there is only one ff master in the retailer colour
			if (count($foundmaster) == 1) {
				$foundmaster = array_pop($foundmaster);
				//retailer colour has 0 synonyms

				if (!$foundsynonym) {
					//map to ff master
					return $foundmaster;
				}
				// if the master colour is neutral
				if ($this->is_neutral($foundmaster)) {

					//retailer colour has 1 synonym
					if (count($foundsynonym) == 1) {
						$foundsynonym = array_pop($foundsynonym);

						// synonym is neutral
						if ( $this->is_neutral($foundsynonym) ) {
							//two neutrals found = Multi
							return $this->multi;
						}
						// synonym is not neutral
						else {
							// synonym takes precedent as master colour was neutral
							// this might be wrong, as synonyms of neutral colors are also neutral
							return $this->parents[$foundsynonym];
						}
					}
					// if there are multiple synonyms
					else if (count($foundsynonym) > 1) { 
						// if all are synonyms, less neutral synonyms leaves one colour
						if ($t = $this->one_not_neutral($foundsynonym)) {
							return $this->parents[$t];
						}
						// otherwise there are multiple coloured synonyms or all synonyms are neutral
						else {
							return $this->multi;
						}
					}
				}
				// if master colour is not neutral
				else {
					// retailer has one $foundsynonymonym
					if (count($foundsynonym) == 1) {
						$foundsynonym = array_pop($foundsynonym);
						// synonym is neutral
						if ($this->is_neutral($foundsynonym)) {
							// map to ff master as it is not neutral
							return $foundmaster;
						}
						else {
							// two not neutral colours found = Multi
							return $this->multi;
						}
					}
					else if (count($foundsynonym) > 1) {
						return $this->multi;
					}
				}
			}
			// trere are multiple master colours in retailer colour
			else {
				if ($t = $this->one_not_neutral($foundmaster)) {
					return $t;
				}
				//otherwise there are multiple coloured synonyms or all synonyms are neutral
				else {
					return $this->multi;
				}
			}
		}
		//retailer colour contains synonyms but not ff master colours
		else if ($foundsynonym) {
			//only one synonym in retailer colour
			if (count($foundsynonym) == 1) {
				$foundsynonym = array_pop($foundsynonym);
				// map to the master colour for that $foundsynonymonym
				return $this->parents[$foundsynonym];
			}
			// more than one $foundsynonymonym in retailer colour
			else if (count($foundsynonym) > 1) {
				//if all synonyms less neutral synonyms leaves one colour
				if ($t = $this->one_not_neutral($foundsynonym)) {
					return $this->parents[$t];
				}
				//otherwise there are multiple coloured synonyms or all synonyms are neutral
				else {
					return $this->multi;
				}
			}
		}
		return $this->nocolour;
	}
}