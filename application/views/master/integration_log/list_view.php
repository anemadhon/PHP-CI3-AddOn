<!DOCTYPE html>
<html lang="en">
	<head>
		<?php  $this->load->view("_template/head.php")?>
	</head>
	<body>
	<?php  $this->load->view("_template/nav.php")?>
		<div class="page-content">
			<?php  $this->load->view("_template/sidebar.php")?>
			<div class="content-wrapper">
				<div class="content">
                    <div class="card">
                        <div class="card-header">
                            <legend class="font-weight-semibold"><i class="icon-list mr-2"></i>List of Integration Log</legend>
                        </div>
                        <div class="card-body">
                            <table id="table-manajemen" class="table table-striped " style="width:100%">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Modul</th>
                                        <th>Message</th>
                                        <th>Error Time</th>
                                        <th>Trans ID</th>
                                        <th>Edit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>                    
				</div>
                <?php
                $isFreeze = $this->auth->is_freeze()['is_freeze'];
                $isReject = $this->auth->is_freeze()['is_reject'];
                ?>
				<?php  $this->load->view("_template/footer.php")?>
			</div>
		</div>
        <?php  $this->load->view("_template/js.php")?>
        <script>
            $(document).ready(function(){
                let freeze = '<?php echo $isFreeze; ?>';
                let reject = '<?php echo $isReject; ?>';
                let urlToEditPage = '';

                var table = $('#table-manajemen').DataTable({
                    "ordering":false,  "paging": true, "searching":true,
                    "ajax": {
                        "url":"<?php echo site_url('master/integration/showAllData');?>",
                        "type":"POST"
                    },
					"order": [[ 0, 'asc' ]],
                    "columns": [
                        {"data":"modul"},
                        {"data":"modul"},
                        {"data":"message"},
                        {"data":"time_error"},
                        {"data":"id_trans"},
                        {"data":"id_error","className":"dt-center", render:function(data, type, row, meta){
                                rr = freeze == 0 || reject == 1 ? `<a href='${goToEditPage(row['modul'], row['id_trans'])}'><i class='icon-file-plus2' title="Edit"></i></a>&nbsp;` : '';
                                return rr;
                            }
                        }
                    ]
                });
				table.on( 'order.dt search.dt', function () {
					table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
						cell.innerHTML = i+1;
					} );
				} ).draw();
            });

            function goToEditPage(keyword, id){
                
                const pr = '<?php echo site_url('transaksi1/pruchase_request/edit/')?>'
                const povendor = '<?php echo site_url('transaksi1/pofromvendor/edit/')?>'
                const nonpo = '<?php echo site_url('transaksi1/grnopo/edit/')?>'
                const sr = '<?php echo site_url('transaksi2/sr/edit/')?>'
                const sentul = '<?php echo site_url('transaksi1/grfromkitchensentul/edit/')?>'
                const to = '<?php echo site_url('transaksi1/transferoutinteroutlet/edit/')?>'
                const ti = '<?php echo site_url('transaksi1/transferininteroutlet/edit/')?>'
                const ro = '<?php echo site_url('transaksi1/returnout/edit/')?>'
                const ri = '<?php echo site_url('transaksi1/returnin/edit/')?>'
                const gi = '<?php echo site_url('transaksi1/goodissue/edit/')?>'
                const spoiled = '<?php echo site_url('transaksi1/spoiled/edit/')?>'
                const whole = '<?php echo site_url('transaksi2/whole/edit/')?>'
                const disassembly = '<?php echo site_url('transaksi1/disassembly/edit/')?>'
                const wo = '<?php echo site_url('transaksi1/wo/edit/')?>'
                const wopos = '<?php echo site_url('transaksi1/wopos/edit/')?>'
                const so = '<?php echo site_url('transaksi1/stock/edit/')?>'

                if(keyword.toLowerCase().includes('purchase request')) {
                    return pr+id
                } else if(keyword.toLowerCase().includes('po from vendor')) {
                    return povendor+id
                } else if(keyword.toLowerCase().includes('non po')) {
                    return nonpo+id
                } else if (keyword.toLowerCase().includes('store room')) {
                    return sr+id
                } else if (keyword.toLowerCase().includes('from sentul') || keyword.toLowerCase().includes('central kitchen')) {
                    return sentul+id
                } else if (keyword.toLowerCase().includes('transfer out')) {
                    return to+id
                } else if (keyword.toLowerCase().includes('transfer in')) {
                    return ti+id
                } else if (keyword.toLowerCase().includes('retur out')) {
                    return ro+id
                } else if (keyword.toLowerCase().includes('retur in')) {
                    return ri+id
                } else if (keyword.toLowerCase().includes('good issue')) {
                    return gi+id
                } else if (keyword.toLowerCase().includes('good issue from whole outlet')) {
                    return spoiled+id
                } else if (keyword.toLowerCase().includes('good issue waste')) {
                    return whole+id
                } else if (keyword.toLowerCase().includes('disassembly')) {
                    return disassembly+id
                } else if (keyword.toLowerCase().includes('production order from')) {
                    return wo+id
                } else if (keyword.toLowerCase().includes('order pos')) {
                    return wopos+id
                } else if (keyword.toLowerCase().includes('stock counting opname')) {
                    return so+id
                } 

            }
        </script>
	</body>
</html>