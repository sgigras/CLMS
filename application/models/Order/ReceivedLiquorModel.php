<?php


/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of receviedLiquorModel
 *
 * to recieve liquor and mark breakage
 * @author ATS-16
 */

class ReceivedLiquorModel extends CI_Model
{
    public function fetchReceivedLiquor($order_code, $entity_id)
    {
        $db = $this->db;
        $query = "SELECT cd.id AS cart_id,od.id,Concat(ld.brand,' ',ld.liquor_description,' ',ld.bottle_size) AS liquor_name,
        cd.order_code AS order_code,
        cd.cart_type,ld.liquor_type,od.liquor_entity_id,
        od.quantity as dispatch_total_quantity,
        lem.selling_price AS unit_selling_price,
        od.quantity as dispatch_total_bottles,
        (lem.selling_price * od.quantity) AS dispatch_total_cost,
        od.quantity AS received_total_bottles,
        (lem.selling_price * od.quantity) AS received_total_cost,
        DATE_FORMAT(od.order_time,'%d-%m-%Y %H:%i:%s') AS ordertime,
        DATE_FORMAT(od.dispatch_time,'%d-%m-%Y %H:%i:%s') AS dispatch_time,
        od.quantity,od.is_liquor_removed,cd.is_order_delivered,cd.is_order_received,
        cd.ordered_to_entity_id,ca.firstname AS order_by_name,cdb.firstname AS dispatch_name,
        Concat(me.entity_name,' - ',st.state) AS entity_details,mt.entity_type,
        od.is_liquor_removed AS is_liquor_removed FROM cart_details cd  
        INNER JOIN  order_details AS od  ON cd.id=od.cart_id 
        INNER JOIN liquor_entity_mapping lem ON lem.id=od.liquor_entity_id 
        INNER JOIN liquor_details ld ON ld.liquor_description_id=lem.liquor_description_id
        INNER JOIN ci_admin ca ON ca.admin_id =od.order_by
        LEFT JOIN ci_admin cdb ON cdb.admin_id=od.dispatch_by
        LEFT JOIN master_entities me ON  me.id=cd.ordered_to_entity_id
        LEFT JOIN master_entity_type mt ON mt.id=cd.order_from_entity_type
        LEFT JOIN master_state st ON st.id=me.state
        WHERE cd.order_code=? AND cd.is_order_placed=1 AND od.is_liquor_removed=0 AND cd.order_from_entity_id=?";

        // $query = "SELECT cd.id AS cart_id,od.id,Concat(ld.brand,' ',ld.liquor_description,' ',ld.bottle_size) AS liquor_name,cd.order_code AS order_code,
        // cd.cart_type,ld.liquor_type,od.liquor_entity_id,
        // od.dispatch_total_cost_bottles AS dispatch_total_quantity,
        // lem.selling_price AS unit_selling_price,
		// (lem.selling_price * od.dispatch_total_cost_bottles) AS dispatch_total_cost,
        // od.recevied_total_cost_bottles AS received_total_bottles,
        // (lem.selling_price * od.recevied_total_cost_bottles) AS received_total_cost,
        // DATE_FORMAT(od.order_time,'%d-%m-%Y %H:%i:%s') AS ordertime,
        // DATE_FORMAT(od.dispatch_time,'%d-%m-%Y %H:%i:%s') AS dispatch_time,
        // od.recevied_total_cost_bottles,od.is_liquor_removed,cd.is_order_delivered,cd.is_order_received,
        // cd.ordered_to_entity_id,ca.firstname AS order_by_name,cdb.firstname AS dispatch_name,
        // Concat(me.entity_name,' - ',st.state) AS entity_details,mt.entity_type,
        // od.is_liquor_removed AS is_liquor_removed FROM 
        // cart_details cd  
        // INNER JOIN  order_details AS od  ON cd.id=od.cart_id 
        // INNER JOIN liquor_entity_mapping lem ON lem.id=od.liquor_entity_id 
        // INNER JOIN liquor_details ld ON ld.liquor_description_id=lem.liquor_description_id
        // INNER JOIN ci_admin ca ON ca.admin_id =od.order_by
        // LEFT JOIN ci_admin cdb ON cdb.admin_id=od.dispatch_by
        // LEFT JOIN master_entities me ON  me.id=cd.ordered_to_entity_id
        // LEFT JOIN master_entity_type mt ON mt.id=cd.order_from_entity_type
        // LEFT JOIN master_state st ON st.id=me.state
        // WHERE cd.order_code=? AND cd.is_order_placed=1 AND od.is_liquor_removed=0 AND cd.order_from_entity_id=?";

        $response = $db->query($query, array($order_code, $entity_id));
        $result = $response->result_array();
        $db->close();
        return $result;
    }

    public function received_liquor($order_data)
    {
        $db = $this->db;
        $query = "CALL SP_RECEIVED_LIQUOR_DELIVERY('$order_data')";
        $response = $db->query($query, array($order_data));
        $result = $response->result();
        $db->close();
        return $result;
        // return $query;
    }
}
