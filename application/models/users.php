<?php

class Users extends CI_Model {

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	
	function add($data)
	{
		$this->db->insert('u_users', $data); 
		return $this->db->insert_id();
	}
	
	function update($fb_uid, $data)
	{
		$this->db->update('u_users', $data, array('fb_uid' => $fb_uid));
		$a = $this->get_by_fbuid($fb_uid);
		return($a[0]->id);
	}
	
	function get_by_id($id)
	{
		$query = $this->db->get_where('u_users', array('id' => $id));
		if (!$query)
			return false;
		else 
			return $query->result();
	}

	function get_by_fbuid($id)
	{
		$query = $this->db->get_where('u_users', array('fb_uid' => $id));
		if (!$query)
			return false;
		else 
			return $query->result();
	}

	function get_name($id)
	{
		$query = $this->db->query('SELECT concat(users.first_name, " ", users.last_name) as fullname FROM u_users WHERE users.id = ?', array('id' => $id));
		return $query->row()->fullname;
	}


}
