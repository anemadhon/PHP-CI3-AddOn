<!DOCTYPE html>
<html lang="en">
	<head>
		<?php  $this->load->view("_template/head.php")?>
		<style>
		.hide{
			display: none;
		}
		.float_filter{
			float: right !important;
		}

		.float_length{
			float: left !important;
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
                                            <legend class="font-weight-semibold"><i class="icon-reading mr-2"></i>Onhand Report</legend>
                                                                                        
                                            <div class="form-group row">
												<label class="col-lg-3 col-form-label">Item Group</label>
												<div class="col-lg-9">
												<select class="form-control form-control-select2" data-live-search="true" id="itemGroup" name="itemGroup">
													<option value="all">-- All --</option>
														<?php foreach($itemGroup as $key=>$val):?>
															<option value="<?=$val['ItmsGrpNam']?>"><?=$val['ItmsGrpNam']?></option>
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
								<fieldset>
									<table class="table table-striped" id="tblReportOnhand">
										<thead>
											<tr>
												<th style="text-align: center">No</th>
												<th style="text-align: center">Code</th>
												<th style="text-align: center">Description</th>
												<th style="text-align: center">On Hand</th>
												<th style="text-align: center">Uom</th>
												<!--<th style="text-align: center">Item Cost</th>
												<th style="text-align: center">Amount</th>-->
											</tr>
										</thead>
									</table>
								<fieldset>	
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
				format: 'dd-mm-yyyy',
				todayHighlight: true,
				orientation: 'bottom right',
				autoclose: true
			};

			$('#fromDate').datepicker(optSimple);
			$('#toDate').datepicker(optSimple);

		});

		function printExcel(){
			const itemGroup = $('#itemGroup').val();
			const uri = "<?php echo base_url()?>report/onhand/printExcel/?item_group="+itemGroup
			window.location= uri;
		}

		function onSearch(){
			$(".card").removeClass('hide');
			const itemGroup = $('#itemGroup').val();

			showDataList(itemGroup);
		}

		function showDataList(itemGroup){
                const obj = $('#tblReportOnhand tbody tr').length;

                if(obj > 0){
                    const dataTable = $('#tblReportOnhand').DataTable();
                    dataTable.destroy();
                    $('#tblReportOnhand > tbody > tr').remove();
                    
                }         

				$("#tblReportOnhand_filter").addClass("float_filter");
				$("#tblReportOnhand_length").addClass("float_length");
				

                dataTable = $('#tblReportOnhand').DataTable({
                    "ordering":true,  "paging": true, "searching":true,
                    "ajax": {
                        "url":"<?php echo site_url('report/onhand/showAllData');?>",
                        "type":"POST",
                        "data":{item_Group: itemGroup}
                    },
                    "columns": [
                        {"data":"no", "className":"dt-center"},
                        {"data":"code", "className":"dt-center"},
                        {"data":"Description", "className":"dt-center"},
                        {"data":"OnHand", "className":"dt-center"},
						{"data":"Uom", "className":"dt-center"},
                        //{"data":"ItemCost", "className":"dt-center"},
                        //{"data":"Amount", "className":"dt-center"}
                    ]
                });
            }
		</script>
	</body>
</html>