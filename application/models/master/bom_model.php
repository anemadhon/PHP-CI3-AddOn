<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Bom_model extends CI_Model{

    function __construct(){
        parent::__construct(); 
    }

    public function getDataBom_Header($itemCode=''){
        $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
		$SAP_MSI->select('a.Code, b.ItemName, a.Qauntity');
        $SAP_MSI->from('OITT a');
        $SAP_MSI->join('OITM b','a.Code = b.ItemCode');
        
        if(!empty($itemCode)) {
            $SAP_MSI->where('a.Code',$itemCode);
        }

        $query = $SAP_MSI->get();

        if(($query)&&($query->num_rows() > 0)) {
            $pos = $query->result_array();
            return $pos;
        } else {
            return FALSE;
        }
    }
    
    public function getDataBom_Detail($itemCode){
        $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
		$SAP_MSI->select('a.Code, b.ItemName, a.Quantity, b.InvntryUom');
        $SAP_MSI->from('ITT1 a');
        $SAP_MSI->join('OITM b with (NOLOCK)','a.Code = b.ItemCode');
        $SAP_MSI->where('a.Father',$itemCode);

        $query = $SAP_MSI->get();

        if(($query)&&($query->num_rows() > 0)) {
            $pos = $query->result_array();
            return $pos;
        } else {
            return FALSE;
        }
	}
}