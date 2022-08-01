<!DOCTYPE html>
<html lang="en">
	<head>
		<?php  $this->load->view("_template/head.php")?>
		<style>
			th{
				text-align:center;
			}
			td{
				text-align:center;
			}
		</style>
		<style>
			.after-submit {
				display: none;
			}
			#load,
			#load:before,
			#load:after {
				background: #777;
				-webkit-animation: load1 1s infinite ease-in-out;
				animation: load1 1s infinite ease-in-out;
				width: 1em;
				height: 4em;
			}
			#load {
				color: #777;
				text-indent: -9999em;
				margin: 88px auto;
				position: relative;
				font-size: 11px;
				-webkit-transform: translateZ(0);
				-ms-transform: translateZ(0);
				transform: translateZ(0);
				-webkit-animation-delay: -0.16s;
				animation-delay: -0.16s;
			}
			#load:before,
			#load:after {
				position: absolute;
				top: 0;
				content: '';
			}
			#load:before {
				left: -1.5em;
				-webkit-animation-delay: -0.32s;
				animation-delay: -0.32s;
			}
			#load:after {
				left: 1.5em;
			}
			@-webkit-keyframes load1 {
				0%,
				80%,
				100% {
					box-shadow: 0 0;
					height: 4em;
				}
				40% {
					box-shadow: 0 -2em;
					height: 5em;
				}
			}
			@keyframes load1 {
				0%,
				80%,
				100% {
					box-shadow: 0 0;
					height: 4em;
				}
				40% {
					box-shadow: 0 -2em;
					height: 5em;
				}
			}
		</style>
		<style>
			#indicatorCosting {
				padding: 2px;
			}
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
                    <form action="#" method="POST" autocomplete="off">
						<div class="card">
							<div class="card-body">
								<div class="row">
									<div class="col-md-12">
										<fieldset>
											<legend class="font-weight-semibold"><i class="icon-reading mr-2"></i>Copy Product Costing</legend>
											<div class="form-group row">
												<label class="col-lg-3 col-form-label">No. Product</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" placeholder="(Auto Generate After Submiting Document)" readOnly>
												</div>
											</div>

											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Product</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" name="doc_status" id="docStatus" value="<?php echo $pc['existing_bom_code'] ? 'Existing' : 'New' ?>" readOnly>
													<input type="hidden" id="idProdCost" value="<?php echo $pc['id_prod_cost_header'] ?>">
												</div>
											</div>

											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Costing Type</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" id="productType" value="<?php echo $pc['product_type'] == 1 ? 'WP' : 'Finish Goods' ?>" readOnly>
												</div>
											</div>
											
											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Category</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" name="category" id="category" value="<?php echo $pc['category_name'] ?>" readOnly>
												</div>
												<input type="hidden" id="qFactorSAP" value="<?php echo $pc['q_factor_sap'] ?>">
												<input type="hidden" id="minCostSAP" value="<?php echo $pc['min'] ?>">
												<input type="hidden" id="maxCostSAP" value="<?php echo $pc['max'] ?>">
												<input type="hidden" id="catAppSAP" value="<?php echo $pc['category_approver'] ?>">
												<input type="hidden" id="categoryCode" value="<?php echo $pc['category_code'] ?>">
											</div>

											<?php if($pc['existing_bom_code']) :?>
											<div class="form-group row" id="existingCost">
												<label class="col-lg-3 col-form-label">Existing Bom</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" name="existing_bom" id="existingBom" value="<?php echo $pc['existing_bom_code'].' - '.$pc['existing_bom_name'] ?>" readOnly>
												</div>
											</div>
											<?php endif; ?>

											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Name</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" name="product_name" id="productName" value="<?php echo $pc['product_name'] ?>" readOnly <?php echo $pc['status'] == 2 ? 'readOnly' : '' ?>>
												</div>
											</div>

											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Qty Produksi</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" name="product_qty" id="productQty" value="<?php echo $pc['product_qty'] ?>" readOnly>
												</div>
											</div>	

											<div class="form-group row">
												<label class="col-lg-3 col-form-label">UOM</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" name="product_uom" id="productUom" value="<?php echo $pc['product_uom'] ?>" <?php echo $pc['existing_bom_code'] || $pc['status'] == 2 ? 'readOnly' : '' ?>>
												</div>
											</div>

											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Posting Date</label>
												<div class="col-lg-9 input-group date">
													<input type="text" class="form-control" id="postDate" value="<?php echo date('d-m-Y', strtotime($pc['posting_date'])) ?>" readonly autocomplate="off">
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
													<input type="text" class="form-control" id="status" value="<?php echo ($pc['status'] == 1 || $pc['status_head'] === 0 || $pc['status_cat_approver'] === 0 || $pc['status_cost_control'] === 0) ? 'Not Approved' : 'Approved' ?>" readOnly>
													<input type="hidden" id="statusInt" value="<?php echo $pc['status'] ?>">
												</div>
											</div>
											
											<?php if ($pc['status'] == 2 || $pc['status_head'] === 0) : ?>
											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Head of Department</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" id="statusHead" value="<?php echo ($pc['status'] == 2 && $pc['status_head'] == 2 && $pc['status_cat_approver'] !== 0 && $pc['status_cost_control'] !== 0) ? 'Approved' : ($pc['status_head'] === 0 ? 'Rejected' : 'Not Approved') ?>" readOnly>
												</div>
											</div>
											<?php endif; ?>
											
											<?php if (($pc['status'] == 2 && $pc['status_head'] == 2 && $pc['product_type'] == 2) || $pc['status_cat_approver'] === 0) : ?>
											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Category Approver</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" id="statusCatApp" value="<?php echo ($pc['status'] == 2 && $pc['status_head'] == 2 && $pc['status_cat_approver'] == 2 && $pc['status_cost_control'] !== 0) ? 'Approved' : ($pc['status_cat_approver'] === 0 ? 'Rejected' : 'Not Approved') ?>" readOnly>
												</div>
											</div>
											<?php endif; ?>
											
											<?php if (($pc['status'] == 2 && $pc['status_head'] == 2 && $pc['status_cat_approver'] == 2 && $pc['product_type'] == 2) || $pc['status_cost_control'] === 0) : ?>
											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Cost Control</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" id="statusCostControl" value="<?php echo $pc['status'] == 2 && $pc['status_head'] == 2 && $pc['status_cat_approver'] == 2 && $pc['status_cost_control'] == 2 ? 'Approved' : ($pc['status_cost_control'] === 0 ? 'Rejected' : 'Not Approved') ?>" readOnly>
												</div>
											</div>
											<?php endif; ?>
											
											<?php if (($pc['status_head'] === 0 || $pc['status_cat_approver'] === 0 || $pc['status_cost_control'] === 0) && $pc['reject_reason']) : ?>
											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Reject Reason</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" value="<?php echo $pc['reject_reason']?>" readOnly>
												</div>
											</div>
											<?php endif; ?>
											
											<div class="form-group row wp">
												<label class="col-lg-3 col-form-label">Selling Price (include Tax)</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" name="product_sell_price" id="productSellPrice" value="<?php echo $pc['product_selling_price'] ?>" readOnly>
												</div>
											</div>
											
											<div class="form-group row wp">
												<label class="col-lg-3 col-form-label">Product Costing</label>
												<div class="col-lg-9">
													<p class="mt-1"><span id="percentageCosting"><?php echo $pc['product_percentage']?></span> <span id="indicatorCosting"></span></p>
												</div>
											</div>

											<div class="text-right" id="after-submit" style="display: none;">
												<button type="button" class="btn btn-primary" name="save" id="save" onclick="addDatadb()">Copy <i class="icon-copy4 ml-2"></i></button>
											</div>
											
										</fieldset>
									</div>
								</div>
							</div>
						</div> 
						<div id="load" style="display:none"></div>  

						<div class="card">
							<div class="card-body">
								<div class="row">
									<div class="col-md-12">
										<div class="text-left">
											<p>Total Ingredients Cost : <span id="totAllIngCost">0</span></p>
											<p>Total Packaging Cost : <span id="totAllPackCost">0</span></p>
											<p class="wp">Q Factor : <span id="qFactorResult">0</span></p>
											<p>Total Product Cost : <span id="totProdCost">0</span></p>
											<p>Total Product Cost / Qty Produksi: <span id="totProdCostDivQtyProd">0</span></p>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="card">
							<div class="card-body">
								<div class="row">
									<legend class="font-weight-semibold"><i class="icon-list mr-2"></i>List Item Ingredients</legend>
									<div class="col-md-8 mb-2 after-doc" style="display: none;">
										<div class="text-left">
											<select name="item_group_ing" id="itemGroupIng" class="form-control form-control-select2" data-live-search="true">
												<option value="">Select Item Group</option>
												<option value="all">All</option>
											</select>
										</div>
									</div>
									<div class="col-md-12" style="overflow: auto" >
										<table class="table table-striped" id="tblItemIngredients">
											<thead>
												<tr>
													<th><input type="checkbox" name="checkall_ing" id="checkallIng"></th>
													<th>No</th>
													<th>Item Code</th>
													<th>Item Desc</th>
													<th>UOM</th>
													<th>Unit Cost (include Tax 10%)</th>
													<th>Quantity</th>
													<th>Total Cost</th>
												</tr>
											</thead>
											<tbody></tbody>
										</table>
									</div>
								</div>
							</div>
						</div> 

						<div class="card">
							<div class="card-body">
								<div class="row">
									<legend class="font-weight-semibold"><i class="icon-list mr-2"></i>List Item Packaging</legend>
									<div class="col-md-8 mb-2 after-doc" style="display: none;">
										<div class="text-left">
											<select name="item_group_pack" id="itemGroupPack" class="form-control form-control-select2" data-live-search="true">
												<option value="">Select Item Group</option>
												<option value="all">All</option>
											</select>
										</div>
									</div>
									<div class="col-md-12" style="overflow: auto" >
										<table class="table table-striped" id="tblItemPackaging">
											<thead>
												<tr>
													<th><input type="checkbox" name="checkall_pack" id="checkallPack"></th>
													<th>No</th>
													<th>Item Code</th>
													<th>Item Desc</th>
													<th>UOM</th>
													<th>Unit Cost (include Tax 10%)</th>
													<th>Quantity</th>
													<th>Total Cost</th>
												</tr>
											</thead>
											<tbody></tbody>
										</table>
									</div>
								</div>
							</div>
						</div> 
                    </form>          
				</div>
				<?php $this->load->view("_template/footer.php")?>
			</div>
		</div>
        <?php  $this->load->view("_template/js.php")?>
		<script>
			$(document).ready(function(){

				$.post("<?php echo site_url('transaksi1/productcosting/showMatrialGroupIng');?>",(data) => {
					const optData = JSON.parse(data);
					optData.matrialGroupIng.forEach((val)=>{						
						$("<option />", {value:val.ItmsGrpNam, text:val.ItmsGrpNam, desc:val.ItmsGrpNam}).appendTo($('#itemGroupIng'));
					})
					$("<option />", {value:1, text:'Costing WP', desc:'Costing WP'}).appendTo($('#itemGroupIng'));
					$("<option />", {value:2, text:'Costing Finish Good', desc:'Costing Finish Good'}).appendTo($('#itemGroupIng'));
				});
				
				$.post("<?php echo site_url('transaksi1/productcosting/showMatrialGroupPack');?>",(data) => {
					const optData = JSON.parse(data);
					optData.matrialGroupPack.forEach((val)=>{						
						$("<option />", {value:val.ItmsGrpNam, text:val.ItmsGrpNam, desc:val.ItmsGrpNam}).appendTo($('#itemGroupPack'));
					})
				});

				const date = new Date();
				const today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
				var optSimple = {
					format: 'dd-mm-yyyy',
					todayHighlight: true,
					orientation: 'bottom right',
					autoclose: true
				};

				if ($('#productType').val() == 'Finish Goods') {
					$('.wp').show();
				} else {
					$('.wp').hide();
				}

				$('#productQty').val(parseFloat($('#productQty').val()).toLocaleString(('en-US'), {minimumFractionDigits: 2, maximumFractionDigits: 2}))
				
				$('#productSellPrice').val(parseFloat($('#productSellPrice').val()).toLocaleString(('en-US'), {minimumFractionDigits: 2, maximumFractionDigits: 2}))

				$('#tblItemIngredients').DataTable({
					"ordering":false, "paging": false, "searching":true,
					drawCallback: function() {
						$('.form-control-select2').select2();
					},
					"initComplete": function(settings, json) {
						$(".after-doc").show();
						setFoodCostItem();
						//setProdCostPercentage($('#productSellPrice').val());
					},
					"ajax": {
						"url":"<?php echo site_url('transaksi1/productcosting/showDetailEdit');?>",
						"data":{ 
							id: $('#idProdCost').val(),
							type: 1
						},
						"type":"POST"
					},
					"columns": [
						{"data":"0", "className":"dt-center", render:function(data, type, row, meta){
							rr=`<input type="checkbox" value="${data}" class="check_delete_ing" id="dt_ing_${data}">`;
							return rr;
						}},
						{"data":"1", "className":"dt-center"},
						{"data":"2", "className":"dt-center"},
						{"data":"3"},
						{"data":"4"},
						{"data":"5"},
						{"data":"6", render:function(data, type, row, meta){
							rr = `<input type="hidden" class="form-control ing" name="ing" id="ing_${row['1']}" value="1"><input type="text" class="form-control qty-ing" id="qtyCostingIng_${row['1']}" value="${data}" matqty="${data}" style="width:90px" autocomplete="off" readOnly ${$('#status').val() == 'Approved' ? 'readOnly' : ''}>`
							return rr;
						}},
						{"data":"7"}
					]
				});
				
				$("#tblItemPackaging").DataTable({
					"ordering":false,
					"paging":false,
					drawCallback: function() {
						$('.form-control-select2').select2();
					},
					"initComplete": function(settings, json) {
						$(".after-doc").show();
						setMaterialCostItem();
						//setProdCostPercentage($('#productSellPrice').val());
					},
					"ajax": {
						"url":"<?php echo site_url('transaksi1/productcosting/showDetailEdit');?>",
						"data":{ 
							id: $('#idProdCost').val(),
							type: 2
						},
						"type":"POST"
					},
					"columns": [
						{"data":"0", "className":"dt-center", render:function(data, type, row, meta){
							rr=`<input type="checkbox" value="${data}" class="check_delete_pack" id="dt_pack_${data}">`;
							return rr;
						}},
						{"data":"1", "className":"dt-center"},
						{"data":"2", "className":"dt-center"},
						{"data":"3"},
						{"data":"4"},
						{"data":"5"},
						{"data":"6", render:function(data, type, row, meta){
							rr = `<input type="hidden" class="form-control pack" name="pack" id="pack_${row['1']}" value="2"><input type="text" class="form-control qty-pack" id="qtyCostingPack_${row['1']}" value="${data}" matqty="${data}" style="width:90px" autocomplete="off" readOnly ${$('#status').val() == 'Approved' ? 'readOnly' : ''}>`
							return rr;
						}},
						{"data":"7"}
					]
				});

				// untuk check all
				$("#checkallIng").click(function(){
					if($(this).is(':checked')){
						$(".check_delete_ing").prop('checked', true);
					}else{
						$(".check_delete_ing").prop('checked', false);
					}
				});
				
				$("#checkallPack").click(function(){
					if($(this).is(':checked')){
						$(".check_delete_pack").prop('checked', true);
					}else{
						$(".check_delete_pack").prop('checked', false);
					}
				});
			});

			function setFoodCostItem(){
				let tableIng = $("#tblItemIngredients tbody");
				tableIng.find('tr').each(function(i, el){
					let td = $(this).find('td');
					td.eq(7).text(parseFloat(td.eq(5).text().replace(/,(?=.*\.\d+)/g, '') * td.eq(6).find('input:text').val().replace(/,(?=.*\.\d+)/g, '')).toLocaleString(('en-US'), {minimumFractionDigits: 2, maximumFractionDigits: 2}));
				});
				setTotalFoodCost();
				setProdCostPercentage($('#productSellPrice').val());
			}

			function setTotalFoodCost(){
				let tableIng = $("#tblItemIngredients tbody");
				let totCost = 0;
				tableIng.find('tr').each(function(i, el){
					let td = $(this).find('td');
					totCost += parseFloat(td.eq(7).text() ? td.eq(7).text().replace(/,(?=.*\.\d+)/g, '') : '0.00');
					$('#totAllIngCost').text(totCost.toLocaleString(('en-US'), {minimumFractionDigits: 2, maximumFractionDigits: 2}));
				});
			}

			function setMaterialCostItem(){
				let tablePack = $("#tblItemPackaging tbody");
				let tblItemPackagingCountRow = $('#tblItemPackaging > tbody tr');
				tablePack.find('tr').each(function(i, el){
					let tdPack = $(this).find('td');
					if (tblItemPackagingCountRow.length > 0 && tblItemPackagingCountRow.text() != 'No data available in table') {
						if (tdPack.eq(2).text() || (tdPack.eq(2).has('select').length > 0 && tdPack.eq(2).find('select option:selected').val())) {
							tdPack.eq(7).text(parseFloat(tdPack.eq(5).text().replace(/,(?=.*\.\d+)/g, '') * tdPack.eq(6).find('input:text').val().replace(/,(?=.*\.\d+)/g, '')).toLocaleString(('en-US'), {minimumFractionDigits: 2, maximumFractionDigits: 2}));
						}
					}
				});
				setTotalMaterialCost();
				setProdCostPercentage($('#productSellPrice').val());
			}

			function setTotalMaterialCost(){
				let tablePack = $("#tblItemPackaging tbody");
				let totCost = 0;
				tablePack.find('tr').each(function(i, el){
					let td = $(this).find('td');
					totCost += parseFloat(td.eq(7).text() ? td.eq(7).text().replace(/,(?=.*\.\d+)/g, '') : '0.00');
					$('#totAllPackCost').text(totCost.toLocaleString(('en-US'), {minimumFractionDigits: 2, maximumFractionDigits: 2}));
				});
			}

			function setProdCostPercentage(price){
				setQFactor();
				
				let pricePB1 = parseFloat(price ? price.replace(/,(?=.*\.\d+)/g, '') : '0.00') / (110/100);
				let totProdCost = parseFloat($('#totProdCost').text().replace(/,(?=.*\.\d+)/g, ''));
				let percentage = (totProdCost / pricePB1) * 100;

				$('#percentageCosting').text(`${$('#productType').val() == 'Finish Goods' ? (percentage ? percentage.toFixed(2) : '0.00') : '0.00'} %`);
				setPercentageColor();
			}

			function setPercentageColor(){
				let percentageCost = $('#percentageCosting').text().split(' ');
				let min = parseFloat($("#minCostSAP").val().replace(/,(?=.*\.\d+)/g, '')) * (1/100);
				let max = parseFloat($("#maxCostSAP").val().replace(/,(?=.*\.\d+)/g, '')) * (1/100);

				if ($('#percentageCosting').text() == '0.00 %' && $('#productType').val() == 'Finish Goods') {
					$('#after-submit').hide();
				} else {
					$('#after-submit').show();
				}
				
				if (parseFloat(percentageCost[0] / 100) > max) {
					$('#indicatorCosting').text('Product Cost above Threshold');
					$('#indicatorCosting').css('background-color','red');
					$('#indicatorCosting').css('color','black');
				} else if (parseFloat(percentageCost[0] / 100) < min) {
					$('#indicatorCosting').text('Product Cost below Threshold');
					$('#indicatorCosting').css('background-color','yellow');
					$('#indicatorCosting').css('color','black');
				} else {
					$('#indicatorCosting').text('Product Cost within Threshold, Ok to continue');
					$('#indicatorCosting').css('background-color','green');
					$('#indicatorCosting').css('color','white');
				}
			}

			function setQFactor(){
				let sellingPrice = parseFloat($('#productSellPrice').val() ? $('#productSellPrice').val().replace(/,(?=.*\.\d+)/g, '') : 0);
				let qFactorSAP = parseFloat($("#qFactorSAP").val().replace(/,(?=.*\.\d+)/g, '')) * (1/100);
				let qFactor = parseFloat((sellingPrice / 1.1) * qFactorSAP);
				$('#qFactorResult').text(qFactor.toLocaleString(('en-US'), {minimumFractionDigits: 2, maximumFractionDigits: 2}));
				setTotalProdCost();
			}

			function setTotalProdCost(){
				let totFood = parseFloat($('#totAllIngCost').text().replace(/,(?=.*\.\d+)/g, ''));
				let totMaterial = parseFloat($('#totAllPackCost').text().replace(/,(?=.*\.\d+)/g, ''));
				let qFactorResult = parseFloat($('#qFactorResult').text().replace(/,(?=.*\.\d+)/g, ''));
				let result = $('#productType').val() == 'Finish Goods' ? totFood + totMaterial + qFactorResult : totFood + totMaterial
				$('#totProdCost').text(result.toLocaleString(('en-US'), {minimumFractionDigits: 2, maximumFractionDigits: 2}));
				setTotalProdCostDivQtyProduct();
			}

			function setTotalProdCostDivQtyProduct(){
				let productQty = $('#productQty').val() ? $('#productQty').val().replace(/,(?=.*\.\d+)/g, '') : '0.00';
				let totProdCost = parseFloat($('#totProdCost').text().replace(/,(?=.*\.\d+)/g, ''));
				let result = totProdCost / parseFloat(productQty);
				$('#totProdCostDivQtyProd').text(result ? result.toLocaleString(('en-US'), {minimumFractionDigits: 2, maximumFractionDigits: 2}) : '0.00');
			}

			function addDatadb(){
				let id = $('#idProdCost').val();

				$('#load').show();
				$("#after-submit").addClass('after-submit');

				setTimeout(() => {
					$.post("<?php echo site_url('transaksi1/productcosting/duplicateData')?>",{
						id:id
					}, function(){
						$('#load').hide();
					})
					.done(function() {
						location.replace("<?php echo site_url('transaksi1/productcosting/')?>");
					})
					.fail(function(xhr, status) {
						alert(`Terjadi Error (${xhr.status} : ${xhr.statusText}), Silahkan Coba Lagi`);
						location.reload(true);
					});
				}, 600);
			}
		</script>
	</body>
</html>