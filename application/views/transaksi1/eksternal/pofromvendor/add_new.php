<!DOCTYPE html>
<html lang="en">
	<head>
		<?php  $this->load->view("_template/head.php")?>
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
					<form action="" method="POST" id="form_input">
						<div class="card">
							<div class="card-body">
								<div class="row">
									<div class="col-md-12">
										<fieldset>
											<legend class="font-weight-semibold"><i class="icon-reading mr-2"></i>Good Receipt PO from Vendor</legend>

											<div class="form-group row">
												<label class="col-lg-3 col-form-label"><b>Data SAP per Tanggal/Jam</b></label>
												<div class="col-lg-9"><b>Belum ada data</b>
												</div>
											</div>
											
											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Purchase Order Entry</label>
												<div class="col-lg-9">
													<select class="form-control form-control-select2" data-live-search="true" name="poOrderEntry" id="poOrderEntry" onchange="getDataHeader(this.value)">
														<option value="">Select Item</option>
														<?php foreach($po_no as $key=>$value):?>
															<option value="<?=$key?>"><?=$value?></option>
														<?php endforeach;?>
													</select>
												</div>
											</div>

											<div id='form1' style="display:none">
											
											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Purchase Order Number</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" placeholder="Input Purchase Order Number" readonly=""  name="poOrderNumber" id="poOrderNumber">
												</div>
											</div>

											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Vendor Code</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" placeholder="Input Vendor Code Number" readonly=""  name="vendorCode" id="vendorCode">
												</div>
											</div>
											
											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Vendor Name</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" placeholder="Input Vendor Name" readonly=""  name="nameVendor" id="nameVendor">
												</div>
											</div>
											
											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Delivery Date</label>
												<div class="col-lg-9 input-group date">
													<input type="text" class="form-control" id="delivDate">
													<div class="input-group-prepend">
														<span class="input-group-text" id="basic-addon1">
															<i class="icon-calendar"></i>
														</span>
													</div>
												</div>
											</div>
											
											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Goods Receipt Number</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" placeholder="(Auto Number after Posting to SAP)" readonly="" value="" name="grNumber" id="grNumber">
												</div>
											</div>
											
											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Outlet</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" placeholder="Outlet" readonly="" value="<?= $plant ?>" name="outlet" id="outlet">
												</div>
											</div>
											
											<div class="form-group row" hidden>
												<label class="col-lg-3 col-form-label">Storage Location</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" placeholder="Outlet" readonly="" value="<?= $storage_location ?>" name="storageLocation" id="storageLocation">
												</div>
											</div>
											
											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Status</label>
												<div class="col-lg-9">
													<input type="hidden" name="status" id="status" value="1" >
													<input type="text" class="form-control" placeholder="" readonly="" value="Not Approved" name="status_string" id="status_string">
												</div>
											</div>
											
											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Material Group</label>
												<div class="col-lg-9">
													<select class="form-control form-control-select2" data-live-search="true" name="MatrialGroup" id="MatrialGroup">
														
													</select>
												</div>
											</div>
											
											</div>

											<div class='hide' id="form2">
												<div class="form-group row" >
												<label class="col-lg-3 col-form-label">Posting Date</label>
													<div class="col-lg-9 input-group date">
														<input type="text" class="form-control" id="postingDate" autocomplete="off">
														<div class="input-group-prepend">
															<span class="input-group-text" id="basic-addon1">
																<i class="icon-calendar"></i>
															</span>
														</div>
													</div>
												</div>
												<div class="form-group row">
													<label class="col-lg-3 col-form-label">Remarks</label>
													<div class="col-lg-9 input-group date">
														<textarea id="remark" cols="30" rows="3" class="form-control"></textarea>
													</div>
												</div>
												<div class="form-group row hide" id="after-submit">
													<div class="col-lg-12 text-right">
														<div class="text-right">
															<button type="button" class="btn btn-primary" name="save" id="save" onclick="addDatadb()">Save <i class="icon-pencil5 ml-2"></i></button>
															<?php if ($this->auth->is_have_perm('auth_approve')) : ?>
															<button type="button" class="btn btn-success" name="approve" id="approve" onclick="addDatadb(2)" >Approve <i class="icon-paperplane ml-2" ></input></i>
															<?php endif;?>
														</div>
													</div>
												</div>
											</div>
										</fieldset>
									</div>
								</div>	
								
							</div>
						</div>                    
						<div id="load" style="display:none"></div>
						<div class='hide' id="form3">
							<div class="card">
								<div class="card-header">
									<legend class="font-weight-semibold"><i class="icon-list mr-2"></i>List Item</legend>
								</div>
								<div class="card-body">
									<table id="table-manajemen" class="table table-striped " style="width:100%">
										<thead>
											<tr>
												<th>No.</th>
												<th>Material No</th>
												<th>Material Desc</th>
												<th>Outstanding Qty</th>
												<th>Gr Qty</th>
												<th>Uom</th>
												<th>Remark</th>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
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

				const date = new Date();
				const today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
				var optSimple = {
					format: 'dd-mm-yyyy',
					todayHighlight: true,
					orientation: 'bottom right',
					autoclose: true
				};
				$('#postingDate').datepicker(optSimple);

				$('#delivDate').datepicker(optSimple);
			});
			
			function getDataHeader(poNumber){
				$.post("<?php echo site_url('transaksi1/pofromvendor/getDataPoHeader');?>",{poNumberHeader: poNumber},(data)=>{
					
					const value = JSON.parse(data);
					const year = value.data.DELIV_DATE.substring(0,4);
					const bln =  value.data.DELIV_DATE.substring(6,4);
					const day =  value.data.DELIV_DATE.substring(8,6);
					const date = day+'-'+bln+'-'+year;

					$("#poOrderNumber").val(value.data.DOCNUM);
					$('#vendorCode').val(value.data.VENDOR);
					$('#nameVendor').val(value.data.VENDOR_NAME);
					$("#delivDate").val(date);

					// for combobox
					var objCombo = $('#MatrialGroup option').length;
					if(objCombo > 0){
						$('#MatrialGroup > option').remove();
					}
					var cboMatrialGroup = $('#MatrialGroup');
					cboMatrialGroup.html('<option value="">Select Item</option><option value="all">All</option>');

					$.each(value.dataOption,(val, text)=>{
						cboMatrialGroup.append(`<option value="${text}">${text}</option>`)
					})
					cboMatrialGroup.change(()=>{
						$("#form2").removeClass('hide');
						$("#form3").removeClass('hide');
					})
					var obj = $('#table-manajemen tbody tr').length;

					if(obj>0){
						var tables = $('#table-manajemen').DataTable();

						tables.destroy();
           				$("#table-manajemen > tbody > tr").remove();
					}

					var tbodyTable = $('#table-manajemen tbody');
					value.dataTable.forEach(function(val){
						const qtyOutstanding = parseFloat(val.BSTMG).toFixed(4);
						tbodyTable.append(`<tr>
												<td>${val.no}</td>
												<td>${val.MATNR}</td>
												<td>${val.MAKTX}</td>
												<td>${qtyOutstanding}</td>
												<td><input type="text" class="form-control" name="grOutstanding" id="grOutstanding" required></td>
												<td>${val.BSTME}</td>
												<td><input type="text" class="form-control" name="qc_${val.no}" id="qc_${val.no}"></td>
											</tr>`);
					})

					$("#form1").css('display', '');

					$('#table-manajemen').DataTable({
						"ordering":false, "paging":false,
						"initComplete": function(settings, json) {
							$("#after-submit").removeClass('hide');
						},
					});

				})
			}

			function addDatadb(id_approve = ''){
				poEntry 	= $('#poOrderEntry').val();
				poNumber 	= $('#poOrderNumber').val();
				kdVendor 	= $('#vendorCode').val();
				nmVendor 	= $('#nameVendor').val();
				grNumber 	= $('#grNumber').val();
				outlet 		= $('#outlet').val();
				sLocation 	= $('#storageLocation').val();
				stts 		= $('#status').val();
				matrialGrp 	= $('#MatrialGroup').val();
				pstDate 	= $('#postingDate').val();
				delvDate 	= $('#delivDate').val();
				remarkHead 	= $('#remark').val();
				approve		= id_approve;

				splitDate = pstDate.split('-');
				dayPostingDate = splitDate[0];
				monthPostingDate = splitDate[1];
				yearPostingDate = splitDate[2];
				posDate= `${yearPostingDate}/${monthPostingDate}/${dayPostingDate}`;

				splitdelvDate = delvDate.split('-');
				dayDeliveryDate = splitdelvDate[0];
				monthDeliveryDate = splitdelvDate[1];
				yearDeliveryDate = splitdelvDate[2];
				delDate= `${yearDeliveryDate}/${monthDeliveryDate}/${dayDeliveryDate}`;

				postingDate = new Date(posDate);
				deliverDate = new Date(delDate);
				
				table = $('#table-manajemen > tbody');

				let grQty=[];
				let remark=[];
				let dataValidasiQty = [];
				let dataValidasiLessQty = [];
				let dataValidasiEmptyQty = [];
				let dataValidasiRemark = [];
				let errorMesseges = [];
				let validasiRemark = true;
				let validasiQty = true;
				let validasiLessQty = true;
				let validasiEmptyQty = true;
				let confirmNext;
				table.find('tr').each(function(i, el){
					let td = $(this).find('td');
					if(td.eq(4).find('input').val().trim() == ''){
						dataValidasiEmptyQty.push(td.eq(1).text());
						validasiEmptyQty = false;
					}
					if(td.eq(6).find('input').val().trim() == ''){
						dataValidasiRemark.push(td.eq(1).text());
						validasiRemark = false;
					}
					if(parseFloat(td.eq(4).find('input').val().trim(),10) > parseFloat(td.eq(3).text())){
						dataValidasiQty.push(td.eq(1).text());
						validasiQty = false;
						td.eq(4).removeClass();
						td.eq(4).addClass('bg-danger');
					} else if (parseFloat(td.eq(4).find('input').val().trim(),10) < parseFloat(td.eq(3).text())){
						dataValidasiLessQty.push(td.eq(1).text());
						validasiLessQty = false;
						td.eq(4).removeClass();
						td.eq(4).addClass('bg-warning');
					} else if (parseFloat(td.eq(4).find('input').val().trim(),10) === parseFloat(td.eq(3).text())){
						td.eq(4).removeClass();
						td.eq(4).addClass('bg-success');
					}
					grQty.push(td.eq(4).find('input').val());
					remark.push(td.eq(6).find('input').val());	
				})
				// validasi
				if(pstDate.trim() ==''){
					errorMesseges.push('Posting Date harus di isi. \n');
				}
				if(remarkHead.trim() ==''){
					errorMesseges.push('Remark harus di isi. \n');
				}
				if(!validasiEmptyQty){
					errorMesseges.push(`Gr Quantity untuk Material No. : ${dataValidasiEmptyQty.join()} Tidak boleh Kosong, Harap di isi. \n`);
				}
				if(!validasiRemark){
					errorMesseges.push(`Remark untuk Material No. : ${dataValidasiRemark.join()} Tidak boleh Kosong, Harap di isi. \n`);
				}
				if(postingDate > deliverDate){
					errorMesseges.push('Tanggal Posting tidak boleh lebih besar dari Tanggal Delivery. \n');
				}
				if(!validasiQty){
					errorMesseges.push(`Gr Quantity untuk Material No. : ${dataValidasiQty.join()} Tidak boleh lebih besar dari Outstanding Quantity. \n`);
				}
				if (errorMesseges.length > 0) {
					alert(errorMesseges.join(''));
					if(!validasiLessQty){
						let confirmNext = confirm(`Gr Quantity untuk Material No. : ${dataValidasiLessQty.join()} lebih kecil dari Outstanding Quantity, anda yakin ingin melanjutkan ?`);
						if (!confirmNext) {
							return false;
						}
					}
					return false;
				}
				if(!validasiLessQty){
					let confirmNext = confirm(`Gr Quantity untuk Material No. : ${dataValidasiLessQty.join()} lebih kecil dari Outstanding Quantity, anda yakin ingin melanjutkan ?`);
					if (!confirmNext) {
						return false;
					}
				}
				// validasi
				$('#load').show();
				$("#after-submit").addClass('after-submit');

				setTimeout(() => {
					$.post("<?php echo site_url('transaksi1/pofromvendor/addData')?>", {
						poNo:poEntry, docnum:poNumber, kd_vendor:kdVendor, nm_vendor:nmVendor, grpo_no:grNumber, plant:outlet, storage_location:sLocation, status:stts, item_group_code:matrialGrp, posting_date:pstDate, delivery_date:delvDate, RemarkHead:remarkHead, detail_grQty: grQty,  remark: remark, app: approve
					}, function(){
						$('#load').hide();
					})
					.done(function() {
						location.replace("<?php echo site_url('transaksi1/pofromvendor/')?>");
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