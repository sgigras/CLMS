<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Smslib
 *
 * @author ATS-016
 */
class CI_Smslib {

    //put your code here
    protected $now;
    protected static $func_overload;
    protected $CI;

    public function __construct() {
        $this->CI = & get_instance();
        isset(self::$func_overload) OR self::$func_overload = (extension_loaded('mbstring') && ini_get('mbstring.func_overload'));
        $this->now = time();
//        log_message('info', 'ATS Email Class Initialized');
    }

    public function fetchSMSToSend() {
        $db = $this->CI->db;
        $query = "Select ID,MSG from sms_log where IS_SMS_SENT='N' order by INSERT_TIME desc limit 25";
        $response = $db->query($query);
        $data = $response->result();
        $db->close();
        for ($i = 0; $i < count($data); $i++) {

            // $mobileNumber = $data[$i]->MOBILENO;
            $message = $data[$i]->MSG;

            $sms_result = CallCurl_SMS($message);
//            echo $sms_result;
            $result=explode(' | ',$sms_result);
            echo $result[0];
            $db = $this->CI->db;
            if ($result[0] == 'SUBMIT_SUCCESS') {
                $db->query("Update sms_log SET IS_SMS_SENT='Y',IS_SMS_DELIVERED='Y',SMS_RESPONSE='{$sms_result}',SMS_RESPONSE_TIME=NOW() where ID='{$data[$i]->ID}'");
            } else {
                $error_message = $sms_result;
                echo $error_message;
                $db->query("Update sms_log SET IS_SMS_SENT='Y',IS_SMS_DELIVERED='N',SMS_RESPONSE='{$sms_result}',SMS_RESPONSE_TIME=NOW() where ID='{$data[$i]->ID}'");
            }
            $db->close();
        }

//        }
    }

    public function insertCronLog() {
        $db = $this->CI->db;
        $path = DOMAIN . 'crons/Sms_Con/sendAllSms';
        $query = "INSERT INTO cron_log(FILENAME,EXECUTION_TIME)VALUES('{$path}',now())";
        $db->query($query);
        $db->close();
    }

}
