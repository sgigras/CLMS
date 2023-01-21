<?php
defined('BASEPATH') or exit('No direct script access allowed');

class NewAvailable_stock extends CI_Model
{
    function getLiquorNames()
    {
        $entity_id = $this->session->userdata('entity_id');
        // print_r($entity_id);
        // die;
        $db = $this->db;
        $query =  "SELECT lem.id,Concat(ld.brand,' ',ld.liquor_description,' ',ld.liquor_type, ' -- ',lem.selling_price) as liquor
        FROM liquor_entity_mapping lem
        INNER JOIN liquor_details ld
        ON lem.liquor_description_id=ld.liquor_description_id
        WHERE lem.entity_id = $entity_id
        ";
        $response = $db->query($query);
        $result = $response->result();
        // echo "<pre>";
        // print_r($result);
        // die();
        // echo "</pre>";
        $db->close();
        return $result;
    }

    public function fetchAvailableStock($liquor_entity_id)
    {
        $db = $this->db;
        $query = "select available_quantity, selling_price from liquor_entity_mapping where id='$liquor_entity_id';";
        $response = $db->query($query);
        $result = $response->result_array();
        $db->close();
        return $result;
    }

    public function createNewStock($data)
    {

        // $liquor_array=implode(",",$liquor_array);
        // $available_stock_array=implode(",",$available_stock_array);
        // $new_stock_array=implode(",",$new_stock_array);
        // $total_array=implode(",",$total_array);

        $db = $this->db;
        $query = "CALL SP_NEW_AVAILABLE_STOCK('$data')";
        // echo $query;
        // die;
        $result = $db->query($query);
        $db->close();
        return $result;
    }
}
