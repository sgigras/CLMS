<!-- //Author:Hriday Mourya
//Subject:Vehicle Registration Model Database related QUERY.
//Date:01-09-21 -->

<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Vehicle_model extends CI_Model{

	public function add_vehicle($Vehicle_data){
		$this->db->insert('ci_vehicle', $Vehicle_data);
		return true;
	}

	

	function get_all_vehicles($trans_id)
	{
		$this->db->from('ci_vehicle');
		$this->db->where('transporterid',$trans_id);
		$query=$this->db->get();
		return $query->result_array();
		
	}

	function get_transporter_plants($plant_id)
	{
		$fetch_plants="SELECT id,plant_name FROM master_plant WHERE id IN ($plant_id)";
		$result=$this->db->query($fetch_plants);
		return $result->result_array();
	}

	public function change_status()
	{		
		$this->db->set('isactive',$this->input->post('status'));
		$this->db->where('vehicleid',$this->input->post('id'));
		$this->db->update('ci_vehicle');

		
	}

	public function edit_vehicle($Vehicle_data, $id)
	{
		$this->db->where('vehicleid', $id);
		$this->db->update('ci_vehicle', $Vehicle_data);
		return true;
	}

	
	public function get_vehicle_by_id($id)
	{
		$this->db->select('*');
		$this->db->from('ci_vehicle');
		$this->db->where('vehicleid',$id);
		$query=$this->db->get();
		return $query->row_array();
	}

	

}

?>