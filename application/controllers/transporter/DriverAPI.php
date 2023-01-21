<!-- Author:Ujwal Jain
Subject:Driver Registration API
Date:03-09-21 -->

<?php defined('BASEPATH') OR exit('No direct script access allowed');

class DriverAPI extends MY_Controller {

	public function __construct(){

		parent::__construct();
		 auth_check(); // check login auth
		 $this->rbac->check_module_access();
		 $this->load->model('admin/Driver_model', 'driver');
		 $this->load->model('admin/Activity_model', 'activity_model');
		 $this->load->helper(array('form', 'url'));
		}


		public function index(){
			$trans_id= $this->session->userdata('transporter_id');				   					   
			$data['driver_data'] = $this->driver->get_all_driver($trans_id);
			$this->load->view('admin/includes/_header');
			$this->load->view('admin/transporter/driver_list',$data);
			$this->load->view('admin/includes/_footer');
		}

		function change_status(){

			$this->rbac->check_operation_access(); // check opration permission
	
			$this->driver->change_status();
		}

		// public function edit($id=""){
		// 	$this->rbac->check_operation_access(); // check opration permission

		// 	if($this->input->post('submit')){

		// 		$this->form_validation->set_rules('driver_name', 'Driver Name', 'trim|required');
		// 		$this->form_validation->set_rules('driver_license_no', 'Driver Licence Number', 'trim|required');
		// 		$this->form_validation->set_rules('commercial_dl_expiry_date', 'Driving Licence Expiry Date', 'trim|required');
		// 		$this->form_validation->set_rules('mobile_no', 'Number', 'trim|required');
			
		// 		if ($this->form_validation->run() == FALSE) {
		// 			$data = array(
		// 				'errors' => validation_errors()
		// 			);
					
		// 			$this->session->set_flashdata('form_data', $_POST);
		// 			$this->session->set_flashdata('errors', $data['errors']);
		// 			redirect(base_url('transporter/DriverAPI/edit/'.$id),'refresh');
		// 		}
		// 		else if($this->input->post('hiddenupload')==1){
		// 			$upload_path= './uploads/driver/';
		// 			$dl_no=  $this->input->post('driver_license_no');
		// 			$upload_path= $upload_path.$dl_no.'/';
		// 			if (!is_dir($upload_path)) {
		// 				mkdir($upload_path, 0777, true);
		// 				chmod($upload_path, 0775);
		// 			}
		// 			$config['upload_path']          = $upload_path;
        //         	$config['allowed_types']        = 'jpg|png|jpeg';
		// 			$this->load->library('upload', $config);
		// 			if ( ! $this->upload->do_upload('upload_dl_photo'))
        //         	{
        //                 $data = array('errors' => $this->upload->display_errors());
        //                 $this->session->set_flashdata('form_data', $_POST);
		// 				$this->session->set_flashdata('errors', $data['errors']);
		// 				redirect(base_url('transporter/DriverAPI/edit/'.$id),'refresh');
        //         	}else{
		// 				$upload_array=array('upload_data' => $this->upload->data());
		// 				// echo "<pre>";
		// 				// print_r($upload_path.$upload_array['upload_data']['file_name']); die();
		// 				$image_path=$upload_path.$upload_array['upload_data']['file_name'];
		// 				$driver_data = array(
		// 					'drivername' => $this->input->post('driver_name'),
		// 					'mobileno' => $this->input->post('mobile_no'),
		// 					'dl_no' => $this->input->post('driver_license_no'),
		// 					'expiry_dl' => $this->input->post('commercial_dl_expiry_date'),
		// 					'aadhar_no' => $this->input->post('aadhar_no') ,
		// 					'img_driver_licence_path'=> $image_path,
		// 					'modifiedbyid' =>$this->session->userdata('admin_id'),
		// 					'is_active' => 1,
		// 					'modification_time'=>date('Y-m-d : h:m:s')
		// 				);
		// 				$driver_admin_data=array(
		// 					'admin_role_id'=>60, //Role id of Driver
		// 					'username' => $this->input->post('mobile_no'),
		// 					'firstname' => $this->input->post('driver_name'),
		// 					'mobile_no' => $this->input->post('mobile_no'),
		// 					'password' => password_hash($this->input->post('mobile_no'), PASSWORD_BCRYPT),
		// 					'updated_at' => date('Y-m-d : h:m:s'),
		// 				);
					
		// 				$driver_data = $this->security->xss_clean($driver_data);
		// 				$driver_admin_data = $this->security->xss_clean($driver_admin_data);

		// 				$driver_result = $this->driver->update_driver($driver_data,$driver_admin_data,$id);

		// 				if($driver_result){
		// 					$this->session->set_flashdata('success', 'Driver has been Updated successfully!');
		// 					redirect(base_url('transporter/DriverAPI'));
		// 				}

		// 			}

		// 		}else{
		// 			$driver_data = array(
		// 				'drivername' => $this->input->post('driver_name'),
		// 				'mobileno' => $this->input->post('mobile_no'),
		// 				'dl_no' => $this->input->post('driver_license_no'),
		// 				'expiry_dl' => $this->input->post('commercial_dl_expiry_date'),
		// 				'aadhar_no' => $this->input->post('aadhar_no') ,
		// 				'modifiedbyid' =>$this->session->userdata('admin_id'),
		// 				'is_active' => 1,
		// 				'modification_time'=>date('Y-m-d : h:m:s')
		// 			);
		// 			$driver_admin_data=array(
		// 				'admin_role_id'=>60, //Role id of Driver
		// 				'username' => $this->input->post('mobile_no'),
		// 				'firstname' => $this->input->post('driver_name'),
		// 				'mobile_no' => $this->input->post('mobile_no'),
		// 				'password' => password_hash($this->input->post('mobile_no'), PASSWORD_BCRYPT),
		// 				'updated_at' => date('Y-m-d : h:m:s'),
		// 			);
				
		// 			$driver_data = $this->security->xss_clean($driver_data);
		// 			$driver_admin_data = $this->security->xss_clean($driver_admin_data);

		// 			$driver_result = $this->driver->update_driver($driver_data,$driver_admin_data,$id);

		// 			if($driver_result){
		// 				$this->session->set_flashdata('success', 'Driver has been Updated successfully!');
		// 				redirect(base_url('transporter/DriverAPI'));
		// 			}
		// 		}
		// 	}
		// 	else
		// 	{
		// 		$data['driver'] = $this->driver->get_driver_by_id($id);
		// 		$this->load->view('admin/includes/_header');
		// 		$this->load->view('admin/transporter/driver_edit',$data);
		// 		$this->load->view('admin/includes/_footer');
		// 	}
		// }

		public function edit($id=""){
			$this->rbac->check_operation_access(); // check opration permission

			if($this->input->post('submit')){

				$this->form_validation->set_rules('driver_name', 'Driver Name', 'trim|required');
				$this->form_validation->set_rules('mobile_no', 'Mobile Number', 'trim|required');
			
				if ($this->form_validation->run() == FALSE) {
					$data = array(
						'errors' => validation_errors()
					);
					
					$this->session->set_flashdata('form_data', $_POST);
					$this->session->set_flashdata('errors', $data['errors']);
					redirect(base_url('transporter/DriverAPI/edit/'.$id),'refresh');
				}
				else{
					$driver_data = array(
						'drivername' => $this->input->post('driver_name'),
						'mobileno' => $this->input->post('mobile_no'),
						'modifiedbyid' =>$this->session->userdata('admin_id'),
						'is_active' => 1,
						'modification_time'=>date('Y-m-d : h:m:s')
					);
					$driver_admin_data=array(
						'admin_role_id'=>60, //Role id of Driver
						'username' => $this->input->post('mobile_no'),
						'firstname' => $this->input->post('driver_name'),
						'mobile_no' => $this->input->post('mobile_no'),
						'password' => password_hash($this->input->post('mobile_no'), PASSWORD_BCRYPT),
						'updated_at' => date('Y-m-d : h:m:s'),
					);
				
					$driver_data = $this->security->xss_clean($driver_data);
					$driver_admin_data = $this->security->xss_clean($driver_admin_data);

					$driver_result = $this->driver->update_driver($driver_data,$driver_admin_data,$id);

					if($driver_result){
						$this->session->set_flashdata('success', 'Driver has been Updated successfully!');
						redirect(base_url('transporter/DriverAPI'));
					}
				}
			}
			else
			{
				$data['driver'] = $this->driver->get_driver_by_id($id);
				$this->load->view('admin/includes/_header');
				$this->load->view('admin/transporter/driver_edit',$data);
				$this->load->view('admin/includes/_footer');
			}
		}

		public function add(){
			$this->rbac->check_operation_access(); // check opration permission

			if($this->input->post('submit')){

				$this->form_validation->set_rules('driver_name', 'Driver Name', 'trim|required');
				$this->form_validation->set_rules('mobile_no', 'Mobile Number', 'trim|required');
			
				if ($this->form_validation->run() == FALSE) {
					$data = array(
						'errors' => validation_errors()
					);
					
					$this->session->set_flashdata('form_data', $_POST);
					$this->session->set_flashdata('errors', $data['errors']);
					redirect(base_url('transporter/DriverAPI/add'),'refresh');
				}
				else{
					$driver_data = array(
						'transporter_id'=>$this->session->userdata('transporter_id'),
						'drivername' => $this->input->post('driver_name'),
						'mobileno' => $this->input->post('mobile_no'),
						'createdby' =>$this->session->userdata('admin_id'),
						'is_active' => 1,
						'creation_time'=>date('Y-m-d : h:m:s')
					);
					$driver_admin_data=array(
						'admin_role_id'=>60, //Role id of Driver
						'transporter_id' =>$this->session->userdata('transporter_id'),
						'username' => $this->input->post('mobile_no'),
						'firstname' => $this->input->post('driver_name'),
						'mobile_no' => $this->input->post('mobile_no'),
						'password' => password_hash($this->input->post('mobile_no'), PASSWORD_BCRYPT),
						'is_active' => 1,
						'created_at' => date('Y-m-d : h:m:s'),
						'updated_at' => date('Y-m-d : h:m:s'),
					);
				
					$driver_data = $this->security->xss_clean($driver_data);
					$driver_admin_data = $this->security->xss_clean($driver_admin_data);

					$driver_result = $this->driver->add_driver($driver_data,$driver_admin_data);

					if($driver_result){
						$this->session->set_flashdata('success', 'Driver has been added successfully!');
						redirect(base_url('transporter/DriverAPI'));
					}

				}
			}
			else
			{
				$this->load->view('admin/includes/_header');
				$this->load->view('admin/transporter/driver_add');
				$this->load->view('admin/includes/_footer');
			}
		
		}

		// public function add(){
		// 	$this->rbac->check_operation_access(); // check opration permission

		// 	if($this->input->post('submit')){

		// 		$this->form_validation->set_rules('driver_name', 'Driver Name', 'trim|required');
		// 		$this->form_validation->set_rules('driver_license_no', 'Driver License Number', 'trim|required');
		// 		$this->form_validation->set_rules('commercial_dl_expiry_date', 'Driving License Expiry Date', 'trim|required');
		// 		$this->form_validation->set_rules('mobile_no', 'Number', 'trim|required');
			
		// 		if ($this->form_validation->run() == FALSE) {
		// 			$data = array(
		// 				'errors' => validation_errors()
		// 			);
					
		// 			$this->session->set_flashdata('form_data', $_POST);
		// 			$this->session->set_flashdata('errors', $data['errors']);
		// 			redirect(base_url('transporter/DriverAPI/add'),'refresh');
		// 		}
		// 		else{
		// 			$upload_path= './uploads/driver/';
		// 			$dl_no=  $this->input->post('driver_license_no');
		// 			$upload_path= $upload_path.$dl_no.'/';
		// 			if (!is_dir($upload_path)) {
		// 				mkdir($upload_path, 0777, true);
		// 				chmod($upload_path, 0775);
		// 			}
		// 			$config['upload_path']          = $upload_path;
        //         	$config['allowed_types']        = 'jpg|png|jpeg';
		// 			$this->load->library('upload', $config);
		// 			if ( ! $this->upload->do_upload('upload_dl_photo'))
        //         	{
        //                 $data = array('errors' => $this->upload->display_errors());
        //                 $this->session->set_flashdata('form_data', $_POST);
		// 				$this->session->set_flashdata('errors', $data['errors']);
		// 				redirect(base_url('transporter/DriverAPI/add'),'refresh');
        //         	}else{
		// 				$upload_array=array('upload_data' => $this->upload->data());
		// 				// echo "<pre>";
		// 				// print_r($upload_path.$upload_array['upload_data']['file_name']); die();
		// 				$image_path=$upload_path.$upload_array['upload_data']['file_name'];
		// 				$driver_data = array(
		// 					'transporter_id'=>$this->session->userdata('transporter_id'),
		// 					'drivername' => $this->input->post('driver_name'),
		// 					'mobileno' => $this->input->post('mobile_no'),
		// 					'dl_no' => $this->input->post('driver_license_no'),
		// 					'expiry_dl' => $this->input->post('commercial_dl_expiry_date'),
		// 					'aadhar_no' => $this->input->post('aadhar_no') ,
		// 					'img_driver_licence_path'=> $image_path,
		// 					'createdby' =>$this->session->userdata('admin_id'),
		// 					'is_active' => 1,
		// 					'creation_time'=>date('Y-m-d : h:m:s')
		// 				);
		// 				$driver_admin_data=array(
		// 					'admin_role_id'=>60, //Role id of Driver
		// 					'transporter_id' =>$this->session->userdata('transporter_id'),
		// 					'username' => $this->input->post('mobile_no'),
		// 					'firstname' => $this->input->post('driver_name'),
		// 					'mobile_no' => $this->input->post('mobile_no'),
		// 					'password' => password_hash($this->input->post('mobile_no'), PASSWORD_BCRYPT),
		// 					'is_active' => 1,
		// 					'created_at' => date('Y-m-d : h:m:s'),
		// 					'updated_at' => date('Y-m-d : h:m:s'),
		// 				);
					
		// 				$driver_data = $this->security->xss_clean($driver_data);
		// 				$driver_admin_data = $this->security->xss_clean($driver_admin_data);

		// 				$driver_result = $this->driver->add_driver($driver_data,$driver_admin_data);

		// 				if($driver_result){
		// 					$this->session->set_flashdata('success', 'Driver has been added successfully!');
		// 					redirect(base_url('transporter/DriverAPI'));
		// 				}

		// 			}

		// 		}
		// 	}
		// 	else
		// 	{
		// 		$this->load->view('admin/includes/_header');
		// 		$this->load->view('admin/transporter/driver_add');
		// 		$this->load->view('admin/includes/_footer');
		// 	}
		
		// }

		// public function datatable_json(){
		// 	$trans_id= $this->session->userdata('transporter_id');				   					   
		// 	$records['data'] = $this->driver->get_all_driver($trans_id);
		// 	$data = array();

		// 	$i=0;
		// 	foreach ($records['data']   as $row) 
		// 	{  
		// 		$status = ($row['is_active'] == 1)? 'checked': '';
			
		// 		$data[]= array(
		// 			++$i,
		// 			$row['drivername'],
		// 			$row['mobileno'],
		// 			$row['dl_no'],
		// 			$row['expiry_dl'],
		// 			$row['img_driver_licence_path'],
		// 			date_time($row['creation_time']),	
		// 		// '<span class="btn btn-success">'.$verify.'</span>',	
		// 			'<input class="tgl_checkbox tgl-ios" 
		// 			data-id="'.$row['driver_id'].'" 
		// 			id="cb_'.$row['driver_id'].'"
		// 			type="checkbox"  
		// 			'.$status.'><label for="cb_'.$row['driver_id'].'"></label>',		

		// 			'<a title="Edit" class="update btn btn-sm btn-warning" href="'.base_url('transporter/DriverAPI/edit/'.$row['driver_id']).'"> <i class="fa fa-pencil-square-o"></i></a>'
		// 		);
		// 	}
		// 	$records['data']=$data;
		// 	echo json_encode($records['data']);						   
		// }




		

}

