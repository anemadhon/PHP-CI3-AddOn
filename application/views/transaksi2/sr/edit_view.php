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
					<input type="hidden" name="status" id="status" value="<?=$stdstock_header['status']?>">
					<input type="hidden" id="back" value="<?=$stdstock_header['back']?>">
					<div class="card">
                        <div class="card-body">
                            
                                <div class="row">
                                    <div class="col-md-12">
                                        <fieldset>
                                            <legend class="font-weight-semibold"><i class="icon-reading mr-2"></i> Edit Store Room Request (SR)</legend>
                                            <div class="form-group row">
												<label class="col-lg-3 col-form-label">ID Transaksi</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" value="<?=$stdstock_header['id_stdstock_header']?>" id="id_stdstock_header" nama="id_stdstock_header" readOnly>
												</div>
                                            </div>
                                            
                                            <div class="form-group row">
												<label class="col-lg-3 col-form-label">Store Room Reques(SR) Number</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" value="<?= $stdstock_header['status'] == 2 ? $stdstock_header['pr_no1'] :'(Auto Number after Posting to SAP)'?>" id="pr_no1" nama="pr_no1" readOnly>
													<input type="hidden" value="<?= $stdstock_header['pr_no'] ?>" id="pr_no" readOnly>
												</div>
                                            </div>
                                            
                                            <div class="form-group row">
												<label class="col-lg-3 col-form-label">Outlet From</label>
												<div class="col-lg-9">
													<input type="text" class="form-control"value="<?= $plant_name?>" id="outlet" readOnly>
												</div>
                                            </div>
                                            
                                            <div class="form-group row" hidden>
												<label class="col-lg-3 col-form-label">Storage Location</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" value="<?= $storage_location_name?>" readOnly>
												</div>
                                            </div>
                                            
                                            <div class="form-group row">
												<label class="col-lg-3 col-form-label">Status</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" value="<?= $stdstock_header['status_string']?>" readOnly>
												</div>
											</div>

											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Request To Outlet</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" value="<?= $stdstock_header['to_plant']?>" id="rto" readOnly>
												</div>
											</div>

											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Material Group</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" value="<?= $stdstock_header['item_group_code']?>" id="materialGroup" readOnly>
												</div>
											</div>

                                            <div class="form-group row">
                                                <label class="col-lg-3 col-form-label">Delivery Date</label>
                                                <div class="col-lg-9 input-group date">
                                                    <input type="text" class="form-control" id="deliveDate" value="<?= date("d-m-Y", strtotime($stdstock_header['delivery_date']))?>" <?php if($stdstock_header['status']=='2'):?>readonly=""<?php endif; ?>>
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1">
                                                            <i class="icon-calendar"></i>
                                                        </span>
                                                    </div>
                                                </div>
											</div>

                                            <div class="form-group row">
                                                <label class="col-lg-3 col-form-label">Created Date</label>
                                                <div class="col-lg-9 input-group date">
                                                    <input type="text" class="form-control" id="createdDate" value="<?= date("d-m-Y", strtotime($stdstock_header['created_date']))?>" <?php if($stdstock_header['status']=='2'):?>readonly=""<?php endif; ?>>
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
                                                    <textarea id="remark" cols="30" rows="3" class="form-control" <?php if($stdstock_header['status']=='2'):?>readonly=""<?php endif; ?>><?= $stdstock_header['remark']?></textarea>
                                                </div>
											</div>

                                            <div class="text-right hide" id="after-submit">
												<?php if($stdstock_header['status']=='1' || $stdstock_header['back']=='1'):?>
                                               		<button type="button" class="btn btn-primary" name="save" id="save" onclick="<?= ($stdstock_header['status']=='1') ? 'addDatadb()' : 'updateDataDB()'?>"><?= ($stdstock_header['status']=='1') ? 'Save' : 'Change'?> <i class="icon-pencil5 ml-2"></i></button>
												<?php endif; ?>
											   <?php if($stdstock_header['status']=='2' && $stdstock_header['back']=='0' && $cancel['DocStatus']=='O'):?>
													<button type="button" class="btn btn-danger" id="cancel" onclick="onCancel(0)">Cancel <i class="icon-paperplane ml-2"></i></button>
												<?php elseif($stdstock_header['status']=='1'):?>
													<?php if ($this->auth->is_have_perm('auth_approve')) : ?>
													<button type="button" class="btn btn-success" name="approve" id="approve" onclick="addDatadb(2)">Approve<i class="icon-paperplane ml-2"></i></button>
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
								<legend class="font-weight-semibold"><i class="icon-list mr-2"></i>List Item</legend>
									<div class="col-md-12" style="overflow: auto">
										<table class="table table-striped" id="tblWhole">
										<?php if($stdstock_header['status']=='1'):?>
											<div class="col-md-12 mb-2">
												<div class="text-left">
													<input type="button" class="btn btn-primary" value="Add" id="addTable" onclick="onAddrow()"> 
													<input type="button" value="Delete" class="btn btn-danger" id="deleteRecord"> 
												</div>
											</div>
										<?php endif; ?>
											<thead>
												<tr>
													<th></th>
													<th>No</th>
													<th style="width:25%">Material No</th>
													<th style="width:35%">Material Desc</th>
													<th style="width:10%">Quantity</th>
													<th>UOM</th>
													<th>On Hand</th>
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
			let id_stdstock_header = $('#id_stdstock_header').val();
			let stts = $('#status').val();
			let back = $('#back').val();

			table = $("#tblWhole").DataTable({
				"initComplete": function(settings, json) {
					$("#after-submit").removeClass('hide');
				},
				"ordering":false,
				"paging":false,
				"ajax": {
                        "url":"<?php echo site_url('transaksi2/sr/showStdstockDetail');?>",
						"data":{ id: id_stdstock_header, status: stts },
                        "type":"POST"
                    },
				"columns": [
					{"data":"id_stdstock_detail", "className":"dt-center", render:function(data, type, row, meta){
                            rr= (stts==1 || back==1) ? `<input type="checkbox" class="check_delete" id="chk_${data}" value="${data}">`:'';
                            return rr;
                    }},
					{"data":"no", "className":"dt-center"},
					{"data":"material_no", "className":"dt-center"},
					{"data":"material_desc"},
					{"data":"requirement_qty", "className":"dt-center",render:function(data, type, row, meta){
						rr = `${((stts==1 || back==1)?`<input type="text" class="form-control" id="gr_qty_${data}" value="${data}">`:`${data}`)}`;
						return rr;
					}},
					{"data":"uom", "className":"dt-center"},
					{"data":"OnHand", "className":"dt-center"}
				],
				drawCallback: function() {
					$('.form-control-select2').select2();
				}
			});

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
			$('#createdDate').datepicker(optSimple);

			$('#deliveDate').datepicker(optSimple);

			
		});

		function onAddrow(){
			let getTable = $("#tblWhole").DataTable();
			count = getTable.rows().count() + 1;
			let elementSelect = document.getElementsByClassName(`dt_${count}`);
			const requestReason = $('#rr').val();
			const matrialGroup = $('#materialGroup').val();
			const requestToOutlet = $('#rto').val().split(' - ');
			const tbodyTable = $('#tblWhole > tbody');
			let id_stdstock_detail = tbodyTable.find('tr').find('td').eq(0).find('input').val();
			
			getTable.row.add({
				"id_stdstock_detail":`${id_stdstock_detail}`,
				"no":count,
				"material_no":`<select class="form-control form-control-select2 dt_${count} testSelect" data-live-search="true" id="selectDetailMatrial" data-count="${count}">
								<option value="">Select Item</option>
								${showMatrialDetailData(requestReason, matrialGroup, requestToOutlet[0], elementSelect)}
							</select>`,
				"material_desc":"",
				"requirement_qty": "",
				"uom":"",
				"OnHand":""
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

		function showMatrialDetailData(requestReason='', matrialGroup='', requestToOutlet='', select){
			$.ajax({
				url: "<?php echo site_url('transaksi2/sr/getdataDetailMaterial');?>",
				type: "POST",
				data: {
					reqReason: requestReason, 
					matGroup: matrialGroup, 
					reqToOutlet: requestToOutlet
				},
				success:function(res) {
					optData = JSON.parse(res);
					optData.forEach((val)=>{
						$("<option />", {value:val.MATNR, text:val.MAKTX +' - '+ val.MATNR+' - '+val.UNIT	}).appendTo(select);
					})
				}
			});			
		}

		function setValueTable(id,no){
			const requestToOutlet = $('#outlet').val().split(' - ');
			table = document.getElementById("tblWhole").rows[no].cells;
			$.post(
				"<?php echo site_url('transaksi2/sr/getdataDetailMaterialSelect')?>",{ MATNR:id, RTO:requestToOutlet[0] },(res)=>{
					matSelect = JSON.parse(res);
					let onHand = matSelect['dataOnHand'] ? matSelect['dataOnHand'][0].OnHand : 0;
					matSelect['data'].map((val)=>{
						table[2].innerHTML = `<td>${val.MATNR}</td>`;
						table[3].innerHTML = val.MAKTX;
						table[5].innerHTML = val.UNIT;
						table[6].innerHTML = onHand == '.000000' ? '0.0000' : onHand.slice(0,-2);
					})
				}
			)
		}

		function onCancel(flag){
			const id_stdstock_header = $('#id_stdstock_header').val();
			const cancel = flag;

			$.post("<?php echo site_url('transaksi2/sr/onCancel')?>", {
				idStdStock_header: id_stdstock_header, Cancel: cancel
			}, function(res){location.reload(true);});
		}

		function updateDataDB(){
			const id_stdstock_header = $('#id_stdstock_header').val();
			const tbodyTable = $("#tblWhole > tbody");
			const id_stdstock_detail=[];
			const detail_qty=[];
			tbodyTable.find('tr').each(function(i,el){
				let td = $(this).find('td');
				id_stdstock_detail.push(td.eq(0).find('input').val());
				detail_qty.push(td.eq(4).find('input').val());
			})

			$('#load').show();
			$("#after-submit").addClass('after-submit');

			setTimeout(() => {
				$.post("<?php echo site_url('transaksi2/sr/changeDataDB')?>", {
					idStdStock_header: id_stdstock_header, idStdStock_detail: id_stdstock_detail, qty: detail_qty
				}, function(){
					$('#load').hide();
				})
				.done(function() {
					location.replace("<?php echo site_url('transaksi2/sr/')?>");
				})
				.fail(function(xhr, status) {
					alert(`Terjadi Error (${xhr.status} : ${xhr.statusText}), Silahkan Coba Lagi`);
					location.reload(true);
				});
			}, 600);
		}

		function addDatadb(id_approve=''){
			const id_stdstock_header = $('#id_stdstock_header').val();
			const delivDate= $('#deliveDate').val();
			const createDate= $('#createdDate').val();
			const remark= $('#remark').val();
			const approve = id_approve;
			const tbodyTable = $("#tblWhole > tbody");
			let matrial_no=[];
			let detail_qty=[];
			let matrialDesc =[];
			let qty =[];
			let uom =[];
			let onhand =[];
			tbodyTable.find('tr').each(function(i,el){
				let td = $(this).find('td');
				matrial_no.push(td.eq(2).text().trim());
				matrialDesc.push(td.eq(3).text());
				qty.push(td.eq(4).find('input').val());
				uom.push(td.eq(5).text());
				onhand.push(td.eq(6).text());
			})

			$('#load').show();
			$("#after-submit").addClass('after-submit');

			setTimeout(() => {
				$.post("<?php echo site_url('transaksi2/sr/addDataUpdate')?>", {
					idStdStock_header: id_stdstock_header, appr: approve, dateDeliv: delivDate, dateCreate: createDate, Remark:remark, detMatrialNo: matrial_no, detMatrialDesc: matrialDesc, detQty: qty, detUom: uom, OnHand: onhand
				}, function(){
					$('#load').hide();
				})
				.done(function() {
					location.replace("<?php echo site_url('transaksi2/sr/')?>");
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