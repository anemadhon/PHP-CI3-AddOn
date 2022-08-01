<?php
header("Content-type: application/vnd-ms-excel");
 
 header("Content-Disposition: attachment; filename=TranferOutInterOutlet.xls");
 
 header("Pragma: no-cache");
    $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
   foreach($data as $row) 
	 $po=$row['gistonew_out_no'];
?>
<style type="text/css">
<!--
.style5 {font-size: 11px}
-->
</style>

<p>&nbsp;</p>
<table width="648" align="center">
  <tr>
    <td>&nbsp;</td>
    <td><strong>TRANSFER SLIP</strong></td>
  </tr>
  <tr>
    <td width="321"><strong>PT. Mount Scopus Indonesia</strong></td>
    <td width="317">No : <?php echo $row['gistonew_out_no'];?></td>
  </tr>
  <tr>
    <td>Jl. Wolter Monginsidi No.95 Kebayoran Baru</td>
    <td>Date : <?php echo $row['posting_date'];?></td>
  </tr>
  <tr>
    <td>Jakarta Selatan 12110, Indonesia</td>
    <td>From : <?php echo $row['plant'].' - '.$row['NAME1'];?></td>
  </tr>
  <tr>
      <td>Ph. +62 21 726 06680 / Fax. +62 21 727 971 59</td>
      <td>To : <?php echo $row['PLANT_REC'].' - '.$row['PLANT_REC_NAME'];?></td>
  </tr>
</table>
<p>&nbsp;</p>
<table style="border-collapse:collapse;" width="709" border="1" align="center">
  <tr>
    <td width="81"><span class="style3 style5">Item Code</span></td>
    <td width="27"><span class="style3 style5">Description</span></td>
  
    <td width="54"><span class="style3 style5">Quantity</span></td>
    <td width="54"><span class="style3 style5">UOM</span></td>
    <td width="54"><span class="style3 style5">Check Out</span></td>
    <td width="65"><span class="style3 style5">Check In</span></td>
    <!--td width="54"><span class="style3 style5">SNRG</span></td-->
    <td width="54"><span class="style3 style5">Arrived Date</span></td>
    <td width="54"><span class="style3 style5">Expired Date</span></td>
  </tr>
 <?php
  foreach($data as $row1) 
  {
 ?>
  <tr>
    <td><?php echo $row1['material_no'];
	$item=$row1['material_no'];
	$SAP_MSI->select('OBTN.DistNumber,OBTN.ExpDate');
    $SAP_MSI->from('OITL');
    $SAP_MSI->join('ITL1','OITL.LogEntry = ITL1.LogEntry','inner');
    $SAP_MSI->join('OBTN','ITL1.ItemCode = OBTN.ItemCode and ITL1.SysNumber = OBTN.SysNumber','inner');
    $SAP_MSI->where('DocEntry',$po);
    $SAP_MSI->where('DocType', 67);
    $SAP_MSI->where('OITL.StockQty >',0);
    $SAP_MSI->where('OITL.ItemCode',$item);
    $query = $SAP_MSI->get();
    $sell= $query->result_array();

    $selExpDate='';
      if(count($sell) > 0){
        $selExpDate = $sell['ExpDate'];
      }
			
	?></td>
    <td><?php echo $row1['material_desc'];?></td>
    <td><?php echo $row1['gr_quantity'];?></td>
    <td><?php echo $row1['uom'];?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <!--td><?php echo $sell['DistNumber'];?></td-->
    <td><?php echo $row1['posting_date'];
	
	?></td>
    <td><?php echo $selExpDate;?></td>
  </tr>
  <?php
  }
  ?>
  <tr>
    <td colspan="8"><p><span class="style3 style5">Comments</span> : </p>
    <p>
      <?php 
         $SAP_MSI->select('Comments');
         $SAP_MSI->from('OWTR');
         $SAP_MSI->where('DocEntry',$po);
         $query = $SAP_MSI->get();
         $sell2= $query->result_array();
         $sel2Coment='';
       if(count($sell2) > 0){
         $sel2Coment = $sell2['Comments'];
       }
           echo $sel2Coment;
	?>
    </p></td>
  </tr>
  
</table>
<table width="705" style="border-collapse:collapse;" border="0" align="center">
  <tr>
   
    <td width="326">
    <table width="344" style="border-collapse:collapse;" border="1">
      <tr>
        <td colspan="3">Loading</td>
        </tr>
      <tr>
        <td width="76"><p>Store</p>
          <p>&nbsp;</p></td>
        <td width="69"><p>Loading</p>
          <p>&nbsp;</p></td>
        <td width="177"><p>Security</p>
          <p>&nbsp;</p></td>
      </tr>
      <tr>
        <td>Name</td>
        <td>Name</td>
        <td>Name</td>
      </tr>
      <tr>
        <td>Date</td>
        <td>Date</td>
        <td>Date</td>
      </tr>
    </table></td>
    <td width="369"><table width="358" style="border-collapse:collapse;" border="1">
      <tr>
        <td colspan="3">Unloading</td>
      </tr>
      <tr>
        <td width="71"><p>Store</p>
            <p>&nbsp;</p></td>
        <td width="94"><p>Unloading</p>
            <p>&nbsp;</p></td>
        <td width="171"><p>Security</p>
            <p>&nbsp;</p></td>
      </tr>
      <tr>
        <td>Name</td>
        <td>Name</td>
        <td>Name</td>
      </tr>
      <tr>
        <td>Date</td>
        <td>Date</td>
        <td>Date</td>
      </tr>
    </table></td>
  </tr>
</table>
<p>&nbsp;</p>
