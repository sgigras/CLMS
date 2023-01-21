<?php (!defined('BASEPATH')) and exit('No direct script access allowed');

/**
 * Form date validation
 *
 * @package CodeIgniter
 * @author  Harish Manoharan
 */
class MY_Form_validation extends CI_Form_validation {
    public $CI;
    public function __construct($rules = array()) {
        parent::__construct($rules);
        // $this->load->library('form_validation');
       
    }


    public function valid_date($date) {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }

  public function multiple_select()
    {
		// print_r($this->input->post('brewerystate')[0]);die();
            if (empty($this->input->post('brewerystate')[0]))  {
            $this->form_validation->set_message('multiple_select', 'Please Select Atleast One State.');
            return FALSE;
        }
        else {
            return TRUE;
        }
    }

    public function multiple_selectstate()
	{
	
        print("in function");die();
		if (count($this->input->post('brewerystate')) == 1) {
			if ($this->input->post('brewerystate')[0] == '') {
				// echo 'false';
				$this->form_validation->set_message('multiple_selectstate', 'Please Select Atleast One State.');
				return FALSE;
			} else {
				// echo 'true';
				return TRUE;
			}
		} else {
			return TRUE;
		}
		
	}



}