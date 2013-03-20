<?php

class Nutrition extends CI_Model {
	function __construct()
	{
		parent::__construct();
		
		$this->tablename = "f_brands_rel";
		
	}
	
	function delete($nid, $uid)
	{
		return $this->db->delete('u_nutrition', array('nid' => $nid, 'u_id' => $uid));
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
	
	function get_f_servings($fid)
	{
		if ($fid >= "6096091" && $fid <= "6103997")
		{
			$query_raw = "select sid, Gm_Wgt as w, concat(Amount, ' ', Msre_Desc) as s from f_usda_weights where f_usda_weights.fid = '".$fid."' order by fid";
		} else {
			$query_raw = "select f_servings_rel.sid, f_servings.s, NULL as w from f_servings_rel 
			left join f_servings on  f_servings.sid = f_servings_rel.sid 
			where f_servings_rel.fid = '".$fid."'";
		}
		
		$query = $this->db->query($query_raw);
		
		if ($query) {
			$result = $query->result();
			$list = array();
			
			foreach ($result as $r)
			{
				if ($r->sid != NULL) 
				{
					if (!isset($r->w) || $r->w == NULL)
						array_push($list, array('sid' => (int)$r->sid, 'sname' => $r->s));
					else 
						array_push($list, array('sid' => (int)$r->sid, 'sname' => $r->s, 'sw' => (double)$r->w));
				}
			}
			
			return $list;
		} else {
			return null;
		}
	}
	
	function get_f_brands($fid)
	{
		$query_raw = "select f_brands_rel.bid, f_brands.bname from f_brands_rel 
		left join f_brands on f_brands.bid = f_brands_rel.bid 
		where fid = '".$fid."'";
		
		$query = $this->db->query($query_raw);
		
		if ($query) {
			$result = $query->result();
		} else {
			$result = null;
		}
		
		return $result;
	}
	
	function get_f_brand_names($bids)
	{
		$query_raw = "select bname from f_brands where bid in (".implode($bids, ",").") order by bid desc";
		
		$query = $this->db->query($query_raw);
		
		if ($query) {
			$result = $query->result();
		} else {
			$result = null;
		}
		
		return $result;
	}
	
	function get_f_nutrients_cal($fid)
	{
		$query_raw = "select f_nutrients.nvalue as val, f_nutrients.nid as id from f_nutrients where f_nutrients.fid = '".$fid."' and f_nutrients.nid = '208'";
		
		$query = $this->db->query($query_raw);
		
		if ($query)
			$result = $query->result();
		else
			$result = null;
		return $result;
	}
	
	
	function get_f_nutrients($fid)
	{
		$query_raw = "select f_nutrients.nvalue as val, f_nutrients.nid as id from f_nutrients where f_nutrients.fid = '".$fid."'";
		
		$query = $this->db->query($query_raw);
		
		if ($query)
			$result = $query->result();
		else
			$result = null;
		return $result;
	}
	
	function get_f_name($fid)
	{
		$query_raw = "select f_names.* from f_names where fid = '".$fid."'";
		
		$query = $this->db->query($query_raw);
		
		if ($query)
			$result = $query->result();
		else
			$result = null;
		return $result;
	}
	
	function get_f_brands_ids($fid)
	{
		$query_raw = "select bid from f_brands_rel where fid = '".$fid."'";
		
		$query = $this->db->query($query_raw);
		
		if ($query)
			$result = $query->result();
		else
			$result = null;
		return $result;
	}
	
	function add_r($data)
	{
		return $this->db->insert('f_brands_rel', $data);
	}
	
	function add_n($data)
	{
		return $this->db->insert('n_names', $data);
	}
	
	function add_nu($data)
	{
		return $this->db->insert('f_nutrients', $data);
	}
	
	
	function add_food_brand_rel($food_id, $brand_id)
	{
		return $this->db->insert('dm0_food_brands_rel', 
								array(	
									'food_id' 		=> $food_id, 
									'brand_id' 		=> $brand_id, 
									'md5' 			=> md5("|".$food_id."|".$brand_id."|")
									)
								);
	}
	
	function getbrands()
	{
		$query_raw = "select *, length(brand) as len from dm0_food_brands order by len desc";
		
		$query = $this->db->query($query_raw);
		
		if ($query)
			$result = $query->result();
		else
			$result = null;
		return $result;
	}

	function getbrandsidrepl($id)
	{
		$query_raw = "select dm0_foods.fid, dm0_food_brands.id from dm0_food_brands LEFT JOIN dm0_foods ON dm0_food_brands.brand = dm0_foods.Brand where dm0_food_brands.id = '".$id."'";
		
		$query = $this->db->query($query_raw);
		
		if ($query)
			$result = $query->result();
		else
			$result = null;
		return $result;
	}
	
	function updatebrandrel1($fid, $data)
	{
		$this->db->where('fid', $fid);
		return $this->db->update("dm0_foods", $data);
	}

	function getCompletedFoodBrands()
	{
		$query_raw = "select distinct brand_id from dm0_food_brands_rel order by brand_id desc;";
		
		$query = $this->db->query($query_raw);
		
		if ($query)
			$result = $query->result();
		else
			$result = null;
		return $result;
	}

	function getBrandByID($ID)
	{
		$query_raw = "select * from dm0_food_brands where id = '".$ID."'";
		
		$query = $this->db->query($query_raw);
		
		if ($query)
			$result = $query->result();
		else
			$result = null;
		return $result;
	}
	

	function getByID($id)
	{
		$query_raw = "select distinct * from ".$this->tablename." where n_id = '".$id."'";
		$query = $this->db->query($query_raw);
		
		if ($query)
			$result = $query->result();
		else
			$result = null;
		return $result;
	}
	
	function getBT_ID($id)
	{
		$query_raw = "select distinct * from ".$this->foodstable." where ID = '".$id."'";
		$query = $this->db->query($query_raw);
		
		if ($query)
			$result = $query->result();
		else
			$result = null;
		return $result;
	}
	
	function md5content($startid, $count)
	{
		$low_limit =	$startid;
		$high_limit =	$startid+$count;
		
		$query_raw = "select md5(concat(Servings, Calories, Sodium, TotalFat, Potassium, Saturated, TotalCarbs, Polyunsaturated, DietaryFiber, Monounsaturated, Sugars, Trans, Protein, Cholesterol, VitaminA, Calcium, VitaminC, Iron)) as md5, ID, UsersConfirm from ".$this->foodstable." where ID >= '".$low_limit."' AND ID < '".$high_limit."' order by md5";
		
		$query = $this->db->query($query_raw);
		
		if ($query)
			$result = $query->result();
		else
			$result = null;
		return $result;
	}	
	
	function getfoodbyid($fid)
	{
		$query_raw = "select * from u_story where u_id = '".$fid."' order by lastedit desc limit 0,20";
		
		$query = $this->db->query($query_raw);
		
		$results = array();
		
		if (count($query->result()))
		{
			foreach ($query->result() as $row)
			{
				if ($row->foreign_id_link_type == "nid")
				{
					$q2 = "select u_nutrition.fid, f_names.n from u_story left join u_nutrition on u_nutrition.nid = u_story.foreign_id_val left join f_names on f_names.fid = u_nutrition.fid where u_story.u_id='".$u_id."' and u_nutrition.nid='".$row->foreign_id_val."'  order by lastedit desc";
					$type = "n";
				} else if ($row->foreign_id_link_type == "w_id") {
					$q2 = "select u_workouts.w_id, pactivities.pa_txt as n from u_story left join u_workouts on u_workouts.w_id = u_story.foreign_id_val left join pactivities on pactivities.pa_id = u_workouts.pa_id where u_story.u_id='".$u_id."'  and u_workouts.w_id='".$row->foreign_id_val."' order by lastedit desc";
					$type = "w";
				} else {
					continue;
				}
				
				$q2p = $this->db->query($q2);
				$row2 = $q2p->row(); 
				$text = $row2->n;
				
				$id_type = $row->foreign_id_link_type;
				$id = $row->foreign_id_val;
				$cal = $row->s_kcal;
				$date = $row->lastedit;
				
				$results[] = array(	'n'			=>	$text,
									'id'		=>	$id,
									'cal'		=>	$cal,
									'date'		=>	$date,
									'type'		=>	$type
				);
			}
		}
		
		return $results;
	}
	
	
}

