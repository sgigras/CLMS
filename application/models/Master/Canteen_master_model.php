<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Canteen_master_model extends CI_Model
{
    public function fetchCanteenList()
    {
        $db = $this->db;
            $query = "SELECT 
                me.id,
                me.entity_name,
                IFNULL(mt.entity_type,'N/A') AS canteen_club,
                FN_GET_USER_WITH_RANK_AND_IRLA(me.supervisor) supervisor,
                FN_GET_USER_WITH_RANK_AND_IRLA(me.chairman) chairman,
                FN_GET_USER_WITH_RANK_AND_IRLA(me.executive) executive,
                FN_GET_STATENAME(me.state) state,
                FN_GET_UNITNAME(me.battalion_unit) battalion_unit
            FROM
                master_entities me
                left JOIN master_entity_type mt ON me.entity_type = mt.id and me.entity_type in (1,2)";
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result;
    }
    public function fetchCanteenListForChairman($chairmanid)
    {
        $db = $this->db;
        $query = "SELECT me.id,me.entity_name,mt.entity_type AS canteen_club,ms.state,ch.firstname AS chairman,
                cs.firstname AS supervisor,ce.firstname AS executive
                FROM master_entities me 
                INNER JOIN master_state ms ON me.state=ms.id 
                INNER JOIN master_entity_type mt ON me.entity_type=mt.id
                INNER JOIN ci_admin cs ON cs.admin_id=me.supervisor 
                INNER JOIN ci_admin ch ON ch.admin_id=me.chairman 
                INNER JOIN ci_admin ce ON ce.admin_id=me.executive
                where mt.entity_type NOT IN ('Brewery','consumer') AND me.chairman='$chairmanid'";
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result;
    }
    public function fetchInitialEntityFormDetails($userid='')
    {
        $db = $this->db;
        $data['title'] = trans('add_new_canteen');
        $data['mode'] = 'A';
        $data['state_record'] = $this->fetchState($db);
        $data['outlet_type_select_option_array'] = $this->fetchEntities($db);
        $data['battalion_unit_select_option_array'] = $this->battalionfetchEntities($db);
        $data['distributor_authority_record'] = $this->fetchDistributorAuthority($db);
        $userdetail = $this->fetchUserDetails($db,$userid);
        if ($userid == '')
        {
            $data['user_details'] = $userdetail;
        }
        else
        {
            $data['user_details'] = $userdetail[0];
        }
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
        $query = "SELECT id,city_district_name from master_city_district where stateid='{$state_id}'";
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result;
    }
    public function fetchUserDetails($db, $userid = '')
    {
        if ($userid != '') {
            $query = "select
                    *
                from
                    master_entities as m1
                where id='{$userid}'";
            $response = $db->query($query);
            $result = $response->result();
        } else {
            $response = array("id" => "", "username" => "", "name" => "");
            $result = (object)$response;
        }
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
    public function fetchUserList()
    {
        $db = $this->db;
        $query = "SELECT 
            admin_id,
            CONCAT(username,' (',IFNULL(user_rank,'N/A'),') ',IFNULL(firstname,'N/A'),' - ',IFNULL(mobile_no,'N/A')) as name
        from ci_admin 
        where is_active='1'";
        $response = $db->query($query);
        $result = $response->result_array();
        return $result;
    }
    public function battalionfetchEntities($db)
    {
        $db = $this->db;
        $query = "SELECT id,UnitName posting_unit from itbp_posting_unit";
        $response = $db->query($query);
        $result = $response->result();
        return $result;
    }
    public function fetchDistrubutors($distrubtor_authority)
    {
        $db = $this->db;
        $query = "Select id,entity_name as name from master_entities where entity_type='$distrubtor_authority'";
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result;
    }
    public function insert_canteen_details($data)
    {
        $db = $this->db;
        $query = "CALL SP_INSERT_UPDATE_CANTEEN_DETAILS('{$data}')";
        $response = $db->query($query);
        $db->close();
        return $response->result();
    }
    public function update_canteen_details($data)
    {
        $db = $this->db;
        $query = "CALL SP_INSERT_UPDATE_CANTEEN_DETAILS('".$data."')";
        echo $query;
        die;
        $response = $db->query($query);
        $db->close();
        return $response->result();
    }
    public function update_chairman_canteen_details($data)
    {
        $db = $this->db;
        $response = $db->query("CALL SP_UPDATE_CHAIRMAN_CANTEEN_DETAILS(?)", array($data));
        $db->close();
        return $response->result();
    }
    public function fetchEntityDetails($id)
    {
        $db = $this->db;
        $entity_query = "SELECT me.id,me.entity_name,me.address,me.city,me.state,me.chairman,me.supervisor,me.executive,me.authorised_distributor as store_id,
        me.authorised_distributor_entity_type_id as authorised_distributor,me.entity_type 
                        from master_entities me 
                        where me.id=?";
        $entity_response = $db->query($entity_query, array($id));
        $entity_result = $entity_response->result();
        $data['canteen_club_data'] = $entity_result;
        $store_id = $entity_result[0]->store_id;
        $state_id = $entity_result[0]->state;
        $userid = $entity_result[0]->chairman . "," . $entity_result[0]->supervisor . "," . $entity_result[0]->executive;
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
        $data['user_details'] = $this->fetchUserDetails($db, $userid);
        $data['outlet_type_select_option_array'] = $this->fetchEntities($db);
        $data['battalion_unit_select_option_array'] = $this->battalionfetchEntities($db);
        $db->close();
        return $data;
    }
    public function fetchChairmanEntityDetails($id)
    {
        $db = $this->db;
        $entity_query = "SELECT me.id,me.entity_name,me.address,me.city,me.state,me.chairman,me.supervisor,me.executive,me.authorised_distributor as store_id,
        me.authorised_distributor_entity_type_id as authorised_distributor,me.entity_type 
                        from master_entities me 
                        where me.id=?";
        $entity_response = $db->query($entity_query, array($id));
        $entity_result = $entity_response->result();
        $data['canteen_club_data'] = $entity_result;
        $store_id = $entity_result[0]->store_id;
        $state_id = $entity_result[0]->state;
        $userid = $entity_result[0]->chairman . "," . $entity_result[0]->supervisor . "," . $entity_result[0]->executive;
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
        $data['user_details'] = $this->fetchUserDetails($db, $userid);
        $data['outlet_type_select_option_array'] = $this->fetchEntities($db);
        $data['battalion_unit_select_option_array'] = $this->battalionfetchEntities($db);
        $db->close();
        return $data;
    }
    public function getUsersDetails($irlano)
    {
        $db = $this->db;
        $query = "SELECT distinct ca.admin_id AS id,concat(IFNULL(ca.user_rank,'N/A'),'. ',ca.firstname,' - ',ca.username) AS name,ca.username 
                  FROM ci_admin ca 
                   WHERE ca.username like '%$irlano%' and  ca.firstname IS NOT NULL";
        $response = $db->query($query);
        $db->close();
        $result = $response->result_array();
        $json = array();
        foreach ($result as $value) {
            $json[] = array('id' => $value['id'], 'text' => $value['name']);
        }
        $db->close();
        return $json;
    }
}
