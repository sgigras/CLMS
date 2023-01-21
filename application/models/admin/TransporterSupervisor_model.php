<?php defined('BASEPATH') OR exit('No direct script access allowed');

class TransporterSupervisor_model extends CI_Model{

	//-----------------------------------------------------

	function get_all_trans_supervisor($trans_id)
	{
		$this->db->from('ci_admin');
		$this->db->where('transporter_id',$trans_id);
		$this->db->where('admin_role_id',59);
		$query=$this->db->get();
		return $query->result_array();
		
	}

	//-----------------------------------------------------

	function get_transporter_plants($plant_id)
	{
		$fetch_plants="SELECT id,plant_name FROM master_plant WHERE id IN ($plant_id)";
		$result=$this->db->query($fetch_plants);
		return $result->result_array();
	}

	//-----------------------------------------------------

	public function add_supervisor($supervisor_data){
		$result=$this->db->insert('ci_admin', $supervisor_data);
		return $result;		
	}
   //-----------------------------------------------------

    public function change_status()
    {		

	   $this->db->set('is_active',$this->input->post('status'));
	   $this->db->where('transporter_id',$this->input->post('id'));
	   $this->db->update('ci_admin');
    }

    public function get_supervisor_by_id($id)
	{
		$this->db->select('*');
		$this->db->from('ci_admin');
		$this->db->where('admin_id',$id);
		$query=$this->db->get();
		return $query->row_array();
	}

    public function edit_supervisor($data, $id)
	{
		$this->db->where('admin_id', $id);
		$this->db->update('ci_admin', $data);
		return true;
	}


}

?>