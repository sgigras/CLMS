<?php

defined('BASEPATH') or exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Canteen_master_model
 *
 * @author ujwal jain
 */
class RetireeDetailsVerificationModel extends CI_Model
{

    //put your code here

    public function verify_data($userid, $entity_id)
    {
        $db = $this->db;
        $query = "select rvd.id,bhd.id as hrms_id, bhd.irla,bhd.name,if(rvd.is_verified=0,'Pending','N.A.') as verification_status,ca.firstname as requested_by,rvd.requested_time 
        from retiree_verification_details rvd 
        inner join bsf_hrms_data bhd on bhd.id=rvd.hrms_id 
        inner join ci_admin ca on ca.admin_id=rvd.requested_by 
        where rvd.is_verified=0 and rvd.entity_id=? and rvd.approval_by=? ";
        $response = $db->query($query, array($entity_id, $userid));
        $result = $response->result_array();
        $db->close();
        return $result;
    }


    public function verifyRetiree($verification_details)
    {
        $db = $this->db;
        $query = "CALL SP_VERIFY_RETIREE('$verification_details')";
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        echo json_encode($result);
    }

    public function fetchRetireeDetails($id)
    {
        $db = $this->db;
        $query = "SELECT * FROM bsf_hrms_data 
                    WHERE id=?";
        $response = $db->query($query, array($id));
        $result = $response->result();
        $db->close();
        return $result;
    }
}
