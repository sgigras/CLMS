<?php defined('BASEPATH') or exit('No direct script access allowed');

class SmsAPI extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('mailer');
		$this->load->library(array('Cronslib/Smslib'));
		$this->load->helper(array('Ats/curl'));
	}


	public function sendAllSms()
	{
		$this->smslib->fetchSMSToSend();
		$this->smslib->insertCronLog();
	}

	
}  // end class
