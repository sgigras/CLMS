<?php defined('BASEPATH') or exit('No direct script access allowed');
class Liquor_Inventory extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        auth_check(); // check login auth
        $this->rbac->check_module_access();
        $this->load->model('Master/LiquorMapping_Model', 'liquor_mapping_master_model');
        $this->load->model('Order/Stockist_order_model', 'stockist_order_model');
        $this->load->model('admin/Activity_model', 'activity_model');
        $this->load->model('Reports/Sales_report_model', 'Sales');
        $this->load->helper(array('bsf_form/list_field', 'bsf_form/master_table', 'Ats/common'));
    }
    public function liquor_mapping()
    {
        $data = $this->liquor_mapping_master_model->fetchInitialAlcoholFormDetails();
        $this->load->view('admin/includes/_header');
        $this->load->view('liquor/liquorMappingView', $data);
        $this->load->view('admin/includes/_footer');
    }
    public function fetchBookedOrderStock()
    {
        $entity_id = $this->session->userdata('entity_id');
        $response = $this->stockist_order_model->fetchBookedOrderStock($entity_id);
        // echo json_encode($response);
        $cart_id_array = array();
        $cart_id = 0;
        $order_details_array = array();
        foreach ($response as  $row) {
            $cart_id = $row['cart_id'];
            if (!in_array($cart_id, $cart_id_array)) {
                array_push($cart_id_array, $cart_id);
                $order_details_array[$cart_id][] = $row;
            } else {
                $order_details_array[$cart_id][] = $row;
            }
        }
        $this->load->view('admin/includes/_header');
        $this->load->view('order/display_all_orders', array('display_type' => 'Stockist Details', 'order_history' => $order_details_array, 'title' => 'stock_ordered', 'fa_form_icon' => 'fa fa-orders'));
        $this->load->view('admin/includes/_footer');
    }
    public function fetchBookedLiquor()
    {
        $entity_id = $this->session->userdata('entity_id');
        $response = $this->stockist_order_model->fetchBookedLiquor($entity_id);
        $data['title'] = 'booked_liquor';
        $data['fa_form_icon'] = 'fa fa-list';
        $cart_id_array = array();
        $cart_id = 0;
        $order_details_array = array();
        foreach ($response as  $row) {
            $cart_id = $row['cart_id'];
            if (!in_array($cart_id, $cart_id_array)) {
                array_push($cart_id_array, $cart_id);
                $order_details_array[$cart_id][] = $row;
            } else {
                $order_details_array[$cart_id][] = $row;
            }
        }
        $data["booked_liquor"] = $order_details_array;
        $this->load->view('admin/includes/_header');
        $this->load->view('order/displayBookedLiquor', $data);
        $this->load->view('admin/includes/_footer');
    }
    public function fetchBookedLiquorList()
    {
        $entity_id = $this->session->userdata('entity_id');
        $response = $this->stockist_order_model->fetchBookedLiquorList($entity_id);
        $data['title'] = trans('booked_liquor_quantity');
        $data['fa_form_icon'] = 'fa fa-list';
        $data["booked_liquor"] = $response;
        $this->load->view('admin/includes/_header');
        $this->load->view('order/displayBookedLiquorList', $data);
        $this->load->view('admin/includes/_footer');
    }
    public function sales_report()
    {
        $this->load->view('admin/includes/_header');
        $this->load->view('reports/sales_report');
        $this->load->view('admin/includes/_footer');
    }
    public function stock_summary_report()
    {
        $this->load->view('admin/includes/_header');
        $this->load->view('reports/liquor_sales_report');
        $this->load->view('admin/includes/_footer');
    }
    public function monthly_sales_report()
    {
        $entity_id = $this->session->userdata('entity_id');
        $sale_month = date('m');
        $sale_year = date('Y');
        $this->session->set_flashdata('form_data', array("start_date" => date('m-Y')));
        $result['details'] = $this->Sales->monthly_sales_report($sale_month, $sale_year, $entity_id);
        $result['entity_name'] = $this->Sales->fetch_entity_name($entity_id);
        $this->load->view('admin/includes/_header');
        $this->load->view('reports/monthly_sales_summary_report', $result);
        $this->load->view('admin/includes/_footer');
    }
    public function yearly_sales_report()
    {
        $entity_id = $this->session->userdata('entity_id');
        $sale_month = date('m');
        $sale_year = date('Y');
        $this->session->set_flashdata('form_data', array("start_date" => date('Y')));
        $result['details'] = $this->Sales->yearly_sales_report($sale_year, $entity_id);
        $result['entity_name'] = $this->Sales->fetch_entity_name($entity_id);
        $this->load->view('admin/includes/_header');
        $this->load->view('reports/year_sales_summary_report', $result);
        $this->load->view('admin/includes/_footer');
    }
    public function display_stock()
    {
        $this->load->view('admin/includes/_header');
        $this->load->view('stock/stock_display_view');
        $this->load->view('admin/includes/_footer');
    }
    // to fetch entity order
    public function entity_order()
    {
        $entity_id = $this->session->userdata('entity_id');
        $data = $this->stockist_order_model->fetchCanteen('entity', 'entity');
        $delivarable_entity_id = $this->stockist_order_model->fetch_delivarable_entity_id($entity_id); // for onload data show
        if (count(json_decode(json_encode($delivarable_entity_id), true)) > 0) {
            $data['delivarable_entity_id'] = $delivarable_entity_id[0]->delivarable_entity_id;
        } else {
            $data['delivarable_entity_id'] = 1;
        }
        $keyword = 'ALL';
        $selected_state = $this->input->post('selected_state');
        $selected_city = $this->input->post('selected_city');
        $product_cart_BSF = $this->session->userdata('product_cart_BSF');
        if (!is_array($product_cart_BSF)) {
            $product_cart_BSF = array();
        }
        $this->load->view('admin/includes/_header');
        $this->load->view('order/search_product', $data);
        $this->load->view('admin/includes/_footer');
    }
    public function liquorEditListChange() ///actual function name liquor list used to edit quantity blocked so that stock does not activate
    {
        $entity_id = $this->session->userdata('entity_id');
        $liquor_data = $this->liquor_mapping_master_model->fetchAllLiquorMapRecords($entity_id);
        $data['title'] = trans('liquor_list'); // header of the page
        $data['add_url'] = 'master/Liquor_mapping/addLiquorMappingDetails'; //url for adding new product on form submission
        $data['add_title'] = trans('liquor_add'); //add button titl on list page
        $data['table_head'] = LIQUOR_MAPPING_LIST; //from application/helpers/bsf_form list_field_helper //use to create table 
        $data['table_data'] = $liquor_data;
        $data['table_mode'] = "LIQUOR_MAPPING_LIST";
        $data['edit_url'] = 'master/Liquor_mapping/editLiquorMappingDetails';
        $data['csrf_url'] = 'master/Liquor_mapping';
        $this->load->view('admin/includes/_header');
        $this->load->view('master/newMasterTableView', $data);
        $this->load->view('admin/includes/_footer');
    }
    public function stockList()
    {
        $entity_id = $this->session->userdata('entity_id');
        $liquor_data = $this->liquor_mapping_master_model->fetchAllLiquorMapRecords($entity_id);
        $data['title'] = trans('liquor_list'); // header of the page
        $data['add_url'] = 'admin/order/Liquor_Inventory/liquor_mapping'; //url for adding new product on form submission
        $data['add_title'] = trans('liquor_add'); //add button titl on list page
        $data['table_head'] = LIQUOR_STOCK_MAPPING_LIST; //from application/helpers/bsf_form list_field_helper //use to create table 
        $data['table_data'] = $liquor_data;
        $data['table_mode'] = "LIQUOR_STOCK_MAPPING_LIST";
        $data['edit_url'] = 'master/Liquor_mapping/editLiquorMappingDetails';
        $data['csrf_url'] = 'master/Liquor_mapping';
        $this->load->view('admin/includes/_header');
        $this->load->view('master/newMasterTableView', $data);
        $this->load->view('admin/includes/_footer');
    }
    public function liquor_sales_summary()
    {
        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('start_date', 'Start Date', 'trim|required');
            if ($this->form_validation->run() == FALSE) {
                $data = array(
                    'errors' => validation_errors()
                );
                $this->session->set_flashdata('form_data', $_POST);
                $this->session->set_flashdata('errors', $data['errors']);
                redirect(base_url('reports/sale_summary_report'), 'refresh');
            } else {
                $start_date = $this->input->post('start_date');
                $end_date = $this->input->post('end_date');
                $entity_id = $this->session->userdata('entity_id');
                $result['details'] = $this->Sales->liquor_sales_summary($entity_id, $start_date, $end_date);
                $result['entity_name'] = $this->Sales->fetch_entity_name($entity_id);
                $this->session->set_flashdata('form_data', $_POST);
                if (sizeof($result) > 0) {
                    $this->load->view('admin/includes/_header');
                    $this->load->view('reports/sale_summary_report', $result);
                    $this->load->view('admin/includes/_footer');
                }
            }
        } else {
            $entity_id = $this->session->userdata('entity_id');
            $start_date = date('Y-m-d');
            $this->session->set_flashdata('form_data', array("start_date" => date('d-m-Y')));
            $result['details'] = $this->Sales->liquor_sales_summary($entity_id, $start_date, '');
            $result['entity_name'] = $this->Sales->fetch_entity_name($entity_id);
            $this->load->view('admin/includes/_header');
            $this->load->view('reports/sale_summary_report', $result);
            $this->load->view('admin/includes/_footer');
        }
    }
}
