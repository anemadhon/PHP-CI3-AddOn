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
                            <legend class="font-weight-semibold"><i class="icon-search4 mr-2"></i>Search of GR from Central Kitchen</legend>  
                        </div>
                        <div class="card-body">
                        
                        <form action="#" method="POST" class="form-horizontal">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Dari Tanggal</label>
                                        <div class="col-lg-3 input-group date">
                                            <input type="text" class="form-control" id="fromDate" autocomplete="off" readonly>
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">
                                                    <i class="icon-calendar"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <label class="col-lg-2 col-form-label">Sampai Tanggal</label>
                                        <div class="col-lg-4 input-group date">
                                            <input type="text" class="form-control" id="toDate" autocomplete="off" readonly>
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
                                            <select class="form-control form-control-select2" id="status" data-live-search="true">
                                                <option value="">-- All --</option>
                                                <option value="2">Approved</option>
                                                <option value="1">Not Approved</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="text-right">
                                    <button type="button" class="btn btn-primary" onclick="search()">Search<i class="icon-search4  ml-2"></i></button>
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
                            <legend class="font-weight-semibold"><i class="icon-list mr-2"></i>List of GR from Kitchen Sentul</legend>
                            <?php if (($isFreeze == 0 && $isMgr == 0) || $isReject == 1):?>
                            <a href="<?php echo site_url('transaksi1/grfromkitchensentul/add') ?>" class="btn btn-primary"> Add New</a>
                            <input type="button" value="Delete" class="btn btn-danger" id="deleteRecord">  
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12" style="overflow:auto">
                                    <table id="tableWhole" class="table table-striped" style="width:100%" >
                                        <thead>
                                            <tr>
                                                <th style="text-align: left"><input type="checkbox" name="checkall" id="checkall"></th>
                                                <th style="text-align: center">Action</th>
                                                <th style="text-align: center">ID</th>
                                                <th style="text-align: center">Transfer Slip Number</th>
                                                <th style="text-align: center">Good Receipt No</th>
                                                <th style="text-align: center">Delivery Date</th>
                                                <th style="text-align: center">Posting Date</th>
                                                <th style="text-align: center">Status</th>
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

                $('#tableWhole').dataTable({
                    "ordering":true,  
                    "paging": true, 
                    "searching":true,
                    "ajax": {
                        "url": "<?php echo site_url('transaksi1/grfromkitchensentul/showListData');?>",
                        "type": "POST",
                        "data":{fDate: fromDate, tDate: toDate, stts: status}
                    },
                    "columnDefs": [
			 			{"className": "dt-center", "targets": "_all"}
			 		],
                     "columns": [
                        {"data":"no", "className":"dt-center", render:function(data, type, row, meta){
                            rr=`<input type="checkbox" class="check_delete" value="${data}">`;
                            return rr;
                        }},
                        {"data":"action", "className":"dt-center", render:function(data, type, row, meta){
                            let urlEdit = '<?php echo site_url('transaksi1/grfromkitchensentul/edit/') ?>';
                            let urlPrint = '<?php echo site_url('transaksi1/grfromkitchensentul/printpdf/') ?>';
                            rr = `<div style="width:100px">
                                    <a href=${urlPrint}${data} target="_blank"><i class='icon-printer' title="Print"></i></a>&nbsp;
                                    ${freeze == 0 || reject == 1 ? `<a href=${urlEdit}${data}><i class='icon-file-plus2' title="Edit"></i></a>&nbsp;` : ''}
                                </div>`;
                                return rr;
                        }},
                        {"data":"id_grpodlv_header", "className":"dt-center"},
                        {"data":"do_no1"},
                        {"data":"grpodlv_no1"},
                        {"data":"delivery_date"},
                        {"data":"posting_date"},
                        {"data":"status"},
                        {"data":"integrated"}
                    ]
                })
            }
            $(function(){
                
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
                    let dataTable = $("#tableWhole").DataTable();
                    $("input:checkbox[class=check_delete]:checked").each(function(){
                        deleteidArr.push($(this).val());
                    })

                    // mengecek ckeckbox tercheck atau tidak
                    if(deleteidArr.length > 0){
                        var confirmDelete = confirm("Do you really want to Delete records?");
                        if(confirmDelete == true){
                            $.ajax({
                                url:"<?php echo site_url('transaksi1/grfromkitchensentul/deleteData');?>", //masukan url untuk delete
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
        </script>
	</body>
</html>