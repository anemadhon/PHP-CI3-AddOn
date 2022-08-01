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
                        <div class="card-body">
                            <form action="#" method="POST">
                                <div class="row">
                                    <div class="col-md-12">
                                        <fieldset>
                                            <legend class="font-weight-semibold"><i class="icon-reading mr-2"></i>Edit Good Issue</legend>
                                            <div class="form-group row">
												<label class="col-lg-3 col-form-label">ID Transaksi:</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" readonly="" value="42159">
												</div>
											</div>

                                            <div class="form-group row">
												<label class="col-lg-3 col-form-label">Issue No:</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" readonly="" value="1630286">
												</div>
											</div>

                                            <div class="form-group row">
												<label class="col-lg-3 col-form-label">Outlet:</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" readonly="" value="WMSICKST - Cikarang">
												</div>
											</div>

                                            <div class="form-group row">
												<label class="col-lg-3 col-form-label">Storage Location:</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" readonly="" value="WMSICKST - MSI Cikarang">
												</div>
											</div>

                                            <div class="form-group row">
												<label class="col-lg-3 col-form-label">Cost Center:</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" readonly="" value="MSI0078 - MSI OPERASIONAL">
												</div>
											</div>

                                            <div class="form-group row">
												<label class="col-lg-3 col-form-label">Status:</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" readonly="" value="Approved">
												</div>
											</div>

                                            <div class="form-group row">
												<label class="col-lg-3 col-form-label">Material Group:</label>
												<div class="col-lg-9">
                                                    <input type="text" class="form-control" readonly="" value="all">
												</div>
											</div>

                                            <div class="form-group row">
												<label class="col-lg-3 col-form-label">Posting Date:</label>
												<div class="col-lg-9">
                                                   <input type="text" class="form-control" readonly="" value="08-10-2019">
												</div>
											</div>
											
											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Note:</label>
												<div class="col-lg-9">
                                                    <input type="text" class="form-control" value="Integrasi dari POS Ravintola">
												</div>
											</div>

                                            <div class="text-right">
                                                <button type="submit" class="btn btn-primary">Cancel<i class="icon-paperplane ml-2"></i></button>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div> 

					<div class="card">
                        <div class="card-header">
                            <legend class="font-weight-semibold"><i class="icon-list mr-2"></i>List Manajemen Pengguna</legend>
                        </div>
                        <div class="card-body">
                            <table id="table-manajemen" class="table table-striped " style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="text-align: left">*</th>
                                        <th>Issue No</th>
                                        <th>Material Desc</th>
                                        <th>In Whs Quantity</th>
                                        <th>Quantity</th>
                                        <th>Uom</th>
                                        <th>Reason</th>
                                        <th>Other Reason</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
				</div>
				<?php  $this->load->view("_template/footer.php")?>
			</div>
		</div>
        <?php  $this->load->view("_template/js.php")?>
		<script>
            $(document).ready(function(){
                $('#table-manajemen').DataTable({
                    "ordering":false,  "paging": false, "searching":true,
                    "ajax": {
                        "url":"<?php echo site_url('master/integration/goodIssueData');?>",
                        "type":"POST"
                    },
                    "columns": [
                        {"data":"no"},
                        {"data":"issue_no"},
                        {"data":"material"},
                        {"data":"whs_qty"},
                        {"data":"qty"},
                        {"data":"uom"},
                        {"data":"reason"},
                        {"data":"other_reason"}
                    ]
                });

                deleteConfirm = (url)=>{
                    $('#btn-delete').attr('href', url);
	                $('#deleteModal').modal();
                }
            });
        
        </script>
	</body>
</html>