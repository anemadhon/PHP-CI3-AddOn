<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Spoiled extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->library('auth');  
		if(!$this->auth->is_logged_in()) {
			redirect(base_url());
        }
        $this->load->library('form_validation');
        $this->load->library('l_general');

        // load model
        $this->load->model('transaksi1/spoiled_model', 'spol_model');
    }

    public function index(){
        $this->load->view('transaksi1/stock_outlet/spoiled/list_view');
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

        $rs = $this->spol_model->t_waste_header($date_from2, $date_to2, $status);
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
            $nestedData['id_waste_header'] = $val['id_waste_header'];
            $nestedData['material_doc_no'] = $val['material_doc_no'];
            $nestedData['posting_date'] = date("d-m-Y",strtotime($val['posting_date']));
            $nestedData['last_modified'] = date("d-m-Y H:i:s",strtotime($val['lastmodified']));
            $nestedData['user_input'] = $val['user_input'];
            $nestedData['user_approved'] = $val['user_approved'];
            $nestedData['status'] = $status_string; 
            $nestedData['status_real'] = $val['status'];
            $nestedData['back'] = $val['back'] =='0'?'Integrated':'Not Integrated';
            $data[] = $nestedData;
        }
        $json_data = array(
            "data"            => $data 
        );
        echo json_encode($json_data);
     }

     public function add(){
        $object['plant'] = $this->session->userdata['ADMIN']['plant']; 
        $object['plant_name'] = $this->session->userdata['ADMIN']['plant_name'];
        $object['storage_location'] = $this->session->userdata['ADMIN']['storage_location']; 
        $object['storage_location_name'] = $this->session->userdata['ADMIN']['storage_location_name'];
        $object['cost_center'] = $this->session->userdata['ADMIN']['cost_center']; 
        $object['cost_center_name'] = $this->session->userdata['ADMIN']['cost_center_name'];
        $object['matrialGroup'] = $this->spol_model->showMatrialGroup();

         $this->load->view('transaksi1/stock_outlet/spoiled/add_view', $object);
     }

     public function edit(){
        $id_waste_header = $this->uri->segment(4);
        $object['data'] = $this->spol_model->t_waste_header_select($id_waste_header);

        if($object['data']['status'] == '1'){
            $object['waste_header']['status_string'] = 'Not Approved';                              
        }else if($object['data']['status'] == '2'){
            $object['waste_header']['status_string'] = 'Approved';
        }else{
            $object['waste_header']['status_string'] = 'Cancel';
        }

        $object['waste_header']['id_waste_header'] = $id_waste_header;
        $object['waste_header']['material_doc_no'] = $object['data']['material_doc_no'];
        $object['waste_header']['plant'] = $object['data']['plant'].' - '.$object['data']['plant_name'];
        $object['waste_header']['storage_location'] = $object['data']['storage_location'].' - '.$object['data']['storage_location_name'];
        $object['waste_header']['posting_date'] = $object['data']['posting_date'];
        $object['waste_header']['item_group_code'] = $object['data']['item_group_code'];
        $object['waste_header']['status'] = $object['data']['status'];
        $object['waste_header']['cost_center'] = $object["data"]['cost_center'].' - '.$object["data"]['cost_center_name'];
        $object['waste_header']['remark'] = $object['data']['no_acara'];

        $this->load->view('transaksi1/stock_outlet/spoiled/edit_view', $object);
    }

    public function showWasteDetail(){
        $id_waste_header = $this->input->post('id');
        $stts = $this->input->post('status');
        $rs = $this->spol_model->t_waste_details_select($id_waste_header);
        $dt = array();
        $i = 1;
        if($rs){
            foreach($rs as $key=>$value){
                $inwhs = $this->spol_model->getInWhsQtyCommited($value['material_no']);
                $nestedData=array();
                $nestedData['id_waste_detail'] = $value['id_waste_detail'];
                $nestedData['no'] = $i;
                $nestedData['material_no'] = $value['material_no'];
				$nestedData['material_desc'] = $value['material_desc'];
				$nestedData['stock'] = ($stts==1 ? ($inwhs[0]['OnHand']!='.000000' ? number_format($inwhs[0]['OnHand'],4,'.','') : '0.0000') : number_format($value['stock'],4,'.',''));
				$nestedData['quantity'] = number_format((float)$value['quantity'],4,'.','');
                $nestedData['uom'] = $value['uom'];
                $nestedData['status'] = $stts;
                $nestedData['reason_name'] = $value['reason_name'];
                $nestedData['other_reason'] = $value['other_reason'];
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
        
        $data = $this->spol_model->getDataMaterialGroup($item_group_code);
        echo json_encode($data);

    }

    function getdataDetailMaterialSelect(){
        $itemSelect = $this->input->post('MATNR');
        
        $dataMatrialSelect = $this->spol_model->getDataMaterialGroupSelect($itemSelect);
        $dt = array();

        foreach($dataMatrialSelect as $val){
            $getinwhsqty = $this->spol_model->getInWhsQtyCommited($val['MATNR']);
            $inwhsqty = 0;
            if($getinwhsqty != false){
                $inwhsqty = (float)$getinwhsqty[0]['OnHand'];
            }
        
            $nestedData = array();
            $nestedData['MATNR']        = $val['MATNR'];
            $nestedData['MAKTX']        = $val['MAKTX'];
            $nestedData['DISPO']        = $val['DISPO'];
            $nestedData['UNIT']         = $val['UNIT'];
            $nestedData['WHSQTY']       = number_format($inwhsqty,4,'.','');
            $dt[] = $nestedData;
        }
        echo json_encode($dt) ;        
    }

    public function addData(){
        $plant = $this->session->userdata['ADMIN']['plant']; 
        $plant_name = $this->session->userdata['ADMIN']['plant_name'];
        $storage_location = $this->session->userdata['ADMIN']['storage_location']; 
        $storage_location_name = $this->session->userdata['ADMIN']['storage_location_name'];
        $cost_center = $this->session->userdata['ADMIN']['cost_center']; 
        $cost_center_name = $this->session->userdata['ADMIN']['cost_center_name'];
        $admin_id = $this->session->userdata['ADMIN']['admin_id'];

        $waste_header['posting_date'] = $this->l_general->str_to_date($this->input->post('posting_date'));
        $waste_header['material_doc_no'] = '';
        $waste_header['plant'] = $plant;
        $waste_header['plant_name'] = $plant_name;
        $waste_header['id_waste_plant'] = $this->spol_model->id_waste_plant_new_select($waste_header['plant'],$waste_header['posting_date']);
        $waste_header['storage_location'] = $storage_location;
        $waste_header['storage_location_name'] = $storage_location_name;
        $waste_header['cost_center'] = $cost_center;
        $waste_header['cost_center_name'] = $cost_center_name;
        $waste_header['status'] = $this->input->post('appr')? $this->input->post('appr') : '1';
        $waste_header['item_group_code'] = $this->input->post('matGroup');
        $waste_header['id_user_input'] = $admin_id;
        $waste_header['back'] = 1;
        $waste_header['id_user_approved'] = $this->input->post('appr')? $admin_id : 0;
        $waste_header['no_acara'] = $this->input->post('Remark');

        $waste_detail['material_no'] = $this->input->post('detMatrialNo');
        $count = count($waste_detail['material_no']);
        
        if($id_waste_header= $this->spol_model->waste_header_insert($waste_header)){
            $input_detail_success = false;
            for($i =0; $i < $count; $i++){
                $waste_details['id_waste_header'] = $id_waste_header;
                $waste_details['id_waste_h_detail'] = $i+1;
                $waste_details['material_no'] = $this->input->post('detMatrialNo')[$i];
                $waste_details['material_desc'] = $this->input->post('detMatrialDesc')[$i];
                $waste_details['stock'] = $this->input->post('detWhsQty')[$i];
                $waste_details['quantity'] = $this->input->post('detQty')[$i];
                $waste_details['uom'] = $this->input->post('detUom')[$i];
                $waste_details['reason_name'] = $this->input->post('detReason')[$i];
                $waste_details['other_reason'] = $this->input->post('detText')[$i];

                if($this->spol_model->waste_details_insert($waste_details))
                $input_detail_success = TRUE;
            }
        }

        if($input_detail_success){
            return $this->session->set_flashdata('success', "Spoiled Telah Terbentuk");
        }else{
            return $this->session->set_flashdata('failed', "Spoiled Gagal Terbentuk");
        }
    }

    public function deleteData(){
        $id_waste_header = $this->input->post('deleteArr');
        $deleteData = false;
        foreach($id_waste_header as $id){
            $dataHeader = $this->spol_model->t_waste_header_select($id);
            if($dataHeader['status'] == '2'){
                $deleteData = false;
            }else{
                if($this->spol_model->t_waste_header_delete($id))
                $deleteData = true;
            }
        }
        
        if($deleteData){
            return $this->session->set_flashdata('success', "Spoiled Berhasil dihapus");
        }else{
            return $this->session->set_flashdata('failed', "Spoiled Approved, Gagal dihapus");
        }
    }
    
    public function addDataUpdate(){
        $admin_id = $this->session->userdata['ADMIN']['admin_id'];
        $waste_header['id_waste_header'] = $this->input->post('id_waste_header');
        $waste_header['posting_date'] = $this->l_general->str_to_date($this->input->post('posting_date'));
        $waste_header['status'] = $this->input->post('appr')? $this->input->post('appr') : '1';
        $waste_header['id_user_approved'] = $this->input->post('appr')? $admin_id : 0;
        $waste_header['no_acara'] = $this->input->post('Remark');
        
        $count = count($this->input->post('detMatrialNo'));
        if($this->spol_model->waste_header_update($waste_header)){
            $update_detail_success = false;
            if($this->spol_model->t_waste_details_delete($waste_header['id_waste_header'])){
                for($i =0; $i < $count; $i++){
                    $waste_details['id_waste_header'] = $waste_header['id_waste_header'];
                    $waste_details['id_waste_h_detail'] = $i+1;
                    $waste_details['material_no'] = $this->input->post('detMatrialNo')[$i];
                    $waste_details['material_desc'] = $this->input->post('detMatrialDesc')[$i];
                    $waste_details['stock'] = $this->input->post('detWhsQty')[$i];
                    $waste_details['quantity'] = $this->input->post('detQty')[$i];
                    $waste_details['uom'] = $this->input->post('detUom')[$i];
                    $waste_details['reason_name'] = $this->input->post('detReason')[$i];
                    $waste_details['other_reason'] = $this->input->post('detText')[$i];

                    if($this->spol_model->waste_details_insert($waste_details))
                        $update_detail_success = TRUE;
                }
            }
        }

        if($update_detail_success){
            return $this->session->set_flashdata('success', "Spoiled Berhasil di Update");
        }else{
            return $this->session->set_flashdata('failed', "Spoiled Gagal di Update");
        }
    }


    public function printpdf(){
        
        $id_waste_header = $this->uri->segment(4);
		$data['data'] = $this->spol_model->tampil($id_waste_header);
        
        ob_start();
		$content = $this->load->view('transaksi1/stock_outlet/spoiled/spoiled_pdf',$data);
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
?>