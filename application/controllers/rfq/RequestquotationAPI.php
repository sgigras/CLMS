<?php defined('BASEPATH') OR exit('No direct script access allowed');

class RequestquotationAPI extends MY_Controller {

	public function __construct(){

		parent::__construct();
		 auth_check(); // check login auth
		 $this->rbac->check_module_access();
		 $this->load->model('admin/rfq_model', 'rfq');
		 $this->load->model('admin/Request_Vehicle_model', 'request_vehicle');
		 $this->load->model('admin/Activity_model', 'activity_model');
		 $this->load->helper(array('form', 'url'));
		}


		public function index(){
			$admin_role_id=$this->session->userdata('admin_role_id');
			$plant_id=$this->session->userdata('plant_id');


			switch ($admin_role_id) {
				// Load RFQ list for ACG corporate admin
				case "2":
				$data['rfq']=$this->rfq->get_all_rfq($plant_id);
				$this->load->view('admin/includes/_header');
				$this->load->view('admin/rfq/rfq_corporatewise_list',$data);
				$this->load->view('admin/includes/_footer');
				break;

				default:
				
				// Load RFQ list for particular plant (plant stored in session)
				$data['rfq']=$this->rfq->get_all_rfq($plant_id);
				$this->load->view('admin/includes/_header');
				$this->load->view('admin/rfq/rfq_plantwise_list',$data);
				$this->load->view('admin/includes/_footer');
			}
			
		}
		public function fetchCity(){
			$searchterm = $this->input->get('q');
			$result = $this->request_vehicle->fetchCity($searchterm);
			echo json_encode($result); 
		}

	

		public function plantwise_view($order_id){


			if($order_id !==''){

				$result['info'] = $this->rfq->getorder_no($order_id);
				$this->load->view('admin/includes/_header');
				$this->load->view('admin/rfq/rfq_plantwise_details',$result);
				$this->load->view('admin/includes/_footer');      
			}else{
				$plant_id=$this->session->userdata('plant_id');
				$data['rfq']=$this->rfq->get_all_rfq($plant_id);
				$this->load->view('admin/includes/_header');
				$this->load->view('admin/rfq/rfq_plantwise_list',$data);
				$this->load->view('admin/includes/_footer');
			}

		}

		public function corporate_view($order_id){

			$res=$this->rfq->cost_reduction_rate();
			$result['cost_reduction_rate']=$res[0]->value;


			if($order_id !==''){

				$result['info'] = $this->rfq->getorder_no($order_id);
				$this->load->view('admin/includes/_header');
				$this->load->view('admin/rfq/rfq_corporatewise_details',$result);
				$this->load->view('admin/includes/_footer');  

			}else{
				$plant_id=$this->session->userdata('plant_id');
				$data['rfq']=$this->rfq->get_all_rfq($plant_id);
				$this->load->view('admin/includes/_header');
				$this->load->view('admin/rfq/rfq_corporatewise_list',$data);
				$this->load->view('admin/includes/_footer');
			}

		}

		public function chec_plant(){
			$searchterm = $this->session->userdata('plant_id');
			$plant_array= explode(",",$searchterm);
			if(count($plant_array)==1){
				$result = $this->rfq->fetchCode($searchterm);
			}else{
				$result="0";
			}
			echo json_encode($result); 
		}


		public function push(){
			$order_id=$this->input->post('order_id');

			if($this->input->post('submit')){
				$this->form_validation->set_rules('bid_start_time', 'Bid Start Time', 'trim|required');
				$this->form_validation->set_rules('bid_close_time', 'Bid Close Time', 'trim|required');
				$this->form_validation->set_rules('cost_reduction', 'Cost Reduction Rate', 'trim|required');



				if ($this->form_validation->run() == FALSE) {
					$data = array(
						'errors' => validation_errors()
					);
					$this->session->set_flashdata('form_data', $_POST);
					$this->session->set_flashdata('errors', $data['errors']);
					redirect(base_url('rfq/RequestquotationAPI/corporate_view/'.$order_id),'refresh');
				}
				else{

					$bid_start_time=$this->input->post('bid_start_time');
					$bid_close_time=$this->input->post('bid_close_time');
					$cost_reduction_rate=$this->input->post('cost_reduction');
					$verified_by=$this->session->userdata('admin_id');

					$rfq_status=1;
					

					$Rfq_data = $this->security->xss_clean($bid_start_time,$bid_close_time,$cost_reduction_rate,$rfq_status,$verified_by,$order_id);
					$Rfq_result = $this->rfq->update_rfq($bid_start_time,$bid_close_time,$cost_reduction_rate,$rfq_status,$verified_by,$order_id);
					if($Rfq_result){
						$this->session->set_flashdata('success', 'RFQ Push to Gocomet Successfully');
						redirect(base_url('rfq/RequestquotationAPI/'));
					}

				}
			}

		}

		public function add(){
			$this->rbac->check_operation_access(); // check opration permission
			$plant_id= $this->session->userdata('plant_id');
			$plant_array= explode(",",$plant_id);
			if(count($plant_array)==1){
				$code_name = $this->rfq->fetchCode($plant_id);
				$data['code_name']=$code_name[0]->short_code_name;  
			}else{
				$data['code_name']="";
			}
			$data['transporter_plants']=$this->rfq->get_transporter_plants($plant_id);
			$data['truck_type']=$this->rfq->truck_type();

			if($this->input->post('submit')){

				$this->form_validation->set_rules('order_no', 'Order No', 'trim|required');
				$this->form_validation->set_rules('mode', 'Mode', 'trim|required');
				$this->form_validation->set_rules('shipper', 'Shipper', 'trim|required');
				$this->form_validation->set_rules('picup_date', 'Pick Up Date', 'trim|required');
				$this->form_validation->set_rules('origin_add', 'Origin Address', 'trim|required');
				$this->form_validation->set_rules('origin_zip_code', 'Origin Zip Code', 'trim|required');
				$this->form_validation->set_rules('no_of_trucks[]', 'No of Trucks', 'trim|required');
				$this->form_validation->set_rules('truck_type[]', 'Truck Type', 'trim|required');
				$this->form_validation->set_rules('city[]', 'City', 'trim|required');
				$this->form_validation->set_rules('destination_add[]', 'Destinaton Address', 'trim|required');
				$this->form_validation->set_rules('destination_zip_code[]', 'Destination Zip Code', 'trim|required');
				
				if ($this->form_validation->run() == FALSE) {
					$data = array(
						'errors' => validation_errors()
					);
					$this->session->set_flashdata('form_data', $_POST);
					$this->session->set_flashdata('errors', $data['errors']);
					redirect(base_url('rfq/RequestquotationAPI/add'),'refresh');
				}else{
					$no_of_trucks=$this->input->post('no_of_trucks[]');
					$no_trucks = implode(",",$no_of_trucks);

					$truck=$this->input->post('truck_type[]');
					$truck_type = implode(",",$truck);

					$des_address=$this->input->post('destination_add[]');
					$destination_add = implode("|",$des_address);

					$des_zip=$this->input->post('destination_zip_code[]');
					$destination_zip = implode(",",$des_zip);

					$des_city=$this->input->post('city[]');
					$destination_city = implode(",",$des_city);

					$Rfq_data=array(
						'order_no'=>$this->input->post('order_no'),
						'mode'=>$this->input->post('mode'),
						'shipper' => $this->input->post('shipper'),
						'picup_date' => $this->input->post('picup_date'),
						'origin_address' => $this->input->post('origin_add'),
						'origin_zip_code' => $this->input->post('origin_zip_code'),
						'no_of_trucks' => $no_trucks,
						'truck_type' => $truck_type,
						'destination_address' => $destination_add,
						'destination_zip_code' => $destination_zip,
						'destination_city' => $destination_city,
						'createdbyid' =>$this->session->userdata('admin_id'),
						'isactive' => 1



					);

					$Rfq_data=$this->security->xss_clean($Rfq_data);
					$Rfq_result = $this->rfq->add_rfq($Rfq_data);
					if($Rfq_result){

						$order_no=$Rfq_data['order_no'];
						$order_no=$this->security->xss_clean($order_no);
						$rfq_details = $this->rfq->fetch_data($order_no);
						
						if($rfq_details){
							$created_by=$rfq_details[0]['createdbyid'];
							$username=$rfq_details[0]['username'];
							$firstname=$rfq_details[0]['firstname'];
							$lastname=$rfq_details[0]['lastname'];
							$plantname=$rfq_details[0]['plant_name_for_gocomet'];
							$creation_time=$rfq_details[0]['creation_time'];
							$city=$rfq_details[0]['destination_city'];
						
                                   $email_list = implode(',',array_column($rfq_details,'email'));
							$this->load->helper('custom_email_helper');

							$mail_data = array(
								'shipper' => $plantname,
								'createdby'=> $firstname.' '.$lastname,
								'creation_time'=>$creation_time,
								'city'=>$city,
								'order_no' => $Rfq_data['order_no']
							);

							$to = $email_list;

							$email = $this->mailer->sent_email($to,'rfq-verification',$mail_data);

							if($email){
					

								$this->session->set_flashdata('success', 'Rfq Sent to Corporate For Verification');
								redirect(base_url('rfq/RequestquotationAPI/'));
							}	
							else{
								echo 'Email Error';
							}


						}else{
							echo 'Email Error';
						}
					}



				}


			}else{
				$this->load->view('admin/includes/_header');
				$this->load->view('admin/rfq/request_quotation',$data);
				$this->load->view('admin/includes/_footer');

			}



		}
	}

