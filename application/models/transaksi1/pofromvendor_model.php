<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Pofromvendor_model extends CI_Model {

  public function getDataPoVendor_Header($fromDate='', $toDate='', $status=''){
    $kd_plant = $this->session->userdata['ADMIN']['plant'];
      $this->db->select('t_grpo_header.*,(select admin_realname from d_admin where admin_id = t_grpo_header.id_user_input) as user_input, (select admin_realname from d_admin where admin_id = t_grpo_header.id_user_approved) as user_approved, OUTLET_NAME1 ');
      $this->db->from('t_grpo_header');
      $this->db->join('m_outlet', 'm_outlet.OUTLET = t_grpo_header.plant');
      $this->db->where('plant', $kd_plant);
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

      $this->db->order_by('id_grpo_header', 'desc');
      $query = $this->db->get();
      $ret = $query->result_array();
      return $ret;
  }

  public function sap_grpo_headers_select_by_kd_vendor($kd_vendor="",$kd_plant="",$po_no="",$po_item=""){
    $kd_plant = $this->session->userdata['ADMIN']['plant'];
    $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
    
    $SAP_MSI->select("POR1.DocEntry as EBELN,LineNum as EBELP,OPOR.CardCode as VENDOR, OPOR.CardName as VENDOR_NAME,
    POR1.ItemCode as MATNR,Dscription as MAKTX,OpenQty as BSTMG,
    unitMsr as BSTME, ItmsGrpCod as DISPO,CONVERT(VARCHAR(8),POR1.ShipDate,112) as DELIV_DATE, 
    SeriesName + RIGHT('0000000'  + CONVERT(varchar, OPOR.DocNum), 7) as DOCNUM, 
    SeriesName + RIGHT('0000000'  + u_odocnum, 7) as OLDDOCNUM ");
    $SAP_MSI->from('POR1');
    $SAP_MSI->join('OPOR','POR1.DocEntry = OPOR.DocEntry');
    $SAP_MSI->join('OITM with (NOLOCK) ','POR1.ItemCode = OITM.ItemCode');
    $SAP_MSI->join('NNM1','OPOR.Series = NNM1.Series');
    $SAP_MSI->where('WhsCode',$kd_plant);
    $SAP_MSI->where('OPOR.DocStatus' ,'O');
    $SAP_MSI->where('OpenQty >', 0);
    
    if(!empty($po_no)) {
        $SAP_MSI->where('POR1.DocEntry',$po_no);
    }
    if(!empty($po_item)) {
        $SAP_MSI->where('LineNum',$po_item);
    }

    $SAP_MSI->order_by('DOCNUM', 'asc');
    $query = $SAP_MSI->get();

    if(($query)&&($query->num_rows() > 0)) {
        $pos = $query->result_array();
        $count = count($pos);
        $k = 1;
        for ($i=0;$i<=$count-1;$i++) {
            $po[$k] = $pos[$i];
            $k++;
        }
        return $po;
    } else {
        return FALSE;
    }
  }

  function sap_grpo_details_select_by_po_no($po_no) {
    if (empty($this->session->userdata['grpo_nos'])) {
        $poitems = $this->sap_grpo_headers_select_by_kd_vendor("","",$po_no);
    } else {
        $po_nos = $this->session->userdata['grpo_nos'];
        $k = 1;
        $count = count($po_nos);
        for ($i=1;$i<=$count;$i++) {
          if ($po_nos[$i]['EBELN']==$po_no){
            $poitems[$k] = $po_nos[$i];
            $k++;
          }
        }
    }
    $count = count($poitems);
    if ($count > 0) {
      for($i=1;$i<=$count;$i++) {
        $poitems[$i]['id_grpo_h_detail'] = $i;
      }
      return $poitems;
    }
    else {
      unset($poitems);
      return FALSE;
    }
  }
  
  function sap_item_groups_select_all() {
  
    $kd_plant = $this->session->userdata['ADMIN']['plant'];
    $this->db->from('m_item_group');
        $this->db->where('kdplant', $kd_plant);

    $query = $this->db->get();
    if(($query)&&($query->num_rows() > 0)) {
        $item_groups = $query->result_array();
      $count = count($item_groups);
      $k = 1;
      for ($i=0;$i<=$count-1;$i++) {
        $item_group[$k] = $item_groups[$i];
        $k++;
      }
      return $item_group;
    } else {
      return FALSE;
    }
  }

  function sap_grpo_select_item_group_po($po_no) {
    $poitems = $this->sap_grpo_details_select_by_po_no($po_no);
    $item_groups = $this->sap_item_groups_select_all();
    $count = count($item_groups);
    $count_po = count($poitems);
    $k = 1;
    for ($i=1;$i<=$count;$i++) {
      for($j=1;$j<=$count_po;$j++) {
        if ($poitems[$j]['DISPO']==$item_groups[$i]['DISPO']){
          $item_groups_filter[$k] = $item_groups[$i]['DSNAM'];
          $k++;
          break;
        }
      }
    }
    if (count($item_groups_filter) > 0) {
      $item_groups_filter = array_unique($item_groups_filter);
      return $item_groups_filter;
    } else {
      return FALSE;
    }
  }

  function grpo_header_select($id_grpo_header){
    $this->db->from('t_grpo_header');
    $this->db->where('id_grpo_header', $id_grpo_header);
    
    $query = $this->db->get();

    if(($query)&&($query->num_rows() > 0)){
      return $query->row_array();
    }else{
      return FALSE;
    }
  }

  function grpo_details_select($id_grpo_header) {
		$this->db->from('t_grpo_detail');
    $this->db->where('id_grpo_header', $id_grpo_header);
    $this->db->where('ok_cancel', '0');
		$this->db->order_by('id_grpo_detail');

		$query = $this->db->get();

		if(($query)&&($query->num_rows() > 0))
			return $query->result_array();
		else
			return FALSE;
  }

  function posting_date_select_max() {
    $kd_plant = $this->session->userdata['ADMIN']['plant'];
    $this->db->select_max('posting_date');
    $this->db->from('t_posinc_header');
    $this->db->where('plant', $kd_plant);
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
  
  function id_grpo_plant_new_select($id_outlet,$posting_date="",$id_grpo_header="") {

    if (empty($posting_date))
       $posting_date=$this->posting_date_select_max();
    if (empty($id_outlet))
       $id_outlet=$this->session->userdata['ADMIN']['plant'];

    $this->db->select_max('id_grpo_plant');
    $this->db->from('t_grpo_header');
    $this->db->where('plant', $id_outlet);
    $this->db->where('posting_date', $posting_date);
    if (!empty($id_grpo_header)) {
      $this->db->where('id_grpo_header <> ', $id_grpo_header);
    }

    $query = $this->db->get();

    if(($query)&&($query->num_rows() > 0)) {
      $grpo = $query->row_array();
      $id_grpo_outlet = $grpo['id_grpo_plant'] + 1;
    }	else {
      $id_grpo_outlet = 1;
    }

    return $id_grpo_outlet;
  }

  function grpo_header_insert($data) {
		if($this->db->insert('t_grpo_header', $data))
			return $this->db->insert_id();
		else
			return FALSE;
  }
  
  function grpo_detail_insert($data) {
    if($this->db->insert('t_grpo_detail', $data))
      return $this->db->insert_id();
		else
			return FALSE;
  }

  function grpo_header_update($data) {
    $update = array(
      'status' => $data['status'],
      'remark' => $data['remark'],
      'posting_date' => $data['posting_date'],
      'id_user_approved' => $data['id_user_approved']
    );
    $this->db->set('posting_date', $data['posting_date']);
		$this->db->where('id_grpo_header', $data['id_grpo_header']);
    if($this->db->update('t_grpo_header', $update))
      return TRUE;
		else
			return FALSE;
  }
  
  function grpo_detail_update($data) {
    $update = array(
      'gr_quantity' => $data['gr_quantity'],
      'qc' => $data['qc']
    );
		$this->db->where('id_grpo_detail', $data['id_grpo_detail']);
    if($this->db->update('t_grpo_detail', $update))
			return TRUE;
		else
			return FALSE;
	}
  
  function cancelHeaderPoFromVendor($data){
    $this->db->set('status', '3');
    $this->db->where('id_grpo_header', $data['id_grpo_header']);
    if($this->db->update('t_grpo_header')){
      return TRUE;
    }else{
      return FALSE;
    }
  }

  function cancelDetailsPoFromVendor($data){
    $this->db->set('ok_cancel', '1');
    $this->db->where('id_grpo_detail', $data);
    if($this->db->update('t_grpo_detail')){
      return TRUE;
    }else{
      return FALSE;
    }
  }

  function grpo_header_delete($id_grpo_header){
    $data = $this->grpo_header_select($id_grpo_header);
    $back = $data['status'];
    if($back != 2){
      if($this->grpo_details_delete($id_grpo_header)){
        $this->db->where('id_grpo_header', $id_grpo_header);
        if($this->db->delete('t_grpo_header'))
            return TRUE;
        else
            return FALSE;
        }
    }else{
      return FALSE;
    }  
  }

  function grpo_details_delete($id_grpo_header) {
  $this->db->where('id_grpo_header', $id_grpo_header);
    if($this->db->delete('t_grpo_detail'))
      return TRUE;
    else
      return FALSE;
  }

  function sap_get_nopp($po_no){
    $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
    $SAP_MSI->select('A.DocEntry, C.U_DocNum');
    $SAP_MSI->from('PRQ1 A');
    $SAP_MSI->join('POR1 B','B.BaseEntry=A.DocEntry');
    $SAP_MSI->join('OPRQ C','A.DocEntry=C.DocEntry');
    $SAP_MSI->where('B.DocEntry',$po_no);
    $query = $SAP_MSI->get();
    $ret = $query->result_array();

    if (count($ret) > 0){
        return $ret[0]['U_DocNum'];
      }else{
        return '';
      }
  }

  function get_no_po($doc) {
    $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
    $SAP_MSI->select('C.DocEntry');
    $SAP_MSI->from('POR1 A');
    $SAP_MSI->join('PRQ1 B','A.BaseEntry=B.DocEntry AND A.BaseType=B.ObjType','inner');
    $SAP_MSI->join('OPOR C','A.DocEntry=C.DocEntry','inner');
    $SAP_MSI->join('nnm1 D','C.Series = D.Series AND C.ObjType=D.ObjectCode','inner');
    $SAP_MSI->where('B.DocEntry',$doc);
    $SAP_MSI->group_by("C.DocEntry");
    $query = $SAP_MSI->get();

    $s = $query->row_array();

    if (empty($s)) {
      $doc1 = 0;
    } else {
      $doc1 = $s['DocEntry'];
    }

    $SAP_MSI->select("isnull(SeriesName,'')+right(replicate('0',7)+convert(varchar,docnum),7) AS NoDoc");
    $SAP_MSI->from('OPOR a');
    $SAP_MSI->join('nnm1 b','a.Series = b.Series AND a.ObjType = b.ObjectCode','inner');
    $SAP_MSI->where('a.DocEntry',$doc1);
    $querypo = $SAP_MSI->get();
    if($querypo->num_rows() > 0) {
      $nopo = $querypo->row_array();
      $po = $nopo['NoDoc'];
    }	else {
      $po = '';
    }

    return $po;
  }

  function tampilPdf($id){
    $query = $this->db->query("SELECT a.po_no, a.posting_date, a.grpo_no, a.docnum, a.delivery_date, a.kd_vendor, a.nm_vendor, a.plant, a.plant_name, a.remark, b.material_no, b.material_desc, b.uom, b.outstanding_qty, b.gr_quantity,b.item FROM t_grpo_header a JOIN t_grpo_detail b ON a.id_grpo_header = b.id_grpo_header where a.id_grpo_header ='$id' ");
      
    return $query;  
  }

}