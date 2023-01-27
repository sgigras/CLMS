<?php defined('BASEPATH') or exit('No direct script access allowed');



class Dashboard extends My_Controller
{

	public function __construct()
	{

		parent::__construct();

		auth_check(); // check login auth

		$this->rbac->check_module_access();
		$this->load->model('admin/auth_model', 'auth_model');
		$this->load->library(array('Cronslib/Smslib', 'Ats/atsuser'));

		if ($this->uri->segment(3) != '')
			$this->rbac->check_operation_access();

		$this->load->model('admin/dashboard_model', 'dashboard_model');
	
	}

	//--------------------------------------------------------------------------

	public function index()
	{

		$data['title'] = 'Dashboard';

		$this->load->view('admin/includes/_header', $data);

		$this->load->view('admin/dashboard/general');

		$this->load->view('admin/includes/_footer');
	}

	//--------------------------------------------------------------------------

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



	//--------------------------------------------------------------------------

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



	//--------------------------------------------------------------------------

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

				print_r($data);
				die();

		}





		// 		$this->session->set_flashdata('username', $this->input->get('username'));
		// 		$this->session->set_flashdata('dob', $this->input->post('dob'));
		// 		$this->session->set_flashdata('pin', $this->input->post('pin'));


		// $username = $result['username'];
		// print_r($username);
		// die();


		// $date_of_birth = $result['date_of_birth'];
		// $this->db->select('rank, present_appoitment, status, location_name, district_name, state_name');
		// $this->db->from('bsf_hrms_data');
		// $this->db->where('irla', $irlano);
		// $this->db->where('date_of_birth', $date_of_birth);
		// $query = $this->db->get();

		// print_r($query);
		// die();

		// $result = $query->result_array();

		


		// // $this->db->close();
		// return $result;


		// $db = $this->db;
		// $irla_no = (int)$data['irlano'];
		// $username = $data['irlano'];
		// $date_of_birth = $data['dob'];
		// $this->db->from('ci_admin');
		// $this->db->join('ci_admin_roles', 'ci_admin_roles.admin_role_id = ci_admin.admin_role_id');
		// $this->db->where('ci_admin.username', $irla_no);
		// $this->db->where('ci_admin.date_of_birth', $data['dob']);
		// $query = $this->db->get();
		// print_r($query);
		// die();



		// $data = $this->session->userdata('admin_id');


		// $additionaldata = $this->auth_model->fetchDetailsFromHrms($data);
		// print_r($additionaldata);
		// die();
		// $username = (int)$data['irlano'];

		// 	print_r($username);
		// 	die();



		// 	$additionaldata = $this->auth_model->fetchDetailsFromHrms($result);

		// 				// print_r($additionaldata[0]['rank']);die();

		// 				$token = $this->getToken(50);

		// 				$admin_data = array(
		// 					'admin_id' => $result['admin_id'],
		// 					'entity_id' => (isset($result['entity_id'])) ? $result['entity_id'] : '',
		// 					'username' => $result['username'],
		// 					'rank' => (isset($additionaldata[0]['rank'])) ? $additionaldata[0]['rank'] : 'N.A',
		// 					'mobile_no' => $result['mobile_no'],
		// 					'full_name' => $result['firstname'],
		// 					'admin_role_id' => $result['admin_role_id'],
		// 					'admin_role' => $result['admin_role_title'],
		// 					'is_supper' => $result['is_supper'],
		// 					'transporter_id' => $result['transporter_id'],
		// 					'plant_id' => $result['plant_id'],
		// 					'is_admin_login' => TRUE,
		// 					'profile_picture' => $result['image'],
		// 					'token' => $token,
		// 				);
		// 				print_r($admin_data);
		// 				die();
		// 				$this->session->set_userdata($admin_data);
				

		// }




	// public function getToken($length){
	// 	$token = "";
	// 	$codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	// 	$codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
	// 	$codeAlphabet.= "0123456789";
	//   $max = strlen($codeAlphabet); // edited
	
	//   for ($i=0; $i < $length; $i++) {
	// 	$token .= $codeAlphabet[random_int(0, $max-1)];
	//   }
	
	//   return $token;
	// }


}