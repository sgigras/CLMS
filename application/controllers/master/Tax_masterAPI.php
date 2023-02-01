    <?php
/**
Author: Ujwal Jain
Created on:27-07-2022
Scope: Tax management API
Force: ITBP
 **/
class Tax_masterAPI extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        auth_check(); // check login auth
        $this->load->model('Master/Master_model', 'master_model');
        $this->load->model('admin/Activity_model', 'activity_model');
        $this->load->model('admin/Tax_model', 'tax_model');
        $this->load->helper(array('bsf_form/list_field', 'bsf_form/master_table', 'Ats/common','bsf_form/check_input'));
    }
    //function to load view page --------------
    public function index()
    {
        $tax_data = $this->master_model->fetchTaxnameList();
        $data['title'] = trans('tax_master');
        $data['add_url'] = 'master/Tax_masterAPI/addTaxNames';
        $data['add_url_type'] = 'master/Tax_masterAPI/addTaxCategory';
        $data['add_title'] = trans('add_tax_name');
        $data['table_head'] = TAX_MASTER;
        $data['table_data'] = $tax_data;
        $data['edit_url'] = 'master/Tax_masterAPI/editTaxNames';
        $data['csrf_url'] = 'master/CanteenMaster';
        // echo '<pre>';
        // print_r($data);
        // die;
        $this->load->view('admin/includes/_header');
        $this->load->view('master/masterTableView', $data);
        $this->load->view('admin/includes/_footer');
    }
    //function to add Tax details---------
    public function addTaxNames()
    {
        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('tax_name', 'Tax name', 'trim|required');
            // $this->form_validation->set_rules('tax_category', 'Tax category', 'trim|required');
            $this->form_validation->set_rules('entity_type', 'Entity Type', 'trim|required');
            $data = array(
                'tax_name' => checkIMP($this->input->post('tax_name')),
                'tax_category_id' => $this->input->post('tax_category'),
                'entity_type' => $this->input->post('entity_type'),
                'created_by' => $this->session->userdata('admin_id'),
            );
            if ($this->form_validation->run() == FALSE) {
                $data = array(
                    'errors' => validation_errors()
                );
                $this->session->set_flashdata('errors', $data['errors']);
                redirect(base_url('master/Tax_masterAPI/addTaxNames'), 'refresh');
            }
            $result = $this->master_model->insert_tax_name($data);
            if ($result) {
                $this->session->set_flashdata('form_data', 'Tax name added successfully');
                redirect(base_url('master/Tax_masterAPI/index'), 'refresh');
            }
        } else {
            $data['title'] = trans('add_tax_name');
            $data['mode'] = 'A';
            $data['outlet_type_select_option_array']= $this->tax_model->fetchEntities();
            $data['outlet_type_select_option_array1']= $this->tax_model->fetchTaxCategories();
            $this->load->view('admin/includes/_header');
            $this->load->view('master_forms/taxNameview', $data);
            $this->load->view('admin/includes/_footer');
        }
    }
    // Function add tax category-----
    public function addTaxCategory()
    {
        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('tax_category', 'Tax category', 'trim|required');
            // $this->form_validation->set_rules('entity_type', 'Entity Type', 'trim|required');
            $data = array(
                'tax_category' => checkIMP($this->input->post('tax_category')),
                'created_by' => $this->session->userdata('admin_id'),
                'created_at'=> date('y=m=d h:i;s')
            );
            if ($this->form_validation->run() == FALSE) {
                $data = array(
                    'errors' => validation_errors()
                );
                $this->session->set_flashdata('errors', $data['errors']);
                redirect(base_url('master/Tax_masterAPI/addTaxCategory'), 'refresh');
            }
            $result = $this->master_model->insert_tax_category($data);
            if ($result) {
                $this->session->set_flashdata('form_data', 'Tax category added successfully.');
                redirect(base_url('master/Tax_masterAPI/addTaxCategory'), 'refresh');
            } else{
                $this->session->set_flashdata('form_data1', 'Tax category already exist.');
                redirect(base_url('master/Tax_masterAPI/addTaxCategory'), 'refresh');
            }
        } else {
            $data['title'] = trans('add_tax_category');
            $data['mode'] = 'A';
            $this->load->view('admin/includes/_header');
            $this->load->view('master_forms/taxCategoryview', $data);
            $this->load->view('admin/includes/_footer');
        }
    }
    //function to edit  Tax details---------
    public function editTaxNames($id)
    {
        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('tax_name', 'Tax name', 'trim|required');
            $data = array(
                'tax_name' => checkIMP($this->input->post('tax_name')),
                'entity_type' => $this->input->post('entity_type'),
                'tax_category_id' => $this->input->post('tax_category'),
                'created_by' => $this->session->userdata('admin_id'),
                'id' => $id,
            );
            if ($this->form_validation->run() == FALSE) {
                $data = array(
                    'errors' => validation_errors()
                );
                $this->session->set_flashdata('errors', $data['errors']);
                redirect(base_url('master/Tax_masterAPI/editTaxNames'), 'refresh');
            }
            $result = $this->master_model->update_tax_name($data);
            if ($result) {
                $this->session->set_flashdata('form_data', 'Tax name Updated successfully');
                redirect(base_url('master/Tax_masterAPI/index'), 'refresh');
            }
        } else {
            $data['title'] = trans('edit_tax_name');
            $data['outlet_type_select_option_array']= $this->tax_model->fetchEntities();
            $data['outlet_type_select_option_array1'] = $this->tax_model->fetchTaxCategories();
            $details = $this->master_model->fetchTaxDetails($id);
            $data['tax_name'] = $details[0];            
            $data['mode'] = 'E';
            $this->load->view('admin/includes/_header');
            $this->load->view('master_forms/taxNameview', $data);
            $this->load->view('admin/includes/_footer');
        }
    }
    function tax_Mapping()
    {
        $this->rbac->check_operation_access(); // check opration permission
        if ($this->input->post('submit')) {
            $data = array('success' => false, 'messages' => array());
            $this->form_validation->set_rules('breweryname', 'Brewery Name', 'trim|required|min_length[3]|max_length[80]');
            $this->form_validation->set_rules('breweryaddress', 'Brewery Address', 'trim|required|min_length[3]|max_length[250]');
            $this->form_validation->set_rules('contactperson', 'Contact Person', 'trim|required|min_length[3]|max_length[100]');
            $this->form_validation->set_rules('mobilenumber', 'Mobile Number', 'trim|required|min_length[10]|max_length[10]');
            $this->form_validation->set_rules('emailaddress', 'Email Address', 'trim|required|valid_email|min_length[3]|max_length[580]');
            $this->form_validation->set_rules('brewerystate[]', 'Brewery States', 'trim|required|xss_clean|callback_multiple_selectstate');
            // $this->form_validation->set_rules('brewerystate', 'Brewery States', 'required',array('required' =>'Select At Least One State'));
            // $this->form_validation->set_rules('breweryentity', 'Brewery Entity', 'required',array('required' =>'Select At Least One Entity'));
            $this->form_validation->set_rules('breweryentity[]', 'Brewery Entity', 'trim|required|xss_clean|callback_multiple_selectentity');
            $this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');
            if ($this->form_validation->run()) {
                $data['success'] = true;
                // $this->brewery_model->insert();
                $this->session->set_flashdata('success', 'Brewery Added Successfully');
                redirect('admin/brewery/Brewery/add');
                // die('Save');
            } else {
                foreach ($_POST as $key => $value) {
                    print_r($_POST);
                    die();
                    $data['messages'][$key] = form_error($key);
                }
            }
            echo json_encode($data);
            return;
        }
        $data['title'] = trans('taxmapping');
        $data['taxlist'] = $this->tax_model->getTaxes();
        $data['liquorlist'] = $this->tax_model->getLiquor_Brands();
        $this->load->view('admin/includes/_header');
        $this->load->view('admin/tax/tax_mapping_view', $data);
        $this->load->view('admin/includes/_footer');
    }
}
