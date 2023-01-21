<?php


/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Cart_model
 *
 * @author ATS-16
 */
class New_Cart_model extends CI_Model
{

    // to fetch cartDetails
    public function fetchCartDetalis($cart_id)
    {
        $db = $this->db;
        // $query = "SELECT cd.id as cart_id,Concat(ld.brand,'<br>',ld.liquor_description,'<br>',ld.bottle_size,'<br>',ld.liquor_ml,' ml') as liquor_name,cd.order_code as order_code,
        // ld.liquor_description_id as liquor_id,
        // cd.cart_type,ld.liquor_type,ld.liquor_ml,ld.liquor_image,cl.liquor_entity_id,cd.liquor_count,
        // concat(cl.quantity,'_',lem.id,'_',cd.id) as quantity, 
        // IF(cd.cart_type='E',lem.selling_price,lem.minimum_order_quantity)as unit_lot_cost, 
        // IF(cd.cart_type='E',(lem.selling_price * cl.quantity),(lem.minimum_order_quantity * cl.quantity))as total_quantity_cost,cd.ordered_to_entity_id,
        // CONCAT(entity_name,' - ',city_district_name,',',state) AS canteen_details,
        // cl.is_liquor_removed as is_liquor_removed FROM 
        // cart_details cd 
        // INNER JOIN  cart_liquor cl ON cd.id=cl.cart_id 
        // INNER JOIN liquor_entity_mapping lem ON lem.id=cl.liquor_entity_id
        // INNER JOIN entity_location_mapping elm on elm.entity_id=cd.ordered_to_entity_id
        // INNER JOIN liquor_details ld ON ld.liquor_description_id=lem.liquor_description_id 
        // where cd.id=? and cl.is_liquor_removed=0";

        $query = "SELECT cd.id as cart_id,Concat(ld.brand,'<br>',ld.liquor_description,'<br>',ld.bottle_size,'<br>',ld.liquor_ml,' ml') as liquor_name,cd.order_code as order_code,
        ld.liquor_description_id as liquor_id,
        cd.cart_type,ld.liquor_type,ld.liquor_ml,ld.liquor_image,cl.liquor_entity_id,cd.liquor_count,
        concat(cl.quantity,'_',lem.id,'_',cd.id) as quantity, 
        unit_cost_lot_size as unit_lot_cost, 
        total_cost_bottles as total_quantity_cost,cd.ordered_to_entity_id,cd.order_from_id,
        (lem.selling_price * total_cost_bottles) as total_cost,
        lem.selling_price as selling_price,
        CONCAT(entity_name,' - ',city_district_name,',',state) AS canteen_details,
        cl.is_liquor_removed as is_liquor_removed FROM 
        cart_details cd 
        INNER JOIN  cart_liquor cl ON cd.id=cl.cart_id 
        INNER JOIN liquor_entity_mapping lem ON lem.id=cl.liquor_entity_id
        INNER JOIN entity_location_mapping elm on elm.entity_id=cd.ordered_to_entity_id
        INNER JOIN liquor_details ld ON ld.liquor_description_id=lem.liquor_description_id 
        where cd.id=? and cl.is_liquor_removed=0";

        $response = $db->query($query, array($cart_id));

        $result = $response->result_array();
        // $userid = $result[0]['order_from_id'];
        // if ($result[0]['cart_type'] == 'consumer') {
        //     $liquor_used_quota_array = $this->fetchLiquorQuotaDetails($db, $userid);
        //     $result = array_merge($result, $liquor_used_quota_array);
        // }

        $db->close();
        return $result;
    }

    public function fetchLiquorQuotaDetails($db, $userid)
    {
        // $db = $this->db;
        $query = "SELECT username,bh.rank,lrq.quota,luq.liquor_count FROM 
                    ci_admin ca 
                    INNER JOIN bsf_hrms_data bh ON bh.irla=ca.username
                    INNER JOIN master_rank mr ON mr.rank=bh.rank 
                    INNER JOIN liquor_rank_quota_mapping lrq ON lrq.rankid=mr.id
                    INNER JOIN liquor_user_used_quota luq ON luq.userid=ca.admin_id
                    WHERE admin_id=?";
        $response = $db->query($query, array($userid));
        $result = $response->result_array();
        // $db->close();
        return $result;
    }

    public function placeOrder($cart_details)
    {
        $db = $this->db;
        $query = "CALL SP_CART_PLACE_ORDER('$cart_details')";
        // return $query;
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result;
    }

    //FOR CART QUANTITY INCREMENT DECREMENT AND REMOVE FUNCITONALITY
    public function checkOut($cart_details)
    {
        $db = $this->db;
        $query = "CALL SP_NEW_CART_CHECK_OUT('$cart_details')";
        // return $query;
        // echo  $query;
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result;
    }

    public function add_remove_liquor($cart_data)
    {
        $db = $this->db;
        $query = "CALL SP_ADD_REMOVE_LIQUOR('$cart_data')";
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result;
    }

    public function cancelOrder($data)
    {
        $db = $this->db;
        $query = "CALL SP_USER_CANCEL_ORDER('$data')";
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result;
    }
    // public function 
}
