<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stats extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		//$this->load->library('template');
		
		$this->load->model('workout/workout', 'workout');
		$this->load->model('nutrition/nutrition', 'nutrition');
		$this->load->model('stats/statsm', 'stats_m');
	}
	
	public function index()
	{
		
	}

	public function nutrition()
	{
		
	}
	
	public function cal()
	{
		$data = array('result'	=>	'false');
		if (!isset($_GET['date']))
		{
			echo json_encode($data);
			return 0;
		}
		
		//mm/dd/yy
		$date = $_GET['date'];
		
		$nutr_kcal = 0;
		$n_s = $this->stats_m->get_nutrition_cals($date);
		foreach ($n_s as $nutr)
		{
			$nutr_kcal += $nutr->n_kcal;
		}
		
		$w_kcal = 0;
		$w_s = $this->stats_m->get_workout_cals($date);
		foreach ($w_s as $w)
		{
			$w_kcal += $w->pa_kcal;
		}
		
		$data['stats'] = array(	'n' =>	$nutr_kcal,
								'w' =>	$w_kcal,
								't' =>	$nutr_kcal - $w_kcal);
		$data['result'] = 'true';
		echo json_encode($data);
		return 1;
	}
	

	
}
