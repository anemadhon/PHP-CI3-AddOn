<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Disassembly extends CI_Controller{
     public function __construct(){
		parent::__construct();
		$this->load->library('auth');  
		if(!$this->auth->is_logged_in()) {
			redirect(base_url());
        }
        $this->load->library('form_validation');
        $this->load->library('l_general');
        
        // load model
		$this->load->model('transaksi1/disassembly_model', 'diss_model');
    }

    public function index(){
        $this->load->view('transaksi1/produksi/disassembly/list_view');
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
        
		$rs = $this->diss_model->getDataWoVendor_Header($date_from2, $date_to2, $status);
		$data = array();
		$back='';
		if($rs){
			foreach($rs as $key=>$val){
				$log = $val['back'];
				$po = $val['disassembly_no'];
				if ($log==0 && $po !='' && $po !='C'){
					$back = "Integrated";
				}else if ($log==1 && ($po =='' || $po =='C')){
					$back = "Not Integrated";
				}else if ($log==0 &&  $po =='C'){
					$back = "Close Document";
				}

				$nestedData = array();
				$nestedData['id_disassembly_header'] 	= $val['id_disassembly_header'];
				$nestedData['id_disassembly_plant'] 	= $val['id_disassembly_plant'];
				$nestedData['posting_date'] 			= date("d-m-Y",strtotime($val['posting_date']));
				$nestedData['disassembly_no'] 			= $val['disassembly_no'];
				$nestedData['plant'] 					= $val['plant'];
				$nestedData['plant_name'] 				= $val['plant_name'];
				$nestedData['storage_location'] 		= $val['storage_location'];
				$nestedData['storage_location_name'] 	= $val['storage_location_name'];
				$nestedData['created_date'] 			= date("d-m-Y",strtotime($val['created_date']));
				$nestedData['kode_paket'] 				= $val['kode_paket'];
				$nestedData['nama_paket'] 				= $val['nama_paket'];
				$nestedData['qty_paket'] 				= $val['qty_paket'];
				$nestedData['status'] 					= $val['status'] =='1'?'Not Approved':'Approved';
				$nestedData['id_user_input'] 			= $val['id_user_input'];
				$nestedData['id_user_approved'] 		= $val['id_user_approved'];
				$nestedData['id_user_cancel'] 			= $val['id_user_cancel'];
				$nestedData['filename'] 				= $val['filename'];
				$nestedData['lastmodified'] 			= date("d-m-Y",strtotime($val['lastmodified']));
				$nestedData['num'] 						= $val['num'];
				$nestedData['uom_paket'] 				= $val['uom_paket'];
				$nestedData['issue'] 					= $val['doc_issue'];
				$nestedData['created_by'] 				= $val['created_by'];
				$nestedData['approved_by'] 				= $val['approved_by'];
				$nestedData['qty_paket'] 				= $val['qty_paket'];
				$nestedData['back'] 					= $back;
				$data[] = $nestedData;

			}
		}
		
		$json_data = array(
			"status"		=> $status,
            "recordsTotal"    =>  10, 
            "recordsFiltered" =>  12,
            "data"            =>  $data 
        );
		
        echo json_encode($json_data);
    }
	
	public function deleteData(){
        $id_disassembly_header = $this->input->post('deleteArr');
        $deleteData = false;
        foreach($id_disassembly_header as $id){
            if($this->diss_model->disassembly_header_delete($id))
            $deleteData = true;
        }
        
        if($deleteData){
            return $this->session->set_flashdata('success', "Disassembly Berhasil dihapus");
        }else{
            return $this->session->set_flashdata('failed', "Disassembly Approved, Gagal dihapus");
        }
    }

     public function add(){
		$object['plant'] = $this->session->userdata['ADMIN']['plant']; 
        $object['plant_name'] = $this->session->userdata['ADMIN']['plant_name'];
		$data['wo_codes'] = $this->diss_model->sap_disassembly_headers_select_by_item();
		
        $object['wo_code']['-'] = '';
        if($data['wo_codes'] != FALSE){
            foreach($data['wo_codes'] as $wo_no){
				$object['wo_code'][$wo_no['Code']] = $wo_no['Code'].' - '.$wo_no['ItemName'];
            }
		}

        $this->load->view('transaksi1/produksi/disassembly/add_view',$object);
     }
	 
	 public function disassembly_header_uom(){
		$material_no = $this->input->post('material_no');
		$data = $this->diss_model->sap_disassembly_headers_select_by_item($material_no);		
		if(count($data)>0){
			$json_data=array(
				"data" => $data
			);
			echo json_encode($json_data);
		}
     }

    public function edit(){
        $id_disassembly_header = $this->uri->segment(4);
		$object['data'] = $this->diss_model->disassembly_header_select($id_disassembly_header);
		$u_Locked = $this->diss_model->sap_disassembly_headers_select_by_item($object['data']['kode_paket']);

		
        $object['disassembly_header']['id_disassembly_header'] = $object['data']['id_disassembly_header'];
        $object['disassembly_header']['kode_paket'] = $object['data']['kode_paket'];
        $object['disassembly_header']['nama_paket'] = $object['data']['nama_paket'];
        $object['disassembly_header']['qty_paket'] = $object['data']['qty_paket'];
        $object['disassembly_header']['uom_paket'] = $object['data']['uom_paket'];
        $object['disassembly_header']['posting_date'] = $object['data']['posting_date'];
		$object['disassembly_header']['status'] = $object['data']['status'];
		$object['disassembly_header']['U_Locked'] = $u_Locked[0]['U_Locked'];
		$object['disassembly_header']['on_hand'] = $u_Locked[0]['OnHand'];
		$object['disassembly_header']['plant'] = $this->session->userdata['ADMIN']['plant'].' - '.$this->session->userdata['ADMIN']['plant_name'];
		
        $this->load->view('transaksi1/produksi/disassembly/edit_view', $object);
    }
	
	public function showDetailEdit(){
        $id_wo_header = $this->input->post('id');
        $kode_paket = $this->input->post('kodepaket');
        $qty_paket = $this->input->post('qtypaket');
		$rs = $this->diss_model->disassembly_details_select($id_wo_header,$kode_paket,$qty_paket);
		$object['data'] = $this->diss_model->disassembly_header_select($id_wo_header);
		$disabled = $object['data']['status'] == 2 ? 'disabled' : '';
		
		$dt = array();
        $i = 1;
        if($rs){
            foreach($rs as $data){
				$queryUOM = $this->diss_model->disassembly_detail_uom($data['material_no']);
				$inWhs = $this->diss_model->disassembly_detail_onhand($data['material_no']);
				if(count($queryUOM)>0){
					$uom = $queryUOM[0]['UNIT'];
				} else {
					$uom = '';
				}
				$ucaneditqty = $this->diss_model->disassembly_detail_ucaneditqty($kode_paket,$data['material_no']);
				$getlocked = $this->diss_model->sap_disassembly_select_locked($kode_paket);
				$getucaneditqty=$data['qty'];
				
				$querySAP2 = $this->diss_model->disassembly_detail_itemcodebom($kode_paket,$data['material_no']);
				$select = '<select class="form-control form-control-select2" data-live-search="true" name="descmat" id="descmat" '.$disabled.'>
								<option value="'.$data['material_no'].'" rel="'.$ucaneditqty[0]['CanEditQty'].'" matqty="'.$data['qty'].'"  onHand="'.$data['OnHand'].'" minStock = "'.$data['MinStock'].'" uOm="'.$data['uom'].'" matdesc="'.$data['material_desc'].'">'.$data['material_desc'].'</option>';
								if($querySAP2){
									foreach($querySAP2 as $_querySAP2){
										$getonhandAlt = $this->diss_model->disassembly_detail_onhand($_querySAP2['U_SubsCode']);
										$onhandAlt = '';
										$minstockAlt = '';
										if($getonhandAlt != false){
											$onhandAlt = (float)$getonhandAlt[0]['OnHand'];
											$minstockAlt = (float)$getonhandAlt[0]['MinStock'];
										}
										if($_querySAP2['U_ItemCodeBOM'] = $data['material_no']){
											$select .= '<option value="'.$_querySAP2['U_SubsCode'].'" 
											rel="'.$ucaneditqty[0]['CanEditQty'].'" onHand="'.$onhandAlt.'" minStock = "'.$minstockAlt.'" uOm="'.$_querySAP2['U_SubsUOM'].'"
											matqty="'.$_querySAP2['U_SubsQty'] * (float)$qty_paket.'" matdesc="'.$_querySAP2['NAME'].'">'.$_querySAP2['NAME'].'</option>';
										}
									}
								}
				$select .= '</select>';
				$descolumn = '';
				if($querySAP2){
					foreach($querySAP2 as $_querySAP2){
						if($_querySAP2['U_ItemCodeBOM'] = $data['material_no']){
							$descolumn = $select;
						}else{
							$descolumn = $data['material_no'];
						}
					}
				}else{
					$descolumn = $select;
				}
				
				$nestedData=array();
				$nestedData['no'] = $i;
				$nestedData['id_disassembly_detail'] = $data['id_disassembly_detail'];
				$nestedData['id_disassembly_header'] = $data['id_disassembly_header'];
				$nestedData['material_no'] = $data['material_no'];
				$nestedData['material_desc'] = $data['material_desc'];
				$nestedData['qty'] = number_format($getucaneditqty,4,'.','');
				$nestedData['uom'] = $uom;
				$nestedData['OnHand'] = $inWhs[0]['OnHand']!='.000000' ? number_format($inWhs[0]['OnHand'],4,'.','') : '0.0000';
				$nestedData['MinStock'] = $data['MinStock']; 
				$nestedData['OpenQty'] = $data['OpenQty'];
				$nestedData['descolumn'] = $descolumn;
				$dt[] = $nestedData;
				$i++;
			}
        }
        $json_data = array(
			"data" => $dt
		);
		echo json_encode($json_data);

	}
	
	public function addItemRow(){
		$rs = $this->diss_model->sap_item();
		echo json_encode($rs);
	}

	function getdataDetailMaterialSelect(){
        $itemSelect = $this->input->post('MATNR');
        
		$dataMatrialSelect = $this->diss_model->sap_item($itemSelect);
		
		$dt = array();
		foreach ($dataMatrialSelect as $data) {
			$getonhand = $this->diss_model->disassembly_detail_onhand($data['MATNR']);
			$onhand = '';
			$minstock = '';
			if($getonhand != false){
				$onhand = (float)$getonhand[0]['OnHand'];
				$minstock = (float)$getonhand[0]['MinStock'];
			}
			$getopenqty = $this->diss_model->disassembly_detail_openqty($data['MATNR']);
			$openqty = '';
			if($getopenqty != false){
				$openqty = (float)$getopenqty[0]['OpenQty'];
			}
			$uom = '';
			if($data['UNIT'] != '' || !empty($data['UNIT'])){
				$uom = $data['UNIT'];
			}

			$nestedData=array();
			$nestedData['MATNR'] = $data['MATNR'];
			$nestedData['MAKTX'] = $data['MAKTX'];
			$nestedData['qty'] = 0;
			$nestedData['UNIT'] = $uom;
			$nestedData['OnHand'] = $onhand; 
			$nestedData['MinStock'] = $minstock; 
			$nestedData['OpenQty'] = $openqty;
			$dt[] = $nestedData;
		}
		
		echo json_encode($dt);
        
	}
	
	public function showDetailInput(){
		$kode_paket = $this->input->post('kode_paket');
		$qty_header = $this->input->post('Qty');
        $rs = $this->diss_model->disassembly_details_input_select($kode_paket);
		
		$dt = array();
        $i = 1;
        if($rs){
            foreach($rs as $data){
		
				$querySAP = $this->diss_model->disassembly_detail_valid($data['material_no']);
				$validFor = '';
				$decreasAc = '';
				if($querySAP != false){
					$validFor = $querySAP[0]['validFor'];
					$decreasAc = $querySAP[0]['DecreasAc'];
				}
				
				$qty_paket = $data['quantity'];
				
				$getonhand = $this->diss_model->disassembly_detail_onhand($data['material_no']);
				$onhand = '';
				$minstock = '';
				if($getonhand != false){
					$onhand = (float)$getonhand[0]['OnHand'];
					$minstock = (float)$getonhand[0]['MinStock'];
				}
				
				$getopenqty = $this->diss_model->disassembly_detail_openqty($data['material_no']);
				$openqty = '';
				if($getopenqty != false){
					$openqty = (float)$getopenqty[0]['OpenQty'];
				}
				
				$ucaneditqty = $this->diss_model->disassembly_detail_ucaneditqty($kode_paket,$data['material_no']);
				$getlocked = $this->diss_model->sap_disassembly_select_locked($kode_paket);

				$getucaneditqty=$data['quantity'] * (float)$qty_header;

				$queryUOM = $this->diss_model->disassembly_detail_uom($data['material_no']);
				if(count($queryUOM)>0){
					$uom = $queryUOM[0]['UNIT'];
				} else {
					$uom = '';
				}
				
				$querySAP2 = $this->diss_model->disassembly_detail_itemcodebom($kode_paket,$data['material_no']);
				
				$select = '<select class="form-control form-control-select2" data-live-search="true" name="descmat" id="descmat">
								<option value="'.$data['material_no'].'" rel="'.$ucaneditqty[0]['CanEditQty'].'" onHand="'.$onhand.'" minStock = "'.$minstock.'" uOm="'.$uom.'" matqty="'.$data['quantity'] * (float)$qty_header.'" matdesc="'.$data['material_desc'].'">'.$data['material_desc'].'</option>';
								if($querySAP2){
									foreach($querySAP2 as $_querySAP2){
										$getonhandAlt = $this->diss_model->disassembly_detail_onhand($_querySAP2['U_SubsCode']);
										$onhandAlt = '';
										$minstockAlt = '';
										if($getonhandAlt != false){
											$onhandAlt = (float)$getonhandAlt[0]['OnHand'];
											$minstockAlt = (float)$getonhandAlt[0]['MinStock'];
										}
										if($_querySAP2['U_ItemCodeBOM'] = $data['material_no']){
											$select .= '<option value="'.$_querySAP2['U_SubsCode'].'" 
											rel="'.$ucaneditqty[0]['CanEditQty'].'"  onHand="'.$onhandAlt.'" minStock = "'.$minstockAlt.'" uOm="'.$_querySAP2['U_SubsUOM'].'"
											matqty="'.$_querySAP2['U_SubsQty'] * (float)$qty_header.'" matdesc="'.$_querySAP2['NAME'].'">'.$_querySAP2['NAME'].'</option>';
										}
									}
								}
				$select .= '</select>';
				
				$descolumn = '';
				if($querySAP2){
					foreach($querySAP2 as $_querySAP2){
						if($_querySAP2['U_ItemCodeBOM'] = $data['material_no']){
							$descolumn = $select;
						}else{
							$descolumn = $data['material_no'];
						}
					}
				}else{
					$descolumn = $select;
				}
				
				$openitem = $this->diss_model->disassembly_detail_item();
				$qtyopen = '';
				foreach($openitem as $_openqty){
					if($_openqty['U_ItemCodeBOM'] = $data['material_no']){
						$qtyopen = $select;
					}else{
						$qtyopen = $data['material_no'];
					}
				}

				$nestedData=array();
				$nestedData['no'] = $i;
				$nestedData['id_mpaket_header'] = $data['id_mpaket_header'];
				$nestedData['id_mpaket_h_detail'] = $data['id_mpaket_h_detail'];
				$nestedData['material_no'] = $data['material_no'];
				$nestedData['material_desc'] = $data['material_desc'];
				$nestedData['qty'] = number_format($getucaneditqty,4,'.','');
				$nestedData['uom'] = $uom;
				$nestedData['OnHand'] = number_format($onhand,4,'.',''); 
				$nestedData['MinStock'] = $minstock; 
				$nestedData['OpenQty'] = $openqty;
				$nestedData['validFor'] = $validFor;
				$nestedData['decreasAc'] = $decreasAc;
				$nestedData['descolumn'] = $descolumn;
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
		$disassembly_header['plant'] = $this->session->userdata['ADMIN']['plant'];
		$disassembly_header['storage_location'] = $this->session->userdata['ADMIN']['storage_location'];
		$disassembly_header['posting_date'] = $this->l_general->str_to_date($this->input->post('postDate'));
		$disassembly_header['id_disassembly_plant'] = $this->diss_model->id_produksi_plant_new_select($this->session->userdata['ADMIN']['plant'],$this->input->post('postDate'));
		$disassembly_header['disassembly_no'] = '';
		$disassembly_header['status'] = $this->input->post('approve')? $this->input->post('approve') : '1';
		$disassembly_header['kode_paket'] = $this->input->post('woNumber');
		$disassembly_header['nama_paket'] = $this->input->post('woDesc');
		$disassembly_header['qty_paket'] = $this->input->post('qtyProd');
		$disassembly_header['uom_paket'] = $this->input->post('uomProd');
		$disassembly_header['id_user_input'] = $this->session->userdata['ADMIN']['admin_id'];
		$disassembly_header['id_user_approved'] = $this->input->post('approve')? $this->session->userdata['ADMIN']['admin_id'] : 0 ;
		$disassembly_header['created_date']=date('Y-m-d');
		$disassembly_header['back']=1;
		
		/*Batch Number */
		$date=date('ym');
		$batch = $this->diss_model->disassembly_header_batch($disassembly_header['kode_paket'],$this->session->userdata['ADMIN']['plant']);
		if(!empty($batch)){
			$date=date('ym');
			$count1=count($batch) + 1;
			if ($count1 > 9 && $count1 < 100){
				$dg="0";
			}else {
				$dg="00";
			}
			$num=$disassembly_header['kode_paket'].$date.$dg.$count1;
			$disassembly_header['num'] = $num;
		}else{
			$disassembly_header['num'] = '';
		}
		
		$count = count($this->input->post('matrialNo'));
		if($id_disassembly_header = $this->diss_model->disassembly_header_insert($disassembly_header)) {
			$input_detail_success = FALSE;
			for($i = 0; $i < $count; $i++){
				$disassembly_detail['id_disassembly_header'] = $id_disassembly_header;
				$disassembly_detail['qty'] = $this->input->post('qty')[$i];
				$disassembly_detail['id_disassembly_h_detail'] = $i+1;
				$disassembly_detail['material_no'] = $this->input->post('matrialNo')[$i];
				$disassembly_detail['num'] = '';
				$disassembly_detail['material_desc'] = trim($this->input->post('matrialDesc')[$i]);
				$disassembly_detail['uom'] = $this->input->post('uom')[$i];
				$disassembly_detail['qc'] = '';
				$disassembly_detail['OnHand'] = $this->input->post('onHand')[$i];
				$disassembly_detail['MinStock'] = $this->input->post('minStock')[$i];
				$disassembly_detail['OpenQty'] = $this->input->post('outStandTot')[$i];
				if($this->diss_model->disassembly_detail_insert($disassembly_detail) ){
					$input_detail_success = TRUE;
				}
			}
		}
        if($input_detail_success){
            return $this->session->set_flashdata('success', "Disassembly Telah Terbentuk");
        }else{
            return $this->session->set_flashdata('failed', "Disassembly Gagal Terbentuk");
        } 
	}
	
	public function addUpdateData(){
		$id_disassembly_header = $this->input->post('id_wo_header');
		$kode_paket 		=$this->input->post('kd_paket');
		$approve 			=$this->input->post('approve');
		$disassembly_header['id_disassembly_header'] = $id_disassembly_header;
		$disassembly_header['status'] = $approve ? $approve: "1";
		$disassembly_header['id_user_approved'] = $approve? $this->session->userdata['ADMIN']['admin_id'] : 0 ;
		$max = count($this->input->post('matrialNo'));

		$disassembly_header_update = $this->diss_model->update_disassembly_header($disassembly_header);
		$succes_update = false;
		if($disassembly_header_update){
			$this->diss_model->disassembly_details_delete($id_disassembly_header);
			for($i=0; $i < $max; $i++){
				$disassembly_detail['id_disassembly_header'] = $id_disassembly_header;
				$disassembly_detail['qty'] = $this->input->post('qty')[$i];
				$disassembly_detail['id_disassembly_h_detail'] = $i+1;
				$disassembly_detail['material_no'] = $this->input->post('matrialNo')[$i];
				$disassembly_detail['num'] = '';
				$disassembly_detail['material_desc'] = $this->input->post('matrialDesc')[$i];
				$disassembly_detail['uom'] = $this->input->post('uom')[$i];
				$disassembly_detail['qc'] = '';
				$disassembly_detail['OnHand'] = $this->input->post('onHand')[$i];
				$disassembly_detail['MinStock'] = $this->input->post('minStock')[$i];
				$disassembly_detail['OpenQty'] = $this->input->post('outStandTot')[$i];
							
				if($this->diss_model->disassembly_detail_insert($disassembly_detail) ){
					$succes_update = TRUE;
				}
			}
		}
		if($succes_update){
            return $this->session->set_flashdata('success', "Disassembly Telah Berhasil Terupdate");
        }else{
            return $this->session->set_flashdata('failed', "Disassembly Gagal Terupdate");
        }
	} 
}
?>