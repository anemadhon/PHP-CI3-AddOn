<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Transferout_model extends CI_Model {

    function t_gistonew_out_headers($fromDate='', $toDate='', $status=''){
      $kd_plant = $this->session->userdata['ADMIN']['plant'];

        $this->db->select('t_gistonew_out_header.*,(select OUTLET_NAME1 from m_outlet where OUTLET = t_gistonew_out_header.to_plant) as OUTLET_NAME1,
        (select admin_realname from d_admin where admin_id = t_gistonew_out_header.id_user_input) as user_input
        , (select admin_realname from d_admin where admin_id = t_gistonew_out_header.id_user_approved) as user_approved ');
        $this->db->from('t_gistonew_out_header');
        $this->db->where('t_gistonew_out_header.plant',$kd_plant);
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

        $this->db->order_by('id_gistonew_out_header', 'desc');

        $query = $this->db->get();
        $ret = $query->result_array();
        return $ret;
    }

    public function sap_do_select_all($kd_plant="",$do_no="",$do_item=""){
        $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
        $kd_plant = $this->session->userdata['ADMIN']['plant'];
        
        $SAP_MSI->select('t0.DocEntry As VBELN, t0.U_DocNum as VBELN1, t0.DocDate as DELIVDATE, t0.ToWhsCode as RECEIVING_PLANT, t1.LineNum as PONSR, t4.ItmsGrpCod as DISPO, t1.ItemCode as MATNR, t2.ItemName as MAKTX, t1.Quantity as LFIMG, t1.unitMsr as VRKME, t1.LineNum as item, t0.ToWhsCode as Plant,t0.Filler ,(SELECT WhsName FROM OWHS WHERE WhsCode=ToWhsCode) as ABC');
        $SAP_MSI->from('ODRF t0');
        $SAP_MSI->join('DRF1 t1','t0.DocEntry = t1.DocEntry','inner');
        $SAP_MSI->join('OITM T2 with (NOLOCK)','t2.ItemCode = T1.ItemCode','inner');
        $SAP_MSI->join('OITB T4','T2.ItmsGrpCod = t4.ItmsGrpCod','inner');
        $SAP_MSI->where_in('Filler', $kd_plant);
        $SAP_MSI->where('t0.ObjType','1250000001');
        $SAP_MSI->where('t0.DocStatus','O');

        if(!empty($do_no)) {
            $SAP_MSI->where('t0.DocEntry',$do_no);
        }

        if(!empty($do_item)) {
          $SAP_MSI->where('t1.ItemCode ' ,$do_item);
        }

        $query = $SAP_MSI->get();

        if($query->num_rows() > 0) {
            $DELV_OUTS = $query->result_array();
            $count = count($DELV_OUTS)-1;
                for ($i=0;$i<=$count;$i++) {
                    $poitems[$i+1] = $DELV_OUTS[$i];
                }

            return $poitems;
        } else {
            return FALSE;
        }
    }

    function sap_gistonew_out_details_select_by_do_no($do_no) {
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

    function sap_gistonew_out_details_select_by_do_and_item_group($do_no,$item_group_code) {
        $doitems = $this->sap_gistonew_out_details_select_by_do_no($do_no);
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
        $doitems = $this->sap_gistonew_out_details_select_by_do_no($do_no);
        $item_groups = $this->sap_item_groups_select_all();
        $count = count($item_groups);
        $count_do = count($doitems);
        $k = 1;
        for ($i=1;$i<=$count;$i++) {
          for($j=1;$j<=$count_do;$j++) {
            if ($doitems[$j]['DISPO']==$item_groups[$i]['DISPO']){
              $item_groups_filter[$k] = $item_groups[$i]['DSNAM'];
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
    
    function id_stdstock_plant_new_select($id_outlet,$posting_date="",$id_gistonew_out_header="") {

      if (empty($posting_date))
          $posting_date=$this->m_general->posting_date_select_max();
      if (empty($id_outlet))
          $id_outlet=$this->session->userdata['ADMIN']['plant'];

      $this->db->select_max('id_gistonew_out_plant');
      $this->db->from('t_gistonew_out_header');
      $this->db->where('plant', $id_outlet);
	  	$this->db->where('posting_date', $posting_date);
        if (!empty($id_gistonew_out_header)) {
    		  $this->db->where('id_gistonew_out_header <> ', $id_gistonew_out_header);
        }
      $query = $this->db->get();

      if($query->num_rows() > 0) {
        $gistonew_out = $query->row_array();
        $id_stdstock_outlet = $gistonew_out['id_gistonew_out_plant'] + 1;
      }	else {
        $id_stdstock_outlet = 1;
      }
      return $id_stdstock_outlet;
    }

    function gistonew_out_header_insert($data) {
		if($this->db->insert('t_gistonew_out_header', $data))
			return $this->db->insert_id();
		else
			return FALSE;
    }

    function gistonew_out_detail_insert($data) {
    if($this->db->insert('t_gistonew_out_detail', $data))
			return $this->db->insert_id();
		else 
			return FALSE;
    }

    function getDataMaterialGroupSelect($po_no, $itemSelect){
      $kd_plant = $this->session->userdata['ADMIN']['plant'];
      $SAP_MSI = $this->load->database('SAP_MSI', TRUE);

      $dataHeader = $this->sap_do_select_all('',$po_no, $itemSelect);
      for($i = 1; $i <= count($dataHeader); $i++){
        $SAP_MSI->select('(OnHand - IsCommited) as OnHand'); 
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
      $SAP_MSI->select('(OnHand - IsCommited) as OnHand'); 
      $SAP_MSI->from('OITW');
      $SAP_MSI->where('WhsCode', $plant);
      $SAP_MSI->where('ItemCode', $item_code);

      $query = $SAP_MSI->get();
      $inwhs = $query->result_array();
      return $inwhs;
    }

    function U_grqty_web($base,$line){
      $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
      $SAP_MSI->select('U_grqty_web'); 
      $SAP_MSI->from('WTQ1');
      $SAP_MSI->where('DocEntry', $base);
      $SAP_MSI->where('LineNum', $line);

      $query = $SAP_MSI->get();
      $inwhs = $query->result_array();
      return $inwhs[0];

    }

    function updateOWTQ($base){
      $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
      $SAP_MSI->set('U_Stat' , 1);
      $SAP_MSI->where('DOcEntry', $base);
      if($SAP_MSI->update('OWTQ'))
        return TRUE;
      else
        return FALSE;
    }

    function updateWTQ1($gr_qty, $base, $line){
      $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
      $SAP_MSI->set('U_grqty_web', $gr_qty);
      $SAP_MSI->where('DOcEntry', $base);
      $SAP_MSI->where('LineNum', $line);
      if($SAP_MSI->update('WTQ1'))
        return TRUE;
      else
        return FALSE;
    }

    function gistonew_out_header_update($data){
      $update = array(
        'status' => $data['status'],
        'remark' => $data['remark'],
        'po_no' => $data['po_no'],
        'po_no1' => $data['po_no1'],
        'id_user_approved' => $data['id_user_approved']
      );
      $this->db->where('id_gistonew_out_header', $data['id_gistonew_out_header']);
        if($this->db->update('t_gistonew_out_header', $update))
          return TRUE;
        else
          return FALSE;
    }

    function gistonew_out_details_delete($id_gistonew_out_header) {
      $this->db->where('id_gistonew_out_header', $id_gistonew_out_header);
      if($this->db->delete('t_gistonew_out_detail'))
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

    function sap_gistonew_out_header_select_by_do_no($do_no) {

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
    
    function gistonew_out_header_select($id_gistonew_out_header){
      $kd_plant = $this->session->userdata['ADMIN']['plant'];
      
        $this->db->select('t_gistonew_out_header.*,(select OUTLET_NAME1 from m_outlet where OUTLET = t_gistonew_out_header.plant) as PLANTS_NAME,(select STOR_LOC_NAME from m_outlet where OUTLET = t_gistonew_out_header.storage_location) as STORAGE_LOCATION_NAME,(select OUTLET_NAME1 from m_outlet where OUTLET = t_gistonew_out_header.to_plant) as STOR_LOC_NAME');
        $this->db->from('t_gistonew_out_header');
        $this->db->where('id_gistonew_out_header', $id_gistonew_out_header);
        $this->db->where('t_gistonew_out_header.plant',$kd_plant);
        
        $query = $this->db->get();
    
        if(($query)&&($query->num_rows() > 0)){
          return $query->row_array();
        }else{
          return FALSE;
        }
    }

    function stdstock_details_select($id_gistonew_out_header) {
		  $this->db->from('t_gistonew_out_detail');
        $this->db->where('id_gistonew_out_header', $id_gistonew_out_header);
        $this->db->order_by('id_gistonew_out_detail');

            $query = $this->db->get();

            if(($query)&&($query->num_rows() > 0))
                return $query->result_array();
            else
                return FALSE;
    }

    function cancelHeaderPoFromVendor($data){
        $this->db->set('status', '3');
        $this->db->where('id_gistonew_out_header', $data['id_gistonew_out_header']);
        if($this->db->update('t_gistonew_out_header')){
            return TRUE;
        }else{
            return FALSE;
        }
    }
    
    function cancelDetailsPoFromVendor($data){
        $this->db->set('ok_cancel', '1');
        $this->db->where('id_gistonew_out_detail', $data);
        if($this->db->update('t_gistonew_out_detail')){
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
    $this->db->where('DATE(posting_date)', $posting_date);
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
  
    function t_gistonew_out_header_delete($id_gistonew_out_header){
      $data = $this->gistonew_out_header_select($id_gistonew_out_header);
      $back = $data['status'];
      if($back != 2){
        if($this->t_gistonew_out_details_delete($id_gistonew_out_header)){
          $this->db->where('id_gistonew_out_header', $id_gistonew_out_header);
          if($this->db->delete('t_gistonew_out_header'))
            return TRUE;
          else
            return FALSE;
        }
      }else{
        return false;
      }
        
    }

    function t_gistonew_out_details_delete($id_gistonew_out_header) {
        $this->db->where('id_gistonew_out_header', $id_gistonew_out_header);
        if($this->db->delete('t_gistonew_out_detail'))
            return TRUE;
        else
            return FALSE;
    }

    function tampil($id_gistonew_out_header){
      $this->db->select('a.gistonew_out_no,a.gistonew_out_no1,a.po_no,a.posting_date,b.material_no,b.material_desc,b.uom,b.gr_quantity,b.num,a.plant,a.receiving_plant, a.remark, (SELECT OUTLET_NAME1 FROM m_outlet WHERE OUTLET=a.plant) as NAME1, (SELECT OUTLET FROM m_outlet WHERE TRANSIT=a.receiving_plant) as PLANT_REC,
      (SELECT OUTLET_NAME1 FROM m_outlet WHERE TRANSIT=a.receiving_plant) as PLANT_REC_NAME');
      $this->db->from('t_gistonew_out_header a');
      $this->db->join('t_gistonew_out_detail b','a.id_gistonew_out_header = b.id_gistonew_out_header','left');
      $this->db->where('a.id_gistonew_out_header',$id_gistonew_out_header);

      $query = $this->db->get();

      return $query->result_array();
	}
}