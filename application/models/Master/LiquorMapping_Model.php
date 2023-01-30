<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Description of LiquorMapping_Model
 *
 * @author Zeel Mewada
 * 25-10-21
 * 
 * library for liquor mapping
 * 
 */
class LiquorMapping_Model extends CI_Model
{
    //put your code here
    public function fetchAllLiquorMapRecords($entity_id)
    {
        $db = $this->db;
        $query = "SELECT lem.id,Concat(ld.brand,' ',ld.liquor_description,' ',ld.liquor_type) as liquor,ld.liquor_image,ld.liquor_ml,lem.purchase_price,lem.selling_price,lem.available_quantity,lem.actual_available_quantity,lem.reorder_level 
        FROM liquor_entity_mapping lem
        INNER JOIN liquor_details ld
        ON lem.liquor_description_id=ld.liquor_description_id
        WHERE entity_id='$entity_id'";
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result;
    }
    public function fetchLiquorBrand($liquor_brand_id)
    {
        $db = $this->db;
        $query = "SELECT  concat(ld.id,'#',IFNULL(ld.liquor_image,'')) as id,
                 concat(lb.brand,' ',ld.liquor_description) as liquor_name
                from liquor_description ld
                INNER JOIN liquor_brand lb on lb.id=ld.liquor_brand_id
                where liquor_type_id=?";
        $response = $db->query($query, array($liquor_brand_id));
        $result = $response->result();
        $db->close();
        return $result;
    }
    public function getLiquorTaxForPurchasePrice($liquor_description_id,$entity_id){
        $db = $this->db;
        $query = "CALL SP_GET_PURACHASE_PRICE_TAX('{$liquor_description_id}','{$entity_id}','100')";
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result;
    }
    public function getLiquorTaxForSellingPrice($liquor_description_id,$entity_id,$entity_type, $tax_type_id){
        $db = $this->db;
        $liquorArray = explode('#', $liquor_description_id);
        $query = "CALL SP_GET_SELLING_PRICE_TAX('{$liquorArray[0]}','{$entity_id}','{$entity_type}','100')";
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result;
    }
    public function fetchEntityList($entity_name_id)
    {
        $db = $this->db;
        $query = "SELECT id,entity_name from master_entities where entity_type=?";
        $response = $db->query($query, array($entity_name_id));
        $result = $response->result();
        $db->close();
        return $result;
    }
    public function fetchInitialAlcoholFormDetails()
    {
        $db = $this->db;
        $result['title'] = trans('liquor_add');
        $result['mode'] = 'A';
        $result['alcohol_type_record'] = $this->getAlcoholType($db);
        $result['entity_type_record'] = $this->getEntityType($db);
        $result['ml_record'] = $this->getml($db);
        // $result['']
        $db->close();
        return $result;
    }
    public function getAlcoholType($db)
    {
        $query = "Select id,liquor_type from liquor_type";
        $response = $db->query($query);
        $result = $response->result();
        return $result;
    }
    public function getEntityType($db)
    {
        $user_id = $this->session->userdata('admin_id');
        $query = "SELECT entity_type FROM master_entities WHERE id IN 
        (SELECT entity_id FROM ci_admin WHERE admin_id='$user_id')";
        $response = $db->query($query);
        $result = $response->result();
        return $result;
    }
    public function getml($db)
    {
        $query = "Select id,liquor_ml from liquor_ml";
        $response = $db->query($query);
        $result = $response->result();
        return $result;
    }
    // public function fetchAlcoholDetails($id)
    // {
    //     $db = $this->db;
    //     $result['title'] = trans('liquor_edit');
    //     $result['mode'] = 'E';
    //     // $query = "SELECT id,liquor_name,liquor_type as alcohol_type from master_liquor where id=$id";
    //     $query = "SELECT lem.id,Concat(ld.brand,' ',ld.liquor_description,' ',ld.liquor_type) as liquor,ld.liquor_image,ld.liquor_ml,lem.minimum_order_quantity,lem.purchase_price,lem.selling_price,(lem.selling_price-lem.purchase_price) as profit,lem.reorder_level,lem.available_quantity 
    //     FROM liquor_entity_mapping lem
    //     INNER JOIN liquor_details ld
    //     ON lem.liquor_description_id=ld.liquor_description_id
    //     WHERE lem.id='$id'";
    //     $response = $db->query($query);
    //     $result['liquor_data'] = $response->result();
    //     // $result['alcohol_type_record'] = $this->getAlcoholType($db);
    //     // $result['entity_type_record'] = $this->getEntityType($db);
    //     // $result['ml_record'] = $this->getml($db);
    //     $db->close();
    //     return $result;
    // }
    public function fetchAlcoholDetails($id)
    {
        $db = $this->db;
        $result['title'] = trans('liquor_edit');
        $result['mode'] = 'E';
        // $query = "SELECT id,liquor_name,liquor_type as alcohol_type from master_liquor where id=$id";
        $query = "SELECT lem.id,Concat(ld.brand,' ',ld.liquor_description,' ',ld.liquor_type) as liquor,ld.liquor_image,ld.liquor_ml,lem.minimum_order_quantity,lem.base_price,lem.purchase_price,lem.selling_price,(lem.selling_price-lem.purchase_price) as profit,lem.reorder_level,lem.available_quantity,lem.actual_available_quantity  
        FROM liquor_entity_mapping lem
        INNER JOIN liquor_details ld
        ON lem.liquor_description_id=ld.liquor_description_id
        WHERE lem.id='$id'";
        $response = $db->query($query);
        $result['liquor_data'] = $response->result();
        // $result['alcohol_type_record'] = $this->getAlcoholType($db);
        // $result['entity_type_record'] = $this->getEntityType($db);
        // $result['ml_record'] = $this->getml($db);
        $db->close();
        return $result;
    }
    public function insert_update_liquor_mapping_details($product_data)
    {
        $db = $this->db;
        $query = "CALL SP_INSERT_UPDATE_LIQUOR_MAPPING_DETAILS('$product_data')";
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result;
    }
    public function addStock($product_data)
    {
        $db = $this->db;
        $query = "CALL SP_ADD_STOCK('$product_data')";
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result;
    }
}
