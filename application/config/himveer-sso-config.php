<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$config["siteurl"] = "https://10.246.22.135/CLMS/";//http://localhost/CLMS/

// API Details
$config["api-baseurl"] = "https://uatserver2.itbp.gov/HimveerConnect6Local/";
$config['client_id'] = "clms_new";
$config["client_secret"] = "SuperSecretPassword";
$config["scope"] = "openid";
$config["redirect-uri"] = $config["siteurl"]."himveer-sso-login/SSOAuthentication/login";

$config["get-token-url"] = "connect/token";
$config["get-profile-detail-api-url"] = "connect/userinfo";
$config["authorize-url"] = "connect/authorize";
$config["user-logout"] = "connect/endsession";


$config["hrms-basic-detail"] = "api/Clms/GetBasicDetails";
$config["hrms-api-baseurl"] = "https://uatserver2.itbp.gov/HrmsApiCore/";
$config["hrms-scope-token"] = "HrmsApi.Clms";
$config["hrms-scope"] = "HrmsApi.Clms";

$config["portal-post-back-logout-url"] = $config["siteurl"];
