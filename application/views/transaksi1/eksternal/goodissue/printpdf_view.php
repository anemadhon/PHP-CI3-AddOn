<?php
    foreach($data as $row)
        if ($row['back']=='1') {
            
?>

<table width="682"  align="center">
    <tr>
        <td width="336"><u><span class="style10"><span class="style12">____________</span><br />
        <span class="style6">THE HARVEST</span></span></u><br />
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="style8">&nbsp;Partisier &amp; Chocolatier</span></td>
        <td colspan="2" align="center"><span class="style6">Good Issue</span></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td colspan="2" align="center">&nbsp;</td>
    </tr>
    <tr>
        <td><strong>PT. Mount Scopus Indonesia</strong></td>
        <td width="157">Issue No.</td>
        <td width="173">:&nbsp;<?php echo $row['material_doc_no'];?></td>
    </tr>
    <tr>
        <td>Jl. Wolter Monginsidi No.95 Kebayoran Baru</td>
        <td>Date</td>
        <td>:&nbsp;<?php echo $row['posting_date'];?></td>
    </tr>
    <tr>
        <td>Jakarta Selatan 12110, Indonesia</td>
        <td>Outlet</td>
        <td>:&nbsp;<?php echo $row['plant'].' - '.$row['plant_name'];?></td>
    </tr>
    <tr>
        <td>Ph. +62 21 726 06680 / Fax. +62 21 727 971 59</td>
        <td></td>
        <td>:&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
</table>

<p>&nbsp;</p>


<table style="border-collapse:collapse;" width="47%" border="1" align="center">
    <tr>
        <td width="22" align="center" bgcolor="#999999"><strong>No</strong></td>
        <td width="65" align="center" bgcolor="#999999"><strong>Code</strong></td>
        <td width="178" align="center" bgcolor="#999999"><strong>Description</strong></td>
        <td width="35" align="center" bgcolor="#999999"><strong>Uom</strong></td>
        <td width="80" align="center" bgcolor="#999999"><strong>Qty</strong></td>
        <td width="83" align="center" bgcolor="#999999"><strong>Reason</strong></td>
    </tr>
    <?php 
        $no=1;
        foreach($data as $row1) {
    ?>
    <tr>
        <td align="center"><?php echo $no++; ?></td>
        <td>&nbsp;<?php echo $row1['material_no']; ?></td>
        <td>&nbsp;<?php echo $row1['material_desc']; ?></td>
        <td align="right"><?php echo $row1['uom']; ?>&nbsp;</td>
        <td align="right"><?php echo substr($row1['quantity'],0,-4);?>&nbsp;</td>
        <td align="right"><?php echo $row1['reason_name'].' - '.$row1['other_reason'];?>&nbsp;</td>
    </tr>
    <?php } ?>
</table>


<p>&nbsp;	</p>
<p>&nbsp;</p>


<table width="606" border="1"   style="border-collapse:collapse;" align="center">
    <tr>
        <td width="187" align="center" scope="col">Received by :</td>
        <td width="196" align="center" scope="col">Approved by :</td>
        <td width="201" align="center" scope="col">Verified by :</td>
    </tr>
    <tr>
        <?php
        $id=$row->id_user_approved;

        $name='Admin Realname';

        ?>
        <td height="115" align="center" valign="bottom">(User)</td>
        <td align="center" valign="bottom">(User)</td>
        <td align="center" valign="bottom">(User)</td>
    </tr>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>

<div align="justify"></div>
<table width="640" align="center">
    <tr>
        <td width="10"><span class="style5">1.</span></td>
        <td width="618"><span class="style3 style5"> FILLOUT IN DUPLICATE RETAIL YELLOW COPY FOR YOURFILE</span></td>
    </tr>
    <tr>
        <td><span class="style5">2.</span></td>
        <td><span class="style3 style5"> DEPARTEMENT MANAGERS MUST SIGN PRIOR TO ORDERING</span></td>
    </tr>
    <tr>
        <td><span class="style5">3.</span></td>
        <td><span class="style3 style5"> RECEIVING - RED</span></td>
    </tr>
    <tr>
        <td><span class="style5">4.</span></td>
        <td><span class="style3 style5"> USER - BLUE</span></td>
    </tr>
</table>
<?php } else { ?>
<h1>Data Belum Di Terintegrasi!!! Silahkan Integrasikan Data</h1>
<?php } ?>
