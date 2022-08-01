<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Returnin extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->library('auth');  
		if(!$this->auth->is_logged_in()) {
			redirect(base_url());
        }

        // load model
        $this->load->library('form_validation');
        $this->load->library('l_general');
        $this->load->model('transaksi1/returnin_model', 'rIn_model');
    }

    public function index(){
        $this->load->view('transaksi1/eksternal/returnin/list_view');
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

        $rs = $this->rIn_model->getDataReturnIn_Header($date_from2, $date_to2, $status);
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
            
            if ($val['back'] == 0) {
                $log = 'Integrated';
            } else {
                $log = 'Not Integrated';
            }

            $nestedData = array();
            $nestedData['id_retin_header'] = $val['id_retin_header'];
            $nestedData['do_no'] = $val['do_no'];
            $nestedData['do_no1'] = $val['do_no1'];
            $nestedData['retin_no'] = $val['retin_no'];
            $nestedData['retin_no1'] = $val['retin_no1'];
            $nestedData['posting_date'] = date("d-m-Y",strtotime($val['posting_date']));
            $nestedData['last_modified'] = date("d-m-Y H:i:s",strtotime($val['lastmodified']));
            $nestedData['status'] = $val['status'];
            $nestedData['status_string'] = $status_string; 
            $nestedData['log'] = $log; 
            $nestedData['user_input'] = $val['user_input'];
            $nestedData['user_approved'] = $val['user_approved'];
            $nestedData['ok_cancel'] = $val['ok_cancel'];
            $nestedData['ok_cancel_string'] = $val['ok_cancel'] ? 'Y' : 'N';
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
        $data['ro_nos'] = $this->rIn_model->sap_do_select_all();
        $object['po_no']['-'] = '';
        if($data['ro_nos'] != FALSE){
            foreach($data['ro_nos'] as $po_no){
                $object['po_no'][$po_no['VBELN']] = $po_no['VBELN1'].' - ('.$po_no['RETURN_FROM'].' - '.$po_no['RETURN_FROM_NAME'].')';
            }
        } 
        $this->load->view('transaksi1/eksternal/returnin/add_view', $object);
    }

     public function getDataRoHeader(){
        $roNumber = $this->input->post('roNumberHeader');
        $data = $this->rIn_model->sap_retin_header_select_by_do_no($roNumber);
        $dataOption = $this->rIn_model->sap_retin_select_item_group_do($roNumber);

        if (count($data) > 0) {

            $json_data = array(
                "data" => $data,
                "dataOption" => $dataOption,
            );
            echo json_encode($json_data) ;
        }
    }

    public function getDetailsReturnIn(){
        $item_group_code = $this->input->post('matrialGroup');
        $do_no = $this->input->post('po_no');

		if ((!empty($item_group_code)) || (trim($item_group_code)!="")) {
			if($item_group_code == 'all') {
				$retin_details = $this->rIn_model->sap_retin_details_select_by_do_no($do_no);
				
			} else {
				$retin_details  = $this->rIn_model->sap_retin_details_select_by_do_and_item_group($do_no, $item_group_code);
            }

            $dt = array();
            $i = 1;
            foreach($retin_details as $key=>$value){
                $nestedData=array();
                $nestedData['NO'] = $i;
                $nestedData['VBELN'] = $value['VBELN'];
                $nestedData['DELIV_DATE'] = $value['DELIV_DATE'];
                $nestedData['ToWhsCode'] = $value['ToWhsCode'];
                $nestedData['POSNR'] = $value['POSNR'];
                $nestedData['DISPO'] = $value['DISPO'];
                $nestedData['MATNR'] = $value['MATNR'];
                $nestedData['MAKTX'] = $value['MAKTX'];
                $nestedData['LFIMG'] = number_format($value['LFIMG'],4,'.','');
                $nestedData['VRKME'] = $value['VRKME'];
                $nestedData['item'] = $value['item'];        
                $nestedData['U_grqty_web'] = number_format($value['U_grqty_web'],4,'.','');            
                $nestedData['RETURN_FROM'] = $value['RETURN_FROM'];    
                $nestedData['RETURN_FROM_NAME'] = $value['RETURN_FROM_NAME'];    
                $nestedData['PLANT'] = $value['PLANT'];    
                $nestedData['PLANT_NAME'] = $value['PLANT_NAME'];  
                $dt[] = $nestedData;
                $i++;
            }
            $json_data = array(
                "data" => $dt
            );
            echo json_encode($json_data) ;
		}
    }
    
    public function addData(){
        $admin_id = $this->session->userdata['ADMIN']['admin_id'];
        if($this->input->post("plant")!= ''){
            $strPlant = explode("-",$this->input->post("plant"));
            $plant = trim($strPlant[0]);
            $plant_name = trim($strPlant[1]);
        }else{
            $plant = '';
            $plant_name = '';
        }

        if($this->input->post("returnFrom")!= ''){
            $str = explode("-",$this->input->post('returnFrom'));
            $delivery_plant = trim($str[0]);
            $delivery_plant_name = trim($str[1]);
        }else{
            $delivery_plant = '';
            $delivery_plant_name = '';
        }

        if($this->input->post("storage_location")!= ''){
            $strStorage = explode("-",$this->input->post("storage_location"));
            $storage_location = trim($strStorage[0]);
            $storage_location_name = trim($strStorage[1]);
        }else{
            $storage_location = '';
            $storage_location_name = '';
        }

        $approve = $this->input->post('appr');

        $retin_header['do_no'] = $this->input->post('poNo');
        $retin_header['do_no1'] = $this->input->post('poNo1');
		$retin_header['posting_date'] = $this->l_general->str_to_date($this->input->post('posting_date'));
        $retin_header['item_group_code'] = $this->input->post('item_group_code');

        $retin_header['plant'] = $plant;
        $retin_header['plant_name'] = $plant_name;

        $retin_header['storage_location'] = $storage_location;
        $retin_header['storage_location_name'] = $storage_location_name;

        $retin_header['delivery_date'] = $this->l_general->str_to_date($this->input->post('delivery_date'));
        
        $retin_header['id_retin_plant'] = $this->rIn_model->id_retin_plant_new_select($retin_header['plant'],$retin_header['posting_date']);
        $retin_header['status'] = $approve == 2 ? $approve: '1';
        $retin_header['id_user_input'] = $admin_id;
        $retin_header['id_user_approved'] = $approve == 2 ? $admin_id : 0;
        $retin_header['id_user_cancel'] = 0;
        $retin_header['back'] = 1;
        $retin_header['to_plant'] = $this->input->post('toPlant');
        $retin_header['remark'] = $this->input->post('Remark');

        $retin_details['material_no'] = $this->input->post('detMatrialNo');
        $count = count($retin_details['material_no']);

        if($id_retin_header= $this->rIn_model->retin_header_insert($retin_header)){
            $input_detail_success = false;
            for($i =0; $i < $count; $i++){
                $retin_details = $this->rIn_model->sap_retin_details_select_by_do_and_item($retin_header['do_no'],$i);
                
                $retin_detail['id_retin_header'] = $id_retin_header;
                $retin_detail['id_retin_h_detail'] = $i+1;
                $retin_detail['item'] = $i;
                $retin_detail['material_no'] = $this->input->post('detMatrialNo')[$i];
                $retin_detail['material_desc'] = $this->input->post('detMatrialDesc')[$i];
                $retin_detail['outstanding_qty'] = $this->input->post('detOutStdQty')[$i];

                if ($retin_details['LFIMG'] >= $this->input->post('detQty')[$i]){
                    $retin_detail['outstanding_qty'] = $retin_details['LFIMG'] - $this->input->post('detQty')[$i];
                }else{
                    $retin_detail['outstanding_qty'] = $retin_details['LFIMG'];
                }

                $retin_detail['gr_quantity'] = $this->input->post('detQty')[$i];
                $retin_detail['uom'] = $this->input->post('detUom')[$i];
                $retin_detail['ok'] = 1;
                $retin_detail['ok_cancel'] = 0;
                $retin_detail['id_user_cancel'] = 0;

                $rem=$this->rIn_model->u_grqty_web($retin_header['do_no'],$i);
                
                $gr_qty1=$rem;
                $gr_qty = $gr_qty1 + $this->input->post('detQty')[$i];
                
                $outstanding =$retin_details['LFIMG'];
                
                if($approve == 2){
                    if ($outstanding = 0){ 
                        $this->rIn_model->updateOwtrStat(1, $retin_header['do_no']);
                    }
					$this->rIn_model->updateWtrGrQty($gr_qty, $retin_header['do_no'], $i);
                }
                
                if($this->rIn_model->retin_detail_insert($retin_detail))
                $input_detail_success = TRUE;
            }
            
        }

        if($input_detail_success){
            return $this->session->set_flashdata('success', "Retur In Telah Terbentuk");
        }else{
            return $this->session->set_flashdata('failed', "Retur In Gagal Terbentuk");
        }
    }

    public function edit(){
        $id_retin_header = $this->uri->segment(4);
        $object['data'] = $this->rIn_model->retin_header_select($id_retin_header);

        $object['retin_header']['id_retin_header'] = $id_retin_header;

		if($object['data']['status'] == '1'){
            $object['retin_header']['status_string'] = 'Not Approved';                              
        }else if($object['data']['status'] == '2'){
            $object['retin_header']['status_string'] = 'Approved';
        }else{
            $object['retin_header']['status_string'] = 'Cancel';
        }
        
        $ro = $object['data']['do_no'];
        $data_return_from= $this->rIn_model->getReturnFrom($ro);

        $object['retin_header']['plant'] = $object['data']['plant'].' - '.$object['data']['plant_name_new'];
        $object['retin_header']['do_no'] = $object['data']['do_no'];
        $object['retin_header']['do_no1'] = $object['data']['do_no1'];
        $object['retin_header']['transfer_in_number'] = $object['data']['retin_no'];
        $object['retin_header']['transfer_in_number1'] = $object['data']['retin_no1'];
		$object['retin_header']['storage_location'] = $object['data']['storage_location'].' - '.$object['data']['storage_location_name_new'];
        $object['retin_header']['delivery_date'] = $object['data']['delivery_date'];
        $object['retin_header']['posting_date'] = $object['data']['posting_date'];
        $object['retin_header']['item_group_code'] = $object['data']['item_group_code'];
        $object['retin_header']['status'] = $object['data']['status'];
        $object['retin_header']['remark'] = $object['data']['remark'];
        $object['retin_header']['return_from'] = $data_return_from[0]['plant'].' - '.$data_return_from[0]['OUTLET_NAME1'];

        $this->load->view('transaksi1/eksternal/returnin/edit_view', $object);
    }

    public function addDataUpdate(){
        $admin_id = $this->session->userdata['ADMIN']['admin_id'];
        $retin_header['id_retin_header'] = $this->input->post('idRetH');
        $retin_header['remark'] = $this->input->post('Remark');
        $retin_header['status'] = $this->input->post('appr') ? $this->input->post('appr') : '1';
        $retin_header['id_user_approved'] = $this->input->post('appr')? $admin_id : 0;
        $retin_header['posting_date'] = $this->l_general->str_to_date($this->input->post('posting_date'));
        
        $approve = $this->input->post('appr');
 
        if($this->rIn_model->retin_header_update($retin_header)){
            $update_detail_success = TRUE;
            $update_not_detail=TRUE;
        }

        if($update_detail_success || $update_not_detail){
            return $this->session->set_flashdata('success', "Retur In Inter Outlet Berhasil di Update");
        }else{
            return $this->session->set_flashdata('failed', "Retur In Inter Outlet Gagal di Update");
        }
    }

    public function showReturnInDetail(){
        $id_retin_header = $this->input->post('id');
        $stts = $this->input->post('status');
        $rs = $this->rIn_model->retin_details_select($id_retin_header);
        $dt = array();
        $i = 1;
       
        if($rs){
            foreach($rs as $key=>$value){
                $nestedData=array();
                $nestedData['id_retin_detail'] = $value['id_retin_detail'];
                $nestedData['no'] = $i;
                $nestedData['material_no'] = $value['material_no'];
				$nestedData['material_desc'] = $value['material_desc'];
				$nestedData['outstanding_qty'] = number_format($value['outstanding_qty'],4,'.','');
				$nestedData['gr_quantity'] = number_format($value['gr_quantity'],4,'.','');
                $nestedData['uom'] = $value['uom'];
                $dt[] = $nestedData;
                $i++;
            }
        }

        $json_data = array(
            "data" => $dt
        );
        echo json_encode($json_data);
    }
    
    public function cancelReturnIn(){
        $retin_header['id_retin_header'] = $this->input->post('id_retin_header');
        $retin_details = $this->input->post('deleteArr');

        if($this->rIn_model->cancelHeaderTranferIn($retin_header)){
            $succes_cancel_ri_from_vendor = false;
            for($i=0; $i<count($retin_details); $i++){
                if($this->rIn_model->cancelDetailsTransferIn($retin_details[$i]))
                $succes_cancel_ri_from_vendor = true;
            }
        }
        if($succes_cancel_ri_from_vendor){
            return $this->session->set_flashdata('success', "In PO From Vendor Berhasil di Cancel");
        }else{
            return $this->session->set_flashdata('failed', "In PO From Vendor Gagal di Cancel");
        }  
    }

    public function deleteData(){
        $id_retin_header = $this->input->post('deleteArr');
        $deleteData = false;
        foreach($id_retin_header as $id){
            if($this->rIn_model->t_retin_header_delete($id))
            $deleteData = true;
        }
        
        if($deleteData){
            return $this->session->set_flashdata('success', "Return In Berhasil dihapus");
        }else{
            return $this->session->set_flashdata('failed', "Return In Approved, Gagal dihapus");
        }
	}

    public function printpdf(){
		$id_retin_header = $this->uri->segment(4);
		$data['data'] = $this->rIn_model->tampil($id_retin_header);

		ob_start();
		$content = $this->load->view('transaksi1/eksternal/returnin/printpdf_view',$data);
		$content = ob_get_clean();		
        
        require_once(APPPATH.'libraries/html2pdf/html2pdf.class.php');
        
		try
		{
			$html2pdf = new HTML2PDF('P','A4','fr', false, 'ISO-8859-15',array(5, 0, 10, 0));
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

	public function excel(){
		$id_retin_header = $this->uri->segment(4);
		$data['data'] = $this->rIn_model->tampil($id_retin_header);
		
		ob_start();
	   	$this->load->view('transaksi1/eksternal/returnin/printexcel_view',$data);
	}
}
?>