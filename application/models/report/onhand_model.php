<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Onhand_model extends CI_Model {

    function __construct(){
        parent::__construct(); 
    }

    function item_group(){
        $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
        $SAP_MSI->select('ItmsGrpNam');
        $SAP_MSI->from('OITB');

        $query = $SAP_MSI->get();
        $ret = $query->result_array();
        return $ret;
    }

    function getData($itemGroup){
        $kd_plant = $this->session->userdata['ADMIN']['plant'];
        $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
        $SAP_MSI->select('A.ItemCode,B.ItemName,A.OnHand,C.ItmsGrpNam,B.InvntryUom Uom, B.AvgPrice ItemCost');
        $SAP_MSI->from('OITW A');
        $SAP_MSI->join('OITM B with (NOLOCK)', 'A.ItemCode=B.ItemCode');
        $SAP_MSI->join('OITB C', 'B.ItmsGrpCod=C.ItmsGrpCod');
        $SAP_MSI->where('A.OnHand >',  0);
        $SAP_MSI->where('A.WhsCode', $kd_plant);
        if($itemGroup != 'all'){
            $SAP_MSI->where('C.ItmsGrpNam', $itemGroup);
        }
        $query = $SAP_MSI->get();

        if(($query)&&($query->num_rows()>0))
            return $query->result_array();
		else
			return FALSE;
    }

}