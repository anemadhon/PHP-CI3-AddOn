<!DOCTYPE html>
<html lang="en">
	<head>
		<?php  $this->load->view("_template/head.php")?>
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
                                            <legend class="font-weight-semibold"><i class="icon-reading mr-2"></i>Inventory Audit</legend>
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
												<label class="col-lg-3 col-form-label">Item Group</label>
												<div class="col-lg-9">
												<select class="form-control form-control-select2" data-live-search="true" id="itemGroup" name="itemGroup"  onchange="showMatrialDetail(this.value)">
														<option value="">-- All --</option>
														<?php foreach($itemGroup as $key=>$val):?>
															<option value="<?=$val['ItmsGrpNam']?>"><?=$val['ItmsGrpNam']?></option>
														<?php endforeach;?>
													</select>
												</div>
											</div>

											<div class="form-group row">
												<label class="col-lg-3 col-form-label">From Item</label>
												<div class="col-lg-9">
													<select class="form-control form-control-select2" data-live-search="true" id="frmItem" name="frmItem">
														<option value="">-- All --</option>
													</select>
												</div>
											</div>

											<div class="form-group row">
												<label class="col-lg-3 col-form-label">To Item</label>
												<div class="col-lg-9">
													<select class="form-control form-control-select2" data-live-search="true" id="toItem" name="toItem">
														<option value="">-- All --</option>
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
                            <legend class="font-weight-semibold"><i class="icon-list mr-2"></i>List of Inventory Audit</legend>
                            <button onclick="printExcel()" class="btn btn-success"> Download To Excel</button>
                            
                        </div>
						<div class="card-body" >
							<div class="row">
								<div class="col-md-12" style="overflow: auto">
								
									<table class="table table-striped" id="tblReportInventory">
										<thead>
											<tr>
												<th>No</th>
												<th>Item Code</th>
												<th>Description</th>
												<th>Add On Doc.No</th>
												<th>SAP Doc.No</th>
												<th>Posting Date</th>
												<th>System Date</th>
												<th>Quantity</th>
											<!--	<th>Cost</th> 
												<th>Trans Value</th> -->
												<th>Cummulative QTY</th> 
											<!--	<th>Cummulative Value</th> -->
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

		function showMatrialDetail(cboMaterial){
			const fromItem = $('#frmItem');
			const toItem = $('#toItem');
			$.ajax({
				url: "<?php echo site_url('report/inventory/getdataDetailMaterial');?>",
				type: "POST",
				data: {
					matGroup: cboMaterial
				},
				success:function(res) {
					optData = JSON.parse(res);
					fromItem.empty();
					toItem.empty();
					$("<option />", {value:'', text:'--All--'}).appendTo(fromItem);
					$("<option />", {value:'', text:'--All--'}).appendTo(toItem);
					optData.forEach((val)=>{						
						$("<option />", {value:val.MATNR, text:val.MAKTX +' - '+ val.MATNR+' - '+ ((val.UNIT) ? val.UNIT : val.UNIT1)}).appendTo(fromItem);
						$("<option />", {value:val.MATNR, text:val.MAKTX +' - '+ val.MATNR+' - '+ ((val.UNIT) ? val.UNIT : val.UNIT1)}).appendTo(toItem);
					})
				}
			});		
		}

		function onSearch(){

			if($('#fromDate').val() == '' || $('#toDate').val()== ''){
				alert('Silahkan Isi Tanggal Terlebih Dahulu');
				return false;
			}
			
			$(".card").removeClass('hide');
			const fromDate = $('#fromDate').val();
			const toDate = $('#toDate').val();
			const itemGroup = $('#itemGroup').val();
			const fromItem = $('#frmItem').val();
			const toItem = $('#toItem').val();

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
			const itemGroup = $('#itemGroup').val();
			const fromItem = $('#frmItem').val();
			const toItem = $('#toItem').val();
			const arrfDate = fDate.split('-');
			const fromDate = arrfDate[0]+arrfDate[1]+arrfDate[2];
			const arrTDate = tDate.split('-');
			const toDate = arrTDate[0]+arrTDate[1]+arrTDate[2]; 

			dataTable = $('#tblReportInventory').DataTable({
				"ordering":false,  
				"paging": true, 
				"searching":true,
				"ajax": {
					"url":"<?php echo site_url('report/inventory/showAllData');?>",
					"type":"POST",
					"data":{item_Group: itemGroup, fromDate:fromDate, toDate:toDate, fItem:fromItem,tItem:toItem}
				},
				"columns": [
					{"data":"no", "className":"dt-center"},
					{"data":"ItemCode", render:function(data, type, row, meta){
						className = row['fg'] == '1' ? 'visiblenone' :'';
						rr = `<span class="${className}">${data}</span>`;
						return rr;
						}
					},
					{"data":"ItemName", render:function(data, type, row, meta){
						className = row['fg'] == '1' ? 'visiblenone' :'';
						rr = `<span class="${className}">${data}</span>`;
						return rr;
						}
					},
					{"data":"AddOnDocNo", "className":"dt-center"},
					{"data":"SAPDocNo", "className":"dt-center"},
					{"data":"docdate", "className":"dt-center"},
					{"data":"SystemDate", "className":"dt-center"},
					{"data":"quantity", "className":"dt-right"},
					// {"data":"cost", "className":"dt-right"},
					//{"data":"transvalue", "className":"dt-right"}, 
					{"data":"CummulativeQty", "className":"dt-right"}, 
					//{"data":"CummulativeValue", "className":"dt-right"}, 
				],
			});
		}

		function printExcel(){
			const fDate = $('#fromDate').val();
			const tDate = $('#toDate').val();
			const itemGroup = $('#itemGroup').val();
			const fromItem = $('#frmItem').val();
			const toItem = $('#toItem').val();
			const arrfDate = fDate.split('-');
			const fromDate = arrfDate[0]+arrfDate[1]+arrfDate[2];
			const arrTDate = tDate.split('-');
			const toDate = arrTDate[0]+arrTDate[1]+arrTDate[2];
			const uri = "<?php echo base_url()?>report/inventory/printExcel/?frmDate="+fromDate
																	+"&toDate="+toDate
																	+"&itemGroup="+itemGroup
																	+"&fromItem="+fromItem
																	+"&toItem="+toItem
			window.location= uri;
		}
		</script>
	</body>
</html>