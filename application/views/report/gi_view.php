<!DOCTYPE html>
<html lang="en">
	<head>
		<?php $this->load->view("_template/head.php")?>
		<style>
		.hide{
			display: none;
		}
		.bolded{
			font-weight: bold;
		}
		.visiblenone{
			visibility: hidden;
		}
		.dt-right{
			text-align:right;
		}
		</style>
	</head>
	<body>
	<?php $this->load->view("_template/nav.php")?>
		<div class="page-content">
			<?php $this->load->view("_template/sidebar.php")?>
			<div class="content-wrapper">
				<div class="content">
                    <div class="card">
                        <div class="card-body">
                            <form action="#" method="POST">
                                <div class="row">
                                    <div class="col-md-12">
                                        <fieldset>
                                            <legend class="font-weight-semibold"><i class="icon-reading mr-2"></i>Good Issue Report</legend>
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
												<label class="col-lg-3 col-form-label">Type</label>
												<div class="col-lg-9">
													<select class="form-control form-control-select2" data-live-search="true" id="type" name="type">
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
										</fieldset>
                                    </div>
								</div>	
								<br>
                            </form>
                        </div>
                    </div>  
					<div class="card hide">
						<div class="card-header">
                            <legend class="font-weight-semibold"><i class="icon-list mr-2"></i>List of Good Issue</legend>
                            <button onclick="printExcel()" class="btn btn-success"> Download To Excel</button>
                            
                        </div>
						<div class="card-body" >
							<div class="row">
								<div class="col-md-12" style="overflow: auto">
								
									<table class="table table-striped" id="tblReportInventory">
									<thead>
                                            <tr>
                                                <th style="text-align: center" rowspan="2">No.</th>
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
                                            </tr>
                                            <tr>
                                                <?php for ($i=1; $i <= $max_attempt['max_attempt']; $i++) { ?>
                                                    <th style="text-align: center">SAP Doc No.</th>
                                                    <th style="text-align: center">Doc Date</th>
                                                    <th style="text-align: right">Qty integrate</th>
                                                <?php } ?>
                                            </tr>
                                        </thead>
									</table>	
								</div>
							</div>
						</div>
					</div>                  
				</div>
				<?php $this->load->view("_template/footer.php")?>
			</div>
		</div>
		<?php $this->load->view("_template/js.php")?>
		<script>
		$(document).ready(function () {
			

			const date = new Date();
			const today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
			var optSimple = {
				format: 'yyyy-mm-dd',
				todayHighlight: true,
				autoclose: true
			};

			$('#fromDate').datepicker(optSimple);
			$('#toDate').datepicker(optSimple);

		});

		function onSearch(){

			if($('#fromDate').val() == '' || $('#toDate').val()== ''){
				alert('Silahkan Isi Tanggal Terlebih Dahulu');
				return false;
			}
			
			$(".card").removeClass('hide');
			const fromDate = $('#fromDate').val();
			const toDate = $('#toDate').val();
			const itemGroup = $('#type').val();

			showDataList();
		}

		function showDataList(){
			const obj = $('#tblReportInventory tbody tr').length;

			if(obj > 0){
				const dataTable = $('#tblReportInventory').DataTable();
				dataTable.destroy();
				$('#tblReportInventory > tbody > tr').remove();
			}    

			var element = document.getElementById("tblReportInventory");
  			element.classList.remove("sorting"); 

			const fDate = $('#fromDate').val();
			const tDate = $('#toDate').val();
			const type = $('#type').val();
			const arrfDate = fDate.split('-');
			const fromDate = arrfDate[0]+arrfDate[1]+arrfDate[2];
			const arrTDate = tDate.split('-');
			const toDate = arrTDate[0]+arrTDate[1]+arrTDate[2]; 

			let maxAttempt = '<?php echo $max_attempt["max_attempt"]; ?>'; 
			let additionalColumns = [
				{"data":"no", "className":"dt-center"},
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

			dataTable = $('#tblReportInventory').DataTable({
				"ordering":false,  
				"paging": true, 
				"searching":true,
				"ajax": {
					"url":"<?php echo site_url('report/goodissue/showAllData');?>",
					"type":"POST",
					"data":{type: type, fromDate:fromDate, toDate:toDate}
				},
				"columns": additionalColumns,
			});
		}

		function printExcel(){
			const fDate = $('#fromDate').val();
			const tDate = $('#toDate').val();
			const type = $('#type option:selected').val();
			const arrfDate = fDate.split('-');
			const fromDate = arrfDate[0]+arrfDate[1]+arrfDate[2];
			const arrTDate = tDate.split('-');
			const toDate = arrTDate[0]+arrTDate[1]+arrTDate[2];
			const uri = "<?php echo base_url()?>report/goodissue/printExcel/?frmDate="+fromDate
																	+"&toDate="+toDate
																	+"&type="+type
			window.location= uri;
		}
		</script>
	</body>
</html>