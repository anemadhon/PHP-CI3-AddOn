<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Transferoutinteroutlet extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->library('auth');  
		if(!$this->auth->is_logged_in()) {
			redirect(base_url());
        }
        $this->load->library('form_validation');
        $this->load->library('l_general');
        
        // load model
        $this->load->model('transaksi1/transferout_model', 'tout_model');
    }

    public function index(){
        $this->load->view('transaksi1/eksternal/transferoutinteroutlet/list_view');
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

        $rs = $this->tout_model->t_gistonew_out_headers($date_from2, $date_to2, $status);
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
            $nestedData['id_gistonew_out_header'] = $val['id_gistonew_out_header'];
            $nestedData['gistonew_out_no'] = $val['gistonew_out_no'];
            $nestedData['gistonew_out_no1'] = $val['gistonew_out_no1'];
			$nestedData['po_no'] = $val['po_no'];
			$nestedData['po_no1'] = $val['po_no1'];
			$nestedData['posting_date'] = date("d-m-Y",strtotime($val['posting_date']));
            $nestedData['request_to'] = $val['to_plant'].' - '.$val['OUTLET_NAME1'];
            $nestedData['status'] = $status_string; 
            $nestedData['created_by'] = $val['user_input'];
            $nestedData['approved_by'] = $val['user_approved'];
            $nestedData['last_modified'] = date("d-m-Y",strtotime($val['lastmodified']));
            $nestedData['back'] = $val['back'] =='0'?'Integrated':'Not Integrated';;
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
        $data['do_nos'] = $this->tout_model->sap_do_select_all();
        $object['po_no']['-'] = '';
		if($data['do_nos'] !== FALSE) {
			$object['do_no'][0] = '';
			foreach ($data['do_nos'] as $do_no) {
				$object['do_no'][$do_no['VBELN']] = $do_no['VBELN1'].' - '.$do_no['ABC'];
			}
        }
        
        $object['plant'] = $this->session->userdata['ADMIN']['plant'].' - '.$this->session->userdata['ADMIN']['plant_name'];
        $object['storage_location'] = $this->session->userdata['ADMIN']['storage_location'].' - '.$this->session->userdata['ADMIN']['storage_location_name'];
    
        $this->load->view('transaksi1/eksternal/transferoutinteroutlet/add_new', $object);
	}

	public function getHeaderTransferOut(){
		$srNumber = $this->input->post('srNumberHeader');
		$data = $this->tout_model->sap_gistonew_out_header_select_by_do_no($srNumber);
		$dataOption = $this->tout_model->sap_gistonew_out_select_item_group_do($srNumber);
		
		if (count($data) > 0) {

            $json_data = array(
                "data" => $data,
                "dataOption" => $dataOption
            );
            echo json_encode($json_data) ;
        }
	}
	
	public function getDetailsTransferOut(){
        $item_group_code = $this->input->post('cboMatrialGroup');
        
		$do_no = $this->input->post('doNo');

		if ((!empty($item_group_code)) || (trim($item_group_code)!="")) {
			if($item_group_code == 'all') {
				$gistonew_out_details = $this->tout_model->sap_gistonew_out_details_select_by_do_no($do_no);
			} else {
				$gistonew_out_details= $this->tout_model->sap_gistonew_out_details_select_by_do_and_item_group($do_no, $item_group_code);
            }

            $dt = array();
            $i=1;
            foreach($gistonew_out_details as $key=>$value){
                $inWhsQty = $this->tout_model->getDataMaterialGroupSelect($value['VBELN'],$value['MATNR']);
                $nestedData=array();
                $nestedData['NO'] = $i;
                $nestedData['MATNR'] = $value['MATNR'];
                $nestedData['MAKTX'] = $value['MAKTX'];
                $nestedData['inWhsQty'] = $inWhsQty[1]["In_Whs_Qty"]!='.000000' ? number_format($inWhsQty[1]["In_Whs_Qty"],4,'.','') : '0.0000' ;
                $nestedData['LFIMG'] = number_format($value["LFIMG"],4,'.','');
                $nestedData['GRQUANTITY'] = '';
                $nestedData['UOM_REG'] = $value['VRKME'];
                $nestedData['UOM'] = $value['VRKME'];
                $nestedData['item'] = $value['PONSR'];
                $dt[] = $nestedData;
                $i++;
            }
            
            $json_data = array(
                "data" => $dt
            );
            echo json_encode($json_data);
		}
    }
    
    public function getDetailsTransferOutEdit(){
        $item_group_code = $this->input->post('cboMatrialGroup');
        
		$do_no = $this->input->post('doNo');

		if ((!empty($item_group_code)) || (trim($item_group_code)!="")) {
			if($item_group_code == 'all') {
				$gistonew_out_details = $this->tout_model->sap_gistonew_out_details_select_by_do_no($do_no);
				
			} else {
				$gistonew_out_details= $this->tout_model->sap_gistonew_out_details_select_by_do_and_item_group($do_no, $item_group_code);
            }
			
			echo json_encode($gistonew_out_details);
		}
	}

	function getdataDetailMaterialSelect(){
		$itemSelect = $this->input->post('MATNR');
		$po_no = $this->input->post('do_no');
        
        $dataMatrialSelect = $this->tout_model->getDataMaterialGroupSelect($po_no, $itemSelect);

        echo json_encode($dataMatrialSelect) ;
        
    }

	public function addData(){
        $plant = $this->session->userdata['ADMIN']['plant'];
        $storage_location = $this->session->userdata['ADMIN']['storage_location'];
        $plant_name = $this->session->userdata['ADMIN']['plant_name'];
        $storage_location_name = $this->session->userdata['ADMIN']['storage_location_name'];
        $admin_id = $this->session->userdata['ADMIN']['admin_id'];

        if($this->input->post("Rto")!= ''){
            $strPlant = explode("-",$this->input->post("Rto"));
            $receiving_plant = trim($strPlant[0]);
            $receiving_plant_name = trim($strPlant[1]);
        }else{
            $receiving_plant = '';
            $receiving_plant_name = '';
        }

		$gistonew_out_header['po_no'] = $this->input->post('reqRes');
		$gistonew_out_header['po_no1'] = $this->input->post('reqRes1');
		$gistonew_out_header['posting_date'] = $this->l_general->str_to_date($this->input->post('pstDate'));
        $gistonew_out_header['item_group_code'] = $this->input->post('matGrp');
        $gistonew_out_header['status'] = $this->input->post('stss');
        $gistonew_out_header['remark'] = $this->input->post('Remark');
        $gistonew_out_header['receiving_plant'] = $receiving_plant;
        $gistonew_out_header['receiving_plant_name'] = $receiving_plant_name;
        $gistonew_out_header['plant'] = $plant;
        $gistonew_out_header['plant_name'] = $plant_name;
        $gistonew_out_header['storage_location'] = $storage_location;
        $gistonew_out_header['id_gistonew_out_plant'] = $this->tout_model->id_stdstock_plant_new_select($gistonew_out_header['plant'],$gistonew_out_header['posting_date']);
        $gistonew_out_header['status'] = $this->input->post('appr')? $this->input->post('appr') : '1';

        $gistonew_out_header['id_user_input'] = $admin_id;
        $gistonew_out_header['id_user_approved'] = $this->input->post('appr')? $admin_id : '0';
        $gistonew_out_header['to_plant'] = $receiving_plant;
        $gistonew_out_header['back'] = 1;

        $gistonew_out_details['material_no'] = $this->input->post('detMatrialNo');
        $count = count($gistonew_out_details['material_no']);

        $base = $gistonew_out_header['po_no'];

        if($id_gistonew_out_header= $this->tout_model->gistonew_out_header_insert($gistonew_out_header)){
            $input_detail_success = false;
            for($i =0; $i < $count; $i++){
                $gistonew_out_detail['id_gistonew_out_header'] = $id_gistonew_out_header;
				$gistonew_out_detail['id_gistonew_out_h_detail'] = $i+1;
                $gistonew_out_detail['material_no'] = $this->input->post('detMatrialNo')[$i];
                $gistonew_out_detail['material_desc'] = $this->input->post('detMatrialDesc')[$i];
                $gistonew_out_detail['whs_qty'] = $this->input->post('detWhsQty')[$i];
                $gistonew_out_detail['outstanding_qty'] = $this->input->post('detOutStdQty')[$i];
                $gistonew_out_detail['gr_quantity'] = $this->input->post('detQty')[$i];
				$gistonew_out_detail['uom'] = $this->input->post('detUom')[$i];
                $gistonew_out_detail['uom_req'] = $this->input->post('detUomReg')[$i];
                $gistonew_out_detail['posnr'] = $i;

                $line = $gistonew_out_detail['posnr'];

                $rem = $this->tout_model->U_grqty_web($base,$line);
                $gr_qty1=$rem['U_grqty_web'];
                $gr_qty = $gr_qty1 + $gistonew_out_detail['gr_quantity'];

                $LFIMG = $this->tout_model->sap_do_select_all('',$gistonew_out_header['po_no'],$gistonew_out_detail['material_no']);
                $outstanding = $LFIMG[1]['LFIMG'];

                if($this->tout_model->gistonew_out_detail_insert($gistonew_out_detail))
                $input_detail_success = TRUE;

                if( $input_detail_success = TRUE){
                    if($this->input->post('appr') == 2){
                        if ($outstanding = 0){
                            $this->tout_model->updateOWTQ($base);
                        }
                        $this->tout_model->updateWTQ1($gr_qty, $base, $line);
                    }
                }
            }
        }

        if($input_detail_success){
            return $this->session->set_flashdata('success', "Transfer Out Inter Outlet Telah Terbentuk");
        }else{
            return $this->session->set_flashdata('failed', "Transfer Out Inter Outlet Gagal Terbentuk");
        }
    }
	
	public function edit(){
		$id_gistonew_out_header = $this->uri->segment(4);
		$object['data'] = $this->tout_model->gistonew_out_header_select($id_gistonew_out_header);

		$object['gistonew_out_header']['id_gistonew_out_header'] = $id_gistonew_out_header;

		if($object['data']['status'] == '1'){
            $object['gistonew_out_header']['status_string'] = 'Not Approved';                              
        }else if($object['data']['status'] == '2'){
            $object['gistonew_out_header']['status_string'] = 'Approved';
        }else{
            $object['gistonew_out_header']['status_string'] = 'Cancel';
		}
		
        $object['gistonew_out_header']['plant'] = $object['data']['plant'];
        $object['gistonew_out_header']['plant_str'] = $object['data']['plant'].' - '.$object['data']['PLANTS_NAME'];
		$object['gistonew_out_header']['po_no'] = $object['data']['po_no'];
		$object['gistonew_out_header']['po_no1'] = $object['data']['po_no1'];
		$object['gistonew_out_header']['transfer_slip_number'] = $object['data']['gistonew_out_no'];
		$object['gistonew_out_header']['transfer_slip_number1'] = $object['data']['gistonew_out_no1'];
        $object['gistonew_out_header']['storage_location'] = $object['data']['storage_location'];
        $object['gistonew_out_header']['storage_location_str'] = $object['data']['storage_location'].' - '.$object['data']['STORAGE_LOCATION_NAME'];
		$object['gistonew_out_header']['to_plant'] = $object['data']['to_plant'].' - '.$object['data']['STOR_LOC_NAME'];
        $object['gistonew_out_header']['posting_date'] = $object['data']['posting_date'];
        $object['gistonew_out_header']['item_group_code'] = $object['data']['item_group_code'] ? $object['data']['item_group_code'] : 'all';
        $object['gistonew_out_header']['status'] = $object['data']['status'];
        $object['gistonew_out_header']['remark'] = $object['data']['remark'];

        $this->load->view('transaksi1/eksternal/transferoutinteroutlet/edit_view', $object);
    }
    
    public function addDataUpdate(){
        $admin_id = $this->session->userdata['ADMIN']['admin_id'];
        $gistonew_out_header['id_gistonew_out_header'] = $this->input->post('idGistonew_out_header');
        $gistonew_out_header['status'] = $this->input->post('aapr') ? $this->input->post('aapr') : '1';
        $gistonew_out_header['po_no'] = $this->input->post('poNo');
        $gistonew_out_header['po_no1'] = $this->input->post('poNo1');
        $gistonew_out_header['remark'] = $this->input->post('Remark');
        $gistonew_out_header['id_user_approved'] = $this->input->post('aapr')? $admin_id : 0;
        
        $base = $gistonew_out_header['po_no'];
        $count = count($this->input->post('detMatrialNo'));
        if($this->tout_model->gistonew_out_header_update($gistonew_out_header)){
            $update_detail_success = false;
            if($this->tout_model->gistonew_out_details_delete($gistonew_out_header['id_gistonew_out_header'])){
                for($i =0; $i < $count; $i++){
                    $gistonew_out_details['id_gistonew_out_header'] = $gistonew_out_header['id_gistonew_out_header'];
                    $gistonew_out_details['id_gistonew_out_h_detail'] = $i+1;
                    $gistonew_out_details['material_no'] = $this->input->post('detMatrialNo')[$i];
                    $gistonew_out_details['material_desc'] = $this->input->post('detMatrialDesc')[$i];
                    $gistonew_out_details['whs_qty'] = $this->input->post('detWhsQty')[$i];
                    $gistonew_out_details['outstanding_qty'] = $this->input->post('detOutQty')[$i];
                    $gistonew_out_details['gr_quantity'] = $this->input->post('detQty')[$i];
                    $gistonew_out_details['uom'] = $this->input->post('detUom')[$i];
                    $gistonew_out_details['uom_req'] = $this->input->post('detUomReg')[$i];
                    $gistonew_out_details['posnr'] = $i;

                    $line = $gistonew_out_detail['posnr'];
                    $rem = $this->tout_model->U_grqty_web($base,$line);
                    $gr_qty1=$rem['U_grqty_web'];
                    $gr_qty = $gr_qty1 + $gistonew_out_details['gr_quantity'];
    
                    $LFIMG = $this->tout_model->sap_do_select_all('',$gistonew_out_header['po_no'],$gistonew_out_details['material_no']);
                    $outstanding = $LFIMG[1]['LFIMG'];

                    if($this->input->post('appr')){
                        if ($outstanding = 0){
                            $this->tout_model->updateOWTQ($base);
                        }
                        $this->tout_model->updateWTQ1($gr_qty, $base, $line);
                    }
                
                    if($this->tout_model->gistonew_out_detail_insert($gistonew_out_details))
                    $update_detail_success = TRUE;
                }
            }
        }

        if($update_detail_success){
            return $this->session->set_flashdata('success', "Transfer Out Inter Outlet Berhasil di Update");
        }else{
            return $this->session->set_flashdata('failed', "Transfer Out Inter Outlet Gagal di Update");
        }
    }

	public function showGistonewOutDetail(){
        $plant = $this->session->userdata['ADMIN']['plant'];
        $id_gistonew_out_header = $this->input->post('id');
        $stts = $this->input->post('status');
        $rs = $this->tout_model->stdstock_details_select($id_gistonew_out_header);
        $dt = array();
        $i = 1;
        if($rs){
            foreach($rs as $key=>$value){
                $inwhs = $this->tout_model->in_whs_qty($plant,$value['material_no']);
                $nestedData=array();
                $nestedData['id_gistonew_out_detail'] = $value['id_gistonew_out_detail'];
                $nestedData['no'] = $i;
                $nestedData['material_no'] = $value['material_no'];
				$nestedData['material_desc'] = $value['material_desc'];
				$nestedData['in_whs_qty'] = ($stts==1 ? ($inwhs[0]['OnHand']!='.0000' ? number_format($inwhs[0]['OnHand'],4,'.','') : '0.0000') : ($value['whs_qty'] ? number_format($value['whs_qty'],4,'.','') : '0.0000'));
				$nestedData['outstanding_qty'] = $value['outstanding_qty'];
				$nestedData['gr_quantity'] = $value['gr_quantity'];
                $nestedData['uom'] = $value['uom'];
                $nestedData['uom_req'] = $value['uom_req'];
                $nestedData['posnr'] = $value['posnr'];
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
        $gistonew_out_header['id_gistonew_out_header'] = $this->input->post('id_gistonew_out_header');
        $gistonew_out_details = $this->input->post('deleteArr');

        if($this->tout_model->cancelHeaderPoFromVendor($gistonew_out_header)){
            $succes_cancel_po_from_vendor = false;
            for($i=0; $i<count($gistonew_out_details); $i++){
                if($this->tout_model->cancelDetailsPoFromVendor($gistonew_out_details[$i]))
                $succes_cancel_po_from_vendor = true;
            }
        }
        if($succes_cancel_po_from_vendor){
            return $this->session->set_flashdata('success', "Transfer Out Inter Outlet Berhasil di Cancel");
        }else{
            return $this->session->set_flashdata('failed', "Transfer Out Inter Outlet Gagal di Cancel");
        }  
    }
	
	public function deleteData(){
        $id_gistonew_out_header = $this->input->post('deleteArr');
        $deleteData = false;
        foreach($id_gistonew_out_header as $id){
            $cek = $this->tout_model->t_gistonew_out_header_delete($id);
            if($cek){
                $deleteData = true;
            }else{
                $deleteData = false;
            }
        }
        
        if($deleteData){
            return $this->session->set_flashdata('success', "Transfer Out Inter Outlet Berhasil dihapus");
        }else{
            return $this->session->set_flashdata('failed', "Transfer Out Inter Outlet Gagal dihapus");
        }
	}
	
	public function printpdf(){
		$id_gistonew_out_header = $this->uri->segment(4);
		$data['data'] = $this->tout_model->tampil($id_gistonew_out_header);
		
		ob_start();
		$content = $this->load->view('transaksi1/eksternal/transferoutinteroutlet/printpdf_view',$data);
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
		$id_gistonew_out_header = $this->uri->segment(4);
		$data['data'] = $this->tout_model->tampil($id_gistonew_out_header);
		
		ob_start();
	   	$this->load->view('transaksi1/eksternal/transferoutinteroutlet/printexcel_view',$data);
	}
}
?>