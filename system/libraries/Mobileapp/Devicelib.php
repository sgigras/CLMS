
<?php
/**
 *This is the library for the Driver API 
 *Date Created : 2021-09-06
 * @author Reshma Fasale
 */
defined('BASEPATH') OR exit('No direct script access allowed');
  date_default_timezone_set('Asia/Kolkata');
class CI_Devicelib {

    public $now;
    protected static $func_overload;
    protected $CI;

    public function __construct() {
        $this->CI = & get_instance();
        isset(self::$func_overload) OR self::$func_overload = (extension_loaded('mbstring') && ini_get('mbstring.func_overload'));
        $this->now = time();
        log_message('info', 'ATS User Class Initialized');
    }
    
     public function vehicleWithoutDeviceList()
    {
        $db = $this->CI->db;
        $query = "select vehicleid,vehicleno from ci_vehicle where deviceid IS NULL OR deviceid = '' ";
        $response = $db->query($query);
        $result = $response->result_array();
        $db->close();
        return $result;
    }

    public function DeviceList($search)
    {
        $db = $this->CI->db;
        $query = "select distinct deviceid from latest_asset_info";
        $response = $db->query($query);
        $db->close();
        return $response->result();
    }

     public function checkDeviceData($device_id)
    {
        $responseArray = array();
        $search = $this->QRDecryption($device_id);
      if ($search != '') {
        $qrdata = explode('||', $search);
        $deviceid = trim($qrdata[0], ' ');
     } else {
         $responseArray = array("status" => 2, "data" => array(), "msg" => "Kindly Scan again or contact with Ats team");
     }

        $db = $this->CI->db;
        $check_device_install ="select deviceid from ci_vehicle where deviceid ='$deviceid'";
        $check_device_install_response =$db->query($check_device_install);
         $check_device_install_result = $check_device_install_response->result();

         if (count($check_device_install_result) == 0){
             $query = "select deviceid,TIMESTAMPDIFF(minute,rddate,now()) as last_packet_time,rddate,powerstatus,latitude,longitude,extra2_var from latest_asset_info 
                 where deviceid = '$deviceid'";
           $response = $db->query($query);
           $result = $response->result();
           $db->close();
               $responseArray = array("status" => 1, "data" => $result, "msg" => "Device Data Fetch Successfully");
          
         }else{
            $responseArray = array("status" => 0, "data" => array(), "msg" => "Device already installed");
         }  
          return $responseArray;
    }
    

     public function devicedatacheck($device_id)
    {
        $db = $this->CI->db;
        $query = "select deviceid,TIMESTAMPDIFF(minute,rddate,now()) as last_packet_time,rddate,powerstatus,latitude,longitude,extra2_var  from latest_asset_info where deviceid = '$device_id'";
        $response = $db->query($query);
        $result = $response->result_array();
        $db->close();
        return $result;
    }
    public function checkVehicleData($veh_no)
    {
        $db = $this->CI->db;
        $query = "select isactive from ci_vehicle where vehicleno = '$veh_no'";
        $response = $db->query($query);
        $result = $response->result_array();
        $db->close();
        return $result;
    }

     public function Install($data)
    {
        $db = $this->CI->db;
        $query = "CALL SP_MO_INSTALL_DEVICE('I','{$data->veh_no}','{$data->devce_id}','{$data->latitude}','{$data->longitude}','{$data->userid}','{$data->transporter}')";
        $response = $db->query($query);
        $db->close();
        $result = $response->result();
        $responseArray = array();
        if (count($result) > 0)
        {
            $row = $result[0];
            if ($row->STATUS == 1)
            {
              
                    $veh_number = $data->veh_no;
                    $deviceid = $data->devce_id;
                    $is_sync_query = "update ats_device_activity_log set IS_SYNC = '1' where VEHICLENO = '$veh_number' and DEVICEID = '$deviceid' and ACTIVITY = 'I' and (TIMESTAMPDIFF(minute,INSERT_TIME,now()) < 2)";
                    $db->query($is_sync_query);
                $responseArray = array("status"=>1,"data"=>$row,"msg"=>"Device Installed Successfully"); 
            }
            else
            {
                $responseArray = array("status"=>0,"data"=>$row,"msg"=>"Device Install Unsuccessful"); 
            }
        }
        else
        {
            $responseArray = array("status"=>0,"data"=>array(),"msg"=>"Device Install Unsuccessful"); 
        }
        return $responseArray;
    }

   public function Replace($data)
    {
        $db = $this->CI->db;
        $query = "CALL SP_MO_REPLACE_DEVICE('R','{$data->veh_no}','{$data->devce_id}','{$data->latitude}','{$data->longitude}','{$data->userid}','{$data->remark}')";
        $response = $db->query($query);
        $db->close();
        $result = $response->result();
        $responseArray = array();
        
        if (count($result) > 0)
        {
            $row = $result[0];
            if ($row->STATUS == 1)
            {
                $responseArray = array("status"=>1,"data"=>$row,"msg"=>"Device Replaced Successfully"); 
            }
            else
            {
                $responseArray = array("status"=>0,"data"=>$row,"msg"=>"Device Replaced unsuccessful");
            }
        }
        else
        {
            $responseArray = array("status"=>0,"data"=>array(),"msg"=>"Device Replaced unsuccessful"); 
        }
        return $responseArray;
    }

     public function UnInstall($data) {
        $db = $this->CI->db;
        $deviceCondition = $data->buzzer_working . ',' . $data->relay_working . ',' . $data->device_damaged . ',' . $data->physically_damaged . ',' . $data->battery_damaged . ',' . $data->sim_present;
        $query = "CALL SP_MO_UNINSTALL_DEVICE('U','{$data->veh_no}','{$data->latitude}','{$data->longitude}','{$data->userid}','{$data->remark}','{$deviceCondition}')";
        $response = $db->query($query);
        $db->close();
        $result = $response->result();
        $responseArray = array();
        if (count($result) > 0) {
            $row = $result[0];
            if ($row->STATUS == 1) {
                    $veh_number = $data->veh_no;
                    $is_sync_query = "update ats_device_activity_log set is_sync = '1' where VEHICLENO = '$veh_number' and activity = 'U' and (TIMESTAMPDIFF(minute,insert_time,now()) < 2)";
                    $db->query($is_sync_query);
                // }
                $responseArray = array("status" => 1, "data" => $row, "msg" => "Device Uninstalled Successfully");
            } else {
                $responseArray = array("status" => 0, "data" => $row, "msg" => "Device Uninstall Unsuccessful");
            }
        } else {
            $responseArray = array("status" => 0, "data" => array(), "msg" => "Device Uninstallation Unsuccessful");
        }
        return $responseArray;
    }

     public function vehicleWithDeviceList()
    {
        $db = $this->CI->db;
        $query = "select vehicleid,vehicleno from ci_vehicle where deviceid IS NOT NULL OR deviceid != '' ";
        $response = $db->query($query);
        $result = $response->result_array();
        $db->close();
        return $result;
    }

      public function CheckData($data) {
        $db = $this->CI->db;
        // $option = $data->option;;
         $search = $data->search;

        // if ($option == "2") {
            $query = "select '{$search}' AS vehicleno,deviceid,latitude,longitude,DATE_FORMAT(rddate, '%D %b %Y %r') as RDDATE,powerstatus,battery_value from latest_asset_info where deviceid = (select deviceid from ci_vehicle where vehicleno='{$search}' AND isactive=1 limit 1) order by rddate DESC LIMIT 1";
        // }else{

        // $query = "select 
        //         (select vehicleno from ci_vehicle where vehicleno='{$search}'  AND isactive=1 limit 1) AS vehicleno,
        //         deviceid,latitude,longitude,DATE_FORMAT(rddate, '%D %b %Y %r') as RDDATE,powerstatus,battery_value from latest_asset_info where deviceid ='{$search}' order by rddate DESC LIMIT 1";  
        // }
        $responseArray = array();
        $response = $db->query($query);
        $num_rows = $response->num_rows();
        $db->close();
        if($num_rows > 0)
        {
            $result = $response->result();
            $responseArray = array("status" => 1, "data" => $result, "msg" => "Vehicle OR Device Data Fetch Successfully");
        }
        else
        {
            $responseArray = array("status" => 0, "data" => array(), "msg" => "No Data Found");
        }
        
        return $responseArray;
    }

  function QRDecryption($string) {
    $output = false;
    $enc_method = "aes-256-cbc";
    $sec_key = SECRET_KEY;
    $sec_iv = SECRET_IV;
    $enc_keys = md5($sec_key);
    $key = hash('sha256', $enc_keys);
    $iv = substr(hash('sha256', $sec_iv), 0, 16);
    $output = openssl_decrypt(base64_decode($string), $enc_method, $key, 0, $iv);
    return $output;
}