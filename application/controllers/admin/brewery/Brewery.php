<?php defined('BASEPATH') or exit('No direct script access allowed');

class Brewery extends MY_Controller
{
	function __construct()
	{

		parent::__construct();
		auth_check(); // check login auth
		$this->rbac->check_module_access();

		$this->load->model('admin/Brewery_model', 'brewery_model');
		$this->load->model('admin/Activity_model', 'activity_model');
	}

	//-----------------------------------------------------		
	function index()
	{


		$data['title'] = trans('brewery_master');;
		$data['records'] = $this->brewery_model->get_all();

		$this->load->view('admin/includes/_header');
		$this->load->view('admin/brewery/index', $data);
		$this->load->view('admin/includes/_footer');
	}

	function add()
	{

		$this->rbac->check_operation_access(); // check opration permission

		if ($this->input->post('submit')) {
			// print_r($_POST);die();
			$data = array('success' => false, 'messages' => array());
			$this->form_validation->set_rules('breweryname', 'Brewery Name', 'trim|required|min_length[3]|max_length[80]');
			$this->form_validation->set_rules('breweryaddress', 'Brewery Address', 'trim|required|min_length[3]|max_length[250]');
			$this->form_validation->set_rules('contactperson', 'Contact Person', 'trim|required|min_length[3]|max_length[100]');
			$this->form_validation->set_rules('mobilenumber', 'Mobile Number', 'trim|required|min_length[10]|max_length[10]');
			$this->form_validation->set_rules('emailaddress', 'Email Address', 'trim|required|valid_email|min_length[3]|max_length[580]');
			$this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

			if ($this->form_validation->run()) {
				$data['success'] = true;
				$this->brewery_model->insert();
			} else {
				foreach ($_POST as $key => $value) {
					$data['messages'][$key] = form_error($key);
				}
			}
			echo json_encode($data);
			return;
		}

		$data['title'] = trans('add_new_brewery');
		$data['stateslist'] = $this->brewery_model->getStates();
		$data['entities'] = $this->brewery_model->getentities();
		$this->load->view('admin/includes/_header');
		$this->load->view('admin/brewery/add', $data);
		$this->load->view('admin/includes/_footer');
	}

	//For select validation
	public function multipleselectstate()
	{
	
		// print_r($str);die();
		if (count($this->input->post('brewerystate')) == 1) {
			if ($this->input->post('brewerystate')[0] == '') {
				// echo 'false';
				$this->form_validation->set_message('multipleselectstate', 'Please Select Atleast One State.');
				// echo validation_errors();die();
				return FALSE;
			} else {
				// echo 'true';
				return TRUE;
			}
		} else {
			return TRUE;
		}
		
	}

	//For select validation
	public function multiple_selectentity()
	{
		print_r($this->input->post('brewerystate')[0]);
		if (empty($this->input->post('breweryentity')[0])) {
			$this->form_validation->set_message('multiple_selectentity', 'Please Select Atleast One Entity.');
			return FALSE;
		} else {
			return TRUE;
		}
	}



	function stateMapping()
	{


		$this->rbac->check_operation_access(); // check opration permission

		if ($this->input->post('submit')) {
			$data = array('success' => false, 'messages' => array());
			$this->form_validation->set_rules('breweryname', 'Brewery Name', 'trim|required|min_length[3]|max_length[80]');
			$this->form_validation->set_rules('breweryaddress', 'Brewery Address', 'trim|required|min_length[3]|max_length[250]');
			$this->form_validation->set_rules('contactperson', 'Contact Person', 'trim|required|min_length[3]|max_length[100]');
			$this->form_validation->set_rules('mobilenumber', 'Mobile Number', 'trim|required|min_length[10]|max_length[10]');
			$this->form_validation->set_rules('emailaddress', 'Email Address', 'trim|required|valid_email|min_length[3]|max_length[580]');
			$this->form_validation->set_rules('brewerystate[]', 'Brewery States', 'trim|required|xss_clean|callback_multiple_selectstate');
			// $this->form_validation->set_rules('brewerystate', 'Brewery States', 'required',array('required' =>'Select At Least One State'));
			// $this->form_validation->set_rules('breweryentity', 'Brewery Entity', 'required',array('required' =>'Select At Least One Entity'));
			$this->form_validation->set_rules('breweryentity[]', 'Brewery Entity', 'trim|required|xss_clean|callback_multiple_selectentity');
			$this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

			if ($this->form_validation->run()) {
				$data['success'] = true;
				// $this->brewery_model->insert();
				$this->session->set_flashdata('success', 'Brewery Added Successfully');
				redirect('admin/brewery/Brewery/add');
				// die('Save');
			} else {
				foreach ($_POST as $key => $value) {
					// print_r($_POST);
					// die();
					$data['messages'][$key] = form_error($key);
				}
			}
		}

		if (!empty($_POST['breweryname'])) {
			$breweryid = $this->input->post('breweryname');
			$data['brewery_statemappedlist'] = $this->brewery_model->getBrandMappedList($breweryid);

		}
		$data['title'] = trans('brewery_state_mapping');
		// $data['brewerylist'] = $this->brewery_model->getBreweryList();
		$data['depotlist'] = $this->brewery_model->getDepotName();
		$this->load->view('admin/includes/_header');
		$this->load->view('admin/brewery/state_mapping_view', $data);
		$this->load->view('admin/includes/_footer');
	}

	function fetchstatesmapped()
	{
		$breweryid = $this->input->post('breweryid');
		$brewery_statemappedlist = $this->brewery_model->getBreweryMappedList($breweryid);
		echo json_encode($brewery_statemappedlist);
	}





	//Fetch Brand Mapping List
	function fetchbrandmapped()
	{
		$stockistid = $this->input->post('stockistid');
		$stockist_brandmappedlist = $this->brewery_model->getBrandMappedList($stockistid);
		echo json_encode($stockist_brandmappedlist);
	}

	function fetchStatesList()
	{
		$getStates = $this->brewery_model->getStates();
		echo json_encode($getStates);
	}

	//Liquor Brand Name Model
	function fetchBrandNameList()
	{
		$getBrandName = $this->brewery_model->getBrandName();
		echo json_encode($getBrandName);
	}

	function fetchBreweryList()
	{
		$getBrewery = $this->brewery_model->getBrewery();
		echo json_encode($getBrewery);
	}


	function mapBreweryToStates()
	{
		$breweryid = $this->input->post('breweryid');
		$statesid = $this->input->post('statesids');
		$statesid = implode(",", $statesid);
		$data = array('state' => $statesid);
		$getStates = $this->brewery_model->mapBreweryToStates($breweryid, $data);
		echo json_encode($getStates);
	}


	//Map Stockist to brand
	function mapStockistToBrand()
	{
		$stockistid = $this->input->post('stockistid');
		$brandid = $this->input->post('brandid');
		$brandid = trim(implode(",",$brandid),",");
		// $data = array('brand_id' => $brandid);
		$data = array('entity_id' => $stockistid, 'brand_id' => $brandid);
		$getBrand = $this->brewery_model->mapStockistToBrand($stockistid,$data);
		echo json_encode($getBrand);
	}



	//--------------------------------------------------
	function edit($id = "")
	{

		$this->rbac->check_operation_access(); // check opration permission

		if ($this->input->post('submit')) {
			$this->brewery_model->update();
			$this->session->set_flashdata('success', 'Record updated Successfully');
			redirect('admin/brewery/Brewery');
		}
		if ($id == "")
			redirect('admin/brewery/Brewery');

		$data['title'] = trans('edit_role');
		$data['record'] = $this->brewery_model->get_role_by_id($id);

		$this->load->view('admin/includes/_header');
		$this->load->view('admin/brewery/index', $data);
		$this->load->view('admin/includes/_footer');
	}
}
