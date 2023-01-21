<?php

class Canteen_report_model extends CI_Model{
    
     public function fetchCanteenDetails($report_type)
    {
        $db = $this->db;
        switch($report_type){
            case 1:
            
            $query = "
            SELECT me.id,me.entity_name,mt.entity_type AS canteen_club,ms.state,CONCAT(ch.user_rank,'. ',ch.firstname,' ',IFNULL(ch.lastname,''))AS chairman,
            CONCAT(ce.user_rank,'. ',cs.firstname,' ',IFNULL(cs.lastname,'')) AS supervisor,CONCAT(ce.user_rank,'. ',ce.firstname,' ',IFNULL(ce.lastname,'')) AS executive
            FROM master_entities me
            INNER JOIN master_state ms ON me.state=ms.id
            INNER JOIN master_entity_type mt ON me.entity_type=mt.id
            INNER JOIN ci_admin cs ON cs.admin_id=me.supervisor
            INNER JOIN ci_admin ch ON ch.admin_id=me.chairman
            INNER JOIN ci_admin ce ON ce.admin_id=me.executive
            where mt.entity_type NOT IN ('Brewery','consumer')";
            $data['table_head'] = ALL_CANTEEN;
            $data['report_title'] = "ALL_CANTEEN";
            break;
            case 2:
            $query = "
            select entity_name from master_entities where id in (select distinct entity_id from liquor_stock_sales)";
            $data['table_head'] = CANTEEN_SALES_LIQUORS_ADDED;
            $data['report_title'] = "CANTEEN_SALES";
            break;
            case 3:
            $query = "
            SELECT me.id,me.entity_name,mt.entity_type AS canteen_club,ms.state,CONCAT(ch.user_rank,'. ',ch.firstname,' ',IFNULL(ch.lastname,''))AS chairman,
            CONCAT(ce.user_rank,'. ',cs.firstname,' ',IFNULL(cs.lastname,'')) AS supervisor,CONCAT(ce.user_rank,'. ',ce.firstname,' ',IFNULL(ce.lastname,'')) AS executive
            FROM master_entities me
            INNER JOIN master_state ms ON me.state=ms.id
            INNER JOIN master_entity_type mt ON me.entity_type=mt.id
            INNER JOIN ci_admin cs ON cs.admin_id=me.supervisor
            INNER JOIN ci_admin ch ON ch.admin_id=me.chairman
            INNER JOIN ci_admin ce ON ce.admin_id=me.executive
            where mt.entity_type NOT IN ('Brewery','consumer') AND (chairman NOT IN ('1898','1919','1905') or executive NOT IN ('1898','1919','1905') or supervisor NOT IN ('1898','1919','1905'))
            ";
            $data['table_head'] = ALL_CANTEEN;
            $data['report_title'] = "RIGHTS_GIVEN";
            break;
            case 4:
            $query = "
            SELECT me.id,me.entity_name,mt.entity_type AS canteen_club,ms.state,CONCAT(ch.user_rank,'. ',ch.firstname,' ',IFNULL(ch.lastname,''))AS chairman,
            CONCAT(ce.user_rank,'. ',cs.firstname,' ',IFNULL(cs.lastname,'')) AS supervisor,CONCAT(ce.user_rank,'. ',ce.firstname,' ',IFNULL(ce.lastname,'')) AS executive
            FROM master_entities me
            INNER JOIN master_state ms ON me.state=ms.id
            INNER JOIN master_entity_type mt ON me.entity_type=mt.id
            INNER JOIN ci_admin cs ON cs.admin_id=me.supervisor
            INNER JOIN ci_admin ch ON ch.admin_id=me.chairman
            INNER JOIN ci_admin ce ON ce.admin_id=me.executive
            where mt.entity_type IN ('Brewery','consumer') OR (chairman IN ('1898','1919','1905') AND executive IN ('1898','1919','1905') AND supervisor IN ('1898','1919','1905'))
            ";
            $data['table_head'] = ALL_CANTEEN;
            $data['report_title'] = "RIGHTS_NOT_GIVEN";
            break;
            case 5:
            $query = "
            select entity_name from master_entities where id in ( select distinct entity_id from liquor_entity_mapping)";
            $data['table_head'] = CANTEEN_SALES_LIQUORS_ADDED;
            $data['report_title'] = "CANTEEN_LIQUORS_ADDED";
            break;
            default:
            echo "Something Goes Wrong";
        }
        
        $response = $db->query($query);
        $result = $response->result_array();
        $data['table_data'] = $result;
        $db->close();
        return $data;

    }
}
