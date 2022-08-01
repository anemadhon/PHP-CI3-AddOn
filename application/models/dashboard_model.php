<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_Model extends CI_Model{

    function __construct(){
        parent::__construct(); 
    }

    function getCountPOVendor(){
        $kd_plant = $this->session->userdata['ADMIN']['plant'];
        $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
        
        $SAP_MSI->select('COUNT(DISTINCT POR1.DocEntry) as Total');
        $SAP_MSI->from('POR1 with (NOLOCK)');
        $SAP_MSI->join('OPOR with (NOLOCK)','POR1.DocEntry = OPOR.DocEntry');
   //     $SAP_MSI->join('OITM with (NOLOCK)','POR1.ItemCode = OITM.ItemCode');
   //     $SAP_MSI->join('NNM1 with (NOLOCK)','OPOR.Series = NNM1.Series');
        $SAP_MSI->where('WhsCode',$kd_plant);
        $SAP_MSI->where('OPOR.DocStatus' ,'O');
        $SAP_MSI->where('OpenQty >', 0);

        $query = $SAP_MSI->get();
        $tot = $query->result_array();

        if(($query)&&($query->num_rows() > 0)) {
            return $tot[0];
        } else {
            return FALSE;
        }
    }

    function getCountGRfromKitchen(){
        $kd_plant = $this->session->userdata['ADMIN']['plant'];
        $SAP_MSI = $this->load->database('SAP_MSI', TRUE); 
        $sub_query = $SAP_MSI->select('WhsCode')
            ->from('OWHS')
            ->where('U_CENTRALKITCHEN','Y')
            ->get()
            ->result_array();
        $filler=[];
        foreach($sub_query as $key=>$val){
            array_push($filler, $val['WhsCode']);
        }
        $statusPick = ['P','Y'];
        $SAP_MSI->select("COUNT(DISTINCT OWTQ.DocEntry) Total");
        $SAP_MSI->from('OWTQ');
        $SAP_MSI->join('WTQ1 with (NOLOCK)','OWTQ.DocEntry = WTQ1.DocEntry','inner');
     //   $SAP_MSI->join('OITM with (NOLOCK)','OITM.ItemCode = WTQ1.ItemCode','inner');
      //  $SAP_MSI->join('OWHS with (NOLOCK)','OWTQ.ToWhsCode = OWHS.WhsCode','inner');
      //  $SAP_MSI->join('NNM1 with (NOLOCK)','OWTQ.Series=NNM1.Series','inner');
     //   $SAP_MSI->join('OITB with (NOLOCK)','OITM.ItmsGrpCod = OITB.ItmsGrpCod','inner');

        $SAP_MSI->where('OWTQ.ToWhsCode', $kd_plant);
        $SAP_MSI->where('WTQ1.Quantity = WTQ1.OpenCreQty');
        $SAP_MSI->where("OWTQ.DocEntry not in (SELECT TOP 1 DocEntry FROM WTQ1 A WHERE A.DocEntry = OWTQ.DocEntry AND LineStatus = 'C')");
        $SAP_MSI->where_in('Filler', $filler);
        $SAP_MSI->where_in('WTQ1.PickStatus',$statusPick);

        $query = $SAP_MSI->get();
        $tot = $query->result_array();

        if(($query)&&($query->num_rows() > 0)) {
            return $tot[0];
        } else {
            return FALSE;
        }
    }

    function getCountTransferOut(){
        $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
        $kd_plant = $this->session->userdata['ADMIN']['plant'];
        
        $SAP_MSI->select('COUNT(t0.DocEntry)Total');
        $SAP_MSI->from('ODRF t0 with (NOLOCK)');
     //   $SAP_MSI->join('DRF1 t1','t0.DocEntry = t1.DocEntry','inner');
     //   $SAP_MSI->join('OITM T2','t2.ItemCode = T1.ItemCode','inner');
     //   $SAP_MSI->join('OITB T4','T2.ItmsGrpCod = t4.ItmsGrpCod','inner');
        $SAP_MSI->where_in('Filler', $kd_plant);
        $SAP_MSI->where('t0.ObjType','1250000001');
        $SAP_MSI->where('t0.DocStatus','O');

        $query = $SAP_MSI->get();
       
        $tot = $query->result_array();

        if(($query)&&($query->num_rows() > 0)) {
            return $tot[0];
        } else {
            return FALSE;
        }
    }

    function getCountTransferIn(){
        $kd_plant = $this->session->userdata['ADMIN']['plant'];

        $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
        $SQL = "SELECT COUNT(DISTINCT gistonew_out_no) Total FROM t_gistonew_out_header 
        INNER JOIN t_gistonew_out_detail ON t_gistonew_out_detail.id_gistonew_out_header = t_gistonew_out_header.id_gistonew_out_header 
        INNER JOIN m_outlet with (NOLOCK) ON m_outlet.outlet = t_gistonew_out_header.plant 
        INNER JOIN ".$SAP_MSI->database.".dbo.OWTQ t0 with (NOLOCK) ON CONVERT(VARCHAR, t0.DocEntry) = CONVERT(VARCHAR, t_gistonew_out_header.gistonew_out_no) 
        INNER JOIN ".$SAP_MSI->database.".dbo.WTQ1 t1 with (NOLOCK) ON t0.DocEntry = t1.DocEntry AND t1.LineNum = t_gistonew_out_detail.posnr
        WHERE receiving_plant = '".$kd_plant."' AND [status] =2 AND po_no != '' AND gistonew_out_no != '' AND DocStatus != 'C' AND receipt = 0 AND [close] = 0 AND plant != '05WHST' ";

        //INNER JOIN ".$SAP_MSI->database.".dbo.OITM a with (NOLOCK) ON a.ItemCode COLLATE DATABASE_DEFAULT = t_gistonew_out_detail.material_no COLLATE DATABASE_DEFAULT
 
        $query = $this->db->query($SQL);
        $tot = $query->result_array();

        if(($query)&&($query->num_rows() > 0)) {
            return $tot[0];
        } else {
            return FALSE;
        }
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

    function getCountIntLog(){
        $kd_plant = $this->session->userdata['ADMIN']['plant'];
		$sql = "SELECT * FROM error_log WHERE modul NOT LIKE 'Module:%' AND Whs = '".$kd_plant."'";
		$query = $this->db->query($sql);
		$num = $query->num_rows();

        return $num;
    }
}   