<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_model extends CI_Model {

  function freeze(){
    $kd_plant = $this->session->userdata['ADMIN']['plant'];
    $this->db->distinct();
    $this->db->select('a.id_opname_header, status, freeze, am_approved, rm_approved');
    $this->db->from('t_opname_header a');
    $this->db->join('t_opname_detail b','a.id_opname_header = b.id_opname_header');
    $this->db->where('freeze','Y');
    $this->db->where('plant',$kd_plant);
    
    $query = $this->db->get();

    if(($query)&&($query->num_rows() > 0)){
      return $query->result_array();
    }else{
      return FALSE;
    }
  }
  
  function opname_headers($fromDate, $toDate, $status){
    $kd_plant = $this->session->userdata['ADMIN']['plant'];
    $this->db->select('t_opname_header.*,(select admin_realname from d_admin where admin_id = t_opname_header.id_user_input) as user_input, (select admin_realname from d_admin where admin_id = t_opname_header.id_user_approved) as user_approved ');
    $this->db->from('t_opname_header');
    $this->db->where('t_opname_header.plant',$kd_plant);
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

    $this->db->order_by('id_opname_header', 'desc');

    $query = $this->db->get();
    $ret = $query->result_array();
    return $ret;

  }

  function tampil($id_opname_header){
    $this->db->select('a.opname_no,  a.created_date,b.material_no,b.material_desc,b.uom,b.requirement_qty,a.plant, a.id_user_approved,b.num, (select admin_realname from d_admin where admin_id = a.id_user_approved) as user_approved');
    $this->db->from('t_opname_header a');
    $this->db->join('t_opname_detail b','a.id_opname_header = b.id_opname_header');
    $this->db->where('a.id_opname_header', $id_opname_header);
  
    $query = $this->db->get();
    $ret = $query->result_array();
    return $ret;
  }

  function id_opname_plant_new_select($id_outlet,$created_date="",$id_pr_header="") {
    if (empty($created_date))
       $created_date=$this->posting_date_select_max();
    if (empty($id_outlet))
       $id_outlet=$this->session->userdata['ADMIN']['plant'];

    $this->db->select_max('id_pr_plant');
    $this->db->from('t_prnew_header');
    $this->db->where('plant', $id_outlet);
    $this->db->where('created_date', $created_date);
    if (!empty($id_pr_header)) {
    $this->db->where('id_pr_header <> ', $id_pr_header);
    }

    $query = $this->db->get();

    if($query->num_rows() > 0) {
      $pr = $query->row_array();
      $id_pr_outlet = $pr['id_pr_plant'] + 1;
    }	else {
      $id_pr_outlet = 1;
    }

    return $id_pr_outlet;
  }

  function posting_date_select_max() {
    $id_outlet = $this->session->userdata['ADMIN']['plant'];
    $this->db->select_max('posting_date');
    $this->db->from('t_posinc_header');
    $this->db->where('plant', $id_outlet);
    $this->db->where('status', 2);

    $query = $this->db->get();
    if ($query) {
      $posting_date = $query->row_array();
    }
    if(!empty($posting_date['posting_date'])) {
        $oneday = 60 * 60 * 24;
            $posting_date = date("Y-m-d H:i:s", strtotime($posting_date['posting_date'])+ $oneday);
            return $posting_date;
    }	else {
          return date("Y-m-d H:i:s");
    }
  }

  function opname_header_insert($data) {
		if($this->db->insert('t_opname_header', $data))
			return $this->db->insert_id();
		else
			return FALSE;
  }
  
  function opname_detail_insert($data) {
		if($this->db->insert('t_opname_detail', $data))
			return $this->db->insert_id();
		else
			return FALSE;
  }

  function opname_room_detail_insert($data) {
		if($this->db->insert('t_opname_room', $data))
			return $this->db->insert_id();
		else
			return FALSE;
  }
  
  function opname_header_delete($id_opname_header){
    $data = $this->opname_header_select($id_opname_header);
    $status = $data['status'];
    $am = $data['am_approved'];
    $rm = $data['rm_approved'];
    if ($status!=2 || $am!=2 || $rm!=2) {
      if($this->opname_details_delete($id_opname_header)){
        $this->db->where('id_opname_header', $id_opname_header);
        if($this->db->delete('t_opname_header'))
          return TRUE;
        else
          return FALSE;
      }
    } else {
      return false;
    }
  }

  function opname_details_delete($id_opname_header) {
		$this->db->where('id_opname_header', $id_opname_header);
		if($this->db->delete('t_opname_detail'))
			return TRUE;
		else
			return FALSE;
  } 

  function opname_room_delete($id_opname_header, $id_opname_detail) {
		$this->db->where('id_opname_header', $id_opname_header);
		$this->db->where('id_opname_detail', $id_opname_detail);
		if($this->db->delete('t_opname_room'))
			return TRUE;
		else
			return FALSE;
  } 

  function opname_header_select($id_opname_header){
    $kd_plant = $this->session->userdata['ADMIN']['plant'];
    $this->db->from('t_opname_header');
    $this->db->where('id_opname_header', $id_opname_header);
    $this->db->where('plant',$kd_plant);
    
    $query = $this->db->get();

    if(($query)&&($query->num_rows() > 0)){
      return $query->row_array();
    }else{
      return FALSE;
    }
  }

  function opname_details_select($id_opname_header) {
		  $this->db->from('t_opname_detail');
      $this->db->where('id_opname_header', $id_opname_header);
      $this->db->order_by('id_opname_detail');

      $query = $this->db->get();

      if(($query)&&($query->num_rows() > 0))
        return $query->result_array();
      else
        return FALSE;
  }

  function opname_room_select($id_opname_header, $id_opname_detail) {
    $this->db->from('t_opname_room');
    $this->db->where('id_opname_header', $id_opname_header);
    $this->db->where('id_opname_detail', $id_opname_detail);
    $this->db->order_by('id_opname_room');

    $query = $this->db->get();

    if(($query)&&($query->num_rows() > 0))
      return $query->result_array();
    else
      return FALSE;
}

  function opname_header_update($data){
    $update = array(
      'status' => $data['status'],
      'am_approved' => $data['am_approved'],
      'rm_approved' => $data['rm_approved'],
      'id_user_approved' => $data['id_user_approved'],
      'admin_approved_date' => $data['admin_approved_date']
    );
    $this->db->where('id_opname_header', $data['id_opname_header']);
    if($this->db->update('t_opname_header', $update))
      return TRUE;
    else
      return FALSE;
  }
  
  function opname_header_update_area_mgr($data){
    if ($data['request_reason']) {
      $update = array(
        'id_am' => $data['id_am'],
        'am_approved' => $data['am_approved'],
        'request_reason' => $data['request_reason'],
        'am_rejected_date' => $data['am_rejected_date']
      );
    } else {
      $update = array(
        'id_am' => $data['id_am'],
        'am_approved' => $data['am_approved'],
        'am_approved_date' => $data['am_approved_date']
      );
    }
    
    $this->db->where('id_opname_header', $data['id_opname_header']);
    if($this->db->update('t_opname_header', $update))
      return TRUE;
    else
      return FALSE;
  }

  function opname_header_update_reg_mgr($data){
    if ($data['request_reason']) {
      $update = array(
        'id_rm' => $data['id_rm'],
        'rm_approved' => $data['rm_approved'],
        'request_reason' => $data['request_reason'],
        'rm_rejected_date' => $data['rm_rejected_date']
      );
    } else {
      $update = array(
        'id_rm' => $data['id_rm'],
        'rm_approved' => $data['rm_approved'],
        'rm_approved_date' => $data['rm_approved_date']
      );
    }
    
    $this->db->where('id_opname_header', $data['id_opname_header']);
    if($this->db->update('t_opname_header', $update))
      return TRUE;
    else
      return FALSE;
  }

  function headTemplate(){
    $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
    
    $query = $SAP_MSI->query("SELECT Code, Name FROM [@YBC_OUTLET_ROOM] ");

    $head = $query->result_array();
    return $head;
  }
  
  function template($parameter=''){
    $kd_plant = $this->session->userdata['ADMIN']['plant'];
    $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
    $whereItemCode = '';

    if ($parameter) {
      foreach ($parameter as $value) {
        $whereItemCode .= "AND t0.ItemCode <> '".$value."' ";
      }
    }

    $where = ($parameter ? " ".$whereItemCode." " : "");
    
    $query = $SAP_MSI->query("SELECT t0.ItemCode, t2.ItmsGrpNam, t0.ItemName, t0.InvntryUom as UNIT, t1.OnHand FROM OITM t0 INNER JOIN OITW t1 ON t0.ItemCode = t1.ItemCode INNER JOIN OITB t2 ON t2.ItmsGrpCod = t0.ItmsGrpCod where t1.WhsCode = '$kd_plant' AND t2.u_soaddons = 'Y' AND ItemType = 'I' AND InvntItem= 'Y' AND t0.validFor = 'Y' ".$where." ORDER BY t2.ItmsGrpNam ASC");

    $ret = $query->result_array();
    return $ret;
  }

  function checkTemplate($code){
    $kd_plant = $this->session->userdata['ADMIN']['plant'];
    $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
    
    $query = $SAP_MSI->query("SELECT t0.ItemCode FROM OITM t0 with (NOLOCK) INNER JOIN OITW t1 ON t0.ItemCode = t1.ItemCode INNER JOIN OITB t2 ON t2.ItmsGrpCod = t0.ItmsGrpCod where t1.WhsCode ='$kd_plant' AND t2.u_soaddons = 'Y' AND ItemType = 'I' AND InvntItem= 'Y' AND t0.validFor = 'Y' AND t0.ItemCode = '$code' ");

    $valid = $query->row_array();
    return $valid;
  }

  function getBeginBalanceInOut($itemcode){
    $kd_plant = $this->session->userdata['ADMIN']['plant'];
    $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
    $SAP_MSI->select('BeginBalance, InQty, OutQty');
    $SAP_MSI->from('v_stkopnaddon');
    $SAP_MSI->where('LocCode',$kd_plant);
    $SAP_MSI->where('ItemCode',$itemcode);
    
    $query = $SAP_MSI->get();

    if(($query)&&($query->num_rows() > 0)){
      $qty = $query->row_array();
      return $qty;
    }else{
      return 0;
    }
  }  

  function getSODate() {
    $kd_plant = $this->session->userdata['ADMIN']['plant'];
    $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
    
    $query = $SAP_MSI->query("SELECT U_SODate FROM [@YBC_STK_WHS] a INNER JOIN [@YBC_STK_DATE] b ON a.Code = b.Code WHERE a.Code = '$kd_plant' ");

    if(($query)&&($query->num_rows() > 0)){
      $soDate = $query->result_array();
      return $soDate;
    }else{
      return FALSE;
    }
  }

  function getLastPrice($itemcode){
    $kd_plant = $this->session->userdata['ADMIN']['plant'];
    $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
    $SAP_MSI->select('Avgprice');
    $SAP_MSI->from('OITM with (NOLOCK)');
    $SAP_MSI->where('ItemCode',$itemcode);
    
    $query = $SAP_MSI->get();

    if(($query)&&($query->num_rows() > 0)){
      $qty = $query->row_array();
      return $qty;
    }else{
      return 0;
    }
  } 

  function getUpdateData($item_code){
    $kd_plant = $this->session->userdata['ADMIN']['plant'];
    $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
    $SAP_MSI->select('t2.ItmsGrpNam, t0.ItemName, t0.InvntryUom as uom, t1.OnHand');
    $SAP_MSI->from('OITM t0 with (NOLOCK)');
    $SAP_MSI->join('OITW t1','t0.ItemCode = t1.ItemCode');
    $SAP_MSI->join('OITB t2','t2.ItmsGrpCod = t0.ItmsGrpCod');
    $SAP_MSI->where('t1.WhsCode', $kd_plant);
    $SAP_MSI->where('t0.ItemCode', $item_code);
    $SAP_MSI->where('ItemType', 'I');
    $SAP_MSI->where('InvntItem', 'Y');
    $SAP_MSI->where('validFor', 'Y');

    $query = $SAP_MSI->get();
    $onHand = $query->row_array();
    return $onHand;
  }

  function getLastSOOutlet($last = ''){
    $kd_plant = $this->session->userdata['ADMIN']['plant'];
    $this->db->distinct();
    $this->db->select('a.id_opname_header, status, freeze, am_approved, rm_approved, posting_date');
    $this->db->from('t_opname_header a');
    $this->db->join('t_opname_detail b','a.id_opname_header = b.id_opname_header');
    $this->db->where('plant',$kd_plant);
    if ($last == 'last') {
      $this->db->where('back',0);
    }
    $this->db->order_by('a.id_opname_header','DESC');
    $this->db->limit(1);
    
    $query = $this->db->get();

    if(($query)&&($query->num_rows() > 0)){
      return $query->row_array();
    }else{
      return FALSE;
    }
  }

  function getNextSODate() {
    $kd_plant = $this->session->userdata['ADMIN']['plant'];
    $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
    
    $query = $SAP_MSI->query("SELECT MAX(U_SODate) as U_SODate FROM [@YBC_STK_WHS] a INNER JOIN [@YBC_STK_DATE] b ON a.Code = b.Code WHERE a.Code = '$kd_plant' ");

    if(($query)&&($query->num_rows() > 0)){
      $soDate = $query->row_array();
      return $soDate;
    }else{
      return FALSE;
    }
  }

  function cekTgl($tgl){
    $kd_plant = $this->session->userdata['ADMIN']['plant'];
    $this->db->distinct();
    $this->db->select('a.id_opname_header');
    $this->db->from('t_opname_header a');
    $this->db->join('t_opname_detail b','a.id_opname_header = b.id_opname_header');
    $this->db->where('plant',$kd_plant);
    $this->db->where('posting_date',$tgl);
    
    $query = $this->db->get();

    if(($query)&&($query->num_rows() > 0)){
      return $query->num_rows();
    }else{
      return 0;
    }
  }
  
  function getUpdateDataOnHand($item_code){
    $kd_plant = $this->session->userdata['ADMIN']['plant'];
    $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
    $SAP_MSI->select("OnHandStock,ItemCost");
    $SAP_MSI->from('v_stockohaddon');
    $SAP_MSI->where('whsCode', $kd_plant);
    $SAP_MSI->where('itemCode', $item_code);
    $query = $SAP_MSI->get();
    $onHand = $query->row_array();
    return $onHand;
  }

  function checkAMRM($ids){
    $this->db->select('group_name');
    $this->db->from('d_perm_group');
    $this->db->where_in('group_id', $ids);
    $query = $this->db->get();

    if(($query)&&($query->num_rows() > 0)){
      return $query->result_array();
    }else{
      return FALSE;
    }
  }

}