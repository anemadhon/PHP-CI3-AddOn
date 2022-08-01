<?php
    $this->DB_SAP = $this->load->database('SAP_MSI', TRUE);
    if (!empty($data->result())) {
        foreach($data->result() as $row)

        $plant=$row->plant;
        $doc=$row->grnonpo_no;
        $remark=$row->remark;

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
    <td colspan="2" align="center"><span class="style7">GR NON PO</span></td>
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
    <td>No. GR Non PO</td>
    <td>:&nbsp;<?php echo $row->grnonpo_no;?></td>
  </tr>
  <tr>
    <td>Plaza Simatupang Lt. 8 - 9</td>
    <td>Date</td>
    <td>:&nbsp;<?php echo $row->posting_date;?></td>
  </tr>
  <tr>
    <td>Jl. T.B. Simatupang Kav 1S-1</td>
    <td>Delivery Date</td>
    <td>:&nbsp;<?php echo $row->lastmodified;?></td>
  </tr>
  <tr>
    <td>Jakarta Selatan, 12310, Indonesia</td>
    <td>Plant</td>
    <td>:&nbsp;<strong><?php echo $row->plant.' - '.$row->plant_name;?></strong></td>
  </tr>
  <tr>
    <td>Ph. +62 21 726 06680 / Fax. +62 21 727 971 59</td>
    <td>Cost. Center</td>
    <td>:&nbsp;<strong><?php echo $row->cost_center;?></strong></td>
  </tr>
</table>

<p>&nbsp;</p>
<table style="border-collapse:collapse;" width="450" border="1" align="center">
    <tr class="head">
        <td width="20" align="center"><strong>No.</strong></td>
        <td width="70" align="center"><strong>Item Code</strong></td>
        <td width="150" align="center"><strong>Description</strong></td>
        <td width="30" align="center"><strong>UOM</strong></td>
        <td width="70" align="center"><strong>QTY</strong></td>
        <td width="60" align="center"><strong>Price</strong></td>
        <td width="60" align="center"><strong>Sub Total</strong></td>
    </tr>
    <?php 
        $no=1;
        $loop = count($data->result());
        $total = array();
        foreach($data->result() as $row1) {
            $qty=$row1->quantity; 
            $price=$row1->price;
            $total[]=$qty*$price;
    ?>
    <tr>
        <td align="center" height="25"><?php echo $no++;?></td>
        <td align="center"><?php echo $row1->material_no;?></td>
        <td>
        <div style="width:300px;">   
        <?php 
        $desc=$row1->material_desc ? $row1->material_desc : '';
        echo $desc; 
        ?>
        </div>
        </td>
        <td align="center"><?php echo $row1->uom;?></td>
        <td align="right"><?php echo number_format($qty,2,'.','');?></td>
        <td align="right"><?php echo number_format($price);?></td>
        <td align="right"><?php echo number_format(($qty*$price),0,'',',');?></td>
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
        <td align="right"><strong>Grand Total</strong></td>
        <td align="right"><?php echo number_format(array_sum($total),0,'',','); ?></td>
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
<?php  
    } else {
?>
<h1 align="center">Data Kosong</h1>
<?php  
    }
?>
