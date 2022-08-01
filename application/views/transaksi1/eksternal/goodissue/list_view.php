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
                    <?php if ($this->session->flashdata('success')): ?>
						<div class="alert alert-success" role="alert">
							<?php echo $this->session->flashdata('success'); ?>
						</div>
					<?php endif; ?>
					<?php if ($this->session->flashdata('failed')): ?>
						<div class="alert alert-danger" role="alert">
							<?php echo $this->session->flashdata('failed'); ?>
						</div>
					<?php endif; ?>
                    <div class="card">
                        <div class="card-header">
                            <legend class="font-weight-semibold"><i class="icon-search4 mr-2"></i>Search of Good Issue</legend>  
                        </div>
                        <div class="card-body">
                        <form action="#" method="POST">
                            <div class="row">
                                <div class="col-md-12">
                                <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Dari Tanggal</label>
                                        <div class="col-lg-3 input-group date">
                                            <input type="text" class="form-control" id="fromDate" name="fDate">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">
                                                    <i class="icon-calendar"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <label class="col-lg-2 col-form-label">Sampai Tanggal</label>
                                        <div class="col-lg-4 input-group date">
                                            <input type="text" class="form-control" id="toDate" name="tDate">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">
                                                    <i class="icon-calendar"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Status</label>
                                        <div class="col-lg-9">
                                            <select class="form-control form-control-select2" data-live-search="true" id="status" name="status">
                                                <option value="">-- All --</option>
                                                <?php 
                                                if ($filters) { 
                                                    foreach ($filters as $filter) {
                                                ?>
                                                    <option value="<?php echo $filter['status_desc'] ?>"><?php echo $filter['status_desc'] ?></option>
                                                <?php } ?>

                                                <?php }?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        <button type="button" class="btn btn-primary" onclick="onSearch()">Search<i class="icon-search4  ml-2"></i></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        </div>                        
                    </div> 
                    <div class="card">
                        <div class="card-body d-flex justify-content-between">
                            <div>
                                <p>Total On Hand Qty: <span id="onHand">0.0000</span></p>
                                <p>Total Issued Qty: <span id="issued">0.0000</span></p>
                                <p>Total Outsanding Qty To Integrate: <span id="outstand">0.0000</span></p>
                                <p>Total of Total Qty Integrated: <span id="totIntegrate">0.0000</span></p>
                            </div>
                            <div>
                                <?php for ($i=1; $i <= $max_attempt['max_attempt']; $i++) { ?>
                                    <p>Total Qty Integrated Attempt <?php echo $i?>: <span id="<?php echo 'integrate_'.$i?>">0.0000</span></p>
                                <?php } ?>
                            </div>
                        </div>                        
                    </div> 
                    <?php
                    $isFreeze = $this->auth->is_freeze()['is_freeze'];
                    $isReject = $this->auth->is_freeze()['is_reject'];
                    $isMgr = $this->auth->is_freeze()['is_mgr'];
                    ?>
                    <div class="card">
                        <div class="card-header">
                            <legend class="font-weight-semibold"><i class="icon-list mr-2"></i>List of Good Issue</legend>
                            <?php if (($isFreeze == 0 && $isMgr == 0) || $isReject == 1):?>
                            <a href="<?php echo site_url('transaksi1/goodissue/add') ?>" class="btn btn-primary"> Add New</a>
                            <input type="button" value="Delete" class="btn btn-danger" id="deleteRecord"> 
                            <?php endif; ?>
                            <input type="button" value="Integrate" class="btn btn-primary" id="integrateRecord"> 
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12" style="overflow:auto">
                                    <table id="tableWhole" class="table table-striped" style="widht:100%" >
                                        <thead>
                                            <tr>
                                                <th style="text-align: center" rowspan="2"><input type="checkbox" name="checkall" id="checkall"></th>
                                                <th style="text-align: center" rowspan="2">Action</th>
                                                <th style="text-align: center" rowspan="2">ID</th>
                                                <th style="text-align: center" rowspan="2">Status</th>
                                                <th style="text-align: center" rowspan="2">Posting Date</th>
                                                <th style="text-align: center" rowspan="2">Item Code</th>
                                                <th style="text-align: center" rowspan="2">Item Name</th>
                                                <th style="text-align: center" rowspan="2">Warehouse</th>
                                                <th style="text-align: right" rowspan="2">On Hand Qty</th>
                                                <th style="text-align: right" rowspan="2">Issued Qty</th>
                                                <th style="text-align: center" rowspan="2">UOM</th>
                                                <th style="text-align: right" rowspan="2">Outsanding Qty To Integrate</th>
                                                <th style="text-align: right" rowspan="2">Total Qty Integrated</th>
                                                <?php for ($i=1; $i <= $max_attempt['max_attempt']; $i++) { ?>
                                                    <th style="text-align: center" colspan="3">Attempt <?php echo $i?></th>
                                                <?php } ?>
                                                <!-- <th style="text-align: center">Created by</th>
                                                <th style="text-align: center">Approved by</th>
                                                <th style="text-align: center">Last Modified</th>
                                                <th style="text-align: center">Status</th>
                                                <th style="text-align: center">Log</th> -->
                                            </tr>
                                            <tr>
                                                <?php for ($i=1; $i <= $max_attempt['max_attempt']; $i++) { ?>
                                                    <th style="text-align: center">SAP Doc No.</th>
                                                    <th style="text-align: center">Doc Date</th>
                                                    <th style="text-align: right">Qty integrate</th>
                                                <?php } ?>
                                                <!-- <th style="text-align: center">Created by</th>
                                                <th style="text-align: center">Approved by</th>
                                                <th style="text-align: center">Last Modified</th>
                                                <th style="text-align: center">Status</th>
                                                <th style="text-align: center">Log</th> -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        </div>                   
				</div>
				<?php  $this->load->view("_template/footer.php")?>
			</div>
		</div>
        <?php  $this->load->view("_template/modal_delete.php")?>
        <?php  $this->load->view("_template/js.php")?>
        <script>
            $(document).ready(function(){
                $('#fromDate').datepicker();
                $('#toDate').datepicker();

                showDataList();

                // untuk check all
                $("#checkall").click(function(){

                    if($(this).is(':checked')){
                        $(".check_delete_integrate").prop('checked', true);
                    }else{
                        $(".check_delete_integrate").prop('checked', false);
                    }
                });
                // end check all

                $("#deleteRecord").click(function(){
                    let deleteidArr=[];
                    $("input:checkbox[class=check_delete_integrate]:checked").each(function(){
                        deleteidArr.push($(this).val());
                    })
                    
                    // mengecek ckeckbox tercheck atau tidak
                    if(deleteidArr.length > 0){
                        var confirmDelete = confirm("Do you really want to Delete records?");
                        if(confirmDelete == true){
                            $.ajax({
                                url:"<?php echo site_url('transaksi1/goodissue/deleteData');?>", //masukan url untuk delete
                                type: "post",
                                data:{deleteArr: deleteidArr},
                                success:function(res) {
                                    location.reload(true);
                                }
                            });
                        }
                    }
                });
                
                $("#integrateRecord").click(function(){
                    let integrateidArr=[];
                    let validateAttemptArr=[];

                    let maxAttempt = '<?php echo $max_attempt["max_attempt"]; ?>';

                    $("input:checkbox[class=check_delete_integrate]:checked").each(function(){
                        integrateidArr.push($(this).attr('detail'));
                        validateAttemptArr.push($(this).attr('attempt').length);
                    })
                    
                    // mengecek ckeckbox tercheck atau tidak
                    if(integrateidArr.length > 0){
                        var confirmIntegrate = confirm("Do you really want to integrate the records?");
                        if(confirmIntegrate == true){
                            let validateOnHandQty = [];
                            let validateOnHandQtyZero = [];
                            let validateApproved = [];
                            let idx = 0;
                            $('#tableWhole > tbody').find('tr').each(function(i, el){
                                let td = $(this).find('td');
                                if (td.eq(0).find('input:checkbox').is(':checked')) {
                                    if (integrateidArr[idx] == td.eq(0).find('input:checkbox:checked').attr('detail')) {
                                        if (td.eq(8).text() === '0.0000') {
                                            validateOnHandQtyZero.push(`${td.eq(5).text()} baris ke ${i+1}`);
                                        }
                                    }
                                    idx++
                                }
                            })
                            idx = 0
                            $('#tableWhole > tbody').find('tr').each(function(i, el){
                                let td = $(this).find('td');
                                if (td.eq(0).find('input:checkbox').is(':checked')) {
                                    if (validateAttemptArr[idx] == (maxAttempt - 1)) {
                                        if (validateAttemptArr[idx] == td.eq(0).find('input:checkbox:checked').attr('attempt').length) {
                                            if ((parseFloat(td.eq(8).text()) < parseFloat(td.eq(11).text()))) {
                                                validateOnHandQty.push(`${td.eq(5).text()} baris ke ${i+1}`);
                                            }
                                        }
                                    }
                                    idx++
                                }
                            })
                            idx = 0
                            $('#tableWhole > tbody').find('tr').each(function(i, el){
                                let td = $(this).find('td');
                                if (td.eq(0).find('input:checkbox').is(':checked')) {
                                    if (integrateidArr[idx] == td.eq(0).find('input:checkbox:checked').attr('detail')) {
                                        if (td.eq(3).text() !== '' && !td.eq(3).text().includes('Not Approved')) {
                                            validateApproved.push(`${td.eq(2).text()}`);
                                        }
                                    }
                                    idx++
                                }
                            })

                            if (validateApproved.length > 0) {
                                alert('ID Transaksi '+validateApproved.join()+' sudah Approved.');
                                return
                            }
                            
                            if (validateOnHandQtyZero.length > 0) {
                                alert('On Hand untuk Item Code '+validateOnHandQtyZero.join()+' tidak boleh 0.');
                                return
                            }
                            
                            if (validateOnHandQty.length > 0) {
                                alert('On Hand untuk Item Code '+validateOnHandQty.join()+' harus lebih besar sama dengan Outstanding Qty to Integrate.');
                                return
                            }

                            $.ajax({
                                url:"<?php echo site_url('transaksi1/goodissue/integrateData');?>",
                                type: "post",
                                data:{integrateArr: integrateidArr},
                                success:function(res) {
                                    location.reload(true);
                                }
                            });
                        }
                    }
                });

                // ini adalah function versi ES6
                checkcheckbox = () => {
                    
                    const lengthcheck = $(".check_delete_integrate").length;
                    
                    let totalChecked = 0;
                    $(".check_delete_integrate").each(function(){
                        if($(this).is(":checked")){
                            totalChecked += 1;
                        }
                    });

                    if(totalChecked == lengthcheck){
                        $("#checkall").prop('checked', true);
                    }else{
                        $("#checkall").prop('checked', false);
                    }

                }

            });

            function onSearch(){
                const fromDate = $('#fromDate').val();
                const toDate = $('#toDate').val();
                const status = $('#status').val();

                showDataList();
            }

            function showDataList(){
                const obj = $('#tableWhole tbody tr').length;

                if(obj > 0){
                    const dataTable = $('#tableWhole').DataTable();
                    dataTable.destroy();
                    $('#tableWhole > tbody > tr').remove();
                    
                }
                 
                const fromDate = $('#fromDate').val();
                const toDate = $('#toDate').val();
                const status = $('#status').val();  

                let freeze = '<?php echo $isFreeze; ?>';
                let reject = '<?php echo $isReject; ?>';  

                let maxAttempt = '<?php echo $max_attempt["max_attempt"]; ?>'; 
                let additionalColumns = [
                    {"data":"id_issue_header", "className":"dt-center"},
                    {"data":"id_issue_header", "className":"dt-center"},
                    {"data":"id_issue_header", "className":"dt-center"},
                    {"data":"status_desc", "className":"dt-center"},
                    {"data":"posting_date", "className":"dt-center"},
                    {"data":"material_no", "className":"dt-center"},
                    {"data":"material_desc", "className":"dt-center"},
                    {"data":"plant", "className":"dt-center"},
                    {"data":"on_hand", "className":"dt-center"},
                    {"data":"quantity", "className":"dt-center"},
                    {"data":"uom", "className":"dt-center"},
                    {"data":"outstd_qty_to_intgrte", "className":"dt-center"},
                    {"data":"total_qty_intgrted", "className":"dt-center"},
                ];

                for (let index = 1; index <= maxAttempt; index++) {
                    additionalColumns.push({data: "doc_no_"+index, className: "dt-center"});
                    additionalColumns.push({data: "approved_time_"+index, className: "dt-center"});
                    additionalColumns.push({data: "qty_integrate_"+index, className: "dt-center"});
                }

                dataTable = $('#tableWhole').DataTable({
                    "ordering":true,  "paging": true, "searching":true,
                    "ajax": {
                        "url":"<?php echo site_url('transaksi1/goodissue/showAllData');?>",
                        "type":"POST",
                        "data":{fDate: fromDate, tDate: toDate, stts: status}
                    },
                    "initComplete": function(settings, json) {
                        $('#onHand').text(this.api().column(8).data().reduce(function(a, b) {
                            return parseFloat(parseFloat(a) + parseFloat(b)).toLocaleString(('en-US'), {minimumFractionDigits: 4, maximumFractionDigits: 4});
                        }, 0))
                        $('#issued').text(this.api().column(9).data().reduce(function(a, b) {
                            return parseFloat(parseFloat(a) + parseFloat(b)).toLocaleString(('en-US'), {minimumFractionDigits: 4, maximumFractionDigits: 4});
                        }, 0))
                        $('#outstand').text(this.api().column(11).data().reduce(function(a, b) {
                            return parseFloat(parseFloat(a) + parseFloat(b)).toLocaleString(('en-US'), {minimumFractionDigits: 4, maximumFractionDigits: 4});
                        }, 0))
                        $('#totIntegrate').text(this.api().column(12).data().reduce(function(a, b) {
                            return parseFloat(parseFloat(a) + parseFloat(b)).toLocaleString(('en-US'), {minimumFractionDigits: 4, maximumFractionDigits: 4});
                        }, 0))

                        let clm = 15
                        for (let idx = 1; idx <= maxAttempt; idx++) {
                            $(`#integrate_${idx}`).text(this.api().column(clm).data().reduce(function(a, b) {
                                return parseFloat(parseFloat(a) + parseFloat(b)).toLocaleString(('en-US'), {minimumFractionDigits: 4, maximumFractionDigits: 4});
                            }, 0))
                            clm += 3
                        }
                    },
                    "columns": additionalColumns,
                    "columnDefs": [
                        {
                            "targets": 0,
                            "render": function ( data, type, row ) {
                                rr =`<input type="checkbox" class="check_delete_integrate" id="chk_${data}" value="${data}" detail="${row['id_issue_detail']}" attempt="${row['attempt']}" onclick="checkcheckbox();">`;
                                return rr;
                            }
                        },
                        {
                            "targets": 1,
                            "render": function ( data, type, row ) {
                                rr = `<div style="width:100px">
                                    ${freeze == 0 || reject == 1 ? `<a href='<?php echo site_url('transaksi1/goodissue/edit/')?>${data}' ><i class='icon-file-plus2' title="Edit"></i></a>&nbsp;` : ''}
                                </div>`;
                                return rr;
                            }
                        }
                    ]
                    /* "columns": [
                        {"data":"id_issue_header", "className":"dt-center", render:function(data, type, row, meta){
                            rr=`<input type="checkbox" class="check_delete_integrate" id="chk_${data}" value="${data}" onclick="checkcheckbox();">`;
                            return rr;
                        }},
                        {"data":"id_issue_header", "className":"dt-center", render:function(data, type, row, meta){
                            rr = `<div style="width:100px">
                                    ${freeze == 0 || reject == 1 ? `<a href='<?php //echo site_url('transaksi1/goodissue/edit/')?>${data}' ><i class='icon-file-plus2' title="Edit"></i></a>&nbsp;` : ''}
                                </div>`;
                            return rr;
                        }},
                        {"data":"id_issue_header", "className":"dt-center"},
                        {"data":"material_doc_no", "className":"dt-center"},
                        {"data":"posting_date", "className":"dt-center"},
                        {"data":"material_no", "className":"dt-center"},
                        {"data":"material_desc", "className":"dt-center"},
                        {"data":"plant", "className":"dt-center"},
                        {"data":"on_hand", "className":"dt-center"},
                        {"data":"quantity", "className":"dt-center"},
                        {"data":"uom", "className":"dt-center"},
                        {"data":"outstd_qty_to_intgrte", "className":"dt-center"},
                        {"data":"total_qty_intgrted", "className":"dt-center"},
                        // return additionalColumns
                        {"data":"user_input", "className":"dt-center"},
                        {"data":"user_approved", "className":"dt-center"},
                        {"data":"last_modified", "className":"dt-center"},
                        {"data":"status_string", "className":"dt-center"},
                        {"data":"log", "className":"dt-center"}
                    ], */
                });
            }
        
        </script>
	</body>
</html>