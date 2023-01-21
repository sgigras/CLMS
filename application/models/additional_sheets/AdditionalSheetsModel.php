<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AdditionalSheetsModel extends CI_Model
{

    function fetchSalesTypeData($sales_type, $search)
    {
        $result = [];
        $db = $this->db;
        if ($sales_type == "mess") {
            // $query = "SELECT id, mess_type as select_type FROM mess_types WHERE mess_type LIKE '%$search%'";
            $query = "SELECT admin_id as id, firstname  as select_type from ci_admin WHERE firstname LIKE '%$search%' and firstname IN ('SOS MESS','OFFICER MESS','ORS MESS')";
            $response = $db->query($query);
            $result = $response->result_array();
            // print_r($result);
            // die();
        } elseif ($sales_type == "user") {
            $query = "SELECT admin_id as id, concat(username,' - ',firstname)  as select_type from ci_admin WHERE username LIKE '%$search%' group by select_type";
            $response = $db->query($query);
            $result = $response->result_array();
            // print_r($result);
            // die();
        }


        $json = array();
        foreach ($result as $value) {
            $json[] = array('id' => $value['id'], 'text' => $value['select_type']);
        }

        $db->close();
        return $json;
    }

    public function createNewAdditionalSheet($data)
    {
        $db = $this->db;
        $query = "CALL SP_ADDITIONAL_SHEETS('$data')";
        // echo $query;
        // die;
        $response = $db->query($query);
        $db->close();
        $result = $response->result();
        return $result;
    }

    public function fetchLiquorList($sales_type)
    {
        $entity_id = $this->session->userdata('entity_id');
        // print_r($entity_id);
        // die;
        if ($sales_type == 'user') {
            $where_clause = " and lem.liquor_type_id IN (select id from liquor_type where liquor_type='BEER') ";
        } else {
            $where_clause = '';
        }

        $db = $this->db;


        $query =  "SELECT lem.id,Concat(ld.brand,' ',ld.liquor_description,' ',ld.liquor_type, ' -- ',lem.selling_price) as liquor
        FROM liquor_entity_mapping lem
        INNER JOIN liquor_details ld
        ON lem.liquor_description_id=ld.liquor_description_id
        WHERE lem.entity_id = $entity_id $where_clause";
        // echo $query;
        $response = $db->query($query);
        $result = $response->result();
        // echo "<pre>";
        // print_r($result);
        // die();
        // echo "</pre>";
        $db->close();
        return $result;
    }


    public function fetchBreweryLiquorList($entity_id)
    {
       
        $db = $this->db;

        $query =  "SELECT lem.id,Concat(ld.brand,' ',ld.liquor_description,' ',ld.liquor_type, ' -- ',lem.selling_price) as liquor
        FROM liquor_entity_mapping lem
        INNER JOIN liquor_details ld
        ON lem.liquor_description_id=ld.liquor_description_id
        WHERE lem.entity_id = $entity_id $where_clause";
        // echo $query;
        $response = $db->query($query);
        $result = $response->result();
        // echo "<pre>";
        // print_r($result);
        // die();
        // echo "</pre>";
        $db->close();
        return $result;
    }




}
