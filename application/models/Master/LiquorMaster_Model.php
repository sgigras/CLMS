<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LiquorMaster_Model
 *
 * @author Jitendra Pal
 * 23-10-21
 * 
 * library for liquor master and liquor state mapping
 * 
 */
class LiquorMaster_Model extends CI_Model
{

    //put your code here

    public function fetchAllLiquorRecords()
    {
        $db = $this->db;
         $query = "select (select brewery_name from master_brewery where id = ml.brewery_id) as brewery_name,ml.id,lb.brand as liquor_brand,ml.liquor_description as liquor_name,
                    mat.liquor_type as liquor_type,concat(lm.liquor_ml,' ML') as bottle_size from liquor_description ml
                    inner join liquor_type mat on mat.id=ml.liquor_type_id
                    inner join liquor_brand lb on lb.id=ml.liquor_brand_id
                    inner join liquor_ml lm on lm.id=ml.liquor_ml_id";

        $response = $db->query($query);
        $result = $response->result();
        return $result;
    }

    public function fetchInitialAlcoholFormDetails()
    {
        $db = $this->db;
        $result['title'] = trans('liquor_add');
        $result['mode'] = 'A';
        $result['alcohol_type_record'] = $this->getAlcoholType($db);
        $result['alcohol_brand_record'] = $this->getLiquorBrand($db);
        $result['bottle_size_record'] = $this->getBottleSize($db);
        $result['bottle_volume_record'] = $this->getBottleVolume($db);
        $result['brewery_data'] = $this->getBreweryData($db);
        $db->close();
        return $result;
    }

    public function getBreweryData($db)
    {
        $query = "Select id, brewery_name from master_brewery";
        $response = $db->query($query);
        $result = $response->result();
        return $result;
    }

    public function getBottleVolume($db)
    {
        $query = "Select id, liquor_ml as bottle_volume from liquor_ml";
        $response = $db->query($query);
        $result = $response->result();
        return $result;
    }

    public function getAlcoholType($db)
    {
        $query = "Select id,liquor_type as alcohol_type from liquor_type";
        $response = $db->query($query);
        $result = $response->result();
        return $result;
    }


    public function getBottleSize($db)
    {
        $query = "Select id, bottle_size as bottle_size from liquor_bottle_size";
        $response = $db->query($query);
        $result = $response->result();
        return $result;
    }

    public function getLiquorBrand($db)
    {

        $query = "Select id, brand as liquor_brand from liquor_brand";
        $response = $db->query($query);
        $result = $response->result();
        return $result;
    }

    public function fetchAlcoholDetails($id)
    {
        $db = $this->db;
        $result['title'] = trans('liquor_edit');
        $result['mode'] = 'E';

        // $query = "SELECT id,liquor_name,liquor_type as alcohol_type from master_liquor where id=$id";
        $query = "
        SELECT id,liquor_description as liquor_name,liquor_image,liquor_type_id as liquor_type,
        liquor_brand_id as liquor_brand,liquor_description,liquor_bottle_size_id as bottle_size,liquor_ml_id as bottle_volume ,brewery_id
        from liquor_description where id=?";
        $response = $db->query($query, array($id));
        // $response = $db->query($query);
        $result['liquor_data'] = $response->result();
        $result['alcohol_type_record'] = $this->getAlcoholType($db);
        $result['alcohol_brand_record'] = $this->getLiquorBrand($db);
        $result['bottle_size_record'] = $this->getBottleSize($db);
        $result['bottle_volume_record'] = $this->getBottleVolume($db);
        $result['brewery_data'] = $this->getBreweryData($db);
        $db->close();
        return $result;
    }

    public function insert_update_liquor_details($product_data)
    {
        $db = $this->db;
        $query = "CALL SP_INSERT_UPDATE_LIQUOR_DETAILS(?)";
        $response = $db->query($query, array($product_data));
        $result = $response->result();
        $db->close();
        return $result;
    }


    // public function insert_update_liquor_details($product_data)
    // {
    //     $db = $this->db;
    //     $query = "CALL SP_INSERT_UPDATE_LIQUOR_DETAILS('$product_data)";
    //     return $query;
    //     // $response = $db->query($query, array($product_data));
    //     // $result = $response->result();
    //     // $db->close();
    //     // return $result;
    // }

    //    public functi
}
