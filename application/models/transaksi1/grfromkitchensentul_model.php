<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Grfromkitchensentul_model extends CI_Model {

  function __construct() {
    parent:: __construct();
    $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
  }

  public function getDataPoKitchen_Header($fromDate,$toDate,$status){
    $kd_plant = $this->session->userdata['ADMIN']['plant'];
      $this->db->from('t_grpodlv_header');
      $this->db->join('m_outlet', 'm_outlet.OUTLET = t_grpodlv_header.plant');
      $this->db->where('plant',$kd_plant);

      if((!empty($fromDate)) || (!empty($toDate))){

        if( (!empty($fromDate)) || (!empty($toDate)) ) {
            $this->db->where("posting_date BETWEEN '$fromDate' AND '$toDate'");
        }else if( (!empty($fromDate))) {
            $this->db->where("posting_date >= '$fromDate'");
        } else if( (!empty($toDate))) {
            $this->db->where("posting_date <= '$toDate'");
        }

      }

      if((!empty($status))){
          $this->db->where('status', $status);
      }
      $this->db->order_by('id_grpodlv_header','DESC');
      $query = $this->db->get();
      $ret = $query->result_array();
      return $ret;
  }

  function sap_grpodlv_headers_select_slip_number($slipNumberHeader = "", $ItmsGrpNam = ""){
    $response = NULL;
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
    $SAP_MSI->select("OWTQ.DocEntry as VBELN, 
                            NNM1.SeriesName + RIGHT('0000000' + CONVERT(varchar, DocNum), 7) as VBELN1,
                            NNM1.SeriesName + RIGHT('0000000' + u_odocnum, 7) as OLDDOCNUM,
                            convert(date, WTQ1.ShipDate) as DELIV_DATE, 
                            OWTQ.ToWhsCode, 
                            OITM.ItmsGrpCod as DISPO, 
                            OITM.InvntryUom as VRKME, 
                            WTQ1.LineNum as Item, 
                            NNM1.SeriesName + RIGHT('0000000' + CONVERT(varchar, DocNum), 7) AS Doc_Num,
                            OWTQ.ToWhsCode as PLANT, 
                            OWHS.WhsName as Outlet, 
                            OWHS.WhsCode as OutletCode, 
                            WTQ1.ItemCode as Material_Code, 
                            OITM.ItemName as Material_Desc, 
                            ISNULL((SELECT SUM(SnBAllocateViewAllocQty) FROM B1_SnBAllocateDocView 
                                    WHERE SnBAllocateViewDocEntry = WTQ1.DocEntry
                                    AND SnBAllocateViewDocType = WTQ1.ObjType
                                    AND SnBAllocateViewDocLine = WTQ1.LineNum),WTQ1.Quantity) as TF_QTY, 
                            WTQ1.unitMsr as UOM, 
                            OWTQ.Filler,
                            (OpenQty-U_grqty_web) LFIMG,
                            OITB.ItmsGrpNam,OITB.ItmsGrpNam");
     //                       WTQ1.PickOty as TF_QTY, 
     //OWTQ.U_DocNum as VBELN1, 
     $SAP_MSI->from('OWTQ');
    $SAP_MSI->join('WTQ1','OWTQ.DocEntry = WTQ1.DocEntry','inner');
    $SAP_MSI->join('OITM with (NOLOCK)','OITM.ItemCode = WTQ1.ItemCode','inner');
    $SAP_MSI->join('OWHS','OWTQ.ToWhsCode = OWHS.WhsCode','inner');
    $SAP_MSI->join('NNM1 with (NOLOCK)','OWTQ.Series=NNM1.Series','inner');
    $SAP_MSI->join('OITB','OITM.ItmsGrpCod = OITB.ItmsGrpCod','inner');
    $SAP_MSI->where('OWTQ.ToWhsCode', $kd_plant);
    $SAP_MSI->where('WTQ1.Quantity = WTQ1.OpenCreQty');
    $SAP_MSI->where("OWTQ.DocEntry not in (SELECT TOP 1 DocEntry FROM WTQ1 A WHERE A.DocEntry = OWTQ.DocEntry AND LineStatus = 'C')");
    $SAP_MSI->where_in('Filler', $filler);
   // $SAP_MSI->where_in('WTQ1.PickStatus',$statusPick);
    
    if(empty($slipNumberHeader) && empty($ItmsGrpNam)) {

    } else if(!empty($slipNumberHeader) && empty($ItmsGrpNam)) {
      $SAP_MSI->where('OWTQ.DocEntry',(int)$slipNumberHeader);
    } else if(!empty($slipNumberHeader) && !empty($ItmsGrpNam)){

      if($ItmsGrpNam == 'all'){
        $SAP_MSI->where('OWTQ.DocEntry', (int)$slipNumberHeader);
      } else {
        $SAP_MSI->where('OWTQ.DocEntry', (int)$slipNumberHeader);
        $SAP_MSI->where('OITB.ItmsGrpNam', $ItmsGrpNam);
      }
      
    } 
    
    $query = $SAP_MSI->get();
    // echo $SAP_MSI->last_query();
    
    $k=1;
    foreach ($query->result_array() as $row)
    {
      $response[$k] = $row;
      $k++;
    }
    return $response;

  }

    function id_grpodlv_plant_new_select($id_outlet,$posting_date="") {

      $this->db->select_max('id_grpodlv_plant');
      $this->db->from('t_grpodlv_header');
      $this->db->where('plant', $id_outlet);
      $this->db->where('posting_date', $posting_date);
      $query = $this->db->get();

      if(($query)&&($query->num_rows() > 0)) {
        $grpodlv = $query->row_array();
        $id_grpodlv_outlet = $grpodlv['id_grpodlv_plant'] + 1;
      }	else {
        $id_grpodlv_outlet = 1;
      }

      return $id_grpodlv_outlet;
    }

    function getDataQtyU_grqty_web($base, $item){
      $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
      $SAP_MSI->from('WTR1');
      $SAP_MSI->where('DocEntry', $base);
      $SAP_MSI->where('LineNum', $item);

      $query = $SAP_MSI->get();

      if(($query)&&($query->num_rows() > 0))
        return $query->row_array();
      else
        return FALSE;
        
    }
    

    function grpodlv_header_insert($data){
      if($this->db->insert('t_grpodlv_header', $data)){
        return $this->db->insert_id();
      } else {
        return FALSE;
      }

    }

    function grpodlv_detail_insert($data) {
      if($this->db->insert('t_grpodlv_detail', $data)){
        return $this->db->insert_id();
      } else {
        return FALSE;
      }
    }

    function sap_grpodlv_details_select_by_do_and_item($do_no,$item) {
      if (empty($this->session->userdata['do_nos'])) {
          $doitem = $this->sap_grpodlv_headers_select_slip_number("",$do_no,$item);
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

    public function sap_do_select_all($kd_plant="",$do_no="",$do_item="") { 

      $SAP_MSI = $this->load->database('SAP_MSI', TRUE);

      $SAP_MSI->select("OWTR.DocEntry VBELN,
                              OWTR.DocDate DELIV_DATE,
                              OWTR.ToWhsCode,
                              WTR1.LineNum POSNR, 
                              SeriesName + RIGHT('00000' + CONVERT(varchar, DocNum), 6) AS Doc_Num,
                              OITM.ItmsGrpCod DISPO, 
                              WTR1.ItemCode MATNR, 
                              Dscription MAKTX, 
                              (OpenQty-U_grqty_web) LFIMG, 
                              OITM.InvntryUom VRKME, 
                              WTR1.LineNum item, 
                              ToWhsCode PLANT");
      $SAP_MSI->from('OWTR');
      $SAP_MSI->join('WTR1','OWTR.DocEntry = WTR1.DocEntry','inner');
      $SAP_MSI->join('OITM with (NOLOCK)','WTR1.ItemCode = OITM.ItemCode','inner');
      $SAP_MSI->join('OWHS','OWHS.WhsCode = OWTR.Filler','inner');
      $SAP_MSI->join('NNM1','OWTR.Series=NNM1.Series','inner');

      $SAP_MSI->where('ToWhsCode',$kd_plant);
      $SAP_MSI->where('(OpenQty-U_grqty_web) >', 0);
      $SAP_MSI->where('OWTR.CANCELED','N');
      $SAP_MSI->where('OWTR.U_Reverse','N');
      $SAP_MSI->where('OWTR.U_Stat',0); //buat ngilangin notifikasi

      $query = $SAP_MSI->get();

      if(($query)&&($query->num_rows() > 0)) {
        
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

    function grpodlv_header_select($id_grpodlv_header) {
      
      $this->db->from('t_grpodlv_header');
      $this->db->where('id_grpodlv_header', $id_grpodlv_header);

      $query = $this->db->get();

      if(($query)&&($query->num_rows() > 0))
        return $query->row_array();
      else
        return FALSE;
    }

    function grpodlv_details_select($id_grpodlv_header) {
      
      $this->db->from('t_grpodlv_detail');
      $this->db->where('id_grpodlv_header', $id_grpodlv_header);
      $this->db->order_by('id_grpodlv_detail');
  
      $query = $this->db->get();
      
      if(($query)&&($query->num_rows() > 0))
        return $query->result_array();
      else
        return FALSE;

    }
    
    function Update_StatOwtr($data, $DOcEntry) {
      $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
      $SAP_MSI->where('DOcEntry', $DOcEntry);
      if($SAP_MSI->update('OWTR', $data))
        return TRUE;
      else
        return FALSE;
    }

    function Update_U_grqty_web_WTR1($data, $DOcEntry, $LineNum) {
      $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
      $SAP_MSI->where('DOcEntry', $DOcEntry);
      $SAP_MSI->where('LineNum', $LineNum);

      if($SAP_MSI->update('WTR1', $data))
        return TRUE;
      else
        return FALSE;

    }

    function grpodlv_header_update($data) {
      $update = array(
        //'grpodlv_no' => $data['grpodlv_no'],
        'status' => $data['status'],
        'remark' => $data['remark'],
        'id_user_approved' => $data['id_user_approved']
      );
      $this->db->where('id_grpodlv_header', $data['id_grpodlv_header']);
      if($this->db->update('t_grpodlv_header', $update))
        return TRUE;
      else
        return FALSE;
    }

    function grpodlv_detail_update($data) {
      $update = array(
        'gr_quantity' => $data['gr_quantity']
      );
      $this->db->where('id_grpodlv_detail', $data['id_grpodlv_detail']);
      if($this->db->update('t_grpodlv_detail', $update))
        return TRUE;
      else
        return FALSE;
    }
    
    function getHeadDeatForPrint($ID){

      $query = $this->db->query("SELECT a.do_no, a.do_no1, FORMAT(getdate(), 'dd-MM-yyyy', 'en-US') posting_date, a.grpodlv_no, a.grpodlv_no1, FORMAT(getdate(), 'dd-MM-yyyy', 'en-US') lastmodified, a.remark, b.material_no, b.material_desc, b.uom, b.outstanding_qty, b.gr_quantity, b.Sr_Qty, b.Tf_Qty, a.plant, a.id_user_approved  FROM t_grpodlv_header a JOIN t_grpodlv_detail b ON a.id_grpodlv_header = b.id_grpodlv_header
        where a.id_grpodlv_header ='$ID' ");
      
      return $query;

    }

    function showDetailDataStoreRequest($SrNumber){
      $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
      $SQL = "SELECT a.DocEntry, a.ItemCode, a.Dscription, a.Quantity , a.unitMsr
            from drf1 a inner join odrf b on a.docentry = b.docentry
            where a.ItemCode  not in 
            (select c.ItemCode from wtq1 c inner join owtq d on c.DocEntry = d.DocEntry 
			      and d.draftKey=b.DocEntry and d.ObjType = a.ObjType 
            and c.ItemCode = a.ItemCode
            where d.U_DocNum = b.U_DocNum
            AND c.LineStatus != 'C'
            )  
            and b.U_DocNum = '".$SrNumber."' ";
//			      and C.PickStatus in ('P','Y') 
            $query = $SAP_MSI->query($SQL);
        if(($query)&&($query->num_rows() > 0)) {
          return $query->result_array();
        }else {
            return FALSE;
        }
    }

    function showDetailDataGrSend($SrNumber){
      $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
      $SQL = "SELECT a.DocEntry, a.ItemCode, a.Dscription, a.Quantity , a.unitMsr
              from wtq1 a inner join owtq b on a.docentry = b.docentry
              where a.ItemCode  not in 
              (select c.ItemCode from drf1 c inner join odrf d on c.DocEntry = d.DocEntry 
              where d.U_DocNum = b.U_DocNum) 
              AND a.LineStatus != 'C'
              and b.U_DocNum = '".$SrNumber."'";
              // and a.PickStatus in ('P','Y')
       $query = $SAP_MSI->query($SQL);
        if(($query)&&($query->num_rows() > 0)) {
          return $query->result_array();
        }else {
            return FALSE;
        }
    }

    function sap_grpodlv_headers_select_by_kd_do($kd_plant="",$do_no="",$do_item=""){
        $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
        $filler=Array('WMSISTBS','WMSISTFG','WMSISTPR','WMSISTQC','WMSISTRM','WMSISTTR','WDFGSYBS','WDFGSYFG','WDFGSYPR','WDFGSYRM','WDFGSYQC','WDFGSTRM');
																									
        $SAP_MSI->select("OWTR.DocEntry VBELN, 
                            OWTR.DocDate DELIV_DATE, 
                            OWTR.ToWhsCode, 
                            WTR1.LineNum POSNR, 
                            SeriesName + RIGHT('00000' + CONVERT(varchar, DocNum), 6) AS Doc_Num, 
                            OITM.ItmsGrpCod DISPO, 
                            WTR1.ItemCode MATNR, 
                            Dscription MAKTX, 
                            (OpenQty-U_grqty_web) LFIMG, 
                            OITM.InvntryUom VRKME, 
                            WTR1.LineNum item, 
                            ToWhsCode PLANT");
        $SAP_MSI->from('OWTR');
        $SAP_MSI->join('WTR1','OWTR.DocEntry = WTR1.DocEntry','inner');
        $SAP_MSI->join('OITM with (NOLOCK)','WTR1.ItemCode = OITM.ItemCode','inner');
        $SAP_MSI->join('OWHS','OWHS.WhsCode = OWTR.Filler','inner');
        $SAP_MSI->join('NNM1','OWTR.Series=NNM1.Series','inner');
        $SAP_MSI->where('OWTR.ToWhsCode', 'WMSITJST');
        $SAP_MSI->where('OWTR.CANCELED', 'N');
        $SAP_MSI->where('OWTR.U_Reverse', 'N');
        $SAP_MSI->where('OWTR.U_Stat', '0');
        $SAP_MSI->where_in('Filler',$filler);

        $query = $SAP_MSI->get();
        $ret = $query->result_array();
        return $ret;
    }

    function sap_grpodlv_details_select_by_do_no($do_no)
    {
        if (empty($this->session->userdata['do_nos'])) {          
          $doitems= $this->sap_grpodlv_headers_select_slip_number($do_no);
        } else {
            $do_nos = $this->session->userdata['do_nos'];
            $count = count($do_nos);
            for ($i=1;$i<=$count;$i++) {
              if ($do_nos[$i]['VBELN']==$do_no){
                $doitems[1] = $do_nos[$i];
                break;
              }
            }
        }
        
        $count = count($doitems);
        if ($count > 0) {
            $k=1;
            for($i=1;$i<=$count;$i++) {
              $doitems[$i]['id_grpodlv_h_detail'] = $k;
              $k++;
            }
        } else {
          unset($doitems);
        }

        return $doitems;
    }

    function sap_item_groups_select_all() {
  
        $kd_plant = $this->session->userdata['ADMIN']['plant'];
        $this->db->from('m_item_group');
        $this->db->where('kdplant', $kd_plant);
    
        $query = $this->db->get();
        if(($query)&&($query->num_rows() > 0)) {
            $item_groups = $query->result_array();
          $count = count($item_groups);
          $k = 0;
          for ($i=0;$i<=$count-1;$i++) {
            $item_group[$k] = $item_groups[$i];
            $k++;
          }
          return $item_group;
        } else {
          return FALSE;
        }
    }

    function sap_grpodlv_select_item_group_do($do_no){
        $doitems = $this->sap_grpodlv_details_select_by_do_no($do_no);
        $item_groups = $this->sap_item_groups_select_all();

        $k = 1;        
        foreach($item_groups as $igroups){
          
          foreach($doitems as $item){ 

            if( $igroups['DISPO'] == (int)$item['DISPO']){
              $item_groups_filter[$k] = $igroups['DSNAM'];
              $k++;
              break;
            }

          }

        }
        
        if(count($item_groups_filter) > 0){
          $item_groups_filter = array_unique($item_groups_filter);
          return $item_groups_filter;
        } else {
          return FALSE;
        }
    }

  function grpodlv_header_delete($id){
    if($this->grpodlv_details_delete($id)){
      $this->db->where('id_grpodlv_header', $id);
      if($this->db->delete('t_grpodlv_header'))
          return TRUE;
      else
          return FALSE;
    }
  }

  function grpodlv_details_delete($id){
      $this->db->where('id_grpodlv_header', $id);
      if($this->db->delete('t_grpodlv_detail'))
        return TRUE;
      else
        return FALSE;
    }

    function getQtySR($pr_no,$material_no,$plant){
      $this->db->select('id_stdstock_header'); 
      $this->db->from('t_stdstock_header');
      $this->db->where('pr_no1', $pr_no);
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
  

}