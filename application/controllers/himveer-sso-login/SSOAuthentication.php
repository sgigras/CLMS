<?php defined('BASEPATH') or exit('No direct script access allowed');
class SSOAuthentication extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ssoauthorization/HimveerSSOAuthModel', 'SSO_model');
    }
    public function login()
    {
        if (isset($_POST["id_token"]))
        {
            //Get Login Token
            $token = $_POST["id_token"];
            //echo $token;
            $sub = $this->SSO_model->JWTDecode($token);

            $profiletoken = $this->SSO_model->GetProfileToken();

            $profiledata = $this->SSO_model->GetProfile($profiletoken,$sub);

            $pin = password_hash('1234', PASSWORD_BCRYPT);
            $this->SSO_model->InsertUpdateHimveerUserInfo($profiletoken,$profiledata,$pin);

            $irlano = $sub;
            $dob = $profiledata["BIRTH_DATE"];
            $this->SSO_model->UserLogin($profiletoken,$irlano,$dob,$pin);
            //print_r($profiledata);
        }
    }
}