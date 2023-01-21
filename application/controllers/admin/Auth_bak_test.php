<?php defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends MY_Controller
{

	public function __construct()
	{

		parent::__construct();
		$this->load->library('mailer');
		$this->load->model('admin/auth_model', 'auth_model');
		$this->load->library(array('Cronslib/Smslib'));
		$this->load->helper(array('Ats/curl'));
	}


	public function sendAllSms()
	{
		$this->smslib->fetchSMSToSend();
		$this->smslib->insertCronLog();
//                $curl = curl_init();
//
//                curl_setopt_array($curl, array(
//                  CURLOPT_URL => 'https://smsgw.sms.gov.in/failsafe/HttpLink?username=recttbsf.sms&pin=95gdbmqq&message=Dear%252520User%252520%2013021991,%252520Your%252520OTP%252520for%252520CLMS%252520registration%252520is%252520.-.906728%252520Use%252520this%252520passcode%252520to%252520validate%252520your%252520registration%252520process.%252520Thank%252520you.%252520CLMS%252520TEAM&mnumber=9969640190&signature=BSFSMS&dlt_entity_id=1701160101013178098&dlt_template_id=1707163531266975285',
//                  CURLOPT_RETURNTRANSFER => true,
//                  CURLOPT_ENCODING => '',
//                  CURLOPT_MAXREDIRS => 10,
//                  CURLOPT_TIMEOUT => 0,
//                  CURLOPT_FOLLOWLOCATION => true,
//                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//                  CURLOPT_CUSTOMREQUEST => 'GET',
//                  CURLOPT_SSL_VERIFYHOST => 0,
//                  CURLOPT_SSL_VERIFYPEER => 0
//                ));
//
//                $response = curl_exec($curl);
//
//                curl_close($curl);
//                echo 'response-'.$response;
	}

	function sendSMS()
	{
		$message = "THIS IS A TEST MESSAGE. PLEASE IGNORE";

		$sms_result = CallCurl_SMS($message);
		//            echo $sms_result;
		$result = explode(' | ', $sms_result);
		$db = $this->CI->db;
		if ($result[0] == 'SUBMIT_SUCCESS') {
			echo 'SENT SUCCESSFULLY';
		} else {
			$error_message = $sms_result;
			echo $error_message;
		}
	}

	//--------------------------------------------------------------
	public function index()
	{

		if ($this->session->has_userdata('is_admin_login')) {
			redirect('admin/dashboard');
		} else {
			redirect('admin/auth/login');
		}
	}

	//--------------------------------------------------------------
	public function login()
	{

		if ($this->input->post('submit')) {

			$this->form_validation->set_rules('irlano', 'IRLA Number', 'trim|required|numeric|min_length[4]|max_length[20]');
			$this->form_validation->set_rules('dob', 'Date Of Birth', 'trim|required|valid_date');
			$this->form_validation->set_rules('pin', 'Pin', 'trim|required|min_length[4]|max_length[15]');
			$this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');
			if ($this->form_validation->run() == FALSE) {
				// $data = array(
				// 	'errors' => validation_errors()
				// );

				foreach ($_POST as $key => $value) {

					$data['messages'][$key] = form_error($key);
				}
				// print_r($data);
				// 	die();

				$this->session->set_flashdata('error', $data['messages']);
				$this->session->set_flashdata('irlano', $this->input->post('irlano'));
				$this->session->set_flashdata('dob', $this->input->post('dob'));
				$this->session->set_flashdata('pin', $this->input->post('pin'));
				
				redirect(base_url('admin/auth/login'), 'refresh');
			} else {
				$data = array(
					'irlano' => $this->input->post('irlano'),
					'dob' => $this->input->post('dob'),
					'pin' => $this->input->post('pin')
				);
				$result = $this->auth_model->login($data);
				// echo '<pre>';
				// print_r($result);die();
				if ($result) {
					if ($result['is_verify'] == 0) {
						$this->session->set_flashdata('error', 'Please verify your email address!');
						redirect(base_url('admin/auth/login'));
						exit();
					}
					if ($result['is_active'] == 0) {
						$this->session->set_flashdata('error', 'Account is disabled by Admin!');
						redirect(base_url('admin/auth/login'));
						exit();
					}
					if ($result['is_admin'] == 1) {
						$additionaldata = $this->auth_model->fetchDetailsFromHrms($result);
						// print_r($additionaldata[0]['rank']);die();
						$admin_data = array(
							'admin_id' => $result['admin_id'],
							'entity_id' => $result['entity_id'],
							'username' => $result['username'],
							'rank'=>(isset($additionaldata[0]['rank']))?$additionaldata[0]['rank']:'N.A',
							'mobile_no' => $result['mobile_no'],
							'full_name' => $result['firstname'] . " " . $result['lastname'],
							'admin_role_id' => $result['admin_role_id'],
							'admin_role' => $result['admin_role_title'],
							'is_supper' => $result['is_supper'],
							'transporter_id' => $result['transporter_id'],
							'plant_id' => $result['plant_id'],
							'is_admin_login' => TRUE,
							'profile_picture' =>$result['image']
						);
						$this->session->set_userdata($admin_data);
						$this->rbac->set_access_in_session(); // set access in session

						if ($result['is_supper'])
							redirect(base_url('admin/dashboard/index_2'), 'refresh');
						else
							redirect(base_url('admin/dashboard/index_2'), 'refresh');
					}
				} else {
					$this->session->set_flashdata('errors', 'Invalid Username or Password!');
					redirect(base_url('admin/auth/login'));
				}
			}
		} else {
			$data['title'] = 'Login';
			$data['navbar'] = false;
			$data['sidebar'] = false;
			$data['footer'] = false;
			$data['bg_cover'] = true;

			$this->load->view('admin/includes/_header', $data);
			$this->load->view('admin/auth/login');
			$this->load->view('admin/includes/_footer', $data);
		}
	}

	//-------------------------------------------------------------------------
	public function register()
	{

		if ($this->input->post('submit')) {

			// for google recaptcha
			if ($this->recaptcha_status == true) {
				if (!$this->recaptcha_verify_request()) {
					$this->session->set_flashdata('form_data', $this->input->post());
					$this->session->set_flashdata('error', 'reCaptcha Error');
					redirect(base_url('admin/auth/register'));
					exit();
				}
			}

			$this->form_validation->set_rules('irlano', 'IRLA Number', 'trim|is_unique[ci_admin.username]|required');
			$this->form_validation->set_message('is_unique', 'The %s Is Already Registered');
			$this->form_validation->set_rules('dob', 'Date Of Birth', 'trim|required|valid_date');
			$this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');
			// $this->form_validation->set_rules('lastname', 'Lastname', 'trim|required');
			// $this->form_validation->set_rules('email', 'Email', 'trim|valid_email|is_unique[ci_admin.email]|required');
			// $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[8]');
			// $this->form_validation->set_rules('confirm_password', 'Password Confirmation', 'trim|required|matches[password]');

			if ($this->form_validation->run() == FALSE) {
				// $data = array(
				// 	'errors' => validation_errors()
				// );
				$this->session->set_flashdata('form_data', $this->input->post());

				foreach ($_POST as $key => $value) {

					$data['messages'][$key] = form_error($key);
				}
				// print_r($data);
				// 	die();

				$this->session->set_flashdata('error', $data['messages']);
				$this->session->set_flashdata('irlano', $this->input->post('irlano'));
				$this->session->set_flashdata('dob', $this->input->post('dob'));
				// $this->session->set_flashdata('errors', $data['errors']);
				redirect(base_url('admin/auth/register'), 'refresh');
			} else {
				$data = array(
					'irla' => $this->input->post('irlano'),
					'date_of_birth' => $this->input->post('dob')
				);
				$data = $this->security->xss_clean($data);

				//VERIFY IF USER IN HRMS DATABASE
				$result = $this->auth_model->verify_user($data);

				if ($result) {
					// echo 'exist';
					$otp = $this->generateNumericOTP();
					// echo $otp;
					$mobile_no = $result['mobile_no'];
					$email_id = $result['email_id'];
					$date_of_birth = $result['date_of_birth'];
					$name = $result['name'];


					//1>SEND OTP/EMAIL

					$result = $this->auth_model->sendOtp($otp,$email_id,$name,$mobile_no);
					$irlano = $data['irla'];
					$dateofbirth = $data['date_of_birth'];
					$otpsaved = $this->auth_model->saveOTPForValidation($otp, $mobile_no, $email_id, $irlano);

					
					//2>store otp details in database against emailid or mobile number to which otp send
					if ($otpsaved) {
						$sessionarray = array("name"=>$name,"date_of_birth"=>$date_of_birth,"mobile_no" => $mobile_no, "email_id" => $email_id, "irla_no" => $irlano, "dob" => $dateofbirth);
						$this->session->set_userdata($sessionarray);
                                                
//                                                $curl_request = 'https://smsgw.sms.gov.in/failsafe/HttpLink?username=recttbsf.sms&pin=95gdbmqq&message='
//                                                    . 'Dear%2520User%2520'.$irlano.',%2520Your%2520OTP%2520for%2520CLMS%2520registration%2520is%2520'.$otp
//                                                    . '%2520Use%2520this%2520passcode%2520to%2520validate%2520your%2520registration%2520process.'
//                                                    . '%2520Thank%2520you.'
//                                                    . '%2520CLMS%2520TEAM&mnumber='.$mobile_no.'&signature=BSFSMS&dlt_entity_id=1701160101013178098&dlt_template_id=1707163531266975285';
                                                
                                                //$curl_request = 'https://smsgw.sms.gov.in/failsafe/HttpLink?username=recttbsf.sms&pin=95gdbmqq&message=Dear%252520User%252520%20'.$irlano.',%252520Your%252520OTP%252520for%252520CLMS%252520registration%252520is%252520.-.'.$otp.'%252520Use%252520this%252520passcode%252520to%252520validate%252520your%252520registration%252520process.%252520Thank%252520you.%252520CLMS%252520TEAM&mnumber='.$mobile_no.'&signature=BSFSMS&dlt_entity_id=1701160101013178098&dlt_template_id=1707163531266975285';
                                                $curl_request = 'https://smsgw.sms.gov.in/failsafe/HttpLink?username=recttbsf.sms&pin=95gdbmqq&message=Dear%20User%20%20'.$irlano.',%20Your%20OTP%20for%20CLMS%20registration%20is%20.-.'.$otp.'%20Use%20this%20passcode%20to%20validate%20your%20registration%20process.%20Thank%20you.%20CLMS%20TEAM&mnumber='.$mobile_no.'&signature=BSFSMS&dlt_entity_id=1701160101013178098&dlt_template_id=1707163531266975285';
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
                                                $log_result = $this->auth_model->log_curl_response($response,$curl_request);

                                                //CURL - Rquest for SMS

						//REDIRECT TO VERIFY OTP PAGE
						//$this->session->set_flashdata('success', 'Hello '.$irlano.', OTP Has Been Sent To Your Email Address Registered With BSF HRMS.');
						$this->session->set_flashdata('success', 'Hello '.$irlano.', OTP Has Been Sent To Your Mobile No - ******'.substr($mobile_no,-4).' Registered With BSF HRMS.');
                                                redirect(base_url('admin/auth/verifyotp'), 'refresh');
					} else {
						$this->session->set_flashdata('errors', 'Could Not Send OTP. Contact Admin');
						redirect(base_url('admin/auth/register'), 'refresh');
					}

				} else {
					// echo 'does not exist';

					$this->session->set_flashdata('errors', 'Invalid User Details Entered For Registration');
					redirect(base_url('admin/auth/register'), 'refresh');
				}
				// die();
				// if($result){
				// 	//sending welcome email to user
				// 	$this->load->helper('email_helper');

				// 	$mail_data = array(
				// 		'fullname' => $data['firstname'].' '.$data['lastname'],
				// 		'verification_link' => base_url('admin/auth/verify/').'/'.$data['token']
				// 	);

				// 	$to = $data['email'];

				// 	$email = $this->mailer->mail_template($to,'email-verification',$mail_data);

				// 	if($email){
				// 		$this->session->set_flashdata('success', 'Your Account has been made, please verify it by clicking the activation link that has been send to your email.');	
				// 		redirect(base_url('admin/auth/login'));
				// 	}	
				// 	else{
				// 		echo 'Email Error';
				// 	}
				// }
			}
		} else {
			$data['title'] = 'Register';
			$data['navbar'] = false;
			$data['sidebar'] = false;
			$data['footer'] = false;
			$data['bg_cover'] = true;

			$this->load->view('admin/includes/_header', $data);
			$this->load->view('admin/auth/register');
			$this->load->view('admin/includes/_footer', $data);
		}
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

	public function verifyotp()
	{
		$data['title'] = 'Verify OTP';
		$data['navbar'] = false;
		$data['sidebar'] = false;
		$data['footer'] = false;
		$data['bg_cover'] = true;

		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/auth/verifyotp');
		$this->load->view('admin/includes/_footer', $data);
	}

	public function pin()
	{
		$data['title'] = 'Enter Pin';
		$data['navbar'] = false;
		$data['sidebar'] = false;
		$data['footer'] = false;
		$data['bg_cover'] = true;

		$this->session->set_flashdata('success', 'Your email has been verified, you can now login.');
		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/auth/enterpin');
		$this->load->view('admin/includes/_footer', $data);
	}

	public function otpValidation()
	{

		if ($this->input->post('submit')) {

			$this->form_validation->set_rules('otp', 'OTP Number', 'trim|required|min_length[4]|max_length[6]');

			if ($this->form_validation->run() == FALSE) {
			

				foreach ($_POST as $key => $value) {

					$data['messages'][$key] = form_error($key);
				}

				// $this->session->set_flashdata('errors', $data['messages']);
				$this->session->set_flashdata('errors', "OTP length can not be more than 6 digits");
				$this->session->set_flashdata('otp', $this->input->post('otp'));
				redirect(base_url('admin/auth/verifyotp'), 'refresh');
			} else {
				$data = array(
					'otp_code' => $this->input->post('otp'),
					'email_id' => $this->session->userdata('email_id'),
					'isactive' => '1'
				);

				//VALIDATE ATIVE OTP IN DATABASE AGAINST EMAIL ID
				$result = $this->auth_model->validateOTP($data);


				if ($result) {
					//OTP VERIFIED BY USER


					//DEACTIVATE ALL OTPS TILL NOW
					$useremail=array(
						'email_id'=>$this->session->userdata('email_id')
					);
					$result = $this->auth_model->deactivateAllOTP($useremail);


					//ASK USER FOR PIN FOR THEIR ACCOUNT
					redirect(base_url('admin/auth/pin'));


				
				} else {

					$this->session->set_flashdata('errors', 'Invalid OTP!');
					redirect(base_url('admin/auth/verifyotp'));
				}
			}
		}
	}

	public function setpassword()
	{
		if ($this->input->post('submit')) {

			$this->form_validation->set_rules('pin', 'PIN Number', 'trim|required|min_length[4]|max_length[4]');

			if ($this->form_validation->run() == FALSE) {

				foreach ($_POST as $key => $value) {

					$data['messages'][$key] = form_error($key);
				}

				$this->session->set_flashdata('error', $data['messages']);
				$this->session->set_flashdata('otp', $this->input->post('otp'));
				redirect(base_url('admin/auth/pin'), 'refresh');
			} else {
				$data = array(
					'password' => password_hash($this->input->post('pin'), PASSWORD_BCRYPT),
					'admin_role_id' => '63',
					'username' => $this->session->userdata('irla_no'),
					'email' => $this->session->userdata('email_id'),
					'mobile_no' => $this->session->userdata('mobile_no'),
					'date_of_birth' => $this->session->userdata('date_of_birth'),
					'firstname' => $this->session->userdata('name'),
					'is_verify' => '1',
					'is_admin' => '1',
					'is_active' => '1',

				);

				$registrationresponse = $this->auth_model->newUserRegistration($data);
					if ($registrationresponse) {
							
						//ASK FOR PHOTO UPLOAD
						$this->session->set_flashdata('success', 'Your Account Has Been Activated. You Can Now Login With The Password You Set For Your Account.');
						redirect(base_url('admin/auth/login'));
					}else{
						$this->session->set_flashdata('errors', 'Server Error.');
						redirect(base_url('admin/auth/login'));
					}
			}
		}
	}

	//----------------------------------------------------------	
	public function verify()
	{

		$verification_id = $this->uri->segment(4);
		$result = $this->auth_model->email_verification($verification_id);
		if ($result) {
			$this->session->set_flashdata('success', 'Your email has been verified, you can now login.');
			redirect(base_url('admin/auth/login'));
		} else {
			$this->session->set_flashdata('success', 'The url is either invalid or you already have activated your account.');
			redirect(base_url('admin/auth/login'));
		}
	}

	//--------------------------------------------------		
	public function forgot_password()
	{

		if ($this->input->post('submit')) {
			//checking server side validation
			$this->form_validation->set_rules('email', 'Email', 'valid_email|trim|required');
			if ($this->form_validation->run() == FALSE) {
				$data = array(
					'errors' => validation_errors()
				);
				$this->session->set_flashdata('errors', $data['errors']);
				redirect(base_url('admin/auth/forget_password'), 'refresh');
			}

			$email = $this->input->post('email');
			$response = $this->auth_model->check_user_mail($email);

			if ($response) {

				$rand_no = rand(0, 1000);
				$pwd_reset_code = md5($rand_no . $response['admin_id']);
				$this->auth_model->update_reset_code($pwd_reset_code, $response['admin_id']);

				// --- sending email
				$to = $response['email'];
				$mail_data = array(
					'fullname' => $response['firstname'] . ' ' . $response['lastname'],
					'reset_link' => base_url('admin/auth/reset_password/' . $pwd_reset_code)
				);
				$this->mailer->mail_template($to, 'forget-password', $mail_data);

				if ($email) {
					$this->session->set_flashdata('success', 'We have sent instructions for resetting your password to your email');

					redirect(base_url('admin/auth/forgot_password'));
				} else {
					$this->session->set_flashdata('error', 'There is the problem on your email');
					redirect(base_url('admin/auth/forgot_password'));
				}
			} else {
				$this->session->set_flashdata('error', 'The Email that you provided are invalid');
				redirect(base_url('admin/auth/forgot_password'));
			}
		} else {

			$data['title'] = 'Forget Password';
			$data['navbar'] = false;
			$data['sidebar'] = false;
			$data['footer'] = false;
			$data['bg_cover'] = true;

			$this->load->view('admin/includes/_header', $data);
			$this->load->view('admin/auth/forget_password');
			$this->load->view('admin/includes/_footer', $data);
		}
	}

	//----------------------------------------------------------------		
	public function reset_password($id = 0)
	{

		// check the activation code in database
		if ($this->input->post('submit')) {
			$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]');
			$this->form_validation->set_rules('confirm_password', 'Password Confirmation', 'trim|required|matches[password]');

			if ($this->form_validation->run() == FALSE) {
				$data = array(
					'errors' => validation_errors()
				);

				$this->session->set_flashdata('reset_code', $id);
				$this->session->set_flashdata('errors', $data['errors']);
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			} else {
				$new_password = password_hash($this->input->post('password'), PASSWORD_BCRYPT);
				$this->auth_model->reset_password($id, $new_password);
				$this->session->set_flashdata('success', 'New password has been Updated successfully.Please login below');
				redirect(base_url('admin/auth/login'));
			}
		} else {
			$result = $this->auth_model->check_password_reset_code($id);

			if ($result) {

				$data['title'] = 'Reseat Password';
				$data['reset_code'] = $id;
				$data['navbar'] = false;
				$data['sidebar'] = false;
				$data['footer'] = false;
				$data['bg_cover'] = true;

				$this->load->view('admin/includes/_header', $data);
				$this->load->view('admin/auth/reset_password');
				$this->load->view('admin/includes/_footer', $data);
			} else {
				$this->session->set_flashdata('error', 'Password Reset Code is either invalid or expired.');
				redirect(base_url('admin/auth/forgot_password'));
			}
		}
	}

	//-----------------------------------------------------------------------
	public function logout()
	{
		$this->session->sess_destroy();
		redirect(base_url('admin/auth/login'), 'refresh');
	}

	// Get Country. State and City
	//----------------------------------------
	public function get_country_states()
	{
		$states = $this->db->select('*')->where('country_id', $this->input->post('country'))->get('ci_states')->result_array();
		$options = array('' => 'Select Option') + array_column($states, 'name', 'id');
		$html = form_dropdown('state', $options, '', 'class="form-control select2" required');
		$error =  array('msg' => $html);
		echo json_encode($error);
	}

	//----------------------------------------
	public function get_state_cities()
	{
		$cities = $this->db->select('*')->where('state_id', $this->input->post('state'))->get('ci_cities')->result_array();
		$options = array('' => 'Select Option') + array_column($cities, 'name', 'id');
		$html = form_dropdown('city', $options, '', 'class="form-control select2" required');
		$error =  array('msg' => $html);
		echo json_encode($error);
	}

	public function test_mail()
	{
		$this->load->helper('email_helper');

		$mail_data = array(
			'fullname' => 'Ujwal Jain',
			'verification_link' => base_url('admin/auth/verify/')
		);

		$to = 'ujwal.jain@aniruddhagps.com';

		$email = $this->mailer->mail_template($to, 'email-verification', $mail_data);

		if ($email) {
			$this->session->set_flashdata('success', 'Your Account has been made, please verify it by clicking the activation link that has been send to your email.');
			// redirect(base_url('admin/auth/login'));
		} else {
			echo 'Email Error';
		}
	}
}  // end class
