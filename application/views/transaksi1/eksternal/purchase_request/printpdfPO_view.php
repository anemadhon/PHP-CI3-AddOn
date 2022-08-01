<?php
    $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
    $doc_en = $data['id'];
    $plant = $data['plant'];

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

    $SAP_MSI->select("isnull(SeriesName,'')+right(replicate('0',7)+convert(varchar,docnum),7) AS NoDoc, a.DocDate, a.DocDueDate, a.Comments");
    $SAP_MSI->from('OPOR a');
    $SAP_MSI->join('nnm1 b','a.Series = b.Series AND a.ObjType = b.ObjectCode','inner');
    $SAP_MSI->where('a.DocEntry',$doc_en);
    $querypo = $SAP_MSI->get();
    $nopo = $querypo->result_array();
    if (empty($nopo)) {
        $po='';
    } else {
        $po=$nopo[0]['NoDoc'];
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
    <td colspan="2" align="center"><span class="style7">PURCHASE ORDER</span></td>
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
    <td>:&nbsp;<?php echo $po;?></td>
  </tr>
  <tr>
    <td>Plaza Simatupang Lt. 8 - 9</td>
    <td>Date</td>
    <td>:&nbsp;<?php echo date("d-m-Y",strtotime($nopo[0]['DocDate']));
	?></td>
  </tr>
  <tr>
    <td>Jl. T.B. Simatupang Kav 1S-1</td>
    <td>Delivery Date</td>
    <td>:&nbsp;<?php echo date("d-m-Y",strtotime($nopo[0]['DocDueDate']));?></td>
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
  <tr>
    <td>&nbsp;</td>
    <td>Doc Entry&nbsp;</td>
    <td>:&nbsp;<strong><?=$doc_en;?></strong></td>
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
    <td width="75" align="center"><strong>Unit Price</strong></td>
    <td width="85" align="center"><strong>Total Amount</strong></td>
  </tr>
  <?php
    $no = 1;
    $SAP_MSI = $this->load->database('SAP_MSI', TRUE);
    $SAP_MSI->select('A.ItemCode,A.Dscription,A.Price,A.unitMsr,A.Quantity,A.LineTotal,A.DocEntry,B.DiscSum,B.VatSum,B.DocTotal');
    $SAP_MSI->from('POR1 A');
    $SAP_MSI->join('OPOR B','A.DocEntry=B.DocEntry','inner');
    $SAP_MSI->where('B.DocEntry',$doc_en);
    $SAP_MSI->group_by("A.ItemCode,A.Dscription,A.unitMsr,A.Price,A.Quantity,A.LineTotal,A.DocEntry,B.DiscSum,B.VatSum,B.DocTotal");
    $SAP_MSI->order_by("A.ItemCode");
    $query = $SAP_MSI->get();
    $s = $query->result_array();
    $loop = count($s);
    $total = array();
    if (!empty($s)) {
      for($i=0; $i<= count($s)-1; $i++){
?>
  <tr>
    <td align="center" height="25"><?php echo $no++; ?></td>
    <td align="center"><?php echo $s[$i]['ItemCode']; ?></td>
    <td>
      <div style="width:300px;">   
      <?php 
	    $desc=$s[$i]['Dscription'] ? $s[$i]['Dscription'] : '';
      echo $desc; 
      ?>
      </div>
    </td>
    <td align="center"><?php echo $s[$i]['unitMsr']; ?></td>
    <td align="right"><?php echo number_format($s[$i]['Quantity'],2,'.',''); ?></td>
    <?php 
      $price = substr($s[$i]['Price'],0,-7);
      $subtotal = substr($s[$i]['LineTotal'],0,-7);
      if ($price=='') {
        $prc = 0;
      } else {
        $prc = number_format($price);
      }
      if ($subtotal=='') {
        $tot = 0;
      } else {
        $tot = number_format($subtotal,0,'',',');
      }
    ?>
    <td align="right"><?php echo $prc; ?></td>
    <td align="right"><?php echo $tot;?></td>
  </tr>
  <?php
      $total[] = $s[$i]['LineTotal'];
      $Diskon=substr($s[$i]['DiscSum'],0,-7);
      $Ppn=substr($s[$i]['VatSum'],0,-7);
      $Grand=substr($s[$i]['DocTotal'],0,-7);
    }
  } else {
    $total[] = 0;
    $Diskon=0;
    $Ppn=0;
    $Grand=0;
  }
  if (array_sum($total)==0) {
    $stotal = 0;
  } else {
    $stotal = number_format(array_sum($total),0,'',',');
  }
  if ($Diskon=='' || $Diskon==0) {
    $disc = 0;
  } else {
    $dic = number_format($Diskon,0,'',',');
  }
  if ($Ppn=='' || $Ppn==0) {
    $ppn = 0;
  } else {
    $ppn = number_format($Ppn,0,'',',');
  }
  if ($Grand=='' || $Grand==0) {
    $gtotal = 0;
  } else {
    $gtotal = number_format($Grand,0,'',',');
  }
  $h=(17-$loop)*30;
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
    <td align="right"><?php echo $stotal; ?></td>
  </tr>
  <tr>
    <td colspan="5">Remark :</td>
    <td align="right"><strong>Discount</strong></td>
    <td align="right"><?php echo $disc; ?></td>
  </tr>
  <tr>
    <td colspan="5"><?php echo substr($nopo[0]['Comments'],0,50);?></td>
    <td align="right"><strong>Ppn</strong></td>
    <td align="right"><?php echo $ppn; ?></td>
  </tr>
  <tr>
    <td colspan="5"><?php echo substr($nopo[0]['Comments'],50,100);?></td>
    <td align="right"><strong>Grand Total</strong></td>
    <td align="right"><?php echo $gtotal; ?></td>
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

