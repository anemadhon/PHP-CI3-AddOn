<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Sr_model extends CI_Model {

    function __construct(){ 
        parent::__construct(); 
    } 

    function showOutlet(){
        $this->db->from('m_outlet');
        $this->db->order_by("OUTLET", "asc");
        $this->db->not_like('OUTLET', 'T.','after');

        $query = $this->db->get();
        $ret = $query->result_array();
        return $ret;
    }

    function showMatrialGroup(){
        $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
        $SAP_MSI->distinct();
        $SAP_MSI->select('ItmsGrpNam');
        $SAP_MSI->from('OITB t0');
        $SAP_MSI->join('OITM t1 with (NOLOCK)','t0.ItmsGrpCod = t1.ItmsGrpCod','inner');
        $SAP_MSI->where('t1.validFor', 'Y');
        $SAP_MSI->where("ISNULL(t1.U_CantRequest,'') <> 'Y' ", null, false);

        $query = $SAP_MSI->get();
        $ret = $query->result_array();
        return $ret;
    }

    function stdstock_headers($fromDate='', $toDate='', $status='',$rto=''){
        $plant = $this->session->userdata['ADMIN']['plant'];

        $this->db->select('* ,(select OUTLET_NAME1 from m_outlet where OUTLET = t_stdstock_header.to_plant) as OUTLET_NAME1,(select admin_realname from d_admin where admin_id = t_stdstock_header.id_user_input) as user_input, (select admin_realname from d_admin where admin_id = t_stdstock_header.id_user_approved) as user_approved, (select admin_realname from d_admin where admin_id = t_stdstock_header.id_user_cancel) as user_cancel');
        $this->db->from('t_stdstock_header');
        
        $this->db->where('t_stdstock_header.plant', $plant);
        if((!empty($fromDate)) || (!empty($toDate))){
            if( (!empty($fromDate)) || (!empty($toDate)) ) {
            $this->db->where("delivery_date BETWEEN '$fromDate' AND '$toDate'");
          } else if( (!empty($fromDate))) {
            $this->db->where("delivery_date >= '$fromDate'");
          } else if( (!empty($toDate))) {
            $this->db->where("delivery_date <= '$toDate'");
          }
        }
        if((!empty($status))){
            $this->db->where('status', $status);
        }
        if((!empty($rto))){
            $this->db->where('to_plant', $rto);
        }

        $this->db->order_by('id_stdstock_header', 'desc');

        $query = $this->db->get();

        $ret = $query->result_array();
        return $ret;
    }

    function getDataMaterialGroup($item_group_code ='all', $flagCK){
        $kd_plant = $this->session->userdata['ADMIN']['plant'];
        $trans_type = 'stdstock';
        $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
        $SAP_MSI->select('t0.ItemCode as MATNR,t0.ItemName as MAKTX,t0.ItmsGrpCod as DISPO,t0.InvntryUom as UNIT,t1.ItmsGrpNam as DSNAM');
        $SAP_MSI->from('OITM  t0 with (NOLOCK)');
        $SAP_MSI->join('oitb t1','t1.ItmsGrpCod = t0.ItmsGrpCod','inner');
        $SAP_MSI->where('validFor', 'Y');
        $SAP_MSI->where('InvntItem', 'Y');
        $SAP_MSI->where("ISNULL(t0.U_CantRequest,'') <> 'Y' ", null, false);
        
        if($item_group_code !='all'){
            $SAP_MSI->where('t1.ItmsGrpNam', $item_group_code);
        }

        if ($flagCK == 'Y') {
            $SAP_MSI->where('U_OrderCK', 'Y');
        }
        
        $SAP_MSI->order_by('t0.ItemCode', 'desc');

        $query = $SAP_MSI->get();
        
        if(($query)&&($query->num_rows()>0))
            return $query->result_array();
		else
			return FALSE;
    }

    function checkCentralKitchenFlag($rto) {
        $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
        $SAP_MSI->select('U_CentralKitchen');
        $SAP_MSI->from('OWHS');
        $SAP_MSI->where('WhsCode', $rto);
        $query = $SAP_MSI->get();
        
        return $query->row_array();
    }

    function getDataMaterialGroupSelect($itemSelect){
        $kd_plant = $this->session->userdata['ADMIN']['plant'];
        $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
        if(($itemSelect != '') || ($itemSelect != null)){
            
            $SAP_MSI->select('t0.ItemCode as MATNR,t0.ItemName as MAKTX,t0.ItmsGrpCod as DISPO,t0.InvntryUom as UNIT,t1.ItmsGrpNam as DSNAM');
            $SAP_MSI->from('OITM  t0 with (NOLOCK)');
            $SAP_MSI->join('oitb t1','t1.ItmsGrpCod = t0.ItmsGrpCod','inner');
            $SAP_MSI->where('ItemCode', $itemSelect);
            $SAP_MSI->where('validFor', 'Y');
            $SAP_MSI->where('InvntItem', 'Y'); 
            $SAP_MSI->where("ISNULL(t0.U_CantRequest,'') <> 'Y' ", null, false);

            $query = $SAP_MSI->get();
            return $query->result_array();
        }else{
            return false;
        }
    }

    function getDataOnHand($itemSelect, $reqPlant){
        $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
        $SAP_MSI->select('(OnHand - isnull((select sum(OpenQty) from WTQ1 a where a.ItemCode = oitw.ItemCode 
		and a.WhsCode = oitw.WhsCode),0)) as OnHand');
        $SAP_MSI->from('OITW');
        $SAP_MSI->where('WhsCode', $reqPlant); 
        $SAP_MSI->where('ItemCode', $itemSelect);
        $query = $SAP_MSI->get();

        if(($query)&&($query->num_rows()>0))
            return $query->result_array();
		else
			return FALSE;
    }

    function id_stdstock_plant_new_select($id_outlet,$created_date="",$id_stdstock_header="") {

        if (empty($created_date))
           $created_date=$this->m_general->posting_date_select_max();
        if (empty($id_outlet))
           $id_outlet=$this->session->userdata['ADMIN']['plant'];

		$this->db->select_max('id_stdstock_plant');
		$this->db->from('t_stdstock_header');
		$this->db->where('plant', $id_outlet);
	  	$this->db->where('created_date', $created_date);
        if (!empty($id_stdstock_header)) {
    		$this->db->where('id_stdstock_header <> ', $id_stdstock_header);
        }

		$query = $this->db->get();

		if($query->num_rows() > 0) {
			$stdstock = $query->row_array();
			$id_stdstock_outlet = $stdstock['id_stdstock_plant'] + 1;
		}	else {
			$id_stdstock_outlet = 1;
		}

		return $id_stdstock_outlet;
    }
    
    function stdstock_header_insert($data) {
		if($this->db->insert('t_stdstock_header', $data))
			return $this->db->insert_id();
		else
			return FALSE;
    }
    
    function stdstock_detail_insert($data) {
		if($this->db->insert('t_stdstock_detail', $data))
			return $this->db->insert_id();
		else 
			return FALSE;
    }
    
    function stdstock_header_select($id_stdstock_header){
        $kd_plant = $this->session->userdata['ADMIN']['plant'];
        $this->db->from('t_stdstock_header');
        $this->db->join('m_outlet', 'm_outlet.OUTLET = t_stdstock_header.to_plant');
        $this->db->where('id_stdstock_header', $id_stdstock_header);
        $this->db->where('t_stdstock_header.plant',$kd_plant);
        
        $query = $this->db->get();
    
        if(($query)&&($query->num_rows() > 0)){
          return $query->row_array();
        }else{
          return FALSE;
        }
    }

    function stdstock_details_select($id_stdstock_header) {
		$this->db->from('t_stdstock_detail');
        $this->db->where('id_stdstock_header', $id_stdstock_header);
        $this->db->order_by('id_stdstock_detail');

        $query = $this->db->get();

        if(($query)&&($query->num_rows() > 0))
            return $query->result_array();
        else
            return FALSE;
    }

    function cancelCheck($DocEntry){
        $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
        $SAP_MSI->select('DocStatus');
        $SAP_MSI->from('ODRF');
        $SAP_MSI->where('DocEntry',$DocEntry);

        $query = $SAP_MSI->get();
        $cancel = $query->row_array();
        return $cancel;
    }

    function changeUpdateToDb($data){
        $this->db->where('id_stdstock_detail', $data['id_stdstock_detail']);
        if($this->db->update('t_stdstock_detail', $data))
			return TRUE;
		else
			return FALSE;
    }

    function stdstock_header_cancel($data){
        $cancel = array(
            'status' => 0,
            'id_user_cancel' => $data['id_user_cancel']
        );

        $this->db->where('id_stdstock_header', $data['id_stdstock_header']);
        if($this->db->update('t_stdstock_header', $cancel))
            return TRUE;
        else
            return FALSE;
    }

    function stdstock_header_update($data){
        $update = array(
            'delivery_date' => $data['delivery_date'],
            'created_date' => $data['created_date'],
            'status' => $data['status'],
            'request_reason' => $data['request_reason'],
            'id_user_approved' => $data['id_user_approved']
        );

        $this->db->where('id_stdstock_header', $data['id_stdstock_header']);
        if($this->db->update('t_stdstock_header', $update))
            return TRUE;
        else
            return FALSE;
    }

    function stdstock_header_delete($id_stdstock_header){
        $this->db->select('pr_no');
        $this->db->from('t_stdstock_header');
        $this->db->where('id_stdstock_header', $id_stdstock_header);
        $query = $this->db->get();
        $dataArr = $query->result_array();
        if($dataArr[0]['pr_no'] != ''){
            if($this->cekNoSRinTO($dataArr[0]['pr_no'])){
                return FALSE;
            }else{
                if($this->stdstock_details_delete($id_stdstock_header)){
                    $this->db->where('id_stdstock_header', $id_stdstock_header);
                    if($this->db->delete('t_stdstock_header'))
                        return TRUE;
                    else
                        return FALSE;
                }

            }
        }else{
            if($this->stdstock_details_delete($id_stdstock_header)){
                $this->db->where('id_stdstock_header', $id_stdstock_header);
                if($this->db->delete('t_stdstock_header'))
                    return TRUE;
                else
                    return FALSE;
            }
        }

        if($this->stdstock_details_delete($id_stdstock_header)){
            $this->db->where('id_stdstock_header', $id_stdstock_header);
            if($this->db->delete('t_stdstock_header'))
                return TRUE;
            else
                return FALSE;
        }
    }

    function delete_stdstock_details($id_stdstock_header) {
        $this->db->where('id_stdstock_header', $id_stdstock_header);
        if($this->db->delete('t_stdstock_detail'))
            return TRUE;
        else
            return FALSE;
    }
    
    function stdstock_details_delete($id_stdstock_header) {
        $data = $this->stdstock_header_select($id_stdstock_header);
        $status = $data['status'];
        if ($status!=2) {
            $this->db->where('id_stdstock_header', $id_stdstock_header);
            if($this->db->delete('t_stdstock_detail'))
                return TRUE;
            else
                return FALSE;
        } else {
            return FALSE;
        }
    }
    
    function tampil($id_stdstock_header){
        $this->db->select('a.pr_no1, a.created_date, a.delivery_date, b.material_no, b.material_desc, b.uom, b.requirement_qty, b.price, a.plant, a.plant_name, a.id_user_approved , to_plant, c.OUTLET_NAME1, a.request_reason');
        $this->db->from('t_stdstock_header a');
        $this->db->join('t_stdstock_detail b','a.id_stdstock_header = b.id_stdstock_header','left');
        $this->db->join('m_outlet c','a.to_plant=c.OUTLET','inner');
        $this->db->where('a.id_stdstock_header', $id_stdstock_header);
        
        $query = $this->db->get();

        return $query->result_array();
    }
    
    function userApproved($id_user_approved=''){
        $this->db->select('admin_realname');
        $this->db->from('d_admin');
        $this->db->where('admin_id',$id_user_approved);

        $query = $this->db->get();

        return $query->result_array();
    }

    function cekNoSRinTO($srNo){
        $this->db->from('t_gistonew_out_header');
        $this->db->where('po_no',$srNo);
        $this->db->where('status','2');
        $query = $this->db->get();
        if(($query)&&($query->num_rows() > 0))
            return TRUE;
        else
            return FALSE;
    }
}