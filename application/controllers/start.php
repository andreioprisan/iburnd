<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Start extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->library('template');

		// core
		$this->load->model('workout', 'workout');
		$this->load->model('nutrition', 'nutrition');
		// social
		$this->load->model('badges', 'nutrition');
		
		
		if (!$this->session->userdata('name'))
			header('Location: https://'.$_SERVER['HTTP_HOST'].'/homepage/');
			
	}
	
	public function search()
	{
		//$searchword = "winco bulk";
		if (isset($_GET['q']))
			$searchword = $_GET['q'];
		else if (isset($_GET['id']))
		{
			$brands = $this->nutrition->getBrandByID($_GET['id']);

			if(!$brands)
				return;

			foreach ($brands as $brand)
			{
				$searchword = $brand->brand;
				$brand_id = $brand->id;
			}
		}
		else {
			$brands = $this->nutrition->getRandomBrand();

			foreach ($brands as $brand)
			{
				$searchword = $brand->brand;
				$brand_id = $brand->id;
			}
			//var_dump($brands);
//			$searchword = "100 calorie packs";

		}

		$cl = $this->sphinxsearch;
		$cl->SetLimits(0,100000);

		$cl->SetIndexWeights(array('UsersConfirm' => 2));
//		$cl->SetGroupBy('UsersConfirm', SPH_GROUPBY_ATTR);

//		$cl->SetArrayResult(true);
		/*
		SPH_MATCH_ALL
		SPH_MATCH_ANY
		SPH_MATCH_PHRASE
		SPH_MATCH_BOOLEAN
		SPH_MATCH_EXTENDED
		SPH_MATCH_EXTENDED2
		*/
		$cl->SetMatchMode( SPH_MATCH_ALL );

		$result = $cl->Query($searchword);
		//var_dump($result);

		if (isset($result['error']) && $result['error'])
		{
			echo "<h1>".$result['error']."</h1>";
		}

		if (isset($result['warning']) && $result['warning'])
		{
			echo "<h2>".$result['warning']."</h2>";
		}

		if (isset($result['total']))
		{
			echo "<h2>".$result['total_found']." results in ".$result['time']."s for query (<font color=red>".$searchword."</font>) - showing ".$result['total']."</h2>";
		}

		if (isset($result['words']))
		{
			echo "<h3>individual word stats</h3>";
			foreach ($result['words'] as $searchword_name => $searchword_res)
			{
				echo "<b>".$searchword_name."</b> count: ".$searchword_res['docs']."<br>";
			}
		}

		if ($cl->GetLastError())
		{
			echo "<h1>".$cl->GetLastError()."</h1>";
		}

		if ($result['total_found'] == 0)
		{
			echo "no matches for this brand!";
			return;
		}

		echo "<br>";
		echo "<table border=1><tr><td>name</td><td>new name</td><td>id</td><td>weight</td><td>servings</td><td>portion</td><td>calories</td><td>usersconfirm</td></tr>";

		foreach ($result['matches'] as $match)
		{
			$newname = $this->nutritional_entry_process($match['attrs']['name'], $searchword);
			if (!$newname)
				continue;

			echo "<tr>";
			echo "<td>".$match['attrs']['name']."</td>";
			echo "<td>".$newname."</td>";

			$a = $this->nutrition->update($match['id'], array('Name' => $newname));
			if ($a)
				echo "+";
			else 
				echo "-";

			unset($a);

			$a = $this->nutrition->add_food_brand_rel($match['id'], $brand_id);
			if ($a)
				echo "+";
			else 
				echo "-";

			unset($a);

			//$a= $this->nutrition->getBT_ID($match['id']);
			//var_dump($a);
			echo "<td>".$match['id']."</td>";
			echo "<td>".$match['weight']."</td>";
			echo "<td>".$match['attrs']['servings']."</td>";
			echo "<td>".$match['attrs']['portion']."</td>";
			echo "<td>".$match['attrs']['calories']."</td>";
			echo "<td>".$match['attrs']['usersconfirm']."</td>";
			echo "</tr>";
		}

		echo "</table>";

		unset($result);
	}
	
	public function index()
	{
		$this->template->write('title', 'iburnd - home');
		
		$payload['menu'] = array(	
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
								
		
		$payload['menu_heading'] = "iburnd";
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

		$this->template->write_view('content_main', 'layouts/default', $payload);
		$this->template->write_view('alertitemadd', 'layouts/alertitemadd', $payload);
		$this->template->write_view('start', 'layouts/start', $payload);
		$this->template->write_view('dashboard', 'layouts/dashboard', $payload);
		
		return $this->template->render();
	}
}

