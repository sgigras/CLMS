<?php
	class User_model extends CI_Model{

		public function add_user($data){
			$this->db->insert('ci_admin', $data);
			return true;
		}

		//---------------------------------------------------
		// get all users for server-side datatable processing (ajax based)
		public function get_all_users(){
			$query = "select * from ci_admin where is_supper != 1";
			// $this->db->select('*');
			// $this->db->where('is_admin',0);
			// return $this->db->get('ci_users')->result_array();
			$response = $this->db->query($query);
			return $response->result_array();
		}


		//---------------------------------------------------
		// Get user detial by ID
		public function get_user_by_id($id){
			$query = "select * from ci_admin where admin_id = {$id}";
			// $query = $this->db->get_where('ci_users', array('id' => $id));
			// return $result = $query->row_array();
			$response = $this->db->query($query);
			return $response->result_array();
		}

		//---------------------------------------------------
		// Edit user Record
		public function edit_user($data, $id){
		extract($data);
		$query = "update ci_admin set 
					username='{$username}'
					,firstname='{$firstname}'
					,email='{$email}'
					,mobile_no='{$mobile_no}'
					,is_active='{$is_active}'
					,updated_at='{$updated_at}'
				where admin_id='$id'
			";
			// $this->db->where('id', $id);
			// $this->db->update('ci_users', $data);
			$response = $this->db->query($query);
			// return $response->result_array();
			return true;
		}

		//---------------------------------------------------
		// Change user status
		//-----------------------------------------------------
		function change_status()
		{		
			$this->db->set('is_active', $this->input->post('status'));
			$this->db->where('admin_id', $this->input->post('id'));
			$this->db->update('ci_admin');
		} 

	}

?>