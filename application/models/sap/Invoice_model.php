<?php 

//defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice_model extends CI_Model{

 //put your code here
 protected $CI;

 function __construct()
 {
  $this->CI = &get_instance();
}


function saveTokenDetails($data)
{
  $this->db->insert('ci_jwt_tokens', $data);
  return true;

}

function apiTestingData($apiData){
  $this->db->insert('ci_api_testing_data', $apiData);
  return true;
}

function checkToken($token_number){
 $db = $this->db;
 $query = "SELECT EXISTS (SELECT id FROM ci_jwt_tokens WHERE token_expires_at > NOW() AND  token=".$this->db->escape($token_number).") AS TokenCount";
 $response = $db->query($query);
 $tokendata=$response->result();
 return ($tokendata[0]->TokenCount>0)?true:false;
}

function checkTransaction_id($transaction_id){
 $db = $this->db;
 $query = "SELECT EXISTS (SELECT invoice_id FROM acg_invoice_details WHERE transaction_id=".$this->db->escape($transaction_id).") AS Transaction_id_count";
 $response = $db->query($query);
 $transaction_id=$response->result();
 return ($transaction_id[0]->Transaction_id_count>0)?true:false;
}


function addInvoice_old($invoice_details, $product_details)
{


//Adding invoice details in acg_invoice_details table
  $result=$this->db->insert('acg_invoice_details', $invoice_details);

  if($result){
    $invoice_no=$invoice_details['invoice_no'];
    $db = $this->db;
    $query = "SELECT invoice_id ,transaction_id from acg_invoice_details WHERE invoice_no=".$this->db->escape($invoice_no)." ";
    $response = $db->query($query);
    $invoice=$response->result();
    $invoice_id=$invoice[0]->invoice_id;
    $transaction_id=$invoice[0]->transaction_id;

//Adding product details to acg_invoice_product_details against invoice details

    foreach($product_details as $product){

      $product_det= array(
        'product_code' => $product['product_code'], 
        'invoice_id' =>  $invoice_id, 
        'line_item' => $product['line_item'],
        'product_description' => $product['product_description'],
        'requested_delivery_date' => $product['requested_delivery_date'],
        'no_of_boxes'=>$product['no_of_boxes']

      );
      $result=$this->db->insert('acg_invoice_product_details', $product_det);
    }
    if($result){

      $response_array=array(
        "message"=>"Invoice Data Received Successfully",
        "invoice_number"=>$invoice_no,
        "transaction_id"=>$transaction_id,
        "status"=>"success"
      );

    }else{
      // If product details against invoice not saved
      $response_array=array(
        "message"=>"Unknown Error",
        "status"=>"Fail"
      );
    }

  }else{
          // If invoice details not saved
    $response_array=array(
      "message"=>"Unknown Error",
      "status"=>"Fail"
    );
  }
  return $response_array;
}

function updateInvoice_old($invoice_details, $product_details, $transaction_id)
{
//Updating invoice details in acg_invoice_details table
  $this->db->where('transaction_id', $transaction_id);
  $result=$this->db->update('acg_invoice_details',$invoice_details);

  if($result){
    $invoice_no=$invoice_details['invoice_no'];
    $db = $this->db;
    $query = "SELECT invoice_id ,transaction_id from acg_invoice_details WHERE invoice_no=".$this->db->escape($invoice_no)." ";
    $response = $db->query($query);
    $invoice=$response->result();
    $invoice_id=$invoice[0]->invoice_id;
    $transaction_id=$invoice[0]->transaction_id;

// Deleting Existing product details against invoice id
    $this->db->where('invoice_id', $invoice_id);
    $result=$this->db->delete('acg_invoice_product_details');


//Updating product details to acg_invoice_product_details against invoice details
    foreach($product_details as $product){

      $product_det= array(
        'product_code' => $product['product_code'], 
        'invoice_id' =>  $invoice_id, 
        'line_item' => $product['line_item'],
        'product_description' => $product['product_description'],
        'requested_delivery_date' => $product['requested_delivery_date'],
        'no_of_boxes'=>$product['no_of_boxes']

      );
      $result=$this->db->insert('acg_invoice_product_details', $product_det);

    }
    if($result){

      $response_array=array(
        "message"=>"Invoice Data Updated Successfully",
        "invoice_number"=>$invoice_no,
        "transaction_id"=>$transaction_id,
        "status"=>"success"
      );

    }else{
      // If product details against invoice not saved
      $response_array=array(
        "message"=>"Unknown Error",
        "status"=>"Fail"
      );
    }

  }else{
          // If invoice details not saved
    $response_array=array(
      "message"=>"Unknown Error",
      "status"=>"Fail"
    );
  }
  return $response_array;
}



function addInvoice($invoice_details, $product_details)
{


//Adding invoice details in acg_invoice_details table
  $result=$this->db->insert('acg_invoice_details', $invoice_details);

  if($result){
    $invoice_no=$invoice_details['invoice_no'];
    $db = $this->db;
    $query = "SELECT invoice_id ,transaction_id from acg_invoice_details WHERE invoice_no=".$this->db->escape($invoice_no)." ";
    $response = $db->query($query);
    $invoice=$response->result();
    $invoice_id=$invoice[0]->invoice_id;
    $transaction_id=$invoice[0]->transaction_id;

//Adding product details to acg_invoice_product_details against invoice details

    $product_count= (isset($product_details[0]))? "multiple products": "single product";

    if($product_count=="multiple products"){
      foreach($product_details as $product){

        $product_det= array(
          'product_code' => $product['product_code'], 
          'invoice_id' =>  $invoice_id, 
          'line_item' => $product['line_item'],
          'product_description' => $product['product_description'],
          'requested_delivery_date' => $product['requested_delivery_date'],
          'no_of_boxes'=>$product['no_of_boxes']

        );
        $result=$this->db->insert('acg_invoice_product_details', $product_det);
      }
    }else{
      $product_details['invoice_id']=$invoice_id;
      $result=$this->db->insert('acg_invoice_product_details', $product_details);
    }

    


    if($result){

      $response_array=array(
        "message"=>"Invoice Data Received Successfully",
        "invoice_number"=>$invoice_no,
        "transaction_id"=>$transaction_id,
        "status"=>"success"
      );

    }else{
      // If product details against invoice not saved
      $response_array=array(
        "message"=>"Unknown Error",
        "status"=>"Fail"
      );
    }

  }else{
          // If invoice details not saved
    $response_array=array(
      "message"=>"Unknown Error",
      "status"=>"Fail"
    );
  }
  return $response_array;
}


function updateInvoice($invoice_details, $product_details, $transaction_id)
{
//Updating invoice details in acg_invoice_details table
  $this->db->where('transaction_id', $transaction_id);
  $result=$this->db->update('acg_invoice_details',$invoice_details);

  if($result){
    $invoice_no=$invoice_details['invoice_no'];
    $db = $this->db;
    $query = "SELECT invoice_id ,transaction_id from acg_invoice_details WHERE invoice_no=".$this->db->escape($invoice_no)." ";
    $response = $db->query($query);
    $invoice=$response->result();
    $invoice_id=$invoice[0]->invoice_id;
    $transaction_id=$invoice[0]->transaction_id;

// Deleting Existing product details against invoice id
    $this->db->where('invoice_id', $invoice_id);
    $result=$this->db->delete('acg_invoice_product_details');


//Updating product details to acg_invoice_product_details against invoice details
    $product_count= (isset($product_details[0]))? "multiple products": "single product";

    if($product_count=="multiple products"){
      foreach($product_details as $product){

        $product_det= array(
          'product_code' => $product['product_code'], 
          'invoice_id' =>  $invoice_id, 
          'line_item' => $product['line_item'],
          'product_description' => $product['product_description'],
          'requested_delivery_date' => $product['requested_delivery_date'],
          'no_of_boxes'=>$product['no_of_boxes']

        );
        $result=$this->db->insert('acg_invoice_product_details', $product_det);
      }
    }else{
      $product_details['invoice_id']=$invoice_id;
      $result=$this->db->insert('acg_invoice_product_details', $product_details);
    }
    if($result){

      $response_array=array(
        "message"=>"Invoice Data Updated Successfully",
        "invoice_number"=>$invoice_no,
        "transaction_id"=>$transaction_id,
        "status"=>"success"
      );

    }else{
      // If product details against invoice not saved
      $response_array=array(
        "message"=>"Unknown Error",
        "status"=>"Fail"
      );
    }

  }else{
          // If invoice details not saved
    $response_array=array(
      "message"=>"Unknown Error",
      "status"=>"Fail"
    );
  }
  return $response_array;
}



}

?>