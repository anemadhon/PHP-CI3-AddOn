<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Returnout extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->library('auth');  
		if(!$this->auth->is_logged_in()) {
			redirect(base_url());
        }

        // load model
        $this->load->library('form_validation');
        $this->load->library('l_general');

        $this->load->model('transaksi1/returnOut_model', 'retOut_model');
    }

    public function index(){
        $this->load->view('transaksi1/eksternal/returnout/list_view');
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

        $rs = $this->retOut_model->getDataReturnOut_Header($date_from2, $date_to2, $status);
        $data = array();
        
        $status_string='';
        $log = '';

        foreach($rs as $key=>$val){
			if($val['status'] =='1'){
				$status_string= 'Not Approved';
			}else if($val['status'] =='2'){
				$status_string= 'Approved';
			}else{
				$status_string= 'Cancel';
            }
            
            if ($val['back'] == 0 && $val['gisto_dept_no'] !='' && $val['gisto_dept_no'] !='C')
            {
                $log = "Integrated";
            }else if ($val['back'] == 1 && ($val['gisto_dept_no'] =='' || $val['gisto_dept_no'] =='C'))
            {
                $log = "Not Integrated";
            }else if ($val['back'] == 0 &&  $val['gisto_dept_no'] =='C')
            {
                $log ="Close Document";
            }

            $nestedData = array();
            $nestedData['id_gisto_dept_header'] = $val['id_gisto_dept_header'];
            $nestedData['gisto_dept_no'] = $val['gisto_dept_no'];
            $nestedData['gisto_dept_no1'] = $val['gisto_dept_no1'];
            $nestedData['posting_date'] = date("d-m-Y",strtotime($val['posting_date']));
            $nestedData['receiving_plant'] = $val['receiving_plant'].' - '.$val['receiving_plant_name'];
            $nestedData['status'] = $val['status'];
            $nestedData['status_string'] = $status_string; 
            $nestedData['user_input'] = $val['user_input'];
            $nestedData['user_approved'] = $val['user_approved'];
            $nestedData['lastmodified'] = $val['lastmodified'];
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
        $object['plants'] = $this->retOut_model->showOutlet();
        $object['matrialGroup'] = $this->retOut_model->showMatrialGroup();
		$object['plant'] = $this->session->userdata['ADMIN']['plant'].' - '.$this->session->userdata['ADMIN']['plant_name'];
    	$object['storage_location'] = $this->session->userdata['ADMIN']['storage_location'].' - '.$this->session->userdata['ADMIN']['storage_location_name'];
    
        $this->load->view('transaksi1/eksternal/returnout/add_view', $object);
     }

    function getdataDetailMaterial(){
        $item_group_code = $this->input->post('matGroup');
    
        $data = $this->retOut_model->sap_items_select_by_item_group($item_group_code);
        
        echo json_encode($data);
    }
    
    function getdataDetailMaterialSelect(){
        $item_group_code = $this->input->post('DSNAM');
        $itemSelect = $this->input->post('MATNR');
        $reqPlant = $this->input->post('RTO');
        
        $dataMatrialSelect = $this->retOut_model->sap_items_select_by_item_group($item_group_code, $itemSelect);
        $dataInWhsQty = $this->retOut_model->getDataInWhsQtyCommited($itemSelect,$reqPlant);

        $json_data = array(
            "data"              => $dataMatrialSelect,
            "dataInWhsQty"      => $dataInWhsQty
        );

        echo json_encode($json_data);
    }

    function addData(){
        $plant = $this->session->userdata['ADMIN']['plant'];
        $storage_location = $this->session->userdata['ADMIN']['storage_location'];
        $plant_name = $this->session->userdata['ADMIN']['plant_name'];
        $storage_location_name = $this->session->userdata['ADMIN']['storage_location_name'];
        $admin_id = $this->session->userdata['ADMIN']['admin_id'];

        $gisto_dept_header['plant'] = $plant;
        $gisto_dept_header['plant_name'] = $plant_name;
        $gisto_dept_header['posting_date'] = $this->l_general->str_to_date($this->input->post('posting_date'));
        $gisto_dept_header['storage_location'] = $storage_location;
        $gisto_dept_header['id_gisto_dept_plant'] = $this->retOut_model->id_gisto_dept_plant_new_select($gisto_dept_header['plant'],$gisto_dept_header['posting_date']);
        $gisto_dept_header['receiving_plant'] = $this->input->post('reqOutlet');
        $gisto_dept_header['receiving_plant_name'] = $this->input->post('reqOutletName');
        $gisto_dept_header['po_no'] = '';
        $gisto_dept_header['gisto_dept_no'] = '';
        $gisto_dept_header['item_group_code'] = $this->input->post('matGroup');
        $gisto_dept_header['remark'] = $this->input->post('RemarkHead');
        $gisto_dept_header['status'] = $this->input->post('appr')? $this->input->post('appr') : '1';
        $gisto_dept_header['id_user_input'] = $admin_id;
        $gisto_dept_header['id_user_approved'] = $this->input->post('appr')? $admin_id : 0;
        $gisto_dept_header['id_user_cancel'] = 0;
        $gisto_dept_header['back'] = 1;

        $detMatrialNo = $this->input->post('detMatrialNo');
        $max = count($detMatrialNo);

        if($id_gisto_dept_header= $this->retOut_model->gisto_dept_header_insert($gisto_dept_header)){
            $input_detail_success = false;
            for($i =0; $i < $max; $i++){
                $gisto_dept_detail['id_gisto_dept_header'] = $id_gisto_dept_header;
                $gisto_dept_detail['id_gisto_dept_h_detail'] = $i+1;
                $gisto_dept_detail['material_no'] = $this->input->post('detMatrialNo')[$i];
                $gisto_dept_detail['material_desc'] = $this->input->post('detMatrialDesc')[$i];
                $gisto_dept_detail['gr_quantity'] = $this->input->post('detQty')[$i];
                $gisto_dept_detail['uom'] = $this->input->post('detUom')[$i];
                $gisto_dept_detail['ok'] = 0;
                $gisto_dept_detail['ok_cancel'] = 0;
                $gisto_dept_detail['reason'] = $this->input->post('detRemark')[$i];
                $gisto_dept_detail['stock'] = (float)$this->input->post('inWhsQty')[$i];

                if($this->retOut_model->gisto_dept_detail_insert($gisto_dept_detail))
                $input_detail_success = TRUE;
            }
        }

        if($input_detail_success){
            return $this->session->set_flashdata('success', "Retur Out Telah Terbentuk");
        }else{
            return $this->session->set_flashdata('failed', "Retur Out Gagal Terbentuk");
        }
    }

    public function edit(){
        $id_gisto_dept_header = $this->uri->segment(4);
        $object['data'] = $this->retOut_model->gisto_dept_header_select($id_gisto_dept_header);
        $object['retOut_header']['id_gisto_dept_header'] = $id_gisto_dept_header;
        if($object['data']['status'] == '1'){
            $object['retOut_header']['status_string'] = 'Not Approved';                              
        }else if($object['data']['status'] == '2'){
            $object['retOut_header']['status_string'] = 'Approved';
        }else{
            $object['retOut_header']['status_string'] = 'Cancel';
        }
        $object['retOut_header']['plant'] = $object['data']['plant'].' - '.$object['data']['plant_name_new'];
        $object['retOut_header']['gisto_dept_no'] = $object['data']['gisto_dept_no'];
        $object['retOut_header']['gisto_dept_no1'] = $object['data']['gisto_dept_no1'];
		$object['retOut_header']['storage_location'] = $object['data']['storage_location'].' - '.$object['data']['storage_location_name'];
        $object['retOut_header']['receiving_plant'] = $object['data']['receiving_plant'].' - '.$object['data']['receiving_plant_name'];
        $object['retOut_header']['posting_date'] = $object['data']['posting_date'];
        $object['retOut_header']['item_group_code'] = $object['data']['item_group_code'];
        $object['retOut_header']['status'] = $object['data']['status'];
        $object['retOut_header']['remark'] = $object['data']['remark'];

        $this->load->view('transaksi1/eksternal/returnout/edit_view', $object);
    }

    public function addDataUpdate(){
        $admin_id = $this->session->userdata['ADMIN']['admin_id'];
        $retout_header['id_gisto_dept_header'] = $this->input->post('idRetOut');
        $retout_header['remark'] = $this->input->post('RemarkHead');
        $retout_header['status'] = $this->input->post('appr') ? $this->input->post('appr') : '1';
        $retout_header['id_user_approved'] = $this->input->post('appr')? $admin_id : 0;
        $retout_header['posting_date'] = $this->l_general->str_to_date($this->input->post('posting_date'));
        
        $approve = $this->input->post('appr');
        $detMatrialNo = $this->input->post('detMatrialNo');
        $max = count($detMatrialNo);
 
        if($this->retOut_model->retout_header_update($retout_header)){
            if($this->retOut_model->gisto_dept_details_delete($this->input->post('idRetOut'))){
                for($i=0; $i < $max; $i++){
                    $gisto_dept_detail['id_gisto_dept_header'] = $this->input->post('idRetOut');
                    $gisto_dept_detail['id_gisto_dept_h_detail'] = $i+1;
                    $gisto_dept_detail['material_no'] = $this->input->post('detMatrialNo')[$i];
                    $gisto_dept_detail['material_desc'] = $this->input->post('detMatrialDesc')[$i];
                    $gisto_dept_detail['gr_quantity'] = $this->input->post('detQty')[$i];
                    $gisto_dept_detail['uom'] = $this->input->post('detUom')[$i];
                    $gisto_dept_detail['ok'] = 0;
                    $gisto_dept_detail['ok_cancel'] = 0;
                    $gisto_dept_detail['reason'] = $this->input->post('detRemark')[$i];
                    $gisto_dept_detail['stock'] = $this->input->post('inWhsQty')[$i];

                    if($this->retOut_model->gisto_dept_detail_insert($gisto_dept_detail))
                        $update_detail_success = TRUE;
                }
            } 
        }

        if($update_detail_success){
            return $this->session->set_flashdata('success', "Retur Out Inter Outlet Berhasil di Update");
        }else{
            return $this->session->set_flashdata('failed', "Retur Out Inter Outlet Gagal di Update");
        }
    }

    public function showReturnInDetail(){
        $plant = $this->session->userdata['ADMIN']['plant'];
        $id_gisto_dept_header = $this->input->post('id');
        $stts = $this->input->post('status');
        $rs = $this->retOut_model->gisto_dept_details_select($id_gisto_dept_header);
        $dt = array();
        $i = 1;
       
        if($rs){
            foreach($rs as $key=>$value){
                $inwhs = $this->retOut_model->getDataInWhsQtyCommited($value['material_no'],$plant);
                $nestedData=array();
                $nestedData['id_gisto_dept_detail'] = $value['id_gisto_dept_detail'];
                $nestedData['no'] = $i;
                $nestedData['material_no'] = $value['material_no'];
				$nestedData['material_desc'] = $value['material_desc'];
				$nestedData['stock'] = ($stts==1 ? ($inwhs[0]['OnHand']!='.000000' ? number_format($inwhs[0]['OnHand'],4,'.','') : '0.0000') : number_format($value['stock'],4,'.',''));
				$nestedData['gr_quantity'] = $value['gr_quantity'];
                $nestedData['uom'] = $value['uom'];
                $nestedData['reason'] = $value['reason'];
                $dt[] = $nestedData;
                $i++;
            }
        }

        $json_data = array(
            "data" => $dt
        );
        echo json_encode($json_data);
    }

    public function deleteData(){
        $id_gisto_dept_header = $this->input->post('deleteArr');
        $deleteData = false;
        foreach($id_gisto_dept_header as $id){
            $dataHeader = $this->retOut_model->gisto_dept_header_select($id);
            if($dataHeader['status'] == '2'){
                $deleteData = false;
            }else{
                if($this->retOut_model->gisto_dept_header_delete($id))
                $deleteData = true;
            }
        }
        if($deleteData){
            return $this->session->set_flashdata('success', "Retur Out Berhasil dihapus");
        }else{
            return $this->session->set_flashdata('failed', "Retur Out Approved, Gagal dihapus");
        }
    }

    public function cancelReturnOut(){
        $retOut_header['id_gisto_dept_header'] = $this->input->post('id_gisto_dept_header');
        $retOut_details = $this->input->post('deleteArr');

        if($this->retOut_model->cancelHeaderReturnOut($retOut_header)){
            $succes_cancel_ro_from_vendor = false;
            for($i=0; $i<count($retOut_details); $i++){
                if($this->retOut_model->cancelDetailsReturnOut($retOut_details[$i]))
                $succes_cancel_ro_from_vendor = true;
            }
        }
        if($succes_cancel_ro_from_vendor){
            return $this->session->set_flashdata('success', "Return Out Berhasil di Cancel");
        }else{
            return $this->session->set_flashdata('failed', "Return Out Gagal di Cancel");
        }  
    }

    public function printpdf(){
		$id_retout_header = $this->uri->segment(4);
		$data['data'] = $this->retOut_model->tampil($id_retout_header);

		ob_start();
		$content = $this->load->view('transaksi1/eksternal/returnout/printpdf_view',$data);
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
}
?>