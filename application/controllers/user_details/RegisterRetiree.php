<?php
class RegisterRetiree extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        auth_check();
        $this->load->model('user_details/Register_retiree_model', 'register_retiree_model');
        $this->load->helper('bsf_form/upload_image', 'bsf_form/check_input');
    }
    public function index()
    {
        $this->rbac->check_operation_access();
        if ($this->input->post('submit')) {
            $data = array(
                'irla_no' => $this->input->post('personnel_no'),
                'retiree_name' => $this->input->post('retiree_name'),
                'mobile_no' => $this->input->post('mobile_no'),
                'email_id' => $this->input->post('email_id'),
                'date_of_birth' => $this->input->post('date_of_birth'),
                'force_type' => $this->input->post('force_type'),
                'posting_unit_type' => $this->input->post('posting_unit_type'),
                'rank' => $this->input->post('rank'),
                'ppo_no' => $this->input->post('ppo_no'),
                'aadhar_card_no' => $this->input->post('aadhar_card_no'),
                'address' => $this->input->post('address'),
                'joining_date' => $this->input->post('joining_date'),
                'retirement_date' => $this->input->post('retirement_date'),
                'personnel_photo' => $this->input->post('personnel_photo'),
                'ppo_photo' => $this->input->post('ppo_photo'),
                'id_card_photo' => $this->input->post('id_card_photo'),
                'user_id' => $this->session->userdata('admin_id'),
                'mode' => 'A',
            );
            $response = $this->register_retiree_model->insert_retiree_details(json_encode($data));
            echo json_encode($response);
        } else {
            $data = $this->register_retiree_model->fetchInitialFormDetails();
            $this->load->view('admin/includes/_header');
            $this->load->view('master_forms/registerRetireeDetails', $data);
            $this->load->view('admin/includes/_footer');
        }
    }
    public function fetchRetireeInitialFormDetails()
    {
        $data = $this->register_retiree_model->fetchInitialFormDetails();
        echo json_encode($data);
    }
    public function addRetiree()
    {
        $form_content = explode("&", str_replace("%20", " ", $_POST['form_content']));
        $perssonel_no =  explode("=", $form_content[0]);
        $name =  explode("=", $form_content[1]);
        $mobile_no =  explode("=", $form_content[2]);
        $email_id =  explode("=", str_replace("%40", "@", $form_content[3]));
        $date_of_birth =  explode("=", $form_content[4]);
        $force_type =  explode("=", $form_content[5]);
        $posting_unit_type =  explode("=", $form_content[6]);
        $rank =  explode("=", $form_content[7]);
        $ppo_no =  explode("=", $form_content[8]);
        $aadhar_card_no =  explode("=", $form_content[9]);
        $address =  explode("=", $form_content[10]);
        $joining_date  =  explode("=", $form_content[11]);
        $retirement_date =  explode("=", $form_content[12]);
        $personnel_photo = explode("=", $form_content[13]);
        $ppo_photo =   explode("=", $form_content[14]);
        $id_card_photo =  explode("=", $form_content[15]);
        $files = $_FILES;
        $folder = 'retiree_details';
        $personnel_photo_file = $files['personnel_photo'];
        $ppo_photo_file = $files['ppo_photo'];
        $id_card_photo_file = $files['id_card_photo'];
        $personnel_photo_name = $personnel_photo_file['name'];
        $ppo_photo_name = $ppo_photo_file['name'];
        $id_card_photo_name = $id_card_photo_file['name'];
        UploadPics($folder, $personnel_photo_name, "personnel_photo");
        UploadPics($folder, $ppo_photo_name, "ppo_photo");
        UploadPics($folder, $id_card_photo_name, "id_card_photo");
        $data = array(
            'perssonel_no' => $perssonel_no[1],
            'name' => $name[1],
            'mobile_no' => $mobile_no[1],
            'email' => $email_id[1],
            'date_of_birth' => $date_of_birth[1],
            'posting_unit_type' => $posting_unit_type[1],
            'rank' => $rank[1],
            'force_type' => $force_type[1],
            'ppo_no' => $ppo_no[1],
            'adhaar_card' => $aadhar_card_no[1],
            'address' => $address[1],
            'date_of_joining' => $joining_date[1],
            'date_of_retirement' => $retirement_date[1],
            'user_id' => $this->session->userdata('admin_id'),
            'mode' => 'W',
            'personnel_photo' => 'uploads/retiree_details/' . $personnel_photo[1],
            'ppo_photo' => 'uploads/retiree_details/' . $ppo_photo[1],
            'card_photo' => 'uploads/retiree_details/' . $id_card_photo[1]
        );
        $response = $this->register_retiree_model->addRetireeData(json_encode($data));
        echo json_encode($response);
    }
    public function updateRetiree()
    {
        $personnel_no =   $_POST['personnel_no'];
        $name =   $_POST['retiree_name'];
        $mobile_no =   $_POST['mobile_no'];
        $email =   $_POST['email_id'];
        $date_of_birth =   $_POST['date_of_birth'];
        $posting_unit_type =   $_POST['posting_unit_type'];
        $rank =   $_POST['rank'];
        $force_type =   $_POST['force_type'];
        $ppo_no =   $_POST['ppo_no'];
        $adhaar_card =   $_POST['aadhar_card_no'];
        $address =   $_POST['address'];
        $date_of_joining =   $_POST['joining_date'];
        $date_of_retirement =   $_POST['retirement_date'];
        $files = $_FILES;
        $folder = 'retiree_details';
        $personnel_photo_file = $files['personnel_photo'];
        $ppo_photo_file = $files['ppo_photo'];
        $id_card_photo_file = $files['id_card_photo'];
        $personnel_photo_name = $personnel_photo_file['name'];
        $ppo_photo_name = $ppo_photo_file['name'];
        $id_card_photo_name = $id_card_photo_file['name'];
        UploadPics($folder, $personnel_photo_name, "personnel_photo");
        UploadPics($folder, $ppo_photo_name, "ppo_photo");
        UploadPics($folder, $id_card_photo_name, "id_card_photo");
        $data = array(
            'perssonel_no' => $personnel_no,
            'name' => $name,
            'mobile_no' => $mobile_no,
            'email' => $email,
            'date_of_birth' => $date_of_birth,
            'posting_unit_type' => $posting_unit_type,
            'rank' => $rank,
            'force_type' => $force_type,
            'ppo_no' => $ppo_no,
            'adhaar_card' => $adhaar_card,
            'address' => $address,
            'date_of_joining' => $date_of_joining,
            'date_of_retirement' => $date_of_retirement,
            'user_id' => $this->session->userdata('admin_id'),
            'mode' => 'W',
            'personnel_photo' => 'uploads/retiree_details/' . $personnel_photo_name,
            'ppo_photo' => 'uploads/retiree_details/' . $ppo_photo_name,
            'card_photo' => 'uploads/retiree_details/' . $id_card_photo_name
        );
        $response = $this->register_retiree_model->addRetireeData(json_encode($data));
        echo json_encode($response);
    }
    public function fetchRetireeDetails()
    {
        $irlano = $this->input->post('search');
        $result = $this->register_retiree_model->fetchRetireeDetails($irlano);
        echo json_encode($result);
    }
    public function checkRetireeData()
    {
        $data = new stdClass;
        $data->perssonel_no = $this->input->post('perssonel_no');
        $response = $this->register_retiree_model->checkRetireeData($data);
        echo json_encode($response);
    }
    public function uploadPics($img_mode = "", $path = "")
    {
        $mode = ($img_mode == "" ? $_POST["img_mode"] : $img_mode);
        $path = ($path == "" ? $_POST["path"] : $path);
        $response = array();
        date_default_timezone_set('Asia/Kolkata');
        $folder = "";
        switch ($mode) {
            case "profile_pic":
                $folder = "profilepics";
                break;
            case "retiree_details":
                $folder = "./uploads/retiree_details/";
                break;
            default:
                $folder = "";
        }
        if ($folder != "") {
            $response = $this->user_model->uploadPics($folder, $path);
        } else {
            $response = array("status" => 0, "data" => array(), "msg" => "Upload Failed due to insufficent data");
        }
        echo json_encode($response);
    }
}
