<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Transporter_model extends CI_Model{

	
	function get_plant()
	{
		$this->db->from('master_plant');
		$this->db->where('plant_status',1);
		$query=$this->db->get();
		return $query->result_array();
	}

	//-----------------------------------------------------

	function get_all_transporter()
	{
		$this->db->from('ci_transporter');
		$query=$this->db->get();
		return $query->result_array();
		
	}

	//-----------------------------------------------------

	public function add_transporter($transporter_data,$trans_admmin_data){
		$result=$this->db->insert('ci_transporter', $transporter_data);
		if($result){
			$transporter_email=$transporter_data['email_id'];
            $transporter_query=$this->db->query("SELECT id FROM ci_transporter where email_id='$transporter_email'");
            $trans_result=$transporter_query->result();
            $transporter_id= $trans_result[0]->id;
            $trans_admmin_data['transporter_id']=$transporter_id;  
			$result_admin=$this->db->insert('ci_admin', $trans_admmin_data);
			if($result_admin){
				return true;
			}
		}
		
	}
   //-----------------------------------------------------

    public function change_status()
    {		
	   $this->db->set('is_active',$this->input->post('status'));
	   $this->db->where('id',$this->input->post('id'));
	   $this->db->update('ci_transporter');

	   $this->db->set('is_active',$this->input->post('status'));
	   $this->db->where('transporter_id',$this->input->post('id'));
	   $this->db->update('ci_admin');
    }

    public function get_transporter_by_id($id)
	{
		$this->db->select('*');
		$this->db->from('ci_transporter');
		$this->db->where('id',$id);
		$query=$this->db->get();
		return $query->row_array();
	}

    public function edit_transporter($data, $transporter_admin_data, $id)
	{
		$this->db->where('id', $id);
		$result= $this->db->update('ci_transporter', $data);
		if($result){
			$this->db->where('transporter_id', $id);
			$this->db->where('admin_role_id', 58);
			$result_admin= $this->db->update('ci_admin', $transporter_admin_data);
			if($result_admin){
				return true;
			}
		}	
	}


}

?>