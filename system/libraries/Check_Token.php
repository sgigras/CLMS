<?php
class CI_Check_Token 

{
protected $CI;

function __construct()

 {

 $this->CI =& get_instance();

 }



 public function Check_Token()
 {
 $db = $this->CI->db;

 $username=isset($_SESSION['username']);

 if ($username) { 
$username=$_SESSION['username'];
 $query ="SELECT token FROM user_token where username='$username'";
 $query_response=$db->query($query);
$count= $query_response->num_rows();


 if ($count > 0) {
$result=$query_response->result();

$token=$result[0]->token;

 if($_SESSION['token'] != $token){
 session_destroy();
 $db->close();
 redirect(base_url(), 'refresh');
}
 }
}

 }



}

?>