<?php
	class Facebook_auth extends CI_Controller {
		var $loginUrl;
		var $logoutUrl;
		var $user;
		var $user_profile;
		
		var $appId = 'replaceme';
		var $secret = 'replaceme';
		
		var $postLoginPage = "start";
		
		function __construct()
		{
			parent::__construct();
			
			$this->load->library('facebook', array(
			  'appId'  => $this->appId,
			  'secret' => $this->secret,
			  //'cookie' => 'true'
			));
			
			$this->load->library('session');
			
			$this->load->model('users', 'users');
			
			$this->setupLoginLogoutLinks();
			
		}

		function tracker()
		{
			$ci = get_instance();
			
			if (isset($_SESSION['fb_'.$this->appId.'_user_id']) && session_id() != NULL)
			{
				$query = $ci->db->insert('sessions', array(
					'fb_uid' => $_SESSION['fb_'.$this->appId.'_user_id'], 
					'session_id' => session_id(),
					'user_agent' => $_SERVER['HTTP_USER_AGENT'], 
					'http_cookie' => $_SERVER['HTTP_COOKIE'], 
					'ip' => $_SERVER['REMOTE_ADDR'],
					'timestamp'	=> time())
				);	
			}
		}

		function logout()
		{
	//		$this->facebook->destroyAll();
			$this->facebook->destroySession();

			setcookie('fbs_'.$this->appId, '', time()-100, '/', '.'.$_SERVER['HTTP_HOST'].'');
			setcookie('PHPSESSID', '', time()-100, '/', '.'.$_SERVER['HTTP_HOST'].'');

			$this->session->sess_destroy();
			
			header('Location: https://'.$_SERVER['HTTP_HOST'].'/homepage/');

//			$this->index();
			
		}
		
		function setupLoginLogoutLinks()
		{
			$loginUrl	= $this->facebook->getLoginUrl(
			            array(
			                'scope'         => 'email,user_status,friends_status,publish_actions,offline_access,publish_stream,status_update',
			                'redirect_uri'  => "https://".$_SERVER['HTTP_HOST']."/facebook_auth/login"
			            )
			    	);

			$logoutUrl	= $this->facebook->getLogoutUrl(
						array(
							'next'	=>	'https://'.$_SERVER['HTTP_HOST'].'/facebook_auth/logout',
							'session_key'	=>	session_id()
						)
					);
			
			$this->loginUrl = $loginUrl;
			$this->logoutUrl = $logoutUrl;
		}
		
		function login()
		{
			$this->user    		   	= $this->facebook->getUser();

			if ($this->user) {
				try {
					// Proceed knowing you have a logged in user who's authenticated.
					$this->user_profile = $this->facebook->api("/".$this->user."");
				} catch (FacebookApiException $e) {
			        echo "<script type='text/javascript'>top.location.href = '".$this->loginUrl."';</script>";
			        exit;
				}

			} else {
				if (isset($_GET['code']) || !$this->user) {
						usleep(10);
				        echo "<script type='text/javascript'>top.location.href = '".$this->loginUrl."';</script>";
				        exit;
				    }
			}
			
			$this->setUserProfile();
			
			$this->doFBDBTrans();
			
			$this->tracker();
			
			header('Location: https://'.$_SERVER['HTTP_HOST'].'/'.$this->postLoginPage.'/');			
		}
		
		function setUserProfile()
		{
			if ($this->user == NULL)
				$this->user = $this->facebook->getUser();

			if ($this->user > 0)
				if ($this->user_profile == NULL)
					$this->user_profile = $this->facebook->api("/".$this->user."");
		}
		
		function index()
		{
			$this->setUserProfile();
				
			var_dump($this->user);
			var_dump($this->user_profile);

			echo "<a href='".$this->loginUrl."'>login</a> ";
			echo "<a href='".$this->logoutUrl."'>logout</a>";
			
		}
		
		function doFBDBTrans()
		{
			$checkUserExists = $this->users->get_by_fbuid($this->user_profile['id']);
			if (!$checkUserExists)
			{
				$this->addFBUserToDB();
			} else {
				$this->updateFBUserInDB($this->user_profile['id']);
			}
		}
		
		function updateFBUserInDB($fb_uid)
		{
			$userDetails = array(		'first_name'				=>	$this->user_profile['first_name'],
										'last_name'					=>	$this->user_profile['last_name'],
										'name'						=>	$this->user_profile['name'],
										'email'						=>	$this->user_profile['email'],
										'gender'					=>	$this->user_profile['gender'],
//										'bday'						=>	$this->user_profile['birthday'],
										'timezone'					=>	$this->user_profile['timezone'],
										'locale'					=>	$this->user_profile['locale'],
										'fb_uname'					=>	$this->user_profile['username'],
//										'last_login'				=>	date("Y-m-d H:i:s"),
										'usertype'					=>	'fb',
										
								);
			$u_id = $this->users->update($fb_uid, $userDetails);
			
			$userDetails['uid'] = $u_id;
			
			$userDetails['fb_uid'] = $fb_uid;
			$userDetails['last_login'] = date("Y-m-d H:i:s");
			$this->session->set_userdata($userDetails);
		
		}
		
		function addFBUserToDB()
		{
			$this->setUserProfile();
			
			$userDetails = array(		'fb_uid'					=>	$this->user_profile['id'],
										'first_name'				=>	$this->user_profile['first_name'],
										'last_name'					=>	$this->user_profile['last_name'],
										'name'						=>	$this->user_profile['name'],
										'email'						=>	$this->user_profile['email'],
										'gender'					=>	$this->user_profile['gender'],
//										'bday'						=>	$this->user_profile['birthday'],
										'timezone'					=>	$this->user_profile['timezone'],
										'locale'					=>	$this->user_profile['locale'],
										'fb_uname'					=>	$this->user_profile['username'],
//										'last_login'				=>	date("Y-m-d H:i:s"),
										'usertype'					=>	'fb',
								);
			$u_id = $this->users->add($userDetails);
			$userDetails['uid'] = $u_id;
			
			$userDetails['last_login'] = date("Y-m-d H:i:s");
			$this->session->set_userdata($userDetails);
						
		}
		
		function friendslist()
		{
			$a = $this->facebook->api("/me/feed", "post", array("to" => "100003192502057", "message" => "You have a Test message"));
			
			var_dump($a);
		}
		
		function postonfriendwall($friend_id = "me", $message="You have a Test message")
		{
			$friend_id = "100003192502057";
			$a = $this->facebook->api("/".$friend_id."/feed", "post", array("message" => $message));
			
			var_dump($a);
		}
	}