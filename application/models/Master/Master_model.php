<!--  
 Author: SUJIT N. MISHRA
 Created on:23/10/2021
 Scope:  Master Model
 Source: -->
<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_model extends CI_Model {

// function to get tax list from database table-----------
	public function fetchTaxnameList(){
		$db = $this->db;
		$query =  "SELECT mt.id, mt.tax_name, tc.tax_category, DATE_FORMAT(mt.creation_time, '%d-%m-%Y') as creation_time, mt.created_by FROM master_tax as mt left join tax_category as tc on mt.tax_category_id = tc.id";
		$response= $db->query($query);
		$result = $response->result();
		$db->close();
		return $result;
	}

	// function to insert tax details into data table----------

	public function insert_tax_name($data){
		$db = $this->db;
        $response['tax'] = $db->insert('master_tax', $data);
        $response['tax_log'] = $db->insert('log_master_tax', $data);
        $db->close();
        return $response;

	}

	//function insert tax category---------------
	public function insert_tax_category($data){
		$db = $this->db;
		$query = "select tax_category from tax_category where tax_category = ?";
		$result = $db->query($query, array($data['tax_category']));
		if ($result->num_rows() == 0 || $result->num_rows() == "0"){
			$response['tax_category'] = $db->insert('tax_category', $data);
			$db->close();
			return $response;
		} else{
			$response = "Data already exist.";
			$db->close();
			return false;
		}
	}


	//Function to edit tax details 
	public function update_tax_name($data){
		$db = $this->db;
		$id= $data['id'];
		$tax_name = $data['tax_name'];
		$tax_category_id = $data['tax_category_id'];
		$entity_type=$data['entity_type'];
		$created_by = $data['created_by'];
        $query =  "UPDATE master_tax SET tax_name=?, tax_category_id=?, entity_type=?, creation_time = now(), created_by =? WHERE id = ?";
		$response['update_master_tax'] = $db->query($query, array($tax_name, $tax_category_id, $entity_type,  $created_by, $id));
		$insert_tax_log ="INSERT into log_master_tax (master_tax_id, tax_name, tax_category_id, entity_type, creation_time, created_by) select id, tax_name, tax_category_id, entity_type , now(), ? from master_tax where id = ?";
		$response['insert_log'] = $db->query($insert_tax_log,array($created_by, $id));
		$db->close();
        return $response;

	}

	// function to get tax list from database table for editing tax details-----------

	public function fetchTaxDetails($id){
		$db = $this->db;
		$tax_query =  "SELECT mt.id, mt.tax_name, tc.tax_category, mt.creation_time, mt.created_by FROM master_tax as mt left join tax_category as tc on mt.tax_category_id = tc.id where mt.id = ?";
		$tax_response = $db->query($tax_query, array($id));

		$tax_result = $tax_response->result();
		$db->close();
		return $tax_result;

	} 

	// function to get liquor list from database table-----------

	public function fetchLiquorlist(){
		$db = $this->db;
		$query = "SELECT id, liquor_type FROM liquor_type";
		$response= $db->query($query);
		$result = $response->result();
		$db->close();
		return $result;

	
	}
	// function to insert liquor details into data table----------	

	public function insert_alcohol_name($data){
		$db = $this -> db;
		$response['alcohol'] = $db->insert('liquor_type', $data);
		$response['alcohol_log'] = $db->insert('log_master_liquor', $data);
		 $db->close();
        return $response;
    }

    //Function to edit liquor details 


    public function update_liquor_name($data){
    	$db = $this->db;
    	$id = $data['id'];
    	$alcohol_type = $data['alcohol_type'] ;
    	$created_by = $data['created_by'] ;

    	$query =  "UPDATE liquor_type SET liquor_type = ?, created_by=?, creation_time = now()  WHERE id =?";
    	$response['update'] = $db->query($query,array($alcohol_type, $created_by, $id));
    	$insert_alcohol_log = "INSERT INTO log_master_liquor (master_liquor_id, liquor_name, created_by, creation_time) select id, liquor_type, '$created_by', now() from liquor_type where id = '$id'";
    	$response['insert_log'] = $db->query($insert_alcohol_log);
    	$db->close();
    	
    	return $response;


    }


    // function to get liquor list from database table for editing liquor details-----------

    public function fetchAlcoholDetails ($id){
    	$db = $this->db;
		$query = "SELECT id, liquor_type FROM liquor_type WHERE ID = ?";
		$response= $db->query($query,array($id));
		$result = $response->result();
		$db->close();
		return $result;
	}

	//function fetch states for dropdown 


	public function fetchState() {
		$db = $this->db;
        $query = "Select id,state from master_state";
        $response = $db->query($query);
        $result = $response->result();
        return $result;
    }

    // function to get city and state list from database table-----------

    public function fetchInitialCityDetails(){
    	$db = $this->db;
    	$query =  "SELECT C.id, C.city_district_name, S.state FROM master_city_district as C inner join master_state as S  on C.stateid = S.id;";
    	$response = $db->query($query);
    	$result = $response->result();
    	$db->close();
    	return $result;
    }

	// function to insert city and state details into data table----------	


    public function insertCityDetails($data){

    	$db = $this ->db;
    	$id =$data['stateid'];
    	$city_name = $data['city_district_name'];
    	
    	$response = $db->insert('master_city_district', $data);
    	$response = $db->insert('log_master_city_district', $data);
    	$db->close();
    	return $response;
    }

     //Function to edit city and state details

    public function updateCityMaster($data){
    	$db= $this->db;
    	$id = $data['id'];
    	$stateid =  $data['stateid'];
    	$city_name = $data['city_district_name'];
    	$created_by = $data['created_by'] ;
    	$query =" UPDATE master_city_district SET city_district_name=?, stateid=?, creation_time = now(), created_by =? WHERE id =?";
    	$response['update'] = $db->query($query, array($city_name, $stateid,  $created_by ,$id));
    	$insert_city_log = "insert into log_master_city_district (master_city_district_id, city_district_name, stateid, creation_time, created_by) select id, city_district_name, stateid, now(), ? from master_city_district where id = ? ";
    	$response['insert'] = $db->query($insert_city_log,array($created_by,$id));

    	$db->close();
    	return $response;
    }

    // function to get city and state  list from database table for editing city and state details-----------

    public function fetchCityMasterDetails($id){
    	$db = $this->db;
    	$query =  "SELECT  C.id, C.city_district_name, S.state, C.stateid FROM master_city_district as C inner join master_state as S  on C.stateid = S.id WHERE C.id=?";
    	$response = $db->query($query,array($id));
    	$result = $response->result();
        $db->close();
        return $result;

    }

    // function to getalcohol quantity list from database table-----------


    public function fetchAlcoholQuantityList(){
    	$db = $this->db;
    	$query =  "SELECT id, liquor_ml FROM liquor_ml";
    	$response = $db->query($query);
    	$result =  $response->result();
    	$db->close();
    	return $result;


    }
    // function to insert alcohol quantity into data table----------

    public function insertAlcoholQuantity($data){
    	$db = $this->db;
        $response= $db->insert('liquor_ml', $data);
        $db->close();
        return $response;
    }

	public function updateAlcoholQuantity($data){
    	$db = $this->db;
        $response= $db->insert('liquor_ml', $data);
        $db->close();
        return $response;
    }

	// function to get liquor brand list from database table-----------


    public function fetchLiquorBrandList(){
    	$db = $this->db;
    	$query =  "SELECT id, brand as liquor_brand from liquor_brand";
    	$response = $db->query($query);
    	$result =  $response->result();
    	$db->close();
    	return $result;

    }

	// function to insert liquor details into data table----------	

	public function insert_brand_name($data){
		$db = $this -> db;
		$response['brand'] = $db->insert('liquor_brand', $data);
		// $response['alcohol_log'] = $db->insert('log_master_liquor', $data);
		 $db->close();
        return $response;
    }

	//Function to edit liquor brand


    public function update_liquor_brand($data){

    	$db = $this->db;
    	$id = $data['id'];
    	$brand = $data['brand'] ;
    	$created_by = $data['created_by'] ;

    	$query =  "UPDATE liquor_brand SET brand = ? WHERE id =?";
    	$response['update'] = $db->query($query,array($brand, $id));
    	$insert_brand_log ="INSERT into log_liquor_brand (brand_id, brand, created_by, creation_time ) select id, brand, created_by, creation_time from liquor_brand where id = ?";
    	$response['insert_log'] = $db->query($insert_brand_log,array('$db'));
    	$db->close();
    	
    	return $response;


    }

	// function to get liquor band list from database table for editing liquor brand details-----------

    public function fetchBrandDetails ($id){
    	$db = $this->db;
		$query = "SELECT id, brand FROM liquor_brand WHERE ID = '$id'";
		$response= $db->query($query,array($id));
		$result = $response->result();
		$db->close();
		return $result;
	}
    

    
}





