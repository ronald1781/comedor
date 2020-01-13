<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class My_PHPMailer {
	
	public function My_PHPMailer()
	{
		require_once('phpmailer/class.phpmailer.php');
		require_once('phpmailer/class.smtp.php');
		//require_once('phpmailer/PHPMailerAutoload.php');
	}
        
        
        
}
?>