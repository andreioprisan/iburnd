<?php

class Tagifym extends CI_Model {
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

	function delete($id)
	{
		$this->db->delete('dm1_0_foods', array('ID' => $id));
	}

	function update($id, $data)
	{
		$this->db->update('dm1_0_foods', $data, "ID = '".$id."'");
	}

	function add($tablename, $data)
	{
//		echo "adding data";
		$this->adb->insert($tablename, $data);
	}

	function getall($tablename)
	{
		$query_raw = "SELECT pa_id, pa_txt FROM ".$tablename." ";

		$result = array();
		$query = $this->adb->query($query_raw);
		
		if ($query)
			$result = $query->result();
		else
			$result = array();
		return $result;
		
	}
	
}