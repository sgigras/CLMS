<?php


header("Access-Control-Allow-Origin: *");
class SearchLiquorAPI extends CI_Controller
{

    public function __construct()
    {

        parent::__construct();
        //auth_check(); // check login auth
        //$this->rbac->check_module_access();
        $this->load->model('Cart_details/Cart_model', 'cart_model');
        $this->load->model('Order/Stockist_order_model', 'stockist_order_model');
        $this->load->model('Order_details/order_model', 'order_model');
        $this->load->model('admin/Activity_model', 'activity_model');
        $this->load->helper(array('bsf_form/list_field', 'bsf_form/master_table', 'Ats/common'));
    }




    public function fetchDashboardDetails()
    {
        $user_id = $this->input->post('user_id');
        $response = $this->order_model->fetchDashboardDetails($user_id);
        echo json_encode($response);
    }

    public function fetchCanteen()
    {
        $data = $this->stockist_order_model->fetchCanteen('consumer', 'your_order');
        echo json_encode($data);
    }

    // public function

    public function getProductList()
    {
        $keyword = $this->input->post('keyword');
        $delivarable_entity_id = $this->input->post('delivarable_entity_id');
        $selected_city = '';
        $result['list_product_name'] = $this->stockist_order_model->fetchProductsName($keyword, $delivarable_entity_id, $selected_city);
        $result['list_product_type'] = $this->stockist_order_model->fetchProductsType($keyword, $delivarable_entity_id, $selected_city);
        echo json_encode($result);
    }

    // to display products
    public function displayProducts()
    {
        $cart_type = $this->input->post('cart_type');
        $keyword = $this->input->post('keyword');
        $delivarable_entity_id = $this->input->post('delivarable_entity_id');
        $result = $this->stockist_order_model->fetchProductsOnSearch($keyword, $delivarable_entity_id, $cart_type);
        echo json_encode($result);
    }




    public function addToCart()
    {
        // $this->rbac->check_operation_access(); // check opration permission
        $product_id = $this->input->post('product_id');
        $liqour_count = $this->input->post('liquor_count');
        $cart_id = $this->input->post('cart_id');
        $cart_type = $this->input->post('cart_type');
        $mode = $this->input->post('mode');
        $product_price = $this->input->post('product_price');
        $product_quantity = $this->input->post('product_quantity');

        $total_cost_size = (int)$product_quantity * (int)$product_price;
        $cart_data = array(
            'action' => 'ADD',
            'liquor_entity_id' => $product_id,
            'mode' => $mode,
            'liquor_count' => $liqour_count,
            'order_from_id' => $this->input->post('user_id'),
            'cart_type' => $cart_type,
            'quantity' => $product_quantity,
            'unit_cost_lot_size' => $product_price,
            'total_cost_bottles' => $total_cost_size,
            'order_by_userid' => $this->input->post('user_id'),
            'cart_id' => $this->input->post('cart_id'),
        );

        $response = $this->stockist_order_model->createUpdateCartDetails(json_encode($cart_data));
        // if (!$this->session->userdata('session_cart_details')) {
        //     $this->session->set_userdata("session_cart_details", array("cart_id" => $response[0]->V_CART_ID, "cart_type" => $response[0]->V_CART_TYPE));
        // } // echo '<pre>';
        // print_r($response);
        // echo '</pre>';
        echo json_encode($response);
    }


    public function fetchCartDetails()
    {
        $cart_id = $this->input->post('cart_id');

        $response = $this->cart_model->fetchCartDetalis($cart_id);
        echo json_encode($response);
    }

    public function testSession()
    {
        $session_data = $this->session->userdata('product_cart_BSF');
        echo '<pre>';
        print_r($session_data);
        echo '</pre>';
    }

    public function updateQuantityInCartSession()
    {
        $this->rbac->check_operation_access(); // check opration permission
        $product_id = $this->input->post('product_id');
        $product_quantity = $this->input->post('product_quantity');

        $restore_cart_data = array();
        // fetch the stored copy first.
        $product_cart_BSF = $this->session->userdata('product_cart_BSF');
        if (!is_array($product_cart_BSF)) {
            $product_cart_BSF = array();
        } else {
            $value_cart = $product_cart_BSF['CART1'];
            $message = 'Product Not Found In Cart';
            for ($i = 0; $i < count($value_cart); $i++) {

                $restore_product_id = $value_cart[$i]['product_id'];
                $restore_product_name = $value_cart[$i]['product_name'];
                $restore_product_price = $value_cart[$i]['product_price'];
                $restore_product_quantity = $value_cart[$i]['product_quantity'];

                if ($restore_product_id == $product_id) {
                    $restore_cart_data[] = array("product_id" => $restore_product_id, "product_name" => $restore_product_name, "product_price" => $restore_product_price, "product_quantity" => $product_quantity);
                    $message = 'Incremented/Decremented Quantity in CART';
                } else {
                    $restore_cart_data[] = array("product_id" => $restore_product_id, "product_name" => $restore_product_name, "product_price" => $restore_product_price, "product_quantity" => $restore_product_quantity);
                }
            }
        }

        $product_cart_BSF['CART1'] = $restore_cart_data;

        $this->session->set_userdata('product_cart_BSF', $product_cart_BSF);

        $session_data = $this->session->userdata('product_cart_BSF');
        echo json_encode(array("session_data" => $session_data, "message" => $message));
    }

    public function displaySingleProducts()
    {
        $cart_type = $this->input->post('cart_type');
        $keyword = 'ALL';
        $result = $this->stockist_order_model->fetchProductsOnSearch($keyword, '', '', $cart_type);
        // echo '<pre>';

        // print_r($result);
        // echo '</pre>';
        // $product_cart_BSF = $this->session->userdata('product_cart_BSF');
        $this->load->view('admin/includes/_header');
        $this->load->view('master/product_table_view', array('product_data' => $result, 'cart_type' => $cart_type));
        $this->load->view('admin/includes/_footer');
    }

    public function placeOrder()
    {
        // $cart_details = array(
        //     'cart_data' => $this->input->post('cart_data'),
        //     'cart_id' => $this->input->post('cart_id'),
        //     'cart_type' => $this->input->post('cart_type'),
        //     'page_mode' => $this->input->post('page_mode'),
        //     'user_id' => $this->input->post('user_id'),
        //     'liquor_per_bottle' => $this->input->post('liquor_per_bottle'),
        //     'mode' => 'A'
        // );
        // // echo json_encode($cart_details);
        // $response = $this->cart_model->checkOut(json_encode($cart_details));
        // echo $response;

        $text = "Kindly update the application from the given link: https://play.google.com/store/apps/details?id=com.clms.bsf&hl=en  or from playstore to successfully place the order";

        // $data = array("V_SWAL_TITLE" => $text, "V_SWAL_TEXT" => "", "V_SWAL_TYPE" => "error");
        $data = array();
        $details = new stdClass;
        $details->V_SWAL_TITLE = 'Update Application';
        $details->V_SWAL_TEXT = $text;
        $details->V_SWAL_TYPE = 'error';
        $data[] = $details;
        echo json_encode($data);
        // echo json_encode($response);
    }

    public function newPlaceOrder()
    {
        $text = "Kindly update the application from the given link: https://play.google.com/store/apps/details?id=com.clms.bsf&hl=en  or from playstore to successfully place the order";

        // $data = array("V_SWAL_TITLE" => $text, "V_SWAL_TEXT" => "", "V_SWAL_TYPE" => "error");
        $data = array();
        $details = new stdClass;
        $details->V_SWAL_TITLE = 'Update Application';
        $details->V_SWAL_TEXT = $text;
        $details->V_SWAL_TYPE = 'error';
        $data[] = $details;
        echo json_encode($data);
    }

    public function fetchAllOrders()
    {
        $user_id = $this->input->post('user_id');
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
        echo json_encode($order_details_array);
    }
}
