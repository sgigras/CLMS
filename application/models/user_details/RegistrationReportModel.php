<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RegistrationReportModel extends CI_Model
{
    public function fetchRegisteredRetireeDetails($entity_id, $report_type)
    {
        switch ($report_type) {
            case 1:
                $where_clause = "rvd.is_verified=0 AND rvd.action=0 AND";
                $data['report_title'] = "Verification Pending";
                break;
            case 2:
                $where_clause = "rvd.is_verified=1 AND rvd.action=1 AND";
                $data['report_title'] = "Verification Approved";
                break;
            case 3:
                $where_clause = "rvd.is_verified=1 AND rvd.action=0 AND";
                $data['report_title'] = "Verification Denied";
                break;
            default:
                $where_clause = "";
                $data['report_title'] = "Verification Denied";
                break;
        }

        $query = "SELECT (@cnt := @cnt + 1) AS rowNumber,bh.irla,bh.rank,bh.name,bh.email_id,bh.mobile_no,cn.firstname as requested_by,
                ca.firstname as approval_from,bh.posting_unit,date_format(bh.retirement_date,'%d-%m-%Y') AS retirement_date,
            date_format(rvd.requested_time,'%d-%m-%Y %H:%i:%s') AS request_time, 
            (CASE
                WHEN rvd.is_verified=0 AND rvd.action=0 THEN 'Verification Pending'
                WHEN rvd.is_verified=1 AND rvd.action=1 THEN 'Approved'
                ELSE 'REJECTED'
            END) AS retiree_status
            FROM retiree_verification_details rvd 
            CROSS JOIN (SELECT @cnt := 0) AS dummy
            INNER JOIN ci_admin ca ON ca.admin_id=rvd.approval_by
            INNER JOIN ci_admin cn ON cn.admin_id=rvd.requested_by
            INNER JOIN bsf_hrms_data bh ON bh.id=rvd.hrms_id
            INNER JOIN master_entities me ON me.id=rvd.entity_id
            WHERE $where_clause rvd.entity_id=? AND rvd.isactive=1 ORDER BY rvd.id DESC";

        $db = $this->db;
        $response = $db->query($query, array($entity_id));
        $result = $response->result_array();
        // $data['table_head'] = RETIREE_VERIFICATION_CANTEEN_STATUS;
        // $data['table_data'] = $result;
        $db->close();
        return $result;
    }
}
