<?php

defined('BASEPATH') or exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Stockist_order_model
 *
 * @author Tapan Tripathi
 */
class Stockist_order_model extends CI_Model
{

    //put your code here

    // public function fetchInitialEntityFormDetails()
    // {
    //     $db = $this->db;
    //     $data['title'] = trans('add_new_canteen');
    //     $data['mode'] = 'A';
    //     $data['state_record'] = $this->fetchState($db);
    //     $data['distributor_authority_record'] = $this->fetchDistributorAuthority($db);
    //     $data['user_details'] = $this->fetchUserDetails($db);
    //     $db->close();
    //     return $data;
    // }

    public function fetch_delivarable_entity_id($entity_id)
    {
        $db = $this->db;
        $query = "SELECT me.authorised_distributor as delivarable_entity_id
        FROM master_entities me
        WHERE me.id='$entity_id' ";
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result;
    }

    public function fetchCanteen($cart_type, $title)
    {
        $db = $this->db;
        $data['title'] = trans($title);
        if ($cart_type == 'consumer') {
            $query = "select entity_id as id,concat(entity_name,' - ',city_district_name,' - ',state) as state
        FROM entity_new_location_mapping where entity_type='Sub-Depot'";
        } else {
            $query = "select id as id,concat(entity_name,' - ',state) as state
            FROM master_entities";
        }
        $response = $db->query($query);
        $data['state_record'] = $response->result();
        $data['cart_type'] = $cart_type;
        $db->close();
        return $data;
    }

    // public function fetchState($cart_type, $title)
    // {
    //     $db = $this->db;
    //     $data['title'] = trans($title);
    //     $query = "Select id,state from master_state";
    //     $response = $db->query($query);
    //     $data['state_record'] = $response->result();
    //     $data['cart_type'] = $cart_type;
    //     $db->close();
    //     return $data;
    // }

    public function fetchCities($state_id)
    {
        $db = $this->db;
        $query = "SELECT id,city_district_name from master_city_district where stateid=?";
        $response = $db->query($query, array($state_id));
        $result = $response->result();
        $db->close();
        return $result;
    }

    public function fetchProductsName($keyword, $delivarable_entity_id, $selected_city)
    {
        $db = $this->db;
        $whereCondition = '';
        if ($delivarable_entity_id != '') {
            // $whereCondition .= " and E.state_id = '" . $selected_state . "' ";
            $whereCondition .= " and lem.entity_id = '" . $delivarable_entity_id . "' ";
        }
        // if ($selected_city != '') {
        //     $whereCondition .= " and E.city_id = '" . $selected_city . "' ";
        // }
        //        $query = "SELECT id,product_name,product_image,product_type from master_product where product_name=?";
        //        $response = $db->query($query, array($keyword));
        //        $query = "SELECT id,product_name,product_image,product_type from master_product where product_name like '%".$keyword."%'";
        // $query = "SELECT P.id,P.product_name,P.product_image,P.product_type from master_product P inner join mapping_product_entity E on P.id = E.productid where P.product_name like '%".$keyword."%' ".$whereCondition;

        $query = "SELECT lem.id,ld.brand as product_name,ld.liquor_image as product_image, ld.liquor_type as product_type FROM liquor_details ld
      inner join liquor_entity_mapping lem on lem.liquor_description_id=ld.liquor_description_id  
      where ld.brand like '%" . $keyword . "%'  group by ld.brand";
        $response = $db->query($query);
        // $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result;
    }

    public function fetchProductsType($keyword, $delivarable_entity_id, $selected_city)
    {
        $db = $this->db;
        $whereCondition = '';
        if ($delivarable_entity_id != '') {
            $whereCondition .= " and lem.entity_id = '" . $delivarable_entity_id . "' ";
        }
        // if ($selected_city != '') {
        //     $whereCondition .= " and E.city_id = '" . $selected_city . "' ";
        // }
        //        $query = "SELECT id,product_name,product_image,product_type from master_product where product_name=?";
        //        $response = $db->query($query, array($keyword));
        //        $query = "SELECT id,product_name,product_image,product_type from master_product where product_type like '%".$keyword."%' group by product_type";
        // $query = "SELECT P.id,P.product_name,P.product_image,P.product_type from master_product P inner join mapping_product_entity E on P.id = E.productid where P.product_type like '%" . $keyword . "%' " . $whereCondition . " group by P.product_type";
        $query = "SELECT lem.id,ld.brand as product_name,ld.liquor_image as product_image, ld.liquor_type as product_type FROM liquor_details ld
                inner join liquor_entity_mapping lem on lem.liquor_description_id=ld.liquor_description_id  
                where ld.liquor_type like '%" . $keyword . "%'  $whereCondition group by ld.liquor_type,ld.brand";
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result;
    }

    public function fetchProductsOnSearch($keyword, $delivarable_entity_id, $cart_type)
    {
        $db = $this->db;
        $whereCondition = '';
        // if ($delivarable_entity_id != 0) {
        //     $whereCondition .= " and lem.entity_id = '" . $delivarable_entity_id . "' ";
        // }
            $data_array = array();

        if ($delivarable_entity_id != 0) {
            $whereCondition .= " and lem.entity_id = ? ";
            $data_array[] = $delivarable_entity_id;
        }

        if ($keyword != 'ALL') {
            $whereCondition .= " WHERE ld.brand LIKE ? OR ld.liquor_type LIKE ? OR ld.liquor_description LIKE ?";
            $data_array[] = '%'.$keyword.'%';
            $data_array[] = '%'.$keyword.'%';
            $data_array[] = '%'.$keyword.'%';
        }

        if ($cart_type == 'consumer') {
            $query = " SELECT lem.id,ld.brand as product_name,ld.liquor_image as product_image, ld.liquor_type as product_type, 
                        ld.liquor_description as liquor_description,selling_price as unit_per_bottel,lem.available_quantity,ld.liquor_ml 
                        FROM liquor_details ld
                         inner join liquor_entity_mapping lem on lem.liquor_description_id=ld.liquor_description_id $whereCondition order by  available_quantity desc";
        } else {
            $query = " SELECT lem.id,ld.brand as product_name,ld.liquor_image as product_image, ld.liquor_type as product_type, 
                        ld.liquor_description as liquor_description,minimum_order_quantity as unit_lot_cost,selling_price as unit_per_bottel,available_quantity,ld.liquor_ml 
                        FROM liquor_details ld
                        inner join liquor_entity_mapping lem on lem.liquor_description_id=ld.liquor_description_id $whereCondition order by  available_quantity desc";
        }

        try {
            // run your code here

            $response = $db->query($query, $data_array);
            $result = $response->result();
            $db->close();
        } finally {
            // $result = array();
            $db->close();
            //optional code that always runs
        }
        return $result;
    }

    // public function insert_canteen_details($data)
    // {
    //     $db = $this->db;
    //     $response = $db->insert('master_entities', $data);
    //     $db->close();
    //     return $response;
    // }

    // public function update_canteen_details($data)
    // {
    //     $db = $this->db;
    //     $response = $db->update('master_entities', $data);
    //     $db->close();
    //     return $response;
    // }

    public function createUpdateCartDetails($cart_details)
    {
        $db = $this->db;
        // $result='';
        $query = "call SP_CART_INSERT_UPDATE_ITEM('$cart_details')";
        // return $query;
        try {
            $response = $db->query($query);
            $result = $response->result();
            $db->close();
        } finally {
            $db->close();
        }
        return $result;
    }

    // public function fetchEntityDetails($id)
    // {
    //     $db = $this->db;
    //     $entity_query = "SELECT me.id,me.entity_name,me.address,me.city,me.state,me.chairman,me.supervisor,me.executive,me.authorised_distributor,md.details_map_table,md.column_name,"
    //         . "(CASE "
    //         . "WHEN me.authorised_distributor=1 THEN me.authorised_brewery "
    //         . "WHEN me.authorised_distributor=2 THEN me.authorised_entity "
    //         . "END ) as store_id,me.entity_type from "
    //         . "master_entities me "
    //         . "INNER JOIN master_distributor_authority md on me.authorised_distributor=md.id "
    //         . "where me.id=?";
    //     $entity_response = $db->query($entity_query, array($id));
    //     $entity_result = $entity_response->result();
    //     $data['canteen_club_data'] = $entity_result;
    //     $store_id = $entity_result[0]->store_id;
    //     $state_id = $entity_result[0]->state;
    //     $column_name = $entity_result[0]->column_name;
    //     $table_name = $entity_result[0]->details_map_table;

    //     $city_list_query = "SELECT id,city_district_name FROM master_city_district WHERE stateid=?";
    //     $city_list_response = $db->query($city_list_query, array($state_id));
    //     $data['city_list'] = $city_list_response->result();

    //     $distributor_name_query = "SELECT id,$column_name as store_name from $table_name where id=?";
    //     $distributor_name_response = $db->query($distributor_name_query, array($store_id));
    //     $data['distributor_name_list'] = $distributor_name_response->result();

    //     $data['title'] = trans('edit_new_canteen');
    //     $data['mode'] = 'E';
    //     $data['state_record'] = $this->fetchState($db);
    //     $data['distributor_authority_record'] = $this->fetchDistributorAuthority($db);
    //     $data['user_details'] = $this->fetchUserDetails($db);
    //     $db->close();
    //     return $data;
    // }

    public function fetchInitialAlcoholFormDetails()
    {
        $db = $this->db;
        $result['title'] = trans('liquor_inventory');
        $result['mode'] = 'A';
        $result['liquor_data'] = $this->fetchliquor();
        //        print_r($this->getAlcoholType($db));
        //        print_r($this->fetchliquor());
        //        die();
        $db->close();
        return $result;
    }

    public function fetchliquor()
    {
        $db = $this->db;
        $whereCondition = '';

        $entity_id = $this->session->userdata('entity_id');

        if ($entity_id != 0) {
            $whereCondition .= " and lem.entity_id = '" . $entity_id . "' ";
        }

        $query = " SELECT lem.id,concat(ld.brand,'-',ld.liquor_type,'-', 
                    ld.liquor_description,'-',ld.liquor_ml) as liquor_data
                    FROM liquor_details ld
                    inner join liquor_entity_mapping lem on lem.liquor_description_id=ld.liquor_description_id $whereCondition";


        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result;
    }

    public function fetchLiquorPrvAvlQty($liquor_entity_mapping_id)
    {
        $db = $this->db;
        $result['title'] = trans('liquor_inventory');
        $query = "Select id,available_quantity from liquor_entity_mapping where id = '" . $liquor_entity_mapping_id . "'";
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result;
    }

    public function updateCurrAvlQty($qty_sum, $liquor_entity_mapping_id)
    {
        $db = $this->db;
        $query = "update liquor_entity_mapping set available_quantity = '" . $qty_sum . "' where id = '" . $liquor_entity_mapping_id . "'";
        $db->query($query);
        $query = "Select 'success' as result;";
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result;
    }

    // to display quantity of liquors booked according to consumer
    public function fetchBookedLiquor($entity_id)
    {
        $db = $this->db;
        $query = "SELECT cd.id as cart_id,Concat(ld.brand,' ',ld.liquor_description,' ',ld.bottle_size) AS liquor_name,cd.order_code AS order_code,
                    ld.liquor_description_id AS liquor_id,
                    cd.cart_type,ld.liquor_type,ld.liquor_ml,ld.liquor_image,od.liquor_entity_id,cd.liquor_count,date_format(cd.order_time,'%d-%m-%Y %H:%i:%s') liquor_order_time,
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
                    WHERE cd.ordered_to_entity_id=? AND od.is_liquor_removed=0 AND cd.is_order_placed=1 AND cd.is_order_delivered=0 AND cd.is_order_cancel=0";
        $response = $db->query($query, array($entity_id));
        $result = $response->result_array();
        $db->close();
        return $result;
    }

    // to display quantity of liquors booked
    public function fetchBookedLiquorList($entity_id)
    {
        $db = $this->db;
        $query = "SELECT lem.id,CONCAT(ld.brand,' ',ld.liquor_description,' ',ld.liquor_type,' ',ld.liquor_ml,'ml ') as Liquor_details,od.unit_cost_lot_size,sum(od.quantity) as booked_quantity 
        FROM cart_details cd 
        INNER JOIN order_details od ON cd.id=od.cart_id
        INNER JOIN liquor_entity_mapping lem ON lem.id=od.liquor_entity_id
        INNER JOIN liquor_details ld ON ld.liquor_description_id=lem.liquor_description_id
        WHERE cd.ordered_to_entity_id=? 
        AND cd.is_order_placed=1 AND cd.is_order_delivered=0 AND cd.is_order_cancel=0 AND od.order_process=1 AND od.is_liquor_removed=0 AND  cd.cart_type='consumer' group by liquor_entity_id 
        ORDER BY Liquor_details";
        $response = $db->query($query, array($entity_id));
        $result = $response->result_array();
        $db->close();
        return $result;
    }

    public function fetchBookedOrderStock($entity_id)
    {
        $db = $this->db;

        $query = " SELECT cd.id as cart_id,Concat(ld.brand,'<br>',ld.liquor_description,'<br>',ld.bottle_size,'<br>',ld.liquor_ml,' ml') as liquor_name,
        cd.order_code as order_code,
        ld.liquor_description_id as liquor_id,
        cd.cart_type,ld.liquor_type,ld.liquor_ml,ld.liquor_image,od.liquor_entity_id,cd.liquor_count,
        concat(od.quantity,'_',lem.id,'_',cd.id) as quantity, 
        unit_cost_lot_size as unit_lot_cost, 
        (lem.selling_price * total_cost_bottles) as total_cost,
        lem.selling_price as selling_price,
        total_cost_bottles as total_quantity_cost,cd.ordered_to_entity_id,cd.order_from_id,
        CONCAT(entity_name,' - ',city_district_name,',',state) AS canteen_details,
        od.is_liquor_removed as is_liquor_removed,od.order_process,date_format(cd.order_time,'%d-%m-%Y %H:%i:%s') as liquor_order_time FROM 
        cart_details cd
        INNER JOIN order_details od ON cd.id=od.cart_id
        -- INNER JOIN  cart_liquor cl ON cd.id=cl.cart_id 
        INNER JOIN liquor_entity_mapping lem ON lem.id=od.liquor_entity_id
        INNER JOIN entity_location_mapping elm on elm.entity_id=cd.ordered_to_entity_id
        INNER JOIN liquor_details ld ON ld.liquor_description_id=lem.liquor_description_id 
        where cd.order_from_entity_id=? and od.is_liquor_removed=0 order by cd.order_time desc";

        $response = $db->query($query, array($entity_id));
        $result = $response->result_array();
        $db->close();
        return $result;
    }
}
