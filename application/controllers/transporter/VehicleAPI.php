<!-- Author:Hriday Mourya
Subject:Vehicle Registration API
Date:01-09-21 -->

<?php defined('BASEPATH') OR exit('No direct script access allowed');

class VehicleAPI extends MY_Controller {

	public function __construct(){

		parent::__construct();
		 auth_check(); // check login auth
		 $this->rbac->check_module_access();
		 $this->load->model('admin/Vehicle_model', 'vehicle');
		 $this->load->model('admin/Activity_model', 'activity_model');
		 $this->load->helper(array('form', 'url'));
		}


		public function index(){
			$plant_id= $this->session->userdata('plant_id');
			$data['transporter_plants']=$this->vehicle->get_transporter_plants($plant_id);
			$this->load->view('admin/includes/_header');
			$this->load->view('admin/transporter/vehicle_add',$data);
			$this->load->view('admin/includes/_footer');
		}

		public function vehicle_list()
		{

			$trans_id= $this->session->userdata('transporter_id');				   					   
			$data['vehicle_data'] = $this->vehicle->get_all_vehicles($trans_id);
			$this->load->view('admin/includes/_header');
			$this->load->view('admin/transporter/vehicle_list',$data);
			$this->load->view('admin/includes/_footer');
		}

		function change_status(){

			$this->rbac->check_operation_access(); // check opration permission
			$this->vehicle->change_status();
		}

		function edit($id=""){

		$this->rbac->check_operation_access(); // check opration permission
		$plant_id= $this->session->userdata('plant_id');
		$data['transporter_plants']=$this->vehicle->get_transporter_plants($plant_id);

		if($this->input->post('submit')){
			$this->form_validation->set_rules('vehicle_no', 'Vehicle No', 'trim|required');
			$this->form_validation->set_rules('vehicle_type', 'Vehicle Type', 'trim|required');
			$this->form_validation->set_rules('plant', 'Plant', 'trim|required');
			$this->form_validation->set_rules('capacity_in_mt', 'Capacity IN MT', 'trim|required');
			$this->form_validation->set_rules('box_capacity', 'Box Capacity', 'trim|required');
			$this->form_validation->set_rules('insurance_expiry_date', 'Insurance EX Date', 'trim|required');
			$this->form_validation->set_rules('puc_expiry_date', 'PUC EX Date', 'trim|required');
			$this->form_validation->set_rules('r/c_expiry_date', 'R/C EX Date', 'trim|required');

			if ($this->form_validation->run() == FALSE) {
				$data = array(
					'errors' => validation_errors()
				);
				$this->session->set_flashdata('errors', $data['errors']);
				redirect(base_url('transporter/VehicleAPI/edit/'.$id),'refresh');
			}
			else{
				$Vehicle_data = array(
					'vehicleno'=>$this->input->post('vehicle_no'),
					'transporterid'=>$this->session->userdata('transporter_id'),
					'vehicle_type' => $this->input->post('vehicle_type'),
					'plant_id' => $this->input->post('plant'),
					'capacity' => $this->input->post('capacity_in_mt'),
					'box_count' => $this->input->post('box_capacity'),
					'expiry_insurance' => $this->input->post('insurance_expiry_date'),
					'expiry_puc' => $this->input->post('puc_expiry_date'),
					'expiry_rto' => $this->input->post('r/c_expiry_date'),
					'modifiedbyid' =>$this->session->userdata('admin_id'),
					'isactive' => 1,
					'modification_time'=>date('Y-m-d : h:m:s')


				);
				$Vehicle_data = $this->security->xss_clean($Vehicle_data);
				$Vehicle_result = $this->vehicle->edit_vehicle($Vehicle_data,$id);
				if($Vehicle_result){
					$this->session->set_flashdata('success', 'Vehicle has been updated successfully!');
					redirect(base_url('transporter/VehicleAPI/vehicle_list'));
				}

			}
		}
		elseif($id==""){
			redirect('admin/admin');
		}
		else{
			$data['vehicle'] = $this->vehicle->get_vehicle_by_id($id);
			
			$this->load->view('admin/includes/_header');
			$this->load->view('admin/transporter/vehicle_edit', $data);
			$this->load->view('admin/includes/_footer');
		}		
	}



	public function addvehicles(){

		$this->rbac->check_operation_access(); // check opration permission
		$plant_id= $this->session->userdata('plant_id');
		$data['transporter_plants']=$this->vehicle->get_transporter_plants($plant_id);

		if($this->input->post('submit')){

			$this->form_validation->set_rules('vehicle_no', 'Vehicle No', 'trim|required');
			$this->form_validation->set_rules('vehicle_type', 'Vehicle Type', 'trim|required');
			$this->form_validation->set_rules('plant', 'Plant', 'trim|required');
			$this->form_validation->set_rules('capacity_in_mt', 'Capacity IN MT', 'trim|required');
			$this->form_validation->set_rules('box_capacity', 'Box Capacity', 'trim|required');
			$this->form_validation->set_rules('insurance_expiry_date', 'Insurance EX Date', 'trim|required');
			$this->form_validation->set_rules('puc_expiry_date', 'PUC EX Date', 'trim|required');
			$this->form_validation->set_rules('r/c_expiry_date', 'R/C EX Date', 'trim|required');
			
			if ($this->form_validation->run() == FALSE) {
				$data = array(
					'errors' => validation_errors()
				);
				$this->session->set_flashdata('form_data', $_POST);
				$this->session->set_flashdata('errors', $data['errors']);
				redirect(base_url('transporter/VehicleAPI/addvehicles'),'refresh');
			}
				else{

					
					$Vehicle_data = array(
						'vehicleno'=>$this->input->post('vehicle_no'),
						'transporterid'=>$this->session->userdata('transporter_id'),
						'vehicle_type' => $this->input->post('vehicle_type'),
						'plant_id' => $this->input->post('plant'),
						'capacity' => $this->input->post('capacity_in_mt'),
						'box_count' => $this->input->post('box_capacity'),
						'expiry_insurance' => $this->input->post('insurance_expiry_date'),
						'expiry_puc' => $this->input->post('puc_expiry_date'),
						'expiry_rto' => $this->input->post('r/c_expiry_date'),
						'createdbyid' =>$this->session->userdata('admin_id'),
						'isactive' => 1,
						'creation_time'=>date('Y-m-d : h:m:s')


					);
					$Vehicle_data = $this->security->xss_clean($Vehicle_data);
					$Vehicle_result = $this->vehicle->add_vehicle($Vehicle_data);
					if($Vehicle_result){
						$this->session->set_flashdata('success', 'Vehicle has been added successfully!');
						redirect(base_url('transporter/VehicleAPI/vehicle_list'));
					}

				}
			
		}
		else
		{
			$this->load->view('admin/includes/_header');
			$this->load->view('admin/transporter/vehicle_add',$data);
			$this->load->view('admin/includes/_footer');
		}

	}

}

