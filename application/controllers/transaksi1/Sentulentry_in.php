<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Sentulentry_in extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->library('auth');  
		if(!$this->auth->is_logged_in()) {
			redirect(base_url());
        }

        // load model
        $this->load->library('form_validation');
        $this->load->library('l_general');
        $this->load->model('transaksi1/sentulentry_model', 'se_model');
        $this->load->model('master/permission_model', 'm_perm');
		$this->load->model('general_model', 'm_general');
    }

    public function index(){
        $this->load->view('transaksi1/eksternal/sentulentry_in/list_view');
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
        } else {
            $date_from2 = '';
        }

        if($toDate != '') {
			$year = substr($toDate, 6);
			$month = substr($toDate, 3,2);
			$day = substr($toDate, 0,2);
			$date_to2 = $year.'-'.$day.'-'.$month.' 23:59:59';
        } else {
            $date_to2 = '';
        }

        $rs = $this->se_model->getSentulEntryHeaderIn($date_from2, $date_to2, $status);
        $data = array();
        
        $status_string = '';
        $log = '';

        foreach($rs as $key=>$val) {
			if($val['status'] == '1') {
				$status_string = 'Created';
			} elseif ($val['status'] == '2') {
				$status_string = 'Edited';
            } else {
                $status_string = 'Canceled';
            }

            $nestedData = array();
            $nestedData['id_sentul_entry_header'] = $val['id_sentul_entry_header'];
            $nestedData['posting_date'] = date("d-m-Y",strtotime($val['posting_date']));
            $nestedData['last_modified'] = date("d-m-Y H:i:s",strtotime($val['last_modified']));
            $nestedData['user_input'] = $val['user_input'];
            $nestedData['user_update'] = $val['user_update'];
            $nestedData['user_cancel'] = $val['user_cancel'];
            $nestedData['status'] = $val['status'];
            $nestedData['sap_doc_number'] = $val['sap_doc_number'];
            $nestedData['status_string'] = $status_string; 
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
        $object['plant'] = $this->session->userdata['ADMIN']['plant']; 
        $object['plant_name'] = $this->session->userdata['ADMIN']['plant_name'];
        $object['storage_location'] = $this->session->userdata['ADMIN']['storage_location']; 
        $object['storage_location_name'] = $this->session->userdata['ADMIN']['storage_location_name'];
        $object['reasons'] = $this->se_model->getReasons('In');

        $this->load->view('transaksi1/eksternal/sentulentry_in/add_view', $object);
    }

    public function addData(){
        if($this->input->post('plant') != ''){
            $strPlant = explode(' - ',$this->input->post('plant'));
            $plant = trim($strPlant[0]);
            $plant_name = trim($strPlant[1]);
        }else{
            $plant = '';
            $plant_name = '';
        }

        if($this->input->post('storageLoc') != ''){
            $strStorage = explode(' - ',$this->input->post('storageLoc'));
            $storage_location = trim($strStorage[0]);
            $storage_location_name = trim($strStorage[1]);
        }else{
            $storage_location = '';
            $storage_location_name = '';
        }
        
        if($this->input->post('fromOutlet') != ''){
            $strfromOutlet = explode(' - ',$this->input->post('fromOutlet'));
            $from_outlet = trim($strfromOutlet[0]);
            $from_outlet_name = trim($strfromOutlet[1]);
        }else{
            $from_outlet = '';
            $from_outlet_name = '';
        }

        $admin_id = $this->session->userdata['ADMIN']['admin_id'];

        $sentulentry_header['plant'] = $plant;
        $sentulentry_header['plant_name'] = $plant_name;
        $sentulentry_header['storage_location'] = $storage_location;
        $sentulentry_header['storage_location_name'] = $storage_location_name;
        $sentulentry_header['posting_date'] = $this->l_general->str_to_date($this->input->post('postingDate'));
        $sentulentry_header['id_sentul_entry_plant'] = $this->se_model->idSentulEntryNewPlantSelect($plant,$sentulentry_header['posting_date']);
        $sentulentry_header['type_doc'] = $this->input->post('type');
        $sentulentry_header['reason'] = $this->input->post('reason');
        $sentulentry_header['from_outlet'] = $from_outlet;
        $sentulentry_header['from_outlet_name'] = $from_outlet_name;
        $sentulentry_header['to_outlet'] = $this->input->post('toOutlet');
        $sentulentry_header['to_outlet_name'] = $this->input->post('toOutletName');
        $sentulentry_header['status'] = $this->input->post('appr') ? $this->input->post('appr') : 1;
        $sentulentry_header['remark'] = $this->input->post('remark');
        $sentulentry_header['sap_doc_number'] = $this->input->post('SAPNumber');
        $sentulentry_header['id_user_input'] = $admin_id;
        $sentulentry_header['id_user_update'] = 0;
        $sentulentry_header['id_user_cancel'] = 0;

        $count = count($this->input->post('detMatrialNo'));
        
        if($id_sentulentry_header = $this->se_model->sentulEntryInsertHeader($sentulentry_header)){
            $input_detail_success = false;
            for($i = 0; $i < $count; $i++){
                $sentulentry_details['id_sentul_entry_header'] = $id_sentulentry_header;
                $sentulentry_details['id_se_item_sequence'] = $i+1;
                $sentulentry_details['material_no'] = $this->input->post('detMatrialNo')[$i];
                $sentulentry_details['material_desc'] = $this->input->post('detMatrialDesc')[$i];
                $sentulentry_details['quantity'] = $this->input->post('detQty')[$i];
                $sentulentry_details['uom'] = $this->input->post('detUom')[$i];

                if($this->se_model->sentulEntryInsertDetails($sentulentry_details))
                    $input_detail_success = TRUE;
            }
        }

        if($input_detail_success){
            return $this->session->set_flashdata('success', "Sentul Data Entry Telah Terbentuk");
        }else{
            return $this->session->set_flashdata('failed', "Sentul Data Entry Gagal Terbentuk");
        }
    }
    
    public function edit(){
        $id_sentulentry_header = $this->uri->segment(4);
        $object['data'] = $this->se_model->sentulEntrySelectHeader($id_sentulentry_header);
        
        $object['sentulentry_header']['id_sentul_entry_header'] = $id_sentulentry_header;
        $object['sentulentry_header']['plant'] = $object['data']['plant'].' - '.$object['data']['PLANT_NAME_NEW'];
        $object['sentulentry_header']['from_outlet'] = $object['data']['from_outlet'].' - '.$object['data']['from_outlet_name'];
        $object['sentulentry_header']['to_outlet'] = $object['data']['to_outlet'].' - '.$object['data']['to_outlet_name'];
        $object['sentulentry_header']['posting_date'] = $object['data']['posting_date'];
        $object['sentulentry_header']['type'] = $object['data']['type_doc'];
        $object['sentulentry_header']['reason'] = $object['data']['reason'];
        $object['sentulentry_header']['status'] = $object['data']['status'];
        $object['sentulentry_header']['remark'] = $object['data']['remark'];
        $object['sentulentry_header']['cancel_reason'] = $object['data']['cancel_reason'];
        $object['sentulentry_header']['sap_doc_number'] = $object['data']['sap_doc_number'];

        $this->load->view('transaksi1/eksternal/sentulentry_in/edit_view', $object);
    }

    public function addDataUpdate(){
        $admin_id = $this->session->userdata['ADMIN']['admin_id'];
        $sentulentry_header['id_sentul_entry_header'] = $this->input->post('idsentulentry_header');
        $sentulentry_header['posting_date'] = $this->l_general->str_to_date($this->input->post('postingDate'));
        $sentulentry_header['status'] = $this->input->post('appr');
        $sentulentry_header['remark'] = $this->input->post('remark');
        $sentulentry_header['id_user_update'] = $admin_id;
        
        $count = count($this->input->post('detMatrialNo'));
        if($this->se_model->sentulEntryUpdateHeader($sentulentry_header)){
            $update_detail_success = false;
            if($this->se_model->sentulEntryDeleteDetails($sentulentry_header['id_sentul_entry_header'])){
                for($i = 0; $i < $count; $i++){
                    $sentulentry_details['id_sentul_entry_header'] = $sentulentry_header['id_sentul_entry_header'];
                    $sentulentry_details['id_se_item_sequence'] = $i+1;
                    $sentulentry_details['material_no'] = $this->input->post('detMatrialNo')[$i];
                    $sentulentry_details['material_desc'] = $this->input->post('detMatrialDesc')[$i];
                    $sentulentry_details['quantity'] = $this->input->post('detQty')[$i];
                    $sentulentry_details['uom'] = $this->input->post('detUom')[$i];

                    if($this->se_model->sentulEntryInsertDetails($sentulentry_details))
                        $update_detail_success = TRUE;
                }
            }
        }

        if($update_detail_success){
            return $this->session->set_flashdata('success', "Sentul Data Entry Berhasil di Update");
        }else{
            return $this->session->set_flashdata('failed', "Sentul Data Entry Gagal di Update");
        }
    }

    public function showDataDetailOnEdit(){
        $id_sentulentry_header = $this->input->post('id');
        $stts = $this->input->post('status');
        $rs = $this->se_model->sentulEntrySelectDetails($id_sentulentry_header);
        $dt = array();
        $i = 1;
        if($rs){
            foreach($rs as $key=>$value){
                $nestedData=array();
                $nestedData['id_sentul_entry_detail'] = $value['id_sentul_entry_detail'];
                $nestedData['no'] = $i;
                $nestedData['material_no'] = $value['material_no'];
				$nestedData['material_desc'] = $value['material_desc'];
				$nestedData['quantity'] = $value['quantity'];
                $nestedData['uom'] = $value['uom'];
                $nestedData['status'] = $stts;
                $dt[] = $nestedData;
                $i++;
            }
        }

        $json_data = array(
            "data" => $dt
        );
        echo json_encode($json_data);
    }

    function getdataDetailMaterial(){
        $item_group_code = $this->input->post('matGroup');
        
        $data = $this->se_model->getDataMaterialGroup($item_group_code);

        echo json_encode($data);
    }

    function getdataDetailMaterialSelect(){
		$itemSelect = $this->input->post('MATNR');
        
        $dataMatrialSelect = $this->se_model->getDataMaterialGroupSelect($itemSelect);
      
        echo json_encode($dataMatrialSelect) ;
    }

    public function cancelDocument(){
        $admin_id = $this->session->userdata['ADMIN']['admin_id'];
        $sentulentry_header['id_sentul_entry_header'] = $this->input->post('idsentulentry_header');
        $sentulentry_header['status'] = $this->input->post('status');
        $sentulentry_header['cancel_reason'] = $this->input->post('cancel_reason');
        $sentulentry_header['id_user_cancel'] = $admin_id;

        if($this->se_model->cancelDocument($sentulentry_header)){
            $succes_cancel = true;
        }
        if($succes_cancel){
            return $this->session->set_flashdata('success', "Sentul Data Entry Berhasil di Cancel");
        }else{
            return $this->session->set_flashdata('failed', "Sentul Data Entry Gagal di Cancel");
        }  
    }
}
?>