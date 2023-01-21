<?php


class VerifyRetireeDetails extends My_Controller
{

    public function __construct()
    {
        parent::__construct();
        auth_check();
        $this->load->model('user_details/RetireeDetailsVerificationModel', 'verification');
    }

    public function index()
    {
        $userid = $this->session->userdata('admin_id');
        $entity_id = $this->session->userdata('entity_id');
        $verification_data = $this->verification->verify_data($userid, $entity_id);
        $this->load->view('admin/includes/_header');
        // echo $userid;
        // echo $entity_id;

        $this->load->view('master_forms/retireeDetails', array("verification_data" => $verification_data));
        $this->load->view('admin/includes/_footer');
    }


    // function setCheckId()
    // {
    //     print_r($_POST);
    // }


    public function fetchRetireeDetails()
    {
        $hrms_id = $this->input->post('hrms_id');
        $response = $this->verification->fetchRetireeDetails($hrms_id);
        $this->load->view('master_forms/showRetireeDetails', array("resultData" => $response));
        // echo json_encode($response);
    }

    public  function verifyRetiree()
    {
        // $hrms_id = $this->input->post('hrms_id');
        // $data = json_decode(file_get_contents("php://input"));
        // print_r($data);
        $verification_details = array(
            'hrms_id' => $this->input->post('hrms_id'),
            'id' => $this->input->post('id'),
            'action' => $this->input->post('action')
        );
        // echo $hrms_id."---".$id
        $response = $this->verification->verifyRetiree(json_encode($verification_details));
       
    }



    public function approve_deny_user()
    {
        $irla = $this->input->post("irla");
        $dob = $this->input->post("dob");
        $mobile = $this->input->post("mobile");
        $email = $this->input->post("email");
        $response = $this->user_details_model->update_user($irla, $dob, $mobile, $email);
        echo json_encode($response);
    }
}
