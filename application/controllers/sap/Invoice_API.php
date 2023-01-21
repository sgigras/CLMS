        <?php

        //defined('BASEPATH') OR exit('No direct script access allowed');

        class Invoice_API extends CI_Controller {


          function __construct(){

                parent::__construct();

                $this->load->model('sap/Invoice_model','invoice_model');
        }

        function index(){

                $this->genrateToken();

        }




        function genrateToken(){


         // Takes raw data from the request
                $json = file_get_contents('php://input');
        // Converts it into a PHP object
                $user_credentials =  json_decode($json, TRUE);
        // Check if username & password present in JSON
                $result= $this->Exists($user_credentials);
        // Check if username & password valid or not
                if($result){

                        $is_credentials_valid=$this->validate_user_credentials($user_credentials);
                }
                $tokendata=array();
                if($is_credentials_valid){
                        date_default_timezone_set("Asia/Calcutta"); 
                        $message="Token Generated Successfully";
                        $token=  bin2hex(random_bytes(64));
                        $current_time=date('Y-m-d H:i:s');
                        $expires_at= date('Y-m-d H:i:s' ,strtotime('+30 days',strtotime($current_time)));
                        $status="success";

                        $tokendata['message']= $message;
                        $tokendata['token']=$token;
                        $tokendata['expires_at']=$expires_at;
                        $tokendata['status']=$status;

                        // storing token information to database

                        $data=array(
                                "token_desc"=>"Invoice API new token",
                                "token"=>$token,
                                "creation_time"=>$current_time,
                                "token_expires_at"=>$expires_at

                        );
                        $this->invoice_model->saveTokenDetails($data);

                        echo json_encode($tokendata);

                }else{
                        // Invalid user cred
                        $message="Unknown Error-2";
                        $status="Fail";

                        $tokendata['message']=$message;
                        $tokendata['status']=$status;

                        echo json_encode($tokendata);

                }

        }

        function Exists($user_credentials)
        {
           $index_array=array("username", "password");
           $data=array();

           for($i=0; $i<sizeof($index_array); $i++){
                $index= $index_array[$i];

                if(empty($user_credentials)){
                      $data['message']= "Unknown error-1";
                      $data['status'] ="failure";

                      echo json_encode($data);

                      exit();  
              }

              if (! array_key_exists($index, $user_credentials)){
                 $data['message']= "Unknown error-1";
                 $data['status'] ="failure";

                 echo json_encode($data);

                 exit();
         }
 }

 return TRUE;
}


function validate_user_credentials($user_credentials){
        $username=$user_credentials['username'];
        $password=$user_credentials['password'];

        $sap_username= SAP_USERNAME;
        $sap_password= SAP_PASSWORD;

        return ($username==$sap_username && $password==$sap_password)? true: false;

}

function validate_invoice_details($invoice_details, $mode){

       $token_number= $invoice_details['token'];
       $response = array();
// check if token is valid or not
       $is_token_valid=$this->invoice_model->checkToken($token_number);

       if(! $is_token_valid){
        $response['message']="Invalid Token";
        $response['status']="Fail";
                //echo $ip = $_SERVER['REMOTE_ADDR'];
        echo json_encode($response); 
        exit();
}
// check invoice is empty or not
if(empty($invoice_details['invoice_no']) || $invoice_details['invoice_no'] == -1){
  $response['message']="Validation Failed: Invoice No can't be blank";
  $response['status']="Fail";
                //echo $ip = $_SERVER['REMOTE_ADDR'];
  echo json_encode($response); 
  exit();  
}

//check transaction id is empty or not
if($mode=='update' && $invoice_details['transaction_code']==''){
  $response['message']="Validation Failed: Transaction Id can't be blank";
  $response['status']="Fail". "Transaction ID". $invoice_details['transaction_code'];
  
                //echo $ip = $_SERVER['REMOTE_ADDR'];
  echo json_encode($response); 
  exit();  
}

//Cheking transaction id is valid or not  
if($mode=='update'){
        $transaction_id=$invoice_details['transaction_code'];
        $is_transaction_id_valid=$this->invoice_model->checkTransaction_id($transaction_id);
        if(! $is_transaction_id_valid){
                $response['message']="Invalid Transaction Id";
                $response['status']="Fail";
                //echo $ip = $_SERVER['REMOTE_ADDR'];
                echo json_encode($response); 
                exit();
        }
}

}

function pushInvoiceData(){

       // Takes raw data from the request
        $request_json = file_get_contents('php://input');
        $invoice_details =  json_decode($request_json, TRUE);
        // performing validation on invoice detail
        $this->validate_invoice_details($invoice_details,'create');

        $vehicleno=($invoice_details['vehicle_no']!= -1)? $invoice_details['vehicle_no']: NULL;
        $transporter_code=($invoice_details['transporter_code']!= -1)?$invoice_details['transporter_code']: NULL ;
        $transporter_name=($invoice_details['transporter_name']!= -1)? $invoice_details['transporter_name']: NULL ;
        $invoice_no=$invoice_details['invoice_no'];
        $invoice_date=date("Y-m-d", strtotime($invoice_details['invoice_date']));
        // Product details array 
        $product_details=$invoice_details['Product_details'];
        $sold_to_code=($invoice_details['sold_to_code']!= -1)? $invoice_details['sold_to_code']: NULL ;
        $ship_to_address=($invoice_details['ship_to_address']!= -1)? $invoice_details['ship_to_address']: NULL ;
        $ship_to_city=($invoice_details['ship_to_city']!= -1)? $invoice_details['ship_to_city']: NULL ;
        $ship_to_state=($invoice_details['ship_to_state']!= -1)? $invoice_details['ship_to_state']: NULL ;
        $pincode=($invoice_details['pincode']!= -1)? $invoice_details['pincode']: NULL ;
        $source_plant=($invoice_details['source_plant']!= -1)? $invoice_details['source_plant']: NULL ;
        $load_type=($invoice_details['load_type']!= -1)? $invoice_details['load_type']: NULL ;
        $ship_to_party_email_address=($invoice_details['ship_to_party_email_address']!= -1)? $invoice_details['ship_to_party_email_address']: NULL ;
        $ship_to_party_phone_number=($invoice_details['ship_to_party_phone_number']!= -1)? $invoice_details['ship_to_party_phone_number']: NULL ;
        $ship_to_party_email_address_2=($invoice_details['ship_to_party_email_address_2']!= -1)? $invoice_details['ship_to_party_email_address_2']: NULL ;
        $ship_to_party_phone_number_2=($invoice_details['ship_to_party_phone_number_2']!= -1)? $invoice_details['ship_to_party_phone_number_2']: NULL ;
        $seq_number=($invoice_details['seq_number']!= -1)? $invoice_details['seq_number']: NULL ;
        $single_multi_load=($invoice_details['single_multi_load']!= -1)? $invoice_details['single_multi_load']: NULL ;
        $e_way_no=($invoice_details['e_way_no']!= -1)? $invoice_details['e_way_no']: NULL ;
        $e_way_bill_expiry_date=date("Y-m-d", strtotime($invoice_details['e_way_bill_expiry_date']));
        $accounting_release_datetime=date("Y-m-d", strtotime($invoice_details['accounting_release_datetime']));
        $transaction_id = "ACG".date('YmdHis').rand();
       
        $invoice_details=array(
         'invoice_no'=>$invoice_no,
         'vehicle_no'=>$vehicleno,
         'transporter_code'=>$transporter_code,
         'transporter_name'=>$transporter_name,
         'invoice_date'=>$invoice_date,
         'customer_vendor_code'=>$sold_to_code,
         'address'=>$ship_to_address,
         'city'=>$ship_to_city,
         'state'=>$ship_to_state,
         'source_plant_code'=>$source_plant,
         'ship_to_party_email_address'=>$ship_to_party_email_address,
         'ship_to_party_phone_number'=>$ship_to_party_phone_number,
         'ship_to_party_email_address_2'=>$ship_to_party_email_address_2,
         'ship_to_party_phone_number_2'=>$ship_to_party_phone_number_2,
         'single_multi_load'=>$single_multi_load,
         'e_way_no'=>$e_way_no,
         'accounting_release_datetime'=>$accounting_release_datetime,
         'e_way_bill_expiry_date'=>$e_way_bill_expiry_date,
         'transaction_id'=>$transaction_id
 );
        $response=$this->invoice_model->addInvoice($invoice_details,$product_details);
        echo json_encode($response);
}

function updateInvoiceData(){

               // Takes raw data from the request
        $request_json = file_get_contents('php://input');
        $invoice_details =  json_decode($request_json, TRUE);
        $token_number= $invoice_details['token'];
        
        $this->validate_invoice_details($invoice_details,'update');
        $transaction_id=$invoice_details['transaction_code'];
         $vehicleno=($invoice_details['vehicle_no']!= -1)? $invoice_details['vehicle_no']: NULL;
        $transporter_code=($invoice_details['transporter_code']!= -1)?$invoice_details['transporter_code']: NULL ;
        $transporter_name=($invoice_details['transporter_name']!= -1)? $invoice_details['transporter_name']: NULL ;
        $invoice_no=$invoice_details['invoice_no'];
        $invoice_date=date("Y-m-d", strtotime($invoice_details['invoice_date']));
        // Product details array 
        $product_details=$invoice_details['Product_details'];
        $sold_to_code=($invoice_details['sold_to_code']!= -1)? $invoice_details['sold_to_code']: NULL ;
        $ship_to_address=($invoice_details['ship_to_address']!= -1)? $invoice_details['ship_to_address']: NULL ;
        $ship_to_city=($invoice_details['ship_to_city']!= -1)? $invoice_details['ship_to_city']: NULL ;
        $ship_to_state=($invoice_details['ship_to_state']!= -1)? $invoice_details['ship_to_state']: NULL ;
        $pincode=($invoice_details['pincode']!= -1)? $invoice_details['pincode']: NULL ;
        $source_plant=($invoice_details['source_plant']!= -1)? $invoice_details['source_plant']: NULL ;
        $load_type=($invoice_details['load_type']!= -1)? $invoice_details['load_type']: NULL ;
        $ship_to_party_email_address=($invoice_details['ship_to_party_email_address']!= -1)? $invoice_details['ship_to_party_email_address']: NULL ;
        $ship_to_party_phone_number=($invoice_details['ship_to_party_phone_number']!= -1)? $invoice_details['ship_to_party_phone_number']: NULL ;
        $ship_to_party_email_address_2=($invoice_details['ship_to_party_email_address_2']!= -1)? $invoice_details['ship_to_party_email_address_2']: NULL ;
        $ship_to_party_phone_number_2=($invoice_details['ship_to_party_phone_number_2']!= -1)? $invoice_details['ship_to_party_phone_number_2']: NULL ;
        $seq_number=($invoice_details['seq_number']!= -1)? $invoice_details['seq_number']: NULL ;
        $single_multi_load=($invoice_details['single_multi_load']!= -1)? $invoice_details['single_multi_load']: NULL ;
        $e_way_no=($invoice_details['e_way_no']!= -1)? $invoice_details['e_way_no']: NULL ;
        $e_way_bill_expiry_date=date("Y-m-d", strtotime($invoice_details['e_way_bill_expiry_date']));
        $accounting_release_datetime=date("Y-m-d", strtotime($invoice_details['accounting_release_datetime']));

        $invoice_details=array(
         'invoice_no'=>$invoice_no,
         'vehicle_no'=>$vehicleno,
         'transporter_code'=>$transporter_code,
         'transporter_name'=>$transporter_name,
         'invoice_date'=>$invoice_date,
         'customer_vendor_code'=>$sold_to_code,
         'address'=>$ship_to_address,
         'city'=>$ship_to_city,
         'state'=>$ship_to_state,
         'source_plant_code'=>$source_plant,
         'ship_to_party_email_address'=>$ship_to_party_email_address,
         'ship_to_party_phone_number'=>$ship_to_party_phone_number,
         'ship_to_party_email_address_2'=>$ship_to_party_email_address_2,
         'ship_to_party_phone_number_2'=>$ship_to_party_phone_number_2,
         'single_multi_load'=>$single_multi_load,
         'e_way_no'=>$e_way_no,
         'accounting_release_datetime'=>$accounting_release_datetime,
         'e_way_bill_expiry_date'=>$e_way_bill_expiry_date

 );
        $response=$this->invoice_model->updateInvoice($invoice_details,$product_details,$transaction_id);

        echo json_encode($response);
}









}