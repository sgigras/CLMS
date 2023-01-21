<?php


class verifyRetireeAPI extends My_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_details/retireeVerificationModel', 'verification');
    }

    public function index()
    {
        $userid= $this->session->userdata('admin_id');
        $entity_id= $this->session->userdata('entity_id');
        $verification_data=$this->verification->verify_data($userid,$entity_id);
        // $this->load->view('admin/includes/_header');
        $this->load->view('master_forms/retireeDetails',$verification_data);
        // $this->load->view('admin/includes/_footer');
    }

    

    public function approve_deny_user()
    {
        $irla = $this->input->post("irla");
        $dob = $this->input->post("dob");
        $mobile = $this->input->post("mobile");
        $email = $this->input->post("email");
        $response = $this->user_details_model->update_user($irla,$dob,$mobile,$email);
        echo json_encode($response); 
    }

}
