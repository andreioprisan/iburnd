<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		//$this->load->library('template');
		
		$this->load->model('workout', 'workout');
		$this->load->model('nutrition', 'nutrition');
		
	}
	
	public function index()
	{
		echo "search";
	}

	public function servings()
	{
		$sid = $_GET['id'];
		$this->workout->update_sid_for_fid($sid);
		
	}
	
	public function servings_rel()
	{
		$limit = $_GET['id'];
		
		foreach ($this->workout->get_unproc_ss($limit) as $fitem)
		{
			$this->workout->add_serving_f_rel(array('sid' => $fitem->ss, 'fid' => $fitem->fid));
		}
		
	}
	
	public function nutrition()
	{
		$totalcountsearch = 20;
		
		if (!count($_GET) > 0)
		{
			echo json_encode(array());
			return 0;
		} else {
			$searchword = $_GET['q'];
		}
		
		// type = slim won't show 
		if (isset($_GET['type']))
			$type = $_GET['type'];
		else
			$type = "slim";
			
		$bench['0_0'] = microtime(true);
		$bench['1_0'] = microtime(true);
		
		$cl = $this->sphinxsearch;
		$cl->SetLimits(0, $totalcountsearch);

		$cl->SetSelect('*, (@weight)*99 + cl as score');
		$cl->SetSortMode(SPH_SORT_EXTENDED, ' score DESC, cl DESC');

		$cl->SetMatchMode( SPH_MATCH_ALL );

		$result = $cl->Query($searchword, "fnames");
		
		$bench['1_1'] = microtime(true);
		$bench['query'] = $bench['1_1']-$bench['1_0'];
		unset($bench['1_0']);
		unset($bench['1_1']);
		
		$total_found = (int)$result['total_found'];
		
		if ($result)
			if (isset($result['matches']))
				$total_showing = sizeof($result['matches']);
			else
				$total_showing = 0;
			
		//$total_time = $result['time'];
		
		//echo "<h1>found $total_found in $total_time seconds</h1>";
		
		$json_result = array(	'stats' 	=> 	array(
													'found'		=>	$total_found,
													'showing'	=>	NULL
												),
								'benchmark'	=>	array(
													'query'		=>	NULL,
													'total'		=>	NULL
												),
								'items' 	=> 	array()
							);
		
		if ($result)
			$json_result['stats']['showing'] = $total_showing;
		
		if ($result && $total_showing != 0)
		{
		
		foreach ($result['matches'] as $f)
		{
			$item = array(	'fid'				=>	NULL,
							'name'				=>	NULL,
							'brand'				=>	NULL,
							'servingsize'		=>	NULL,
			//				'confidence'		=>	NULL
							);
			
			$item['fid'] = $f['id'];
			
			if (isset($f['attrs']['n']))
				$item['name'] = utf8_decode(strtolower($f['attrs']['n']));
			
			$servingsize = $this->nutrition->get_f_servings($f['id']);
			if (isset($servingsize) && count($servingsize) > 0)
				$item['servingsize'] = $servingsize;
			
			$brands = $this->nutrition->get_f_brands($f['id']);
			if ($brands)
				$item['brand'] = $brands;
			
			unset($servingsize);	
			unset($brands);	
			
			if ($type == "full")
			{
				$item['nutrients'] = NULL;
				
				$bench['2_0'] = microtime(true);
				
				$nutrients = $this->nutrition->get_f_nutrients($f['id']);
			
				foreach ($nutrients as $nutrient)
				{
					//var_dump($nutrient);
					if ($nutrient->val != 0)
						$item['nutrients'][$nutrient->id] = $nutrient->val;
				}
				
				if (count($item['nutrients']) == 0)
				{
					$item['nutrients'][208] = 0;
				}
				
				$bench['2_1'] = microtime(true);
				$bench['nutrients'] = $bench['2_1']-$bench['2_0'];
				unset($bench['2_0']);
				unset($bench['2_1']);
				
			} else if ($type == "cal") {
				$item['nutrients'] = NULL;
				
				$bench['2_0'] = microtime(true);
				
				$nutrients = $this->nutrition->get_f_nutrients_cal($f['id']);
			
				foreach ($nutrients as $nutrient)
				{
					//var_dump($nutrient);
					if ($nutrient->val != 0)
						$item['nutrients'][$nutrient->id] = $nutrient->val;
				}
				
				if (count($item['nutrients']) == 0)
				{
					$item['nutrients'][208] = 0;
				}
				
				$bench['2_1'] = microtime(true);
				$bench['nutrients'] = $bench['2_1']-$bench['2_0'];
				unset($bench['2_0']);
				unset($bench['2_1']);
			}
			
			//var_dump($item['nutrients']);
			//var_dump($nutrients);
			
//			if ($nutrients)
				#$item['brand'] = implode($brands, "|");
				#$item['brand'] = implode($brands, "|");
//				$item['nutrients'] = $nutrients;
			
			$json_result['items'][] = $item;
			unset($item);
		}
		
		}
		
		$bench['0_1'] = microtime(true);
		$bench['total'] = $bench['0_1']-$bench['0_0'];
		unset($bench['0_0']);
		unset($bench['0_1']);
		
		$json_result['benchmark']['query'] = round($bench['query']*1000, 2);
		if (isset($bench['nutrients']))
			$json_result['benchmark']['nutrients'] = round($bench['nutrients']*$total_showing*1000, 2);
		$json_result['benchmark']['total'] = round($bench['total']*1000, 2);
		
		
		header('Cache-Control: no-cache, must-revalidate');
		header('Content-type: application/json');
		echo json_encode($json_result);
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
		
		header('Cache-Control: no-cache, must-revalidate');
		header('Content-type: application/json');
		echo json_encode($data);
		return 1;
	}
	
}
