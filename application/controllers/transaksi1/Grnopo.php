<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Grnopo extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->library('auth');  
		if(!$this->auth->is_logged_in()) {
			redirect(base_url());
        }

        // load model
        $this->load->library('form_validation');
        $this->load->library('l_general');
        $this->load->model('transaksi1/grnonpo_model', 'gnon_model');
    }

    public function index(){
        $this->load->view('transaksi1/eksternal/grnopo/list_view');
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
			$date_to2 = $year.'-'.$day.'-'.$month.' 23:59:59';
        }else{
            $date_to2='';
        }

        $rs = $this->gnon_model->getDataGrNonPo_Header($date_from2, $date_to2, $status);
        $data = array();
        
        $status_string='';
        $log='';

        foreach($rs as $key=>$val){
			if($val['status'] =='1'){
				$status_string= 'Not Approved';
			}else if($val['status'] =='2'){
				$status_string= 'Approved';
			}else{
				$status_string= 'Cancel';
            }
            
            if ($val['back'] == 0 && $val['grnonpo_no'] !='' && $val['grnonpo_no'] !='C')
            {
                $log = "Integrated";
            }else if ($val['back'] == 1 && ($val['grnonpo_no'] =='' || $val['grnonpo_no'] =='C'))
            {
                $log = "Not Integrated";
            }else if ($val['back'] == 0 &&  $val['grnonpo_no'] =='C')
            {
                $log ="Close Document";
            }

            $nestedData = array();
            $nestedData['id_grnonpo_header'] = $val['id_grnonpo_header'];
            $nestedData['grnonpo_no'] = $val['grnonpo_no'];
            $nestedData['posting_date'] = date("d-m-Y",strtotime($val['posting_date']));
            $nestedData['last_modified'] = date("d-m-Y H:i:s",strtotime($val['lastmodified']));
            $nestedData['user_input'] = $val['user_input'];
            $nestedData['user_approved'] = $val['user_approved'];
            $nestedData['status'] = $val['status'];
            $nestedData['status_string'] = $status_string; 
            $nestedData['log'] = $log;
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
        $object['cost_center'] = $this->gnon_model->get_cost_center($object['plant']); 
        $object['matrialGroup'] = $this->gnon_model->showMatrialGroup();

        $this->load->view('transaksi1/eksternal/grnopo/add_view', $object);
    }

    function getdataDetailMaterial(){
        $item_group_code = $this->input->post('matGroup');
        
        $data = $this->gnon_model->getDataMaterialGroup($item_group_code);
        echo json_encode($data);

    }

    public function addData(){
        if($this->input->post("Plant")!= ''){
            $strPlant = explode("-",$this->input->post("Plant"));
            $plant = trim($strPlant[0]);
            $plant_name = trim($strPlant[1]);
        }else{
            $plant = '';
            $plant_name = '';
        }

        if($this->input->post("costCenter")!= ''){
            $str = explode("-",$this->input->post('costCenter'));
            $cost_center = trim($str[0]);
            $cost_center_name = trim($str[1]);
        }else{
            $cost_center = '';
            $cost_center_name = '';
        }

        if($this->input->post("StorageLoc")!= ''){
            $strStorage = explode("-",$this->input->post("StorageLoc"));
            $storage_location = trim($strStorage[0]);
            $storage_location_name = trim($strStorage[1]);
        }else{
            $storage_location = '';
            $storage_location_name = '';
        }
        $admin_id = $this->session->userdata['ADMIN']['admin_id'];

        $grnonpo_header['posting_date'] = $this->l_general->str_to_date($this->input->post('posting_date'));
        $grnonpo_header['item_group_code'] = $this->input->post('matGroup');
        $grnonpo_header['plant'] = $plant;
        $grnonpo_header['plant_name'] = $plant_name;
        $grnonpo_header['storage_location'] = $storage_location;
        $grnonpo_header['storage_location_name'] = $storage_location_name;
        $grnonpo_header['cost_center'] = $cost_center;
        $grnonpo_header['id_grnonpo_plant'] = $this->gnon_model->id_grnonpo_plant_new_select($grnonpo_header['plant'],$grnonpo_header['posting_date']);
        $grnonpo_header['status'] = $this->input->post('appr')? $this->input->post('appr') : '1';
        $grnonpo_header['id_user_input'] = $admin_id;
        $grnonpo_header['grnonpo_no'] = '';
        $grnonpo_header['back'] = 1;
        $grnonpo_header['id_user_approved'] = $this->input->post('appr')? $admin_id : 0;
        $grnonpo_header['remark'] = $this->input->post('Remark');

        $grnonpo_detail['material_no'] = $this->input->post('detMatrialNo');
        $count = count($grnonpo_detail['material_no']);
        
        if($id_grnonpo_header= $this->gnon_model->grnonpo_header_insert($grnonpo_header)){
            $input_detail_success = false;
            for($i =0; $i < $count; $i++){
                $grnonpo_details['id_grnonpo_header'] = $id_grnonpo_header;
                $grnonpo_details['id_grnonpo_h_detail'] = $i+1;
                $grnonpo_details['material_no'] = $this->input->post('detMatrialNo')[$i];
                $grnonpo_details['material_desc'] = $this->input->post('detMatrialDesc')[$i];
                $grnonpo_details['quantity'] = $this->input->post('detQty')[$i];
                $grnonpo_details['price'] = $this->input->post('detPrc')[$i];
                $grnonpo_details['uom'] = $this->input->post('detUom')[$i];
                $grnonpo_details['additional_text'] = $this->input->post('detText')[$i];
                $grnonpo_details['ok'] = 0;
                $grnonpo_details['ok_cancel'] = 0;
                $grnonpo_details['id_user_cancel'] = 0;

                if($this->gnon_model->grnonpo_details_insert($grnonpo_details))
                $input_detail_success = TRUE;
            }
        }

        if($input_detail_success){
            return $this->session->set_flashdata('success', "Goods Receipt Non PO Telah Terbentuk");
        }else{
            return $this->session->set_flashdata('failed', "Goods Receipt Non PO Gagal Terbentuk");
        }
    }

    public function edit(){
        $id_grnonpo_header = $this->uri->segment(4);
        $object['data'] = $this->gnon_model->grnonpo_header_select($id_grnonpo_header);

        $object['grnonpo_header']['id_grnonpo_header'] = $id_grnonpo_header;

		if($object['data']['status'] == '1'){
            $object['grnonpo_header']['status_string'] = 'Not Approved';                              
        }else if($object['data']['status'] == '2'){
            $object['grnonpo_header']['status_string'] = 'Approved';
        }else{
            $object['grnonpo_header']['status_string'] = 'Cancel';
        }
        
        $object['grnonpo_header']['plant'] = $object['data']['plant'].' - '.$object['data']['PLANT_NAME_NEW'];
        $object['grnonpo_header']['grnonpo_no'] = $object['data']['grnonpo_no'];
        $object['grnonpo_header']['storage_location'] = $object['data']['storage_location'].' - '.$object['data']['STOR_LOC_NAME'];
        $object['grnonpo_header']['posting_date'] = $object['data']['posting_date'];
        $object['grnonpo_header']['item_group_code'] = $object['data']['item_group_code'];
        $object['grnonpo_header']['status'] = $object['data']['status'];
        $object['grnonpo_header']['cost_center'] = $object["data"]['cost_center'].' - '.$object["data"]['COST_CENTER_NAME'];
        $object['grnonpo_header']['remark'] = $object['data']['remark'];

        $this->load->view('transaksi1/eksternal/grnopo/edit_view', $object);
    }

    public function addDataUpdate(){
        $admin_id = $this->session->userdata['ADMIN']['admin_id'];
        $grnonpo_header['id_grnonpo_header'] = $this->input->post('idgrnonpo_header');
        $grnonpo_header['posting_date'] = $this->l_general->str_to_date($this->input->post('pstDate'));
        $grnonpo_header['status'] = $this->input->post('aapr')? $this->input->post('aapr') : '1';
        $grnonpo_header['id_user_approved'] = $this->input->post('aapr')? $admin_id : 0;
        $grnonpo_header['remark'] = $this->input->post('Remark');
        
        $count = count($this->input->post('detMatrialNo'));
        if($this->gnon_model->grnonpo_header_update($grnonpo_header)){
            $update_detail_success = false;
            if($this->gnon_model->grnonpo_details_delete($grnonpo_header['id_grnonpo_header'])){
                for($i =0; $i < $count; $i++){
                    $grnonpo_details['id_grnonpo_header'] = $grnonpo_header['id_grnonpo_header'];
                    $grnonpo_details['id_grnonpo_h_detail'] = $i+1;
                    $grnonpo_details['material_no'] = $this->input->post('detMatrialNo')[$i];
                    $grnonpo_details['material_desc'] = $this->input->post('detMatrialDesc')[$i];
                    $grnonpo_details['quantity'] = $this->input->post('detQty')[$i];
                    $grnonpo_details['price'] = $this->input->post('detPrc')[$i];
                    $grnonpo_details['uom'] = $this->input->post('detUom')[$i];
                    $grnonpo_details['additional_text'] = $this->input->post('detText')[$i];
                    $grnonpo_details['ok'] = 0;
                    $grnonpo_details['ok_cancel'] = 0;
                    $grnonpo_details['id_user_cancel'] = 0;

                    if($this->gnon_model->grnonpo_detail_insert($grnonpo_details))
                        $update_detail_success = TRUE;
                }
            }
        }

        if($update_detail_success){
            return $this->session->set_flashdata('success', "Goods Receipt Non PO Berhasil di Update");
        }else{
            return $this->session->set_flashdata('failed', "Goods Receipt Non PO Gagal di Update");
        }
    }

    public function showGistonewOutDetail(){
        $id_grnonpo_header = $this->input->post('id');
        $stts = $this->input->post('status');
        $rs = $this->gnon_model->grnonpo_details_select($id_grnonpo_header);
        $dt = array();
        $i = 1;
        if($rs){
            foreach($rs as $key=>$value){
                $nestedData=array();
                $nestedData['id_grnonpo_detail'] = $value['id_grnonpo_detail'];
                $nestedData['no'] = $i;
                $nestedData['material_no'] = $value['material_no'];
				$nestedData['material_desc'] = $value['material_desc'];
				$nestedData['gr_quantity'] = $value['quantity'];
				$nestedData['price'] = $value['price'];
                $nestedData['uom'] = $value['uom'];
                $nestedData['text'] = $value['additional_text'];
                $nestedData['status'] = $stts;
                $nestedData['total'] = number_format($value['quantity']*$value['price'],4,'.','');
                $dt[] = $nestedData;
                $i++;
            }
        }

        $json_data = array(
            "data" => $dt
        );
        echo json_encode($json_data);
    }

    public function getDetailsTransferOut(){
        $item_group_code = $this->input->post('cboMatrialGroup');
        
		$do_no = $this->input->post('doNo');

		if ((!empty($item_group_code)) || (trim($item_group_code)!="")) {
			if($item_group_code == 'all') {
                $gistonew_out_details = $this->gnon_model->sap_item_groups_select_all_grnonpo();
			} else {
				$gistonew_out_details = $this->gnon_model->sap_items_select_by_item_group($item_group_code, 'grnonpo');
            }
			
			echo json_encode($gistonew_out_details);
		}
	}

    function getdataDetailMaterialSelect(){
		$itemSelect = $this->input->post('MATNR');
        
        $dataMatrialSelect = $this->gnon_model->getDataMaterialGroupSelect($itemSelect);
      
        echo json_encode($dataMatrialSelect) ;
    }
    
    public function cancelGrNonPo(){
        $grnonpo_header['id_grnonpo_header'] = $this->input->post('id_grnonpo_header');
        $grnonpo_details = $this->input->post('deleteArr');

        if($this->gnon_model->cancelHeaderGrNonPo($grnonpo_header)){
            $succes_cancel_grnonpo = false;
            for($i=0; $i<count($grnonpo_details); $i++){
                if($this->gnon_model->cancelDetailsGrNonPo($grnonpo_details[$i]))
                $succes_cancel_grnonpo = true;
            }
        }
        if($succes_cancel_grnonpo){
            return $this->session->set_flashdata('success', "Goods Receipt Non PO Berhasil di Cancel");
        }else{
            return $this->session->set_flashdata('failed', "Goods Receipt Non PO Gagal di Cancel");
        }  
    }

    public function deleteData(){
        $id_grnonpo_header = $this->input->post('deleteArr');
        $deleteData = false;
        foreach($id_grnonpo_header as $id){
            if($this->gnon_model->t_grnonpo_header_delete($id))
            $deleteData = true;
        }
        
        if($deleteData){
            return $this->session->set_flashdata('success', "Goods Receipt Non PO Berhasil dihapus");
        }else{
            return $this->session->set_flashdata('failed', "Goods Receipt Non PO Approved, Gagal dihapus");
        }
    }
    
    public function printpdf($id =""){

        if($id != ""){
            $data['data'] = $this->gnon_model->tampil($id);
            
            ob_start();
            $content = $this->load->view('transaksi1/eksternal/grnopo/printpdf_view',$data);
            $content = ob_get_clean();		
            require_once(APPPATH.'libraries/html2pdf/html2pdf.class.php');
            try
            {
                $html2pdf = new HTML2PDF('P', 'F4', 'en');
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
        
    }
}
?>