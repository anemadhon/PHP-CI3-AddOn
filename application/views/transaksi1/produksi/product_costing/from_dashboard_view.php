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
											<legend class="font-weight-semibold"><i class="icon-reading mr-2"></i>Edit Product Costing</legend>
                                            
                                            <div class="form-group row" id="noProdDiv">
												<label class="col-lg-3 col-form-label">No. Product</label>
												<div class="col-lg-9">
													<select class="form-control form-control-select2" data-live-search="true" onchange="getDataFromDashboardAfterSelectProduct(this.value)">
														<option value="">Select Product</option>
														<?php foreach($data_costing as $value):?>
															<option value="<?=$value['id']?>"><?=$value['no'].' - '.$value['name']?></option>
														<?php endforeach;?>
                                                    </select>
												</div>
                                            </div>
                                            
                                            <div class="after-select-product" style="display:none;">
                                                <div class="form-group row">
                                                    <label class="col-lg-3 col-form-label">No. Product</label>
                                                    <div class="col-lg-9">
                                                        <input type="text" class="form-control" name="prod_cost_no" id="prodCostNo" readOnly>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-lg-3 col-form-label">Product</label>
                                                    <div class="col-lg-9">
                                                        <input type="text" class="form-control" name="doc_status" id="docStatus" readOnly>
                                                        <input type="hidden" id="idProdCost">
														<input type="hidden" id="userInput">
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-lg-3 col-form-label">Costing Type</label>
                                                    <div class="col-lg-9">
                                                        <input type="text" class="form-control" name="product_type" id="productType" readOnly>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-lg-3 col-form-label">Category</label>
                                                    <div class="col-lg-9" id="categories-select" style="display:none;">
                                                        <select name="category" id="category-select" class="form-control form-control-select2" data-live-search="true" onchange="getDataForQFactorFormula(this.value)">
                                                        <option value="">Select Category</option></select>
                                                    </div>
                                                    <div class="col-lg-9" id="categories-input" style="display:none;">
                                                        <input type="text" class="form-control" name="category" id="category-input" readOnly>
                                                    </div>
                                                    <input type="hidden" id="qFactorSAP">
                                                    <input type="hidden" id="minCostSAP">
                                                    <input type="hidden" id="maxCostSAP">
                                                    <input type="hidden" id="catAppSAP">
                                                    <input type="hidden" id="categoryCode">
                                                </div>
                                            </div>

                                            <div class="after-select-product" style="display:none;">
                                                <div class="form-group row" id="existingCost" style="display:none;">
                                                    <label class="col-lg-3 col-form-label">Existing Bom</label>
                                                    <div class="col-lg-9">
                                                        <input type="text" class="form-control" name="existing_bom" id="existingBom" readOnly>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-lg-3 col-form-label">Name</label>
                                                    <div class="col-lg-9">
                                                        <input type="text" class="form-control" name="product_name" id="productName">
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-lg-3 col-form-label">Qty Produksi</label>
                                                    <div class="col-lg-9">
                                                        <input type="text" class="form-control" name="product_qty" id="productQty" onchange="multiplyingQtyItems_setTotalCost(this.value)">
                                                    </div>
                                                </div>	

                                                <div class="form-group row">
                                                    <label class="col-lg-3 col-form-label">UOM</label>
                                                    <div class="col-lg-9">
                                                        <input type="text" class="form-control" name="product_uom" id="productUom">
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-lg-3 col-form-label">Posting Date</label>
                                                    <div class="col-lg-9 input-group date">
                                                        <input type="text" class="form-control" id="postDate" readonly autocomplate="off">
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
                                                        <input type="text" class="form-control" id="status" readOnly>
                                                        <input type="hidden" id="statusInt">
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group row" id="divhead" style="display:none;">
                                                    <label class="col-lg-3 col-form-label">Head of Department</label>
                                                    <div class="col-lg-9">
                                                        <input type="text" class="form-control" id="statusHead" readOnly>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group row" id="divCatApprover" style="display:none;">
                                                    <label class="col-lg-3 col-form-label">Category Approver</label>
                                                    <div class="col-lg-9">
                                                        <input type="text" class="form-control" id="statusCatApp" readOnly>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group row" id="divCostControl" style="display:none;">
                                                    <label class="col-lg-3 col-form-label">Cost Control</label>
                                                    <div class="col-lg-9">
                                                        <input type="text" class="form-control" id="statusCostControl" readOnly>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group row"id="divReject" style="display:none;">
                                                    <label class="col-lg-3 col-form-label">Reject Reason</label>
                                                    <div class="col-lg-9">
                                                        <input type="text" class="form-control" id="rejectReason" readOnly>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group row wp">
                                                    <label class="col-lg-3 col-form-label">Selling Price (include Tax)</label>
                                                    <div class="col-lg-9">
                                                        <input type="text" class="form-control" name="product_sell_price" id="productSellPrice" onchange="setProdCostPercentage(this.value)">
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group row wp">
                                                    <label class="col-lg-3 col-form-label">Product Costing</label>
                                                    <div class="col-lg-9">
                                                        <p class="mt-1"><span id="percentageCosting"></span> <span id="indicatorCosting"></span></p>
                                                    </div>
                                                </div>

                                                <div class="text-right" id="after-submit" style="display: none;"></div>
                                            </div>
											
										</fieldset>
									</div>
								</div>
							</div>
						</div> 
						<div id="load" style="display:none"></div>  

						<div class="card after-select-product" style="display:none;">
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

						<div class="card after-select-product" style="display:none;">
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
									<div class="col-md-4 mb-2 after-doc ing-after-select-product" style="display: none;">
										<div class="text-right">
											<input type="button" class="btn btn-primary" value="Add" id="addTableIng" onclick="onAddrowItemIngredients()"> 
											<input type="button" value="Delete" class="btn btn-danger" id="deleteRecordIng"> 
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

						<div class="card after-select-product" style="display:none;">
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
									<div class="col-md-4 mb-2 after-doc pack-after-select-product" style="display: none;">
										<div class="text-right">
											<input type="button" class="btn btn-primary" value="Add" id="addTablePack" onclick="onAddrowItemPackaging()"> 
											<input type="button" value="Delete" class="btn btn-danger" id="deleteRecordPack"> 
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
				<!-- Modal -->
				<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="exampleModalLabel">Reject Reason</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<div class="form-group">
									<label for="message-text" class="col-form-label">Reason</label>
									<textarea class="form-control" id="reason"></textarea>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-danger" onclick="reject()">Send</button>
							</div>
						</div>
					</div>
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
                
                $('#productQty').change(function () {
					if ($(this).val() && $(this).val().includes(',')) {
						$(this).val($(this).val().replace(/,(?=.*\.\d+)/g, ''));
						$(this).val(parseFloat($(this).val()).toLocaleString(('en-US'), {minimumFractionDigits: 2, maximumFractionDigits: 2}));
					} else if ($(this).val() && !$(this).val().includes(',')) {
						$(this).val(parseFloat($(this).val()).toLocaleString(('en-US'), {minimumFractionDigits: 2, maximumFractionDigits: 2}));
					} else {
						$(this).val('0.00');
					}
                });
				
				$('#productSellPrice').change(function () {
					if ($(this).val() && $(this).val().includes(',')) {
						$(this).val($(this).val().replace(/,(?=.*\.\d+)/g, ''));
						$(this).val(parseFloat($(this).val()).toLocaleString(('en-US'), {minimumFractionDigits: 2, maximumFractionDigits: 2}));
					} else if ($(this).val() && !$(this).val().includes(',')) {
						$(this).val(parseFloat($(this).val()).toLocaleString(('en-US'), {minimumFractionDigits: 2, maximumFractionDigits: 2}));
					} else {
						$(this).val('0.00');
					}
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

				$("#deleteRecordIng").click(function(){
					let deleteidArrIng = [];
					let getTableIng = $("#tblItemIngredients").DataTable();
					$("input:checkbox[class=check_delete_ing]:checked").each(function(){
						deleteidArrIng.push($(this).val());
					})
					// mengecek ckeckbox tercheck atau tidak
					if(deleteidArrIng.length > 0){
						var confirmDeleteIng = confirm("Do you really want to Delete records?");
						if(confirmDeleteIng == true){
							$("input:checkbox[class=check_delete_ing]:checked").each(function(){
								getTableIng.row($(this).closest("tr")).remove().draw();
								setTotalFoodCost();
								setProdCostPercentage($('#productSellPrice').val());
							});
						}
					}
				});
				
				$("#deleteRecordPack").click(function(){
					let deleteidArrPack = [];
					let getTablePack = $("#tblItemPackaging").DataTable();
					$("input:checkbox[class=check_delete_pack]:checked").each(function(){
						deleteidArrPack.push($(this).val());
					})
					// mengecek ckeckbox tercheck atau tidak
					if(deleteidArrPack.length > 0){
						var confirmDeletePack = confirm("Do you really want to Delete records?");
						if(confirmDeletePack == true){
							$("input:checkbox[class=check_delete_pack]:checked").each(function(){
								getTablePack.row($(this).closest("tr")).remove().draw();
								setTotalMaterialCost();
								setProdCostPercentage($('#productSellPrice').val());
							});
						}
					}
				});

				let tbodyIng = $("#tblItemIngredients tbody");
				tbodyIng.on('change','.qty-ing', function(){
					let trIng = $(this).closest('tr');
					let noIng = trIng[0].rowIndex;
					if ($(this).val() && $(this).val().includes(',')) {
						$(this).val($(this).val().replace(/,(?=.*\.\d+)/g, ''));
						$(this).val(parseFloat($(this).val()).toLocaleString(('en-US'), {minimumFractionDigits: 2, maximumFractionDigits: 2}));
					} else if ($(this).val() && !$(this).val().includes(',')) {
						$(this).val(parseFloat($(this).val()).toLocaleString(('en-US'), {minimumFractionDigits: 2, maximumFractionDigits: 2}));
					} else {
						$(this).val('0.00');
					}
					setTotalCostIng($(this).val(),noIng);
					setProdCostPercentage($('#productSellPrice').val());
				});
				tbodyIng.on('change','.cost-ing', function(){
					let trIng = $(this).closest('tr');
					let noIng = trIng[0].rowIndex;
					if ($(this).val() && $(this).val().includes(',')) {
						$(this).val($(this).val().replace(/,(?=.*\.\d+)/g, ''));
						$(this).val(parseFloat($(this).val()).toLocaleString(('en-US'), {minimumFractionDigits: 2, maximumFractionDigits: 2}));
					} else if ($(this).val() && !$(this).val().includes(',')) {
						$(this).val(parseFloat($(this).val()).toLocaleString(('en-US'), {minimumFractionDigits: 2, maximumFractionDigits: 2}));
					} else {
						$(this).val('0.00');
					}
					setTotalCostByPriceIng($(this).val(),noIng);
					setProdCostPercentage($('#productSellPrice').val());
				});
				let tbodyPack = $("#tblItemPackaging tbody");
				tbodyPack.on('change','.qty-pack', function(){
					let trPack = $(this).closest('tr');
					let noPack = trPack[0].rowIndex;
					if ($(this).val() && $(this).val().includes(',')) {
						$(this).val($(this).val().replace(/,(?=.*\.\d+)/g, ''));
						$(this).val(parseFloat($(this).val()).toLocaleString(('en-US'), {minimumFractionDigits: 2, maximumFractionDigits: 2}));
					} else if ($(this).val() && !$(this).val().includes(',')) {
						$(this).val(parseFloat($(this).val()).toLocaleString(('en-US'), {minimumFractionDigits: 2, maximumFractionDigits: 2}));
					} else {
						$(this).val('0.00');
					}
					setTotalCostPack($(this).val(),noPack);
					setProdCostPercentage($('#productSellPrice').val());
				});
				tbodyPack.on('change','.cost-pack', function(){
					let trPack = $(this).closest('tr');
					let noPack = trPack[0].rowIndex;
					if ($(this).val() && $(this).val().includes(',')) {
						$(this).val($(this).val().replace(/,(?=.*\.\d+)/g, ''));
						$(this).val(parseFloat($(this).val()).toLocaleString(('en-US'), {minimumFractionDigits: 2, maximumFractionDigits: 2}));
					} else if ($(this).val() && !$(this).val().includes(',')) {
						$(this).val(parseFloat($(this).val()).toLocaleString(('en-US'), {minimumFractionDigits: 2, maximumFractionDigits: 2}));
					} else {
						$(this).val('0.00');
					}
					setTotalCostByPricePack($(this).val(),noPack);
					setProdCostPercentage($('#productSellPrice').val());
				});
				
            });
            
            function getDataFromDashboardAfterSelectProduct(id){
                $('#load').show();
				$.post("<?php echo site_url('transaksi1/productcosting/getDataFromDashboardAfterSelectProduct');?>",{id: id},(data)=>{
                    
                    $('#load').hide();
                    $('#noProdDiv').hide();
                    const value = JSON.parse(data);
                     
                    // config posting date value
                    const year = value.pc.posting_date.substring(0,4);
					const bln = value.pc.posting_date.substring(5,7);
					const day = value.pc.posting_date.substring(8,10);
                    const postDate = day+'-'+bln+'-'+year;

                    $('#idProdCost').val(value.pc.id_prod_cost_header)
                    $('#userInput').val(value.pc.user_input)
                    $('#statusInt').val(value.pc.status)
                    $('#prodCostNo').val(value.pc.prod_cost_no)
                    $('#docStatus').val(value.pc.existing_bom_code ? 'Existing' : 'New')
                    $('#productType').val(value.pc.product_type == 1 ? 'WP' : 'Finish Goods')

                    if (value.pc.status != 2) {
                        $('#categories-input').hide()
                        $('#categories-select').show()
                        value.categories.forEach((val)=>{						
                            $("<option />", {value:val.Code, text:val.Name, desc:val.Name, selected:val.Code == value.pc.category_code ? 'selected' : ''}).appendTo($('#category-select'));
                        })
                    } else {
                        $('#categories-select').hide()
                        $('#categories-input').show()
                        $('#category-input').val(value.pc.category_name)
                    }

                    $('#qFactorSAP').val(value.pc.q_factor_sap ? parseFloat(value.pc.q_factor_sap).toLocaleString(('en-US'), {minimumFractionDigits: 2, maximumFractionDigits: 2}) : '0.00')
                    $('#minCostSAP').val(value.pc.min ? parseFloat(value.pc.min).toLocaleString(('en-US'), {minimumFractionDigits: 2, maximumFractionDigits: 2}) : '0.00')
                    $('#maxCostSAP').val(value.pc.max ? parseFloat(value.pc.max).toLocaleString(('en-US'), {minimumFractionDigits: 2, maximumFractionDigits: 2}) : '0.00')
                    $('#catAppSAP').val(value.pc.category_approver)
                    $('#categoryCode').val(value.pc.category_code)

                    if (value.pc.existing_bom_code) {
                        $('#existingCost').show()
                    } else {
                        $('#existingCost').hide()
                    }

                    $('#existingBom').val(`${value.pc.existing_bom_code} - ${value.pc.existing_bom_name}`)
                    $('#productName').val(value.pc.product_name)

                    if (value.pc.status == 2) { 
                        $('#productName').attr('readOnly', true)
                    } else {
                        $('#productName').attr('readOnly', false)
                    }

                    $('#productQty').val(parseFloat(value.pc.product_qty).toLocaleString(('en-US'), {minimumFractionDigits: 2, maximumFractionDigits: 2}))

                    if (value.pc.status == 2) { 
                        $('#productQty').attr('readOnly', true)
                    } else {
                        $('#productQty').attr('readOnly', false)
                    }

                    $('#productUom').val(value.pc.product_uom)

                    if (value.pc.existing_bom_code || value.pc.status == 2) { 
                        $('#productUom').attr('readOnly', true)
                    } else {
                        $('#productUom').attr('readOnly', false)
                    }

                    $('#postDate').val(postDate)
                    $('#status').val((value.pc.status == 1 || value.pc.status_head === 0 || value.pc.status_cat_approver === 0 || value.pc.status_cost_control === 0) ? 'Not Approved' : 'Approved')
                   
                    if (value.pc.status == 2 || value.pc.status_head === 0) {
                        $('#divhead').show()
                    } else {
                        $('#divhead').hide()
                    }
                   
                    $('#statusHead').val((value.pc.status == 2 && value.pc.status_head == 2 && value.pc.status_cat_approver !== 0 && value.pc.status_cost_control !== 0) ? 'Approved' : (value.pc.status_head === 0 ? 'Rejected' : 'Not Approved'))
                    
                    if ((value.pc.status == 2 && value.pc.status_head == 2 && value.pc.product_type == 2) || value.pc.status_cat_approver === 0) {
                        $('#divCatApprover').show()
                    } else {
                        $('#divCatApprover').hide()
                    }
                    
                    $('#statusCatApp').val((value.pc.status == 2 && value.pc.status_head == 2 && value.pc.status_cat_approver == 2 && value.pc.status_cost_control !== 0) ? 'Approved' : (value.pc.status_cat_approver === 0 ? 'Rejected' :'Not Approved'))
                    
                    if ((value.pc.status == 2 && value.pc.status_head == 2 && value.pc.status_cat_approver == 2 && value.pc.product_type == 2) || value.pc.status_cost_control === 0) {
                        $('#divCostControl').show()
                    } else {
                        $('#divCostControl').hide()
                    }
                    
                    $('#statusCostControl').val((value.pc.status == 2 && value.pc.status_head == 2 && value.pc.status_cat_approver == 2 && value.pc.status_cost_control == 2) ? 'Approved' : (value.pc.status_cost_control === 0 ? 'Rejected' : 'Not Approved'))
                    
                    if ((value.pc.status_head === 0 || value.pc.status_cat_approver === 0 || value.pc.status_cost_control === 0) && vale.pc.reject_reason) {
                        $('#divReject').show()
                    } else {
                        $('#divReject').hide()
                    }
                    
                    $('#rejectReason').val(value.pc.reject_reason)
                    $('#productSellPrice').val(parseFloat(value.pc.product_selling_price).toLocaleString(('en-US'), {minimumFractionDigits: 2, maximumFractionDigits: 2}))
                    
                    if (value.pc.status == 2) { 
                        $('#productSellPrice').attr('readOnly', true)
                    } else {
                        $('#productSellPrice').attr('readOnly', false)
                    }
                    
                    $('.after-select-product').show()
                    
                    if ($('#productType').val() == 'Finish Goods') {
                        $('.wp').show();
                    } else {
                        $('.wp').hide();
                    }

                    $.post("<?php echo site_url('transaksi1/productcosting/setApprovalCondition');?>", {id:id}, (btn) => {
                        $('#after-submit').html(btn)
                    });
                    
                    if (value.pc.status != 2 || value.pc.status_head === 0 || value.pc.status_cat_approver === 0 || value.pc.status_cost_control === 0) {
                        $('.ing-after-select-product').show()
                    } else {
                        $('.ing-after-select-product').hide()
                    }

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
                                rr = `<input type="hidden" class="form-control ing" name="ing" id="ing_${row['1']}" value="1"><input type="text" class="form-control qty-ing" id="qtyCostingIng_${row['1']}" value="${data}" matqty="${data}" style="width:90px" autocomplete="off" ${$('#status').val() == 'Approved' ? 'readOnly' : ''}>`
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
                                rr = `<input type="hidden" class="form-control pack" name="pack" id="pack_${row['1']}" value="2"><input type="text" class="form-control qty-pack" id="qtyCostingPack_${row['1']}" value="${data}" matqty="${data}" style="width:90px" autocomplete="off" ${$('#status').val() == 'Approved' ? 'readOnly' : ''}>`
                                return rr;
                            }},
                            {"data":"7"}
                        ]
                    });

				})
			}

			function getDataForQFactorFormula($code){
				$.post("<?php echo site_url('transaksi1/productcosting/getDataForQFactorFormula');?>",{code:$code},(data) => {
					const value = JSON.parse(data);
					$("#qFactorSAP").val(value.data['q_factor'] ? parseFloat(value.data['q_factor']).toLocaleString(('en-US'), {minimumFractionDigits: 2, maximumFractionDigits: 2}) : '0.00');
					$("#minCostSAP").val(value.data['min_cost'] ? parseFloat(value.data['min_cost']).toLocaleString(('en-US'), {minimumFractionDigits: 2, maximumFractionDigits: 2}) : '0.00');
					$("#maxCostSAP").val(value.data['max_cost'] ? parseFloat(value.data['max_cost']).toLocaleString(('en-US'), {minimumFractionDigits: 2, maximumFractionDigits: 2}) : '0.00');
					$("#catAppSAP").val(value.data['approver']);
					setTotalFoodCost();
					setTotalMaterialCost();
					setTotalProdCostDivQtyProduct();
					setProdCostPercentage($('#productSellPrice').val());
				});
			}

			function multiplyingQtyItems_setTotalCost(productQty){
				if ($('#docStatus').val() == 'Existing') {
					let tableIng = $("#tblItemIngredients tbody");
					let tablePack = $("#tblItemPackaging tbody");
					let tblItemPackagingCountRow = $('#tblItemPackaging > tbody tr');
					tableIng.find('tr').each(function(i, el){
						let tdIng = $(this).find('td');
						let costIng = parseFloat(tdIng.eq(5).text() ? tdIng.eq(5).text().replace(/,(?=.*\.\d+)/g, '') : (tdIng.eq(5).find('input:text').val() ? tdIng.eq(5).find('input:text').val().replace(/,(?=.*\.\d+)/g, '') : '0.00'));
						let qtyIng = parseFloat($('input:text.qty-ing', this).attr('matqty').replace(/,(?=.*\.\d+)/g, ''));
						tdIng.eq(6).find('input:text').val(parseFloat(productQty.replace(/,(?=.*\.\d+)/g, '') * qtyIng).toLocaleString(('en-US'), {minimumFractionDigits: 2, maximumFractionDigits: 2}));
						tdIng.eq(7).text(parseFloat(tdIng.eq(6).find('input:text').val().replace(/,(?=.*\.\d+)/g, '') * costIng).toLocaleString(('en-US'), {minimumFractionDigits: 2, maximumFractionDigits: 2}));
					});
					tablePack.find('tr').each(function(i, el){
						let tdPack = $(this).find('td');
						if (tblItemPackagingCountRow.length > 0 && tblItemPackagingCountRow.text() != 'No data available in table') {
							if (!tdPack.eq(2).text().includes('Select Item') || (tdPack.eq(2).has('select').length > 0 && tdPack.eq(2).find('select option:selected').val())) {
								let costPack = parseFloat(tdPack.eq(5).text() ? tdPack.eq(5).text().replace(/,(?=.*\.\d+)/g, '') : (tdPack.eq(5).find('input:text').val() ? tdPack.eq(5).find('input:text').val().replace(/,(?=.*\.\d+)/g, '') : '0.00'));
								let qtyPack = parseFloat($('input:text.qty-pack', this).attr('matqty').replace(/,(?=.*\.\d+)/g, ''));
								tdPack.eq(6).find('input:text').val(parseFloat(productQty.replace(/,(?=.*\.\d+)/g, '') * qtyPack).toLocaleString(('en-US'), {minimumFractionDigits: 2, maximumFractionDigits: 2}));
								tdPack.eq(7).text(parseFloat(tdPack.eq(6).find('input:text').val().replace(/,(?=.*\.\d+)/g, '') * costPack).toLocaleString(('en-US'), {minimumFractionDigits: 2, maximumFractionDigits: 2}));
							}
						}
					});
					setTotalFoodCost();
					setTotalMaterialCost();
					setTotalProdCostDivQtyProduct();
					setProdCostPercentage($('#productSellPrice').val());
				} else {
					setTotalProdCostDivQtyProduct();
				}
			}

			function onAddrowItemIngredients(){
				let getTable = $("#tblItemIngredients").DataTable();
				count = getTable.rows().count() + 1;
				let elementSelect = document.getElementsByClassName(`dt-ing-${count}`);
				let itmGrp = $('#itemGroupIng option:selected').val();
				if (itmGrp) {
					getTable.row.add({
						"0":"",
						"1":count,
						"2":`<select class="form-control form-control-select2 dt-ing-${count} selectIng" data-live-search="true" id="selectDetailMatrialIng_${count}" data-count="${count}">
										<option value="">Select Item</option>
										${showMatrialDetailDataIng(elementSelect)}
									</select>`,
						"3":"",
						"4":`<input type="text" class="form-control uom-ing" id="uomCostingIng_${count}" value="" style="width:90px" autocomplete="off">`,
						"5":`<input type="text" class="form-control cost-ing" id="costCostingIng_${count}" value="" style="width:90px" autocomplete="off">`,
						"6":"",
						"7":""
						}).draw();
					count++;
				} else {
					alert('Silahkan Pilih Material Grup');
					return false;
				}

				tbody = $("#tblItemIngredients tbody");
				tbody.on('change','.selectIng', function(){
					tr = $(this).closest('tr');
					no = tr[0].rowIndex;
					id = $('.dt-ing-'+no+' option:selected').attr('rel');
					setValueTableIngredients(id,no);
				});
				tbody.on('change','.qty-ing', function(){
					tr = $(this).closest('tr');
					no = tr[0].rowIndex;
					$(this).attr('matqty', $(this).val());
					setTotalCostIng($(this).val(),no);
					setProdCostPercentage($('#productSellPrice').val());
				});
				tbody.on('change','.cost-ing', function(){
					tr = $(this).closest('tr');
					no = tr[0].rowIndex;
					setTotalCostByPriceIng($(this).val(),no);
					setProdCostPercentage($('#productSellPrice').val());
				});
			}

			function showMatrialDetailDataIng(selectTable){
				const select = selectTable;
				let itmGrp = $('#itemGroupIng option:selected').val();
				$.ajax({
					url: "<?php echo site_url('transaksi1/productcosting/addItemRow');?>",
					data: {
						itmGrp:itmGrp,
						type:'ing'
					},
					type: "POST",
					success:function(res) {
						optData = JSON.parse(res);
						optData.forEach((val)=>{						
							$("<option />", {value:val.MATNR, text:val.MATNR+' - '+val.MAKTX, rel:val.MATNR, tax:val.TAX}).appendTo(select);
						})
						$("<option />", {value:'-', text:'other', rel:'-'}).appendTo(select);
					}
				});	
			}

			function setValueTableIngredients(id,no){
				let tbodyItemIngredientsRows = document.getElementById("tblItemIngredients").rows[no].cells;
				if (id == '-') {
					tbodyItemIngredientsRows[3].innerHTML = `<input type="text" class="form-control" id="productNameIng_${no}" value="" style="width:300px" autocomplete="off">`;
					tbodyItemIngredientsRows[4].innerHTML = `<input type="text" class="form-control uom-ing" id="uomCostingIng_${no}" value="" style="width:90px" autocomplete="off">`;
					tbodyItemIngredientsRows[5].innerHTML = `<input type="text" class="form-control cost-ing" id="costCostingIng_${no}" value="" style="width:90px" autocomplete="off">`;
				} else {
					$.post(
						"<?php echo site_url('transaksi1/productcosting/getdataDetailMaterialSelect')?>",{ MATNR:id },(res)=>{
							matSelect = JSON.parse(res);
							taxIdx = tbodyItemIngredientsRows[2].children[0].selectedOptions[0].attributes[2].value
							tbodyItemIngredientsRows[3].innerHTML = matSelect.data.MAKTX;
							tbodyItemIngredientsRows[4].innerHTML = matSelect.data.UNIT1;
							tbodyItemIngredientsRows[5].innerHTML = matSelect.dataLast.LastPrice == ".000000" ? "0.00" : (taxIdx == 'Y' ? parseFloat(matSelect.dataLast.LastPrice * (110/100)).toLocaleString(('en-US'), {minimumFractionDigits: 2, maximumFractionDigits: 2}) : parseFloat(matSelect.dataLast.LastPrice).toLocaleString(('en-US'), {minimumFractionDigits: 2, maximumFractionDigits: 2}));
						}
					)
				}
			}

			function setTotalCostIng(qty,no){
				let docStatus = $('#docStatus').val();
				let tbodyItemIngredientsRows = document.getElementById("tblItemIngredients").rows[no].cells;
				let itemCodeSelected = ((docStatus == 'Existing' && tbodyItemIngredientsRows[2].children[0]) || tbodyItemIngredientsRows[2].children[0]) ? tbodyItemIngredientsRows[2].children[0].value : tbodyItemIngredientsRows[2].innerHTML;
				let lastPrice = (tbodyItemIngredientsRows[2].children[0] && itemCodeSelected == '-') ? tbodyItemIngredientsRows[5].children[0].value.replace(/,(?=.*\.\d+)/g, '') : tbodyItemIngredientsRows[5].innerHTML.replace(/,(?=.*\.\d+)/g, '');
				tbodyItemIngredientsRows[6].children[1].value = parseFloat(qty ? qty.replace(/,(?=.*\.\d+)/g, '') : '0.00').toLocaleString(('en-US'), {minimumFractionDigits: 2, maximumFractionDigits: 2});
				tbodyItemIngredientsRows[7].innerHTML = (parseFloat(lastPrice) * parseFloat(qty ? qty.replace(/,(?=.*\.\d+)/g, '') : '0.00')).toLocaleString(('en-US'), {minimumFractionDigits: 2, maximumFractionDigits: 2});
				setTotalFoodCost();
				setProdCostPercentage($('#productSellPrice').val());
			}
			
			function setTotalCostByPriceIng(price,no){
				let tbodyItemIngredientsRows = document.getElementById("tblItemIngredients").rows[no].cells;
				let qty = tbodyItemIngredientsRows[6].children[1].value ? tbodyItemIngredientsRows[6].children[1].value.replace(/,(?=.*\.\d+)/g, '') : '0.00';
				tbodyItemIngredientsRows[7].innerHTML = (parseFloat(price) * parseFloat(qty)).toLocaleString(('en-US'), {minimumFractionDigits: 2, maximumFractionDigits: 2});
				setTotalFoodCost();
				setProdCostPercentage($('#productSellPrice').val());
			}

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
			
			function onAddrowItemPackaging(){
				let getTable = $("#tblItemPackaging").DataTable();
				count = getTable.rows().count() + 1;
				let elementSelect = document.getElementsByClassName(`dt-pack-${count}`);
				let itmGrp = $('#itemGroupPack option:selected').val();
				if (itmGrp) {
					getTable.row.add({
						"0":"",
						"1":count,
						"2":`<select class="form-control form-control-select2 dt-pack-${count} selectPack" data-live-search="true" id="selectDetailMatrialPack_${count}" data-count="${count}">
										<option value="">Select Item</option>
										${showMatrialDetailDataPack(elementSelect)}
									</select>`,
						"3":"",
						"4":`<input type="text" class="form-control uom-pack" id="uomCostingPack_${count}" value="" style="width:90px" autocomplete="off">`,
						"5":`<input type="text" class="form-control cost-pack" id="costCostingPack_${count}" value="" style="width:90px" autocomplete="off">`,
						"6":"",
						"7":""
						}).draw();
					count++;
				} else {
					alert('Silahkan Pilih Material Grup');
					return false;
				}

				tbody = $("#tblItemPackaging tbody");
				tbody.on('change','.selectPack', function(){
					tr = $(this).closest('tr');
					no = tr[0].rowIndex;
					id = $('.dt-pack-'+no+' option:selected').attr('rel');
					setValueTablePackaging(id,no);
				});
				tbody.on('change','.qty-pack', function(){
					tr = $(this).closest('tr');
					no = tr[0].rowIndex;
					$(this).attr('matqty', $(this).val());
					setTotalCostPack($(this).val(),no);
					setProdCostPercentage($('#productSellPrice').val());
				});
				tbody.on('change','.cost-pack', function(){
					tr = $(this).closest('tr');
					no = tr[0].rowIndex;
					setTotalCostByPricePack($(this).val(),no);
					setProdCostPercentage($('#productSellPrice').val());
				});
			}

			function showMatrialDetailDataPack(selectTable){
				const select = selectTable;
				let itmGrp = $('#itemGroupPack option:selected').val();
				$.ajax({
					url: "<?php echo site_url('transaksi1/productcosting/addItemRow');?>",
					data: {
						itmGrp:itmGrp,
						type:'pack'
					},
					type: "POST",
					success:function(res) {
						optData = JSON.parse(res);
						optData.forEach((val)=>{						
							$("<option />", {value:val.MATNR, text:val.MATNR+' - '+val.MAKTX, rel:val.MATNR, tax:val.TAX}).appendTo(select);
						})
						$("<option />", {value:'-', text:'other', rel:'-'}).appendTo(select);
					}
				});			
			}

			function setValueTablePackaging(id,no){
				let tbodyItemPackagingRows = document.getElementById("tblItemPackaging").rows[no].cells;
				if (id == '-') {
					tbodyItemPackagingRows[3].innerHTML = `<input type="text" class="form-control" id="productNamePack_${no}" value="" style="width:300px" autocomplete="off">`;
					tbodyItemPackagingRows[4].innerHTML = `<input type="text" class="form-control uom-pack" id="uomCostingPack_${no}" value="" style="width:90px" autocomplete="off">`;
					tbodyItemPackagingRows[5].innerHTML = `<input type="text" class="form-control cost-pack" id="costCostingPack_${no}" value="" style="width:90px" autocomplete="off">`;
				} else {
					$.post(
						"<?php echo site_url('transaksi1/productcosting/getdataDetailMaterialSelect')?>",{ MATNR:id },(res)=>{
							matSelect = JSON.parse(res);
							taxIdx = tbodyItemPackagingRows[2].children[0].selectedOptions[0].attributes[2].value
							tbodyItemPackagingRows[3].innerHTML = matSelect.data.MAKTX;
							tbodyItemPackagingRows[4].innerHTML = matSelect.data.UNIT1;
							tbodyItemPackagingRows[5].innerHTML = matSelect.dataLast.LastPrice == ".000000" ? "0.00" : (taxIdx == 'Y' ? parseFloat(matSelect.dataLast.LastPrice * (110/100)).toLocaleString(('en-US'), {minimumFractionDigits: 2, maximumFractionDigits: 2}) : parseFloat(matSelect.dataLast.LastPrice).toLocaleString(('en-US'), {minimumFractionDigits: 2, maximumFractionDigits: 2}));
						}
					)
				}
			}

			function setTotalCostPack(qty,no){
				let docStatus = $('#docStatus').val();
				let tbodyItemPackagingRows = document.getElementById("tblItemPackaging").rows[no].cells;
				let itemCodeSelected = ((docStatus == 'Existing' && tbodyItemPackagingRows[2].children[0]) || tbodyItemPackagingRows[2].children[0]) ? tbodyItemPackagingRows[2].children[0].value : tbodyItemPackagingRows[2].innerHTML;
				let lastPrice = (tbodyItemPackagingRows[2].children[0] && itemCodeSelected == '-') ? tbodyItemPackagingRows[5].children[0].value.replace(/,(?=.*\.\d+)/g, '') : tbodyItemPackagingRows[5].innerHTML.replace(/,(?=.*\.\d+)/g, '');
				tbodyItemPackagingRows[6].children[1].value = parseFloat(qty ? qty.replace(/,(?=.*\.\d+)/g, '') : '0.00').toLocaleString(('en-US'), {minimumFractionDigits: 2, maximumFractionDigits: 2});
				tbodyItemPackagingRows[7].innerHTML = (parseFloat(lastPrice) * parseFloat(qty ? qty.replace(/,(?=.*\.\d+)/g, '') : '0.00')).toLocaleString(('en-US'), {minimumFractionDigits: 2, maximumFractionDigits: 2});
				setTotalMaterialCost();
				setProdCostPercentage($('#productSellPrice').val());
			}
			
			function setTotalCostByPricePack(price,no){
				let tbodyItemPackagingRows = document.getElementById("tblItemPackaging").rows[no].cells;
				let qty = tbodyItemPackagingRows[6].children[1].value ? tbodyItemPackagingRows[6].children[1].value.replace(/,(?=.*\.\d+)/g, '') : '0.00';
				tbodyItemPackagingRows[7].innerHTML = (parseFloat(price)*parseFloat(qty)).toLocaleString(('en-US'), {minimumFractionDigits: 2, maximumFractionDigits: 2});
				setTotalMaterialCost();
				setProdCostPercentage($('#productSellPrice').val());
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

			function addDatadb(id_approve){
                let idDoc = $('#idProdCost').val();
                let prodCostNo = $('#prodCostNo').val();
				let categoryCode = $('#statusInt').val() == 2 ? $('#categoryCode').val() : $('#category-select option:selected').val();
				let categoryName = $('#statusInt').val() == 2 ? $('#category-input').val() : $('#category-select option:selected').text();
				let categoryQF = $('#qFactorSAP').val().replace(/,(?=.*\.\d+)/g, '');
				let categoryMinCost = $('#minCostSAP').val().replace(/,(?=.*\.\d+)/g, '');
				let categoryMaxCost = $('#maxCostSAP').val().replace(/,(?=.*\.\d+)/g, '');
                let categoryApprover = $('#catAppSAP').val();
                let productType = $('#productType').val() == 'WP' ? 1 : 2;
				let productName = $('#productName').val();
				let productQty = $('#productQty').val().replace(/,(?=.*\.\d+)/g, '');
				let productUom = $('#productUom').val();
				let productSellPrice = $('#productSellPrice').val().replace(/,(?=.*\.\d+)/g, '');
				let productQFactor = $('#qFactorResult').text().replace(/,(?=.*\.\d+)/g, '');
				let productResult = $('#totProdCost').text().replace(/,(?=.*\.\d+)/g, '');
				let productPercentage = $('#percentageCosting').text().split(' ');
                let productResultDivQtyProd = $('#totProdCostDivQtyProd').text().replace(/,(?=.*\.\d+)/g, '');
                let postDate = $('#postDate').val();
				let approve = id_approve;

				let tblItemIngredients = $('#tblItemIngredients > tbody');
				let tblItemPackaging = $('#tblItemPackaging > tbody');
				let tblItemPackagingCountRow = $('#tblItemPackaging > tbody tr');
				let matrialNo = [];
				let matrialDesc = [];
				let itemType = [];
				let itemQty = [];
				let itemUom = [];
				let itemCost = [];
				let validasi = true;
				let dataValidasi = [];
				let errorMesseges = [];
				tblItemIngredients.find('tr').each(function(i, el){
					let tdIng = $(this).find('td');
					matrialNo.push(tdIng.eq(2).has('select').length > 0 ? tdIng.eq(2).find('select option:selected').val() : tdIng.eq(2).text());
					matrialDesc.push(tdIng.eq(3).children().length === 0 ? tdIng.eq(3).text() : tdIng.eq(3).children(0).val()); 
					itemUom.push(tdIng.eq(4).has('input:text').length > 0 ? tdIng.eq(4).find('input').val() : tdIng.eq(4).text());	
					itemCost.push(tdIng.eq(5).has('input:text').length > 0 ? tdIng.eq(5).find('input').val() : tdIng.eq(5).text());
					itemQty.push(tdIng.eq(6).find('input:text').val().replace(/,(?=.*\.\d+)/g, ''));
					itemType.push(tdIng.eq(6).find('input:hidden').val());
					if(tdIng.eq(6).find('input:text').val() == ''){
						dataValidasi.push(tdIng.eq(2).has('select').length > 0 ? tdIng.eq(2).find('select option:selected').val() : tdIng.eq(2).text());
						validasi = false;
					}
				});
				tblItemPackaging.find('tr').each(function(i, el){
					let tdPack = $(this).find('td');
					if (tblItemPackagingCountRow.length > 0 && tblItemPackagingCountRow.text() != 'No data available in table') {
						if (!tdPack.eq(2).text().includes('Select Item') || (tdPack.eq(2).has('select').length > 0 && tdPack.eq(2).find('select option:selected').val())) {
							matrialNo.push(tdPack.eq(2).has('select').length > 0 ? tdPack.eq(2).find('select option:selected').val() : tdPack.eq(2).text());
							matrialDesc.push(tdPack.eq(3).children().length === 0 ? tdPack.eq(3).text() : tdPack.eq(3).children(0).val()); 
							itemUom.push(tdPack.eq(4).has('input:text').length > 0 ? tdPack.eq(4).find('input').val() : tdPack.eq(4).text());	
							itemCost.push(tdPack.eq(5).has('input:text').length > 0 ? tdPack.eq(5).find('input').val() : tdPack.eq(5).text());
							itemQty.push(tdPack.eq(6).find('input:text').val().replace(/,(?=.*\.\d+)/g, ''));
							itemType.push(tdPack.eq(6).find('input:hidden').val());
							if(tdPack.eq(6).find('input:text').val() == ''){
								dataValidasi.push(tdIng.eq(2).has('select').length > 0 ? tdIng.eq(2).find('select option:selected').val() : tdIng.eq(2).text());
								validasi = false;
							}
						}
					}
				});
				if(categoryCode == ''){
					errorMesseges.push('Category harus di pilih. \n');
				}
				if(productName.trim() == ''){
					errorMesseges.push('Product Name harus di isi. \n');
				}
				if(productQty.trim() == ''){
					errorMesseges.push('Product Qty harus di isi. \n');
				}
				if(productUom.trim() == ''){
					errorMesseges.push('Product UOM harus di isi. \n');
				}
				if(productSellPrice.trim() == ''){
					errorMesseges.push('Selling Price Product harus di isi. \n');
				}
				if(!validasi){
					errorMesseges.push('Quatity untuk Material Number '+dataValidasi.join()+' Tidak boleh Kosong, Harap isi Quantity');
				}
				if (errorMesseges.length > 0) {
					alert(errorMesseges.join(''));
					return false;
				}
				$('#load').show();
				$("#after-submit").addClass('after-submit');

				setTimeout(() => {
					$.post("<?php echo site_url('transaksi1/productcosting/updateData')?>",{
                        id:idDoc,
                        prodCostNo:prodCostNo,
						categoryCode:categoryCode,
						categoryName:categoryName,
						categoryQF:categoryQF,
						categoryMin:categoryMinCost,
						categoryMax:categoryMaxCost,
                        categoryApprover:categoryApprover,
                        productType:productType,
						productName:productName,
						productQty:productQty,
						productUom:productUom,
						productSellPrice:productSellPrice,
						productQFactor:productQFactor,
						productResult:productResult,
						productPercentage:productPercentage[0],
                        productResultDivQtyProd:productResultDivQtyProd,
                        postDate:postDate,
						approve:approve, 
						matrialNo:matrialNo, 
						matrialDesc:matrialDesc, 
						itemQty:itemQty, 
						itemUom:itemUom,
						itemCost:itemCost,
						itemType:itemType,
						userInput:$('#userInput').val()
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

			function reject(){
				let idDoc = $('#idProdCost').val();
				let reason = $('#reason').val();
				let status = $('#status').val();
				let statusHead = $('#statusHead').val();
				let statusCatApp = $('#statusCatApp').val();
				let statusCostControl = $('#statusCostControl').val();
				let whosRejectFlag = '';
				if (status == 'Approved' && statusHead == 'Not Approved') {
					whosRejectFlag = 'head'
				} else if (status == 'Approved' && statusHead == 'Approved' && statusCatApp == 'Not Approved') {
					whosRejectFlag = 'catApp'
				} else if (status == 'Approved' && statusHead == 'Approved' && statusCatApp == 'Approved' && statusCostControl == 'Not Approved') {
					whosRejectFlag = 'costControl'
				}

				if ((status == 'Approved' && statusHead == 'Not Approved' && reason.trim() == '') || (status == 'Approved' && statusHead == 'Approved' && statusCatApp == 'Not Approved' && reason.trim() == '') || (status == 'Approved' && statusHead == 'Approved' && statusCatApp == 'Approved' && statusCostControl == 'Not Approved' && reason.trim() == '')) {
					alert('Alasan Tidak Boleh Kosong')
					return false;
				}

				$.post("<?php echo site_url('transaksi1/productcosting/reject')?>", {
					id:idDoc, reason:reason, whosRejectFlag:whosRejectFlag
				}, function(res){
					location.replace("<?php echo site_url('transaksi1/productcosting/')?>");
				}
				);
			}
		</script>
	</body>
</html>