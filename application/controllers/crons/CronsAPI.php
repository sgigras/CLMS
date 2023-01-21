<?php
header("Access-Control-Allow-Origin: *");
class CronsAPI extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model(array('crons/Crons_Model', 'crons_model'));
    }

    public function expired_order()
    {
        $file_path = base_url('crons/CronsAPI/expired_order');
        $result = $this->crons_model->expired_order();
        $result = $this->crons_model->cron_log($file_path);
        return $result;
    }

    public function updateBalance()
    {
        $file_path = base_url('crons/CronsAPI/updateBalance');
        // $result = $this->Crons_Model->expired_order();
        $result = $this->crons_model->UpdateActualBalance();
        $result = $this->crons_model->cron_log($file_path);
        return $result;
    }

    
    public function createTodayStock()
    {
        $file_path = base_url('crons/CronsAPI/createTodayStock');
        // $result = $this->Crons_Model->expired_order();
        $result = $this->crons_model->createTodayStock();
        $result = $this->crons_model->cron_log($file_path);
        return $result;
    }

    public function updateHrmsDetails()
    {
        $file_path = base_url('crons/CronsAPI/updateHrmsDetails');
        $result = $this->crons_model->updateHrmsDetails();
        $this->crons_model->cron_log($file_path);
        echo json_encode($result);
    }
}
