<?php defined('BASEPATH') or exit('No direct script access allowed');

class OrderAPI extends MY_Controller
{
	function __construct()
	{

		parent::__construct();
		auth_check(); // check login auth
		$this->rbac->check_module_access();

		$this->load->model('Order/Order_model', 'order_model');
		$this->load->model('admin/Activity_model', 'activity_model');
	}

	//-----------------------------------------------------		
	function index()
	{
		$data['title'] = trans('order_details');;
		$data['records'] = $this->brewery_model->get_all();

		$this->load->view('admin/includes/_header');
		$this->load->view('order/orderdetails', $data);
		$this->load->view('admin/includes/_footer');
	}

	public function orderdetails()
	{
		$this->rbac->check_operation_access(); // check opration permission

		$data['title'] = trans('order_details');
		// 
		$this->load->view('admin/includes/_header');
		$this->load->view('order/orderdetails', $data);
		$this->load->view('admin/includes/_footer');
	}

	public function fetch_orderdetails(){
		$ordercode = $this->input->post('ordercode');
	
		$orderdetailsdata = $this->order_model->get_order_details($ordercode);
		echo json_encode($orderdetailsdata);
	}

}
