<?php
/*
AUTHOR: RESHMA FASALE
CREATION DATE :30th August 2021
PURPOSE : Check login,Change password,Logout
*/
defined('BASEPATH') OR exit('No direct script access allowed');
class CI_Atsuser {
        public $now;
        protected static $func_overload;
        protected $CI;
        public function __construct()
        {
                $this->CI = & get_instance();
                isset(self::$func_overload) OR self::$func_overload = (extension_loaded('mbstring') && ini_get('mbstring.func_overload'));
                $this->now = time();
                log_message('info', 'ATS User Class Initialized');
        }

        public function change_password($ADMIN_ID,$NEW_PASSWORD,$RE_ENTERED_PASSWORD)
        {
            $db = $this->CI->db;
            $newpasswordEnc = password_hash($NEW_PASSWORD, PASSWORD_BCRYPT);
                // echo $newpasswordEnc."===".$ADMIN_ID;
                $query = "update ci_admin set password='{$newpasswordEnc}' where admin_id='{$ADMIN_ID}'"; 
                // $response = $db->query($query);
                 $db->close();
                 return $newpasswordEnc;
        }   
             
        // MOBILE ACTIVITY
        public function MobileUserVerification($username, $upassword, $androidid,$gcmid="",$platform="",$appname="",$appcode) 
        {
            $date = date("Y-m-d H:i:s");
            $db = $this->CI->db;
            $get_login_details = "SELECT admin_id AS ID, password, firstname, lastname, android_uuid,admin_role_id,email,is_verify,transporter_id,plant_id,gcm_id,(select GROUP_CONCAT(admin_role_title) from ci_admin_roles where INSTR(A1.admin_role_id,admin_role_id)>0) AS ROLE_NAME,'' AS MENUS,
            (SELECT count(*) FROM ci_admin WHERE android_uuid='{$androidid}') AS ANDROID_UUID_COUNT
            FROM ci_admin as A1 WHERE username = '" . $username . "' AND is_active = '1'";
            // echo $get_login_details;
            $response = $db->query($get_login_details);
            $responseArray = array();
            if (count($response->result()) > 0) {
                $result = $response->result();
                $row = $result[0];
             
            // purpose: allows multiple login from a specific device     
                $validPassword = password_verify($upassword, $row->password);

                if($validPassword){
                    // echo 'validPassword';
                    if(is_null($row->android_uuid) || $row->android_uuid == "") {              
                           if ($row->is_verify == 0)
                           {
                                   $responseArray["status"] = 0;
                                   $responseArray["data"] = $row;
                                   $responseArray["msg"] = "Please verify your email address!";
                           }
                           else
                           {
                               $update_gcmid = "UPDATE ci_admin SET android_uuid = '".$androidid."' , gcm_id='".$gcmid."' WHERE admin_id = '".$row->ID."'";
                               // echo $update_gcmid;
                               $db->query($update_gcmid);die();

                               $insert_android_gcm = "INSERT into ci_android_gcm(userid,uuid,gcm_id,appname,platform,is_active,insert_time) values(
                                           '{$row->ID}','{$androidid}','{$gcmid}','{$appname}','{$platform}','1','{$date}'
                                       )";
                               $db->query($insert_android_gcm);
                               // echo  $insert_android_gcm;
                               $responseArray["status"] = 1;
                               $responseArray["data"] = $row;
                               $responseArray["msg"] = "This is your first login.";
                           }
                   }
                   else /* if($row->ANDROID_UUID == $androidid)*/ {
                       
                              $update_gcmid = "UPDATE ci_admin SET android_uuid = '".$androidid."' , gcm_id='".$gcmid."' WHERE admin_id = '".$row->ID."'";
                               $db->query($update_gcmid);  
                                // echo $update_gcmid."+++";die();
                                // $insert_android_gcm = "INSERT into ci_android_gcm(userid,uuid,gcm_id,appname,platform,is_active,insert_time) values(
                                //            '{$row->ID}','{$androidid}','{$gcmid}','{$appname}','{$platform}','1','{$date}'
                                //        )";
                               // $db->query($insert_android_gcm);              
                       $responseArray["status"] = 1;
                       $row->MAPKEY  = "AIzaSyA0Pax7yvzilHvbZi7gfF0JHLko3N4AlZM";
                       $responseArray["data"] = $row;
                       $responseArray["msg"] = "Logged in successfully.";
                   }
               }
            else {
                $responseArray["status"] = 0;
                $responseArray["data"] = array();
//                $responseArray["msg"] = "There is no such user";
                $responseArray["msg"] = "You have entered a wrong password";
            }
            $db->close();

            //Insert User login activity
            $this->MobileUserLoginActivity($username, $upassword, $androidid,$row->ID,$gcmid, $responseArray["status"],$responseArray["msg"]);
            return $responseArray;
        }else{
            // when user doesnt exits
            $responseArray["status"] = 2;
            $responseArray["msg"] = "Account is disabled by Admin!";
            return $responseArray;
        }
        }


        public function MobileUserLoginActivity($username,$password,$androidid,$userid,$gcmid,$passfail,$message)
        {
            $date = date("Y-m-d H:i:s");
            $url =  (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            $ip = $this->CI->input->ip_address();
            $browser = $_SERVER['HTTP_USER_AGENT'];
            $os = $this->CI->atscommon->GetDevice();
            $remotehost = gethostbyaddr($_SERVER['REMOTE_ADDR']);
            $query = "CALL SP_MAINTAIN_MOBILE_LOG('{$username}','{$password}', '{$ip}', '{$date}', '{$os}', '{$url}', '{$browser}', '{$remotehost}', '{$passfail}', '{$message}', '{$androidid}', {$userid}, '$gcmid') ";                                          
            $db = $this->CI->db;
            $db->query($query);
            $db->close();
        }

        
	    public function update_user_token($username, $token){
		$db = $this->CI->db;
		$check_token_exist = "SELECT id as allcount from user_token where username = '{$username}'";
		$response = $db->query($check_token_exist);
		$count = $response->num_rows();

		if ($count>0) {
            $data = "update user_token set token = ? where username = ?";
			$response = $db->query($data, array($token, $username));
		} else {
			$data = "insert into user_token (token, username) values ('".$token."', '".$username."')";
			$response = $db->query($data);
		}

	    }

}
