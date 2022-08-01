<?php
  foreach($data->result() as $row)
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
    <td colspan="2" align="center"><span class="style7">SPOILED / BREAKAGE / LOST</span></td>
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
    <td>:&nbsp;<?php echo $row->material_doc_no;?></td>
  </tr>
  <tr>
    <td>Plaza Simatupang Lt. 8 - 9</td>
    <td>Date</td>
    <td>:&nbsp;<?php echo $row->posting_date;
	?></td>
  </tr>
  <tr>
    <td>Jl. T.B. Simatupang Kav 1S-1</td>
    <td>Outlet</td>
    <td>:&nbsp;<strong><?php echo $row->plant.' - '.$row->OUTLET_NAME1 ;?></strong></td>
  </tr>
  <tr>
    <td>Jakarta Selatan, 12310, Indonesia</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Ph. +62 21 726 06680 / Fax. +62 21 727 971 59</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
<table style="border-collapse:collapse;" width="350" border="1" align="center">
  <tr align="center" class="head">
    <td width="20"><strong>No</strong></td>
    <td width="80"><strong>Item Code</strong></td>
    <td width="50"><strong>Description</strong></td>
    <td width="70"><strong>Unit</strong></td>
    <td width="70"><strong>Qty</strong></td>
    <td width="40"><strong>Reason</strong></td>
    <td width="40"><strong>Detail</strong></td>
  </tr>
  <?php
    $no = 1;
    $loop = count($data->result());
    foreach($data->result() as $row1) {
  ?>
  <tr>
    <td align="center" height="25"><?php echo $no++; ?></td>
    <td align="center">
    <?php 
	  $mat=$row1->material_no ? $row1->material_no : '';
    echo $mat; 
    ?>
    </td>
    <td> 
      <div style="width:300px;">   
      <?php 
	    $desc=$row1->material_desc ? $row1->material_desc : '';
      echo $desc; 
      ?>
      </div>
    </td>
    <td align="center"><?php echo $row1->uom; ?></td>
    <td align="right">
    <?php
	  $plant=$row1->plant; 
	  $qty=$row1->quantity;
	  echo number_format($qty,2,'.','')
	  ?>
    </td>
    <td align="left"><?php echo $row1->reason_name; ?></td>
    <td width="100"> 
      <div style="max-width:100%; word-wrap:break-word;">   
      <?php 
      echo substr($row1->other_reason,0,15).'..';
      ?>
      </div>
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
<p class="space"><?php echo $row->no_acara;?></p>
<table width="600" style="border-collapse:collapse;" align="center" border="1">
  <tr>
    <td width="200" align="center">Prepared By </td>
    <td width="200" align="center">Approved by </td>
    <td width="200" align="center">Knowledge by</td>
  </tr>
  <tr>
    <td align="center">
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>Staff</p>
    </td>
    <td align="center">
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>MOD</p>
    </td>
    <td align="center">
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>Security</p>
    </td>
  </tr>
  <tr>
    <td >Name </td>
    <td >Name </td>
    <td >Name </td>
  </tr>
  <tr>
    <td >Time </td>
    <td >Time </td>
    <td >Time </td>
  </tr>
  <tr>
    <td>Date</td>
    <td>Date</td>
    <td>Date</td>
  </tr>
</table>
