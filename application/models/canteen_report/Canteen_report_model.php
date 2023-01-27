<?php
class Canteen_report_model extends CI_Model{
    
     public function fetchCanteenDetails($report_type)
    {
        $db = $this->db;
        if ($report_type == 1)
        {
            $query = "CALL SP_CANTEEN_REPORT('{$report_type}')";
            $data['table_head'] = ALL_CANTEEN;
            $data['report_title'] = "ALL_CANTEEN";
        }
        elseif ($report_type == 2 || $report_type == 5)
        {
            $query = "CALL SP_CANTEEN_REPORT('{$report_type}')";
            $data['table_head'] = CANTEEN_SALES_LIQUORS_ADDED;
            $data['report_title'] = "CANTEEN_SALES";
        }
        elseif ($report_type == 3 || $report_type == 4)
        {
            $query = "CALL SP_CANTEEN_REPORT('{$report_type}')";
            $data['table_head'] = ALL_CANTEEN;
            $data['report_title'] = "ALL_CANTEEN";
        }
       
        $response = $db->query($query);
        $result = $response->result_array();
        $data['table_data'] = $result;
        $db->close();
        return $data;
    }
}
