<?php

class Storym extends CI_Model {
	var $salt = "b7240edf5c9c8c4d4b3d6d94b1d18e82";
	
	function __construct()
	{
		parent::__construct();

	}
	
	function insert_general($data)
	{
		$this->db->insert('u_story', $data);
		return $this->db->insert_id();
	}
	
	
	function delete($uid, $bid)
	{
		return $this->db->delete('u_story', array('b_id' => $bid, 'u_id' => $uid));
	}

	function save($data)
	{
		if (!$data['story_id'] || $data['story_id'] == null)
		{
			$this->db->insert('u_story', $data);
		} else {
			$query_raw = "select count(*) as count from u_story where story_id = '".$data['story_id']."'";
			$query = $this->db->query($query_raw);
		
			$r_r = get_object_vars($query->row());
			if ($r_r['count'] > 0)
			{
				$this->db->update('u_story', $data, array("story_id" => $data['story_id']));
			} else {
				$this->db->insert('u_story', $data);
			}
		}
		
		return $this->db->insert_id();
	}
	
	function get($story_id)
	{
		$query_raw = "select * from u_story where story_id = '".$story_id."'";
		
		$query = $this->db->query($query_raw);
		
		$results = array();
		
		if (count($query->result()))
		{
			return $query->row();
		} else {
			return null;
		}
	}
	
	function getall($u_id)
	{
		$query_raw = "select * from u_story where u_id = '".$u_id."' order by lastedit desc limit 0,20";
		
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
				if ($row2 == NULL)
					continue;
					
				if ($row2)
					$text = $row2->n;
					
				$id_type = $row->foreign_id_link_type;
				$id = $row->foreign_id_val;
				$cal = $row->s_kcal;
				$date = $row->lastedit;
				
				$results[] = array(	'n'			=>	$text,
									'id'		=>	$id,
									'cal'		=>	$cal,
									'date'		=>	$date,
									'story_id'	=>	$row->story_id,
									'type'		=>	$type
				);
			}
		}
		
		return $results;
	}
	
	function encrypt($text) 
	{ 
		return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->salt, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)))); 
	} 

	function decrypt($text) 
	{ 
		return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->salt, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))); 
	}
	
	
	
	
}

