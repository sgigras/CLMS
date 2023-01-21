<?php
header("Access-Control-Allow-Origin: *");

class UsersAPI extends MY_Controller
{
	function __construct()
	{

		parent::__construct();

		// $this->load->model('admin/admin_model', 'admin');
		// $this->load->model('admin/Activity_model', 'activity_model');
		$this->load->model('mobile/User_model', 'user_model');
		$this->load->model('admin/auth_model', 'auth_model');
		$this->load->helper(array('custom_email_helper'));
	}

	//-----------------------------------------------------		
	public function login()
	{
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

	public function loginold()
	{
		$data = json_decode(file_get_contents('php://input'));
		$parts = explode('/', $data->dob);
		$yyyy_mm_dd = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
		$data->dob = $yyyy_mm_dd;
		$data = (array)$data;
		$userdetailsarray = [];


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
		$parts = explode('/', $data->date_of_birth);
		$yyyy_mm_dd = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
		$data->date_of_birth = $yyyy_mm_dd;
		$data->irla = ltrim($data->irla, "0");;
		//TYPECAST OBJ TO ARRAY
		$data = (array)$data;
		$data['is_verified'] = 1;
		$userdetailsarray = "";
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

			$result = $this->auth_model->sendOtp($otp, $email_id, $name, $mobile_no);
			$irlano = $data['irla'];
			$dateofbirth = $data['date_of_birth'];
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

				if ($email_id != '' && $email_id != 'null' && $email_id != NULL) {
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
					<br>To Know More Go To Our Website https://clms.bsf.gov.in/admin/auth/login<br>This is an auto generated mail. Please do not reply back to this.<br><br><br>Thanks<br>Aniruddha Telemetry Systems';
					$cc = "";
					$attach = "";
					$email_response = sendEmail_BSF($subject, $msg_body, $email_id, $cc, $attach);
				}
			} else {



				$userdetailsarray = array("status" => "fail");
			}
		} else {
			// echo 'does not exist';

			$userdetailsarray = array("status" => "not_found");
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
		$statusarray = [];
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
		if ($registrationresponse) {

			//ASK FOR PHOTO UPLOAD
			$statusarray = array("status" => "success");
		} else {
			$statusarray = array("status" => "failed");
		}

		echo json_encode($statusarray);
	}
}
