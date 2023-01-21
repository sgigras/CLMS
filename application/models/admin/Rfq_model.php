<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Rfq_model extends CI_Model{


	function get_transporter_plants($plant_id)
	{
		$fetch_plants="SELECT id,plant_name_for_gocomet FROM master_plant WHERE id IN ($plant_id) AND id NOT IN (1,2,3,4)";
		$result=$this->db->query($fetch_plants);
		return $result->result_array();
	}

	function truck_type()
	{
		$this->db->select('*');
		$this->db->from('truck_type_master');
		$query=$this->db->get();
		return $query->result_array();
	}

	function add_rfq($Rfq_data){
		$this->db->insert('rfq_gocomet', $Rfq_data);
		return true;
	}

	function cost_reduction_rate() {
		$db = $this->db;
		$query = "select id,value,status from config_variable where description='RFQ';";
		$response = $db->query($query);
		$result = $response->result();
		
		$db->close();
		return $result;
	}

	function getorder_no($order_no)
	{
		$get_rfq="SELECT R.id, R.order_no,  R.mode ,R.cost_reduction_rate,R.bid_start_time, R.bid_close_time,R.picup_date,R.no_of_trucks,R.truck_type,R.origin_address,R.destination_address,R.origin_zip_code,R.destination_zip_code ,R.destination_city,R.creation_time,R.rfq_status,R.createdbyid ,R.verified_by,R.verification_time,A.username,A.firstname,A.lastname,M.plant_name_for_gocomet FROM rfq_gocomet AS R INNER JOIN master_plant as M INNER JOIN ci_admin as A ON R.shipper=M.id AND R.createdbyid=A.admin_id where R.id='$order_no' ;";
		$result=$this->db->query($get_rfq);
		return $result->result_array();
		
	}

	function fetchCode($searchterm) {
		$db = $this->db;
		$query = "SELECT short_code_name FROM master_plant WHERE id=$searchterm ;";
		$response = $db->query($query);
		$result = $response->result();

		$db->close();
		return $result;
	}

	function get_all_rfq($plant)
	{
		
		$get_rfq="SELECT R.id, R.order_no,  R.mode ,R.cost_reduction_rate, R.bid_close_time,R.picup_date,R.no_of_trucks,R.truck_type,R.origin_address,R.destination_address,R.origin_zip_code,R.rfq_status,R.destination_city,R.destination_zip_code ,M.plant_name_for_gocomet FROM rfq_gocomet AS R  INNER JOIN master_plant as M  ON R.shipper=M.id WHERE R.shipper IN ($plant) ORDER BY R.rfq_status asc";
		$result=$this->db->query($get_rfq);	
		return $result->result_array();
		
	}

	function update_rfq($bid_start_time,$bid_close_time,$cost_reduction_rate,$isverified,$verified_by,$order_id){
		$db = $this->db;
		$query = "UPDATE rfq_gocomet set bid_start_time='$bid_start_time',bid_close_time='$bid_close_time',cost_reduction_rate='$cost_reduction_rate',verified_by='$verified_by',rfq_status='$isverified',verification_time=now() where id='$order_id';";
		$response = $db->query($query);
		return $response;
		$db->close();
	}

	

	function fetch_data($order_no)
	{
		$get_rfq="SELECT R.createdbyid ,R.creation_time,R.destination_city,A.username,A.firstname,A.lastname,M.plant_name_for_gocomet FROM rfq_gocomet AS R INNER JOIN master_plant as M INNER JOIN ci_admin as A ON R.shipper=M.id AND R.createdbyid=A.admin_id where R.order_no='$order_no' ;";
		$result=$this->db->query($get_rfq);
		$get_corporate_email="SELECT email FROM ci_admin where admin_role_id='2'";
		$email_result=$this->db->query($get_corporate_email);
		$res_rfq=$result->result_array();
		$res_email=$email_result->result_array();
		return array_merge($res_rfq, $res_email);

		
	}



	

}

?>