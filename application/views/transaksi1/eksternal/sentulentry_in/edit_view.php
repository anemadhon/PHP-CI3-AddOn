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
			.hide,
			.after-submit {
				display: none;
			}
		</style>
		<style>
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
                	<form action="#" method="POST">
						<div class="card">
							<div class="card-body">
								<div class="row">
									<div class="col-md-12">
										<fieldset>
											<legend class="font-weight-semibold"><i class="icon-reading mr-2"></i>Tambah Sentul Entry In</legend>
											<div class="form-group row">
												<label class="col-lg-3 col-form-label">ID Transaksi</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" id="idSentulEntry" name="idSentulEntry" value="<?=$sentulentry_header['id_sentul_entry_header']?>" readOnly>
												</div>
											</div>

											<div class="form-group row">
												<label class="col-lg-3 col-form-label">SAP Document Number</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" id="SAPNumber" name="SAPNumber" value="<?=$sentulentry_header['sap_doc_number']?>" readOnly>
												</div>
											</div>
											
											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Plant</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" id="plant" name="plant" value="<?= $sentulentry_header['plant'] ?>" readOnly>
												</div>
											</div>

											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Posting Date</label>
												<div class="col-lg-9 input-group date">
													<input type="text" class="form-control" id="postingDate" value="<?=date("d-m-Y", strtotime($sentulentry_header['posting_date']))?>" readOnly>
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
													<input type="text" class="form-control" id="typeDoc" name="typeDoc" value="<?=$sentulentry_header['type']?>" readonly>
												</div>
											</div>

											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Reason</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" id="reason" name="reason" value="<?=$sentulentry_header['reason']?>" readonly>
												</div>
											</div>

											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Remark</label>
												<div class="col-lg-9 input-group date">
													<input type="hidden" class="form-control" id="remarkOld" name="reason" value="<?=$sentulentry_header['remark']?>" readonly>
													<textarea id="remark" name="remark" cols="5" rows="5" class="form-control"><?=$sentulentry_header['remark']?></textarea>
												</div>
											</div>

											<?php if($sentulentry_header['status'] == 0):?>
												<div class="form-group row">
													<label class="col-lg-3 col-form-label">Cancel Reason</label>
													<div class="col-lg-9">
														<input type="text" class="form-control" value="<?=$sentulentry_header['cancel_reason']?>" readonly>
													</div>
												</div>
											<?php endif; ?>

											<?php if($sentulentry_header['status'] == 1):?>
												<div class="text-right hide" id="after-submit">
													<button type="button" class="btn btn-primary" id="save" name="save" onclick="addDatadb(2)">Save <i class="icon-pencil5 ml-2"></i></button>
													<button type="button" class="btn btn-danger" name="cancel" id="cancel" data-toggle="modal" data-target="#exampleModal" data-backdrop="static">Cancel<i class="icon-paperplane ml-2"></i></button>
												</div>
											<?php endif; ?>
										</fieldset>
									</div>
								</div>
							</div>
						</div>
						<div id="load" style="display:none"></div>
						<div class="card">
							<div class="card-body">
								<div class="row">
									<legend class="font-weight-semibold"><i class="icon-list mr-2"></i>List Item</legend>
									<?php if($sentulentry_header['status'] == 1):?>
										<div class="col-md-12 mb-2">
											<div class="text-left">
												<input type="button" class="btn btn-primary" value="Add" id="addTable" onclick="onAddrow()"> 
												<input type="button" value="Delete" class="btn btn-danger" id="deleteRecord"> 
											</div>
										</div>
									<?php endif; ?>
									<div class="col-md-12" style="overflow: auto">
										<table class="table table-striped" id="tblWhole">
											<thead>
												<tr>
													<th></th>
													<th>No</th>
													<th>Material No</th>
													<th>Material Desc</th>
													<th>UOM</th>
													<th>Quantity</th>
												</tr>
											</thead>
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
							<h5 class="modal-title" id="exampleModalLabel">Cancel Reason</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<div class="form-group">
								<label for="message-text" class="col-form-label">Reason</label>
								<textarea class="form-control" id="cancelReason"></textarea>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-danger" onclick="onCancel()">Send</button>
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
				let id_sentulentry_header = $('#idSentulEntry').val();
				let stts = '<?php echo $sentulentry_header['status']; ?>';

				const date = new Date();
				const today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
				var optSimple = {
					format: 'dd-mm-yyyy',
					todayHighlight: true,
					orientation: 'bottom right',
					autoclose: true
				};
				$('#postingDate').datepicker(optSimple);

				table = $("#tblWhole").DataTable({
					"initComplete": function(settings, json) {
						$("#after-submit").removeClass('hide');
					},
					"ordering":false,
					"paging":false,
					"ajax": {
							"url":"<?php echo site_url('transaksi1/sentulentry_in/showDataDetailOnEdit');?>",
							"data":{ id: id_sentulentry_header, status: stts },
							"type":"POST"
						},
					"columns": [
						
						{"data":"id_sentul_entry_detail", "className":"dt-center", render:function(data, type, row, meta){
								rr = (row["status"] == 0) ? '' : `<input type="checkbox" class="check_delete" id="chk_${data}" value="${data}" >`;
								return rr;
						}},
						{"data":"no", "className":"dt-center"},
						{"data":"material_no",
						"createdCell":  function (td, data, row, col) {
							$(td).attr('data-old-code', data); 
						}},
						{"data":"material_desc", "createdCell":  function (td, data, row, col) {
							$(td).attr('data-old-desc', data); 
						}},
						{"data":"uom", "createdCell":  function (td, data, row, col) {
							$(td).attr('data-old-uom', data); 
						}},
						{"data":"quantity", "className":"dt-center",render:function(data, type, row, meta){
							rr = (stts == 0) ? data : `<input type="text" class="form-control qty" id="qty_${row['no']}" value="${data}" data-old-qty="${data}">`;
							return rr;
						}}
					],
					drawCallback: function() {
						$('.form-control-select2').select2();
					}
				});

				$("#deleteRecord").click(function(){
					let deleteidArr = [];
					let getTable = $("#tblWhole").DataTable();
					$("input:checkbox[class=check_delete]:checked").each(function(){
						deleteidArr.push($(this).val());
					})

					// mengecek ckeckbox tercheck atau tidak
					if(deleteidArr.length > 0){
						var confirmDelete = confirm("Do you really want to Delete records?");
						if(confirmDelete == true){
							$("input:checked").each(function(){
								getTable.row($(this).closest("tr")).remove().draw();
							});
						}
					}
					
				});

				checkcheckbox = () => {
					let totalChecked = 0;
					$(".check_delete").each(function(){
						if($(this).is(":checked")){
							totalChecked += 1;
						}
					});
				}

			});

			function onAddrow(){
				let getTable = $("#tblWhole").DataTable();
				count = getTable.rows().count() + 1;
				let elementSelect = document.getElementsByClassName(`dt_${count}`);

				getTable.row.add({
					"no":count,
					"material_no":`<select class="form-control form-control-select2 dt_${count} testSelect" data-live-search="true" id="selectDetailMatrial" data-count="${count}">
							<option value="">Select Item</option>
							${showMatrialDetailData('all', elementSelect)}
						</select>`,
					"material_desc":"",
					"uom":"",
					"quantity":""
				}).draw();
				count++;

				tbody = $("#tblWhole tbody");
				tbody.on('change','.testSelect', function(){
					tr = $(this).closest('tr');
					no = tr[0].rowIndex;
					id = $('.dt_'+no).val();
					setValueTable(id,no);
				});
			}

			function showMatrialDetailData(matrialGroup, selectTable){
				const select = selectTable ? selectTable : $('#matrialGroupDetail');
				$.ajax({
					url: "<?php echo site_url('transaksi1/sentulentry_in/getdataDetailMaterial');?>",
					type: "POST",
					data: {
						matGroup: matrialGroup
					},
					success:function(res) {
						optData = JSON.parse(res);
						optData.forEach((val) => {
							$("<option />", {value:val.MATNR, text:val.MATNR +' - '+ val.MAKTX+' - '+val.UNIT	}).appendTo(select);
						})
					}
				});			
			}

			function setValueTable(id,no){
				table = document.getElementById("tblWhole").rows[no].cells;
				$.post(
					"<?php echo site_url('transaksi1/sentulentry_in/getdataDetailMaterialSelect')?>",{ MATNR:id },(res) => {
						matSelect = JSON.parse(res);
						matSelect.map((val)=>{
							table[2].innerHTML = val.MATNR;
							table[3].innerHTML = val.MAKTX;
							table[4].innerHTML = val.UNIT
						})
					}
				)
			}

			function addDatadb(id_approve){
				const id_sentulentry = $('#idSentulEntry').val();
				const plant = $('#plant').val();
				const storage_location = $('#storageLocation').val();
				const from_outlet = plant;
				const posting_date = $('#postingDate').val();
				const type_doc = $('#typeDoc').val();
				const reason = $('#reason').val();
				const remark = $('#remark').val();
				const remarkOld = $('#remarkOld').val();
				const sap_number = $('#SAPNumber').val();
				const approve = id_approve;
				const tbodyTable = $('#tblWhole > tbody');
				let matrialNo = [];
				let matrialNoOld = [];
				let matrialDesc = [];
				let matrialDescOld = [];
				let qty = [];
				let qtyOld = [];
				let uom = [];
				let uomOld = [];
				let errorrMessages = [];
				let dataValidasiQty = [];
				let validasiQty = true;
				tbodyTable.find('tr').each(function(i, el){
					let td = $(this).find('td');	
					if (td.eq(5).find('input').val().trim() == '') {
						dataValidasiQty.push(td.eq(2).text())
						validasiQty = false;
					}
					matrialNo.push(td.eq(2).text()); 
					if (td.eq(2).data('old-code').length > 14) {
						matrialNoOld.push('');
					} else {
						matrialNoOld.push(td.eq(2).data('old-code'));
					} 
					matrialDesc.push(td.eq(3).text());
					matrialDescOld.push(td.eq(3).data('old-desc'));
					uom.push(td.eq(4).text());
					uomOld.push(td.eq(4).data('old-uom'));
					qty.push(td.eq(5).find('input').val());
					qtyOld.push(td.eq(5).find('input').data('old-qty'));
				})
				
				if(remark.trim() == ''){
					errorrMessages.push('Remark harus di isi. \n');
				}
				if(!validasiQty){
					errorrMessages.push(`Quantity dengan material no. ${dataValidasiQty.join()} harus di isi. \n`);
				}
				if (errorrMessages.length > 0) {
					alert(errorrMessages.join(''))
					return false
				}

				let oldMNo = matrialNoOld.filter(matrialNoOld => matrialNoOld != '')
				let oldMDesc = matrialDescOld.filter(matrialDescOld => matrialDescOld != '')
				let oldUom = uomOld.filter(uomOld => uomOld != '')
				let oldQty = qtyOld.filter(qtyOld => qtyOld != '')

				$('#load').show();
				$("#after-submit").addClass('after-submit');

				setTimeout(() => {
					$.post("<?php echo site_url('transaksi1/sentulentry_in/addDataUpdate')?>", {
						idsentulentry_header:id_sentulentry, appr: approve, postingDate: posting_date, remark:remarkOld, detMatrialNo: oldMNo, detMatrialDesc: oldMDesc, detQty: oldQty, detUom: oldUom
					}, function(){
						$.post("<?php echo site_url('transaksi1/sentulentry_in/addData')?>", {
							plant: plant, storageLoc: storage_location, appr: '', stts: 1, postingDate: posting_date, type:type_doc, reason:reason, fromOutlet:from_outlet, fromOutletName:'', toOutlet:'', toOutletName:'', remark:remark, SAPNumber:sap_number, detMatrialNo: matrialNo, detMatrialDesc: matrialDesc, detQty: qty, detUom: uom
						});
						$('#load').hide();
					})
					.done(function() {
						location.replace("<?php echo site_url('transaksi1/sentulentry_in/')?>");
					})
					.fail(function(xhr, status) {
						alert(`Terjadi Error (${xhr.status} : ${xhr.statusText}), Silahkan Coba Lagi`);
						location.reload(true);
					});
				}, 600);
			}

			function onCancel() {
				const id_sentulentry = $('#idSentulEntry').val();
				const cancel_reason = $('#cancelReason').val();

				$('#load').show();
				$("#after-submit").addClass('after-submit');

				setTimeout(() => {
					$.post("<?php echo site_url('transaksi1/sentulentry_in/cancelDocument')?>", {
						idsentulentry_header:id_sentulentry, status:0, cancel_reason:cancel_reason
					}, function(){
						$('#load').hide();
					})
					.done(function() {
						location.replace("<?php echo site_url('transaksi1/sentulentry_in/')?>");
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