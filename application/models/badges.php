<?php

class Badges extends CI_Model {
	function __construct()
	{
		parent::__construct();

	}
	
	function insert_general($data)
	{
		$this->db->insert('g_badges', $data);
		return $this->db->insert_id();
	}
	
	
	function delete($uid, $bid)
	{
		return $this->db->delete('u_badges', array('b_id' => $bid, 'u_id' => $uid));
	}

	function save($data)
	{
		$table = "u_badges";
		$query_raw = "select count(*) as count from u_badges where b_id = '".$data['b_id']."' and u_id = '".$data['u_id']."'";
		
		$query = $this->db->query($query_raw);
		
		$r_r = get_object_vars($query->row());
		if ($r_r['count'] > 0)
		{
			$this->db->update('u_badges', $data, array("b_id" => $data['b_id'], "u_id" => $data['u_id']));
		} else {
			$this->db->insert('u_badges', $data);
		}
		
	}
	
	
	function getall($u_id)
	{
		$query_raw = "select * from u_badges left join g_badges on u_badges.b_id = g_badges.b_id where u_id = '".$u_id."' ";
		
		$query = $this->db->query($query_raw);
		
		if (count($query->result()))
			return $query->result();
		else 
			return null;
		
	}


	
	
	
}

