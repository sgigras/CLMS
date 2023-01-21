<?php

class NewStock_master extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('newStock/NewAvailable_stock');
    }


    function index()
    { 

        $liquor_data = $this->NewAvailable_stock->getLiquorNames();
        // $liquor_stock = $this->NewAvailable_stock->getAvailableStock();
        $data['title'] = trans('NEW_STOCK');
        // $data['table_head'] = NEW_STOCK;
        // $data['table_head'] = ALCOHOL_MASTER;

        $data['liquor_name_record'] = $liquor_data;

        $this->load->view('admin/includes/_header');
        $this->load->view('newAvailable_stock/newStock_view',$data);
        $this->load->view('admin/includes/_footer');
        
    }

    public function fetchAvailableStock(){
		$liquor_id = $this->input->post('liquor_id');
		$result = $this->NewAvailable_stock->fetchAvailableStock($liquor_id);
	 	echo json_encode($result); 
	 }

     public function createNewStock(){
        
        $data = array(
            "user_id"=>$this->session->userdata('admin_id'),
            "entity_id"=>$this->session->userdata('entity_id'),
            "invoice_no"=>$this->input->post('invoice_no'),
            "stock_data"=>$this->input->post('mainArr'),
        );
        // print_r($data);
        // die();
        $result = $this->NewAvailable_stock->createNewStock(json_encode($data));
        echo json_encode($result);


        // $liquor_array = $this->input->post('liquor_array');
        // $available_stock_array = $this->input->post('available_stock_array');
        // $new_stock_array = $this->input->post('new_stock_array');
        // $total_array = $this->input->post('total_array');

        // print_r($invoice_no);
        // print_r($liquor_array);
        // print_r($available_stock_array);
        // print_r($new_stock_array);
        // print_r($total_array);

        // $result = $this->NewAvailable_stock->createNewStock($invoice_no, $liquor_array,$new_stock_array, $available_stock_array,$total_array);
        // echo json_encode($result);
    }

}

?>