<?php

/**
 * A Nike+ API implementation
 * 
 * requires:
 *  json_encode
 *  json_decode
 *  simplexml
 *  curl
 * 
 * @author 		Andrei Oprisan
 * @link 		http://www.iburnd.com
 * @version 	1.0
 */
 
class NikePlus {
	private $requestData, $userId, $username, $password, $profile, $minprofile;
	var $debug 			= true;
	var $isLoggedin 	= false;
	
	# auth
	var $url = array(	
		'auth' 				=> 'https://secure-nikerunning.nike.com/services/profileService',
		'profile' 			=> 'http://nikerunning.nike.com/nikeplus/v2/services/app/get_user_data.jsp',
		'userevents' 		=> 'http://nikerunning.nike.com/nikeplus/v2/services/userevent/get_user_events.jsp',
		'runs' 				=> 'http://nikerunning.nike.com/nikeplus/v2/services/app/run_list.jsp',
		'run' 				=> 'http://nikerunning.nike.com/nikeplus/v2/services/app/get_run.jsp',
		'run_gps_details' 	=> 'http://nikerunning.nike.com/nikeplus/v2/services/app/get_gps_detail.jsp',
		#_plus=true&type=all&max=3
		'personalrecord' 	=> 'http://nikerunning.nike.com/nikeplus/v2/services/app/personal_records.jsp',
		'goals' 			=> 'http://nikerunning.nike.com/nikeplus/v2/services/app/goal_list.jsp',
		'goalscompleted' 	=> 'http://nikerunning.nike.com/nikeplus/v2/services/app/completed_goal_list.jsp',
		'challenge' 		=> 'http://nikerunning.nike.com/nikeplus/v2/services/app/get_challenge_detail.jsp', 
		#id
		'challenges' 		=> 'http://nikerunning.nike.com/nikeplus/v2/services/app/get_challenges_for_user.jsp',
		'challengesrec'  	=> 'http://nikerunning.nike.com/nikeplus/v2/services/app/get_recommended_challenges.jsp',
		'challengemessages' => 'http://nikerunning.nike.com/nikeplus/v2/services/app/get_public_challenge_messages.jsp',
		#format=json
		'generatepin' 		=> 'http://nikerunning.nike.com/nikeplus/v2/services/app/generate_pin.jsp?login=USER&password=PASS'
	);
	
	/**
	 * __construct()
	 * Called when you initiate the class and keeps a cookie that allows you to keep authenticating
	 * against the Nike+ website.
	 * 
	 * @param string $username your Nike username, should be an email address
	 * @param string $password your Nike password 
	 */
	public function __construct($username, $password) {
		$this->tmpfile = "/tmp/".md5($username."|".$password);
		
		if ($this->debug)
			log_message('debug', "Nike+::__construct() cookie:".$this->tmpfile."");
		
		$this->login($username, $password);
		//$this->setProfile();
	}
	
	/**
	 * login()
	 * Performs the actual login action
	 * 
	 * @param string $username
	 * @param string $password
	 * 
	 * @return string
	 */
	private function login($username, $password) {
		$url = $this->url['auth'];
		
		$this->setUserName($username);
		$this->setPassword($password);
		
		$params = array('action' 		=>	"login",
						'login' 		=>	"$username",
						'password' 		=>	"$password"
						);
		
		$this->curlAction($url, NULL, $params);
		
		try {
			if ($contents = @simplexml_load_string($this->raw2xml($this->requestData))) {
				//$this->profile = $this->xml2array($contents);
				$this->minprofile = $this->xml2array($contents);
				
				$this->userId = (integer) $contents->profile->id;
				if(!$this->userId || gettype($this->userId) != 'integer' || $this->userId == "0") {
					$this->isLoggedin = false;
					throw new ErrorException("Login Failed!");
				}
			} else {
				throw new ErrorException("Could not read data from Nike+ service");
			}
		} catch (ErrorException $e) {
			$this->isLoggedin = false;
			//echo 'Nike+ exception: ',  $e->getMessage(), "\n";
		}
		
	}
	
	
	/**
	 * getMinProfile()
	 * Gets the post-login auth profile data, which is strangely different than the profile data
	 * 
	 * @return int
	 */
	public function getMinProfile()
	{
		return $this->minprofile;
	}


	/**
	 * getUserId()
	 * Gets the current NIke+ userid
	 * 
	 * @return int
	 */
	public function getUserId()
	{
		return $this->userId;
	}
	
	/**
	 * getUserName()
	 * Gets the current NIke+ username
	 * 
	 * @return int
	 */
	public function getUserName()
	{
		return $this->username;
	}
	
	/**
	 * getPassword()
	 * Gets the current NIke+ user password
	 * 
	 * @return string
	 */
	public function getPassword()
	{
		return $this->password;
	}
	
	/**
	 * setUserName()
	 * Sets the current NIke+ username
	 * 
	 * @return null
	 */
	public function setUserName($username)
	{
		$this->username = $username;
	}
	
	/**
	 * setPassword()
	 * Sets the current NIke+ user password
	 * 
	 * @return null
	 */
	public function setPassword($password)
	{
		$this->password = $password;
	}
	
	/**
	 * curlAction()
	 * Do actual curl posting and interpret results
	 * 
	 * @param string $url the URL of the request
	 * @param array $get post URL parameters
	 * @param array $post post URL parameters
	 * 
	 * @return global array $this->requestData set with results data
	 */
	private function curlAction($url, $get = NULL, $post = NULL)
	{
		if (!$url)
			return false;
		
		$ch = curl_init();
		
		if (is_array($get) && count($get) > 0)
		{
			if (isset($get['_plus'])) 
			{
				unset($get['_plus']);
				$url .= '?_plus=true&'.http_build_query($get);
			} else 
				$url .= '?'.http_build_query($get);	
		}
		
		if (is_array($post) && count($post) > 0)
		{
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
		}
		
		if ($this->debug)
			log_message('debug', "Nike+::curlAction() url:".$url."");
		
		// post url with cookie info via CURL
		curl_setopt($ch, CURLOPT_URL, $url);
		if ($this->isLoggedin == FALSE)
		{
			curl_setopt($ch, CURLOPT_HEADER, 1);
			curl_setopt($ch, CURLOPT_COOKIEJAR, $this->tmpfile);
			$this->isLoggedin = TRUE;
		}
		else 
		{
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_COOKIEJAR, $this->tmpfile);
			curl_setopt($ch, CURLOPT_COOKIEFILE, $this->tmpfile);
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		
		$this->requestData = curl_exec($ch);
		curl_close($ch);
	}
	
	/**
	 * processURL()
	 * Process a Nike+ action URL and convert results to an array
	 * 
	 * @param string $filePath the path to the file that needs to be read
	 * @param boolean $isPlusURL specifies if the URL should get the new style _plus parameter or not
	 * @param array $params URL parameters to be added to the request
	 * 
	 * @return array response
	 */
	private function processURL($url, $isPlusURL = 1, $params = NULL) 
	{
		if ($isPlusURL == 1)
			$params['_plus'] = 'true';
		
		$this->curlAction($url, $get = $params, $post = NULL);
		
		// get XML and convert to array (90%) and then catch any leftover objects to convert into array elements as well
		if ($this->isjson($this->requestData))
		{
			return $this->obj2array(json_decode($this->requestData));
		} else {
			if($content = @simplexml_load_string($this->requestData))
			{
				return $this->xml2array($content);
			} else {
				throw new ErrorException('The XML feed could not be read.');
			}
		}
	}

	/**
	 * isjson()
	 * Check if string is valid json
	 * 
	 * @param string $json
	 * 
	 * @return string
	 */
	public function isjson($json)
	{
		json_decode($json);
		return (json_last_error() == JSON_ERROR_NONE);
	}
	
	/**
	 * raw2xml()
	 * Process an raw request with headers and return only the ending xml
	 * 
	 * @param string $raw
	 * 
	 * @return string
	 */
	public function raw2xml($raw)
	{
		$exploded = explode("\n", $this->requestData);
		return $exploded[count($exploded)-1];
	}
	
	/**
	 * xml2array()
	 * Process an xml to array
	 * 
	 * @param string $xml
	 * 
	 * @return array
	 */
	public function xml2array($xml)
	{
		return $this->obj2array(json_decode(json_encode($xml)));
	}
	
	/**
	 * obj2array()
	 * Process an object to array
	 * 
	 * @param string $obj
	 * 
	 * @return array
	 */
	public function obj2array($obj) 
	{
		$result = array();
		$arrObj = is_object($obj) ? get_object_vars($obj) : $obj;
		foreach ($arrObj as $key => $val) 
			$result[$key] = (is_array($val) || is_object($val)) ? $this->obj2array($val) : $val;
			
        return $result;
	}
	
	/**
     * setProfile()
     * Set the profile of the user
     * 
     * @return null
     */
    public function setProfile() {
		$this->profile = $this->processURL($this->url['profile'], 1);
    }

    /**
     * getProfile()
     * Get the profile of the user
     * Retrieve profile data first if not set globally
     * 
     * @return array
     */
    public function getProfile() {
		if (count($this->profile) > 0)
			return $this->profile;
		else {
			$this->setProfile();
			return $this->profile;
		}
    }
	
   /**
     * getMostRecentRunId()
     * Get the id for a the latest run
     * 
     * @return int the numeric ID of the last run
     */
    public function getMostRecentRunId() {
        return (float) $this->profile['mostRecentRun']['@attributes']['id'];
    }

	/**
	 * getRuns()
	 * Get ALL run data for the user
	 * 
	 * @return array
	 */
	public function getRuns() {
		$this->requestData = $this->processURL($this->url['runs'], 1);
		
		if ($this->requestData['status'] == "failure")
			return NULL;
		else
			return $this->requestData['runList'];
	}

    /**
     * getRun()
     * Get the data for a single run
     * 
     * @param int|string $runId the numeric ID of the run to retrieve
     * 
     * @return array
     */
    public function getRun($id) {
        $this->requestData = $this->processURL($this->url['run'], 1, array('id' => $id));
        return $this->requestData;
    }

    /**
     * getRunGPSDetails()
     * Get the data for a single run
     * 
     * @param int|string $runId the numeric ID of the run to retrieve
     * 
     * @return array
     */
    public function getRunGPSDetails($id) {
        $this->requestData = $this->processURL($this->url['run_gps_details'], 1, array('format' => 'json', 'type' => 'all', 'id' => $id));
        return $this->requestData;
    }

	/**
	 * getPersonalRecords()
	 * Get the personal records for the user
	 * 
	 * @return array
	 */
	public function getPersonalRecords() {
		return $this->processURL($this->url['personalrecord'], 1);
	}

	/**
	 * getGoals()
	 * Get the goals set for the user
	 * 
	 * @return array
	 */
	public function getGoals() {
		return $this->processURL($this->url['goals'], 1);
	}

	/**
	 * getCompleteGoals()
	 * Get the completed goals for the user
	 * 
	 * @return array
	 */
	public function getCompletedGoals() {
		return $this->processURL($this->url['goalscompleted'], 1);
	}
	
	/**
	 * getChallenges()
	 * Get the challenges for the user
	 * 
	 * @return array
	 */
	public function getChallenge($id = NULL) {
		return $this->processURL($this->url['challenge'], 1, array('id' => $id));
	}

	/**
	 * getCurrentChallenges()
	 * Get the current challenges for the user
	 * 
	 * @return array
	 */
	public function getChallenges() {
		return $this->processURL($this->url['challenges'], 1);
	}
	
	/**
	 * getCurrentChallenges()
	 * Get public challenge messages for the user
	 * 
	 * @return array
	 */
	public function getPublicChallengeMessages() {
		return $this->processURL($this->url['challengemessages'], 1);
	}
	
	/**
	 * getUserEvents()
	 * Get a list of events for the user
	 * 
	 * @return array
	 */
	public function getUserEvents() {
		return $this->processURL($this->url['userevents'], 1);
	}
	
}