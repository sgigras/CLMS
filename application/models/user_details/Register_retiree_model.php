<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Register_retiree_model extends CI_Model
{
    public function fetchRetireeDetails($irlano)
    {
        $db = $this->db;
        $query = "SELECT bh.username as id,concat(bh.username,' - ', bh.user_rank,'. ',bh.firstname) AS name 
                  FROM ci_admin bh 
                  WHERE bh.username like '$irlano%'   AND bh.user_rank is NOT NULL ";
        $response = $db->query($query);
        $result = $response->result_array();
        $json = array();
        foreach ($result as $value) {
            $json[] = array('id' => $value['id'], 'text' => $value['name']);
        }
        $json[] = array('id' => $irlano, 'text' => $irlano);
        $db->close();
        return $json;
    }
    public function checkRetireeData($data)
    {
        $db = $this->db;
        $username = $data->perssonel_no;
        $check_user_registered_query = "select count(admin_id) as user_count,IFNULL(is_hrms_user,0) as is_hrms_user from ci_admin where username='$username'";
        $response = $db->query($check_user_registered_query);
        $result = $response->result();
        if ($result[0]->user_count == 0) {
            if ($result[0]->is_hrms_user == 0) {
                $result_array['message'] = "";
                $result_array['status'] = "success";
            } else {
                $result_array['message'] = "The given perssonel No- $username is not retired ";
                $result_array['status'] = "Fail";
            }
        } else {
            if ($result[0]->is_hrms_user == 1) {
                $result_array['message'] = "";
                $result_array['status'] = "success";
            } else {
                $result_array['message'] = "User has already been registered";
                $result_array['status'] = "Fail";
            }
        }
        $query = "select * from ci_admin where username='$username'";
        $response = $db->query($query);
        $response = $response->result();
        $result_array['user_details'] = $response;
        $db->close();
        return $result_array;
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
    public function addRetireeData($data)
    {
        $db = $this->db;
        $query = "CALL SP_ADD_UPDATE_RETIREE_DATA('{$data}')";
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result;
    }
    public function fetchInitialFormDetails()
    {
        $db = $this->db;
        $data['title'] = trans('register_retiree');
        $data['force_select_option_array'] = $this->fetchForce($db);
        $data['rank_select_option_array'] = $this->fetchRank($db);
        $data['posting_unit_select_option_array'] = $this->fetchPostingUnit($db);
        $db->close();
        return $data;
    }
    public function fetchRank($db)
    {
        $query = "select id,`rank` from master_rank";
        $response = $db->query($query);
        $result = $response->result();
        return $result;
    }
    public function fetchPostingUnit($db)
    {
        $query = "SELECT id,posting_unit FROM bsf_posting_unit";
        $response = $db->query($query);
        $result = $response->result();
        return $result;
    }
    public function fetchForce($db)
    {
        $query = "SELECT force_code, CONCAT(force_name,' (',force_code,') ') AS force_details FROM clms_force";
        $response = $db->query($query);
        $result = $response->result();
        return $result;
    }
    public function insert_retiree_details($user_data)
    {
        $db = $this->db;
        $query = "CALL SP_INSERT_USER_DATA('$user_data')";
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result;
    }
    public function fetchCanteenList()
    {
        $db = $this->db;
        $query = "SELECT me.id,me.entity_name,mt.entity_type AS canteen_club,ms.state,CONCAT(ch.firstname,' ',ch.lastname)AS chairman,
                CONCAT(cs.firstname,' ',cs.lastname) AS supervisor,CONCAT(ce.firstname,' ',ce.lastname) AS executive 
                FROM master_entities me 
                INNER JOIN master_state ms ON me.state=ms.id 
                INNER JOIN master_entity_type mt ON me.entity_type=mt.id
                INNER JOIN ci_admin cs ON cs.admin_id=me.supervisor 
                INNER JOIN ci_admin ch ON ch.admin_id=me.chairman 
                INNER JOIN ci_admin ce ON ce.admin_id=me.executive
                where mt.entity_type NOT IN ('Brewery','consumer')";
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result;
    }
    public function fetchInitialEntityFormDetails()
    {
        $db = $this->db;
        $data['title'] = trans('add_new_canteen');
        $data['mode'] = 'A';
        $data['state_record'] = $this->fetchState($db);
        $data['outlet_type_select_option_array'] = $this->fetchEntities($db);
        $data['distributor_authority_record'] = $this->fetchDistributorAuthority($db);
        $data['user_details'] = $this->fetchUserDetails($db);
        $db->close();
        return $data;
    }
    public function fetchState($db)
    {
        $query = "Select id,state from master_state";
        $response = $db->query($query);
        $result = $response->result();
        return $result;
    }
    public function fetchCities($state_id)
    {
        $db = $this->db;
        $query = "SELECT id,city_district_name from master_city_district where stateid=?";
        $response = $db->query($query, array($state_id));
        $result = $response->result();
        $db->close();
        return $result;
    }
    public function fetchUserDetails($db)
    {
        $query = "SELECT ca.admin_id AS id,ca.username,concat( bh.rank,'. ',ca.firstname,' ',ca.lastname) AS name
                  FROM ci_admin ca 
                  INNER JOIN bsf_hrms_data bh ON bh.irla=ca.username
                  WHERE ca.firstname IS NOT NULL AND ca.lastname IS NOT NULL AND bh.rank is NOT NULL";
        $response = $db->query($query);
        $result = $response->result();
        return $result;
    }
    public function fetchDistributorAuthority($db)
    {
        $query = "Select id,entity_type AS distributor_authority FROM master_entity_type WHERE entity_type NOT IN ('consumer','club')";
        $response = $db->query($query);
        $result = $response->result();
        return $result;
    }
    public function fetchEntities($db)
    {
        $query = "SELECT id,entity_type from master_entity_type where entity_type NOT IN ('Brewery','Consumer')";
        $response = $db->query($query);
        $result = $response->result();
        return $result;
    }
    public function insert_canteen_details($data)
    {
        $db = $this->db;
        $response = $db->query("CALL SP_INSERT_UPDATE_CANTEEN_DETAILS(?)", array($data));
        $db->close();
        return $response->result();
    }
    public function update_canteen_details($data)
    {
        $db = $this->db;
        $query = "CALL SP_INSERT_UPDATE_CANTEEN_DETAILS('$data')";
        $response = $db->query("CALL SP_INSERT_UPDATE_CANTEEN_DETAILS(?)", array($data));
        $db->close();
        return $response->result();
    }
    public function fetchEntityDetails($id)
    {
        $db = $this->db;
        $entity_query = "SELECT me.id,me.entity_name,me.address,me.city,me.state,me.chairman,me.supervisor,me.executive,
                        me.authorised_distributor,
                        me.authorised_distributor_entity_type_id as store_id,me.entity_type from 
                        master_entities me 
                        INNER JOIN master_entity_type md on me.authorised_distributor_entity_type_id=md.id 
                        where me.id=?";
        $entity_response = $db->query($entity_query, array($id));
        $entity_result = $entity_response->result();
        $data['canteen_club_data'] = $entity_result;
        $store_id = $entity_result[0]->store_id;
        $state_id = $entity_result[0]->state;
        $city_list_query = "SELECT id,city_district_name FROM master_city_district WHERE stateid=?";
        $city_list_response = $db->query($city_list_query, array($state_id));
        $data['city_list'] = $city_list_response->result();
        $distributor_name_query = "SELECT id,entity_name as store_name from master_entities where id=?";
        $distributor_name_response = $db->query($distributor_name_query, array($store_id));
        $data['distributor_name_list'] = $distributor_name_response->result();
        $data['title'] = trans('edit_new_canteen');
        $data['mode'] = 'E';
        $data['state_record'] = $this->fetchState($db);
        $data['distributor_authority_record'] = $this->fetchDistributorAuthority($db);
        $data['user_details'] = $this->fetchUserDetails($db);
        $data['outlet_type_select_option_array'] = $this->fetchEntities($db);
        $db->close();
        return $data;
    }
}
