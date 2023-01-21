<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Request_Vehicle_model extends CI_Model{

	
	//-----------------------------------------------------

	public function request($request_data){
		$result=$this->db->insert('acg_request_log', $request_data);
		if($result){
			return true;
		}	
	}
   //-----------------------------------------------------

    public function fetchDestination($searchterm) {
        $db = $this->db;
		$plant= $this->session->userdata('plant_id');
		if($plant== 1){
			$query = "select id,concat(destination,'_',route_code) destination from acpl_kandiwali where destination like'%$searchterm%' OR route_code like'%$searchterm%';";
		}
        if($plant== 2){
			$query = "select id,concat(destination,'_',route_code) destination from acpl_dahanu where destination like'%$searchterm%' OR route_code like'%$searchterm%';";
		}
		if($plant== 3){
			$query = "select id,concat(destination,'_',route_code) destination from acpl_shirwal where destination like'%$searchterm%' OR route_code like'%$searchterm%';";
		}
		if($plant== 4){
			$query = "select id,concat(destination,'_',route_code) destination from acpl_pithampur where destination like'%$searchterm%' OR route_code like'%$searchterm%';";
		}
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result;
    }

	//-----------------------------------------

	public function fetchCity($searchterm) {
        $db = $this->db;
		$query = "select id,city from acg_city_master where city like'%$searchterm%';";
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result;
    }

	//--------------------------------------------

	public function existing_request($userid,$plant) {

		if($plant== 1){
			$plantname = "acpl_kandiwali";
		}
        if($plant== 2){
			$plantname = " acpl_dahanu";
		}
		if($plant== 3){
			$plantname = "acpl_shirwal";
		}
		if($plant== 4){
			$plantname = "acpl_pithampur";
		}
		
        $db = $this->db;
		$query = "select arl.id,arl.box_count,arl.shipping_date,
		(Select concat(destination,'#',route_code) from $plantname where id = arl.location_id) destination,
		ad.destination as category 
		From acg_request_log arl 
		inner join acg_destination ad on ad.id= arl.category_id 
		Where arl.requested_by='$userid' AND date(NOW())=date(arl.shipping_date) AND status=0;";
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result;
    }

	//--------------------------------------------

	public function fetchboxcount($plant_id){
		$db = $this->db;
		$count= array(
			'count_A'=>0,
			'count_B'=>0,
			'count_C'=>0,
			'count_D'=>0,
			'count_E'=>0,
			'count_F'=>0
		);
		$query = "select box_count From acg_vehicle_log Where plant_id='$plant_id' AND status='0';";
        $response = $db->query($query);
        $result = $response->result_array();
		if($response->num_rows() > 0){
			foreach($result as $row){
				if ($row['box_count'] > 0 && $row['box_count'] <= 44) {
					$count['count_A'] = ++$count['count_A'];
				} else if ($row['box_count'] >= 45 && $row['box_count'] <= 64) {
					$count['count_B'] = ++$count['count_B'];;
				} else if ($row['box_count'] >= 65 && $row['box_count'] <= 95) {
					$count['count_C'] = ++$count['count_C'];;
				} else if ($row['box_count'] >= 96 && $row['box_count'] <= 120) {
					$count['count_D'] = ++$count['count_D'];;
				} else if ($row['box_count'] >= 121 && $row['box_count'] <= 169) {
					$count['count_E'] = ++$count['count_E'];;
				} else if ($row['box_count'] >= 170) {
					$count['count_F'] = ++$count['count_F'];;
				}
			}
		}
        $db->close();
        return $count;
	}

	//--------------------------------------------

	public function updateBoxcount($id,$box_count){
		$db = $this->db;
		$query = "UPDATE acg_request_log SET box_count = $box_count WHERE id=$id; ";
        $response = $db->query($query);
        $db->close();
        return $response;
	}

	//--------------------------------------------

	public function fetchRequest(){
		$db= $this->db;
		$plant= $this->session->userdata('plant_id');
		$adminid= $this->session->userdata('admin_id');
		$plantname="";
		if($plant== 1){
			$plantname = "acpl_kandiwali";
		}
        if($plant== 2){
			$plantname = " acpl_dahanu";
		}
		if($plant== 3){
			$plantname = "acpl_shirwal";
		}
		if($plant== 4){
			$plantname = "acpl_pithampur";
		}

		$queryfetchrequest="select arl.id,arl.vehicle_log_id,arl.box_count,arl.reason_cancellation,
		(Select route_code from $plantname where id = arl.location_id) route_code,
		acm.city city,
		arl.shipping_date,
		IFNULL(cv.vehicleno, 'NA') vehiclenum,
		IFNULL(cv.box_count, 'NA') box_capacity,
		IFNULL(ct.transporter_name, 'NA') transporter,
		arl.status 
		From acg_request_log arl
		inner join acg_city_master acm on acm.id = arl.city_id
		left join ci_vehicle cv on cv.vehicleid=arl.vehicle_id
		left join ci_transporter ct on ct.id= arl.transporter_id
		Where arl.requested_by='$adminid' And date(arl.shipping_date)>=date(now()) order by arl.id";
		$response = $db->query($queryfetchrequest);
        $result = $response->result_array();
        $db->close();
        return $result;

	}

	//--------------------------------------------------

	public function cancel_request($req_id,$veh_log_id,$vehicle_num,$remarks){
		$db= $this->db;
		$update_req_log="update acg_request_log set status = '2', reason_cancellation = '$remarks' where id = '$req_id'";
		$update_req_log_res=$db->query($update_req_log);
		if($update_req_log_res){
			$update_veh_log="update acg_vehicle_log set status = '0' where id='$veh_log_id'";
			$update_veh_log_res=$db->query($update_veh_log);
			if($update_veh_log_res){
				$get_trip_id="Select trip_id,from_userid,to_user_id from acg_notification where req_log_id='$req_id'";
				$get_trip_id_res=$db->query($get_trip_id);
				$get_trip_result=$get_trip_id_res->result();

				$trip_id=$get_trip_result[0]->trip_id;
				$from_userid=$get_trip_result[0]->from_userid;
				$to_user_id=$get_trip_result[0]->to_user_id;
				$to_gcm_id=$this->get_gcm_id($to_user_id);
				$title = "Request CANCELED";
				$message = "Request Of $vehicle_num is CANCELED by PFD";

				$update_acg_trip_qry="update acg_trip set is_completed = 'C' where id = '$trip_id'";
				$update_acg_trip_qry_res=$db->query($update_acg_trip_qry);
				$update_acg_trip_tat_qry="update acg_trip_tat set is_completed = '2' where trip_id = '$trip_id'";
				$update_acg_trip_tat_qry_res=$db->query($update_acg_trip_tat_qry);

				$send_notification_qry="insert into acg_notification (trip_id,req_log_id,notif_type,to_gcm_id,notification_title,message,from_userid,to_user_id,cancellation_time,insert_time,status) 
				values ('$trip_id','$req_id','1','$to_gcm_id','$title','$message','$from_userid','$to_user_id',now(),now(),'3')";
				$send_notification_qry_res=$db->query($send_notification_qry);

				if($send_notification_qry_res){
					$db->close();
					return true;
				}
			}else{
				$db->close();
				return false;
			}
		}else{
			$db->close();
			return false;
		}
	}

	//--------------------------------------------------

	public function allotVehicle(){
		$this->load->helper('Ats/notification');
		$category_array = array("A", "B", "C", "D", "E", "F");
		$db= $this->db;
		$counter="select dahanu_count,shirwal_count,kandivali_count,prithampura_count from acg_vehicle_allocation_counter where id=1;";
		$count_response= $db->query($counter);
		$count_rows=$count_response->result();

		$dahanu_count=$count_rows[0]->dahanu_count;
		++$dahanu_count;
		$shirwal_count=$count_rows[0]->shirwal_count;
		++$shirwal_count;
		$kandivali_count=$count_rows[0]->kandivali_count;
		++$kandivali_count;
		$prithampura_count=$count_rows[0]->prithampura_count;
		++$prithampura_count;

		$requested_vehicle_qry="SELECT id,box_count,shipping_date,location_id,category_id,city_id,plant_id,requested_by from acg_request_log where date(shipping_date)=date(now()) AND status=0;";
		$req_resp=$db->query($requested_vehicle_qry);
		$requested_vehicle_list=$req_resp->result_array();
		
		$k = 1;
		$allocation_mode = "NORMAL CASE";
		unset($priority_array);
		unset($eligible_array);
		foreach($requested_vehicle_list as $row){
			$priority_mode = "";
			$box_count = "";
			$tbox_count = "";
			$requested_by= $row['requested_by'];
			if ($row['category_id'] == 1) {
				$SUITABLE_COLUMN = "A.suited_rest_of_india";
			} else if ($row['category_id'] == 2) {
				$SUITABLE_COLUMN = "A.suited_north_east";
			} else if ($row['category_id'] == 3) {
				$SUITABLE_COLUMN = "A.suited_bangladesh";
			} else if ($row['category_id'] == 4) {
				$SUITABLE_COLUMN = "A.suited_nepal";
			}

			$normal_case_qry="  SELECT AVL.id VEH_LOG_ID, A.category, AVL.vehicle_id, AVL.trans_id, AVL.plant_id, AVL.box_count TBOX_COUNT, AVL.insert_time "
			. "FROM acg_vehicle_log AVL inner join ci_vehicle A on A.vehicleid = AVL.vehicle_id "
			. "WHERE AVL.status = '0' and AVL.plant_id = '".$row['plant_id']."' and $SUITABLE_COLUMN = 'Y' ORDER BY AVL.insert_time ASC;";
			$nrm_case_qry=$db->query($normal_case_qry);
			$normalcase_result=$nrm_case_qry->result_array();

			$special_case_qry="SELECT AVL.id VEH_LOG_ID, A.category, AVL.vehicle_id, AVL.trans_id, AVL.plant_id, AVL.box_count TBOX_COUNT," .
			" AVL.insert_time FROM acg_vehicle_log AVL inner join ci_vehicle A on A.vehicleid = AVL.vehicle_id WHERE AVL.status = '0' " .
			"and AVL.plant_id = '".$row['plant_id']."' and $SUITABLE_COLUMN = 'Y' and AVL.trans_id='107'" .
			"Union all(SELECT AVL.ID VEH_LOG_ID, A.category, AVL.vehicle_id, AVL.trans_id, AVL.plant_id, AVL.box_count TBOX_COUNT," .
			" AVL.insert_time FROM acg_vehicle_log AVL inner join ci_vehicle A on A.vehicleid = AVL.vehicle_id WHERE AVL.status = '0' and AVL.plant_id = '".$row['plant_id']."' " .
			"and $SUITABLE_COLUMN = 'Y' and AVL.trans_id NOT IN('107') ORDER BY AVL.insert_time ASC);";
			$spc_case_qry=$db->query($special_case_qry);
			$specialcase_result=$spc_case_qry->result_array();
			

			$city_query = "select city from acg_city_master where id='".$row['city_id']."';";
			$city_resp=$db->query($city_query);
			$city_row=$city_resp->result();
			$city=$city_row[0]->city;

			$fetchdistancetocity = "select distance_kandivali,distance_dahanu,distance_pithampur,distance_shirwal from acg_customer where city like '%$city%' order by insert_time DESC LIMIT 1;";
			$distancetocity_resp=$db->query($fetchdistancetocity);
			$distancetocity_row=$distancetocity_resp->result();

			if($distancetocity_resp->num_rows() > 0){
				
				$distance_kandivali=$distancetocity_row[0]->distance_kandivali;
				$distance_dahanu=$distancetocity_row[0]->distance_dahanu;
				$distance_pithampur=$distancetocity_row[0]->distance_pithampur;
				$distance_shirwal=$distancetocity_row[0]->distance_shirwal;

				if ($row['plant_id'] == 1) {
					$allocation_mode = "SPECIAL CASE";
					$DISTANCE_FOR_TRIP = $distance_kandivali;
				} else if ($row['plant_id'] == 2) {
					$allocation_mode = "SPECIAL CASE";
					$DISTANCE_FOR_TRIP = $distance_dahanu;
				} else if ($row['plant_id'] == 3) {
					$allocation_mode = "SPECIAL CASE";
					$DISTANCE_FOR_TRIP = $distance_shirwal;
				} else if ($row['plant_id'] == 4) {
					$allocation_mode = "SPECIAL CASE";
					$DISTANCE_FOR_TRIP = $distance_pithampur;
				} else {
					$allocation_mode = "NORMAL CASE";
				}

			} else{
				$allocation_mode = "NORMAL CASE";
			}

			if($allocation_mode == "SPECIAL CASE" && $DISTANCE_FOR_TRIP > 300){
				if($spc_case_qry->num_rows() > 0){
					foreach($specialcase_result as $sp_case){
						$request_category = $this->get_capacity_category($row['box_count']);
						$req_category_key=array_search($request_category,$category_array);

						$vehicle_category = $sp_case['category'];

						for($i=$req_category_key;$i<count($category_array);$i++){
							if($vehicle_category==$category_array[$i]){
								if($row['box_count']<=$sp_case['TBOX_COUNT']){
									$priority_mode = '1';
                                    $priority_array = array("REQ_ID" => $row['id'], "VEH_LOG_ID" => $sp_case['VEH_LOG_ID'], "ASSETID" => $sp_case['vehicle_id'], "PLANTID" => $sp_case['plant_id'], "BOX_COUNT" => $sp_case['TBOX_COUNT'], "INSERT_TIME" => $sp_case['insert_time'], "IS_OCCUPIED" => 'Y', "TRANS_ID" => $sp_case['trans_id'], "DESTINATON_CATEGORY" => $row['category_id']);
                                    break; //IF VEHICLE BOX IS SAME AS REQUSTED BOX STOP FETCHING OTHER VEHICLES
								}
							}
						}
						// if($priority_mode=='1'){
						// 	break;
						// }
					}
				}
				if($priority_mode!="" && count($priority_array) > 0){
					$transporter_id=$priority_array['TRANS_ID'];
					$trans_admin_id=$this->get_trans_admin_id($transporter_id);
					$trans_gcm_id=$this->get_gcm_id($trans_admin_id);
					if($trans_gcm_id != ""){
						$final_veh_log_id = $priority_array['VEH_LOG_ID'];
						$vehicle_id= $priority_array['ASSETID'];
                        $vehicle_num = $this->get_vehicle_detail($vehicle_id, "vehicleno");
						$check_duplicate_query= "select id from acg_trip where vehicleno = '$vehicle_num' and IS_COMPLETED = 'N' ";
						$check_duplicate_resp=$db->query($check_duplicate_query);
						if($check_duplicate_resp->num_rows() == 0){
							$title = "Request For Vehicle";
							$message = "Vehicle No. $vehicle_num is requested for loading";
							// $url = "notification.html";
							// $send_request = $this->notification->SEND_PUSH_NOTIFICATION($trans_gcm_id, $title, $message, $url); //SENT TO TRANSPORTER WHOSE VEHICLE IS SELECTED IN ABOVE
							$request_id = $priority_array['REQ_ID'];
							$destination_category = $priority_array['DESTINATON_CATEGORY'];
							$box_to_insert = $priority_array['BOX_COUNT'];
							$source_insert = $priority_array['PLANTID'];
							$device_no = $this->get_vehicle_detail($vehicle_id, "deviceid");
							$get_lat_long = $this->get_lat_long($source_insert);
							$get_lat_long_arr = explode("#", $get_lat_long);
							$source_lat = $get_lat_long_arr[0];
							$source_long = $get_lat_long_arr[1];
							
							if (($source_insert == 1) && ($transporter_id == 107)) {
								$update_counter_kandivali = "UPDATE acg_vehicle_allocation_counter set kandivali_count='$kandivali_count';";
								$update_counter_kandivali_resp=$db->query($update_counter_kandivali);
							}
							if (($source_insert == 2) && ($transporter_id == 107)) {
								$update_counter_dahanu = "UPDATE acg_vehicle_allocation_counter set dahanu_count='$dahanu_count';";
								$update_counter_dahanu_resp=$db->query($update_counter_dahanu);
							}
							if (($source_insert == 3) && ($transporter_id == 107)) {
								$update_counter_shirwal = "UPDATE acg_vehicle_allocation_counter set shirwal_count='$shirwal_count';";
								$update_counter_shirwal_resp=$db->query($update_counter_shirwal);
							}
							if (($source_insert == 4) && ($transporter_id == 107)) {
								$update_counter_pithampur = "UPDATE acg_vehicle_allocation_counter set prithampura_count='$prithampura_count';";
								$update_counter_pithampur_resp=$db->query($update_counter_pithampur);
							}
							if (($source_insert == 1) && ($transporter_id != 107) && ($kandivali_count > 3)) {
								$update_counter_kandivali = "UPDATE acg_vehicle_allocation_counter set kandivali_count='0';";
								$update_counter_kandivali_resp = $db->query($update_counter_kandivali);
							}
							if (($source_insert == 2) && ($transporter_id != 107) && ($dahanu_count > 3)) {
								$update_counter_dahanu = "UPDATE acg_vehicle_allocation_counter set dahanu_count='0';";
								$update_counter_dahanu_resp = $db->query($update_counter_dahanu);
							}
							if (($source_insert == 3) && ($transporter_id != 107) && ($shirwal_count > 3)) {
								$update_counter_shirwal = "UPDATE acg_vehicle_allocation_counter set shirwal_count='0';";
								$update_counter_shirwal_resp = $db->query($update_counter_shirwal);
							}
							if (($source_insert == 4) && ($transporter_id != 107) && ($prithampura_count > 3)) {
								$update_counter_pithampur = "UPDATE acg_vehicle_allocation_counter set prithampura_count='0';";
								$update_counter_pithampur_resp = $db->query($update_counter_pithampur);
							}


							$this->keep_log($request_id,$requested_by,$vehicle_id, $final_veh_log_id, $destination_category,$title, $message,$trans_gcm_id, $transporter_id, $vehicle_num, $box_to_insert, $source_insert, $device_no, $source_lat, $source_long, $trans_admin_id);
							$db->close();
						}
					}
				}
			}else{
				if($nrm_case_qry->num_rows() > 0){
					foreach($normalcase_result as $nrm_case){
						$request_category = $this->get_capacity_category($row['box_count']);
						$req_category_key=array_search($request_category,$category_array);

						$vehicle_category = $nrm_case['category'];

						for($i=$req_category_key;$i<count($category_array);$i++){
							if($vehicle_category==$category_array[$i]){
								if($row['box_count']<=$nrm_case['TBOX_COUNT']){
									$priority_mode = '1';
                                    $priority_array = array("REQ_ID" => $row['id'], "VEH_LOG_ID" => $nrm_case['VEH_LOG_ID'], "ASSETID" => $nrm_case['vehicle_id'], "PLANTID" => $nrm_case['plant_id'], "BOX_COUNT" => $nrm_case['TBOX_COUNT'], "INSERT_TIME" => $nrm_case['insert_time'], "IS_OCCUPIED" => 'Y', "TRANS_ID" => $nrm_case['trans_id'], "DESTINATON_CATEGORY" => $row['category_id']);
                                    break; //IF VEHICLE BOX IS SAME AS REQUSTED BOX STOP FETCHING OTHER VEHICLES
								}
							}
						}
						// if($priority_mode=='1'){
						// 	break;
						// }
					}
				}
				if($priority_mode!="" && count($priority_array) > 0){
					$transporter_id=$priority_array['TRANS_ID'];
					$trans_admin_id=$this->get_trans_admin_id($transporter_id);
					$trans_gcm_id=$this->get_gcm_id($trans_admin_id);
					if($trans_gcm_id != ""){
						$final_veh_log_id = $priority_array['VEH_LOG_ID'];
						$vehicle_id= $priority_array['ASSETID'];
                        $vehicle_num = $this->get_vehicle_detail($vehicle_id, "vehicleno");
						$check_duplicate_query= "select id from acg_trip where vehicleno = '$vehicle_num' and IS_COMPLETED = 'N' ";
						$check_duplicate_resp=$db->query($check_duplicate_query);
						if($check_duplicate_resp->num_rows() == 0){
							$title = "Request For Vehicle";
							$message = "Vehicle No. $vehicle_num is requested for loading";
							$url = "notification.html";
							// $send_request = $this->notification->SEND_PUSH_NOTIFICATION($trans_gcm_id, $title, $message, $url); //SENT TO TRANSPORTER WHOSE VEHICLE IS SELECTED IN ABOVE
							$request_id = $priority_array['REQ_ID'];
							$destination_category = $priority_array['DESTINATON_CATEGORY'];
							$box_to_insert = $priority_array['BOX_COUNT'];
							$source_insert = $priority_array['PLANTID'];
							$device_no = $this->get_vehicle_detail($vehicle_id, "deviceid");
							$get_lat_long = $this->get_lat_long($source_insert);
							$get_lat_long_arr = explode("#", $get_lat_long);
							$source_lat = $get_lat_long_arr[0];
							$source_long = $get_lat_long_arr[1];

							$this->keep_log($request_id,$requested_by,$vehicle_id, $final_veh_log_id, $destination_category,$title, $message,$trans_gcm_id, $transporter_id, $vehicle_num, $box_to_insert, $source_insert, $device_no, $source_lat, $source_long, $trans_admin_id);
							$db->close();
						}
					}
				}
			}

		}

	}

	public function keep_log($request_id,$requested_by,$vehicle_id, $final_veh_log_id, $destination_category,$title, $message,$trans_gcm_id, $transporter_id, $vehicle_num, $box_to_insert, $source_insert, $device_no, $source_lat, $source_long, $trans_admin_id)
	{
		$db = $this->db;
		$CURRENT_DATETIME = date("Y-m-d H:i:s");
		$update_request_log = "update acg_request_log set vehicle_log_id = '$final_veh_log_id',vehicle_id='$vehicle_id' ,transporter_id='$transporter_id', status = '1' where id = '$request_id'";
	
		$update_request_log_res =  $db->query($update_request_log);
	
		if ($update_request_log_res) {
			$update_vehicle_log = "update acg_vehicle_log set status = '1' , destination_category = '$destination_category' where id = '$final_veh_log_id' ";
			$update_vehicle_log_res = $db->query($update_vehicle_log);
			if ($update_vehicle_log_res) {
				
				$acg_trip_log_query = "insert into acg_trip (vehicleno,no_of_boxes,source_lat,source_lon,transporter,source_id,insert_time) values ('$vehicle_num','$box_to_insert','$source_lat','$source_long','$transporter_id','$source_insert',now())";
				$acg_trip_log_res = $db->query($acg_trip_log_query);

				if($acg_trip_log_res){
					$fetchtrip_id_qry= "select id from acg_trip where vehicleno='$vehicle_num' ORDER BY insert_time desc";
					$fetchtrip_id_qry_res = $db->query($fetchtrip_id_qry);
					$fetchtrip_id_row=$fetchtrip_id_qry_res->result();
					$trip_id=$fetchtrip_id_row[0]->id;
			
					$acg_trip_tat_log = "insert into acg_trip_tat (trip_id,deviceid,sourceid,source_lat,source_lon,next_scan_time,insert_time) values ('$trip_id','$device_no','$source_insert','$source_lat','$source_long',now(),now())";
					$acg_trip_tat_log_res = $db->query($acg_trip_tat_log);

					$acg_trip_log_qry = "insert into acg_trip_log (trip_id,vehicle_log_id,vehicle_log_status,EVENT_TIME,INSERT_TIME) values ('$trip_id','$final_veh_log_id','1',now(),now())";
					$acg_trip_log_qry_res = $db->query($acg_trip_log_qry);
					
					$notification_log_qry = "insert into acg_notification (trip_id,req_log_id,notif_type,to_gcm_id,notification_title,message,from_userid,to_user_id,insert_time,status) values ('$trip_id','$request_id','1','$trans_gcm_id','$title','$message','$requested_by','$trans_admin_id',now(),'0')";
					$notification_log_qry_res = $db->query($notification_log_qry);
				}
			
			} else {
				
				$update_req_log = "update acg_request_log set VEHICLE_LOG_ID = '' , status = '0' where id = '$request_id'";
				$update_req_log_res = $db->query($update_req_log);
			}
		}
		$db->close();
	}

	public function get_lat_long($source_insert){
		$db = $this->db;
		$query = "select concat(latitude,'#',longitude) lat_long from master_plant where id='$source_insert'";
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result[0]->lat_long;
	}

	public function get_vehicle_detail($final_veh_log_id, $mode){
		$db = $this->db;
		$query = "select vehicleno,deviceid from ci_vehicle where vehicleid='$final_veh_log_id'";
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
		if($mode=="vehicleno"){
        	return $result[0]->vehicleno;
		}else if($mode=="deviceid"){
			return $result[0]->deviceid;
		}
	}

	public function get_gcm_id($trans_admin_id){
		$db = $this->db;
		$query = "select gcm_id from ci_admin where admin_id='$trans_admin_id'";
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result[0]->gcm_id;
	}

	public function get_trans_admin_id($transporter_id){
		$db = $this->db;
		$query = "select admin_id from ci_admin where transporter_id='$transporter_id' and admin_role_id=58;";
        $response = $db->query($query);
        $result = $response->result();
        $db->close();
        return $result[0]->admin_id;
	}

	public function get_capacity_category($CAPACITY){
		$CATEGORY = '';
		
		if ($CAPACITY > 0 && $CAPACITY <= 44) {
			$CATEGORY = 'A';
		} else if ($CAPACITY >= 45 && $CAPACITY <= 64) {
			$CATEGORY = 'B';
		} else if ($CAPACITY >= 65 && $CAPACITY <= 95) {
			$CATEGORY = 'C';
		} else if ($CAPACITY >= 96 && $CAPACITY <= 120) {
			$CATEGORY = 'D';
		} else if ($CAPACITY >= 121 && $CAPACITY <= 169) {
			$CATEGORY = 'E';
		} else if ($CAPACITY >= 170) {
			$CATEGORY = 'F';
		}
		return $CATEGORY;
	}


}

?>