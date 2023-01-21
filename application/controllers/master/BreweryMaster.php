<?php defined('BASEPATH') or exit('No direct script access allowed');

class BreweryMaster extends MY_Controller
{

    //put your code here
    public function __construct()
    {
        parent::__construct();
        auth_check(); // check login auth
        $this->rbac->check_module_access();

        $this->load->model('admin/dashboard_model', 'dashboard_model');


        $this->load->model('Order_details/Order_model', 'order_model');
        $this->load->model('admin/Brewery_model', 'brewery_model');
        $this->load->model('admin/Activity_model', 'activity_model');
        $this->load->model('newStock/NewAvailable_stock');


        $this->load->helper(array('bsf_form/list_field', 'bsf_form/master_table', 'Ats/common', 'bsf_form/check_input'));
    }


    function brandMapping()
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
                    // print_r($_POST);
                    // die();
                    $data['messages'][$key] = form_error($key);
                }
            }
        }

        if (!empty($_POST['breweryname'])) {
            $breweryid = $this->input->post('breweryname');
            $data['brewery_statemappedlist'] = $this->brewery_model->getBreweryMappedList($breweryid);
        }

        // print_r($data);
        $data['title'] = trans('brewery_state_mapping');
        $data['brewerylist'] = $this->brewery_model->getBreweryList();
        $data['depotlist'] = $this->brewery_model->getDepotName();
        $data['selectbrewery'] = $this->brewery_model->getAllBrewery();


        $this->load->view('admin/includes/_header');
        $this->load->view('admin/brewery/state_mapping_view', $data);
        $this->load->view('admin/includes/_footer');
    }


    public function index()
    {
        $this->rbac->check_operation_access(); // check opration permission
        $brewery_data = $this->brewery_model->getBreweryList();
        $brewery_data_array = json_decode(json_encode($brewery_data), true);
        if (count($brewery_data_array) > 0) {
            $data['title'] = trans('brewery_master');
            $data['add_url'] = 'master/BreweryMaster/addBrewery';
            $data['add_title'] = trans('add_new_brewery');
            $data['table_head'] = BREWERY_MASTER_LIST;
            $data['table_data'] = $brewery_data;
            $data['edit_url'] = 'master/BreweryMaster/editBrewery';
            $data['csrf_url'] = 'master/BreweryMaster';
            $this->load->view('admin/includes/_header');
            $this->load->view('master/masterTableView', $data);
            $this->load->view('admin/includes/_footer');
        } else {
            $data = $this->brewery_model->fetchInitialEntityFormDetails();
            $this->load->view('admin/includes/_header');
            $this->load->view('admin/brewery/breweryDetailView', $data);
            $this->load->view('admin/includes/_footer');
        }
    }


    // to add new Brewery details
    public function addBrewery()
    {
        $this->rbac->check_operation_access(); // check opration permission
        if ($this->input->post('submit')) {
            $data = array('success' => false, 'messages' => array());
            $this->form_validation->set_rules('brewery_name', 'Brewery Name', 'trim|required|min_length[3]|max_length[80]');
            $this->form_validation->set_rules('breweryaddress', 'Brewery Address', 'trim|required|min_length[3]|max_length[250]');
            $this->form_validation->set_rules('contactperson', 'Contact Person', 'trim|required|min_length[3]|max_length[100]');
            $this->form_validation->set_rules('mobilenumber', 'Mobile Number', 'trim|required|min_length[10]|max_length[10]');
            $this->form_validation->set_rules('emailaddress', 'Email Address', 'trim|required|valid_email|min_length[3]|max_length[580]');
            $this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');


            if ($this->form_validation->run()) {
                $data['success'] = true;
                $this->brewery_model->insert();
                $this->brewery_model->insertToEntityTable();
            } else {
                foreach ($_POST as $key => $value) {
                    $data['messages'][$key] = form_error($key);
                }
            }
            echo json_encode($data);
            return;
        } else {

            $data = $this->brewery_model->fetchInitialEntityFormDetails();
            $this->load->view('admin/includes/_header');
            $this->load->view('admin/brewery/breweryDetailView', $data);
            $this->load->view('admin/includes/_footer');
        }
    }

    //Edit Brewery details  
    public function editBrewery($id)
    {
        $this->rbac->check_operation_access(); // check opration permission
        if ($this->input->post('submit')) {
            $data = array(
                'brewery_name' => $this->input->post('brewery_name'),
                'address' => $this->input->post('breweryaddress'),
                'contact_person_name' => $this->input->post('contactperson'),
                'mobile_no' => $this->input->post('mobilenumber'),
                'mail_id' => $this->input->post('emailaddress'),
                'state' => implode(',', $this->input->post('select_brewerystate')),
                // 'serving_entity' => implode(',', $this->input->post('select_breweryentity'))
            );

            $response = $this->CheckEntityMasterForm();
            if ($response['success']) {
                $response['model_response'] = $this->brewery_model->updateBreweryDetails($id, $data);
                if ($response['model_response']) {
                    $response['mode'] = 'edit';
                    echo json_encode($response);
                }
            }
            // echo json_encode($response);
        } else {
            $data = $this->brewery_model->fetchBreweryDetails($id);
            $this->load->view('admin/includes/_header');
            $this->load->view('admin/brewery/breweryDetailView', $data);
            $this->load->view('admin/includes/_footer');
        }
    }

    public function CheckEntityMasterForm()
    {
        $this->form_validation->set_rules('brewery_name', 'Brewery Name', 'trim|required');
        $this->form_validation->set_rules('breweryaddress', 'Brewery Address', 'trim|required');
        $this->form_validation->set_rules('contactperson', 'Contact Person Name', 'trim|required');
        $this->form_validation->set_rules('mobilenumber', 'Mobile Number', 'trim|required');
        $this->form_validation->set_rules('emailaddress', 'Email Address', 'trim|required');
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
        $this->rbac->check_operation_access(); // check opration permission
        $state_id = $this->input->post('state_id');
        $result = $this->canteen_master_model->fetchCities($state_id);
        echo json_encode($result);
    }

    public function getDistrubutors()
    {
        $this->rbac->check_operation_access(); // check opration permission
        //        $distrubtor_authority_id = $this->input->post('distrubtor_authority_id');
        $distrubtor_authority = $this->input->post('distrubtor_authority');
        $result = $this->canteen_master_model->fetchDistrubutors($distrubtor_authority);
        echo json_encode($result);
    }
    public function loadBreweryOrder()
    {
        // $order_id = $this->input->post("order_id");
        $order_id = 15;
        $data['brewerysummary'] = $this->brewery_model->fetchOrderRequested($order_id);

        echo '<pre>';
        print_r($data);
        echo '</pre>';
        die();
        $user_id = $this->session->userdata('admin_id');
        $page_labels = unserialize(ORDER_DELIVERY_SUMMARY); //to fetch page labels

        $this->load->view('admin/includes/_header');
        $this->load->view('order/brewery_order_approval_summary', $data); //$data
        $this->load->view('admin/includes/_footer');
    }



    /// below functions are used for order to brewery
    public function orderToBrewery()
    {
        $data['brewerylist'] = $this->brewery_model->getBrewery();
        $this->load->view('admin/includes/_header');
        $this->load->view('admin/brewery/orderToBreweryView', $data);
        $this->load->view('admin/includes/_footer');
    }

    public function fetchLiquorList()
    {
        $selectedbreweryid = $this->input->post('selectedbreweryid');
        $response['liquor_name_record'] = $this->brewery_model->fetchLiquorList($selectedbreweryid);
        $this->load->view('admin/brewery/liquor_tabular_details_view', $response);
    }


    public function insertIntoBreweryOrderLiquorDetails()
    {
        $data = array(
            "user_id" => $this->session->userdata('admin_id'),
            "entity_id" => $this->session->userdata('entity_id'),
            "brewery_id" => $this->input->post('brewery_id'),
            "liquor_details_data" => $this->input->post('mainArr'),
        );

        $result = $this->brewery_model->insertIntoBreweryOrderLiquorDetails(json_encode($data));
        echo json_encode($result);
    }
}
