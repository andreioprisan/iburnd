<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Nutritions extends CI_Controller {
	var $menu;
	var $uid;
	var $appId = 'replaceme';
	var $secret = 'replaceme';
	
	public function __construct()
	{
		parent::__construct();
		//$this->load->library('template');
		
		$this->load->library('facebook', array(
		  'appId'  => $this->appId,
		  'secret' => $this->secret,
		  //'cookie' => 'true'
		));
		
		if (!$this->session->userdata('name'))
			header('Location: https://'.$_SERVER['HTTP_HOST'].'/homepage/');
		
		$this->load->model('nutrition', 'nutrition_m');
		$this->load->model('statsm', 'stats_m');
		$this->load->model('storym', 'story_m');
		
		$this->menu = array(	
									array('name' => "Nutrition", 'val' => array(	
										array('name' => "Log", 'val' => "/nutritions/log"),
									), 'align' => 'right'),
									array('name' => "Workout", 'val' => array(	
										array('name' => "Log", 'val' => "/workouts/log"),
									), 'align' => 'right'),
									array('name' => "Nike+", 'val' => array(	
										array('name' => "Sync", 'val' => "/nike/sync"),
										array('name' => "Runs", 'val' => "/nike/runs")
									), 'align' => 'right'),
									'calorietracker' => true,
									array(	'username'	=>	$this->session ? $this->session->userdata('name') : NULL,
											'align'		=>	'right')
								);
								

		$this->uid = $this->session ? $this->session->userdata('uid') : "0";	
	}
	
	public function index()
	{
		
	}

	public function nutrition()
	{
		
	}
	
	public function search()
	{
		$this->template->write('title', 'iburnd - nutrition search');

		$payload['menu'] = $this->menu;
		$payload['css'] = array(	"prettify", 
									"bootstrap2.0-jqueryui/style",
									"bootstrap2.0/bootstrap.min",
									"bootstrap2.0/bootstrap-responsive.min",
									"iburnd",
									);
		$payload['js'] = array(	"jquery-1.7.min", "jquery.tablesorter", "prettify",
								"bootstrap2.0-jqueryui/jquery-ui-1.8.16.custom.min",
								"bootstrap2.0-jqueryui/start",
								"bootstrap2.0/bootstrap.min",
								"application",
								);
		
		$this->template->write_view('content_main', 'layouts/default', $payload);
		$this->template->write_view('start', 'layouts/start', $payload);
		$this->template->write_view('alertitemadd', 'layouts/alertitemadd', $payload);
		$this->template->write_view('nutritionsearch', 'layouts/nutritionsearch', $payload);
		
		return $this->template->render();

	}
	
	public function log()
	{
		$this->template->write('title', 'iburnd - nutrition log');
		
		$payload['menu'] = $this->menu;
		$payload['css'] = array(	"prettify", 
									"font-awesome",
									"bootstrap2.0-jqueryui/style",
									"bootstrap2.0/bootstrap.min",
									"bootstrap2.0/bootstrap-responsive.min",
									"iburnd",
									);
		$payload['js'] = array(	"jquery-1.7.min", "jquery.tablesorter", "prettify",
								"bootstrap2.0-jqueryui/jquery-ui-1.8.16.custom.min",
								"bootstrap2.0-jqueryui/start",
								"bootstrap2.0/bootstrap.min",
								"application",
								);
		
		//$payload['useraccess'] = $this->useraccess->get($netid);
		//$payload['start_forms'] = $this->useraccess->getStartFiles();

		if (isset($_GET['date']))
			$date = $_GET['date'];
		else
			$date = date('m/d/y');
		
		$payload['date'] = $date;	
		$payload['foods'] = $this->stats_m->get_nutrition_cals($date);

		$payload['stats_m'] = $this->stats_m;
		//get_all_nutrients
		$this->template->write_view('content_main', 'layouts/default', $payload);
//		$this->template->write_view('start', 'layouts/start', $payload);
		$this->template->write_view('nutritionlog', 'layouts/nutritionlog', $payload);
		$this->template->write_view('alertitemadd', 'layouts/alertitemadd', $payload);
//		$this->template->write_view('nutritionalinfo', 'layouts/nutritionalinfo', $payload);
		
		return $this->template->render();
		
		
	}
	
	public function delete()
	{
		$data = array('result'	=>	'false');
		
		if (!isset($_GET['nid']))
		{
			echo json_encode($data);
			return 0;
		}
		
		$nid = $_GET['nid'];
		
		$this->nutrition_m->delete($nid, $this->uid);
		$data['result'] = true;
		
		echo json_encode($data);
		return 1;
	}
	
	public function save()
	{
		$data = array('result'	=>	'false', 'nid' => 0);
		
		if (!isset($_GET['fid']) ||
			!isset($_GET['n_kcal'])
			)
		{
			echo json_encode($data);
			return 0;
		}
		
		
		$fid = $_GET['fid'];
		$n_kcal = $_GET['n_kcal'];

		if (isset($_GET['sid']))
			$sid = $_GET['sid'];
		else
			$sid = "42";

		if (isset($_GET['s_s']))
			$s_s = $_GET['s_s'];
		else
			$s_s = "1";

		$n_data = array(
					// submitted data
					'fid' 		=> $_GET['fid'],
					'sid' 		=> $_GET['sid'],
					'n_kcal' 	=> $_GET['n_kcal'],
					's_s' 		=> $_GET['s_s'],
					'n_date' 	=> $_GET['n_date'],
					// timestamp
					'n_datetime'	=> date("Y-m-d H:i:s"),
					// author info - u_id
					'u_id'			=>	$this->uid
					);
		
		$nid = "0";
		$action = "insert";
		
		if (isset($_GET['nid']) && $_GET['nid'] != "")
		{
			if (is_numeric($_GET['nid']) && $_GET['nid'] > 0)
			{
				$nid = $_GET['nid'];
				$action = "update";
			}
		}
		
		if ($action == "insert")
		{
			$nid = $this->nutrition_m->add($n_data);
			$data['result'] = true;
		} else {
			$did = $this->nutrition_m->update($nid, $n_data);
			$data['result'] = $did;
		}
		
		$data['nid'] = $nid;
		
		$story_id = $this->story_m->save(array(
			'story_id'						=>	null,
			'u_id'							=>	$this->uid,
			'type'							=>	'nutrition',
			'foreign_id_link_type'			=>	'nid',
			'foreign_id_val'				=>	$nid,
			's_kcal'						=>	$_GET['n_kcal'],
			'fingerprint'					=>	md5($this->uid."|".'nid'."|".$nid)
		));
		$enc_story_id = $this->base64url_encode($story_id);
		
		if ($action == "insert")
		{
			$data['story'] = $enc_story_id;
		}	
	
		echo json_encode($data);
	
		return 1;
		
	}

	function facebookpost()
	{
		$enc_story_id = $_GET['story_id'];
		$a = $this->facebook->api("/me/iburndapp:eat", "post", array(
				"food" => "https://www.iburnd.com/pub/nutrition/".$enc_story_id
			)
		);
		
		echo json_encode($a);
		return 1;
	}

	function getone()
	{
		$a = $this->nutrition_m->get_nutrition_log_item($date, $fid);
		var_dump($a);
		
	}
	
	function base64url_encode($data) { 
	  return rtrim(strtr(base64_encode($data), '+/', '-_'), '='); 
	} 

	function base64url_decode($data) { 
	  return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT)); 
	}
	
	
}
