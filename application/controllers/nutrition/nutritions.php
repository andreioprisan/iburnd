<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Nutritions extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		//$this->load->library('template');
		
		$this->load->model('nutrition/nutrition', 'nutrition_m');
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
					'u_id'			=>	$this->session ? $this->session->userdata('uid') : "0"
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
		echo json_encode($data);
		return 1;
		
	}

}
