<?php
    $this->DB_SAP = $this->load->database('SAP_MSI', TRUE);
    $remark = '';
    foreach($data->result_array() as $row)

      $plant=$row['plant'];
      $doc=$row['po_no'];
      $remark = $row['remark'];

      $this->DB_SAP->select('WhsName,City');
      $this->DB_SAP->from('OWHS');
      $this->DB_SAP->where('WhsCode',$plant);

      $query = $this->DB_SAP->get();
      $result = $query->result_array();

      if (empty($result)) {
          $reck='NAMA PLANT (DEFAULT)';
          $reck_loc='LOKASI PLANT (DEFAULT)';
      } else {
          $reck=$result[0]['WhsName'];
          $reck_loc=$result[0]['City'];
      }

      $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
      $SAP_MSI->select('C.DocEntry');
      $SAP_MSI->from('POR1 A');
      $SAP_MSI->join('PRQ1 B','A.BaseEntry=B.DocEntry AND A.BaseType=B.ObjType','inner');
      $SAP_MSI->join('OPOR C','A.DocEntry=C.DocEntry','inner');
      $SAP_MSI->join('nnm1 D','C.Series = D.Series AND C.ObjType=D.ObjectCode','inner');
      $SAP_MSI->where('B.DocEntry',$doc);
      $SAP_MSI->group_by("C.DocEntry");
      $query = $SAP_MSI->get();
      $s = $query->row_array();

      if (empty($s)) {
        $doc1 = 0;
      } else {
        $doc1 = $s['DocEntry'];
      }

      $SAP_MSI->select("isnull(SeriesName,'')+right(replicate('0',7)+convert(varchar,docnum),7) AS NoDoc");
      $SAP_MSI->from('OPOR a');
      $SAP_MSI->join('nnm1 b','a.Series = b.Series AND a.ObjType=b.ObjectCode','inner');
      $SAP_MSI->where('a.DocEntry',$doc1);
      $querypo = $SAP_MSI->get();
      
      if($querypo->num_rows() > 0) {
        $nopo = $querypo->row_array();
        $po = $nopo['NoDoc'];
      }	else {
        $po = '';
      }
?>

<style type="text/css">

.style5 {font-size: 10px}
.style7 {
	font-size: 28px;
	font-weight: bold;
}
.style8 {
  font-size: 9px;
  margin-left:100px;
}
.style10 {font-size: 24px}
.style12 {font-size: 18px}
.head {font-size:13px}
.m {margin:10px 10px 0 20px}
.space {margin:10px 10px 15px 20px}

</style>

<table width="300"  align="center">
  <tr>
    <td width="350">
      <img src="<?php echo base_url('/files/');?>assets/images/logo.jpeg" alt="logo-harvest" width="270">
    </td>
    <td colspan="2" align="center"><span class="style7">GR From VENDOR</span></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="2" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="2" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td><strong>PT. Mount Scopus Indonesia</strong></td>
    <td>No. GRPO</td>
    <td>:&nbsp;<?php echo $row['grpo_no'];?></td>
  </tr>
  <tr>
    <td>Plaza Simatupang Lt. 8 - 9</td>
    <td>No. PO</td>
    <td>:&nbsp;<?php echo $row['docnum'];?></td>
  </tr>
  <tr>
    <td>Jl. T.B. Simatupang Kav 1S-1</td>
    <td>Date</td>
    <td>:&nbsp;<?php echo date("d-m-Y", strtotime($row['posting_date']));?></td>
  </tr>
  <tr>
    <td>Jakarta Selatan, 12310, Indonesia</td>
    <td>Delivery Date</td>
    <td>:&nbsp;<?php echo date("d-m-Y", strtotime($row['delivery_date']));;?></td>
  </tr>
  <tr>
    <td>Ph. +62 21 726 06680 / Fax. +62 21 727 971 59</td>
    <td>Delivery</td>
    <td>:&nbsp;<strong><?php echo $reck;?></strong></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Vendor</td>
    <td>:&nbsp;<strong><?php echo $row['kd_vendor'].' - '.$row['nm_vendor'];?></strong></td>
  </tr>
</table>
<p>&nbsp;</p>
<table style="border-collapse:collapse;" width="500" border="1" align="center">
    <tr class="head">
        <td width="20" align="center"><strong>No.</strong></td>
        <td width="70" align="center"><strong>Item Code</strong></td>
        <td width="50" align="center"><strong>Description</strong></td>
        <td width="30" align="center"><strong>UOM</strong></td>
        <td width="50" align="center"><strong>Order</strong></td>
        <td width="50" align="center"><strong>Receive</strong></td>
        <td width="70" align="center"><strong>Price</strong></td>
    </tr>
    <?php 
        $no=1;
        $loop = count($data->result_array());
        foreach($data->result_array() as $key=>$row1) {
          $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
          
          $SAP_MSI->select('Price, Quantity');
          $SAP_MSI->from('POR1');
          $SAP_MSI->where('WhsCode',$plant);
          $SAP_MSI->where('DocEntry',$row1['po_no']);
          $SAP_MSI->where('ItemCode',$row1['material_no']);

          $query = $SAP_MSI->get();
          $pos = $query->row_array();
        
          $qty_grpo=$row1['gr_quantity'];

          $price =substr($pos['Price'],0,-7);

          if ($price=='') {
            $prc = 0;
          } else {
            $prc = number_format($pos['Price']);
          }
    ?>
    <tr>
        <td align="center" height="25"><?php echo $no++;?></td>
        <td align="center"><?php echo $row1['material_no'];?></td>
        <td>
        <div style="width:300px;">   
        <?php 
        $desc=$row1['material_desc'] ? $row1['material_desc'] : '';
        echo $desc; 
        ?>
        </div>
        </td>
        <td align="center"><?php echo $row1['uom'];?></td>
        <td align="right"><?php echo number_format($row1['outstanding_qty'],2,'.','');?></td>
        <td align="right"><?php echo number_format($qty_grpo,2,'.','');?></td>
        <td align="right"><?php echo $prc;?></td>
    </tr>
    <?php 
      }
      $h = (17-$loop)*30;
      ?>
      <tr>
        <td height="<?php echo $h ?>"></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
</table>
<p class="m">Comments :</p>
<p class="space"><?php echo $remark;?></p>
<table width="600" border="1"   style="border-collapse:collapse;" align="center">
  <tr>
    <td width="120" align="center" scope="col">Checked By :</td>
    <td width="120" align="center" scope="col">Shipped By (Driver):</td>
    <td width="120" align="center" scope="col">Verified By (Security):</td>
    <td width="120" align="center" scope="col">Verified By (QC):</td>
    <td width="120" align="center" scope="col">Approved By (Manager):</td>
    <td width="120" align="center" scope="col">Posted By (Admin):</td>
  </tr>
  <tr>
    <td height="100" align="left" valign="bottom">(Name)</td>
    <td align="left" valign="bottom">(Name)</td>
    <td align="left" valign="bottom">(Name)</td>
    <td align="left" valign="bottom">(Name)</td>
    <td align="left" valign="bottom">(Name)</td>
    <td align="left" valign="bottom">(Name)</td>
  </tr>
  <tr>
    <td align="left" valign="bottom">(Date)</td>
    <td align="left" valign="bottom">(Date)</td>
    <td align="left" valign="bottom">(Date)</td>
    <td align="left" valign="bottom">(Date)</td>
    <td align="left" valign="bottom">(Date)</td>
    <td align="left" valign="bottom">(Date)</td>
  </tr>
</table>