<!-- //Author:Harish Manoharan
//Subject:Email Model
//Date:21-10-21 -->

<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Email_model extends CI_Model{

	public function fetchMailToSend() {
		$db = $this->db;
		$query = "Select id,receiver_mailid,subject,msg_body,cc_receiver_mailid,IFNULL(attachment_path,'0') AS attachment_path from alerts_log where is_alert_sent='n' order by insert_time desc limit 10";
		$response = $db->query($query);
		$data = $response->result();
      

		for ($i = 0; $i < count($data); $i++) {
			//$result = sendEmail($data[$i]->subject, $data[$i]->msg_body, $data[$i]->receiver_mailid, $data[$i]->cc_receiver_mailid, $data[$i]->attachment_path);
			$result = sendEmail_BSF($data[$i]->subject, $data[$i]->msg_body, $data[$i]->receiver_mailid, $data[$i]->cc_receiver_mailid, $data[$i]->attachment_path);
			if ($result) {
				$db->query("Update alerts_log SET is_alert_sent='y',is_alert_delivered='y' where id='{$data[$i]->id}'");
			} else {
				$db->query("Update alerts_log SET is_alert_sent='y',is_alert_delivered='n' where id='{$data[$i]->id}'");
			}
		}
		$db->close();
	}
}

?>