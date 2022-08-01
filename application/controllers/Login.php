<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('master/user_model', 'm_user');
		$this->load->model('general_model', 'm_general');
	}
	
	public function index(){
		$this->load->view('login');
	}

	public function userLogin(){
		$POST_Data = $this->input->post('data');

		$username = $POST_Data['username'];
		$password = $POST_Data['password'];
		
		if(!empty($username)) {

			if($return['username_right'] = $this->m_user->check_username_exist($username)){			
				if ($return['username_right']){ 

					if(!empty($password)) { 
						
						$return = $this->m_user->check_username_password($username, $password);
						
						if($return['password_right']) { 

							$userdata['ADMIN'] = $this->m_user->admin_select($return['admin_id']);

							$plant = $this->m_general->sap_plant_select_by_id($userdata['ADMIN']['plant']);

							$employee = $this->m_user->check_employee_data($return['admin_id']);

							$userdata['ADMIN']['admin_emp_id'] = $employee['employee_id'];
							$userdata['ADMIN']['nik'] = $employee['nik'];
							$userdata['ADMIN']['employee_name'] = $employee['nama'];
							$userdata['ADMIN']['password'] = $password;
							$userdata['ADMIN']['plant_name'] = $plant['OUTLET_NAME1'];
							$userdata['ADMIN']['storage_location'] = $plant['STOR_LOC'];
							$userdata['ADMIN']['storage_location_name'] = $plant['STOR_LOC_NAME'];
							$userdata['ADMIN']['cost_center'] = $plant['COST_CENTER'];
							$userdata['ADMIN']['cost_center_name'] = $plant['COST_CENTER_TXT'];
							$userdata['ADMIN']['hr_plant_code'] = $plant['HR_CODE'];

							$plant_type = $this->m_general->sap_jenisoutlet_select($userdata['ADMIN']['plant_type_id']);

							$userdata['ADMIN']['plant_type_name'] = $plant_type['jenisoutlet'];

							$userdata['logged_in'] = TRUE;
							$userdata['password'] = $password; 
							$userdata['startup'] = 1; 
							$userdata['lang_name'] = 'indonesian';

							$this->session->set_userdata($userdata);
							
						}

					}
				}
			} else {

				$return['username_right'] = $this->m_user->check_employee_exist($username);

				if ($return['username_right']){

					if(!empty($password)) { 
						// empty password used when user forgot the password
						$return = $this->m_user->check_employee_password($username, $password);
	
						if($return['password_right']) {
							if (intval($return['admin_id'])>1){
								// jika ada login di tabel d_admin
								$userdata['ADMIN'] = $this->m_user->admin_select($return['admin_id']);
	
								$plant = $this->m_general->sap_plant_select_by_id($userdata['ADMIN']['plant']);
	
								$userdata['ADMIN']['admin_emp_id'] = $return['admin_emp_id'];
								$userdata['ADMIN']['nik'] = $return['nik'];
								$userdata['ADMIN']['employee_name'] = $return['nama'];
								$userdata['ADMIN']['password'] = $password;
								$userdata['ADMIN']['plant_name'] = $plant['OUTLET_NAME1'];
								$userdata['ADMIN']['storage_location'] = $plant['STOR_LOC'];
								$userdata['ADMIN']['storage_location_name'] = $plant['STOR_LOC_NAME'];
								$userdata['ADMIN']['cost_center'] = $plant['COST_CENTER'];
								$userdata['ADMIN']['cost_center_name'] = $plant['COST_CENTER_TXT'];
								$userdata['ADMIN']['hr_plant_code'] = $plant['HR_CODE'];
	
								$plant_type = $this->m_general->sap_jenisoutlet_select($userdata['ADMIN']['plant_type_id']);
								$userdata['ADMIN']['plant_type_name'] = $plant_type['jenisoutlet'];

							} else {
														
								$plant = $this->m_general->sap_plant_select_by_id($return['plant']);
	
								$userdata['ADMIN']['admin_id'] = $return['admin_id'];
								$userdata['ADMIN']['admin_emp_id'] = $return['admin_emp_id'];
								$userdata['ADMIN']['nik'] = $return['nik'];
								$userdata['ADMIN']['admin_perm_grup_ids'] = '0';
								$userdata['ADMIN']['admin_username'] = $return['nik'];
								$userdata['ADMIN']['admin_realname'] = $return['nama'];
								$userdata['ADMIN']['admin_password'] = $return['portal_password'];
								$userdata['ADMIN']['plants'] = $return['plant'];
								$userdata['ADMIN']['admin_email'] = $return['email_kantor'];
								$userdata['ADMIN']['group_id'] = 0;
								$userdata['ADMIN']['group_order'] = 0;
								$userdata['ADMIN']['group_name'] = 'Employee Self Service';
								$userdata['ADMIN']['group_perms'] = 'ae200';
								$userdata['ADMIN']['group_ids_manage'] = '';
								
								$userdata['ADMIN']['plant_type_id'] = $plant['COMP_CODE'];
								$userdata['ADMIN']['password'] = $password;
								$userdata['ADMIN']['plant_name'] = $plant['OUTLET_NAME1'];
								$userdata['ADMIN']['storage_location'] = $plant['STOR_LOC'];
								$userdata['ADMIN']['storage_location_name'] = $plant['STOR_LOC_NAME'];
								$userdata['ADMIN']['cost_center'] = $plant['COST_CENTER'];
								$userdata['ADMIN']['cost_center_name'] = $plant['COST_CENTER_TXT'];
								$userdata['ADMIN']['hr_plant_code'] = $plant['HR_CODE'];
	
								$plant_type = $this->m_general->sap_jenisoutlet_select($userdata['ADMIN']['plant_type_id']);
								$userdata['ADMIN']['plant_type_name'] = $plant_type['jenisoutlet'];
							
							}
	
							$userdata['logged_in'] = TRUE;
							$userdata['password'] = $password; 
							$userdata['startup'] = 1;
							$userdata['lang_name'] = 'indonesian';
							$this->session->set_userdata($userdata);
						
						}
					}

				}
			}
		}
		
		if ($return['username_right'] == TRUE && $return['password_right'] == TRUE){ 
			
			if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
			{
				$swbipaddress=$_SERVER['HTTP_CLIENT_IP'];
			}
			elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
			{
				$swbipaddress=$_SERVER['HTTP_X_FORWARDED_FOR'];
			}
			else
			{
				$swbipaddress=$_SERVER['REMOTE_ADDR'];
			}

			if (Empty($swbipaddress)) { 
				$swbipaddress="";
			} 
				
			$this->m_user->update_login($username,$swbipaddress);

			$errMsg = array(
				'success' => true,
				'message' => 'Berhasil Login'
			); 
			
			echo json_encode($errMsg);

		} elseif($return['username_right'] == TRUE) {

			$errMsg = array(
				'success' => false,
				'message' => 'Password yang anda masukan salah'
			); 
			echo json_encode($errMsg);   
			
		} else {

			$errMsg = array(
				'success' => false,
				'message' => 'Username yang anda masukan salah'
			); 
			echo json_encode($errMsg);   
		}
		
	}
	
	public function forgotPassword(){
		
		$password = $this->input->post('password');
		$conf_password = $this->input->post('conf_password');
		$fp = $this->input->post('data');

		$admin = array();
		$errMsg = array();

		$admin['admin_id'] = $this->session->has_userdata('ADMIN') ? $this->session->userdata['ADMIN']['admin_id'] : 0;
		$admin['admin_username'] = $this->session->has_userdata('ADMIN') ? $this->session->userdata['ADMIN']['admin_username'] : $fp['name'];
		$admin['admin_password'] = $conf_password ? md5($conf_password) : md5($fp['passw']);

		if($this->m_user->admin_update_password($admin)) {
			$this->session->unset_userdata('password');

			$errMsg = array(
				'success' => true,
				'message' => 'Ubah password berhasil'
			); 
			
		} else {

			$errMsg = array(
				'success' => false,
				'message' => 'Ubah password tidak berhasil'
			); 

		}

		echo json_encode($errMsg);   
		
	}
	
	public function logout(){
		session_destroy();
		redirect(base_url());
	}

}
