<?php defined('BASEPATH') OR exit('No direct script access allowed');

class RequestVehicleAPI extends MY_Controller {

	public function __construct(){

		parent::__construct();
		 auth_check(); // check login auth
		 $this->rbac->check_module_access();

		 $this->load->model('admin/Request_Vehicle_model', 'request_vehicle');
		 $this->load->model('admin/Activity_model', 'activity_model');
		 $this->load->helper(array('form', 'url'));


		}


		public function index(){
			$data['request']=$this->request_vehicle->fetchRequest();
			$this->load->view('admin/includes/_header');
			$this->load->view('admin/request_vehicles/request_vehicles',$data);
			$this->load->view('admin/includes/_footer');
		}

		public function fetchDestination(){
			$searchterm = $this->input->get('q');
			$result = $this->request_vehicle->fetchDestination($searchterm);
			echo json_encode($result); 
		}

		public function fetchboxcount(){
			$plantid= $this->session->userdata('plant_id');
			$result = $this->request_vehicle->fetchboxcount($plantid);
			echo json_encode($result); 
		}

		public function fetchCity(){
			$searchterm = $this->input->get('q');
			$result = $this->request_vehicle->fetchCity($searchterm);
			echo json_encode($result); 
		}

		function change_status(){

			$this->rbac->check_operation_access(); // check opration permission
	
			$this->transporter->change_status();
		}

		public function existing_request(){
			$plantid= $this->session->userdata('plant_id');
			$userid=$this->session->userdata('admin_id');
			$result = $this->request_vehicle->existing_request($userid,$plantid);
			echo json_encode($result); 
		}

		public function updateBoxcount(){
			$box_count= $this->input->post('total_BoxCount');
			$id=$this->input->post('id');
			$result = $this->request_vehicle->updateBoxcount($id,$box_count);
			echo json_encode($result);
		}

			public function request(){
			$this->rbac->check_operation_access(); // check opration permission

				$request_data = array(
					'box_count'=>$this->input->post('boxcount'),
					'shipping_date' => $this->input->post('ship_date'),
					'location_id' => $this->input->post('dest'),
					'city_id' => $this->input->post('city'),
					'category_id' => $this->input->post('category'),
					'requested_by' =>$this->session->userdata('admin_id'),
					'plant_id' =>$this->session->userdata('plant_id')
				);
								
				$request_data = $this->security->xss_clean($request_data);
				$request_result = $this->request_vehicle->request($request_data);

				if($request_result){

						// Activity Log 
						$this->activity_model->add_log(4);
						echo json_encode($request_result);			
				}
			}

			public function urgentShipment(){
				// $this->rbac->check_operation_access(); // check opration permission
	
					$request_data = array(
						'box_count'=>$this->input->post('boxcount'),
						'shipping_date' => $this->input->post('ship_date'),
						'location_id' => $this->input->post('dest'),
						'city_id' => $this->input->post('city'),
						'category_id' => $this->input->post('category'),
						'reason_urgent_shipment' => $this->input->post('value'),
						'requested_by' =>$this->session->userdata('admin_id'),
						'plant_id' =>$this->session->userdata('plant_id')
					);
									
					$request_data = $this->security->xss_clean($request_data);
					$request_result = $this->request_vehicle->request($request_data);
	
					if($request_result){
	
							// Activity Log 
							$this->activity_model->add_log(4);
							echo json_encode($request_result);			
					}
			}

			public function vehicle_allotment_service() {
				$allotVehicle = $this->request_vehicle->allotVehicle();
			}

			public function cancel_request(){
				$req_id = $this->input->post('req_id');
				$veh_log_id = $this->input->post('veh_log_id');
				$remarks = $this->input->post('value');
				$vehicle_num = $this->input->post('vehicle_num');
				$result = $this->request_vehicle->cancel_request($req_id,$veh_log_id,$vehicle_num,$remarks);
				echo json_encode($result); 
			}


}