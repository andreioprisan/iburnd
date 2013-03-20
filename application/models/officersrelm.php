<?php

class Officersrelm extends CI_Model {
	function __construct()
	{
		parent::__construct();
	}

	function delete($id)
	{
		$this->db->delete('officers_clubs_rel', array('or_id' => $id));
	}

	function update($id, $data)
	{
		$this->db->update('officers_clubs_rel', $data, "or_id = '".$id."'");
		return $id;
	}

	function add($data)
	{
		$this->db->insert('officers_clubs_rel', $data);
		return $this->db->insert_id();
	}

	function get($id)
	{
		$query_raw = "select distinct * from officers_clubs_rel where id = '".$id."'";
		
		$result = array();
		$query = $this->db->query($query_raw);
		
		if ($query)
			$result = $query->result();
		else
			$result = array();
		return $result;
	}
	
}

