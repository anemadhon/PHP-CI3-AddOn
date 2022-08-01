<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase_request extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->library('auth');  
		if(!$this->auth->is_logged_in()) {
			redirect(base_url());
        }
        $this->load->library('form_validation');
        $this->load->library('l_general');

        // load model
        $this->load->model('transaksi1/purchase_model', 'pr_model');
    }

    public function index(){
        $this->load->view("transaksi1/eksternal/purchase_request/list_view");
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

        $rs = $this->pr_model->t_pr_headers($date_from2, $date_to2, $status);
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
            $nestedData['id_pr_header'] = $val['id_pr_header'];
            $nestedData['pr_no'] = $val['pr_no'];
            $nestedData['pr_no1'] = $val['pr_no1'];
            $nestedData['created_date'] = date("d-m-Y",strtotime($val['created_date']));
            $nestedData['delivery_date'] = date("d-m-Y",strtotime($val['delivery_date']));
            $nestedData['request_reason'] = $val['request_reason'];
            $nestedData['status'] = $status_string; 
            $nestedData['created_by'] = $val['user_input'];
            $nestedData['approved_by'] = $val['user_approved'];
            $nestedData['last_modified'] = date("d-m-Y",strtotime($val['lastmodified']));
            $nestedData['po_print'] = '';
            $nestedData['po'] = $this->pr_model->get_no_po($val['pr_no']);
            $nestedData['back'] = $val['back'] =='1'?'Not Integrated':'Integrated';
            $nestedData['status_real'] = $val['status'];
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
		$object['request_reason'] = ['Pastry', 'Cake Shop', 'Store', 'Bar'];
		$object['matrialGroup'] = $this->pr_model->showMatrialGroup();
		$object['plant'] = $this->session->userdata['ADMIN']['plant'].' - '.$this->session->userdata['ADMIN']['plant_name'];
    	$object['storage_location'] = $this->session->userdata['ADMIN']['storage_location'].' - '.$this->session->userdata['ADMIN']['storage_location_name'];
    
        $this->load->view('transaksi1/eksternal/purchase_request/add_view', $object);
	}
	
	function getdataDetailMaterial(){
		$item_group_code = $this->input->post('matGroup');
		
		if($item_group_code == 'all'){
			$data = $this->pr_model->sap_item_groups_select_all_grnonpo();
		}else{
			$data = $this->pr_model->sap_items_select_by_item_group($item_group_code, 'pr');
        }
        
        echo json_encode($data);

	}
	
	function getdataDetailMaterialSelect(){
        $itemSelect = $this->input->post('MATNR');
        
        $dataMatrialSelect = $this->pr_model->sap_item_groups_select_all_grnonpo($itemSelect);
        $dataLastVendorPrice = $this->pr_model->sap_get_last_data($itemSelect);

        $json_data = array(
            "data" => $dataMatrialSelect,
            "dataLast" => $dataLastVendorPrice
        );

        echo json_encode($json_data);
        
	}
	
	public function addData(){
        $plant = $this->session->userdata['ADMIN']['plant'];
        $storage_location = $this->session->userdata['ADMIN']['storage_location'];
        $plant_name = $this->session->userdata['ADMIN']['plant_name'];
        $storage_location_name = $this->session->userdata['ADMIN']['storage_location_name'];
        $admin_id = $this->session->userdata['ADMIN']['admin_id'];

        $pr_header['delivery_date'] = $this->l_general->str_to_date($this->input->post('posting_date'));
        $pr_header['request_reason'] = $this->input->post('reqReason');
        $pr_header['item_group_code'] = $this->input->post('matGroup');
        $pr_header['request_reason'] = $this->input->post('Remark');
        $pr_header['created_date'] = date('Y-m-d');
        $pr_header['plant'] = $plant;
        $pr_header['plant_name'] = $plant_name;
        $pr_header['storage_location_name'] = $storage_location_name ;
        $pr_header['storage_location'] = $storage_location ;
        $pr_header['id_pr_plant'] = $this->pr_model->id_pr_plant_new_select($pr_header['plant'],$pr_header['created_date']);
        $pr_header['status'] = $this->input->post('appr')? $this->input->post('appr') : '1';
        $pr_header['id_user_input'] = $admin_id;
        $pr_header['pr_no'] = '';
        $pr_header['back'] = 1;
        $pr_header['id_user_approved'] = $this->input->post('appr')? $admin_id : 0;

        $pr_details['material_no'] = $this->input->post('detMatrialNo');
        $count = count($pr_details['material_no']);

        if($id_pr_header= $this->pr_model->pr_header_insert($pr_header)){
            $input_detail_success = false;
            for($i =0; $i < $count; $i++){
                $pr_detail['id_pr_header'] = $id_pr_header;
                $pr_detail['id_pr_h_detail'] = $i+1;
                $pr_detail['material_no'] = $this->input->post('detMatrialNo')[$i];
                $pr_detail['material_desc'] = $this->input->post('detMatrialDesc')[$i];
                $pr_detail['requirement_qty'] = $this->input->post('detQty')[$i];
                $pr_detail['uom'] = $this->input->post('detUom')[$i];
                $pr_detail['OnHand'] = $this->input->post('OnHand')[$i];

                if($this->pr_model->pr_detail_insert($pr_detail))
                $input_detail_success = TRUE;
            }
        }

        if($input_detail_success){
            return $this->session->set_flashdata('success', "Purchase Request Telah Terbentuk");
        }else{
            return $this->session->set_flashdata('failed', "Purchase Request Gagal Terbentuk");
        }
    }

    function edit(){
		$id_pr_header = $this->uri->segment(4);
        $object['plant_name'] = $this->session->userdata['ADMIN']['plant'].' - '.$this->session->userdata['ADMIN']['plant_name'];
		$object['storage_location_name'] = $this->session->userdata['ADMIN']['storage_location'].' - '.$this->session->userdata['ADMIN']['storage_location_name'];
		$object['data'] = $this->pr_model->prnew_header_select($id_pr_header);
		$object['pr_header']['id_pr_header'] = $id_pr_header;

        if($object['data']['status'] == '1'){
            $object['pr_header']['status_string'] = 'Not Approved';                              
        }else if($object['data']['status'] == '2'){
            $object['pr_header']['status_string'] = 'Approved';
        }else{
            $object['pr_header']['status_string'] = 'Cancel';
		}
		
        $object['pr_header']['delivery_date'] = $object['data']['delivery_date'];
        $object['pr_header']['pr_no'] = $object['data']['pr_no'];
        $object['pr_header']['pr_no1'] = $object['data']['pr_no1'];
        $object['pr_header']['remark'] = $object['data']['request_reason'];
        $object['pr_header']['item_group_code'] = $object['data']['item_group_code'];
        $object['pr_header']['status'] = $object['data']['status'];
        
        $this->load->view('transaksi1/eksternal/purchase_request/edit_view', $object);
	}

	public function showPurchaseDetail(){
        $id_pr_header = $this->input->post('id');
        $stts = $this->input->post('status');
        $rs = $this->pr_model->pr_details_select($id_pr_header);
        $dt = array();
        $i = 1;
        if($rs){
            foreach($rs as $key=>$value){
                $nestedData=array();
                $dataLastVendorPrice = $this->pr_model->sap_get_last_data_no_limit($value['material_no']);
                $dataUnitUOM = $this->pr_model->sap_get_uom_convertion($value['material_no']);
                $nestedData['id_pr_detail'] = $value['id_pr_detail'];
                $nestedData['no'] = $i;
                $nestedData['material_no'] = $value['material_no'];
                $nestedData['material_desc'] = $value['material_desc'];
                $nestedData['requirement_qty'] = number_format($value['requirement_qty'],4,'.','');
                $nestedData['uom'] = $value['uom'];
                $nestedData['uomInv'] = number_format(($value['requirement_qty']*$dataUnitUOM['NUM']),4,'.','').' '.$dataUnitUOM['UNIT1'];
                $nestedData['price'] = $dataLastVendorPrice['LastPrice']?number_format($dataLastVendorPrice['LastPrice'],4,'.',''):0;
                $nestedData['vendor'] = $dataLastVendorPrice['VendorCode'].' - '.$dataLastVendorPrice['VendorName'];
                $nestedData['onHand'] = number_format($value['OnHand'],4,'.','');
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
	
	public function chageDataDB(){
        $id_pr_header = $this->input->post('idpr_header');
        $sum = count($this->input->post('idpr_detail'));
        $succes_update_detail = false;
        for($i = 0; $i < $sum; $i++){
            $pr_detail['id_pr_detail'] = $this->input->post('idpr_detail')[$i];
            $pr_detail['requirement_qty'] = $this->input->post('qty')[$i];
            if($this->pr_model->changeUpdateToDb($pr_detail))
            $succes_update_detail = true;
        }

        if($succes_update_detail){
            return $this->session->set_flashdata('success', "Purchase Request Berhasil di Update");
        }else{
            return $this->session->set_flashdata('failed', "Purchase Request Gagal di Update");
        } 
	}
	
	public function addDataUpdate(){
        $admin_id = $this->session->userdata['ADMIN']['admin_id'];

        $pr_header['id_pr_header'] = $this->input->post('idpr_header');
        $pr_header['delivery_date'] = $this->l_general->str_to_date($this->input->post('deliveDate'));
        $pr_header['status'] = $this->input->post('appr')? $this->input->post('appr') : '1';
        $pr_header['request_reason'] = $this->input->post('Remark');
        $pr_header['id_user_approved'] = $this->input->post('appr')? $admin_id : 0;
        
        $count = count($this->input->post('detMatrialNo'));
        if($this->pr_model->pr_header_update($pr_header)){
            $update_detail_success = false;
            if($this->pr_model->pr_details_delete($pr_header['id_pr_header'])){
                for($i =0; $i < $count; $i++){
                    $pr_details['id_pr_header'] = $pr_header['id_pr_header'];
                    $pr_details['id_pr_h_detail'] = $i+1;
                    $pr_details['material_no'] = $this->input->post('detMatrialNo')[$i];
                    $pr_details['material_desc'] = $this->input->post('detMatrialDesc')[$i];
                    $pr_details['requirement_qty'] = $this->input->post('detQty')[$i];
					$pr_details['price'] = $this->input->post('detPrice')[$i];
					$pr_details['vendor'] = $this->input->post('detVendor')[$i];
					$pr_details['uom'] = $this->input->post('detUom')[$i];
					$pr_details['OnHand'] = $this->input->post('detOnHand')[$i];

                    if($this->pr_model->pr_detail_insert($pr_details))
                    $update_detail_success = TRUE;
                }
            }
        }

        if($update_detail_success){
            return $this->session->set_flashdata('success', "Purchase Request Berhasil di Update");
        }else{
            return $this->session->set_flashdata('failed', "Purchase Request Gagal di Update");
        }
    }
	
	public function deleteData(){
        $id_pr_header = $this->input->post('deleteArr');
        $deleteData = false;
        foreach($id_pr_header as $id){
            if($this->pr_model->t_prnew_header_delete($id))
            $deleteData = true;
        }
        
        if($deleteData){
            return $this->session->set_flashdata('success', "Purchase Request Berhasil dihapus");
        }else{
            return $this->session->set_flashdata('failed', "Purchase Request Approved, Gagal dihapus");
        }
	}

    function printpdf(){
		$id_pr_header = $this->uri->segment(4);
		$data['data'] = $this->pr_model->tampil($id_pr_header);
		
		ob_start();
		$content = $this->load->view('transaksi1/eksternal/purchase_request/printpdf_view',$data);
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
    
    public function printpdfPO(){
		$data['data']['id'] = $this->uri->segment(4);
		$data['data']['plant'] = $this->session->userdata['ADMIN']['plant'];
		
		ob_start();
		$content = $this->load->view('transaksi1/eksternal/purchase_request/printpdfPO_view',$data);
		$content = ob_get_clean();		
		require_once(APPPATH.'libraries/html2pdf/html2pdf.class.php');
		try
		{
			$html2pdf = new HTML2PDF('P', 'F4', 'en');
			$html2pdf->setTestTdInOnePage(false);
			$html2pdf->pdf->SetDisplayMode('fullpage');
			$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
			$html2pdf->Output('print PO.pdf');
		}
		catch(HTML2PDF_exception $e) {
			echo $e;
			exit;
		}
		
	}

	public function excel(){
		$id_pr_header = $this->uri->segment(4);
		$data['data'] = $this->pr_model->tampil($id_pr_header);
		
		ob_start();
	   	$this->load->view('transaksi1/eksternal/purchase_request/printexcel_view',$data);
	}
}
?>