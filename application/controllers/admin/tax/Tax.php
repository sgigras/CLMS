<?php defined('BASEPATH') or exit('No direct script access allowed');

class Tax extends MY_Controller
{
	function __construct()
	{

		parent::__construct();
		auth_check(); // check login auth
		$this->rbac->check_module_access();

		$this->load->model('admin/Tax_model', 'tax_model');
		$this->load->model('admin/Activity_model', 'activity_model');
	}

	//-----------------------------------------------------		
	function index()
	{


		$data['title'] = trans('brewery_master');
		$data['records'] = $this->brewery_model->get_all();

		$this->load->view('admin/includes/_header');
		$this->load->view('admin/brewery/index', $data);
		$this->load->view('admin/includes/_footer');
	}

	function updateTaxToLiquor(){

		$data = array(
			'mappingid' => $this->input->post('mappingid'),
			'tax_percent' => $this->input->post('interestvalue'),
			'tax_type_id'=> $this->input->post('checked_value'),
			'tax_category' => $this->input->post('tax_category')
		);
		$getStates = $this->tax_model->updateTaxToLiquor($data);
		echo json_encode($getStates);
	}

	function addTaxToLiquor(){
		$data = array(
			'liquor_description_id' => $this->input->post('liquorid'),
			'tax_id' => $this->input->post('taxid'),
			'tax_category' => $this->input->post('tax_category'),
			'tax_percent' => $this->input->post('interestvalue'),
			'entity_id' => $this->session->userdata('entity_id'),
			'created_by' => $this->session->userdata('admin_id'),
			'modified_by' => $this->session->userdata('admin_id'),
			'isactive'=>'1',
			'tax_type_id'=> $this->input->post('checked_value')
		);
		$getStates = $this->tax_model->addTaxToLiquor($data);
		echo json_encode($getStates);
	}


	function change_status()
	{   
		$this->tax_model->change_status();
	}

	function tax_Mapping()
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
					print_r($_POST);
					die();
					$data['messages'][$key] = form_error($key);
				}
			}
			echo json_encode($data);
			return;


			// $this->brewery_model->insert();	
			// $this->session->set_flashdata('success', 'Record Added Successfully');	
			// redirect('admin/brewery/Brewery');
		}

		$data['title'] = trans('taxmapping');
		$data['taxlist'] = $this->tax_model->getTaxes();
		
		$data['stateslist'] = $this->tax_model->getStates();
		$this->load->view('admin/includes/_header');
		$this->load->view('admin/tax/tax_mapping_view', $data);
		$this->load->view('admin/includes/_footer');
	}

	//For select validation
	public function multiple_selectstate()
	{
		// print_r($this->input->post('brewerystate')[0]);die();
		if (empty($this->input->post('brewerystate')[0])) {
			$this->form_validation->set_message('multiple_selectstate', 'Please Select Atleast One State.');
			return FALSE;
		} else {
			return TRUE;
		}
	}

	//For select validation
	public function multiple_selectentity()
	{
		// print_r($this->input->post('brewerystate')[0]);die();
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

			// echo json_encode($data);
			// return;


			// $this->brewery_model->insert();	
			// $this->session->set_flashdata('success', 'Record Added Successfully');	
			// redirect('admin/brewery/Brewery');
		}

		if (!empty($_POST['breweryname'])) {
			$breweryid = $this->input->post('breweryname');
			$data['brewery_statemappedlist'] = $this->brewery_model->getBreweryMappedList($breweryid);
			// $data['testkey']='test';
			// print_r($data['brewery_statemappedlist']);
			// die();
		}

		// print_r($data);
		$data['title'] = trans('brewery_state_mapping');
		$data['brewerylist'] = $this->brewery_model->getBreweryList();
		$this->load->view('admin/includes/_header');
		$this->load->view('admin/brewery/state_mapping_view', $data);
		$this->load->view('admin/includes/_footer');
	}

	function fetchliquortaxesmapped()
	{
		$liquoridlist = $this->input->post('liquoridlist');
		// $tax_category = $this->input->post('tax_category');
		$entity_id = $this->session->userdata('entity_id');
		$liquortaxMappedList['taxlist'] = $this->tax_model->getliquortaxMappedList($liquoridlist,$entity_id);
		// $liquortaxMappedList['liquortypes'] = $this->tax_model->getLiquorTypes();
		echo json_encode($liquortaxMappedList);
	}

	function getBSFMarginData()
	{
		$stateid = $this->input->post('stateid');
		$liquortypeid = $this->input->post('liquortypeid');
		$bsfMarginData= $this->tax_model->getBSFMarginData($stateid,$liquortypeid);
		echo json_encode($bsfMarginData);
	}

	function mapnewBSFMarginData(){
		$stateid = $this->input->post('stateid');
		$liquortypeid = $this->input->post('liquortypeid');
		$priceperbottle = $this->input->post('priceperbottle');
		$bsfMarginData= $this->tax_model->mapnewBSFMarginData($stateid,$liquortypeid,$priceperbottle);
		echo json_encode($bsfMarginData);
	}

	function fetchStatesList(){
		$getStates = $this->brewery_model->getStates();
		echo json_encode($getStates);
	}

	function mapTaxToStates(){
		$data=$_POST;
		print_r($data);
		die();
		$tabledata = $this->input->post('xFinalArr');
		print_r($tabledata);
		die();
		$getStates = $this->brewery_model->mapBreweryToStates($breweryid,$data);
		echo json_encode($getStates);
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
		$this->load->view('admin/brewery/edit', $data);
		$this->load->view('admin/includes/_footer');
	}
}
