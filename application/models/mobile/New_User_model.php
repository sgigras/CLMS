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
class New_User_model extends CI_Model
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
    public function checkDeviceRegistered($data)
    {
        $db = $this->db;
        $details = json_encode($data);
        $query1 = "INSERT mobile_login_details(data_passed,action_mode)values('$details','check_device')";
        $db->query($query1);
        $query = "SELECT username,date_of_birth FROM ci_admin WHERE android_uuid=?";

        $response = $db->query($query, array($data->android_id));
        $result = $response->result();
        $db->close();
        return $result;
    }

    public function checkUserRegistered($date_of_birth, $irla)
    {
        $db = $this->db;
        $query = "Select count(admin_id) as user_count FROM ci_admin where date_of_birth=? and username=?";
        $response = $db->query($query, array($date_of_birth, $irla));
        $result = $response->result();
        $db->close();
        return $result;
    }

    public function mobile_login($login_data)
    {

        // return 'inside';
        $db = $this->db;

        $login_response['user_details'] = $this->checkUser($db, $login_data);

        if (count($login_response['user_details'])) {

            if ($login_response['user_details'][0]->android_uuid == $login_data['android_id']) {
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
                    $login_response['login_message'] = 'Invalid Credentials!';
                    $login_response['login_fail_type'] = 'invalid_credentails';

                    $login_response['module_list'] = array();
                }
            } else {
                $login_response['login_status'] = 'fail';
                $login_response['login_message'] = 'Irla/Registration no is mapped with different mobile !';
                $login_response['login_fail_type'] = 'invalid_mobile';

                $login_response['module_list'] = array();
            }
        } else {
            $login_response['login_status'] = 'fail';
            $login_response['login_message'] = 'Invalid Credentials';
            $login_response['login_fail_type'] = 'invalid_mobile';
            $login_response['module_list'] = array();

            // if()
        }

        $db->close();
        return $login_response;
    }



    public function checkUser($db, $login_data)
    {

        $login_details = json_encode($login_data);
        $insert_details = "INSERT INTO mobile_login_details(data_passed)values('$login_details')";

        $db->query($insert_details);

        $fetch_android_query = "SELECT IFNULL(ca.android_uuid,'') as android_uuid,ca.admin_id
                            FROM ci_admin ca  
                            WHERE ca.username=?  AND ca.date_of_birth=? order by ca.admin_id limit 1";

        $fetch_android_response = $db->query($fetch_android_query, array($login_data['irl_no'], $login_data['date_of_birth']));

        $fetch_android_response = $fetch_android_response->result();

        if ($fetch_android_response[0]->android_uuid == '') {
            $update_query = "UPDATE ci_admin SET android_uuid=? WHERE admin_id=?";
            $db->query($update_query, array($login_data['android_id'], $fetch_android_response[0]->admin_id));
        }



        $query = "SELECT ca.admin_id,ca.admin_role_id as admin_role_id,ca.password,ca.entity_id,ca.mobile_no,
                    ca.username,CONCAT(ifnull(ca.firstname,''),' ',ifnull(ca.lastname,''))as `name`,ca.user_rank as rank,ca.image,ca.email,ca.android_uuid   
                    FROM ci_admin ca  
                    WHERE ca.username=?  AND ca.date_of_birth=?";
        $response = $db->query($query, array($login_data['irl_no'], $login_data['date_of_birth']));



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
                    AND (sm.mobile_link IS NOT NULL and sm.mobile_link!='') order by sort_order";
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

    public function fetchUserDetails($user_id)
    {
        $db = $this->db;
        $query = "Select * from where admin_id=?";
        $response = $db->query($query, array($user_id));
        $result = $response->result_array();
        $db->close();
        return $result;
    }

    public function update_profile_data($profile_data)
    {
        $db = $this->db;
        $query = "UPDATE  ci_admin SET mobile_no=?,email=?,image=?  WHERE admin_id=?";
        $response = $db->query($query, array($profile_data->mobile_no, $profile_data->email, $profile_data->image_src, $profile_data->user_id));
        // $result = $response->result_array();
        $db->close();
        return $response;
    }

    public function UploadPics($folder, $file)
    {

        $folder = '/var/www/html/uploads/' . $folder . "/";
        $responseArray = array();

        //         $destination_path = getcwd().DIRECTORY_SEPARATOR;
        // $target_path = $destination_path . basename( $_FILES["profpic"]["name"]);
        // @move_uploaded_file($_FILES['profpic']['tmp_name'], $target_path)
        // $year = date("Y");
        // $month = date("m");
        // $day = date("d");
        // $date_folder = $year . $month . $day;
        // $folder .= $date_folder . '/';
        // if (!is_dir($folder)) {
        //     mkdir($folder, 0777, true);
        //     chmod($folder, 0755);
        // }
        $file = $folder . $file;
        if (move_uploaded_file($_FILES['file']['tmp_name'], $file)) {
            $responseArray = array("status" => 1, "data" => array(), "msg" => "Upload Successfully");
        } else {
            $responseArray = array("status" => 0, "data" => array(), "msg" => "Upload Failed");
        }

        return $responseArray;
    }
}
