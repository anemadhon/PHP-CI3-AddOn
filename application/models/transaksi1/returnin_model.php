<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Returnin_model extends CI_Model {

    public function getDataReturnIn_Header($fromDate='', $toDate='', $status=''){
        $kd_plant = $this->session->userdata['ADMIN']['plant'];
        $this->db->select('*, (select DISTINCT ok_cancel from t_retin_detail where id_retin_header = t_retin_header.id_retin_header) ok_cancel, (SELECT admin_realname FROM d_admin WHERE admin_id = t_retin_header.id_user_input) AS user_input, (SELECT admin_realname FROM d_admin WHERE admin_id = t_retin_header.id_user_approved) AS user_approved');
        $this->db->from('t_retin_header');
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

        $this->db->order_by('id_retin_header', 'desc');
        $query = $this->db->get();
        $ret = $query->result_array();
        return $ret;
    }

    function retin_header_select($id_retin_header) {
        $this->db->select('t_retin_header.*,(select STOR_LOC_NAME from m_outlet where OUTLET = t_retin_header.plant) as plant_name_new, (select STOR_LOC_NAME from m_outlet where OUTLET = t_retin_header.storage_location) as storage_location_name_new');
        $this->db->from('t_retin_header');
        $this->db->where('id_retin_header', $id_retin_header);

        $query = $this->db->get();

        if($query->num_rows() > 0)
          return $query->row_array();
        else
          return FALSE;
    }
    
    function retin_details_select($id_retin_header) {
        $this->db->from('t_retin_detail');
        $this->db->where('id_retin_header', $id_retin_header);

            $query = $this->db->get();

            if(($query)&&($query->num_rows() > 0))
                return $query->result_array();
            else
                return FALSE;
    }

    function getReturnFrom($ro){
        $this->db->select('plant,m_outlet.OUTLET_NAME1');
        $this->db->from('t_gisto_dept_header');
        $this->db->join('m_outlet','m_outlet.OUTLET=t_gisto_dept_header.plant');
        $this->db->where('gisto_dept_no', $ro);

            $query = $this->db->get();

            if(($query)&&($query->num_rows() > 0))
                return $query->result_array();
            else
                return FALSE;
    }

    public function sap_do_select_all($kd_plant="",$do_no="",$do_item=""){
        $kd_plant = $this->session->userdata['ADMIN']['plant'];
        $SAP_MSI = $this->load->database('SAP_MSI', TRUE);

        $SAP_MSI->select("OWTQ.DocEntry VBELN, OWTQ.U_DocNum VBELN1, OWTQ.DocDate DELIV_DATE, OWTQ.ToWhsCode, WTQ1.LineNum POSNR,
							OITM.ItmsGrpCod DISPO, WTQ1.ItemCode MATNR, Dscription MAKTX, (OpenQty-U_grqty_web) LFIMG , unitMsr VRKME, WTQ1.LineNum item, U_grqty_web, OWHS.WhsCode RETURN_FROM, OWHS.WhsName RETURN_FROM_NAME,
                            (SELECT WhsCode FROM OWHS where WhsCode = '$kd_plant')PLANT,
                            (SELECT  WhsName FROM OWHS where WhsCode = '$kd_plant')PLANT_NAME");
        $SAP_MSI->from('OWTQ');
        $SAP_MSI->join('WTQ1','OWTQ.DocEntry = WTQ1.DocEntry','inner');
        $SAP_MSI->join('OITM with (NOLOCK) ','WTQ1.ItemCode = OITM.ItemCode','inner');
        $SAP_MSI->join('OWHS','OWHS.WhsCode = OWTQ.Filler','inner');
        $SAP_MSI->where('ToWhsCode',$kd_plant);
        $SAP_MSI->where('U_INVreturn', 'Y'); 
        $SAP_MSI->where('WTQ1.Quantity = WTQ1.OpenCreQty');

        if((!empty($do_no))){
            $SAP_MSI->where('OWTQ.DocEntry', $do_no);
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

    function sap_retin_header_select_by_do_no($do_no) {

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
    
    function sap_retin_select_item_group_do($do_no) {
        $doitems = $this->sap_retin_details_select_by_do_no($do_no);
        $item_groups = $this->sap_item_groups_select_all();
        $count = count($item_groups);
        $count_do = count($doitems);
        $k = 1;
        for ($i=1;$i<=$count;$i++) {
          for($j=1;$j<=$count_do;$j++) {
            if ($doitems[$j]['DISPO']==$item_groups[$i]['DISPO']){
              $item_groups_filter[$k]['DSNAM'] = $item_groups[$i]['DSNAM'];
              $item_groups_filter[$k]['DISPO'] = $item_groups[$i]['DISPO'];
              $k++;
              break;
            }
          }
        }
        
        if (count($item_groups_filter) > 0) {
          $item_groups_filter = array_unique($item_groups_filter, SORT_REGULAR );
          return $item_groups_filter;
        }
        else {
          return FALSE;
        }
    }
    
    function sap_retin_details_select_by_do_no($do_no) {
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
            $doitems[$i]['id_retin_h_detail'] = $i;
          }
          return $doitems;
        }
        else {
          unset($doitems);
          return FALSE;
        }
    }
    
    function sap_retin_details_select_by_do_and_item_group($do_no,$item_group_code) {
        $doitems = $this->sap_retin_details_select_by_do_no($do_no);
        $count = count($doitems);
        $k = 1;
        for ($i=1;$i<=$count;$i++) {
          if ($doitems[$i]['DISPO']==$item_group_code){
            $doitem[$k] = $doitems[$i];
            $k++;
          }
        }
        if (count($doitem) > 0) {
          return $doitem;
          echo "<pre>";
          print_r($doitem);
          echo "</pre>";
        }
        else {
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
  
    function cancelHeaderTranferIn($data){
        $this->db->set('status', '3');
        $this->db->where('id_retin_header', $data['id_retin_header']);
        if($this->db->update('t_retin_header')){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    function cancelDetailsTransferIn($data){
        $this->db->set('ok_cancel', '1');
        $this->db->where('id_retin_detail', $data);
        if($this->db->update('t_retin_detail')){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    function t_retin_header_delete($id_retin_header){
        $data = $this->retin_header_select($id_retin_header);
        $status = $data['status'];
        if ($status != 2) {
          if($this->t_retin_details_delete($id_retin_header)){
              $this->db->where('id_retin_header', $id_retin_header);
              if($this->db->delete('t_retin_header'))
                  return TRUE;
              else
                  return FALSE;
          }
        } else {
          return FALSE;
        }
    }

    function t_retin_details_delete($id_retin_header) {
        $this->db->where('id_retin_header', $id_retin_header);
        if($this->db->delete('t_retin_detail'))
            return TRUE;
        else
            return FALSE;
    }

    function id_retin_plant_new_select($id_outlet,$posting_date="",$id_retin_header="") {

        if (empty($posting_date))
            $posting_date=$this->m_general->posting_date_select_max();
        if (empty($id_outlet))
            $id_outlet=$this->session->userdata['ADMIN']['plant'];
  
        $this->db->select_max('id_retin_plant');
        $this->db->from('t_retin_header');
        $this->db->where('plant', $id_outlet);
        $this->db->where('posting_date', $posting_date);
        if (!empty($id_retin_header)) {
            $this->db->where('id_retin_header <> ', $id_retin_header);
        }
        $query = $this->db->get();
  
        if($query->num_rows() > 0) {
            $retin_out = $query->row_array();
            $id_retin_outlet = $retin_out['id_retin_plant'] + 1;
        }else {
            $id_retin_outlet = 1;
        }
        return $id_retin_outlet;
    }

    function sap_retin_details_select_by_do_and_item($do_no,$item) {
        if (empty($this->session->userdata['do_nos'])) {
            $doitem = $this->sap_do_select_all("",$do_no,$item);
        } else {
            $do_nos = $this->session->userdata['do_nos'];
            $k = 1;
            $count = count($do_nos);
            for ($i=1;$i<=$count;$i++) {
              if(($do_nos[$i]['VBELN']==$do_no)&&($do_nos[$i]['POSNR']==$item)){
                $doitem[1] = $do_nos[$i];
                break;
              }
            }
        }

        if (count($doitem) > 0) {
          return $doitem[1];
        }
        else {
          return FALSE;
        }
    }
    
    function u_grqty_web($po_no,$line){
        $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
        $SAP_MSI->select('U_grqty_web');
        $SAP_MSI->from('WTR1');
        $SAP_MSI->where('DocEntry',$po_no);
        $SAP_MSI->where('LineNum',$line);
        $querySAP = $SAP_MSI->get();
        $ret = $querySAP->result_array();

        if (count($ret) > 0){
            return $ret[0]['U_grqty_web'];
        }else{
            return "none";
        }
    }

    function updateOwtrStat($no, $po_no){
        $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
        $SAP_MSI->set('U_Stat', $no);
        $SAP_MSI->where('DOcEntry', $po_no);
        if($SAP_MSI->update('OWTR'))
          return TRUE;
        else
          return FALSE;
    }

    function retin_header_insert($data) {
		if($this->db->insert('t_retin_header', $data))
			return $this->db->insert_id();
		else
			return FALSE;
    }

    function retin_detail_insert($data) {
        if($this->db->insert('t_retin_detail', $data))
                return $this->db->insert_id();
            else 
                return FALSE;
    }

    function retin_header_update($data) {
      $update = array(
        'status' => $data['status'],
        'posting_date' => $data['posting_date'],
        'remark' => $data['remark'],
        'id_user_approved' => $data['id_user_approved']
      );
      $this->db->where('id_retin_header', $data['id_retin_header']);
        if($this->db->update('t_retin_header', $update))
          return TRUE;
        else
          return FALSE;
    }

    function updateWtrGrQty($grQty, $po_no, $line){
        $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
        $SAP_MSI->set('U_grqty_web', $grQty);
        $SAP_MSI->where('DOcEntry', $po_no);
        $SAP_MSI->where('LineNum', $line);
        if($SAP_MSI->update('WTR1'))
          return TRUE;
        else
          return FALSE;
    }

    function tampil($id_retin_header){
        $this->db->select('a.id_user_approved,a.do_no, a.do_no1, a.retin_no, a.retin_no1, a.posting_date,b.material_no,b.material_desc,b.uom,b.gr_quantity,a.plant, a.remark,
        (select f.reason from t_gisto_dept_header d, t_gisto_dept_detail f where d.id_gisto_dept_header =f.id_gisto_dept_header
        and a.do_no=d.gisto_dept_no
        and d.receiving_plant=a.plant
        and f.material_no=b.material_no ) reason,
        (select D.plant from t_gisto_dept_header d, t_gisto_dept_detail f where d.id_gisto_dept_header =f.id_gisto_dept_header
        and a.do_no=d.gisto_dept_no
        and d.receiving_plant=a.plant
        and f.material_no=b.material_no )  FromPlant ,
        (select e.OUTLET_NAME1 from t_gisto_dept_header d, t_gisto_dept_detail f ,m_outlet e where d.id_gisto_dept_header =f.id_gisto_dept_header
        and a.do_no=d.gisto_dept_no
        and d.receiving_plant=a.plant
        and D.plant=e.OUTLET
        and f.material_no=b.material_no )  OUTLET_NAME1,
        (select D.posting_date from t_gisto_dept_header d, t_gisto_dept_detail f where d.id_gisto_dept_header =f.id_gisto_dept_header
        and a.do_no=d.gisto_dept_no
        and d.receiving_plant=a.plant
        and f.material_no=b.material_no )  delivery,
        (select f.gr_quantity from t_gisto_dept_header d, t_gisto_dept_detail f where d.id_gisto_dept_header =f.id_gisto_dept_header
        and a.do_no=d.gisto_dept_no
        and d.receiving_plant=a.plant
        and f.material_no=b.material_no ) Qty_Retur, (SELECT admin_realname FROM d_admin WHERE admin_id = a.id_user_approved) nameApproved');
        $this->db->from('t_retin_header a');
        $this->db->join('t_retin_detail b','a.id_retin_header = b.id_retin_header','left');
        $this->db->where('a.id_retin_header',$id_retin_header);

        $query = $this->db->get();

        return $query->result_array();
    }

}