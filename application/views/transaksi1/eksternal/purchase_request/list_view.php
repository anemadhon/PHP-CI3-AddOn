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
                    <div class="card" >
                        <div class="card-header">
                            <legend class="font-weight-semibold"><i class="icon-search4 mr-2"></i>Search of Purchase Request (PR)</legend>  
                        </div>
                        <div class="card-body">
                            <form action="#" method="POST">
                                <div class="row">
									<div class="col-md-12">
										<div class="form-group row">
											<label class="col-lg-3 col-form-label">Dari Tanggal</label>
											<div class="col-lg-3 input-group date">
												<input type="text" class="form-control" id="fromDate" autocomplete="off">
												<div class="input-group-prepend">
													<span class="input-group-text" id="basic-addon1">
														<i class="icon-calendar"></i>
													</span>
												</div>
											</div>
											<label class="col-lg-2 col-form-label">Sampai Tanggal</label>
											<div class="col-lg-4 input-group date">
												<input type="text" class="form-control" id="toDate" autocomplete="off">
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
												<select class="form-control form-control-select2" data-live-search="true" id="status" name="status" >
													<option value="">-- All --</option>
													<option value="2">Approved</option>
													<option value="1">Not Approved</option>
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
                        <div class="card-header">
                            <legend class="font-weight-semibold"><i class="icon-list mr-2"></i>List of Purchase Request (PR)</legend>
                            <a href="<?php echo site_url('transaksi1/purchase_request/add') ?>" class="btn btn-primary"> Add New</a>
                            <input type="button" value="Delete" class="btn btn-danger" id="deleteRecord">  
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
                                                <th style="text-align: center">Purchase Request (PR) No</th>
                                                <th style="text-align: center">Created Date</th>
                                                <th style="text-align: center">Delivery Date</th>
                                                <th style="text-align: center">Status</th>
                                                <th style="text-align: center">Created by</th>
                                                <th style="text-align: center">Approved by</th>
                                                <th style="text-align: center">Last Modified</th>
                                                <th style="text-align: center">PO</th>
                                                <th style="text-align: center">Log</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
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

                showListData();

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
                                url:"<?php echo site_url('transaksi1/purchase_request/deleteData');?>", 
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

                printPdf = (data)=>{
                    uri = "<?php echo site_url('transaksi1/purchase_request/printpdf/')?>"+data
                    window.open(uri);

                }

            });

            function onSearch(){
                const fromDate = $('#fromDate').val();
                const toDate = $('#toDate').val();
                const status = $('#status').val();

                showListData();
            }

            function showListData(){
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
                    "ordering":true,  "paging": true, "searching":true,
                    "ajax": {
                        "url":"<?php echo site_url('transaksi1/purchase_request/showAllData');?>",
                        "type":"POST",
                        "data":{fDate: fromDate, tDate: toDate, stts: status}
                    },
                    "columns": [
                        {"data":"id_pr_header", "className":"dt-center", render:function(data, type, row, meta){
                            rr=`<input type="checkbox" class="check_delete" id="chk_${data}" value="${data}" onclick="checkcheckbox();">`;
                            return rr;
                        }},
                        {"data":"id_pr_header", "className":"dt-center", render:function(data, type, row, meta){
                            rr = `<div style="width:100px">
										<a onClick="printPdf(${data})" href="#" ><i class='icon-printer' title="Print"></i></a>&nbsp;
                                        <a href='<?php echo site_url('transaksi1/purchase_request/edit/')?>${data}' ><i class='icon-file-plus2' title="Edit"></i></a>&nbsp;
                                    </div>`;
                            return rr;
                        }},
                        {"data":"id_pr_header"},
                        {"data":"pr_no1", "className":"dt-center"},
                        {"data":"created_date"},
                        {"data":"delivery_date"},
                        {"data":"status"},
                        {"data":"created_by"},
                        {"data":"approved_by"},
                        {"data":"last_modified"},
                        {"data":"po", "className":"dt-center", render:function(data, type, row, meta){ 

                                if(data.length > 0){
                                    rr = `<div class="for_po_${row['id_pr_header']}">${showAllPO(data, row['id_pr_header'])}</div>`;
                                }else{
                                    rr = '';
                                }
                                return rr;
                            }
                        },
                        {"data":"back"}
                    ]
                });
            }

            function showAllPO(nopo='',id){
                if (nopo) {
                    nopo.forEach((po)=> {                   
                        var url = "<?php echo site_url('transaksi1/purchase_request/printpdfPO/')?>"+po.DocEntry;
                        $(`<p><a href="${url}" target="_blank">${po.NoDoc}</a></p>`).appendTo(`.for_po_${id}`);
                    });
                }
                let div_po = $(`div.for_po_${id}`).html();
                if (div_po) {
                    let div_po_clear = div_po.slice(9);
                    $(`div.for_po_${id}`).html(div_po_clear)
                }
            }
        
        </script>
	</body>
</html>