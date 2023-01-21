<?php
class Brewery_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}

	//-----------------------------------------------------
	function get_role_by_id($id)
	{
		$this->db->from('ci_admin_roles');
		$this->db->where('admin_role_id', $id);
		$query = $this->db->get();
		return $query->row_array();
	}

	//-----------------------------------------------------
	function get_all()
	{
		$this->db->from('master_brewery');
		$query = $this->db->get();
		return $query->result_array();
	}

	//-----------------------------------------------------
	function insert()
	{
		$this->db->set('brewery_name', checkIMPalpha($this->input->post('brewery_name')));
		$this->db->set('address', $this->input->post('breweryaddress'));
		$this->db->set('contact_person_name', checkIMPalpha($this->input->post('contactperson')));
		$this->db->set('mobile_no', $this->input->post('mobilenumber'));
		$this->db->set('mail_id', checkIMPemail($this->input->post('emailaddress')));
		$this->db->set('state', implode(',', $this->input->post('select_brewerystate')));
		// $this->db->set('serving_entity',implode(',', $this->input->post('select_breweryentity')));
		$this->db->insert('master_brewery');
	}

	function insertToEntityTable()
	{
		$this->db->set('entity_name', $this->input->post('brewery_name'));
		$this->db->set('entity_type', '1');
		$this->db->set('address', $this->input->post('breweryaddress'));
		// $this->db->set('brewery_contactperson',$this->input->post('contactperson'));
		$this->db->set('chairman_mobileno', $this->input->post('mobilenumber'));
		$this->db->set('chairman_mailid', $this->input->post('emailaddress'));
		$this->db->set('state', implode(',', $this->input->post('select_brewerystate')));
		$this->db->set('creation_time', date('Y-m-d H:i:s'));
		$this->db->set('created_by', $this->session->userdata('admin_id'));
		// $this->db->set('serving_entity',implode(',', $this->input->post('select_breweryentity')));
		$this->db->insert('master_entities');
	}

	//-----------------------------------------------------
	function update()
	{
		$this->db->set('admin_role_title', $this->input->post('admin_role_title'));
		$this->db->set('admin_role_status', $this->input->post('admin_role_status'));
		$this->db->set('admin_role_modified_on', date('Y-m-d h:i:sa'));
		$this->db->where('admin_role_id', $this->input->post('admin_role_id'));
		$this->db->update('ci_admin_roles');
	}

	//-----------------------------------------------------
	function change_status()
	{
		$this->db->set('admin_role_status', $this->input->post('status'));
		$this->db->where('admin_role_id', $this->input->post('id'));
		$this->db->update('ci_admin_roles');
	}

	//-----------------------------------------------------
	function delete($id)
	{
		$this->db->where('admin_role_id', $id);
		$this->db->delete('ci_admin_roles');
	}

	//-----------------------------------------------------
	function get_modules()
	{
		$this->db->from('module');
		$this->db->order_by('sort_order', 'asc');
		$query = $this->db->get();
		return $query->result_array();
	}

	//-----------------------------------------------------
	function set_access()
	{
		if ($this->input->post('status') == 1) {
			$this->db->set('admin_role_id', $this->input->post('admin_role_id'));
			$this->db->set('module', $this->input->post('module'));
			$this->db->set('operation', $this->input->post('operation'));
			$this->db->insert('module_access');
		} else {
			$this->db->where('admin_role_id', $this->input->post('admin_role_id'));
			$this->db->where('module', $this->input->post('module'));
			$this->db->where('operation', $this->input->post('operation'));
			$this->db->delete('module_access');
		}
	}
	//-----------------------------------------------------
	function get_access($admin_role_id)
	{
		$this->db->from('module_access');
		$this->db->where('admin_role_id', $admin_role_id);
		$query = $this->db->get();
		$data = array();
		foreach ($query->result_array() as $v) {
			$data[] = $v['module'] . '/' . $v['operation'];
		}
		return $data;
	}

	/* SIDE MENU & SUB MENU */

	//-----------------------------------------------------
	function get_all_module()
	{
		$this->db->select('*');
		$this->db->order_by('sort_order', 'asc');
		$query = $this->db->get('module');
		return $query->result_array();
	}

	//-----------------------------------------------------
	function add_module($data)
	{
		$this->db->insert('module', $data);
		return true;
	}

	//---------------------------------------------------
	// Edit Module
	public function edit_module($data, $id)
	{
		$this->db->where('module_id', $id);
		$this->db->update('module', $data);
		return true;
	}

	//-----------------------------------------------------
	function delete_module($id)
	{
		$this->db->where('module_id', $id);
		$this->db->delete('module');
	}

	//-----------------------------------------------------
	function get_module_by_id($id)
	{
		$this->db->from('module');
		$this->db->where('module_id', $id);
		$query = $this->db->get();
		return $query->row_array();
	}

	/*------------------------------
		Sub Module / Sub Menu  
	------------------------------*/

	//-----------------------------------------------------
	function add_sub_module($data)
	{
		$this->db->insert('sub_module', $data);
		return $this->db->insert_id();
	}

	//-----------------------------------------------------
	function get_sub_module_by_id($id)
	{
		$this->db->from('sub_module');
		$this->db->where('id', $id);
		$query = $this->db->get();
		return $query->row_array();
	}

	//-----------------------------------------------------
	function get_sub_module_by_module($id)
	{
		$this->db->select('*');
		$this->db->where('parent', $id);
		$this->db->order_by('sort_order', 'asc');
		$query = $this->db->get('sub_module');
		return $query->result_array();
	}

	//----------------------------------------------------
	function edit_sub_module($data, $id)
	{
		$this->db->where('id', $id);
		$this->db->update('sub_module', $data);
		return true;
	}

	//-----------------------------------------------------
	function delete_sub_module($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('sub_module');
		return true;
	}


	//FETCH STATES LIST FROM DATABASE
	function getStates()
	{
		$this->db->from('master_state');
		$query = $this->db->get();
		return $query->result_array();
	}

	//Fetch Brewery List From Database
	function getBrewery()
	{
		$this->db->from('master_brewery');
		$query = $this->db->get();
		return $query->result_array();
	}

	//Fetch Brand name from liquor_details table
	function getBrandName()
	{
		$this->db->select("liquor_description_id, concat(brand,' ',liquor_description,' - ',liquor_type) as ln");
		$this->db->from('liquor_details');
		$this->db->group_by('ln');
		$query = $this->db->get();
		return $query->result_array();
	}

	//Fetch Depot Name from master_entites table
	function getDepotName()
	{
		$this->db->select("me.id,me.entity_name,mt.entity_type AS canteen_club,CONCAT(cc.firstname,' ',IFNULL(cc.lastname,''))AS chairman, CONCAT(IFNULL(cs.firstname,'N.A.'),' ',IFNULL(cs.lastname,'')) AS supervisor,CONCAT(IFNULL(ce.firstname,'N.A.'),' ',IFNULL(ce.lastname,'')) AS executive");
		$this->db->from('master_entities as me');
		$this->db->join("master_entity_type as mt", "find_in_set(me.entity_type,mt.id)<> 0",false);
		$this->db->join("ci_admin as cs", "find_in_set(cs.admin_id,me.supervisor)<> 0", false);
		$this->db->join("ci_admin as cc", "find_in_set(cc.admin_id,me.chairman)<> 0", false);
		$this->db->join("ci_admin as ce", "find_in_set(ce.admin_id,me.executive)<> 0", false);
		$this->db->where("mt.entity_type not in('brewery','consumer','sub-depot')");

		// $this->db->where('entity_type = 3');
		$this->db->group_by('entity_name');
		$query = $this->db->get();
		return $query->result_array();
	}



	function getDepotNameold()
	{
		$this->db->select("bm.id,me.entity_name,concat(ld.brand,' ',ld.liquor_description,' ',ld.liquor_type) as liquor_brand");
		$this->db->from("brand_stockist_mapping as bm");
		$this->db->join("master_entities as me","find_in_set(me.id=bm.entity_id)<>0",false);
		$this->db->join("liquor_details as ld","find_in_set(ld.liquor_description_id=bm.brand_id)<>0", 'left');
		// $this->db->groupBy("entity_name");
		$query = $this->db->get();
		return $query->result_array();
	}





	//Fetch Brewery Name from master_brewery table
	function getBreweryName()                           
	{
		$this->db->select('id,entity_name');
		$this->db->from('master_brewery');
		// $this->db->where('entity_type = 3');
		$this->db->group_by('brewery_name');
		$query = $this->db->get();
		return $query->result_array();
	}

	function getentities()
	{
		$this->db->from('master_entities');
		$query = $this->db->get();
		return $query->result_array();
	}

	function getAllBrewery()
	{
		$this->db->select('id,brewery_name');
		$this->db->from('master_brewery');
		$query = $this->db->get();
		return $query->result_array();
	}

	//Fetches list of brewery
	function getBreweryList()
	{

		$this->db->select('MB.id,MB.brewery_name,MB.address,MB.contact_person_name,MB.mobile_no,MB.mail_id, group_concat(DISTINCT MS.state) as state,group_concat(DISTINCT ME.entity_name) as serving_entity');
		$this->db->from('master_brewery as MB');
		// $this->db->join('master_state as MS', 'MS.id = MB.state', 'left');
		$this->db->join("master_state as MS", "find_in_set(MS.id,MB.state)<> 0", "left", false);
		$this->db->join("master_entities as ME", "find_in_set(ME.id,MB.serving_entity)<> 0", "left", false);
		$this->db->group_by('MB.id');
		// $this->db->join('master_entities as ME', 'ME.id = MB.serving_entity', 'left');

		// $this->db->where('FIND_IN_SET(MS.id, MB.state)');
		// $this->db->or_where('FIND_IN_SET(ME.id, MB.serving_entity)');
		// $this->db->where_in('taxid', $taxid);
		// $this->db->where_in('stateid', $statesidlist);
		$query = $this->db->get();
		// $querypreview=$this->db->last_query();
		// print_r($querypreview);
		// die();
		return $query->result_array();
	}

	//Fetches states mapped with brewery
	function getBreweryMappedList($breweryid)
	{
		$this->db->select('liquorbrand');
		$this->db->where('id', $breweryid);
		$query = $this->db->get('master_brewery');
		return $query->row_array();
	}



	//Fetch Brand Mapping List Query
	function getBrandMappedList($breweryid)
	{
		$this->db->select("brand_id");
		$this->db->where("entity_id",$breweryid);
		$query = $this->db->get("brand_stockist_mapping");
		// $this->db->select("bm.id,me.entity_name,concat(ld.brand,'',ld.liquor_description,'',' - ',ld.liquor_type) as brand_name");
		// $this->db->from("brand_stockist_mapping as bm");
		// $this->db->join("master_entities as me","find_in_set(me.id,bm.entity_id)<>0",false);
		// $this->db->join("liquor_details as ld","find_in_set(ld.liquor_description_id,bm.brand_id)<>0",false);
		// // $this->db->where("bm.id",$stockistid);
		// $this->db->where('id',$stockistid);
		// $query = $this->db->get();
		return $query->row_array();
	}






	//Fetches states mapped with brewery
	function mapBreweryToStates($breweryid, $data)
	{
		$this->db->where('id', $breweryid);
		$this->db->update('master_brewery', $data);
		return true;
	}

	//Map Stockist to brand query
	function mapStockistToBrand($stockistid, $data)
	{
		$this->db->select("entity_id");
		 $this->db->where("entity_id",$stockistid);
		 $response = $this->db->get("brand_stockist_mapping");
		 $result = $response->result_array();
		// $result[0]['entity_id']	$this->db->update("brand_stockist_mapping",$data);

		// print_r($result);
		// die();
		if (count($result)>0)
		{
			// $this->db->set("brand_id", $this->db->post("brand_id"));
			$this->db->where("entity_id",$stockistid);
			$this->db->update("brand_stockist_mapping",$data);
			return true;
		}
		else
		{
			$this->db->where('entity_id',$stockistid);
			$this->db->insert('brand_stockist_mapping',$data);
			return true;
		}
		
	}




	public function fetchInitialEntityFormDetails()
	{
		$data['title'] = trans('add_new_brewery');
		$data['mode'] = 'A';
		$data['state_record'] = $this->getStates();
		$data['entities_record'] = $this->getentities();
		return $data;
	}



	public function fetchBreweryDetails($id)
	{
		$this->db->select('MB.id,MB.brewery_name,MB.address,MB.contact_person_name,MB.mobile_no,MB.mail_id, MB.state,MB.serving_entity,MB.isactive');
		$this->db->from('master_brewery as MB');
		// $this->db->join('master_state as MS', 'MS.id = MB.state', 'left');
		$this->db->join("master_state as MS", "find_in_set(MS.id,MB.state)<> 0", "left", false);
		$this->db->join("master_entities as ME", "find_in_set(ME.id,MB.serving_entity)<> 0", "left", false);
		$this->db->where('MB.id', $id);
		$this->db->group_by('MB.id');
		$query = $this->db->get();
		$brewery_result = $query->result();

		$data['brewery_data'] = $brewery_result;
		// $brewery_name = $brewery_result[0]->brewery_name;
		// $address = $brewery_result[0]->address;
		// $contact_person_name = $brewery_result[0]->contact_person_name;
		// $mobile_no = $brewery_result[0]->mobile_no;
		// $mail_id = $brewery_result[0]->mail_id;
		$state = $brewery_result[0]->state;

		//CONVERTIN COMMA SEPARATED STATE IDS TO ARRAY
		$stateid_array = explode(",", $state);
		$data['brewery_data'][0]->state = $stateid_array;

		$serving_entity = $brewery_result[0]->serving_entity;

		//CONVERTING COMMA SEPARATED ENTITY IDS TO ARRAY
		$servingentity_array = explode(",", $serving_entity);
		// $data['brewery_data'][0]->serving_entity=$servingentity_array;
		// $isactive = $brewery_result[0]->isactive;

		//GET IDS AND NAMES OF STATES MAPPED WITH BREWERY
		$this->db->select('id,state');
		$this->db->from('master_state as MS');
		$this->db->where_in('MS.id', $stateid_array);
		$statequery = $this->db->get();
		$stateresult = $statequery->result();

		//GET ENTITIES MAPPED WITH BREWERY
		$this->db->select('ME.id,ME.entity_name');
		$this->db->from('master_entities as ME');
		$this->db->where_in('ME.id', $servingentity_array);
		$servingentityquery = $this->db->get();
		$servingentityresult = $servingentityquery->result();


		$data['title'] = trans('edit_brewery_list');
		$data['mode'] = 'E';
		$data['state_array'] = $stateresult;

		$this->db->from('master_state');
		$allstatesquery = $this->db->get();
		$allstatesqueryresponse = $allstatesquery->result();

		$this->db->from('master_entities');
		$allentitiesquery = $this->db->get();
		$allentitiesqueryresponse = $allentitiesquery->result_array();

		$data['state_record'] = $allstatesqueryresponse;
		$data['entities_record'] = $allentitiesqueryresponse;
		return $data;
	}



	//Fetches states mapped with brewery
	function updateBreweryDetails($breweryid, $data)
	{
		$this->db->where('id', $breweryid);
		$this->db->update('master_brewery', $data);
		return true;
	}
}
