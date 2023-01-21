<?php defined('BASEPATH') or exit('No direct script access allowed');

class Auth_model extends CI_Model
{

	public function login($data)
	{

		$this->db->from('ci_admin');
		$this->db->join('ci_admin_roles', 'ci_admin_roles.admin_role_id = ci_admin.admin_role_id');
		$this->db->where('ci_admin.username', $data['irlano']);
		$this->db->where('ci_admin.date_of_birth', $data['dob']);
		// return true;
		$query = $this->db->get();
		if ($query->num_rows() == 0) {
			return false;
		} else {
			//Compare the password attempt with the password we have stored.
			$result = $query->row_array();
			$validPassword = password_verify($data['pin'], $result['password']);
			if ($validPassword) {
				return $result = $query->row_array();
			}
		}
	}

	//--------------------------------------------------------------------
	public function register($data)
	{
		$this->db->insert('ci_admin', $data);
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
			return true;
		} else {
			return false;
		}
	}

	//============ Check User Email ============
	function check_user_mail($irlano)
	{
		$result = $this->db->get_where('ci_admin', array('username' => $irlano));

		if ($result->num_rows() > 0) {
			$result = $result->row_array();
			return $result;
		} else {
			return false;
		}
	}

	//============ Update Reset Code Function ===================
	public function update_reset_code($reset_code, $user_id)
	{
		$data = array('password_reset_code' => $reset_code);
		$this->db->where('admin_id', $user_id);
		$this->db->update('ci_admin', $data);
	}

	//============ Activation code for Password Reset Function ===================
	public function check_password_reset_code($code)
	{

		$result = $this->db->get_where('ci_admin',  array('password_reset_code' => $code));
		if ($result->num_rows() > 0) {
			return true;
		} else {
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
		return true;
	}

	public function reset_pin($irlano, $password)
	{
		$this->db->set('password', $password);
		$this->db->where('username', $irlano);
		$this->db->update('ci_admin');
		return true;
	}

	//--------------------------------------------------------------------
	public function get_admin_detail()
	{
		$id = $this->session->userdata('admin_id');
		$query = $this->db->get_where('ci_admin', array('admin_id' => $id));
		return $result = $query->row_array();
	}

	//--------------------------------------------------------------------
	public function update_admin($data)
	{
		$id = $this->session->userdata('admin_id');
		$this->db->where('admin_id', $id);
		$this->db->update('ci_admin', $data);
		return true;
	}

	//--------------------------------------------------------------------
	public function change_pwd($data, $id)
	{
		$this->db->where('admin_id', $id);
		$this->db->update('ci_admin', $data);
		return true;
	}

	public function change_pin($data, $irla) //added by jitu for change pin
	{
		$this->db->where('username', $irla);
		$this->db->update('ci_admin', $data);
		return true;
	}

	public function verify_user($data)
	{
		$query = $this->db->get_where('bsf_hrms_data',  $data);
		if ($query->num_rows() > 0) {
			return $result = $query->row_array();
		} else {
			return false;
		}
	}



	public function verify_otp_user($data) //added by jitu
	{
		$query = $this->db->get_where('bsf_hrms_data',  $data);
		if ($query->num_rows() > 0) {
			return $result = $query->row_array();
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
		return $query;
	}

	public function validateOTP($data)
	{
		$query = $this->db->get_where('otp_log',  $data);
		if ($query->num_rows() > 0) {
			return true;
		} else {
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
			return true;
		} else {
			return false;
		}
	}

	public function newUserRegistration($data)
	{
		$this->db->insert('ci_admin', $data);
		return true;
	}

	public function deactivateAllOTP($data)
	{
		$dataarray = array(
			'isactive' => '0'
		);
		$this->db->where('email_id', $data['email_id']);
		$this->db->update('otp_log', $dataarray);
		return true;
	}

	public function fetchDetailsFromHrms($result)
	{
		//FETCHIN DATA FROM HRMS TABLE USING IRLA NUMBER
		$irlano = $result['username'];
		$this->db->select('rank, present_appoitment, status, location_name, district_name, state_name');
		$this->db->from('bsf_hrms_data');
		$this->db->where('irla', $irlano);
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}

	public function log_curl_response($response, $curl_request)
	{
		$db = $this->db;
		$query = "INSERT INTO `curl_response`(`response`, `curl_request`, `insert_time`) VALUES ('" . $response . "','" . $curl_request . "',now());";
		$query_response = $db->query($query);
		$db->close();
	}
}
