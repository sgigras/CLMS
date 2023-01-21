
<?php
/**
 *This is the library for the Availble vehicle
 *Date Created : 2021-09-06
 * @author Reshma Fasale
 */
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Kolkata');
class CI_Available_vehiclelib {

    public $now;
    protected static $func_overload;
    protected $CI;

    public function __construct() {
        $this->CI = & get_instance();
        isset(self::$func_overload) OR self::$func_overload = (extension_loaded('mbstring') && ini_get('mbstring.func_overload'));
        $this->now = time();
    }
    
    public function get_available_vehiclelist($userid) {
           $db = $this->CI->db;
           $query = "CALL SP_GET_AVAIALBLE_VEHICLE_LIST('{$userid}') ";   
           $response = $db->query($query);
           $db->close();
          return $response->result();
      }

      public function vehicle_available($vehicle,$transporterid,$userid,$plant_id) {
         $responseArray = array();
         $db = $this->CI->db;
         $get_box_count_query = "SELECT box_count,capacity,plant_id FROM ci_vehicle WHERE vehicleid ='$vehicle'";                                          
         $get_box_count_response= $db->query($get_box_count_query);
         $get_box_count_result= $get_box_count_response->result();
         $current_time =date('Y-m-d H:i:s');
         $box_count= $get_box_count_result[0]->box_count;
         $plant_id= $get_box_count_result[0]->plant_id;
         $capacity= $get_box_count_result[0]->capacity;

         $insert_user = "INSERT INTO acg_vehicle_log(vehicle_id,trans_id,user_id,plant_id,capacity,box_count,status,insert_time,is_completed) VALUES 
                                   ('$vehicle','$transporterid','$userid','$plant_id','$capacity','$box_count','0','$current_time','N')";
         $insert_user_response = $db->query($insert_user);
         $db->close();
         if($insert_user_response){
             $responseArray = array("status" => $insert_user_response, "data" => array(), "msg" => "Vehicle available successfully");
         }else{
             $responseArray = array("status" => 0, "data" => array(), "msg" => "Process Failed.. Please Try Again");
         }
         return $responseArray;
      } 
    
     public function show_availableVehicle($userid){
         $result = array();
         $db = $this->CI->db;

         $get_vehicles_query = "SELECT A.vehicleno,AVL.status, AVL.insert_time, AVL.box_count FROM acg_vehicle_log AVL INNER JOIN ci_vehicle A on A.vehicleid = AVL.vehicle_id WHERE  AVL.is_completed = 'Y' and AVL.user_id = '$userid' order by AVL.insert_time ASC";                                          
         $get_vehicles_response= $db->query($get_vehicles_query);
         $get_response =$get_vehicles_response->result();
         $db->close();
         return $get_response ;
        }

         public function get_requested_vehicle($transid) {
           $db = $this->CI->db;
           $query = "CALL SP_SHOW_REQUESTED_VEHICLES('{$transid}') ";   
           $response = $db->query($query);
           $request_vehicle_response = $response->result();
           $db->close();
           return  $request_vehicle_response;
      }

      public function update_requestnotification_response($notif_status,$noti_id,$veh_log_id,$req_log_id,$reject_reason){
           $db = $this->CI->db;
           $query = "CALL SP_PROCESS_NOTIFICATION('{$notif_status}','{$noti_id}','{$veh_log_id}','{$req_log_id}','{$reject_reason}') ";   
           $response = $db->query($query);
           $notification_response = $response->result();
           $db->close();
          return $notification_response;
         
      }


      public function get_device_list(){
           $db = $this->CI->db;
           $query = "SELECT id,deviceid from latest_asset_info";   
           $response = $db->query($query);
           $device_list_response = $response->result();
           $db->close();
           return $device_list_response;
      }

   
}

