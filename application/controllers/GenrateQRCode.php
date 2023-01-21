<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Qrcode Generation for install device 
 *
 * @author Reshma Fasale
 */
class GenrateQRCode extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper(array('file'));
        $this->load->library(array('session','Mobileapp/Available_vehiclelib','Mobileapp/QR_BarCode'));
    }
    
      public function index() {
            $this->load->view('mobileapi/scanqr');
       }

     public function generate() {
     
        $device_id = $this->input->post('deviceid');
        $dlist = explode (",", $device_id); 
        for($i=0;$i<count($dlist);$i++){
          $device = $dlist[$i];  
              $string_path = array();
         // foreach ($device_id as $dlist) {
            $path = "uploads/QRcodes/$device.png";
            array_push($string_path, $path);
            // array_push($string_path, $device);
            $string = $device;
            $string_data = $this->QREncryption($string);
        //     // create text QR code 
           $this->qr_barcode->text($string_data);
        // // save QR code image
            $result[] = $this->qr_barcode->qrCode(200, "uploads/QRcodes/$device.png",$device);

        }
         echo json_encode($result); 
        
    }

    public function QREncryption($string) {
        $output = false;
        $enc_method = "aes-256-cbc";
        $sec_key = SECRET_KEY;
        $sec_iv = SECRET_IV;
        $enc_keys = md5($sec_key);
        $key = hash('sha256', $enc_keys);
        $iv = substr(hash('sha256', $sec_iv), 0, 16);
        $output = openssl_encrypt($string, $enc_method, $key, 0, $iv);
        $output = base64_encode($output);
        return $output;
    }

      public function get_device_list(){
           $response=$this->available_vehiclelib->get_device_list();
            echo json_encode($response);
      }
}
