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
					<input type="hidden" name="status" id="status" value="<?=$retOut_header['status']?>">
						<div class="card">
                        	<div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <fieldset>
                                            <legend class="font-weight-semibold"><i class="icon-reading mr-2"></i>Ubah Retur Out</legend>
                                            <div class="form-group row">
												<label class="col-lg-3 col-form-label">ID Transaksi</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" value="<?=$retOut_header['id_gisto_dept_header']?>" id="idreturnOut" name="idreturnOut" readOnly>
												</div>
                                            </div>
                                            
                                            <div class="form-group row">
												<label class="col-lg-3 col-form-label">Return No</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" value="<?= $retOut_header['status'] == 2 ? $retOut_header['gisto_dept_no1'] :'(Auto Number after Posting to SAP)'?>" id="retOutNo1" name="retOutNo1" readOnly>
													<input type="hidden" value="<?= $retOut_header['gisto_dept_no'] ?>" id="retOutNo" name="retOutNo" readOnly>
												</div>
                                            </div>
                                            
                                            <div class="form-group row">
												<label class="col-lg-3 col-form-label">Outlet</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" value="<?=$retOut_header['plant']?>" id="plant" name="plant" readOnly>
												</div>
                                            </div>
                                            
                                            <div class="form-group row" hidden>
												<label class="col-lg-3 col-form-label">Storage Location</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" value="<?=$retOut_header['storage_location']?>" id="storageLocation" name="storageLocation" readOnly>
												</div>
											</div>
											
											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Retur Out to Outlet</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" value="<?=$retOut_header['receiving_plant']?>" id="receiving_plant" name="receiving_plant" readOnly>
												</div>
                                            </div>

											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Status</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" value="<?=$retOut_header['status_string']?>" id="status_string" name="status_string" readOnly>
												</div>
											</div>

                                           	<div class="form-group row">
												<label class="col-lg-3 col-form-label">Material Group</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" value="<?=$retOut_header['item_group_code']?>" name="MatrialGroup" id="MatrialGroup" readonly>
												</div>
											</div>

                                            <div class="form-group row">
                                                <label class="col-lg-3 col-form-label">Posting Date</label>
                                                <div class="col-lg-9 input-group date">
													<input type="text" class="form-control"  value="<?=date("d-m-Y", strtotime($retOut_header['posting_date']))?>" id="postingDate" <?= $retOut_header['status'] == 2 ? "readonly" :''?>>
													<?php if($retOut_header['status'] !='2'): ?>
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
														<textarea id="remark" cols="30" rows="3" class="form-control" <?= $retOut_header['status'] == 2 ? "readonly" :''?>><?php echo $retOut_header['remark']; ?></textarea>
													</div>
												</div>

                                            <div class="text-right hide" id="after-submit">
												<?php if($retOut_header['status'] !='2'): ?>
												<button type="button" class="btn btn-primary" name="save" id="save" onclick="addDatadb()">Save<i class="icon-paperplane ml-2"></i></button> 
													<?php if ($this->auth->is_have_perm('auth_approve')) : ?>
													<button type="button" class="btn btn-success" name="approve" id="approve" onclick="addDatadb(2)">Approve<i class="icon-paperplane ml-2"></i></button>
													<?php endif;?>
												<?php endif;?>
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
										<?php if($retOut_header['status'] !='2'): ?>
											<input type="button" class="btn btn-primary" value="Add" id="addTable" onclick="onAddrow()"> 
											<input type="button" value="Delete" class="btn btn-danger" id="deleteRecord">
										<?php endif;?>
										</div>
									</div>
									<div class="col-md-12" style="overflow: auto">
										<table class="table table-striped" id="tblWhole">
											<thead>
												<tr>
													<th>No</th>
													<th>Material No</th>
													<th>Material Desc</th>
													<th>In Whs Quantity</th>
													<th>Quantity</th>
													<th>UOM</th>
													<th>Remark</th>
													<th><?php if($retOut_header['status'] !='2'): ?>Cancel<?php endif;?></th>
												</tr>
											</thead>
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
			const id_gisto_dept_header = $('#idreturnOut').val();
			const stts = $('#status').val();

			table = $("#tblWhole").DataTable({
				"initComplete": function(settings, json) {
					$("#after-submit").removeClass('hide');
				},
				"ordering":false,
				"paging":false,
				"ajax": {
						"url":"<?php echo site_url('transaksi1/returnout/showReturnInDetail');?>",
						"data":{ id: id_gisto_dept_header, status: stts },
						"type":"POST"
					},
					"columns": [
					{"data":"no", "className":"dt-center"},
					{"data":"material_no", "className":"dt-center"},
					{"data":"material_desc"},
					{"data":"stock", "className":"dt-center"},
					{"data":"gr_quantity", "className":"dt-center", render:function(data, type, row, meta){
						rr= stts == 1 ? `<input type="text" class="form-control qty" value="${data}">`: `${row['gr_quantity']}`;
						return rr;
					}},
					{"data":"uom", "className":"dt-center"},
					{"data":"reason", "className":"dt-center", render:function(data, type, row, meta){
						rr= stts == 1 ? `<input type="text" class="form-control remark" value="${data}">`: `${row['reason']}`;
						return rr;
					}},
					{"data":"id_gisto_dept_detail", "className":"dt-center", render:function(data, type, row, meta){
						rr=stts==2 ? '' : `<input type="checkbox" class="check_delete" id="chk_${data}" value="${data}" onclick="checkcheckbox();">`;
						return rr;
					}},
				],
				drawCallback: function() {
					$('.form-control-select2').select2();
				}
			});				

			$("#cancelRecord").click(function(){
				const id_gisto_dept_header = $('#idreturnOut').val();
				let deleteidArr=[];
				$("input:checkbox[class=check_delete]:checked").each(function(){
					deleteidArr.push($(this).val());
				})


				// mengecek ckeckbox tercheck atau tidak
				if(deleteidArr.length > 0){
					var confirmDelete = confirm("Apa Kamu Yakin Akan Membatalkan Return Out ini?");
					if(confirmDelete == true){
						$.ajax({
							url:"<?php echo site_url('transaksi1/returnout/cancelReturnOut');?>", //masukan url untuk delete
							type: "post",
							data:{deleteArr: deleteidArr, id_gisto_dept_header:id_gisto_dept_header},
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

			$("#deleteRecord").click(function(){
				let deleteidArr=[];
				$("input:checkbox[class=check_delete]:checked").each(function(){
					deleteidArr.push($(this).val());
				})

				// mengecek ckeckbox tercheck atau tidak
				if(deleteidArr.length > 0){
					var confirmDelete = confirm("Do you really want to Delete records?");
					if(confirmDelete == true){
						$("input:checked").each(function(){
							table.row($(this).closest("tr")).remove().draw();;
						});
					}
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
		});

		function onAddrow(){
			let getTable = $("#tblWhole").DataTable();
			count = getTable.rows().count() + 1;
			let elementSelect = document.getElementsByClassName(`dt_${count}`);
			const reqtOutlet = $('#receiving_plant').val();
			const arrOutletVal = reqtOutlet.split('|');
			const requestOutlet = arrOutletVal[0];
			const matrialGroup = $('#MatrialGroup').val();

			
			getTable.row.add({
				"no":count,
				"material_no":`<select class="form-control form-control-select2 dt_${count} testSelect" data-live-search="true" id="selectDetailMatrial" data-count="${count}">
						<option value="">Select Item</option>
						${showMatrialDetailData(requestOutlet, matrialGroup, elementSelect)}
					</select>`,
				"material_desc":"",
				"stock":"",
				"gr_quantity":"",
				
				"uom":"",
				"reason":"",
				"id_gisto_dept_detail":"id_gisto_dept_detail",
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

		function setValueTable(id,no){
			const reqtOutlet = $('#receiving_plant').val();
			const arrOutletVal = reqtOutlet.split('|');
			const requestToOutlet = arrOutletVal[0];
			const materialGroup = $('#MatrialGroup').val();
			table = document.getElementById("tblWhole").rows[no].cells;
			$.post(
				"<?php echo site_url('transaksi1/returnout/getdataDetailMaterialSelect')?>",{ MATNR:id, RTO:requestToOutlet, DSNAM:materialGroup },(res)=>{
					matSelect = JSON.parse(res);
					let inWhsQty = matSelect['dataInWhsQty'] ? matSelect['dataInWhsQty'][0].OnHand : 0;
					
					matSelect['data'].map((val)=>{
						table[1].innerHTML = val.MATNR1;
						table[2].innerHTML = val.MAKTX;
						table[3].innerHTML = inWhsQty == '.000000' ? '0.0000' : inWhsQty.slice(0,-2);
						table[4].innerHTML = `<input type="text" class="form-control qty" value="" style="width:100%" autocomplete="off">`;
						table[5].innerHTML = val.UNIT;
						table[6].innerHTML = `<input type="text" class="form-control remark" value="" style="width:100%" autocomplete="off">`;
					})
				}
			)
		}

		function showMatrialDetailData(requestOutlet='', selectMaterial='',  select){
			$.ajax({
				url: "<?php echo site_url('transaksi1/returnout/getdataDetailMaterial');?>",
				type: "POST",
				data: {
					reqOutlet: requestOutlet, 
					matGroup: selectMaterial
				},
				success:function(res) {
					optData = JSON.parse(res);
					
					optData.forEach((val)=>{						
						$("<option />", {value:val.MATNR1, text:val.MATNR1 +' - '+ val.MAKTX+' - '+val.UNIT	}).appendTo(select);
					})
				}
			});			
		}

		function addDatadb(id_approve=''){
			const approve = id_approve;
			const postDate= document.getElementById('postingDate').value;
			const idreto  = document.getElementById('idreturnOut').value;
			const remarkHead  = document.getElementById('remark').value;

			const tbodyTable = $('#tblWhole > tbody');
			let matrialNo =[];
			let matrialDesc =[];
			let whsQty =[];
			let qty =[];
			let uom =[]; 
			let remark =[];
			tbodyTable.find('tr').each(function(i, el){
				let td = $(this).find('td');
				matrialNo.push(td.eq(1).text()); 
				matrialDesc.push(td.eq(2).text());
				whsQty.push(td.eq(3).text());
				qty.push(td.eq(4).find('input').val());
				uom.push(td.eq(5).text());
				remark.push(td.eq(6).find('input').val());
			})

			$('#load').show();
			$("#after-submit").addClass('after-submit');

			setTimeout(() => {
				$.post("<?php echo site_url('transaksi1/returnout/addDataUpdate')?>", {
					idRetOut: idreto, appr: approve, posting_date: postDate, RemarkHead:remarkHead, detMatrialNo: matrialNo, detMatrialDesc: matrialDesc, inWhsQty:whsQty, detQty: qty, detUom: uom, detRemark:remark
				}, function(){
					$('#load').hide();
				})
				.done(function() {
					location.replace("<?php echo site_url('transaksi1/returnout/')?>");
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