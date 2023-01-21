<!-- //Author:Hriday Mourya
//Subject:Gocomet Response Model
//Date:07-09-21 -->

<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Gocomet_model extends CI_Model{

	public function ins($cUrl_json_object){

		$response_array=json_decode($cUrl_json_object,true);

		$token=$response_array['token'];
		$token_expires_at=$response_array['expires_at'];
		$token_desc="Gocomet Token";
	
       
					$token = array(
						'token'=>$token,
						'token_expires_at'=>$token_expires_at,
						'token_desc' =>$token_desc ,
						'creation_time'=>date('Y-m-d : h:m:s')


					);
		

		$table="ci_jwt_tokens";

		$response=$this->db->insert($table,$token);


	}


	public function insert($data){
		echo $data;
	}


}

?>