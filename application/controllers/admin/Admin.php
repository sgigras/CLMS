<?php defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends MY_Controller
{
	function __construct()
	{

		parent::__construct();
		auth_check(); // check login auth
		$this->rbac->check_module_access();

		$this->load->model('admin/admin_model', 'admin');
		$this->load->model('admin/Activity_model', 'activity_model');
	}

	//-----------------------------------------------------		
	function index($type = '')
	{
		$this->rbac->check_operation_access();
		$this->session->set_userdata('filter_type', $type);
		$this->session->set_userdata('filter_keyword', '');
		$this->session->set_userdata('filter_status', '');

		$data['admin_roles'] = $this->admin->get_admin_roles();

		$data['title'] = 'Admin List';

		$this->load->view('admin/includes/_header');
		$this->load->view('admin/admin/index', $data);
		$this->load->view('admin/includes/_footer');
	}

	//---------------------------------------------------------
	function filterdata()
	{

		$this->session->set_userdata('filter_type', $this->input->post('type'));
		$this->session->set_userdata('filter_status', $this->input->post('status'));
		$this->session->set_userdata('filter_keyword', $this->input->post('keyword'));
	}

	//--------------------------------------------------		
	function list_data()
	{

		$data['info'] = $this->admin->get_all();

		$this->load->view('admin/admin/list', $data);
	}

	//-----------------------------------------------------------
	function change_status()
	{

		$this->rbac->check_operation_access(); // check opration permission

		$this->admin->change_status();
	}

	//--------------------------------------------------
	function add()
	{

		$this->rbac->check_operation_access(); // check opration permission

		$data['admin_roles'] = $this->admin->get_admin_roles();
		// $data['plants']=$this->admin->get_plant();

		if ($this->input->post('submit')) {
			// $this->form_validation->set_rules('username', 'Username', 'trim|alpha_numeric|is_unique[ci_admin.username]|required');
			$this->form_validation->set_rules('username', 'Username', 'trim|is_unique[ci_admin.username]|required');
			$this->form_validation->set_rules('firstname', 'Firstname', 'trim|required');
			$this->form_validation->set_rules('lastname', 'Lastname', 'trim|required');
			$this->form_validation->set_rules('email', 'Email', 'trim|valid_email|required');
			// $this->form_validation->set_rules('mobile_no', 'Number', 'trim|required');
			//$this->form_validation->set_rules('password', 'Password', 'trim|required');
			$this->form_validation->set_rules('role', 'Role', 'trim|required');
			// $this->form_validation->set_rules('plant[]', 'Plant', 'trim|required');
			if ($this->form_validation->run() == FALSE) {
				$data = array(
					'errors' => validation_errors()
				);

				$this->session->set_flashdata('errors', $data['errors']);
				$this->session->set_flashdata('form_data', $_POST);
				redirect(base_url('admin/admin/add'), 'refresh');
			} else {
				$plant = $this->input->post('role');
				$plant_str = explode("-", $plant);
				// Normal password string to send on mail
				$password = $this->randomPassword();
				$data = array(
					'admin_role_id' => $plant_str[0],
					'plant_id' => $plant_str[1],
					'username' => $this->input->post('username'),
					'firstname' => $this->input->post('firstname'),
					'lastname' => $this->input->post('lastname'),
					'email' => $this->input->post('email'),
					'mobile_no' => $this->input->post('mobile_no'),
					'password' =>  password_hash($password, PASSWORD_BCRYPT),
					'is_active' => 1,
					'is_verify' => 0,
					'token' => md5(rand(0, 1000)),
					'created_at' => date('Y-m-d : h:m:s'),
					'updated_at' => date('Y-m-d : h:m:s'),
				);
				$data = $this->security->xss_clean($data);
				$result = $this->admin->add_admin($data);


				if ($result) {

					//sending welcome email to user
					$this->load->helper('email_helper');

					$mail_data = array(
						'fullname' => $data['firstname'] . ' ' . $data['lastname'],
						'verification_link' => base_url('admin/auth/verify/') . $data['token'],
						'username' => $data['username'],
						'password' => $password
					);

					$to = $data['email'];

					$email = $this->mailer->mail_template($to, 'email-verification', $mail_data);

					if ($email) {
						// Activity Log 
						$this->activity_model->add_log(4);

						$this->session->set_flashdata('success', 'User has been added successfully!');
						redirect(base_url('admin/admin'));
					} else {
						echo 'Email Error';
					}
				}
			}
		} else {
			$this->load->view('admin/includes/_header', $data);
			$this->load->view('admin/admin/add');
			$this->load->view('admin/includes/_footer');
		}
	}


	function randomPassword()
	{
		$alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
		$pass = array(); //remember to declare $pass as an array
		$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
		for ($i = 0; $i < 8; $i++) {
			$n = rand(0, $alphaLength);
			$pass[] = $alphabet[$n];
		}
		return implode($pass); //turn the array into a string
	}




	//--------------------------------------------------
	function edit($id = "")
	{

		$this->rbac->check_operation_access(); // check opration permission

		$data['admin_roles'] = $this->admin->get_admin_roles();

		if ($this->input->post('submit')) {
			$this->form_validation->set_rules('username', 'Username', 'trim|alpha_numeric|required');
			$this->form_validation->set_rules('firstname', 'Firstname', 'trim|required');
			$this->form_validation->set_rules('lastname', 'Lastname', 'trim|required');
			$this->form_validation->set_rules('email', 'Email', 'trim|valid_email|required');
			// $this->form_validation->set_rules('mobile_no', 'Number', 'trim|required');
			// $this->form_validation->set_rules('password', 'Password', 'trim|min_length[5]');
			$this->form_validation->set_rules('role', 'Role', 'trim|required');
			if ($this->form_validation->run() == FALSE) {
				$data = array(
					'errors' => validation_errors()
				);
				$this->session->set_flashdata('errors', $data['errors']);
				redirect(base_url('admin/admin/edit/' . $id), 'refresh');
			} else {
				$plant = $this->input->post('role');
				$plant_str = explode("-", $plant);
				$data = array(
					'admin_role_id' => $plant_str[0],
					'plant_id' => $plant_str[1],
					'username' => $this->input->post('username'),
					'firstname' => $this->input->post('firstname'),
					'lastname' => $this->input->post('lastname'),
					'email' => $this->input->post('email'),
					'mobile_no' => $this->input->post('mobile_no'),
					'is_active' => 1,
					'updated_at' => date('Y-m-d : h:m:s'),
				);

				if ($this->input->post('password') != '')
					$data['password'] = password_hash($this->input->post('password'), PASSWORD_BCRYPT);

				$data = $this->security->xss_clean($data);
				$result = $this->admin->edit_admin($data, $id);

				if ($result) {
					// Activity Log 
					$this->activity_model->add_log(5);

					$this->session->set_flashdata('success', 'Admin has been updated successfully!');
					redirect(base_url('admin/admin'));
				}
			}
		} elseif ($id == "") {
			redirect('admin/admin');
		} else {
			$data['admin'] = $this->admin->get_admin_by_id($id);

			$this->load->view('admin/includes/_header');
			$this->load->view('admin/admin/edit', $data);
			$this->load->view('admin/includes/_footer');
		}
	}

	//--------------------------------------------------
	function check_username($id = 0)
	{

		$this->db->from('admin');
		$this->db->where('username', $this->input->post('username'));
		$this->db->where('admin_id !=' . $id);
		$query = $this->db->get();
		if ($query->num_rows() > 0)
			echo 'false';
		else
			echo 'true';
	}

	//------------------------------------------------------------
	function delete($id = '')
	{

		$this->rbac->check_operation_access(); // check opration permission

		$this->admin->delete($id);

		// Activity Log 
		$this->activity_model->add_log(6);

		$this->session->set_flashdata('success', 'User has been Deleted Successfully.');
		redirect('admin/admin');
	}
}
