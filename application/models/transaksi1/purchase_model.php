<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase_model extends CI_Model {
  
  function t_pr_headers($date_from2, $date_to2, $status){
    $kd_plant = $this->session->userdata['ADMIN']['plant'];
    $this->db->select('t_prnew_header.*,(select admin_realname from d_admin where admin_id = t_prnew_header.id_user_input) as user_input, (select admin_realname from d_admin where admin_id = t_prnew_header.id_user_approved) as user_approved ');
    $this->db->from('t_prnew_header');
    $this->db->where('t_prnew_header.plant',$kd_plant);
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

    $this->db->order_by('id_pr_header', 'desc');
    $this->db->order_by('delivery_date','desc');

    $query = $this->db->get();
    $ret = $query->result_array();
    return $ret;

  }

  function tampil($id_pr_header){
    $this->db->select('a.pr_no, a.pr_no1, a.created_date, a.delivery_date,b.material_no,b.material_desc,b.uom,b.requirement_qty requirement_qty,b.price,a.plant, a.request_reason, (select admin_realname from d_admin where admin_id = a.id_user_approved) as user_approved');
    $this->db->from('t_prnew_header a');
    $this->db->join('t_pr_detail b','a.id_pr_header = b.id_pr_header');
    $this->db->where('a.id_pr_header', $id_pr_header);
  
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
    $SAP_MSI->where("ISNULL(t1.U_PurcReq,'') <> 'Y' ", null, false);
    $SAP_MSI->where('t1.PrchseItem ', 'Y');

    $query = $SAP_MSI->get();
    $ret = $query->result_array();
    return $ret;
  }

  function sap_item_groups_select_all_grnonpo($itemSelect='') {
    $kd_plant = $this->session->userdata['ADMIN']['plant'];
    $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
    $SAP_MSI->select('t0.ItemCode as MATNR, t0.ItemName as MAKTX, t0.ItmsGrpCod as DISPO, t0.BuyUnitMsr as UNIT, t0.InvntryUom as UNIT1, t0.NumInBuy as NUM, t1.ItmsGrpNam as DSNAM, t2.OnHand');
    $SAP_MSI->from('OITM t0 with (NOLOCK)');
    $SAP_MSI->join('OITB t1','t1.ItmsGrpCod = t0.ItmsGrpCod','inner');
    $SAP_MSI->join('OITW t2','t2.ItemCode = t0.ItemCode','inner');
    $SAP_MSI->where('validFor', 'Y');
    $SAP_MSI->where("ISNULL(U_PurcReq,'') <> 'Y' ", null, false);
    $SAP_MSI->where('t0.PrchseItem ', 'Y');
    $SAP_MSI->where('WhsCode ', $kd_plant);

    if($itemSelect != ''){
      $SAP_MSI->where('t0.ItemCode', $itemSelect);
    }
    
    $item_groups = $SAP_MSI->get();
    
    if ($item_groups->num_rows() > 0) {
      return $item_groups->result_array();
    } else {
      return FALSE;
    }
  }

  function sap_get_uom_convertion($itemSelect) {
    $kd_plant = $this->session->userdata['ADMIN']['plant'];
    $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
    $SAP_MSI->select('t0.InvntryUom as UNIT1, t0.NumInBuy as NUM');
    $SAP_MSI->from('OITM t0 with (NOLOCK)');
    $SAP_MSI->join('OITB t1','t1.ItmsGrpCod = t0.ItmsGrpCod','inner');
    $SAP_MSI->join('OITW t2','t2.ItemCode = t0.ItemCode','inner');
    $SAP_MSI->where('validFor', 'Y');
    $SAP_MSI->where("ISNULL(U_PurcReq,'') <> 'Y' ", null, false);
    $SAP_MSI->where('t0.PrchseItem ', 'Y');
    $SAP_MSI->where('WhsCode ', $kd_plant);
    $SAP_MSI->where('t0.ItemCode', $itemSelect);
    
    $item_groups = $SAP_MSI->get();
    
    if ($item_groups->num_rows() > 0) {
      return $item_groups->row_array();
    } else {
      return FALSE;
    }
  }

  function sap_get_last_data($itemSelected){
    $kd_plant = $this->session->userdata['ADMIN']['plant'];
    $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
    $SAP_MSI->select('cardcode as VendorCode, cardname as VendorName, Price as LastPrice');
    $SAP_MSI->from('OPOR a');
    $SAP_MSI->join('POR1 b','a.docentry=b.DocEntry','inner');
    $SAP_MSI->where('itemcode',$itemSelected);
    $SAP_MSI->where('WhsCode',$kd_plant);
    $SAP_MSI->order_by('a.DocDate','desc');
    $SAP_MSI->limit(1);

    $last = $SAP_MSI->get();
    
    if ($last->num_rows() > 0) {
      return $last->result_array();
    } else {
      return FALSE;
    }
  }

  function sap_get_last_data_no_limit($itemSelected){
    $kd_plant = $this->session->userdata['ADMIN']['plant'];
    $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
    $SAP_MSI->select('cardcode as VendorCode, cardname as VendorName, Price as LastPrice');
    $SAP_MSI->from('OPOR a');
    $SAP_MSI->join('POR1 b','a.docentry=b.DocEntry','inner');
    $SAP_MSI->where('itemcode',$itemSelected);
    $SAP_MSI->where('WhsCode',$kd_plant);
    $SAP_MSI->order_by('a.DocDate','desc');

    $last = $SAP_MSI->get();
    
    if ($last->num_rows() > 0) {
      return $last->row_array();
    } else {
      return FALSE;
    }
  }

  function sap_items_select_by_item_group($item_group, $trans_type) {
    $kd_plant = $this->session->userdata['ADMIN']['plant'];
    $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
    $SAP_MSI->select('t0.ItemCode as MATNR, t0.ItemName as MAKTX, t0.ItmsGrpCod as DISPO, t0.BuyUnitMsr as UNIT, t0.InvntryUom as UNIT1, t1.ItmsGrpNam as DSNAM');
    $SAP_MSI->from('OITM t0 with (NOLOCK) ');
    $SAP_MSI->join('OITB t1','t1.ItmsGrpCod = t0.ItmsGrpCod','inner');
    $SAP_MSI->where('validFor', 'Y');
    $SAP_MSI->where("ISNULL(U_PurcReq,'') <> 'Y' ", null, false);
    $SAP_MSI->where('t0.PrchseItem ', 'Y');
    $SAP_MSI->where('t1.ItmsGrpNam ', $item_group);
    
    $item_groups = $SAP_MSI->get();
    
    if ($item_groups->num_rows() > 0) {
      return $item_groups->result_array();
    } else {
      return FALSE;
    }
  }

  function id_pr_plant_new_select($id_outlet,$created_date="",$id_pr_header="") {
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
    
    $s = $query->result_array();
    $loop = count($s);
    $po = array();
    if (empty($s)) {
      $doc1 = 0;
    } else {
      for ($i=0; $i < $loop; $i++) { 
        $doc1 = $s[$i]['DocEntry'];

        $SAP_MSI->select("a.DocEntry, isnull(SeriesName,'')+right(replicate('0',7)+convert(varchar,docnum),7) AS NoDoc");
        $SAP_MSI->from('OPOR a');
        $SAP_MSI->join('nnm1 b','a.Series = b.Series AND a.ObjType=b.ObjectCode','inner');
        $SAP_MSI->where('a.DocEntry',$doc1);
        $querypo = $SAP_MSI->get();
        $nopo = $querypo->row_array();
        $po[] = $nopo;
      } 
    }
    return $po;
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

  function pr_header_insert($data) {
		if($this->db->insert('t_prnew_header', $data))
			return $this->db->insert_id();
		else
			return FALSE;
  }
  
  function pr_detail_insert($data) {
		if($this->db->insert('t_pr_detail', $data))
			return $this->db->insert_id();
		else
			return FALSE;
  }
  
  function t_prnew_header_delete($id_pr_header){
    $data = $this->prnew_header_select($id_pr_header);
    $status = $data['status'];
    if ($status != 2) {
      if($this->t_pr_details_delete($id_pr_header)){
          $this->db->where('id_pr_header', $id_pr_header);
          if($this->db->delete('t_prnew_header'))
              return TRUE;
          else
              return FALSE;
      }
    } else {
      return FALSE;
    }
  }

  function t_pr_details_delete($id_pr_header) {
      $this->db->where('id_pr_header', $id_pr_header);
      if($this->db->delete('t_pr_detail'))
          return TRUE;
      else
          return FALSE;
  }

  function prnew_header_select($id_prnew_header){
    $kd_plant = $this->session->userdata['ADMIN']['plant'];
    $this->db->from('t_prnew_header');
    $this->db->where('id_pr_header', $id_prnew_header);
    $this->db->where('plant',$kd_plant);
    
    $query = $this->db->get();

    if(($query)&&($query->num_rows() > 0)){
      return $query->row_array();
    }else{
      return FALSE;
    }
  }

  function pr_details_select($id_pr_header) {
		$this->db->from('t_pr_detail');
      $this->db->where('id_pr_header', $id_pr_header);
      $this->db->order_by('id_pr_detail');

      $query = $this->db->get();

      if(($query)&&($query->num_rows() > 0))
        return $query->result_array();
      else
        return FALSE;
  }

  function changeUpdateToDb($data){
    $this->db->where('id_pr_detail', $data['id_pr_detail']);
    if($this->db->update('t_pr_detail', $data))
      return TRUE;
    else
      return FALSE;
  }

  function pr_header_update($data){
    $update = array(
      'delivery_date' => $data['delivery_date'],
      'status' => $data['status'],
      'request_reason' => $data['request_reason'],
      'id_user_approved' => $data['id_user_approved']
    );
    $this->db->where('id_pr_header', $data['id_pr_header']);
    if($this->db->update('t_prnew_header', $update))
      return TRUE;
    else
      return FALSE;
  }

  function pr_details_delete($id_pr_header) {
		$this->db->where('id_pr_header', $id_pr_header);
		if($this->db->delete('t_pr_detail'))
			return TRUE;
		else
			return FALSE;
  }    
}