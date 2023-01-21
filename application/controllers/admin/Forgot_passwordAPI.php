<?php  defined('BASEPATH') or exit('No direct script access allowed');


class Forgot_passwordAPI extends MY_Controller
{
	public function __construct()
    {
        parent::__construct();

       
        $this->load->model('admin/Auth_model', 'auth_model');
        $this->load->helper(array('bsf_form/list_field', 'bsf_form/master_table', 'Ats/common'));
        $this->load->library(array('Ats/atscommon'));
    }

    public function verifyUser()
    {
        // print_r($_SESSION);
        // die();
            if($this->input->post('submit')){
            $this->form_validation->set_rules('username', 'Email', 'trim|valid_email|required');
            $this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');
            if($this->form_validation->run() == False)
             {
              foreach ($_POST as $key => $value) {

                    $data['messages'][$key] = form_error($key);
                }
              
                $this->session->set_flashdata('error', $data['messages']);
               $this->session->set_flashdata('username', $this->input->post('username'));

                redirect(base_url('admin/Forgot_passwordAPI/verifyUser'), 'refresh');
            }else
            {

                $username = $this->input->post('username');
              //  $admin_id = $this ->session->userdata('username');
                $admin_id = '22222222';
                // echo $admin_id;
                
               
                $result = $this->auth_model->verifyUser($username, $admin_id);
                               if($result)
                {
                    if($result[0]->email_id == $username)
                    {
                             $fourRandomDigit = rand(1000,9999);
                             $FromMail="noreply@aniruddhagps.com";
                             $FromName="BSF";
                             $To=$Cc=$Bcc=$username;
                             $Subject="Reset Password";
                             $link = base_url('admin/Forgot_passwordAPI/verifyotp'); 
                             $url = "<a href=$link target='_blank'>Click here</a>";
                             $Message="<h5>Dear " .$username."</h5>"."Please find below to reset your password. <br>kindly click on below link to reset your password with provided 4-digit otp.<br>". $url."<br>".$fourRandomDigit."<br>Thank You!"; 
                            $attachment="";
                            $mail_result=sendEmail($Subject, $Message, $To);
                            
                            if($mail_result){
                                if($mail_result == "success"){
                                    //print_r($admin_id); die();
                                    $otp_insert = $this->auth_model->otpInsert($admin_id, $fourRandomDigit);
                                    if($otp_insert == '1')
                                    {
                                         $this->session->set_flashdata("Kindly check your mail");
                                         redirect(base_url('admin/Forgot_passwordAPI/verifyUser'), 'refresh');
                                    }else
                                    {
                                        $this->session->set_flashdata("Failed to insert otp");
                                         redirect(base_url('admin/Forgot_passwordAPI/verifyUser'));
                                    }
                                }

                            }else
                            {
                                $this->session->set_flashdata("Failed to send mail");
                                redirect(base_url('admin/Forgot_passwordAPI/verifyUser'));
                            }


                    }

                }else
                {
                    $this->session->set_flashdata('errors', "User doesn't exist");
                    redirect(base_url('admin/Forgot_passwordAPI/verifyUser'));


                }
            }
        }else
        {
            $data['navbar'] = false;
            $data['sidebar'] = false;
            $data['footer'] = false;
            ///$data['bg_cover'] = true;
            $data['title'] = trans('forgot_pasword');
            $this->load->view('admin/includes/_header',$data);
            $this->load->view('admin/auth/test_password_forgot_vw');
            $this->load->view('admin/includes/_footer', $data);

        }
        

    }

    // public function  verifyUser()
    // {
        
    //     $username = $this->input->post('username');
    //     $admin_id = $this->session->userdata('username');
    //     //$admin_id = '22222222';
    //     $response = $this->auth_model->verifyUser($username, $admin_id);
    //     echo json_encode($response);
    // }

    // public function sendVerificationMail(){
    //      $username = $this->input->post('username');
    //      $admin_id = $this->session->userdata('username');
    //      $fourRandomDigit = rand(1000,9999);
    //      $FromMail="noreply@aniruddhagps.com";
    //      $FromName="BSF";
    //      $To=$Cc=$Bcc=$username;
    //      $Subject="Reset Password"; 
    //      $url = "<a href='http://localhost/BSF/CLMS/admin/Forgot_passwordAPI/otpViewload' target='_blank'>Click here</a>";
    //      $Message="<h5>Dear " .$username."</h5>"."Please find below to reset your password. <br>kindly click on below link to reset your password with provided 6-digit otp.<br>". $url."<br>".$fourRandomDigit."<br>Thank You!"; 
    //     $attachment="";

        
    //     $result=sendEmail($Subject, $Message, $To);
    //     if($result == "success"){
    //         $otp_insert_response = $this->auth_model->otpInsert($admin_id, $fourRandomDigit);
    //     }

    //     echo $result;
    // }

    public function verifyotp(){

        if($this->input->post('submit')){
             $this->form_validation->set_rules('otp', 'OTP', 'trim|required|numeric|max_length[4]');
            $this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');
            if($this->form_validation->run() == False)
             {
              foreach ($_POST as $key => $value) {

                    $data['messages'][$key] = form_error($key);
                }
              
                $this->session->set_flashdata('error', $data['messages']);
                $this->session->set_flashdata('otp', $this->input->post('otp'));
                

                redirect(base_url('admin/Forgot_passwordAPI/verifyotp'), 'refresh');
            }else
            {
                $otp = $this->input->post('otp');

                //$admin_id = $this ->session->userdata('username');
                $admin_id = '22222222';

            }





        }else
        {
            $data['navbar'] = false;
            $data['sidebar'] = false;
            $data['footer'] = false;
            $data['bg_cover'] = true;
            $data['title'] = trans('forgot_pasword');
            $this->load->view('admin/includes/_header',$data);
            $this->load->view('admin/auth/test_otpVerification_vw');
            $this->load->view('admin/includes/_footer',$data);

        }
        


    }

    // public function verifyotp(){
    //     $otp = $this->input ->post('otp');
    //     $admin_id = $this->session->userdata('username');
    //     $response = $this->auth_model->verifyotp($otp, $admin_id);
    //     echo json_encode($response);

    // }

    public function updatePassword(){
        $newPassword =$this->input->post('new_pass');
        $encrypt_new_pass = password_hash($newPassword, PASSWORD_BCRYPT);
        $admin_id = $this->session->userdata('username');
        $response = $this->auth_model->updatePassword($encrypt_new_pass, $admin_id);
        echo json_encode($response);
    }


}
?>





















