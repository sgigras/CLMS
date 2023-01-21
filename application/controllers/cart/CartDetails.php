<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CartDetails
 *
 * @author ATS-16
 */

class CartDetails extends MY_Controller
{

    //put your code here
    public function __construct()
    {
        parent::__construct();
        auth_check(); // check login auth
        // $this->rbac->check_module_access();
        $this->load->model('Cart_details/Cart_model', 'cart_model');
        $this->load->model('Order_details/Order_model', 'order_model');
        $this->load->model('admin/Activity_model', 'activity_model');
        $this->load->helper(array('bsf_form/list_field', 'bsf_form/master_table', 'Ats/common'));
    }

    //displays session cart to the user after click on cart icon or cart views
    public function viewSessionCart()
    {
        // echo '<pre>';
        // print_r($_SESSION);
        // echo '</pre>';
        // die();
        if ($this->session->userdata('session_cart_details')) {
            $cart_details = $this->session->userdata('session_cart_details');
            $cart_id = $cart_details['cart_id'];

            $this->fetchCartDetails($cart_id);
        } else {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
    }

    public function createContinueSessionShopping()
    {
        $this->session->set_userdata(
            'continue_cart_shopping_session',
            array(

                'delivarable_entity_id' => $this->input->post('delivarable_entity_id'),
                'cart_type' => $this->input->post('cart_type'),
                'cart_id' => $this->input->post('cart_id'),
                'redirect_url' => $this->input->post('redirect_url'),
                'trigger_consumer_auto_select_change' => 'true',
                'page_mode' => $this->input->post('page_mode')
            )
        ); //CART_SUMMARY from list_field_helper
        // print_r()
        $session_data = $this->session->userdata('continue_cart_shopping_session');

        $message = array("V_SWAL_TYPE" => "success");
        $message = array_merge($session_data, $message);
        echo json_encode($message);
    }



    //displays fetch cart details and display to the user
    public function fetchCartDetails($cart_id)
    {
        $data['title'] = trans('cart');
        $data['mode'] = 'A';

        $response = $this->cart_model->fetchCartDetalis($cart_id);

        if (count($response) > 0) {
            $data['cart_type'] = $response[0]['cart_type'];
            $data['cart_id'] = $response[0]['cart_id'];
            // $data['selling_price'] = $response[0]['selling_price'];
            $data['delivarable_entity_id'] = $response[0]['ordered_to_entity_id'];
            $data['cart_table_data'] = $response;
        }

        // echo '<pre>';
        // print_r($data);
        // echo '</pre>';
        // die();

        $data = array_merge($data, unserialize(PURCHASE_CART));
        $this->load->view('admin/includes/_header');
        $this->load->view('cart/cart', $data);
        $this->load->view('admin/includes/_footer');
    }


    //to add update quantity from cart view for the given cart
    public function checkOut()
    {
        $cart_details = array(
            'cart_data' => $this->input->post('cart_data'),
            'cart_id' => $this->input->post('cart_id'),
            'cart_type' => $this->input->post('cart_type'),
            'page_mode' => $this->input->post('page_mode'),
            'user_id' => $this->session->userdata('admin_id'),
            'liquor_per_bottle' => $this->input->post('liquor_per_bottle'),
            'liquor_count' => $this->input->post('liquor_count')
        );

        if ($this->input->post('page_mode') == 'shopping_cart') {
            $response = $this->cart_model->checkOut(json_encode($cart_details));
            if ($response[0]->V_SWAL_TYPE == 'success') {
                // $this->session->set_userdata('cart_summary_session', array('check_out_cart_id' => $response[0]->V_CART_ID, 'order_summary_type' => CART_SUMMARY)); //CART_SUMMARY from list_field_helper
                $this->session->set_userdata(
                    'order_code_summary_session',
                    array(
                        'order_code' => $response[0]->V_ORDER_CODE,
                        'check_out_cart_id' => $response[0]->V_CART_ID,
                        'order_summary_type' => ORDER_SUMMARY
                    )
                );
            }
        } else {
            $response = $this->order_model->deliveryCheckOut(json_encode($cart_details));
            if ($response[0]->V_SWAL_TYPE == 'success') {

                $this->session->set_userdata(
                    'delivery_cart_summary_session',
                    array(
                        'order_code' => $response[0]->V_ORDER_CODE,
                        'check_out_cart_id' => $response[0]->V_CART_ID,
                        'entity_id' => $response[0]->V_ENTITY_ID,
                        'page_hit' => 'second'
                        // 'order_summary_type' => ORDER_DELIVERY_SUMMARY
                    )
                );
            }
        }
        // echo $response;
        echo json_encode($response);
    }

    //to place order from session
    public function displaySessionOrder()
    {
        if ($this->session->userdata('cart_summary_session')) {
            $order_details = $this->session->userdata('cart_summary_session');
            $cart_id = $order_details['check_out_cart_id'];
            $cart_total_details_type = $order_details['order_summary_type'];
            $this->displayCartSummary($cart_id, $cart_total_details_type);
        } else {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
    }

    //place order to the entity
    public function placeOrder()
    {
        $cart_details = array(
            'user_id' => $this->session->userdata('admin_id'),
            'cart_id' => $this->input->post('cart_id'),
            'mode' => 'order',
        );
        $response = $this->cart_model->placeOrder(json_encode($cart_details));

        if ($response[0]->V_SWAL_TYPE == 'success') {

            $this->session->set_userdata(
                'order_code_summary_session',
                array(
                    'order_code' => $response[0]->V_ORDER_CODE,
                    'check_out_cart_id' => $response[0]->V_CART_ID,
                    'order_summary_type' => ORDER_SUMMARY
                )
            );
        }
        echo json_encode($response);
    }

    //redirected from cart summary
    public function orderCodeDisplay()
    {
        if ($this->session->userdata('order_code_summary_session')) {
            $cart_details = $this->session->userdata('order_code_summary_session');


            $cart_id = $cart_details['check_out_cart_id'];
            $cart_total_display_type = $cart_details['order_summary_type']; // from list_field_helper to load labels according to the display page

            $this->session->unset_userdata('session_cart_details');
            $this->session->unset_userdata('cart_summary_session');
            $this->session->unset_userdata('order_code_summary_session');

            $this->displayCartSummary($cart_id, $cart_total_display_type);
        } else {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
    }

    // after order is placed from cart_page to display code and order details
    public function displayCartSummary($cart_id, $cart_total_details_type) // $cart_total_details_type -from list_field_helper
    {
        $page_labels = unserialize($cart_total_details_type); //to fetch page labels from listfield helper -CART_SUMMARY


        $response = $this->cart_model->fetchCartDetalis($cart_id);

        if (count($response) > 0) {
            $cart_details['cart_type'] = $response[0]['cart_type'];
            $cart_details['cart_id'] = $response[0]['cart_id'];
            $cart_details['canteen_details'] = $response[0]['canteen_details'];
            $cart_details['cart_table_data'] = $response;
        }
        $page_labels['cart_footer_buttons'] = array(
            "fa_form_icon" => $page_labels['fa_form_icon'],
            "button_label_1" => $page_labels['button_label_1'],
            "button_label_2" => $page_labels['button_label_2'],
            "button_id_1" => $page_labels['button_id_1'],
            "button_id_2" => $page_labels['button_id_2'],
            "button_class_1" => $page_labels['button_class_1'],
            "button_class_2" => $page_labels['button_class_2'],
            "fa_button_icon" => $page_labels['fa_button_icon'],
            "cart_footer_button_mode" => $page_labels['cart_footer_button_mode'],
            "cart_id" => $cart_details['cart_id']
        );

        $data = array_merge($page_labels, $cart_details);
        $this->load->view('admin/includes/_header');
        $this->load->view('cart/summary', $data);
        $this->load->view('admin/includes/_footer');
    }


    // to display cart summary 
    public function fetchOrderSummary($cart_id, $cart_total_details_type)
    {
        $data['title'] = trans('cart');
        $data['mode'] = 'A';
        $page_labels = unserialize($cart_total_details_type); //to fetch page labels

        $response = $this->cart_model->fetchCartDetalis($cart_id);

        if (count($response) > 0) {
            $cart_details['cart_type'] = $response[0]['cart_type'];
            $cart_details['cart_id'] = $response[0]['cart_id'];
            $cart_details['cart_table_data'] = $response;
        }
        $data = array_merge($page_labels, $cart_details);
        $this->load->view('admin/includes/_header');
        // $this->load->view('cart/cart_purchase', $data);
        $this->load->view('cart/summary', $data);
        $this->load->view('admin/includes/_footer');
    }
}
