<?php defined('BASEPATH') or exit('No direct script access allowed');

class Import_excel extends MY_Controller
{

	public function __construct()
	{

		parent::__construct();
		// auth_check(); // check login auth
		$this->load->model('admin/import_model');
		$this->load->library('PHPExcel');
	}

	//----------------------------------------------------------------
	public function index()
	{

		$data['title'] = 'Simple Table';



		$this->load->view('admin/includes/_header');
		$this->load->view('admin/uidesign/excel_file_upload');
		$this->load->view('admin/includes/_footer');
	}

	public function import()
	{

		$error_flag = 0;
		if (isset($_FILES["FILE"]["name"])) {
			$path = $_FILES["FILE"]["tmp_name"];
			$object = PHPExcel_IOFactory::load($path);
			foreach ($object->getWorksheetIterator() as $worksheet) {
				$error_flag = 0;
				$highestRow = $worksheet->getHighestRow();


				$highestColumn = $worksheet->getHighestColumn();
				$excel_array = array();
				$rank_array=array();
				$counter = $highestRow;
				for ($row = 2; $row <= $counter; $row++) {
					$irla = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
					$name = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
					$mobile_no = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
					$date_of_birth = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
					$birth = PHPExcel_Style_NumberFormat::toFormattedString($date_of_birth, "YYYY-M-D");
					$rank = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
					$present_appointment = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
					$status = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
					$location_name = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
					$district_name = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
					$state_name = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
					$email_id = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
					$posting_unit = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
					$frontier = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
					$mobile_no =trim($mobile_no," ");
					
					if($mobile_no==""){
						$mobile_no=0;
					}
					$data = array(
						'irla'				=>	$irla,
						'name'				=>	$name,
						'mobile_no'			=>	$mobile_no,
						'date_of_birth'		=>	$birth,
						'rank'				=>	$rank,
						'present_appoitment' =>	$present_appointment,
						'status'			=>	$status,
						'location_name'		=>	$location_name,
						'district_name'		=>	$district_name,
						'state_name'		=>	$state_name,
						'email_id'			=>	$email_id,
						'posting_unit'		=> $posting_unit,
						'frontier'			=> $frontier
					);

					array_push($excel_array, $data);

					// $data_rank=array(
					// 	'rank' =>$rank
					// );

					if (!in_array($rank, $rank_array))
						{
							array_push($rank_array, $rank);
						}
					// if($irla == '' || $name =='' || $mobile_no== '' || $birth =='' || $rank == '' || $present_appointment == '' || $status =='' || $location_name == '' || $district_name == '' || $state_name == ''|| $email_id =='' )
					// {
					// 	$error_flag++;
					// }else
					// {
					
					// }
				}
			}
		}
		// echo $error_flag;
		if ($error_flag > 0) {
			echo "false";
		} else {
			// echo '<pre>';
			// print_r($excel_array);
			// print_r($rank_array);
			// echo '</pre>';die();
			// echo json_encode($excel_array);
			// echo json_en
			$response = $this->import_model->insert($excel_array,$rank_array);

			// echo $response;
			// die();
			echo json_encode($response);
			// echo $response[0]->V_SWAL_TITLE;
		}
	}
}
