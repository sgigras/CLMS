<?php
function CheckLogin($data) {
    $CI = & get_instance();
    $user_logged_in = $CI->session->userdata('user_logged_in');
    if ($user_logged_in === FALSE || !isset($_SESSION["user_logged_in"])) {
        $data = array('content' => array(
                array('view' => 'module/user/login-form', 'data' => array())
            )
        );
        $CI->load->view('view-login-template', $data);
        //redirect('/Login', 'refresh');
    } else {
        $requesturl = $CI->input->server('REQUEST_URI');
        if ($requesturl == "/") {
            $CI->load->view('view-general-template', $data);
        } else {
            $usermenu = $CI->session->userdata("usermenu");
            $flag = FALSE;
            if ($usermenu != NULL) {
                foreach ($usermenu as $row) {
                    if (strpos(strtolower($requesturl), strtolower($row->URL)) > 0) {
                        $_SESSION["parentmenuid"] = $row->PARENTMODULEID;
                        $CI->session->set_userdata("pageusermenu", $row);
                        $flag = TRUE;
                        break;
                    }
                }
            } else {
                $flag = TRUE;
            }
            $flag = TRUE; //TEMP SETTING BECAUSE USER SPECIFIC MODULE NTO READY
            if ($flag === FALSE) {
                show_404();
            }
            $CI->load->view('view-general-template', $data);
        }
    }
}
function JsonDataFilter($data) {
    $filteredData = array_filter($data, function ($el) {
        $filter = array($GLOBALS["JsonDataFilterValue"]);
        return in_array($el['PARENTMODULEID'], $filter);
    });
    return $filteredData;
}
function MenuJsonDataFilter($data) {
    $filteredData = array_filter($data, function ($el) {
        $filter = array($GLOBALS["LEFT_SIDE_BAR_MENU"]);
        return in_array($el['PARENTMODULEID'], $filter);
    });
    return $filteredData;
}
function getLookupValue($result, $lookup_code) {
    return array_key_exists($lookup_code, $result) ? $result[$lookup_code] : (array_key_exists(strtoupper($lookup_code), $result) ? $result[strtoupper($lookup_code)] : "");
}
function getValue($id, $result) {
    return property_exists($result, $id) ? trim($result->$id) : (property_exists($result, strtoupper($id)) ? $result->strtoupper($id) : "");
}
function getDateValue($result, $id) {
    return array_key_exists($id, $result) ? date("d-m-Y", strtotime($result[$id])) : (array_key_exists(strtoupper($id), $result) ? date("d-m-Y", strtotime($result[strtoupper($id)])) : date("d-m-Y"));
    //return date("m-d-Y");
}
function getDateTimeValue($result, $id) {
    return array_key_exists($id, $result) ? date("Y-m-d H:m:s", strtotime($result[$id])) : (array_key_exists(strtoupper($id), $result) ? date("Y-m-d H:m:s", strtotime($result[strtoupper($id)])) : date("Y-m-d H:m:s"));
    //return date("m-d-Y");
}
function getCheckedValue($result, $id, $value) {
    return array_key_exists($id, $result) ? ($result[$id] == $value ? "checked" : "") : (array_key_exists(strtoupper($id), $result) ? ($result[strtoupper($id)] == $value ? "checked" : "") : "");
}
function getImage($filename, $foldername) {
    if ($filename != "") {
        $filepath = UPLOAD_PATH . $foldername . "/" . $filename;
        if (!file_exists($filepath)) {
            $filepath = UPLOAD_URL . $foldername . "/default.jpg";
        } else {
            $filepath = UPLOAD_URL . $foldername . "/" . $filename;
        }
    } else {
        $filepath = UPLOAD_URL . $foldername . "/default.jpg";
    }
    return $filepath;
}
function uploadFileName($data, $keyName, $foldername, $concat_string = "") {
    if ($_FILES[$keyName]['name'] != "") {
        $CI = & get_instance();
        $data[$keyName] = $_FILES[$keyName]['name'];
        extract($data);
        $file = explode(".", $$keyName);
        $file[0] = preg_replace('/\s+/', '', $file[0]);
//        $filename = $file[0] . date("Ymd") . "." . $file[1];
        $timeinmilliseconds = str_replace(' ', '', date("YmdHis") . gettimeofday()["usec"]);
//        $filename = $concat_string . date("Ymd") . "." . $file[1];
        if ($concat_string != "") {
            $filename = $concat_string . '_' . $timeinmilliseconds . "." . $file[1];
        } else {
            $filename = $file[0] . $timeinmilliseconds . "." . $file[1];
        }
        $response = $CI->atsupload->UploadPics($foldername, $filename, $keyName);
        $data[$keyName] = date("Ymd") . "/" . $filename;
    } else {
        $data[$keyName] = $data["H_" . $keyName];
    }
    return $data;
}
function checkselectedvalue($dataarray, $val) {
    $flag = false;
    for ($i = 0; $i < count($dataarray); $i++) {
        if ($dataarray[$i] == $val)
            return true;
    }
    return false;
}
function timeDifference($SECS_STOPG) {
    if ($days = intval((floor($SECS_STOPG / 86400))))
        $SECS_STOPG = $SECS_STOPG % 86400;
    if ($hours = intval((floor($SECS_STOPG / 3600))))
        $SECS_STOPG = $SECS_STOPG % 3600;
    if ($minutes = intval((floor($SECS_STOPG / 60))))
        $SECS_STOPG = $SECS_STOPG % 60;
    if ($seconds = intval((floor($SECS_STOPG / 1))))
        $SECS_STOPG = $SECS_STOPG % 1;
    $SECS_STOPG = intval($SECS_STOPG);
    $str_day = $str_hour = $str_minute = $str_second = "";
    if ($days == 1)
        $str_day = $days . " Day";
    else if ($days > 1)
        $str_day = $days . " Days";
    if ($hours == 1)
        $str_hour = $hours . " Hour";
    else if ($hours > 1)
        $str_hour = $hours . " Hours";
    if ($minutes == 1)
        $str_minute = $minutes . " Minute";
    else if ($minutes > 1)
        $str_minute = $minutes . " Minutes";
    if ($seconds == 1)
        $str_second = $seconds . " Second";
    else if ($seconds > 1)
        $str_second = $seconds . " Seconds";
    return $str_day . " " . $str_hour . " " . $str_minute;
}
function epochsecDifference($START_TIME, $END_TIME) {
    //Seperate all values of the start date.
    $st_arr = explode(' ', $START_TIME);
    $st_arr1 = explode('-', $st_arr[0]);
    $st_arr2 = explode(':', $st_arr[1]);
    $st_year = $st_arr1[0];
    $st_month = $st_arr1[1];
    $st_day = $st_arr1[2];
    $st_hour = $st_arr2[0];
    $st_min = $st_arr2[1];
    $st_sec = $st_arr2[2];
    //Seperate all values of the end date.
    $end_arr = explode(' ', $END_TIME);
    $end_arr1 = explode('-', $end_arr[0]);
    $end_arr2 = explode(':', $end_arr[1]);
    $end_year = $end_arr1[0];
    $end_month = $end_arr1[1];
    $end_day = $end_arr1[2];
    $end_hour = $end_arr2[0];
    $end_min = $end_arr2[1];
    $end_sec = $end_arr2[2];
    $epoch_1 = @mktime($end_hour, $end_min, $end_sec, $end_month, $end_day, $end_year);
    $epoch_2 = @mktime($st_hour, $st_min, $st_sec, $st_month, $st_day, $st_year);
    $diff_seconds = $epoch_1 - $epoch_2;
    return $diff_seconds;
}
function ARRAYTOSTRING($JSON) {
    $coalplanningStr = "";
    $ii = 0;
    foreach ($JSON as $key => $value) {
        if ($ii != 0)
            $coalplanningStr .= "~";
        $i = 0;
        foreach ($value as $k => $v) {
            if ($i != 0)
                $coalplanningStr .= ",";
            $coalplanningStr .= $k . "#" . $v;
            $i++;
        }
        $ii++;
    }
    return $coalplanningStr;
}
function logToFile($filename, $msg) {
    // open file
    $fd = fopen($filename, "a");
    // append date/time to message
    $str = "[" . date("Y/m/d h:i:s", time()) . "] " . $msg;
    // write string
    fwrite($fd, $str . "\n");
    // close file
    fclose($fd);
}
function getcurlresponse($URL) {
    $headers = array('Accept: application/text', 'Content-Type: application/text');
    $ch = curl_init($URL);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
    curl_setopt($ch, CURLOPT_USERPWD, "admin:1808");
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "");
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($ch);
    curl_close($ch);  // Seems like good practice
    return $result;
}
//function SEND_PUSH_NOTIFICATION($USER_GCMID, $TITLE, $MESSAGE, $NOTIF_DATA) 
//{
//    define('API_ACCESS_KEY', 'AIzaSyAU8y0xALjCssvcDJ0I0aOwYD3TX9O8GSg');//BALCO PROJECT    
//
//    $GCM_RECEIVER_ID = array($USER_GCMID);
//
//    $MSG = array(
//        'title' => $TITLE,
//        'body' => $MESSAGE,
//        'soundname' => "default",//should be under platforms\android\app\src\main\res\raw . HERE DON'T GIVE RINGTONE'S EXTENSION ONLY NAME IS REQUIRED
//        'vibrate' => 1,//VIBRATE TRUE
//        'content-available' => 0,//USED TO SEND NOTIFICATION NOT ONLY WHEN THE APP IS OPEN BUT ALSO WHEN THE APP IS NOT OPEN / STARED 
//        'style'=> "inbox",//TO SHOW ALL NOTIFICATION FROM SAME TITLE IN ONE BUNCH IN NOTIFICATION BAR
//        'summaryText' => "%n% new notifications from BALCO",
//        'icon' => "mdpi",
//        'image-type' => "circular",
////        "actions"=>$NOTIF_DATA,
//        "notId"=> 10
//    );
//     
//    $FIELDS = array(
//        'registration_ids' => $GCM_RECEIVER_ID,
//        'data' => $MSG,
//        'priority'=> "high"
//    );
//
//    $HEADERS = array(
//        'Authorization: key=' . API_ACCESS_KEY,
//        'Content-Type: application/json'
//    );
//
//    $CURL_INIT = curl_init();
//    curl_setopt($CURL_INIT, CURLOPT_URL, 'https://android.googleapis.com/gcm/send');
//    curl_setopt($CURL_INIT, CURLOPT_POST, true);
//    curl_setopt($CURL_INIT, CURLOPT_HTTPHEADER, $HEADERS);
//    curl_setopt($CURL_INIT, CURLOPT_RETURNTRANSFER, true);
//    curl_setopt($CURL_INIT, CURLOPT_SSL_VERIFYPEER, false);
//    curl_setopt($CURL_INIT, CURLOPT_POSTFIELDS, json_encode($FIELDS));
//    $CURL_RESPONSE = curl_exec($CURL_INIT);
//    curl_close($CURL_INIT);
//    
//    
////    echo "<pre>";
////    print_r($CURL_RESPONSE);
////    print_r($MSG);
////    echo "</pre>";
////    return '..SENT>>'.$TITLE;
//}
function ChangePasswordMail($Username, $Password, $Firstname, $userid) {
    $CI = & get_instance();
    $CI->load->library('Ats/Atsuser');
    $CI->load->helper('Ats/email_helper');
    $ToMailArray = $CI->atsuser->getUserMail($userid);
    $MailSubBody = $CI->atsuser->getMailSubBody("CHANGE_PASS");
    $MailSubBody = json_decode(json_encode($MailSubBody), true);
    $To = json_decode(json_encode($ToMailArray), true);
    $Toemail = $To[0]['emailaddress'];
    $Subject = $MailSubBody[0]['MESSAGE_SUBJECT'];
    $Body = $MailSubBody[0]['MESSAGE_BODY'];
    $BodyF = str_replace('[user_greetings]', $Firstname, $Body);
    $BodyF = str_replace('[User_name]', $Username, $BodyF);
    $BodyF = str_replace('[Pass_word]', $Password, $BodyF);
    $isSend = SEND_MAIL($Toemail, "", "", $Subject, $BodyF, "");
    if ($isSend == "success") {
        return 1;
    } else {
        return 0;
    }
}
