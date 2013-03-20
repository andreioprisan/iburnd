<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pub extends CI_Controller {
	var $menu;
	var $uid;
	var $salt = "replaceme";
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
		
		$this->load->model('nutrition', 'nutrition_m');
		$this->load->model('statsm', 'stats_m');
		$this->load->model('storym', 'story_m');
		
		if ($this->session->userdata('name'))
		{
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
		} else {
			$this->menu = array(	
									array(	'name' 		=> "Sign In", 
											'val' 		=> "/facebook_auth/login", 
											'align' 	=> "right", 
											'login' 	=> "true"),
								);
			
		}

		$this->uid = $this->session ? $this->session->userdata('uid') : "0";	
	}
	
	public function index()
	{
		
	}

	public function nutrition()
	{
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
		
		$this->template->write_view('content_main', 'layouts/default', $payload);
		
		$enc = $this->uri->segment(3, 0);
		$story_id = $this->base64url_decode($enc);
		$story_details = $this->story_m->get($story_id);
		
		$type = $story_details->type;
		$id = $story_details->foreign_id_val;
		
		
		if ($type == "nutrition")
		{
			// theme
			$payload['new_url'] = "https://".$_SERVER['HTTP_HOST']."/pub/nutrition/".$enc;
			$payload['new_img_url'] = "https://".$_SERVER['HTTP_HOST']."/assets/img/logo.png";
			
			// food data 
			$payload['foods'] = $this->stats_m->get_nutrition_cals(null, $id);
			$payload['stats_m'] = $this->stats_m;
			
			if (isset($payload['foods']) && $payload['foods'] != null)
				$payload['new_title'] = $payload['foods'][0]->n;

			if (count($payload['foods']) != 0)
			{
				$payload['serving'] = $payload['foods'][0]->s == NULL ? "serving" : $payload['foods'][0]->s;
				$payload['servings'] = $payload['foods'][0]->s_s == NULL || $payload['foods'][0]->s_s == "1.00" ? "1" : $payload['foods'][0]->s_s;
				$payload['calories'] = $payload['foods'][0]->n_kcal == NULL || $payload['foods'][0]->s_s == "0.00" ? "0" : $payload['foods'][0]->n_kcal;
				$payload['nop'] = false;
			} else {
				$payload['nop'] = true;
			} 
			
			// layout stuff for nutritional item
			$this->template->write('title', 'iburnd - nutrient');
			$this->template->write_view('nutritionlog', 'layouts/pubnutrition', $payload);
		} else if ($type == "workout")
		{
			$payload['workouts'] = $this->stats_m->get_workout(null, $id);

			// template stuff for workout
			$payload['new_url'] = "https://".$_SERVER['HTTP_HOST']."/pub/workout/".$enc;
			$payload['new_img_url'] = "https://".$_SERVER['HTTP_HOST']."/assets/img/logo.png";
			$payload['new_title'] = $payload['workouts'][0]->pa_txt;
			
			$payload['time'] = $payload['workouts'][0]->pa_mins;
			$payload['mets'] = $payload['workouts'][0]->pa_mets;
			$payload['calories'] = $payload['workouts'][0]->pa_kcal;
			$payload['date'] = $payload['workouts'][0]->pa_date;
			
			$this->template->write('title', 'iburnd - workout');
			$this->template->write_view('nutritionlog', 'layouts/pubworkout', $payload);
		}
		
		$this->template->write_view('alertitemadd', 'layouts/alertitemadd', $payload);
		if ($this->session->userdata('name'))
			$this->template->write_view('alertitemadd', 'layouts/alertitemadd', $payload);
		
		
//		$this->template->write_view('nutritionalinfo', 'layouts/nutritionalinfo', $payload);

		return $this->template->render();

	}
	
	
	public function workout()
	{
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

		$this->template->write_view('content_main', 'layouts/default', $payload);

		$enc = $this->uri->segment(3, 0);
		$story_id = $this->base64url_decode($enc);
		$story_details = $this->story_m->get($story_id);

		$type = $story_details->type;
		$id = $story_details->foreign_id_val;


		if ($type == "nutrition")
		{
			// theme
			$payload['new_url'] = "https://".$_SERVER['HTTP_HOST']."/pub/nutrition/".$enc;
			$payload['new_img_url'] = "https://".$_SERVER['HTTP_HOST']."/assets/img/logo.png";

			// food data 
			$payload['foods'] = $this->stats_m->get_nutrition_cals(null, $id);
			$payload['stats_m'] = $this->stats_m;

			if (isset($payload['foods']) && $payload['foods'] != null)
				$payload['new_title'] = $payload['foods'][0]->n;

			if (count($payload['foods']) != 0)
			{
				$payload['serving'] = $payload['foods'][0]->s == NULL ? "serving" : $payload['foods'][0]->s;
				$payload['servings'] = $payload['foods'][0]->s_s == NULL || $payload['foods'][0]->s_s == "1.00" ? "1" : $payload['foods'][0]->s_s;
				$payload['calories'] = $payload['foods'][0]->n_kcal == NULL || $payload['foods'][0]->s_s == "0.00" ? "0" : $payload['foods'][0]->n_kcal;
				$payload['nop'] = false;
			} else {
				$payload['nop'] = true;
			} 

			// layout stuff for nutritional item
			$this->template->write('title', 'iburnd - nutrient');
			$this->template->write_view('nutritionlog', 'layouts/pubnutrition', $payload);
		} else if ($type == "workout")
		{
			$payload['workouts'] = $this->stats_m->get_workout(null, $id);

			// template stuff for workout
			$payload['new_url'] = "https://".$_SERVER['HTTP_HOST']."/pub/workout/".$enc;
			$payload['new_img_url'] = "https://".$_SERVER['HTTP_HOST']."/assets/img/logo.png";
			$payload['new_title'] = $payload['workouts'][0]->pa_txt;

			$payload['time'] = $payload['workouts'][0]->pa_mins;
			$payload['mets'] = $payload['workouts'][0]->pa_mets;
			$payload['calories'] = $payload['workouts'][0]->pa_kcal;
			$payload['date'] = $payload['workouts'][0]->pa_date;

			$this->template->write('title', 'iburnd - workout');
			$this->template->write_view('nutritionlog', 'layouts/pubworkout', $payload);
		}

		$this->template->write_view('alertitemadd', 'layouts/alertitemadd', $payload);
		if ($this->session->userdata('name'))
			$this->template->write_view('alertitemadd', 'layouts/alertitemadd', $payload);


//		$this->template->write_view('nutritionalinfo', 'layouts/nutritionalinfo', $payload);

		return $this->template->render();

	}

	function base64url_encode($data) { 
	  return rtrim(strtr(base64_encode($data), '+/', '-_'), '='); 
	} 

	function base64url_decode($data) { 
	  return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT)); 
	}
	
	
	public function encrypt($text) 
	{ 
		return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->salt, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)))); 
	} 

	public function decrypt($text) 
	{ 
		return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->salt, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))); 
	}
	
}
