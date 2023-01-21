<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Vehicle_veri_model extends CI_Model{

	// Fetch Vehicle through searchterm

	public function fetchvehicle($searchterm) {
    $db = $this->db;
    $query = "select vehicleid,vehicleno from ci_vehicle where vehicleno like'%$searchterm%';";
    $response = $db->query($query);
    $result = $response->result();
    $db->close();
    return $result;
 }

  //Get Vehicle by particular vehicleid

 public function getvehicle($vehicle){
   $db = $this->db;
   $getvehicle="SELECT R.vehicleid,  R.plant_id ,R.transporterid, R.vehicleno,R.vehicle_type,R.box_count,R.capacity,R.expiry_puc,R.expiry_insurance,R.expiry_rto ,M.transporter_name FROM ci_vehicle AS R INNER JOIN ci_transporter as M  ON R.transporterid=M.id WHERE R.vehicleid='$vehicle' ";
   $response = $db->query($getvehicle);
   $result = $response->result_array();
   $db->close();
   return $result;
}


//Approve Vehicle by set is_verified to '1'

public function verify($Approve,$vehicleid){
   $db = $this->db;
   $query = "UPDATE ci_vehicle SET is_verified =$Approve WHERE vehicleid='$vehicleid'";
   $result = $db->query($query);
   return $result;

   $db->close();

}

//Reject Vehicle by set is_verified to '0'

public function reject($vehicleid){
   $db = $this->db;
   $query = "UPDATE ci_vehicle SET is_verified =0 WHERE vehicleid='$vehicleid'";
   $result = $db->query($query);
   return $result;

   $db->close();

}


}

?>