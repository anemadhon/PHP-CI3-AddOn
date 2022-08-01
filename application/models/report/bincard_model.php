<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Bincard_model extends CI_Model {

    function __construct(){
        parent::__construct(); 
    }

    function warehouse(){
        $notIn = array('01','02','03','IC_DShip');
        $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
        $SAP_MSI->select('WhsCode,WhsName');
        $SAP_MSI->from('OWHS');
        $SAP_MSI->where('LEFT(WhsCode,2)!=','T.');
        $SAP_MSI->where_not_in('WhsCode',$notIn);

        $query = $SAP_MSI->get();
        $ret = $query->result_array();
        return $ret;
    }

    function item_group(){
        $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
        $SAP_MSI->select('ItemCode,ItemName,DocEntry');
        $SAP_MSI->from('OITM with (NOLOCK)');
        $SAP_MSI->where('validFor', 'Y');
        $SAP_MSI->order_by('DocEntry','desc');
        $query = $SAP_MSI->get();
        $ret = $query->result_array();
        return $ret;
    }

    function getData($fromDate, $itemGroup, $itemGroup2, $warehouse,$toDate,  $length, $start){
        $kd_plant = $this->session->userdata['ADMIN']['plant'];
        $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
        $SAP_MSI->select('A.DocEntry,A.ItemCode,A.ItemName');
        $SAP_MSI->from('OITM A with (NOLOCK)');
        $SAP_MSI->join('OIVL B', 'A.ItemCode=B.ItemCode');
        $SAP_MSI->where('A.DocEntry >=', $itemGroup);
        $SAP_MSI->where('A.DocEntry <=', $itemGroup2);
        $SAP_MSI->where('DocDate >=', $fromDate);
        $SAP_MSI->where('DocDate <=', $toDate);
        $SAP_MSI->where('B.LocCode', $warehouse);
        $SAP_MSI->where('LEFT(B.LocCode,2)!=','T.');
        $SAP_MSI->group_by('A.DocEntry,A.ItemCode,A.ItemName');
        $SAP_MSI->order_by('DocEntry');
        $SAP_MSI->limit($length,$start);
        $query = $SAP_MSI->get();

        if(($query)&&($query->num_rows()>0))
            return $query->result_array();
		else
			return FALSE;  
    }

    function totalDataBincard($fromDate, $itemGroup, $itemGroup2, $warehouse,$toDate){
        $kd_plant = $this->session->userdata['ADMIN']['plant'];
        $SAP_MSI = $this->load->database('SAP_MSI', TRUE);

        $SAP_MSI->select('COUNT(*) num');
        $SAP_MSI->from('OITM A with (NOLOCK)');
        $SAP_MSI->join('OIVL B', 'A.ItemCode=B.ItemCode');
        $SAP_MSI->where('A.DocEntry >=', $itemGroup);
        $SAP_MSI->where('A.DocEntry <=', $itemGroup2);
        $SAP_MSI->where('DocDate >=', $fromDate);
        $SAP_MSI->where('DocDate <=', $toDate);
        $SAP_MSI->where('B.LocCode', $warehouse);
        $SAP_MSI->where('LEFT(B.LocCode,2)!=','T.');
        $query = $SAP_MSI->get();

        if(($query)&&($query->num_rows()>0))
            return $query->result_array();
		else
			return FALSE;

    }

    // Quantity In start 
    function qty_ck($itemCode, $fromDate, $toDate){
        $kd_plant = $this->session->userdata['ADMIN']['plant'];
        $this->db->select('SUM(c.gr_quantity) qty_ck');
        $this->db->from('t_grpodlv_header b');
        $this->db->join('t_grpodlv_detail c', 'b.id_grpodlv_header = c.id_grpodlv_header');
        $this->db->where('b.do_no !=', '');
        $this->db->where('b.grpodlv_no !=', '');
        $this->db->where('c.material_no', $itemCode);
        $this->db->where('b.plant', $kd_plant);
        $this->db->not_like('b.do_no', '%C%');
        $this->db->not_like('b.grpodlv_no', '%C%');
        $this->db->where('b.posting_date >=', $fromDate);
        $this->db->where('b.posting_date <=', $toDate);
        
        $query = $this->db->get();

        if(($query)&&($query->num_rows()>0))
            return $query->result_array();
		else
			return FALSE;
    }

    function qty_fo($itemCode, $fromDate, $toDate){
        $kd_plant = $this->session->userdata['ADMIN']['plant'];
        $this->db->select('SUM(c.gr_quantity) qty_fo');
        $this->db->from('t_grsto_header b');
        $this->db->join('t_grsto_detail c', 'b.id_grsto_header = c.id_grsto_header');
        $this->db->where('b.po_no !=', '');
        $this->db->where('b.grsto_no !=', '');
        $this->db->where('b.no_doc_gist !=', '');
        $this->db->where('c.material_no', $itemCode);
        $this->db->where('b.plant', $kd_plant);
        $this->db->not_like('b.po_no', '%C%');
        $this->db->not_like('b.grsto_no', '%C%');
        $this->db->not_like('b.no_doc_gist', '%C%');
        $this->db->where('b.posting_date >=', $fromDate);
        $this->db->where('b.posting_date <=', $toDate);
        
        $query = $this->db->get();

        if(($query)&&($query->num_rows()>0))
            return $query->result_array();
		else
			return FALSE;
    }

    function qty_retin($itemCode, $fromDate, $toDate){
        $kd_plant = $this->session->userdata['ADMIN']['plant'];
        $this->db->select('SUM(c.gr_quantity) qty_retin');
        $this->db->from('t_retin_header b');
        $this->db->join('t_retin_detail c', 'b.id_retin_header = c.id_retin_header');
        $this->db->where('b.retin_no !=', '');
        $this->db->where('b.do_no !=', '');
        $this->db->where('c.material_no', $itemCode);
        $this->db->where('b.plant', $kd_plant);
        $this->db->not_like('b.retin_no', '%C%');
        $this->db->not_like('b.do_no', '%C%');
        $this->db->where('b.posting_date >=', $fromDate);
        $this->db->where('b.posting_date <=', $toDate);
        
        $query = $this->db->get();

        if(($query)&&($query->num_rows()>0))
            return $query->result_array();
		else
			return FALSE;
    }
    // Quantity In End

    // Quantity Out start
    function qty_to($itemCode, $fromDate, $toDate){
        $kd_plant = $this->session->userdata['ADMIN']['plant'];
        $this->db->select('SUM(c.gr_quantity) qty_to');
        $this->db->from('t_gistonew_out_header b');
        $this->db->join('t_gistonew_out_detail c', 'b.id_gistonew_out_header = c.id_gistonew_out_header');
        $this->db->where('b.po_no !=', '');
        $this->db->where('b.gistonew_out_no !=', '');
        $this->db->where('c.material_no', $itemCode);
        $this->db->where('b.plant', $kd_plant);
        $this->db->not_like('b.gistonew_out_no', '%C%');
        $this->db->not_like('b.po_no', '%C%');
        $this->db->where('b.posting_date >=', $fromDate);
        $this->db->where('b.posting_date <=', $toDate);
        
        $query = $this->db->get();

        if(($query)&&($query->num_rows()>0))
            return $query->result_array();
		else
			return FALSE;
    }

    function qty_ro($itemCode, $fromDate, $toDate){
        $kd_plant = $this->session->userdata['ADMIN']['plant'];
        $this->db->select('SUM(c.gr_quantity) qty_ro');
        $this->db->from('t_gisto_dept_header b');
        $this->db->join('t_gisto_dept_detail c', 'b.id_gisto_dept_header = c.id_gisto_dept_header');
        $this->db->where('b.gisto_dept_no !=', '');
        $this->db->where('b.po_no !=', '');
        $this->db->where('c.material_no', $itemCode);
        $this->db->where('b.plant', $kd_plant);
        $this->db->not_like('b.gisto_dept_no', '%C%');
        $this->db->not_like('b.po_no', '%C%');
        $this->db->where('b.posting_date >=', $fromDate);
        $this->db->where('b.posting_date <=', $toDate);
        
        $query = $this->db->get();

        if(($query)&&($query->num_rows()>0))
            return $query->result_array();
		else
			return FALSE;
    }

}