<?php
    $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
    foreach($data as $row) 
    
      $plant = $row['plant'] ;
      $SAP_MSI->select('WhsName,City');
      $SAP_MSI->from('OWHS');
      $SAP_MSI->where('WhsCode',$plant);
      $query = $SAP_MSI->get();
      $temp = $query->row_array();
      if (empty($temp)) {
        $reck='NAMA PLANT (DEFAULT)';
        $reck_loc='LOKASI PLANT (DEFAULT)';
      } else {
        $reck=$temp['WhsName'];
        $reck_loc=$temp['City'];
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
.m {margin:10px 10px 10px 55px}

</style>

<table width="300"  align="center">
  <tr>
    <td width="350">
      <img src="<?php echo base_url('/files/');?>assets/images/logo.jpeg" alt="logo-harvest" width="270">
    </td>
    <td colspan="2" align="center"><span class="style7">PURCHASE REQUEST</span></td>
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
    <td>Delivery Date</td>
    <td>:&nbsp;<?php echo date("d-m-Y",strtotime($row['delivery_date']));?></td>
  </tr>
  <tr>
    <td>Jakarta Selatan, 12310, Indonesia</td>
    <td>Deliver To</td>
    <td>:&nbsp;<strong><?=$reck;?></strong></td>
  </tr>
  <tr>
    <td>Ph. +62 21 726 06680 / Fax. +62 21 727 971 59</td>
    <td>&nbsp;</td>
    <td>:&nbsp;<strong><?=$reck_loc;?></strong></td>
  </tr>
</table>
<p>&nbsp;</p>
<table style="border-collapse:collapse;" width="350" border="1" align="center">
  <tr class="head">
    <td width="20" height="20" align="center"><strong>No</strong></td>
    <td width="80" align="center"><strong>Item Code</strong></td>
    <td width="50" align="center"><strong>Description</strong></td>
    <td width="35" align="center"><strong>Uom</strong></td>
    <td width="35" align="center"><strong>Qty</strong></td>
    <td width="75" align="center"><strong>Last Purchase Price</strong></td>
    <td width="85" align="center"><strong>Total Amount</strong></td>
  </tr>
  <?php
   $no = 1;
   $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
   $sum = array();
   $loop = count($data);
   foreach($data as $row1) {
      $SAP_MSI->select('Price as LastPrice');
      $SAP_MSI->from('OPOR a');
      $SAP_MSI->join('POR1 b','a.docentry=b.DocEntry','inner');
      $SAP_MSI->where('itemcode',$row1['material_no']);
      $SAP_MSI->where('WhsCode',$row1['plant']);
      $SAP_MSI->order_by('a.DocDate','desc');

      $last = $SAP_MSI->get();
      $item = $last->row_array();

      $sum[] = ($row1['requirement_qty']*$item['LastPrice']);
  ?>
  <tr>
    <td align="center" height="25"><?php echo $no++; ?></td>
    <td align="center"><?php echo $row1['material_no']; ?></td>
    <td>
      <div style="width:300px;">   
      <?php 
	    $desc=$row1['material_desc'] ? $row1['material_desc'] : '';
      echo $desc; 
      ?>
      </div>
    </td>
    <td align="center"><?php echo $row1['uom']; ?></td>
    <td align="right">
    <?php 
      $qty=$row1['requirement_qty'];
      echo number_format($qty,2,'.','');
    ?>
    </td>
    <td align="right"><span> &nbsp;<?php echo $item['LastPrice']?number_format($item['LastPrice']):0; ?>&nbsp;</span></td>
    <td align="right"><span> &nbsp;<?php echo number_format($row1['requirement_qty']*$item['LastPrice'],0,'',',') ?>&nbsp;</span></td>
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
  <tr>
    <td colspan="5"></td>
    <td align="right"><strong>Sub Total</strong></td>
    <td align="right"><?php echo number_format(array_sum($sum),0,'',','); ?></td>
  </tr>
  <tr>
    <td colspan="5">Remark :</td>
    <td align="right"><strong>Discount</strong></td>
    <td align="right"><?php echo 0; ?></td>
  </tr>
  <tr>
    <td colspan="5"><?php echo substr($data[0]['request_reason'],0,50);?></td>
    <td align="right"><strong>Ppn</strong></td>
    <td align="right"><?php echo 0; ?></td>
  </tr>
  <tr>
    <td colspan="5"><?php echo substr($data[0]['request_reason'],50,100);?></td>
    <td align="right"><strong>Grand Total</strong></td>
    <td align="right"><?php echo number_format(0+0+array_sum($sum),0,'',','); ?></td>
  </tr>
</table>
<p></p>
<table width="600" border="1"   style="border-collapse:collapse;" align="center">
  <tr>
    <td width="155" align="center" scope="col">Prepared By :</td>
    <td width="155" align="center" scope="col">Verified By :</td>
    <td width="155" align="center" scope="col">Approved 1 By :</td>
    <td width="155" align="center" scope="col">Approved 2 By :</td>
  </tr>
  <tr>
    <td height="100" align="center" valign="bottom">(...)</td>
    <td align="center" valign="bottom">(...)</td>
    <td align="center" valign="bottom">(HOD)</td>
    <td align="center" valign="bottom">(Director)</td>
  </tr>
</table>
