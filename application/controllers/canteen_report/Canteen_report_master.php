<?php
class Canteen_report_master extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('canteen_report/Canteen_report_model');
    }
    public function index()
    {
        $data['title'] = trans('all_canteen');
        $this->load->view('admin/includes/_header');
        $this->load->view('canteen/get_canteenMasterView', $data);
        $this->load->view('admin/includes/_footer');
    }
    public function getCanteenData()
    {
        $report_type = $this->input->post('report_type');
        $liquor_data = $this->Canteen_report_model->fetchCanteenDetails($report_type);
        $this->load->view('canteen/get_canteenReportTable', $liquor_data);
    }
}
