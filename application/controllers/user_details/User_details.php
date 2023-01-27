<?php
class User_details extends My_Controller
{
    public function __construct()
    {
        parent::__construct();
        auth_check();
        $this->load->model('user_details/User_details_model', 'user_details_model');
        $this->load->model('admin/dashboard_model', 'dashboard_model');
    }
    // public function index()
    // {
    //     // $this->load->view('admin/includes/_header');
    //     $this->load->view('master/master_user');
    //     // $this->load->view('admin/includes/_footer');
    // }
    public function index()
    {
        $data['all_users'] = $this->dashboard_model->get_all_users();
        // $data['active_users'] = $this->dashboard_model->get_active_users();
        // $data['deactive_users'] = $this->dashboard_model->get_deactive_users();
        $data['title'] = 'Dashboard';
        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/dashboard/index', $data);
        $this->load->view('admin/includes/_footer');
    }
    public function viewRetireeeVerificationCanteenWiseDetails()
    {
        $data['canteen_data_array'] = $this->user_details_model->fetchCanteens();
        $data['title'] = "verification_status";
        $this->load->view('admin/includes/_header');
        $this->load->view('user_details/canteen_retiree_verification', $data);
        $this->load->view('admin/includes/_footer');
    }
    public function fetchVerificationDetails()
    {
        $entity_id = $this->input->post('entity_id');
        $mode = $this->input->post('mode');
        $response = $this->user_details_model->fetchVerificationDetails($entity_id, $mode);
        echo json_encode($response);
    }
    public function fetchRegistrationDetails()
    {
        $data['all_users'] = $this->dashboard_model->get_all_users();
        $data['registered'] = $this->user_details_model->get_all_registered();
        $data['approved'] = $this->user_details_model->get_all_approved();
        $data['registered_serving'] = $this->user_details_model->get_all_registered_serving();
        $data['serving_user'] = $this->user_details_model->get_all_serving_users();
        $data['retired_user'] = $this->user_details_model->get_all_retired_users();
        $data['all_entities'] = $this->user_details_model->get_all_entities();
        $data['all_canteen'] = $this->user_details_model->get_all_canteen_count();
        $data['all_stockist'] = $this->user_details_model->get_all_stockist_count();
        $data['entities_started_sale'] = $this->user_details_model->get_entities_started_sale_count();
        $data['canteen_started_sale'] = $this->user_details_model->get_canteens_started_sale_count();
        $data['stockist_started_sale'] = $this->user_details_model->get_stockists_started_sale_count();
        $data['entities_right_given'] = $this->user_details_model->get_entities_right_given_count();
        $data['canteen_right_given'] = $this->user_details_model->get_canteen_right_given_count();
        $data['stockist_right_given'] = $this->user_details_model->get_stockist_right_given_count();
        $data['entities_right_not_given'] = $this->user_details_model->get_entites_right_not_given_count();
        $data['canteen_right_not_given'] = $this->user_details_model->get_canteen_right_not_given_count();
        $data['stockist_right_not_given'] = $this->user_details_model->get_stockist_right_not_given_count();
        $data['entities_liquor_added'] = $this->user_details_model->get_entities_liquor_added_count();
        $data['canteen_liquor_added'] = $this->user_details_model->get_canteen_liquor_added_count();
        $data['stockist_liquor_added'] = $this->user_details_model->get_stockist_liquor_added_count();
        $this->load->view('admin/includes/_header');
        $this->load->view('user_details/registration_details', $data);
        $this->load->view('admin/includes/_footer');
    }
    public function GetIrlaNumber()
    {
        $searchterm = $this->input->get('q');
        $response = $this->user_details_model->GetIrlaNumber($searchterm);
        echo json_encode($response);
    }
    public function GetCanteenName()
    {
        $searchterm = $this->input->get('q');
        $response = $this->user_details_model->GetCanteenName($searchterm);
        echo json_encode($response);
    }
    public function update_user()
    {
        echo "h";
        $irla = $this->input->post("irla");
        $dob = $this->input->post("dob");
        $mobile = $this->input->post("mobile");
        $email = $this->input->post("email");
        $response = $this->user_details_model->update_user($irla, $dob, $mobile, $email);
        die;
        echo json_encode($response);
    }
    public function GetUserdetails()
    {
        $irla_no = $this->input->post("irlano");
        // echo $irla_no;
        $response = $this->user_details_model->fetchUserDetails($irla_no);
        // $count_response= $this->user_details_model->fetchCountUserDetails();
        $user_details_array = array();
        // echo '<pre>';
        // print_r($response);
        // echo '</pre>';
        $i = 1;
        foreach ($response as $row) {
            $row['sr_no'] = $i++;
            if ($row['registration_status'] == null) {
                $row['registration_status'] = 'Not Registered';
            } else {
                $row['registration_status'] = 'Registered';
            }
            $user_details_array[] = $row;
        }
        // echo '<pre>';
        // print_r($user_details_array);
        // echo '</pre>';die();
        $this->load->view('master_forms/edit_user', array("user_details_array" => $user_details_array));
    }
    public function GetPostingUnit()
    {
        $searchterm = $this->input->get('q');
        $response = $this->user_details_model->GetPostingUnit($searchterm);
        echo json_encode($response);
    }
    public function GetPostingunitdata()
    {
        $posting_unit = $this->input->post("posting_unit");
        $response = $this->user_details_model->fetchUserDetailsPostingUnit($posting_unit);
        foreach ($response as $row) {
            if ($row['registration_status'] == null) {
                $row['registration_status'] = 'Not Registered';
            } else {
                $row['registration_status'] = 'Registered';
            }
            $user_details_array[] = $row;
        }
        $this->load->view('master/master_user', array("user_details_array" => $user_details_array));
        //    echo json_encode($user_details_array);
    }
    public function viewPostinUnit()
    {
        $data['posting_unit_data'] = $this->user_details_model->fetch_posting_units();
        $data['title'] = trans('user_report');
        $this->load->view('admin/includes/_header');
        $this->load->view('user_details/viewPostingWise', $data);
        $this->load->view('admin/includes/_footer');
    }
    public function GetPostingWise()
    {
        $posting_unit = $this->input->post("posting_unit");
        $mode = $this->input->post("mode");
        $personnel_type = $this->input->post("personnel_type");
        $response = $this->user_details_model->getPostingWise($posting_unit, $mode, $personnel_type);
        echo json_encode($response);
    }
    public function getRetireeReport()
    {
        $data['pending'] = $this->user_details_model->get_all_pending();
        $data['approved'] = $this->user_details_model->get_all_approved();
        $data['denied'] = $this->user_details_model->get_all_denied();
        $data['registered'] = $this->user_details_model->get_all_registered();
        $data['title'] = trans('all_canteen');
        $this->load->view('admin/includes/_header');
        $this->load->view('user_details/get_RetireeMasterView', $data);
        $this->load->view('admin/includes/_footer');
    }
    public function getRetireeData()
    {
        // echo "Hello";
        $report_type = $this->input->post('report_type');
        $data = $this->user_details_model->fetchRetireeDetails($report_type);
        $this->load->view('user_details/get_RetireeReportTable', $data);
    }
    public function getRetireeCanteenData()
    {
        // echo "Hello";
        $report_type = $this->input->post('report_type');
        $entity_id = $this->input->post('entity_id');
        $data = $this->user_details_model->fetchRetireeCanteenDetails($report_type, $entity_id);
        $this->load->view('user_details/get_RetireeReportTable', $data);
    }

    public  function displayRanks()
    {
        $rank_quota_data = $this->user_details_model->fetchAllRankQuotaRecords();
        $data['title'] = trans('liquor_rank_quota_list'); // header of the page
        $data['add_url'] = 'master/Liquor_mapping/addLiquorMappingDetails'; //url for adding new product on form submission
        $data['add_title'] = trans('liquor_rank'); //add button titl on list page
        $data['table_head'] = LIQUOR_RANK_QUOTA_LIST; //from application/helpers/bsf_form list_field_helper //use to create table 
        $data['table_data'] = $rank_quota_data;
        $data['table_mode'] = "LIQUOR_RANK_QUOTA_LIST";
        $data['edit_url'] = 'user_details/User_details/editRankQuotaDetails';
        $data['csrf_url'] = 'user_details/User_details';
        $this->load->view('admin/includes/_header');
        $this->load->view('master/newMasterTableView', $data);
        $this->load->view('admin/includes/_footer');
    }
    public function editRankQuotaDetails()
    {
        $id = $this->input->post('id');
        $mode = $this->input->post('mode');
        $quota = $this->input->post('quota');
        $response = $this->user_details_model->editRankQuotaDetails($id, $mode, $quota);
        echo json_encode($response);
    }
    public function getOtp()
    {
        $irla_no = $this->input->post('irlano');
        $response = $this->user_details_model->getOtp($irla_no);
        echo json_encode($response);
    }
    public function removeMacBinding()
    {
        $irla_no = $this->input->post('irlano');
        $response = $this->user_details_model->removeMacBinding($irla_no);
        echo $response;
    }
    public function getActiveUsers()
        {
            
        }
}
