<?php

class Statsm extends CI_Model {
	function __construct()
	{
		parent::__construct();
		
		$this->uid = $this->session ? $this->session->userdata('uid') : "0";
	}
	
	function delete($nid)
	{
		return $this->db->delete('u_nutrition', array('nid' => $nid));
	}

	function update($nid, $data)
	{
		return $this->db->update('u_nutrition', $data, "nid = '".$nid."'");
	}

	function add($data)
	{
		$this->db->insert('u_nutrition', $data);
		return $this->db->insert_id();
	}
	
	function get_overall_cals($date)
	{
		$query_raw = "select * from u_nutrition, u_workouts where u_nutrition.n_date = '".$date."' and u_workouts.pa_date = '".$date."' and u_id = '".$uid."'";
		
		$query = $this->db->query($query_raw);
		
		if ($query) {
			$result = $query->result();
		} else {
			$result = null;
		}
		
		return $result;
	}
	
	function get_nutrition_cals($date)
	{
		$query_raw = "select * from u_nutrition where u_nutrition.n_date = '".$date."'  and u_id = '".$this->uid."'";
		
		$query = $this->db->query($query_raw);
		
		if ($query) {
			$result = $query->result();
		} else {
			$result = null;
		}
		
		return $result;
	}
	
	function get_workout_cals($date)
	{
		$query_raw = "select * from u_workouts where u_workouts.pa_date = '".$date."'  and u_id = '".$this->uid."'";
		
		$query = $this->db->query($query_raw);
		
		if ($query) {
			$result = $query->result();
		} else {
			$result = null;
		}
		
		return $result;
	}
	
}

