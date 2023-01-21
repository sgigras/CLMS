<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Config_MasterAPI extends MY_Controller {

	public function __construct(){

		parent::__construct();
		 auth_check(); // check login auth
		 $this->rbac->check_module_access();
		 $this->load->model('configvariable/Config_model', 'configvar');
		 $this->load->helper(array('form', 'url'));

		}


		public function index(){
			$this->load->view('admin/includes/_header');
			$this->load->view('configvariable/config');
			$this->load->view('admin/includes/_footer');
		}	


		public function add(){



			if($this->input->post('submit')){

				$this->form_validation->set_rules('variable_name', 'Variable Name', 'trim|is_unique[config_variable.variable]|required');
				$this->form_validation->set_rules('variable_value', 'Variable Value', 'trim|required');
				$this->form_validation->set_rules('description', 'Description', 'trim|required');


				if ($this->form_validation->run() == FALSE) {
					$data = array(
						'errors' => validation_errors()
					);
					$this->session->set_flashdata('form_data', $_POST);
					$this->session->set_flashdata('errors', $data['errors']);
					redirect(base_url('configvarible/Config_MasterAPI/add'),'refresh');
				}
				else{

					
					$Config_variable = array(
						'variable'=>$this->input->post('variable_name'),
						'value'=>$this->input->post('variable_value'),
						'description'=>$this->input->post('description'),
						'status' => $this->input->post('active'),
						'insert_time'=>date('Y-m-d : h:m:s')


					);

					$Config_variable = $this->security->xss_clean($Config_variable);
					$result = $this->configvar->add($Config_variable);
					if($result){
						$this->session->set_flashdata('success', 'Config Variable has been added successfully!');
						redirect(base_url('configvarible/Config_MasterAPI'));
					}

				}

			}
			else
			{
				$this->load->view('admin/includes/_header');
				$this->load->view('configvariable/addconfig_variables');
				$this->load->view('admin/includes/_footer');
			}

		}

		public function update(){

			if($this->input->post('submit')){

				$this->form_validation->set_rules('variable_name', 'Variable Name', 'trim|required');
				$this->form_validation->set_rules('variable_value', 'Variable Value', 'trim|required');
                $this->form_validation->set_rules('description', 'Description', 'trim|required');


				if ($this->form_validation->run() == FALSE) {
					$data = array(
						'errors' => validation_errors()
					);
					$this->session->set_flashdata('form_data', $_POST);
					$this->session->set_flashdata('errors', $data['errors']);
					redirect(base_url('configvarible/Config_MasterAPI/update'),'refresh');
				}
				else{

					$variableid=$this->input->post('select_variable');
					$variable=$this->input->post('variable_name');
					$description=$this->input->post('description');
					$value=$this->input->post('variable_value');
					$status=$this->input->post('active');
					$update_time=date('Y-m-d : h:m:s');

					$Config_variable = $this->security->xss_clean($variable,$value,$description,$status,$update_time,$variableid);
					$result = $this->configvar->update($variable,$value,$description,$status,$update_time,$variableid);
					if($result){
						$this->session->set_flashdata('success', 'Config Variable has been Updated successfully!');
						redirect(base_url('configvarible/Config_MasterAPI'));
					}

				}

			}
			else
			{
				
			    $data['variables'] = $this->configvar->fatchvariables();
				$this->load->view('admin/includes/_header');
				$this->load->view('configvariable/updateconfig_variables',$data);
				$this->load->view('admin/includes/_footer');
			}

		}

		public function getVariableDetails() {
			$selected_value = $this->input->post('selected_value');
			$result = $this->configvar->getVariableDetails($selected_value);
			echo json_encode($result);
		}

		



	}