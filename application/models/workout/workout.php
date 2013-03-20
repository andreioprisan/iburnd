<?php

class Workout extends CI_Model {
	function __construct()
	{
		parent::__construct();
		$activities['username'] = 'replaceme';
		$activities['password'] = 'replaceme';
		$activities['hostname'] = 'localhost';
		$activities['database'] = 'replaceme';
		$activities['dbdriver'] = 'mysqli';
		$activities['dbprefix'] = '';
		$activities['pconnect'] = FALSE;
		$activities['db_debug'] = FALSE;
		$activities['cache_on'] = FALSE;
		$activities['cachedir'] = '';
		$activities['char_set'] = 'utf8';
		$activities['dbcollat'] = 'utf8_general_ci';
		
		$this->adb = $this->load->database($activities, TRUE);
	}
	
	function add_serving_f_rel($data)
	{
		$this->db->insert('f_servings_rel', $data);
	}
	
	function add_serving($data)
	{
		$this->db->insert('f_servings', $data);
		return $this->db->insert_id();
	}
	
	function delete($wid)
	{
		return $this->db->delete('u_workouts', array('w_id' => $w_id));
	}

	function update($wid, $data)
	{
		return $this->db->update('u_workouts', $data, "w_id = '".$wid."'");
	}

	function add($data)
	{
		$this->db->insert('u_workouts', $data);
		return $this->db->insert_id();
	}
	
	function search($searchword)
	{
		$totalcountsearch = 20;
		$cl = $this->sphinxsearch;
		$cl->SetLimits(0, $totalcountsearch);

//		$cl->SetSelect('*, (@weight)*99 + cl as score');
//		$cl->SetSortMode(SPH_SORT_EXTENDED, ' score DESC, cl DESC');

		$cl->SetMatchMode( SPH_MATCH_ALL );

		$result = $cl->Query($searchword, "pactivities");
		$total_found = (int)$result['total_found'];

		if ($result)
			if (isset($result['matches']))
				$total_showing = sizeof($result['matches']);
			else
				$total_showing = 0;
		
		$newr_sorder = array();
		
		foreach ($result['matches'] as $w)
		{
			array_push($newr_sorder, array(	'pa_id' 	=>	$w['id'],
											'pa_txt'	=>	$w['attrs']['pa_txt'],
											'pa_mets'	=>	$w['attrs']['pa_mets']
											));
		}
		
		return $newr_sorder;
	}
	
}

