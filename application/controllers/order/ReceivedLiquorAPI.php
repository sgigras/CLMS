<?php

class ReceivedLiquorAPI extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Order/ReceivedLiquorModel', 'received_model');
        auth_check(); // check login auth
        // $this->rbac->check_module_access();
    }


    public function index()
    {
        $data['title'] = 'received_liquor';
        $this->load->view('admin/includes/_header');
        $this->load->view('order/received_liquor', $data);
        $this->load->view('admin/includes/_footer');
    }

    public function fetchReceivedLiquor()
    {
        $order_code = $this->input->post('order_code');
        $entity_id = $this->session->userdata('entity_id');

        $result = $this->received_model->fetchReceivedLiquor($order_code, $entity_id);
        // if()
        // print_r($result);
        if (count($result) > 0) {
            if ($result[0]["is_order_delivered"] == '0') {
                echo '<h6 style="color:red"> Order is not dispatched Yet</h6>';
            } else if ($result[0]["is_order_received"] == '1') {
                echo '<h6 style="color:red"> Delivery has already been received </h6>';
            } else {
                $this->load->view('order/receive_liquor_table', array('cart_data' => $result));
            }
        } else {
            echo '<h6 style="color:red"> No liquor found for the given order code </h6>';
        }
    }

    public function received_liquor()
    {
        $order_data = array(
            'received_liquor_details' => $this->input->post('received_liquor'),
            'user_id' => $this->session->userdata('admin_id'),
            'order_code' => $this->input->post('order_code'),
            'damage_quantity_flag' => $this->input->post('damage_quantity_flag'),
            'entity_id' => $this->session->userdata('entity_id')
        );

        $response = $this->received_model->received_liquor(json_encode($order_data));
        // print_r($response);


        echo json_encode($response);
    }
}
