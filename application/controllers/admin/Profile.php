<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Profile extends MY_Controller {
	
	public function __construct(){
		
		parent::__construct();
		auth_check();
		$this->load->model('admin/admin_model', 'admin_model');
	}
	public function index(){
		if($this->input->post('submit') && count($_FILES['profilepics']['name']) > 0){
			$number_of_files = count($_FILES['profilepics']['name']);
            $files = $_FILES;
			$mode="";
            if (!is_dir('uploads/profilepics/'.$this->input->post('username').'/')) {
                mkdir('./uploads/profilepics/'.$this->input->post('username').'/', 0777, true);
            }
            for ($i = 0; $i < $number_of_files; $i++) {
                $_FILES['profilepics']['name'] = $files['profilepics']['name'][$i];
                $_FILES['profilepics']['type'] = $files['profilepics']['type'][$i];
                $_FILES['profilepics']['tmp_name'] = $files['profilepics']['tmp_name'][$i];
                $_FILES['profilepics']['error'] = $files['profilepics']['error'][$i];
                $_FILES['profilepics']['size'] = $files['profilepics']['size'][$i];
                $config['upload_path'] = './uploads/profilepics/'.$this->input->post('username').'/';
                $config['allowed_types'] = 'gif|jpg|png|PNG|jpeg';
                $config['max_size'] = '0';
                $config['max_width']  = '0';
                $config['max_height']  = '0';
                $config['remove_spaces']  = TRUE;
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if ($this->upload->do_upload('profilepics')) {
                    $upload_data = $this->upload->data();
					$file_path = "uploads/profilepics/" .$this->input->post('username').'/'. $upload_data['orig_name'];
                    $file_name = $upload_data['orig_name'];
                } else {
					$mode="nofile";
                }
            }
			if($mode=="nofile"){
				$data = array(
					'username' => $this->input->post('username'),
					'firstname' => $this->input->post('firstname'),
					'email' => $this->input->post('email'),
					'mobile_no' => $this->input->post('mobile_no'),
					'updated_at' => date('Y-m-d : h:m:s')
				);
			}else{
				$data = array(
					'username' => $this->input->post('username'),
					'firstname' => $this->input->post('firstname'),
					'email' => $this->input->post('email'),
					'mobile_no' => $this->input->post('mobile_no'),
					'updated_at' => date('Y-m-d : h:m:s'),
					'image'=>$file_path
				);
			}		
			$data = $this->security->xss_clean($data);
			$result = $this->admin_model->update_user($data);
			if($result){
				$this->session->set_flashdata('success', 'Profile has been Updated Successfully!');
				redirect(base_url('admin/profile'), 'refresh');
			}
		}
		else{
			$data['title'] = 'Admin Profile';
			$data['admin'] = $this->admin_model->get_user_detail();
			
			$this->load->view('admin/includes/_header');
			$this->load->view('admin/profile/index', $data);
			$this->load->view('admin/includes/_footer');
		}
	}
	public function change_pwd(){
		$id = $this->session->userdata('admin_id');
		if($this->input->post('submit')){
			$this->form_validation->set_rules('password', 'Password', 'trim|required');
			$this->form_validation->set_rules('confirm_pwd', 'Confirm Password', 'trim|required|matches[password]');
			if ($this->form_validation->run() == FALSE) {
				$data = array(
					'errors' => validation_errors()
				);
				$this->session->set_flashdata('errors', $data['errors']);
				redirect(base_url('admin/profile/change_pwd'),'refresh');
			}
			else{
				$data = array(
					'password' => md5($this->input->post('password'))
				);
				$data = $this->security->xss_clean($data);
				$result = $this->admin_model->change_pwd($data, $id);
				if($result){
					$this->session->set_flashdata('success', 'Password has been changed successfully!');
					redirect(base_url('admin/profile/change_pwd'));
				}
			}
		}
		else{			
			$data['title'] = 'Change Password';
			// $data['user'] = $this->admin_model->get_user_detail();			
			$this->load->view('admin/includes/_header');
			$this->load->view('admin/profile/change_pwd', $data);
			$this->load->view('admin/includes/_footer');
		}
	}
}
?>	