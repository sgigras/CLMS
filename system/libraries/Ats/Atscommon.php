<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class CI_Atscommon {

    public $now;
    protected static $func_overload;
    protected $CI;

    public function __construct() {
        $this->CI = & get_instance();
        isset(self::$func_overload) OR self::$func_overload = (extension_loaded('mbstring') && ini_get('mbstring.func_overload'));
        $this->now = time();
        log_message('info', 'ATS Common Class Initialized');
    }

    public function PasswordEncryption($string) {
        $output = false;
        $enc_method = "aes-256-cbc";
        $sec_key = SECRET_KEY;
        $sec_iv = SECRET_IV;
        $enc_keys = md5($sec_key);
        $key = hash('sha256', $enc_keys);
        $iv = substr(hash('sha256', $sec_iv), 0, 16);
        $output = openssl_encrypt($string, $enc_method, $key, 0, $iv);
        $output = base64_encode($output);
        return $output;
    }

    public function PasswordDecryption($string) {
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

    function postGpsItms($JSON_GPS_ITMS) {
        //    echo "called";
        //      $ITMSURL = "http://182.173.65.11:9080/ITMSGPSIntegrationService/rest/ssWebService/gpsPostDataToITMS";
        //      $ITMSURL = "https://128.199.140.128/ITMS_GETDATA.php";
        //      $ITMSURL = "http://182.173.65.11:9080/ITMS_GPS_PROXY/rest/postItmsMessage";    // TCS TESTING PORT
        $ITMSURL = CURL_URL;    // TCS TESTING PORT    
        $headers = array('Accept: application/json', 'Content-Type: application/json');
        $ch = curl_init($ITMSURL);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $JSON_GPS_ITMS);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        curl_close($ch);  // Seems like good practice

        return $result;
    }

    function getGUID() {
        if (function_exists('com_create_guid')) {
            return com_create_guid();
        } else {
            mt_srand((double) microtime() * 10000); //optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));

            $hyphen = chr(45); // "-"
            $uuid = substr($charid, 0, 8) . $hyphen
                    . substr($charid, 8, 4) . $hyphen
                    . substr($charid, 12, 4) . $hyphen
                    . substr($charid, 16, 4) . $hyphen
                    . substr($charid, 20, 12);

            return $uuid;
        }
    }

    function logToFile($filename, $msg) {
        $msg = "[" . Date("d-m-Y H:i:s") . "]" . $msg;
        // open file
        $fd = fopen($filename, "a");
        // append date/time to message
        $str = $msg;
        // write string
        fwrite($fd, $str . "\n");
        // close file
        fclose($fd);
    }

    function printText($strObj) {
        echo "<pre>";
        print_r($strObj);
        die;
    }

    function getRandomWord($len = 5) {
        $word = array_merge(range('0', '9')); // $word = array_merge(range('0', '9'), range('A', 'Z'));
        shuffle($word);
        return substr(implode($word), 0, $len);
    }

    function get_envclient_ip() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    function get_serverclient_ip() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if (isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    function getDirContents($dir, &$results = array()) {
        $files = scandir($dir);
        foreach ($files as $key => $value) {
            $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
            if (!is_dir($path)) {
                if (strpos($path, '.php') !== false) {
                    $results[] = $path;
                }
            } else if ($value != "." && $value != "..") {
                getDirContents($path, $results);
                //$results[] = $path;
            }
        }
        return $results;
    }

    function get_func_argNames($funcName) {
        $f = new ReflectionFunction($funcName);
        $result = array();
        foreach ($f->getParameters() as $param) {
            $result[] = $param->name;
        }
        return $result;
    }

    function GetDevice() {
        $iPod = stripos($_SERVER['HTTP_USER_AGENT'], "iPod");
        $iPhone = stripos($_SERVER['HTTP_USER_AGENT'], "iPhone");
        $iPad = stripos($_SERVER['HTTP_USER_AGENT'], "iPad");
        $Android = stripos($_SERVER['HTTP_USER_AGENT'], "Android");
        $webOS = stripos($_SERVER['HTTP_USER_AGENT'], "webOS");

        //do something with this information
        if ($iPod || $iPhone) {
            return "iPod/iPhone";
        } else if ($iPad) {
            return "iPad";
        } else if ($Android) {
            return "Android";
        } else if ($webOS) {
            return "webOS";
        }
    }

    function CallCurl($url) {
        $CI = & get_instance();
        $curl = $CI->curl;
        $curl->create($url);
        $curl->option('useragent', 'Aniruddha Telemetry Systems');
        $curl->option('returntransfer', 1);
        $curl->option('connecttimeout', 2);
        $json_data = $curl->execute();

        return json_decode($json_data, true);
    }

    public function Delete($id, $status, $module,$userid) {
        $query = "CALL SP_COMMON_DELETE({$id},{$status},'{$module}','$userid')";
        $db = $this->CI->db;
        $response = $db->query($query);
        $db->close();
        return $response->result_array();
    }

}
