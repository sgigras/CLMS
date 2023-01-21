<?php

/**
Author: SUJIT N. MISHRA
Created on:23/10/2021
Scope: Alcohol master API
Source:
 **/



class Alcohol_masterAPI extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        auth_check();
        $this->load->model('Master/Master_model', 'master_model');
        $this->load->model('Master/LiquorMaster_Model', 'liquor_master_model');
        $this->load->model('admin/Activity_model', 'activity_model');
        $this->load->helper(array('bsf_form/list_field', 'bsf_form/master_table', 'Ats/common', 'bsf_form/check_input'));
    }

    //function to load view page --------------

    public function index()
    {
        $liquor_data = $this->master_model->fetchLiquorlist();

        $data['title'] = trans('alcohol_master');

        $data['add_url'] = 'master/Alcohol_masterAPI/addalcholType';

        $data['add_title'] = trans('add_alcohol_type');

        $data['table_head'] = ALCOHOL_MASTER;

        $data['table_data'] = $liquor_data;

        $data['edit_url'] = 'master/Alcohol_masterAPI/editalcoholNames';

        $data['csrf_url'] = 'master/CanteenMaster';


        $this->load->view('admin/includes/_header');
        $this->load->view('master/masterTableView', $data);

        $this->load->view('admin/includes/_footer');
    }

    //function to add liquor details---------


    public function addalcholType()
    {
        $this->rbac->check_operation_access(); // check opration permission

        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('alcohol_name', 'Alcohol name', 'trim|required');

            $alcohol_type = $this->input->post('alcohol_name');
            $alcohol_type = checkIMP($alcohol_type);

            $data = array(
                'liquor_type' => $alcohol_type,

                'created_by' => $this->session->userdata('admin_id'),

            );
            if ($this->form_validation->run() == FALSE) {
                $data = array(
                    'errors' => validation_errors()
                );

                $this->session->set_flashdata('errors', $data['errors']);
                redirect(base_url('master/Alcohol_masterAPI/addalcholType'), 'refresh');
            }


            $result = $this->master_model->insert_alcohol_name($data);
            if ($result) {
                $this->session->set_flashdata('form_data', 'Alcohol name added successfully');
                redirect(base_url('master/Alcohol_masterAPI/index'));
            }
        } else {
            $data['title'] = trans('add_alcohol_name');
            $data['mode'] = 'A';
            $this->load->view('admin/includes/_header');
            $this->load->view('master_forms/alcoholMasterview', $data);
            $this->load->view('admin/includes/_footer');
        }
    }


    //function to edit liquor details---------

    public function editalcoholNames($id)
    {
        $this->rbac->check_operation_access(); // check opration permission


        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('alcohol_name', 'Alcohol name', 'trim|required');
            $data = array(
                'alcohol_type' => checkIMP($this->input->post('alcohol_name')),
                'created_by' => $this->session->userdata('admin_id'),
                'id' => $id,
            );
            if ($this->form_validation->run() == FALSE) {
                $data = array(
                    'errors' => validation_errors()
                );

                $this->session->set_flashdata('errors', $data['errors']);
                redirect(base_url('master/Alcohol_masterAPI/editalcoholNames/' . getValue('id', $resultArray)), 'refresh');
            }

            $result = $this->master_model->update_liquor_name($data);
            if ($result) {
                $this->session->set_flashdata('form_data', 'Alcohol name Updated successfully');
                redirect(base_url('master/Alcohol_masterAPI/index'), 'refresh');
            }
        } else {
            $data['title'] = trans('edit_alcohol_name');
            $details = $this->master_model->fetchAlcoholDetails($id);
            $data['alcohol_type'] = $details[0];
            $data['mode'] = 'E';
            $this->load->view('admin/includes/_header');
            $this->load->view('master_forms/alcoholMasterview', $data);
            $this->load->view('admin/includes/_footer');
        }
    }


    //LIQUOR MM MASTERAPI FUNCTIONS ADDED
    public function liquorcapacitymaster()
    {

        $tax_data = $this->master_model->fetchAlcoholQuantityList();

        $data['title'] = trans('alcohol_mm_master');

        $data['add_url'] = 'master/Alcohol_masterAPI/addAlcoholQuantity';

        $data['add_title'] = trans('add_alcohol_quantity');

        $data['table_head'] = ALCOHOL_MM_MASTER;

        $data['table_data'] = $tax_data;

        $data['edit_url'] = 'master/Alcohol_masterAPI/addAlcoholQuantity';


        // $data['edit_url'] = 'master/Tax_masterAPI/editTaxNames';

        // $data['edit_url'] = 'master/Alcohol_masterAPI/liquorcapacitymaster';

        $data['csrf_url'] = 'master/CanteenMaster';

        $this->load->view('admin/includes/_header');
        $this->load->view('master/masterTableView', $data);
        $this->load->view('admin/includes/_footer');
    }


    //function to add liquor quantity---------

    public function addAlcoholQuantity()
    {

        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('alcohol_quantity', 'Liquor quantity', 'trim|required');


            $data = array(
                'liquor_ml' => checkIMP($this->input->post('alcohol_quantity')),
                'created_by' => $this->session->userdata('admin_id'),

            );

            if ($this->form_validation->run() == FALSE) {
                $data = array(
                    'errors' => validation_errors()
                );

                $this->session->set_flashdata('errors', $data['errors']);
                redirect(base_url('master/Alcohol_masterAPI/addAlcoholQuantity'), 'refresh');
            }

            $result = $this->master_model->insertAlcoholQuantity($data);
            if ($result) {
                $this->session->set_flashdata('form_data', 'Alcohol quantity added successfully');
                redirect(base_url('master/Alcohol_masterAPI/liquorcapacitymaster'), 'refresh');
            }
        } else {
            $data['title'] = trans('add_alcohol_quantity');
            $data['mode'] = 'A';
            $this->load->view('admin/includes/_header');
            $this->load->view('master_forms/alcoholMMview', $data);
            $this->load->view('admin/includes/_footer');
        }
    }

    //LIQUOR MM MASERAPI FUNCTION END


    //LIQUOR MASTER CONTROLLER FUNCTIONS ADDED
    public function liquorspecificdetails()
    {
        $liquor_data = $this->liquor_master_model->fetchAllLiquorRecords();

        $liquor_data_array = json_decode(json_encode($liquor_data), true);

        if (count($liquor_data_array) > 0) {
            $data['title'] = trans('liquor_list'); // header of the page

            $data['add_url'] = 'master/Alcohol_masterAPI/addLiquorDetails'; //url for adding new product on form submission

            $data['add_title'] = trans('liquor_add'); //add button titl on list page

            $data['table_head'] = LIQUOR_MASTER_LIST; //from application/helpers/bsf_form list_field_helper //use to create table 

            $data['table_data'] = $liquor_data;

            $data['edit_url'] = 'master/Alcohol_masterAPI/editLiquorDetails';

            $data['csrf_url'] = 'master/Alcohol_masterAPI/liquorspecificdetails';

            $this->load->view('admin/includes/_header');
            $this->load->view('master/masterTableView', $data);
            $this->load->view('admin/includes/_footer');
        } else {
            $data['title'] = "Add Liquor Details";
            $data = $this->liquor_master_model->fetchInitialAlcoholFormDetails();
            $this->load->view('admin/includes/_header');
            $this->load->view('liquor/liquorDetailView', $data);
            $this->load->view('admin/includes/_footer');
        }
    }

    public function addLiquorDetails()
    {
        // $this->rbac->check_operation_access(); // check opration permission
        if ($this->input->post('submit')) {
            $files = $_FILES;
            $upload_path = './liquor_image/';
            $year = date("Y");
            $month = date("m");
            $day = date("d");
            $date_folder = $year . $month . $day;
            $upload_path .= $date_folder . '/';

            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, true);
                chmod($upload_path, 0775);
            }

            $config['upload_path']          = $upload_path;
            $config['allowed_types']        = 'jpg|png|jpeg';
            $this->load->library('upload', $config);
            // echo  $this->input->post('liquor_image_h'); die();
            if (!$this->upload->do_upload('liquor_image')) {

                $data = array('errors' => $this->upload->display_errors());
                $this->session->set_flashdata('form_data', $_POST);
                $this->session->set_flashdata('errors', $data['errors']);
                redirect(base_url('master/Alcohol_masterAPI/addLiquorDetails'), 'refresh');
            } else {
                $upload_array = array('upload_data' => $this->upload->data());

                $image_path = $upload_path . $upload_array['upload_data']['file_name'];
                //   echo "<pre>";
                //   print_r($image_path);  echo "<pre>"; 
                //   $data = array(
                //         //'liquor_image' => $this->input->post('liquor_image_h'),
                //     'liquor_image' => $image_path,
                //     'liquor_name' => $this->input->post('liquor_name'),
                //     'liquor_type_id' => $this->input->post('liquor_type'),
                //     'liquor_brand_id' => $this->input->post('liquor_brand'),
                //     'liquor_description' => $this->input->post('liquor_description'),
                //     'liquor_bottle_size_id' => $this->input->post('bottle_size'),
                //     'liquor_ml_id' => $this->input->post('bottle_vol'),
                //     'user_id' => $this->session->userdata('admin_id'),
                //     'mode' => 'A',
                // );
                $data = array(
                    //'liquor_image' => $this->input->post('liquor_image_h'),
                    'liquor_image' => $image_path,
                    // 'liquor_name' => $this->input->post('liquor_name'),
                    'liquor_type_id' => $this->input->post('liquor_type'),
                    'liquor_brand_id' => $this->input->post('liquor_brand'),
                    'liquor_description' => $this->input->post('liquor_description'),
                    'liquor_bottle_size_id' => $this->input->post('bottle_size'),
                    'liquor_ml_id' => $this->input->post('bottle_vol'),
                    'user_id' => $this->session->userdata('admin_id'),
                    'mode' => 'A',
                );
            }
                // print_r($data);die();
            $result = $this->liquor_master_model->insert_update_liquor_details(json_encode($data));
            if ($result) {

                $this->session->set_flashdata('success', 'Liquor Details added successfully');
                redirect(base_url('master/Alcohol_masterAPI/liquorspecificdetails'), 'refresh');
            }
            //  echo "<pre>"; echo $data; echo '<pre>';

            //$response = $this->CheckEntityMasterForm();
            //   if ($response['success']) {
            //     $response['model_response'] = $this->insert_update_liquor_details($data);
            // }
            //echo json_encode($response);
        } else {

            $data = $this->liquor_master_model->fetchInitialAlcoholFormDetails();
            $this->load->view('admin/includes/_header');
            $this->load->view('liquor/liquorDetailView', $data);
            $this->load->view('admin/includes/_footer');
        }
    }


    public function editLiquorDetails($id)
    {
        // $this->rbac->check_operation_access(); // check opration permission
        if ($this->input->post('submit')) {

            $upload_path = './liquor_image/';
            $year = date("Y");
            $month = date("m");
            $day = date("d");
            $date_folder = $year . $month . $day;
            $upload_path .= $date_folder . '/';
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, true);
                chmod($upload_path, 0775);
            }
            $config['upload_path']          = $upload_path;
            $config['allowed_types']        = 'jpg|png|jpeg';
            $this->load->library('upload', $config);
            // echo  $this->input->post('liquor_image_h'); die();
            if (!$this->upload->do_upload('liquor_image')) {
                $data = array('errors' => $this->upload->display_errors());
                $this->session->set_flashdata('form_data', $_POST);
                $this->session->set_flashdata('errors', $data['errors']);
                redirect(base_url('master/Alcohol_masterAPI/addLiquorDetails'), 'refresh');
            } else {
                $upload_array = array('upload_data' => $this->upload->data());

                $image_path = $upload_path . $upload_array['upload_data']['file_name'];

                $data = array(
                    //'liquor_image' => $this->input->post('liquor_image_h'),
                    'id' => $id,
                    'liquor_image' => $image_path,  
                    // 'liquor_name' => $this->input->post('liquor_name'),
                    'liquor_type_id' => $this->input->post('liquor_type'),
                    'liquor_brand_id' => $this->input->post('liquor_brand'),
                    'liquor_description' => $this->input->post('liquor_description'),
                    'liquor_bottle_size_id' => $this->input->post('bottle_size'),
                    'liquor_ml_id' => $this->input->post('bottle_vol'),
                    'user_id' => $this->session->userdata('admin_id'),
                    'mode' => 'E',
                );
            }
            $response = $this->CheckEntityMasterForm();
            // print_r($data);die();
            if ($response['success']) {
                $response['model_response'] = $this->insert_update_liquor_details($data);
            }
            $this->session->set_flashdata('success', 'Liquor Details Update successfully');
            redirect(base_url('master/Alcohol_masterAPI/liquorspecificdetails'));

            // print_r($data);die();


            // echo json_encode($response);
        } else {

            $data = $this->liquor_master_model->fetchAlcoholDetails($id);
            $this->load->view('admin/includes/_header');
            $this->load->view('liquor/liquorDetailView', $data);
            $this->load->view('admin/includes/_footer');
        }
    }

    public function insert_update_liquor_details($data)
    {
        $response = $this->liquor_master_model->insert_update_liquor_details(json_encode($data));

        if ($response[0]->V_SWAL_TYPE == 'success') {
            $this->session->set_userdata('action_messages', $response[0]->V_SWAL_TITLE);
            $this->session->set_userdata('action_messages', $response[0]->V_SWAL_MESSAGE);
            $this->session->set_userdata('swal_icon', $response[0]->V_SWAL_TYPE);
        }
        return $response;
    }

    public function CheckEntityMasterForm()
    {
        $this->form_validation->set_rules('liquor_image_h', 'Liquor image', 'trim|required');
        $this->form_validation->set_rules('liquor_description', 'Liquor Description', 'trim|required');
        $this->form_validation->set_rules('liquor_type', 'Liquor type', 'trim|required');

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

    //mapping liquor with city and entity  with 
    public function viewProductEnityMapping()
    {
        $liquor_data = $this->liquor_master_model->fetchAllLiquorRecords();

        $liquor_data_array = json_decode(json_encode($liquor_data), true);

        if (count($liquor_data_array) > 0) {
            $data['title'] = trans('liquor_details'); // header of the page

            $data['add_url'] = 'master/Alcohol_masterAPI/addLiquorEntityMapping'; //url for adding new product on form submission

            $data['add_title'] = trans('liquor_add'); //add button titl on list page

            $data['table_head'] = LIQUOR_MASTER_LIST; //from application/helpers/bsf_form list_field_helper //use to create table 

            $data['table_data'] = $liquor_data;

            $data['edit_url'] = 'master/Alcohol_masterAPI/editLiquorEntityMapping';

            $data['csrf_url'] = 'master/Alcohol_masterAPI/liquorspecificdetails';

            $this->load->view('admin/includes/_header');
            $this->load->view('master/masterTableView', $data);
            $this->load->view('admin/includes/_footer');
        } else {

            $data = $this->liquor_master_model->fetchInitialAlcoholFormDetails();
            $this->load->view('admin/includes/_header');
            $this->load->view('liquor/liquorDetailView', $data);
            $this->load->view('admin/includes/_footer');
        }
    }
    //LIQUOR MASTER FUNCTIONS END

    // LIQUOR BRAND MASTER FUNCTION START
    public function liquorBrandDetails()
    {
        $liquor_data = $this->master_model->fetchLiquorBrandList();

        $data['title'] = trans('liquor_brand');

        $data['add_url'] = 'master/Alcohol_masterAPI/addLiquorBrand';

        $data['add_title'] = trans('liquor_brand');

        $data['table_head'] = LIQUOR_BRAND_MASTER;

        $data['table_data'] = $liquor_data;

        $data['edit_url'] = 'master/Alcohol_masterAPI/editLiquorBrand';

        $data['csrf_url'] = 'master/CanteenMaster';

        $this->load->view('admin/includes/_header');
        $this->load->view('master/masterTableView', $data);
        $this->load->view('admin/includes/_footer');
    }

    public function addLiquorBrand()
    {
        // $this->rbac->check_operation_access(); // check opration permission

        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('liquor_brand', 'Brand name', 'trim|required');

            $brand_type = checkIMP($this->input->post('liquor_brand'));

            $data = array(
                'brand' => $brand_type,

                //'created_by' => $this->session->userdata('admin_id'),

            );
            if ($this->form_validation->run() == FALSE) {
                $data = array(
                    'errors' => validation_errors()
                );

                $this->session->set_flashdata('errors', $data['errors']);
                redirect(base_url('master/Alcohol_masterAPI/addLiquorBrand'), 'refresh');
            }


            $result = $this->master_model->insert_brand_name($data);
            if ($result) {
                $this->session->set_flashdata('form_data', 'Brand name added successfully');
                redirect(base_url('master/Alcohol_masterAPI/liquorBrandDetails'));
            }
        } else {
            $data['title'] = trans('add_brand_name');
            $data['mode'] = 'A';
            $this->load->view('admin/includes/_header');
            $this->load->view('master_forms/liquorBrandMasterview', $data);
            $this->load->view('admin/includes/_footer');
        }
    }

    public function editLiquorBrand($id)
    {
        //$this->rbac->check_operation_access(); // check opration permission


        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('liquor_brand', 'Brand name', 'trim|required');
            $data = array(
                'brand' => checkIMP($this->input->post('liquor_brand')),
                'created_by' => $this->session->userdata('admin_id'),
                'id' => $id,
            );
            if ($this->form_validation->run() == FALSE) {
                $data = array(
                    'errors' => validation_errors()
                );

                $this->session->set_flashdata('errors', $data['errors']);
                redirect(base_url('master/Alcohol_masterAPI/editLiquorBrand/' . getValue('id', $resultArray)), 'refresh');
            }

            $result = $this->master_model->update_liquor_brand($data);
            if ($result) {
                $this->session->set_flashdata('form_data', 'Brand name Updated successfully');
                redirect(base_url('master/Alcohol_masterAPI/liquorBrandDetails'), 'refresh');
            }
        } else {
            $data['title'] = trans('edit_brand_name');
            $details = $this->master_model->fetchBrandDetails($id);
            $data['brand'] = $details[0];
            $data['mode'] = 'E';
            $this->load->view('admin/includes/_header');
            $this->load->view('master_forms/liquorBrandMasterview', $data);
            $this->load->view('admin/includes/_footer');
        }
    }

    // LIQUOR BRAND MASTER FUNCTION END
}
