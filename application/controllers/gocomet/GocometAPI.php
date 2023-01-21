<!-- //Author:Hriday Mourya
//Subject:Gocomet API
//Date:07-09-21 -->

<?php defined('BASEPATH') OR exit('No direct script access allowed');
class GocometAPI extends MY_Controller {

    public function __construct() {
       parent::__construct();
       $this->load->model('gocomet/Gocomet_model', 'gocomet');
   }

   public function generate_token()
   {
    /* Endpoint */
    $url = GO_COMET_TOKEN_GENERATION_URL_TEST;

    /* eCurl */
    $curl = curl_init($url);

    /* Data */
    $data = [
        'email'=>GO_COMET_TOKEN_EMAIL_TEST, 
        'password'=>GO_COMET_TOKEN_PASSWORD_TEST
    ];
    $data=json_encode($data,true);
 
    /* Set JSON data to POST */
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

    /* Define content type */
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

    /* Return json */
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    /* make request */
    $cUrl_json_object = curl_exec($curl);
  

    if($er=curl_error($curl)){
        echo $er;
    }else{
    
      $this->gocomet->ins($cUrl_json_object);
  }

  /* close curl */
  curl_close($curl);
}

}