<?php


class RegisterRetiree extends My_Controller
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
        $this->rbac->check_operation_access(); // check opration permission

        // $data = $this->register_retiree_model->fetchInitialFormDetails();
        //     $this->load->view('admin/includes/_header');
        //     $this->load->view('master_forms/registerRetireeDetails', $data);
        //     $this->load->view('admin/includes/_footer');

        if ($this->input->post('submit')) {
            $data = array(
                'irla_no' => $this->input->post('irla_no'),
                'retiree_name' => $this->input->post('retiree_name'),
                'mobile_no' => $this->input->post('mobile_no'),
                'email_id' => $this->input->post('email_id'),
                'date_of_birth' => $this->input->post('date_of_birth'),
                'posting_unit_type' => $this->input->post('posting_unit_type'),
                'rank' => $this->input->post('rank'),
                'force_type' => $this->input->post('force_type'),
                'user_id' => $this->session->userdata('admin_id'),
                'mode' => 'A',
            );

            // $response = $this->CheckEntityMasterForm();
            // print_r($response);
            // die();
            // if ($response['success']) {
            // $response['model_response'] = $this->register_retiree_model->insert_retiree_details(json_encode($data));
            $response = $this->register_retiree_model->insert_retiree_details(json_encode($data));

            //     if ($response['model_response'][0]->V_SWAL_TYPE == 'success') {
            //         $this->session->set_userdata('action_messages', $response['model_response'][0]->V_SWAL_TITLE);
            //         $this->session->set_userdata('action_messages', $response['model_response'][0]->V_SWAL_MESSAGE);
            //         $this->session->set_userdata('swal_icon', $response['model_response'][0]->V_SWAL_TYPE);
            //     }
            // }
            echo json_encode($response);
        } else {

            $data = $this->register_retiree_model->fetchInitialFormDetails();
            // echo "<pre>";
            // print_r($data);
            // die;
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
        // echo "Hello";
        // die;        

        // print_r($files);
        // print_r($_POST);
        // $form_content = unserialize($_POST);
        // print_r($form_content);
        // print_r (explode("&",$_POST['form_content']));
        $form_content = explode("&", str_replace("%20", " ", $_POST['form_content']));
        // print_r($form_content);
        // die;
        $perssonel_no =  explode("=", $form_content[0]);
        $name =  explode("=", $form_content[1]);
        $mobile_no =  explode("=", $form_content[2]);
        $email_id =  explode("=", str_replace("%40", "@", $form_content[3]));
        // $email_id =  explode("=",  $form_content[3]);
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
        $signed_form_photo =  explode("=", $form_content[16]);


        $files = $_FILES;
        // print_r($files);
        // print_r("Image :".$files);
        // count($files);
        // print_r($files);
        // die();
        $folder = 'retiree_details';
        $personnel_photo_file = $files['personnel_photo'];
        $ppo_photo_file = $files['ppo_photo'];
        $id_card_photo_file = $files['id_card_photo'];
        $signed_form_photo_file = $files['signed_form_photo'];
        // print_r($f_name);
        $personnel_photo_name = $personnel_photo_file['name'];
        $ppo_photo_name = $ppo_photo_file['name'];
        $id_card_photo_name = $id_card_photo_file['name'];
        $signed_form_photo_name = $signed_form_photo_file['name'];

        // print_r($file_name);
        // die();
        UploadPics($folder, $personnel_photo_name, "personnel_photo");
        UploadPics($folder, $ppo_photo_name, "ppo_photo");
        UploadPics($folder, $id_card_photo_name, "id_card_photo");
        UploadPics($folder, $signed_form_photo_name, "signed_form_photo");


        // $upload_path = './uploads/register_retiree/';
        // // $year = date("Y");
        // // $month = date("m");
        // // $day = date("d");
        // // $date_folder = $year . $month . $day;
        // // $upload_path .= $date_folder . '/';

        // if (!is_dir($upload_path)) {
        //     mkdir($upload_path, 0777, true);
        //     chmod($upload_path, 0775);
        // }

        // $config['upload_path'] = $upload_path;
        // $config['allowed_types'] = 'jpg|png|jpeg';
        // $this->load->library('upload', $config);

        // if (!$this->upload->do_upload('liquor_image')) {

        //         $data = array('errors' => $this->upload->display_errors());
        //         // print_r($data);die();
        //         $this->session->set_flashdata('form_data', $_POST);
        //         $this->session->set_flashdata('errors', $data['errors']);
        //         redirect(base_url('master/Alcohol_masterAPI/addLiquorDetails'), 'refresh');
        //     }
        // $upload_array = array('upload_data' => $this->upload->data());

        // $image_path = $upload_path . $upload_array['upload_data']['file_name'];
        // print_r (explode(" ",$str));
        $data = array(
            // 'perssonel_no' => $form_content[0],
            'perssonel_no' => $perssonel_no[1],
            'name' => $name[1],
            'mobile_no' => checkIMPnum($mobile_no[1]),
            'email' => checkIMPemail($email_id[1]),
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
            'card_photo' => 'uploads/retiree_details/' . $id_card_photo[1],
            'signed_form_photo' => 'uploads/retiree_details/' . $signed_form_photo[1],
        );
        // print_r($data);
        // die;

        $response = $this->register_retiree_model->addRetireeData(json_encode($data));
        echo json_encode($response);
    }

    public function updateRetiree()
    {
        // print_r($_POST);
        $personnel_no =   $_POST['personnel_no'];
        // print_r($personnel_no);
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
        // $personnel_photo =   $_POST['personnel_photo'];
        // $ppo_photo =   $_POST['ppo_photo'];
        // $card_photo =   $_POST['card_photo'];
        // $signed_form_photo =   $_POST['signed_form_photo'];

        $files = $_FILES;
        // print_r($files);
        // print_r("Image :".$files);
        // count($files);
        // print_r($files);
        // die();
        $folder = 'retiree_details';
        $personnel_photo_file = $files['personnel_photo'];
        $ppo_photo_file = $files['ppo_photo'];
        $id_card_photo_file = $files['id_card_photo'];
        $signed_form_photo_file = $files['signed_form_photo'];
        // print_r($f_name);
        $personnel_photo_name = $personnel_photo_file['name'];
        $ppo_photo_name = $ppo_photo_file['name'];
        $id_card_photo_name = $id_card_photo_file['name'];
        $signed_form_photo_name = $signed_form_photo_file['name'];

        // print_r($file_name);
        // die();
        UploadPics($folder, $personnel_photo_name, "personnel_photo");
        UploadPics($folder, $ppo_photo_name, "ppo_photo");
        UploadPics($folder, $id_card_photo_name, "id_card_photo");
        UploadPics($folder, $signed_form_photo_name, "signed_form_photo");

        $data = array(
            // 'perssonel_no' => $form_content[0],
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
            'card_photo' => 'uploads/retiree_details/' . $id_card_photo_name,
            'signed_form_photo' => 'uploads/retiree_details/' . $signed_form_photo_name,
        );

        // print_r($data);
        $response = $this->register_retiree_model->addRetireeData(json_encode($data));
        echo json_encode($response);
    }

    public function fetchRetireeDetails()
    {
        $irlano = $this->input->post('search');
        $result = $this->register_retiree_model->fetchRetireeDetails($irlano);
        // print_r($result);
        echo json_encode($result);
    }

    public function checkRetireeData()
    {
        // $data = json_decode(file_get_contents('php://input'));
        $data = new stdClass;
        $data->perssonel_no = $this->input->post('perssonel_no');
        // print_r($data);
        // die;
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
    // public function CheckEntityMasterForm()
    // {
    //     $this->form_validation->set_rules('irla_no', 'Irla No', 'trim|required');
    //     $this->form_validation->set_rules('retiree_name', 'Retiree Name', 'trim|required');
    //     $this->form_validation->set_rules('mobile_no', 'Mobile NO', 'trim|required');
    //     $this->form_validation->set_rules('email_id', 'Email ID', 'trim|required');
    //     $this->form_validation->set_rules('date_of_birth', 'Date of birth', 'trim|required');
    //     $this->form_validation->set_rules('posting_unit_type', 'Posting unit', 'trim|required');
    //     $this->form_validation->set_rules('rank', 'Rank', 'trim|required');
    //     $this->form_validation->set_rules('force_type', 'Force  Name', 'trim|required');

    //     $this->form_validation->set_error_delimiters('<p class="text-danger" style="font-size:14px">', '</p>');
    //     if ($this->form_validation->run()) {
    //         $data['success'] = true;
    //     } else {
    //         $data['success'] = false;
    //         foreach ($_POST as $key => $value) {
    //             $data['messages'][$key] = form_error($key);
    //         }
    //     }
    //     return $data;
    // }


}
