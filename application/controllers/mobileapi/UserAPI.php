<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UsersAPI
 *
 * @author JITENDRA PAL
 * FOR USER LOGIN AND  
 */
//defined('BASEPATH') OR exit('No direct script access allowed');
//header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Origin: *");

class UserAPI extends MY_Controller
{

    //put your code here

    public function __construct()
    {
        parent::__construct();
        $this->load->model('mobile/User_model', 'user_model');
    }

    public function index()
    {
        echo 'hello';
    }

    public function login()
    {
        $login_data = json_decode(file_get_contents("php://input"), true);
        // $login_data = array("irl_no" => "87654321", "date_of_birth" => "1996-06-06", "pin_code" => "snehaltalele");
        $response = $this->user_model->mobile_login($login_data);
        $module_list = $response['module_list'];
        foreach ($module_list as $row) {
            $row->module_name = trans($row->module_name);
            $row->sub_module_name = trans($row->sub_module_name);
        }
        $response['module_list'] = $module_list;
        
        echo json_encode($response);
    }
}
