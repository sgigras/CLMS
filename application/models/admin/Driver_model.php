<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Driver_model extends CI_Model{


	//-----------------------------------------------------

	function get_all_driver($trans_id)
	{
		$this->db->from('ci_driver');
		$this->db->where('transporter_id',$trans_id);
		$query=$this->db->get();
		return $query->result_array();
		
	}

	//-----------------------------------------------------

	public function add_driver($driver_data,$driver_admmin_data){
		$result=$this->db->insert('ci_driver', $driver_data);
		if($result){
			$driver_mobile=$driver_data['mobileno'];
            $driver_query=$this->db->query("SELECT driver_id FROM ci_driver where mobileno='$driver_mobile'");
            $driver_result=$driver_query->result();
            $driver_id= $trans_result[0]->driver_id;
            $driver_admmin_data['driver_id']=$driver_id;
			$result_admin=$this->db->insert('ci_admin', $driver_admmin_data);
			if($result_admin){
				return true;
			}
		}
		
	}
   //-----------------------------------------------------

    public function change_status()
    {		
	   $this->db->set('is_active',$this->input->post('status'));
	   $this->db->where('driver_id',$this->input->post('id'));
	   $this->db->update('ci_driver');

	   $this->db->set('is_active',$this->input->post('status'));
	   $this->db->where('driver_id',$this->input->post('id'));
	   $this->db->update('ci_admin');
    }

    public function get_driver_by_id($id)
	{
		$this->db->select('*');
		$this->db->from('ci_driver');
		$this->db->where('driver_id',$id);
		$query=$this->db->get();
		return $query->row_array();
	}

    public function update_driver($driver_data,$driver_admin_data,$id)
	{
		$this->db->where('driver_id', $id);
		$result=$this->db->update('ci_driver', $driver_data);
		if($result){
			$this->db->where('driver_id', $id);
			$result_driver_update=$this->db->update('ci_admin', $driver_admin_data);
			if($result_driver_update){
				return true;
			}
		}
		
	}


}

?>