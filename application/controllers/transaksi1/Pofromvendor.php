<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Pofromvendor extends CI_Controller {   
    protected $dataGroupItem = [];

    public function __construct(){
        parent::__construct();
        $this->load->library('auth');  
		if(!$this->auth->is_logged_in()) {
			redirect(base_url());
        }
        
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->library('l_general');
        
        // load model
        $this->load->model('transaksi1/pofromvendor_model', 'povendor');
    }

    public function index(){
        $this->load->view('transaksi1/eksternal/pofromvendor/list_view');
    }
	
	public function add(){
        $data['po_nos'] = $this->povendor->sap_grpo_headers_select_by_kd_vendor();
        $object['po_no']['-'] = '';
        if($data['po_nos'] != FALSE){
            foreach($data['po_nos'] as $po_no){
                $po_nos = $this->povendor->sap_get_nopp($po_no['EBELN']);
                $object['po_no'][$po_no['EBELN']] = $po_no['EBELN'].' - '.$po_no['DOCNUM'].' (PR -> '.$po_nos.')';
                if ($po_no['OLDDOCNUM'] != '') {
                    $object['po_no'][$po_no['EBELN']]  = $object['po_no'][$po_no['EBELN']] .' (OLD PO NO -> '.$po_no['OLDDOCNUM'].')';
                }
            }
        } 
        $object['plant'] = $this->session->userdata['ADMIN']['plant'].' - '.$this->session->userdata['ADMIN']['plant_name'];
        $object['storage_location'] = $this->session->userdata['ADMIN']['storage_location'].' - '.$this->session->userdata['ADMIN']['storage_location_name'];
        $this->load->view('transaksi1/eksternal/pofromvendor/add_new',$object);
    }

    public function getDataPoHeader(){
        $poNumber = $this->input->post('poNumberHeader');
        $data = $this->povendor->sap_grpo_headers_select_by_kd_vendor("","",$poNumber);
        $dataOption = $this->povendor->sap_grpo_select_item_group_po($poNumber);
        
        $i = 1;
        foreach($data as $key=>$value){
            $nestedData=array();
            $nestedData['no'] = $i;
            $nestedData['MATNR'] = $value['MATNR'];
            $nestedData['MAKTX'] = $value['MAKTX'];
            $nestedData['BSTMG'] = $value['BSTMG'];
            $nestedData['DISPO'] = $value['DISPO'];
            $nestedData['DOCNUM'] = $value['DOCNUM'];
            $nestedData['BSTME'] = $value['BSTME'];
            $dt[] = $nestedData;
            $i++;
        }

        if (count($data) > 0) {

            $json_data = array(
                "data" => $data[1],
                "dataOption" => $dataOption,
                "dataTable" =>$dt
            );
            echo json_encode($json_data) ;
        }
    }
	
	public function edit(){
        $id_grpo_header = $this->uri->segment(4);
        $object['data'] = $this->povendor->grpo_header_select($id_grpo_header);

        $object['grpo_header']['id_grpo_header'] = $id_grpo_header;
        if($object['data']['status'] == '1'){
            $object['grpo_header']['status_string'] = 'Not Approved';                              
        }else if($object['data']['status'] == '2'){
            $object['grpo_header']['status_string'] = 'Approved';
        }else{
            $object['grpo_header']['status_string'] = 'Cancel';
        }
        $object['grpo_header']['kd_vendor'] = $object['data']['kd_vendor'];
        $object['grpo_header']['nm_vendor'] = $object['data']['nm_vendor'];
        $object['grpo_header']['delivery_date'] = $object['data']['delivery_date'];
        $object['grpo_header']['po_no'] = $object['data']['po_no'];
        $object['grpo_header']['docnum'] = $object['data']['docnum'];
        $object['grpo_header']['grpo_no'] = $object['data']['grpo_no'];
        $object['grpo_header']['posting_date'] = $object['data']['posting_date'];
        $object['grpo_header']['status'] = $object['data']['status'];
        $object['grpo_header']['remarkHead'] = $object['data']['remark'];
        $object['plant'] = $this->session->userdata['ADMIN']['plant'].' - '.$this->session->userdata['ADMIN']['plant_name'];
        $object['storage_location'] = $this->session->userdata['ADMIN']['storage_location'].' - '.$this->session->userdata['ADMIN']['storage_location_name'];
        
        $this->load->view('transaksi1/eksternal/pofromvendor/edit_view', $object);
    }

    public function showDeatailEdit(){
        $id_grpo_header = $this->input->post('id');
        $stts = $this->input->post('status');
        $rs = $this->povendor->grpo_details_select($id_grpo_header);
        $dt = array();
        $i = 1;
        if($rs){
            foreach($rs as $key=>$value){
                $nestedData=array();
                $nestedData['no'] = $i;
                $nestedData['id_grpo_detail'] = $value['id_grpo_detail'];
                $nestedData['id_grpo_header'] = $value['id_grpo_header'];
                $nestedData['id_grpo_h_detail'] = $value['id_grpo_h_detail'];
                $nestedData['item'] = $value['item'];
                $nestedData['material_no'] = $value['material_no'];
                $nestedData['material_desc'] = $value['material_desc'];
                $nestedData['outstanding_qty'] = number_format($value['outstanding_qty'],4,'.','');//- $value['gr_quantity']
                $nestedData['gr_quantity'] = number_format($value['gr_quantity'],4,'.',''); 
                $nestedData['uom'] = $value['uom'];
                $nestedData['qc'] = $value['qc'];
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

        $data = array();
        $rs = $this->povendor->getDataPoVendor_Header($date_from2, $date_to2, $status);
        
        foreach($rs as $key=>$val){
            $nestedData = array();
            $nestedData['id_grpo_header'] = $val['id_grpo_header'];
            $nestedData['grpo_no'] = $val['grpo_no'];
            $nestedData['po_no'] = $val['docnum'];
            $nestedData['kd_vendor'] = $val['kd_vendor'];
            $nestedData['nm_vendor'] = $val['nm_vendor'];
            $nestedData['delivery_date'] = $val['delivery_date'];
            $nestedData['posting_date'] = date("d-m-Y",strtotime($val['posting_date']));
            $nestedData['status'] = $val['status'] =='1'?'Not Approved':'Approved';
            $nestedData['id_user_input'] = $val['user_input'];
            $nestedData['id_user_approved'] = $val['user_approved'];
            $nestedData['lastmodified'] = date("d-m-Y",strtotime($val['lastmodified']));
            $nestedData['integrated'] = $val['back'] == 1 ?'Not Integrated' :'Integrated' ;
            $nestedData['outlet_name'] = $val['OUTLET_NAME1'];
            $data[] = $nestedData;
        }

        $json_data = array(
            "recordsTotal"    => 10, 
            "recordsFiltered" => 12,
            "data"            => $data 
        );

        echo json_encode($json_data);
    }

    public function addData(){
        $plant = $this->session->userdata['ADMIN']['plant'];
        $storage_location = $this->session->userdata['ADMIN']['storage_location'];
        $plant_name = $this->session->userdata['ADMIN']['plant_name'];
        $storage_location_name = $this->session->userdata['ADMIN']['storage_location_name'];
        $admin_id = $this->session->userdata['ADMIN']['admin_id'];

        $grpo_header['po_no'] = $this->input->post('poNo');
        $grpo_header['grpo_no'] = '';
        $grpo_header['kd_vendor'] = $this->input->post('kd_vendor');
        $grpo_header['delivery_date'] = $this->l_general->str_to_date_clone($this->input->post('delivery_date'));
        $grpo_header['nm_vendor'] = $this->input->post('nm_vendor');
        $grpo_header['docnum'] = $this->input->post('docnum');
        $grpo_header['plant'] = $plant;
        $grpo_header['storage_location'] = $storage_location;
        $grpo_header['posting_date'] = $this->l_general->str_to_date($this->input->post('posting_date'));
        $grpo_header['id_grpo_plant'] = $this->povendor->id_grpo_plant_new_select($grpo_header['plant'],$grpo_header['posting_date']);
        $grpo_header['status'] = $this->input->post('app') == "2" ? "2" : $this->input->post('status');
        $grpo_header['id_user_approved'] = $this->input->post('app') ? $admin_id : '0';
        $grpo_header['item_group_code'] = $this->input->post('item_group_code');
        $grpo_header['remark'] = $this->input->post('RemarkHead');
        $grpo_header['id_user_input'] = $admin_id;
        $grpo_header['back'] = 1;

        $web_trans_id = $this->l_general->_get_web_trans_id($grpo_header['plant'],$grpo_header['posting_date'],$grpo_header['id_grpo_plant'],'01');

        $grpo_details = $this->povendor->sap_grpo_details_select_by_po_no($this->input->post('poNo'));
       
        if($id_grpo_header = $this->povendor->grpo_header_insert($grpo_header)){
            $input_detail_success = false;
            foreach($grpo_details as $key=>$val ){
                    
                $grpo_detail['id_grpo_header']      = $id_grpo_header;
                $grpo_detail['id_grpo_h_detail']    = $val['id_grpo_h_detail'];
                $grpo_detail['item']                = $val['EBELP'];
                $grpo_detail['material_no']         = $val['MATNR'];
                $grpo_detail['material_desc']       = $val['MAKTX'];
                $grpo_detail['outstanding_qty']     = number_format($val['BSTMG'],4,'.','');
                $grpo_detail['gr_quantity']         = $this->input->post('detail_grQty')[$key-1];
                $grpo_detail['uom']                 = $val['BSTME'];
                $grpo_detail['ok']                  = '1';
                $grpo_detail['ok_cancel']           = '0';
                $grpo_detail['qc']                  = $this->input->post('remark')[$key-1];

                //for batch and batch1
                $batch['BaseEntry']     = $id_grpo_header;
                $batch['BaseLinNum']    = $val['id_grpo_h_detail'];
                $batch['ItemCode']      = $val['MATNR'];
                $batch1['ItemCode']     = $val['MATNR'];
                $batch['Createdate']    = $grpo_header['posting_date'];
                $batch['BaseType']      = 3;
                $batch['Quantity']      = $this->input->post('detail_grQty')[$key-1];
                $batch1['Quantity']     = $this->input->post('detail_grQty')[$key-1];
                $batch1['Whs']          = $grpo_header['plant'];
        
                if($this->povendor->grpo_detail_insert($grpo_detail))
                $input_detail_success = TRUE;
            }
        }
        if($input_detail_success){
            return $this->session->set_flashdata('success', "In PO From Vendor Telah Terbentuk");
        }else{
            return $this->session->set_flashdata('failed', "In PO From Vendor Gagal Terbentuk");
        } 
    }

    public function editTable(){
        $admin_id = $this->session->userdata['ADMIN']['admin_id'];
        $grpo_header['id_grpo_header'] = $this->input->post('id_grpo_header');
        $grpo_header['remark'] = $this->input->post('RemarkHead');
        $grpo_header['posting_date'] = $this->l_general->str_to_date($this->input->post('pstDate'));
        $grpo_header['status'] = $this->input->post('appr') ? $this->input->post('appr') : '1';
        $grpo_header['id_user_approved'] = $this->input->post('appr') ? $admin_id : 0;
        $count = count($this->input->post('idGrpoDetails'));
        $grpo_h = $this->povendor->grpo_header_update($grpo_header);

        if($grpo_h){
            $succes_update_detail = false;
            for($i=0; $i<$count; $i++){
                $grpo_detail['id_grpo_detail'] = $this->input->post('idGrpoDetails')[$i] ;
                $grpo_detail['gr_quantity'] = $this->input->post('detail_grQty')[$i];
                $grpo_detail['qc'] = $this->input->post('remark')[$i];
                if($this->povendor->grpo_detail_update($grpo_detail))
                $succes_update_detail = true;
            }
        }
        if($succes_update_detail){
            return $this->session->set_flashdata('success', "In PO From Vendor Berhasil di Update");
        }else{
            return $this->session->set_flashdata('failed', "In PO From Vendor Gagal di Update");
        }  
    }

    public function cancelPoFromVendor(){
        $grpo_header['id_grpo_header'] = $this->input->post('id_grpo_header');
        $grpo_details = $this->input->post('deleteArr');

        if($this->povendor->cancelHeaderPoFromVendor($grpo_header)){
            $succes_cancel_po_from_vendor = false;
            for($i=0; $i<count($grpo_details); $i++){
                if($this->povendor->cancelDetailsPoFromVendor($grpo_details[$i]))
                $succes_cancel_po_from_vendor = true;
            }
        }
        if($succes_cancel_po_from_vendor){
            return $this->session->set_flashdata('success', "In PO From Vendor Berhasil di Cancel");
        }else{
            return $this->session->set_flashdata('failed', "In PO From Vendor Gagal di Cancel");
        }  
    }

    public function deleteData(){
        $id_grpo_header = $this->input->post('deleteArr');
        $deleteData = false;
        foreach($id_grpo_header as $id){
            $cek = $this->povendor->grpo_header_delete($id);
            if($cek){
                $deleteData = true;
            }else{
                $deleteData = false;
            }
            
        }
        
        if($deleteData){
            return $this->session->set_flashdata('success', "In PO From Vendor Berhasil dihapus");
        }else{
            return $this->session->set_flashdata('failed', "In PO From Vendor Gagal dihapus");
        }
    }

    public function printpdf($id =""){

        if($id != ""){
            $data['data'] = $this->povendor->tampilPdf($id);
            
            ob_start();
            $content = $this->load->view('transaksi1/eksternal/pofromvendor/printpdf_view',$data);
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