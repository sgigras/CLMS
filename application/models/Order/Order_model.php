<?php
class Order_model extends CI_Model{
   
   	public function __construct()
	{
		parent::__construct();
	}

	//-----------------------------------------------------
	function get_order_details($ordercode)
    {
		// $this->db->from('cart_details');
		// $this->db->where('order_code',$ordercode);
		// $query=$this->db->get();
        // return $query->result_array();

        $db = $this->db;
        // $query = "SELECT cd.id as cart_id,cd.is_order_placed,cd.order_time,cd.is_order_delivered,Concat(ld.brand,' ',ld.liquor_description,' ',ld.bottle_size) as liquor_name,ld.liquor_description_id as liquor_id,
        // cd.cart_type,ld.liquor_type,ld.liquor_ml,ld.liquor_image,cl.liquor_entity_id,cd.liquor_count,
        // cl.quantity as quantity, 
        // lem.selling_price * cl.quantity as total_quantity_cost FROM 
        // cart_details cd
        // INNER JOIN  cart_liquor cl ON cd.id=cl.cart_id 
        // INNER JOIN liquor_entity_mapping lem ON lem.id=cl.liquor_entity_id 
        // INNER JOIN liquor_details ld ON ld.liquor_description_id=lem.liquor_description_id where cd.order_code=? and cl.is_liquor_removed=0";


        $query = "SELECT cd.id as cart_id,cd.is_order_placed,cd.order_time,cd.is_order_delivered,Concat(ld.brand,' ',ld.liquor_description,' ',ld.bottle_size) as liquor_name,ld.liquor_description_id as liquor_id,
                    cd.cart_type,ld.liquor_type,ld.liquor_ml,ld.liquor_image,cl.liquor_entity_id,cd.liquor_count,
                    cl.quantity as quantity, 
                    IF(lem.selling_price,lem.minimum_order_quantity)as unit_lot_cost, 
                    IF((lem.selling_price * cl.quantity),(lem.minimum_order_quantity * cl.quantity))as total_quantity_cost FROM 
                    cart_details cd 
                    INNER JOIN  cart_liquor cl ON cd.id=cl.cart_id 
                    INNER JOIN liquor_entity_mapping lem ON lem.id=cl.liquor_entity_id 
                    INNER JOIN liquor_details ld ON ld.liquor_description_id=lem.liquor_description_id 
                    where cd.order_code=? and cl.is_liquor_removed=0";
        $response = $db->query($query, array($ordercode));
        $result = $response->result_array();
        return $result;
    }

	

}
?>