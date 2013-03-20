<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Workouts extends CI_Controller {
	var $uid;
	var $appId = 'replaceme';
	var $secret = 'replaceme';
	
	public function __construct()
	{
		parent::__construct();
		//$this->load->library('template');
		
		$this->load->model('workout', 'workout');
		$this->load->model('storym', 'story_m');
		
		$this->uid = $this->session ? $this->session->userdata('uid') : "0";	
		
		$this->load->library('facebook', array(
		  'appId'  => $this->appId,
		  'secret' => $this->secret,
		  //'cookie' => 'true'
		));
		
	}
	
	public function index()
	{
		if (!$this->session->userdata('name'))
			header('Location: https://'.$_SERVER['HTTP_HOST'].'/homepage/');
		
	}

	public function nutrition()
	{
		
	}
	
	public function log()
	{
		
	}
	
	function facebookpost()
	{
		$enc_story_id = $_GET['story_id'];
		$a = $this->facebook->api("/me/iburndapp:finish", "post", array(
				"workout" => "https://www.iburnd.com/pub/workout/".$enc_story_id
			)
		);
		
		echo json_encode($a);
		return 1;
	}
	
	function base64url_encode($data) { 
	  return rtrim(strtr(base64_encode($data), '+/', '-_'), '='); 
	} 

	function base64url_decode($data) { 
	  return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT)); 
	}
	
	public function delete()
	{
		$data = array('result'	=>	'false');
		
		if (!isset($_GET['w_id']))
		{
			echo json_encode($data);
			return 0;
		}
		
		$w_id = $_GET['w_id'];
		
		$this->workout->delete($w_id, $this->uid);
		$data['result'] = true;
		
		echo json_encode($data);
		return 1;
	}
	
	
	public function save()
	{
		$data = array('result'	=>	'false', 'w_id' => 0);
		
		if (!isset($_GET['pa_id']) ||
			!isset($_GET['pa_mets']) ||
			!isset($_GET['pa_kcal']) ||
			!isset($_GET['pa_date']) ||
			!isset($_GET['pa_mins'])
			)
		{
			echo json_encode($data);
			return 0;
		}
		
		
		$pd_id = $_GET['pa_id'];
		$pa_mets = $_GET['pa_mets'];
		$pa_kcal = $_GET['pa_kcal'];
		$pa_date = $_GET['pa_date'];
		$pa_mins = $_GET['pa_mins'];
		
		$w_data = array(
					// submitted data
					'pa_id' 		=> $_GET['pa_id'],
					'pa_mets' 		=> $_GET['pa_mets'],
					'pa_kcal' 		=> $_GET['pa_kcal'],
					'pa_date' 		=> $_GET['pa_date'],
					'pa_mins' 		=> $_GET['pa_mins'],
					// timestamp
					'w_datetime'	=> date("Y-m-d H:i:s"),
					// author info - u_id
					'u_id'			=>	$this->uid
					);
		
		$w_id = "0";
		$action = "insert";
		
		if (isset($_GET['w_id']) && $_GET['w_id'] != "")
		{
			if (is_numeric($_GET['w_id']) && $_GET['w_id'] > 0)
			{
				$w_id = $_GET['w_id'];
				$action = "update";
			}
		}
		
		if ($action == "insert")
		{
			$w_id = $this->workout->add($w_data);
			$data['result'] = true;
		} else {
			$did = $this->workout->update($w_id, $w_data);
			$data['result'] = $did;
		}
		
		$data['w_id'] = $w_id;
		
		$story_id = $this->story_m->save(array(
			'story_id'						=>	null,
			'u_id'							=>	$this->uid,
			'type'							=>	'workout',
			'foreign_id_link_type'			=>	'w_id',
			'foreign_id_val'				=>	$w_id,
			's_kcal'						=>	$_GET['pa_kcal'],
			'fingerprint'					=>	md5($this->uid."|".'w_id'."|".$w_id)
		));
		
		$enc_story_id = $this->base64url_encode($story_id);
		
		if ($action == "insert")
		{
			$data['story'] = $enc_story_id;
		}	
		
		echo json_encode($data);
		
		return 1;
	}

	public function workout()
	{
		
		$data = array('result'	=>	'false', 'items' => NULL);
		
		if (!count($_GET) > 0)
		{
			echo json_encode($data);
			return 0;
		}

		if (!isset($_GET['q']))
		{
			echo json_encode($data);
			return 0;
		}
		
		$terms = $_GET['q'];
		
		$res = $this->workout->search($terms);
		
		$results = array();
		
		foreach ($res as $result)
		{
			array_push($results, array(	'id'	=>	$result['pa_id'],
										'mets'	=>	$result['pa_mets'],
										'text'	=>	$result['pa_txt'],
										));
		}
		
		if (count($results) > 0)
		{
			$data = array('result'	=>	'true', 'items' => $results);
		}
		
		echo json_encode($data);
		return 1;
	}
	
	public function story()
	{
		$a = $this->story_m->getall($this->uid);
		var_dump($a);
		
	}
}
