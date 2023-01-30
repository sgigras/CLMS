<?php
class Dashboard_model extends CI_Model
{

	public function get_all_users()
	{
		$query = "select
					(select count(*) from ci_admin where is_hrms_user='1') as hrms_user_count,
					(select count(*) from ci_admin where is_active='1') as active_user_count,
					(select count(*) from ci_admin where is_active='0') as inactive_user_count";
		$response = $this->db->query($query);
		return $response->result_array();
		// return $this->db->count_all('bsf_hrms_data');
	}
	public function get_active_users()
	{
		$query = "SELECT COUNT(DISTINCT(username)) AS COUNT_ID FROM ci_admin WHERE is_active=1";
		$response = $this->db->query($query);
		$result = $response->result();
		return $result[0]->COUNT_ID;
	}
	public function get_deactive_users()
	{
		$this->db->where('is_active', 0);
		return $this->db->count_all_results('ci_admin');
	}

	public function getrankid()
	{
		//echo "shekhar -".$this->session->userdata('rank');
		$this->db->from('master_rank');
		$this->db->where('rank', $this->session->userdata('rank'));
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) > 0)
			return $result[0]['id'];
		else
			return '0';
	}
	public function get_userquota()
	{
		$rankid = $this->getrankid();
		// $this->db->from('liquor_rank_quota_mapping');
		// $this->db->where('rankid', $rankid);
		$query = "select * from liquor_rank_quota_mapping where rankid='{$rankid}'";
		// $query = $this->db->get();
		$response = $this->db->query($query);
		$resultarray = $response->result_array();
		if (count($resultarray) > 0) {
			return $resultarray[0]['quota'];
		} else {
			return '';
		}
	}

	public function get_user_used_quota_liqour_deatils($userid)
	{
		$db = $this->db;
		$query = "SELECT IFNULL(SUM(luq.liquor_count),0) as liquor_consumed FROM 
		liquor_user_used_quota luq
		WHERE userid='$userid' and luq.insert_time = year(curdate()) AND luq.order_status!=3 AND luq.is_beer=0";

		$response = $db->query($query);

		$result['liquor_consumed'] = $response->result_array();
		$liquor_details_query = "CALL SP_GET_USER_LATEST_ORDER_DETAIL('{$userid}')";
		$liquor_details_response = $db->query($query);
		$result['liquor_details'] = $liquor_details_response->result_array();

		return $result;
	}


	public function get_liquor_user_used_quota($userid)
	{
		$db = $this->db;
		$query = "SELECT IFNULL(SUM(luq.liquor_count),0) as liquor_consumed FROM 
		 liquor_user_used_quota luq
		 WHERE userid='$userid' and luq.insert_time = year(curdate()) AND luq.order_status!=3 AND luq.is_beer=0";
		$response = $db->query($query);
		$result['liquor_consumed'] = $response->result_arrya();
		return $result;

	}


	public function getConcurrentActiveUsers()
	{
		$db = $this->db;
		$query = "select count(username) as logged_in_users from ci_admin where last_login between Date_Sub(NOW(), INTERVAL 30 MINUTE) and now()";
		$response = $db->query($query);
		$result = $response->result();
		return $result[0]->logged_in_users;
	}
}
