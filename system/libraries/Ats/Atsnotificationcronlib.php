<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Atsnotificationcronlib
 *
 * @author ATS
 */
class CI_Atsnotificationcronlib {

    //put your code here

    public $now;
    protected static $func_overload;
    protected $CI;

    public function __construct() {
        $this->CI = & get_instance();
        isset(self::$func_overload) OR self::$func_overload = (extension_loaded('mbstring') && ini_get('mbstring.func_overload'));
        $this->now = time();
        log_message('info', 'ATS User Class Initialized');
    }

    public function getlist() {
        $db = $this->CI->db;
        $query = 'select id,to_gcm_id ,notification_title ,message ,notification_send_status ,from_userid,to_user_id,android_uuid from acg_notification where notification_send_status = "0"';
        $response = $db->query($query);
        $db->close();

        $result = $response->result_array();
        $array = json_decode(json_encode($result), true);
//            print_r($array);
//            echo count($array);

        if (count($array) > 0) {
            for ($i = 0; $i < count($array); $i++) {
                $userid = $array[$i]['from_userid'];
                $SHARE_USERID = $array[$i]['to_user_id'];
                $android_uuid = $array[$i]['android_uuid'];
                $ID = $array[$i]['id'];


                $USER_GCMID = $array[$i]['to_gcm_id'];
                $TITLE = $array[$i]['notification_title'];
                $MESSAGE = $array[$i]['message'];
                $db = $this->CI->db;
               
                $response = SEND_PUSH_NOTIFICATION_CRON($ID, $USER_GCMID, $TITLE, $MESSAGE, $userid, $SHARE_USERID, $android_uuid);
                // die();
//                return $response;
                if ($response == 1) {

                    $db = $this->CI->db;
                    $query = "update acg_notification set notification_send_status=1 where id=$ID";
                    $response = $db->query($query);
                    // echo $query;
                    // die();
                    $db->close();
                }
            }
        }
    }

}
