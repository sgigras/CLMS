<?php

class AdditionalSheetsController extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        auth_check();
        $this->load->model('additional_sheets/AdditionalSheetsModel');
        $this->load->model('newStock/NewAvailable_stock');
    }


    function index()
    {
        $liquor_data = $this->NewAvailable_stock->getLiquorNames();
        $data['liquor_name_record'] = $liquor_data;
        // print_r($liquor_data);
        $this->load->view('admin/includes/_header');
        $this->load->view('additional_sheets/AdditionalSheetsView', $data);
        $this->load->view('admin/includes/_footer');
    }



    function getSalesTypeData()
    {
        $sales_type = $this->input->post('sales_type');
        $search = $this->input->post('search');
        // print_r($sales_type);
        $sales_type = $this->AdditionalSheetsModel->fetchSalesTypeData($sales_type, $search);
        echo json_encode($sales_type);
        // print_r($sales_type);
        // die();
        // $this->load->view('additional_sheets/AdditionalSheetsView',$sales_type_data);
    }

    function createNewAdditionalSheet()
    {
        $data = array(
            "user_id" => $this->session->userdata('admin_id'),
            "entity_id" => $this->session->userdata('entity_id'),
            "sales_type" => $this->input->post('sales_type'),
            "select_type" => $this->input->post('select_type'),
            "purpose" => $this->input->post('purpose'),
            "additional_sheets_data" => $this->input->post('mainArr'),
        );
        // print_r($data);
        // die();
        $result = $this->AdditionalSheetsModel->createNewAdditionalSheet(json_encode($data));

        if ($result[0]->V_SWAL_TYPE == 'success') {
            $this->session->set_userdata('print_reciept', $result[0]->V_ORDER_CODE);
        }
        // echo $result;
        echo json_encode($result);
    }

    public function fetchLiquorList()
    {
        $sales_type = $this->input->post('sales_type');
        $response['liquor_name_record'] = $this->AdditionalSheetsModel->fetchLiquorList($sales_type);
        $this->load->view('additional_sheets/liquor_tabular_details_view', $response);
    }
}
