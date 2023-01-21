<?php

/**
Author: SUJIT N. MISHRA
Created on:23/10/2021
Scope: City master API
Source:
 **/
class City_masterAPI extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        auth_check();
        $this->load->model('Master/Master_model', 'master_model');
        $this->load->model('admin/Activity_model', 'activity_model');
        $this->load->helper(array('bsf_form/list_field', 'bsf_form/master_table', 'Ats/common', 'bsf_form/check_input'));
    }
    //function to load view page --------------

    public function index()
    {
        $tax_data = $this->master_model->fetchInitialCityDetails();

        $data['title'] = trans('city_master');

        $data['add_url'] = 'master/City_masterAPI/addCityDetails';

        $data['add_title'] = trans('add_city_state_name');

        $data['table_head'] = CITY_MSTER;

        $data['table_data'] = $tax_data;

        $data['edit_url'] = 'master/City_masterAPI/editCityDetails';

        $data['csrf_url'] = 'master/CanteenMaster';

        $this->load->view('admin/includes/_header');
        $this->load->view('master/masterTableView', $data);
        $this->load->view('admin/includes/_footer');
    }

    //function to add city and state details---------


    public function addCityDetails()
    {


        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('city_name', 'City name', 'trim|required');
            $this->form_validation->set_rules('select_state', 'Select state', 'trim|required');


            $data = array(
                'city_district_name' => checkIMP($this->input->post('city_name')),
                'stateid' => checkIMP($this->input->post('select_state')),
                'created_by' => $this->session->userdata('admin_id'),

            );
            if ($this->form_validation->run() == FALSE) {
                $data = array(
                    'errors' => validation_errors()
                );

                $this->session->set_flashdata('errors', $data['errors']);
                redirect(base_url('master/City_masterAPI/addCityDetails'), 'refresh');
            }




            $result = $this->master_model->insertCityDetails($data);
            if ($result) {
                $this->session->set_flashdata('form_data', 'City master added successfully');
                redirect(base_url('master/City_masterAPI/index'), 'refresh');
            }
        } else {
            $data['title'] = trans('add_city_state_name');
            $data['mode'] = 'A';

            $data['state_record'] = $this->master_model->fetchState();



            $this->load->view('admin/includes/_header');
            $this->load->view('master_forms/cityMasterview', $data);
            $this->load->view('admin/includes/_footer');
        }
    }


    //function to edit  city and state details---------


    public function editCityDetails($id)
    {




        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('city_name', 'City name', 'trim|required');
            $this->form_validation->set_rules('select_state', 'Select state', 'trim|required');



            $data = array(
                'city_district_name' => $this->input->post('city_name'),
                'stateid' => $this->input->post('select_state'),
                'created_by' => $this->session->userdata('admin_id'),
                'id' => $id

            );
            if ($this->form_validation->run() == FALSE) {
                $data = array(
                    'errors' => validation_errors()
                );

                $this->session->set_flashdata('errors', $data['errors']);
                redirect(base_url('master/City_masterAPI/editCityDetails/'  . getValue('id', $resultArray)), 'refresh');
            }
            $result = $this->master_model->updateCityMaster($data);
            if ($result) {
                $this->session->set_flashdata('form_data', 'City master Updated successfully');
                redirect(base_url('master/City_masterAPI/index'), 'refresh');
            }
        } else {
            $data['title'] = trans('edit_city_state_name');
            $data['state_record'] = $this->master_model->fetchState();
            $details = $this->master_model->fetchCityMasterDetails($id);

            $data['city_district_name'] = $details[0];
            $data['state'] = $details[0];
            $data['stateid'] = $details[0];


            $data['mode'] = 'E';
            $this->load->view('admin/includes/_header');
            $this->load->view('master_forms/cityMasterview', $data);
            $this->load->view('admin/includes/_footer');
        }
    }
}
