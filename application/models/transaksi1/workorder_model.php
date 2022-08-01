<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Workorder_model extends CI_Model {

	/* public function getDataWoVendor_Header($fromDate='', $toDate='', $status='', $length, $start, $type = ''){
		$arr 		=	array();
		$kd_plant = $this->session->userdata['ADMIN']['plant'];
		//a.*
		$this->db->select('a.id_produksi_header, a.id_produksi_plant, a.posting_date, a.produksi_no, a.plant, a.plant_name, a.storage_location, a.storage_location_name, a.created_date, a.kode_paket, a.nama_paket, a.qty_paket, a.status, a.id_user_input, a.id_user_cancel, a.filename, 
		a.lastmodified, a.num, a.uom_paket, a.back, a.doc_issue, a.produksi_no1, a.id_user_approved,
		(SELECT admin_realname FROM d_admin WHERE admin_id = a.id_user_input)created_by,
		(SELECT admin_realname FROM d_admin WHERE admin_id = a.id_user_approved)approved_by');
		$this->db->from('t_produksi_header a');
		$this->db->join('t_produksi_detail b', 'a.id_produksi_header = b.id_produksi_header');
		$this->db->where('a.plant', $kd_plant);
		$this->db->where("isnull(a.num,'') = ''");
		
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
		$this->db->group_by('a.id_produksi_header');
		$this->db->group_by('a.id_produksi_plant');
		$this->db->group_by('a.posting_date');
		$this->db->group_by('a.produksi_no');
		$this->db->group_by('a.plant');
		$this->db->group_by('a.plant_name');
		$this->db->group_by('a.storage_location');
		$this->db->group_by('a.storage_location_name');
		$this->db->group_by('a.created_date');
		$this->db->group_by('a.kode_paket');
		$this->db->group_by('a.nama_paket');
		$this->db->group_by('a.qty_paket');
		$this->db->group_by('a.status');
		$this->db->group_by('a.id_user_input');
		$this->db->group_by('a.id_user_cancel');
		$this->db->group_by('a.id_user_approved');
		$this->db->group_by('a.filename');
		$this->db->group_by('a.lastmodified');
		$this->db->group_by('a.num');
		$this->db->group_by('a.uom_paket');
		$this->db->group_by('a.back');
		$this->db->group_by('a.doc_issue');
		$this->db->group_by('a.produksi_no1');
		// $this->db->group_by('a.approved_time');
		// $this->db->group_by('a.integrated_time');
		// $this->db->group_by('a.time_to_integrate');
		// $this->db->group_by('a.no_integrate_retries');
		// $this->db->group_by('b.doc_issue');
		$this->db->order_by('a.id_produksi_header', 'DESC');
		$this->db->limit($length, $start);

		$query = $this->db->get();
		$wo = $query->result_array();
		return $wo;
	} */
	
	public function getDataWoVendor_Header($fromDate='', $toDate='', $status='', $length, $start, $type = ''){
		$kd_plant = $this->session->userdata['ADMIN']['plant'];
		$this->db->select('a.id_produksi_header, a.posting_date, a.plant, a.kode_paket as material_no, 
		a.nama_paket as material_desc, a.qty_paket as qty, a.uom_paket as uom, 
		a.outstd_qty_to_intgrte as outstd_qty_to_intgrte_h, a.total_qty_intgrted as total_qty_intgrted_h, a.status_desc');
		$this->db->from('t_produksi_header a');
		//$this->db->join('t_produksi_detail b', 'a.id_produksi_header = b.id_produksi_header');
		/* b.id_produksi_detail, b.material_no, b.material_desc, b.qty, b.uom, b.outstd_qty_to_intgrte as outstd_qty_to_intgrte_d, b.total_qty_intgrted as total_qty_intgrted_d,
		(SELECT admin_realname FROM d_admin WHERE admin_id = a.id_user_input) created_by,
		(SELECT admin_realname FROM d_admin WHERE admin_id = a.id_user_approved) approved_by' */
		$this->db->where('a.plant', $kd_plant);
		//$this->db->where("isnull(a.num,'') = ''");
		
		if((!empty($fromDate)) || (!empty($toDate))){
			if( (!empty($fromDate)) || (!empty($toDate)) ) {
				$this->db->where("posting_date BETWEEN '$fromDate' AND '$toDate'");
			} else if( (!empty($fromDate))) {
				$this->db->where("posting_date >= '$fromDate'");
			} else if( (!empty($toDate))) {
				$this->db->where("posting_date <= '$toDate'");
			}
		}
		
		if ($type === '') {
			$this->db->where('a.outstd_qty_to_intgrte >', 0);
		}

		if((!empty($status))){
			$this->db->where('status_desc', $status);
		}
		
		$this->db->order_by('a.id_produksi_header', 'DESC');
		if ($length && $start) {
			$this->db->limit($length, $start);
		}

		$query = $this->db->get();
		$wo = $query->result_array();
		return $wo;
	}

	public function getCountDataWoVendor_Header($fromDate='', $toDate='', $status='', $type = ''){
		$arr 		=	array();
		$kd_plant = $this->session->userdata['ADMIN']['plant'];
		$this->db->select('COUNT(a.id_produksi_header) num'); //DISTINCT 
		$this->db->from('t_produksi_header a');
		// $this->db->join('t_produksi_detail b', 'a.id_produksi_header = b.id_produksi_header');
		$this->db->where('a.plant', $kd_plant);
		//$this->db->where("isnull(a.num,'') = ''");
		
		if((!empty($fromDate)) || (!empty($toDate))){
			if( (!empty($fromDate)) || (!empty($toDate)) ) {
				$this->db->where("posting_date BETWEEN '$fromDate' AND '$toDate'");
			} else if( (!empty($fromDate))) {
				$this->db->where("posting_date >= '$fromDate'");
			} else if( (!empty($toDate))) {
				$this->db->where("posting_date <= '$toDate'");
			}
		}

		if ($type === '') {
			$this->db->where('a.outstd_qty_to_intgrte >', 0);
		}

		if((!empty($status))){
			$this->db->where('status_desc', $status);
		}

		$query = $this->db->get();

		if($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return FALSE;
		}
	}
  
	function wo_header_delete($id_wo_header){
		$data = $this->wo_header_select($id_wo_header);
		$status = $data['status'];
		if ($status!=2) {
			if($this->wo_details_delete($id_wo_header)){
				$this->db->where('id_produksi_header', $id_wo_header);
				if($this->db->delete('t_produksi_header'))
					return TRUE;
				else
					return FALSE;
			}
		} else {
			return FALSE;
		}
	}
	
	function wo_details_delete($id_wo_header) {
		$this->db->where('id_produksi_header', $id_wo_header);
		if($this->db->delete('t_produksi_detail'))
			return TRUE;
		else
			return FALSE;
	}

	function wo_header_select($id_wo_header){
		$arr 	=	array();
		$this->db->from('t_produksi_header');
		$this->db->where('id_produksi_header', $id_wo_header);
		$query = $this->db->get();

		if(($query)&&($query->num_rows() > 0)){
			return $query->row_array();
		}else{
		return FALSE;
		}
	}
  
	function wo_detail_valid($material_no){
		$SAP_MSI = $this->load->database('SAP_MSI', TRUE);
		$SAP_MSI->select('OITM.validFor,OITB.DecreasAc');
		$SAP_MSI->from('OITM with (NOLOCK)');
		$SAP_MSI->join('OITB','OITM.ItmsGrpCod = OITB.ItmsGrpCod');
		$SAP_MSI->where('ItemCode',$material_no);
		$query = $SAP_MSI->get();

		if(($query)&&($query->num_rows() > 0)){
			return $query->result_array();
		}else{
		return FALSE;
		}
	}
  
	function wo_detail_onhand($material_no){
		$kd_plant = $this->session->userdata['ADMIN']['plant'];
		$SAP_MSI = $this->load->database('SAP_MSI', TRUE);
		$SAP_MSI->select("(OnHand -isnull((select sum(OpenQty) from WTQ1 a where a.ItemCode = oitw.ItemCode 
		and a.FromWhsCod = oitw.WhsCode),0)) as OnHand,MinStock");
		$SAP_MSI->from('OITW');
		$SAP_MSI->where('ItemCode',$material_no);
		$SAP_MSI->where('WhsCode',$kd_plant);
		$query = $SAP_MSI->get();

		if(($query)&&($query->num_rows() > 0)){
			return $query->result_array();
		}else{
			return FALSE;
		}
	}
	
	function wo_detail_itemcodebom($kode_paket,$material_no){
		$SAP_MSI = $this->load->database('SAP_MSI', TRUE);
		$SAP_MSI->distinct();
		$SAP_MSI->select("T1.U_SubsName as NAME, T1.U_ItemCodeBOM, T1.U_SubsQty, T1.U_SubsCode, T1.Code, T1.U_SubsUOM");
		$SAP_MSI->from('@MSI_ALT_ITM_HDR T0');
		$SAP_MSI->join('@MSI_ALT_ITM_DTL T1','T1.Code = T0.Code');
		$SAP_MSI->where('T0.Code',$kode_paket);
		$SAP_MSI->where('T1.U_ItemCodeBOM',$material_no);
		$query = $SAP_MSI->get();
		if(($query)&&($query->num_rows() > 0)){
			return $query->result_array();
		}else{
			return FALSE;
		}
	} 
  
	function wo_detail_openqty($material_no){
		$kd_plant = $this->session->userdata['ADMIN']['plant'];
		$SAP_MSI = $this->load->database('SAP_MSI', TRUE);
		$SAP_MSI->select("SUM(OpenQty) as OpenQty");
		$SAP_MSI->from('WTQ1');
		$SAP_MSI->where('ItemCode',$material_no);
		$SAP_MSI->where('WhsCode',$kd_plant);
		$query = $SAP_MSI->get();

		if(($query)&&($query->num_rows() > 0)){
			return $query->result_array();
		}else{
		return FALSE;
		}
	}
  
	function wo_detail_item(){
		$SAP_MSI = $this->load->database('SAP_MSI', TRUE);
		$SAP_MSI->select('ItemCode as MATNR, ItemName as MAKTX');
		$SAP_MSI->from('OITM with (NOLOCK)');

		$query = $SAP_MSI->get();

		if(($query)&&($query->num_rows() > 0)){
			return $query->result_array();
		}else{
		return FALSE;
		}
	}
	
	function wo_detail_uom($material_no){
		$SAP_MSI = $this->load->database('SAP_MSI', TRUE);
		$SAP_MSI->select('InvntryUom as UNIT');
		$SAP_MSI->from('OITM with (NOLOCK)');

		if($material_no != ''){
			$SAP_MSI->where('ItemCode',$material_no);
		}
		$query = $SAP_MSI->get();

		if(($query)&&($query->num_rows() > 0)){
			return $query->result_array();
		}else{
		return FALSE;
		}
	}
  
	function wo_detail_ucaneditqty($kode_paket,$material_no){
		$SAP_MSI = $this->load->database('SAP_MSI', TRUE);
		$SAP_MSI->select("U_CanEditQty as CanEditQty");
		$SAP_MSI->from('ITT1');
		$SAP_MSI->where('Code',$material_no);
		$SAP_MSI->where('Father',$kode_paket);
		$query = $SAP_MSI->get();


		if(($query)&&($query->num_rows() > 0)){
			return $query->result_array();
		}else{
		return FALSE;
		}
	}

	function sap_wo_select_locked($material_no){
		$SAP_MSI = $this->load->database('SAP_MSI', TRUE);
		$SAP_MSI->select("U_Locked");
		$SAP_MSI->from('OITT');
		$SAP_MSI->where('Code', $material_no);
		$query = $SAP_MSI->get();

		if(($query)&&($query->num_rows() > 0)){
			return $query->result_array();
		}else{
		return FALSE;
		}
	}
  
	function sap_wo_headers_select_by_item($material_no=''){
		$SAP_MSI = $this->load->database('SAP_MSI', TRUE);
		$SAP_MSI->select("T0.Code, T1.ItemName, T0.U_Locked, T1.InvntryUom, T0.Qauntity");
		$SAP_MSI->from('OITT T0');
		$SAP_MSI->join('OITM T1 with (NOLOCK)','T1.ItemCode = T0.Code');
		$SAP_MSI->where("ISNULL(U_CantProduce,'') <> 'Y' ", null, false);
		
		if($material_no != ''){
			$SAP_MSI->where('T0.Code',$material_no);
		}
		$query = $SAP_MSI->get();

		if(($query)&&($query->num_rows() > 0)){
			return $query->result_array();
		}else{
		return FALSE;
		}
	}

	function wo_detail_quantity($kode_paket,$material_no){
		$kd_plant = $this->session->userdata['ADMIN']['plant'];
		$this->db->select('quantity,quantity_paket');
		$this->db->from('m_mpaket_detail');
		$this->db->join('m_mpaket_header','m_mpaket_detail.id_mpaket_header = m_mpaket_header.id_mpaket_header');
		$this->db->where('m_mpaket_header.kode_paket', $kode_paket);
		$this->db->where('m_mpaket_header.plant', $kd_plant);
		$this->db->where('m_mpaket_detail.material_no', $material_no);
		$query = $this->db->get();

		if(($query)&&($query->num_rows() > 0)){
			return $query->result_array();
		}else{
		return FALSE;
		}
	}
  
	function wo_details_input_select($kode_paket){
		$SAP_MSI = $this->load->database('SAP_MSI', TRUE);
		$SAP_MSI->select("a.Father id_mpaket_header,a.ChildNum id_mpaket_h_detail, a.Code material_no, b.ItemName material_desc, a.Quantity quantity, b.InvntryUom uom, ISNULL(b.U_canInputBlnk,'N') as canInpuntBlnk, ISNULL(b.u_RatioQty,1) as RatioQty");
		$SAP_MSI->from('ITT1 a');
		$SAP_MSI->join('OITM b with (NOLOCK)', 'a.Code = b.ItemCode');
		$SAP_MSI->where('a.Father', $kode_paket);

		$query = $SAP_MSI->get();

		if(($query)&&($query->num_rows() > 0)){
			return $query->result_array();
		}else{
			return FALSE;
		}
	}
	
	function get_ratio_and_can_blank_for_expand($material_no){
		$SAP_MSI = $this->load->database('SAP_MSI', TRUE);
		$SAP_MSI->select("ItemCode, ISNULL(U_canInputBlnk,'N') as canInpuntBlnk, ISNULL(u_RatioQty,1) as RatioQty");
		$SAP_MSI->from('OITM');
		$SAP_MSI->where('ItemCode', $material_no);

		$query = $SAP_MSI->get();

		if(($query)&&($query->num_rows() > 0)){
			return $query->row_array();
		}else{
			return FALSE;
		}
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
  
	function id_produksi_plant_new_select($id_outlet,$posting_date="",$id_produksi_header="") {

        if (empty($posting_date))
		   $posting_date=$this->posting_date_select_max();
		   $posting_date = strtotime($posting_date);
		   $posting_date = date("Y-m-d", $posting_date);
        if (empty($id_outlet))
           $id_outlet=$this->session->userdata['ADMIN']['plant'];
		
		$this->db->select_max('id_produksi_plant');
		$this->db->from('t_produksi_header');
		$this->db->where('plant', $id_outlet);
		$this->db->where('posting_date', $posting_date);
		//$this->db->where("isnull(num,'') = ''");
        if (!empty($id_produksi_header)) {
    	  $this->db->where('id_produksi_header <> ', $id_produksi_header);
        }

		$query = $this->db->get();

		if(($query)&&($query->num_rows() > 0)) {
			$produksi = $query->row_array();
			$id_produksi_outlet = $produksi['id_produksi_plant'] + 1;
		}	else {
			$id_produksi_outlet = 1;
		}

		return $id_produksi_outlet;
	}

	function wo_integrasi($id_header) ///
	{
		$update = ['status' => 1];

		$valid = [];

        $this->db->where('id_produksi_header', $id_header);
		$this->db->where('status', 0);

        if($this->db->update('t_produksi_intgrtn', $update)) {
			$details = $this->wo_details_select($id_header);
			foreach ($details as $detail) {
				$this->db->where('id_produksi_detail', $detail['id_produksi_detail']);
				$this->db->where('status', 0);

				if ($this->db->update('t_produksi_detail_intgrtn', $update)) {
					$valid[] = 1;
				} else {
					$valid[] = 0;
				}
			}

			if (in_array(0, $valid)) {
				return FALSE;
			}

			return TRUE;

		} else {
			return FALSE;
		}
	}

	function get_filter_status_desc($type = '')
	{
		$this->db->distinct();
		$this->db->select('a.status_desc');
		$this->db->from('t_produksi_header a');
		$this->db->where('a.status_desc <>', null);
		if ($type === '') {
			$this->db->where('a.status_desc <>', 'Approved / Fully Integrated');
		}

		$query = $this->db->get();
		return $query->result_array();
	}
  
	function wo_details_select($id_wo_header, $kode_paket = '', $qty_paket = ''){
		$arr 	=	array();
		$this->db->from('t_produksi_detail');
		$this->db->where('id_produksi_header', $id_wo_header);
		$this->db->order_by('id_produksi_detail');
		$query = $this->db->get();
		$ret = $query->result_array();
		return $ret;
	}

	function wo_header_batch($item,$whs){
		$this->db->select('*');
		$this->db->from('m_batch');
		$this->db->where('ItemCode', $item);
		$this->db->where('Whs', $whs);
		$query = $this->db->get();

		if(($query)&&($query->num_rows() > 0)){
			return $query->result_array();
		}else{
		return FALSE;
		}
	}

	function produksi_detail_insert($data) {
		if($this->db->insert('t_produksi_detail', $data))
			return $this->db->insert_id();
		else
			return FALSE;
	}

	function produksi_header_insert($data) {
		if($this->db->insert('t_produksi_header', $data))
			return $this->db->insert_id();
		else
			return FALSE;
	}
	
	function produksi_detail_expand_insert($data) {
		if($this->db->insert('t_produksi_detail_expand', $data))
			return $this->db->insert_id();
		else
			return FALSE;
	}

	function wo_details_expand_delete($id_wo_header) {
		$this->db->where('id_produksi_header', $id_wo_header);
		if($this->db->delete('t_produksi_detail_expand'))
			return TRUE;
		else
			return FALSE;
	}

	function select_detail_expand($id_detail, $parent_code){
		$this->db->from('t_produksi_detail_expand');
		$this->db->where('id_produksi_detail', $id_detail);
		$this->db->where('material_no_parent_expand', $parent_code);
		$query = $this->db->get();

		if(($query)&&($query->num_rows() > 0)){
			return $query->result_array();
		}else{
			return FALSE;
		}
	}

	function update_produksi_header($produksi_header){
		$update = array(
			'status' => $produksi_header['status'],
			'id_user_approved' => $produksi_header['id_user_approved']
		);
		$this->db->where('id_produksi_header', $produksi_header['id_produksi_header']);
        if($this->db->update('t_produksi_header', $update))
			return TRUE;
		else
			return FALSE;
	}

	function sap_item($itemNo = ''){
		$kd_plant = $this->session->userdata['ADMIN']['plant'];
        $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
        $SAP_MSI->select('t0.ItemCode as MATNR,t0.ItemName as MAKTX,t0.ItmsGrpCod as DISPO,t0.InvntryUom as UNIT,t1.ItmsGrpNam as DSNAM');
        $SAP_MSI->from('OITM  t0 with (NOLOCK)');
        $SAP_MSI->join('oitb t1','t1.ItmsGrpCod = t0.ItmsGrpCod','inner');
        $SAP_MSI->where('validFor', 'Y');
		$SAP_MSI->where('t0.InvntItem', 'Y');

		if($itemNo != ''){
			$SAP_MSI->where('ItemCode', $itemNo);
		}
		
		$query = $SAP_MSI->get();
        
        if(($query)&&($query->num_rows() > 0)) {
            return $query->result_array();
        }else {
            return FALSE;
        }
	}

	function get_max_attempt_whs(){
		$SAP_MSI = $this->load->database('SAP_MSI', TRUE);
		$kd_plant = $this->session->userdata['ADMIN']['plant'];
		$SAP_MSI->select('U_MaxProsesWO as max_attempt');
		$SAP_MSI->from('OWHS');
		$SAP_MSI->where('WhsCode', $kd_plant);
  
		$query = $SAP_MSI->get();
		$max = $query->row_array();
		return $max;
	}

	function get_attempt_header($idHeader){
        $this->db->select('a.*');
        $this->db->from('t_produksi_intgrtn a');
        $this->db->where('id_produksi_header', $idHeader);

        $query = $this->db->get();
        return $query->result_array();
    }
	
	function get_attempt_detail($idDetail){
        $this->db->select('a.*');
        $this->db->from('t_produksi_detail_intgrtn a');
        $this->db->where('id_produksi_detail', $idDetail);

        $query = $this->db->get();
        return $query->result_array();
    }
}