<?php

class canteenMasterModel extends CI_Model{
    
     public function fetchCanteenDetails()
    {
        $db = $this->db;
        $query = "
        SELECT me.id,me.entity_name,mt.entity_type AS canteen_club,ms.state,CONCAT(ch.firstname,' ',IFNULL(ch.lastname,''))AS chairman,
        CONCAT(cs.firstname,' ',IFNULL(cs.lastname,'')) AS supervisor,CONCAT(ce.firstname,' ',IFNULL(ce.lastname,'')) AS executive
        FROM master_entities me
        INNER JOIN master_state ms ON me.state=ms.id
        INNER JOIN master_entity_type mt ON me.entity_type=mt.id
        INNER JOIN ci_admin cs ON cs.admin_id=me.supervisor
        INNER JOIN ci_admin ch ON ch.admin_id=me.chairman
        INNER JOIN ci_admin ce ON ce.admin_id=me.executive
        where mt.entity_type NOT IN ('Brewery','consumer')";
        $response = $db->query($query);
        $result = $response->result_array();
        $db->close();
        return $result;
    }
}

?>