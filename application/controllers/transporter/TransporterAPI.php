<?php defined('BASEPATH') OR exit('No direct script access allowed');

class TransporterAPI extends MY_Controller {

	public function __construct(){

		parent::__construct();
		 auth_check(); // check login auth
		 $this->rbac->check_module_access();

		 $this->load->model('admin/Transporter_model', 'transporter');
		 $this->load->model('admin/Activity_model', 'activity_model');
		 $this->load->helper(array('form', 'url'));


		}


		public function index(){
			$data['plants']=$this->transporter->get_plant();
			$this->load->view('admin/includes/_header');
			$this->load->view('admin/transporter/addtransporter',$data);
			$this->load->view('admin/includes/_footer');
		}

		function change_status(){

			$this->rbac->check_operation_access(); // check opration permission
	
			$this->transporter->change_status();
		}

		public function transporter_list(){

			$data['info'] = $this->transporter->get_all_transporter();
			
			$this->load->view('admin/includes/_header');
			$this->load->view('admin/transporter/transporter_list',$data);
			$this->load->view('admin/includes/_footer');
		}

		public function datatable_json(){				   					   
			$records['data'] = $this->transporter->get_all_transporter();
			$data = array();

			$i=0;
			foreach ($records['data']   as $row) 
			{  
				$status = ($row['is_active'] == 1)? 'checked': '';
			// $verify = ($row['is_verify'] == 1)? 'Verified': 'Pending';
				$transporter_type = ($row['transporter_type'] == 1)? 'Reefer': (($row['transporter_type'] == 2)? 'Ambient': 'PTL');
				$data[]= array(
					++$i,
					$row['transporter_name'],
					$transporter_type,
					$row['contact_person'],
					$row['email_id'],
					$row['phone_number'],
					date_time($row['created_on']),	
				// '<span class="btn btn-success">'.$verify.'</span>',	
					'<input class="tgl_checkbox tgl-ios" 
					data-id="'.$row['id'].'" 
					id="cb_'.$row['id'].'"
					type="checkbox"  
					'.$status.'><label for="cb_'.$row['id'].'"></label>',		

					'<a title="Edit" class="update btn btn-sm btn-warning" href="'.base_url('transporter/TransporterAPI/edit/'.$row['id']).'"> <i class="fa fa-pencil-square-o"></i></a>'
				);
			}
			$records['data']=$data;
			echo json_encode($records);						   
		}

		public function add(){
		$this->rbac->check_operation_access(); // check opration permission
		$data['plants']=$this->transporter->get_plant();

		if($this->input->post('submit')){

			$this->form_validation->set_rules('transporter_type', 'Transporter Type', 'trim|required');
			$this->form_validation->set_rules('name', 'Name', 'trim|required');
			$this->form_validation->set_rules('contactperson', 'Contact Person', 'trim|required');
			$this->form_validation->set_rules('email', 'Email', 'trim|valid_email|required');
			$this->form_validation->set_rules('mobile_no', 'Number', 'trim|required');
			$this->form_validation->set_rules('plant[]', 'Plant', 'trim|required');
			$this->form_validation->set_rules('username', 'Username', 'trim|is_unique[ci_admin.username]|required');
			$this->form_validation->set_rules('password', 'Password', 'trim|required');
			
			if ($this->form_validation->run() == FALSE) {
				$data = array(
					'errors' => validation_errors()
				);
				$this->session->set_flashdata('form_data', $_POST);
				$this->session->set_flashdata('errors', $data['errors']);
				redirect(base_url('transporter/TransporterAPI/add'),'refresh');
			}
			else{
				$plant=$this->input->post('plant[]');
                $plant_str = implode(",",$plant);
				$transporter_data = array(
					'transporter_type'=>$this->input->post('transporter_type'),
					'transporter_name' => $this->input->post('name'),
					'contact_person' => $this->input->post('contactperson'),
					'email_id' => $this->input->post('email'),
					'phone_number' => $this->input->post('mobile_no'),
					'plant_id' =>$plant_str ,
					'createdby' =>$this->session->userdata('admin_id'),
					'is_active' => 1,
					'created_on'=>date('Y-m-d : h:m:s')


				);
				$password=$this->input->post('password');
				$trans_admmin_data=array(
					'admin_role_id'=>58, //Role id of transporter admin
					'plant_id' =>$plant_str,
					'username' => $this->input->post('username'),
					'firstname' => $this->input->post('contactperson'),
					'email' => $this->input->post('email'),
					'mobile_no' => $this->input->post('mobile_no'),
					'password' => password_hash($password, PASSWORD_BCRYPT),
					'is_active' => 1,
					'is_verify' => 0,
					'token' => md5(rand(0,1000)),
					'created_at' => date('Y-m-d : h:m:s'),
					'updated_at' => date('Y-m-d : h:m:s')

				);
				
				$transporter_data = $this->security->xss_clean($transporter_data);
				$trans_admmin_data = $this->security->xss_clean($trans_admmin_data);

				$transporter_result = $this->transporter->add_transporter($transporter_data,$trans_admmin_data);

				if($transporter_result){

						//sending welcome email to user
					$this->load->helper('email_helper');

					$mail_data = array(
						'fullname' => $transporter_data['contact_person'],
						'verification_link' => base_url('admin/auth/verify/').$trans_admmin_data['token'],
						'username'=> $trans_admmin_data['username'],
						'password'=>$password
					);

					$to = $trans_admmin_data['email'];

					$email = $this->mailer->mail_template($to,'email-verification',$mail_data);

					if($email){
						// Activity Log 
						$this->activity_model->add_log(4);

						$this->session->set_flashdata('success', 'User has been added successfully!');
						redirect(base_url('transporter/TransporterAPI/transporter_list'));
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
			$this->load->view('admin/transporter/addtransporter',$data);
			$this->load->view('admin/includes/_footer');
		}
		
	}

	function edit($id=""){

		$this->rbac->check_operation_access(); // check opration permission
		$data['plants']=$this->transporter->get_plant();

		if($this->input->post('submit')){
			$this->form_validation->set_rules('transporter_type', 'Transporter Type', 'trim|required');
			$this->form_validation->set_rules('name', 'Name', 'trim|required');
			$this->form_validation->set_rules('contactperson', 'Contact Person', 'trim|required');
			$this->form_validation->set_rules('email', 'Email', 'trim|required');
			$this->form_validation->set_rules('mobile_no', 'Number', 'trim|required');
			$this->form_validation->set_rules('plant[]', 'Plant', 'trim|required');
			if ($this->form_validation->run() == FALSE) {
				$data = array(
					'errors' => validation_errors()
				);
				$this->session->set_flashdata('errors', $data['errors']);
				redirect(base_url('transporter/TransporterAPI/edit/'.$id),'refresh');
			}
			else{
				$plant=$this->input->post('plant[]');
                $plant_str = implode(",",$plant);

				$transporter_data = array(
					'transporter_type'=>$this->input->post('transporter_type'),
					'transporter_name' => $this->input->post('name'),
					'contact_person' => $this->input->post('contactperson'),
					'email_id' => $this->input->post('email'),
					'phone_number' => $this->input->post('mobile_no'),
					'plant_id' =>$plant_str ,
					'modifiedby' =>$this->session->userdata('admin_id'),
					'modified_on'=>date('Y-m-d : h:m:s')
				);
				$transporter_admin_data = array(
					'firstname' => $this->input->post('contactperson'),
					'email' => $this->input->post('email'),
					'mobile_no' => $this->input->post('mobile_no'),
					'plant_id' =>$plant_str ,
					'updated_at' => date('Y-m-d : h:m:s')
				);

				$transporter_data = $this->security->xss_clean($transporter_data);
				$transporter_admin_data== $this->security->xss_clean($transporter_admin_data);
				$result = $this->transporter->edit_transporter($transporter_data, $transporter_admin_data, $id);

				if($result){
					// Activity Log 
					$this->activity_model->add_log(5);

					$this->session->set_flashdata('success', 'Transporter has been updated successfully!');
					redirect(base_url('transporter/TransporterAPI/transporter_list'));
				}
			}
		}
		elseif($id==""){
			redirect('admin/admin');
		}
		else{
			$data['transporter'] = $this->transporter->get_transporter_by_id($id);
			
			$this->load->view('admin/includes/_header');
			$this->load->view('admin/transporter/transporter_edit', $data);
			$this->load->view('admin/includes/_footer');
		}		
	}

}