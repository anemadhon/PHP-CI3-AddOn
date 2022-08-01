<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Grnonpo_model extends CI_Model {

    public function getDataGrNonPo_Header($fromDate='', $toDate='', $status=''){
        $kd_plant = $this->session->userdata['ADMIN']['plant'];
        $this->db->select('*, (select admin_realname from d_admin where admin_id = t_grnonpo_header.id_user_input) as user_input, (select admin_realname from d_admin where admin_id = t_grnonpo_header.id_user_approved) as user_approved');
        $this->db->from('t_grnonpo_header');
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

        $this->db->order_by('id_grnonpo_header', 'desc');
        $query = $this->db->get();
        $ret = $query->result_array();
        return $ret;
    }

    function grnonpo_header_select($id_grnonpo_header) {
        $this->db->select('*, (SELECT OUTLET_NAME1 FROM m_outlet WHERE OUTLET = t_grnonpo_header.plant) PLANT_NAME_NEW, (SELECT STOR_LOC_NAME FROM m_outlet WHERE STOR_LOC = t_grnonpo_header.storage_location) STOR_LOC_NAME, (SELECT TOP 1 COST_CENTER_TXT FROM m_outlet WHERE COST_CENTER = t_grnonpo_header.cost_center ORDER BY COST_CENTER_TXT DESC)COST_CENTER_NAME');
		$this->db->from('t_grnonpo_header');
		$this->db->where('id_grnonpo_header', $id_grnonpo_header);

		$query = $this->db->get();

		if($query->num_rows() > 0)
			return $query->row_array();
		else
			return FALSE;
    }
    
    function grnonpo_details_select($id_grnonpo_header) {
		$this->db->from('t_grnonpo_detail');
        $this->db->where('id_grnonpo_header', $id_grnonpo_header);

            $query = $this->db->get();

            if(($query)&&($query->num_rows() > 0))
                return $query->result_array();
            else
                return FALSE;
    }

    function sap_item_groups_select_all_grnonpo() {
        $kd_plant = $this->session->userdata['ADMIN']['plant'];
        $trans_type = 'grnonpo';
        $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
        $SAP_MSI->select('t0.ItemCode as MATNR,t0.ItemName as MAKTX,t0.ItmsGrpCod as DISPO,t0.InvntryUom as UNIT,t1.ItmsGrpNam as DSNAM');
        $SAP_MSI->from('OITM  t0 with (NOLOCK)');
        $SAP_MSI->where('validFor', 'Y');
        $SAP_MSI->where('InvntItem', 'Y');
        $SAP_MSI->join('oitb t1','t1.ItmsGrpCod = t0.ItmsGrpCod','inner');
        
        $item_groups = $SAP_MSI->get();
        
        if ($item_groups->num_rows() > 0) {
          return $item_groups->result_array();
        } else {
          return FALSE;
        }
    }
    
    function sap_items_select_by_item_group($item_group, $trans_type) {
        $kd_plant = $this->session->userdata['ADMIN']['plant'];
          
        $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
        $SAP_MSI->select('t0.ItemCode as MATNR,t0.ItemName as MAKTX,t0.ItmsGrpCod as DISPO,t0.InvntryUom as UNIT,t1.ItmsGrpNam as DSNAM');
        $SAP_MSI->from('OITM  t0 with (NOLOCK) ');
        $SAP_MSI->join('oitb t1','t1.ItmsGrpCod = t0.ItmsGrpCod','inner');
        $SAP_MSI->where('validFor', 'Y');
        $SAP_MSI->where('InvntItem', 'Y');
        $SAP_MSI->where('t1.ItmsGrpNam',$item_group);

        $query = $SAP_MSI->get();
        
        if(($query)&&($query->num_rows() > 0)) {
			return $query->result_array();
		}	else {
			return FALSE;
		}
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

    function grnonpo_detail_insert($data) {
		if($this->db->insert('t_grnonpo_detail', $data))
			return $this->db->insert_id();
		else 
			return FALSE;
    }

    function grnonpo_header_update($data){
        $update = array(
            'status' => $data['status'],
            'posting_date' => $data['posting_date'],
            'id_user_approved' => $data['id_user_approved'],
            'remark' => $data['remark']
        );
        $this->db->where('id_grnonpo_header', $data['id_grnonpo_header']);
        if($this->db->update('t_grnonpo_header', $update))
			return TRUE;
		else
			return FALSE;
    }

    function grnonpo_details_delete($id_grnonpo_header) {
		$this->db->where('id_grnonpo_header', $id_grnonpo_header);
		if($this->db->delete('t_grnonpo_detail'))
			return TRUE;
		else
			return FALSE;
    }

    function id_grnonpo_plant_new_select($id_outlet,$created_date="",$id_grnonpo_header="") {

        if (empty($created_date))
           $created_date=$this->m_general->posting_date_select_max();
        if (empty($id_outlet))
           $id_outlet=$this->session->userdata['ADMIN']['plant'];

		$this->db->select_max('id_grnonpo_plant');
		$this->db->from('t_grnonpo_header');
		$this->db->where('plant', $id_outlet);
	  	$this->db->where('posting_date', $created_date);
        if (!empty($id_grnonpo_header)) {
    		$this->db->where('id_grnonpo_header <> ', $id_grnonpo_header);
        }

		$query = $this->db->get();

		if($query->num_rows() > 0) {
			$stdstock = $query->row_array();
			$id_stdstock_outlet = $stdstock['id_grnonpo_plant'] + 1;
		}	else {
			$id_stdstock_outlet = 1;
		}

		return $id_stdstock_outlet;
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

        $SAP_MSI->select('U_TransFor');
        $SAP_MSI->from('OWHS');
        $SAP_MSI->where('WhsCode',$kd_plant);
        $querySAP = $SAP_MSI->get();
        $ret = $querySAP->result_array();
        $plant=$ret[0]['U_TransFor'];

        $SAP_MSI->select("OWTR.DocEntry VBELN, OWTR.DocDate DELIV_DATE, OWTR.ToWhsCode, WTR1.LineNum POSNR,
							OITM.ItmsGrpCod DISPO, WTR1.ItemCode MATNR, Dscription MAKTX, (OpenQty-U_grqty_web) LFIMG , unitMsr VRKME, WTR1.LineNum item, U_grqty_web, OWHS.WhsCode RETURN_FROM, OWHS.WhsName RETURN_FROM_NAME,
                            (SELECT WhsCode FROM OWHS where WhsCode = '$kd_plant')PLANT,
                            (SELECT  WhsName FROM OWHS where WhsCode = '$kd_plant')PLANT_NAME");
        $SAP_MSI->from('OWTR');
        $SAP_MSI->join('WTR1','OWTR.DocEntry = WTR1.DocEntry','inner');
        $SAP_MSI->join('OITM with (NOLOCK)','WTR1.ItemCode = OITM.ItemCode','inner');
        $SAP_MSI->join('OWHS','OWHS.WhsCode = OWTR.Filler','inner');
        $SAP_MSI->where_in('ToWhsCode',$plant);
        $SAP_MSI->where('U_Stat <>',1);
        $SAP_MSI->where('U_Retur =',1); 
        $SAP_MSI->where('(OpenQty-U_grqty_web) >', 0);
        $SAP_MSI->where('OWTR.U_Reverse','N');

        if((!empty($do_no))){
            $SAP_MSI->where('OWTR.DocEntry', $do_no);
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

    function showMatrialGroup(){
        $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
        $SAP_MSI->select('ItmsGrpNam');
        $SAP_MSI->from('OITB');
        $SAP_MSI->where("ItmsGrpCod in (select ItmsGrpCod from oitm with (NOLOCK) where U_GrNonPO = 'Y')");

        $query = $SAP_MSI->get();
        $ret = $query->result_array();
        return $ret;
    }

    function getDataMaterialGroup($item_group_code ='all'){
        $kd_plant = $this->session->userdata['ADMIN']['plant'];
        $trans_type = 'grnonpo';

        $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
        $SAP_MSI->select('t0.ItemCode as MATNR,t0.ItemName as MAKTX,t0.ItmsGrpCod as DISPO,t0.InvntryUom as UNIT,t1.ItmsGrpNam as DSNAM');
        $SAP_MSI->from('OITM t0 with (NOLOCK) ');
        $SAP_MSI->join('OITB t1','t1.ItmsGrpCod = t0.ItmsGrpCod','inner');
        $SAP_MSI->where("t1.ItmsGrpCod in (select ItmsGrpCod from oitm with (NOLOCK) where U_GrNonPO = 'Y')");
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
  
    function cancelHeaderGrNonPo($data){
        $this->db->set('status', '3');
        $this->db->where('id_grnonpo_header', $data['id_grnonpo_header']);
        if($this->db->update('t_grnonpo_header')){
            return TRUE;
            
        }else{
            return FALSE;
        }
    }

    function cancelDetailsGrNonPo($data){
        $this->db->set('ok_cancel', '1');
        $this->db->where('id_grnonpo_detail', $data);
        if($this->db->update('t_grnonpo_detail')){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    function t_grnonpo_header_delete($id_grnonpo_header){
        $data = $this->grnonpo_header_select($id_grnonpo_header);
        $status = $data['status'];
        if ($status != 2) {
            if($this->t_grnonpo_details_delete($id_grnonpo_header)){
                $this->db->where('id_grnonpo_header', $id_grnonpo_header);
                if($this->db->delete('t_grnonpo_header'))
                    return TRUE;
                else
                    return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    function t_grnonpo_details_delete($id_grnonpo_header) {
        $this->db->where('id_grnonpo_header', $id_grnonpo_header);
        if($this->db->delete('t_grnonpo_detail'))
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
        $this->db->where('DATE(posting_date)', $posting_date);
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

    function grnonpo_header_insert($data) {
		if($this->db->insert('t_grnonpo_header', $data))
			return $this->db->insert_id();
		else
			return FALSE;
    }

    function grnonpo_details_insert($data) {
		if($this->db->insert('t_grnonpo_detail', $data))
			return $this->db->insert_id();
		else
			return FALSE;
    }

    function tampil($id){
        $query = $this->db->query("SELECT FORMAT(getdate(), 'dd-MM-yyyy', 'en-US') posting_date, a.grnonpo_no, FORMAT(getdate(), 'dd-MM-yyyy', 'en-US') lastmodified, a.plant, a.plant_name, a.cost_center, a.remark, b.material_no, b.material_desc, b.uom, b.quantity, b.price FROM t_grnonpo_header a JOIN t_grnonpo_detail b ON a.id_grnonpo_header = b.id_grnonpo_header where a.id_grnonpo_header ='$id' ");
          
        return $query;  
    }

    function sap_item_select_by_item_code($item_code) {
        $this->db->select('m_item.*,m_uom.UomCode as UNIT1');
        $this->db->select("(REPLACE(m_item.MATNR,REPLICATE('0',(12)),SPACE(0))) AS MATNR1");
        $this->db->from('m_item');
        $this->db->join('m_uom','m_uom.UomEntry = m_item.UNIT','left');
        $this->db->where('m_item.MATNR', $item_code);
        $query = $this->db->get();
        
        if(($query)&&($query->num_rows() > 0)) {
            return $query->row_array();
        }	else {
            return FALSE;
        }
    } 

    function get_cost_center($whsCode){
        $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
        $SAP_MSI->select('u_outlet');
        $SAP_MSI->from('OWHS');
        $SAP_MSI->where('WhsCode',$whsCode);
        $query_cc = $SAP_MSI->get();
        $cc = $query_cc->row_array();
        return $cost_center = $cc['u_outlet'];
    }

}