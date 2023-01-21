<!-- //Author:Harish Manoharan
//Subject:Email API To Fetch List of Emails To Be Sent From Database
//Date:24-10-21 -->

<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Email_Con extends MY_Controller {

	public function __construct(){

		parent::__construct();
		
		$this->load->model('email_con/Email_model', 'mail');
		$this->load->helper(array('custom_email_helper'));

	}


	public function sendAllEmail()
	{
		$this->mail->fetchMailToSend();

	}


}