<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Transferininteroutlet extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->library('auth');  
		if(!$this->auth->is_logged_in()) {
			redirect(base_url());
        }
        $this->load->library('form_validation');
        $this->load->library('l_general');
        
        // load model
        $this->load->model('transaksi1/transferIn_model', 'tIn_model');
    }

    public function index(){
        $this->load->view('transaksi1/eksternal/transferininteroutlet/list_view');
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

        $rs = $this->tIn_model->t_grsto_headers($date_from2, $date_to2, $status);
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

            $nestedData = array();
            $nestedData['id_grsto_header'] = $val['id_grsto_header'];
            $nestedData['grsto_no'] = $val['grsto_no'];
            $nestedData['grsto_no1'] = $val['grsto_no1'];
            $nestedData['po_no'] = $val['po_no'];
            $nestedData['po_no1'] = $val['po_no1'];
            $nestedData['no_doc_gist'] = $val['no_doc_gist'];
            $nestedData['no_doc_gist1'] = $val['no_doc_gist1'];
            $nestedData['delivery_outlet'] = $val['delivery_plant']." - ".$val['delivery_plant_name'];
            $nestedData['delivery_date'] = date("d-m-Y",strtotime($val['delivery_date']));
			$nestedData['posting_date'] = date("d-m-Y",strtotime($val['posting_date']));
            $nestedData['status'] = $status_string; 
            $nestedData['created_by'] = $val['user_input'];
            $nestedData['approved_by'] = $val['user_approved'];
            $nestedData['last_modified'] = date("d-m-Y",strtotime($val['lastmodified']));
            $nestedData['back'] = (($val['back'] =='1') ? 'Not Integrated':'Integrated');
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
        $data['do_nos'] = $this->tIn_model->sap_do_select_all();

        $object['po_no']['-'] = '';
		if($data['do_nos'] !== FALSE) {
			$object['do_no'][0] = '';
			foreach ($data['do_nos'] as $do_no) {
				$object['do_no'][$do_no['EBELN'].'_'.$do_no['MBLNR']] = $do_no['EBELN1'].' ('.$do_no['MBLNR1'].' ) - '.$do_no['SPLANT_NAME'];
			}
        }
        $object['plant'] = $this->session->userdata['ADMIN']['plant'].' - '.$this->session->userdata['ADMIN']['plant_name'];
        $object['storage_location'] = $this->session->userdata['ADMIN']['storage_location'].' - '.$this->session->userdata['ADMIN']['storage_location_name'];

        $this->load->view('transaksi1/eksternal/transferininteroutlet/add_new', $object);
	}

	public function getHeaderTransferIn(){
		$srNumber = $this->input->post('srNumberHeader');
		$data = $this->tIn_model->sap_grsto_header_select_by_do_no($srNumber);
        $dataOption = $this->tIn_model->sap_gistonew_out_select_item_group_do($srNumber);
		
		if (count($data) > 0) {

            $json_data = array(
                "data" => $data,
                "dataOption" => $dataOption
            );
            echo json_encode($json_data) ;
        }
	}
	
	public function getDetailsTransferIn(){
        $item_group_code = $this->input->post('matrialGroup');
        $do_no = $this->input->post('po_no');

		if ((!empty($item_group_code)) || (trim($item_group_code)!="")) {
			if($item_group_code == 'all') {
				$grsto_details = $this->tIn_model->sap_grsto_details_select_by_do_no($do_no);
				
			} else {
				$grsto_details  = $this->tIn_model->sap_grsto_details_select_by_do_and_item_group($do_no, $item_group_code);
            }

            $dt = array();
            $i = 1;
            foreach($grsto_details as $key=>$value){
                
                $srQty = $this->tIn_model->getQtySR($value['EBELN'],$value['MATNR'],$value['receiving_plant']);
                $srQuantity = 0;
                if($srQty){
                    $srQuantity = $srQty[0]["requirement_qty"];
                }
                $nestedData=array();
                $nestedData['NO'] = $i;
                $nestedData['MATNR'] = $value['MATNR'];
                $nestedData['MAKTX'] = $value['MAKTX'];
                $nestedData['SRQUANTITY'] = $srQuantity;
                $nestedData['TFQUANTITY'] = $value["TFQUANTITY"] != '.000000' ? number_format($value["TFQUANTITY"],4,'.','') : '0.0000';
                $nestedData['GRQUANTITY'] = '';
                $nestedData['UOM'] = $value['BSTME'];
                $nestedData['Item'] = $value['EBELP'];
                $dt[] = $nestedData;
                $i++;
            }
            $json_data = array(
                "data" => $dt
            );
            echo json_encode($json_data);
		}
	}

	function getdataDetailMaterialSelect(){
		$itemSelect = $this->input->post('MATNR');
		$po_no = $this->input->post('do_no');
        
        $dataMatrialSelect = $this->tIn_model->getDataMaterialGroupSelect($po_no, $itemSelect);
		
        echo json_encode($dataMatrialSelect);
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

        if($this->input->post("Rto")!= ''){
            $str = explode("-",$this->input->post('Rto'));
            $delivery_plant = trim($str[0]);
            $delivery_plant_name = trim($str[1]);
        }else{
            $delivery_plant = '';
            $delivery_plant_name = '';
        }

        if($this->input->post("storageLoc")!= ''){
            $strStorage = explode("-",$this->input->post("storageLoc"));
            $storage_location = trim($strStorage[0]);
            $storage_location_name = trim($strStorage[1]);
        }else{
            $storage_location = '';
            $storage_location_name = '';
        }

        $approve = $this->input->post('appr');

        $grsto_header['po_no'] = $this->input->post('reqRes');
        $grsto_header['po_no1'] = $this->input->post('reqRes1');
        $grsto_header['no_doc_gist'] = $this->input->post('toNumb');
        $grsto_header['no_doc_gist1'] = $this->input->post('toNumb1');
		$grsto_header['posting_date'] = $this->l_general->str_to_date($this->input->post('pstDate'));
        $grsto_header['item_group_code'] = $this->input->post('matGrp');
        $grsto_header['status'] = $this->input->post('stss');

        $grsto_header['plant'] = $plant;
        $grsto_header['plant_name'] = $plant_name;

        $grsto_header['storage_location'] = $storage_location;
        $grsto_header['storage_location_name'] = $storage_location_name;

        $grsto_header['delivery_plant'] = $delivery_plant;
        $grsto_header['delivery_plant_name'] = $delivery_plant_name;

        $grsto_header['delivery_date'] = $this->l_general->str_to_date($this->input->post('delivDate'));
        
        $grsto_header['id_grsto_plant'] = $this->tIn_model->id_stdstock_plant_new_select($grsto_header['plant'],$grsto_header['posting_date']);
        $grsto_header['status'] = $approve == 2 ? $approve: '1';
        $grsto_header['id_user_input'] = $admin_id;
        $grsto_header['id_user_approved'] = $approve == 2 ? $admin_id : 0;
        $grsto_header['back'] = 1;

        $grsto_details['material_no'] = $this->input->post('detMatrialNo');
        $count = count($grsto_details['material_no']);

        if($id_grsto_header= $this->tIn_model->grsto_header_insert($grsto_header)){
            $input_detail_success = false;
            for($i = 0; $i < $count; $i++){

                $grsto_detail['id_grsto_header'] = $id_grsto_header;
                $grsto_detail['id_grsto_h_detail'] = $i+1;
                $grsto_detail['item'] = $this->input->post('detposnr')[$i];
                $grsto_detail['material_no'] = $this->input->post('detMatrialNo')[$i];
                $grsto_detail['material_desc'] = $this->input->post('detMatrialDesc')[$i];
                $grsto_detail['sr_qty'] = $this->input->post('detsrQty')[$i];
                $grsto_detail['outstanding_qty'] = $this->input->post('detOutStdQty')[$i];
                $grsto_detail['gr_quantity'] = $this->input->post('detQty')[$i];
                $grsto_detail['uom'] = $this->input->post('detUom')[$i];
                $grsto_detail['ok'] = 1;
                $grsto_detail['ok_cancel'] = 0;
                $grsto_detail['val'] = 0;
                $grsto_detail['var'] = ($this->input->post('detOutStdQty')[$i]-$this->input->post('detQty')[$i]);

                if($approve == 2){
                    $cekQty = $this->tIn_model->cekQty($grsto_header['po_no'],$grsto_detail['material_no']);
                    $gistonew_out_header['po_no'] = $grsto_header['po_no'];
                    $gistonew_out_header['material_no'] = $grsto_detail['material_no'];
                    $gistonew_out_header['receipt'] = $cekQty[0]['receipt'] + $grsto_detail['gr_quantity'];
                    $gistonew_out_header['var'] = $grsto_detail['var'];
                    $this->tIn_model->update_grstonew_out_detail($gistonew_out_header);
                }
                
                if($this->tIn_model->grsto_detail_insert($grsto_detail))
                $input_detail_success = TRUE;
            }
            
        }

        if($input_detail_success){
            return $this->session->set_flashdata('success', "Transfer In Inter Outlet Telah Terbentuk");
        }else{
            return $this->session->set_flashdata('failed', "Transfer In Inter Outlet Gagal Terbentuk");
        }
    }
	
	public function edit(){
		$id_grsto_header = $this->uri->segment(4);
		$object['data'] = $this->tIn_model->grsto_header_select($id_grsto_header);

		$object['grsto_header']['id_grsto_header'] = $id_grsto_header;

		if($object['data']['status'] == '1'){
            $object['grsto_header']['status_string'] = 'Not Approved';                              
        }else if($object['data']['status'] == '2'){
            $object['grsto_header']['status_string'] = 'Approved';
        }else{
            $object['grsto_header']['status_string'] = 'Cancel';
		}
		
		$object['grsto_header']['plant'] = $object['data']['plant'].' - '.$object['data']['plant_name_new'];
        $object['grsto_header']['po_no'] = $object['data']['po_no'];
        $object['grsto_header']['po_no1'] = $object['data']['po_no1'];
        $object['grsto_header']['transfer_out_number'] = $object['data']['no_doc_gist'];
        $object['grsto_header']['transfer_out_number1'] = $object['data']['no_doc_gist1'];
		$object['grsto_header']['transfer_in_number'] = $object['data']['grsto_no'];
		$object['grsto_header']['transfer_in_number1'] = $object['data']['grsto_no1'];
		$object['grsto_header']['storage_location'] = $object['data']['storage_location'].' - '.$object['data']['storage_location_name_new'];
        $object['grsto_header']['to_plant'] = $object['data']['delivery_plant'].' - '.$object['data']['delivery_plant_name_new'];
        $object['grsto_header']['delivery_date'] = $object['data']['delivery_date'];
        $object['grsto_header']['posting_date'] = $object['data']['posting_date'];
        $object['grsto_header']['item_group_code'] = $object['data']['item_group_code'];
        $object['grsto_header']['status'] = $object['data']['status'];

        $this->load->view('transaksi1/eksternal/transferininteroutlet/edit_view', $object);
    }
    
    public function addDataUpdate(){
        $admin_id = $this->session->userdata['ADMIN']['admin_id'];

        $grsto_header['id_grsto_header'] = $this->input->post('idgrsto_header');
        $grsto_header['po_no'] = $this->input->post('poNo');
        $grsto_header['po_no1'] = $this->input->post('poNo1');
        $grsto_header['status'] = $this->input->post('appr') ? $this->input->post('appr') : '1';
        $grsto_header['posting_date'] = $this->l_general->str_to_date($this->input->post('pstDate'));
        $grsto_header['id_user_approved'] = $this->input->post('appr') == 2 ? $admin_id : 0;

        $approve = $this->input->post('appr');
       
        $count = count($this->input->post('detMatrialNo'));
 
        $update_not_detail=false;
        if($this->tIn_model->grsto_header_update($grsto_header)){
            $update_detail_success = false;
            if($this->tIn_model->grsto_details_delete($grsto_header['id_grsto_header'])){
                if($count > 0){
                    for($i = 0; $i < $count; $i++){
                        $grsto_details['id_grsto_header'] = $grsto_header['id_grsto_header'];
                        $grsto_details['id_grsto_h_detail'] = $i+1;
                        $grsto_details['item'] = $i;
                        $grsto_details['material_no'] = $this->input->post('detMatrialNo')[$i];
                        $grsto_details['material_desc'] = $this->input->post('detMatrialDesc')[$i];
                        $grsto_details['sr_qty'] = $this->input->post('detSrQty')[$i];
                        $grsto_details['outstanding_qty'] = $this->input->post('detTftQty')[$i];
                        $grsto_details['gr_quantity'] = $this->input->post('detGrQty')[$i];
                        $grsto_details['uom'] = $this->input->post('detUom')[$i];
                        $grsto_details['ok'] = 1;
                        $grsto_details['ok_cancel'] = 0;
                        $grsto_details['var'] = ((int)$this->input->post('detTftQty')[$i] - (int)$this->input->post('detGrQty')[$i]);

                        if($approve == 2){
                            $cekQty = $this->tIn_model->cekQty($grsto_header['po_no'],$grsto_details['material_no']);
                            $gistonew_out_header['po_no'] = $grsto_header['po_no'];
                            $gistonew_out_header['material_no'] = $grsto_details['material_no'];
                            $gistonew_out_header['receipt'] = $cekQty[0]['receipt'] + $grsto_details['gr_quantity'];
                            $gistonew_out_header['var'] = $grsto_details['var'];
                            $this->tIn_model->update_grstonew_out_detail($gistonew_out_header); 
                        }

                        if($this->tIn_model->grsto_detail_insert($grsto_details))
                        $update_detail_success = TRUE;
                    }
                }
                $update_not_detail=TRUE;
            }
        }

        if($update_detail_success || $update_not_detail){
            return $this->session->set_flashdata('success', "Transfer In Inter Outlet Berhasil di Update");
        }else{
            return $this->session->set_flashdata('failed', "Transfer In Inter Outlet Gagal di Update");
        }
    }

	public function showGistonewOutDetail(){
        $id_grsto_header = $this->input->post('id');
        $stts = $this->input->post('status');
        $rs = $this->tIn_model->stdstock_details_select($id_grsto_header);
        $dt = array();
        $i = 1;
        if($rs){
            foreach($rs as $key=>$value){
                $nestedData=array();
                $nestedData['id_grsto_detail'] = $value['id_grsto_detail'];
                $nestedData['no'] = $i;
                $nestedData['material_no'] = $value['material_no'];
				$nestedData['material_desc'] = $value['material_desc'];
				$nestedData['in_whs_qty'] = $value['sr_qty']!= '.000000' ? number_format($value['sr_qty'],4,'.','') : '0.0000';
				$nestedData['outstanding_qty'] = $value['outstanding_qty']!= '.000000' ? number_format($value['outstanding_qty'],4,'.','') : '0.0000';
				$nestedData['gr_quantity'] = number_format($value['gr_quantity'],4,'.','');
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
	
	public function cancelGistonewOut(){
        $grsto_header['id_grsto_header'] = $this->input->post('id_grsto_header');
        $grsto_details = $this->input->post('deleteArr');

        if($this->tIn_model->cancelHeaderPoFromVendor($grsto_header)){
            $succes_cancel_po_from_vendor = false;
            for($i=0; $i<count($grsto_details); $i++){
                if($this->tIn_model->cancelDetailsPoFromVendor($grsto_details[$i]))
                $succes_cancel_po_from_vendor = true;
            }
        }
        if($succes_cancel_po_from_vendor){
            return $this->session->set_flashdata('success', "Transfer In Inter Outlet Berhasil di Cancel");
        }else{
            return $this->session->set_flashdata('failed', "Transfer In Inter Outlet Gagal di Cancel");
        }  
    }
	
	public function deleteData(){
        $id_grsto_header = $this->input->post('deleteArr');
        $deleteData = false;
        foreach($id_grsto_header as $id){
            $cek = $this->tIn_model->t_grsto_header_delete($id);
            if($cek){
                $deleteData = true;
            }else{
                $deleteData = false;
            }
        }
        
        if($deleteData){
            return $this->session->set_flashdata('success', "Transfer In Inter Outlet Berhasil dihapus");
        }else{
            return $this->session->set_flashdata('failed', "Transfer In Inter Outlet Gagal dihapus");
        }
	}
	
	public function printpdf(){
		$id_grsto_header = $this->uri->segment(4);
		$data['data'] = $this->tIn_model->tampil($id_grsto_header);
		
		ob_start();
		$content = $this->load->view('transaksi1/eksternal/transferininteroutlet/printpdf_view',$data);
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

	public function excel(){
		$id_grsto_header = $this->uri->segment(4);
		$data['data'] = $this->tIn_model->tampil($id_grsto_header);
		
		ob_start();
	   	$this->load->view('transaksi1/eksternal/transferininteroutlet/printexcel_view',$data);	
	}
}
?>