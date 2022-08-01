<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Spoiled_model extends CI_Model {
  
    function t_waste_header($fromDate, $toDate, $status){
        $kd_plant = $this->session->userdata['ADMIN']['plant'];
        $this->db->select('t_waste_header.*,(select admin_realname from d_admin where admin_id = t_waste_header.id_user_input) as user_input, (select admin_realname from d_admin where admin_id = t_waste_header.id_user_approved) as user_approved ');
        $this->db->from('t_waste_header');
        $this->db->where('t_waste_header.plant',$kd_plant);

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
    
        $this->db->order_by('id_waste_header', 'desc');
    
        $query = $this->db->get();
        $ret = $query->result_array();
        return $ret;
    }

    function t_waste_header_select($id_waste_header) {
      
        $this->db->from('t_waste_header');
        $this->db->where('id_waste_header', $id_waste_header);
  
        $query = $this->db->get();
  
        if(($query)&&($query->num_rows() > 0))
          return $query->row_array();
        else
          return FALSE;
    }

    function t_waste_details_select($id_waste_header) {
		$this->db->from('t_waste_detail');
        $this->db->where('id_waste_header', $id_waste_header);

            $query = $this->db->get();

            if(($query)&&($query->num_rows() > 0))
                return $query->result_array();
            else
                return FALSE;
    }

    function showMatrialGroup(){
        $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
        $SAP_MSI->select('ItmsGrpNam');
        $SAP_MSI->from('OITB');
    
        $query = $SAP_MSI->get();
        $ret = $query->result_array();
        return $ret;
    }

    function getDataMaterialGroup($item_group_code ='all'){
        $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
        $SAP_MSI->select('t0.ItemCode as MATNR,t0.ItemName as MAKTX,t0.ItmsGrpCod as DISPO,t0.BuyUnitMsr as UNIT,t1.ItmsGrpNam as DSNAM');
        $SAP_MSI->from('OITM  t0 with (NOLOCK) ');
        $SAP_MSI->join('oitb t1','t1.ItmsGrpCod = t0.ItmsGrpCod','inner');
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
        if(($itemSelect != '') || ($itemSelect != null)){

            $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
            $SAP_MSI->select('t0.ItemCode as MATNR,t0.ItemName as MAKTX,t0.ItmsGrpCod as DISPO,t0.InvntryUom as UNIT,t1.ItmsGrpNam as DSNAM');
            $SAP_MSI->from('OITM  t0 with (NOLOCK) ');
            $SAP_MSI->join('oitb t1','t1.ItmsGrpCod = t0.ItmsGrpCod','inner');
            $SAP_MSI->where('validFor', 'Y');
            $SAP_MSI->where('InvntItem', 'Y');
            $SAP_MSI->where('t0.ItemCode',$itemSelect);

            $query = $SAP_MSI->get();
            return $query->result_array();
        }else{
            return false;
        }
    }
    
    function getInWhsQtyCommited($itemNo){
        $kd_plant = $this->session->userdata['ADMIN']['plant'];
        $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
        $SAP_MSI->select('(OnHand - IsCommited) as OnHand');
        $SAP_MSI->from('OITW');
        $SAP_MSI->where('WhsCode', $kd_plant);
        $SAP_MSI->where('ItemCode', $itemNo);

        $query = $SAP_MSI->get();

        if(($query)&&($query->num_rows()>0))
            return $query->result_array();
		else
			return FALSE;
    }

    function id_waste_plant_new_select($id_outlet,$created_date="",$id_waste_header="") {

        if (empty($created_date))
           $created_date=$this->m_general->posting_date_select_max();
        if (empty($id_outlet))
           $id_outlet=$this->session->userdata['ADMIN']['plant'];

		$this->db->select_max('id_waste_plant');
		$this->db->from('t_waste_header');
		$this->db->where('plant', $id_outlet);
	  	$this->db->where('posting_date', $created_date);
        if (!empty($id_waste_header)) {
    		$this->db->where('id_waste_header <> ', $id_waste_header);
        }

		$query = $this->db->get();

		if($query->num_rows() > 0) {
			$stdstock = $query->row_array();
			$id_stdstock_outlet = $stdstock['id_waste_plant'] + 1;
		}	else {
			$id_stdstock_outlet = 1;
		}

		return $id_stdstock_outlet;
    }

    function waste_header_insert($data) {
		if($this->db->insert('t_waste_header', $data))
			return $this->db->insert_id();
		else
			return FALSE;
    }

    function waste_details_insert($data) {
		if($this->db->insert('t_waste_detail', $data))
			return $this->db->insert_id();
		else
			return FALSE;
    }

    function t_waste_header_delete($id){
        if($this->t_waste_details_delete($id)){
          $this->db->where('id_waste_header', $id);
          if($this->db->delete('t_waste_header'))
              return TRUE;
          else
              return FALSE;
        }
    }
    
    function t_waste_details_delete($id){
        $this->db->where('id_waste_header', $id);
        if($this->db->delete('t_waste_detail'))
            return TRUE;
        else
            return FALSE;
        }
    
    function waste_header_update($data){
        $update = array(
            'status' => $data['status'],
            'posting_date' => $data['posting_date'],
            'id_user_approved' => $data['id_user_approved'],
            'no_acara' => $data['no_acara']
        );
        $this->db->where('id_waste_header', $data['id_waste_header']);
        if($this->db->update('t_waste_header', $update))
            return TRUE;
        else
            return FALSE;
    }

    function tampil($id_waste_header)
	{
        $query = $this->db->query("SELECT a.plant,a.posting_date,b.material_no,b.material_desc,b.uom,b.quantity,b.reason_name,b.other_reason,a.no_acara ,c.OUTLET_NAME1,a.material_doc_no
        FROM t_waste_header a
        JOIN t_waste_detail b ON a.id_waste_header=b.id_waste_header 
        JOIN m_outlet c ON a.plant=c.OUTLET
        WHERE a.id_waste_header =".$id_waste_header."");
        return $query;
    }
  
}