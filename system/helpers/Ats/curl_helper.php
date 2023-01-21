<?php

function CallCurl($url, $JSON_OBJ) {


    $ch = curl_init($url);

    $headers = array("");



    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $JSON_OBJ);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_NOBODY, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 4000);
    $exception_output = curl_exec($ch);
    curl_close($ch);
    print_r($exception_output);
    return $exception_output;
}

function CallCurl_P10($command) {
//    $result = $command;
    // $cmd_str = $this->StringEnc($command);    //  Encrypted string
    // $TTSURLEN = urlencode("http://".$panelip."/?~@".$cmd_str."*!");
    // log_message('error', 'LED_URL '.$command);
    $TTSURLEN = urlencode($command);
    $TTSURLDE = urldecode($TTSURLEN);
    $headers = array();

    $ch = curl_init($TTSURLDE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5); //   timeout in seconds
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($ch);

    if (curl_errno($ch)) {
        $result = curl_errno($ch) . ' - ' . curl_error($ch);
    }
    curl_close($ch);  // Seems like good practice

    return $result;
}

function CallCurl_SMS($message){
//    $message1= urlencode($message);
//    $TTSURLDE = urldecode($message1);
    $url= $message;
    // echo $message;die;
    $curl = curl_init($url);
    curl_setopt_array($curl, array(
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
));

$response = curl_exec($curl);

curl_close($curl);
 return $response;
}

?>
