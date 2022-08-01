<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email</title>
</head>
<body>
    <?php 
    $cat_app = $template['header']['category_approver'];
    $sql = "SELECT admin_realname as name FROM d_admin WHERE admin_username = '$cat_app'";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
        $category_app = $query->row_array();
    }

    $qFactor = (float)(number_format($template['q_factor_min_max_sap']['q_factor'],2) * (1/100));
    $min = (float)(number_format($template['q_factor_min_max_sap']['min_cost'],2) * (1/100));
    $max = (float)(number_format($template['q_factor_min_max_sap']['max_cost'],2) * (1/100));

    $totAllIngCost = 0;
    foreach($template['detail_ing'] as $keyIng => $ing){ 
        $totAllIngCost += (float)($ing['item_qty'] * $ing['item_cost']);
    }

    $totAllPackCost = 0;
    if ($template['detail_pack']) {
        foreach($template['detail_pack'] as $keyPack => $pack){ 
            $totAllPackCost += (float)($pack['item_qty'] * $pack['item_cost']);
        }
    }

    if ((float)(number_format($template['header']['product_percentage'],2) / 100) > $max) {
        $percentageDesc = 'Product Cost above Threshold';
    } elseif ((float)(number_format($template['header']['product_percentage'],2) / 100) < $min) {
        $percentageDesc = 'Product Cost below Threshold';
    } else {
        $percentageDesc = 'Product Cost within Threshold, Ok to continue';
    }
    ?>
    <p>Hai <strong><?php echo $template['to'] ?></strong></p>
    <p>User <strong><?php echo $template['from']['name'] ?></strong> mengajukan product costing kepada anda dan membutuhkan approval anda segera.</p>
    <p>Pengajuan ini telah di approve oleh user sebagai berikut :</p>
    <table>
        <tr>
            <td>Requestor</td>
            <td>: <strong><?php echo $template['from']['name'] ?></strong> Pada <strong><?php echo isset($template['date_appoved']['approved_user_date']) ? date('d-m-Y H:i:s', strtotime($template['date_appoved']['approved_user_date'])) : '-' ?></strong></td>
        </tr>
        <tr>
            <td>Kepala Departemen</td>
            <td>: <strong><?php echo $template['hod']['name'] ?></strong> Pada <strong><?php echo isset($template['date_appoved']['approved_head_dept_date']) ? date('d-m-Y H:i:s', strtotime($template['date_appoved']['approved_head_dept_date'])) : '-' ?></strong></td>
        </tr>
        <tr>
            <td>Kategori Approver</td>
            <td>: <strong><?php echo $category_app['name'] ?></strong> Pada <strong><?php echo isset($template['date_appoved']['approved_cat_approver_date']) ? date('d-m-Y H:i:s', strtotime($template['date_appoved']['approved_cat_approver_date'])) : '-' ?></strong></td>
        </tr>
    </table>
    <p>Detail product costing yang diajukan adalah sebagai berikut :</p>
    <table>
        <tr>
            <td>Kode Produk Pengajuan</td>
            <td>: <strong><?php echo $template['header']['prod_cost_no'] ?></strong></td>
        </tr>
        <tr>
            <td>Nama Produk Pengajuan</td>
            <td>: <strong><?php echo $template['header']['product_name'] ?></strong></td>
        </tr>
        <tr>
            <td>Qty Produk</td>
            <td>: <strong><?php echo number_format($template['header']['product_qty'],2) ?></strong></td>
        </tr>
        <?php if ($template['header']['product_type'] == 2) { ?>
        <tr>
            <td>Harga Jual (Termasuk Pajak)</td>
            <td>: <strong><?php echo number_format($template['header']['product_selling_price'],2) ?></strong></td>
        </tr>
        <?php } ?>
        <tr>
            <td>Total Produk Cost</td>
            <td>: <strong><?php echo number_format($template['header']['product_result'],2) ?></strong></td>
        </tr>
        <?php if ($template['header']['product_type'] == 2) { ?>
        <tr>
            <td>Q Factor</td>
            <td>: <strong><?php echo number_format((($template['header']['product_selling_price'] / 1.1) * $qFactor),2) ?></strong></td>
        </tr>
        <tr>
            <td>Persentase Costing Produk (%)</td>
            <td>: <strong><?php echo number_format($template['header']['product_percentage'],2).' % '.$percentageDesc ?></strong></td>
        </tr>
        <?php } ?>
        <tr>
            <td>Total Produk Cost Per-Qty</td>
            <td>: <strong><?php echo number_format($template['header']['product_result_div_product_qty'],2) ?></strong></td>
        </tr>
        <tr>
            <td>Tanggal Pengajuan</td>
            <td>: <strong><?php echo date('d-m-Y', strtotime($template['header']['posting_date'])) ?></strong></td>
        </tr>
    </table>
    <p><strong>LIST MATERIAL</strong></p>
    <table style="border-collapse:collapse;" border="1" width="100%">
        <tr align="center">
            <td width="5%"><strong>NO</strong></td>
            <td width="10%"><strong>ITEM CODE</strong></td>
            <td width="25%"><strong>ITEM NAME</strong></td>
            <td width="5%"><strong>UOM</strong></td>
            <td width="5%"><strong>QTY</strong></td>
            <td width="25%"><strong>UNIT COST (INCLUDE TAX)</strong></td>
            <td width="25%"><strong>TOTAL COST</strong></td>
        </tr>
        <?php foreach ($template['detail_ing'] as $keyIng => $ing) { ?>
            <tr>
                <td><?php echo $keyIng+1?></td>
                <td><?php echo $ing['material_no']?></td>
                <td><?php echo $ing['material_desc']?></td>
                <td><?php echo $ing['item_uom']?></td>
                <td><?php echo number_format($ing['item_qty'],2)?></td>
                <td><?php echo number_format($ing['item_cost'],2)?></td>
                <td><?php echo number_format((float)($ing['item_cost'] * $ing['item_qty']),2)?></td>
            </tr>
        <?php } ?>
        <tr>
            <td colspan="6" align="right"><strong>GRAND TOTAL INGREDIENT COST</strong></td>
            <td><strong><?php echo number_format($totAllIngCost,2) ?></strong></td>
        </tr>
    </table>
    <?php if ($template['detail_pack']) { ?>
        <p><strong>LIST PACKAGING</strong></p>
        <table style="border-collapse:collapse;" border="1" width="100%">
            <tr align="center">
                <td width="5%"><strong>NO</strong></td>
                <td width="10%"><strong>ITEM CODE</strong></td>
                <td width="25%"><strong>ITEM NAME</strong></td>
                <td width="5%"><strong>UOM</strong></td>
                <td width="5%"><strong>QTY</strong></td>
                <td width="25%"><strong>UNIT COST (INCLUDE TAX)</strong></td>
                <td width="25%"><strong>TOTAL COST</strong></td>
            </tr>
            <?php foreach ($template['detail_pack'] as $keyPack => $pack) { ?>
                <tr>
                    <td><?php echo $keyPack+1?></td>
                    <td><?php echo $pack['material_no']?></td>
                    <td><?php echo $pack['material_desc']?></td>
                    <td><?php echo $pack['item_uom']?></td>
                    <td><?php echo number_format($pack['item_qty'],2)?></td>
                    <td><?php echo number_format($pack['item_cost'],2)?></td>
                    <td><?php echo number_format((float)($pack['item_cost'] * $pack['item_qty']),2)?></td>
                </tr>
            <?php } ?>
            <tr>
                <td colspan="6" align="right"><strong>GRAND TOTAL PACKAGING COST</strong></td>
                <td><strong><?php echo number_format($totAllPackCost,2) ?></strong></td>
            </tr>
        </table>
    <?php } ?>
    <p>Untuk menyetujui atau menolak product costing di atas pastikan anda telah login terlebih dahulu ke add on, kemudian klik button <strong>APPROVE / REJECT</strong> pada tautan berikut : <span><a href="<?php echo site_url('transaksi1/productcosting/edit/').$template['header']['id_prod_cost_header']?>"><?php echo site_url('transaksi1/productcosting/edit/').$template['header']['id_prod_cost_header']?></a></span></p>
    <br>
    <p>Terima Kasih</p>
    <p><em><strong>Catatan : email ini dikirimkan secara otomatis dari sistem, mohon untuk tidak me-reply email ini.</strong></em></p>
</body>
</html>