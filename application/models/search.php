<?php

class Search extends CI_Model {
	var $SALT = "02fa571776817419b4704560e295f7ac";

	function __construct()
	{
		parent::__construct();
	}
	
	public function encrypt($text) 
	{ 
		return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->SALT, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)))); 
	} 

	public function decrypt($text) 
	{ 
		return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->SALT, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))); 
	}
	
}

