<?php




/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of OrderExpiry_Model
 *
 * to cancel expired order
 * @author Ujwal Jain
 */
class Crons_Model extends CI_Model
{
    public function expired_order()
    {
        $db = $this->db;
        $fetch_data = "SELECT group_concat(cd.order_code,'#',cd.ordered_to_entity_id,'#',cd.order_by_userid,'#',cd.id) as data,count(cd.order_code) as count
        FROM cart_details cd
        WHERE now()>DATE_ADD(cd.order_time, INTERVAL 24 HOUR) AND cd.is_order_placed=1 AND cd.is_order_delivered=0 AND cd.is_order_cancel=0";

        $response = $db->query($fetch_data);
        $result = $response->result_array();

        $data = $result[0]['data'];
        $data_array = explode(',', $data);

        for ($i = 0; $i < count($data_array); $i++) {
            $query = "CALL SP_CANCEL_EXPIRED_ORDER('" . $data_array[$i] . "')";
            // echo $query;die;
            $sp_response = $db->query($query);
            $sp_response->next_result();
            $sp_response->free_result();
            // echo $query;die();
        }
        // echo '<pre>';
        // print_r($sp_response->result());
        // die();
        $db->close();
        return $sp_response;
    }

    public function UpdateActualBalance()
    {
        $db = $this->db;
        $query = "CALL SP_DAILY_UPDATE_STOCK()";
        $response = $db->query($query);
        $db->close();
        return $response;
    }




    public function createTodayStock()
    {
        $db = $this->db;
        $query = " INSERT INTO 
                    liquor_stock_sales(entity_id,liquor_entity_id,liquor_description_id,liquor_opening_qty,liquor_sale_qty,liquor_unit_purchase_price,liquor_unit_sell_price,liquor_unit_profit,liquor_balance,insert_date)
                    SELECT entity_id,id,liquor_description_id,actual_available_quantity,0,purchase_price,selling_price,(selling_price-purchase_price),actual_available_quantity,curdate() 
                    FROM liquor_entity_mapping";
        $response = $db->query($query);
        $db->close();
        return $response;
    }


    public function updateHrmsDetails()
    {
        $db = $this->db;
        $fetch_data = "SELECT ID FROM CLMS_HRMS_INTEGRATION_MASTER WHERE UPDATE_IN_CLMS_MYSQL='N' ORDER BY id DESC LIMIT 200";
        $response = $db->query($fetch_data);
        $result = $response->result_array();
        $db->close();
        echo json_encode($result);
        echo '<pre>';
        foreach ($result as $row) {
            $db1 = $this->db;
            $id = $row["ID"];
            $query = "CALL SP_INSERT_UPDATE_HRMS_CLMS_DATA('$id')";

            $response = $db1->query($query);
            $result = $response->result();
            // echo json_encode($result);

            print_r($result);
            $db1->close();
        }
        echo '</pre>';
        return $result;
    }

    public function cron_log($file_path)
    {
        $db = $this->db;
        $query = "INSERT INTO cron_log(file_name)values('$file_path')";
        $response = $db->query($query);
        $db->close();
    }
}
