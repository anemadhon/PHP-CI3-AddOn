<?php
  $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
  foreach($data as $row) 
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
.m {margin:10px}
.space {margin:10px 10px 10px 20px}

</style>

<table width="300"  align="center">
  <tr>
    <td width="350">
      <img src="<?php echo base_url('/files/');?>assets/images/logo.jpeg" alt="logo-harvest" width="270">
    </td>
    <td colspan="2" align="center"><span class="style7">STORE ROOM REQUISITION</span></td>
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
    <td>No.</td>
    <td>:&nbsp;<?php echo $row['pr_no1'];?></td>
  </tr>
  <tr>
    <td>Plaza Simatupang Lt. 8 - 9</td>
    <td>Date</td>
    <td>:&nbsp;<?php echo date("d-m-Y",strtotime($row['created_date']));
	?></td>
  </tr>
  <tr>
    <td>Jl. T.B. Simatupang Kav 1S-1</td>
    <td>Outlet</td>
    <td>:&nbsp;<strong><?php echo $row['plant'].' - '.$row['plant_name'] ;?></strong></td>
  </tr>
  <tr>
    <td>Jakarta Selatan, 12310, Indonesia</td>
    <td>Delivery Date</td>
    <td>:&nbsp;<?php echo date("d-m-Y",strtotime($row['delivery_date']));?></td>
  </tr>
  <tr>
    <td>Ph. +62 21 726 06680 / Fax. +62 21 727 971 59</td>
    <td>Deliver To</td>
    <td>:&nbsp;<strong><?php echo $row['to_plant'].' - '.$row['OUTLET_NAME1'] ;?></strong></td>
  </tr>
</table>
<p>&nbsp;</p>
<table style="border-collapse:collapse;" width="350" border="1" align="center">
  <tr align="center" class="head">
    <td width="20"><strong>No</strong></td>
    <td width="80"><strong>Item Code</strong></td>
    <td width="50"><strong>Description</strong></td>
    <td width="70"><strong>Part Stock</strong></td>
    <td width="70"><strong>Stock On Hand</strong></td>
    <td width="40"><strong>Uom</strong></td>
    <td width="70"><strong>Request Qty</strong></td>
  </tr>
  <?php
  $no = 1;
  $loop = ($data ? count($data) : 0);
  foreach($data as $row1) {	
  ?>
  <tr>
    <td align="center" height="25"><?php echo $no++; ?></td>
    <td align="center">
    <?php 
	  $mat=$row1['material_no'] ? $row1['material_no'] : '';
    echo $mat; 
    ?>
    </td>
    <td> 
      <div style="width:300px;">   
      <?php 
	    $desc=$row1['material_desc'] ? $row1['material_desc'] : '';
      echo $desc; 
      ?>
      </div>
    </td>
    <td align="right">
    <?php
    $plant=$row1['plant'] ? $row1['plant'] : ''; 
    $SAP_MSI->from('OITW');
    $SAP_MSI->where('ItemCode',$mat);
    $SAP_MSI->where('WhsCode',$plant);
    $query = $SAP_MSI->get();
    $r = $query->result_array();
    $part=$r[0]['MinStock'] ? $r[0]['MinStock']:0;
    $onHand = $r[0]['OnHand'] ? $r[0]['OnHand']:0;
	  echo number_format($part,2,'.','');
	  ?>
    </td>
    <td align="right"><?php echo number_format($onHand,2,'.',''); ?></td>
    <td align="center"><?php echo $row1['uom']; ?></td>
    <td align="right">
    <?php 
    $qty=$row1['requirement_qty'];
	  echo number_format($qty,2,'.',''); ?>
    </td>
  </tr>
  <?php 
    } 
    $h = (17-$loop)*30;
    ?>
    <tr>
      <td height="<?php echo $h; ?>"></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
</table>
<p class="m">Remark :</p>
<p class="space"><?php echo $data[0]['request_reason'];?></p>
<table width="450"style="border-collapse:collapse;" border="1" align="center" >
  <tr>
    <td width="150" align="center">Requested By :</td>
    <td width="150" align="center">Issued By :</td>
    <td width="150" align="center">Received By :</td>
  </tr>
  <tr>
    <td height="100" align="left"> 
    <p>(Date :..............................)</p>
    <p>&nbsp;</p>
    <p>(Name :..............................)</p></td>

    <td align="left">
    <p>(Date :..............................)</p>
    <p>&nbsp;</p>
    <p>(Name :..............................)</p></td>
    
    <td align="left">
    <p>(Date :..............................)</p>
    <p>&nbsp;</p>
    <p>(Name :..............................)</p></td>
  </tr>
</table>
