<?php defined('BASEPATH') or exit('No direct script access allowed');



class Home extends My_Controller
{



	public function __construct()
	{

		parent::__construct();

		auth_check(); // check login auth

		$this->rbac->check_module_access();

		if ($this->uri->segment(3) != '')
			$this->rbac->check_operation_access();

		$this->load->model('admin/dashboard_model', 'dashboard_model');
	}


	//--------------------------------------------------------------------------

	public function index()
	{

		$data['title'] = 'Dashboard';

		// $data['title'] = 'Active Users';

		// $data['title'] = 'Dashboard';

		$userid = $this->session->userdata('admin_id');
		// print_r($_SESSION);

		$data['user_details'] = $this->dashboard_model->get_user_used_quota_liqour_deatils($userid);

		// $data['user_details'] = $this->dashboard_model->get_liquor_user_used_quota($userid);


		$this->load->view('admin/includes/_header');

		$this->load->view('admin/dashboard/index2', $data);

		$this->load->view('admin/includes/_footer');
	}
}
