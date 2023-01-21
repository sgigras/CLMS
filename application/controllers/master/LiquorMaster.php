<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LiquorMaster
 *
 * @author ATS-16
 */
class LiquorMaster extends MY_Controller {

    //put your code here
    public function __construct() {
        parent::__construct();
        auth_check(); // check login auth
        $this->rbac->check_module_access();

        $this->load->model('Master/LiquorMaster_Model', 'liquor_master_model');
        $this->load->model('admin/Activity_model', 'activity_model');
        $this->load->helper(array('bsf_form/list_field', 'bsf_form/master_table', 'Ats/common'));
    }

    public function index() {
        $liquor_data = $this->liquor_master_model->fetchAllLiquorRecords();

        $liquor_data_array = json_decode(json_encode($liquor_data), true);

        if (count($liquor_data_array) > 0) {
            $data['title'] = trans('liquor_list'); // header of the page

            $data['add_url'] = 'master/LiquorMaster/addLiquorDetails'; //url for adding new product on form submission

            $data['add_title'] = trans('liquor_add'); //add button titl on list page

            $data['table_head'] = LIQUOR_MASTER_LIST; //from application/helpers/bsf_form list_field_helper //use to create table 

            $data['table_data'] = $liquor_data;

            $data['edit_url'] = 'master/LiquorMaster/editLiquorDetails';

            $data['csrf_url'] = 'master/LiquorMaster';

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

    public function addLiquorDetails() {
       // $this->rbac->check_operation_access(); // check opration permission
        if ($this->input->post('submit') ) {
          

           

            $files = $_FILES;
           
            


           $upload_path= './uploads/liquor_image/';
           $year = date("Y");
           $month = date("m");
           $day = date("d");
           $date_folder = $year.$month.$day;
           $upload_path .= $date_folder . '/';




           if (!is_dir($upload_path)) {
               mkdir($upload_path, 0777, true);
               chmod($upload_path, 0775);
           }
           




           $config['upload_path']          = $upload_path;
           $config['allowed_types']        = 'jpg|png|jpeg';
           $this->load->library('upload', $config);
           // echo  $this->input->post('liquor_image_h'); die;
           if ( ! $this->upload->do_upload('liquor_image'))
           {

            $data = array('errors' => $this->upload->display_errors());
            print_r($data);
            $this->session->set_flashdata('form_data', $_POST);
            $this->session->set_flashdata('errors', $data['errors']);
            redirect(base_url('master/LiquorMaster/addLiquorDetails'),'refresh');
        }else{
          $upload_array=array('upload_data' => $this->upload->data());
                   
          $image_path = $upload_path.$upload_array['upload_data']['file_name'];
          echo "<pre>";
          print_r($image_path);  echo "<pre>"; 
          $data = array(
                //'liquor_image' => $this->input->post('liquor_image_h'),
            'liquor_image' => $image_path,
            'liquor_name' => $this->input->post('liquor_name'),
            'liquor_type_id' => $this->input->post('liquor_type'),
            'liquor_brand_id' => $this->input->post('liquor_brand'),
            'liquor_description' => $this->input->post('liquor_description'),
            'liquor_bottle_size_id' => $this->input->post('bottle_size'),
            'liquor_ml_id' => $this->input->post('bottle_vol'),
            'user_id' => $this->session->userdata('admin_id'),
            'mode' => 'A',
        );
            


      }
      $result = $this->liquor_master_model->insert_update_liquor_details($data);
            if ($result) {

            $this->session->set_flashdata('form_data', 'Tax name Updated successfully');
            redirect(base_url('master/LiquorMaster/index'), 'refresh');
        }
    //  echo "<pre>"; echo $data; echo '<pre>';

      //$response = $this->CheckEntityMasterForm();
    //   if ($response['success']) {
    //     $response['model_response'] = $this->insert_update_liquor_details($data);
    // }
    //echo json_encode($response);
}
else {

    $data = $this->liquor_master_model->fetchInitialAlcoholFormDetails();
    $this->load->view('admin/includes/_header');
    $this->load->view('liquor/liquorDetailView', $data);
    $this->load->view('admin/includes/_footer');
}

}


public function editLiquorDetails($id) {
        $this->rbac->check_operation_access(); // check opration permission
        if ($this->input->post('submit')) {
            $data = array(
                'liquor_image' => $this->input->post('liquor_image_h'),
                'liquor_name' => $this->input->post('liquor_name'),
                'liquor_type_id' => $this->input->post('liquor_type'),
                'user_id' => $this->session->userdata('admin_id'),
                'id' => $id,
                'mode' => 'E',
            );

            $response = $this->CheckEntityMasterForm();
            if ($response['success']) {
                $response['model_response'] = $this->insert_update_liquor_details($data);
            }
            echo json_encode($response);
        } else {

            $data = $this->liquor_master_model->fetchAlcoholDetails($id);
            $this->load->view('admin/includes/_header');
            $this->load->view('liquor/liquorDetailView', $data);
            $this->load->view('admin/includes/_footer');
        }
    }

    public function insert_update_liquor_details($data) {
        $response = $this->liquor_master_model->insert_update_liquor_details(json_encode($data));

        if ($response[0]->V_SWAL_TYPE == 'success') {
            $this->session->set_userdata('action_messages', $response[0]->V_SWAL_TITLE);
            $this->session->set_userdata('action_messages', $response[0]->V_SWAL_MESSAGE);
            $this->session->set_userdata('swal_icon', $response[0]->V_SWAL_TYPE);
        }
        return $response;
    }

    public function CheckEntityMasterForm() {
        $this->form_validation->set_rules('liquor_image_h', 'Liquor image', 'trim|required');
        $this->form_validation->set_rules('liquor_name', 'Liquor name', 'trim|required');
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
    public function viewProductEnityMapping() {
        $liquor_data = $this->liquor_master_model->fetchAllLiquorRecords();

        $liquor_data_array = json_decode(json_encode($liquor_data), true);

        if (count($liquor_data_array) > 0) {
            $data['title'] = trans('liquor_details'); // header of the page

            $data['add_url'] = 'master/LiquorMaster/addLiquorEntityMapping'; //url for adding new product on form submission

            $data['add_title'] = trans('liquor_add'); //add button titl on list page

            $data['table_head'] = LIQUOR_MASTER_LIST; //from application/helpers/bsf_form list_field_helper //use to create table 

            $data['table_data'] = $liquor_data;

            $data['edit_url'] = 'master/LiquorMaster/editLiquorEntityMapping';

            $data['csrf_url'] = 'master/LiquorMaster';

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

}