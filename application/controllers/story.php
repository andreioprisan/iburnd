<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Story extends CI_Controller {
	var $uid;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('workout', 'workout');
		$this->load->model('storym', 'story_m');
		
		$this->uid = $this->session ? $this->session->userdata('uid') : "0";	
		
	}
	
	public function json()
	{
		$a = $this->story_m->getall($this->uid);
		echo json_encode($a);
	}
	
	public function html()
	{
		$a = $this->story_m->getall($this->uid);
		
		$html = "";
		foreach($a as $row)
		{
			$d1 = explode(" ", $row['date']);
			$d2 = explode("-", $d1[0]);
			$d3 = $d2[1]."/".$d2[2]."/".substr($d2[0],2,2);
			
			if ($row['type'] == "n")
			{
				
				$html .= '<a href="/pub/nutrition/'.$this->base64url_encode($row['story_id']).'" style="text-decoration:none;"><div class="alert alert-info" style="display: block; height:19px;">
				<strong><div style="display:inline"><span class="label label-important" style="font-size: 16px;">Food</span> <span class="label" style="font-size: 16px;">'.$row['cal'].' Cal</span> <span class="label" style="font-size: 16px;">on '.$d3.'</span></div></strong>
				<div style="display: inline; font-weight: bold; font-size: 15px; padding-left: 1%; ">'.$row['n'].'</div>
			</div></a>';
			
			} else if ($row['type'] == "w")
			{
				$html .= '<a href="/pub/workout/'.$this->base64url_encode($row['story_id']).'" style="text-decoration:none;"><div class="alert alert-danger" style="display: block; height:19px">
				<strong><div style="display:inline"><span class="label label-info" style="font-size: 16px;">Workout</span> <span class="label" style="font-size: 16px;">'.$row['cal'].' Cal</span> <span class="label" style="font-size: 16px;">on '.$d3.'</span></div></strong>
				<div style="display: inline; font-weight: bold; font-size: 15px; padding-left: 1%; ">'.substr($row['n'],0,122).'</div>
			</div></a>';
			}
		}
		
		echo $html;
	}
	
	function base64url_encode($data) { 
	  return rtrim(strtr(base64_encode($data), '+/', '-_'), '='); 
	} 

	function base64url_decode($data) { 
	  return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT)); 
	}
	
}
