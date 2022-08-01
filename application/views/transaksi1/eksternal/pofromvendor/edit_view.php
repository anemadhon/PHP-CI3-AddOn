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
					<input type="hidden" name="status" id="status" value="<?=$grpo_header['status']?>">
						<div class="card">
							<div class="card-body">
								<div class="row">
									<div class="col-md-12">
										<fieldset>
											<legend class="font-weight-semibold"><i class="icon-reading mr-2"></i>PO from Vendor</legend>

											<div class="form-group row">
												<label class="col-lg-3 col-form-label"><b>Data SAP per Tanggal/Jam</b></label>
												<div class="col-lg-9"><b></b>
												</div>
											</div>
											
											<div class="form-group row">
												<label class="col-lg-3 col-form-label">ID Transaksi</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" readonly="" value="<?= $grpo_header['id_grpo_header']?>" id="id_grpo_header" nama="id_grpo_header">
												</div>
											</div>
											
											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Purchase Order Entry</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" readonly="" value="<?= $grpo_header['po_no']?>" id="po_no" nama="po_no">
												</div>
											</div>
											
											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Purchase Order Number</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" readonly="" value="<?= $grpo_header['docnum']?>" id="docnum" nama="docnum">
												</div>
											</div>
											
											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Vendor Code</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" readonly="" value="<?= $grpo_header['kd_vendor']?>" id="kd_vendor" nama="kd_vendor">
												</div>
											</div>
											
											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Vendor Name</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" readonly="" value="<?= $grpo_header['nm_vendor']?>" id="nm_vendor" nama="nm_vendor">
												</div>
											</div>
											
											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Delivery Date</label>
												<div class="col-lg-9 input-group date">
													<input type="text" class="form-control" id="delivDate" value="<?= date("d-m-Y", strtotime($grpo_header['delivery_date']))?>" readonly="" id="delivery_date" nama="delivery_date">
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
													<input type="text" class="form-control" readonly="" value="<?= $grpo_header['status'] == 2 ? $grpo_header['grpo_no'] :'(Auto Number after Posting to SAP)'?>" id="grpo_no" nama="grpo_no">
												</div>
											</div>
											
											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Outlet</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" readonly="" value="<?= $plant ?>" id="outlet" nama="outlet">
												</div>
											</div>
											
											<div class="form-group row" hidden>
												<label class="col-lg-3 col-form-label">Storage Location</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" readonly="" value="<?= $storage_location ?>">
												</div>
											</div>
											
											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Status</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" readonly="" value="<?= $grpo_header['status_string']?>">
												</div>
											</div>
											
											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Material Group</label>
												<div class="col-lg-9">
													<input type="text" readonly="" value="All" class="form-control">
												</div>
											</div>

											<div class="form-group row" >
											<label class="col-lg-3 col-form-label">Posting Date</label>
												<div class="col-lg-9 input-group date">
													<input type="text" class="form-control" id="postingDate" value="<?= date("d-m-Y", strtotime($grpo_header['posting_date']))?>" <?php if($grpo_header['status']=='2'):?>readonly=""<?php endif; ?>>
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
                                                    <textarea id="remark" cols="30" rows="3" class="form-control" <?php ($grpo_header['status']=='2' ? 'readonly' : ''); ?>><?php echo $grpo_header['remarkHead']; ?></textarea>
                                                </div>
											</div>

											<?php if($grpo_header['status']=='1'): ?>
											<div class="form-group row hide" id="after-submit">
												<div class="col-lg-12 text-right">
													<div class="text-right">
														<button type="button" class="btn btn-primary" id="btn-update">Save <i class="icon-pencil5 ml-2"></i></button>
														<?php if ($this->auth->is_have_perm('auth_approve')) : ?>
														<button type="button" class="btn btn-success" id="btn-approve">Approve <i class="icon-paperplane ml-2"></i></button>
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
								<legend class="font-weight-semibold"><i class="icon-list mr-2"></i>List PO from Vendor</legend>
							</div>
							<div class="card-body">
								<table id="table-manajemen" class="table table-striped " style="width:100%">
									<thead>
										<tr>
											<th style="text-align: left">*</th>
											<th>Material No</th>
											<th>Material Desc</th>
											<th>Outstanding Qty</th>
											<th>Gr Qty</th>
											<th>Uom</th>
											<th>QC</th>
											<th><?php if($grpo_header['status']=='1'): ?>Cancel<?php endif;?></th>
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
				let id_grpo_header = $('#id_grpo_header').val();
				let stts = $('#status').val();

                $('#table-manajemen').DataTable({
					"initComplete": function(settings, json) {
						$("#after-submit").removeClass('hide');
					},
                    "ordering":false, "paging":false,
                    "ajax": {
                        "url":"<?php echo site_url('transaksi1/pofromvendor/showDeatailEdit');?>",
						"data":{ id: id_grpo_header, status: stts },
                        "type":"POST"
                    },
                    "columns": [
                        {"data":"no", "className":"dt-center"},
                        {"data":"material_no", "className":"dt-center"},
                        {"data":"material_desc"},
                        {"data":"outstanding_qty", "className":"dt-center"},
                        {"data":"gr_quantity", "className":"dt-center", render:function(data, type, row, meta){
							
							rr= row['status'] == 1 ? `<input type="text" class="form-control" id="gr_qty_${data}" value="${data}">`: `${row['gr_quantity']}`;
                            return rr;
						}},
                        {"data":"uom", "className":"dt-center"},
						{"data":"qc", "className":"dt-center", render:function(data, type, row, meta){
                            rr=`<input type="text" class="form-control" value="${data}" id="${row['id_grpo_detail']}">`;
                            return rr;
                        }},
						{"data":"id_grpo_detail", "className":"dt-center", render:function(data, type, row, meta){
                            rr=row['status'] == 2 ? '' :`<input type="checkbox" class="check_delete" id="chk_${data}" value="${data}" onclick="checkcheckbox();" hidden>`;
                            return rr;
                        }},
                    ]
                });

				$("#btn-update").click(function(){
					const postingDate = $('#postingDate').val();
					const delvDate 	= $('#delivDate').val();
					const idGrpoHeader = $('#id_grpo_header').val();
					const remarkHead = $('#remark').val();

					table = $('#table-manajemen > tbody');

					splitDate = postingDate.split('-');
					dayPostingDate = splitDate[0];
					monthPostingDate = splitDate[1];
					yearPostingDate = splitDate[2];
					posDate= `${yearPostingDate}/${monthPostingDate}/${dayPostingDate}`;

					splitdelvDate = delvDate.split('-');
					dayDeliveryDate = splitdelvDate[0];
					monthDeliveryDate = splitdelvDate[1];
					yearDeliveryDate = splitdelvDate[2];
					delDate= `${yearDeliveryDate}/${monthDeliveryDate}/${dayDeliveryDate}`;

					datePosting = new Date(posDate);
					deliverDate = new Date(delDate);

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
					let id_grpo_detail=[];

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
							td.eq(4).addClass('bg-danger');
						} else if (parseFloat(td.eq(4).find('input').val().trim(),10) < parseFloat(td.eq(3).text())){
							dataValidasiLessQty.push(td.eq(1).text());
							validasiLessQty = false;
							td.eq(4).addClass('bg-warning');
						} else if (parseFloat(td.eq(4).find('input').val().trim(),10) === parseFloat(td.eq(3).text())){
							td.eq(4).removeClass();
							td.eq(4).addClass('bg-success');
						}
						grQty.push(td.eq(4).find('input').val());
						remark.push(td.eq(6).find('input').val());
						id_grpo_detail.push(td.eq(6).find('input').attr('id'));	
					})
					// validasi
					if(postingDate.trim() ==''){
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
					if(datePosting > deliverDate){
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
						$.post("<?php echo site_url('transaksi1/pofromvendor/editTable')?>", {
							id_grpo_header:idGrpoHeader, pstDate:postingDate, RemarkHead:remarkHead, detail_grQty:grQty, remark:remark, idGrpoDetails:id_grpo_detail
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
					
				});

				$("#btn-approve").click(function(){
					const postingDate = $('#postingDate').val();
					const delvDate 	= $('#delivDate').val();
					const idGrpoHeader = $('#id_grpo_header').val();
					const remarkHead = $('#remark').val();
					const approve = '2'

					splitDate = postingDate.split('-');
					dayPostingDate = splitDate[0];
					monthPostingDate = splitDate[1];
					yearPostingDate = splitDate[2];
					posDate= `${yearPostingDate}/${monthPostingDate}/${dayPostingDate}`;

					splitdelvDate = delvDate.split('-');
					dayDeliveryDate = splitdelvDate[0];
					monthDeliveryDate = splitdelvDate[1];
					yearDeliveryDate = splitdelvDate[2];
					delDate= `${yearDeliveryDate}/${monthDeliveryDate}/${dayDeliveryDate}`;

					datePosting = new Date(posDate);
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
					let id_grpo_detail=[];

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
							td.eq(4).addClass('bg-danger');
						} else if (parseFloat(td.eq(4).find('input').val().trim(),10) < parseFloat(td.eq(3).text())){
							dataValidasiLessQty.push(td.eq(1).text());
							validasiLessQty = false;
							td.eq(4).addClass('bg-warning');
						} else if (parseFloat(td.eq(4).find('input').val().trim(),10) === parseFloat(td.eq(3).text())){
							td.eq(4).removeClass();
							td.eq(4).addClass('bg-success');
						}
						grQty.push(td.eq(4).find('input').val());
						remark.push(td.eq(6).find('input').val());
						id_grpo_detail.push(td.eq(6).find('input').attr('id'));	
					})
					// validasi
					if(postingDate.trim() ==''){
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
					if(datePosting > deliverDate){
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
						$.post("<?php echo site_url('transaksi1/pofromvendor/editTable')?>", {
							id_grpo_header:idGrpoHeader, appr:approve, pstDate:postingDate, RemarkHead:remarkHead, detail_grQty:grQty, remark:remark, idGrpoDetails:id_grpo_detail
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
					
				});

				$("#cancelRecord").click(function(){
					const idGrpoHeader = $('#id_grpo_header').val();
                    let deleteidArr=[];
                    $("input:checkbox[class=check_delete]:checked").each(function(){
                        deleteidArr.push($(this).val());
                    })

                    // mengecek ckeckbox tercheck atau tidak
                    if(deleteidArr.length > 0){
                        var confirmDelete = confirm("Apa Kamu Yakin Akan Membatalkan PO ini?");
                        if(confirmDelete == true){
                            $.ajax({
                                url:"<?php echo site_url('transaksi1/pofromvendor/cancelPoFromVendor');?>", //masukan url untuk delete
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
        
        </script>
	</body>
</html>