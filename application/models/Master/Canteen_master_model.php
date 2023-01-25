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
 * @author Jitendra Pal
 */
class Canteen_master_model extends CI_Model
{

    //put your code here

    public function fetchCanteenList()
    {
        $db = $this->db;
            $query = "SELECT me.id,me.entity_name,bpu.posting_unit as battalion_unit ,mt.entity_type AS canteen_club,ms.state,CONCAT(ch.username,' (',IFNULL(ch.user_rank,'NA'),') ',IFNULL(ch.firstname,' '),IFNULL(ch.lastname,''),' - ',IFNULL(ch.mobile_no,''))AS chairman,CONCAT(cs.username,' (',IFNULL(cs.user_rank,'NA'),') ',IFNULL(cs.firstname,''),' ',IFNULL(cs.lastname,''),' - ',IFNULL(cs.mobile_no, '')) AS supervisor,CONCAT(ce.username,' (',IFNULL(ce.user_rank,'NA'),') ',IFNULL(ce.firstname,''),' ',IFNULL(ce.lastname,' '),' - ',IFNULL(ce.mobile_no, '')) AS executive 
            FROM master_entities me
            LEFT JOIN bsf_posting_unit bpu ON bpu.id=me.battalion_unit
            INNER JOIN master_state ms ON me.state=ms.id 
            INNER JOIN master_entity_type mt ON me.entity_type=mt.id
            INNER JOIN ci_admin cs ON cs.admin_id=me.supervisor 
            INNER JOIN ci_admin ch ON ch.admin_id=me.chairman 
            INNER JOIN ci_admin ce ON ce.admin_id=me.executive
            where mt.entity_type NOT IN ('Brewery','consumer')"; //BREWERy is registered using another page
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result;
    }

    public function fetchCanteenListForChairman($chairmanid)
    {
        $db = $this->db;
        $query = "SELECT me.id,me.entity_name,mt.entity_type AS canteen_club,ms.state,CONCAT(ch.firstname,' ',IFNULL(ch.lastname,''))AS chairman,
                CONCAT(IFNULL(cs.firstname,'N.A.'),' ',IFNULL(cs.lastname,'')) AS supervisor,CONCAT(IFNULL(ce.firstname,'N.A.'),' ',IFNULL(ce.lastname,'')) AS executive
                FROM master_entities me 
                INNER JOIN master_state ms ON me.state=ms.id 
                INNER JOIN master_entity_type mt ON me.entity_type=mt.id
                INNER JOIN ci_admin cs ON cs.admin_id=me.supervisor 
                INNER JOIN ci_admin ch ON ch.admin_id=me.chairman 
                INNER JOIN ci_admin ce ON ce.admin_id=me.executive
                where mt.entity_type NOT IN ('Brewery','consumer') AND me.chairman='$chairmanid'"; //BREWERy is registered using another page
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
        $data['battalion_unit_select_option_array'] = $this->battalionfetchEntities($db);

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

    //    public function fetchClubEntityID() {
    //        $query = "Select id from master_state";
    //        $response = $db->query($query);
    //        $result = $response->result();
    //        return $result;
    //    }

    public function fetchCities($state_id)
    {
        $db = $this->db;
        $query = "SELECT id,city_district_name from master_city_district where stateid=?";
        $response = $db->query($query, array($state_id));
        $result = $response->result();
        $db->close();
        return $result;
    }

    public function fetchUserDetails($db, $userid = '')
    {
        if ($userid != '') {
            $query = "SELECT ca.admin_id AS id,ca.username,concat( bh.rank,'. ',ca.firstname,' ',IFNULL(ca.lastname,''),' - ',bh.irla) AS name
                  FROM ci_admin ca 
                  INNER JOIN bsf_hrms_data bh ON bh.irla=ca.username and bh.date_of_birth=ca.date_of_birth 
                  WHERE ca.firstname IS NOT NULL  AND bh.rank is NOT NULL and ca.admin_id IN ($userid)";
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
    public function battalionfetchEntities($db)
    {
        $query = "SELECT id,posting_unit from bsf_posting_unit";
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
        // print_r($data);die();
        $db = $this->db;
        $response = $db->query("CALL SP_INSERT_UPDATE_CANTEEN_DETAILS(?)", array($data));
        $db->close();
        return $response->result();
    }

    public function update_canteen_details($data)
    {
        $db = $this->db;
        // $query = "CALL SP_INSERT_UPDATE_CANTEEN_DETAILS('$data')";
        // return $query;
        // $response = $db->query();
        $response = $db->query("CALL SP_INSERT_UPDATE_CANTEEN_DETAILS(?)", array($data));
        $db->close();
        return $response->result();
    }

    public function update_chairman_canteen_details($data)
    {
        $db = $this->db;
        // $query = "CALL SP_INSERT_UPDATE_CANTEEN_DETAILS('$data')";
        // return $query;
        // $response = $db->query();
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
        // $userid = $entity_result[0]->chairman;
        // $column_name = $entity_result[0]->column_name;
        // $table_name = $entity_result[0]->details_map_table;

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
        // $userid = $entity_result[0]->chairman;
        // $column_name = $entity_result[0]->column_name;
        // $table_name = $entity_result[0]->details_map_table;

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
        $query = "SELECT distinct ca.admin_id AS id,concat( ca.user_rank,'. ',ca.firstname,' ',IFNULL(ca.lastname,''),' - ',ca.username) AS name,ca.username 
                  FROM ci_admin ca 
                   WHERE ca.username like '%$irlano%' and  ca.firstname IS NOT NULL  AND ca.user_rank is NOT NULL";

        $response = $db->query($query);
        $db->close();
        $result = $response->result_array();
        $json = array();
        // $result
        // if (count($result) > 0) {
        foreach ($result as $value) {
            $json[] = array('id' => $value['id'], 'text' => $value['name']);
        }

        // } else {
        //     $json[] = array('id' => $irlano, 'text' => $irlano);
        // }
        $db->close();
        return $json;
    }
}
