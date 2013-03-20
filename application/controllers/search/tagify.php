<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tagify extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		//$this->load->library('template');
		
		$this->load->model('tagifym');
		
	}
	
	public function index()
	{
		echo "tagify";
		$all = $this->tagifym->getall('pactivities');
		
		$tagcloud = array();
		
		foreach ($all as $item)
		{
			$split = explode(",", $item->pa_txt);
			
			foreach ($split as $tagitem)
			{
				$item_text = str_replace(array(" (e.g.", "(e.g.", "etc."), "", trim(strtolower($tagitem)));
				
				if (!strpos($item_text, "mile") && !strpos($item_text, "mph") && !strpos($item_text, "km"))
				{
					if (strpos($item_text, ")") == strlen($item_text)-1)
					{
						if (!strpos($item_text, "(ren"))
							$item_text = substr($item_text, 0, strlen($item_text)-1);
					}
					
				} else {
					$item_text = $item_text;
				}
				
				$item_text = str_replace(array("&"), " and ", trim(strtolower($item_text)));
				$item_text = str_replace(array("?"), "'", trim(strtolower($item_text)));
				
				
				foreach (array(	"(formerly code", 
								"(now code", 
								"(taylor code") as $filterout)
				{
					if (strpos($item_text, $filterout))
					{
						$startat = strpos($item_text, $filterout);
						$item_text = substr($item_text, 0, $startat);
					}
				}
					
				if (strpos($item_text, "ode ="))
				{
					$item_text = "";
				}
				
				if (strlen(trim($item_text)) == 0)
					continue;
				
				array_push($tagcloud, array('pa_id' 	=>	$item->pa_id, 
											'pa_tag'	=>	$item_text
											)
				);
				
				unset($item_text);
			}
		}
		
		foreach ($tagcloud as $item)
		{
			var_dump($item);
			echo "<br>";
			//$this->tagifym->add('pactivities_tags', $item);
		}
		
		
		
		//$unique = array_unique($tagcloud);
		//var_dump($unique);
	}
}

