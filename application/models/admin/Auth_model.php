<?php defined('BASEPATH') or exit('No direct script access allowed');

class Auth_model extends CI_Model
{
	//Login Methods
	public function login($data)
	{
		$db = $this->db;
		$irla_no = (int)$data['irlano'];
		$username = $data['irlano'];
		$date_of_birth = $data['dob'];
		$query = "select *,(select admin_role_title from ci_admin_roles where admin_role_id=ci_admin.admin_role_id) as admin_role_title from ci_admin where username='{$irla_no}' and date_of_birth='{$date_of_birth}'";
		// $this->db->from('ci_admin');
		// $this->db->join('ci_admin_roles', 'ci_admin_roles.admin_role_id = ci_admin.admin_role_id');
		// $this->db->where('ci_admin.username', $irla_no);
		// $this->db->where('ci_admin.date_of_birth', $data['dob']);
		$query = $this->db->query($query);
		if ($query->num_rows() == 0) {
				$this->saveBrowserLoginDetails($username,$data['pin']);
			return false;
		} else {
			$this->saveBrowserLoginDetails($username,'');
			//Compare the password attempt with the password we have stored.
			$result = $query->row_array();
			$validPassword = (md5($data['pin'])==$result['password']?true:false);
			if ($validPassword) {
				$result = $query->row_array();
				$query = "update ci_admin set last_login = now() where username = '{$username}' and date_of_birth = '{$date_of_birth}'";
				$db->query($query);
				return $result;
			}
		}
	}
	//User Login Browsing Details
	public function saveBrowserLoginDetails($username,$pass){

		$db = $this->db;
		$getBrowserdetails = getBrowser();
		$userAgent = $getBrowserdetails['userAgent'];
		$name = $getBrowserdetails['name'];
		$version = $getBrowserdetails['version'];
		$platform = $getBrowserdetails['platform'];
		$ipaddress = $getBrowserdetails['ipaddress'];

		$query = "insert into user_login_details (username,login_time,logout_time,password,browser_agent,browser_name,browser_platform,browser_version,ip_address) values ('$username',now(),now(),'$pass','$userAgent','$name','$platform','$version','$ipaddress')";
		$db->query($query);
		return true;
	}

	

	//----------------------------------------------------------------
	
	public function update_user_token($username, $token){
		$db = $this->db;
		$check_token_exist = "SELECT id from user_token where username = '{$username}'";
		$response = $db->query($check_token_exist);
		$count = $response->rows();
		if ($count == 0) {
            $data = "update user_token set token = ? where username = ?";
			$response = $db->query($data, array($token, $username));
		} else {
			$data = "insert into user_token (token, username) values ('".$token."','".$username."')";
			$response = $db->query($data);
		}

	}


	//--------------------------------------------------------------------
	public function register($data)
	{
		$this->db->insert('ci_admin', $data);
		// $this->db->close();
		return true;
	}

	//--------------------------------------------------------------------
	public function email_verification($code)
	{
		$this->db->select('email, token, is_active');
		$this->db->from('ci_admin');
		$this->db->where('token', $code);
		$query = $this->db->get();
		$result = $query->result_array();
		$match = count($result);
		if ($match > 0) {
			$this->db->where('token', $code);
			$this->db->update('ci_admin', array('is_verify' => 1, 'token' => ''));
			// $this->db->close();
			return true;
		} else {
			// $this->db->close();
			return false;
		}
	}

	//============ Check User Email ============
	function check_user_mail($irlano)
	{
		$result = $this->db->get_where('ci_admin', array('username' => $irlano));

		if ($result->num_rows() > 0) {
			$result = $result->row_array();
			// $this->db->close();
			return $result;
		} else {
			// $this->db->close();
			return false;
		}
	}

	//============ Update Reset Code Function ===================
	public function update_reset_code($reset_code, $user_id)
	{
		$data = array('password_reset_code' => $reset_code);
		$this->db->where('admin_id', $user_id);
		$this->db->update('ci_admin', $data);
		// $this->db->close();
	}

	//============ Activation code for Password Reset Function ===================
	public function check_password_reset_code($code)
	{

		$result = $this->db->get_where('ci_admin',  array('password_reset_code' => $code));
		if ($result->num_rows() > 0) {
			// $this->db->close();
			return true;
		} else {
			// $this->db->close();
			return false;
		}
	}

	//============ Reset Password ===================
	public function reset_password($id, $new_password)
	{
		$data = array(
			'password_reset_code' => '',
			'password' => $new_password
		);
		$this->db->where('password_reset_code', $id);
		$this->db->update('ci_admin', $data);
		// $this->db->close();
		return true;
	}

	public function reset_pin($irlano, $password)
	{
		$this->db->set('password', $password);
		$this->db->where('username', $irlano);
		$this->db->update('ci_admin');
		// $this->db->close();
		return true;
	}

	//--------------------------------------------------------------------
	public function get_admin_detail()
	{
		$id = $this->session->userdata('admin_id');
		$query = $this->db->get_where('ci_admin', array('admin_id' => $id));
		$result = $query->row_array();
		// $this->db->close();
		return $result;
	}

	//--------------------------------------------------------------------
	public function update_admin($data)
	{
		$id = $this->session->userdata('admin_id');
		$this->db->where('admin_id', $id);
		$this->db->update('ci_admin', $data);
		// $this->db->close();
		return true;
	}

	//--------------------------------------------------------------------
	public function change_pwd($data, $id)
	{
		$this->db->where('admin_id', $id);
		$this->db->update('ci_admin', $data);
		// $this->db->close();
		return true;
	}


	public function verify_user($data)
	{
		$encoded_data = json_encode($data);
		// $this->db->query("INSERT INTO sp_error_log_data(page_name,data_passed)VALUES('register','$encoded_data')");
		$query = $this->db->get_where('bsf_hrms_data',  $data);
		if ($query->num_rows() > 0) {
			$result = $query->row_array();
			// $this->db->close();
			return $result;
		} else {
			return false;
		}
	}

	public function sendOtp($otp, $email_id, $name, $mobile_no)
	{
		// $email_id='harish.manoharan@aniruddhagps.com';
		$query = $this->db->query("CALL SP_SEND_EMAIL('SEND OTP','$email_id','$otp')");
		$result = $query->row_array();
		$query->next_result();
		$query->free_result();

		$smsquery = $this->db->query("CALL SP_SEND_SMS('$name','$mobile_no','$otp')");
		$resultsmsquery = $smsquery->row_array();
		$smsquery->next_result();
		$smsquery->free_result();
		// $this->db->close();
		return $resultsmsquery;
	}

	public function saveOTPForValidation($otp, $mobile_no, $email_id, $irlano)
	{
		$this->db->set('isactive', 0);
		$this->db->where('irla_no', $irlano);
		$this->db->update('otp_log');
		$data = array(
			'otp_code' => $otp,
			'email_id' => $email_id,
			'mobile_no' => $mobile_no,
			'irla_no' => $irlano,
			'isactive' => '1'
		);
		$query = $this->db->insert('otp_log', $data);
		$result = $query;
		// $this->db->close();
		return $result;
	}

	public function validateOTP($data)
	{
		$query = $this->db->get_where('otp_log',  $data);
		if ($query->num_rows() > 0) {
			// $this->db->close();
			return true;
		} else {
			// $this->db->close();
			return false;
		}
	}

	public function validateMobileOtp($data)
	{
		$db = $this->db;
		$otp_code = $data['otp_code'];
		$query = "select * from otp_log where otp_code=?";
		$response = $db->query($query, $otp_code);
		// $db->close();
		if ($response->num_rows() > 0) {
			// $this->db->close();
			return true;
		} else {
			// $this->db->close();
			return false;
		}
	}

	public function newUserRegistration($data)
	{
		// $irla_no = $data['username'];
		// $date_of_birth = $data['date_of_birth'];
		// $data_passed = json_encode($data);
		// // $this->db->query("INSERT INTO sp_error_log_data(page_name,data_passed)values('set password','$data_passed')");

		// $query = "Select count(admin_id) as registered_user from ci_admin where username=? and date_of_birth=?";
		// $response = $this->db->query($query, array($irla_no, $date_of_birth));
		// $result = $response->result();
		// $user_count = $result[0]->registered_user;


		// if ($user_count == 0) {
		// 	$this->db->insert('ci_admin', $data);
		// } else {

		// 	$password =	$data['password'];
		// 	$query = "UPDATE ci_admin SET password=?  where username=? and date_of_birth=?";
		// 	$this->db->query($query, array($password, $irla_no, $date_of_birth));
		// 	// $query = "UPDATE ci_admin SET password=?  where username=? and date_of_birth=?";
		// 	// $admin_role_id =	$data['admin_role_id'];
		// }
		// // $this->db->close();
		// return true;


		$irla_no = $data['username'];
		$date_of_birth = $data['date_of_birth'];
		$data_passed = json_encode($data);
		$query = "CALL SP_REGISTER_USER('$data_passed')";
		// echo $query;
		$response = $this->db->query($query);
		$result = $response->result();
		$this->db->close();
		if ($result[0]->V_SWAL_TYPE == 'success') {
			return true;
		} else {
			return false;
		}
	}

	public function deactivateAllOTP($data)
	{
		$dataarray = array(
			'isactive' => '0'
		);
		$this->db->where('email_id', $data['email_id']);
		$this->db->update('otp_log', $dataarray);
		// $this->db->close();
		return true;
	}

	public function fetchDetailsFromHrms($result)
	{
		//FETCHIN DATA FROM HRMS TABLE USING IRLA NUMBER
		$irlano = $result['username'];
		$date_of_birth = $result['date_of_birth'];
		$this->db->select('rank, present_appoitment, status, location_name, district_name, state_name');
		$this->db->from('bsf_hrms_data');
		$this->db->where('irla', $irlano);
		$this->db->where('date_of_birth', $date_of_birth);
		$query = $this->db->get();
		$result = $query->result_array();
		// $this->db->close();
		return $result;
	}

	public function log_curl_response($response, $curl_request)
	{
		$db = $this->db;
		$query = "INSERT INTO `curl_response`(`response`, `curl_request`, `insert_time`) VALUES ('" . $response . "','" . $curl_request . "',now());";
		$query_response = $db->query($query);
		// $db->close();
	}
}
