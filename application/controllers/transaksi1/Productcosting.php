<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require('./application/third_party/PHPExcel/PHPExcel.php');

class Productcosting extends CI_Controller{
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
		$this->load->model('transaksi1/productcosting_model', 'pc');
    }

    public function index(){
        $this->load->view('transaksi1/produksi/product_costing/list_view');
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
        
		$rs = $this->pc->getProdCostData($date_from2, $date_to2, $status, $this->auth->who_is_login());
		$data = array();

        foreach($rs as $key=>$val){

            $nestedData = array();
			$nestedData['id_prod_cost_header'] 			= $val['id_prod_cost_header'];
			$nestedData['prod_cost_no'] 				= $val['prod_cost_no'];
			$nestedData['product_name'] 				= $val['product_name'];
            $nestedData['existing_bom'] 				= $val['existing_bom_code'].' - '.$val['existing_bom_name'];
			$nestedData['product_qty'] 					= number_format($val['product_qty'],2);
			$nestedData['product_uom'] 					= $val['product_uom'];
			$nestedData['status'] 						= ($val['status'] == 1 || $val['status_head'] === 0 || $val['status_cat_approver'] === 0 || $val['status_cost_control'] === 0) ? 'Not Approved' : 'Approved';
			$nestedData['created_date'] 				= date("d-m-Y",strtotime($val['created_date']));
            $nestedData['created_by'] 					= $val['created_by'];
            $nestedData['approved_by'] 					= $val['status'] == 2 ? $val['approved_by'] : ''; 
            $nestedData['approval_admin_date'] 			= $val['status'] == 2 && $val['approved_user_date'] != '1900-01-01 00:00:00.000' ? date("d-m-Y H:i:s",strtotime($val['approved_user_date'])) : ''; 
			$nestedData['status_head'] 					= $val['status'] == 2 && $val['status_head'] == 1 ? 'Not Approved' : ($val['status_head'] === 0 ? 'Rejected' : ($val['status_head'] == 2 ? 'Approved' : '')); 
            $nestedData['head_dept'] 					= $val['status_head'] == 1 ? '' : (($val['status_head'] === 0 || $val['status_head'] == 2) ? $val['head_dept'] : ''); 
            $nestedData['dept'] 						= $val['status_head'] == 1 ? '' : (($val['status_head'] === 0 || $val['status_head'] == 2) ? $val['dept'] : ''); 
            $nestedData['approval_head_date'] 			= $val['status_head'] == 1 ? '' : ($val['status_head'] === 0 ? date("d-m-Y H:i:s",strtotime($val['rejected_head_dept_date'])) : (($val['approved_head_dept_date'] && $val['approved_head_dept_date'] != '1900-01-01 00:00:00.000' && $val['status_head'] == 2) ? date("d-m-Y H:i:s",strtotime($val['approved_head_dept_date'])) : '')); 
            $nestedData['status_cat_approver'] 			= $val['product_type'] == 2 && $val['status'] == 2 && $val['status_head'] == 2 && $val['status_cat_approver'] == 1 ? 'Not Approved' : ($val['product_type'] == 2 && $val['status_cat_approver'] === 0 ? 'Rejected' : ($val['product_type'] == 2 && $val['status_cat_approver'] == 2 ? 'Approved' : '')); 
            $nestedData['cat_approver'] 				= $val['status_cat_approver'] == 1 ? '' : ($val['product_type'] == 2 && ($val['status_cat_approver'] === 0 || $val['status_cat_approver'] == 2) ? $val['category_approver'] : ''); 
            $nestedData['approval_cat_approver_date']	= $val['status_cat_approver'] == 1 ? '' : ($val['status_cat_approver'] === 0 ? date("d-m-Y H:i:s",strtotime($val['rejected_cat_approver_date'])) : ($val['product_type'] == 2 && $val['approved_cat_approver_date'] && $val['approved_cat_approver_date'] != '1900-01-01 00:00:00.000' && $val['status_cat_approver'] == 2 ? date("d-m-Y H:i:s",strtotime($val['approved_cat_approver_date'])) : '')); 
            $nestedData['status_cost_control'] 			= $val['product_type'] == 2 && $val['status'] == 2 && $val['status_head'] == 2 && $val['status_cat_approver'] == 2 && $val['status_cost_control'] == 1 ? 'Not Approved' : ($val['product_type'] == 2 && $val['status_cost_control'] === 0 ? 'Rejected' : ($val['product_type'] == 2 && $val['status_cost_control'] == 2 ? 'Approved' : '')); 
            $nestedData['cost_control'] 				= $val['status_cost_control'] == 1 ? '' : ($val['product_type'] == 2 && ($val['status_cost_control'] === 0 || $val['status_cost_control'] == 2) ? $val['cost_control'] : ''); 
            $nestedData['approval_cost_control_date']	= $val['status_cost_control'] == 1 ? '' : ($val['status_cost_control'] === 0 ? date("d-m-Y H:i:s",strtotime($val['rejected_cost_control_date'])) : ($val['product_type'] == 2 && $val['approved_cost_control_date'] && $val['approved_cost_control_date'] != '1900-01-01 00:00:00.000' && $val['status_cost_control'] == 2 ? date("d-m-Y H:i:s",strtotime($val['approved_cost_control_date'])) : '')); 
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
		$object['categories'] = $this->pc->getCategory();
		$existing_bom['bom'] = $this->pc->sapCostingExistBOMReferences();
		
        $object['existing_bom']['-'] = '';
        if($existing_bom['bom'] != FALSE){
            foreach($existing_bom['bom'] as $bom){
				$object['existing_bom'][$bom['Code']] = $bom['Code'].' - '.$bom['ItemName'];
            }
		}
       	
        $this->load->view('transaksi1/produksi/product_costing/add_view',$object);
	}

	public function showMatrialGroupIng(){
		$object['matrialGroupIng'] = $this->pc->showMatrialGroup('NONEPK');

        echo json_encode($object);
	}
	
	public function showMatrialGroupPack(){
		$object['matrialGroupPack'] = $this->pc->showMatrialGroup('PK');

        echo json_encode($object);
	}

	public function getDataForQFactorFormula(){
		$code = $this->input->post('code');
		$data = $this->pc->getDataForQFactorFormula($code);
		$json_data=array(
			"data" => $data
		);
		echo json_encode($json_data);
	}

	function getdataDetailMaterialSelect(){
        $itemSelect = $this->input->post('MATNR');
        
        $dataMatrialSelect = $this->pc->getDataItemSelected($itemSelect);
        $dataLastPrice = $this->pc->getDataLastPurchaseItemSelected($itemSelect);

        $json_data = array(
            "data" => $dataMatrialSelect,
            "dataLast" => $dataLastPrice
        );

        echo json_encode($json_data);
	}

	function getExistingBomData(){
		$materialNo = $this->input->post('material_no');
		$data = $this->pc->getExistingBomData($materialNo);	
		$json_data=array(
			"data" => $data
		);
		echo json_encode($json_data);
	}

	public function addItemRow(){
		$itmGrp = $this->input->post('itmGrp');
		$type = $this->input->post('type');
		if ($itmGrp == 'all') {
			if ($type == 'ing') {
				$sap = $this->pc->getAllDataItems($itmGrp, $type);
				$addon = $this->pc->getAllDataItemsWPSelling($itmGrp);
				$rs = array_merge($sap, $addon);
			} else {
				$rs = $this->pc->getAllDataItems($itmGrp, $type);
			}
		} elseif ($itmGrp == 1 || $itmGrp == 2) {
			$rs = $this->pc->getAllDataItemsWPSelling($itmGrp);
		} else {
			$rs = $this->pc->getAllDataItems($itmGrp, $type);
		}
		echo json_encode($rs);
	}

	public function showDetailInputIng(){
		$kode_paket = $this->input->post('kode_paket');
		$qty_header = $this->input->post('Qty');
		$qtyDefault = $this->input->post('qtyDefault');
        $rs = $this->pc->getDataDetailForExistingBomIng($kode_paket);
		
		$dt = array();
        $i = 1;
        if($rs){
            foreach($rs as $data){

				$getucaneditqty = '<input type="hidden" class="form-control" id="typeCostIng_'.$i.'"><input type="text" id="qtyCostIng_'.$i.'" class="form-control" value="'.number_format(($data['quantity'] / (float)$qtyDefault * (float)$qty_header),2).'" style="width:90px" autocomplete="off">';

				$queryUOM = $this->pc->getDataDetailUOMForExistingBom($data['material_no']);
				if(count($queryUOM)>0){
					$uom = $queryUOM[0]['UNIT'];
				} else {
					$uom = '';
				}
				
				$queryGetLastPurchase = $this->pc->getDataLastPurchaseItemSelected($data['material_no']);
				$lastPurchase = $queryGetLastPurchase['LastPrice'] == ".000000" ? "0.0000" : number_format($queryGetLastPurchase['LastPrice'],2);
				$querySAP2 = $this->pc->getDataDetailItemBOMForExistingBom($kode_paket,$data['material_no']);
				
				$select = '<select class="form-control form-control-select2 descmatIng" data-live-search="true" name="descmat" id="descmatIng_'.$i.'">
								<option value="'.$data['material_desc'].'" uOm="'.$uom.'" matqty="'.number_format(($data['quantity'] / (float)$qtyDefault * (float)$qty_header),2).'" matno="'.$data['material_no'].'" lastprice="'.$lastPurchase.'">'.$data['material_desc'].'</option>'; 
								if($querySAP2){
									foreach($querySAP2 as $_querySAP2){
										$queryGetSubLastPurchase = $this->pc->getDataLastPurchaseItemSelected($_querySAP2['U_SubsCode']);
										$subLastPurchase = $queryGetSubLastPurchase['LastPrice'] == ".000000" ? "0.0000" : number_format($queryGetSubLastPurchase['LastPrice'],2);
										if($_querySAP2['U_ItemCodeBOM'] == $data['material_no']){
											$select .= '<option value="'.$_querySAP2['NAME'].'" uOm="'.$_querySAP2['U_SubsUOM'].'" matqty="'.number_format(((float)$_querySAP2['U_SubsQty'] / (float)$qtyDefault * (float)$qty_header),2).'" matno="'.$_querySAP2['U_SubsCode'].'" lastprice="'.$subLastPurchase.'">'.$_querySAP2['NAME'].'</option>'; 
										}
									}
								}
				$select .= '</select>';
				
				$descolumn = '';
				if($querySAP2){
					foreach($querySAP2 as $_querySAP2){
						if($_querySAP2['U_ItemCodeBOM'] == $data['material_no']){
							$descolumn = $select;
						}else{
							$descolumn = $data['material_no'];
						}
					}
				}else{
					$descolumn = $select;
				}

				$nestedData=array();
				$nestedData['0'] = '<input type="checkbox" id="chkIng_'.$i.'" value="'.$i.'">';
				$nestedData['1'] = $i;
				$nestedData['2'] = $data['material_no'];
				$nestedData['3'] = $descolumn;
				$nestedData['4'] = $uom;
				$nestedData['5'] = $lastPurchase;
				$nestedData['6'] = $getucaneditqty;
				$nestedData['7'] = '';
				$dt[] = $nestedData;
				$i++;
			}
        }
        $json_data = array(
			"data" => $dt
		);
		echo json_encode($json_data);

	}
	
	public function showDetailInputPack(){
		$kode_paket = $this->input->post('kode_paket');
		$qty_header = $this->input->post('Qty');
		$qtyDefault = $this->input->post('qtyDefault');
        $rs = $this->pc->getDataDetailForExistingBomPack($kode_paket);
		
		$dt = array();
        $i = 1;
        if($rs){
            foreach($rs as $data){

				$getucaneditqty = '<input type="hidden" class="form-control" id="typeCostPack_'.$i.'"><input type="text" id="qtyCostPack_'.$i.'" class="form-control" value="'.number_format(($data['quantity'] / (float)$qtyDefault * (float)$qty_header),2).'" style="width:90px" autocomplete="off">';

				$queryUOM = $this->pc->getDataDetailUOMForExistingBom($data['material_no']);
				if(count($queryUOM)>0){
					$uom = $queryUOM[0]['UNIT'];
				} else {
					$uom = '';
				}
				
				$queryGetLastPurchase = $this->pc->getDataLastPurchaseItemSelected($data['material_no']);
				$lastPurchase = $queryGetLastPurchase['LastPrice'] == ".000000" ? "0.0000" : number_format($queryGetLastPurchase['LastPrice'],2);
				$querySAP2 = $this->pc->getDataDetailItemBOMForExistingBom($kode_paket,$data['material_no']);
				
				$select = '<select class="form-control form-control-select2 descmatPack" data-live-search="true" name="descmat" id="descmatPack_'.$i.'">
								<option value="'.$data['material_desc'].'" uOm="'.$uom.'" matqty="'.number_format(($data['quantity'] / (float)$qtyDefault * (float)$qty_header),2).'" matno="'.$data['material_no'].'" lastprice="'.$lastPurchase.'">'.$data['material_desc'].'</option>'; 
								if($querySAP2){
									foreach($querySAP2 as $_querySAP2){
										$queryGetSubLastPurchase = $this->pc->getDataLastPurchaseItemSelected($_querySAP2['U_SubsCode']);
										$subLastPurchase = $queryGetSubLastPurchase['LastPrice'] == ".000000" ? "0.0000" : number_format($queryGetSubLastPurchase['LastPrice'],2);
										if($_querySAP2['U_ItemCodeBOM'] == $data['material_no']){
											$select .= '<option value="'.$_querySAP2['NAME'].'" uOm="'.$_querySAP2['U_SubsUOM'].'" matqty="'.number_format(((float)$_querySAP2['U_SubsQty'] / (float)$qtyDefault * (float)$qty_header),2).'" matno="'.$_querySAP2['U_SubsCode'].'" lastprice="'.$subLastPurchase.'">'.$_querySAP2['NAME'].'</option>'; 
										}
									}
								}
				$select .= '</select>';
				
				$descolumn = '';
				if($querySAP2){
					foreach($querySAP2 as $_querySAP2){
						if($_querySAP2['U_ItemCodeBOM'] == $data['material_no']){
							$descolumn = $select;
						}else{
							$descolumn = $data['material_no'];
						}
					}
				}else{
					$descolumn = $select;
				}

				$nestedData=array();
				$nestedData['0'] = '<input type="checkbox" id="chkPack_'.$i.'" value="'.$i.'">';
				$nestedData['1'] = $i;
				$nestedData['2'] = $data['material_no'];
				$nestedData['3'] = $descolumn;
				$nestedData['4'] = $uom;
				$nestedData['5'] = $lastPurchase;
				$nestedData['6'] = $getucaneditqty;
				$nestedData['7'] = '';
				$dt[] = $nestedData;
				$i++;
			}
        }
        $json_data = array(
			"data" => $dt
		);
		echo json_encode($json_data);

	}

	public function addData(){
		$product_cost_header['id_prod_cost_plant'] = $this->pc->selectIDPlant($this->session->userdata['ADMIN']['plant'],$this->l_general->str_to_date($this->input->post('postDate')));
		$product_cost_header['plant'] = $this->session->userdata['ADMIN']['plant'];
		$product_cost_header['plant_name'] = $this->session->userdata['ADMIN']['plant_name'];
		$product_cost_header['category_code'] = $this->input->post('categoryCode');
		$product_cost_header['category_name'] = $this->input->post('categoryName');
		$product_cost_header['category_q_factor'] = $this->input->post('categoryQF');
		$product_cost_header['category_min'] = $this->input->post('categoryMin');
		$product_cost_header['category_max'] = $this->input->post('categoryMax');
		$product_cost_header['category_approver'] = $this->input->post('categoryApprover');
		$product_cost_header['existing_bom_code'] = $this->input->post('existingBomCode');
		$product_cost_header['existing_bom_name'] = $this->input->post('existingBomName');
		$product_cost_header['product_name'] = $this->input->post('productName');
		$product_cost_header['product_qty'] = $this->input->post('productQty');
		$product_cost_header['product_uom'] = $this->input->post('productUom');
		$product_cost_header['product_selling_price'] = $this->input->post('productSellPrice');
		$product_cost_header['product_q_factor'] = $this->input->post('productQFactor');
		$product_cost_header['product_percentage'] = $this->input->post('productPercentage');
		$product_cost_header['product_result'] = $this->input->post('productResult');
		$product_cost_header['product_result_div_product_qty'] = $this->input->post('productResultDivQtyProd');
		$product_cost_header['product_type'] = $this->input->post('productType');
		$product_cost_header['status'] = $this->input->post('approve');
		$product_cost_header['status_cat_approver'] = 1;
		$product_cost_header['status_cost_control'] = 1;
		$product_cost_header['posting_date'] = $this->l_general->str_to_date($this->input->post('postDate'));
		$product_cost_header['created_date'] = date('Y-m-d H:i:s');
		$product_cost_header['lastmodified'] = date('Y-m-d H:i:s');
		$product_cost_header['id_user_input'] = $this->session->userdata['ADMIN']['admin_id'];
		$product_cost_header['id_user_approved'] = $this->input->post('approve') == 2 ? $this->session->userdata['ADMIN']['admin_id'] : 0;
		$product_cost_header['id_cost_control'] = 0;
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
			$product_cost_header['approved_user_date'] = date('Y-m-d H:i:s');
		}
		
		$count = count($this->input->post('matrialNo'));
		if($id_product_cost_header = $this->pc->insertHeaderProdCost($product_cost_header)) {
			$product_cost_header['prod_cost_no'] = $this->input->post('productType') == 1 ? 'WP'.date('Y').sprintf("%06s", $id_product_cost_header) : 'FG'.date('Y').sprintf("%06s", $id_product_cost_header);
			if ($updateNoProdCost = $this->pc->updateNoProdCost($product_cost_header['prod_cost_no'],$id_product_cost_header)) {
				$input_detail_success = FALSE;
				for($i = 0; $i < $count; $i++){
					$product_cost_detail['id_prod_cost_header'] = $id_product_cost_header;
					$product_cost_detail['id_prod_cost_h_detail'] = $i+1;
					$product_cost_detail['material_no'] = $this->input->post('matrialNo')[$i];
					$product_cost_detail['material_desc'] = $this->input->post('matrialDesc')[$i];
					$product_cost_detail['item_type'] = $this->input->post('itemType')[$i];
					$product_cost_detail['item_qty'] = $this->input->post('itemQty')[$i];
					$product_cost_detail['item_uom'] = $this->input->post('itemUom')[$i];
					$product_cost_detail['item_cost'] = $this->input->post('itemCost')[$i];
					if($this->pc->insertDetailProdCost($product_cost_detail) ){
						$input_detail_success = TRUE;
					}
				}
			}
		}
        if($input_detail_success){
			$this->reminderEmail($product_cost_header, 'add', $product_cost_detail['id_prod_cost_header']);
            return $this->session->set_flashdata('success', "Product Costing Telah Terbentuk");
        }else{
            return $this->session->set_flashdata('failed', "Product Costing Gagal Terbentuk");
        } 
	}

	public function deleteData(){
        $idProCost = $this->input->post('deleteArr');
        $deleteData = false;
        foreach($idProCost as $id){
            if($this->pc->deleteProdCostHeader($id))
            $deleteData = true;
        }
        
        if($deleteData){
            return $this->session->set_flashdata('success', "Product Costing Berhasil dihapus");
        }else{
            return $this->session->set_flashdata('failed', "Product Costing Approved, Gagal dihapus");
        }
	}

	public function edit(){
		$id = $this->uri->segment(4);
		$object['categories'] = $this->pc->getCategory();
		$object['data'] = $this->pc->selectProdCostHeader($id);
		$object['qf'] = $this->pc->getDataForQFactorFormula($object['data']['category_code']);
		$object['dept'] = $this->pc->getDeptUserLogin($this->session->userdata['ADMIN']['admin_id']);
		
        $object['pc']['id_prod_cost_header'] = $object['data']['id_prod_cost_header'];
        $object['pc']['prod_cost_no'] = $object['data']['prod_cost_no'];
        $object['pc']['product_type'] = $object['data']['product_type'];
        $object['pc']['category_code'] = $object['data']['category_code'];
        $object['pc']['category_name'] = $object['data']['category_name'];
        $object['pc']['category_q_factor'] = $object['data']['category_q_factor'];
        $object['pc']['category_approver'] = $object['qf']['approver'];
        $object['pc']['q_factor_sap'] = $object['qf']['q_factor'];
        $object['pc']['min'] = $object['qf']['min_cost'];
        $object['pc']['max'] = $object['qf']['max_cost'];
        $object['pc']['existing_bom_code'] = $object['data']['existing_bom_code'];
        $object['pc']['existing_bom_name'] = $object['data']['existing_bom_name'];
        $object['pc']['product_name'] = $object['data']['product_name'];
        $object['pc']['product_qty'] = $object['data']['product_qty'];
        $object['pc']['product_uom'] = $object['data']['product_uom'];
        $object['pc']['product_selling_price'] = $object['data']['product_selling_price'];
        $object['pc']['product_percentage'] = number_format($object['data']['product_percentage'], 2).' %'; //
        $object['pc']['product_result'] = number_format($object['data']['product_result'], 2); //
		$object['pc']['status'] = $object['data']['status'];
		$object['pc']['status_head'] = $object['data']['status_head'];
		$object['pc']['status_cat_approver'] = $object['data']['status_cat_approver'];
		$object['pc']['status_cost_control'] = $object['data']['status_cost_control'];
		$object['pc']['reject_reason'] = $object['data']['reject_reason'];
        $object['pc']['posting_date'] = $object['data']['posting_date'];
        $object['pc']['user_input'] = $object['data']['id_user_input'];
		$object['pc']['plant'] = $this->session->userdata['ADMIN']['plant'].' - '.$this->session->userdata['ADMIN']['plant_name'];
		$object['pc']['user_login'] = $this->session->userdata['ADMIN']['admin_id'];
		$object['pc']['username_login'] = $this->session->userdata['ADMIN']['admin_username'];
		$object['pc']['username_dept'] = $object['dept']['dept'];
		
        $this->load->view('transaksi1/produksi/product_costing/edit_view', $object);
	}
	
	public function showDetailEdit(){
		$id = $this->input->post('id');
		$type = $this->input->post('type');
		$object = $this->pc->selectDataDetail($id,$type);
		$dt = array();
		$i = 1;
		if ($object) {
			foreach ($object as $data) {
				$nestedData=array();
				$nestedData['0'] = $data['id_prod_cost_detail'];
				$nestedData['1'] = $i;
				$nestedData['2'] = $data['material_no'];
				$nestedData['3'] = $data['material_desc'];
				$nestedData['4'] = $data['item_uom'];
				$nestedData['5'] = number_format($data['item_cost'],2);
				$nestedData['6'] = number_format($data['item_qty'],2);
				$nestedData['7'] = '';
				$dt[] = $nestedData;
				$i++;
			}
		}

		$json_data = array(
			"data" => $dt
		);
		echo json_encode($json_data);
	}

	public function updateData(){
		$id = $this->input->post('id');
		$approve = $this->input->post('approve');
		$prod_cost_header['id_prod_cost_header'] = $id;
		$prod_cost_header['prod_cost_no'] = $this->input->post('prodCostNo');
		$prod_cost_header['category_code'] = $this->input->post('categoryCode');
		$prod_cost_header['category_name'] = $this->input->post('categoryName');
		$prod_cost_header['category_q_factor'] = $this->input->post('categoryQF');
		$prod_cost_header['category_min'] = $this->input->post('categoryMin');
		$prod_cost_header['category_max'] = $this->input->post('categoryMax');
		$prod_cost_header['category_approver'] = $this->input->post('categoryApprover');
		$prod_cost_header['product_type'] = $this->input->post('productType');
		$prod_cost_header['product_name'] = $this->input->post('productName');
		$prod_cost_header['product_qty'] = $this->input->post('productQty');
		$prod_cost_header['product_uom'] = $this->input->post('productUom');
		$prod_cost_header['product_selling_price'] = $this->input->post('productSellPrice');
		$prod_cost_header['product_q_factor'] = $this->input->post('productQFactor');
		$prod_cost_header['product_percentage'] = $this->input->post('productPercentage');
		$prod_cost_header['product_result'] = $this->input->post('productResult');
		$prod_cost_header['product_result_div_product_qty'] = $this->input->post('productResultDivQtyProd');
		$prod_cost_header['posting_date'] = $this->l_general->str_to_date($this->input->post('postDate'));
		$prod_cost_header['lastmodified'] = date('Y-m-d H:i:s');
		$prod_cost_header['id_user_input'] = $this->input->post('userInput');
		if ($approve == 1) {
			$prod_cost_header['flag'] = 1;
		}
		if ($approve == 2) {
			$prod_cost_header['status'] = 2;
			$prod_cost_header['approved_user_date'] = date('Y-m-d H:i:s');
			$prod_cost_header['id_user_approved'] = $this->session->userdata['ADMIN']['admin_id'];
			$prod_cost_header['flag'] = 2;
			if ($this->auth->is_head_dept()['head_dept'] == $this->session->userdata['ADMIN']['admin_id']) {
				$prod_cost_header['status_head'] = 2;
				$prod_cost_header['approved_head_dept_date'] = date('Y-m-d H:i:s');
				$prod_cost_header['id_head_dept'] = $this->session->userdata['ADMIN']['admin_id'];
			} else {
				$prod_cost_header['status_head'] = 1;
			}
			$prod_cost_header['status_cat_approver'] = 1;
			$prod_cost_header['status_cost_control'] = 1;
		}
		if ($approve == 3) {
			$prod_cost_header['status'] = 2;
			$prod_cost_header['status_head'] = 2;
			$prod_cost_header['approved_head_dept_date'] = date('Y-m-d H:i:s');
			$prod_cost_header['id_head_dept'] = $this->session->userdata['ADMIN']['admin_id'];
			$prod_cost_header['flag'] = 3;
			if (strcasecmp($this->auth->is_head_dept()['head_dept_username'], $prod_cost_header['category_approver']) == 0) {
				$prod_cost_header['head_dept_username'] = $this->auth->is_head_dept()['head_dept_username'];
				$prod_cost_header['status_cat_approver'] = 2;
				$prod_cost_header['approved_cat_approver_date'] = date('Y-m-d H:i:s');
			}
		}
		if ($approve == 4) {
			$prod_cost_header['status_cat_approver'] = 2;
			$prod_cost_header['approved_cat_approver_date'] = date('Y-m-d H:i:s');
			$prod_cost_header['flag'] = 4;
		}
		if ($approve == 5) {
			$prod_cost_header['status_cost_control'] = 2;
			$prod_cost_header['approved_cost_control_date'] = date('Y-m-d H:i:s');
			$prod_cost_header['id_cost_control'] = $this->session->userdata['ADMIN']['admin_id'];
			$prod_cost_header['flag'] = 5;
		}
		$max = count($this->input->post('matrialNo'));

		$prod_cost_header_update = $this->pc->updateDataProdCostHeader($prod_cost_header);
		$succes_update = false;

		if ($approve > 2) {
            if($prod_cost_header_update){
                $succes_update = true;
            }
        } else {
			if($prod_cost_header_update){
				$this->pc->selectProdCostDetailForDelete($id);
				for($i = 0; $i < $max; $i++){
					$prod_cost_detail['id_prod_cost_header'] = $id;
					$prod_cost_detail['id_prod_cost_h_detail'] = $i+1;
					$prod_cost_detail['material_no'] = $this->input->post('matrialNo')[$i];
					$prod_cost_detail['material_desc'] = $this->input->post('matrialDesc')[$i];
					$prod_cost_detail['item_type'] = $this->input->post('itemType')[$i];
					$prod_cost_detail['item_qty'] = $this->input->post('itemQty')[$i];
					$prod_cost_detail['item_uom'] = $this->input->post('itemUom')[$i];
					$prod_cost_detail['item_cost'] = $this->input->post('itemCost')[$i];
								
					if($this->pc->insertDetailProdCost($prod_cost_detail)){
						$succes_update = TRUE;
					}
				}
			}
		}
		
		if($succes_update){
			$this->reminderEmail($prod_cost_header, 'edit');
			return $this->session->set_flashdata('success', "Product Costing Telah Berhasil Terupdate");
        }else{
            return $this->session->set_flashdata('failed', "Product Costing Gagal Terupdate");
        }
	}
	
	public function fromDashboard(){
		$status = $this->uri->segment(4);
		$object['data_costing'] = $this->pc->selectProdCostHeaderByUserLogin($this->auth->who_is_login(), $this->auth->who_is_login()['flag'], $status);
		
        $this->load->view('transaksi1/produksi/product_costing/from_dashboard_view', $object);
	}

	public function getDataFromDashboardAfterSelectProduct(){
		$id = $this->input->post('id');
		$object['data'] = $this->pc->selectProdCostHeader($id);
		$object['qf'] = $this->pc->getDataForQFactorFormula($object['data']['category_code']);
		$object['dept'] = $this->pc->getDeptUserLogin($this->session->userdata['ADMIN']['admin_id']);
		$object['categories'] = $this->pc->getCategory();
		
        $object['pc']['id_prod_cost_header'] = $object['data']['id_prod_cost_header'];
        $object['pc']['prod_cost_no'] = $object['data']['prod_cost_no'];
        $object['pc']['product_type'] = $object['data']['product_type'];
        $object['pc']['category_code'] = $object['data']['category_code'];
        $object['pc']['category_name'] = $object['data']['category_name'];
        $object['pc']['category_q_factor'] = $object['data']['category_q_factor'];
        $object['pc']['category_approver'] = $object['qf']['approver'];
        $object['pc']['q_factor_sap'] = $object['qf']['q_factor'];
        $object['pc']['min'] = $object['qf']['min_cost'];
        $object['pc']['max'] = $object['qf']['max_cost'];
        $object['pc']['existing_bom_code'] = $object['data']['existing_bom_code'];
        $object['pc']['existing_bom_name'] = $object['data']['existing_bom_name'];
        $object['pc']['product_name'] = $object['data']['product_name'];
        $object['pc']['product_qty'] = $object['data']['product_qty'];
        $object['pc']['product_uom'] = $object['data']['product_uom'];
        $object['pc']['product_selling_price'] = $object['data']['product_selling_price'];
		$object['pc']['status'] = $object['data']['status'];
		$object['pc']['status_head'] = $object['data']['status_head'];
		$object['pc']['status_cat_approver'] = $object['data']['status_cat_approver'];
		$object['pc']['status_cost_control'] = $object['data']['status_cost_control'];
		$object['pc']['reject_reason'] = $object['data']['reject_reason'];
        $object['pc']['posting_date'] = $object['data']['posting_date'];
        $object['pc']['user_input'] = $object['data']['id_user_input'];
		$object['pc']['plant'] = $this->session->userdata['ADMIN']['plant'].' - '.$this->session->userdata['ADMIN']['plant_name'];
		$object['pc']['user_login'] = $this->session->userdata['ADMIN']['admin_id'];
		$object['pc']['username_login'] = $this->session->userdata['ADMIN']['admin_username'];
		$object['pc']['username_dept'] = $object['dept']['dept'];

		echo json_encode($object);
	}

	public function setApprovalCondition(){
		$id = $this->input->post('id');
		$object['data'] = $this->pc->selectProdCostHeader($id);
		$object['dept'] = $this->pc->getDeptUserLogin($this->session->userdata['ADMIN']['admin_id']);

		$isUser = 0;
		$btn = '';
		if ($this->auth->is_head_dept()) {
			foreach ($this->auth->is_head_dept()['users'] as $user) {
				if ($user['admin_id'] == $object['data']['id_user_input']) {
					$isUser = $user['admin_id'];
					break;
				};
			}
		}                                                                              

		if ($object['data']['status'] == 1 && $this->auth->is_have_perm('auth_approve') && $object['data']['id_user_input'] == $this->session->userdata['ADMIN']['admin_id']) {
			$btn .= '<button type="button" class="btn btn-primary mr-1" name="save" id="save" onclick="addDatadb(1)">Save <i class="icon-pencil5 ml-2"></i></button>';
			$btn .= '<button type="button" class="btn btn-success" name="approve" id="approve" onclick="addDatadb(2)" >Approve <i class="icon-paperplane ml-2" ></input></i>';
		} elseif($object['data']['status'] == 2 && $this->auth->is_have_perm('auth_approve') && $object['data']['status_head'] !== 0 && $this->auth->is_head_dept()['head_dept'] == $this->session->userdata['ADMIN']['admin_id'] && $isUser !== 0) {
			if($object['data']['status_head'] == 1) {
				$btn .= '<button type="button" class="btn btn-danger mr-1" name="reject" id="reject" data-toggle="modal" data-target="#exampleModal" data-backdrop="static">Reject<i class="icon-paperplane ml-2"></i></button>';	
			}
			if($object['data']['status'] != 2 || $object['data']['status_head'] != 2) {
				$btn .= '<button type="button" class="btn btn-success" name="approve" id="approve" onclick="addDatadb(3)" >Approve <i class="icon-paperplane ml-2" ></input></i>';
			}
			if($object['data']['product_type'] == 2 && $object['data']['status'] == 2 && $object['data']['status_head'] == 2 && $this->auth->is_head_dept()['head_dept'] == $this->session->userdata['ADMIN']['admin_id'] && strtolower($object['data']['category_approver']) == strtolower($this->session->userdata['ADMIN']['admin_username']) && $object['data']['status_cat_approver'] == 1) {
				$btn .= '<button type="button" class="btn btn-danger mr-1" name="reject" id="reject" data-toggle="modal" data-target="#exampleModal" data-backdrop="static">Reject<i class="icon-paperplane ml-2"></i></button>';	
				$btn .= '<button type="button" class="btn btn-success" name="approve" id="approve" onclick="addDatadb(4)" >Approve <i class="icon-paperplane ml-2" ></input></i>';
			}
			if($object['data']['product_type'] == 2 && $object['data']['status'] == 2 && $object['data']['status_head'] == 2 && $object['data']['status_cat_approver'] == 2 && $this->auth->is_head_dept()['head_dept'] == $this->session->userdata['ADMIN']['admin_id'] && strtolower($object['dept']['dept']) == 'cost control') {
				$btn .= '<button type="button" class="btn btn-danger mr-1" name="reject" id="reject" data-toggle="modal" data-target="#exampleModal" data-backdrop="static">Reject<i class="icon-paperplane ml-2"></i></button>';	
				$btn .= '<button type="button" class="btn btn-success" name="approve" id="approve" onclick="addDatadb(5)" >Approve <i class="icon-paperplane ml-2" ></input></i>';
			}
		} elseif($object['data']['product_type'] == 2 && $object['data']['status'] == 2 && $object['data']['status_head'] == 2 && strtolower($object['data']['category_approver']) == strtolower($this->session->userdata['ADMIN']['admin_username']) && $object['data']['status_cat_approver'] !== 0) {
			if($object['data']['status_cat_approver'] == 1) {
				$btn .= '<button type="button" class="btn btn-danger mr-1" name="reject" id="reject" data-toggle="modal" data-target="#exampleModal" data-backdrop="static">Reject<i class="icon-paperplane ml-2"></i></button>';	
			}
			if($object['data']['status'] != 2 || $object['data']['status_head'] != 2 || $object['data']['status_cat_approver'] != 2) {
				$btn .= '<button type="button" class="btn btn-success" name="approve" id="approve" onclick="addDatadb(4)" >Approve <i class="icon-paperplane ml-2" ></input></i>';
			}
		} elseif($object['data']['product_type'] == 2 && $object['data']['status'] == 2 && $object['data']['status_head'] == 2 && $object['data']['status_cat_approver'] == 2 && strtolower($object['dept']['dept']) == 'cost control' && $object['data']['status_cost_control'] !== 0) {
			if($object['data']['status_cost_control'] == 1) {
				$btn .= '<button type="button" class="btn btn-danger mr-1" name="reject" id="reject" data-toggle="modal" data-target="#exampleModal" data-backdrop="static">Reject<i class="icon-paperplane ml-2"></i></button>';	
			}
			if($object['data']['status'] != 2 || $object['data']['status_head'] != 2 || $object['data']['status_cat_approver'] != 2 || $object['data']['status_cost_control'] != 2) {
				$btn .= '<button type="button" class="btn btn-success" name="approve" id="approve" onclick="addDatadb(5)" >Approve <i class="icon-paperplane ml-2" ></input></i>';
			}
		}

		echo $btn;
	}

	function reject(){	
		$reject['id_prod_cost_header'] = $this->input->post('id');
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

		if($this->pc->reject($reject)){
			return $this->session->set_flashdata('failed', "Product Costing Rejected");
		} else {
			return $this->session->set_flashdata('failed', "Product Costing Gagal di Reject");
		}
	}

	public function copy(){
		$id = $this->uri->segment(4);
		$object['categories'] = $this->pc->getCategory();
		$object['data'] = $this->pc->selectProdCostHeader($id);
		$object['qf'] = $this->pc->getDataForQFactorFormula($object['data']['category_code']);
		$object['dept'] = $this->pc->getDeptUserLogin($this->session->userdata['ADMIN']['admin_id']);
		
        $object['pc']['id_prod_cost_header'] = $object['data']['id_prod_cost_header'];
        $object['pc']['prod_cost_no'] = $object['data']['prod_cost_no'];
        $object['pc']['product_type'] = $object['data']['product_type'];
        $object['pc']['category_code'] = $object['data']['category_code'];
        $object['pc']['category_name'] = $object['data']['category_name'];
        $object['pc']['category_q_factor'] = $object['data']['category_q_factor'];
        $object['pc']['category_approver'] = $object['qf']['approver'];
        $object['pc']['q_factor_sap'] = $object['qf']['q_factor'];
        $object['pc']['min'] = $object['qf']['min_cost'];
        $object['pc']['max'] = $object['qf']['max_cost'];
        $object['pc']['existing_bom_code'] = $object['data']['existing_bom_code'];
        $object['pc']['existing_bom_name'] = $object['data']['existing_bom_name'];
        $object['pc']['product_name'] = $object['data']['product_name'];
        $object['pc']['product_qty'] = $object['data']['product_qty'];
        $object['pc']['product_uom'] = $object['data']['product_uom'];
		$object['pc']['product_selling_price'] = $object['data']['product_selling_price'];
		$object['pc']['product_percentage'] = number_format($object['data']['product_percentage'], 2).' %'; //
		$object['pc']['status'] = $object['data']['status'];
		$object['pc']['status_head'] = $object['data']['status_head'];
		$object['pc']['status_cat_approver'] = $object['data']['status_cat_approver'];
		$object['pc']['status_cost_control'] = $object['data']['status_cost_control'];
		$object['pc']['reject_reason'] = $object['data']['reject_reason'];
        $object['pc']['posting_date'] = $object['data']['posting_date'];
        $object['pc']['user_input'] = $object['data']['id_user_input'];
		$object['pc']['plant'] = $this->session->userdata['ADMIN']['plant'].' - '.$this->session->userdata['ADMIN']['plant_name'];
		$object['pc']['user_login'] = $this->session->userdata['ADMIN']['admin_id'];
		$object['pc']['username_login'] = $this->session->userdata['ADMIN']['admin_username'];
		$object['pc']['username_dept'] = $object['dept']['dept'];
		
        $this->load->view('transaksi1/produksi/product_costing/copy_view', $object);
	}

	public function duplicateData(){
		$header = $this->pc->selectProdCostheader($this->input->post('id'));
		$details = $this->pc->selectDataDetail($this->input->post('id'));

		$product_cost_header['id_prod_cost_plant'] = $this->pc->selectIDPlant($this->session->userdata['ADMIN']['plant'],$header['posting_date']);
		$product_cost_header['category_code'] = $header['category_code'];
		$product_cost_header['category_name'] = $header['category_name'];
		$product_cost_header['category_q_factor'] = $header['category_q_factor'];
		$product_cost_header['category_min'] = $header['category_min'];
		$product_cost_header['category_max'] = $header['category_max'];
		$product_cost_header['category_approver'] = $header['category_approver'];
		$product_cost_header['existing_bom_code'] = $header['existing_bom_code'];
		$product_cost_header['existing_bom_name'] = $header['existing_bom_name'];
		$product_cost_header['product_name'] = $header['product_name'];
		$product_cost_header['product_qty'] = $header['product_qty'];
		$product_cost_header['product_uom'] = $header['product_uom'];
		$product_cost_header['product_selling_price'] = $header['product_selling_price'];
		$product_cost_header['product_q_factor'] = $header['product_q_factor'];
		$product_cost_header['product_percentage'] = $header['product_percentage'];
		$product_cost_header['product_result'] = $header['product_result'];
		$product_cost_header['product_result_div_product_qty'] = $header['product_result_div_product_qty'];
		$product_cost_header['product_type'] = $header['product_type'];
		$product_cost_header['posting_date'] = $header['posting_date'];
		$product_cost_header['status'] = 1;
		$product_cost_header['status_head'] = 1;
		$product_cost_header['status_cat_approver'] = 1;
		$product_cost_header['status_cost_control'] = 1;
		$product_cost_header['created_date'] = date('Y-m-d H:i:s');
		$product_cost_header['lastmodified'] = date('Y-m-d H:i:s');
		$product_cost_header['id_user_input'] = $this->session->userdata['ADMIN']['admin_id'];
		$product_cost_header['id_user_approved'] = 0;
		$product_cost_header['id_cost_control'] = 0;
		$product_cost_header['id_head_dept'] = 0;
		
		if($id_product_cost_header = $this->pc->insertHeaderProdCost($product_cost_header)) {
			$product_cost_header['prod_cost_no'] = $header['product_type'] == 1 ? 'WP'.date('Y').sprintf("%06s", $id_product_cost_header) : 'FG'.date('Y').sprintf("%06s", $id_product_cost_header);
			if ($updateNoProdCost = $this->pc->updateNoProdCost($product_cost_header['prod_cost_no'],$id_product_cost_header)) {
				$input_detail_success = FALSE;
				foreach ($details as $key => $detail) {
					$product_cost_detail['id_prod_cost_header'] = $id_product_cost_header;
					$product_cost_detail['id_prod_cost_h_detail'] = ((int)$key+1);
					$product_cost_detail['material_no'] = $detail['material_no'];
					$product_cost_detail['material_desc'] = $detail['material_desc'];
					$product_cost_detail['item_type'] = $detail['item_type'];
					$product_cost_detail['item_qty'] = $detail['item_qty'];
					$product_cost_detail['item_uom'] = $detail['item_uom'];
					$product_cost_detail['item_cost'] = $detail['item_cost'];
					if($this->pc->insertDetailProdCost($product_cost_detail) ){
						$input_detail_success = TRUE;
					}
				}
			}
		}
		
        if($input_detail_success){
            return $this->session->set_flashdata('success', "Product Costing Telah Terduplikat");
        }else{
            return $this->session->set_flashdata('failed', "Product Costing Gagal Terduplikat");
        } 
	}

	public function printXls($id){
		$kd_plant = $this->session->userdata['ADMIN']['plant'];
		$plant_name = $this->session->userdata['ADMIN']['plant_name'];

		$object['data'] = $this->pc->selectProdCostHeader($id);

		$object['ing'] = $this->pc->selectDataDetail($id,1);
		$object['pack'] = $this->pc->selectDataDetail($id,2);

        $excel = new PHPExcel();

        //set config for column width
        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(70);
        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);

        // set config for title header file
        $excel->getActiveSheet()->getStyle('B2')->getAlignment()
              ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('B3')->getAlignment()
              ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excel->getActiveSheet()->getStyle('B2')->getFont()->setBold(true);
        $excel->getActiveSheet()->getStyle('B3')->getFont()->setBold(true);

        $excel->getActiveSheet()->mergeCells('B2:H2');
        $excel->setActiveSheetIndex(0)->setCellValue('B2', 'Product Costing'); 
		
		// set config for title header
        $excel->getActiveSheet()->getStyle('B5:B16')->getFont()->setBold(true);

        $excel->setActiveSheetIndex(0)->setCellValue('B5', "No. Document"); 
        $excel->setActiveSheetIndex(0)->setCellValue('B6', "Document"); 
        $excel->setActiveSheetIndex(0)->setCellValue('B7', "Costing Type"); 
        $excel->setActiveSheetIndex(0)->setCellValue('B8', "Category"); 
        $excel->setActiveSheetIndex(0)->setCellValue('B9', "Existing Bom"); 
		$excel->setActiveSheetIndex(0)->setCellValue('B10', "Name"); 
		$excel->setActiveSheetIndex(0)->setCellValue('B11', "Qty Produksi"); 
        $excel->setActiveSheetIndex(0)->setCellValue('B12', "UOM"); 
        $excel->setActiveSheetIndex(0)->setCellValue('B13', "Posting Date"); 
		if ($object['data']['product_type'] == 2) {
			$excel->setActiveSheetIndex(0)->setCellValue('B15', "Selling Price"); 
		}
		
		// set config for value header
		$excel->setActiveSheetIndex(0)->setCellValue('C5', $object['data']['prod_cost_no']); 
		$excel->setActiveSheetIndex(0)->setCellValue('C6', $object['data']['existing_bom_code'] ? 'Existing' : 'New'); 
		$excel->setActiveSheetIndex(0)->setCellValue('C7', $object['data']['product_type'] == 1 ? 'WP' : 'Selling'); 
        $excel->setActiveSheetIndex(0)->setCellValue('C8', $object['data']['category_name']); 
        $excel->setActiveSheetIndex(0)->setCellValue('C9', $object['data']['existing_bom_code'] ? $object['data']['existing_bom_code'].' - '.$object['data']['existing_bom_name'] : '-'); 
		$excel->setActiveSheetIndex(0)->setCellValue('C10', $object['data']['product_name']); 
		$excel->setActiveSheetIndex(0)->setCellValue('C11', number_format($object['data']['product_qty'],2)); 
        $excel->setActiveSheetIndex(0)->setCellValue('C12', $object['data']['product_uom']); 
        $excel->setActiveSheetIndex(0)->setCellValue('C13', date('d-m-Y', strtotime($object['data']['posting_date']))); 
		if ($object['data']['product_type'] == 2) { 
			$excel->setActiveSheetIndex(0)->setCellValue('C15', number_format($object['data']['product_selling_price'],2)); 
		}

		//style of border
        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => '00000000'),
                ),
            ),
        );
        
        $excel->getActiveSheet()->getStyle('B19:H19')->applyFromArray($styleArray);

        // set config for title header table 
        $excel->getActiveSheet()->getStyle('B19:H19')->getAlignment()
              ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excel->getActiveSheet()->getStyle('B19:H19')->getAlignment()
              ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $excel->getActiveSheet()->getStyle('B19:H19')->getFont()->setBold(true);

        $excel->setActiveSheetIndex(0)->setCellValue('B18', "List of Ingredients"); 
        $excel->setActiveSheetIndex(0)->setCellValue('B19', "No"); 
        $excel->setActiveSheetIndex(0)->setCellValue('C19', "Item Code"); 
		$excel->setActiveSheetIndex(0)->setCellValue('D19', "Item Desc"); 
        $excel->setActiveSheetIndex(0)->setCellValue('E19', "UOM"); 
        $excel->setActiveSheetIndex(0)->setCellValue('F19', "Unit Cost"); 
		$excel->setActiveSheetIndex(0)->setCellValue('G19', "Quantity"); 
		$excel->setActiveSheetIndex(0)->setCellValue('H19', "Total Cost"); 
        
		$numrowIng = 20;
		$totIngCost = 0;
        foreach($object['ing'] as $keyIng => $rIng){ 

			$excel->getActiveSheet()->getStyle('B'.$numrowIng)->getAlignment()
			  ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle('E'.$numrowIng)->getAlignment()
			  ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			
            // applying border style
            $excel->getActiveSheet()->getStyle('B'.$numrowIng.':H'.$numrowIng)->applyFromArray($styleArray);

            $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowIng, ($keyIng+1));
            $excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowIng, $rIng['material_no']);
            $excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowIng, $rIng['material_desc']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowIng, $rIng['item_uom']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowIng, number_format($rIng['item_cost'],2));
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowIng, number_format($rIng['item_qty'],2));
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowIng, number_format((float)($rIng['item_qty'] * $rIng['item_cost']),2));

			$totIngCost += (float)($rIng['item_qty'] * $rIng['item_cost']);
            $numrowIng++;
		}

		$excel->getActiveSheet()->getStyle('B'.$numrowIng.':H'.$numrowIng)->applyFromArray($styleArray);

		$excel->getActiveSheet()->getStyle('B'.$numrowIng)->getAlignment()
			  ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			  
		$excel->getActiveSheet()->mergeCells('B'.$numrowIng.':G'.$numrowIng);

		$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowIng, 'Total Ingredients Cost'); 
		$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowIng, number_format($totIngCost,2));

		$numrowPackThead = $numrowIng + 2;

		$excel->getActiveSheet()->getStyle('B'.($numrowPackThead+1).':H'.($numrowPackThead+1))->applyFromArray($styleArray);

		// set config for title header table 
        $excel->getActiveSheet()->getStyle('B'.($numrowPackThead+1).':H'.($numrowPackThead+1))->getAlignment()
              ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excel->getActiveSheet()->getStyle('B'.($numrowPackThead+1).':H'.($numrowPackThead+1))->getAlignment()
              ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $excel->getActiveSheet()->getStyle('B'.($numrowPackThead+1).':H'.($numrowPackThead+1))->getFont()->setBold(true);

		$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowPackThead, "List of Packaging"); 
        $excel->setActiveSheetIndex(0)->setCellValue('B'.($numrowPackThead+1), "No"); 
        $excel->setActiveSheetIndex(0)->setCellValue('C'.($numrowPackThead+1), "Item Code"); 
		$excel->setActiveSheetIndex(0)->setCellValue('D'.($numrowPackThead+1), "Item Desc"); 
        $excel->setActiveSheetIndex(0)->setCellValue('E'.($numrowPackThead+1), "UOM"); 
        $excel->setActiveSheetIndex(0)->setCellValue('F'.($numrowPackThead+1), "Unit Cost"); 
		$excel->setActiveSheetIndex(0)->setCellValue('G'.($numrowPackThead+1), "Quantity"); 
		$excel->setActiveSheetIndex(0)->setCellValue('H'.($numrowPackThead+1), "Total Cost"); 
		
		$numrowPack = $numrowIng + 4;
		$totPackCost = 0;
		if ($object['pack']) {
			foreach($object['pack'] as $keyPack => $rPack){ 
	
				$excel->getActiveSheet()->getStyle('B'.$numrowPack)->getAlignment()
				  ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$excel->getActiveSheet()->getStyle('E'.$numrowPack)->getAlignment()
				  ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				
				// applying border style
				$excel->getActiveSheet()->getStyle('B'.$numrowPack.':H'.$numrowPack)->applyFromArray($styleArray);
	
				$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowPack, ($keyPack+1));
				$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowPack, $rPack['material_no']);
				$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowPack, $rPack['material_desc']);
				$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowPack, $rPack['item_uom']);
				$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowPack, number_format($rPack['item_cost'],2));
				$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowPack, number_format($rPack['item_qty'],2));
				$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowPack, number_format((float)($rPack['item_qty'] * $rPack['item_cost']),2));
				
				$totPackCost += (float)($rPack['item_qty'] * $rPack['item_cost']);
				$numrowPack++;
			}
		}

		$excel->getActiveSheet()->getStyle('B'.$numrowPack.':H'.$numrowPack)->applyFromArray($styleArray);

		$excel->getActiveSheet()->getStyle('B'.$numrowPack)->getAlignment()
			  ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

		$excel->getActiveSheet()->mergeCells('B'.$numrowPack.':G'.$numrowPack);

		$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrowPack, 'Total Packaging Cost'); 
		$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowPack, number_format($totPackCost,2));

		if ($object['data']['product_type'] == 2) {
			$excel->getActiveSheet()->getStyle('B'.($numrowPack+3).':B'.($numrowPack+6))->getFont()->setBold(true);

			$excel->setActiveSheetIndex(0)->setCellValue('B'.($numrowPack+3), "Q Factor"); 
			$excel->setActiveSheetIndex(0)->setCellValue('B'.($numrowPack+4), "Total Product Cost"); 
			$excel->setActiveSheetIndex(0)->setCellValue('B'.($numrowPack+5), "Total Product Cost / Qty Produksi"); 
			$excel->setActiveSheetIndex(0)->setCellValue('B'.($numrowPack+6), "Product Costing"); 
			
			$excel->setActiveSheetIndex(0)->setCellValue('C'.($numrowPack+3), number_format($object['data']['product_q_factor'],2)); 
			$excel->setActiveSheetIndex(0)->setCellValue('C'.($numrowPack+4), number_format($object['data']['product_result'],2)); 
			$excel->setActiveSheetIndex(0)->setCellValue('C'.($numrowPack+5), number_format($object['data']['product_result_div_product_qty'],2)); 
			$excel->setActiveSheetIndex(0)->setCellValue('C'.($numrowPack+6), $object['data']['product_percentage'].' %');

			if ($object['data']['product_percentage'] > $object['data']['category_max']) {
				$ket = 'Product Cost above Threshold';
				$color = 'FF0000';
			} elseif ($object['data']['product_percentage'] < $object['data']['category_min']) {
				$ket = 'Product Cost below Threshold';
				$color = 'FFFF00';
			} else {
				$ket = 'Product Cost within Threshold, Ok to continue';
				$color = '008000';
			}

			$excel->getActiveSheet()->getStyle('D'.($numrowPack+6))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
				->getStartColor()->setARGB($color);

			$excel->setActiveSheetIndex(0)->setCellValue('D'.($numrowPack+6), $ket); 																			
		}
    
        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);
        // Set orientasi kertas jadi LANDSCAPE
        $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        // Set judul file excel nya
        $excel->getActiveSheet(0)->setTitle("Product Costing");
        $excel->setActiveSheetIndex(0);
        // Proses file excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Product Costing.xlsx"'); // Set nama file excel nya
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
			$header['id_prod_cost_header'] = $idAdd;
		}

		$requestor = $this->pc->getNameNEmailById($header['id_user_input']); //get email & name by id

		if (($header['flag'] == 1 && $flag == 'edit') || (isset($header['status']) && $header['status'] == 1 && $flag == 'add')) { //status new, status edit, save  
			$to = $this->pc->getNameNEmailById($this->session->userdata['ADMIN']['admin_id']); //get email & name by id
		} elseif ((isset($header['status']) && $header['status'] == 2 && isset($header['flag']) && $header['flag'] == 2) || (isset($header['status']) && $header['status'] == 2 && $flag == 'add')) { //status edit, approve pembuat
			if (isset($header['status_head']) && $header['status_head'] == 2 && $header['product_type'] == 2) { //status edit, approve pembuat & HOD
				$to = $this->pc->getNameNEmailByUsername($header['category_approver']); //get email & name by username
			} else {
				$to = $this->pc->getNameNEmailById($requestor['hod']); //get email & name by id
			}
		} elseif ($header['product_type'] == 2 && isset($header['status_head']) && $header['status_head'] == 2 && isset($header['flag']) && $header['flag'] == 3) { //status edit, approve HOD
			if (isset($header['status_cat_approver']) && $header['status_cat_approver'] == 2) { //status edit, approve HOD & Cat Approver
				$to = $this->pc->getAllNameNEmailCostControl(); //get all email & name cost control
			} else {
				$to = $this->pc->getNameNEmailByUsername($header['category_approver']); //get email & name by username
			}
		} elseif ($header['product_type'] == 2 && isset($header['status_cat_approver']) && $header['status_cat_approver'] == 2 && isset($header['flag']) && $header['flag'] == 4) { //status edit, approve Cat Approver
			if (isset($header['status_cost_control']) && $header['status_cost_control'] == 2) { //status edit, approve Cat Approver & Cost Control
				//tdk ada notif email, approval selesai
			} else {
				$to = $this->pc->getAllNameNEmailCostControl(); //get all email & name cost control
			}
		}

		if (count($to) > 0) {
			$template['template'] = [
				'to' => count($to) > 4 ? 'Cost Control' : $to['name'],
				'from' => $requestor,
				'hod' => $this->pc->getNameNEmailById($requestor['hod']),
				'q_factor_min_max_sap' => $this->pc->getDataForQFactorFormula($header['category_code']),
				'date_appoved' => $this->pc->selectProdCostHeader(($flag == 'add' ? $idAdd : $header['id_prod_cost_header'])),
				'header' => $header,
				'detail_ing' => $this->pc->selectDataDetail(($flag == 'add' ? $idAdd : $header['id_prod_cost_header']),1),
				'detail_pack' => $this->pc->selectDataDetail(($flag == 'add' ? $idAdd : $header['id_prod_cost_header']),2)
			];

			$message = $this->load->view('transaksi1/produksi/product_costing/email_view', $template, TRUE);

			$subject = 'PENGAJUAN ITEM COSTING '.strtoupper($header['product_name']).' BY '.strtoupper($requestor['name']);

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