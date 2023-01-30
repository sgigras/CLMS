<?php defined('BASEPATH') or exit('No direct script access allowed');

class LiquorMaster_Model extends CI_Model
{
    public function fetchAllLiquorRecords()
    {
        $db = $this->db;
         $query = "select
                        ml.id,
                        ml.liquor_description as liquor_name,
                        FN_GET_BREWERY_NAME(ml.brewery_id) as brewery_name,
                        FN_GET_LIQUOR_BRAND(ml.liquor_brand_id) as liquor_brand,
                        FN_GET_LIQUOR_TYPE(ml.liquor_type_id) as liquor_type,
                        FN_GET_LIQUOR_ML(ml.liquor_ml_id) as bottle_size
                    from liquor_description ml
                    ";

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
        $query = "CALL SP_INSERT_UPDATE_LIQUOR_DETAILS('".$product_data."')";
        $response = $db->query($query, array($product_data));
        $result = $response->result();
        $db->close();
        return $result;
    }
}
