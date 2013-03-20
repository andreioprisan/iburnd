<?php

class Homepage extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->library('template');
		
	}
	
	function index()
	{
		
		$this->template->write('title', 'iburnd - home');
		
		$payload['menu'] = array(	
									array(	'name' 		=> "Sign In", 
											'val' 		=> "/facebook_auth/login", 
											'align' 	=> "right", 
											'login' 	=> "true"),
								);
		
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
		
		$this->template->write_view('start', 'layouts/homepage', $payload);

		return $this->template->render();
	}
}
