<?php defined('BASEPATH') OR exit('No direct script access allowed');

class TransporterSupervisorAPI extends MY_Controller {

	public function __construct(){

		parent::__construct();
		 auth_check(); // check login auth
		 $this->rbac->check_module_access();

		 $this->load->model('admin/TransporterSupervisor_model', 'transportersupervisor');
		 $this->load->model('admin/Activity_model', 'activity_model');
		 $this->load->helper(array('form', 'url'));


		}

		public function index(){
					
			$this->load->view('admin/includes/_header');
			$this->load->view('admin/transporter/trans_supervisor_list');
			$this->load->view('admin/includes/_footer');
		}


		function change_status(){

			$this->rbac->check_operation_access(); // check opration permission
	
			$this->transporter->change_status();
		}

		public function datatable_json(){
			$trans_id= $this->session->userdata('transporter_id');				   					   
			$records['data'] = $this->transportersupervisor->get_all_trans_supervisor($trans_id);
			$data = array();

			$i=0;
			foreach ($records['data']   as $row) 
			{  
				$status = ($row['is_active'] == 1)? 'checked': '';
			
				$data[]= array(
					++$i,
					'<h4 class="m0 mb5">'.$row['firstname'].' '.$row['lastname'].'</h4>',
					$row['username'],
					$row['email'],
					$row['mobile_no'],
					date_time($row['created_at']),	
				// '<span class="btn btn-success">'.$verify.'</span>',	
					'<input class="tgl_checkbox tgl-ios" 
					data-id="'.$row['admin_id'].'" 
					id="cb_'.$row['admin_id'].'"
					type="checkbox"  
					'.$status.'><label for="cb_'.$row['admin_id'].'"></label>',		

					'<a title="Edit" class="update btn btn-sm btn-warning" href="'.base_url('transporter/TransporterSupervisorAPI/edit/'.$row['admin_id']).'"> <i class="fa fa-pencil-square-o"></i></a>'
				);
			}
			$records['data']=$data;
			echo json_encode($records);						   
		}

	public function add(){

			$this->rbac->check_operation_access(); // check opration permission

			$plant_id= $this->session->userdata('plant_id');
			$data['transporter_plants']=$this->transportersupervisor->get_transporter_plants($plant_id);
			// $data['plants']=$this->admin->get_plant();
	
			if($this->input->post('submit'))
			{
					// $this->form_validation->set_rules('username', 'Username', 'trim|alpha_numeric|is_unique[ci_admin.username]|required');
				    $this->form_validation->set_rules('username', 'Username', 'trim|is_unique[ci_admin.username]|required');
					$this->form_validation->set_rules('firstname', 'Firstname', 'trim|required');
					$this->form_validation->set_rules('lastname', 'Lastname', 'trim|required');
					$this->form_validation->set_rules('email', 'Email', 'trim|valid_email|required');
					$this->form_validation->set_rules('mobile_no', 'Mobile Number', 'trim|required');
					$this->form_validation->set_rules('plant', 'Plant', 'trim|required');
					if ($this->form_validation->run() == FALSE) {
						$data = array(
							'errors' => validation_errors()
						);
						
						$this->session->set_flashdata('errors', $data['errors']);
						$this->session->set_flashdata('form_data',$_POST);
						redirect(base_url('transporter/TransporterSupervisorAPI/add'),'refresh');
					}
					else{
						 // Normal password string to send on mail
						$password=$this->randomPassword();
						$supervisor_data = array(
							'admin_role_id' => 59, //Supervisor role id
							'plant_id' => $this->input->post('plant'),
							'transporter_id'=> $this->session->userdata('transporter_id'),
							'username' => $this->input->post('username'),
							'firstname' => $this->input->post('firstname'),
							'lastname' => $this->input->post('lastname'),
							'email' => $this->input->post('email'),
							'mobile_no' => $this->input->post('mobile_no'),
							'password' =>  password_hash($password, PASSWORD_BCRYPT),
							'is_active' => 1,
							'is_verify' => 0,
							'token' => md5(rand(0,1000)),
							'created_at' => date('Y-m-d : h:m:s'),
							'updated_at' => date('Y-m-d : h:m:s'),
						);
						$supervisor_data = $this->security->xss_clean($supervisor_data);
						$result = $this->transportersupervisor->add_supervisor($supervisor_data);
	
	
						if($result){
	
							//sending welcome email to user
						$this->load->helper('email_helper');
	
						$mail_data = array(
							'fullname' => $supervisor_data['firstname'].' '.$supervisor_data['lastname'],
							'verification_link' => base_url('admin/auth/verify/').$supervisor_data['token'],
							'username'=> $supervisor_data['username'],
							'password'=>$password
						);
	
						$to = $supervisor_data['email'];
	
						$email = $this->mailer->mail_template($to,'email-verification',$mail_data);
	
						if($email){
							// Activity Log 
							$this->activity_model->add_log(4);
	
							$this->session->set_flashdata('success', 'Supervisor has been added successfully!');
							redirect(base_url('transporter/TransporterSupervisorAPI'));
						}	
						else{
							echo 'Email Error';
						}
						}
					}
			}
			else
				{
					$this->load->view('admin/includes/_header');
					$this->load->view('admin/transporter/trans_supervisor_add',$data);
					$this->load->view('admin/includes/_footer');
				}
		}

		function randomPassword() {
			$alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
			$pass = array(); //remember to declare $pass as an array
			$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
			for ($i = 0; $i < 8; $i++) {
				$n = rand(0, $alphaLength);
				$pass[] = $alphabet[$n];
			}
			return implode($pass); //turn the array into a string
		}

	function edit($id=""){

		$this->rbac->check_operation_access(); // check opration permission
		$plant_id= $this->session->userdata('plant_id');
		$data['transporter_plants']=$this->transportersupervisor->get_transporter_plants($plant_id);

		if($this->input->post('submit')){
				$this->form_validation->set_rules('username', 'Username', 'trim|required');
				$this->form_validation->set_rules('firstname', 'Firstname', 'trim|required');
				$this->form_validation->set_rules('lastname', 'Lastname', 'trim|required');
				$this->form_validation->set_rules('email', 'Email', 'trim|valid_email|required');
				$this->form_validation->set_rules('mobile_no', 'Mobile Number', 'trim|required');
				$this->form_validation->set_rules('plant', 'Plant', 'trim|required');
			if ($this->form_validation->run() == FALSE) {
				$data = array(
					'errors' => validation_errors()
				);
				$this->session->set_flashdata('errors', $data['errors']);
				redirect(base_url('transporter/TransporterSupervisorAPI/edit/'.$id),'refresh');
			}
			else{
			
				$supervisor_data = array(
					'username'=>$this->input->post('username'),
					'firstname' => $this->input->post('firstname'),
					'lastname' => $this->input->post('lastname'),
					'email' => $this->input->post('email'),
					'mobile_no' => $this->input->post('mobile_no'),
					'plant_id' => $this->input->post('plant'),
					'updated_at'=>date('Y-m-d : h:m:s')
				);

				$supervisor_data = $this->security->xss_clean($supervisor_data);
				$result = $this->transportersupervisor->edit_supervisor($supervisor_data, $id);

				if($result){
					// Activity Log 
					$this->activity_model->add_log(5);

					$this->session->set_flashdata('success', 'Supervisor has been updated successfully!');
					redirect(base_url('transporter/TransporterSupervisorAPI'));
				}
			}
		}
		elseif($id==""){
			redirect('transporter/TransporterSupervisorAPI');
		}
		else{
			$data['supervisor'] = $this->transportersupervisor->get_supervisor_by_id($id);
			
			$this->load->view('admin/includes/_header');
			$this->load->view('admin/transporter/trans_supervisor_edit', $data);
			$this->load->view('admin/includes/_footer');
		}		
	}

}