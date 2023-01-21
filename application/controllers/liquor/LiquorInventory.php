<?php defined('BASEPATH') or exit('No direct script access allowed');

class LiquorInventory extends MY_Controller
{

    public function __construct()
    {

        parent::__construct();
        auth_check();
        //auth_check(); // check login auth
        //$this->rbac->check_module_access();
        $this->load->model('Order/Stockist_order_model', 'stockist_order_model');
        $this->load->model('admin/Activity_model', 'activity_model');
        $this->load->helper(array('bsf_form/list_field', 'bsf_form/master_table', 'Ats/common'));
    }

    //--------------------------to fetch consumer liquor--------------------------------------
    public function your_order()
    {
        // $this->rbac->check_operation_access(); // check opration permission

        // $data = $this->stockist_order_model->fetchState('consumer', 'your_order');
        $data = $this->stockist_order_model->fetchCanteen('consumer', 'your_order');

        $keyword = 'ALL';
        $selected_state = $this->input->post('selected_state');
        $selected_city = $this->input->post('selected_city');
        //$result = $this->stockist_order_model->fetchProductsOnSearch($keyword, $selected_state, $selected_city);
        //            echo json_encode($result);
        //            echo $result[1]->product_name;
        $product_cart_BSF = $this->session->userdata('product_cart_BSF');
        if (!is_array($product_cart_BSF)) {
            $product_cart_BSF = array();
        }

        $this->load->view('admin/includes/_header');
        $this->load->view('order/search_product', $data);
        //$this->load->view('master/product_table_view', array('product_data' => $result, 'product_cart_BSF' => $product_cart_BSF));
        $this->load->view('admin/includes/_footer');
    }

    // to fetch entity order
    public function entity_order()
    {
        // $this->rbac->check_operation_access(); // check opration permission
        $data['cart_type'] = 'entity';
        $entity_id = $this->session->userdata('entity_id');
        $delivarable_entity_id = $this->stockist_order_model->fetch_delivarable_entity_id($entity_id); // for onload data show

        if (count(json_decode(json_encode($delivarable_entity_id), true)) > 0) {
            $data['delivarable_entity_id'] = $delivarable_entity_id[0]->delivarable_entity_id;
        } else {
            $data['delivarable_entity_id'] = 1;
        }


        $keyword = 'ALL';

        $selected_state = $this->input->post('selected_state');
        $selected_city = $this->input->post('selected_city');

        $product_cart_BSF = $this->session->userdata('product_cart_BSF');
        if (!is_array($product_cart_BSF)) {
            $product_cart_BSF = array();
        }

        $this->load->view('admin/includes/_header');
        $this->load->view('order/search_product', $data);
        $this->load->view('admin/includes/_footer');
    }



    public function continueCartShoppingSession()
    {
        if ($this->session->userdata('continue_cart_shopping_session')) {
            $cart_details = $this->session->userdata('continue_cart_shopping_session');
            $state_record = $this->stockist_order_model->fetchCanteen('consumer', 'your_order');

            $displayData = array_merge($cart_details, $state_record);
            $displayData['redirection_mode'] = 'existing';
            // echo '<pre>';
            // print_r($displayData);
            // echo '</pre>';
            // die();
            $this->continueShopping($displayData);
        }
    }

    public function continueShopping($data)
    {
        $product_cart_BSF = $this->session->userdata('product_cart_BSF');
        if (!is_array($product_cart_BSF)) {
            $product_cart_BSF = array();
        }

        $this->load->view('admin/includes/_header');
        $this->load->view('order/search_product', $data);
        $this->load->view('admin/includes/_footer');
    }


    public function getCityList()
    {
        $this->rbac->check_operation_access(); // check opration permission
        $state_id = $this->input->post('state_id');
        $result = $this->stockist_order_model->fetchCities($state_id);
        echo json_encode($result);
    }

    public function getProductList()
    {
        $this->rbac->check_operation_access(); // check opration permission
        $keyword = $this->input->post('keyword');
        // $selected_state = $this->input->post('selected_state');
        $selected_city = $this->input->post('selected_city');
        $delivarable_entity_id = $this->input->post('delivarable_entity_id');
        $result['list_product_name'] = $this->stockist_order_model->fetchProductsName($keyword, $delivarable_entity_id, $selected_city);
        $result['list_product_type'] = $this->stockist_order_model->fetchProductsType($keyword, $delivarable_entity_id, $selected_city);
        echo json_encode($result);
    }

    // to display products
    public function displayProducts()
    {
        $this->rbac->check_operation_access(); // check opration permission
        $cart_type = $this->input->post('cart_type');
        $keyword = $this->input->post('keyword');
        // $selected_state = $this->input->post('selected_state');
        $selected_city = $this->input->post('selected_city');
        $delivarable_entity_id = $this->input->post('delivarable_entity_id');
        // $result = $this->stockist_order_model->fetchProductsOnSearch($keyword, $selected_state, $selected_city);
        $result = $this->stockist_order_model->fetchProductsOnSearch($keyword, $delivarable_entity_id, $cart_type);
        $product_cart_BSF = $this->session->userdata('product_cart_BSF');
        if (!is_array($product_cart_BSF)) {
            $product_cart_BSF = array();
        }

        $this->load->view('master/product_table_view', array('product_data' => $result, 'product_cart_BSF' => $product_cart_BSF, 'cart_type' => $cart_type));
        //            $data .= $this->load->view('master/product_display_field', array("field_id" => "product_".$result[$i]->id, "product_id" => $result[$i]->id, "product_name" => $result[$i]->product_name, "cart_path" => "shop.html", "image_path" => $result[$i]->product_image, "user_type" => "consumer", "product_price" => "1230"));



        // $keyword = 'ALL';

        // $this->load->view('admin/includes/_header');
        // $this->load->view('master/product_table_view', array('product_data' => $result, 'cart_type' => $cart_type));
        // $this->load->view('admin/includes/_footer');




    }




    public function addToCart()
    {
        $this->rbac->check_operation_access(); // check opration permission
        $product_id = $this->input->post('product_id');
        $liqour_count = $this->input->post('liquor_count');
        $cart_id = $this->input->post('cart_id');
        $cart_type = $this->input->post('cart_type');
        $product_name = $this->input->post('product_name');
        $mode = $this->input->post('mode');
        $product_price = $this->input->post('product_price');
        $product_quantity = $this->input->post('product_quantity');
        $product_add_remove_type = $this->input->post('add_remove_type'); //0->Add,1->Remove

        $total_cost_size = (int)$product_quantity * (int)$product_price;
        $cart_data = array(
            'action' => 'ADD',
            'liquor_entity_id' => $product_id,
            'mode' => $mode,
            'liquor_count' => $liqour_count,
            'order_from_id' => $this->session->userdata('admin_id'),
            'cart_type' => $cart_type,
            'quantity' => $product_quantity,
            'unit_cost_lot_size' => $product_price,
            'total_cost_bottles' => $total_cost_size,
            'order_by_userid' => $this->session->userdata('admin_id'),
            'cart_id' => $cart_id
        );



        $response = $this->stockist_order_model->createUpdateCartDetails(json_encode($cart_data));
        if (!$this->session->userdata('session_cart_details')) {
            $this->session->set_userdata("session_cart_details", array("cart_id" => $response[0]->V_CART_ID, "cart_type" => $response[0]->V_CART_TYPE));
        } // echo '<pre>';
        // print_r($response);
        // echo '</pre>';
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

    public function liquor_inventory_updation()
    {
        $data = $this->stockist_order_model->fetchInitialAlcoholFormDetails();
        $this->load->view('admin/includes/_header');
        $this->load->view('liquor/liquorInventoryView', $data);
        $this->load->view('admin/includes/_footer');
    }

    public function getLiquorPrvAvlQty()
    {
        // $this->rbac->check_operation_access(); // check opration permission
        $liquor_entity_mapping_id = $this->input->post('liquor_entity_mapping_id');
        $result = $this->stockist_order_model->fetchLiquorPrvAvlQty($liquor_entity_mapping_id);
        echo json_encode($result);
    }

    public function updateQuantity()
    {
        // $this->rbac->check_operation_access(); // check opration permission
        $previous_avl_qty = $this->input->post('previous_avl_qty');
        $current_avl_qty = $this->input->post('current_avl_qty');
        $qty_sum = $previous_avl_qty + $current_avl_qty;
        $liquor_entity_mapping_id = $this->input->post('liquor_entity_mapping_id');
        $result = $this->stockist_order_model->updateCurrAvlQty($qty_sum, $liquor_entity_mapping_id);
        echo json_encode($result);
    }
}
