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
											<legend class="font-weight-semibold"><i class="icon-reading mr-2"></i>Tambah Sentul Entry Out</legend>
											<div class="form-group row">
												<label class="col-lg-3 col-form-label">SAP Document Number</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" id="SAPNumber" name="SAPNumber">
												</div>
											</div>
											
											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Plant</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" id="plant" name="plant" value = "<?php echo $plant.' - '.$plant_name ?>" readOnly>
												</div>
											</div>

											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Posting Date</label>
												<div class="col-lg-9 input-group date">
													<input type="text" class="form-control" id="postingDate">
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
													<input type="text" class="form-control" id="typeDoc" name="typeDoc" value="OUT" readOnly>
												</div>
											</div>

											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Reason</label>
												<div class="col-lg-9">
													<select class="form-control form-control-select2" id="reason" name="reason" data-live-search="true">
														<option value="">Select Reason</option>
														<?php foreach ($reasons as $value) { ?>
															<option value="<?php echo $value['Name'];?>"><?php echo $value['Name'];?></option>
														<?php } ?>
													</select>
												</div>
											</div>

											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Remark</label>
												<div class="col-lg-9 input-group date">
													<textarea id="remark" name="remark" cols="5" rows="5" class="form-control"></textarea>
												</div>
											</div>

											<div class="text-right" id="after-submit">
												<input type="hidden" class="form-control" id="storageLocation" name="storageLocation" value = "<?php echo $storage_location.' - '.$storage_location_name ?>" readOnly>
												<button type="button" class="btn btn-primary" id="save" name="save" onclick="addDatadb()">Save <i class="icon-pencil5 ml-2"></i></button>
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
									<legend class="font-weight-semibold"><i class="icon-list mr-2"></i>List Item</legend>
									<div class="col-md-12 mb-2">
										<div class="text-left">
											<input type="button" class="btn btn-primary" value="Add" id="addTable" onclick="onAddrow()"> 
											<input type="button" value="Delete" class="btn btn-danger" id="deleteRecord"> 
										</div>
									</div>
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
											<tbody>
												<tr>
													<td><input type="checkbox" id="record"/></td>
													<td>1</td>
													<td >
														<select class="form-control form-control-select2" data-live-search="true" id="matrialGroupDetail" onchange="setValueTable(this.value,1)">
															<option value="">Select Item</option>
														</select>
													</td>
													<td></td>
													<td></td>
													<td><input type="text" class="form-control qty" id="qty" name="qty[]" style="width:100%"></td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
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
			var table = $("#tblWhole").DataTable({
				"ordering":false,
				"paging":false,
				drawCallback: function() {
					$('.form-control-select2').select2();
				}
			});

			$("#deleteRecord").click(function(){
				let deleteidArr = [];
				$("input:checkbox[class=check_delete]:checked").each(function(){
					deleteidArr.push($(this).val());
				})

				// mengecek ckeckbox tercheck atau tidak
				if(deleteidArr.length > 0){
					var confirmDelete = confirm("Do you really want to Delete records?");
					if(confirmDelete == true){
						$("input:checked").each(function(){
							table.row($(this).closest("tr")).remove().draw();
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

			const date = new Date();
			const today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
			var optSimple = {
				format: 'dd-mm-yyyy',
				todayHighlight: true,
				orientation: 'bottom right',
				autoclose: true
			};
			$('#postingDate').datepicker(optSimple);
			$('#postingDate').datepicker( 'setDate', today );

			showMatrialDetailData('all');	

		});

		function onAddrow(){
			let getTable = $("#tblWhole").DataTable();
			count = getTable.rows().count() + 1;
			let elementSelect = document.getElementsByClassName(`dt_${count}`);

			getTable.row.add({
				"0":`<input type="checkbox" class="check_delete" id="chk_${count}" value="${count}">`,
				"1":count,
				"2":`<select class="form-control form-control-select2 dt_${count} testSelect" data-live-search="true" id="selectDetailMatrial" data-count="${count}">
						<option value="">Select Item</option>
						${showMatrialDetailData('all', elementSelect)}
					</select>`,
				"3":"",
				"4":"",
				"5":`<input type="text" class="form-control qty" id="qty_${count}" value="" style="width:100%">`
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
				url: "<?php echo site_url('transaksi1/sentulentry_out/getdataDetailMaterial');?>",
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
				"<?php echo site_url('transaksi1/sentulentry_out/getdataDetailMaterialSelect')?>",{ MATNR:id },(res) => {
					matSelect = JSON.parse(res);
					matSelect.map((val)=>{
						table[3].innerHTML = val.MAKTX;
						table[4].innerHTML = val.UNIT
					})
				}
			)
		}

		function addDatadb(id_approve = ''){
			const plant = $('#plant').val();
			const storage_location = $('#storageLocation').val();
			const from_outlet = plant;
			const status = 1;
			const posting_date = $('#postingDate').val();
			const type_doc = $('#typeDoc').val();
			const reason = $('#reason').val();
			const remark = $('#remark').val();
			const sap_number = $('#SAPNumber').val();
			const approve = id_approve;
			const tbodyTable = $('#tblWhole > tbody');
			let matrialNo = [];
			let matrialDesc = [];
			let qty = [];
			let uom = [];
			let errorrMessages = [];
			let dataValidasiQty = [];
			let validasiQty = true;
			tbodyTable.find('tr').each(function(i, el){
				let td = $(this).find('td');	
				if (td.eq(5).find('input').val().trim() == '') {
					dataValidasiQty.push(td.eq(2).find('select').val())
					validasiQty = false;
				}
				matrialNo.push(td.eq(2).find('select').val()); 
				matrialDesc.push(td.eq(3).text());
				uom.push(td.eq(4).text());
				qty.push(td.eq(5).find('input').val());
			})
			if(sap_number.trim() == ''){
				errorrMessages.push('No. Dok. SAP harus di isi. \n');
			}
			if(posting_date.trim() == ''){
				errorrMessages.push('Posting Date harus di isi. \n');
			}
			if(type_doc.trim() == ''){
				errorrMessages.push('Type belum dipilih, Silahkan pilih terlebih dahulu. \n');
			}
			if(reason.trim() == ''){
				errorrMessages.push('Reason belum dipilih, Silahkan pilih terlebih dahulu. \n');
			}
			if(remark.trim() == ''){
				errorrMessages.push('Remark harus di isi. \n');
			}
			if(!validasiQty){
				errorrMessages.push(`Quantity dengan material no. ${dataValidasiQty.join()} harus di isi. \n`);
			}
			if (errorrMessages.length > 0) {
				alert(errorrMessages.join(''))
				return false;
			}

			$('#load').show();
			$("#after-submit").addClass('after-submit');

			setTimeout(() => {
				$.post("<?php echo site_url('transaksi1/sentulentry_out/addData')?>", {
					plant: plant, storageLoc: storage_location, appr: approve, stts: status, postingDate: posting_date, type:type_doc, reason:reason, fromOutlet:from_outlet, fromOutletName:'', toOutlet:'', toOutletName:'', remark:remark, SAPNumber:sap_number, detMatrialNo: matrialNo, detMatrialDesc: matrialDesc, detQty: qty, detUom: uom
				}, function(){
					$('#load').hide();
				})
				.done(function() {
					location.replace("<?php echo site_url('transaksi1/sentulentry_out/')?>");
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