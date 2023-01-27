<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Description of Cart_model
 *
 * @author ATS-16
 */
class User_details_model extends CI_Model
{
    public function fetchCanteens()
    {
        $db = $this->db;
        $query = "SELECT id,entity_name FROM master_entities";
        $response = $db->query($query);
        $result = $response->result_array();
        $db->close();
        return $result;
    }
    public function fetchVerificationDetails($entity_id, $mode)
    {
        $db = $this->db;
        // $db = $this->db;
        $query = "SELECT (@cnt := @cnt + 1) AS rowNumber,Concat(bh.rank,'. ',bh.name) as retiree_name,bh.irla as retiree_irla,bh.mobile_no,bh.email_id,bh.posting_unit,bh.retirement_date,
        CONCAT(ca.user_rank,'. ',ca.firstname) as approval_from,CONCAT(cr.user_rank,'. ',cr.firstname)as requested_by,date_format(rvd.requested_time,'%d-%m-%Y %H:%i:%s') as request_time 
        from retiree_verification_details rvd
        CROSS JOIN (SELECT @cnt := 0) AS dummy
        INNER JOIN ci_admin ca on ca.admin_id=rvd.approval_by
        INNER JOIN ci_admin cr on cr.admin_id=rvd.requested_by
        INNER JOIN bsf_hrms_data bh on bh.id=rvd.hrms_id 
        where rvd.entity_id='$entity_id' and rvd.is_verified=0";
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result;
    }
    // to fetch cartDetails
    public function fetchUserDetails($irla_no)
    {
        $db = $this->db;
        $query = "select 
            username as registration_status,
            username irla,
            firstname name,
            mobile_no,
            date_of_birth,
            user_rank `rank`,
            is_active status,
            email email_id,
            created_at creation_time,
            location_name,
            district_name,
            present_appoitment,
            state_name 
        from ci_admin where username={$irla_no}";
        $response = $db->query($query);
        $result = $response->result_array();
        $db->close();
        return $result;
    }
    public function fetchUserDetailsPostingUnit($posting_unit)
    {
        $db = $this->db;
        $query = "SELECT
                username as registration_status,
                username irla,
                firstname name,
                mobile_no,
                date_of_birth,
                user_rank `rank`,
                is_active status,
                email email_id,
                created_at creation_time,
                location_name,
                district_name,
                present_appoitment,
                state_name,
                UnitName posting_unit,
                frontier
            FROM ci_admin
            WHERE UnitName ='$posting_unit'";
        $response = $db->query($query);
        $result = $response->result_array();
        $db->close();
        return $result;
    }
    public function fetchCountUserDetails()
    {
        $db = $this->db;
        $query = "SELECT count(bh.irla) AS count_data FROM bsf_hrms_data bh
        LEFT JOIN ci_admin ca ON bh.irla=ca.username";
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result;
    }
    // public function 
    public function GetIrlaNumber($searchterm)
    {
        $db = $this->db;
        $query = "SELECT username as irla,CONCAT(username,' - ',firstname) AS option_data FROM ci_admin WHERE is_supper!='1' and username LIKE '%$searchterm%'";
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result;
    }
    public function GetCanteenName($searchterm)
    {
        $db = $this->db;
        $query = "SELECT id,entity_name AS option_data FROM master_entities WHERE entity_name LIKE '%$searchterm%'";
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result;
    }
    public function GetPostingUnit($searchterm)
    {
        $db = $this->db;
        $query = "SELECT distinct UnitName as posting_unit FROM ci_admin WHERE UnitName LIKE '%$searchterm%'";
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result;
    }
    public function update_user($irla, $dob, $mobile, $email)
    {
        $dob = ($dob == "" ? date('Y-m-d h:i:s') : $dob);
        $db = $this->db;
        $query = "UPDATE ci_admin SET mobile_no=$mobile,email='$email' WHERE username=$irla AND date_of_birth='$dob'";
        $response = $db->query($query);
        $message = "User Updated Successfully !!";
        $db->close();
        return $message;
    }
    public function getPostingWise($posting_unit, $mode, $personnel_type)
    {
        if ($mode == 'verification_pending') {
            $db = $this->db;
            $query = "SELECT
                (@cnt := @cnt + 1) AS rowNumber,
                username as irla,
                firstname as name,
                mobile_no,
                if(IFNULL(email,'')='','NA',email) as email_id,
                user_rank as `rank`,
                UnitName posting_unit,
                status
            from
                ci_admin
            WHERE UnitName = '{$posting_unit}' AND status='{$personnel_type}' and is_verify='0' order by rowNumber
            ";
            $response = $db->query($query);
            $result = $response->result();
            $db->close();
        } else if ($mode == 'verification_completed') {
            $db = $this->db;
            $query = "SELECT
                (@cnt := @cnt + 1) AS rowNumber,
                username as irla,
                firstname as name,
                mobile_no,
                if(IFNULL(email,'')='','NA',email) as email_id,
                user_rank as `rank`,
                UnitName posting_unit,
                status
            from
                ci_admin
            WHERE UnitName = '{$posting_unit}' AND status='{$personnel_type}' and is_verify='1' order by rowNumber
            ";
            $response = $db->query($query);
            $result = $response->result();
            $db->close();
        } else {
            $db = $this->db;
            $query = "SELECT
                (@cnt := @cnt + 1) AS rowNumber,
                username as irla,
                firstname as name,
                mobile_no,
                if(IFNULL(email,'')='','NA',email) as email_id,
                user_rank as `rank`,
                UnitName posting_unit,
                status
            from
                ci_admin
            WHERE UnitName = '{$posting_unit}' AND status='{$personnel_type}' order by rowNumber
            ";
            $response = $db->query($query);
            $result = $response->result();
            $db->close();
        }
        return $result;
    }
    public function get_all_pending()
    {
        $query = "select count(rvd.id) AS count
        from retiree_verification_details rvd 
        INNER JOIN ci_admin ca on ca.admin_id=rvd.approval_by
        INNER JOIN ci_admin cn on cn.admin_id=rvd.requested_by
        INNER JOIN bsf_hrms_data bh on bh.id=rvd.hrms_id
        INNER JOIN master_entities me on me.id=rvd.entity_id
        where rvd.is_verified=0 AND rvd.action=0";
        $db = $this->db;
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result[0]->count;
    }
    public function get_all_approved()
    {
        $query = "select count(rvd.id) AS count
        from retiree_verification_details rvd 
        INNER JOIN ci_admin ca on ca.admin_id=rvd.approval_by
        INNER JOIN ci_admin cn on cn.admin_id=rvd.requested_by
        INNER JOIN bsf_hrms_data bh on bh.id=rvd.hrms_id
        INNER JOIN master_entities me on me.id=rvd.entity_id
        where rvd.is_verified=1 AND rvd.action=1";
        $db = $this->db;
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result[0]->count;
    }
    public function get_all_denied()
    {
        $query = "select count(rvd.id) AS count
        from retiree_verification_details rvd 
        INNER JOIN ci_admin ca on ca.admin_id=rvd.approval_by
        INNER JOIN ci_admin cn on cn.admin_id=rvd.requested_by
        INNER JOIN bsf_hrms_data bh on bh.id=rvd.hrms_id
        INNER JOIN master_entities me on me.id=rvd.entity_id
        where rvd.is_verified=1 AND rvd.action=0";
        $db = $this->db;
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result[0]->count;
    }
    public function get_all_registered()
    {
        $query = "Select count(admin_id) As count 
        from ci_admin ca 
        inner join bsf_hrms_data bh on ca.username=bh.irla and ca.date_of_birth=bh.date_of_birth 
        Where bh.status='RETIRED'";
        $db = $this->db;
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result[0]->count;
    }
    public function get_all_registered_serving()
    {
        $query = "Select count(admin_id) As count 
        from ci_admin ca 
        inner join bsf_hrms_data bh on ca.username=bh.irla and ca.date_of_birth=bh.date_of_birth 
        Where bh.status='SERVING'";
        $db = $this->db;
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result[0]->count;
    }
    public function get_all_serving_users()
    {
        $query = "Select count(id) As count 
        from bsf_hrms_data 
        Where status='SERVING'";
        $db = $this->db;
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result[0]->count;
    }
    public function get_all_retired_users()
    {
        $query = "Select count(id) As count 
        from bsf_hrms_data 
        Where status='RETIRED'";
        $db = $this->db;
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result[0]->count;
    }
    public function get_all_entities()
    {
        $query = "Select count(id) As count 
        from master_entities
        Where entity_name not in ('TEST STOCKIST','TEST CANTEEN')";
        $db = $this->db;
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result[0]->count;
    }
    public function get_all_canteen_count()
    {
        $query = "Select count(id) As count 
        from master_entities
        where entity_type=2 and entity_name not in ('TEST STOCKIST','TEST CANTEEN')";
        $db = $this->db;
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result[0]->count;
    }
    public function get_all_stockist_count()
    {
        $query = "Select count(id) As count 
        from master_entities
        where entity_type=3 and entity_name not in ('TEST STOCKIST','TEST CANTEEN')";
        $db = $this->db;
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result[0]->count;
    }
    public function get_entities_started_sale_count()
    {
        $query = "Select count(id) As count 
        from master_entities
        where entity_name not in ('TEST STOCKIST','TEST CANTEEN') and id in (select distinct entity_id from liquor_stock_sales)";
        $db = $this->db;
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result[0]->count;
    }
    public function get_canteens_started_sale_count()
    {
        $query = "Select count(id) As count 
        from master_entities
        where entity_type=2 and entity_name not in ('TEST STOCKIST','TEST CANTEEN') and id in (select distinct entity_id from liquor_stock_sales)";
        $db = $this->db;
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result[0]->count;
    }
    public function get_stockists_started_sale_count()
    {
        $query = "Select count(id) As count 
        from master_entities
        where entity_type=3 and entity_name not in ('TEST STOCKIST','TEST CANTEEN') and id in (select distinct entity_id from liquor_stock_sales)";
        $db = $this->db;
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result[0]->count;
    }
    public function get_entities_right_given_count()
    {
        $query = "Select count(id) As count 
        from master_entities
        where entity_name not in ('TEST STOCKIST','TEST CANTEEN') AND (chairman NOT IN ('1898','1919','1905') or executive NOT IN ('1898','1919','1905') or supervisor NOT IN ('1898','1919','1905'))";
        $db = $this->db;
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result[0]->count;
    }
    public function get_canteen_right_given_count()
    {
        $query = "Select count(id) As count 
        from master_entities
        where entity_type=2 and entity_name not in ('TEST STOCKIST','TEST CANTEEN') AND (chairman NOT IN ('1898','1919','1905') or executive NOT IN ('1898','1919','1905') or supervisor NOT IN ('1898','1919','1905'))";
        $db = $this->db;
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result[0]->count;
    }
    public function get_stockist_right_given_count()
    {
        $query = "Select count(id) As count 
        from master_entities
        where entity_type=3 and entity_name not in ('TEST STOCKIST','TEST CANTEEN') AND (chairman NOT IN ('1898','1919','1905') or executive NOT IN ('1898','1919','1905') or supervisor NOT IN ('1898','1919','1905'))";
        $db = $this->db;
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result[0]->count;
    }
    public function get_entites_right_not_given_count()
    {
        $query = "Select count(id) As count 
        from master_entities
        where entity_name not in ('TEST STOCKIST','TEST CANTEEN') AND (chairman IN ('1898','1919','1905') or executive IN ('1898','1919','1905') or supervisor IN ('1898','1919','1905'))";
        $db = $this->db;
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result[0]->count;
    }
    public function get_canteen_right_not_given_count()
    {
        $query = "Select count(id) As count 
        from master_entities
        where entity_type=2 and entity_name not in ('TEST STOCKIST','TEST CANTEEN') AND (chairman IN ('1898','1919','1905') or executive IN ('1898','1919','1905') or supervisor IN ('1898','1919','1905'))";
        $db = $this->db;
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result[0]->count;
    }
    public function get_stockist_right_not_given_count()
    {
        $query = "Select count(id) As count 
        from master_entities
        where entity_type=3 and entity_name not in ('TEST STOCKIST','TEST CANTEEN') AND (chairman IN ('1898','1919','1905') or executive IN ('1898','1919','1905') or supervisor IN ('1898','1919','1905'))";
        $db = $this->db;
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result[0]->count;
    }
    public function get_entities_liquor_added_count()
    {
        $query = "Select count(id) As count 
        from master_entities
        where entity_name not in ('TEST STOCKIST','TEST CANTEEN') AND id in ( select distinct entity_id from liquor_entity_mapping)";
        $db = $this->db;
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result[0]->count;
    }
    public function get_canteen_liquor_added_count()
    {
        $query = "Select count(id) As count 
        from master_entities
        where entity_type=2 and entity_name not in ('TEST STOCKIST','TEST CANTEEN') AND id in ( select distinct entity_id from liquor_entity_mapping)";
        $db = $this->db;
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result[0]->count;
    }
    public function get_stockist_liquor_added_count()
    {
        $query = "Select count(id) As count 
        from master_entities
        where entity_type=3 and entity_name not in ('TEST STOCKIST','TEST CANTEEN') AND id in ( select distinct entity_id from liquor_entity_mapping)";
        $db = $this->db;
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result[0]->count;
    }
    public function fetchRetireeDetails($report_type)
    {
        switch ($report_type) {
            case 1:
                $where_clause = "rvd.is_verified=0 AND rvd.action=0";
                $data['report_title'] = "Verification Pending";
                break;
            case 2:
                $where_clause = "rvd.is_verified=1 AND rvd.action=1";
                $data['report_title'] = "Verification Approved";
                break;
            case 3:
                $where_clause = "rvd.is_verified=1 AND rvd.action=0";
                $data['report_title'] = "Verification Denied";
                break;
            default:
                break;
        }
        $query = "select bh.irla,bh.rank,bh.name,bh.email_id,bh.mobile_no,cn.firstname as requested_by,ca.firstname as approval_from,rvd.requested_time,me.entity_name from retiree_verification_details rvd 
        INNER JOIN ci_admin ca on ca.admin_id=rvd.approval_by
        INNER JOIN ci_admin cn on cn.admin_id=rvd.requested_by
        INNER JOIN bsf_hrms_data bh on bh.id=rvd.hrms_id
        INNER JOIN master_entities me on me.id=rvd.entity_id
        where $where_clause";
        $db = $this->db;
        $response = $db->query($query);
        $result = $response->result_array();
        $data['table_head'] = RETIREE_VERIFICATION_STATUS;
        $data['table_data'] = $result;
        $db->close();
        return $data;
    }
    public function fetchRetireeCanteenDetails($report_type, $entity_id)
    {
        switch ($report_type) {
            case 1:
                $where_clause = "rvd.is_verified=0 AND rvd.action=0 AND rvd.entity_id='$entity_id'";
                $data['report_title'] = "Verification Pending";
                break;
            case 2:
                $where_clause = "rvd.is_verified=1 AND rvd.action=1 AND rvd.entity_id='$entity_id'";
                $data['report_title'] = "Verification Approved";
                break;
            case 3:
                $where_clause = "rvd.is_verified=1 AND rvd.action=0 AND rvd.entity_id='$entity_id'";
                $data['report_title'] = "Verification Denied";
                break;
            default:
                break;
        }
        $query = "select bh.irla,bh.rank,bh.name,bh.email_id,bh.mobile_no,cn.firstname as requested_by,ca.firstname as approval_from,rvd.requested_time,me.entity_name from retiree_verification_details rvd 
        INNER JOIN ci_admin ca on ca.admin_id=rvd.approval_by
        INNER JOIN ci_admin cn on cn.admin_id=rvd.requested_by
        INNER JOIN bsf_hrms_data bh on bh.id=rvd.hrms_id
        INNER JOIN master_entities me on me.id=rvd.entity_id
        where $where_clause";
        $db = $this->db;
        $response = $db->query($query);
        $result = $response->result_array();
        $data['table_head'] = RETIREE_VERIFICATION_STATUS;
        $data['table_data'] = $result;
        $db->close();
        return $data;
    }
    public function fetch_posting_units()
    {
        $query = "SELECT UnitName posting_unit from itbp_posting_unit";
        $db = $this->db;
        $response = $db->query($query);
        $result = $response->result_array();
        $db->close();
        return $result;
    }
    public function fetchAllRankQuotaRecords()
    {
        $query = "SELECT id,(select `rank` from master_rank where id=liquor_rank_quota_mapping.rankid) as `bsf_rank`,quota FROM liquor_rank_quota_mapping order by bsf_rank";
        $db = $this->db;
        $response = $db->query($query);
        $result = $response->result_array();
        $db->close();
        return $result;
    }
    public function editRankQuotaDetails($id, $mode, $quota)
    {
        $db = $this->db;
        $query = "UPDATE liquor_rank_quota_mapping SET quota='$quota' where id='$id'";
        $response = $db->query($query);
        if ($response) {
            $result[0]['V_SWAL_TITLE'] = 'SUCCESS';
            $result[0]['V_SWAL_MESSAGE'] = 'Quota updated successfully';
            $result[0]['V_SWAL_TYPE'] = 'success';
        } else {
            $result[0]['V_SWAL_TITLE'] = 'OOPSS!!';
            $result[0]['V_SWAL_MESSAGE'] = 'Quota updation failed';
            $result[0]['V_SWAL_TYPE'] = 'warning';
        }
        $db->close();
        return $result;
    }
    public function getOtp($irla_no)
    {
        $db = $this->db;
        $query = "select otp_code from otp_log where irla_no='$irla_no' and isactive=1 order by id desc limit 1";
        $response = $db->query($query);
        $result = $response->result();
        return $result;
    }
    public function removeMacBinding($irla_no)
    {
        $db = $this->db;
        $query = "UPDATE ci_admin SET android_uuid='' where  username='$irla_no'";
        $response = $db->query($query);
        // $result = $respoFnse->result();
        return $response;
    }
}
