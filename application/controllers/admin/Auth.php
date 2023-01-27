<?php defined('BASEPATH') or exit('No direct script access allowed');
class Auth extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->session->set_flashdata('error', null);
		$this->load->library('mailer');
		$this->load->model('admin/auth_model', 'auth_model');
		$this->load->library(array('Cronslib/Smslib', 'Ats/atsuser'));
		$this->load->helper(array('Ats/curl'));
		$this->load->helper(array('custom_email_helper'));
		$this->load->helper(array('browser_ip'));
		$this->load->model('ssoauthorization/HimveerSSOAuthModel', 'SSO_model');
	}
	public function login()
	{
			if ($this->input->post('submit')) {
				$this->form_validation->set_rules('irlano', 'IRLA Number', 'trim|required|numeric|min_length[3]|max_length[9]');
				$this->form_validation->set_message('min_length', 'IRLA Number should have atleast 8 digits');
				$this->form_validation->set_message('max_length', 'IRLA Number cannot exceed 9 digits');
				$this->form_validation->set_rules('dob', 'Date Of Birth', 'trim|required|valid_date');
				$this->form_validation->set_rules('pin', 'Pin', 'trim|required|min_length[4]|max_length[15]');
				$this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');
				if ($this->form_validation->run() == FALSE) {	
					foreach ($_POST as $key => $value) {
						$data['messages'][$key] = form_error($key);
					}
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
							//$additionaldata = $this->auth_model->fetchDetailsFromHrms($result);
							$token = $this->getToken(50);
							$admin_data = array(
								'admin_id' => $result['admin_id'],
								'entity_id' => (isset($result['entity_id'])) ? $result['entity_id'] : '',
								'username' => $result['username'],
								'rank' => $result['rank'],
								'mobile_no' => $result['mobile_no'],
								'full_name' => $result['firstname'],
								'admin_role_id' => $result['admin_role_id'],
								'admin_role' => $result['admin_role_title'],
								'is_supper' => $result['is_supper'],
								'is_admin_login' => TRUE,
								'profile_picture' => $result['image'],
								'token' => $token,
								'IsHimveerLogin'=>FALSE
							);
							$this->session->set_userdata($admin_data);
							$this->atsuser->update_user_token($this->session->userdata('username'),
							$this->session->userdata('token'));
							$this->rbac->set_access_in_session();
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
			$authurl = $this->SSO_model->ImplicitLoginUrl();
			$modelArray = array();
			$modelArray["AuthUrl"] = $authurl;
			$this->load->view('admin/includes/_header', $data);
			$this->load->view('admin/auth/login',$modelArray);
			$this->load->view('admin/includes/_footer', $data);
		}
	}
	public function sendMailTest()
	{
		$subject = "BSF-CLMS OTP FOR REGISTRATION";
		$msg_body = 'Dear User, <br><br>To complete user registration on clms kindly enter OTP as shown below.<br>
			<table style="width:100%;" border="0" align="center" cellpadding="10" cellspacing="0" id = "FIRST">
				<tr height="37px;">
					<th colspan= "2" style = "font: bold 13px Trebuchet MS, Verdana, Arial, Helvetica, sans-serif; color: #4f6b72;border-right: 1px solid #C1DAD7; border-top: 1px solid #C1DAD7;border-left: 1px solid #C1DAD7;letter-spacing: 2px;text-transform: uppercase;padding: 6px 6px 6px 12px;background: #CAE8EA  no-repeat; text-align:left;font-weight:bold;"><center>USER REGISTRATION </center></th>
				</tr>
				<tr>
					<th style = "font: bold 11px Trebuchet MS, Verdana, Arial, Helvetica, sans-serif; color: #4f6b72; border-bottom: 1px solid #C1DAD7;border-top: 1px solid #C1DAD7;border-left: 1px solid #C1DAD7;letter-spacing: 2px;text-transform: uppercase;padding: 6px 6px 6px 12px;background: #CAE8EA  no-repeat; text-align:left;"><strong>OTP</strong></th>
					<td style = "padding: 6px 6px 6px 12px;border-bottom: 1px solid #C1DAD7;border-left: 1px solid #C1DAD7;border-top: 0;font:12px Trebuchet MS, Verdana, Arial, Helvetica, sans-serif; border-right: 1px solid #C1DAD7;">12452</td>
				</tr>
				</table>
			<br>To Know More Go To Our Website https://clms.bsf.gov.in/admin/auth/login<br>This is an auto generated mail. Please do not reply back to this.<br><br><br>Thanks<br>BSF-CLMS TEAM';
		$cc = "";
		$attach = "";
		$email_response = sendEmail_BSF_TEST($subject, $msg_body, 'amit.ashok@aniruddhagps.com', $cc, $attach);
	}
	public function sendAllSms()
	{
		$this->smslib->fetchSMSToSend();
		$this->smslib->insertCronLog();
	}
	function sendSMS()
	{
		$curl_request = 'https://smsgw.sms.gov.in/failsafe/HttpLink?username=recttbsf.sms&pin=95gdbmqq&message=Dear%20User%20%2021304,%20Your%20OTP%20for%20CLMS%20registration%20is%20.-.144490%20Use%20this%20passcode%20to%20validate%20your%20registration%20process.%20Thank%20you.%20CLMS%20TEAM&mnumber=7738544503&signature=BSFSMS&dlt_entity_id=1701160101013178098&dlt_template_id=1707163531266975285';
		echo $curl_request;
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
		));
		$response = curl_exec($curl);
		curl_close($curl);
	}
	public function index()
	{
		if ($this->session->has_userdata('is_admin_login')) {
			redirect('admin/dashboard');
		} else {
			redirect('admin/auth/login');
		}
	}
	
	public function register()
	{
		if ($this->input->post('submit')) {
			$this->form_validation->set_rules('irlano', 'IRLA Number', 'trim|is_unique[ci_admin.username]|required|min_length[3]|max_length[9]');
			$this->form_validation->set_message('min_length', 'IRLA Number should have atleast 8 digits');
			$this->form_validation->set_message('max_length', 'IRLA Number cannot exceed 9 digits');
			$this->form_validation->set_message('is_unique', 'The %s Is Already Registered');
			$this->form_validation->set_rules('dob', 'Date Of Birth', 'trim|required|valid_date');
			$this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');
			if ($this->form_validation->run() == FALSE) {
				$this->session->set_flashdata('form_data', $this->input->post());
				foreach ($_POST as $key => $value) {
					$data['messages'][$key] = form_error($key);
				}
				$this->session->set_flashdata('error', $data['messages']);
				$this->session->set_flashdata('irlano', $this->input->post('irlano'));
				$this->session->set_flashdata('dob', $this->input->post('dob'));
				redirect(base_url('admin/auth/register'), 'refresh');
			} else {
				$data = array(
					'irla' => (int)$this->input->post('irlano'),
					'date_of_birth' => $this->input->post('dob')
				);
				$data = $this->security->xss_clean($data);
				$result = $this->auth_model->verify_user($data);
				if ($result) {
					$otp = $this->generateNumericOTP();	 	
					$mobile_no = $result['mobile_no'];
					$date_of_birth = $result['date_of_birth'];
					$name = $result['name'];
					$result = $this->auth_model->sendOtp($otp, $email_id, $name, $mobile_no);
					$irlano = $data['irla'];
					$dateofbirth = $data['date_of_birth'];
					$otpsaved = $this->auth_model->saveOTPForValidation($otp, $mobile_no, $email_id, $irlano);
					if ($otpsaved) {
						$sessionarray = array("name" => $name, "date_of_birth" => $date_of_birth, "mobile_no" => $mobile_no, "email_id" => $email_id, "irla_no" => $irlano, "dob" => $dateofbirth);
						$this->session->set_userdata($sessionarray);
						$curl_request = 'https://smsgw.sms.gov.in/failsafe/HttpLink?username=recttbsf.sms&pin=95gdbmqq&message=Dear%20User%20%20' . $irlano . ',%20Your%20OTP%20for%20CLMS%20registration%20is%20' . $otp . '%20Use%20this%20passcode%20to%20validate%20your%20registration%20process.%20Thank%20you.%20CLMS%20TEAM&mnumber=' . $mobile_no . '&signature=BSFSMS&dlt_entity_id=1701160101013178098&dlt_template_id=1707163531266975285';
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
						$log_result = $this->auth_model->log_curl_response($response, $curl_request);
						if ($email_id != '' && $email_id != 'null' && $email_id != NULL) {
							$subject = "BSF-CLMS OTP FOR REGISTRATION";
							$msg_body = 'Dear User, <br><br>To complete user registration on clms kindly enter OTP as shown below.<br>
								<table style="width:100%;" border="0" align="center" cellpadding="10" cellspacing="0" id = "FIRST">
									<tr height="37px;">
										<th colspan= "2" style = "font: bold 13px Trebuchet MS, Verdana, Arial, Helvetica, sans-serif; color: #4f6b72;border-right: 1px solid #C1DAD7; border-top: 1px solid #C1DAD7;border-left: 1px solid #C1DAD7;letter-spacing: 2px;text-transform: uppercase;padding: 6px 6px 6px 12px;background: #CAE8EA  no-repeat; text-align:left;font-weight:bold;"><center>USER REGISTRATION </center></th>
									</tr>
									<tr>
										<th style = "font: bold 11px Trebuchet MS, Verdana, Arial, Helvetica, sans-serif; color: #4f6b72; border-bottom: 1px solid #C1DAD7;border-top: 1px solid #C1DAD7;border-left: 1px solid #C1DAD7;letter-spacing: 2px;text-transform: uppercase;padding: 6px 6px 6px 12px;background: #CAE8EA  no-repeat; text-align:left;"><strong>OTP</strong></th>
										<td style = "padding: 6px 6px 6px 12px;border-bottom: 1px solid #C1DAD7;border-left: 1px solid #C1DAD7;border-top: 0;font:12px Trebuchet MS, Verdana, Arial, Helvetica, sans-serif; border-right: 1px solid #C1DAD7;">' . $otp . '</td>
									</tr>
									</table>
								<br>To Know More Go To Our Website https://clms.bsf.gov.in/admin/auth/login<br>This is an auto generated mail. Please do not reply back to this.<br><br><br>Thanks<br>BSF-CLMS TEAM';
							$cc = "";
							$attach = "";
							$email_response = sendEmail_BSF($subject, $msg_body, $email_id, $cc, $attach);
						}
						$mobile = substr($mobile_no, 7);
						$email = explode("@", $email_id);
						if ($mobile_no == 0 && ($email_id != '' && $email_id != 'null' && $email_id != NULL)) {
							$this->session->set_flashdata('success', 'Hello ' . $name . ', OTP has been sent to your registered email address ' . $email[0] . '@XXXXX and your mobile number was not found in BSF HRMS.');
							redirect(base_url('admin/auth/verifyotp'), 'refresh');
						} else if ($mobile_no != 0 && ($email_id == '' || $email_id == 'null' || $email_id == NULL)) {
							$this->session->set_flashdata('success', 'Hello ' . $name . ', OTP has been sent to your registered mobile number XXXXXXX' . $mobile . ' and your email address was not found in BSF HRMS.');
							redirect(base_url('admin/auth/verifyotp'), 'refresh');
						} else if ($mobile_no != 0 && ($email_id != '' || $email_id != 'null' || $email_id != NULL)) {
							$this->session->set_flashdata('success', 'Hello ' . $name . ', OTP has been sent to your registered email address ' . $email[0] . '@XXXXX as well as on registered mobile number XXXXXXX' . $mobile . ' with BSF HRMS.');
							redirect(base_url('admin/auth/verifyotp'), 'refresh');
						} else {
							$this->session->set_flashdata('errors', 'Your mobile number & email address has not registered with BSF HRMS. please contact admin');
							redirect(base_url('admin/auth/register'), 'refresh');
						}
					} else {
						$this->session->set_flashdata('errors', 'Could Not Send OTP. Contact Admin');
						redirect(base_url('admin/auth/register'), 'refresh');
					}
				} else {
					$this->session->set_flashdata('errors', 'Invalid User Details Entered For Registration');
					redirect(base_url('admin/auth/register'), 'refresh');
				}
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
	public function generateNumericOTP()
	{
		$n = 6;
		$generator = "1357902468";
		$result = "";
		for ($i = 1; $i <= $n; $i++) {
			$result .= substr($generator, (rand() % (strlen($generator))), 1);
		}
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
				$this->session->set_flashdata('errors', "OTP length can not be more than 6 digits");
				$this->session->set_flashdata('otp', $this->input->post('otp'));
				redirect(base_url('admin/auth/verifyotp'), 'refresh');
			} else {
				$data = array(
					'otp_code' => $this->input->post('otp'),
					'email_id' => $this->session->userdata('email_id'),
					'isactive' => '1'
				);
				$result = $this->auth_model->validateOTP($data);
				if ($result) {
					$useremail = array(
						'email_id' => $this->session->userdata('email_id')
					);
					$result = $this->auth_model->deactivateAllOTP($useremail);
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
			$this->form_validation->set_rules('confirm_pin', 'Confirm Pin', 'trim|required|matches[pin]');
			if ($this->form_validation->run() == FALSE) {
				foreach ($_POST as $key => $value) {
					$data['messages'][$key] = form_error($key);
				}
				$this->session->set_flashdata('error', $data['messages']);
				$this->session->set_flashdata('otp', $this->input->post('otp'));
				redirect(base_url('admin/auth/pin'), 'refresh');
			} else {
				if ($this->session->userdata('isforgotpass') == "YES") {
					$newpassword = password_hash($this->input->post('pin'), PASSWORD_BCRYPT);
					$irlano = $this->session->userdata('irla_no');
					$passwordretresponse = $this->auth_model->reset_pin($irlano, $newpassword);
					if ($passwordretresponse) {
						$this->session->set_flashdata('success', 'Your Password Has Reset Successfully. You Can Now Login With The New Password You Set For Your Account.');
						redirect(base_url('admin/auth/login'));
					} else {
						$this->session->set_flashdata('errors', 'Server Error.');
						redirect(base_url('admin/auth/login'));
					}
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
						$this->session->set_flashdata('success', 'Your Account Has Been Activated. You Can Now Login With The Password You Set For Your Account.');
						redirect(base_url('admin/auth/login'));
					} else {
						$this->session->set_flashdata('errors', 'Server Error.');
						redirect(base_url('admin/auth/login'));
					}
				}
			}
		}
	}
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
	public function forgot_password()
	{
		if ($this->input->post('submit')) {
			$this->form_validation->set_rules('irlano', 'IRLA Number', 'trim|required|numeric|min_length[3]|max_length[9]');
			$this->form_validation->set_rules('dob', 'Date Of Birth', 'trim|required|valid_date');
			if ($this->form_validation->run() == FALSE) {
				$data = array(
					'errors' => validation_errors()
				);
				$this->session->set_flashdata('errors', $data['errors']);
				redirect(base_url('admin/auth/forgot_password'), 'refresh');
			}
			$irlano = $this->input->post('irlano');
			$date_of_birth = $this->input->post('dob');
			if ($result) {
				$otp = $this->generateNumericOTP();
				$mobile_no = $result['mobile_no'];
				$email_id = $result['email'];
				$date_of_birth = $result['date_of_birth'];
				$name = $result['firstname'];
				if ($mobile_no == 0) {
					$this->session->set_flashdata('errors', 'Your Mobile Number is Not registered with HRMS , Kindly contact your Administrator');
					redirect(base_url('admin/auth/register'), 'refresh');
				} else {
					if ($otpsaved) {
						$sessionarray = array("name" => $name, "date_of_birth" => $date_of_birth, "mobile_no" => $mobile_no, "irla_no" => $irlano, "dob" => $date_of_birth, "isforgotpass" => "YES");
						$this->session->set_userdata($sessionarray);
						$curl_request = 'https://smsgw.sms.gov.in/failsafe/HttpLink?username=recttbsf.sms&pin=95gdbmqq&message=Dear%20User%20%20' . $irlano . ',%20Your%20OTP%20for%20CLMS%20registration%20is%20' . $otp . '%20Use%20this%20passcode%20to%20validate%20your%20registration%20process.%20Thank%20you.%20CLMS%20TEAM&mnumber=' . $mobile_no . '&signature=BSFSMS&dlt_entity_id=1701160101013178098&dlt_template_id=1707163531266975285';
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
						$log_result = $this->auth_model->log_curl_response($response, $curl_request);
						if ($email_id != '' && $email_id != 'null' && $email_id != NULL) {
							$subject = "BSF-CLMS OTP FOR REGISTRATION";
							$msg_body = 'Dear User, <br><br>To complete user registration on clms kindly enter OTP as shown below.<br>
								<table style="width:100%;" border="0" align="center" cellpadding="10" cellspacing="0" id = "FIRST">
									<tr height="37px;">
										<th colspan= "2" style = "font: bold 13px Trebuchet MS, Verdana, Arial, Helvetica, sans-serif; color: #4f6b72;border-right: 1px solid #C1DAD7; border-top: 1px solid #C1DAD7;border-left: 1px solid #C1DAD7;letter-spacing: 2px;text-transform: uppercase;padding: 6px 6px 6px 12px;background: #CAE8EA  no-repeat; text-align:left;font-weight:bold;"><center>USER REGISTRATION </center></th>
									</tr>
									<tr>
										<th style = "font: bold 11px Trebuchet MS, Verdana, Arial, Helvetica, sans-serif; color: #4f6b72; border-bottom: 1px solid #C1DAD7;border-top: 1px solid #C1DAD7;border-left: 1px solid #C1DAD7;letter-spacing: 2px;text-transform: uppercase;padding: 6px 6px 6px 12px;background: #CAE8EA  no-repeat; text-align:left;"><strong>OTP</strong></th>
										<td style = "padding: 6px 6px 6px 12px;border-bottom: 1px solid #C1DAD7;border-left: 1px solid #C1DAD7;border-top: 0;font:12px Trebuchet MS, Verdana, Arial, Helvetica, sans-serif; border-right: 1px solid #C1DAD7;">' . $otp . '</td>
									</tr>
									</table>
								<br>To Know More Go To Our Website https://clms.bsf.gov.in/admin/auth/login<br>This is an auto generated mail. Please do not reply back to this.<br><br><br>Thanks<br>BSF-CLMS TEAM';
							$cc = "";
							$attach = "";
							$email_response = sendEmail_BSF($subject, $msg_body, $cc, $attach);
						}
						$this->session->set_flashdata('success', 'Hello ' . $irlano . ', OTP Has Been Sent To Your Email Address & Mobile Number Registered With BSF HRMS.');
						redirect(base_url('admin/auth/verifyotp'), 'refresh');
					} else {
						$this->session->set_flashdata('errors', 'Could Not Send OTP. Contact Admin');
						redirect(base_url('admin/auth/register'), 'refresh');
					}
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
	public function reset_password($id = 0)
	{
		if ($this->input->post('submit')) {
			$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[4]');
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
	public function logout()
	{
		$authurl = $this->SSO_model->HimveerLogoutUrl();
		if ($authurl == "") {
			$this->session->sess_destroy();
			redirect(base_url('admin/auth/login'), 'refresh');
		}
		else
		{
			$this->session->sess_destroy();
			redirect($authurl);
		}
	}
	public function get_country_states()
	{
		$states = $this->db->select('*')->where('country_id', $this->input->post('country'))->get('ci_states')->result_array();
		echo json_encode($states);
	}
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
		$email_id = "ujwal.jain@aniruddhagps.com";
		$otp = 1234;
		$subject = "OTP FOR REGISTRATION";
		$msg_body = 'Dear User, <br><br>To complete user registration enter OTP as shown below.<br>
								<table style="width:100%;" border="0" align="center" cellpadding="10" cellspacing="0" id = "FIRST">
									<tr height="37px;">
										<th colspan= "2" style = "font: bold 13px Trebuchet MS, Verdana, Arial, Helvetica, sans-serif; color: #4f6b72;border-right: 1px solid #C1DAD7; border-top: 1px solid #C1DAD7;border-left: 1px solid #C1DAD7;letter-spacing: 2px;text-transform: uppercase;padding: 6px 6px 6px 12px;background: #CAE8EA  no-repeat; text-align:left;font-weight:bold;"><center>USER REGISTRATION </center></th>
									</tr>
									<tr>
										<th style = "font: bold 11px Trebuchet MS, Verdana, Arial, Helvetica, sans-serif; color: #4f6b72; border-bottom: 1px solid #C1DAD7;border-top: 1px solid #C1DAD7;border-left: 1px solid #C1DAD7;letter-spacing: 2px;text-transform: uppercase;padding: 6px 6px 6px 12px;background: #CAE8EA  no-repeat; text-align:left;"><strong>OTP</strong></th>
										<td style = "padding: 6px 6px 6px 12px;border-bottom: 1px solid #C1DAD7;border-left: 1px solid #C1DAD7;border-top: 0;font:12px Trebuchet MS, Verdana, Arial, Helvetica, sans-serif; border-right: 1px solid #C1DAD7;">' . $otp . '</td>
									</tr>
									</table>
								<br>To Know More Go To Our Website https://server.aniruddhagps.in/bsfdev/CLMS/admin<br>This is an auto generated mail. Please do not reply back to this.<br><br><br>Thanks<br>Aniruddha Telemetry Systems';
		$cc = "";
		$attach = "";
		$email_response = sendEmail_BSF($subject, $msg_body, $email_id, $cc, $attach);
	}
	public function getToken($length){
		$token = "";
		$codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
		$codeAlphabet.= "0123456789";
	  $max = strlen($codeAlphabet);
	
	  for ($i=0; $i < $length; $i++) {
		$token .= $codeAlphabet[random_int(0, $max-1)];
	  }
	
	  return $token;
	}
}
