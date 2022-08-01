<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email</title>
</head>
<body>
    <?php 
    $arrayItemsCode = array();
    $arrayNewCost = array();
    $arrayVariance = array();
    ?>
    <p>Hai <strong><?php echo $template['to'] ?></strong></p>
    <p>User <strong><?php echo $template['from']['username'] ?></strong> mengajukan <strong><?php echo $template['header']['bom_type'] == 1 ? 'Subtitusi' : 'Alternatif' ?></strong> kepada anda dan membutuhkan approval anda segera.</p>
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
            <td>: <strong><?php echo $template['header']['category_approver'] ?></strong> Pada <strong><?php echo isset($template['date_appoved']['approved_cat_approver_date']) ? date('d-m-Y H:i:s', strtotime($template['date_appoved']['approved_cat_approver_date'])) : '-' ?></strong></td>
        </tr>
    </table>
    <p>Detail item <strong><?php echo $template['header']['bom_type'] == 1 ? 'Subtitusi' : 'Alternatif' ?></strong> yang diajukan adalah sebagai berikut :</p>
    <table>
        <tr>
            <td>
                <table style="border-collapse:collapse;" border="1">
                    <tr align="center"><td colspan="4"><strong>ITEM EXISTING</strong></td></tr>
                    <tr align="center">
                        <td><strong>Item Code</strong></td>
                        <td><strong>Item Description</strong></td>
                        <td><strong>Inventory UOM</strong></td>
                        <td><strong>Item Cost</strong></td>
                    </tr>
                    <tr align="center">
                        <td><?php echo $template['header']['raw_mat_code_old']?></td>
                        <td><?php echo $template['header']['raw_mat_name_old']?></td>
                        <td><?php echo $template['rm_current']['UNIT']?></td>
                        <td><?php echo number_format($template['rm_current']['LastPrice'] ? $template['rm_current']['LastPrice'] : 0, 4)?></td>
                    </tr>
                </table>
            </td>
            <td></td>
            <td>
                <table style="border-collapse:collapse;" border="1">
                    <tr align="center"><td colspan="4"><strong>ITEM <?php echo $template['header']['bom_type'] == 1 ? 'SUBTITUTION' : 'ALTERNATIVE' ?></strong></td></tr>
                    <tr align="center">
                        <td><strong>Item Code</strong></td>
                        <td><strong>Item Description</strong></td>
                        <td><strong>Inventory UOM</strong></td>
                        <td><strong>Item Cost</strong></td>
                    </tr>
                    <tr align="center">
                        <td><?php echo $template['header']['raw_mat_code_new']?></td>
                        <td><?php echo $template['header']['raw_mat_name_new']?></td>
                        <td><?php echo $template['rm_new']['UNIT']?></td>
                        <td><?php echo number_format($template['rm_new']['LastPrice'] ? $template['rm_new']['LastPrice'] : 0, 4)?></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <p>BOM (Bill Of Material) atau resep yang diajukan untuk <strong><?php echo $template['header']['bom_type'] == 1 ? 'Subtitusi' : 'Alternatif' ?></strong> adalah sebagai berikut :</p>
    <table style="border-collapse:collapse;" border="1">
        <tr align="center">
            <td><strong>No.</strong></td>
            <td><strong>Item Group</strong></td>
            <td><strong>Item Code</strong></td>
            <td><strong>Item Description</strong></td>
            <td><strong>BOM Line No<</strong></td>
            <td><strong>Existing Item Qty</strong></td>
            <td><strong>Existing Item UOM</strong></td>
            <td><strong>New Item Qty</strong></td>
            <td><strong>New Item UOM</strong></td>
            <td><strong>Existing Item Total Cost</strong></td>
            <td><strong>New Item Total Cost</strong></td>
            <td><strong>Variance (Amount)</strong></td>
            <td><strong>Variance (%)</strong></td>
        </tr>
        <?php 
        foreach ($template['detail'] as $key => $detail) { 
            $arrayItemsCode[] =  "'".$detail['material_no']."'";
            $arrayVariance[$detail['material_no']] =  $detail['variance'];
            $arrayNewCost[] =  $detail['tot_cost_new'];
        ?>
            <tr>
                <td><?php echo $key+1?></td>
                <td><?php echo $detail['item_group_detail']?></td>
                <td><?php echo $detail['material_no']?></td>
                <td><?php echo $detail['material_desc']?></td>
                <td><?php echo $detail['sap_line']?></td>
                <td><?php echo number_format($detail['qty_old'],4)?></td>
                <td><?php echo $detail['uom_old']?></td>
                <td><?php echo number_format($detail['qty_new'],4)?></td>
                <td><?php echo $detail['uom_new']?></td>
                <td><?php echo number_format($detail['tot_cost_old'],4)?></td>
                <td><?php echo number_format($detail['tot_cost_new'],4)?></td>
                <td><?php echo number_format($detail['variance'],4)?></td>
                <td><?php echo number_format($detail['variance_percentage'],4)?></td>
            </tr>
        <?php } ?>
    </table>
    <?php 
    $SAP_MSI = $this->load->database('SAP_MSI', TRUE);

    $array = implode(',', $arrayItemsCode);

    $SQL = "WITH [BOM_Recursive] (FGItmCode,FgItmName,ChildNum,RmItemCode,RmItemName,Quantity,[CurrentTotalCost], [RecursionLevel]) 
    -- CTE name and columns
    AS (

        select father as FGItmCode, b.ItemName as FgItmName, a.ChildNum, a.Code RmItemCode, D.ItemName RmItemName,
        Quantity,
        dbo.f_bomrollup(a.Father)
        [CurrentTotalCost], 0 [RecursionLevel]
        from ITT1 A 
        INNER JOIN OITM B ON A.Father = B.ItemCode
        INNER JOIN OITM d ON A.Code = d.ItemCode
        where a.code in ($array)

            UNION ALL

            SELECT c.ItemCode, c.ItemName, b.ChildNum, cte.RmItemCode RmItemCode, cte.RmItemName RmItemName,
            b.Quantity Quantity,
            dbo.f_bomrollup(b.Father) [CurrentTotalCost],
            [RecursionLevel] + 1 
            -- Join recursive member to anchor
            FROM [BOM_Recursive] cte
                INNER JOIN itt1 b 
                inner join oitm c on b.[Father]=c.itemcode 
                INNER JOIN OITM e ON b.Code = e.ItemCode
                on b.Code = cte.FGItmCode
            )

    SELECT d.ItmsGrpNam, FGItmCode as FGCodeImpact, FgItmName as FGNameImpact, ChildNum as line, RmItemCode as FGCode,
    RmItemName as FGName, Quantity as oldQty, C.InvntryUom as oldUOM, [CurrentTotalCost] as oldPrice, RecursionLevel
    FROM [BOM_Recursive] a
    inner join oitm c on a.FGItmCode = c.ItemCode
    inner join oitb d on d.ItmsGrpCod = c.ItmsGrpCod
    order by 4,7";

    $query = $SAP_MSI->query($SQL);
    if ($query->num_rows() > 0) {
        $differetns = $query->result_array();
    }
    ?>
    <p>Adapun Produk Finished Goods terkait dari pengajuan <strong><?php echo $template['header']['bom_type'] == 1 ? 'Subtitusi' : 'Alternatif' ?></strong> diatas adalah sebagai berikut :</p>
    <table style="border-collapse:collapse;" border="1">
        <tr align="center">
            <td><strong>No.</strong></td>
            <td><strong>Item Group</strong></td>
            <td><strong>Item Code</strong></td>
            <td><strong>Item Description</strong></td>
            <td><strong>Existing Item Total Cost</strong></td>
            <td><strong>New Item Total Cost</strong></td>
            <td><strong>Variance (Amount)</strong></td>
            <td><strong>Variance (%)</strong></td>
        </tr>
        <?php if ($query->num_rows() === 0) { ?>
            <tr align="center"><td colspan="8">Tidak Ada Data</td></tr>
        <?php } ?>
        <?php if ($query->num_rows() > 0) { ?>
            <?php foreach ($differetns as $key => $differetn) { ?>
                <tr align="center">
                    <td><?php echo (int)($key + 1) ?></td>
                    <td><?php echo $differetn['ItmsGrpNam'] ?></td>
                    <td><?php echo $differetn['FGCodeImpact'] ?></td>
                    <td><?php echo $differetn['FGNameImpact'] ?></td>
                    <td><?php echo number_format($differetn['oldPrice'] * 1.1, 4) ?></td>
                    <td><?php echo number_format((($differetn['oldQty'] * $arrayVariance[$differetn['FGCode']]) + ($differetn['oldPrice'] * 1.1)), 4) ?></td>
                    <td><?php echo number_format(((($differetn['oldQty'] * $arrayVariance[$differetn['FGCode']]) + ($differetn['oldPrice'] * 1.1)) - ($differetn['oldPrice'] * 1.1)), 4) ?></td>
                    <td><?php echo number_format(((((($differetn['oldQty'] * $arrayVariance[$differetn['FGCode']]) + ($differetn['oldPrice'] * 1.1)) - ($differetn['oldPrice'] * 1.1)) / ($differetn['oldPrice'] * 1.1)) * 100), 4).' %' ?></td>
                </tr>
            <?php }?>
        <?php } ?>
    </table>
    <p>Untuk menyetujui atau menolak <strong><?php echo $template['header']['bom_type'] == 1 ? 'Subtitusi' : 'Alternatif' ?></strong> di atas pastikan anda telah login terlebih dahulu ke add on, kemudian klik button <strong>APPROVE / REJECT</strong> pada tautan berikut : <span><a href="<?php echo site_url('transaksi1/bomsubalt/edit/').$template['header']['id_bom_subalt_header']?>"><?php echo site_url('transaksi1/bomsubalt/edit/').$template['header']['id_bom_subalt_header']?></a></span></p>
    <br>
    <p>Terima Kasih</p>
    <p><em><strong>Catatan : email ini dikirimkan secara otomatis dari sistem, mohon untuk tidak me-reply email ini.</strong></em></p>
</body>
</html>