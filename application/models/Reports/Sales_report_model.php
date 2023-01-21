<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Sales_report_model extends CI_Model
{

    public function sales_report($start_date, $end_date, $entity_id)
    {

        $db = $this->db;
        $query = "SELECT od.cart_id,ca.firstname,cad.firstname As customer_name,cd.order_code,CONCAT(ld.brand,' ',ld.liquor_description,' ',ld.liquor_type,' ',ld.liquor_ml,'ml ') as Liquor_details,od.dispatch_quantity,od.dispatch_time,od.dispatch_total_cost_bottles,od.order_time
    FROM cart_details cd 
    INNER JOIN order_details od ON cd.id=od.cart_id
    INNER JOIN liquor_entity_mapping lem ON lem.id=od.liquor_entity_id
    INNER JOIN liquor_details ld ON ld.liquor_description_id=lem.liquor_description_id
    INNER JOIN ci_admin ca ON ca.admin_id=od.dispatch_by
    INNER JOIN ci_admin cad ON cad.admin_id=od.order_by
    WHERE cd.ordered_to_entity_id='$entity_id' AND cd.is_order_delivered=1 AND od.is_liquor_removed=0 AND DATE(od.dispatch_time) BETWEEN '$start_date' AND '$end_date'
    ORDER BY od.dispatch_time";
        $response = $db->query($query);
        $result = $response->result_array();
        return $result;
    }

    public function cost_data($start_date, $end_date, $entity_id)
    {

        $db = $this->db;
        $query = "SELECT SUM(od.dispatch_quantity) AS total_quantity,SUM(od.dispatch_total_cost_bottles) AS total_sale
    FROM cart_details cd 
    INNER JOIN order_details od ON cd.id=od.cart_id
    INNER JOIN liquor_entity_mapping lem ON lem.id=od.liquor_entity_id
    INNER JOIN liquor_details ld ON ld.liquor_description_id=lem.liquor_description_id
    INNER JOIN ci_admin ca ON ca.admin_id=od.dispatch_by
    INNER JOIN ci_admin cad ON cad.admin_id=od.order_by
    WHERE cd.ordered_to_entity_id='$entity_id' AND  cd.is_order_delivered=1 AND od.is_liquor_removed=0 AND DATE(od.dispatch_time) BETWEEN '$start_date' AND '$end_date'
    ORDER BY od.dispatch_time";
        $response = $db->query($query);
        $result = $response->result_array();
        return $result;
    }

    public function liquor_sales_report($entity_id, $start_date, $end_date)
    {
        $db = $this->db;

        if ($end_date == '') {
            $condition = "insert_date='$start_date' order by liquor_details";
        } else {
            $condition = "insert_date BETWEEN '$start_date' and '$end_date' order by liquor_details";
        }

        $query = "SELECT brand AS liquor_details,liquor_type,
                liquor_opening_qty,liquor_sale_qty,liquor_unit_purchase_price,liquor_unit_sell_price,liquor_unit_profit,liquor_balance,liquor_balance*liquor_unit_purchase_price as closing_stock_value,liquor_opening_qty*liquor_unit_purchase_price as opening_stock_value,
                ifnull(liquor_total_purchase_price,0)as liquor_total_purchase_price,ifnull(liquor_total_sale_price,0)as liquor_total_sale_price,ifnull(liquor_profit,0) as liquor_profit
                FROM liquor_stock_sales ls
                INNER JOIN liquor_details ld ON ld.liquor_description_id=ls.liquor_description_id
                WHERE entity_id='$entity_id' and $condition";

        // return $query;

        $response = $db->query($query);

        $result = $response->result_array();
        $db->close();
        return $result;
    }


    public function total_liquor_sales_summary($start_date, $end_date, $entity_id)
    {
        $db = $this->db;
        if ($end_date == '') {
            $condition = "insert_date='$start_date'";
        } else {
            $condition = "insert_date BETWEEN '$start_date' and '$end_date'";
        }

        $query = "SELECT sum(liquor_sale_qty) as liquor_sale_qty, 
                sum(liquor_balance) as liquor_balance,
                sum(liquor_balance*liquor_unit_purchase_price) as closing_stock_value,
                SUM(liquor_opening_qty) as liquor_opening_qty,
                SUM(liquor_opening_qty*liquor_unit_purchase_price) as opening_stock_value,
                ifnull(sum(liquor_total_purchase_price),0) as liquor_total_purchase_price,
                ifnull(sum(liquor_total_sale_price),0) as liquor_total_sale_price,
                ifnull(sum(liquor_profit),0) as liquor_profit 
                FROM liquor_stock_sales ls
                INNER JOIN liquor_details ld ON ld.liquor_description_id=ls.liquor_description_id
                WHERE entity_id='$entity_id' and $condition";

        // return $query;

        $response = $db->query($query);

        $result = $response->result_array();
        $db->close();
        return $result;
    }


    public function liquor_sales_summary($entity_id, $start_date, $end_date)
    {
        $db = $this->db;

        if ($end_date == '') {
            $condition = "insert_date='$start_date' order by liquor_details";
        } else {
            $condition = "insert_date BETWEEN '$start_date' and '$end_date' order by liquor_details";
        }

        $query = "SELECT CONCAT(ld.brand,' ',ld.liquor_description,' ',ld.liquor_type,' ',ld.liquor_ml,'ml ') as liquor_details,liquor_sale_qty,liquor_unit_sell_price,
		        IFNULL(liquor_total_sale_price,0)as liquor_total_sale_price
                FROM liquor_stock_sales ls
                INNER JOIN liquor_details ld ON ld.liquor_description_id=ls.liquor_description_id
                WHERE entity_id='$entity_id' and $condition";

        // return $query;

        $response = $db->query($query);

        $result = $response->result_array();
        $db->close();
        return $result;
    }


    public function fetch_entity_name($entity_id)
    {
        $db = $this->db;
        $query = "select entity_name from master_entities where id='$entity_id'";
        $response = $db->query($query);
        $result = $response->result_array();
        $db->close();
        return $result;
    }

    public function createDailyStock()
    {
        $db = $this->db;
        $query = "call SP_CREATE_DAILY_STOCK()";

        $db->query($query);

        $db->close();
    }

    public function monthly_sales_report($sale_month, $sale_year, $entity_id)
    {
        $db = $this->db;
        $query = "SELECT CONCAT(ld.brand,' ',ld.liquor_description,' ',ld.liquor_type,' ',ld.liquor_ml,'ml ') as liquor_details,
        lm.liquor_entity_id,lm.liquor_opening_qty,lm.liquor_sale_qty,lm.liquor_unit_profit,
        lm.liquor_unit_purchase_price,lm.liquor_unit_sell_price,lm.liquor_total_purchase_price,
        lm.liquor_total_sale_price,lm.liquor_profit,lm.liquor_balance,
        (lm.liquor_unit_purchase_price*lm.liquor_balance)closing_stock_value
        from liquor_month_stock_sales lm
        INNER JOIN liquor_details ld ON ld.liquor_description_id=lm.liquor_description_id
        where lm.sale_month=? and lm.sale_year=? and lm.entity_id=? order by liquor_details";

        $response = $db->query($query, array($sale_month, $sale_year, $entity_id));

        $result = $response->result_array();
        $db->close();
        return $result;
    }

    public function yearly_sales_report($sale_year, $entity_id)
    {
        $db = $this->db;
        $query = "SELECT CONCAT(ld.brand,' ',ld.liquor_description,' ',ld.liquor_type,' ',ld.liquor_ml,'ml ') as liquor_details,
        lm.liquor_entity_id,lm.liquor_opening_qty,lm.liquor_sale_qty,lm.liquor_unit_profit,
        lm.liquor_unit_purchase_price,lm.liquor_unit_sell_price,lm.liquor_total_purchase_price,
        lm.liquor_total_sale_price,lm.liquor_profit,lm.liquor_balance,
        (lm.liquor_unit_purchase_price*lm.liquor_balance)closing_stock_value
        from liquor_month_stock_sales lm
        INNER JOIN liquor_details ld ON ld.liquor_description_id=lm.liquor_description_id
        where  lm.sale_year=? and lm.entity_id=? order by liquor_details";

        $response = $db->query($query, array($sale_year, $entity_id));

        $result = $response->result_array();
        $db->close();
        return $result;
    }
}
