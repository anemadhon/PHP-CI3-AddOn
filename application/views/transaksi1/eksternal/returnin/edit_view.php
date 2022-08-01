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
                    <div class="card">
                        <div class="card-body">
                            <form action="#" method="POST">
							<input type="hidden" name="status" id="status" value="<?=$retin_header['status']?>">
                                <div class="row">
                                    <div class="col-md-12">
                                        <fieldset>
                                            <legend class="font-weight-semibold"><i class="icon-reading mr-2"></i>Ubah Retur In</legend>
                                            <div class="form-group row">
												<label class="col-lg-3 col-form-label">ID Transaksi</label>
												<div class="col-lg-9">
													<input type="text" class="form-control"  value="<?=$retin_header['id_retin_header']?>" id="idreturn" name="idreturn" readOnly>
												</div>
                                            </div>
                                            
                                            <div class="form-group row">
												<label class="col-lg-3 col-form-label">Return Out Number</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" id="roNumber1" name="roNumber1" value="<?=$retin_header['do_no1']?>" readOnly>
													<input type="hidden" id="roNumber" name="roNumber" value="<?=$retin_header['do_no']?>" readOnly>
												</div>
                                            </div>

											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Return In Number</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" id="riNumber1"  name="riNumber1" value="<?=$retin_header['transfer_in_number1']?>" readOnly>
													<input type="hidden" id="riNumber"  name="riNumber" value="<?=$retin_header['transfer_in_number']?>" readOnly>
												</div>
                                            </div>

											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Return From</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" id="rf"  name="rf" value="<?=$retin_header['return_from']?>" readOnly>
												</div>
                                            </div>
                                            
                                            <div class="form-group row">
												<label class="col-lg-3 col-form-label">Outlet To</label>
												<div class="col-lg-9">
													<input type="text" class="form-control"  value="<?=$retin_header['plant']?>" id="plant" name="plant" readOnly>
												</div>
                                            </div>
                                            
                                            <div class="form-group row" hidden>
												<label class="col-lg-3 col-form-label">Storage Location</label>
												<div class="col-lg-9">
													<input type="text" class="form-control"  value="<?=$retin_header['storage_location']?>" id="storageLocation" name="storageLocation" readOnly>
												</div>
                                            </div>

											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Status</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" value="<?=$retin_header['status_string']?>" id="status_string" name="status_string" readOnly>
												</div>
											</div>

                                           	<div class="form-group row">
												<label class="col-lg-3 col-form-label">Material Group</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" value="<?=$retin_header['item_group_code']?>" name="MatrialGroup" id="MatrialGroup" readonly>
												</div>
											</div>

                                            <div class="form-group row">
                                                <label class="col-lg-3 col-form-label">Posting Date</label>
                                                <div class="col-lg-9 input-group date">
													<input type="text" class="form-control"  value="<?=date("d-m-Y", strtotime($retin_header['posting_date']))?>" id="postingDate" <?= $retin_header['status'] == 2 ? "readonly" :''?>>
													<?php if($retin_header['status'] !='2'): ?>
														<div class="input-group-prepend">
															<span class="input-group-text" id="basic-addon1">
																<i class="icon-calendar"></i>
															</span>
														</div> 
													<?php endif;?>
												</div>
											</div>

											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Remarks</label>
												<div class="col-lg-9 input-group date">
													<textarea id="remark" cols="30" rows="3" class="form-control" <?= $retin_header['status'] == 2 ? "readonly" :''?>><?php echo $retin_header['remark']; ?></textarea>
												</div>
											</div>

                                            <div class="text-right hide" id="after-submit">
												<?php if($retin_header['status'] !='2'): ?>
												<button type="button" class="btn btn-primary" name="save" id="save" onclick="addDatadb()">Save<i class="icon-paperplane ml-2"></i></button>
													<?php if ($this->auth->is_have_perm('auth_approve')) : ?>
													<button type="button" class="btn btn-success" name="approve" id="approve" onclick="addDatadb(2)">Approve<i class="icon-paperplane ml-2"></i></button>
													<?php endif;?>
												<?php endif;?>
                                            </div>

											
                                        </fieldset>
                                    </div>
                                </div>
								<div id="load" style="display:none"></div>  
								<div class="row">
									<div class="col-md-12" style="overflow: auto">
										<table class="table table-striped" id="tblWhole">
											<thead>
												<tr>
													<th>No</th>
													<th>Material No</th>
													<th>Material Desc</th>
													<th>Outstanding Qty</th>
													<th>GR Qty</th>
													<th>UOM</th>
													<th><?php if($retin_header['status'] =='1'): ?><?php endif; ?></th>
												</tr>
											</thead>
										</table>
									</div>
								</div>
                            </form>
                        </div>
                    </div>                    
				</div>
				<?php  $this->load->view("_template/footer.php")?>
			</div>
		</div>
        <?php  $this->load->view("_template/js.php")?>
		<script>
		$(document).ready(function(){
                let id_retin_header = $('#idreturn').val();
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

				table = $("#tblWhole").DataTable({
					"initComplete": function(settings, json) {
						$("#after-submit").removeClass('hide');
					},
					"ordering":false,
					"paging":false,
					"ajax": {
							"url":"<?php echo site_url('transaksi1/returnin/showReturnInDetail');?>",
							"data":{ id: id_retin_header, status: stts },
							"type":"POST"
						},
					"columns": [
                        {"data":"no", "className":"dt-center"},
                        {"data":"material_no", "className":"dt-center"},
                        {"data":"material_desc"},
                        {"data":"outstanding_qty", "className":"dt-center"},
                        {"data":"gr_quantity", "className":"dt-center", render:function(data, type, row, meta){
							rr= row['status'] == 1 ? `<input type="text" class="form-control" id="gr_qty_${data}" value="${data}">`: data;
                            return rr;
						}},
                        {"data":"uom", "className":"dt-center"},
						{"data":"id_retin_detail", "className":"dt-center", render:function(data, type, row, meta){
                            rr= row['status'] == 1 ? `<input type="checkbox" class="check_delete" id="chk_${data}" value="${data}" onclick="checkcheckbox();">` : '';
                            return rr;
                        }},
                    ],
					drawCallback: function() {
						$('.form-control-select2').select2();
					}
				});


				$("#cancelRecord").click(function(){
					const id_retin_header = $('#idreturn').val();
                    let deleteidArr=[];
                    $("input:checkbox[class=check_delete]:checked").each(function(){
                        deleteidArr.push($(this).val());
                    })


                    // mengecek ckeckbox tercheck atau tidak
                    if(deleteidArr.length > 0){
                        var confirmDelete = confirm("Apa Kamu Yakin Akan Membatalkan Transfer In Inter Outlet ini?");
                        if(confirmDelete == true){
                            $.ajax({
                                url:"<?php echo site_url('transaksi1/returnin/cancelReturnIn');?>", //masukan url untuk delete
                                type: "post",
                                data:{deleteArr: deleteidArr, id_retin_header:id_retin_header},
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

			checkcheckbox = () => {
                    
				const lengthcheck = $(".check_delete").length;
				
				let totalChecked = 0;
				$(".check_delete").each(function(){
					if($(this).is(":checked")){
						totalChecked += 1;
					}
				});
			}

			function addDatadb(id_approve = ''){

				idretin		= $('#idreturn').val();
				pstDate 	= $('#postingDate').val();
				delvDate 	= $('#delivDate').val();
				remark 		= $('#remark').val();
				approve		= id_approve;

				$('#load').show();
				$("#after-submit").addClass('after-submit');

				setTimeout(() => {
					$.post("<?php echo site_url('transaksi1/returnin/addDataUpdate')?>", {
						idRetH:idretin, posting_date:pstDate, delivery_date:delvDate, appr: approve, Remark:remark
					}, function(){
						$('#load').hide();
					})
					.done(function() {
						location.replace("<?php echo site_url('transaksi1/returnin/')?>");
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