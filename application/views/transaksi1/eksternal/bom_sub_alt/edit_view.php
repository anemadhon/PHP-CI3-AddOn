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
											<legend class="font-weight-semibold"><i class="icon-reading mr-2"></i>BOM Subtitution & Alternative</legend>
											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Transaction No.</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" id="idBOM" value="<?php echo $bsa['id_bom_subalt_header'] ?>" readOnly>
												</div>
											</div>

											<?php if ($bsa['status'] == 1) { ?>
												<div class="form-group row">
													<label class="col-lg-3 col-form-label">Type</label>
													<div class="col-lg-9">
														<select name="bom_type" class="form-control form-control-select2" data-live-search="true" id="BOMType">
															<option value="">Select Type</option>
															<option value="1" <?php echo $bsa['bom_type'] == 1 ? 'selected' : '' ?>>Subtitution</option>
															<option value="2" <?php echo $bsa['bom_type'] == 2 ? 'selected' : '' ?>>Alternative</option>
														</select>
													</div>
												</div>
											<?php } ?>
											
											<?php if ($bsa['status'] == 2) { ?>
												<div class="form-group row">
													<label class="col-lg-3 col-form-label">Type</label>
													<div class="col-lg-9">
														<select name="bom_type" class="form-control form-control-select2" data-live-search="true" id="BOMType">
															<option value="<?php echo $bsa['bom_type']?>" selected><?php echo $bsa['bom_type'] == 1 ? 'Subtitution' : 'Alternative' ?></option>
														</select>
													</div>
												</div>
											<?php } ?>

											<?php if ($bsa['status'] == 1) { ?>
												<div class="form-group row">
													<label class="col-lg-3 col-form-label">Category</label>
													<div class="col-lg-9">
														<select name="category" id="category" class="form-control form-control-select2" data-live-search="true" onchange="getCatApproverName(this.value)">
														<option value="">Select Category</option>
															<?php foreach($categories as $key=>$value){?>
																<option value="<?php echo $value['Code']?>" desc="<?php echo $value['Name']?>" <?php echo $bsa['category_code'] == $value['Code'] ? 'selected' : '' ?>><?php echo $value['Name']?></option>
															<?php };?>
														</select>
														<input type="hidden" id="catAppSAP" value="<?php echo $bsa['category_approver']?>">
													</div>
												</div>
											<?php } ?>

											<?php if ($bsa['status'] == 2) { ?>
												<div class="form-group row">
													<label class="col-lg-3 col-form-label">Category</label>
													<div class="col-lg-9">
														<select class="form-control form-control-select2" data-live-search="true" id="category">
															<option value="<?php echo $bsa['category_code']?>" desc="<?php echo $bsa['category_name']?>" selected><?php echo $bsa['category_name'] ?></option>
														</select>
														<input type="hidden" id="catAppSAP" value="<?php echo $bsa['category_approver']?>">
													</div>
												</div>
											<?php } ?>

											<?php if ($bsa['status'] == 1) { ?>
												<div class="form-group row">
													<label class="col-lg-3 col-form-label">Current Items Group</label>
													<div class="col-lg-9">
														<select class="form-control form-control-select2" data-live-search="true" id="itemsGrpOld" onchange="showRawMatSelectItems('old', this.value)">
															<option value="">Select Items Group</option>
															<option value="all">All</option>
														</select>
														<input type="hidden" id="itemsGrpOldVal" value="<?php echo $bsa['item_group_header_old'] ?>">
													</div>
												</div>
											<?php } ?>
											
											<?php if ($bsa['status'] == 2) { ?>
												<div class="form-group row">
													<label class="col-lg-3 col-form-label">Current Items Group</label>
													<div class="col-lg-9">
														<select class="form-control form-control-select2" data-live-search="true" id="itemsGrpOld">
															<option value="<?php echo $bsa['item_group_header_old']?>" desc="<?php echo $bsa['item_group_header_old']?>" selected><?php echo $bsa['item_group_header_old'] == 'all' ? 'All' : $bsa['item_group_header_old'] ?></option>
														</select>
													</div>
												</div>
											<?php } ?>

											<?php if ($bsa['status'] == 1) { ?>
												<div class="form-group row">
													<label class="col-lg-3 col-form-label">Current Raw Material</label>
													<div class="col-lg-9">
														<select class="form-control form-control-select2" data-live-search="true" id="rawMatOld" onchange="showTableDetailsData(this.value)">
															<option value="">Select Items</option>
														</select>
														<input type="hidden" id="rawMatOldVal" value="<?php echo $bsa['raw_mat_code_old'] ?>">
													</div>
												</div>
											<?php } ?>
											
											<?php if ($bsa['status'] == 2) { ?>
												<div class="form-group row">
													<label class="col-lg-3 col-form-label">Current Raw Material</label>
													<div class="col-lg-9">
														<select class="form-control form-control-select2" data-live-search="true" id="rawMatOld">
															<option value="<?php echo $bsa['raw_mat_code_old']?>" rel="<?php echo $bsa['raw_mat_code_old']?>" desc="<?php echo $bsa['raw_mat_name_old']?>" selected><?php echo $bsa['raw_mat_code_old'].' - '.$bsa['raw_mat_name_old'] ?></option>
														</select>
													</div>
												</div>
											<?php } ?>

											<?php if ($bsa['status'] == 1) { ?>
												<div class="form-group row">
													<label class="col-lg-3 col-form-label">New Items Group</label>
													<div class="col-lg-9">
														<select class="form-control form-control-select2" data-live-search="true" id="itemsGrpNew" onchange="showRawMatSelectItems('new', this.value)">
															<option value="">Select Items Group</option>
															<option value="all">All</option>
														</select>
														<input type="hidden" id="itemsGrpNewVal" value="<?php echo $bsa['item_group_header_new'] ?>">
													</div>
												</div>
											<?php } ?>

											<?php if ($bsa['status'] == 2) { ?>
												<div class="form-group row">
													<label class="col-lg-3 col-form-label">New Items Group</label>
													<div class="col-lg-9">
														<select class="form-control form-control-select2" data-live-search="true" id="itemsGrpNew">
															<option value="<?php echo $bsa['item_group_header_new']?>" desc="<?php echo $bsa['item_group_header_new']?>" selected><?php echo $bsa['item_group_header_new'] == 'all' ? 'All' : $bsa['item_group_header_new'] ?></option>
														</select>
													</div>
												</div>
											<?php } ?>

											<?php if ($bsa['status'] == 1) { ?>
												<div class="form-group row">
													<label class="col-lg-3 col-form-label">New Raw Material</label>
													<div class="col-lg-9">
														<select class="form-control form-control-select2" data-live-search="true" id="rawMatNew" onchange="showNewUOMLastPriceItemsDetail(this.value)">
															<option value="">Select Items</option>
														</select>
														<input type="hidden" id="rawMatNewVal" value="<?php echo $bsa['raw_mat_code_new'] ?>">
														<input type="hidden" id="uomNewRM" value="<?php echo $bsa['raw_mat_uom_new'] ?>">
														<input type="hidden" id="lastPriceNewRM" value="<?php echo $bsa['raw_mat_last_price_new'] ?>">
													</div>
												</div>
											<?php } ?>

											<?php if ($bsa['status'] == 2) { ?>
												<div class="form-group row">
													<label class="col-lg-3 col-form-label">New Raw Material</label>
													<div class="col-lg-9">
														<select class="form-control form-control-select2" data-live-search="true" id="rawMatNew">
															<option value="<?php echo $bsa['raw_mat_code_new']?>" rel="<?php echo $bsa['raw_mat_code_new']?>" desc="<?php echo $bsa['raw_mat_name_old']?>" selected><?php echo $bsa['raw_mat_code_new'].' - '.$bsa['raw_mat_name_new'] ?></option>
														</select>
													</div>
												</div>
											<?php } ?>

											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Status</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" id="status" value="<?php echo ($bsa['status'] == 1 || $bsa['status_head'] === 0 || $bsa['status_cat_approver'] === 0 || $bsa['status_cost_control'] === 0) ? 'Not Approved' : 'Approved' ?>" readOnly>
													<input type="hidden" id="statusInt" value="<?php echo $bsa['status'] ?>">
													<input type="hidden" id="userInput" value="<?php echo $bsa['user_input'] ?>">
												</div>
											</div>
											
											<?php if ($bsa['status'] == 2 || $bsa['status_head'] === 0) : ?>
											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Head of Department</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" id="statusHead" value="<?php echo ($bsa['status'] == 2 && $bsa['status_head'] == 2 && $bsa['status_cat_approver'] !== 0 && $bsa['status_cost_control'] !== 0) ? 'Approved' : ($bsa['status_head'] === 0 ? 'Rejected' : 'Not Approved') ?>" readOnly>
												</div>
											</div>
											<?php endif; ?>
											
											<?php if (($bsa['status'] == 2 && $bsa['status_head'] == 2) || $bsa['status_cat_approver'] === 0) : ?>
											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Category Approver</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" id="statusCatApp" value="<?php echo ($bsa['status'] == 2 && $bsa['status_head'] == 2 && $bsa['status_cat_approver'] == 2 && $bsa['status_cost_control'] !== 0) ? 'Approved' : ($bsa['status_cat_approver'] === 0 ? 'Rejected' : 'Not Approved') ?>" readOnly>
												</div>
											</div>
											<?php endif; ?>
											
											<?php if (($bsa['status'] == 2 && $bsa['status_head'] == 2 && $bsa['status_cat_approver'] == 2) || $bsa['status_cost_control'] === 0) : ?>
											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Cost Control</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" id="statusCostControl" value="<?php echo $bsa['status'] == 2 && $bsa['status_head'] == 2 && $bsa['status_cat_approver'] == 2 && $bsa['status_cost_control'] == 2 ? 'Approved' : ($bsa['status_cost_control'] === 0 ? 'Rejected' : 'Not Approved') ?>" readOnly>
												</div>
											</div>
											<?php endif; ?>
											
											<?php if (($bsa['status_head'] === 0 || $bsa['status_cat_approver'] === 0 || $bsa['status_cost_control'] === 0) && $bsa['reject_reason']) : ?>
											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Reject Reason</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" value="<?php echo $bsa['reject_reason']?>" readOnly>
												</div>
											</div>
											<?php endif; ?>

											<?php 
											$isUser = 0;
											if ($this->auth->is_head_dept()) {
												foreach ($this->auth->is_head_dept()['users'] as $user) {
													if ($user['admin_id'] == $bsa['user_input']) {
														$isUser = $user['admin_id'];
														break;
													};
												}
											}
											?>

											<div class="text-right" id="after-submit" style="display: none;">
												<button type="button" class="btn btn-secondary" name="new_page" id="newPage" data-toggle="modal" data-target="#exampleModalTable" data-backdrop="static" onclick="showDifferent()">Show<i class="icon-eye ml-2"></i></button>
												<?php if ($bsa['status'] == 1 && $this->auth->is_have_perm('auth_approve') && $bsa['user_input'] == $bsa['userid_login']) : ?>
													<button type="button" class="btn btn-primary" name="save" id="save" onclick="addDatadb(1)">Save <i class="icon-pencil5 ml-2"></i></button>
													<button type="button" class="btn btn-success" name="approve" id="approve" onclick="addDatadb(2)" >Approve <i class="icon-paperplane ml-2" ></input></i>
												<?php elseif($bsa['status'] == 2 && $this->auth->is_have_perm('auth_approve') && $bsa['status_head'] !== 0 && $this->auth->is_head_dept()['head_dept'] == $bsa['userid_login'] && $isUser !== 0) : ?>
													<?php if($bsa['status_head'] == 1) : ?>
														<button type="button" class="btn btn-danger" name="reject" id="reject" data-toggle="modal" data-target="#exampleModal" data-backdrop="static">Reject<i class="icon-paperplane ml-2"></i></button>	
													<?php endif; ?>
													<?php if($bsa['status'] != 2 || $bsa['status_head'] != 2) : ?>
														<button type="button" class="btn btn-success" name="approve" id="approve" onclick="addDatadb(3)" >Approve <i class="icon-paperplane ml-2" ></input></i>
													<?php endif; ?>
												<?php elseif($bsa['status'] == 2 && $bsa['status_head'] == 2 && strtolower($bsa['category_approver']) == strtolower($bsa['username_login']) && $bsa['status_cat_approver'] !== 0) : ?>
													<?php if($bsa['status_cat_approver'] == 1) : ?>
														<button type="button" class="btn btn-danger" name="reject" id="reject" data-toggle="modal" data-target="#exampleModal" data-backdrop="static">Reject<i class="icon-paperplane ml-2"></i></button>	
													<?php endif; ?>
													<?php if($bsa['status'] != 2 || $bsa['status_head'] != 2 || $bsa['status_cat_approver'] != 2) : ?>
														<button type="button" class="btn btn-success" name="approve" id="approve" onclick="addDatadb(4)" >Approve <i class="icon-paperplane ml-2" ></input></i>
													<?php endif; ?>
												<?php elseif($bsa['status'] == 2 && $bsa['status_head'] == 2 && $bsa['status_cat_approver'] == 2 && strtolower($bsa['username_dept']) == 'cost control' && $bsa['status_cost_control'] !== 0) : ?>
													<?php if($bsa['status_cost_control'] == 1) : ?>
														<button type="button" class="btn btn-danger" name="reject" id="reject" data-toggle="modal" data-target="#exampleModal" data-backdrop="static">Reject<i class="icon-paperplane ml-2"></i></button>	
													<?php endif; ?>
													<?php if($bsa['status'] != 2 || $bsa['status_head'] != 2 || $bsa['status_cat_approver'] != 2 || $bsa['status_cost_control'] != 2) : ?>
														<button type="button" class="btn btn-success" name="approve" id="approve" onclick="addDatadb(5)" >Approve <i class="icon-paperplane ml-2" ></input></i>
													<?php endif; ?>
												<?php endif; ?>
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
									<legend class="font-weight-semibold"><i class="icon-list mr-2"></i>List Items</legend>
									<div class="col-md-12" style="overflow: auto" >
										<table class="table table-striped" id="tblItem">
											<thead>
												<tr>
													<?php if ($bsa['status'] == 1) { ?>
														<th>All<input type="checkbox" name="checkall" id="checkall"></th>
													<?php } else { ?>
														<th></th>
													<?php } ?>
													<th>No</th>
													<th>Item Group</th>
													<th>Item Code</th>
													<th>Item Desc</th>
													<th>BOM Line No</th>
													<th>Current Qty</th>
													<th>Current UOM</th>
													<th>New Qty</th>
													<th>New UOM</th>
													<th>Current Total Cost </th>
													<th>New Total Cost </th>
													<th>Variance</th>
													<th>Variance (%)</th>
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
				<!-- Modal -->
				<div class="modal fade" id="exampleModalTable" tabindex="-1" role="dialog" aria-labelledby="exampleModalTableLabel" aria-hidden="true">
					<div class="modal-dialog modal-lg" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="exampleModalTableLabel">New Page</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<table class="table table-striped" id="tableModal">
									<thead>
										<tr>
											<th>No</th>
											<th>FG Item Code</th>
											<th>FG Item Name</th>
											<th>FG Item Code Terdampak</th>
											<th>FG Item Name Terdampak</th>
											<th>Quantity Lama</th>
											<th>Harga Lama</th>
											<th>Harga Baru</th>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</div>
						</div>
					</div>
				</div>   
				<?php  $this->load->view("_template/footer.php")?>
			</div>
		</div>
        <?php  $this->load->view("_template/js.php")?>
		<script>
			$(document).ready(function(){
				
				$.ajax({
					url: "<?php echo site_url('transaksi1/bomsubalt/getItemsGrpSelectItems');?>",
					type: "POST",
					beforeSend: function() {
						$('#load').show()
					},
					success:function(res) {
						if ($('#statusInt').val() == 1) {
							optData = JSON.parse(res);
							optData.matrialGroup.forEach((val)=>{						
								$("<option />", {value:val.ItmsGrpNam, text:val.ItmsGrpNam}).appendTo($('#itemsGrpOld'));
							})
							optData.matrialGroup.forEach((val)=>{						
								$("<option />", {value:val.ItmsGrpNam, text:val.ItmsGrpNam}).appendTo($('#itemsGrpNew'));
							})
							$('#itemsGrpOld').val($('#itemsGrpOldVal').val()).trigger('change')
							$.ajax({
								url: "<?php echo site_url('transaksi1/bomsubalt/getRawMatByItmGrpItems');?>",
								type: "POST",
								"data":{
									itmGrp:$('#itemsGrpOldVal').val()
								},
								success:function(res) {
									const optData = JSON.parse(res);
									optData.forEach((val)=>{						
										$("<option />", {value:val.MATNR, text:val.MATNR+' - '+val.MAKTX, rel:val.MATNR, tax:val.TAX}).appendTo($('#rawMatOld'));
									})
									$('#rawMatOld').val($('#rawMatOldVal').val()).trigger('change')
									$('#itemsGrpNew').val($('#itemsGrpNewVal').val()).trigger('change')
									$.ajax({
										url: "<?php echo site_url('transaksi1/bomsubalt/getRawMatByItmGrpItems');?>",
										type: "POST",
										"data":{
											itmGrp:$('#itemsGrpNewVal').val()
										},
										success:function(res) {
											const optData = JSON.parse(res);
											optData.forEach((val)=>{						
												$("<option />", {value:val.MATNR, text:val.MATNR+' - '+val.MAKTX, rel:val.MATNR, tax:val.TAX}).appendTo($('#rawMatNew'));
											})
											$('#rawMatNew').val($('#rawMatNewVal').val()).trigger('change')
											$('#tblItem').DataTable({
												"ordering":false, "paging": false, "searching":true,
												drawCallback: function() {
													$('.form-control-select2').select2();
												},
												"initComplete": function(settings, json) {
													$('#load').hide();
													$('#after-submit').show();
												},
												"ajax": {
													"url":"<?php echo site_url('transaksi1/bomsubalt/showDataDetailOnEdit');?>",
													"type":"POST",
													"data":{ 
														id: $('#idBOM').val(),
													}
												},
												"columns": [
													{"data":"0", "className":"dt-center", render:function(data, type, row, meta){
														rr=`${$('#statusInt').val() == 1 ? `<input type="checkbox" class="is-checked" value="${data == 1 ? (meta.row+1) : ''}" ${data == 1 ? 'checked' : ''}>` : ''}`;
														return rr;
													}},
													{"data":"1", "className":"dt-center"},
													{"data":"2", "className":"dt-center"},
													{"data":"3", "className":"dt-center"},
													{"data":"4", "className":"dt-center"},
													{"data":"5", "className":"dt-center"},
													{"data":"6", "className":"dt-center"},
													{"data":"7", "className":"dt-center"},
													{"data":"8", "className":"dt-center", render:function(data, type, row, meta){
														rr=`<input type="text" class="form-control qty-new" name="qty_new" id="qtyNew_${row['1']}" value="${data}" matqty="${row['6']}" matprc="${row['14']}" style="width:110px" autocomplete="off" ${row['0'] == 1 && $('#statusInt').val() == 1 ? '' : 'readonly'}>`;
														return rr;
													}},
													{"data":"9", "className":"dt-center"},
													{"data":"10", "className":"dt-center"},
													{"data":"11", "className":"dt-center"},
													{"data":"12", "className":"dt-center"},
													{"data":"13", "className":"dt-center"}
												]
											});
										}
									});
								}
							});
						} else {
							$('#tblItem').DataTable({
								"ordering":false, "paging": false, "searching":true,
								drawCallback: function() {
									$('.form-control-select2').select2();
								},
								"initComplete": function(settings, json) {
									$('#load').hide();
									$('#after-submit').show();
								},
								"ajax": {
									"url":"<?php echo site_url('transaksi1/bomsubalt/showDataDetailOnEdit');?>",
									"type":"POST",
									"data":{ 
										id: $('#idBOM').val(),
									}
								},
								"columns": [
									{"data":"0", "className":"dt-center", render:function(data, type, row, meta){
										rr=`${$('#statusInt').val() == 1 ? `<input type="checkbox" class="is-checked" value="${data == 1 ? (meta.row+1) : ''}" ${data == 1 ? 'checked' : ''}>` : ''}`;
										return rr;
									}},
									{"data":"1", "className":"dt-center"},
									{"data":"2", "className":"dt-center"},
									{"data":"3", "className":"dt-center"},
									{"data":"4", "className":"dt-center"},
									{"data":"5", "className":"dt-center"},
									{"data":"6", "className":"dt-center"},
									{"data":"7", "className":"dt-center"},
									{"data":"8", "className":"dt-center", render:function(data, type, row, meta){
										rr=`<input type="text" class="form-control qty-new" name="qty_new" id="qtyNew_${row['1']}" value="${data}" matqty="${row['6']}" matprc="${row['14']}" style="width:110px" autocomplete="off" ${row['0'] == 1 && $('#statusInt').val() == 1 ? '' : 'readonly'}>`;
										return rr;
									}},
									{"data":"9", "className":"dt-center"},
									{"data":"10", "className":"dt-center"},
									{"data":"11", "className":"dt-center"},
									{"data":"12", "className":"dt-center"},
									{"data":"13", "className":"dt-center"}
								]
							});
						}
						
					}
				});
				
				// untuk check all
				$("#checkall").click(function(){
					let tblItem = $('#tblItem > tbody');
					if($(this).is(':checked')){
						tblItem.find('tr').each(function(i, el){
							let td = $(this).find('td');
							td.eq(0).find('input:checkbox').prop('checked', true);
							td.eq(0).find('input:checkbox').val((i+1));
							td.eq(8).find('input:text').attr('readonly', false);
						});
					}else{
						tblItem.find('tr').each(function(i, el){
							let td = $(this).find('td');
							td.eq(0).find('input:checkbox').prop('checked', false);
							td.eq(0).find('input:checkbox').val('');
							td.eq(8).find('input:text').val(td.eq(8).find('input:text').attr('matqty'));
							td.eq(8).find('input:text').attr('readonly', true);
						});
					}
				});

				let tbody = $("#tblItem tbody");
				tbody.on('change','.is-checked', function(){
					let tr = $(this).closest('tr');
					let td = tr.find('td');
					let no = tr[0].rowIndex;
					setCheckedRow(no);
				});
				tbody.on('change','.qty-new', function(){
					let tr = $(this).closest('tr');
					let no = tr[0].rowIndex;
					setNewCostByQty($(this).val(),no);
				});
			});

			function showRawMatSelectItems(type, appendTo){
				const select = type == 'old' ? $('#rawMatOld') : $('#rawMatNew');
				let itmGrp = appendTo;
				$.ajax({
					url: "<?php echo site_url('transaksi1/bomsubalt/getRawMatByItmGrpItems');?>",
					type: "POST",
					data: {
						itmGrp:itmGrp
					},
					success:function(res) {
						optData = JSON.parse(res);
						optData.forEach((val)=>{						
							$("<option />", {value:val.MATNR, text:val.MATNR+' - '+val.MAKTX, rel:val.MATNR, tax:val.TAX}).appendTo(select);
						})
					}
				});	
			}

			function showTableDetailsData(rawMatCode){
				if ($('#rawMatOld option:selected').val() != $('#rawMatOldVal').val()) {
					let category = $('#category').val()
					$.ajax({
						url:"<?php echo site_url('transaksi1/bomsubalt/getDetailsDataByCurrentRawMat');?>",
						type:"POST",
						data:{ 
							rmCode:rawMatCode,
							category:category,
							mode: 'edit'
						},
						beforeSend: function() {
							$('#after-submit').hide();
							$('#load').show()
						},
						success:function(res) {
							row = JSON.parse(res);
							let getTable = $("#tblItem").DataTable();
							getTable.rows().remove().draw();
							getTable.rows.add(row.data).draw();
							$('#load').hide();
						},
					});
				}
			}

			function showNewUOMLastPriceItemsDetail(rawMatCode) {
				if ($('#rawMatNew option:selected').val() != $('#rawMatNewVal').val()) {
					$.ajax({
						url:"<?php echo site_url('transaksi1/bomsubalt/getNewUOMLastPriceItemsDetail');?>",
						type:"POST",
						data:{ 
							rmCode:rawMatCode
						},
						beforeSend: function() {
							$('#load').show()
						},
						success:function(res) {
							let row = JSON.parse(res);
							$('#uomNewRM').val(row ? row['UNIT'] : '')
							$('#lastPriceNewRM').val(parseFloat(row ? row['LastPrice'] : '0.0000'))
							let rmOld = $('#rawMatOld option:selected').val();
							let tblItem = $('#tblItem > tbody');
							let tblItemCountRow = $('#tblItem > tbody tr');
							tblItem.find('tr').each(function(i, el){
								let td = $(this).find('td');
								if (tblItemCountRow.length > 0 && tblItemCountRow.text() != 'No data available in table') {
									$.ajax({
										url:"<?php echo site_url('transaksi1/bomsubalt/getUnitPrice');?>",
										type:"POST",
										data:{
											rmOld:rmOld,
											father:td.eq(3).text(),
											line:td.eq(5).text()
										},
										beforeSend:function(){
											td.eq(9).text('loading..')
											td.eq(11).text('loading..')
											td.eq(12).text('loading..')
											td.eq(13).text('loading..')
										},
										success:function(res_a) {
											row_a = JSON.parse(res_a);
											let price = row_a ? ((row_a['UnitPrice'] == '.000000' || row_a['UnitPrice'] == '.00000000000') ? '0.0000' : row_a['UnitPrice']) : '0.0000'
											td.eq(8).find('input:text').attr('matprc', price)
											let newQty = td.eq(8).find('input:text').val();
											let currentCost = td.eq(10).text();
											td.eq(9).text($('#uomNewRM').val());
											td.eq(11).text(parseFloat(parseFloat(currentCost.replace(/,(?=.*\.\d+)/g, '')) - parseFloat(price) + (parseFloat(newQty.replace(/,(?=.*\.\d+)/g, '')) * $('#lastPriceNewRM').val()) * 1.1).toLocaleString(('en-US'), {minimumFractionDigits: 4, maximumFractionDigits: 4}));
											let newCost = td.eq(11).text();
											td.eq(12).text(parseFloat(parseFloat(newCost.replace(/,(?=.*\.\d+)/g, '')) - parseFloat(currentCost.replace(/,(?=.*\.\d+)/g, ''))).toLocaleString(('en-US'), {minimumFractionDigits: 4, maximumFractionDigits: 4}));
											let variance = td.eq(12).text();
											td.eq(13).text(parseFloat( (parseFloat(variance.replace(/,(?=.*\.\d+)/g, '')) / parseFloat(currentCost.replace(/,(?=.*\.\d+)/g, '')) ? parseFloat(variance.replace(/,(?=.*\.\d+)/g, '')) / parseFloat(currentCost.replace(/,(?=.*\.\d+)/g, '')) : 0) * 100).toLocaleString(('en-US'), {minimumFractionDigits: 4, maximumFractionDigits: 4})+' %');
											
											$('#after-submit').show();
										}
									});
								}
							});

							$('#load').hide();
						},
					});
				}
			}

			function getCatApproverName($code){
				$.post("<?php echo site_url('transaksi1/bomsubalt/getCatApproverName');?>",{code:$code},(data) => {
					const value = JSON.parse(data);
					$("#catAppSAP").val(value.data['approver'].toLowerCase());
				});
			}

			function setCheckedRow(no) {
				let tbodyItemRows = document.getElementById("tblItem").rows[no].cells;
				if (tbodyItemRows[0].children[0].checked == true) {
					tbodyItemRows[0].children[0].checked = true;
					tbodyItemRows[0].children[0].value = no; 
					tbodyItemRows[8].children[0].readOnly = false;
				} else {
					tbodyItemRows[0].children[0].checked = false;
					tbodyItemRows[0].children[0].value = '';
					tbodyItemRows[8].children[0].value = tbodyItemRows[8].children[0].getAttribute('matqty');
					tbodyItemRows[8].children[0].readOnly = true;
				}
			}

			function setNewCostByQty(val, no) {
				let lastPriceNewRM = $('#lastPriceNewRM').val();
				let tbodyItemRows = document.getElementById("tblItem").rows[no].cells;
				tbodyItemRows[8].children[0].value = parseFloat(val ? parseFloat(val.replace(/,(?=.*\.\d+)/g, '')) : 0).toLocaleString(('en-US'), {minimumFractionDigits: 4, maximumFractionDigits: 4});
				tbodyItemRows[11].innerHTML = parseFloat(parseFloat(tbodyItemRows[10].innerHTML.replace(/,(?=.*\.\d+)/g, '')) - parseFloat(tbodyItemRows[8].children[0].getAttribute('matprc')) + (val ? parseFloat(val.replace(/,(?=.*\.\d+)/g, '')) * lastPriceNewRM : 0) * 1.1).toLocaleString(('en-US'), {minimumFractionDigits: 4, maximumFractionDigits: 4});
				setVariance(tbodyItemRows[11].innerHTML, no);
			}

			function setVariance(newCost, no) {
				let tbodyItemRows = document.getElementById("tblItem").rows[no].cells;
				tbodyItemRows[11].innerHTML = parseFloat(newCost ? parseFloat(newCost.replace(/,(?=.*\.\d+)/g, '')) : 0).toLocaleString(('en-US'), {minimumFractionDigits: 4, maximumFractionDigits: 4});
				let currentCost = parseFloat(tbodyItemRows[10].innerHTML.replace(/,(?=.*\.\d+)/g, ''));
				tbodyItemRows[12].innerHTML = parseFloat(parseFloat(newCost.replace(/,(?=.*\.\d+)/g, '')) - currentCost).toLocaleString(('en-US'), {minimumFractionDigits: 4, maximumFractionDigits: 4});
				setVariancePercentage(no)
			}

			function setVariancePercentage(no) {
				let tbodyItemRows = document.getElementById("tblItem").rows[no].cells;
				let currentCost = parseFloat(tbodyItemRows[10].innerHTML.replace(/,(?=.*\.\d+)/g, ''));
				let variance = parseFloat(tbodyItemRows[12].innerHTML.replace(/,(?=.*\.\d+)/g, ''));
				tbodyItemRows[13].innerHTML = parseFloat(((variance / currentCost) ? (variance / currentCost) : 0) * 100).toLocaleString(('en-US'), {minimumFractionDigits: 4, maximumFractionDigits: 4})+' %';
			}

			function showDifferent() {
				const arrays = collectDatasToShowDifferent()
				if (arrays[0].length > 0) {	
					$.ajax({
						url:"<?php echo site_url('transaksi1/bomsubalt/getDifferentImpact');?>",
						type:"POST",
						data:{
							arrayItem:arrays[0],
							arrayVariance:arrays[1],
							arrayNewCost:arrays[2]
						},
						success:function(res) {
							result = JSON.parse(res)
							let getTable = $("#tableModal").DataTable();
							getTable.rows().remove().draw();
							getTable.rows.add(result.data).draw();
						}
					})
				}
			}

			function collectDatasToShowDifferent() {
				let tblItem = $('#tblItem > tbody');
				let tblItemCountRow = $('#tblItem > tbody tr');
				let itemCodeArray = [];
				let newTotalCostArray = [];
				let varianceArray = [];
				let datas = [];
				tblItem.find('tr').each(function(i, el){
					let td = $(this).find('td');
					if ($('#statusInt').val() != 2 && tblItemCountRow.length > 0 && tblItemCountRow.text() != 'No data available in table') {
						if (td.eq(0).find('input:checkbox').is(':checked')) {
							itemCodeArray.push(td.eq(3).text())
							varianceArray.push(td.eq(3).text()+'|'+parseFloat(td.eq(12).text().replace(/,(?=.*\.\d+)/g, '')))
							newTotalCostArray.push(parseFloat(td.eq(11).text().replace(/,(?=.*\.\d+)/g, '')))
						}
					}
					if ($('#statusInt').val() == 2) {
						itemCodeArray.push(td.eq(3).text())
						varianceArray.push(td.eq(3).text()+'|'+parseFloat(td.eq(12).text().replace(/,(?=.*\.\d+)/g, '')))
						newTotalCostArray.push(parseFloat(td.eq(11).text().replace(/,(?=.*\.\d+)/g, '')))
					}
				})

				datas.push(itemCodeArray)
				datas.push(varianceArray)
				datas.push(newTotalCostArray)

				return datas
			}

			function addDatadb(id_approve){
				let idBOM = $('#idBOM').val();
				let BOMType = $('#BOMType option:selected').val();
				let itemsGrpOld = $('#itemsGrpOld option:selected').val();
				let rawMatCodeOld = $('#rawMatOld option:selected').val();
				let rawMatNameOld = $('#rawMatOld option:selected').text().split(' - ');
				let itemsGrpNew = $('#itemsGrpNew option:selected').val();
				let rawMatCodeNew = $('#rawMatNew option:selected').val();
				let rawMatNameNew = $('#rawMatNew option:selected').text().split(' - ');
				let categoryCode = $('#category option:selected').val();
				let categoryName = $('#category option:selected').text();
				let categoryApprover = $('#catAppSAP').val();
				let approve = id_approve;

				let tblItem = $('#tblItem > tbody');
				let countChecked = [];
				let itemChecked = [];
				let itemGroup = [];
				let matrialNo = [];
				let matrialDesc = [];
				let qtyOld = [];
				let uomOld = [];
				let totCostOld = [];
				let qtyNew = [];
				let uomNew = [];
				let totCostNew = [];
				let variance = [];
				let variancePercentage = [];
				let validasi = true;
				let dataValidasi = [];
				let errorMesseges = [];
				let SAPLine = [];

				tblItem.find('tr').each(function(i, el){
					let td = $(this).find('td');
					countChecked.push(td.eq(0).find('input:checkbox:checked').length);
					if ((approve == 2 && td.eq(0).find('input:checkbox:checked').val() && td.eq(0).find('input:checkbox:checked').val() !== '') || $('#statusInt').val() == 2) {
						itemChecked.push(td.eq(0).find('input:checkbox:checked').val() ? 1 : 0);
						itemGroup.push(td.eq(2).text());
						matrialNo.push(td.eq(3).text());
						matrialDesc.push(td.eq(4).text()); 
						SAPLine.push(td.eq(5).text()); 
						qtyOld.push(td.eq(6).text().replace(/,(?=.*\.\d+)/g, ''));
						uomOld.push(td.eq(7).text());	
						totCostOld.push(td.eq(10).text());
						qtyNew.push(td.eq(8).find('input:text').val().replace(/,(?=.*\.\d+)/g, ''));
						uomNew.push(td.eq(9).text());
						totCostNew.push(td.eq(11).text());
						variance.push(td.eq(12).text().replace(/,(?=.*\.\d+)/g, ''));
						variancePercentage.push(td.eq(13).text().replace(/,(?=.*\.\d+)/g, '').split(' %')[0]);
						if(td.eq(11).text() == ''){
							dataValidasi.push(td.eq(3).text());
							validasi = false;
						}
					} else if (approve == 1) {
						itemChecked.push(td.eq(0).find('input:checkbox:checked').val() ? 1 : 0);
						itemGroup.push(td.eq(2).text());
						matrialNo.push(td.eq(3).text());
						matrialDesc.push(td.eq(4).text()); 
						SAPLine.push(td.eq(5).text()); 
						qtyOld.push(td.eq(6).text().replace(/,(?=.*\.\d+)/g, ''));
						uomOld.push(td.eq(7).text());	
						totCostOld.push(td.eq(10).text());
						qtyNew.push(td.eq(8).find('input:text').val().replace(/,(?=.*\.\d+)/g, ''));
						uomNew.push(td.eq(9).text());
						totCostNew.push(td.eq(11).text());
						variance.push(td.eq(12).text().replace(/,(?=.*\.\d+)/g, ''));
						variancePercentage.push(td.eq(13).text().replace(/,(?=.*\.\d+)/g, '').split(' %')[0]);
						if(td.eq(11).text() == ''){
							dataValidasi.push(td.eq(3).text());
							validasi = false;
						}
					}
				});
				if (countChecked.reduce((acc, val) => acc + val) === 0 && approve < 3) {
					alert('Silahkan Check minimal 1 checkbox atau check all checkbox yang tersedia');
					return false;
				}
				if(BOMType == ''){
					errorMesseges.push('Type harus di pilih. \n');
				}
				if(rawMatCodeOld == ''){
					errorMesseges.push('Current Raw Mat harus di pilih. \n');
				}
				if(rawMatCodeNew == ''){
					errorMesseges.push('New Raw Mat harus di pilih. \n');
				}
				if(categoryCode == ''){
					errorMesseges.push('Category harus di pilih. \n');
				}
				if(!validasi){
					errorMesseges.push('New Total Cost untuk Item Code '+dataValidasi.join()+' Tidak boleh Kosong.');
				}
				if (errorMesseges.length > 0) {
					alert(errorMesseges.join(''));
					return false;
				}
				$('#load').show();
				$("#after-submit").addClass('after-submit');

				setTimeout(() => {
					$.post("<?php echo site_url('transaksi1/bomsubalt/updateData')?>",{
						idBOM:idBOM,
						BOMType:BOMType,
						itemsGrpOld:itemsGrpOld,
						rawMatCodeOld:rawMatCodeOld,
						rawMatNameOld:rawMatNameOld[1],
						itemsGrpNew:itemsGrpNew,
						rawMatCodeNew:rawMatCodeNew,
						rawMatNameNew:rawMatNameNew[1],
						categoryCode:categoryCode,
						categoryName:categoryName,
						categoryApprover:categoryApprover,
						approve:approve, 
						itemChecked:itemChecked, 
						itemGroup:itemGroup, 
						matrialNo:matrialNo, 
						matrialDesc:matrialDesc, 
						qtyOld:qtyOld, 
						uomOld:uomOld,
						totCostOld:totCostOld,
						qtyNew:qtyNew, 
						uomNew:uomNew,
						totCostNew:totCostNew,
						variance:variance,
						variancePercentage:variancePercentage,
						SAPLine:SAPLine,
						userInput:$('#userInput').val()
					}, function(){
						$('#load').hide();
					})
					.done(function() {
						location.replace("<?php echo site_url('transaksi1/bomsubalt/')?>");
					})
					.fail(function(xhr, status) {
						alert(`Terjadi Error (${xhr.status} : ${xhr.statusText}), Silahkan Coba Lagi`);
						location.reload(true);
					});
				}, 600);
			}

			function reject(){
				let idBOM = $('#idBOM').val();
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

				$.post("<?php echo site_url('transaksi1/bomsubalt/reject')?>", {
					id:idBOM, reason:reason, whosRejectFlag:whosRejectFlag
				}, function(res){
					location.replace("<?php echo site_url('transaksi1/bomsubalt/')?>");
				}
				);
			}
		</script>
	</body>
</html>