<?php
defined('BASEPATH') or exit('No direct script access allowed');

class HimveerSSOAuthModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
		$this->load->model('admin/auth_model', 'auth_model');
		$this->load->helper(array('browser_ip'));
    }

    //Himveer Login Url (Redirect to Himveer Portal)
    public function ImplicitLoginUrl()
    {
        $authorizeUrl = $this->config->item("api-baseurl").$this->config->item("authorize-url");
        $client_id =  $this->config->item("client_id");
        $scope = "openid profile";
        $response_type = "id_token";
        $redirectUri = $this->config->item("redirect-uri");
        $state = uniqid(rand(), true);
        $nonce = uniqid(rand(), true);
        $requestType = "GET";
        $response_mode = "form_post";

        $url = $authorizeUrl . "?client_id=" . $client_id . "&scope=" . $scope . "&response_type=" . $response_type .
            "&redirect_uri=" . $redirectUri . "&state=" . $state . "&nonce=" . $nonce . "&response_mode=" . $response_mode;

        return $url;
    }

    //Get Employee ID on the basis og Login Token
    public function JWTDecode($token)
    {
        $secret = "";
        $tokenParts = explode('.', $token);
        $header = base64_decode($tokenParts[0]);
        $payload = base64_decode($tokenParts[1]);
        $signatureProvided = $tokenParts[2];

        $base64UrlHeader = base64_encode($header);
        $base64UrlPayload = base64_encode($payload);
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);
        $base64UrlSignature = base64_encode($signature);

        $signatureValid = ($base64UrlSignature === $signatureProvided);
        $json = json_decode($payload);

        return $json->sub;
    }

    //Get Token for profile with brief details
    public function GetProfileToken()
    {
        $requestUrl = $this->config->item("api-baseurl").$this->config->item("get-token-url");
        $client_id =  $this->config->item("client_id");
        $client_secret =  $this->config->item("client_secret");
        $scope = $this->config->item("hrms-scope-token");
        $requestType = "POST";
        $grant_type = "client_credentials";
        $ContentType = 'application/x-www-form-urlencoded';
        $headeroption = array(
            'Authorization: Basic '.base64_encode($client_id.":".$client_secret),
            'Content-Type: '.$ContentType
        );
        $dataArray = 'grant_type='.$grant_type.'&scope='.$scope;
        $data = self::RequestAPI($requestUrl, $requestType,$headeroption,$dataArray);
        $profiletoken = $data["access_token"];

        return $profiletoken;
    }

    //Get Profile on the basis of profile token
    public function GetProfile($token,$RegtlNo)
    {
        $requestUrl = $this->config->item("hrms-api-baseurl").$this->config->item("hrms-basic-detail");
        $requestType = "POST";
        $ContentType = 'application/x-www-form-urlencoded';
        $scope = $this->config->item("hrms-scope");
        $headeroption = array(
            'Authorization: Bearer '.$token,
            'Content-Type: '.$ContentType
        );
        $dataArray = 'RegtlNo='.$RegtlNo;
        $dataArray .= '&scope='.$scope;
        $data = self::RequestAPI($requestUrl, $requestType,$headeroption,$dataArray);

        return $data;
    }
    
    //Curl Request Url for Himveer SSO Login and Get Employee Detail (Not retired Person)
    function RequestAPI($requestUrl,$requestType,$headerOption=array(),$dataArray="")
    {
        $curl = curl_init();
        $option = array(
            CURLOPT_URL => $requestUrl,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_SSL_VERIFYHOST=> 0,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $requestType,
            CURLOPT_HTTPHEADER => $headerOption,
            CURLOPT_POSTFIELDS => $dataArray
        );

        curl_setopt_array($curl, $option);
        $response = curl_exec($curl);
        if ($response === false)
        {
            print_r('Curl error: ' . curl_error($curl));
        }
        curl_close($curl);
        $data = json_decode($response,true);
        return $data;
    }

    //Insert Himveer User Info in ITBP (Only Required for login)
    public function InsertUpdateHimveerUserInfo($profiletoken,$profiledata,$pin)
    {
        $V_USERNAME = $profiledata["FORCE_NUMBER"];
        $V_PIN = $pin;
        $V_NAME = $profiledata["NAME"];
        $V_DOB = $profiledata["BIRTH_DATE"];
        $V_EMAIL = $profiledata["EMAIL_ID"];
        $V_MOBILENO = $profiledata["MOBILE_NO"];
        $V_TOKEN = $profiletoken;
        $V_RANK = $profiledata["PRESENT_RANK_NAME"];
        $V_SUB_RANK = $profiledata["PRESENT_SUBRANK_NAME"];

        $query = "CALL SP_INSERT_UPDATE_HIMVEER_USER_DETAIL('$V_USERNAME','$V_PIN','$V_NAME','$V_DOB','$V_EMAIL',
        '$V_MOBILENO','$V_TOKEN','$V_RANK','$V_SUB_RANK')";

        // print_r($profiledata);
        // echo $query;
        // die;

        $response = $this->db->query($query);
		$result = $response->result();
		$this->db->close();

        return true;
    }

    //Get Userinfo from Himveer
    public function UserLogin($token,$irlano,$dob,$pin)
    {
        $data = array(
            'irlano' => $irlano,
            'dob' => $dob,
            'pin' => $pin
        );
        //$additionaldata = $this->UserAdditionalDetail($irlano,$dob);
        $resultArray = $this->HimveerLogin($irlano,$dob,$pin);
        // print_r($resultArray);
        // die;
        if (count($resultArray) > 0) {
            $result = $resultArray[0];
            $admin_data = array(
                'admin_id' => $result->admin_id,
                'entity_id' => (isset($result->entity_id) ? $result->entity_id : ''),
                'username' => $result->username,
                'rank' => $result->user_rank,
                'mobile_no' => $result->mobile_no,
                'full_name' => $result->firstname,
                'admin_role_id' => $result->admin_role_id,
                'admin_role' => $result->admin_role_title,
                'is_supper' => $result->is_supper,
                'is_admin_login' => TRUE,
                'token' => $token,
                'IsHimveerLogin'=>TRUE
            );

            $this->session->set_userdata($admin_data);
        }
        $this->session->userdata('token');
        if ($result->is_supper)
            redirect(base_url('admin/dashboard/index_2'), 'refresh');
        else
            redirect(base_url('admin/dashboard/index_2'), 'refresh');
    }

    public function UserAdditionalDetail($irlano,$dob)
    {
        $query = "select `rank`, present_appoitment, status, location_name, district_name, state_name from bsf_hrms_data where irla='" . $irlano
            . "' and date_of_birth='" . $dob . "'";
        $response = $this->db->query($query);
        $result = $response->result();
        if (count($result) >0)
        {
            return $result;
        }
        else
        {
            return array();
        }
    }

    public function HimveerLogin($irlano,$dob,$pin)
    {
        $this->saveBrowserLoginDetails($irlano,'');
        $query = "select * 
            ,(select admin_role_id from ci_admin_roles where admin_role_id=ci_admin.admin_role_id) as admin_role_id
            ,(select admin_role_title from ci_admin_roles where admin_role_id=ci_admin.admin_role_id) as admin_role_title
            from ci_admin where username='" . $irlano .
            "' and date_of_birth='" . $dob . "'"; 
        $response = $this->db->query($query);
        $result = $response->result();
        if (count($result) > 0)
        {
            // $validPassword =($pin==$result[0]->password?true:false);
            // if ($validPassword) {
                $query = "update ci_admin set last_login = now() where username = '".$irlano."' and date_of_birth = '".$dob."'";
                $this->db->query($query);
                return $result;
            // }
        }
        return array();
    }

    public function saveBrowserLoginDetails($username,$pass){

		$db = $this->db;
		// print_r($pass);die()
		$getBrowserdetails = getBrowser();

		$userAgent = $getBrowserdetails['userAgent'];
		$name = $getBrowserdetails['name'];
		$version = $getBrowserdetails['version'];
		$platform = $getBrowserdetails['platform'];
		$ipaddress = $getBrowserdetails['ipaddress'];

		$query = "insert into user_login_details (username,login_time,logout_time,password,browser_agent,browser_name,browser_platform,browser_version,ip_address) values ('$username',now(),now(),'$pass','$userAgent','$name','$platform','$version','$ipaddress')";
		$db->query($query);
		return true;

	}

    public function HimveerLogoutUrl()
    {
        $data = $this->session->get_userdata();

        if (isset($data["IsHimveerLogin"]) && $data["IsHimveerLogin"] == TRUE) {
            $requestUrl = $this->config->item("api-baseurl") . $this->config->item("user-logout") .
                "?id_token=" . $data["token"] . "&post_logout_redirect_uri=" . $this->config->item("portal-post-back-logout-url") .
                "&state=" . $this->config->item("portal-post-back-logout-url");

            return $requestUrl;
        }
        else
        {
            return "";
        }
    }
}