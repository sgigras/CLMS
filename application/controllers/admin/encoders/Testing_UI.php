        <?php

        //defined('BASEPATH') OR exit('No direct script access allowed');

        class Testing_UI extends MY_Controller {

             public function index(){

                

                $this->load->view('admin/includes/_header');
                $this->load->view('admin/transporter/addtransporter');
                $this->load->view('admin/includes/_footer');

                
        }



}