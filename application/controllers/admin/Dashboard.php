<?php defined('BASEPATH') or exit('No direct script access allowed');
class Dashboard extends My_Controller
{
	public function __construct()
	{
		parent::__construct();
		auth_check();
		$this->rbac->check_module_access();
		$this->load->model('admin/auth_model', 'auth_model');
		$this->load->library(array('Cronslib/Smslib', 'Ats/atsuser'));
		if ($this->uri->segment(3) != '')
			$this->rbac->check_operation_access();
		$this->load->model('admin/dashboard_model', 'dashboard_model');
	}
	public function index()
	{
		$data['title'] = 'Dashboard';
		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/dashboard/general');
		$this->load->view('admin/includes/_footer');
	}
	public function index_1()
	{
		$data['all_users'] = $this->dashboard_model->get_all_users();
		$data['active_users'] = $this->dashboard_model->get_active_users();
		$data['deactive_users'] = $this->dashboard_model->get_deactive_users();
		
		$data['title'] = 'Dashboard';
		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/dashboard/index', $data);
		$this->load->view('admin/includes/_footer');
	}
	public function index_2()
	{
		$data['title'] = 'Dashboard';
		$userid = $this->session->userdata('admin_id');
		$data['user_details'] = $this->dashboard_model->get_user_used_quota_liqour_deatils($userid);
		$data["user_data"] = $this->dashboard_model->get_userquota();
		$this->load->view('admin/includes/_header');
		$this->load->view('admin/dashboard/index2', $data);
		$this->load->view('admin/includes/_footer');
	}
	public function index_3()
	{
		$data['title'] = 'Dashboard';
		$this->load->view('admin/includes/_header');
		$this->load->view('admin/dashboard/index3');
		$this->load->view('admin/includes/_footer');
	}
	public function loginsession($data)
	{	
		$this->session->set_flashdata('error', $data['messages']);
		$this->session->set_flashdata('irlano', $this->input->post('irlano'));
		$this->session->set_flashdata('dob', $this->input->post('dob'));
		$this->session->set_flashdata('pin', $this->input->post('pin'));

		$data = array(
			'irlano' => $this->input->post('irlano'),
			'dob' => $this->input->post('dob'),
			'pin' => $this->input->post('pin')
		);
	}
}