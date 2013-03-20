<?php

class Advisors extends CI_Model {
	function __construct()
	{
		parent::__construct();
	}

	function update($id, $data)
	{
		$this->db->update('advisors', $data, "id = $id");
	}

	function getAll()
	{
		$table = "advisors";
		$query_raw = "select distinct * from $table";
		
		$query = $this->db->query($query_raw);
		
		if ($query)
			$result = $query->result();
		else
			$result = array();
		return $result;
	}
	
	function getAdvisors($query)
	{
		$table = "advisors";
		$parts = explode(" ", $query);
		$query_raw = "select distinct * from $table where ";
		
		$extra_query = "";
		$sql_part_count = count($parts);
		
		foreach ($parts as $sql_part)
		{
			
			$extra_query .= " 	$table.netid LIKE '%".htmlentities($sql_part)."%' 			or 
								$table.firstname LIKE '%".htmlentities($sql_part)."%'  		or 
								$table.middlename LIKE '%".htmlentities($sql_part)."%'  	or 
								$table.lastname LIKE '%".htmlentities($sql_part)."%'  		or 
								$table.prefix LIKE '%".htmlentities($sql_part)."%'  		or 
								$table.suffix LIKE '%".htmlentities($sql_part)."%'  		or 
								$table.email LIKE '%".htmlentities($sql_part)."%'  			or 
								$table.phone LIKE '%".htmlentities($sql_part)."%'  			or 
								$table.department LIKE '%".htmlentities($sql_part)."%'  	";
			
			$sql_part_count--;
			if ($sql_part_count != 0)
			{
				$extra_query .= " or ";
			}
		}
		
		$query_raw .= $extra_query;
		
 		$result = array();
		$query = $this->db->query($query_raw);
		
		if ($query)
			$result = $query->result();
		else
			$result = array();
		return $result;
	}

	function getAdvisorByID($id)
	{
		$table = "advisors";
		$query_raw = "select distinct * from $table where $table.id = '".$id."'";
		$query = $this->db->query($query_raw);
		
		if ($query)
			$result = $query->result();
		else
			$result = array();
		return $result;
	}
	
}

