
<?php
/**
 *This is the library for the Driver API 
 *Date Created : 2021-09-06
 * @author Reshma Fasale
 */
defined('BASEPATH') OR exit('No direct script access allowed');
  date_default_timezone_set('Asia/Kolkata');
class CI_Driverlib {

    public $now;
    protected static $func_overload;
    protected $CI;

    public function __construct() {
        $this->CI = & get_instance();
        isset(self::$func_overload) OR self::$func_overload = (extension_loaded('mbstring') && ini_get('mbstring.func_overload'));
        $this->now = time();
        // log_message('info', 'ATS User Class Initialized');
    }
     
        public function Details($mobileno) {
        $db = $this->CI->db;
        $result = $this->CheckDriver($mobileno,"U");
        $response = "";
        if (count($result) != 0) {
            $query ="SELECT driver_id,transporter_id,drivername,mobileno,is_active FROM ci_driver WHERE mobileno='{$mobileno}'";
            $response = $db->query($query);
        }
        $db->close();
        return $response != "" ? $response->result() : false;
    }

    public function DriverNameDLList($transporterid) {
        $db = $this->CI->db;
        // $query="";
        if ($transporterid!="" || $transporterid!=null) {
            $query = "SELECT drivername,mobileno FROM ci_driver WHERE transporter_id={$transporterid}";
        }
        else{
        $query = "SELECT drivername,mobileno FROM ci_driver";
        }
        $response = $db->query($query);
        $db->close();
        return $response->result();
    }

    public function CheckDriver($search, $mode, $id = 0) {
        $db = $this->CI->db;
        if ($mode != "U") {
            $query = "SELECT mobileno FROM ci_driver WHERE mobileno='{$search}' OR drivername='{$search}' OR driver_id='{$search}'";
        } else {
            $query = "SELECT mobileno FROM ci_driver WHERE driver_id !='{$id}' AND mobileno='{$search}'";
        }
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result;
    }
    
     public function Add($data) {
        $responseArray = array();
        $mobno = $data["D_mobileno"];
        $currentdate = date("Y-m-d H:i:s");
        $db = $this->CI->db;
        $column = "";
        $columnvalue = "";
        foreach ($data as $key => $value) {
            if (substr($key, 0, 2) == "D_") {
                $column .= substr($key, 2) . ",";
                $columnvalue .= "'{$value}',";
            }
        }
        $column .= "creation_time,driver_uuid";
        $columnvalue .= "'{$currentdate}',UUID()";

        $result = $this->CheckDriver($mobno,"A");
        if (count($result) == 0) {
            $insert_ci_driver = "INSERT INTO ci_driver ({$column}) VALUES ({$columnvalue})";
            $insert_ci_driver_response = $db->query($insert_ci_driver);
            $mobileno = $data["D_mobileno"];
            $dname = $data["D_drivername"];
            $transporterid = $data["D_transporter_id"];
            $password = password_hash($mobileno, PASSWORD_BCRYPT);
            $currentdate =date('Y-m-d H:i:s');
            $isactive = $data["D_is_active"];

            $driver_query="SELECT driver_id FROM ci_driver WHERE mobileno='$mobileno'";
            $driver_response = $db->query($driver_query);
            $driver_result=$driver_response->result();
            $driver_id= $driver_result[0]->driver_id;
           
            $insert_user = "INSERT INTO ci_admin(admin_role_id,transporter_id,driver_id,username,firstname,mobile_no,password,is_active,created_at,updated_at)VALUES('60','$transporterid','$driver_id','$mobileno','$dname','$mobileno','$password','$isactive','$currentdate','$currentdate')";
            $insert_user_response = $db->query($insert_user);
          
            $responseArray = array("status" => $insert_user_response, "data" => array(), "msg" => ($insert_user_response == true ? "Driver registered successfully.Please contact Security for Activation" : "Driver registration failed"));
        } else {
            $responseArray = array("status" => 0, "data" => array(), "msg" => "Driver already registered with us");
        }
        $db->close();
        return $responseArray;
    }

    public function Update($data,$old_mobno) {
        // $dlno = $data["D_dl_no"];
        $currentdate = date("Y-m-d H:i:s");
        $db = $this->CI->db;
        $update = "";
        foreach ($data as $key => $value) {
            if (substr($key, 0, 2) == "D_") {
                $update .= substr($key, 2) . "='{$value}',";
            }
        }
        $update .= "MODIFICATION_TIME='{$currentdate}'";
            $mobileno = $data["D_mobileno"];
            $dname = $data["D_drivername"];
            $transporterid = $data["D_transporter_id"];
            $isactive = $data["D_is_active"];
            
            $password = password_hash($mobileno, PASSWORD_BCRYPT);
            $currentdate =date('Y-m-d : h:m:s');
            $result = $this->CheckDriver($old_mobno,"U");

         $driver_query="SELECT driver_id FROM ci_driver WHERE mobileno='$old_mobno'";
            $driver_response = $db->query($driver_query);
            $driver_result=$driver_response->result();
            $driver_id= $driver_result[0]->driver_id;

        if (count($result) > 0) {
            $query = "update ci_driver set {$update} where mobileno='{$old_mobno}'";
            $response = $db->query($query);
            $update_driver_details ="UPDATE ci_admin set username='$mobileno',firstname='$dname',mobile_no='$mobileno',password='$password',is_active='$isactive' WHERE driver_id ='$driver_id'";
            $update_driver_response = $db->query($update_driver_details);
            $responseArray = array("status" => $update_driver_response, "data" => array(), "msg" => ($update_driver_response == true ? "Driver Details  updated successfully" : "Updation Failed"));
        } else {
            $responseArray = array("status" => 0, "data" => array(), "msg" => "Driver is not registered with us");
        }
        $db->close();
        return $responseArray;
    }
    
    
    public function getDriverStatus($mobileno){
        $db = $this->CI->db;
        $query = "SELECT count(driver_id) as Status FROM ci_driver WHERE mobileno='{$mobileno}' AND is_active='1'";
        $response = $db->query($query);
        $db->close();
        return $response->result();
    }   
}

