<?php
class CanteenMaster extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        auth_check();
        $this->rbac->check_module_access();
        $this->load->model('Master/Canteen_master_model', 'canteen_master_model');
        $this->load->model('admin/Activity_model', 'activity_model');
        $this->load->helper(array('bsf_form/list_field', 'bsf_form/master_table', 'Ats/common'));
    }
    public function index()
    {
        $this->rbac->check_operation_access();
        $roleid = $this->session->userdata('admin_role_id');
        $canteen_data = $this->canteen_master_model->fetchCanteenList();
            $data['title'] = trans('canteen_master');
            $data['add_url'] = 'master/CanteenMaster/addCanteenClub';
            $data['edit_url']= 'master/CanteenMaster/editCanteenClub';
            $data['add_title'] = trans('add_new_canteen');
            $data['table_head'] = CANTEEN_MASTER_LIST;
            $data['table_data'] = $canteen_data;
            $data['csrf_url'] = 'master/CanteenMaster';
            $this->load->view('admin/includes/_header');
            $this->load->view('master/masterTableView', $data);
            $this->load->view('admin/includes/_footer');
    }
    public function getCanteenReport()
    {
        $data['title'] = trans('all_canteen');
        $this->load->view('admin/includes/_header');
        $this->load->view('canteen/get_canteenMasterView', $data);
        $this->load->view('admin/includes/_footer');
    }
    public function addCanteenClub()
    {
        $this->rbac->check_operation_access();
        if ($this->input->post('submit')) {
            $data = array(
                'outlet_type' => $this->input->post('outlet_type'),
                'battalion_unit' => $this->input->post('battalion_unit'),
                'entity_name' => $this->input->post('canteen_name'),
                'address' => $this->input->post('address'),
                'city' => $this->input->post('select_city'),
                'state' => $this->input->post('select_state'),
                'chairman' => $this->input->post('select_chairman'),
                'supervisor' => $this->input->post('select_supervisor'),
                'executive' => $this->input->post('select_executive'),
                'distrubuting_entity_type' => $this->input->post('select_distrubuting_authority'),
                'distributor_authorised_entity' => $this->input->post('select_distributor_name'),
                'user_id' => $this->session->userdata('admin_id'),
                'mode' => 'A',
            );
            
            if ($data['distrubuting_entity_type'] == "" ){
                $data['distrubuting_entity_type'] = "0";
            }
            if ($data['distributor_authorised_entity'] == "" ){
                $data['distributor_authorised_entity'] = "0";
            }
            $response = $this->CheckEntityMasterForm();
            if ($response['success']) {
                $response['model_response'] = $this->canteen_master_model->insert_canteen_details(json_encode($data));
                if ($response['model_response'][0]->V_SWAL_TYPE == 'success') {
                    $this->session->set_userdata('action_messages', $response['model_response'][0]->V_SWAL_TITLE);
                    $this->session->set_userdata('action_messages', $response['model_response'][0]->V_SWAL_MESSAGE);
                    $this->session->set_userdata('swal_icon', $response['model_response'][0]->V_SWAL_TYPE);
                }
            }
            echo json_encode($response);
        } else {
            $userlist = $this->canteen_master_model->fetchUserList();
            $data = $this->canteen_master_model->fetchInitialEntityFormDetails("");
            $data["userlist"] = $userlist;
            $this->load->view('admin/includes/_header');
            $this->load->view('canteen/canteenDetailView', $data);
            $this->load->view('admin/includes/_footer');
        }
    }
    public function editCanteenClub($id)
    {
        $this->rbac->check_operation_access();
        if ($this->input->post('submit')) {
            $data = array(
                'outlet_type' => $this->input->post('outlet_type'),
                'battalion_unit' => $this->input->post('battalion_unit'),
                'entity_name' => $this->input->post('canteen_name'),
                'address' => $this->input->post('address'),
                'city' => $this->input->post('select_city'),
                'state' => $this->input->post('select_state'),
                'chairman' => $this->input->post('select_chairman'),
                'supervisor' => $this->input->post('select_supervisor'),
                'executive' => $this->input->post('select_executive'),
                'distrubuting_entity_type' => $this->input->post('select_distrubuting_authority'),
                'distributor_authorised_entity' => $this->input->post('select_distributor_name'),
                'user_id' => $this->session->userdata('admin_id'),
                'mode' => 'E',
                'entity_id' => $this->input->post('entity_id')
            );
            $response = $this->CheckEntityEditMasterForm();
            if ($response['success']) {
                $response['model_response'] = $this->canteen_master_model->update_canteen_details(json_encode($data));
                if ($response['model_response'][0]->V_SWAL_TYPE == 'success') {
                    $this->session->set_userdata('action_messages', $response['model_response'][0]->V_SWAL_TITLE);
                    $this->session->set_userdata('action_messages', $response['model_response'][0]->V_SWAL_MESSAGE);
                    $this->session->set_userdata('swal_icon', $response['model_response'][0]->V_SWAL_TYPE);
                }
            }
            echo json_encode($response);
        } else {
            $userlist = $this->canteen_master_model->fetchUserList();            
            $data = $this->canteen_master_model->fetchInitialEntityFormDetails($id);
            $data["userlist"] = $userlist;
            $state = $data["user_details"]->state;
            $data["city_select_array"] = $this->canteen_master_model->fetchCities($state);
            $data['mode'] = 'E';
            $this->load->view('admin/includes/_header');
            $this->load->view('canteen/canteenEditDetailView', $data);
            $this->load->view('admin/includes/_footer');
        }
    }
    public function editChairmanCanteenClub($id)
    {
        if ($this->input->post('submit')) {
            $data = array(
                'supervisor' => $this->input->post('select_supervisor'),
                'executive' => $this->input->post('select_executive'),
                'distrubuting_entity_type' => $this->input->post('select_distrubuting_authority'),
                'distributor_authorised_entity' => $this->input->post('select_distributor_name'),
                'user_id' => $this->session->userdata('admin_id'),
                'mode' => 'E',
                'entity_id' => $this->input->post('entity_id')
            );
        
            $response = $this->CheckEntityMasterChairmanForm();
            if ($response['success']) {
                $response['model_response'] = $this->canteen_master_model->update_chairman_canteen_details(json_encode($data));
                if ($response['model_response'][0]->V_SWAL_TYPE == 'success') {
                    $this->session->set_userdata('action_messages', $response['model_response'][0]->V_SWAL_TITLE);
                    $this->session->set_userdata('action_messages', $response['model_response'][0]->V_SWAL_MESSAGE);
                    $this->session->set_userdata('swal_icon', $response['model_response'][0]->V_SWAL_TYPE);
                }
            }
            echo json_encode($response);
        } else {
            $data = $this->canteen_master_model->fetchChairmanEntityDetails($id);
            $this->load->view('admin/includes/_header');
            $this->load->view('canteen/canteenChairmanView', $data);
            $this->load->view('admin/includes/_footer');
        }
    }
    public function CheckEntityMasterForm()
    {
        $this->form_validation->set_rules('outlet_type', 'Type', 'trim|required');
        $this->form_validation->set_rules('select_state', 'State', 'trim|required');
        $this->form_validation->set_rules('select_city', 'City', 'trim|required');
        $this->form_validation->set_rules('select_chairman', 'Chairman', 'trim|required');
        $this->form_validation->set_rules('select_executive', 'Executive', 'trim|required');
        $this->form_validation->set_rules('select_supervisor', 'Supervisor', 'trim|required');
        $this->form_validation->set_rules('canteen_name', 'Canteen/Club Name', 'trim|required');
        $this->form_validation->set_rules('address', 'Address', 'trim|required');
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
    public function CheckEntityEditMasterForm()
    {
        $this->form_validation->set_rules('select_chairman', 'Chairman', 'trim|required');
        $this->form_validation->set_rules('select_executive', 'Executive', 'trim|required');
        $this->form_validation->set_rules('select_supervisor', 'Supervisor', 'trim|required');
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
    public function CheckEntityMasterChairmanForm()
    {
        $this->form_validation->set_rules('select_executive', 'Executive', 'trim|required');
        $this->form_validation->set_rules('select_supervisor', 'Supervisor', 'trim|required');
        $this->form_validation->set_rules('select_distrubuting_authority', 'Distributor Authority', 'trim|required');
        $this->form_validation->set_rules('select_distributor_name', 'Distributor Name', 'trim|required');
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
    public function getCityList()
    {
        $this->rbac->check_operation_access();
        $state_id = $this->input->post('state_id');
        $result = $this->canteen_master_model->fetchCities($state_id);
        echo json_encode($result);
    }
    public function getDistrubutors()
    {
        $this->rbac->check_operation_access();
        $distrubtor_authority = $this->input->post('distrubtor_authority');
        $result = $this->canteen_master_model->fetchDistrubutors($distrubtor_authority);
        echo json_encode($result);
    }
    public function getUsers()
    {
        $irlano = $this->input->post('search');
        $result = $this->canteen_master_model->getUsersDetails($irlano);
        echo json_encode($result);
    }
}
