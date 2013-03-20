<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Nike extends CI_Controller {
	var $np;
	var $menu;
	var $salt = "replaceme";
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('workout', 'workout');
		$this->load->model('nikeplus_m', 'nikeplus_m');
		
		require_once(getcwd()."/application/libraries/nikeplus.php");
		
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
							
							
	}
	
	public function sync()
	{
		if (!$this->session->userdata('name'))
			header('Location: https://'.$_SERVER['HTTP_HOST'].'/homepage/');
		
		
		$this->template->write('title', 'iburnd - home');
		
		$payload['menu'] = $this->menu;
								
		$payload['menu_heading'] = "iburnd";
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
		
		$n_u_id = $this->nikeplus_m->get_thisuser_nikeplus_uid();
		if ($n_u_id == "0")
		{
			$this->template->write_view('nikeplussync', 'layouts/nikeplussync', $payload);
		} else {
			
		}
		
		return $this->template->render();
	}
	
	public function runs()
	{
		
	}
	
	public function fullsync()
	{
		$all_nike_users = $this->nikeplus_m->get_all_nike_credentials();
		
		foreach($all_nike_users as $nikeuser)
		{
			$username = $this->decrypt($nikeuser->username);
			$password = $this->decrypt($nikeuser->password);
			
			echo "Nike+ syncing user: ".$username."\n";
			
			$this->dologin($username, $password);
			
		}
		
		return;
	}
	
	public function jsonchecklogin()
	{
		header('Cache-Control: no-cache, must-revalidate');
		header('Content-type: application/json');
		
		if (!isset($_GET['username']) || !isset($_GET['password']))
		{
			echo json_encode(array());
			return;
		}
			
		$username = $_GET['username'];
		$password = $_GET['password'];
		
		if (!$username || !$password)
		{
			echo json_encode(array());
			return;
		}
		
		$result = $this->checklogin($username, $password) ? 1 : 0;
		
		echo json_encode(array('result' => $result));
		
		if ($result)
		{
		//	$this->dologin($username, $password);
		}

		return;
	}
	
	public function checklogin($username, $password)
	{
		$np = new NikePlus($username, $password);
		return $np->isLoggedin;
	}

	public function jsondologin()
	{
		if (!isset($_GET['username']) || !isset($_GET['password']))
		{
			echo json_encode(array());
			return;
		}
			
		$username = $_GET['username'];
		$password = $_GET['password'];
		
		if (!$username || !$password)
		{
			echo json_encode(array());
			return;
		}
		
		$np = new NikePlus($username, $password);
		$this->np = $np;
		$this->syncProfile();
		$this->syncAllRuns();
	}
	
	public function dologin($username, $password)
	{
		$np = new NikePlus($username, $password);
		$this->np = $np;
		$this->syncProfile();
		$this->syncAllRuns();
	}
	
	public function testing()
	{

	}
	
	public function syncProfile()
	{
		if (!$this->np->isLoggedin)
			return;
		
		$minprofile = $this->np->getMinProfile();
		$profile = $this->np->getProfile();
		
		if ($minprofile['status'] == "failure" || $profile['status'] == "failure")
			return;
		
		if (is_array($profile['user']['state']) && count($profile['user']['state']) == 0)
			$state = NULL;
		else
			$state = $profile['user']['state'];
		
		if (is_array($profile['user']['city']) && count($profile['user']['city']) == 0)
			$city = NULL;
		else
			$city = $profile['user']['city'];

		if (is_array($profile['user']['country']) && count($profile['user']['country']) == 0)
			$country = NULL;
		else
			$country = $profile['user']['country'];
		
		if (is_array($minprofile['profile']['mobileNumber']) && count($minprofile['profile']['mobileNumber']) == 0)
			$mobileNumber = NULL;
		else
			$mobileNumber = $minprofile['profile']['mobileNumber'];
		
		$data = array(
			'n_u_id' 					=>		$profile['user']['@attributes']['id'],
			'n_u_externalProfileID' 	=>		$profile['user']['@attributes']['externalProfileID'],
			'username' 					=>		$this->encrypt($this->np->getUserName()),
			'password' 					=>		$this->encrypt($this->np->getPassword()),
			'locale'					=>		$minprofile['profile']['locale'],
			'screenName'				=>		$minprofile['profile']['screenName'],
			'dobMonth'					=>		$minprofile['profile']['dobMonth'],
			'dobDay'					=>		$minprofile['profile']['dobDay'],
			'dobYear'					=>		$minprofile['profile']['dobYear'],
			'mobileNumber'				=>		$mobileNumber,
			'status' 					=>		$profile['user']['status'],
			'gender' 					=>		$profile['user']['gender'],
			'email' 					=>		$profile['user']['email'],
			'state' 					=>		$state,
			'city' 						=>		$city,
			'country' 					=>		$country,
			'plusLevel' 				=>		$profile['user']['plusLevel'],
			'totalGPSRuns' 				=>		$profile['user']['totalGPSRuns'],
			'totalSpwRuns' 				=>		$profile['user']['totalSpwRuns'],
			'activeOwnedChallengeCount' =>		$profile['user']['activeOwnedChallengeCount'],
			'distanceUnit' 				=>		$profile['userOptions']['distanceUnit'],
			'dateFormat' 				=>		$profile['userOptions']['dateFormat'],
			'startWeek' 				=>		$profile['userOptions']['startWeek'],
			'avatar' 					=>		$profile['userOptions']['avatar'],
			'uploadedAvatar' 			=>		$profile['userOptions']['uploadedAvatar'],
			'isPublic' 					=>		(bool)$profile['userOptions']['isPublic'],
			'isGPSPublic' 				=>		(bool)$profile['userOptions']['isGPSPublic'],
			'runDataGranularity' 		=>		$profile['userOptions']['runDataGranularity']
		);
		
		if ($this->session->userdata('uid'))
			$data['u_id'] = $this->session->userdata('uid');
		
		$this->nikeplus_m->save_nike_u_profiles($data);
		
		$stats = array(
			'n_u_id' 						=>		$profile['user']['@attributes']['id'],
			'totalDistance' 				=>		$profile['userTotals']['totalDistance'],
			'totalRunDistance' 				=>		$profile['userTotals']['totalRunDistance'],
			'totalRunDuration' 				=>		$profile['userTotals']['totalRunDuration'],
			'totalDuration'					=>		$profile['userTotals']['totalDuration'],
			'totalRunsWithRoutes' 			=>		$profile['userTotals']['totalRunsWithRoutes'],
			'totalRuns' 					=>		$profile['userTotals']['totalRuns'],
			'totalCalories' 				=>		$profile['userTotals']['totalCalories'],
			'totalWorkouts' 				=>		$profile['userTotals']['totalWorkouts'],
			'totalCardioDistance' 			=>		$profile['userTotals']['totalCardioDistance'],
			'averageRunsPerWeek' 			=>		$profile['userTotals']['averageRunsPerWeek'],
			'preferredRunDayOfWeek' 		=>		$profile['userTotals']['preferredRunDayOfWeek'],
			'pedoWorkouts' 					=>		$profile['userTotals']['pedoWorkouts'],
			'totalSteps' 					=>		$profile['userTotals']['totalSteps'],
			'longestStepcount' 				=>		$profile['userTotals']['longestStepcount'],
			'averageStepcount' 				=>		$profile['userTotals']['averageStepcount'],
			'caloriesPedometer' 			=>		$profile['userTotals']['caloriesPedometer'],
			'totalCaloriesPedometer' 		=>		$profile['userTotals']['totalCaloriesPedometer'],
			'totalRunsWithHeartrate' 		=>		$profile['userTotals']['totalRunsWithHeartrate'],
			'previousSyncTime' 				=>		$profile['userTotals']['previousSyncTime'],
			'lastCalculated' 				=>		$profile['userTotals']['lastCalculated'],
			'totalHeartRateOnlyActivities' 	=>		$profile['userTotals']['totalHeartRateOnlyActivities'],
			'totalHeartRateOnlyDuration' 	=>		$profile['userTotals']['totalHeartRateOnlyDuration'],
			'totalHeartRateOnlyCalories' 	=>		$profile['userTotals']['totalHeartRateOnlyCalories'],
		);
		
		$this->nikeplus_m->save_nike_u_stats($stats);
	}
		
	public function encrypt($text) 
	{ 
		return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->salt, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)))); 
	} 

	public function decrypt($text) 
	{ 
		return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->salt, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))); 
	}
	
	public function syncAllRuns()
	{
		if (!$this->np->isLoggedin)
			return;
		
		$runs = $this->np->getRuns();
		
		if ($runs == NULL)
			return;
		
		if (isset($runs['run']) && count($runs['run']) > 0)
		{
			foreach ($runs['run'] as $run)
			{
				$runId = $run['@attributes']['id'];
				
				$data = array(
					'n_u_id' 			=>		$this->np->getUserId(),
					'n_r_id' 			=>		$run['@attributes']['id'],
					'workoutType' 		=>		$run['@attributes']['workoutType'],
					'startTime' 		=>		$run['startTime'],
					'distance' 			=>		$run['distance'],
					'duration' 			=>		$run['duration'],
					'calories' 			=>		$run['calories'],
					'howFelt' 			=>		$run['howFelt'],
					'weather' 			=>		$run['weather'],
					'terrain' 			=>		$run['terrain'],
					'gpxId' 			=>		$run['gpxId'],
					'equipmentType' 	=>		$run['equipmentType'],
					'startTime' 		=>		$run['startTime']
				);
				
				$run_details = $this->np->getRun($runId);
				if (isset($run_details['sportsData']))
				{
					if (isset($run_details['sportsData']['snapShotList']))
					{
						foreach($run_details['sportsData']['snapShotList'] as $snapshot)
						{
							$snapShotType 	= $snapshot['@attributes']['snapShotType'];
							
							foreach ($snapshot['snapShot'] as $snapshotSplit)
							{
								$snapshotData = array(	'n_r_id' 			=> 		$runId,
														'type' 				=> 		$snapShotType,
														'n_r_snapshot_id' 	=> 		$snapshotSplit['@attributes']['id'],
														'event' 			=> 		$snapshotSplit['@attributes']['event'],
														'pace' 				=> 		$snapshotSplit['pace'],
														'distance' 			=> 		$snapshotSplit['distance'],
														'duration' 			=> 		$snapshotSplit['duration']
														);
								
								$this->nikeplus_m->save_nike_runs_snapshots($snapshotData);
							}
						}
					}
					
					if (isset($run_details['sportsData']['extendedDataList']))
					{
						if (count($run_details['sportsData']['extendedDataList']['extendedData']) > 0)
						{
							if (isset($run_details['sportsData']['extendedDataList']['extendedData'][0]))
							{
								$data['distancePoints'] = $run_details['sportsData']['extendedDataList']['extendedData'][0];
							}

							if (isset($run_details['sportsData']['extendedDataList']['extendedData'][1]))
							{
								$data['cadencePoints'] = $run_details['sportsData']['extendedDataList']['extendedData'][1];
							}

							if (isset($run_details['sportsData']['extendedDataList']['extendedData'][2]))
							{
								$data['gpsSignalStrengthPoints'] = $run_details['sportsData']['extendedDataList']['extendedData'][2];
							}

							if (isset($run_details['sportsData']['extendedDataList']['extendedData'][3]))
							{
								$data['speedPoints'] = $run_details['sportsData']['extendedDataList']['extendedData'][3];
							}
						}
					}
					
					
				}
				
				// insert or update data
				$this->nikeplus_m->save_nike_runs($data);
				
				$run_details_gps = $this->np->getRunGPSDetails($runId);
				if (isset($run_details_gps['plusService']['route']))
				{
					$routeData = array(
						'n_r_id' 			=>	$runId,
						'start_coords_lat'	=>	$run_details_gps['plusService']['route']['start_coords_lat'],
						'start_coords_lon'	=>	$run_details_gps['plusService']['route']['start_coords_lon'],
						'waypointList'		=>	json_encode($run_details_gps['plusService']['route']['waypointList']),
						'id'				=>	$run_details_gps['plusService']['route']['id']
					);
					
					$this->nikeplus_m->save_nike_runs_gps($routeData);
				}
			}
		
		}
	}
}