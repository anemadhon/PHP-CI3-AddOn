<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require('./application/third_party/PHPExcel/PHPExcel.php');

class Stock extends CI_Controller {
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
        $this->load->model('transaksi1/stock_model', 'st_model');
    }

    public function index(){
        $so_date['so_date'] = $this->st_model->getSODate();
        $this->load->view('transaksi1/stock_outlet/stock/list_view', $so_date);
    }

    public function showAllData(){
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
			$date_to2 = $year.'-'.$day.'-'.$month.' 23:59:59';
        }else{
            $date_to2='';
        }

        $rs = $this->st_model->opname_headers($date_from2, $date_to2, $status);
		$data = array();
		$status_string='';

        foreach($rs as $key=>$val){
			if($val['status'] =='1'){
				$status_string= 'Not Approved';
			}else if($val['status'] =='2'){
				$status_string= 'Approved';
			}else{
				$status_string= 'Cancel';
            }
            
            if($val['am_approved'] =='1'){
				$am_status= 'Rejected';
			}else if($val['am_approved'] =='2'){
				$am_status= 'Approved';
			}else{
				$am_status= '';
            }
            
            if($val['rm_approved'] =='1'){
				$rm_status= 'Rejected';
			}else if($val['rm_approved'] =='2'){
				$rm_status= 'Approved';
			}else{
				$rm_status= '';
			}
            
            if($val['freeze'] =='N'){
				$freeze= 'No';
			}else{
				$freeze= 'Yes';
			}

            $nestedData = array();
            $nestedData['id_opname_header'] = $val['id_opname_header'];
            $nestedData['opname_no'] = $val['opname_no'];
            $nestedData['created_date'] = date("d-m-Y",strtotime($val['created_date']));
            $nestedData['posting_date'] = date("d-m-Y",strtotime($val['posting_date']));
            $nestedData['request_reason'] = $val['request_reason'];
            $nestedData['status'] = $status_string; 
            $nestedData['am_status'] = $am_status; 
            $nestedData['rm_status'] = $rm_status; 
            $nestedData['created_by'] = $val['user_input'];
            $nestedData['approved_by'] = $val['user_approved'];
            $nestedData['last_modified'] = date("d-m-Y",strtotime($val['lastmodified']));
            $nestedData['to_plant'] = $val['to_plant'];
            $nestedData['back'] = ($val['back'] =='1')?'Not Integrated':'Integrated';
            $nestedData['status_real'] = $val['status'];
            $nestedData['freeze'] = $freeze;
            $nestedData['admin_approved_date'] = $val['admin_approved_date'] ? date("d-m-Y H:i:s",strtotime($val['admin_approved_date'])) : '';
            $nestedData['am_approved_date'] = $val['am_approved'] =='1' ? ($val['am_rejected_date'] ? date("d-m-Y H:i:s",strtotime($val['am_rejected_date'])) : '') : ($val['am_approved_date'] ? date("d-m-Y H:i:s",strtotime($val['am_approved_date'])) : '');
            $nestedData['rm_approved_date'] = $val['rm_approved'] =='1' ? ($val['rm_rejected_date'] ? date("d-m-Y H:i:s",strtotime($val['rm_rejected_date'])) : '') : ($val['rm_approved_date'] ? date("d-m-Y H:i:s",strtotime($val['rm_approved_date'])) : '');
            $data[] = $nestedData;

        }

        $json_data = array(
            "recordsTotal"    => 10, 
            "recordsFiltered" => 12,
            "data"            => $data 
        );
        echo json_encode($json_data);
     }

    public function add(){
		$object['head'] = $this->st_model->headTemplate();
        $object['plant'] = $this->session->userdata['ADMIN']['plant'].' - '.$this->session->userdata['ADMIN']['plant_name'];
        $object['so_date'] = $this->st_model->getSODate();
    
        $this->load->view('transaksi1/stock_outlet/stock/add_view', $object);
    }

    public function addData(){
        $plant = $this->session->userdata['ADMIN']['plant'];
        $storage_location = $this->session->userdata['ADMIN']['storage_location'];
        $plant_name = $this->session->userdata['ADMIN']['plant_name'];
        $storage_location_name = $this->session->userdata['ADMIN']['storage_location_name'];
        $admin_id = $this->session->userdata['ADMIN']['admin_id'];

        $opname_header['posting_date'] = $this->input->post('postDate');
        $validasiTgl = $this->st_model->cekTgl($opname_header['posting_date'].' 00:00:00');
        if ($validasiTgl && $validasiTgl > 0) {
            return $this->session->set_flashdata('failed', "Stock Opname Gagal Terbentuk, Tidak Bisa melakukan lebih dari 1 SO dalam 1 Posting Date yang sama");
        } 
        $opname_header['status'] = $this->input->post('appr')? $this->input->post('appr') : '1';
        $opname_header['item_group_code'] = $this->input->post('matGroup');
        $opname_header['created_date'] = date('Y-m-d');
        $opname_header['plant'] = $plant;
        $opname_header['plant_name'] = $plant_name;
        $opname_header['storage_location_name'] = $storage_location_name ;
        $opname_header['storage_location'] = $storage_location ;
        $opname_header['id_opname_plant'] = $this->st_model->id_opname_plant_new_select($opname_header['plant'],$opname_header['created_date']);
        $opname_header['id_user_input'] = $admin_id;
        $opname_header['opname_no'] = '';
        $opname_header['id_user_approved'] = $this->input->post('appr')? $admin_id : 0;
        $opname_header['admin_approved_date'] = $this->input->post('appr')? date('Y-m-d H:i:s') : '';
        $opname_header['id_am'] = 0;
        $opname_header['id_rm'] = 0;
        $opname_header['am_approved'] = 0;
        $opname_header['rm_approved'] = 0;
        $opname_header['freeze'] = 'Y';
        $opname_header['back'] = 1;

        $count = count($this->input->post('detMatrialNo'));

        if($id_opname_header = $this->st_model->opname_header_insert($opname_header)){
            $input_detail_success = false;
            for($i = 0; $i < $count; $i++){
                $opname_detail['id_opname_header'] = (int)$id_opname_header;
                $opname_detail['id_opname_h_detail'] = $i+1;
                $opname_detail['item_grp_name'] = $this->input->post('ItemGrp')[$i];
                $opname_detail['material_no'] = $this->input->post('detMatrialNo')[$i];
                $opname_detail['material_desc'] = $this->input->post('detMatrialDesc')[$i];
                $opname_detail['requirement_qty'] = (float)$this->input->post('detQty')[$i];
                $opname_detail['begin_balance'] = (float)$this->input->post('beginBalance')[$i];
                $opname_detail['data_in'] = (float)$this->input->post('dataIn')[$i];
                $opname_detail['data_out'] = (float)$this->input->post('dataOut')[$i];
                $opname_detail['variance'] = (float)$this->input->post('variance')[$i];
                $opname_detail['variance_value'] = (float)$this->input->post('varianceValue')[$i];
                $opname_detail['uom'] = $this->input->post('detUom')[$i];
                $opname_detail['stock'] = $this->input->post('OnHand')[$i];

                $countRoom = count($this->input->post('Qr')[$i]);

                if($id_opname_detail = $this->st_model->opname_detail_insert($opname_detail)){
                    for ($j=0; $j < $countRoom; $j++) { 
                        $temp = $this->input->post('Qr')[$i][$j];
                        $temp_data = explode('|',$temp);
                        $opname_room_detail['id_opname_header'] = $id_opname_header;
                        $opname_room_detail['id_opname_detail'] = $id_opname_detail;
                        $opname_room_detail['id_room'] = $temp_data[0];
                        $opname_room_detail['requirement_qty'] = $temp_data[1];
                        $opname_room_detail['uom'] = $this->input->post('detUom')[$i];

                        if ($this->st_model->opname_room_detail_insert($opname_room_detail)) {
                            $input_detail_success = TRUE;
                        }
                    }
                }
            }
        }

        if($input_detail_success){
            return $this->session->set_flashdata('success', "Stock Opname Berhasil Terbentuk");
        }else{
            return $this->session->set_flashdata('failed', "Stock Opname Gagal Terbentuk");
        }
    }

    public function edit(){
        $id_opname_header = $this->uri->segment(4);
        $arr_ids = explode(",",str_replace(' ','',$this->session->userdata['ADMIN']['admin_perm_grup_ids']));
        
        $object['plant_name'] = $this->session->userdata['ADMIN']['plant'].' - '.$this->session->userdata['ADMIN']['plant_name'];
		$object['storage_location_name'] = $this->session->userdata['ADMIN']['storage_location'].' - '.$this->session->userdata['ADMIN']['storage_location_name'];
        $object['data'] = $this->st_model->opname_header_select($id_opname_header);
        $object['head'] = $this->st_model->headTemplate();
		$object['check_am_rm'] = $this->st_model->checkAMRM($arr_ids);
		$object['opname_header']['id_opname_header'] = $id_opname_header;

        if($object['data']['status'] == '1'){
            $object['opname_header']['status_string'] = 'Not Approved';                              
        }else if($object['data']['status'] == '2'){
            $object['opname_header']['status_string'] = 'Approved';
        }else{
            $object['opname_header']['status_string'] = 'Cancel';
        }
        
        if($object['data']['am_approved'] == '0'){
            $object['opname_header']['status_string_am'] = 'Not Approved';                              
        }else if($object['data']['am_approved'] == '2'){
            $object['opname_header']['status_string_am'] = 'Approved';
        }else{
            $object['opname_header']['status_string_am'] = 'Rejected';
        }
        
        if($object['data']['rm_approved'] == '0'){
            $object['opname_header']['status_string_rm'] = 'Not Approved';                              
        }else if($object['data']['rm_approved'] == '2'){
            $object['opname_header']['status_string_rm'] = 'Approved';
        }else{
            $object['opname_header']['status_string_rm'] = 'Rejected';
		}
		
        $object['opname_header']['posting_date'] = $object['data']['posting_date'];
        $object['opname_header']['opname_no'] = $object['data']['opname_no'];
        $object['opname_header']['request_reason'] = $object['data']['request_reason'];
        $object['opname_header']['item_group_code'] = $object['data']['item_group_code'];
        $object['opname_header']['status'] = $object['data']['status'];
        $object['opname_header']['request_reason'] = $object['data']['request_reason'];
        $object['opname_header']['am_approved'] = $object['data']['am_approved'];
        $object['opname_header']['rm_approved'] = $object['data']['rm_approved'];
        $object['opname_header']['id_am'] = $object['data']['id_am'];
        $object['opname_header']['id_rm'] = $object['data']['id_rm'];

        $ids = '';
        $ganda = '';
        foreach($object['check_am_rm'] as $val){
            if($val['group_name'] == 'Area Manager'){
                $ids = 14;
                $ganda .= 14;
            }elseif($val['group_name'] == 'Regional Manager'){
                $ids = 10064;
                $ganda .= 10064;
            }
        }
        $object['opname_header']['ids'] = $ids;
        $object['opname_header']['ganda'] = $ganda;

        $this->load->view('transaksi1/stock_outlet/stock/edit_view', $object);
    }

    public function showStockDetail(){
        $id_opname_header = $this->input->post('id_opname_header');
        $stts = $this->input->post('status');
        $rs = $this->st_model->opname_details_select($id_opname_header);
        $head = $this->st_model->headTemplate();
        $dt = array();
        $i = 1;
        if($rs){
            foreach($rs as $key=>$value){
                $room = $this->st_model->opname_room_select($id_opname_header,$value['id_opname_detail']);
                $nestedData=array();
                $room_data=array();
                $nestedData['no'] = $i;
                $nestedData['whscode'] = $this->session->userdata['ADMIN']['plant'];
                $nestedData['itmgrp'] = $value['item_grp_name'];
                $nestedData['itmcode'] = $value['material_no'];
                $nestedData['itmname'] = $value['material_desc'];
                $nestedData['onhand'] = number_format($value['stock'],4,'.',',');
                $nestedData['uom'] = $value['uom'];
                $nestedData['akm'] = number_format($value['requirement_qty'],4,'.',',');
                $nestedData['variance'] = number_format($value['variance'],4,'.',',');
                $nestedData['variance_value'] = number_format($value['variance_value'],4,'.',',');
                $nestedData['begin_balance'] = number_format($value['begin_balance'],4,'.',',');
                $nestedData['in'] = number_format($value['data_in'],4,'.',',');
                $nestedData['out'] = number_format($value['data_out'],4,'.',',');
                for ($j=1; $j <= count($head); $j++) { 
                    $nestedData['qr'.$room[$j-1]['id_room']] = number_format($room[$j-1]['requirement_qty'],4,'.',',');
                }
                $dt[] = $nestedData;
                $i++;
            }
        }

        $json_data = array(
            "data" => $dt
        );
        echo json_encode($json_data);
    }
    
    public function addDataUpdate(){

        $admin_id = $this->session->userdata['ADMIN']['admin_id'];

        $opname_header['id_opname_header'] = $this->input->post('idHead');
        $opname_header['status'] = $this->input->post('appr')? $this->input->post('appr') : '1';
        $opname_header['id_user_approved'] = $this->input->post('appr')? $admin_id : 0;
        $opname_header['admin_approved_date'] = $this->input->post('appr')? date('Y-m-d H:i:s') : '';
        $opname_header['am_approved'] = 0;
        $opname_header['rm_approved'] = 0;

        $count = count($this->input->post('detMatrialNo'));

        $id_detail = $this->st_model->opname_details_select($opname_header['id_opname_header']);

        if($this->st_model->opname_header_update($opname_header)){
            $update_detail_success = false;
            if ($this->st_model->opname_details_delete($opname_header['id_opname_header'])) {
                for($i = 0; $i < $count; $i++){
                    $opname_detail['id_opname_header'] = (int)$opname_header['id_opname_header'];
                    $opname_detail['id_opname_h_detail'] = $i+1;
                    $opname_detail['item_grp_name'] = $this->input->post('ItemGrp')[$i];
                    $opname_detail['material_no'] = $this->input->post('detMatrialNo')[$i];
                    $opname_detail['material_desc'] = $this->input->post('detMatrialDesc')[$i];
                    $opname_detail['requirement_qty'] = (float)$this->input->post('detQty')[$i];
                    $opname_detail['begin_balance'] = (float)$this->input->post('beginBalance')[$i];
                    $opname_detail['data_in'] = (float)$this->input->post('dataIn')[$i];
                    $opname_detail['data_out'] = (float)$this->input->post('dataOut')[$i];
                    $opname_detail['variance'] = (float)$this->input->post('variance')[$i];
                    $opname_detail['variance_value'] = (float)$this->input->post('varianceValue')[$i];
                    $opname_detail['uom'] = $this->input->post('detUom')[$i];
                    $opname_detail['stock'] = $this->input->post('OnHand')[$i];
    
                    $countRoom = count($this->input->post('Qr')[$i]);
    
                    if($id_opname_detail = $this->st_model->opname_detail_insert($opname_detail)){
                        if ($this->st_model->opname_room_delete($opname_header['id_opname_header'],$id_detail[$i]['id_opname_detail'])) {
                            for ($j=0; $j < $countRoom; $j++) { 
                                $temp = $this->input->post('Qr')[$i][$j];
                                $temp_data = explode('|',$temp);
                                $opname_room_detail['id_opname_header'] = (int)$opname_header['id_opname_header'];
                                $opname_room_detail['id_opname_detail'] = $id_opname_detail;
                                $opname_room_detail['id_room'] = $temp_data[0];
                                $opname_room_detail['requirement_qty'] = $temp_data[1];
                                $opname_room_detail['uom'] = $this->input->post('detUom')[$i];
        
                                if ($this->st_model->opname_room_detail_insert($opname_room_detail)) {
                                    $update_detail_success = TRUE;
                                }
                            }
                        }
                    }
                }
            }
        }

        if($update_detail_success){
            return $this->session->set_flashdata('success', "Stock Opname Berhasil di Update");
        }else{
            return $this->session->set_flashdata('failed', "Stock Opname Gagal di Update");
        }
    }

    public function actionAreaRegMgr(){
        $isAM = $this->st_model->opname_header_select($this->input->post('idHead'));
        if ($isAM['am_approved'] == 0) {
            $this->actionAreaMgr('am');
        } else {
            $this->actionRegMgr('rm');
        }
    }

    public function actionAreaMgr($am = ''){
        $admin_id = $this->session->userdata['ADMIN']['admin_id'];

        $opname_header['id_opname_header'] = $this->input->post('idHead');
        if ($this->input->post('action')==1) {
            $opname_header['request_reason'] = $this->input->post('Reason');
            $opname_header['am_rejected_date'] = date('Y-m-d H:i:s');
        }
        $isAM = $am ? $am : 'am';
        $opname_header['id_am'] = $isAM=='am' ? $admin_id : 0;
        $opname_header['am_approved'] = $isAM=='am' ? $this->input->post('action') : 0;
        $opname_header['am_approved_date'] = $this->input->post('action')==2 ? date('Y-m-d H:i:s') : '';

        $succes_update_detail = false;
        if($this->st_model->opname_header_update_area_mgr($opname_header))
            $succes_update_detail = true;

        if($succes_update_detail && $this->input->post('action')==1){
            return $this->session->set_flashdata('failed', "Stock Opname Rejected By Area Manager");
        } else {
            return $this->session->set_flashdata('success', "Stock Opname Berhasil Approved By Area Manager");
        }
    }

    public function actionRegMgr($rm = ''){
        $admin_id = $this->session->userdata['ADMIN']['admin_id'];

        $opname_header['id_opname_header'] = $this->input->post('idHead');
        if ($this->input->post('action')==1) {
            $opname_header['request_reason'] = $this->input->post('Reason');
            $opname_header['rm_rejected_date'] = date('Y-m-d H:i:s');
        }
        $isRM = $rm ? $rm : 'rm';
        $opname_header['id_rm'] = $isRM=='rm' ? $admin_id : 0;
        $opname_header['rm_approved'] = $isRM=='rm' ? $this->input->post('action') : 0;
        $opname_header['rm_approved_date'] = $this->input->post('action')==2 ? date('Y-m-d H:i:s') : '';

        $succes_update_detail = false;
        if($this->st_model->opname_header_update_reg_mgr($opname_header))
            $succes_update_detail = true;

        if($succes_update_detail && $this->input->post('action')==1){
            return $this->session->set_flashdata('failed', "Stock Opname Rejected By Regional Manager");
        } else {
            return $this->session->set_flashdata('success', "Stock Opname Berhasil Approved By Regional Manager");
        }
    }

    public function deleteData(){
        $id_opname_header = $this->input->post('deleteArr');
        $deleteData = false;
        foreach($id_opname_header as $id){
            if($this->st_model->opname_header_delete($id))
            $deleteData = true;
        }
        
        if($deleteData){
            return $this->session->set_flashdata('success', "Stock Opname Berhasil dihapus");
        }else{
            return $this->session->set_flashdata('failed', "Stock Opname Approved, Gagal dihapus");
        }
    }

    function readFile(){

        $config['upload_path'] = './files/uploads';
        $config['allowed_types'] = 'xls|xlsx|xlsm|csv';
        // $config['max_size'] = '10000';
        $config['overwrite'] = TRUE; 
        $config['file_name'] = 'stock-opname-'.$this->session->userdata['ADMIN']['plant'].'-'.date('d-m-Y');

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('file')) {
            $data_upload = $this->upload->data();

            $excelreader = new PHPExcel_Reader_Excel2007();
            $loadexcel   = $excelreader->load('files/uploads/'.$data_upload['file_name']);
            $sheet       = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);

            $head = $this->st_model->headTemplate();

            $data = array();
            //$error = array();
            //$error_data = array();
            $error_angka = array();
            $error_data_angka = array();
            $error_saparator = array();
            $error_data_saparator = array();
            
            $no=1;
            $total_row = count($sheet);
            $total_room = count($head);
            for ($idx=10; $idx <= $total_row; $idx++) { 
                $separator_f = substr($sheet[$idx]['F'], -3, 1); 
                $separator_g = substr($sheet[$idx]['G'], -3, 1);
                $separator_h = substr($sheet[$idx]['H'], -3, 1);
                $separator_i = substr($sheet[$idx]['I'], -3, 1);
                $separator_j = substr($sheet[$idx]['J'], -3, 1);
                $separator_k = substr($sheet[$idx]['K'], -3, 1);
                $separator_l = substr($sheet[$idx]['L'], -3, 1);
                $separator_m = substr($sheet[$idx]['M'], -3, 1);
                
                $rest_f = $sheet[$idx]['F'] ? explode($separator_f, $sheet[$idx]['F']) : []; 
                $rest_g = $sheet[$idx]['G'] ? explode($separator_g, $sheet[$idx]['G']) : [];
                $rest_h = $sheet[$idx]['H'] ? explode($separator_h, $sheet[$idx]['H']) : [];
                $rest_i = $sheet[$idx]['I'] ? explode($separator_i, $sheet[$idx]['I']) : [];
                $rest_j = $sheet[$idx]['J'] ? explode($separator_j, $sheet[$idx]['J']) : [];
                $rest_k = $sheet[$idx]['K'] ? explode($separator_k, $sheet[$idx]['K']) : [];
                $rest_l = $sheet[$idx]['L'] ? explode($separator_l, $sheet[$idx]['L']) : [];
                $rest_m = $sheet[$idx]['M'] ? explode($separator_m, $sheet[$idx]['M']) : [];

                if(!preg_match("/^[0-9.,]*$/", $sheet[$idx]['F']) || !preg_match("/^[0-9.,]*$/", $sheet[$idx]['G']) || !preg_match("/^[0-9.,]*$/", $sheet[$idx]['H']) || !preg_match("/^[0-9.,]*$/", $sheet[$idx]['I']) || !preg_match("/^[0-9.,]*$/", $sheet[$idx]['J']) || !preg_match("/^[0-9.,]*$/", $sheet[$idx]['K']) || !preg_match("/^[0-9.,]*$/", $sheet[$idx]['L']) || !preg_match("/^[0-9.,]*$/", $sheet[$idx]['M']) ){ //validasi huruf tidak bisa di inputkan angka 
                    array_push($error_data_angka, $sheet[$idx]['C']);
                    array_push($error_angka, array(
                        'error' => 1,
                        'message' => $error_data_angka 
                    ));         
                    break;         
                }elseif(($separator_f == '.' && count($rest_f) > 2) || ($separator_g == '.' && count($rest_g) > 2) || ($separator_h == '.' && count($rest_h) > 2) || ($separator_i == '.' && count($rest_i) > 2) || ($separator_j == '.' && count($rest_j) > 2) || ($separator_k == '.' && count($rest_k) > 2) || ($separator_l == '.' && count($rest_l) > 2) || ($separator_m == '.' && count($rest_m) > 2)){
                    array_push($error_data_saparator, $sheet[$idx]['C']);
                    array_push($error_saparator, array(
                        'error' => 1,
                        'message' => $error_data_saparator
                    ));         
                    break; 
                }elseif($separator_f == ',' || $separator_g == ',' || $separator_h == ',' || $separator_i == ',' || $separator_j == ',' || $separator_k == ',' || $separator_l == ',' || $separator_m == ','){
                    array_push($error_data_saparator, $sheet[$idx]['C']);
                    array_push($error_saparator, array(
                        'error' => 1,
                        'message' => $error_data_saparator
                    ));         
                    break; 
                }else{
                    $akm = 0;
                    $abjadBegin = 'E';
                    $dataUpdateData = $this->st_model->getUpdateData($sheet[$idx]['C']);
                    $dataUpdateDataOnHand = $this->st_model->getUpdateDataOnHand($sheet[$idx]['C']);
                    $dataForSO = $this->st_model->getBeginBalanceInOut($sheet[$idx]['C']);
                    $dataForSOPrice = $this->st_model->getLastPrice($sheet[$idx]['C']);
                    if ($dataForSO === 0) {
                        $BeginBalance = 0;
                        $InQty = 0;
                        $OutQty = 0;
                    } else {
                        $BeginBalance = $dataForSO['BeginBalance'];
                        $InQty = $dataForSO['InQty'];
                        $OutQty = $dataForSO['OutQty'];
                    }
                    $dataExcel = array(
                        'no' => $no,
                        'whscode' => $sheet[$idx]['A'],
                        'itmgrp' => $dataUpdateData['ItmsGrpNam'],
                        'itmcode' => $sheet[$idx]['C'],
                        'itmname' => $dataUpdateData['ItemName'],
                        'onhand' => number_format((float)$dataUpdateData['OnHand'],4,'.',','),
                        'uom' => $dataUpdateData['uom']
                    );
                    for ($i=1; $i <= $total_room; $i++) {
                        $currentAbjad = ++$abjadBegin;
                        $dataExcel["qr$i"] = number_format((float)$sheet[$idx][$currentAbjad],4,'.',',');
                        $akm += (float)$sheet[$idx][$currentAbjad];
                    } 
                    $dataExcel["begin_balance"] = number_format($BeginBalance,4,'.',','); 
                    $dataExcel["in"] = number_format($InQty,4,'.',','); 
                    $dataExcel["out"] = number_format($OutQty,4,'.',','); 
                    $dataExcel["akm"] = number_format($akm,4,'.',',');
                    $dataExcel["variance"] = number_format((float)($akm - $dataUpdateDataOnHand['OnHandStock']),4,'.',',');
                    $dataExcel["variance_value"] = number_format((float)(($akm - $dataUpdateDataOnHand['OnHandStock']) * $dataForSOPrice["Avgprice"]),4,'.',',');
                    array_push($data, $dataExcel);
                }
                $no++;
            }

            $json_data = array(
                "data"  => $data ? $data : 0,
                'error_angka' => $error_angka ? $error_angka : 0,
                'error_saparator' => $error_saparator ? $error_saparator : 0
            );

            echo json_encode($json_data);

            //$numrow = 9;
            //$no=0;
            //$lastItem = '';
            //$i = 10;
            /* foreach($sheet as $row){
                if($numrow > 17){
                    $object['validate'] = $this->st_model->checkTemplate($row['C']);
                    if ($object['validate']) { 
                        $separator_f = substr($row['F'], -3, 1); 
                        $separator_g = substr($row['G'], -3, 1);
                        $separator_h = substr($row['H'], -3, 1);
                        $separator_i = substr($row['I'], -3, 1);
                        $separator_j = substr($row['J'], -3, 1);
                        $separator_k = substr($row['K'], -3, 1);
                        $separator_l = substr($row['L'], -3, 1);
                        $separator_m = substr($row['M'], -3, 1);
                        
                        $rest_f = $row['F'] ? explode($separator_f, $row['F']) : []; 
                        $rest_g = $row['G'] ? explode($separator_g, $row['G']) : [];
                        $rest_h = $row['H'] ? explode($separator_h, $row['H']) : [];
                        $rest_i = $row['I'] ? explode($separator_i, $row['I']) : [];
                        $rest_j = $row['J'] ? explode($separator_j, $row['J']) : [];
                        $rest_k = $row['K'] ? explode($separator_k, $row['K']) : [];
                        $rest_l = $row['L'] ? explode($separator_l, $row['L']) : [];
                        $rest_m = $row['M'] ? explode($separator_m, $row['M']) : [];

                        if(!preg_match("/^[0-9.,]*$/", $row['F']) || !preg_match("/^[0-9.,]*$/", $row['G']) || !preg_match("/^[0-9.,]*$/", $row['H']) || !preg_match("/^[0-9.,]*$/", $row['I']) || !preg_match("/^[0-9.,]*$/", $row['J']) || !preg_match("/^[0-9.,]*$/", $row['K']) || !preg_match("/^[0-9.,]*$/", $row['L']) || !preg_match("/^[0-9.,]*$/", $row['M']) ){ //validasi huruf tidak bisa di inputkan angka 
                            array_push($error_data_angka, $row['C']);
                            array_push($error_angka, array(
                                'error' => 1,
                                'message' => $error_data_angka 
                            ));         
                            break;         
                        }elseif(($separator_f == '.' && count($rest_f) > 2) || ($separator_g == '.' && count($rest_g) > 2) || ($separator_h == '.' && count($rest_h) > 2) || ($separator_i == '.' && count($rest_i) > 2) || ($separator_j == '.' && count($rest_j) > 2) || ($separator_k == '.' && count($rest_k) > 2) || ($separator_l == '.' && count($rest_l) > 2) || ($separator_m == '.' && count($rest_m) > 2)){
                            array_push($error_data_saparator, $row['C']);
                            array_push($error_saparator, array(
                                'error' => 1,
                                'message' => $error_data_saparator
                            ));         
                            break; 
                        }elseif($separator_f != '.' || $separator_g != '.' || $separator_h != '.' || $separator_i != '.' || $separator_j != '.' || $separator_k != '.' || $separator_l != '.' || $separator_m != '.'){
                            array_push($error_data_saparator, $row['C']);
                            array_push($error_saparator, array(
                                'error' => 1,
                                'message' => $error_data_saparator
                            ));         
                            break; 
                        }
                        else{
                            $akm = 0;
                            $abjadBegin = 'E';
                            $abjadBeginDouble = 'E';
                            $dataUpdateData = $this->st_model->getUpdateData($row['C']);
                            $dataUpdateDataOnHand = $this->st_model->getUpdateDataOnHand($row['C']);
                            $dataForSO = $this->st_model->getBeginBalanceInOut($row['C']);
                            $dataForSOPrice = $this->st_model->getLastPrice($row['C']);
                            if ($dataForSO == 0) {
                                $BeginBalance = 0;
                                $InQty = 0;
                                $OutQty = 0;
                            } else {
                                $BeginBalance = $dataForSO['BeginBalance'];
                                $InQty = $dataForSO['InQty'];
                                $OutQty = $dataForSO['OutQty'];
                            }
                            if ($lastItem === $row['C']) {
                                for ($i=1; $i <= count($head); $i++) {
                                    $currentAbjadDouble = ++$abjadBeginDouble;
                                    $data[$dataExcel['no']-1]["qr$i"] = number_format(($data[$dataExcel['no']-1]["qr$i"] + (float)$row[$currentAbjadDouble]),4,'.',',');
                                    $data[$dataExcel['no']-1]["akm"] = number_format(($data[$dataExcel['no']-1]["akm"] + (float)$row[$currentAbjadDouble]),4,'.',',');
                                }
                                continue;
                            }
                            $dataExcel = array(
                                'no' => ($no-8),
                                'whscode' => $row['A'],
                                'itmgrp' => $dataUpdateData['ItmsGrpNam'],
                                'itmcode' => $row['C'],
                                'itmname' => $dataUpdateData['ItemName'],
                                'onhand' => number_format((float)$dataUpdateData['OnHand'],4,'.',','),
                                'uom' => $dataUpdateData['uom']
                            );
                            for ($i=1; $i <= count($head); $i++) {
                                $currentAbjad = ++$abjadBegin;
                                $dataExcel["qr$i"] = number_format((float)$row[$currentAbjad],4,'.',',');
                                $akm += (float)$row[$currentAbjad];
                            } 
                            $dataExcel["begin_balance"] = number_format($BeginBalance,4,'.',','); 
                            $dataExcel["in"] = number_format($InQty,4,'.',','); 
                            $dataExcel["out"] = number_format($OutQty,4,'.',','); 
                            $dataExcel["akm"] = number_format($akm,4,'.',',');
                            $dataExcel["variance"] = number_format((float)($akm - $dataUpdateDataOnHand['OnHandStock']),4,'.',',');
                            $dataExcel["variance_value"] = number_format((float)(($akm - $dataUpdateDataOnHand['OnHandStock']) * $dataForSOPrice["Avgprice"]),4,'.',',');
                            $lastItem = $row['C'];
                            array_push($data, $dataExcel);
                        }
                    } else {
                        array_push($error_data, $row['C']);
                        array_push($error, array(
                            'error' => 1,
                            'message' => $error_data
                        ));
                        break; 
                    }
                    $i++;

                }
                $numrow++;
                $no++;
            }

            $json_data = array(
                "data"  => $data ? $data : 0,
                "error" => $error ? $error : 0,
                'error_angka' => $error_angka ? $error_angka : 0,
                'error_saparator' => $error_saparator ? $error_saparator : 0
            );

            echo json_encode($json_data); */
        } else {
            echo $this->upload->display_errors();
        }
    }

    function readFileForDefaultData(){
        $head = $this->st_model->headTemplate();
        $dataDefaultRow = $this->st_model->template();
        $no = count($this->input->post('dataItemCode'));
        $defaultData = array();

        if ($no == count($dataDefaultRow)) {
            $json_data = array(
                "data"  => $defaultData
            );
            echo json_encode($json_data);
            exit();
        }

        $dataDefault = $this->st_model->template($this->input->post('dataItemCode'));
        foreach ($dataDefault as $key => $default) {
            $dataFromDefault = array(
                'no' => ($no+1),
                'whscode' => $this->session->userdata['ADMIN']['plant'],
                'itmgrp' => $default['ItmsGrpNam'],
                'itmcode' => $default['ItemCode'],
                'itmname' => $default['ItemName'],
                'onhand' => number_format((float)$default['OnHand'],4,'.',','),
                'uom' => $default['UNIT']
            );
            $abjadBegin = 'E';
            for ($i=1; $i <= count($head); $i++) {
                $dataFromDefault["qr$i"] = number_format(0,4,'.',',');
            }
            $dataUpdateDataOnHand = $this->st_model->getUpdateDataOnHand($default['ItemCode']);
            $dataForDefault = $this->st_model->getBeginBalanceInOut($default['ItemCode']);
            $dataForDefaultPrice = $this->st_model->getLastPrice($default['ItemCode']);
            if ($dataForDefault == 0) {
                $BeginBalanceDefault = 0;
                $InQtyDefault = 0;
                $OutQtyDefault = 0;
            } else {
                $BeginBalanceDefault = $dataForDefault['BeginBalance'];
                $InQtyDefault = $dataForDefault['InQty'];
                $OutQtyDefault = $dataForDefault['OutQty'];
            }
            $dataFromDefault["begin_balance"] = number_format($BeginBalanceDefault,4,'.',','); 
            $dataFromDefault["in"] = number_format($InQtyDefault,4,'.',','); 
            $dataFromDefault["out"] = number_format($OutQtyDefault,4,'.',','); 
            $dataFromDefault["akm"] = number_format(0,4,'.',',');
            $dataFromDefault["variance"] = number_format((float)(0 - $dataUpdateDataOnHand['OnHandStock']),4,'.',',');
            $dataFromDefault["variance_value"] = number_format((float)((0 - $dataUpdateDataOnHand['OnHandStock']) * $dataForDefaultPrice["Avgprice"]),4,'.',',');
            array_push($defaultData, $dataFromDefault);
            $no++;
        }
        $json_data = array(
            "data"  => $defaultData
        );
        echo json_encode($json_data);
    }
    
    function printpdf(){
		$id_opname_header = $this->uri->segment(4);
        $data['data'] = $this->st_model->tampil($id_opname_header);
        $data['head'] = $this->st_model->headTemplate();
		
		ob_start();
		$content = $this->load->view('transaksi1/stock_outlet/stock/printpdf_view',$data);
		$content = ob_get_clean();		
		require_once(APPPATH.'libraries/html2pdf/html2pdf.class.php');
		try
		{
			$html2pdf = new HTML2PDF('L', 'A4', 'en');
			$html2pdf->setTestTdInOnePage(false);
			$html2pdf->pdf->SetDisplayMode('fullpage');
			$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
			$html2pdf->Output('print.pdf');
		}
		catch(HTML2PDF_exception $e) {
			echo $e;
			exit;
		}
    }
    
    function printBeritaAcara(){
		$id_opname_header = $this->uri->segment(4);
        $data['data'] = $this->st_model->tampil($id_opname_header);
        $data['head'] = $this->st_model->headTemplate();
		
		ob_start();
		$content = $this->load->view('transaksi1/stock_outlet/stock/printBA_view',$data);
		$content = ob_get_clean();		
		require_once(APPPATH.'libraries/html2pdf/html2pdf.class.php');
		try
		{
			$html2pdf = new HTML2PDF('P', 'A4', 'en');
			$html2pdf->setTestTdInOnePage(false);
			$html2pdf->pdf->SetDisplayMode('fullpage');
			$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
			$html2pdf->Output('print.pdf');
		}
		catch(HTML2PDF_exception $e) {
			echo $e;
			exit;
		}
    }

    function downloadExcel(){
        $kd_plant = $this->session->userdata['ADMIN']['plant'];
        $plant_name = $this->session->userdata['ADMIN']['plant_name'];
        $object['dataOnHand'] = $this->st_model->template();
        $object['head'] = $this->st_model->headTemplate();

        $excel = new PHPExcel();

        // set config for image
        $imgHead = new PHPExcel_Worksheet_Drawing();
        $imgHead->setName('Logo');
        $imgHead->setDescription('Logo');
        $imgHead->setPath('./files/assets/images/logo.jpeg');
        $imgHead->setHeight(70);
        $imgHead->setCoordinates('D1');
        $imgHead->setOffsetX(220);
        $imgHead->setWorksheet($excel->getActiveSheet());

        $excel->getActiveSheet()->getProtection()->setSheet(true);

        //set config for column width
        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(80);
        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(9);
        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(9);
        $excel->getActiveSheet()->getColumnDimension('G')->setWidth(9);
        $excel->getActiveSheet()->getColumnDimension('H')->setWidth(9);
        $excel->getActiveSheet()->getColumnDimension('I')->setWidth(9);
        $excel->getActiveSheet()->getColumnDimension('J')->setWidth(13);
        $excel->getActiveSheet()->getColumnDimension('K')->setWidth(12);
        $excel->getActiveSheet()->getColumnDimension('L')->setWidth(9);
        $excel->getActiveSheet()->getColumnDimension('M')->setWidth(10);

        // set config for title header file
        $excel->getActiveSheet()->getStyle('A5')->getAlignment()
              ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('A6')->getAlignment()
              ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excel->getActiveSheet()->getStyle('A5')->getFont()->setBold(true);
        $excel->getActiveSheet()->getStyle('A6')->getFont()->setBold(true);

        $excel->getActiveSheet()->mergeCells('A5:M5');
        $excel->setActiveSheetIndex(0)->setCellValue('A5', 'Template Stock Opname'); 
        $excel->getActiveSheet()->mergeCells('A6:M6');
        $excel->setActiveSheetIndex(0)->setCellValue('A6', 'Outlet '.$kd_plant.' '.$plant_name); 

        //style of border
        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => '00000000'),
                ),
            ),
        );

        $excel->getActiveSheet()->getProtection()->setPassword('MSI_SO');
        
        $excel->getActiveSheet()->getStyle('A9:M9')->applyFromArray($styleArray);

        // set config for title header table 
        $excel->getActiveSheet()->getStyle('A9:M9')->getAlignment()
              ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excel->getActiveSheet()->getStyle('A9:M9')->getAlignment()
              ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $excel->getActiveSheet()->getStyle('A9:M9')->getFont()->setBold(true);
        
        $excel->getActiveSheet()->getRowDimension('9')->setRowHeight(20);

        $excel->setActiveSheetIndex(0)->setCellValue('A9', "WHSCODE"); 
        $excel->setActiveSheetIndex(0)->setCellValue('B9', "Item Group"); 
        $excel->setActiveSheetIndex(0)->setCellValue('C9', "Item Code"); 
        $excel->setActiveSheetIndex(0)->setCellValue('D9', "Item Name"); 
        $excel->setActiveSheetIndex(0)->setCellValue('E9', "UOM"); 
        $abjadBegin = 'E';
        for ($i=1; $i <= count($object['head']); $i++) {
            $excel->setActiveSheetIndex(0)->setCellValue(++$abjadBegin.'9', $object['head'][$i-1]['Name']);
        }

        $numrow = 10;
        foreach($object['dataOnHand'] as $key=>$r){ 
            // applying border style
            $excel->getActiveSheet()->getStyle('A'.$numrow.':M'.$numrow)->applyFromArray($styleArray);

            // set config alignment body table
            $excel->getActiveSheet()->getStyle('A'.$numrow.':B'.$numrow)->getAlignment()
              ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $excel->getActiveSheet()->getStyle('E'.$numrow)->getAlignment()
              ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $kd_plant);
            $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $r['ItmsGrpNam']);
            $excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $r['ItemCode']);
            $excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $r['ItemName']);
            $excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $r['UNIT']);

            $abjadBegin = 'E';
            for ($i=1; $i <= count($object['head']); $i++) {
                $abjad = ++$abjadBegin;
                $excel->getActiveSheet()->getStyle($abjad.$numrow)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);

                $excel->setActiveSheetIndex(0)->setCellValue($abjad.$numrow, '');
            }
            $numrow++;
        }
    
        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);
        // Set orientasi kertas jadi LANDSCAPE
        $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        // Set judul file excel nya
        $excel->getActiveSheet(0)->setTitle("Template Stock Opname");
        $excel->setActiveSheetIndex(0);
        // Proses file excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Template Stock Opname.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');
        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');
    }
}
?>