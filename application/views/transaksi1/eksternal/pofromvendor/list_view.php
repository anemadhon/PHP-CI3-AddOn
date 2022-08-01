<!DOCTYPE html>
<html lang="en">
	<head>
		<?php  $this->load->view("_template/head.php")?>
        <style type="text/css">
            th { font-size: 13px; }
            td { font-size: 12px; }	
        </style>
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
                            <legend class="font-weight-semibold"><i class="icon-search4 mr-2"></i>Search of Good Receive PO from Vendor</legend>  
                        </div>
                        <div class="card-body">
                        <form method="POST">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Dari Tanggal</label>
                                        <div class="col-lg-3 input-group date">
                                            <input type="text" class="form-control" id="fromDate" name="fromDate">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">
                                                    <i class="icon-calendar"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <label class="col-lg-2 col-form-label">Sampai Tanggal</label>
                                        <div class="col-lg-4 input-group date">
                                            <input type="text" class="form-control" id="toDate" name="toDate">
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
                                            <select class="form-control form-control-select2" data-live-search="true" name='status' id='status'>
                                                <option value="">-- All --</option>
                                                <option value="2">Approved</option>
                                                <option value="1">Not Approved</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        <button type="button" class="btn btn-primary" onclick="search()">Search<i class="icon-search4  ml-2" name="btnSearch" id="btnSearch"></i></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        </div>                        
                    </div> 
                    <?php
                    $isFreeze = $this->auth->is_freeze()['is_freeze'];
                    $isReject = $this->auth->is_freeze()['is_reject'];
                    $isMgr = $this->auth->is_freeze()['is_mgr'];
                    ?>
                    <div class="card">
                        <div class="card-header">
                            <legend class="font-weight-semibold"><i class="icon-list mr-2"></i>List of Good Receive PO from Vendor</legend>
                            <?php if (($isFreeze == 0 && $isMgr == 0) || $isReject == 1):?>
                            <a href="<?php echo site_url('transaksi1/pofromvendor/add') ?>" class="btn btn-primary"> Add New</a>
                            <input type="button" value="Delete" class="btn btn-danger" id="deleteRecord">  
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12" style="overflow:auto">
                                    <table id="tableWhole" class="table table-striped" style="widht:100%" >
                                        <thead>
                                            <tr>
                                                <th style="text-align: left"><input type="checkbox" name="checkall" id="checkall"></th>
                                                <th style="text-align: center">Action</th>
                                                <th style="text-align: center">ID</th>
                                                <th style="text-align: center">Good Receipt No</th>
                                                <th style="text-align: center">Purchase Order No</th>
                                                <th style="text-align: center">Vendor Code</th>
                                                <th style="text-align: center">Vendor Name</th>
                                                <th style="text-align: center">Delivery Date</th>
                                                <th style="text-align: center">Posting Date</th>
                                                <th style="text-align: center">Status</th>
                                                <th style="text-align: center">Created By</th>
                                                <th style="text-align: center">Approved By</th>
                                                <th style="text-align: center">Last Modified</th>
                                                <th style="text-align: center">Log</th>
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
                $('#fromDate').datepicker({autoclose:true});
                $('#toDate').datepicker({autoclose:true});

                showDataList();
                
                // untuk check all
                $("#checkall").click(function(){
                    if($(this).is(':checked')){
                        $(".check_delete").prop('checked', true);
                    }else{
                        $(".check_delete").prop('checked', false);
                    }
                });

                // end check all
                $("#deleteRecord").click(function(){
                    let deleteidArr=[];
                    let getTable = $("#tableWhole").DataTable();
                    $("input:checkbox[class=check_delete]:checked").each(function(){
                        deleteidArr.push($(this).val());
                    })

                    // mengecek ckeckbox tercheck atau tidak
                    if(deleteidArr.length > 0){
                        var confirmDelete = confirm("Do you really want to Delete records?");
                        if(confirmDelete == true){
                            $.ajax({
                                url:"<?php echo site_url('transaksi1/pofromvendor/deleteData');?>", //masukan url untuk delete
                                type: "post",
                                data:{deleteArr: deleteidArr},
                                success:function(res) {
                                    location.reload(true);
                                }
                            });
                        }
                    }
                });

                // ini adalah function versi ES6
                checkcheckbox = () => {
                    
                    const lengthcheck = $(".check_delete").length;
                    
                    let totalChecked = 0;
                    $(".check_delete").each(function(){
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

                deleteConfirm = (url)=>{
                    $('#btn-delete').attr('href', url);
	                $('#deleteModal').modal();
                }
            });

            function search(){
                const fromDate = $('#fromDate').val();
                const toDate = $('#toDate').val();
                const status = $('#status').val();

                showDataList();
            };

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

                dataTable = $('#tableWhole').DataTable({
                    "ordering":true, "paging": true, "searching":true,
                    "ajax": {
                        "url":"<?php echo site_url('transaksi1/pofromvendor/showListData');?>",
                        "type":"POST",
                        "data":{fDate: fromDate, tDate: toDate, stts: status}
                    },
                    "columns": [
                        {"data":"id_grpo_header", "className":"dt-center", render:function(data, type, row, meta){
                            rr=`<input type="checkbox" class="check_delete" id="chk_${data}" value="${data}" onclick="checkcheckbox();">`;
                            return rr;
                        }},
                        {"data":"id_grpo_header", "className":"dt-center", render:function(data, type, row, meta){
                            rr = `<div style="width:100px">
                                    <a href='<?php echo site_url('transaksi1/pofromvendor/printPdf/')?>${data}' target='_blank'><i class='icon-printer' title="Print"></i></a>&nbsp;
                                    ${freeze == 0 || reject == 1 ? `<a href='<?php echo site_url('transaksi1/pofromvendor/edit/')?>${data}' ><i class='icon-file-plus2' title="Edit"></i></a>&nbsp;` : ''}
                                  </div>`;
                            return rr;
                        }},
                        {"data":"id_grpo_header", "className":"dt-center"},
                        {"data":"grpo_no", "className":"dt-center"},
                        {"data":"po_no", "className":"dt-center"},
                        {"data":"kd_vendor"},
                        {"data":"nm_vendor"},
                        {"data":"delivery_date"},
                        {"data":"posting_date"},
                        {"data":"status"},
                        {"data":"id_user_input"},
                        {"data":"id_user_approved"},
                        {"data":"lastmodified"},
                        {"data":"integrated"}
                    ]
                });
            }
        </script>
	</body>
</html>