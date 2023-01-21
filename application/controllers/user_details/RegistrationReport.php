<?php


class RegistrationReport extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_details/User_details_model', 'user_details_model');

        $this->load->model('user_details/RegistrationReportModel', 'registration_report_model');
    }


    public function index()
    {
        $data['title'] = trans('verification_status');

        $this->load->view('admin/includes/_header');
        $this->load->view('user_details/canteen_retiree_details', $data);
        $this->load->view('admin/includes/_footer');
    }

    public function getRetireeDetails()
    {
        $entity_id = $this->session->userdata('entity_id');
        $report_type = $this->input->post('report_type');
        $data = $this->registration_report_model->fetchRegisteredRetireeDetails($entity_id, $report_type);
        echo json_encode($data);
        // $data['title'] = trans('all_canteen');
        // $data['title'] = trans('all_canteen');
        // $this->load->view('admin/includes/_header');
        // $this->load->view('user_details/get_RetireeReportTable', $data);
        // $this->load->view('admin/includes/_footer');
    }

    public function viewPostinUnit()
    {
        $data['posting_unit_data'] = $this->user_details_model->fetch_posting_units();
        $data['title'] = trans('user_report');
        $this->load->view('admin/includes/_header');
        $this->load->view('user_details/viewPostingWise', $data);
        $this->load->view('admin/includes/_footer');
    }


    public function GetPostingWise()
    {
        $posting_unit = $this->input->post("posting_unit");
        $mode = $this->input->post("mode");
        $personnel_type = $this->input->post("personnel_type");
        // $posting_unit = "FTR HQ MALDA";
        // $mode = "unregistered";
        $response = $this->user_details_model->getPostingWise($posting_unit, $mode, $personnel_type);
        echo json_encode($response);
        // foreach ($response as $row) {
        //     if ($row['registration_status'] == null) {

        //         $row['registration_status'] = 'Not Registered';
        //     } else {
        //         $row['registration_status'] = 'Registered';
        //     }
        //     $user_details_array[] = $row;
        // }

        // $this->load->view('master/master_user', array("user_details_array" => $user_details_array));
        //    echo json_encode($user_details_array);
    }
}
