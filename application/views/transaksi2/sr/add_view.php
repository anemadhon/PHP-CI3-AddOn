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
		<link href="<?php echo base_url('/files/');?>assets/css/components.min.css" rel="stylesheet" type="text/css">	
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
                                            <legend class="font-weight-semibold"><i class="icon-reading mr-2"></i> Tambah Store Room Request (SR)</legend>
                                            <div class="form-group row">
												<label class="col-lg-3 col-form-label">ID Transaksi</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" placeholder="ID Transaksi" readOnly>
												</div>
                                            </div>
                                            
                                            <div class="form-group row">
												<label class="col-lg-3 col-form-label">Store Room Reques(SR) Number</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" placeholder="(Auto Number after Posting to SAP)" readOnly>
												</div>
                                            </div>
                                            
                                            <div class="form-group row">
												<label class="col-lg-3 col-form-label">Outlet From</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" value="<?= $plant ?>" id="outlet" readOnly>
												</div>
                                            </div>
                                            
                                            <div class="form-group row" hidden>
												<label class="col-lg-3 col-form-label">Storage Location</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" value="<?= $storage_location ?>" readOnly>
												</div>
                                            </div>
                                            
                                            <div class="form-group row">
												<label class="col-lg-3 col-form-label">Status</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" value="Not Approved" readOnly>
												</div>
											</div>

											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Material Group</label>
												<div class="col-lg-9">
													<select class="form-control form-control-select2" data-live-search="true" id="materialGroup" name="materialGroup">
														<option value="">Select Item</option>
														<option value="all">All</option>
														<?php foreach($matrialGroup as $key=>$val):?>
															<option value="<?=$val['ItmsGrpNam']?>"><?=$val['ItmsGrpNam']?></option>
														<?php endforeach;?>
													</select>
												</div>
											</div>

                                            <div class="form-group row">
												<label class="col-lg-3 col-form-label">Request To Outlet</label>
												<div class="col-lg-9">
													<select class="form-control form-control-select2" data-live-search="true" id="rto" name="rto" onchange="showMatrialDetail(this.value)">
														<option value="">Select Item</option>
														<?php foreach($plants as $key=>$val):?>
															<option value="<?=$val['OUTLET']?>"><?=$val['OUTLET_NAME1'].'('.$val['OUTLET'].')'?></option>
														<?php endforeach;?>
													</select>
												</div>
                                            </div>

											<div id='form1' style="display:none">
                                            
                                            <div class="form-group row">
                                                <label class="col-lg-3 col-form-label">Delivery Date</label>
                                                <div class="col-lg-9 input-group date">
                                                    <input type="text" class="form-control" id="deliveDate" readonly="">
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
                                                    <input type="text" class="form-control" id="createdDate" readonly="">
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

                                            <div class="text-right" id="after-submit">
                                                <button type="button" class="btn btn-primary" name="save" id="save" onclick="addDatadb()">Save <i class="icon-pencil5 ml-2"></i></button>
												<?php if ($this->auth->is_have_perm('auth_approve')) : ?>
												
												<button type="button" class="btn btn-success" name="approve" id="approve" onclick="addDatadb(2)">Approve<i class="icon-paperplane ml-2"></i></button>
												<?php endif; ?>
                                            </div>

											
                                        </fieldset>
                                    </div>
                                </div>
								</div>
                                </div>
								<div id="load" style="display:none"></div>
								<div id='form2' style="display:none">
								<div class="card">
									<div class="card-body">
										
										<div class="row">
										<legend class="font-weight-semibold"><i class="icon-list mr-2"></i>List Item</legend>
										<div class="col-md-12" style="overflow: auto">
											<table class="table table-striped" id="tblWhole">
											<div class="col-md-12 mb-2">
												<div class="text-left">
													<input type="button" class="btn btn-primary" value="Add" id="addTable" onclick="onAddrow()"> 
													<input type="button" value="Delete" class="btn btn-danger" id="deleteRecord"> 
												</div>
											</div>
												<thead>
													<tr>
														<th></th>
														<th>No</th>
														<th>Material No</th>
														<th>Material Desc</th>
														<th>Quantity</th>
														<th>UOM</th>
														<th>On Hand</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td><input type="checkbox" id="record"/></td>
														<td>1</td>
														<td width="35%">
															<select class="form-control form-control-select2" data-live-search="true" id="matrialGroup" onchange="setValueTable(this.value,1)">
																<option value="">Select Item</option>
															</select>
														</td>
														<td width="40%"></td>
														<td><input type="text" class="form-control  qty" name="qty[]" id="qty" style="width:90px" autocomplete="off"></td>
														<td></td>
														<td></td>
													</tr>
												</tbody>
											</table>
										</div>
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
		var table ='';
		$(document).ready(function(){
			table = $("#tblWhole").DataTable({
				"ordering":false,
				"paging":false,
				drawCallback: function() {
					$('.form-control-select2').select2();
				}
			});

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
			$('#createdDate').datepicker();

			$('#deliveDate').datepicker(optSimple);
			$('#deliveDate').datepicker();
		});

		function onAddrow(){
			let getTable = $("#tblWhole").DataTable();
			count = getTable.rows().count() + 1;
			let elementSelect = document.getElementsByClassName(`dt_${count}`);
			const requestReason = $('#rr').val();
			const matrialGroup = $('#materialGroup').val();
			const requestToOutlet = $('#rto').val();
			
			getTable.row.add({
				"0":`<input type="checkbox" class="check_delete" id="chk_${count}" value="${count}">`,
				"1":count,
				"2":`<select class="form-control form-control-select2 dt_${count} testSelect" data-live-search="true" id="selectDetailMatrial" data-count="${count}">
								<option value="">Select Item</option>
								${showMatrialDetailData(requestReason, matrialGroup, requestToOutlet, elementSelect)}
							</select>`,
				"3":"",
				"4":`<input type="text" class="form-control qty" id="gr_qty_${count}" value="" style="width:90px" autocomplete="off">`,
				"5":"",
				"6":""
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
			let outlet = $('#outlet').val().split(' - ');
			const requestToOutlet = outlet[0];
			table = document.getElementById("tblWhole").rows[no].cells;
			$.post(
				"<?php echo site_url('transaksi2/sr/getdataDetailMaterialSelect')?>",{ MATNR:id, RTO:requestToOutlet },(res)=>{
					matSelect = JSON.parse(res);
					let onHand = matSelect['dataOnHand'] ? matSelect['dataOnHand'][0].OnHand : 0;
					matSelect['data'].map((val)=>{
						table[3].innerHTML = val.MAKTX;
						table[5].innerHTML = val.UNIT;
						table[6].innerHTML = onHand == '.000000' ? '0.0000' : onHand.slice(0,-2);
					})
				}
			)
		}

		function showMatrialDetail(){
			const requestReason = $('#rr').val();
			const matrialGroup = $('#materialGroup').val();
			const requestToOutlet = $('#rto').val();
			const select = $('#matrialGroup');
			showMatrialDetailData(requestReason, matrialGroup, requestToOutlet, select);		

			$("#form1").css('display', '');
			$("#form2").css('display', '');
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
					localStorage.setItem('tmpDataMaterial', JSON.stringify(optData));
					
					optData.forEach((val)=>{						
						$("<option />", {value:val.MATNR, text:val.MAKTX +' - '+ val.MATNR+' - '+val.UNIT	}).appendTo(select);
					})
				}
			});			
		}

		function addDatadb(id_approve=''){
			if($('.qty').val() ==''){
				alert('Quantity harus di isi');
				return false;
			}

			if($('#createdDate').val() ==''){
				alert('Tanggal Posting harus di isi');
				return false;
			}

			if($('#deliveDate').val() ==''){
				alert('Tanggal Delivery harus di isi');
				return false;
			}

			if($('#remark').val() ==''){
				alert('Remark harus di isi');
				return false;
			}
			
			const matrialGroup= document.getElementById('materialGroup').value;
			const requestToOutlet= document.getElementById('rto').value;
			const delivDate= document.getElementById('deliveDate').value;
			const createDate= document.getElementById('createdDate').value;
			const remark= document.getElementById('remark').value;
			const approve = id_approve;
			const tbodyTable = $('#tblWhole > tbody');
			let matrialNo =[];
			let matrialDesc =[];
			let qty =[];
			let uom =[];
			let onhand =[];
			let validateQty = true;
			tbodyTable.find('tr').each(function(i, el){
					let td = $(this).find('td');
					if(td.eq(4).find('input').val() == '' || td.eq(4).find('input').val() == null){
						validateQty = false
					}
					matrialNo.push(td.eq(2).find('select').val()); 
					matrialDesc.push(td.eq(3).text());
					qty.push(td.eq(4).find('input').val());
					uom.push(td.eq(5).text());
					onhand.push(td.eq(6).text());
				})
			if(!validateQty){
				alert('Quantity harus di isi');
				return false;
			}

			$('#load').show();
			$("#after-submit").addClass('after-submit');

			setTimeout(() => {
				$.post("<?php echo site_url('transaksi2/sr/addData')?>", {
					matGrp: matrialGroup, reqToOutlet: requestToOutlet, appr: approve, dateDeliv: delivDate, dateCreate: createDate, remarks:remark, detMatrialNo: matrialNo, detMatrialDesc: matrialDesc, detQty: qty, detUom: uom, OnHand:onhand
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