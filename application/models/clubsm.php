<?php

class Clubsm extends CI_Model {
	function __construct()
	{
		parent::__construct();
	}

	function delete($id)
	{
		$this->db->delete('clubs', array('id' => $id));
	}

	function update($id, $data)
	{
		$this->db->update('clubs', $data, "id = $id");
	}

	function add($data)
	{
		$this->db->insert('clubs', $data);
	}

	function getAll()
	{
		$table = "clubs";
		$query_raw = "select distinct * from $table";
		
 		$result = array();
		$query = $this->db->query($query_raw);
		
		if ($query)
			$result = $query->result();
		else
			$result = array();
		return $result;
	}
	
	function getclubname($id)
	{
		$query_raw = "select clubname from clubs where id = '".$id."'";
		$query = $this->db->query($query_raw);
		
		if ($query)
			$result = $query->row();
		else
			$result = array();
		return $result;
	}
}

