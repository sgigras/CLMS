<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Vehicle_verificationAPI extends MY_Controller {

 public function __construct() {

  parent::__construct();
      auth_check(); // check login auth
      $this->rbac->check_module_access();
      $this->load->helper(array('form', 'url'));
      $this->load->model('verification/Vehicle_veri_model', 'vehicle_verify');
    }

    public function index(){
      $this->load->view('admin/includes/_header');
      $this->load->view('verification/vehicle_verification');
      $this->load->view('admin/includes/_footer');      
    }

    public function fetchvehicle(){
     $searchterm = $this->input->get('q');
     $result = $this->vehicle_verify->fetchvehicle($searchterm);
     echo json_encode($result); 
   }

   public function getvehicles(){
    $vehicle = $this->input->post('vehicle');
    
    if($this->input->post('submit')){
     $vehicle = $this->input->post('vehicle');
     $result['info'] = $this->vehicle_verify->getvehicle($vehicle);
     $this->load->view('admin/includes/_header');
     $this->load->view('verification/vehicle_verification',$result);
     $this->load->view('admin/includes/_footer');      
   }else if($this->input->post('approve')){

    $Approve = $this->input->post('approve');
    $vehicleid=$this->input->post('value');
    $Approve = $this->security->xss_clean($Approve,$vehicleid);
    $result = $this->vehicle_verify->verify($Approve,$vehicleid);
    if($result==1){
      $this->session->set_flashdata('success', 'Vehicle Has been Verified');
      redirect(base_url('verification/Vehicle_verificationAPI'));
    }


  }else if($this->input->post('reject')){


   $vehicleid=$this->input->post('value');
   $Approve = $this->security->xss_clean($vehicleid);
   $result = $this->vehicle_verify->reject($vehicleid);
   if($result==1){
    $this->session->set_flashdata('success', 'Vehicle Has been Rejected');
    redirect(base_url('verification/Vehicle_verificationAPI'));
  }


}else{
 $this->load->view('admin/includes/_header');
 $this->load->view('verification/vehicle_verification');
 $this->load->view('admin/includes/_footer'); 
}

}



}

