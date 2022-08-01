<!DOCTYPE html>
<html lang="en">
	<head>
		<?php  $this->load->view("_template/head.php")?>
		<style>
		.hide{
			display: none;
		}
		</style>
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
                                            <legend class="font-weight-semibold"><i class="icon-reading mr-2"></i>Bincard Report</legend>
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
												<label class="col-lg-3 col-form-label">Warehouse</label>
												<div class="col-lg-9">
													<select class="form-control form-control-select2" data-live-search="true" id="warehouse" name="warehouse">
														<option value="">Select Item</option>
														<?php foreach($warehouse as $key=>$val):?>
															<option value="<?=$val['WhsCode']?>"><?=$val['WhsName']?></option>
														<?php endforeach;?>
													</select>
												</div>
											</div>
                                            
                                            <div class="form-group row">
												<label class="col-lg-3 col-form-label">Item Group</label>
												<div class="col-lg-9">
												<select class="form-control form-control-select2" data-live-search="true" id="itemGroup" name="itemGroup">
													<option value="0">Pilih Material</option>
													<?php foreach($itemGroup as $key=>$val):?>
														<option value="<?=$val['DocEntry']?>"><?=$val['ItemCode'].'-'.$val['ItemName']?></option>
													<?php endforeach;?>
												</select>
												</div>
											</div>

											<div class="form-group row">
												<label class="col-lg-3 col-form-label"></label>
												<div class="col-lg-9">
												<select class="form-control form-control-select2" data-live-search="true" id="itemGroup2" name="itemGroup2">
													<option value="0">Pilih Material</option>
													<?php foreach($itemGroup as $key=>$val):?>
														<option value="<?=$val['DocEntry']?>"><?=$val['ItemCode'].'-'.$val['ItemName']?></option>
													<?php endforeach;?>
												</select>
												</div>
											</div>

                                            <div class="text-right">
												<button type="button" class="btn btn-primary" onclick="onSearch()">Search<i class="icon-search4  ml-2"></i></button>
											</div>
											

											
                                        </fieldset>
                                    </div>
								</div>	
                            </form>
                        </div>
					</div> 
					<div class="card hide">
						<div class="card-header">
                            <legend class="font-weight-semibold"><i class="icon-list mr-2"></i>List of Onhand Report</legend>
                            <button onclick="printExcel()" class="btn btn-success"> Download To Excel</button>
                            
                        </div>
							<div class="card-body">
							<div class="row">
									<div class="col-md-12" style="overflow: auto">
										<table class="table table-striped" id="tblReportBincard">
											<thead>
												<tr>
													<th>No</th>
													<th>Code</th>
													<th>Description</th>
													<th style="text-align: center">GR From CK</th>
													<th style="text-align: center">GR From Outlet</th>
													<th style="text-align: center">GR Return</th>
													<th style="text-align: center">Total In</th>
													<th style="text-align: center">ISSUE Transfer Outlet</th>
													<th style="text-align: center">ISSUE Return Out</th>
													<th style="text-align: center">Total Out</th>
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
		<?php  $this->load->view("_template/js.php")?>
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
			$(".card").removeClass('hide');
			
			const fromDate = $('#fromDate').val();
			const toDate = $('#toDate').val();
			const warehouse = $('#warehouse').val();
			const itemGroup = $('#itemGroup').val();
			const itemGroup2 = $('#itemGroup2').val();

			showDataList();
		}

		function showDataList(){
			const obj = $('#tblReportBincard tbody tr').length;

			if(obj > 0){
				const dataTable = $('#tblReportBincard').DataTable();
				dataTable.destroy();
				$('#tblReportBincard > tbody > tr').remove();
			}     

			const fromDate = $('#fromDate').val();
			const toDate = $('#toDate').val();
			const warehouse = $('#warehouse').val();
			const itemGroup = $('#itemGroup').val(); 
			const itemGroup2 = $('#itemGroup2').val();   

			dataTable = $('#tblReportBincard').DataTable({
				"ordering":false,  
				"paging": true, 
				"searching":true,
				"pageLength" : 10,
				"processing": true,
            	"serverSide": true,
				"ajax": {
					"url":"<?php echo site_url('report/bincard/showAllData');?>",
					"type":"POST",
					"data":{item_Group: itemGroup, item_Group2: itemGroup2, fromDate:fromDate, toDate:toDate, whs:warehouse}
				},
				"columns": [
					{"data":"no", "className":"dt-center"},
					{"data":"itemcode", "className":"dt-center"},
					{"data":"ItemName", "className":"dt-center"},
					{"data":"qty_ck", "className":"dt-center"},
					{"data":"qty_fo", "className":"dt-center"},
					{"data":"qty_retin", "className":"dt-center"},
					{"data":"total_in", "className":"dt-center"},
					{"data":"qty_to", "className":"dt-center"},
					{"data":"qty_ro", "className":"dt-center"},
					{"data":"total_out", "className":"dt-center"}
				],
				columnDefs: [
					{ targets: 2, width: '100%' },
				]
			});
		}
		</script>
	</body>
</html>