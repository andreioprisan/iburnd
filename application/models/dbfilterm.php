<?php

class Dbfilterm extends CI_Model {
	function __construct()
	{
		parent::__construct();
		
	}
	
	function getuniquenames()
	{
		$limit = 1000;
		$query_raw1 = "SELECT distinct Name, ID FROM dm1_".$tableid."_foods LIMIT 0,".$limit;
		
		$query_phase1 = $this->db->query($query_raw1);
		if ($query_phase1)
			$result1 = $query_phase1->result();
		else
			$result1 = array();
			
		return $result1;
	}
	
	function getbld_crap()
	{
		/*
		
			Name LIKE '%*%' or Name like '%,,%' or Name like '%..%' or 
			Name like '&amp;%' or Name like '&quot;%' or 
			Name like '#%' or Name like '*%' or 
			Name like '.%' or Name like '+%' or 
			Name like '?%' or Name like '!%' or 
			Name like '(%' or Name like '-%' or 
			Name like \"'%\" or Name like '%?%' or
		
		*/
		// gets crap breakfast, lunch and dinner stuff
		$query_raw1 = "SELECT ID, Name from dm1_".$tableid."_foods WHERE 
			(
				
				
			
				Name like 'home -%' or Name like 'lunch' or Name like 'lunch%work%' or 
				
				Name like '%2005%' or Name like '%2006%' or Name like '%2007%' or 
				Name like '%2008%' or Name like '%2009%' or Name like '%2010%' or 
				Name like '%2011%' or Name like '%2012%' or 
				
				Name like '%/05%' or Name like '%/06%' or Name like '%/07%' or 
				Name like '%/08%' or Name like '%/09%' or Name like '%/10%' or 
				Name like '%/11%' or Name like '%/12%' or 
				
				Name like '1 %' or Name like '1' or Name like '1-%' or 
				Name like '2-%' or Name like '3-%' or Name like '4-%' or 
				Name like '5-%' or Name like '6-%' or Name like '7-%' or 
				Name like '8-%' or Name like '9-%' or Name like '0-%' or 
				
				Name like '1.%' or Name like '2.%' or Name like '3.%' or 
				Name like '4.%' or Name like '5.%' or Name like '6.%' or 
				Name like '7.%' or Name like '8.%' or Name like '9.%' or 
				
				Name like '0.%' or Name like '%1/%' or Name like '%2/%' or 
				Name like '%3/%' or Name like '%4/%' or Name like '%5/%' or 
				Name like '%6/%' or Name like '%7/%' or Name like '%8/%' or 
				Name like '%9/%' or Name like '%0/%' or
				
				Name like '1' or Name like '2' or Name like '3' or 
				Name like '4' or Name like '5' or Name like '6' or 
				Name like '7' or Name like '8' or Name like '9' or 
				Name like '0' or
				
				Name like '%0am%' or Name like '%1am%' or Name like '%2am%' or 
				Name like '%3am%' or Name like '%4am%' or Name like '%5am%' or 
				Name like '%6am%' or Name like '%7am%' or Name like '%8am%' or 
				Name like '%9am%' or 
				
				Name like '%0pm%' or Name like '%1pm%' or Name like '%2pm%' or 
				Name like '%3pm%' or Name like '%4pm%' or Name like '%5pm%' or 
				Name like '%6pm%' or Name like '%7pm%' or Name like '%8pm%' or 
				Name like '%9pm%' or

				Name like '%:am%' or
				Name like '%:pm%' or
				
				Name LIKE '%-%lunch' or 
				Name LIKE '%-%breakfast' or 
				Name LIKE '%-%dinner' or 
				
				Name LIKE '%lun.%' or
				Name LIKE '%din.%' or
				Name LIKE '%brk.%' or
				
				Name LIKE '% lun %' or
				Name LIKE '% din %' or
				Name LIKE '% brk %' or
				
				Name LIKE '% lun' or
				Name LIKE '% din' or
				Name LIKE '% brk' or
				
				Name LIKE '% bre%fast' or
				Name LIKE '% di%ner' or
				Name LIKE '% lu%ch' or
				
				Name LIKE '% day %' or
				Name LIKE '% month %' or
				Name LIKE '% year %' or
				
				Name LIKE '%-%-%-%-%'
				
			)  
			
			and UsersConfirm < '2' 
			
			and Name NOT LIKE '%biggest loser%' 
			and Name NOT LIKE '%chick%' 
			and Name NOT LIKE '%biggest loser%' 
			and Name NOT LIKE '%el charrito%'  
			
			";
		
		$query_phase1 = $this->db->query($query_raw1);
		if ($query_phase1)
			$result1 = $query_phase1->result();
		else
			$result1 = array();
			
		return $result1;
	}
	
	
	
	function delete($wid)
	{
		return $this->db->delete('u_workouts', array('w_id' => $w_id));
	}

	function update($tablename, $id, $data)
	{
		$this->db->where('ID', $id);
		return $this->db->update($tablename, $data);
	}

	function add($tablename = 'u_workouts', $data)
	{
//		echo "adding data";
//		var_dump($this->db);
		
		$this->db->insert($tablename, $data);
		return $this->db->insert_id();
		
	}
	
	
	function search($terms)
	{
		
		$prepositions_both = array(" a ", " abaft ", " aboard ", " about ", " above ", " absent ", " across ", " afore ", " after ", " against ", " along ", " alongside ", " amid ", " amidst ", " among ", " amongst ", " an  ", " apropos ", " around ", " as ", " aside ", " astride ", " at ", " athwart ", " atop ", " barring ", " before ", " behind ", " below ", " beneath ", " beside ", " besides ", " between ", " betwixt ", " beyond ", " but ", " by ", " circa ", " concerning ", " despite ", " down ", " during ", " except ", " excluding ", " failing ", " following ", " for ", " from ", " given ", " in ", " including ", " inside ", " into ", " lest ", " like ", " mid ", " midst ", " minus ", " modulo ", " near ", " next ", " notwithstanding ", " of ", " off ", " on ", " onto ", " opposite ", " out ", " outside ", " over ", " pace ", " past ", " per ", " plus ", " pro ", " qua ", " regarding ", " round ", " sans ", " save ", " since ", " than ", " through ", " thru ", " throughout ", " thruout ", " till ", " times ", " to ", " toward ", " towards ", " under ", " underneath ", " unlike ", " until ", " up ", " upon ", " versus ", " via ", " vice ", " with ", " w/ ", " within ", " w/in ", " w/i ", " without ", " w/o ", " worth ", " and ", " or ", " and/or ");
		
		$tablename = 'pactivities_tags';
		$tablename2 = 'pactivities';

		$search = preg_replace('/\s+/', '|', trim($terms));
		$search_terms = explode("|", $search);
		
		$combinatorics = new Combinatorics;
		
		$permutations = array();
		
		foreach($combinatorics->combinations($search_terms, 1) as $p) {
			array_push($permutations, join(' ', $p)); 
		}
		
		if (count($search_terms) >= 2)
			foreach($combinatorics->combinations($search_terms, 2) as $p) {
				array_push($permutations, join(' ', $p)); 
			}

		if (count($search_terms) >= 3)
			foreach($combinatorics->combinations($search_terms, 3) as $p) {
				array_push($permutations, join(' ', $p)); 
			}

		if (count($search_terms) >= 4)
			foreach($combinatorics->combinations($search_terms, count($search_terms)-1) as $p) {
				array_push($permutations, join(' ', $p)); 
			}
		
		$search_terms2 = array_unique($permutations);
		
		$prepositions_both = array(" a ", " abaft ", " aboard ", " about ", " above ", " absent ", " across ", " afore ", " after ", " against ", " along ", " alongside ", " amid ", " amidst ", " among ", " amongst ", " an  ", " apropos ", " around ", " as ", " aside ", " astride ", " at ", " athwart ", " atop ", " barring ", " before ", " behind ", " below ", " beneath ", " beside ", " besides ", " between ", " betwixt ", " beyond ", " but ", " by ", " circa ", " concerning ", " despite ", " down ", " during ", " except ", " excluding ", " failing ", " following ", " for ", " from ", " given ", " in ", " including ", " inside ", " into ", " lest ", " like ", " mid ", " midst ", " minus ", " modulo ", " near ", " next ", " notwithstanding ", " of ", " off ", " on ", " onto ", " opposite ", " out ", " outside ", " over ", " pace ", " past ", " per ", " plus ", " pro ", " qua ", " regarding ", " round ", " sans ", " save ", " since ", " than ", " through ", " thru ", " throughout ", " thruout ", " till ", " times ", " to ", " toward ", " towards ", " under ", " underneath ", " unlike ", " until ", " up ", " upon ", " versus ", " via ", " vice ", " with ", " w/ ", " within ", " w/in ", " w/i ", " without ", " w/o ", " worth ", " and ", " or ", " and/or ");
		
		$search_terms = array();
		
		foreach ($search_terms2 as $search_terms3)
		{
			if (is_numeric($search_terms3) ||
				$search_terms3 == "mph" ||
				$search_terms3 == "mile" ||
				$search_terms3 == "km"
				)
				continue;
			
			if (is_numeric(substr($search_terms3, 0, 1)))
			{
				array_push($search_terms, "at ".$search_terms3);
				array_push($search_terms, "a ".$search_terms3);
				array_push($search_terms, "of ".$search_terms3);
				array_push($search_terms, $search_terms3);
			}
				
			if (in_array(" ".$search_terms3." ", $prepositions_both))
				continue;
			
			array_push($search_terms, $search_terms3);
		}

		unset($permutations);
		
		$search_column = 'pa_tag';
		$search_column2 = 'pa_txt';
		$search_sql = "";
		$total_search_terms = count($search_terms);
		foreach ($search_terms as $term)
		{
			$total_search_terms--;
			
			if (is_numeric(substr($term, 0, 1)))
				$search_sql .= " $search_column LIKE '".$term."%' ";
			else
				$search_sql .= " $search_column LIKE '%".$term."%' ";
			
			if ($total_search_terms != 0)
			{
				$search_sql .= " OR ";
			}
		}
		
		$result = array();

		$query_raw1 = "SELECT pa_id, count(*) as c FROM ".$tablename." WHERE ".$search_sql." group by pa_id order by c, pa_id desc";
		$query_phase1 = $this->adb->query($query_raw1);
		if ($query_phase1)
			$result1 = $query_phase1->result();
		else
			$result1 = array();

		$search_phase2 = preg_replace('/\s+/', '%', trim($terms));
		$query_raw2 = "SELECT pa_id, count(*) as c  FROM ".$tablename2." WHERE ".$search_column2." LIKE '%".$search_phase2."%' group by pa_id order by c desc";
		$query_phase2 = $this->adb->query($query_raw2);
		if ($query_phase2)
			$result2 = $query_phase2->result();
		else
			$result2 = array();
		
		$results = array();
		
		if (count($result1) > 0)
			foreach ($result1 as $item)
			{
				if (!isset($results[$item->pa_id]))
					$results[$item->pa_id] = 0;
				$results[$item->pa_id] += $item->c;
			}
		
		if (count($result2) > 0)
			foreach ($result2 as $item)
			{
				if (!isset($results[$item->pa_id]))
					$results[$item->pa_id] = 0;
				$results[$item->pa_id] += $item->c;
			}
		
		arsort($results);
		
		$newr_sql = "";
		$total_search_terms = count($results);
		$newr_sorder = array();
		
		$max_results = 10;
		foreach ($results as $newr => $newc)
		{
			$max_results--;
			
			if ($max_results < -1)
				break;
				
			$query_raw3 = "SELECT pa_id, pa_txt, pa_mets  FROM ".$tablename2." WHERE pa_id='".$newr."'";

			$query_phase3 = $this->adb->query($query_raw3);
			if ($query_phase3->num_rows() > 0)
			{
				$result3 = $query_phase3->row();
				array_push($newr_sorder, array(	'pa_id' 	=>	$result3->pa_id,
												'pa_txt'	=>	$result3->pa_txt,
												'pa_mets'	=>	$result3->pa_mets
												));
				unset($result3);
			}
			else
				continue;

			unset($query_raw3);
			unset($query_phase3);
		}
		
		return $newr_sorder;
	}
	
}

