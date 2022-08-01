<!DOCTYPE html>
<html lang="en">
	<head>
		<?php  $this->load->view("_template/head.php")?>
		<style>
			.hide,
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
					<input type="hidden" name="status" id="status" value="<?=$grsto_header['status']?>">
						<div class="card">
							<div class="card-body">
								<div class="row">
									<div class="col-md-12">
										<fieldset>
											<legend class="font-weight-semibold"><i class="icon-reading mr-2"></i>Transfer In Inter Outlet</legend>

											<div class="form-group row">
												<label class="col-lg-3 col-form-label">ID Transaksi</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" value="<?=$grsto_header['id_grsto_header']?>" id="id_grsto_header" nama="id_grsto_header" readOnly>
												</div>
                                            </div>
											
											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Store Room Reques(SR) Number</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" value="<?=$grsto_header['po_no1']?>" name="srEntry1" id="srEntry1" readOnly>
													<input type="hidden" value="<?=$grsto_header['po_no']?>" name="srEntry" id="srEntry" readOnly>
												</div>
                                            </div>

											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Transfer Out Number</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" readonly="" value="<?=$grsto_header['transfer_out_number1']?>" name="toNumb1" id="toNumb1">
													<input type="hidden" class="form-control" readonly="" value="<?=$grsto_header['transfer_out_number']?>" name="toNumb" id="toNumb">
												</div>
											</div>
											
											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Transfer In Number</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" value="<?= $grsto_header['status'] == 2 ? $grsto_header['transfer_in_number1'] :'(Auto Number after Posting to SAP)'?>" id="transfer_slip_number1" nama="transfer_slip_number1" readOnly>
													<input type="hidden" class="form-control" value="<?= $grsto_header['status'] == 2 ? $grsto_header['transfer_in_number'] :'(Auto Number after Posting to SAP)'?>" id="transfer_slip_number" nama="transfer_slip_number" readOnly>
												</div>
											</div>
											
											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Outlet From</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" readonly="" value="<?=$grsto_header['plant']?>" name="outlet" id="outlet">
												</div>
											</div>
											
											<div class="form-group row" hidden>
												<label class="col-lg-3 col-form-label">Storage Location</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" readonly="" value="<?=$grsto_header['storage_location']?>" name="storageLocation" id="storageLocation">
												</div>
											</div>
											
											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Transfer To Outlet</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" readonly="" value="<?=$grsto_header['to_plant']?>" name="rto" id="rto">
												</div>
											</div>

											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Delivery Date</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" readonly="" value="<?=date("d-m-Y", strtotime($grsto_header['delivery_date']))?>" id="deliveryDate"> 
												</div>
											</div>

											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Status</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" readonly="" value="<?=$grsto_header['status_string']?>">
												</div>
											</div>
											
											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Material Group</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" value="<?=$grsto_header['item_group_code']?>" name="MatrialGroup" id="MatrialGroup" readonly>
												</div>
											</div>

											<div class="form-group row" >
											<label class="col-lg-3 col-form-label">Posting Date</label>
												<div class="col-lg-9 input-group date">
													<input type="text" class="form-control" id="postingDate" value="<?= date("d-m-Y", strtotime($grsto_header['posting_date']))?>" readonly>
													<div class="input-group-prepend">
														<span class="input-group-text" id="basic-addon1">
															<i class="icon-calendar"></i>
														</span>
													</div>
												</div>
											</div>

											<?php if($grsto_header['status']=='1'): ?>
												<div class="form-group row hide" id="after-submit">
													<div class="col-lg-12 text-right">
														<div class="text-right">
															<button type="button" class="btn btn-primary" id="btn-update" onclick="addDatadb()">Save <i class="icon-pencil5 ml-2"></i></button>
															<?php if ($this->auth->is_have_perm('auth_approve')) : ?>
															<button type="button" class="btn btn-success" id="btn-update" onclick="addDatadb(2)">Approve <i class="icon-paperplane ml-2"></i></button>
															<?php endif;?>
														</div>
													</div>
												</div>
											<?php endif;?>
											
										</fieldset>
									</div>
								</div>	
							</div>
						</div>                    
						<div id="load" style="display:none"></div>  
						<div class="card">
							<div class="card-header">
								<legend class="font-weight-semibold"><i class="icon-list mr-2"></i>List Transfer In Inter Outlet</legend>
							</div>
							<div class="card-body">
								<table id="table-manajemen" class="table table-striped " style="width:100%">
									<thead>
										<tr>
											<th>*</th>
											<th>No</th>
											<th>Material No</th>
											<th>Material Desc</th>
											<th>SR Quantity</th>
											<th>TF Qty</th>
											<th>GR Quantity</th>
											<th>Uom</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
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
                let id_grsto_header = $('#id_grsto_header').val();
				let stts = $('#status').val();

				const date = new Date();
				const today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
				var optSimple = {
					format: 'dd-mm-yyyy',
					todayHighlight: true,
					orientation: 'bottom right',
					autoclose: true
				};
				$('#postingDate').datepicker(optSimple);

				table = $("#table-manajemen").DataTable({
					"initComplete": function(settings, json) {
						$("#after-submit").removeClass('hide');
					},
					"ordering":false,
					"paging":false,
					"ajax": {
							"url":"<?php echo site_url('transaksi1/transferininteroutlet/showGistonewOutDetail');?>",
							"data":{ id: id_grsto_header, status: stts },
							"type":"POST"
						},
					"columns": [
						
						{"data":"id_gistonew_out_detail", "className":"dt-center", render:function(data, type, row, meta){
								rr=row['status']==2 ? '' : `<input type="checkbox" class="check_delete" id="chk_${data}" value="${data}" >`;
								return rr;
						}},
						{"data":"no", "className":"dt-center"},
						{"data":"material_no", "className":"dt-center"},
						{"data":"material_desc"},
						{"data":"in_whs_qty", "className":"dt-center"},
						{"data":"outstanding_qty", "className":"dt-center"},
						{"data":"gr_quantity", "className":"dt-center",render:function(data, type, row, meta){
							rr=  `<input type="text" class="form-control gr_qty" id="gr_qty_${data}" value="${data}" ${row['status']==1 ?'':'readonly'}>`;
							return rr;
						}},
						{"data":"uom"}
					],
					drawCallback: function() {
						$('.form-control-select2').select2();
					}
				});


				$("#cancelRecord").click(function(){
					const id_grsto_header = $('#id_grsto_header').val();
                    let deleteidArr=[];
                    $("input:checkbox[class=check_delete]:checked").each(function(){
                        deleteidArr.push($(this).val());
                    })

                    // mengecek ckeckbox tercheck atau tidak
                    if(deleteidArr.length > 0){
                        var confirmDelete = confirm("Apa Kamu Yakin Akan Membatalkan Transfer In Inter Outlet ini?");
                        if(confirmDelete == true){
                            $.ajax({
                                url:"<?php echo site_url('transaksi1/transferininteroutlet/cancelGistonewOut');?>", //masukan url untuk delete
                                type: "post",
                                data:{deleteArr: deleteidArr, id_grsto_header:id_grsto_header},
                                success:function(res) {
									location.reload(true);
                                }
                            });
                        }
                    }
				});
				
				$("#deleteRecord").click(function(){
					let deleteidArr=[];
					let getTable = $("#table-manajemen").DataTable();
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
			
			function onAddrow(){
				let getTable = $("#table-manajemen").DataTable();
				count = getTable.rows().count() + 1;
				let elementSelect = document.getElementsByClassName(`dt_${count}`);
				var doNo = $('#srEntry').val();
				const matrialGroup = $('#MatrialGroup').val() ? $('#MatrialGroup').val() : 'all';
				
				getTable.row.add({
					"no":count,
					"material_no":`<select class="form-control form-control-select2 dt_${count} testSelect" data-live-search="true" id="selectDetailMatrial" data-count="${count}">
									<option value="">Select Item</option>
									${showMatrialDetailData(matrialGroup, doNo, elementSelect)}
								</select>`,
					"material_desc":"",
					"in_whs_qty":"",
					"outstanding_qty":"",
					"gr_quantity":"",
					"uom_req":"",
					"uom":""
					}).draw();
					count++;

				tbody = $("#table-manajemen tbody");
				tbody.on('change','.testSelect', function(){
					tr = $(this).closest('tr');
					no = tr[0].rowIndex;
					id = $('.dt_'+no).val();
					setValueTable(doNo,id,no);
				});
			}

			function showMatrialDetailData(cboMatrialGroup='',do_no='', selectTable){
				
				const select = selectTable ? selectTable : $('#matrialGroupDetail');

				$.post("<?php echo site_url('transaksi1/transferininteroutlet/getDetailsTransferOut');?>",{ cboMatrialGroup: cboMatrialGroup,doNo: do_no},(data)=>{
					obj = JSON.parse(data);
					for(let key in obj){
						if(obj.hasOwnProperty(key)){
							$("<option />",{value:obj[key].MATNR, text:obj[key].MATNR +' - '+ obj[key].MAKTX}).appendTo(select);
						}
					}
				})		
			}

			function setValueTable(doNo='',id,no){
				doNo = doNo ? doNo : $('#srEntry').val();
				table = document.getElementById("table-manajemen").rows[no].cells;
				$.post(
					"<?php echo site_url('transaksi1/transferininteroutlet/getdataDetailMaterialSelect')?>",{ MATNR:id, do_no:doNo },(res)=>{
						matSelect = JSON.parse(res);

						for(let i in matSelect){
							if(matSelect.hasOwnProperty(i)){
								table[2].innerHTML = `<td>${matSelect[i].MATNR}</td>`;
								table[3].innerHTML = matSelect[i].MAKTX;
								table[4].innerHTML = matSelect[i].In_Whs_Qty;
								table[5].innerHTML = matSelect[i].LFIMG;
								table[7].innerHTML = matSelect[i].VRKME
								table[8].innerHTML = matSelect[i].VRKME
							}
						}
					}
				)
			}

			function addDatadb(id_approve=''){
				const id_grsto_header = $('#id_grsto_header').val();
				const srEntry = $('#srEntry').val();
				const srEntry1 = $('#srEntry1').val();
				const approve = id_approve;
				const postingDate= document.getElementById('postingDate').value;
				const DelivDate = document.getElementById('deliveryDate').value;

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

				const tbodyTable = $("#table-manajemen > tbody");
				let matrial_no=[];
				let matrialDesc =[];
				let srQty=[];
				let tfQty =[];
				let grQty =[];
				let uom =[];
				let dataValidasiQty = [];
				let dataValidasiLessQty = [];
				let dataValidasiEmptyQty = [];
				let errorMesseges = [];
				let validasiQty = true;
				let validasiLessQty = true;
				let validasiEmptyQty = true;
				tbodyTable.find('tr').each(function(i,el){
					let td = $(this).find('td');
					if(td.eq(6).find('input').val().trim() == ''){
						dataValidasiEmptyQty.push(td.eq(2).text());
						validasiEmptyQty = false;
					}
					if(parseFloat(td.eq(6).find('input').val().trim(),10) > parseFloat(td.eq(5).text())){
						dataValidasiQty.push(td.eq(2).text());
						validasiQty = false;
						td.eq(6).removeClass();
						td.eq(6).addClass('bg-danger');
					} else if (parseFloat(td.eq(6).find('input').val().trim(),10) < parseFloat(td.eq(5).text())){
						dataValidasiLessQty.push(td.eq(2).text());
						validasiLessQty = false;
						td.eq(6).removeClass();
						td.eq(6).addClass('bg-warning');
					} else if (parseFloat(td.eq(6).find('input').val().trim(),10) === parseFloat(td.eq(5).text())){
						td.eq(6).removeClass();
						td.eq(6).addClass('bg-success');
					}
					matrial_no.push(td.eq(2).text().trim());
					matrialDesc.push(td.eq(3).text());
					srQty.push(td.eq(4).text());
					tfQty.push(td.eq(5).text());
					grQty.push(td.eq(6).find('input').val());
					uom.push(td.eq(7).text());
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
					$.post("<?php echo site_url('transaksi1/transferininteroutlet/addDataUpdate')?>", {
						idgrsto_header: id_grsto_header, poNo: srEntry, poNo1: srEntry1, appr:approve, pstDate: postingDate, detMatrialNo: matrial_no, detMatrialDesc: matrialDesc, detSrQty:srQty, detTftQty:tfQty, detGrQty: grQty, detUom: uom
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