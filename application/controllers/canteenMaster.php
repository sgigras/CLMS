<?php

class CanteenMaster extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('canteenMasterModel');
    }

    public function index()
    {
        // $this->load->view('canteenMasterView');
    }

    public function GetCanteenData()
    {
        // $data['title'] = trans('liquor_details'); // header of the page

        //     $data['add_url'] = 'master/Alcohol_masterAPI/addLiquorEntityMapping'; //url for adding new product on form submission

        //     $data['add_title'] = trans('liquor_add'); //add button titl on list page

        //     $data['table_head'] = LIQUOR_MASTER_LIST; //from application/helpers/bsf_form list_field_helper //use to create table 

        //     $data['table_data'] = $liquor_data;
        // $data['canteen_details'] = $this->canteenMasterModel->fetchUserDetails();
        // $this->load->view('canteenMasterView',$data);


        $liquor_data = $this->canteenMasterModel->fetchCanteenDetails();

        $data['title'] = trans('all_canteen');

        $data['table_head'] = ALL_CANTEEN;

        $data['table_data'] = $liquor_data;
        
        $this->load->view('admin/includes/_header');
        $this->load->view('canteenMasterView',$data);

        $this->load->view('admin/includes/_footer');


    }
}

?>