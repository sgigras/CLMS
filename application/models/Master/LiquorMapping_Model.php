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

        // $query = "SELECT ml.id,ml.liquor_name,ml.liquor_image,mat.alcohol_type as liquor_type,date_format(ml.creation_time,'%d-%m-%Y %h:%i:%s') as creation_time "
        //         . "from master_liquor ml "
        //         . "INNER JOIN master_alcohol_type mat on mat.id=ml.liquor_type";

        //                $query = "select ma.alcohol_type,ml.liquor_name,et.entity_type,me.entity_name,mm.alcohol_quantity,mp.minimum_order_quantity,
        // date_format(mp.creation_time,'%d-%m-%Y %h:%i:%s') as creation_time from mapping_product_entity mp 
        // inner join master_alcohol_type ma on mp.product_id=ma.id inner join master_liquor ml on mp.product_id=ml.liquor_type 
        // inner join master_entity_type et on mp.entity_type=et.id inner join master_entities me on mp.entity_id=me.entity_type 
        // inner join master_mm_alcohol mm on mp.ml_id=mm.id order by mp.id desc";

        // $query = "SeLect id,liquor_type from liquor_type";


        // $query = "SELECT lem.id,Concat(ld.brand,' ',ld.liquor_description,' ',ld.liquor_type) as liquor,ld.liquor_image,ld.liquor_ml,lem.purchase_price,lem.selling_price,lem.available_quantity 
        // FROM liquor_entity_mapping lem
        // INNER JOIN liquor_details ld
        // ON lem.liquor_description_id=ld.liquor_description_id
        // WHERE entity_id='$entity_id'";


        // $response = $db->query($query);
        // $result = $response->result();
        // $db->close();
        // return $result;

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
        $query = "SELECT  concat(ld.id,'#',ld.liquor_image) as id,
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
        $query = "SELECT SUM(tax_percent) AS tax_value,tax_type_id
        FROM master_tax_liquor_mapping 
        where liquor_description_id= ? AND entity_id= ? AND tax_id!='38' AND isactive='1' group by tax_type_id order by tax_type_id asc;";

        $response = $db->query($query, array($liquor_description_id,$entity_id));
        $result = $response->result();
        $db->close();
        return $result;
    }

    public function getLiquorTaxForSellingPrice($liquor_description_id,$entity_id,$entity_type, $tax_type_id){
        $db = $this->db;
        if($entity_type==3){
                    // fetch depot tax-----------$tax_type_id,$tax_category,
        // $query = "SELECT SUM(mtlm.tax_percent) AS tax_value,mtlm.tax_type_id,tc.id as tax_category_id,tc.tax_category
        //             FROM master_tax_liquor_mapping as mtlm
        //              inner join tax_category as tc on tc.id=mtlm.tax_category
        //              where mtlm.liquor_description_id=? and mtlm.tax_type_id=? and mtlm.tax_category=? AND mtlm.entity_id=? AND mtlm.isactive='1' group by mtlm.tax_type_id order by mtlm.tax_type_id asc";
        
        $query = "SELECT SUM(tax_percent) AS tax_value,tax_type_id
                  FROM master_tax_liquor_mapping 
                  where liquor_description_id=? AND entity_id=? AND isactive='1' group by tax_type_id order by tax_type_id asc";
        $response = $db->query($query, array($liquor_description_id,$tax_type_id,$entity_id));
        } else if($entity_type==2){
                   // fetch sub depot tax-------$tax_type_id,$tax_category,
        //  $query = "SELECT SUM(mtlm.tax_percent) AS tax_value,mtlm.tax_type_id,tc.id as tax_category_id,tc.tax_category
        //         FROM master_tax_liquor_mapping as mtlm
        //         inner join tax_category as tc on tc.id=mtlm.tax_category
        //          where mtlm.liquor_description_id=? and mtlm.tax_type_id=? and mtlm.tax_category=? AND mtlm.entity_id=? AND mtlm.isactive='1' group by mtlm.tax_type_id order by mtlm.tax_type_id asc";
         
        $query = "SELECT SUM(tax_percent) AS tax_value,tax_type_id
              FROM master_tax_liquor_mapping 
              where liquor_description_id=? AND entity_id=? AND isactive='1' group by tax_type_id order by tax_type_id asc";
        $response = $db->query($query, array($liquor_description_id,$tax_type_id,$entity_id)); //$tax_type_id,$tax_category,
        }
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
