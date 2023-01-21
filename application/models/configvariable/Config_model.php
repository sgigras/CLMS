<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Config_model extends CI_Model{

	public function add($Config_variable){
		$this->db->insert('config_variable', $Config_variable);
		return true;
	}

       public function fatchvariables() {
              $db = $this->db;
              $query = "select id,variable,value,status from config_variable;";
              $response = $db->query($query);
              $result = $response->result_array();
              $db->close();
              return $result;
       }

       public function getVariableDetails($selected_value){
         $db = $this->db;
              $query = "select variable,value,description,status from config_variable where id=$selected_value;";
              $response = $db->query($query);
              $result = $response->result();
              $db->close();
              return $result;
       }

        public function update($variable,$value,$description,$status,$update_time,$variableid){
         $db = $this->db;
              $query = "UPDATE config_variable set variable='$variable',value='$value',description='$description',status='$status', update_time=now() where id='$variableid';";
              $response = $db->query($query);
              return $response;
              $db->close();
              
       }

       



}

?>