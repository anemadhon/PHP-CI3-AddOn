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
                            <legend class="font-weight-semibold"><i class="icon-search4 mr-2"></i>Search of Product Costing</legend>  
                        </div>
                        <div class="card-body">
                        <form action="#" method="POST">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Dari Tanggal</label>
                                        <div class="col-lg-3 input-group date">
                                            <input type="text" class="form-control" id="fromDate">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">
                                                    <i class="icon-calendar"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <label class="col-lg-2 col-form-label">Sampai Tanggal</label>
                                        <div class="col-lg-4 input-group date">
                                            <input type="text" class="form-control" id="toDate">
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
                                            <select class="form-control form-control-select2" name="status" id="status" data-live-search="true">
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
                    <div class="card">
                        <div class="card-header">
                            <legend class="font-weight-semibold"><i class="icon-list mr-2"></i>List of Product Costing</legend>
                            <a href="<?php echo site_url('transaksi1/productcosting/add') ?>" class="btn btn-primary"> Add New</a>
                            <input type="button" value="Delete" class="btn btn-danger" id="deleteRecord">
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12" style="overflow:auto">
                                    <table id="tableWhole" class="table table-striped" >
                                        <thead>
                                            <tr>
                                                <th style="text-align: left"><input type="checkbox" name="checkall" id="checkall"></th>
                                                <th style="text-align: center">Action</th>
                                                <th style="text-align: center">ID</th>
                                                <th style="text-align: center">No. Product</th>
                                                <th style="text-align: center">Name</th>
                                                <th style="text-align: center">Existing BOM</th>
                                                <th style="text-align: right">Qty</th>
                                                <th style="text-align: center">UOM</th>
                                                <th style="text-align: center">Created by</th>
                                                <th style="text-align: center">Created Date</th>
                                                <th style="text-align: center">Status</th>
                                                <th style="text-align: center">Approved Admin Date</th>
                                                <th style="text-align: center">Approved by</th>
                                                <th style="text-align: center">Status Kepala Departemen</th>
                                                <th style="text-align: center">Approved/Rejected Date</th>
                                                <th style="text-align: center">Kepala Departemen</th>
                                                <th style="text-align: center">Departemen</th>
                                                <th style="text-align: center">Status Kategori Approval</th>
                                                <th style="text-align: center">Approved/Rejected Date</th>
                                                <th style="text-align: center">Kategori Approver</th>
                                                <th style="text-align: center">Status Cost Control</th>
                                                <th style="text-align: center">Approved/Rejected Date</th>
                                                <th style="text-align: center">Cost Control</th>
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

                dataTable = $('#tableWhole').DataTable({
                    "ordering":true, "paging": true, "searching":true,
                    "ajax": {
                        "url":"<?php echo site_url('transaksi1/productcosting/showListData');?>",
                        "type":"POST",
                        "data":{fDate: fromDate, tDate: toDate, stts: status}
                    },
                    "columns": [
                        {"data":"id_prod_cost_header", "className":"dt-center", render:function(data, type, row, meta){
                            rr=`<input type="checkbox" class="check_delete" id="chk_${data}" value="${data}" onclick="checkcheckbox();">`;
                            return rr;
                        }},
                        {"data":"id_prod_cost_header", "className":"dt-center", render:function(data, type, row, meta){
                            rr = `<div style="width:100px">
                                    <a href='<?php echo site_url('transaksi1/productcosting/edit/')?>${data}' ><i class='icon-file-plus2' title="Edit"></i></a>&nbsp
                                    <a href='<?php echo site_url('transaksi1/productcosting/copy/')?>${data}' ><i class='icon-copy4' title="Copy"></i></a>&nbsp
                                    <a href='<?php echo site_url('transaksi1/productcosting/printXls/')?>${data}' target="_blank"><i class='icon-printer' title="Print Xls"></i></a>&nbsp
                                </div>`;
                            return rr;
                        }},
                        {"data":"id_prod_cost_header", "className":"dt-center"},
                        {"data":"prod_cost_no", "className":"dt-center"},
                        {"data":"product_name", "className":"dt-center"},
                        {"data":"existing_bom", "className":"dt-center"},
                        {"data":"product_qty"},
                        {"data":"product_uom", "className":"dt-center"},
                        {"data":"created_by", "className":"dt-center"},
                        {"data":"created_date", "className":"dt-center"},
                        {"data":"status", "className":"dt-center"},
                        {"data":"approval_admin_date", "className":"dt-center"},
                        {"data":"approved_by", "className":"dt-center"},
                        {"data":"status_head", "className":"dt-center"},
                        {"data":"approval_head_date", "className":"dt-center"},
                        {"data":"head_dept", "className":"dt-center"},
                        {"data":"dept", "className":"dt-center"},
                        {"data":"status_cat_approver", "className":"dt-center"},
                        {"data":"approval_cat_approver_date", "className":"dt-center"},
                        {"data":"cat_approver", "className":"dt-center"},
                        {"data":"status_cost_control", "className":"dt-center"},
                        {"data":"approval_cost_control_date", "className":"dt-center"},
                        {"data":"cost_control", "className":"dt-center"}
                    ]
                });
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
                    let getTable = $("#tableWhole").DataTable();
                    $("input:checkbox[class=check_delete]:checked").each(function(){
                        deleteidArr.push($(this).val());
                    })

                    // mengecek ckeckbox tercheck atau tidak
                    if(deleteidArr.length > 0){
                        var confirmDelete = confirm("Do you really want to Delete records?");
                        if(confirmDelete == true){
                            $.ajax({
                                url:"<?php echo site_url('transaksi1/productcosting/deleteData');?>", //masukan url untuk delete
                                type: "post",
                                data:{deleteArr: deleteidArr},
                                success:function(res) {
                                    location.reload(true);
                                    getTable.row($(this).closest("tr")).remove().draw();
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