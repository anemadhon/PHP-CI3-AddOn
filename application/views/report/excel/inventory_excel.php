<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
    "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<?php
header("Content-type: application/vnd-ms-excel");
 
header("Content-Disposition: attachment; filename=Inventory Audit.xls");

header("Pragma: no-cache");

header("Expires: 0");

$this->load->helper('form');
$this->lang->load('g_general', $this->session->userdata('lang_name'));
$this->lang->load('g_button', $this->session->userdata('lang_name'));

?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
<style type="text/css">
.visiblenone{
	visibility: hidden;
}

.style5 {
    font-size: 9px
}

.style11 {	
    font-size: 27px;
	font-weight: bold;
}

</style>
</head>

<body>
<div align="center" class="page_title">
  <p align="center"><span class="style11"><u>THE HARVEST </u></span><br />
<span class="style5">Partisier &amp; Chocolatier</span> </p>
  <p>
    <?=$page_title;?>
    <br />
    <?=$plant1;?> 
    - 
    <?=$plant_name;?>
    <br />
    Tanggal </p>
<?=$date_from.' - '.$date_to.' - '.$item_group_code.' - '.$WhsCode;?></div>
	<table width="648" align="center">
  <tr>
    <td height="53"><h3 align="center"><strong>PASTRY</strong></h3></td>
  </tr>
</table>
<table style="border-collapse:collapse;" width="2158" border="1" align="center" class="table">
  <tr id="myHeader" class="header">
    <td width="22" align="center" bgcolor="#999999"><strong><span class="style5"><strong>No</span><strong></strong></td>
    <td width="45" align="center" bgcolor="#999999"><strong><span class="style5">Item Code</span></strong></td>
    <td width="215" align="center" bgcolor="#999999"><strong><span class="style5">Description</span></strong></td>
    <td width="27" align="center" bgcolor="#999999"><strong><span class="style5">Add On Doc.No</span></strong></td>
    <td width="68" align="center" bgcolor="#999999"><strong><span class="style5">SAP Doc.No</span></strong></td>
    <td width="68" align="center" bgcolor="#999999"><strong><span class="style5">Posting Date</span></strong></td>
    <td width="27" align="center" bgcolor="#999999"><strong><span class="style5">SystemDate</span></strong></td>
    <td width="27" align="center" bgcolor="#999999"><strong><span class="style5">Quantity</span></strong></td>
    <td width="27" align="center" bgcolor="#999999"><strong><span class="style5">Cost</span></strong></td>
    <td width="27" align="center" bgcolor="#999999"><strong><span class="style5">Trans Value</span></strong></td>
    <td width="27" align="center" bgcolor="#999999"><strong><span class="style5">Cummulative QTY</span></strong></td>
    <td width="68" align="center" bgcolor="#999999"><strong><span class="style5">Cummulative Value</span></strong></td>
  </tr>
    <?php
        $no=1;
        foreach($data as $key=>$r)
        {
            $className = $r['fg'] == '1' ? 'visiblenone' :'';
    ?>
  <tr> 
    <td align="center"><span class="style5"><?=$no++;?></span></td>
    <td align="center"><span class="style5 <?=$className?>"><?=$r['ItemCode'];?></span></td>
    <td><span class="style5 <?=$className?>"><?=$r['itemname'];?></span></td>
    <td align="center"><span class="style5"><?=$r['AddOnDocNo'];?></span></td>
    <td align="center"><span class="style5"><?=$r['SAPDocNo'];?></span></td>
    <td align="center"><span class="style5"><?=$r['docdate'] !='' ? date("d-m-Y",strtotime($r['docdate'])) : '';?></span></td>
    <td align="center"><span class="style5"><?=$r['SystemDate'] !='' ? date("d-m-Y",strtotime($r['SystemDate'])) : '';?></span></td>
    <td><span class="style5"><?= ( $r['SAPDocNo'] =='Opening Balance '&& $r['quantity']==NULL) ? '': number_format($r['quantity'], 2,",",".");?></span></td>
    <td align="center"><span class="style5"><?= ( $r['SAPDocNo'] =='Opening Balance '&& $r['cost']==NULL) ? '': number_format($r['cost'], 2,",",".");?></span></td>
	<td align="center"><span class="style5"><?= ( $r['SAPDocNo'] =='Opening Balance '&& $r['transvalue']==NULL) ? '': number_format($r['transvalue'], 2,",",".");?></span></td>
	<td align="center"><span class="style5"><?= number_format($r['CummulativeQty'], 2,",",".");?></span></td>
	<td align="center"><span class="style5"><?= number_format($r['CummulativeValue'], 2,",",".");?></span></td>
  </tr>
  <?php 
        }
  ?>
</table>
  
</body>
<script>
window.onscroll = function() {myFunction()};

var header = document.getElementById("myHeader");
var sticky = header.offsetTop;

function myFunction() {
  if (window.pageYOffset >= sticky) {
    header.classList.add("sticky");
  } else {
    header.classList.remove("sticky");
  }
}
</script>

</html>