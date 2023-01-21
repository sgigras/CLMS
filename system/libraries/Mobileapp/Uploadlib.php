
<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class CI_Uploadlib {

    public $now;
    protected static $func_overload;
    protected $CI;

    public function __construct() {
        $this->CI = & get_instance();
        isset(self::$func_overload) OR self::$func_overload = (extension_loaded('mbstring') && ini_get('mbstring.func_overload'));
        $this->now = time();
        log_message('info', 'ATS User Class Initialized');
    }

     public function DriveruploadPics($image_mode,$file,$dlno) {
        $upload_path = '/var/www/server/public_html/acgtntdev/uploads/driver/';
        $responseArray = array();
        // $year = date("Y");
        // $month = date("m");
        // $day = date("d");
        // $date_folder = $year.$month.$day;

        $folder_path = $upload_path.$dlno.'/';
        // echo $folder_path;
        if (!is_dir($folder_path)) {
            mkdir($folder_path, 0777, true);
            chmod($folder_path, 0755);
        }
        
        $file = $folder_path . date('ymdhis') .'.png';
       
        if (move_uploaded_file($_FILES['file']['tmp_name'], $file)) {
            $responseArray = array("status" => 1, "data" => array(), "msg" => "Upload Successfully", "path" => $file);
        } else {
            $responseArray = array("status" => 0, "data" => array(), "msg" => "Upload Failed", "filepath" => $file);
        }

        return $responseArray;
    }

}
