<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require('./application/third_party/PHPExcel/PHPExcel.php');

class Bomsubalt extends CI_Controller{
     public function __construct(){
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		$this->load->library('auth');  
		if(!$this->auth->is_logged_in()) {
			redirect(base_url());
        }
        $this->load->library('form_validation');
        $this->load->library('l_general');
        
        // load model
		$this->load->model('transaksi1/bomsubalt_model', 'bomsa');
		$this->load->model('transaksi1/productcosting_model', 'pc');
    }

    public function index(){
        $this->load->view('transaksi1/eksternal/bom_sub_alt/list_view');
    }
	
	public function showListData(){
        $fromDate = $this->input->post('fDate');
        $toDate = $this->input->post('tDate');
		$status = $this->input->post('stts');

        $date_from2;
        $date_to2;

        if($fromDate != '') {
			$year = substr($fromDate, 6);
			$month = substr($fromDate, 3,2);
			$day = substr($fromDate, 0,2);
			$date_from2 = $year.'-'.$day.'-'.$month.' 00:00:00';
        }else{
            $date_from2='';
        }
        
        if($toDate != '') {
			$year = substr($toDate, 6);
			$month = substr($toDate, 3,2);
			$day = substr($toDate, 0,2);
			$date_to2 = $year.'-'.$day.'-'.$month.' 00:00:00';
        }else{
            $date_to2='';
        }
        
		$rs = $this->bomsa->getBomSubAltData($date_from2, $date_to2, $status, $this->auth->who_is_login());
		
		$data = array();

        foreach($rs as $key=>$val){

            $nestedData = array();
			$nestedData['id_bom_subalt_header'] 	    = $val['id_bom_subalt_header'];
			$nestedData['bom_subalt_no'] 			    = $val['bom_subalt_no'];
			$nestedData['bom_subalt_type'] 			    = $val['bom_type'];
            $nestedData['raw_mat'] 			            = $val['raw_mat_code_new'].' - '.$val['raw_mat_name_new'];
			$nestedData['status'] 					    = ($val['status'] == 1 || $val['status_head'] === 0 || $val['status_cat_approver'] === 0 || $val['status_cost_control'] === 0) ? 'Not Approved' : 'Approved';
            $nestedData['created_by'] 				    = $val['created_by'];
			$nestedData['created_date'] 			    = date("d-m-Y",strtotime($val['created_date']));
            $nestedData['approved_by'] 				    = $val['status'] == 2 ? $val['approved_by'] : ''; 
			$nestedData['approval_admin_date'] 		    = $val['status'] == 2 ? date("d-m-Y H:i:s",strtotime($val['approved_user_date'])) : ''; 
			$nestedData['status_head'] 				    = $val['status'] == 2 && $val['status_head'] == 1 ? 'Not Approved' : ($val['status_head'] === 0 ? 'Rejected' : ($val['status_head'] == 2 ? 'Approved' : '')); 
            $nestedData['head_dept'] 				    = $val['status_head'] == 1 ? '' : (($val['status_head'] === 0 || $val['status_head'] == 2) ? $val['head_dept'] : ''); 
            $nestedData['dept'] 					    = $val['status_head'] == 1 ? '' : (($val['status_head'] === 0 || $val['status_head'] == 2) ? $val['dept'] : ''); 
            $nestedData['approval_head_date'] 		    = $val['status_head'] == 1 ? '' : ($val['status_head'] === 0 ? date("d-m-Y H:i:s",strtotime($val['rejected_head_dept_date'])) : (($val['approved_head_dept_date'] && $val['status_head'] == 2) ? date("d-m-Y H:i:s",strtotime($val['approved_head_dept_date'])) : ''));
            $nestedData['status_cat_approver'] 			= $val['status'] == 2 && $val['status_head'] == 2 && $val['status_cat_approver'] == 1 ? 'Not Approved' : ($val['status_cat_approver'] === 0 ? 'Rejected' : ($val['status_cat_approver'] == 2 ? 'Approved' : '')); 
            $nestedData['cat_approver'] 				= $val['status_cat_approver'] == 1 ? '' : (($val['status_cat_approver'] === 0 || ($val['status_cat_approver'] == 2 && $val['approved_cat_approver_date'])) ? $val['category_approver'] : ''); 
            $nestedData['approval_cat_approver_date']	= $val['status_cat_approver'] == 1 ? '' : ($val['status_cat_approver'] === 0 ? date("d-m-Y H:i:s",strtotime($val['rejected_cat_approver_date'])) : ($val['approved_cat_approver_date'] && $val['status_cat_approver'] == 2 ? date("d-m-Y H:i:s",strtotime($val['approved_cat_approver_date'])) : '')); 
            $nestedData['status_cost_control'] 			= $val['status'] == 2 && $val['status_head'] == 2 && $val['status_cat_approver'] == 2 && $val['status_cost_control'] == 1 ? 'Not Approved' : ($val['status_cost_control'] === 0 ? 'Rejected' : ($val['status_cost_control'] == 2 ? 'Approved' : '')); 
            $nestedData['cost_control'] 				= $val['status_cost_control'] == 1 ? '' : (($val['status_cost_control'] === 0 || $val['status_cost_control'] == 2) ? $val['cost_control'] : ''); 
            $nestedData['approval_cost_control_date']	= $val['status_cost_control'] == 1 ? '' : ($val['status_cost_control'] === 0 ? date("d-m-Y H:i:s",strtotime($val['rejected_cost_control_date'])) : ($val['approved_cost_control_date'] && $val['status_cost_control'] == 2 ? date("d-m-Y H:i:s",strtotime($val['approved_cost_control_date'])) : ''));
            $nestedData['back']	                        = $val['back'] == 1 ? 'Not Integrated' : 'Integrated';
            $data[] = $nestedData;					
        }
		
		$json_data = array(
            "data" => $data 
        );
		
        echo json_encode($json_data);
	}
	
	public function add(){
		$object['plant'] = $this->session->userdata['ADMIN']['plant']; 
        $object['plant_name'] = $this->session->userdata['ADMIN']['plant_name'];
		$object['categories'] = $this->bomsa->getCategory();
       	
        $this->load->view('transaksi1/eksternal/bom_sub_alt/add_view',$object);
    }

    public function addData(){
        $createdDate = date('d-m-Y');
		$bom_subalt_header['id_bom_subalt_plant'] = $this->bomsa->selectIDPlant($this->session->userdata['ADMIN']['plant'], $this->l_general->str_to_date($createdDate));
		$bom_subalt_header['plant'] = $this->session->userdata['ADMIN']['plant'];
		$bom_subalt_header['plant_name'] = $this->session->userdata['ADMIN']['plant_name'];
        $bom_subalt_header['bom_type'] = $this->input->post('BOMType');
		$bom_subalt_header['item_group_header_old'] = $this->input->post('itemsGrpNew');
		$bom_subalt_header['raw_mat_code_old'] = $this->input->post('rawMatCodeOld');
		$bom_subalt_header['raw_mat_name_old'] = $this->input->post('rawMatNameOld');
		$bom_subalt_header['item_group_header_new'] = $this->input->post('itemsGrpNew');
		$bom_subalt_header['raw_mat_code_new'] = $this->input->post('rawMatCodeNew');
		$bom_subalt_header['raw_mat_name_new'] = $this->input->post('rawMatNameNew');
		$bom_subalt_header['category_code'] = $this->input->post('categoryCode');
		$bom_subalt_header['category_name'] = $this->input->post('categoryName');
		$bom_subalt_header['category_approver'] = $this->input->post('categoryApprover');
		$bom_subalt_header['status'] = $this->input->post('approve');
		$bom_subalt_header['status_head'] = 1;
		$bom_subalt_header['status_cat_approver'] = 1;
		$bom_subalt_header['status_cost_control'] = 1;
        $bom_subalt_header['created_date'] = date('Y-m-d H:i:s');
        $bom_subalt_header['id_user_input'] = $this->session->userdata['ADMIN']['admin_id'];
        if (strcasecmp($this->session->userdata['ADMIN']['admin_username'], 'sx_superuser_bom') == 0) {
            $bom_subalt_header['status'] = 2;
            $bom_subalt_header['status_head'] = 2;
            $bom_subalt_header['status_cat_approver'] = 2;
            $bom_subalt_header['approved_user_date'] = date('Y-m-d H:i:s');
            $bom_subalt_header['approved_head_dept_date'] = date('Y-m-d H:i:s');
            $bom_subalt_header['approved_cat_approver_date'] = date('Y-m-d H:i:s');
            $bom_subalt_header['id_user_approved'] = $this->session->userdata['ADMIN']['admin_id'];
            $bom_subalt_header['id_head_dept'] = $this->session->userdata['ADMIN']['admin_id'];
        } else {
            $bom_subalt_header['id_user_approved'] = $this->input->post('approve') == 2 ? $this->session->userdata['ADMIN']['admin_id'] : 0;
            $bom_subalt_header['id_cost_control'] = 0;
            $bom_subalt_header['id_head_dept'] = 0;
            $bom_subalt_header['back'] = 1;
            if ($this->input->post('approve') == 2 && $this->auth->is_head_dept()['head_dept'] == $this->session->userdata['ADMIN']['admin_id']) {
                $product_cost_header['status_head'] = 2;
                $product_cost_header['id_head_dept'] = $this->session->userdata['ADMIN']['admin_id'];
                $product_cost_header['approved_user_date'] = date('Y-m-d H:i:s');
                $product_cost_header['approved_head_dept_date'] = date('Y-m-d H:i:s');
            } else {
                $product_cost_header['status_head'] = 1;
                $product_cost_header['id_head_dept'] = 0;
            }
            if ($this->input->post('approve') == 2) {
                $bom_subalt_header['approved_user_date'] = date('Y-m-d H:i:s');
            }
        }
		
        $count = count($this->input->post('matrialNo'));
        
		if($id_bom_subalt_header = $this->bomsa->insertHeaderBomSubAlt($bom_subalt_header)) {
			$bom_subalt_header['bom_subalt_no'] = 'BOMSA'.date('Y').sprintf("%06s", $id_bom_subalt_header);
			if ($updateNoBomSubAlt = $this->bomsa->updateNoBomSubAlt($bom_subalt_header['bom_subalt_no'], $id_bom_subalt_header)) {
				$input_detail_success = FALSE;
				for($i = 0; $i < $count; $i++){
					$bom_subalt_detail['id_bom_subalt_header'] = $id_bom_subalt_header;
					$bom_subalt_detail['id_bom_subalt_h_detail'] = $i+1;
					$bom_subalt_detail['item_checked'] = $this->input->post('itemChecked')[$i];
					$bom_subalt_detail['item_group_detail'] = $this->input->post('itemGroup')[$i];
					$bom_subalt_detail['material_no'] = $this->input->post('matrialNo')[$i];
					$bom_subalt_detail['material_desc'] = $this->input->post('matrialDesc')[$i];
					$bom_subalt_detail['qty_old'] = $this->input->post('qtyOld')[$i];
					$bom_subalt_detail['uom_old'] = $this->input->post('uomOld')[$i];
					$bom_subalt_detail['tot_cost_old'] = $this->input->post('totCostOld')[$i];
					$bom_subalt_detail['qty_new'] = $this->input->post('qtyNew')[$i];
					$bom_subalt_detail['uom_new'] = $this->input->post('uomNew')[$i];
					$bom_subalt_detail['tot_cost_new'] = $this->input->post('totCostNew')[$i];
					$bom_subalt_detail['variance'] = $this->input->post('variance')[$i];
                    $bom_subalt_detail['variance_percentage'] = $this->input->post('variancePercentage')[$i];
                    $bom_subalt_detail['sap_line'] = $this->input->post('SAPLine')[$i];
                    
					if($this->bomsa->insertDetailBomSubAlt($bom_subalt_detail) ){
						$input_detail_success = TRUE;
					}
				}
			}
		}
        if($input_detail_success){
			$this->reminderEmail($bom_subalt_header, 'add', $bom_subalt_detail['id_bom_subalt_header']);
            return $this->session->set_flashdata('success', "BOM Sub & Alt Telah Terbentuk");
        }else{
            return $this->session->set_flashdata('failed', "BOM Sub & Alt Gagal Terbentuk");
        } 
	}
    
    public function edit(){
		$id = $this->uri->segment(4);
		$object['categories'] = $this->bomsa->getCategory();
        $object['data'] = $this->bomsa->selectBomSubAltHeader($id);
        $object['qf'] = $this->bomsa->getCatApproverName($object['data']['category_code']);
        $object['newRM'] = $this->bomsa->getNewUOMLastPriceItemsDetail($object['data']['raw_mat_code_new']);
        $object['dept'] = $this->bomsa->getDeptUserLogin($this->session->userdata['ADMIN']['admin_id']);
		
        $object['bsa']['id_bom_subalt_header'] = $object['data']['id_bom_subalt_header'];
        $object['bsa']['bom_subalt_no'] = $object['data']['bom_subalt_no'];
        $object['bsa']['bom_type'] = $object['data']['bom_type'];
        $object['bsa']['item_group_header_old'] = $object['data']['item_group_header_old'];
        $object['bsa']['raw_mat_code_old'] = $object['data']['raw_mat_code_old'];
        $object['bsa']['raw_mat_name_old'] = $object['data']['raw_mat_name_old'];
        $object['bsa']['item_group_header_new'] = $object['data']['item_group_header_new'];
        $object['bsa']['raw_mat_code_new'] = $object['data']['raw_mat_code_new'];
        $object['bsa']['raw_mat_name_new'] = $object['data']['raw_mat_name_new'];
        $object['bsa']['raw_mat_uom_new'] = $object['newRM']['UNIT'];
        $object['bsa']['raw_mat_last_price_new'] = $object['newRM']['LastPrice'];
        $object['bsa']['category_code'] = $object['data']['category_code'];
        $object['bsa']['category_name'] = $object['data']['category_name'];
        $object['bsa']['category_approver'] = $object['qf']['approver'];
		$object['bsa']['status'] = $object['data']['status'];
		$object['bsa']['status_head'] = $object['data']['status_head'];
		$object['bsa']['status_cat_approver'] = $object['data']['status_cat_approver'];
		$object['bsa']['status_cost_control'] = $object['data']['status_cost_control'];
		$object['bsa']['reject_reason'] = $object['data']['reject_reason'];
        $object['bsa']['user_input'] = $object['data']['id_user_input'];
		$object['bsa']['username_dept'] = $object['dept']['dept'];
		$object['bsa']['userid_login'] = $this->session->userdata['ADMIN']['admin_id'];
		$object['bsa']['username_login'] = $this->session->userdata['ADMIN']['admin_username'];
		
        $this->load->view('transaksi1/eksternal/bom_sub_alt/edit_view', $object);
    }

    public function updateData(){
		$id = $this->input->post('idBOM');
		$approve = $this->input->post('approve');
        $bom_subalt_header['id_bom_subalt_header'] = $id;
        $bom_subalt_header['bom_type'] = $this->input->post('BOMType');
		$bom_subalt_header['item_group_header_old'] = $this->input->post('itemsGrpOld');
		$bom_subalt_header['raw_mat_code_old'] = $this->input->post('rawMatCodeOld');
		$bom_subalt_header['raw_mat_name_old'] = $this->input->post('rawMatNameOld');
		$bom_subalt_header['item_group_header_new'] = $this->input->post('itemsGrpNew');
		$bom_subalt_header['raw_mat_code_new'] = $this->input->post('rawMatCodeNew');
		$bom_subalt_header['raw_mat_name_new'] = $this->input->post('rawMatNameNew');
		$bom_subalt_header['category_code'] = $this->input->post('categoryCode');
		$bom_subalt_header['category_name'] = $this->input->post('categoryName');
		$bom_subalt_header['category_approver'] = $this->input->post('categoryApprover');
        $bom_subalt_header['lastmodified'] = date('Y-m-d H:i:s');
		$bom_subalt_header['id_user_input'] = $this->input->post('userInput');
        if ($approve == 1) {
			$bom_subalt_header['flag'] = 1;
        }
		if ($approve == 2) {
            $bom_subalt_header['status'] = 2;
			$bom_subalt_header['approved_user_date'] = date('Y-m-d H:i:s');
			$bom_subalt_header['id_user_approved'] = $this->session->userdata['ADMIN']['admin_id'];
			$bom_subalt_header['flag'] = 2;
			if ($this->auth->is_head_dept()['head_dept'] == $this->session->userdata['ADMIN']['admin_id']) {
                $bom_subalt_header['status_head'] = 2;
                $bom_subalt_header['approved_head_dept_date'] = date('Y-m-d H:i:s');
                $bom_subalt_header['id_head_dept'] = $this->session->userdata['ADMIN']['admin_id'];
			} else {
                $bom_subalt_header['status_head'] = 1;
			}
			$bom_subalt_header['status_cat_approver'] = 1;
			$bom_subalt_header['status_cost_control'] = 1;
        }
		if ($approve == 3) {
            $bom_subalt_header['status'] = 2;
			$bom_subalt_header['status_head'] = 2;
			$bom_subalt_header['approved_head_dept_date'] = date('Y-m-d H:i:s');
			$bom_subalt_header['id_head_dept'] = $this->session->userdata['ADMIN']['admin_id'];
			$bom_subalt_header['flag'] = 3;
			if (strcasecmp($this->auth->is_head_dept()['head_dept_username'], $bom_subalt_header['category_approver']) == 0) {
                $bom_subalt_header['head_dept_username'] = $this->auth->is_head_dept()['head_dept_username'];
				$bom_subalt_header['status_cat_approver'] = 2;
				$bom_subalt_header['approved_cat_approver_date'] = date('Y-m-d H:i:s');
			}
		}
		if ($approve == 4) {
            $bom_subalt_header['status_cat_approver'] = 2;
			$bom_subalt_header['approved_cat_approver_date'] = date('Y-m-d H:i:s');
			$bom_subalt_header['flag'] = 4;
		}
		if ($approve == 5) {
            $bom_subalt_header['status_cost_control'] = 2;
			$bom_subalt_header['approved_cost_control_date'] = date('Y-m-d H:i:s');
			$bom_subalt_header['id_cost_control'] = $this->session->userdata['ADMIN']['admin_id'];
			$bom_subalt_header['flag'] = 5;
        }
        
		$count = count($this->input->post('matrialNo'));

        $bom_subalt_header_update = $this->bomsa->updateDataBomSubAltHeader($bom_subalt_header);
        $succes_update = false;
        
        if ($approve > 2) {
            if($bom_subalt_header_update){
                $succes_update = true;
            }
        } else {
            if($bom_subalt_header_update){
                $this->bomsa->selectBomSubAltDetailForDelete($id);
                for($i = 0; $i < $count; $i++){
                    $bom_subalt_detail['id_bom_subalt_header'] = $id;
                    $bom_subalt_detail['id_bom_subalt_h_detail'] = $i+1;
                    $bom_subalt_detail['item_checked'] = $this->input->post('itemChecked')[$i];
                    $bom_subalt_detail['item_group_detail'] = $this->input->post('itemGroup')[$i];
                    $bom_subalt_detail['material_no'] = $this->input->post('matrialNo')[$i];
                    $bom_subalt_detail['material_desc'] = $this->input->post('matrialDesc')[$i];
                    $bom_subalt_detail['qty_old'] = $this->input->post('qtyOld')[$i];
                    $bom_subalt_detail['uom_old'] = $this->input->post('uomOld')[$i];
                    $bom_subalt_detail['tot_cost_old'] = $this->input->post('totCostOld')[$i];
                    $bom_subalt_detail['qty_new'] = $this->input->post('qtyNew')[$i];
                    $bom_subalt_detail['uom_new'] = $this->input->post('uomNew')[$i];
                    $bom_subalt_detail['tot_cost_new'] = $this->input->post('totCostNew')[$i];
                    $bom_subalt_detail['variance'] = $this->input->post('variance')[$i];
                    $bom_subalt_detail['variance_percentage'] = $this->input->post('variancePercentage')[$i];
                    $bom_subalt_detail['sap_line'] = $this->input->post('SAPLine')[$i];
                                
                    if($this->bomsa->insertDetailBomSubAlt($bom_subalt_detail)){
                        $succes_update = TRUE;
                    }
                }
            }
        }
		
        if($succes_update){
			$this->reminderEmail($bom_subalt_header, 'edit');
            return $this->session->set_flashdata('success', "BOM Sub & Alt Telah Berhasil Terupdate");
        }else{
            return $this->session->set_flashdata('failed', "BOM Sub & Alt Gagal Terupdate");
        }
	} 
    
    public function deleteData(){
        $idProCost = $this->input->post('deleteArr');
        $deleteData = false;

        foreach($idProCost as $id){
            if($this->bomsa->deleteBomSubAltHeader($id))
            $deleteData = true;
        }
        
        if($deleteData){
            return $this->session->set_flashdata('success', "BOM Sub & Alt Berhasil dihapus");
        }else{
            return $this->session->set_flashdata('failed', "BOM Sub & Alt Approved, Gagal dihapus");
        }
    }

    function reject(){	
		$reject['id_bom_subalt_header'] = $this->input->post('id');
		if ($this->input->post('whosRejectFlag') == 'head') {
			$reject['status_head'] = 0;
			$reject['rejected_head_dept_date'] = date('Y-m-d H:i:s');
			$reject['id_user_approved'] = $this->session->userdata['ADMIN']['admin_id'];
			$reject['whosRejectFlag'] = 1;
		} elseif ($this->input->post('whosRejectFlag') == 'catApp') {
			$reject['status_cat_approver'] = 0;
			$reject['rejected_cat_approver_date'] = date('Y-m-d H:i:s');
			$reject['whosRejectFlag'] = 2;
		} elseif ($this->input->post('whosRejectFlag') == 'costControl') {
			$reject['status_cost_control'] = 0;
			$reject['rejected_cost_control_date'] = date('Y-m-d H:i:s');
			$reject['id_cost_control'] = $this->session->userdata['ADMIN']['admin_id'];
			$reject['whosRejectFlag'] = 3;
		}
		$reject['reject_reason'] = $this->input->post('reason');

		if($this->bomsa->reject($reject)){
			return $this->session->set_flashdata('failed', "BOM Sub & Alt Rejected");
		} else {
			return $this->session->set_flashdata('failed', "BOM Sub & Alt Gagal di Reject");
		}
    }
    
    public function showDataDetailOnEdit(){
        $id = $this->input->post('id');
        $header = $this->bomsa->selectBomSubAltHeader($id);
        $rs = $this->bomsa->selectBomSubAltDetail($id);

        $dt = array();
        $i = 1;

        if ($rs) {
            foreach ($rs as $data) {
				$new = $this->bomsa->getUnitPrice($header['raw_mat_code_old'], $data['material_no'], $data['sap_line']);
                $nestedData = array();
				$nestedData['0'] = $data['item_checked'];
				$nestedData['1'] = $i;
				$nestedData['2'] = $data['item_group_detail'];
				$nestedData['3'] = $data['material_no'];
				$nestedData['4'] = $data['material_desc'];
				$nestedData['5'] = $data['sap_line'];
				$nestedData['6'] = number_format($data['qty_old'],4);
				$nestedData['7'] = $data['uom_old'];
                $nestedData['8'] = number_format($data['qty_new'],4);
                $nestedData['9'] = $data['uom_new'];
				$nestedData['10'] = number_format($data['tot_cost_old'],4);
                $nestedData['11'] = number_format($data['tot_cost_new'],4); 
                $nestedData['12'] = number_format($data['variance'],4);
				$nestedData['13'] = number_format($data['variance_percentage'],4);
				$nestedData['14'] = number_format($new['UnitPrice'] ? $new['UnitPrice']: 0, 4);
				$dt[] = $nestedData;
				$i++;
            }
        }

        $json_data = array(
			"data" => $dt
        );
        
		echo json_encode($json_data);
    }
    
    public function getItemsGrpSelectItems(){
        $object['matrialGroup'] = $this->bomsa->showMatrialGroup();

        echo json_encode($object);
    }

    public function getRawMatByItmGrpItems(){
        $itmGrp = $this->input->post('itmGrp');
        $rs = $this->bomsa->getAllDataItems($itmGrp);

        echo json_encode($rs);
	}

	public function getCatApproverName(){
		$code = $this->input->post('code');
        $data = $this->bomsa->getCatApproverName($code);
        
		$json_data=array(
			"data" => $data
        );
        
		echo json_encode($json_data);
    }
    
    public function getDetailsDataByCurrentRawMat(){
        $rmCode = $this->input->post('rmCode');
        $mode = $this->input->post('mode');
        $category = $this->input->post('category');
        $rs = $this->bomsa->getDataItemSelected($rmCode, $category);

        $dt = array();
        $i = 1;

        if ($rs) {
            foreach ($rs as $data) {

				$currentLastPurchase = $data['LastPrice'] == ".000000" ? "0.0000" : number_format($data['LastPrice'] * 1.1,4);
				$currentQty = $data['QTY'] == ".000000" ? "0.0000" : number_format($data['QTY'],4);
				
                $nestedData=array();
				$nestedData['0'] = '<input type="checkbox" class="is-checked">';
				$nestedData['1'] = $i;
				$nestedData['2'] = $data['DSNAM'];
				$nestedData['3'] = $data['MATNR'];
				$nestedData['4'] = $data['MAKTX'];
				$nestedData['5'] = $data['SAPLINE'];
				$nestedData['6'] = $currentQty;
				$nestedData['7'] = $data['UNIT'];
				$nestedData['8'] = $mode == 'input' ? '<input type="text" class="form-control qty-new" name="qty_new" id="qtyNew_'.$i.'" value="'.$currentQty.'" matqty="'.$currentQty.'" matprc="" style="width:110px" autocomplete="off" readonly>' : $currentQty;
				$nestedData['9'] = '';
				$nestedData['10'] = $currentLastPurchase;
				$nestedData['11'] = '';
				$nestedData['12'] = '';
				$nestedData['13'] = '';
				$dt[] = $nestedData;
				$i++;
            }
        }

        $json_data = array(
			"data" => $dt
        );
        
		echo json_encode($json_data);
    }

    public function getNewUOMLastPriceItemsDetail(){
        $rmCode = $this->input->post('rmCode');
        $rs = $this->bomsa->getNewUOMLastPriceItemsDetail($rmCode);

        echo json_encode($rs);
	}
	
	public function getUnitPrice(){
        $rmCode = $this->input->post('rmOld');
        $father = $this->input->post('father');
        $line = $this->input->post('line');
        $rs = $this->bomsa->getUnitPrice($rmCode, $father, $line);

        echo json_encode($rs);
	}

	public function getDifferentImpact()
	{
		$arrayItemsCode = [];
		$arrayVariance = [];
		foreach ($this->input->post('arrayItem') as $items) {
			$arrayItemsCode[] = "'".$items."'";
		}
		foreach ($this->input->post('arrayVariance') as $variance) {
			$temp = explode('|', $variance);
			$arrayVariance[$temp[0]] = $temp[1];
		}
		$arrayNewCost = $this->input->post('arrayNewCost');
		$rs = $this->bomsa->getDifferentImpact($arrayItemsCode);
		
		$dt = array();
        $i = 1;
		
        if ($rs) {
			foreach ($rs as $key => $data) {
				
				$nestedData = array();
				$nestedData['0'] = $i;
				$nestedData['1'] = $data['FGCode'];
				$nestedData['2'] = $data['FGName'];
				$nestedData['3'] = $data['FGCodeImpact'];
				$nestedData['4'] = $data['FGNameImpact'];
				$nestedData['5'] = number_format($data['oldQty'],4);
				$nestedData['6'] = number_format($data['oldPrice'] * 1.1,4);
				$nestedData['7'] = number_format(($data['oldQty'] * $arrayVariance[$data['FGCode']]) + ($data['oldPrice'] * 1.1),4);
				$dt[] = $nestedData;
				$i++;
            }
        }

        $json_data = array(
			"data" => $dt
        );

		echo json_encode($json_data);
	}

    public function printXls($id){
		$object['header'] = $this->bomsa->selectBomSubAltHeader($id);
		$object['detail'] = $this->bomsa->selectBomSubAltDetail($id);

        $excel = new PHPExcel();

        //set config for column width
        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(70);
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(70);
        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('M')->setWidth(20);

        // set config for title header file
        $excel->getActiveSheet()->getStyle('B2')->getAlignment()
              ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('B3')->getAlignment()
              ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excel->getActiveSheet()->getStyle('B2')->getFont()->setBold(true);
        $excel->getActiveSheet()->getStyle('B3')->getFont()->setBold(true);

        $excel->getActiveSheet()->mergeCells('B2:M2');
        $excel->setActiveSheetIndex(0)->setCellValue('B2', 'BOM Sub & Alt'); 
		
		// set config for title header
        $excel->getActiveSheet()->getStyle('B5:B9')->getFont()->setBold(true);

        $excel->setActiveSheetIndex(0)->setCellValue('B5', "BOM Type"); 
        $excel->setActiveSheetIndex(0)->setCellValue('B6', "Current Raw Mat"); 
        $excel->setActiveSheetIndex(0)->setCellValue('B7', "New Raw Mat"); 
		$excel->setActiveSheetIndex(0)->setCellValue('B8', "Category"); 
		$excel->setActiveSheetIndex(0)->setCellValue('B9', "Status");  
		
		// set config for value header
		$excel->setActiveSheetIndex(0)->setCellValue('C5', $object['header']['bom_type'] == 1 ? 'Subtitution' : 'Alternative'); 
		$excel->setActiveSheetIndex(0)->setCellValue('C6', $object['header']['raw_mat_code_old'].' - '.$object['header']['raw_mat_name_old']); 
        $excel->setActiveSheetIndex(0)->setCellValue('C7', $object['header']['raw_mat_code_new'].' - '.$object['header']['raw_mat_name_new']); 
        $excel->setActiveSheetIndex(0)->setCellValue('C8', $object['header']['category_name']); 
		$excel->setActiveSheetIndex(0)->setCellValue('C9', 'Approved'); 

		//style of border
        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => '00000000'),
                ),
            ),
        );
        
        $excel->getActiveSheet()->getStyle('B13:M13')->applyFromArray($styleArray);

        // set config for title header table 
        $excel->getActiveSheet()->getStyle('B13:M13')->getAlignment()
              ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excel->getActiveSheet()->getStyle('B13:M13')->getAlignment()
              ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $excel->getActiveSheet()->getStyle('B13:M13')->getFont()->setBold(true);

        $excel->setActiveSheetIndex(0)->setCellValue('B12', "List Items"); 
        $excel->setActiveSheetIndex(0)->setCellValue('B13', "No"); 
        $excel->setActiveSheetIndex(0)->setCellValue('C13', "Item Group"); 
        $excel->setActiveSheetIndex(0)->setCellValue('D13', "Item Code"); 
		$excel->setActiveSheetIndex(0)->setCellValue('E13', "Item Desc"); 
		$excel->setActiveSheetIndex(0)->setCellValue('F13', "Current Quantity"); 
        $excel->setActiveSheetIndex(0)->setCellValue('G13', "Current UOM"); 
        $excel->setActiveSheetIndex(0)->setCellValue('H13', "Current Unit Cost"); 
		$excel->setActiveSheetIndex(0)->setCellValue('I13', "New Quantity"); 
        $excel->setActiveSheetIndex(0)->setCellValue('J13', "New UOM"); 
        $excel->setActiveSheetIndex(0)->setCellValue('K13', "New Unit Cost"); 
		$excel->setActiveSheetIndex(0)->setCellValue('L13', "Variance"); 
		$excel->setActiveSheetIndex(0)->setCellValue('M13', "Variance (%)"); 
        
		$num = 14;
        foreach($object['detail'] as $key => $detail){ 

			$excel->getActiveSheet()->getStyle('B'.$num)->getAlignment()
			  ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle('G'.$num)->getAlignment()
			  ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle('J'.$num)->getAlignment()
			  ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			
            // applying border style
            $excel->getActiveSheet()->getStyle('B'.$num.':M'.$num)->applyFromArray($styleArray);

            $excel->setActiveSheetIndex(0)->setCellValue('B'.$num, ($key+1));
            $excel->setActiveSheetIndex(0)->setCellValue('C'.$num, $detail['item_group_detail']);
            $excel->setActiveSheetIndex(0)->setCellValue('D'.$num, $detail['material_no']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$num, $detail['material_desc']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$num, number_format($detail['qty_old'],4));
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$num, $detail['uom_old']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$num, number_format($detail['tot_cost_old'],4));
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$num, number_format($detail['qty_new'],4));
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$num, $detail['uom_new']);
			$excel->setActiveSheetIndex(0)->setCellValue('K'.$num, number_format($detail['tot_cost_new'],4));
			$excel->setActiveSheetIndex(0)->setCellValue('L'.$num, number_format($detail['variance'],4));
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$num, number_format($detail['variance_percentage'],4));

            $num++;
		}
    
        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);
        // Set orientasi kertas jadi LANDSCAPE
        $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        // Set judul file excel nya
        $excel->getActiveSheet(0)->setTitle("BOM Sub & Alt");
        $excel->setActiveSheetIndex(0);
        // Proses file excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="BOM Sub & Alt.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');
        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');
	}

	public function reminderEmail($header, $flag, $idAdd=''){
		$config = [
			'protocol' => 'smtp',
			'smtp_host' => 'ssl://smtp.gmail.com',
			'smtp_port' => 465,
			'smtp_user' => 'addon.notifier@harvestgroup.co.id',
			'smtp_pass' => '9Se#@qnp',
			'mailtype' => 'html',
			'charset' => 'iso-8859-1',
			'wordwrap' => TRUE
		];

		$this->load->library('email', $config);
		$this->email->initialize($config);

		$to = [];
		
		if ($flag == 'add') {
			$header['id_bom_subalt_header'] = $idAdd;
		}

		$requestor = $this->pc->getNameNEmailById($header['id_user_input']); //get email & name by id

		if (($header['flag'] == 1 && $flag == 'edit') || (isset($header['status']) && $header['status'] == 1 && $flag == 'add')) { //status new, status edit, save  
			$to = $this->pc->getNameNEmailById($this->session->userdata['ADMIN']['admin_id']); //get email & name by id
		} elseif ((isset($header['status']) && $header['status'] == 2 && isset($header['flag']) && $header['flag'] == 2) || (isset($header['status']) && $header['status'] == 2 && $flag == 'add')) { //status edit, approve pembuat
			if (isset($header['status_head']) && $header['status_head'] == 2) { //status edit, approve pembuat & HOD
				$to = $this->pc->getNameNEmailByUsername($header['category_approver']); //get email & name by username
			} else {
				$to = $this->pc->getNameNEmailById($requestor['hod']); //get email & name by id
			}
		} elseif (isset($header['status_head']) && $header['status_head'] == 2 && isset($header['flag']) && $header['flag'] == 3) { //status edit, approve HOD
			if (isset($header['status_cat_approver']) && $header['status_cat_approver'] == 2) { //status edit, approve HOD & Cat Approver
				$to = $this->pc->getAllNameNEmailCostControl(); //get all email & name cost control
			} else {
				$to = $this->pc->getNameNEmailByUsername($header['category_approver']); //get email & name by username
			}
		} elseif (isset($header['status_cat_approver']) && $header['status_cat_approver'] == 2 && isset($header['flag']) && $header['flag'] == 4) { //status edit, approve Cat Approver
			if (isset($header['status_cost_control']) && $header['status_cost_control'] == 2) { //status edit, approve Cat Approver & Cost Control
				//tdk ada notif email, approval selesai
			} else {
				$to = $this->pc->getAllNameNEmailCostControl(); //get all email & name cost control
			}
		}

		if (count($to) > 0) {
			$template['template'] = [
				'to' => count($to) > 4 ? 'Cost Control' : $to['username'],
				'from' => $requestor,
				'hod' => $this->pc->getNameNEmailById($requestor['hod']),
				'date_appoved' => $this->bomsa->selectBomSubAltHeader(($flag == 'add' ? $idAdd : $header['id_bom_subalt_header'])),
				'rm_current' => $this->bomsa->getNewUOMLastPriceItemsDetail($header['raw_mat_code_old']),
				'rm_new' => $this->bomsa->getNewUOMLastPriceItemsDetail($header['raw_mat_code_new']),
				'header' => $header,
				'detail' => $this->bomsa->selectBomSubAltDetail(($flag == 'add' ? $idAdd : $header['id_bom_subalt_header']))
			];

			$message = $this->load->view('transaksi1/eksternal/bom_sub_alt/email_view', $template, TRUE);

			$subject = 'PENGAJUAN '.($header['bom_type'] == 1 ? 'SUBTITUSI' : 'ALTERNATIF').' BY '.strtoupper($requestor['name']);

			$this->email->set_newline("\r\n");
			$this->email->from('addon.notifier@harvestgroup.co.id', 'Add On Notifier');
			if (count($to) > 4) {
				$emailCostControl = [];
				foreach ($to as $value) {
					array_push($emailCostControl,$value['email']);
				}
				$this->email->to($emailCostControl);
			} elseif (count($to) == 4) {
				$this->email->to($to['email']);
			}
			$this->email->subject($subject);
			$this->email->message($message);
			if($this->email->send()) {
				if (count($to) > 4) {
					print_r($emailCostControl);
				} else {
					print_r($to);
				}
			} else {
				show_error($this->email->print_debugger());
			}
		} else {
			echo 'Approval Selesai';
		}
		
	}
}
?>