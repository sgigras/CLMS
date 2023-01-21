<?php

defined('BASEPATH') or exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Display_Stock_Model
 *
 * @author Ujwal Jain
 */
class Display_Stock_Model extends CI_Model
{

    public function fetchstocksummary($entity_id)
    {
        $db = $this->db;
        $query = "SELECT lem.id AS entity_mapping_id,ld.liquor_image,ld.brand,concat(ld.brand,'-',ld.liquor_description) AS liquor_name,ld.liquor_type,ld.liquor_ml,lem.purchase_price,lem.selling_price,lem.available_quantity,lem.actual_available_quantity,if(lem.available_quantity<=50 ,'danger',if(lem.available_quantity<=150,'warning',if(lem.available_quantity>150,'success',''))) as class 
        FROM liquor_entity_mapping lem 
        JOIN liquor_details ld ON lem.liquor_description_id=ld.liquor_description_id 
        WHERE lem.entity_id='$entity_id'";
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result;
    }

    public function fetchdetailsofstocksummary($entity_id)
    {
        $db = $this->db;
        $query = "SELECT ca.username AS irla,ca.firstname,if(cd.cart_type='consumer',od.quantity,od.total_cost_bottles) AS quantity,if(od.order_process=1,'Order Placed','Order Issued') AS status,ifnull(od.dispatch_time,'N.A.') AS issued_time 
        FROM order_details od 
        JOIN cart_details cd ON od.cart_id=cd.id 
        JOIN ci_admin ca ON od.order_by=ca.admin_id 
        WHERE od.liquor_entity_id='$entity_id' AND od.order_process!=0 AND DATE(od.order_time)=DATE(now())";
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result;
    }

}
