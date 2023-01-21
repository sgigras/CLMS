<?php defined('BASEPATH') or exit('No direct script access allowed');

class Display_Stock extends MY_Controller
{

    public function __construct()
    {

        parent::__construct();
        auth_check();
        //auth_check(); // check login auth
        //$this->rbac->check_module_access();
        $this->load->model('Stock/Display_Stock_Model', 'display_stock');
        $this->load->model('admin/Activity_model', 'activity_model');
        $this->load->helper(array('bsf_form/list_field', 'bsf_form/master_table', 'Ats/common'));
    }

    public function display_stock()
    {
        $this->load->view('admin/includes/_header');
        $this->load->view('stock/stock_display_view');
        $this->load->view('admin/includes/_footer');
    }

    public function fetchstocksummary()
    {
        $entity_id = $this->session->userdata('entity_id');
        // $entity_id=12;
        $liquor_details = $this->display_stock->fetchstocksummary($entity_id);
        $this->load->view('master/stock_summary/collapsable_div', array("liquor_details" => $liquor_details));
    }

    public function fetchdetailsofstocksummary()
    {
        $entity_id = $this->input->post('entity_mapping_id');
        $liquor_details = $this->display_stock->fetchdetailsofstocksummary($entity_id);
        echo json_encode($liquor_details);
    }
}
