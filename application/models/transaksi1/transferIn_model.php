<?php defined('BASEPATH') OR exit('No direct script access allowed');

class TransferIn_model extends CI_Model {

  function t_grsto_headers($fromDate='', $toDate='', $status=''){
    $kd_plant = $this->session->userdata['ADMIN']['plant'];

    $this->db->select('t_grsto_header.*, (SELECT OUTLET_NAME1 FROM m_outlet WHERE OUTLET = t_grsto_header.delivery_plant) AS OUTLET_NAME1, (SELECT admin_realname FROM d_admin WHERE admin_id = t_grsto_header.id_user_input) AS user_input, (SELECT admin_realname FROM d_admin WHERE admin_id = t_grsto_header.id_user_approved) AS user_approved');
    $this->db->from('t_grsto_header');
    $this->db->where('t_grsto_header.plant', $kd_plant);

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

    $this->db->order_by('id_grsto_header', 'desc');

    $query = $this->db->get();
    $ret = $query->result_array();
    return $ret;
  }

  public function sap_do_select_all($kd_plant="",$do_no="",$do_item=""){
    $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
    $kd_plant = $this->session->userdata['ADMIN']['plant'];

    $no_do = !empty($do_no) ? explode('_',$do_no) : '';

    $sr = !empty($no_do[0]) ? $no_do[0] : '';
    $to = !empty($no_do[1]) ? $no_do[1] : '';

    $where = !empty($sr) && !empty($to)? " AND po_no = '".$sr."' AND gistonew_out_no = '".$to."' " : '';
    
    $SQL = "SELECT po_no EBELN, po_no1 EBELN1, gistonew_out_no MBLNR, gistonew_out_no1 MBLNR1, plant SUPPL_PLANT, 
            STOR_LOC_NAME SPLANT_NAME, posting_date DELIV_DATE, a.ItmsGrpCod DISPO, 
            t1.LineNum EBELP, 
            a.ItemCode MATNR, a.ItemName MAKTX, (gr_quantity-receipt) BSTMG, uom BSTME, gr_quantity as TFQUANTITY, 
            receiving_plant 
    FROM t_gistonew_out_header 
    INNER JOIN t_gistonew_out_detail ON t_gistonew_out_detail.id_gistonew_out_header = t_gistonew_out_header.id_gistonew_out_header 
    INNER JOIN m_outlet ON m_outlet.outlet = t_gistonew_out_header.plant 
    INNER JOIN ".$SAP_MSI->database.".dbo.OITM a with (NOLOCK) ON a.ItemCode COLLATE DATABASE_DEFAULT = t_gistonew_out_detail.material_no COLLATE DATABASE_DEFAULT
    INNER JOIN ".$SAP_MSI->database.".dbo.OWTQ t0 ON CONVERT(VARCHAR, t0.DocEntry) = CONVERT(VARCHAR, t_gistonew_out_header.gistonew_out_no)
    INNER JOIN ".$SAP_MSI->database.".dbo.WTQ1 t1 ON t0.DocEntry = t1.DocEntry AND case when t1.U_BaseType > 0 then t1.U_BaseEntry else t1.LineNum end = t_gistonew_out_detail.posnr 
    WHERE receiving_plant = '".$kd_plant."' AND [status] =2 AND po_no != '' AND gistonew_out_no != '' 
    AND DocStatus != 'C' AND receipt = 0 AND [close] = 0 AND plant != '05WHST'". $where. " ORDER BY EBELN1 asc";

    $query = $this->db->query($SQL);
      
    if($query->num_rows() > 0) {
      $PO_STO_OUTS = $query->result_array();
      $count = count($PO_STO_OUTS)-1;
      for ($i=0;$i<=$count;$i++) {
        $poitems[$i+1] = $PO_STO_OUTS[$i];
      }
      return $poitems;
    } else {
      return FALSE;
    }
  }

  function sap_grsto_details_select_by_do_no($do_no) {
    if (empty($this->session->userdata['do_nos'])) {
      $doitems = $this->sap_do_select_all("",$do_no);
    } else {
      $do_nos = $this->session->userdata['do_nos'];
      $k = 1;
      $count = count($do_nos);
      for ($i=1;$i<=$count;$i++) {
          if ($do_nos[$i]['VBELN']==$do_no){
              $doitems[$k] = $do_nos[$i];
              $k++;
          }
      }
    }

    $count = count($doitems);
    if ($count > 0) {
      for($i=1;$i<=$count;$i++) {
        $doitems[$i]['id_gistonew_out_h_detail'] = $i;
      }
      return $doitems;
    }
    else {
      unset($doitems);
      return FALSE;
    }
  }

  function getQtySR($pr_no,$material_no,$plant){
    $this->db->select('id_stdstock_header'); 
    $this->db->from('t_stdstock_header');
    $this->db->where('pr_no', $pr_no);
    $this->db->where('plant', $plant);
    $query = $this->db->get();
    
    $con = $query->result_array();
    
    $id_stdstock_header='';
    if(count($con) != 0){
      $id_stdstock_header = $con[0]["id_stdstock_header"];

      $this->db->select('requirement_qty'); 
      $this->db->from('t_stdstock_detail');
      $this->db->where('material_no',$material_no);
      if($id_stdstock_header != ''){
        $this->db->where('id_stdstock_header', $id_stdstock_header);
      }

      $queryDetail = $this->db->get();
      $conDetail = $queryDetail->result_array();
  
      if($conDetail){
        return $conDetail;
      }else{
        return false;
      }
    }
  }

  function sap_grsto_details_select_by_do_and_item_group($do_no,$item_group_code) {
    $doitems = $this->sap_grsto_details_select_by_do_no($do_no);
    $count = count($doitems);
    $k = 1;
    for ($i=1;$i<=$count;$i++) {
      if ($doitems[$i]['DISPO']==$item_group_code){
        $doitem[$k] = $doitems[$i];
        $k++;
      }
    }
    if (count($doitems) > 0) {
      return $doitems;
      echo "<pre>";
      print_r($doitems);
      echo "</pre>";
    }
    else {
      return FALSE;
    }
  }
  
  function sap_gistonew_out_select_item_group_do($do_no) {
    $doitems = $this->sap_grsto_details_select_by_do_no($do_no);
    $item_groups = $this->sap_item_groups_select_all();
    $count = count($item_groups);
    $count_do = count($doitems);
    $k = 1;
    for ($i=1;$i<=$count;$i++) {
      for($j=1;$j<=$count_do;$j++) {
        if ($doitems[$j]['DISPO']==$item_groups[$i]['DISPO']){
          $item_groups_filter[$k]["DSNAM"] = $item_groups[$i]['DSNAM'];
          $item_groups_filter[$k]["DISPO"] = $item_groups[$i]['DISPO'];
          $k++;
          break;
        }
      }
    }
    if (count($item_groups_filter) > 0) {
      $item_groups_filter = array_unique($item_groups_filter, SORT_REGULAR);
      return $item_groups_filter;
    }
    else {
      return FALSE;
    }
  }
  
  function id_stdstock_plant_new_select($id_outlet,$posting_date="",$id_grsto_header="") {

    if (empty($posting_date))
      $posting_date=$this->m_general->posting_date_select_max();
    if (empty($id_outlet))
      $id_outlet=$this->session->userdata['ADMIN']['plant'];

    $this->db->select_max('id_grsto_plant');
    $this->db->from('t_grsto_header');
    $this->db->where('plant', $id_outlet);
    $this->db->where('posting_date', $posting_date);
    if (!empty($id_grsto_header)) {
      $this->db->where('id_grsto_header <> ', $id_grsto_header);
    }
    $query = $this->db->get();

    if($query->num_rows() > 0) {
      $gistonew_out = $query->row_array();
      $id_stdstock_outlet = $gistonew_out['id_grsto_plant'] + 1;
    }	else {
      $id_stdstock_outlet = 1;
    }
    return $id_stdstock_outlet;
  }

  function cekQty($po_no,$material_no){
    $this->db->select('A.receipt,A.var');
    $this->db->from('t_gistonew_out_detail A');
    $this->db->join('t_gistonew_out_header B','A.id_gistonew_out_header=B.id_gistonew_out_header');
    $this->db->where('B.po_no', $po_no);
    $this->db->where('A.material_no', $material_no);
    
    $query=$this->db->get();
    $cekQtyR = $query->result_array();
    if($query->num_rows() > 0) {
      return $cekQtyR;
    }else{
      return FALSE;
    }

  }

  function update_grstonew_out_detail($data){
    $receipt = $data['receipt'];
    $var = $data['var'];
    $po_no = $data['po_no'];
    $material_no = $data['material_no'];
    $sql = "update t_gistonew_out_detail
            SET receipt = $receipt, var = $var 
            FROM t_gistonew_out_detail A join t_gistonew_out_header B on A.id_gistonew_out_header = B.id_gistonew_out_header
            WHERE A.material_no = '". $material_no ."' AND B.po_no = '". $po_no ."'";
    $query =  $this->db->query($sql);
    if($query)
      return TRUE;
    else
      return FALSE;
  }

  function grsto_header_insert($data) {
    if($this->db->insert('t_grsto_header', $data))
      return $this->db->insert_id();
    else
      return FALSE;
  }

  function grsto_detail_insert($data) {
    if($this->db->insert('t_grsto_detail', $data))
      return $this->db->insert_id();
    else 
      return FALSE;
  }

  function getDataMaterialGroupSelect($po_no, $itemSelect){
    $kd_plant = $this->session->userdata['ADMIN']['plant'];
    $SAP_MSI = $this->load->database('SAP_MSI', TRUE);

    $dataHeader = $this->sap_do_select_all('',$po_no, $itemSelect);
    for($i = 1; $i <= count($dataHeader); $i++){
      $SAP_MSI->select('OnHand'); 
      $SAP_MSI->from('OITW');
      $SAP_MSI->where('WhsCode', $kd_plant);
      $SAP_MSI->where('ItemCode', $dataHeader[$i]['MATNR']);

      $query = $SAP_MSI->get();
      $inwhs = $query->result_array();

      $dataHeader[$i]['In_Whs_Qty'] = $inwhs[0]['OnHand'];
    }
    return $dataHeader;
  }

  function in_whs_qty($plant,$item_code){
    $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
    $SAP_MSI->select('OnHand'); 
    $SAP_MSI->from('OITW');
    $SAP_MSI->where('WhsCode', $plant);
    $SAP_MSI->where('ItemCode', $item_code);

    $query = $SAP_MSI->get();
    $inwhs = $query->result_array();
    return $inwhs;
  }

  function grsto_header_update($data){
    $update = array(
      'po_no' => $data['po_no'],
      'po_no1' => $data['po_no1'],
      'status' => $data['status'],
      'posting_date' => $data['posting_date'],
      'id_user_approved' => $data['id_user_approved']
    );
    $this->db->where('id_grsto_header', $data['id_grsto_header']);
    if($this->db->update('t_grsto_header', $update))
      return TRUE;
    else
      return FALSE;
  }

  function grsto_details_delete($id_grsto_header) {
    $this->db->where('id_grsto_header', $id_grsto_header);
    if($this->db->delete('t_grsto_detail'))
      return TRUE;
    else
      return FALSE;
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

  function sap_grsto_header_select_by_do_no($do_no) {

    if (empty($this->session->userdata['do_nos'])) {
      $doheader = $this->sap_do_select_all("",$do_no);
    } else {
      $do_nos = $this->session->userdata['do_nos'];
      $count = count($do_nos);
      for ($i=1;$i<=$count;$i++) {
        if ($do_nos[$i]['VBELN']==$do_no){
          $doheader[1] = $do_nos[$i];
          break;
        }
      }
    }

    if (count($doheader) > 0) {
      return $doheader[1];
    }
    else {
      unset($doitems);
      return FALSE;
    }
  }
  
  function grsto_header_select($id_grsto_header){
    $this->db->select('t_grsto_header.*,(select STOR_LOC_NAME from m_outlet where OUTLET = t_grsto_header.plant) as plant_name_new, (select STOR_LOC_NAME from m_outlet where OUTLET = t_grsto_header.storage_location) as storage_location_name_new, (select STOR_LOC_NAME from m_outlet where OUTLET = t_grsto_header.delivery_plant) as delivery_plant_name_new');
    $this->db->from('t_grsto_header');
    $this->db->where('id_grsto_header', $id_grsto_header);
    
    $query = $this->db->get();

    if(($query)&&($query->num_rows() > 0)){
      return $query->row_array();
    }else{
      return FALSE;
    }
  }

  function stdstock_details_select($id_grsto_header) {
    $this->db->from('t_grsto_detail');
    $this->db->where('id_grsto_header', $id_grsto_header);
    $this->db->order_by('id_grsto_detail');

    $query = $this->db->get();

    if(($query)&&($query->num_rows() > 0))
        return $query->result_array();
    else
        return FALSE;
  }

  function cancelHeaderPoFromVendor($data){
    $this->db->set('status', '3');
    $this->db->where('id_grsto_header', $data['id_grsto_header']);
    if($this->db->update('t_grsto_header')){
        return TRUE;
    }else{
        return FALSE;
    }
  }
    
  function cancelDetailsPoFromVendor($data){
    $this->db->set('ok_cancel', '1');
    $this->db->where('id_grsto_detail', $data);
    if($this->db->update('t_grsto_detail')){
        return TRUE;
    }else{
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
    }
    else {
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
    $this->db->set('posting_date', $data['posting_date']);
		$this->db->where('id_grpo_header', $data['id_grpo_header']);
    if($this->db->update('t_grpo_header', $data))
      return TRUE;
		else
			return FALSE;
  }
  
  function grpo_detail_update($data) {
		$this->db->where('id_grpo_detail', $data['id_grpo_detail']);
    if($this->db->update('t_grpo_detail', $data))
			return TRUE;
		else
			return FALSE;
	}
  
  function t_grsto_header_delete($id_grsto_header){
    $data = $this->grsto_header_select($id_grsto_header);
    $back = $data['status'];
    if($back != 2){
      if($this->t_grsto_details_delete($id_grsto_header)){
        $this->db->where('id_grsto_header', $id_grsto_header);
        if($this->db->delete('t_grsto_header'))
          return TRUE;
        else
          return FALSE;
      }
    }else{
      return FALSE;
    }
      
  }

  function t_grsto_details_delete($id_grsto_header) {
    $this->db->where('id_grsto_header', $id_grsto_header);
    if($this->db->delete('t_grsto_detail'))
        return TRUE;
    else
        return FALSE;
  }

  function tampil($id_grsto_header){
    $this->db->select('a.po_no, a.po_no1, a.grsto_no, a.grsto_no1, a.delivery_date,a.delivery_plant,a.delivery_plant_name,b.material_no,b.material_desc,b.uom,b.outstanding_qty,b.gr_quantity,a.plant, (SELECT OUTLET_NAME1 FROM m_outlet WHERE OUTLET=a.plant) as NAME1, (SELECT OUTLET FROM m_outlet WHERE OUTLET=a.delivery_plant) as PLANT_REC, (SELECT OUTLET_NAME1 FROM m_outlet WHERE OUTLET=a.delivery_plant) as PLANT_REC_NAME');
    $this->db->from('t_grsto_header a');
    $this->db->join('t_grsto_detail b','a.id_grsto_header = b.id_grsto_header','left');
    $this->db->where('a.id_grsto_header',$id_grsto_header);

    $query = $this->db->get();

    return $query->result_array();
  }
}