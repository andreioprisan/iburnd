<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Workouts extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		//$this->load->library('template');
		
		$this->load->model('workout/workout', 'workout');
	}
	
	public function index()
	{
		
	}

	public function nutrition()
	{
		
	}
	
	public function log()
	{
		
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
					'u_id'			=>	$this->session ? $this->session->userdata('uid') : "0"
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
	
}
