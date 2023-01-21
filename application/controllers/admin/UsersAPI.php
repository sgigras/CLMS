<?php
header("Access-Control-Allow-Origin: *");
class UsersAPI extends CI_Controller
{
    function __construct() {
        parent::__construct();
        $this->load->library(array('session', 'Ats/atscommon', 'Ats/atsuser'));
    }
    
    function Login()
    {
    //    echo 'Hello'; die();
        $data = json_decode(file_get_contents('php://input'));
       print_r($_POST);die();
    
//        $username = $data->username;
//        $upassword = $data->upassword;
        $username = $data->username;
        // echo $username;die();
        $upassword = $data->upassword;
        $androidid = $data->androidid;
        $appcode = $data->appcode;
        $gcmid = isset($data->gcm)?$data->gcm:"";
        $platform = isset($data->platform)?$data->platform:"";
        $appname = isset($data->appname)?$data->appname:"";
        // $pass = $this->atscommon->PasswordEncryption($data->upassword);
        $response=$this->atsuser->MobileUserVerification($username,$upassword,$androidid,$gcmid,$platform,$appname,$appcode);
        $this->load->view('mobileapi/blank-template',array("CONTENT"=> json_encode($response)));
    }
    
    function ChangePassword()
    {
        //logToFile(LOG_PATH."ChangePassword.txt", file_get_contents('php://input'));
        $data = json_decode(file_get_contents('php://input'));
        $oldpassword = $data->oldpassword;
        $newpassword = $data->newpassword;
        $userid = $data->userid;
        $response=$this->atsuser->MobileUserChangePassword($oldpassword,$newpassword,$userid);
        $this->load->view('mobileapi/blank-template',array("CONTENT"=> json_encode($response)));
    }
   
    public function Password($action,$password)
    {
        if ($action == "E")
        {
            echo $this->atscommon->PasswordEncryption($password);
        }
        else
        {
            echo $this->atscommon->PasswordDecryption($password);
        }
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
    
    public function logLogoutTimeauger()
    {
        $data = json_decode(file_get_contents('php://input'));
        $response = $this->atsuser->logLogoutTimeauger($data);
        echo json_encode($response);
    }
    
    public function updateAugurLogin(){
        $data = json_decode(file_get_contents('php://input'));
        $response = $this->atsuser->updateAugurLogin($data);
        echo json_encode($response);
    }
}

