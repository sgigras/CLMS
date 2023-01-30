<?php
class Liquor_mapping extends MY_Controller
{
    //put your code here
    public function __construct()
    {
        parent::__construct();
        auth_check(); // check login auth
        $this->load->model('Master/LiquorMapping_Model', 'liquor_mapping_master_model');
        $this->load->model('admin/Activity_model', 'activity_model');
        $this->load->helper(array('bsf_form/list_field', 'bsf_form/master_table', 'Ats/common'));
    }
    public function index()
    {
        // $user_id = $this->session->userdata('admin_id');
        $data = $this->liquor_mapping_master_model->fetchInitialAlcoholFormDetails();
        $this->load->view('admin/includes/_header');
        $this->load->view('liquor/liquorMappingView', $data);
        $this->load->view('admin/includes/_footer');
    }
    public function getLiquorBrandList()
    {
        $liquor_brand_id = $this->input->post('liquor_brand_id');
        $result = $this->liquor_mapping_master_model->fetchLiquorBrand($liquor_brand_id);
        echo json_encode($result);
    }
    public function getLiquorTaxForPurchasePrice()
    {
        $liquor_description_id = $this->input->post('liquor_description_id');
        $entity_id = $this->session->userdata('entity_id');
        $result = $this->liquor_mapping_master_model->getLiquorTaxForPurchasePrice($liquor_description_id,$entity_id);
        echo json_encode($result);
    }
    public function getLiquorTaxForSellingPrice()
    {
        $liquor_description_id = $this->input->post('liquor_description_id');
        $entity_type = $this->input->post('entity_type');
        $tax_type_id = $this->input->post('tax_type_id');
        $entity_id = $this->session->userdata('entity_id');
        $result = $this->liquor_mapping_master_model->getLiquorTaxForSellingPrice($liquor_description_id,$entity_id,$tax_type_id,$entity_type);
        echo json_encode($result);
    }
    public function getEntityList()
    {
        $entity_name_id = $this->input->post('entity_name_id');
        $result = $this->liquor_mapping_master_model->fetchEntityList($entity_name_id);
        echo json_encode($result);
    }
    public function addLiquorMappingDetails()
    {
        if ($this->input->post('submit')) {
            $data = array(
                'liquor_type' => $this->input->post('liquor_type'),
                'liquor_brand' => $this->input->post('liquor_brand'),
                'entity_type' => $this->input->post('entity_type'),
                'entity' => $this->input->post('entity'),
                'select_ml' => $this->input->post('select_ml'),
                'moq' => $this->input->post('moq'),
                'base_price'=> $this->input->post('base_price'),
                'sell_price' => $this->input->post('hid_sell_price'),
                'purchase_price' => $this->input->post('purchase_price'),
                'user_id' => $this->session->userdata('admin_id'),
                'available_quantity' => $this->input->post('available_quantity'),
                'reorder_level' => $this->input->post('reorder_level'),
                'mode' => 'A',
            );
            $response = $this->CheckEntityMasterForm();
            if ($response['success']) { 
                $response['model_response'] = $this->insert_update_liquor_mapping_details($data);
            }
            echo json_encode($response);
        } else {
            $data = $this->liquormapping_model->fetchInitialAlcoholFormDetails();
            $this->load->view('admin/includes/_header');
            $this->load->view('liquor/liquorMappingView', $data);
            $this->load->view('admin/includes/_footer');
        }
    }
    function fetchLiquorMappingDetails() //new added
    {
        $page_mode = $this->input->post('page_mode');
        $id = $this->input->post('id');
        $response = $this->liquor_mapping_master_model->fetchAlcoholDetails($id);
        $response['page_mode'] = $page_mode;
        $this->load->view('liquor/editLiquorMappingDetails', $response);
    }
    public function editLiquorMappingDetails()
    {
        $data = array(
            'mode' => 'U',
            'available_quantity' => $this->input->post('available_quantity'),
            'reorder_level' => $this->input->post('reorder_level'),
            'id' => $this->input->post('id'),
            'user_id' => $this->session->userdata('admin_id')
        );
        $response = $this->liquor_mapping_master_model->insert_update_liquor_mapping_details(json_encode($data));
        echo json_encode($response);
    }
    public function insert_update_liquor_mapping_details($data)
    {
        $response = $this->liquor_mapping_master_model->insert_update_liquor_mapping_details(json_encode($data));
        if ($response[0]->V_SWAL_TYPE == 'success') {
            $this->session->set_userdata('action_messages', $response[0]->V_SWAL_TITLE);
            $this->session->set_userdata('action_messages', $response[0]->V_SWAL_MESSAGE);
            $this->session->set_userdata('swal_icon', $response[0]->V_SWAL_TYPE);
        }
        return $response;
    }
    public function CheckEntityMasterForm()
    {
        $this->form_validation->set_rules('liquor_type', 'Liquor type', 'trim|required');
        $this->form_validation->set_rules('liquor_brand', 'Liquor brand', 'trim|required');
        $this->form_validation->set_rules('entity_type', 'Entity type', 'trim|required');
        $this->form_validation->set_rules('entity', 'Entity', 'trim|required');
        $this->form_validation->set_rules('moq', 'MOQ', 'trim|required');
        $this->form_validation->set_rules('hid_sell_price', 'Sell Price', 'trim|required');
        $this->form_validation->set_rules('purchase_price', 'Purchase Price', 'trim|required');
        $this->form_validation->set_error_delimiters('<p class="text-danger" style="font-size:14px">', '</p>');
        if ($this->form_validation->run()) {
            $data['success'] = true;
        } else {
            $data['success'] = false;
            foreach ($_POST as $key => $value) {
                $data['messages'][$key] = form_error($key);
            }
        }
        return $data;
    }
    public function addStock()
    {
        $data = array(
            'mode' => 'U',
            'new_stock' => $this->input->post('new_stock'),
            'total_stock' => $this->input->post('physical_quantity'),
            'id' => $this->input->post('id'),
            'user_id' => $this->session->userdata('admin_id')
        );
        $response = $this->liquor_mapping_master_model->addStock(json_encode($data));
        echo json_encode($response);
    }
}
