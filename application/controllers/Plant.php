<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Plant extends CI_Controller {
	
	public function __construct(){
		parent::__construct(); 
		$this->load->library('auth');        
        if(!$this->auth->is_logged_in()) {
			redirect(base_url());
		}
		$this->load->model('master/user_model', 'm_user');
		$this->load->model('master/permission_model', 'm_perm');
		$this->load->model('general_model', 'm_general');
    }
	
	public function index(){
		
		$object['plant'] = $this->m_general->sap_plant_select_by_id($this->session->userdata['ADMIN']['plant']);

        $object['plants'] = $this->m_perm->admin_plants_select_all($this->session->userdata['ADMIN']['admin_id']);

		if($object['plants'] !== FALSE) {

			$sGroup = '';
			
			foreach ($object['plants'] as $plantkey=>$plant) {
				if (trim($plant['OUTLET'])=="") continue;
				if ($sGroup!=$plant['jenisoutlet']){
					$sGroup = $plant['jenisoutlet'];
				}
				$object['plant'][$sGroup][$plant['OUTLET']] = $plant['OUTLET_NAME2'].' - '.$plant['OUTLET_NAME1']." (".$plant['OUTLET'].")";
			}

        }
        
        $this->load->view('plant/index', $object);
	}
	
	function change_plant(){

		$Msg = array();
		$admin = array();

		$admin['plant'] = $this->input->post('outlet');
		$admin['plant_type_id'] = $this->m_general->sap_get_plant_type_id($this->input->post('outlet'));
		$admin['admin_id'] = $this->session->userdata['ADMIN']['admin_id'];

		if($this->m_user->admin_update($admin)) {

			$userdata['ADMIN'] = $this->session->userdata['ADMIN'];

			$userdata['ADMIN']['plant'] = $admin['plant'];
			$userdata['ADMIN']['plant_type_id'] = $admin['plant_type_id'];
			$userdata['ADMIN']['plants'] = $this->m_general->sap_get_user_plants($this->session->userdata['ADMIN']['admin_id']);
			$plant = $this->m_general->sap_plant_select_by_id($admin['plant']);
        	$userdata['ADMIN']['plant_name'] = $plant['OUTLET_NAME1'];
        	$userdata['ADMIN']['storage_location'] = $plant['STOR_LOC'];
        	$userdata['ADMIN']['storage_location_name'] = $plant['STOR_LOC_NAME'];
        	$userdata['ADMIN']['hr_plant_code'] = $plant['HR_CODE'];
        	$userdata['ADMIN']['cost_center'] = $plant['COST_CENTER'];
        	$userdata['ADMIN']['cost_center_name'] = $plant['COST_CENTER_TXT'];

  	    	$this->session->set_userdata($userdata);

			$Msg = array(
				'success' => true,
				'message' => "Berhasil ganti plant, halaman akan refresh otomatis"
			);
		} else {
			
			$Msg = array(
				'success' => false,
				'message' => "Gagal ganti plant .. "
			);
		}

		echo json_encode($Msg);
	}
}
