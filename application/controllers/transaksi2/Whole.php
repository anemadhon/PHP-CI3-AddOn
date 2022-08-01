<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Whole extends CI_Controller {
    public function __construct(){
        parent::__construct();

        $this->load->library('auth');  
		if(!$this->auth->is_logged_in()) {
			redirect(base_url());
        }
        // load model
        $this->load->model("transaksi2/whole_model","wl_model");
        
        $this->load->library('form_validation');
        $this->load->library('l_general');
    }

    public function index(){
        $this->load->view('transaksi2/whole/list_view');
    }

    public function showAllData(){
       
        $status = $this->input->post('stts');

        $rs = $this->wl_model->twtsnew_headers($status);
        $data = array();
        $log ='';

        foreach($rs as $key=>$val){
            $nestedData = array();
            $nestedData['id_twtsnew_header'] = $val['id_twtsnew_header'];
            $nestedData['posting_date'] = date("d-m-Y",strtotime($val['posting_date']));
            $nestedData['kode_paket'] = $val['kode_paket'];
            $nestedData['nama_paket'] = $val['nama_paket'];
            $nestedData['quantity_paket'] = number_format($val['quantity_paket'], 2, '.', '');
            $nestedData['status'] = $val['status'] =='1'?'Not Approved':'Approved';
            $nestedData['admin_input'] = $val['user_input'];
            $nestedData['admin_approved'] = $val['user_approved'] ? $val['user_approved'] : '-';
            $nestedData['gr_no'] = $val['gr_no'];
            $nestedData['gi_no'] = $val['gi_no'];
            if ($val['back']==0 && $val['gr_no'] !='' && $val['gr_no'] !='C'){
                $log = "Integrated";
            }else if ($val['back']==1 && ($val['gr_no'] =='' || $val['gr_no'] =='C' || $val['gi_no']='')){
                $log = "Not Integrated";
            }else if ($val['back']==0 &&  $val['gr_no'] =='C'){
                $log ="Close Document";
            }
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
        $object['items'] = $this->wl_model->sap_item_groups_select_all_twts_back();
        $this->load->view('transaksi2/whole/add_view', $object);
    }

    public function getOnHand(){
        $itemCode = $this->input->post('item_code');
        $rs = $this->wl_model->getDataOnHand($itemCode);
        echo json_encode($rs);
    }

    function getdataDetailMaterial(){
        $kode_item_paket = $this->input->post('item_code');
        $MATNR = $this->input->post('MATNR');
        
        if(empty($MATNR)){
            $data = $this->wl_model->sap_item_groups_select_all_twts_back_2($kode_item_paket);
        }else{
            $data = $this->wl_model->sap_item_groups_select_all_twts_back_2($kode_item_paket,$MATNR);
        }

        echo json_encode($data);
    }

    public function addData(){
        $plant = $this->session->userdata['ADMIN']['plant'];
        $storage_location = $this->session->userdata['ADMIN']['storage_location'];
        $plant_name = $this->session->userdata['ADMIN']['plant_name'];
        $storage_location_name = $this->session->userdata['ADMIN']['storage_location_name'];
        $admin_id = $this->session->userdata['ADMIN']['admin_id'];

        $itemCode = $this->input->post('item_code');
        $itemDesc = $this->input->post('item_desc');
        $quantityPaket = $this->input->post('qty_paket');
        $approve = $this->input->post('appr');
        $postingDate = $this->l_general->str_to_date($this->input->post('postingDate'));
        $date = date('ymd');

        $twtsnew_header['kode_paket'] = $itemCode;
        $twtsnew_header['nama_paket'] = $itemDesc;
        $twtsnew_header['plant'] = $plant;
        $twtsnew_header['quantity_paket'] = $quantityPaket;
        $twtsnew_header['posting_date'] = $postingDate;
        $twtsnew_header['uom_paket'] = '';
        $twtsnew_header['num'] = '';
        $twtsnew_header['id_user_input'] = $admin_id;
        $twtsnew_header['id_user_approved'] = $approve ? $admin_id : 0;
        $twtsnew_header['status'] = $approve? $approve : '1';
        $twtsnew_header['var'] = '0';
        $twtsnew_header['back'] = 1;

        $count= count($this->input->post('detMatrialNo'));
        if($id_twtsnew_header = $this->wl_model->twtsnew_header_insert($twtsnew_header)){
            $input_detail_success = false;
            for($i=0; $i<$count; $i++){
                $twtsnew_detail['id_twtsnew_header'] = $id_twtsnew_header;
                $twtsnew_detail['id_twtsnew_h_detail'] = $i+1;
                $twtsnew_detail['material_no'] = $this->input->post('detMatrialNo')[$i];            
                $twtsnew_detail['material_desc'] = $this->input->post('detMatrialDesc')[$i];
                $twtsnew_detail['quantity'] = $this->input->post('detQty')[$i];            
                $twtsnew_detail['uom'] = $this->input->post('detUom')[$i];
                $twtsnew_detail['var'] = $this->input->post('Vol')[$i];
                $item = $this->input->post('detMatrialNo')[$i];

                $q = $this->wl_model->jumBatch($item,$plant);
                $count1 =$q['jml']+1;
                if ($count1 > 9 && $count1 < 100) {
                    $dg="0";
                } else {
                    $dg="00";
                }
                $num=$item.$date.$dg.$count1;
                $twtsnew_detail['num'] = $num;

                if($this->wl_model->twtsnew_detail_insert($twtsnew_detail) )
                $input_detail_success = TRUE;
            }
        }

        if($input_detail_success){
            return $this->session->set_flashdata('success', "Cake Cutting Telah Terbentuk");
        }else{
            return $this->session->set_flashdata('failed', "Cake Cutting Gagal Terbentuk");
        }
    }

    public function edit(){
        $id_twtsnew_header = $this->uri->segment(4);
        $object['data']= $this->wl_model->twtsnew_header_select($id_twtsnew_header);
        $rs = $this->wl_model->getDataOnHand($object['data']['kode_paket']);
        
        $object['twtsnew_header']['id_twtsnew_header'] = $id_twtsnew_header;
        $object['twtsnew_header']['status'] = $object['data']['status'];
        $object['twtsnew_header']['kode_paket'] = $object['data']['kode_paket'];
        $object['twtsnew_header']['nama_paket'] = $object['data']['nama_paket'];
        $object['twtsnew_header']['plant'] = $object['data']['plant'];
        $object['twtsnew_header']['quantity_paket'] = $object['data']['quantity_paket'];
        $object['twtsnew_header']['uom_paket'] = $object['data']['uom_paket'];
        $object['twtsnew_header']['posting_date'] = $object['data']['posting_date'];
        $object['twtsnew_header']['onHand'] = number_format($rs[0]['Onhand'],4,'.','');

        $this->load->view('transaksi2/whole/edit_view', $object);
    }

    public function showTwtsnewDetail(){
        $id_twtsnew_header = $this->input->post('id');
        $stts = $this->input->post('status');
        $rs = $this->wl_model->twtsnew_details_select($id_twtsnew_header);
        $dt = array();
        $i = 1;
        if($rs){
            foreach($rs as $key=>$value){
                $nestedData=array();
                $nestedData['no'] = $i;
                $nestedData['id_twtsnew_detail'] = $value['id_twtsnew_detail'];
                $nestedData['material_no'] = $value['material_no'];
                $nestedData['material_desc'] = $value['material_desc'];
                $nestedData['quantity'] = $value['quantity'];
                $nestedData['uom'] = $value['uom'];
                $nestedData['var'] = $value['var']; 
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

    public function addDataUpdate(){
        $plant = $this->session->userdata['ADMIN']['plant'];
        $storage_location = $this->session->userdata['ADMIN']['storage_location'];
        $plant_name = $this->session->userdata['ADMIN']['plant_name'];
        $storage_location_name = $this->session->userdata['ADMIN']['storage_location_name'];
        $admin_id = $this->session->userdata['ADMIN']['admin_id'];

        $idWhole = $this->input->post('id_whole');
        $idWholeDetail = $this->input->post('idTwtsnewDetail');
        $itemCode = $this->input->post('item_code');
        $itemDesc = $this->input->post('item_desc');
        $quantityPaket = $this->input->post('qty_paket');
        $approve = $this->input->post('appr');
        $postingDate = $this->l_general->str_to_date($this->input->post('postingDate'));
        $date = date('ymd');

        $twtsnew_header['id_twtsnew_header'] = $idWhole;
        $twtsnew_header['kode_paket'] = $itemCode;
        $twtsnew_header['nama_paket'] = $itemDesc;
        $twtsnew_header['plant'] = $plant;
        $twtsnew_header['quantity_paket'] = $quantityPaket;
        $twtsnew_header['id_user_input'] = $admin_id;
        $twtsnew_header['id_user_approved'] = $approve ? $admin_id : 0;
        $twtsnew_header['status'] = $approve? $approve : '1';
        $twtsnew_header['posting_date'] = $postingDate;

        $sum_detail = (count($idWholeDetail));

        $twtsnew_update_header = $this->wl_model->update_twtsnew_header($twtsnew_header);
        $succes_update = false;
        if($twtsnew_update_header){
            if ($this->wl_model->twtsnew_details_delete($idWhole)) {
                for($i=0; $i < $sum_detail; $i++){
                    $twtsnew_detail['id_twtsnew_header'] = $this->input->post('id_whole');
                    $twtsnew_detail['id_twtsnew_h_detail'] = $i+1;
                    $twtsnew_detail['material_no'] = $this->input->post('detMatrialNo')[$i];            
                    $twtsnew_detail['material_desc'] = $this->input->post('detMatrialDesc')[$i];
                    $twtsnew_detail['quantity'] = $this->input->post('detQty')[$i];            
                    $twtsnew_detail['uom'] = $this->input->post('detUom')[$i];
                    $twtsnew_detail['var'] = $this->input->post('Vol')[$i];
                    $item = $this->input->post('detMatrialNo')[$i];

                    $q = $this->wl_model->jumBatch($item,$plant);
                    $count1 =$q['jml']+1;
                    if ($count1 > 9 && $count1 < 100) {
                        $dg="0";
                    } else {
                        $dg="00";
                    }
                    $num=$item.$date.$dg.$count1;
                    $twtsnew_detail['num'] = $num;
    
                    if($this->wl_model->twtsnew_detail_insert($twtsnew_detail))
                        $succes_update = true;
                }   
            }
        }

        if($succes_update){
            return $this->session->set_flashdata('success', "Cake Cutting Telah Berhasil Terupdate");
        }else{
            return $this->session->set_flashdata('failed', "Cake Cutting Gagal Terupdate");
        }
    }

    public function deleteData(){
        $id_twtsnew_header = $this->input->post('deleteArr');
        $deleteData = false;
        foreach($id_twtsnew_header as $id){
            $cek = $this->wl_model->twtsnew_header_delete($id);
            if(!$cek){
                $deleteData = false;
            }else{
                $deleteData = true;
            }
        }

        if($deleteData){
            return $this->session->set_flashdata('success', "Cake Cutting Berhasil dihapus");
        }else{
            return $this->session->set_flashdata('failed', "Cake Cutting Approved, Gagal dihapus");
        }
    }
}
?>