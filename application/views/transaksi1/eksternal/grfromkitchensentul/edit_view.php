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

						<div class="card">
							<div class="card-body">
								<div class="row">
									<div class="col-md-12">
										<fieldset>
											<legend class="font-weight-semibold"><i class="icon-reading mr-2"></i>Good Receipt From Kitchen</legend>
											
											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Data SAP per Tanggal/Jam</label>
												<div class="col-lg-9">Data tidak ditemukan. 
												</div>
											</div>
											
											<div class="form-group row">
												<label class="col-lg-3 col-form-label">ID Transaksi</label>
												<div class="col-lg-9"><input type="text" value="<?php echo $gr_list['id_grpodlv_header']; ?>" class="form-control" readonly></div>
											</div>
											
											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Transfer Slip Number</label>
												<div class="col-lg-9"><input type="text" value="<?php echo $gr_list['do_no1']; ?>" class="form-control" id="U_DocNum" readonly></div>
												<input type="hidden" value="<?php echo $gr_list['do_no']; ?>" readonly>
											</div>
											
											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Goods Receipt Number</label>
												<div class="col-lg-9"><input type="text" value="<?php echo $gr_list['grpodlv_no1']; ?>" class="form-control" readonly></div>
												<input type="hidden" value="<?php echo $gr_list['grpodlv_no']; ?>" readonly>
											</div>
											
											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Outlet</label>
												<div class="col-lg-9"><input type="text" value="<?php echo $gr_list['plant']; ?>" class="form-control" readonly></div>
											</div>
											
											<div class="form-group row" hidden>
												<label class="col-lg-3 col-form-label">Storage Transit Location</label>
												<div class="col-lg-9"><input type="text" value="<?php echo $gr_list['plant']; ?> - <?php echo $gr_list['storage_location']; ?>" class="form-control" readonly></div>
											</div>
											
											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Status</label>
												<div class="col-lg-9">
													<?php 
														if($gr_list['status'] == 1) {
													?>

														<input type="text" value="Not Approved" class="form-control" readonly>
													
													<?php
														} else {
													?>

															<input type="text" value="Approved" class="form-control" readonly>
													<?php
														}
													?>
													<input type="hidden" name="status" value="<?php echo $gr_list['status']?>" />
												
												</div>
													
											</div>
											
											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Material Group</label>
												<div class="col-lg-9"><input type="text" value="<?php echo $gr_list['item_group_code']; ?>" class="form-control" readonly></div>
											</div>
											
											
											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Posting Date</label>
												<div class="col-lg-9"><input type="text" value="<?php echo date("d-m-Y", strtotime($gr_list['posting_date'])); ?>" class="form-control" readonly></div>
											</div>

											<div class="form-group row">
                                                <label class="col-lg-3 col-form-label">Remarks</label>
                                                <div class="col-lg-9 input-group date">
                                                    <textarea id="remark" cols="30" rows="3" class="form-control" <?php $gr_list['status']=='2' ? 'readonly' : ''; ?>><?php echo $gr_list['remark']; ?></textarea>
                                                </div>
											</div>
											
											<?php if($gr_list['status']=='1'): ?>
											<div class="form-group row hide" id="after-submit">
												<div class="col-lg-12 text-right">
													<div class="text-right">
														<button class="btn btn-primary" onclick="btnSave()">
															Save <i class="icon-pencil5 ml-2"></i>
														</button>
														<?php if ($this->auth->is_have_perm('auth_approve')) : ?>
														<button type="submit" class="btn btn-success" onclick="btnSave(2)">
															Approve <i class="icon-paperplane ml-2"></i>
														</button>
														<?php endif;?>
													</div>
												</div>
											</div>
											<?php endif; ?>
										</fieldset>
									</div>
								</div>
							</div>							
						</div>							
						<div id="load" style="display:none"></div>		
						<div class="card">
							<ul class="nav nav-tabs ">
								<li class="nav-item">
									<a href="#gr_list" class="nav-link font-size-sm text-uppercase active" data-toggle="tab" data-tab-remote="">
										GR List
									</a>
								</li>

								<li class="nav-item">
									<a href="#sr_list" class="nav-link font-size-sm text-uppercase" data-toggle="tab" data-tab-remote="<?php echo base_url()?>transaksi1/grfromkitchensentul/showDataSr" data-tab="1">
									Not Send
									</a>
								</li>

								<li class="nav-item">
									<a href="#sr_list_now" class="nav-link font-size-sm text-uppercase" data-toggle="tab" data-tab-remote="<?php echo base_url()?>transaksi1/grfromkitchensentul/showDataGrSend" data-tab="2">
									Not In Request
									</a>
								</li>
							</ul>
							<div class="card-header">
								<legend class="font-weight-semibold"><i class="icon-list mr-2"></i>List Good Receipt From Kitchen</legend>
							</div>
							<div class="tab-content card-body">
								<div class="tab-pane fade active show" id="gr_list">
									<table id="tblWhole" class="table table-striped " style="width:100%">
										<thead>
											<tr>
												<th>No</th>
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
								<div class="tab-pane" id="sr_list">
									<table id="tblStore" class="table table-striped " style="width:100%">
										<thead>
											<tr>
												<th>No</th>
												<th>Material No</th>
												<th>Material Desc</th>
												<th>Quantity</th>
												<th>Uom</th>
												
											</tr>
										</thead>
					
									</table>				
								</div>
								<div class="tab-pane" id="sr_list_now">
									<table id="tblStoreNow" class="table table-striped " style="width:100%">
										<thead>
											<tr>
												<th>No</th>
												<th>Material No</th>
												<th>Material Desc</th>
												<th>Quantity</th>
												<th>Uom</th>
												
											</tr>
										</thead>
					
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
				let IDheader = <?php echo $gr_list['id_grpodlv_header']; ?>;

                $('#tblWhole').DataTable({
					"initComplete": function(settings, json) {
						$("#after-submit").removeClass('hide');
					},
                    "ordering":false,  "paging": false, "searching":true,
                    "ajax": {
                        "url":"<?php echo site_url('transaksi1/grfromkitchensentul/showEditData/'.$gr_list['id_grpodlv_header']);?>",
                        "type":"POST",
					},
					"columnDefs": [
			 			{"className": "dt-center", "targets": "_all"}
			 		],
                    "columns": [
                        {"data":"ID","className":"dt-center", render:function(data, type, row, meta){
                            rr=`<span>${row['no']}</span>
								<input type="hidden" style="text-align: right;" class="form-control" value="${data}"> `;
                            return rr;
                        }},
                        {"data":"material_no"},
                        {"data":"material_desc"},
						{"data":"sr_qty"},
						{"data":"tf_qty"},
						{"data":"gr_qty", "className":"dt-center", render:function(data, type, row, meta){
							let readOnly =  row['status'] == 2 ? 'readonly' : '';
							let gr_qty =  data
                            rr=`<input type="text" style="text-align: right;" class="form-control" value="${gr_qty}" ${readOnly} > `;
                            return rr;
                        }},
                        {"data":"uom"},
                    ]
                });

				tableStore = $("#tblStore").DataTable({
					"ordering":false,  
                    "paging": false, 
                    "searching":false,
				});

				tableStoreNow = $("#tblStoreNow").DataTable({
					"ordering":false,  
                    "paging": false, 
                    "searching":false,
				});


				$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
					var target = $(e.target).attr('href');
					var obj = $(this).attr('data-tab-remote');
					var dataTab = $(this).attr('data-tab');
					let getTableStore = $("#tblStore").DataTable();
					let getTableStoreNow = $("#tblStoreNow").DataTable();
					countNow = getTableStoreNow.rows().count() + 1;
					count = getTableStore.rows().count() + 1;

					const noSR = document.getElementById('U_DocNum').value;

					if(dataTab == 1){
						if(count > 1) {
							getTableStore.clear();
						}

						$.post(obj,{SrNumber:noSR},(data)=>{
							const res = JSON.parse(data);
							if(res.hasOwnProperty('data')){

								let i=0;
								for(let key in res.data){ 
									i++;
									
									getTableStore.row.add({ 
										"0":`${i}`,
										"1":`${res.data[key].ItemCode}`,
										"2":`${res.data[key].Dscription}`,
										"3":`${res.data[key].Quantity.toFixed(4)}`,
										"4":`${res.data[key].UOM}`,
									}).draw();
								}
							}
						})
					} else if(dataTab == 2) {
						if(countNow > 1) {
							getTableStoreNow.clear();
						}

						$.post(obj,{SrNumber:noSR},(data)=>{
							const res = JSON.parse(data);
							if(res.hasOwnProperty('data')){

								let i=0;
								for(let key in res.data){ 
									i++;
									
									getTableStoreNow.row.add({ 
										"0":`${i}`,
										"1":`${res.data[key].ItemCode}`,
										"2":`${res.data[key].Dscription}`,
										"3":`${res.data[key].Quantity.toFixed(4)}`,
										"4":`${res.data[key].UOM}`,
									}).draw();
								}
							}
						})
					}
				})

				$("#cancelRecord").click(function(){
					const idGrpoHeader = $('#id_grpo_header').val();
                    let deleteidArr=[];
                    $("input:checkbox[class=check_delete]:checked").each(function(){
                        deleteidArr.push($(this).val());
                    })


                    // mengecek ckeckbox tercheck atau tidak
                    if(deleteidArr.length > 0){
                        var confirmDelete = confirm("Apa Kamu Yakin Akan Membatalkan Good Receipt from Kitchen ini?");
                        if(confirmDelete == true){
                            $.ajax({
                                url:"<?php echo site_url('transaksi1/grfromkitchensentul/cancelPoFromVendor');?>", //masukan url untuk delete
                                type: "post",
                                data:{deleteArr: deleteidArr, id_grpo_header:idGrpoHeader},
                                success:function(res) {
									location.reload(true);
                                }
                            });
                        }
                    }
                });

				checkcheckbox = () => {
                    
                    const lengthcheck = $(".check_delete").length;
                    
                    let totalChecked = 0;
                    $(".check_delete").each(function(){
                        if($(this).is(":checked")){
                            totalChecked += 1;
                        }
                    });
                }
			});
			
			function btnSave(id_approve=''){ 

				table = $('#tblWhole > tbody');

				let grDetail=[];
				let dataValidasiQty = [];
				let dataValidasiLessQty = [];
				let dataValidasiEmptyQty = [];
				let errorMesseges = [];
				let validasiQty = true;
				let validasiLessQty = true;
				let validasiEmptyQty = true;
				let remark = $('#remark').val();
				table.find('tr').each(function(i, el){
					
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
					
					const det = {
						id_grpodlv_detail: td.eq(0).find('input').val(),
						gr_qty: td.eq(5).find('input').val(),
					}
					grDetail.push(det);
				})

				// validasi
				if(remark.trim() ==''){
					errorMesseges.push('Remark harus di isi. \n');
				}
				if(!validasiEmptyQty){
					errorMesseges.push(`Gr Quantity untuk Material No. : ${dataValidasiEmptyQty.join()} Tidak boleh Kosong, Harap di isi. \n`);
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
					$.post("<?php echo site_url('transaksi1/grfromkitchensentul/updateDataGR')?>", {
						IDheader : <?php echo $gr_list['id_grpodlv_header']; ?>,
						id_approve: id_approve,
						remark: remark,
						grDetail: grDetail
					}, function(){
						$('#load').hide();
					})
					.done(function() {
						location.replace("<?php echo site_url('transaksi1/grfromkitchensentul/')?>");
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