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
					<form action="#" method="POST">
						<div class="card">
							<div class="card-body">
								<div class="row">
									<div class="col-md-12">
										<fieldset>
											<legend class="font-weight-semibold"><i class="icon-reading mr-2"></i>Transfer In Inter Outlet</legend>

											<div class="form-group row">
												<label class="col-lg-3 col-form-label"><b>Data SAP per Tanggal/Jam</b></label>
												<div class="col-lg-9"><b>Data tidak ditemukan.</b>
												</div>
											</div>
											
											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Store Room Request (SR) Number (TF Out Number)</label>
												<div class="col-lg-9">
													<select class="form-control form-control-select2" data-live-search="true"
													name="srEntry" id="srEntry" onchange="getDataHeader(this.value)">
														<option value="">Select Item</option>
														<?php foreach($do_no as $key=>$value):?>
															<option value="<?=$key?>"><?=$value?></option>
														<?php endforeach;?>
													</select>
												</div>
											</div>

										<div id='form1' style="display:none">

											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Transfer Out No</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" readonly="" value="" name="toNumb1" id="toNumb1">
													<input type="hidden" class="form-control" readonly="" value="" name="toNumb" id="toNumb">
												</div>
											</div>
											
											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Transfer In Number</label>
												<div class="col-lg-9"><input type="text" class="form-control" readonly="" value="(Auto Number after Posting to SAP)." name="transferSlipNumber" id="transferSlipNumber">
												</div>
											</div>

											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Outlet</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" readonly="" value="<?= $plant ?>" name="outlet" id="outlet">
												</div>
											</div>
											
											<div class="form-group row" hidden>
												<label class="col-lg-3 col-form-label">Storage Location</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" readonly="" value="<?= $storage_location ?>"name="storageLocation" id="storageLocation">
												</div>
											</div>

											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Delivery Outlet</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" readonly=""  name="rto" id="rto">
												</div>
											</div>

											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Delivery Date</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" readonly="" value="" name="delivDate" id="delivDate">
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
											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Posting Date</label>
												<div class="col-lg-9 input-group date">
													<input type="text" class="form-control" readonly="" id="postingDate">
													<div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1">
                                                            <i class="icon-calendar"></i>
                                                        </span>
                                                    </div>
												</div>
											</div>
											
											<div class="form-group row hide" id="after-submit">
												<div class="col-lg-12 text-right">
													<div class="text-right">
														<button type="button" class="btn btn-primary" name="save" id="save" onclick="addDatadb()">Save <i class="icon-pencil5 ml-2"></i></button>
														<?php if ($this->auth->is_have_perm('auth_approve')) : ?>
														<button type="button" class="btn btn-success" name="approve" id="approve" onclick="addDatadb(2)">Approve <i class="icon-paperplane ml-2"></i></button>
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
								<legend class="font-weight-semibold"><i class="icon-list mr-2"></i>List Transfer In Inter Outlet</legend>
							</div>
							<div class="card-body">
								<table id="tblWhole" class="table table-striped " style="width:100%">
									<thead>
										<tr>
											<th style="text-align: left">No</th>
											<th>Material No</th>
											<th>Material Desc</th>
											<th>SR Qty</th>
											<th>TF Qty</th>
											<th>GR Qty</th>
											<th>Uom</th>
										</tr>
									</thead>
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
				table = $("#tblWhole").DataTable({
					"ordering":false,
					"paging":false,
					drawCallback: function() {
						$('.form-control-select2').select2();
					}
				});

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

				$("#deleteRecord").click(function(){
					let deleteidArr=[];
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

				
			});
			
			function getDataHeader(srNumber){
				
				$.post("<?php echo site_url('transaksi1/transferininteroutlet/getHeaderTransferIn');?>",{srNumberHeader: srNumber},(data)=>{
					const value = JSON.parse(data);
					const year = value.data.DELIV_DATE ? value.data.DELIV_DATE.substring(0,4) :'1970';
					const bln =  value.data.DELIV_DATE ? value.data.DELIV_DATE.substring(5,7) : '01';
					const day =  value.data.DELIV_DATE ? value.data.DELIV_DATE.substring(8,10) : '01';
					let date = day+'-'+bln+'-'+year;
					
					$("#delivDate").val(date);
					$("#rto").val(value.data.RECEIVING_PLANT);
					$("#toNumb1").val(value.data.MBLNR1);
					$("#toNumb").val(value.data.MBLNR);
					$("#storageLocation").val();
					$("#rto").val(value.data.SUPPL_PLANT+' - '+value.data.SPLANT_NAME);
					const poNo = $("#srEntry").val();

					var objCombo = $('#MatrialGroup option').length;
					if(objCombo > 0){
						$('#MatrialGroup > option').remove();
					}
					var cboMatrialGroup = $('#MatrialGroup');
					cboMatrialGroup.html('<option value="">Select Item</option><option value="all">All</option>');


					$.each(value.dataOption,(val, text)=>{
						$(`<option value="${text.DISPO}">${text.DSNAM}</option>`).appendTo(cboMatrialGroup);
					})
					$('#srEntry').prop('disabled', 'disabled')
					cboMatrialGroup.change(()=>{
						showMatrialDetailData(cboMatrialGroup.val(),poNo, (dataDet)=> {
							if(dataDet.length > 0) {
								$('#MatrialGroup').prop('disabled', 'disabled')
								// var srEntryOpt = $('#srEntry  option:selected').val()
								// console.log("SR ENTRY : ", srEntryOpt)
								// if(srEntryOpt === '123') {
								// 	$('#srEntry').removeAttr('disabled')
								// 	$('#MatrialGroup').removeAttr('disabled')
								// }
							}
						});
						$("#form2").removeClass('hide');
						$("#form3").removeClass('hide');
					})
					

				})
				$("#form1").css('display', '');
			}

			function showMatrialDetailData(cboMatrialGroup='',poNo, cb){
				const MatrialGroup = cboMatrialGroup;
				let valueLen = []

				var obj = $('#tblWhole tbody tr').length;

				if(obj>0){
					const tables = $('#tblWhole').DataTable();

					tables.destroy();
					$("#tblWhole > tbody > tr").remove();
				}

				dataTable = $('#tblWhole').DataTable({
					"ordering":false,  "paging": false, "searching":true,
					"initComplete": function(settings, json) {
						$("#after-submit").removeClass('hide');
					},
                    "ajax": {
                        "url":"<?php echo site_url('transaksi1/transferininteroutlet/getDetailsTransferIn');?>",
                        "type":"POST",
						"data":{matrialGroup: MatrialGroup, po_no:poNo},
						dataSrc: function(data) {
							for(let [key,value] of Object.entries(data)) {
								console.log(value)
								valueLen.push(value)
							}
							cb(valueLen[0])
							return valueLen[0]
						}
                    },
                    "columns": [
						{data:"NO","className":"dt-center" ,render:function(data, type, row, meta){
							rr = `<input type="hidden" value="${row['Item']}">`;
							return rr+ data;
						}},
						{data:"MATNR"},
						{data:"MAKTX"},
						{data:"SRQUANTITY"},
						{data:"TFQUANTITY"},
						{data:"GRQUANTITY","className":"dt-center",render:function(data, type, row, meta){
							rr=  `<input type="text" class="form-control gr_qty" id="gr_qty_${data}" value="">`;
							return rr;
						}},
						{data:"UOM"}
                    ]
                });
					
			}

			function addDatadb(id_approve=''){

				const requestResponLong= document.getElementById('srEntry');
				const rrText = requestResponLong.options[requestResponLong.selectedIndex].text;
				const rrArr = rrText.split(' - ');
				const requestRespon1 = rrArr[0].split(' ');
				const requestRespon= document.getElementById('srEntry').value.split('_');
				const tranferOutNumb = document.getElementById('toNumb').value;
				const tranferOutNumb1 = document.getElementById('toNumb1').value;
				const outlet = document.getElementById('outlet').value;
				const storageLocation = document.getElementById('storageLocation').value;
				const matrialGroup= document.getElementById('MatrialGroup').value;
				const status= document.getElementById('status').value;
				const rto= document.getElementById('rto').value;
				const DelivDate = document.getElementById('delivDate').value;
				const postingDate= document.getElementById('postingDate').value;
				const approve = id_approve;

				splitDate = postingDate.split('-');
				dayPostingDate = splitDate[0];
				monthPostingDate = splitDate[1];
				yearPostingDate = splitDate[2];
				posDate= `${yearPostingDate}/${monthPostingDate}/${dayPostingDate}`;

				splitdelvDate = DelivDate.split('-');
				dayDeliveryDate = splitdelvDate[0];
				monthDeliveryDate = splitdelvDate[1];
				yearDeliveryDate = splitdelvDate[2];
				delDate= `${yearDeliveryDate}/${monthDeliveryDate}/${dayDeliveryDate}`;

				datePosting = new Date(posDate);
				deliverDate = new Date(delDate);

				const tbodyTable = $('#tblWhole > tbody');
				let item = [];
				let matrialNo =[];
				let matrialDesc =[];
				let srQty = [];
				let outStdQty = [];
				let qty =[];
				let uom =[];
				let dataValidasiQty = [];
				let dataValidasiLessQty = [];
				let dataValidasiEmptyQty = [];
				let errorMesseges = [];
				let validasiQty = true;
				let validasiLessQty = true;
				let validasiEmptyQty = true;
				tbodyTable.find('tr').each(function(i, el){
					let td = $(this).find('td');
					
					if(td.eq(5).find('input').val().trim() == ''){
						dataValidasiEmptyQty.push(td.eq(1).text());
						validasiEmptyQty = false;
					}
					if(parseFloat(td.eq(5).find('input').val().trim(),10) > parseFloat(td.eq(4).text())){
						dataValidasiQty.push(td.eq(1).text());
						validasiQty = false;
						td.eq(5).removeClass();
						td.eq(5).addClass('bg-danger');
					} else if (parseFloat(td.eq(5).find('input').val().trim(),10) < parseFloat(td.eq(4).text())){
						dataValidasiLessQty.push(td.eq(1).text());
						validasiLessQty = false;
						td.eq(5).removeClass();
						td.eq(5).addClass('bg-warning');
					} else if (parseFloat(td.eq(5).find('input').val().trim(),10) === parseFloat(td.eq(4).text())){
						td.eq(5).removeClass();
						td.eq(5).addClass('bg-success');
					}
					
					item.push(td.eq(0).find('input').val());
					matrialNo.push(td.eq(1).text()); 
					matrialDesc.push(td.eq(2).text());
					srQty.push(parseFloat(td.eq(3).text()));
					outStdQty.push(parseFloat(td.eq(4).text()));
					qty.push(parseInt(td.eq(5).find('input').val(),10));
					uom.push(td.eq(6).text());
				})

				// validasi
				if(!validasiEmptyQty){
					errorMesseges.push(`Gr Quantity untuk Material No. : ${dataValidasiEmptyQty.join()} Tidak boleh Kosong, Harap di isi. \n`);
				}
				if(datePosting > deliverDate){
					errorMesseges.push('Tanggal Posting tidak boleh lebih besar dari Tanggal Delivery. \n');
				}
				if(!validasiQty){
					errorMesseges.push(`Gr Quantity untuk Material No. : ${dataValidasiQty.join()} Tidak boleh lebih besar dari Tf Quantity. \n`);
				}
				if (errorMesseges.length > 0) {
					alert(errorMesseges.join(''));
					if(!validasiLessQty){
						let confirmNext = confirm(`Gr Quantity untuk Material No. : ${dataValidasiLessQty.join()} lebih kecil dari Tf Quantity, anda yakin ingin melanjutkan ?`);
						if (!confirmNext) {
							return false;
						}
					}
					return false;
				}
				if(!validasiLessQty){
					let confirmNext = confirm(`Gr Quantity untuk Material No. : ${dataValidasiLessQty.join()} lebih kecil dari Tf Quantity, anda yakin ingin melanjutkan ?`);
					if (!confirmNext) {
						return false;
					}
				}
				// validasi

				$('#load').show();
				$("#after-submit").addClass('after-submit');

				setTimeout(() => {
					$.post("<?php echo site_url('transaksi1/transferininteroutlet/addData')?>", {
						reqRes: requestRespon[0], reqRes1: requestRespon1[0], toNumb: tranferOutNumb, toNumb1: tranferOutNumb1, storageLoc: storageLocation, plant:outlet, matGrp: matrialGroup, stts: status, Rto:rto, delivDate:DelivDate, pstDate: postingDate, detMatrialNo: matrialNo, appr: approve, detMatrialDesc: matrialDesc, detsrQty: srQty, detOutStdQty: outStdQty, detQty: qty, detUom: uom, detposnr: item
					}, function(){
						$('#load').hide();
					})
					.done(function() {
						location.replace("<?php echo site_url('transaksi1/transferininteroutlet/')?>");
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