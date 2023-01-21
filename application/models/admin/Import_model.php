<?php
class Import_model extends CI_Model
{
	public function select()
	{
		$this->db->order_by('id', 'DESC');
		$query = $this->db->get('bsf_hrms_data');
		return $query;
	}

	public function insert($data,$rank_array)
	{
		$json_array = json_encode($data);
		$json_rank_array = json_encode($rank_array);


		// return $json_array;
		// $query = "CALL SP_IMPORT_UPDATE_EXCEL_DATA(?)";
		$query = "CALL SP_IMPORT_UPDATE_EXCEL_DATA('$json_array')";
		// return $query;
		$response = $this->db->query($query);
		$result = $response->result();
		$response->next_result();
        $response->free_result();
		$rank_query = "CALL SP_INSERT_UPDATE_RANK('$json_rank_array')";
		$rank_response = $this->db->query($rank_query);
		$result_rank = $rank_response->result();
		$this->db->close();

		return $result;
	}
}
