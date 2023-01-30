    <?php
    date_default_timezone_set('Asia/Kolkata');
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
            // session_start();
            // $this->rbac->check_module_access();

            $this->load->model('Order_details/Order_model', 'order_model');
            $this->load->model('Cart_details/Cart_model', 'cart_model');
            $this->load->model('admin/Activity_model', 'activity_model');
            $this->load->model('additional_sheets/AdditionalSheetsModel');
            $this->load->model('newStock/NewAvailable_stock');

            $this->load->helper(array('bsf_form/list_field', 'bsf_form/master_table', 'Ats/common', 'bsf_form/check_input'));
        }


        public function index()
        {
            $data['entity_id'] = $this->session->userdata('entity_id');
            $data["page_hit"] = 'start';
            $data["check_out_cart_id"] = '';
            $data["order_code"] = '';

            // $data = preg_replace('/"/i', '', $data);
            // $data = preg_replace("/'/i", '', $data);
            // $data = preg_replace('/<.+>/sU','',$data);
            // $data = preg_replace('/(?:\{|<|\[)/', '(', $data);
            // print_r($data);
            // die;
            $this->loadOrderCode($data);
        }

        function additionalSale()
        {
            $liquor_data = $this->NewAvailable_stock->getLiquorNames();
            $data['liquor_name_record'] = $liquor_data;
            // print_r($liquor_data);
            $this->load->view('admin/includes/_header');
            $this->load->view('additional_sheets/AdditionalSheetsView', $data);
            $this->load->view('admin/includes/_footer');
        }
        

        public function loadOrderCode($initial_data)
        {
            $entity_id = $this->session->userdata('entity_id');
            $response = $this->order_model->fetchDeliveryOrders($entity_id);
            $page_labels = unserialize(ORDER_DELIVERY_SUMMARY);
            $data = array_merge($page_labels, $response);
            $data = array_merge($data, $initial_data);

            $this->load->view('admin/includes/_header');
            $this->load->view('order/order_delivery_summary', $data);
            $this->load->view('admin/includes/_footer');
        }

        public function fetchOrderDetails()
        {
            $order_code = $this->input->post('order_code');
            $order_code = checkIMP($order_code);

            $page_labels = unserialize(ORDER_DELIVERY_SUMMARY); //to fetch page labels

            $response = $this->order_model->fetchOrderCartDetails($order_code);
            $cart_id = 0;
            $cart_details = array();
            if (count($response) > 0) {
                $cart_details['cart_type'] = $response[0]['cart_type'];
                $cart_details['cart_id'] = $response[0]['cart_id'];
                $cart_details['cart_table_data'] = $response;
                $cart_details['irla'] = $response[0]['irla'];
                $cart_details['name'] = $response[0]['name'];
                $cart_details['entity_details'] = $response[0]['entity_details'];
                $cart_details['entity_type'] = $response[0]['entity_type'];
                // $cart_details['name'] = $response[0]['name'];
                $cart_id = $response[0]['cart_id'];
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
                    "cart_id" => $cart_id
                );
                $data = array_merge($page_labels, $cart_details);
                // echo '<pre>';
                // print_r($data);
                // echo '</pre>';
                // die();
                $this->load->view('cart/cart_collapsible', $data);
            } else {
                echo "<h4 class='text-danger'>No liquors found for the given code: $order_code <h4>";
            }
        }





        public function modifyCartDetails()
        {
            // $cart_id  = $this->input->post('cart_id');
            $order_code  = $this->input->post('order_code');
            $this->session->set_userdata('session_order_cart_details', array('order_code' => $order_code)); //creation of session for order management
        }


        public function completeDeliveryProcess()
        {
            // $cart_id = $this->input->post('cart_id');
            $order_code = $this->input->post('order_code');
            $user_id = $this->session->userdata('admin_id');
            // $order_code = $this->input->post('cart_type');
            // $this->input->post('page_mode');
            $response = $this->order_model->completeDeliveryProcess($order_code, $user_id);
            $this->session->unset_userdata('session_order_cart_details');
            $this->session->unset_userdata('delivery_cart_summary_session');
            $this->session->set_userdata('print_reciept', $order_code);
            echo json_encode($response);

            // echo $cart_id;
        }



        //displays fetch cart details and display to the user
        public function fetchCartDetails()
        {

            if ($this->session->userdata('session_order_cart_details')) {
                $cart_details = $this->session->userdata('session_order_cart_details');
                // $cart_id = $cart_details['cart_id'];

                $order_code = $cart_details['order_code'];
                // $data['title'] = trans('cart');
                // $data['mode'] = 'A';

                $response = $this->order_model->fetchOrderCartDetails($order_code);
                if (count($response) > 0) {
                    $data['cart_type'] = $response[0]['cart_type'];
                    $data['cart_id'] = $response[0]['cart_id'];
                    $data['delivarable_entity_id'] = $response[0]['ordered_to_entity_id'];
                    $data['cart_table_data'] = $response;
                } else {
                    $data['cart_type'] = 'consumer';
                    $data['cart_id'] = '';
                    $data['cart_table_data'] = '';
                }

                $data = array_merge($data, unserialize(DELIVERY_CART));
                $this->load->view('admin/includes/_header');
                $this->load->view('cart/cart', $data);
                $this->load->view('admin/includes/_footer');
            } else {
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            }
        }

        public function displaySessionDeliveryOrder()
        {
            // echo '<pre>';

            // echo '</pre>';
            if ($this->session->userdata('delivery_cart_summary_session')) {
                $order_details = $this->session->userdata('delivery_cart_summary_session');
                // $cart_id = $order_details['check_out_cart_id'];
                // $cart_total_details_type = $order_details['order_summary_type'];
                //    $page_hit=$order_details['page_hit'];
                $this->loadOrderCode($order_details);




                // $this->displayDeliveryCartSummary($cart_id, $cart_total_details_type);
            } else {
                // echo 'false';
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            }
        }
        public function printReceipt()
        {
            if (isset($_SESSION["print_reciept"])) {
                $cart_order_code = $_SESSION["print_reciept"];
                $data['brewerysummary'] = $this->order_model->fetchPrintReceipt($cart_order_code);
                $this->load->view('admin/includes/_header');
                $this->load->view('order/invoice', $data);
            } else {
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            }
        }
    }
