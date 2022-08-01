<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
    "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<?php
header("Content-type: application/vnd-ms-excel");
 
 header("Content-Disposition: attachment; filename=OnHand.xls");
 
 header("Pragma: no-cache");
 
 header("Expires: 0");

?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>

</head>

<body>
<div align="center" class="page_title">
  <p align="left"><span style="font-size: 27px; font-weight: bold;"><u>THE HARVEST </u></span><br />
&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size: 9px">Partisier &amp; Chocolatier</span> </p>
  <p>
    <?=$page_title;?>
    <br />
    Outlet 
    <?=$plant1;?> 
    - 
    <?=$plant_name.' <br/> '.$item_group_code;?>
	</p></div>

	<table width="648" align="center">
  <tr>
    <td height="53"><h3 align="center"><strong>OnHand</strong></h3></td>
  </tr>
</table>
<table style="border-collapse:collapse;" width="2158" border="1" align="center" class="table">
  <tr id="myHeader" class="header">
    <td width="22" align="center" bgcolor="#999999"><strong><span style="font-size: 12px"><strong>No</span><strong></strong></td>
    <td width="45" align="center" bgcolor="#999999"><strong><span style="font-size: 12px">Code</span></strong></td>
    <td width="215" align="center" bgcolor="#999999"><strong><span style="font-size: 12px">Description</span></strong></td>
    <td width="27" align="center" bgcolor="#999999"><strong><span style="font-size: 12px">On Hand</span></strong></td>
    <td width="27" align="center" bgcolor="#999999"><strong><span style="font-size: 12px">UOM</span></strong></td>
    <td width="27" align="center" bgcolor="#999999"><strong><span style="font-size: 12px">Item Cost</span></strong></td>
    <td width="27" align="center" bgcolor="#999999"><strong><span style="font-size: 12px">Amount</span></strong></td>
  </tr>

 <?php
 $no = 1;
 if($dataOnHand): 
  foreach($dataOnHand as $key=>$r):
    $Amount = $r['OnHand'] * $r['ItemCost'];
  ?>
  <tr> 
    <td align="center"><span style="font-size: 9px"><?=$no++;?></span></td>
    <td align="left"><span style="font-size: 9px"><?=$r['ItemCode'];?></span></td>
    <td><span style="font-size: 9px"><?=$r['ItemName'];?></span></td>
    <td align="center"><span style="font-size: 9px"><?=number_format($r['OnHand'],2,",",".");?></span></td>
    <td align="center"><span style="font-size: 9px"><?=$r['Uom'] ;?></span></td>
    <td align="center"><span style="font-size: 9px"><?=number_format($r['ItemCost'],2,",",".") ;?></span></td>
    <td align="center"><span style="font-size: 9px"><?=number_format($Amount,2,",",".");?></span></td>
  </tr>
<?php 
  endforeach;
  endif
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