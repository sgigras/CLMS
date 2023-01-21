<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of YourOrderAPI
 *
 * to display all the liqours orders created by user to canteen club brewery
 * @author JItendra pal
 */


class YourOrderAPI extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        auth_check(); // check login auth
        $this->load->model('Order_details/order_model', 'order_model');
    }

    public function index()
    {
        $user_id = $this->session->userdata('admin_id');
        $response = $this->order_model->fetchAllOrders($user_id);
        $cart_id_array = array();
        $cart_id = 0;
        $order_details_array = array();
        foreach ($response as  $row) {
            $cart_id = $row['cart_id'];
            if (!in_array($cart_id, $cart_id_array)) {
                array_push($cart_id_array, $cart_id);
                $order_details_array[$cart_id][] = $row;
            } else {
                $order_details_array[$cart_id][] = $row;
            }
        }
        $this->load->view('admin/includes/_header');
        $this->load->view('order/display_all_orders', array('display_type' => 'Canteen Details','order_history' => $order_details_array, 'title' => 'your_orders', 'fa_form_icon' => 'fa fa-orders'));
        $this->load->view('admin/includes/_footer');
    }
}
