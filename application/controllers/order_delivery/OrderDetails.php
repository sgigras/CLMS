<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CartDetails
 *
 * to display order details to canteen club brewery
 * @author JItendra pal
 */
class OrderDetails extends MY_Controller
{

    //put your code here
    public function __construct()
    {
        parent::__construct();
        auth_check(); // check login auth
        $this->rbac->check_module_access();
        $this->load->model('Order/Order_model', 'order_model');
        $this->load->model('admin/Activity_model', 'activity_model');
        $this->load->helper(array('bsf_form/list_field', 'bsf_form/master_table', 'Ats/common'));
    }

    public function fetchOrderDetails()
    {
        // $order_code = $this->input->post('order_code');
        $order_code = '00VPZPCS';

        $page_labels = unserialize(ORDER_DELIVERY_SUMMARY); //to fetch page labels

        $response = $this->order_model->fetchOrderDetails($order_code);

        if (count($response) > 0) {
            $cart_details['cart_type'] = $response[0]['cart_type'];
            $cart_details['cart_id'] = $response[0]['cart_id'];
            $cart_details['cart_table_data'] = $response;
        }

        $data = array_merge($page_labels, $cart_details);
        // echo '<pre>';
        // print_r($data);
        // echo '</pre>';
        // die();
        $this->load->view('admin/includes/_header');
        $this->load->view('cart/summary', $data);
        $this->load->view('admin/includes/_footer');
    }
}
