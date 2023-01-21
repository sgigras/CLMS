<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
 * Pradeep Taria
 * Aniruddha Telemetry Systems Pvt. Ltd.
 * Dated : 31-10-2021
 * Descriptions : Printable invoice copy for customer
 * 
 */

class InvoiceAPI extends MY_Controller {

    function __construct() {

        parent::__construct();
        auth_check(); // check login auth
        $this->rbac->check_module_access();

//        $this->load->model('Order/Order_model', 'order_model');
//        $this->load->model('admin/Activity_model', 'activity_model');
    }

    //-----------------------------------------------------		
    function index() {
        $data = array();
        $this->load->view('admin/includes/_header');
        $this->load->view('order/invoice', $data);
        $this->load->view('admin/includes/_footer');
    }

}
