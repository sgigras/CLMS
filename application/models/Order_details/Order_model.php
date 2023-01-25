<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Order_model
 *
 * to display order details
 * @author Jitendra Pal
 */
class Order_model extends CI_Model
{


    // public function completeDeliveryProcess($order_code, $cart_id)
    // {
    //     $db = $this->db;
    //     $query = "CALL SP_DELIVER_LIQUOR(?,?)";
    //     $response = $db->query($query, array($order_code, $cart_id));
    //     $result = $response->result();
    //     $db->close();
    //     return $result;
    // }

    public function completeDeliveryProcess($order_code, $user_id)
    {
        $db = $this->db;
        $query = "CALL SP_DELIVER_LIQUOR(?,?)";
        // echo $query;
        $response = $db->query($query, array($order_code, $user_id));
        $result = $response->result();
        $db->close();
        return $result;
    }

    public function fetchDeliveryOrders($entity_id)
    {
        $db = $this->db;
        $query = "Select cd.order_code,cd.id as cart_id
                 from cart_details cd where ordered_to_entity_id=? and is_order_placed=1 and is_order_delivered=0 AND cd.is_order_cancel=0";
        $response = $db->query($query, array($entity_id));
        $result['order_code_data'] = $response->result();
        $db->close();
        return $result;
    }

    // to fetch cartDetails
    public function fetchOrderCartDetails($cart_order_code)
    {
        $db = $this->db;
        $query = "SELECT cd.id as cart_id,Concat(ld.brand,' ',ld.liquor_description,' ',ld.bottle_size) AS liquor_name,cd.order_code AS order_code,
                    ld.liquor_description_id AS liquor_id,
                    cd.cart_type,ld.liquor_type,ld.liquor_ml,ld.liquor_image,od.liquor_entity_id,cd.liquor_count,
                    concat(od.recevied_quantity,'_',lem.id,'_',cd.id) AS quantity, 
                    od.recevied_cost_lot_size as unit_lot_cost,cd.ordered_to_entity_id, 
                    (od.recevied_cost_lot_size * od.recevied_quantity) AS total_quantity_cost,
                    (lem.selling_price * od.recevied_total_cost_bottles) as total_cost,
                    lem.selling_price as selling_price,
                    ca.username AS irla,ca.firstname AS name,
                    Concat(me.entity_name,' - ',st.state) as entity_details,mt.entity_type,
                    od.is_liquor_removed AS is_liquor_removed FROM 
                    cart_details cd
                    INNER JOIN  order_details AS od  ON cd.id=od.cart_id 
                    INNER JOIN liquor_entity_mapping lem ON lem.id=od.liquor_entity_id 
                    INNER JOIN liquor_details ld ON ld.liquor_description_id=lem.liquor_description_id
                    INNER JOIN ci_admin ca ON ca.admin_id =od.order_by
                    LEFT JOIN master_entities me ON  me.id=cd.order_from_entity_id
                    LEFT JOIN master_entity_type mt ON mt.id=cd.order_from_entity_type
                    LEFT JOIN master_state st ON st.id=me.state
                    WHERE cd.order_code=? AND od.is_liquor_removed=0 AND cd.is_order_placed=1 AND cd.is_order_delivered=0 AND cd.is_order_cancel=0";
        $response = $db->query($query, array($cart_order_code));
        $result = $response->result_array();
        $db->close();
        return $result;
    }


    public function fetchPrintReceipt($cart_order_code)
    {
        // $db = $this->db;

        // $fetch_cart_id_query = "SELECT id FROM cart_details WHERE  order_code=? AND is_order_placed=1 AND is_order_delivered=1 ORDER BY ID DESC LIMIT 1";
        // $cart_id_response = $db->query($fetch_cart_id_query, array($cart_order_code));
        // $cart_id_result = $cart_id_response->result();
        // $cart_id = $cart_id_result[0]->id;


        // $query = "SELECT me.entity_name,me.address as canteen_address,ca.username as irla,concat(bh.rank,'-',bh.name) as name,			
        //             Concat(ld.liquor_type,' ',ld.brand,' ',ld.liquor_description,' ',ld.bottle_size) as liquor_bill_description,
        //             cd.order_code as order_code,
        //             concat(od.recevied_quantity) as quantity, 
        //             od.recevied_cost_lot_size as unit_lot_cost, 
        //             (od.recevied_cost_lot_size * od.recevied_quantity) as total_quantity_cost FROM 
        //             cart_details cd  
        //             INNER JOIN order_details as od  ON cd.id=od.cart_id

        //             INNER JOIN ci_admin as ca on ca.admin_id=od.order_by  
        //             INNER JOIN bsf_hrms_data as bh on bh.irla=ca.username and bh.date_of_birth=ca.date_of_birth
        //             INNER JOIN liquor_entity_mapping lem ON lem.id=od.liquor_entity_id 
        //             INNER JOIN master_entities me on me.id=lem.entity_id
        //             INNER JOIN liquor_details ld ON ld.liquor_description_id=lem.liquor_description_id 
        //             WHERE cd.order_code=? and od.is_liquor_removed=0 and cd.is_order_placed=1 and cd.is_order_delivered=1 ";


        // $query = "SELECT me.entity_name,me.address as canteen_address,ca.username as irla,concat(bh.rank,'-',bh.name) as name,			
        //             Concat(ld.liquor_type,' ',ld.brand,' ',ld.liquor_description,' ',ld.bottle_size) as liquor_bill_description,
        //             cd.order_code as order_code,
        //             concat(od.recevied_quantity) as quantity, 
        //             od.recevied_cost_lot_size as unit_lot_cost, 
        //             (od.recevied_cost_lot_size * od.recevied_quantity) as total_quantity_cost FROM 
        //             cart_details cd  
        //             INNER JOIN order_details as od  ON cd.id=od.cart_id

        //             INNER JOIN ci_admin as ca on ca.admin_id=od.order_by  
        //             INNER JOIN bsf_hrms_data as bh on bh.irla=ca.username and bh.date_of_birth=ca.date_of_birth
        //             INNER JOIN liquor_entity_mapping lem ON lem.id=od.liquor_entity_id 
        //             INNER JOIN master_entities me on me.id=lem.entity_id
        //             INNER JOIN liquor_details ld ON ld.liquor_description_id=lem.liquor_description_id 
        //             WHERE cd.id=? and od.is_liquor_removed=0 and cd.is_order_placed=1 and cd.is_order_delivered=1 ";
        // $response = $db->query($query, array($cart_id));
        // $result = $response->result_array();
        // $db->close();
        // return $result;

        $db = $this->db;
		$query = "SELECT
            DISTINCT
            bo.brewery_order_code,
            bd.brewery_order_id,
            bm.brewery_name,
            ca.firstname as requested_by,
            bo.approval_status,
            bo.brewery_order_code,
            (select firstname from ci_admin where admin_id=bo.approved_by) as approved_by,
            bo.approved_time,
            bo.creation_time,
            bo.approval_status
        FROM
            brewery_order_liquor_details as bd
                INNER JOIN brewery_order bo
                    ON bo.id = bd.brewery_order_id
                INNER JOIN ci_admin ca
                    ON ca.admin_id=bo.created_by
                INNER JOIN master_brewery bm
                    ON bm.id=bo.brewery_id WHERE bo.brewery_order_code = ".$cart_order_code;
        echo $query;
		$response = $db->query($query);
		$db->close();
		$result = $response->result_array();
		return $result;
    }

    public function deliveryCheckOut($cart_details)
    {
        $db = $this->db;
        $query = "CALL SP_DELIVERY_CART_CHECK_OUT(?)";

        $response = $db->query($query, array($cart_details));
        $result = $response->result();
        $db->close();
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
        $query = "CALL SP_CART_CHECK_OUT('$cart_details')";
        // return $query;
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result;
    }

    public function fetchAllOrders($user_id)
    {
        $db = $this->db;
        $query = "SELECT cd.id as cart_id,Concat(ld.brand,'<br>',ld.liquor_description,'<br>',ld.bottle_size,'<br>',ld.liquor_ml,' ml') as liquor_name,cd.order_code as order_code,
        ld.liquor_description_id as liquor_id,
        cd.cart_type,ld.liquor_type,ld.liquor_ml,ld.liquor_image,cl.liquor_entity_id,cd.liquor_count,
        concat(cl.quantity,'_',lem.id,'_',cd.id) as quantity, 
        cl.unit_cost_lot_size as unit_lot_cost, 
        cl.total_cost_bottles as total_quantity_cost,cd.ordered_to_entity_id,cd.order_from_id,
        CONCAT(entity_name,' - ',city_district_name,',',state) AS canteen_details,
        cl.is_liquor_removed as is_liquor_removed,od.order_process,date_format(cd.order_time,'%d-%m-%Y %H:%i:%s') as liquor_order_time  FROM 
        cart_details cd 
        INNER JOIN  order_details AS od  ON cd.id=od.cart_id 
        INNER JOIN  cart_liquor cl ON cd.id=cl.cart_id 
        INNER JOIN liquor_entity_mapping lem ON lem.id=cl.liquor_entity_id
        INNER JOIN entity_location_mapping elm on elm.entity_id=cd.ordered_to_entity_id
        INNER JOIN liquor_details ld ON ld.liquor_description_id=lem.liquor_description_id 
        where cd.order_by_userid=? and cd.cart_type='consumer' and  cl.is_liquor_removed=0 group by liquor_id,order_code order by od.order_process";

        $response = $db->query($query, array($user_id));
        $result = $response->result_array();
        $db->close();
        return $result;
    }


    public function fetchDashboardDetails($user_id)
    {
        $db = $this->db;
        $query = "SELECT cd.id as cart_id,Concat(ld.brand,'<br>',ld.liquor_description,'<br>',ld.bottle_size,'<br>',ld.liquor_ml,' ml') as liquor_name,cd.order_code as order_code,
        ld.liquor_description_id as liquor_id,
        cd.cart_type,ld.liquor_type,ld.liquor_ml,ld.liquor_image,cl.liquor_entity_id,cd.liquor_count,
        concat(cl.quantity,'_',lem.id,'_',cd.id) as quantity, 
        cl.unit_cost_lot_size as unit_lot_cost, 
        cl.total_cost_bottles as total_quantity_cost,cd.ordered_to_entity_id,cd.order_from_id,
        CONCAT(entity_name,' - ',city_district_name,',',state) AS canteen_details,
        cl.is_liquor_removed as is_liquor_removed,od.order_process FROM 
        cart_details cd 
        INNER JOIN  order_details AS od  ON cd.id=od.cart_id 
        INNER JOIN  cart_liquor cl ON cd.id=cl.cart_id 
        INNER JOIN liquor_entity_mapping lem ON lem.id=cl.liquor_entity_id
        INNER JOIN entity_location_mapping elm on elm.entity_id=cd.ordered_to_entity_id
        INNER JOIN liquor_details ld ON ld.liquor_description_id=lem.liquor_description_id 
        where cd.order_from_id=? and cd.cart_type='consumer' and  cl.is_liquor_removed=0 order by cd.id desc limit 1";
        $response = $db->query($query, array($user_id));

        $result['order_details'] = $response->result_array();

        $query = "SELECT IFNULL(sum(liquor_count),0) AS liqour_count FROM liquor_user_used_quota WHERE
         order_status IN (1,3) AND created_by=? AND MONTH(INSERT_TIME)=MONTH(NOW()) AND YEAR(INSERT_TIME)=YEAR(NOW()); ";
        $response = $db->query($query, array($user_id));

        $result['liqour_consumed_quota'] = $response->result_array();





        $db->close();
        return $result;
    }

    // public function 
}
