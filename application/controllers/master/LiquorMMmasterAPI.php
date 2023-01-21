<?php

/**
Author: SUJIT N. MISHRA
Created on:25/10/2021
Scope: Alcohol MM master API
Source:
 **/




class LiquorMMmasterAPI extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        auth_check();
        $this->load->model('Master/Master_model', 'master_model');
        $this->load->model('admin/Activity_model', 'activity_model');
        $this->load->helper(array('bsf_form/list_field', 'bsf_form/master_table', 'Ats/common'));
    }

    //function to load view page --------------


    public function index()
    {

        $tax_data = $this->master_model->fetchAlcoholQuantityList();

        $data['title'] = trans('alcohol_mm_master');

        $data['add_url'] = 'master/LiquorMMmasterAPI/addAlcoholQuantity';

        $data['add_title'] = trans('add_alcohol_quantity');

        $data['table_head'] = ALCOHOL_MM_MASTER;

        $data['table_data'] = $tax_data;

        $data['edit_url'] = 'master/Tax_masterAPI/editTaxNames';

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
                'alcohol_quantity' => $this->input->post('alcohol_quantity'),
                'created_by' => $this->session->userdata('admin_id'),

            );

            if ($this->form_validation->run() == FALSE) {
                $data = array(
                    'errors' => validation_errors()
                );

                $this->session->set_flashdata('errors', $data['errors']);
                redirect(base_url('master/LiquorMMmasterAPI/addAlcoholQuantity'), 'refresh');
            }

            $result = $this->master_model->insertAlcoholQuantity($data);
            if ($result) {
                $this->session->set_flashdata('form_data', 'Alcohol quantity added successfully');
                redirect(base_url('master/LiquorMMmasterAPI/index'), 'refresh');
            }
        } else {
            $data['title'] = trans('add_alcohol_quantity');
            $data['mode'] = 'A';
            $this->load->view('admin/includes/_header');
            $this->load->view('master_forms/alcoholMMview', $data);
            $this->load->view('admin/includes/_footer');
        }
    }
}
