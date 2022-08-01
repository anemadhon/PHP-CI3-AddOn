<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Msi extends CI_Controller {
	
	public function __construct(){
		parent::__construct();  
		$this->load->library('auth');  
		if(!$this->auth->is_logged_in()) {
			redirect(base_url());
		}
		
		$this->load->library('l_general');
        
		// load model
		$this->load->model('dashboard_model', 'dash_model');
		$this->load->model('transaksi1/stock_model', 'st_model');
		$this->load->model('master/permission_model', 'm_perm');
		$this->load->model('transaksi1/productcosting_model', 'pc');
		$this->load->model('transaksi1/bomsubalt_model', 'bomsa');
	}

	public function dashboard(){
		
		$object['tglterkini'] = date("j M Y",strtotime($this->dash_model->posting_date_select_max()));

		$object['nama'][101]="PO from Vendor";
		$object['data'][101] = $this->dash_model->getCountPOVendor();
		$object['link'][101] = "/transaksi1/pofromvendor/add";

		$object['nama'][102]="Good Receipt From CK";
		$object['data'][102] = $this->dash_model->getCountGRfromKitchen();
		$object['link'][102] = "/transaksi1/grfromkitchensentul/add";

		$object['nama'][103] = "Good Issue Stock Transfer Antar Outlet";
		$object['data'][103] = $this->dash_model->getCountTransferOut();
		$object['link'][103] = "/transaksi1/transferoutinteroutlet/add";

		$object['nama'][104] = "Good Receipt From Outlet";
		$object['data'][104] = $this->dash_model->getCountTransferIn();
		$object['link'][104] = "/transaksi1/transferininteroutlet/add";
		
		$object['nama'][105] = "Integration Log";
		$object['data'][105] = $this->dash_model->getCountIntLog();
		$object['link'][105] = "/master/integration";

		$object['so_date'] = $this->st_model->getSODate();
		$object['so_status'] = $this->st_model->getLastSOOutlet();
		$object['so_last'] = $this->st_model->getLastSOOutlet('last');
		$object['so_next'] = $this->st_model->getNextSODate();

		if (strlen($this->auth->who_is_login()['flag']) == 2) {
			if ($this->auth->who_is_login()['flag'] == '01') {
				$object['prod_cost_data'] = $this->pc->getAllProdCostData($this->auth->who_is_login(), '01', 1);
				$object['prod_cost_data_head'] = $this->pc->getAllProdCostData($this->auth->who_is_login(), '01', 2);
				$object['prod_cost_data_ca'] = $this->pc->getAllProdCostData($this->auth->who_is_login(), '01', 3);
				$object['prod_cost_data_cc'] = $this->pc->getAllProdCostData($this->auth->who_is_login(), '01', 4);
				
				$object['bom_sub_alt_data'] = $this->bomsa->getAllBOMSubAltData($this->auth->who_is_login(), '01', 1);
				$object['bom_sub_alt_data_head'] = $this->bomsa->getAllBOMSubAltData($this->auth->who_is_login(), '01', 2);
				$object['bom_sub_alt_data_ca'] = $this->bomsa->getAllBOMSubAltData($this->auth->who_is_login(), '01', 3);
				$object['bom_sub_alt_data_cc'] = $this->bomsa->getAllBOMSubAltData($this->auth->who_is_login(), '01', 4);
			} 
			if ($this->auth->who_is_login()['flag'] == '02') {
				$object['prod_cost_data'] = $this->pc->getAllProdCostData($this->auth->who_is_login(), '02', 1);
				$object['prod_cost_data_head'] = $this->pc->getAllProdCostData($this->auth->who_is_login(), '02', 2);
				$object['prod_cost_data_ca'] = $this->pc->getAllProdCostData($this->auth->who_is_login(), '02', 3);
				$object['prod_cost_data_cc'] = $this->pc->getAllProdCostData($this->auth->who_is_login(), '02', 4);
				
				$object['bom_sub_alt_data'] = $this->bomsa->getAllBOMSubAltData($this->auth->who_is_login(), '02', 1);
				$object['bom_sub_alt_data_head'] = $this->bomsa->getAllBOMSubAltData($this->auth->who_is_login(), '02', 2);
				$object['bom_sub_alt_data_ca'] = $this->bomsa->getAllBOMSubAltData($this->auth->who_is_login(), '02', 3);
				$object['bom_sub_alt_data_cc'] = $this->bomsa->getAllBOMSubAltData($this->auth->who_is_login(), '02', 4);
			} 
			if ($this->auth->who_is_login()['flag'] == '03') {
				$object['prod_cost_data'] = $this->pc->getAllProdCostData($this->auth->who_is_login(), '03', 1);
				$object['prod_cost_data_head'] = $this->pc->getAllProdCostData($this->auth->who_is_login(), '03', 2);
				$object['prod_cost_data_ca'] = $this->pc->getAllProdCostData($this->auth->who_is_login(), '03', 3);
				$object['prod_cost_data_cc'] = $this->pc->getAllProdCostData($this->auth->who_is_login(), '03', 4);
				
				$object['bom_sub_alt_data'] = $this->bomsa->getAllBOMSubAltData($this->auth->who_is_login(), '03', 1);
				$object['bom_sub_alt_data_head'] = $this->bomsa->getAllBOMSubAltData($this->auth->who_is_login(), '03', 2);
				$object['bom_sub_alt_data_ca'] = $this->bomsa->getAllBOMSubAltData($this->auth->who_is_login(), '03', 3);
				$object['bom_sub_alt_data_cc'] = $this->bomsa->getAllBOMSubAltData($this->auth->who_is_login(), '03', 4);
			}
			if ($this->auth->who_is_login()['flag'] == '04') {
				$object['prod_cost_data'] = $this->pc->getAllProdCostData($this->auth->who_is_login(), '04', 1);
				$object['prod_cost_data_head'] = $this->pc->getAllProdCostData($this->auth->who_is_login(), '04', 2);
				$object['prod_cost_data_ca'] = $this->pc->getAllProdCostData($this->auth->who_is_login(), '04', 3);
				$object['prod_cost_data_cc'] = $this->pc->getAllProdCostData($this->auth->who_is_login(), '04', 4);
				
				$object['bom_sub_alt_data'] = $this->bomsa->getAllBOMSubAltData($this->auth->who_is_login(), '04', 1);
				$object['bom_sub_alt_data_head'] = $this->bomsa->getAllBOMSubAltData($this->auth->who_is_login(), '04', 2);
				$object['bom_sub_alt_data_ca'] = $this->bomsa->getAllBOMSubAltData($this->auth->who_is_login(), '04', 3);
				$object['bom_sub_alt_data_cc'] = $this->bomsa->getAllBOMSubAltData($this->auth->who_is_login(), '04', 4);
			} 
		} 
		
		if (strlen($this->auth->who_is_login()['flag']) == 4) {
			if ($this->auth->who_is_login()['flag'] == '0203') {
				$object['prod_cost_data'] = $this->pc->getAllProdCostData($this->auth->who_is_login(), '02', 1);
				$object['prod_cost_data_head'] = $this->pc->getAllProdCostData($this->auth->who_is_login(), '02', 2);
				$object['prod_cost_data_ca'] = $this->pc->getAllProdCostData($this->auth->who_is_login(), '03', 3);
				$object['prod_cost_data_cc'] = $this->pc->getAllProdCostData($this->auth->who_is_login(), '02', 4);
				
				$object['bom_sub_alt_data'] = $this->bomsa->getAllBOMSubAltData($this->auth->who_is_login(), '02', 1);
				$object['bom_sub_alt_data_head'] = $this->bomsa->getAllBOMSubAltData($this->auth->who_is_login(), '02', 2);
				$object['bom_sub_alt_data_ca'] = $this->bomsa->getAllBOMSubAltData($this->auth->who_is_login(), '03', 3);
				$object['bom_sub_alt_data_cc'] = $this->bomsa->getAllBOMSubAltData($this->auth->who_is_login(), '02', 4);
			} 
			if ($this->auth->who_is_login()['flag'] == '0204') {
				$object['prod_cost_data'] = $this->pc->getAllProdCostData($this->auth->who_is_login(), '04', 1);
				$object['prod_cost_data_head'] = $this->pc->getAllProdCostData($this->auth->who_is_login(), '02', 2);
				$object['prod_cost_data_ca'] = $this->pc->getAllProdCostData($this->auth->who_is_login(), '04', 3);
				$object['prod_cost_data_cc'] = $this->pc->getAllProdCostData($this->auth->who_is_login(), '04', 4);
				
				$object['bom_sub_alt_data'] = $this->bomsa->getAllBOMSubAltData($this->auth->who_is_login(), '04', 1);
				$object['bom_sub_alt_data_head'] = $this->bomsa->getAllBOMSubAltData($this->auth->who_is_login(), '02', 2);
				$object['bom_sub_alt_data_ca'] = $this->bomsa->getAllBOMSubAltData($this->auth->who_is_login(), '04', 3);
				$object['bom_sub_alt_data_cc'] = $this->bomsa->getAllBOMSubAltData($this->auth->who_is_login(), '04', 4);
			} 
			if ($this->auth->who_is_login()['flag'] == '0304') {
				$object['prod_cost_data'] = $this->pc->getAllProdCostData($this->auth->who_is_login(), '04', 1);
				$object['prod_cost_data_head'] = $this->pc->getAllProdCostData($this->auth->who_is_login(), '04', 2);
				$object['prod_cost_data_ca'] = $this->pc->getAllProdCostData($this->auth->who_is_login(), '03', 3);
				$object['prod_cost_data_cc'] = $this->pc->getAllProdCostData($this->auth->who_is_login(), '04', 4);
				
				$object['bom_sub_alt_data'] = $this->bomsa->getAllBOMSubAltData($this->auth->who_is_login(), '04', 1);
				$object['bom_sub_alt_data_head'] = $this->bomsa->getAllBOMSubAltData($this->auth->who_is_login(), '04', 2);
				$object['bom_sub_alt_data_ca'] = $this->bomsa->getAllBOMSubAltData($this->auth->who_is_login(), '03', 3);
				$object['bom_sub_alt_data_cc'] = $this->bomsa->getAllBOMSubAltData($this->auth->who_is_login(), '04', 4);
			} 
		}

		$object['username_login'] = $this->session->userdata['ADMIN']['admin_username'];

		$this->load->view('index', $object);
	}
	
	public function inpofromvendor(){
		
		$this->load->view('template/header');
		$this->load->view('transaksi1/eksternal/po_from_vendor');
		$this->load->view('template/footer');
	}
	
	public function purchaserequest(){
		
		$this->load->view('template/header');
		$this->load->view('transaksi1/eksternal/purchase_request');
		$this->load->view('template/footer');
	}
	
}
