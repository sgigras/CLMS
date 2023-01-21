<?php
/*
AUTHOR: RESHMA FASALE
CREATION DATE :30th August 2021
PURPOSE : Check login,Change password,Logout
*/

class UsersAPI extends CI_Controller
{
    function __construct() {
        parent::__construct();
        $this->load->library(array('session', 'Ats/atscommon', 'Ats/atsuser'));
    }
    
    function Login()
    {
        $data = json_decode(file_get_contents('php://input'));
    //    print_r($_POST);die();
        $username = $data->username;
        $upassword = $data->upassword;
        $androidid = $data->androidid;
        $appcode = $data->appcode;
        $gcmid = isset($data->gcm)?$data->gcm:"";
        $platform = isset($data->platform)?$data->platform:"";
        $appname = isset($data->appname)?$data->appname:"";
        $response=$this->atsuser->MobileUserVerification($username,$upassword,$androidid,$gcmid,$platform,$appname,$appcode);
        $this->load->view('mobileapi/blank-template',array("CONTENT"=> json_encode($response)));
    }
    
    public function ChangeUserLoginPassword()
    {        
        $data = json_decode(file_get_contents('php://input'));
        $ADMIN_ID = $data->ADMINID;
        $RE_ENTERED_PASSWORD = $data->RE_ENTERED_PASSWORD;
        $NEW_PASSWORD = $data->NEW_PASSWORD;
        // $USERNAME = $data->username;
        // $FIRSTNAME = $data->firstname;
        // $E_OLD_PASSWORD = $this->atscommon->PasswordEncryption($OLD_PASSWORD);
        // $E_NEW_PASSWORD = $this->atscommon->PasswordEncryption($NEW_PASSWORD);
     
        $response=$this->atsuser->change_password($ADMIN_ID,$NEW_PASSWORD,$RE_ENTERED_PASSWORD);
        $this->load->view('mobileapi/blank-template',array("CONTENT"=> json_encode($response)));
    }
   
   
    // public function LatestAppVersion()
    // {
    //     // added new line to get data 
    //     $data = json_decode(file_get_contents('php://input'));
    //     //logToFile(LOG_PATH."Appvesion.txt", file_get_contents('php://input'));
    //     $response=$this->devicelib->LatestAppVersion($data);
    //     echo json_encode($response);
    // }
    public function logLogoutTime()
    {
        $data = json_decode(file_get_contents('php://input'));
        $response = $this->atsuser->logLogoutTime($data);
        echo json_encode($response);
    }
}

