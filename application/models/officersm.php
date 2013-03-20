<?php

class Officersm extends CI_Model {
	function __construct()
	{
		parent::__construct();
	}

	function delete($id)
	{
		$this->db->delete('officers', array('netid' => $id));
	}

	function update($id, $data)
	{
		$this->db->update('officers', $data, "netid = '".$id."'");
	}

	function add($data)
	{
		$this->db->insert('officers', $data);
	}

	function get($query)
	{
		$table = "officers";
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
								$table.email LIKE '%".htmlentities($sql_part)."%'  			";

			$sql_part_count--;
			if ($sql_part_count != 0)
			{
				$extra_query .= " or ";
			}
		}

		$query_raw .= $extra_query;

		$result = array();
		$query = $this->db->query($query_raw);
//		echo $query_raw;
		if ($query)
			$result = $query->result();
		else
			$result = array();
		return $result;
	}
	
	function getCurrent($clubid, $clubname, $netid, $advisor_id)
	{
		if (trim($clubname) == "")
		{
			$clubname_sql = " ";
		} else {
			$clubname_sql = "or clubs.clubname LIKE '%".$clubname."%'";
		}
		
		$query_raw = "	select distinct * from officers_clubs_rel 
							join officers 		on officers_clubs_rel.`officer_id` = officers.id  
							left join clubs 	on clubs.id = officers_clubs_rel.club_id 
						where 
							(concat(officers_clubs_rel.ay,'-08-01') < now() and concat(officers_clubs_rel.ay+1,'-06-01') > now()) 	
							AND
							officers_clubs_rel.club_id = '".$clubid."' or 
							officers.netid = '".$netid."' ".$clubname_sql. " or
							clubs.advisor_id = '".$advisor_id."'
						order by clubs.clubname, officers_clubs_rel.ay";
		//echo $query_raw;
		
 		$result = array();
		$query = $this->db->query($query_raw);
		
		if ($query)
			$result = $query->result();
		else
			$result = array();
		return $result;
	}
	
	function getPast($clubid, $clubname, $netid, $advisor_id)
	{
		if (trim($clubname) == "")
		{
			$clubname_sql = " ";
		} else {
			$clubname_sql = "or clubs.clubname LIKE '%".$clubname."%'";
		}
		
		$query_raw = "	select distinct * from officers_clubs_rel 
							join officers 		on officers_clubs_rel.`officer_id` = officers.id  
							left join clubs 	on clubs.id = officers_clubs_rel.club_id 
						where 
							!(concat(officers_clubs_rel.ay,'-08-01') < now() and concat(officers_clubs_rel.ay+1,'-06-01') > now()) 	
							AND
							officers_clubs_rel.club_id = '".$clubid."' or 
							officers.netid = '".$netid."' ".$clubname_sql. " or
							clubs.advisor_id = '".$advisor_id."'
						order by clubs.clubname, officers_clubs_rel.ay";
		//echo $query_raw;
		
 		$result = array();
		$query = $this->db->query($query_raw);
		
		if ($query)
			$result = $query->result();
		else
			$result = array();
		return $result;
	}
	
}

