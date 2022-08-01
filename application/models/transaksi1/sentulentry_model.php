<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Sentulentry_model extends CI_Model {

    public function getSentulEntryHeaderIn($fromDate='', $toDate='', $status=''){
        $kd_plant = $this->session->userdata['ADMIN']['plant'];
        $this->db->select('*, (select admin_realname from d_admin where admin_id = t_sentul_entry_header.id_user_input) as user_input, (select admin_realname from d_admin where admin_id = t_sentul_entry_header.id_user_update) as user_update,  (select admin_realname from d_admin where admin_id = t_sentul_entry_header.id_user_cancel) as user_cancel');
        $this->db->from('t_sentul_entry_header');
        $this->db->where('plant',$kd_plant);

        if((!empty($fromDate)) || (!empty($toDate))){
            if( (!empty($fromDate)) || (!empty($toDate)) ) {
            $this->db->where("posting_date BETWEEN '$fromDate' AND '$toDate'");
            } else if( (!empty($fromDate))) {
            $this->db->where("posting_date >= '$fromDate'");
            } else if( (!empty($toDate))) {
            $this->db->where("posting_date <= '$toDate'");
            }
        }
        if((!empty($status))){
            $this->db->where('status', $status);
        }
        $this->db->where('type_doc', 'IN');
        $this->db->where('status <>', 2);

        $this->db->order_by('id_sentul_entry_header', 'desc');
        $query = $this->db->get();
        $ret = $query->result_array();
        return $ret;
    }
    
    public function getSentulEntryHeaderOut($fromDate='', $toDate='', $status=''){
        $kd_plant = $this->session->userdata['ADMIN']['plant'];
        $this->db->select('*, (select admin_realname from d_admin where admin_id = t_sentul_entry_header.id_user_input) as user_input, (select admin_realname from d_admin where admin_id = t_sentul_entry_header.id_user_update) as user_update,  (select admin_realname from d_admin where admin_id = t_sentul_entry_header.id_user_cancel) as user_cancel');
        $this->db->from('t_sentul_entry_header');
        $this->db->where('plant',$kd_plant);

        if((!empty($fromDate)) || (!empty($toDate))){
            if( (!empty($fromDate)) || (!empty($toDate)) ) {
            $this->db->where("posting_date BETWEEN '$fromDate' AND '$toDate'");
            } else if( (!empty($fromDate))) {
            $this->db->where("posting_date >= '$fromDate'");
            } else if( (!empty($toDate))) {
            $this->db->where("posting_date <= '$toDate'");
            }
        }
        if((!empty($status))){
            $this->db->where('status', $status);
        }
        $this->db->where('type_doc', 'OUT');
        $this->db->where('status <>', 2);

        $this->db->order_by('id_sentul_entry_header', 'desc');
        $query = $this->db->get();
        $ret = $query->result_array();
        return $ret;
    }

    function idSentulEntryNewPlantSelect($id_outlet, $created_date='', $id_sentulentry_header='') {

        if (empty($created_date))
           $created_date=$this->m_general->posting_date_select_max();
        if (empty($id_outlet))
           $id_outlet=$this->session->userdata['ADMIN']['plant'];

		$this->db->select_max('id_sentul_entry_plant');
		$this->db->from('t_sentul_entry_header');
		$this->db->where('plant', $id_outlet);
	  	$this->db->where('posting_date', $created_date);
        if (!empty($id_sentulentry_header)) {
    		$this->db->where('id_sentul_entry_header <> ', $id_sentulentry_header);
        }

		$query = $this->db->get();

		if($query->num_rows() > 0) {
			$stdstock = $query->row_array();
			$id_stdstock_outlet = $stdstock['id_sentul_entry_plant'] + 1;
		}	else {
			$id_stdstock_outlet = 1;
		}

		return $id_stdstock_outlet;
    }

    function sentulEntryInsertHeader($data) {
		if($this->db->insert('t_sentul_entry_header', $data))
			return $this->db->insert_id();
		else
			return FALSE;
    }

    function sentulEntryInsertDetails($data) {
		if($this->db->insert('t_sentul_entry_detail', $data))
			return $this->db->insert_id();
		else
			return FALSE;
    }

    function sentulEntrySelectHeader($id_sentulentry_header) {
        $this->db->select('*, (SELECT OUTLET_NAME1 FROM m_outlet WHERE OUTLET = t_sentul_entry_header.plant) PLANT_NAME_NEW, (SELECT STOR_LOC_NAME FROM m_outlet WHERE STOR_LOC = t_sentul_entry_header.storage_location) STOR_LOC_NAME');
		$this->db->from('t_sentul_entry_header');
		$this->db->where('id_sentul_entry_header', $id_sentulentry_header);

		$query = $this->db->get();

		if($query->num_rows() > 0)
			return $query->row_array();
		else
			return FALSE;
    }

    function sentulEntrySelectDetails($id_sentul_entry_header) {
		$this->db->from('t_sentul_entry_detail');
        $this->db->where('id_sentul_entry_header', $id_sentul_entry_header);
        $query = $this->db->get();

        if(($query)&&($query->num_rows() > 0))
            return $query->result_array();
        else
            return FALSE;
    }

    function sentulEntryUpdateHeader($data){
        $update = array(
            'status' => $data['status'],
            'posting_date' => $data['posting_date'],
            'remark' => $data['remark'],
            'id_user_update' => $data['id_user_update']
        );
        $this->db->where('id_sentul_entry_header', $data['id_sentul_entry_header']);
        if($this->db->update('t_sentul_entry_header', $update))
			return TRUE;
		else
			return FALSE;
    }

    function sentulEntryDeleteHeader($id_sentulentry_header){
        if($this->sentulEntryDeleteDetails($id_sentulentry_header)){
            $this->db->where('id_sentul_entry_header', $id_sentulentry_header);
            if($this->db->delete('t_sentul_entry_header'))
                return TRUE;
            else
                return FALSE;
        }
    }

    function sentulEntryDeleteDetails($id_sentulentry_header) {
        $this->db->where('id_sentul_entry_header', $id_sentulentry_header);
        if($this->db->delete('t_sentul_entry_detail'))
            return TRUE;
        else
            return FALSE;
    }

    function cancelDocument($data){
        $cancel = array(
            'status' => $data['status'],
            'cancel_reason' => $data['cancel_reason'],
            'id_user_cancel' => $data['id_user_cancel']
        );
        $this->db->where('id_sentul_entry_header', $data['id_sentul_entry_header']);
        if($this->db->update('t_sentul_entry_header', $cancel))
			return TRUE;
		else
			return FALSE;
    }

    function getDataMaterialGroup($item_group_code ='all'){
        $kd_plant = $this->session->userdata['ADMIN']['plant'];
        $trans_type = 'grnonpo';

        $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
        $SAP_MSI->select('t0.ItemCode as MATNR,t0.ItemName as MAKTX,t0.ItmsGrpCod as DISPO,t0.InvntryUom as UNIT,t1.ItmsGrpNam as DSNAM');
        $SAP_MSI->from('OITM t0 with (NOLOCK)');
        $SAP_MSI->join('OITB t1','t1.ItmsGrpCod = t0.ItmsGrpCod','inner');
        //$SAP_MSI->where("t1.ItmsGrpCod in (select ItmsGrpCod from oitm where U_GrNonPO = 'Y')");
        $SAP_MSI->where('validFor', 'Y');
        $SAP_MSI->where('InvntItem', 'Y');

        if($item_group_code !='all'){
            $SAP_MSI->where('t1.ItmsGrpNam', $item_group_code);
        }

        $query = $SAP_MSI->get();

        if(($query)&&($query->num_rows()>0))
            return $query->result_array();
		else
			return FALSE;
    }
    
    function getDataMaterialGroupSelect($itemSelect){
        $trans_type = 'stdstock';
        $kd_plant = $this->session->userdata['ADMIN']['plant'];
        if(($itemSelect != '') || ($itemSelect != null)){

            $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
            $SAP_MSI->select('t0.ItemCode as MATNR,t0.ItemName as MAKTX,t0.ItmsGrpCod as DISPO,t0.InvntryUom as UNIT,t1.ItmsGrpNam as DSNAM');
            $SAP_MSI->from('OITM  t0 with (NOLOCK) ');
            $SAP_MSI->join('OITB t1','t1.ItmsGrpCod = t0.ItmsGrpCod','inner');
            $SAP_MSI->where('validFor', 'Y');
            $SAP_MSI->where('InvntItem', 'Y');
            $SAP_MSI->where('t0.ItemCode',$itemSelect);

            $query = $SAP_MSI->get();
            return $query->result_array();
        }else{
            return false;
        }
    }

    function getReasons($type){
        $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
        $query = $SAP_MSI->query(" SELECT * FROM [@ybc_dt_reason] WHERE u_rsntype = '$type' ");
        $reasons = $query->result_array();
        return $reasons;
    }
}