  <!-- //Author:Ujwal Jain
  //Subject:Sales Report Controller
  //Date:15-12-2021 -->

  <?php defined('BASEPATH') or exit('No direct script access allowed');
  class Sales_Report extends MY_Controller
  {

    public function __construct()
    {
      parent::__construct();
      auth_check();
      $this->load->model('Reports/Sales_report_model', 'Sales');
    }

    public function index()
    {

      if ($this->input->post('submit')) {

        $this->form_validation->set_rules('start_date', 'Start Date', 'trim|required');
        $this->form_validation->set_rules('end_date', 'End Date', 'trim|required');


        if ($this->form_validation->run() == FALSE) {
          $data = array(
            'errors' => validation_errors()
          );
          $this->session->set_flashdata('form_data', $_POST);
          $this->session->set_flashdata('errors', $data['errors']);
          redirect(base_url('reports/Sales_Report'), 'refresh');
        } else {

          $start_date = $this->input->post('start_date');
          $end_date = $this->input->post('end_date');
          // $entity_id = 24;
          $entity_id = $this->session->userdata('entity_id');
          $result['details'] = $this->Sales->sales_report($start_date, $end_date, $entity_id);

          $result['cost_details'] = $this->Sales->cost_data($start_date, $end_date, $entity_id);


          $this->session->set_flashdata('form_data', $_POST);
          if (sizeof($result) > 0) {
            $this->load->view('admin/includes/_header');
            $this->load->view('reports/sales_report', $result);
            $this->load->view('admin/includes/_footer');
          }
        }
      } else {
        $this->load->view('admin/includes/_header');
        $this->load->view('reports/sales_report');
        $this->load->view('admin/includes/_footer');
      }
    }

    public function timeCheck()
    {

      echo date("h:i a d/m/Y");
    }

    public function liquor_sales_report()
    {

      if ($this->input->post('submit')) {

        // $start_date = $this->input->post('start_date');
        // $end_date = $this->input->post('end_date');
        // $entity_id = $this->session->userdata('entity_id');

        // echo $start_date . "------" . $end_date . "----------------" . $entity_id;
        $this->form_validation->set_rules('start_date', 'Start Date', 'trim|required');
        // $this->form_validation->set_rules('end_date', 'End Date', 'trim|required');


        if ($this->form_validation->run() == FALSE) {
          $data = array(
            'errors' => validation_errors()
          );
          $this->session->set_flashdata('form_data', $_POST);
          $this->session->set_flashdata('errors', $data['errors']);
          redirect(base_url('reports/Sales_Report/liquor_sales_report'), 'refresh');
        } else {

          $start_date = $this->input->post('start_date');
          $end_date = $this->input->post('end_date');
          $entity_id = $this->session->userdata('entity_id');

          // echo $start_date . "------" . $end_date . "------" . $entity_id;

          $result['details'] = $this->Sales->liquor_sales_report($entity_id, $start_date, $end_date);
          $result['entity_name'] = $this->Sales->fetch_entity_name($entity_id);
          $result['total_summary_details'] = $this->Sales->total_liquor_sales_summary($start_date, $end_date, $entity_id);
          // echo '<pre>';
          // print_r($result);
          // echo '</pre>';

          $this->session->set_flashdata('form_data', $_POST);
          if (sizeof($result) > 0) {
            $this->load->view('admin/includes/_header');
            $this->load->view('reports/liquor_sales_report', $result);
            $this->load->view('admin/includes/_footer');
          }
        }
      } else {
        $this->load->view('admin/includes/_header');
        $this->load->view('reports/liquor_sales_report');
        $this->load->view('admin/includes/_footer');
      }
    }


    public function stock_summary_report()
    {

      if ($this->input->post('submit')) {

        $this->form_validation->set_rules('start_date', 'Start Date', 'trim|required');
        $this->form_validation->set_rules('end_date', 'End Date', 'trim|required');


        if ($this->form_validation->run() == FALSE) {
          $data = array(
            'errors' => validation_errors()
          );
          $this->session->set_flashdata('form_data', $_POST);
          $this->session->set_flashdata('errors', $data['errors']);
          redirect(base_url('reports/Sales_Report'), 'refresh');
        } else {

          $start_date = $this->input->post('start_date');
          $end_date = $this->input->post('end_date');
          // $entity_id=24;
          $entity_id = $this->session->userdata('entity_id');
          $result['details'] = $this->Sales->liquor_sales_report($start_date, $end_date, $entity_id);
          $result['cost_details'] = $this->Sales->liquor_cost_data($start_date, $end_date, $entity_id);

          $this->session->set_flashdata('form_data', $_POST);
          if (sizeof($result) > 0) {
            $this->load->view('admin/includes/_header');
            $this->load->view('reports/liquor_sales_report', $result);
            $this->load->view('admin/includes/_footer');
          }
        }
      } else {

        $this->load->view('admin/includes/_header');
        $this->load->view('reports/liquor_sales_report');
        $this->load->view('admin/includes/_footer');
      }
    }


    public function liquor_sales_summary()
    {

      if ($this->input->post('submit')) {

        $this->form_validation->set_rules('start_date', 'Start Date', 'trim|required');
        // $this->form_validation->set_rules('end_date', 'End Date', 'trim|required');


        if ($this->form_validation->run() == FALSE) {
          $data = array(
            'errors' => validation_errors()
          );
          $this->session->set_flashdata('form_data', $_POST);
          $this->session->set_flashdata('errors', $data['errors']);
          redirect(base_url('reports/sale_summary_report'), 'refresh');
        } else {

          $start_date = $this->input->post('start_date');
          $end_date = $this->input->post('end_date');
          // $entity_id=24;
          $entity_id = $this->session->userdata('entity_id');
          $result['details'] = $this->Sales->liquor_sales_summary($entity_id, $start_date, $end_date);
          // $result['cost_details'] = $this->Sales->liquor_cost_data($start_date, $end_date, $entity_id);
          $result['entity_name'] = $this->Sales->fetch_entity_name($entity_id);
          $this->session->set_flashdata('form_data', $_POST);
          if (sizeof($result) > 0) {
            $this->load->view('admin/includes/_header');
            $this->load->view('reports/sale_summary_report', $result);
            $this->load->view('admin/includes/_footer');
          }
        }
      } else {


        $entity_id = $this->session->userdata('entity_id');
        $start_date = date('Y-m-d');

        $this->session->set_flashdata('form_data', array("start_date" => date('d-m-Y')));
        $result['details'] = $this->Sales->liquor_sales_summary($entity_id, $start_date, '');
        $result['entity_name'] = $this->Sales->fetch_entity_name($entity_id);
        $this->load->view('admin/includes/_header');
        $this->load->view('reports/sale_summary_report', $result);
        $this->load->view('admin/includes/_footer');
      }
    }

    public function monthly_sales_report()
    {

      if ($this->input->post('submit')) {

        $this->form_validation->set_rules('start_date', 'Sale Month', 'trim|required');
        // $this->form_validation->set_rules('end_date', 'End Date', 'trim|required');


        if ($this->form_validation->run() == FALSE) {
          $data = array(
            'errors' => validation_errors()
          );
          $this->session->set_flashdata('form_data', $_POST);
          $this->session->set_flashdata('errors', $data['errors']);
          redirect(base_url('reports/monthly_sales_summary_report'), 'refresh');
        } else {

          $start_date_my = $this->input->post('start_date');
          $end_date = $this->input->post('end_date');
          // $entity_id=24;
          $start_date_array = explode('-', $start_date_my);
          $sale_month = $start_date_array[0];
          $sale_year = $start_date_array[1];
          // $start_date = $start_date_array[2] . "-" . $start_date_array[1] . "-" . $start_date_array[0];
          // print_r($start_date);
          // die();
          $entity_id = $this->session->userdata('entity_id');
          $result['details'] = $this->Sales->monthly_sales_report($sale_month, $sale_year, $entity_id);
          // $result['cost_details'] = $this->Sales->liquor_cost_data($start_date, $end_date, $entity_id);
          $result['entity_name'] = $this->Sales->fetch_entity_name($entity_id);
          $this->session->set_flashdata('form_data', $_POST);
          if (sizeof($result) > 0) {
            $this->load->view('admin/includes/_header');
            $this->load->view('reports/monthly_sales_summary_report', $result);
            $this->load->view('admin/includes/_footer');
          }
        }
      } else {


        $entity_id = $this->session->userdata('entity_id');
        $sale_month = date('m');
        $sale_year = date('Y');

        $this->session->set_flashdata('form_data', array("start_date" => date('m-Y')));
        $result['details'] = $this->Sales->monthly_sales_report($sale_month, $sale_year, $entity_id);
        $result['entity_name'] = $this->Sales->fetch_entity_name($entity_id);
        $this->load->view('admin/includes/_header');
        $this->load->view('reports/monthly_sales_summary_report', $result);
        $this->load->view('admin/includes/_footer');
      }
    }



    public function yearly_sales_report()
    {

      if ($this->input->post('submit')) {

        $this->form_validation->set_rules('start_date', 'Sale Year', 'trim|required');
        // $this->form_validation->set_rules('end_date', 'End Date', 'trim|required');


        if ($this->form_validation->run() == FALSE) {
          $data = array(
            'errors' => validation_errors()
          );
          $this->session->set_flashdata('form_data', $_POST);
          $this->session->set_flashdata('errors', $data['errors']);
          redirect(base_url('reports/year_sales_summary_report'), 'refresh');
        } else {

          $sale_year = $this->input->post('start_date');
          $end_date = $this->input->post('end_date');
          // $entity_id=24;
         // $start_date_array = explode('-', $start_date_my);
         /* $sale_month = $start_date_array[0];*/
          // $sale_year = $start_date_array[1];
          // $start_date = $start_date_array[2] . "-" . $start_date_array[1] . "-" . $start_date_array[0];
          // print_r($start_date);
          // die();
          $entity_id = $this->session->userdata('entity_id');
          $result['details'] = $this->Sales->yearly_sales_report($sale_year, $entity_id);
          // $result['cost_details'] = $this->Sales->liquor_cost_data($start_date, $end_date, $entity_id);
          $result['entity_name'] = $this->Sales->fetch_entity_name($entity_id);
          $this->session->set_flashdata('form_data', $_POST);
          if (sizeof($result) > 0) {
            $this->load->view('admin/includes/_header');
            $this->load->view('reports/year_sales_summary_report', $result);
            $this->load->view('admin/includes/_footer');
          }
        }
      } else {


        $entity_id = $this->session->userdata('entity_id');
        $sale_month = date('m');
        $sale_year = date('Y');

        $this->session->set_flashdata('form_data', array("start_date" => date('Y')));
        $result['details'] = $this->Sales->yearly_sales_report($sale_year, $entity_id);
        $result['entity_name'] = $this->Sales->fetch_entity_name($entity_id);
        $this->load->view('admin/includes/_header');
        $this->load->view('reports/year_sales_summary_report', $result);
        $this->load->view('admin/includes/_footer');
      }
    }



    public function createDailyStock()
    {
      $this->Sales->createDailyStock();
    }
  }
