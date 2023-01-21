
<?php

/**
 * This is the library for the Vehicle  API 
 * @author Raj Gujar
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class CI_Vehiclelib {

    public $now;
    protected static $func_overload;
    protected $CI;

    public function __construct() {
        $this->CI = & get_instance();
        isset(self::$func_overload) OR self::$func_overload = (extension_loaded('mbstring') && ini_get('mbstring.func_overload'));
        $this->now = time();
        log_message('info', 'ATS User Class Initialized');
    }

    public function Add($data) {

        $userid = $data["D_CREATEDBYID"];
        $vehicleno = $data["D_VEHICLENO"];
        $vehicle_type = $data["D_VEHICLE_TYPE"];
        $suited_rest_of_india = $data["D_SUITED_REST_OF_INDIA"];
        $suited_north_east = $data["D_SUITED_NORTH_EAST"];
        $suited_bangladesh = $data["D_SUITED_BANGLADESH"];
        $suited_nepal = $data["D_SUITED_NEPAL"];
        $capacity = $data["D_CAPACITY"];
        $box_count = $data["D_BOX_COUNT"];
        $transporterid = $data["D_TRANSPORTERID"];
        $plant_id = $data["D_PLANTID"];
        $expiry_insurance = $data["D_EXPIRY_INSURANCE"];
        $expiry_puc = $data["D_EXPIRY_PUC"];
        $expiry_rto = $data["D_EXPIRY_RTO"];
        $category = $this->get_capacity_category($box_count);

        $db = $this->CI->db;
        
        $query = "INSERT INTO ci_vehicle (createdbyid,vehicleno,vehicle_type,suited_rest_of_india,suited_north_east,suited_bangladesh,suited_nepal,capacity,box_count,category,transporterid,
        plant_id,expiry_insurance,expiry_puc,expiry_rto) 
        values ($userid,'{$vehicleno}',$vehicle_type,'{$suited_rest_of_india}','{$suited_north_east}','{$suited_bangladesh}','{$suited_nepal}','{$capacity}','{$box_count}','{$category}','{$transporterid}',
        '{$plant_id}','{$expiry_insurance}','{$expiry_puc}','{$expiry_rto}')";

        echo $query;
        $response = $db->query($query);
        $db->close();
        return $response;
    }

    public function get_capacity_category($CAPACITY){
      $CATEGORY = '';
      
      if ($CAPACITY > 0 && $CAPACITY <= 44) {
         $CATEGORY = 'A';
     } 
     else if ($CAPACITY >= 45 && $CAPACITY <= 64) {
         $CATEGORY = 'B';
     }
     else if ($CAPACITY >= 65 && $CAPACITY <= 95) {
         $CATEGORY = 'C';
     } 
     else if ($CAPACITY >= 96 && $CAPACITY <= 120) {
         $CATEGORY = 'D';
     } 
     else if ($CAPACITY >= 121 && $CAPACITY <= 169) {
         $CATEGORY = 'E';
     } 
     else if ($CAPACITY >= 170) {
         $CATEGORY = 'F';
     }
     return $CATEGORY;
    }


    public function Update($data) {

        $userid = $data["D_modifiedbyid"];
        $vehicleno = $data["D_VEHICLENO"];
        $vehicle_type = $data["D_VEHICLE_TYPE"];
        $suited_rest_of_india = $data["D_SUITED_REST_OF_INDIA"];
        $suited_north_east = $data["D_SUITED_NORTH_EAST"];
        $suited_bangladesh = $data["D_SUITED_BANGLADESH"];
        $suited_nepal = $data["D_SUITED_NEPAL"];
        $capacity = $data["D_CAPACITY"];
        $box_count = $data["D_BOX_COUNT"];
        $transporterid = $data["D_TRANSPORTERID"];
        $plant_id = $data["D_PLANTID"];
        $expiry_insurance = $data["D_EXPIRY_INSURANCE"];
        $expiry_puc = $data["D_EXPIRY_PUC"];
        $expiry_rto = $data["D_EXPIRY_RTO"];

        $db = $this->CI->db;
        
        $query = "UPDATE ci_vehicle SET modifiedbyid =$userid, vehicleno = '{$vehicleno}',vehicle_type = $vehicle_type,
        suited_rest_of_india = '{$suited_rest_of_india}',suited_north_east = '{$suited_north_east}',suited_bangladesh = '{$suited_bangladesh}',suited_nepal = '{$suited_nepal}',
        capacity = '{$capacity}',box_count = '{$box_count}',transporterid = '{$transporterid}',
        plant_id = '{$plant_id}',expiry_insurance = '{$expiry_insurance}',
        expiry_puc = '{$expiry_puc}',expiry_rto = '{$expiry_rto}',
        modification_time=now() WHERE vehicleno = '{$vehicleno}'";
        
        // echo $query;
        $response = $db->query($query);
        $db->close();
        return $response;
    }

    public function VehicleList($transporterid) {
        $db = $this->CI->db;
        $query = "";
        if ($transporterid != "" || $transporterid != null) {
            $query = "SELECT vehicleid,vehicleno FROM ci_vehicle WHERE transporterid={$transporterid}";
        } else {
            $query = "SELECT vehicleid,vehicleno FROM ci_vehicle";
        }
        $response = $db->query($query);
        $db->close();
        return $response->result();
    }

    public function OnTripVehicleList($transporterid) {
        $db = $this->CI->db;
        $query = "";
        if ($transporterid != "" || $transporterid != null) {
            $query = "SELECT vehicleno FROM acg_trip WHERE transporterid={$transporterid} AND is_completed = 'N'";
        } else {
            $query = "SELECT vehicleno FROM acg_trip";
        }
        $response = $db->query($query);
        $db->close();
        return $response->result();
    }

    public function InvoiceList($vehicleno) {
        $db = $this->CI->db;
        $query = "";
        if ($vehicleno != "" || $vehicleno != null) {
            $query = "SELECT invoice_no FROM acg_trip WHERE vehicleno={$vehicleno}";
        } else {
            $query = "SELECT invoice_no FROM acg_trip";
        }
        $response = $db->query($query);
        $db->close();
        return $response->result();
    }

    public function VehicleData() {
        $db = $this->CI->db;
        $query = "SELECT vehicleid,vehicleno FROM ci_vehicle";
        $response = $db->query($query);
        $db->close();
        return $response->result();
    }

    public function Details($vehicleno) {
        $db = $this->CI->db;
        $result = $this->CheckVehicle($vehicleno);
        $response = "";
        if (count($result) != 0) {

            $query = "SELECT vehicleno,vehicle_type,capacity,box_count,transporterid,plant_id,
            suited_rest_of_india,suited_north_east,suited_bangladesh,suited_nepal,
            expiry_insurance,expiry_puc,expiry_rto,isactive 
            FROM ci_vehicle WHERE vehicleno='{$vehicleno}'";
            $response = $db->query($query);
        }
        $db->close();
        return $response != "" ? $response->result() : false;
        
    }

    public function OnTripDetails($vehicleno) {
        $db = $this->CI->db;
        $result = $this->CheckOnTripVehicle($vehicleno);
        $response = "";
        if (count($result) != 0) {

            $query = "SELECT vehicleno,invoice_no
            FROM acg_trip WHERE vehicleno='{$vehicleno}'";
            $response = $db->query($query);
        }
        $db->close();
        return $response != "" ? $response->result() : false;
        
    }

    public function InvoiceDetails($invoice_no) {
        $db = $this->CI->db;
        $result = $this->CheckInvoice($invoice_no);
        $response = "";
        if (count($result) != 0) {

            $query = "SELECT invoice_no,customer_name,destination
            FROM acg_trip WHERE invoice_no='{$invoice_no}'";
            $response = $db->query($query);
        }
        $db->close();
        return $response != "" ? $response->result() : false;
        
    }

    public function PlantDetails($plant_id) {
        $db = $this->CI->db;
        $query = "SELECT id,plant_name from master_plant WHERE id IN ($plant_id)";
        $response = $db->query($query);
        $db->close();
        return $response->result();
        
    }

    public function CheckPlant($adminid) {
        $db = $this->CI->db;
        $query = "SELECT id,plant_name from master_plant";
        $response = $db->query($query);
        $db->close();
        return $response->result();
    }

    public function CheckVehicle($vehicle_no) {
        $db = $this->CI->db;
        $query = "Select vehicleno FROM ci_vehicle WHERE vehicleno='{$vehicle_no}'";
        $response = $db->query($query);
        $db->close();
        return $response->result();
    }

    public function CheckOnTripVehicle($vehicle_no) {
        $db = $this->CI->db;
        $query = "SELECT vehicleno FROM acg_trip WHERE vehicleno='{$vehicle_no}'";
        $response = $db->query($query);
        $db->close();
        return $response->result();
    }

    public function CheckInvoice($invoice_no) {
        $db = $this->CI->db;
        $query = "SELECT invoice_no FROM acg_trip WHERE invoice_no='{$invoice_no}'";
        $response = $db->query($query);
        $db->close();
        return $response->result();
    }

    public function MobileDeviceList($search) {
        $db = $this->CI->db;
        $query = "SELECT deviceid FROM latest_asset_info";
        $response = $db->query($query);
        $db->close();
        return $response->result();
    }

    public function CheckData($data) {
        $db = $this->CI->db;
        $option = $data['option'];
        $search = $data['search'];
        
        $query = "SELECT 
        (SELECT vehicleno FROM ci_vehicle where deviceid='{$search}'  AND isactive=1 limit 1) AS vehicleno,
        deviceid,latitude,longitude,DATE_FORMAT(time, '%D %b %Y %r') as time,powerstatus,battery_value,alarm,accstatus,digital_port,extra2_var FROM latest_asset_info WHERE deviceid ='{$search}' order by time DESC LIMIT 1";
        if ($option == "2") {
            $query = "SELECT '{$search}' AS vehicleno,deviceid,latitude,longitude,DATE_FORMAT(time, '%D %b %Y %r') as time,powerstatus,battery_value,alarm,accstatus,digital_port,extra2_var FROM latest_asset_info where deviceid = (select deviceid from ci_vehicle where vehicleno='{$search}'  AND isactive=1 limit 1) order by time DESC LIMIT 1";
        }
        
        $responseArray = array();
        $response = $db->query($query);
        $num_rows = $response->num_rows();
        $db->close();

        if($num_rows > 0)
        {
            $result = $response->result();
            $row = $result[0]->digital_port;           
            $DIGITAL_PORT = decbin($row);
            $NEWPORT = str_pad($DIGITAL_PORT, 10, "0", STR_PAD_LEFT);
            $IGNITION = substr($NEWPORT, -3, 1);

            $result = $response->result();
            $temp_sensor = $result[0]->extra2_var;
            if ($temp_sensor == 'VARI'){
                $NewTemp = "Not Available";
            }
            else{
                $NewTemp = $temp_sensor . '°C';
            }

            $result = $response->result();
            if ($result[0]->accstatus == 'U') {
                $result[0]->accstatus = "Unknown Error";
            } else if ($result[0]->accstatus == 'S') {
                $result[0]->accstatus = "Standing";
            } else {
                $result[0]->accstatus = "Moving";
            }

            $add_result = $response->result();
            $latitude = number_format((float) $add_result[0]->latitude, 3, '.', '');
            $longitude = number_format((float) $add_result[0]->longitude, 3, '.', '');
            $query = "SELECT address FROM geocode_address WHERE Latitude = $latitude AND Longitude = $longitude";
            $response = $db->query($query);
            $num_rows = $response->num_rows();
            $db->close();

            if($num_rows > 0){

                $address_url = $response->result();
                $address = $address_url[0]->address;
            }
            else{
                $address = $this->getAddress($latitude, $longitude);
                // insert into database
            }

            $responseArray = array("status" => 1, "data" => $result, "address" => $address, "digital_port" => $IGNITION, "extra2_var" => $NewTemp, "msg" => "Vehicle OR Device Data Fetch Successfully");           
            
        }

        else
        {
            $responseArray = array("status" => 0, "data" => array(), "msg" => "No Data Found");
        }

        return $responseArray;

    }

    function getAddress($latitude, $longitude)
    {

        $GOOGLE_MAP_API_KEY = GOOGLE_MAP_API_KEY;

        $url = "https://maps.google.com/maps/api/geocode/json?latlng=$latitude,$longitude&key=$GOOGLE_MAP_API_KEY";

        // send http request
        $geocode = file_get_contents($url);
        
        $json = json_decode($geocode);
        $address = $json->results[0]->formatted_address;

        return $address;
    }


    public function UpdateStatus($vehicleno, $status, $userid) {
        $currentdate = date("Y-m-d H:i:s");
        $db = $this->CI->db;
        $query = "UPDATE ci_vehicle SET isactive ='{$status}', modification_time ='{$currentdate}',
        modifiedbyid ='{$userid}' WHERE vehicleno ='{$vehicleno}'";
        $response = $db->query($query);
        $db->close();
        return $response;
    }

    public function UploadPics($image_mode,$file) {
        $upload_path = '/var/www/server/public_html/acgtntdev/uploads/pod/';
        $responseArray = array();
        // $year = date("Y");
        // $month = date("m");
        // $day = date("d");
        // $date_folder = $year.$month.$day;

        // $folder_path = $upload_path;
        // echo $folder_path;
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, true);
            chmod($upload_path, 0755);
        }
        
        $file = $upload_path . date('ymdhis') .'.png';
       
        if (move_uploaded_file($_FILES['file']['tmp_name'], $file)) {
            $responseArray = array("status" => 1, "data" => array(), "msg" => "Upload Successfully", "path" => $file);
        } else {
            $responseArray = array("status" => 0, "data" => array(), "msg" => "Upload Failed", "filepath" => $file);
        }

        return $responseArray;
    }

    public function UploadPOD($img_pod_path,$vehicleno) {

        $db = $this->CI->db;
        
        $query = "UPDATE acg_trip SET img_pod_path = '{$img_pod_path}' WHERE vehicleno ='{$vehicleno}'";

        $response = $db->query($query);
        
        $db->close();

        if($response){
            $query = "SELECT vehicleid FROM ci_vehicle WHERE vehicleno ='{$vehicleno}'";
            $response = $db->query($query);
            $result = $response->result();
            
            $vehicle_id = $result[0]->vehicleid;
            $update_query = "UPDATE acg_vehicle_log SET is_completed = 'Y' WHERE vehicle_id ='{$vehicle_id}'";    
            $response = $db->query($update_query);
            $db->close();
            
        }
        return $response;
    }
}
