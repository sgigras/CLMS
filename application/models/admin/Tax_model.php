<?php
class Tax_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}


	function getTaxes()
	{
		$this->db->from('master_tax');
		$query = $this->db->get();
		return $query->result_array();
	}

	function getTaxTypeId()
	{
		$this->db->select('tax_type_id');
		$this->db->from('master_tax_liquor_mapping');
		$query = $this->db->get();
		return $query->result_array();
	}



	function getStates()
	{
		$this->db->from('master_state');
		$query = $this->db->get();
		return $query->result_array();
	}

	function getDepots()
	{
		$this->db->from('master_entities');
		$this->db->where('entity_type', '3');
		$query = $this->db->get();
		return $query->result_array();
	}

	function getLiquor_Brands()
	{
		$this->db->select('liquor_description_id AS id,CONCAT(brand," ",liquor_type," ",liquor_description," ",bottle_size," ",liquor_ml," ml ") AS liquor_name');
		$this->db->from('liquor_details');
		$query = $this->db->get();
		return $query->result_array();
	}
	function getLiquorTypes()
	{
		$this->db->from('liquor_type');
		$query = $this->db->get();
		return $query->result_array();
	}

	function getBSFMarginData($stateid, $liquortypeid)
	{
		$query = $this->db->get_where('states_liquor_margin', array('stateid' => $stateid, 'liquortypeid' => $liquortypeid));
		return $query->result_array();
	}

	function mapnewBSFMarginData($stateid, $liquortypeid, $priceperbottle)
	{
		$query = $this->db->get_where('states_liquor_margin', array('stateid' => $stateid, 'liquortypeid' => $liquortypeid));

		if ($query->num_rows() > 0) {
			$this->db->where(array('stateid' => $stateid, 'liquortypeid' => $liquortypeid));
			$this->db->update('states_liquor_margin', array('amount' => $priceperbottle));
			return true;
		} else {
			$this->db->set(array('stateid' => $stateid, 'liquortypeid' => $liquortypeid));
			$this->db->insert('states_liquor_margin', array('stateid' => $stateid, 'liquortypeid' => $liquortypeid, 'amount' => $priceperbottle));
			return true;
		}
	}

	public function fetchEntities()
	{
		$query = "SELECT id,entity_type from master_entity_type where entity_type IN ('Depot','Sub-Depot')";
		$response = $this->db->query($query);
		$result = $response->result();
		return $result;
	}

	public function fetchTaxCategories(){
		$query = "select id,tax_category from tax_category";
		$response = $this->db->query($query);
		$result = $response->result();
		return $result;
	}
	public function fetchTaxCategory(){
		$query = "select tax_category from tax_category";
		$response = $this->db->query($query);
		$result = $response->result();
		return $result;
	}


	//Fetches states mapped with tax
	function getliquortaxMappedList($liquoridlist, $entity_id)
	{
		$liquoridlist = $this->db->escape($liquoridlist);
		// print($statesidlist);
		$liquoridlist = str_replace("'", "", $liquoridlist);
		// print($liquoridlist);
		// die();
		// $taxid = $this->db->escape(explode(',', $taxid));
		// $liquoridlist = $this->db->escape(explode(',', $liquoridlist));
		$liquoridlist = array_map('intval', explode(',', $liquoridlist));
		$liquoridlist = implode("','", $liquoridlist);
		// $fetchtaxmapping = "SELECT ms.id,ms.state,IFNULL((SELECT sm.tax_percent from states_taxes_mapping sm where sm.taxid= $taxid and sm.stateid=ms.id  ),'0') as interest_percent,
		// (SELECT sm.isactive from states_taxes_mapping sm where sm.taxid= $taxid and sm.stateid=ms.id  ) as isactive,(SELECT sm.id from states_taxes_mapping sm where sm.taxid= $taxid and sm.stateid=ms.id  ) as mappingid
		// FROM master_state ms where ms.id in('$statesidlist')";

		$fetchtaxmapping = "SELECT mt.id as taxid,mtlm.id as mappingid,tc.id as tax_category_id,mtlm.liquor_description_id,concat(mt.tax_name,' - ',tc.tax_category) as tax_name,mtlm.tax_percent,mtlm.isactive,mtlm.tax_type_id FROM master_tax mt inner join tax_category tc on tc.id = mt.tax_category_id LEFT JOIN master_tax_liquor_mapping mtlm ON mt.id = mtlm.tax_id AND mtlm.liquor_description_id in('9') AND mtlm.entity_id='$entity_id' and mt.entity_type=(SELECT entity_type FROM master_entities WHERE id='$entity_id') where tc.id !=1 order by tc.id;";
		$fetchtaxmappingresponse = $this->db->query($fetchtaxmapping);

		



		// $this->db->select('ST.id as mappingid,MS.state,MT.tax_name,ST.tax_amount');
		// $this->db->from('states_taxes_mapping AS ST');
		// $this->db->join('master_state as MS', 'MS.id = ST.stateid', 'left');
		// $this->db->join('master_tax as MT', 'MT.id = ST.taxid', 'left');
		// $this->db->where_in('taxid', $taxid);
		// $this->db->where_in('stateid', $statesidlist);
		// $query = $this->db->get();
		// return $query;
		// $querypreview=$this->db->last_query();
		// print_r($fetchtaxmappingresponse);
		// die();
		// return $query->result_array();
		return $fetchtaxmappingresponse->result_array();
	}

	//ENABLE DISABLE MAPPING OF TAX WITH STATE
	function change_status()
	{
		$this->db->set('isactive', $this->input->post('status'));
		$this->db->where('id', $this->input->post('id'));
		$this->db->update('master_tax_liquor_mapping');
	}

	//MAP NEW TAXES TO STATE
	public function addTaxToLiquor($data)
	{
		$result = $this->db->get_where('master_tax_liquor_mapping', array('liquor_description_id' => $data['liquor_description_id'], 'tax_id' => $data['tax_id'], 'entity_id' => $data['entity_id'], 'tax_category' => $data ['tax_category']));
		if ($result->num_rows() > 0) {
			return false;
		} else {
			$this->db->insert('master_tax_liquor_mapping', $data);
			return true;
		}
	}


	function updateTaxToLiquor($data)
	{
		$this->db->set('tax_percent', $data['tax_percent']);
		$this->db->set('tax_type_id', $data['tax_type_id']);
		$this->db->set('tax_category', $data['tax_category']);
		$this->db->where('id', $data['mappingid']);
		$this->db->update('master_tax_liquor_mapping');
	}
}
