<?php

// 2-18-2019 DINESH      
function SEND_PUSH_NOTIFICATION_CRON($ID, $USER_GCMID, $TITLE, $MESSAGE, $FROM_USERID, $TO_USERID, $ANDROID_UUID) {
    // define('API_ACCESS_KEY_A', 'AIzaSyB0SVrCf6ZAaJoHYXetgbDAcXbo3zTWsLw');//MMOE PROJECT  
    
    if($TITLE==="Request For Vehicle"){
        $url="notification.html";
    }else if($TITLE==="Available Vehicle"){
        $url="notification.html";
    }

    $GCM_RECEIVER_ID = array($USER_GCMID);

    $MSG = array(
        'title' => $TITLE,
        'body' => $MESSAGE,
        "android_channel_id"=> "channel1",
        'sound' => "crystal", //should be under platforms\android\app\src\main\res\raw . HERE DON'T GIVE RINGTONE'S EXTENSION ONLY NAME IS REQUIRED
        // file:\\platforms\android\app\src\main\res\raw.mp3
        'vibrate' => 1, //VIBRATE TRUE
        'content-available' => 0, //USED TO SEND NOTIFICATION NOT ONLY WHEN THE APP IS OPEN BUT ALSO WHEN THE APP IS NOT OPEN / STARED 
        'style' => "inbox", //TO SHOW ALL NOTIFICATION FROM SAME TITLE IN ONE BUNCH IN NOTIFICATION BAR
        'summaryText' => "%n% new notifications from FAST",
        'icon' => "mdpi",
        'image-type' => "circular",
        "actions" => "",
        "notId" => $ID,
        "to_userid" => $TO_USERID,
//        "REQUEST_RIDE_ID"=> $REQUEST_RIDE_ID,
        "FROM_USERID" => $FROM_USERID,
        "url"=>$url
//        "SOURCE"=>$SOURCE,
//        "DEVICEID"=> $DEVICEID,
//        "DESTINATION"=>$DESTINATION,
//        "ID"=>$ID,
//        "IS_OUTSTATION"=>$IS_OUTSTATION,
//          "NAME"=>$NAME
            //         "data"=> array(
            //   "notificationOptions" => array(
            //     "sound"=> true
            //   )
            // )
            // "DEVICEID"=>$DEVICEID
    );

    $FIELDS = array(
        'registration_ids' => $GCM_RECEIVER_ID,
        'data' => $MSG,
        'priority' => "high"
    );

    $HEADERS = array(
        'Authorization: key=' . 'AAAAUWSLg4U:APA91bFYjiKBxr6e3guoWkVEt-olc7yxoliEXfRgghIzdOUBaojPMmBWq2P9UOLKNbBlNsZxWUSs3zKDUTLXEjdTOEc-tCmANqW0Ea_po7a0tck0pknzkaJ1SWTSgKi9CZNnvuizN4Wx',
        'Content-Type: application/json'
    );

    $CURL_INIT = curl_init();
    curl_setopt($CURL_INIT, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($CURL_INIT, CURLOPT_POST, true);
    curl_setopt($CURL_INIT, CURLOPT_HTTPHEADER, $HEADERS);
    curl_setopt($CURL_INIT, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($CURL_INIT, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($CURL_INIT, CURLOPT_POSTFIELDS, json_encode(
                    $FIELDS));
    $CURL_RESPONSE = curl_exec($CURL_INIT);
    curl_close($CURL_INIT);
    $result = json_decode($CURL_RESPONSE);
    echo '<pre>';
    print_r($result);
//    die();

    $status = $result->{'success'};
//    echo $status;
    // die();
//    $ci=& get_instance();
//    $ci->load->database();
//    $sql = "insert into agt_android_notifcation_log (FROM_USER_ID,TO_USER_ID,ANDROID_UUID,TO_GCM_ID,TITLE,MESSAGE,APPNAME,PLATFORM,INSERT_TIME,NOTIFICATION_STATUS) values('{$FROM_USERID}','{$TO_USERID}','{$ANDROID_UUID}','{$USER_GCMID}','{$TITLE}','{$MESSAGE}','MMOE','A',now(),'{$status}')"; 
//    $query = $ci->db->query($sql);
//    $ci->db->close(); 
    return $status;
}
