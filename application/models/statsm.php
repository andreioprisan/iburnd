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
	
	function get_nutrition_cals($date = null, $nid = null)
	{
		if ($date != null)
		{
			$date_sql = " u_nutrition.n_date = '".$date."' and ";
		} else {
			$date_sql = "";
		}
		
		if ($this->uid != null)
		{
			$uid_sql = " u_id = '".$this->uid."' and ";
		} else {
			$uid_sql = "";
		}
		
		if ($nid != null)
		{
			$nid_sql = "  nid = '".$nid."' and ";
		} else {
			$nid_sql = "";
		}
		
		$query_raw = "select u_nutrition.*, f_names.n, f_servings.s, f_servings.sid from u_nutrition 
		left join f_names on f_names.fid = u_nutrition.fid 
		left join f_servings on f_servings.sid = u_nutrition.sid
		where ".$date_sql." ".$uid_sql." ".$nid_sql." 1=1
		order by u_nutrition.n_datetime desc";
		
		$query = $this->db->query($query_raw);
		
		if ($query) {
			$result = $query->result();
		} else {
			$result = null;
		}
		
		return $result;
	}
	
	function get_workout($date = null, $w_id = null)
	{
		if ($date != null)
		{
			$date_sql = " u_workouts.pa_date = '".$date."' and ";
		} else {
			$date_sql = "";
		}
		
		if ($this->uid != null)
		{
			$uid_sql = " u_id = '".$this->uid."' and ";
		} else {
			$uid_sql = "";
		}
		
		if ($w_id != null)
		{
			$w_id_sql = "  w_id = '".$w_id."' and ";
		} else {
			$w_id_sql = "";
		}
		
		$query_raw = "select u_workouts.*, pactivities.pa_txt from u_workouts 
		left join pactivities on pactivities.pa_id = u_workouts.pa_id
		where ".$date_sql." ".$uid_sql." ".$w_id_sql." 1=1
		order by u_workouts.w_datetime desc";
		
		$query = $this->db->query($query_raw);
		
		if ($query) {
			$result = $query->result();
		} else {
			$result = null;
		}
		
		return $result;
	}
	
	function get_all_nutrients($fid)
	{
		$query_raw = "select f_nutrients.*, f_nutrients_definitions.Units, f_nutrients_definitions.NutrDesc from f_nutrients left join f_nutrients_definitions on f_nutrients_definitions.Nutr_No = f_nutrients.nid where f_nutrients.fid = '".$fid."'";
		
		$query = $this->db->query($query_raw);
		
		if ($query) {
			$result = $query->result();
		} else {
			$result = null;
		}
		
		return $result;
	}
	
	function get_nutrition_log_item($date, $fid)
	{
		$query_raw = "select u_nutrition.*, f_names.n, f_servings.s, f_servings.sid from u_nutrition 
		left join f_names on f_names.fid = u_nutrition.fid 
		left join f_servings on f_servings.sid = u_nutrition.sid
		where u_nutrition.n_date = '".$date."' and u_id = '".$this->uid."' and u_nutrition.fid='".$fid."'
		order by u_nutrition.n_datetime desc";
		
		$query = $this->db->query($query_raw);
		
		if ($query) {
			$result = $query->result();
		} else {
			$result = null;
		}
		
		return $result;
	}

	function get_nutrition_cals2($date)
	{
		$query_raw = "select *, f_names.* from u_nutrition left join f_names on f_names.fid = u_nutrition.fid where u_nutrition.n_date = '".$date."' and u_id = '".$this->uid."'";
		
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

