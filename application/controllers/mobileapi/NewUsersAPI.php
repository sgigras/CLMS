<?php
header("Access-Control-Allow-Origin: *");

class NewUsersAPI extends MY_Controller
{
    function __construct()
    {

        parent::__construct();

        // $this->load->model('admin/admin_model', 'admin');
        // $this->load->model('admin/Activity_model', 'activity_model');
        $this->load->model('mobile/New_User_model', 'user_model');
        $this->load->model('admin/New_Auth_model', 'auth_model');
        $this->load->model('user_details/Register_retiree_model', 'register_retiree_model');
    }

    public function fetchRetireeDetails()
    {
        $irlano = $this->input->post('search');
        $result = $this->register_retiree_model->fetchRetireeDetails($irlano);
        echo json_encode($result);
    }

    public function checkDeviceRegistered()
    {
        $data = json_decode(file_get_contents('php://input'));
        $response = $this->user_model->checkDeviceRegistered($data);
        echo json_encode($response);
    }

    public function checkRetireeData()
    {
        $data = json_decode(file_get_contents('php://input'));
        $response = $this->register_retiree_model->checkRetireeData($data);
        echo json_encode($response);
    }

    public function checkUserRegistered()
    {
        $login_data = json_decode(file_get_contents("php://input"), true);
        // echo '<pre>';
        $irla = $login_data['irla'];
        $response = $this->user_model->checkUserRegistered($irla);
        echo json_encode($response);
        // echo '</pre>';/
    }

    //-----------------------------------------------------		
    public function login()
    {
        // echo "Hello User";
        $login_data = json_decode(file_get_contents("php://input"), true);
        // $login_data = array("irl_no" => "87654321", "date_of_birth" => "1996-06-06", "pin_code" => "snehaltalele");
        $response = $this->user_model->mobile_login($login_data);

        $module_list = $response['module_list'];

        foreach ($module_list as $row) {
            $row->module_name = trans($row->module_name);
            $row->sub_module_name = trans($row->sub_module_name);
        }

        $response['module_list'] = $module_list;
        echo json_encode($response);
    }


    public function fetchRetireeInitialFormDetails()
    {
        $data = $this->register_retiree_model->fetchInitialFormDetails();
        echo json_encode($data);
    }

    public function addRetireeData()
    {
        $data = json_decode(file_get_contents('php://input'));
        $data->card_photo = "uploads/retiree_details/" . $data->card_photo;
        $data->personnel_photo = "uploads/retiree_details/" . $data->personnel_photo;
        $data->ppo_photo = "uploads/retiree_details/" . $data->ppo_photo;
        $data->signed_form_photo = "uploads/retiree_details/" . $data->signed_form_photo;
        $response = $this->register_retiree_model->addRetireeData(json_encode($data));
        echo json_encode($response);
        // print_r($data);
    }


    public function loginold()
    {
        $data = json_decode(file_get_contents('php://input'));
        $parts = explode('/', $data->dob);
        $yyyy_mm_dd = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
        $data->dob = $yyyy_mm_dd;
        $data = (array)$data;
        $userdetailsarray = [];
        $details = json_encode($data);
        log_message('error', "message login error $details");



        $result = $this->auth_model->login($data);
        // echo '<pre>';
        // print_r($result);die();
        if ($result) {
            if ($result['is_verify'] == 0) {
                // $this->session->set_flashdata('error', 'Please verify your email address!');
                // redirect(base_url('admin/auth/login'));
                // exit();

                $userdetailsarray = array("statusmsg" => "Please verify your email address!");
            }
            if ($result['is_active'] == 0) {
                // $this->session->set_flashdata('error', 'Account is disabled by Admin!');
                // redirect(base_url('admin/auth/login'));
                // exit();
                $userdetailsarray = array("statusmsg" => "Account is disabled by Admin!");
            }
            if ($result['is_admin'] == 1) {
                $additionaldata = $this->auth_model->fetchDetailsFromHrms($result);
                // print_r($additionaldata[0]['rank']);die();
                $admin_data = array(
                    'admin_id' => $result['admin_id'],
                    'entity_id' => $result['entity_id'],
                    'username' => $result['username'],
                    'rank' => (isset($additionaldata[0]['rank'])) ? $additionaldata[0]['rank'] : 'N.A',
                    'mobile_no' => $result['mobile_no'],
                    'full_name' => $result['firstname'] . " " . $result['lastname'],
                    'admin_role_id' => $result['admin_role_id'],
                    'admin_role' => $result['admin_role_title'],
                    'is_supper' => $result['is_supper'],
                    'transporter_id' => $result['transporter_id'],
                    'plant_id' => $result['plant_id'],
                    'is_admin_login' => TRUE,
                    'profile_picture' => $result['image']
                );
                // $this->session->set_userdata($admin_data);
                // $this->rbac->set_access_in_session(); // set access in session

                if ($result['is_supper'])
                    // redirect(base_url('admin/dashboard/index_2'), 'refresh');
                    $userdetailsarray = array("statusmsg" => "redirect", "is_super" => "yes", "userdetailsarray" => $admin_data);
                else
                    // redirect(base_url('admin/dashboard/index_2'), 'refresh');
                    $userdetailsarray = array("statusmsg" => "redirect", "is_super" => "no", "userdetailsarray" => $admin_data);
            }
        } else {
            // $this->session->set_flashdata('errors', 'Invalid Username or Password!');
            // redirect(base_url('admin/auth/login'));
            $userdetailsarray = array("statusmsg" => "Invalid Username Or Password");
        }

        echo json_encode($userdetailsarray);
    }



    public function register()
    {
        date_default_timezone_set('Asia/Kolkata');
        // echo date("Y-m-d H:i:s");
        $data = json_decode(file_get_contents('php://input'));
        // print_r($data);
        $parts = explode('/', $data->date_of_birth);
        $irla_no = $data->irla;
        $yyyy_mm_dd = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
        $data->date_of_birth = $yyyy_mm_dd;
        //TYPECAST OBJ TO ARRAY
        $data = (array)$data;
        $userdetailsarray = array();
        //VERIFY IF USER IN HRMS DATABASE
        $userDetails = array("irla" => $data['irla'], "date_of_birth" => $data['date_of_birth']);
        $result = $this->auth_model->verify_user($userDetails);

        if ($result) {
            // echo 'exist';
            $otp = $this->generateNumericOTP();
            // echo $otp;
            $mobile_no = $result['mobile_no'];
            $email_id = $result['email_id'];
            $date_of_birth = $result['date_of_birth'];
            $name = $result['name'];


            //1>SEND OTP/EMAIL

            $user_check = $this->user_model->checkUserRegistered($date_of_birth, $irla_no);

            // print_r($user_check[0]->user_count);
            $user_count = (int)$user_check[0]->user_count;
            // echo $user_count;
            // echo $date_of_birth;
            // echo $name;
            if ($user_count < 1) {

                if (($mobile_no !== 0 && $mobile_no !== '0' && $mobile_no !== '' && $mobile_no != 'null' && $mobile_no != null)
                    || ($email_id !== '' && $email_id != 'null' && $email_id != null)
                ) {

                    $result = $this->auth_model->sendOtp($otp, $email_id, $name, $mobile_no);

                    $irlano = $data['irla'];
                    $dateofbirth = $data['date_of_birth'];
                    $otpsaved = $this->auth_model->saveOTPForValidation($otp, $mobile_no, $email_id, $irlano);


                    //2>store otp details in database against emailid or mobile number to which otp send
                    if ($otpsaved) {

                        $userdetailsarray = array("status" => "success", "otp" => $otp, "name" => $name, "date_of_birth" => $date_of_birth, "mobile_no" => $mobile_no, "email_id" => $email_id, "irla_no" => $irlano, "dob" => $dateofbirth);

                        $curl_request = 'https://smsgw.sms.gov.in/failsafe/HttpLink?username=recttbsf.sms&pin=95gdbmqq&message=Dear%20User%20%20' . $irlano . ',%20Your%20OTP%20for%20CLMS%20registration%20is%20' . $otp . '%20Use%20this%20passcode%20to%20validate%20your%20registration%20process.%20Thank%20you.%20CLMS%20TEAM&mnumber=' . $mobile_no . '&signature=BSFSMS&dlt_entity_id=1701160101013178098&dlt_template_id=1707163531266975285';
                        //CURL - Request for SMS
                        $curl = curl_init();

                        curl_setopt_array($curl, array(
                            CURLOPT_URL => $curl_request,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'GET',
                            CURLOPT_SSL_VERIFYHOST => 0,
                            CURLOPT_SSL_VERIFYPEER => 0
                        ));

                        $response = curl_exec($curl);

                        curl_close($curl);
                        //echo $response;
                        $log_result = $this->auth_model->log_curl_response($response, $curl_request);
                    } else {
                        $userdetailsarray = array("status" => "fail", "message" => "Invalid details");
                    }
                } else {
                    $userdetailsarray = array("status" => "fail", "message" => "Unable to send OTP, Kindly update mobile no and email id through nearest canteen");
                }
            } else {
                $userdetailsarray = array("status" => "fail", "message" => "User has already been registered");
            }
        } else {
            // echo 'does not exist';

            $userdetailsarray = array("status" => "not_found", "message" => "Details not found");
        }

        echo json_encode($userdetailsarray);
    }


    public function checkAppVersion()
    {
        $application_details = json_decode(file_get_contents('php://input'));
        $application_platform_name = $application_details->application_name;
        if ($application_platform_name == 'BSF-CLMS') {
            $data = array('VERSION_NAME' => '1.0.3');
        } else if ($application_platform_name == 'BSF-CLMS-IOS') {
            $data = array('VERSION_NAME' => '1.0.4');
        } else {
            $data = array('VERSION_NAME' => '1.0.3');
        }

        $details[] = $data;

        echo json_encode($details);
    }


    public function get_otp_pin()
    {
        date_default_timezone_set('Asia/Kolkata');
        // echo date("Y-m-d H:i:s");
        $data = json_decode(file_get_contents('php://input'));
        // print_r($data);
        // $parts = explode('/', $data->date_of_birth);
        $irla_no = $data->irla;
        // $yyyy_mm_dd = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
        // $data->date_of_birth = $yyyy_mm_dd;
        //TYPECAST OBJ TO ARRAY
        $data = (array)$data;
        // $data['is_verified'] = 1;
        $userdetailsarray = array();
        //VERIFY IF USER IN HRMS DATABASE

        $result = $this->auth_model->verify_otp_user($data);

        if ($result) {
            // echo 'exist';
            $otp = $this->generateNumericOTP();
            // echo $otp;
            $mobile_no = $result['mobile_no'];
            $email_id = $result['email_id'];
            $date_of_birth = $result['date_of_birth'];
            $dateofbirth = $result['date_of_birth'];

            $name = $result['name'];


            //1>SEND OTP/EMAIL

            // $user_check = $this->user_model->checkUserRegistered($date_of_birth, $irla_no);

            // // print_r($user_check[0]->user_count);
            // $user_count = (int)$user_check[0]->user_count;
            // // echo $user_count;
            // // echo $date_of_birth;
            // // echo $name;
            // if ($user_count < 1) {
            $result = $this->auth_model->sendOtp($otp, $email_id, $name, $mobile_no);

            $irlano = $data['irla'];
            // $dateofbirth = $data['date_of_birth'];
            $otpsaved = $this->auth_model->saveOTPForValidation($otp, $mobile_no, $email_id, $irlano);


            //2>store otp details in database against emailid or mobile number to which otp send
            if ($otpsaved) {

                $userdetailsarray = array("status" => "success", "otp" => $otp, "name" => $name, "date_of_birth" => $date_of_birth, "mobile_no" => $mobile_no, "email_id" => $email_id, "irla_no" => $irlano, "dob" => $dateofbirth);

                $curl_request = 'https://smsgw.sms.gov.in/failsafe/HttpLink?username=recttbsf.sms&pin=95gdbmqq&message=Dear%20%20' . $irlano . ',%20Your%20OTP%20for%20CLMS%20registration%20is%20' . $otp . '%20Use%20this%20passcode%20to%20validate%20your%20registration%20process.%20Thank%20you.%20CLMS%20TEAM&mnumber=' . $mobile_no . '&signature=BSFSMS&dlt_entity_id=1701160101013178098&dlt_template_id=1707163531266975285';
                //CURL - Request for SMS
                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => $curl_request,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_SSL_VERIFYHOST => 0,
                    CURLOPT_SSL_VERIFYPEER => 0
                ));

                $response = curl_exec($curl);

                curl_close($curl);
                //echo $response;
                $log_result = $this->auth_model->log_curl_response($response, $curl_request);
            } else {
                $userdetailsarray = array("status" => "fail", "message" => "Invalid details");
            }
            // } else {
            // 	$userdetailsarray = array("status" => "fail", "message" => "User has already been registered");
            // }
        } else {
            // echo 'does not exist';

            $userdetailsarray = array("status" => "not_found", "message" => "Details not found");
        }

        echo json_encode($userdetailsarray);
    }



    // Function to generate OTP
    public function generateNumericOTP()
    {
        $n = 6;

        // Take a generator string which consist of
        // all numeric digits
        $generator = "1357902468";

        // Iterate for n-times and pick a single character
        // from generator and append it to $result

        // Login for generating a random character from generator
        //     ---generate a random number
        //     ---take modulus of same with length of generator (say i)
        //     ---append the character at place (i) from generator to result

        $result = "";

        for ($i = 1; $i <= $n; $i++) {
            $result .= substr($generator, (rand() % (strlen($generator))), 1);
        }

        // Return result
        return $result;
    }


    public function otpverify()
    {
        $data = json_decode(file_get_contents('php://input'));
        $data = (array)$data;
        $email = $data['email_id'];
        $statusarray = [];
        $result = $this->auth_model->validateMobileOtp($data);
        // print_r($data);

        if ($result) {
            //OTP VERIFIED BY USER


            //DEACTIVATE ALL OTPS TILL NOW
            $useremail = array(
                'email_id' => $email
            );
            $result = $this->auth_model->deactivateAllOTP($useremail);


            //ASK USER FOR PIN FOR THEIR ACCOUNT
            $statusarray = array("status" => "verified");
        } else {

            $statusarray = array("status" => "failed");
        }

        echo json_encode($statusarray);
    }


    public function setPassword()
    {
        $data = json_decode(file_get_contents('php://input'));
        $pass = $data->pin;
        $irla_no = $data->irla_no;
        $email_id = $data->email_id;
        $mobile_no = $data->mobile_no;
        $date_of_birth = $data->date_of_birth;
        $name = $data->name;
        $pin_mode = $data->pin_mode;
        $statusarray = [];


        if ($pin_mode == 'R') {
            $data = array(
                'password' => password_hash($pass, PASSWORD_BCRYPT),
                'admin_role_id' => '63',
                'username' => $irla_no,
                'email' => $email_id,
                'mobile_no' => $mobile_no,
                'date_of_birth' => $date_of_birth,
                'firstname' => $name,
                'is_verify' => '1',
                'is_admin' => '1',
                'is_active' => '1',
            );

            $registrationresponse = $this->auth_model->newUserRegistration($data);
        } else {
            $data = array(
                'password' => password_hash($pass, PASSWORD_BCRYPT),
            );
            $registrationresponse = $this->auth_model->change_pin($data, $irla_no);
        }


        if ($registrationresponse) {

            //ASK FOR PHOTO UPLOAD
            $statusarray = array("status" => "success");
        } else {
            $statusarray = array("status" => "failed");
        }

        echo json_encode($statusarray);
    }

    public function fetchUserDetails()
    {
        $user_id = $this->input->post('user_id');
        $response = $this->user_model->fetchUserDetails($user_id);
        echo json_encode($response);
    }

    public function update_profile_data()
    {
        $profile_data = json_decode(file_get_contents('php://input'));
        if ($profile_data->image_src != 'bsf_logo.png') {
            $profile_data->image_src = 'uploads/profilepics/' . $profile_data->image_src;
        } else {
            $profile_data->image_src = '';
        }
        // $username=$profile_data->;

        $response = $this->user_model->update_profile_data($profile_data);
        echo $response;
        // echo json_encode($profile_data);
    }


    public function uploadPics($img_mode = "", $path = "")
    {
        $mode = ($img_mode == "" ? $_POST["img_mode"] : $img_mode);
        $path = ($path == "" ? $_POST["path"] : $path);
        $response = array();
        date_default_timezone_set('Asia/Kolkata');
        $folder = "";
        switch ($mode) {
            case "profile_pic":
                $folder = "profilepics";
                break;
            case "retiree_details":
                $folder = "retiree_details";
                break;
            default:
                $folder = "";
        }
        if ($folder != "") {
            $response = $this->user_model->uploadPics($folder, $path);
        } else {
            $response = array("status" => 0, "data" => array(), "msg" => "Upload Failed due to insufficent data");
        }
        echo json_encode($response);
    }
    // picUploads
}
