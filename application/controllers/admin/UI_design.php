<?php defined('BASEPATH') OR exit('No direct script access allowed');

class UI_design extends MY_Controller {

	public function __construct(){

		parent::__construct();
		auth_check(); // check login auth
	}

	//----------------------------------------------------------------
	public function index(){

		$data['title'] = 'Simple Table';

		$this->load->view('admin/includes/_header');
		$this->load->view('admin/uidesign/design');
		$this->load->view('admin/includes/_footer');
	}
	public function cart(){

		$data['title'] = 'Simple Table';

		$this->load->view('admin/includes/_header');
		$this->load->view('admin/uidesign/cart');
		$this->load->view('admin/includes/_footer');
	}

	public function cartdetails(){

		$data['title'] = 'Simple Table';

		$this->load->view('admin/includes/_header');
		$this->load->view('admin/uidesign/cartdetailsview');
		$this->load->view('admin/includes/_footer');
	}

	public function addProduct() {
		$this->load->view('admin/includes/_header');
		$this->load->view('admin/uidesign/productmasterview');
		$this->load->view('admin/includes/_footer');
		
	}

	public function ordersummary(){

	

		$this->load->view('admin/includes/_header');
		$this->load->view('admin/uidesign/ordersummaryview');
		$this->load->view('admin/includes/_footer');
	}

	public function track(){

		$this->load->view('admin/includes/_header');
		$this->load->view('admin/uidesign/track');
		$this->load->view('admin/includes/_footer');
	}


	public function Elements(){

		$this->load->view('admin/includes/_header');
		$this->load->view('admin/uidesign/element');
		$this->load->view('admin/includes/_footer');
	}

}

	?>
