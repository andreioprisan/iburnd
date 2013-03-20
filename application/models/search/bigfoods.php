<?php

class Bigfoods extends CI_Model {
	function __construct()
	{
		parent::__construct();
	}

	function delete($id, $tableid = "0")
	{
		$this->db->delete('dm1_'.$tableid.'_foods', array('ID' => $id));
	}

	function update($id, $data, $tableid = "0")
	{
		$this->db->update('dm1_'.$tableid.'_foods', $data, "ID = '".$id."'");
	}

	function add($data, $tableid = "0")
	{
//		echo "adding data";
		$this->db->insert('dm1_'.$tableid.'_foods', $data);
	}

	function getfoodcats($tableid = "0")
	{
		$query_raw = "select name from dm".$tableid."_food_names_general";

		$result = array();
		$query = $this->db->query($query_raw);

		if ($query)
			$result = $query->result();
		else
			$result = array();
		return $result;
	}
	
	function getfoodbrands($tableid = "0")
	{
		$query_raw = "select brand from dm".$tableid."_food_brands";

		$result = array();
		$query = $this->db->query($query_raw);

		if ($query)
			$result = $query->result();
		else
			$result = array();
		return $result;
	}
	
	
	function get($id, $tableid = "0")
	{
		$query_raw = "select distinct * from dm1_".$tableid."_foods where ID='".$id."'";

		$result = array();
		$query = $this->db->query($query_raw);

		if ($query)
			$result = $query->result();
		else
			$result = array();
		return $result;
	}
	
	function getLast($tableid = "0")
	{
		$query_raw = "SELECT max(ID) as lid FROM dm1_".$tableid."_foods";

		$result = array();
		$query = $this->db->query($query_raw);

		if ($query)
			$result = $query->result();
		else
			$result = array();
			
		return $result[0]->lid;
	}
	
	
	
	function checkID($tableid = "0")
	{
		$query_raw = "select distinct ID from dm1_".$tableid."_foods where ID='".$id."'";

		$result = array();
		$query = $this->db->query($query_raw);

		if ($query) {
			$a = $query->result();
			if (count($a) == 0)	
			{
				$result = false;
			} else {
				$result = true;
			}
		}
		else
			$result = false;
		return $result;
	}
	
}

