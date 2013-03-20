<?php

class Nikeplus_m extends CI_Model {
	function __construct()
	{
		parent::__construct();
	}
	
	function save_nike_runs($data)
	{
		$table = "nike_runs";
		$query_raw = "select count(*) as count from $table where n_r_id = '".$data['n_r_id']."'";
		
		$query = $this->db->query($query_raw);
		
		$r_r = get_object_vars($query->row());
		if ($r_r['count'] > 0)
		{
			return;
			$this->db->update($table, $data, "n_r_id = '".$data['n_r_id']."'");
		} else {
			$this->db->insert($table, $data);
		}
		
	}
	
	function save_nike_runs_gps($data)
	{
		$table = "nike_runs_gps";
		$query_raw = "select count(*) as count from $table where n_r_id = '".$data['n_r_id']."'";
		
		$query = $this->db->query($query_raw);
		
		$r_r = get_object_vars($query->row());
		if ($r_r['count'] > 0)
		{
			return;
			$this->db->update($table, $data, "n_r_id = '".$data['n_r_id']."'");
		} else {
			$this->db->insert($table, $data);
		}
		
	}
	
	function save_nike_runs_snapshots($data)
	{
		$table = "nike_runs_snapshots";
		$query_raw = "select count(*) as count from $table where n_r_snapshot_id = '".$data['n_r_snapshot_id']."'";
		
		$query = $this->db->query($query_raw);
		
		$r_r = get_object_vars($query->row());
		if ($r_r['count'] > 0)
		{
			return;
			$this->db->update($table, $data, "n_r_snapshot_id = '".$data['n_r_snapshot_id']."'");
		} else {
			$this->db->insert($table, $data);
		}
		
	}
	
	function save_nike_u_profiles($data)
	{
		$table = "nike_u_profiles";
		$query_raw = "select count(*) as count from $table where n_u_id = '".$data['n_u_id']."'";
		
		$query = $this->db->query($query_raw);
		
		$r_r = get_object_vars($query->row());
		if ($r_r['count'] > 0)
		{
			$this->db->update($table, $data, "n_u_id = '".$data['n_u_id']."'");
		} else {
			$this->db->insert($table, $data);
		}
	}
	
	function save_nike_u_stats($data)
	{
		$table = "nike_u_stats";
		$query_raw = "select count(*) as count from $table where n_u_id = '".$data['n_u_id']."'";
		
		$query = $this->db->query($query_raw);
		
		$r_r = get_object_vars($query->row());
		if ($r_r['count'] > 0)
		{
			$this->db->update($table, $data, "n_u_id = '".$data['n_u_id']."'");
		} else {
			$this->db->insert($table, $data);
		}
	}
	
	function get_all_nike_credentials()
	{
		$table = "nike_u_profiles";
		$query_raw = "select username, password from $table";
		
		$query = $this->db->query($query_raw);
		return $query->result();
	}
	
	function get_thisuser_nikeplus_uid()
	{
		$table = "nike_u_profiles";
		$query_raw = "select n_u_id from $table where u_id = '".$this->session->userdata('uid')."'";
		
		$query = $this->db->query($query_raw);
		if ($query->result())
		{
			$res = get_object_vars($query->row());
			return $res['n_u_id'];
		} else {
			return "0";
		}
	}
	
}

