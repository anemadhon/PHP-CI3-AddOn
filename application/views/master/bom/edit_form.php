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
					<form action="#" method="POST">
						<div class="card">
							<div class="card-body">
								<div class="row">
									<div class="col-md-12">
										<fieldset>
											<legend class="font-weight-semibold"><i class="icon-reading mr-2"></i>Edit BOM Item</legend>
											<div class="form-group row">
												<label class="col-lg-3 col-form-label">BOM Item Code:</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" readonly="" value="1ACO0117">
												</div>
											</div>

											<div class="form-group row">
												<label class="col-lg-3 col-form-label">BOM Item Description:</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" readonly="" value="Latte Cookies">
												</div>
											</div>
											
											<div class="form-group row">
												<label class="col-lg-3 col-form-label">BOM Quantity:</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" value="15.00 EA"> 
												</div>
											</div>
											
											<div class="text-right">
												<button type="submit" class="btn btn-primary">Save<i class="icon-paperplane ml-2"></i></button>
											</div>
										</fieldset>
									</div>
								</div>
							</div>
						</div> 

						<div class="card">
							<div class="card-header">
								<legend class="font-weight-semibold"><i class="icon-list mr-2"></i>List BOM</legend>
							</div>
							<div class="col-md-12">
								<div class="text-left">
									<input type="button" class="btn btn-primary" value="Add" id="addTable"> 
									<input type="button" value="Delete" class="btn btn-danger" id="deleteRecord"> 
								</div>
							</div>
							<div class="card-body">
								<table id="table-bom" class="table table-striped " style="width:100%">
									<thead>
										<tr>
											<tr>
												<th colspan="6" class="text-center">BOM ITEM</th>
											</tr>
											<th>*</th>
											<th>No</th>
											<th>Material No</th>
											<th>Material Desc</th>
											<th>Quantity</th>
											<th>Uom</th>
										</tr>
									</thead>
								</table>
							</div>
						</div>
					</form>
				</div>
				<?php  $this->load->view("_template/footer.php")?>
			</div>
		</div>
        <?php  $this->load->view("_template/js.php")?>
		<script>
            $(document).ready(function(){
                var table = $("#table-bom").DataTable({
				"ordering":false
			});
			count = 1;

			$("#addTable").on('click', function() {
				table.row.add([
					`<input type="checkbox"  value="dt_${count}" class="check_delete" id="dt_${count}" onclick="checkcheckbox();">`,
					count,
					`<select class="form-control form-control-select2" data-live-search="true">
						<option value="">Select Item</option>
						<option value="1">Pilih 1</option>
						<option value="2">Pilih 2</option>
					</select>`,
					``,
					'<input type="text" name="qty[]" id="qty[]">',
					'',
					'',
					'',
					''
				]).draw(false);
				count++;
			});

			$("#addTable").click();

                deleteConfirm = (url)=>{
                    $('#btn-delete').attr('href', url);
	                $('#deleteModal').modal();
                }
            });
        
        </script>
	</body>
</html>