<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
    "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<?php

header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename=stock_opname.xls'); //tell browser what's the file nameoffile
header("Cache-Control: max-age=0");

?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>

</head>

<body>
<div align="center" class="page_title">
  <p align="center"><span style="font-size: 27px; font-weight: bold;"><u>THE HARVEST </u></span><br /><span style="font-size: 9px">Partisier &amp; Chocolatier</span> </p>
  <p>
    <?=$page_title;?>
    <br />
    Outlet 
    <?=$plant1;?> 
    - 
    <?=$plant_name;?>
	</p></div>

	<table width="648" align="center">
  <tr>
    <td height="53"><h3 align="center"><strong>Template Stock Opname</strong></h3></td>
  </tr>
</table>
<table style="border-collapse:collapse;" width="2158" border="1" align="center" class="table">
  <tr id="myHeader" class="header">
    <td width="22" align="center" bgcolor="#999999"><strong><span style="font-size: 12px"><strong>WHSCODE</span><strong></strong></td>
    <td width="45" align="center" bgcolor="#999999"><strong><span style="font-size: 12px">Item Group</span></strong></td>
    <td width="45" align="center" bgcolor="#999999"><strong><span style="font-size: 12px">Item Code</span></strong></td>
    <td width="215" align="center" bgcolor="#999999"><strong><span style="font-size: 12px">Item Name</span></strong></td>
    <td width="27" align="center" bgcolor="#999999"><strong><span style="font-size: 12px">On Hand</span></strong></td>
    <td width="27" align="center" bgcolor="#999999"><strong><span style="font-size: 12px">UOM</span></strong></td>
    <?php
      $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
      $query = $SAP_MSI->query("SELECT code, name from [@YBC_OUTLET_ROOM]");
      $ret = $query->result_array();
      $max = count($ret);
      foreach($ret as $key=>$val):
    ?>
    <td width="27" align="center" bgcolor="#999999"><strong><span style="font-size: 12px"><?=$val['name']?></span></strong></td>
    <?php 
      endforeach;
    ?>
  </tr>

 <?php
 $no = 1;
 if($dataOnHand): 
  foreach($dataOnHand as $key=>$r):

  ?>
  <tr> 
    <td align="center"><span style="font-size: 9px"><?=$plant1;?></span></td>
    <td align="center"><span style="font-size: 9px"><?=$r['ItmsGrpNam']?></span></td>
    <td align="left"><span style="font-size: 9px"><?=$r['ItemCode'];?></span></td>
    <td><span style="font-size: 9px"><?=$r['ItemName'];?></span></td>
    <td align="center"><span style="font-size: 9px"><?=(float)$r['OnHand']?></span></td>
    <td align="center"><span style="font-size: 9px"><?=$r['UNIT'] ;?></span></td>
    <?php 
      for($i=0; $i<$max; $i++):
    ?>
    <td align="center"><span style="font-size: 9px"></span></td>
    <?php 
      endfor;
    ?>
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