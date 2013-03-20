<?php

class Usdadb extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	function add_perm($data)
	{
		$this->db->insert('nutrient_name_permutations', array('val' => $data));
	}

	function getall()
	{
		$query_raw = "select Long_Desc from food_descriptions";
		
		$result = array();
		$query = $this->db->query($query_raw);
		
		if ($query)
			$result = $query->result();
		else
			$result = array();
		return $result;
		
	}		
}

