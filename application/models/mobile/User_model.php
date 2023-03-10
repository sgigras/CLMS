<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of user_model
 *
 * @author ATS-16
 */
class User_model extends CI_Model
{

    //put your code here

    // public function login($login_data)
    // {

    //     return 'inside';
    //     $db = $this->db;
    //     $login_response['user_details'] = $this->checkUser($db, $login_data);
    //     if (count($login_response['user_details'])) {
    //         $valid_user = password_verify($login_data['pin_code'], $login_response['user_details'][0]->password);
    //         if ($valid_user) {
    //             unset($login_response['user_details'][0]->password);
    //             $login_response['login_status'] = 'success';
    //             $role_id = $login_response['user_details'][0]->admin_role_id;
    //             $login_response['module_list'] = $this->fetchModule($db, $role_id);
    //         } else {
    //             $login_response['login_status'] = 'fail';

    //         }
    //     } else {
    //         $login_response['login_status'] = 'fail';
    //     }
    //     $db->close();
    //     return $login_response;
    // }

    public function mobile_login($login_data)
    {

        // return 'inside';
        $db = $this->db;
        $login_response['user_details'] = $this->checkUser($db, $login_data);
        if (count($login_response['user_details'])) {
            $valid_user = password_verify($login_data['pin_code'], $login_response['user_details'][0]->password);
            if ($valid_user) {
                unset($login_response['user_details'][0]->password);
                $login_response['login_status'] = 'success';
                $role_id = $login_response['user_details'][0]->admin_role_id;
                $rank = $login_response['user_details'][0]->rank;
                $login_response['userquota'] = $this->get_userquota($rank);
                $login_response['module_list'] = $this->fetchModule($db, $role_id);
            } else {
                $login_response['login_status'] = 'fail';
            }
        } else {
            $login_response['login_status'] = 'fail';
        }
        $db->close();
        return $login_response;
    }


    public function checkUser($db, $login_data)
    {
        $irla_no = ltrim($login_data['irl_no'], '0');
        $login_data['irl_no'] = ltrim($login_data['irl_no'], '0');
        $login_details = json_encode($login_data);
        // $db->query("Insert INTO sp_error_log_data(page_name,data_passed) values('user login','$login_details')");

        $query = "SELECT ca.admin_id,ca.admin_role_id as admin_role_id,ca.password,ca.entity_id,ca.mobile_no,
                    ca.username,CONCAT(ifnull(ca.firstname,''),' ',ifnull(ca.lastname,''))as `name`,bh.rank,ca.image
                    FROM ci_admin ca  
                    INNER JOIN bsf_hrms_data bh on ca.username=bh.irla and ca.date_of_birth=bh.date_of_birth
                    WHERE ca.username=?  AND ca.date_of_birth=?";


        $response = $db->query($query, array($irla_no, $login_data['date_of_birth']));
        $result = $response->result();
        return $result;
    }

    //    public function checkPa

    public function fetchModule($db, $role_id)
    {
        if ($role_id == 63) {
            $query = "SELECT mo.module_id,mo.module_name,sm.name as sub_module_name,mo.fa_icon,mo.sort_order,sm.id,sm.parent,sm.mobile_link
                    FROM module mo
                    INNER JOIN sub_module sm on mo.module_id=sm.parent
                    WHERE FIND_IN_SET(module_id,(SELECT group_concat(module_id) FROM module WHERE roleid=63)) 
                    AND (sm.mobile_link IS NOT NULL and sm.mobile_link!='') ORDER BY mo.module_id,mo.sort_order,sm.id,sm.sort_order";
        } else {
            $query = "SELECT mo.module_id,mo.module_name,sm.name as sub_module_name,mo.fa_icon,mo.sort_order,sm.id,sm.parent,sm.mobile_link
                    FROM module mo
                    INNER JOIN sub_module sm on mo.module_id=sm.parent
                    WHERE FIND_IN_SET(module_id,(SELECT group_concat(module_id) FROM module WHERE roleid=65)) 
                    AND (sm.mobile_link IS NOT NULL and sm.mobile_link!='') 
                    UNION
                    SELECT mo.module_id,mo.module_name,sm.name as sub_module_name,mo.fa_icon,mo.sort_order,sm.id,sm.parent,sm.mobile_link
                    FROM module mo
                    INNER JOIN sub_module sm on mo.module_id=sm.parent
                    WHERE FIND_IN_SET(module_id,(SELECT group_concat(module_id) FROM module WHERE roleid=63)) 
                    AND (sm.mobile_link IS NOT NULL and sm.mobile_link!='')";
        }
        $response = $db->query($query, array($role_id));
        $result = $response->result();
        return $result;
    }

    public function getrankid($rank)
    {

        $this->db->from('master_rank');
        $this->db->where('rank', $rank);
        $query = $this->db->get();
        $result = $query->result_array();

        return $result[0]['id'];
    }
    public function get_userquota($rank)
    {
        $rankid = $this->getrankid($rank);
        $this->db->from('liquor_rank_quota_mapping');
        $this->db->where('rankid', $rankid);
        $query = $this->db->get();
        $resultarray = $query->result_array();
        return $resultarray[0]['quota'];
    }
}
