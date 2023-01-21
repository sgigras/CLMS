<?php

class get_canteenMaster extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('get_canteen_details/get_canteenMasterModel');
    }

    public function index()
    {
        $data['title'] = trans('all_canteen');
        $this->load->view('admin/includes/_header');
        $this->load->view('canteen/get_canteenMasterView',$data);

        $this->load->view('admin/includes/_footer');
    }

    public function getCanteenData()
    {
        
        $report_type=$this->input->post('report_type');
        $liquor_data = $this->get_canteenMasterModel->fetchCanteenDetails($report_type);
        $this->load->view('canteen/get_canteenReportTable',$liquor_data);
        // echo $report_type;
        // $data['table_head'] = ALL_CANTEEN;

        // $data['table_data'] = $liquor_data;

        


    }
}

?>