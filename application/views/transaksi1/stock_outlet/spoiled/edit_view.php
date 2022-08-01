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
					 <input type="hidden" name="status" id="status" value="<?=$waste_header['status']?>">
					 <div class="card">
                        <div class="card-body">
                           
                                <div class="row">
                                    <div class="col-md-12">
                                        <fieldset>
                                            <legend class="font-weight-semibold"><i class="icon-reading mr-2"></i>Ubah Spoiled / Breakage / Lost</legend>
                                            <div class="form-group row">
												<label class="col-lg-3 col-form-label">ID Transaksi</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" value="<?=$waste_header['id_waste_header']?>" id="idwaste" name="idwaste" readOnly>
												</div>
                                            </div>
                                            
                                            <div class="form-group row">
												<label class="col-lg-3 col-form-label">Spoiled No</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" value="<?= $waste_header['status'] == 2 ? $waste_header['material_doc_no'] :'(Auto Number after Posting to SAP)'?>" id="material_doc_no" nama="material_doc_no" readOnly>
												</div>
                                            </div>
                                            
                                            <div class="form-group row">
												<label class="col-lg-3 col-form-label">Outlet</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" value="<?=$waste_header['plant']?>" id="plant" name="plant" readOnly>
												</div>
                                            </div>
                                            
                                            <div class="form-group row" hidden>
												<label class="col-lg-3 col-form-label">Storage Location</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" value="<?=$waste_header['storage_location']?>" id="storage_location" name="storage_location" readOnly>
												</div>
                                            </div>

											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Cost Center</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" value="<?=$waste_header['cost_center']?>" id="cost_center" name="cost_center" readOnly>
												</div>
											</div>
                                            
                                            <div class="form-group row">
												<label class="col-lg-3 col-form-label">Status</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" value="<?=$waste_header['status_string']?>" id="status_string" name="status_string" readOnly>
												</div>
											</div>

                                           	<div class="form-group row">
												<label class="col-lg-3 col-form-label">Material Group</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" value="<?=$waste_header['item_group_code']?>" name="MatrialGroup" id="MatrialGroup" readonly>
												</div>
											</div>

                                            <div class="form-group row">
                                                <label class="col-lg-3 col-form-label">Posting Date</label>
												<div class="col-lg-9 input-group date">
													<input type="text" class="form-control"  value="<?=date("d-m-Y", strtotime($waste_header['posting_date']))?>" id="postingDate" <?= $waste_header['status'] == 2 ? "readonly" :''?>>
													<?php if($waste_header['status'] !='2'): ?>
														<div class="input-group-prepend">
															<span class="input-group-text" id="basic-addon1">
																<i class="icon-calendar"></i>
															</span>
														</div> 
													<?php endif;?>
												</div>
											</div>

                                            <div class="form-group row">
                                                <label class="col-lg-3 col-form-label">Remark</label>
                                                <div class="col-lg-9 input-group date">
                                                    <textarea name="remark" id="remark" cols="5" rows="5" class="form-control" <?= $waste_header['status'] == 2 ? "readonly" :''?>><?= $waste_header['remark'];?></textarea>
                                                </div>
											</div>


                                            <div class="text-right hide" id="after-submit">
												<?php if($waste_header['status']=='1'): ?>	
													<button type="button" class="btn btn-primary" id="btn-update" onclick="addDatadb()">Save <i class="icon-pencil5 ml-2"></i></button>
													<?php if ($this->auth->is_have_perm('auth_approve')) : ?>
													<button type="button" class="btn btn-success" id="btn-update" onclick="addDatadb(2)">Approve <i class="icon-paperplane ml-2"></i></button>
													<?php endif; ?>
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
							<legend class="font-weight-semibold"><i class="icon-list mr-2"></i>List Item</legend>
							<?php if($waste_header['status']!='2'):?>
								<div class="col-md-12 mb-2">
									<div class="text-left">
										<input type="button" class="btn btn-primary" value="Add" id="addTable" onclick="onAddrow()"> 
										<input type="button" value="Delete" class="btn btn-danger" id="deleteRecord"> 
									</div>
								</div>
							<?php endif; ?>
								<div class="row">
									<div class="col-md-12" style="overflow: auto">
										<table class="table table-striped" id="tblWhole">
										
											<thead>
												<tr>
													<th></th>
													<th>No</th>
													<th>Material No</th>
													<th>Material Desc</th>
													<th>In Whs Quantity</th>	
													<th>Quantity</th>
													<th>UOM</th>
													<th>Reason</th>
													<th>Detail</th> 
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
			let id_waste_header = $('#idwaste').val();
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
						"url":"<?php echo site_url('transaksi1/spoiled/showWasteDetail');?>",
						"data":{ id: id_waste_header, status: stts },
						"type":"POST"
					},
				"columns": [
					
					{"data":"id_waste_detail", "className":"dt-center", render:function(data, type, row, meta){
							rr=(row["status"] == 2) ? '' : `<input type="checkbox" class="check_delete" id="chk_${data}" value="${data}" >`;
							return rr;
					}},
					{"data":"no", "className":"dt-center"},
					{"data":"material_no", "className":"dt-center"},
					{"data":"material_desc"},
					{"data":"stock", "className":"dt-center"},
					{"data":"quantity", "className":"dt-center",render:function(data, type, row, meta){
						rr=  (row["status"] == 2) ? data : `<input type="text" class="form-control" id="waste_qty_${row['no']}" value="${data}">`;
						return rr;
					}},
					{"data":"uom"},					
					{"data":"reason_name", "className":"dt-center",render:function(data, type, row, meta){
						rr= (row["status"] == 2) ? data : `<select class="form-control form-control-select2 rea_${row['no']}" id="reason_name_${row['no']}" data-live-search="true" data-count="${row['no']}">
								<option value="">Select Item</option>
								<option value="Rusak/Reject" ${(data=='Rusak/Reject' ? 'selected' : '')}>Rusak/Reject</option>
								<option value="Expired" ${(data=='Expired' ? 'selected' : '')}>Expired</option>
								<option value="Jatuh/Tumpah" ${(data=='Jatuh/Tumpah' ? 'selected' : '')}>Jatuh/Tumpah</option>
								<option value="Wastage" ${(data=='Wastage' ? 'selected' : '')}>Wastage</option>
							</select>`;
						return rr;
					}},
					{"data":"other_reason", "className":"dt-center",render:function(data, type, row, meta){
						rr= (row["status"] == 2) ? data : `<input type="text" class="form-control" id="other_reason_${row['no']}" value="${data}">`;
						return rr;
					}}
				],
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

			
		});

		function onAddrow(){
			let getTable = $("#tblWhole").DataTable();
			count = getTable.rows().count() + 1;
			let elementSelect = document.getElementsByClassName(`dt_${count}`);
			const matrialGroup = $('#MatrialGroup').val() ? $('#MatrialGroup').val() : 'all';
			let id_waste_header = $('#idwaste').val();

			getTable.row.add({
				"no":count,
				"material_no":`<select class="form-control form-control-select2 dt_${count} testSelect" data-live-search="true" id="selectDetailMatrial" data-count="${count}">
								<option value="">Select Item</option>
								${showMatrialDetailData(matrialGroup,id_waste_header,elementSelect)}
							</select>`,
				"material_desc":"",
				"stock":"",
				"quantity":"",
				"uom":"",
				"reason_name":"",
				"other_reason":""
				}).draw();
				count++;

			tbody = $("#tblWhole tbody");
			tbody.on('change','.testSelect', function(){
				tr = $(this).closest('tr');
				no = tr[0].rowIndex;
				id = $('.dt_'+no).val();
				setValueTable(id_waste_header,id,no);
			});
		}

		function showMatrialDetailData(cboMatrialGroup='',id_waste_header='', selectTable){
			const select = selectTable ? selectTable : $('#matrialGroupDetail');
			$.post("<?php echo site_url('transaksi1/spoiled/getdataDetailMaterial');?>",{ matGroup: cboMatrialGroup},(data)=>{
				optData = JSON.parse(data);
				optData.forEach((val)=>{
					$("<option />", {value:val.MATNR, text:val.MAKTX +' - '+ val.MATNR+' - '+val.UNIT	}).appendTo(select);
				})
			})
					
		}

		function setValueTable(id_waste_header='',id,no){
			table = document.getElementById("tblWhole").rows[no].cells;
			$.post(
				"<?php echo site_url('transaksi1/spoiled/getdataDetailMaterialSelect')?>",{ MATNR:id ,idWaste:id_waste_header},(res)=>{
					matSelect = JSON.parse(res);
					matSelect.map((val)=>{
						table[2].innerHTML = `<td>${val.MATNR}</td>`;
						table[3].innerHTML = val.MAKTX;
						table[4].innerHTML = val.WHSQTY;
						table[6].innerHTML = val.UNIT
					})
				}
			)
		}

		function addDatadb(id_approve=''){
			if($('#remark').val() ==''){
				alert('Remark harus di isi');
				return false;
			}
			const id_waste_header = $('#idwaste').val();
			const postDate= document.getElementById('postingDate').value;
			const remark= document.getElementById('remark').value;
			const approve = id_approve;
			const tbodyTable = $('#tblWhole > tbody');
			let matrialNo =[];
			let matrialDesc =[];
			let qty =[];
			let whsqty = [];
			let uom =[];
			let reason = [];
			let text = [];
			let validasi = true;
			let validasiQty = true;
			let validasiReasson = true;
			let dataValidasi = [];
			tbodyTable.find('tr').each(function(i, el){
				let td = $(this).find('td');
				if(td.eq(5).find('input').val() == ''){
					validasi = false;
				}
				if(parseInt(td.eq(5).find('input').val(),10) > parseFloat(td.eq(4).text())){
					dataValidasi.push(td.eq(2).text());
					validasiQty = false;
				}	
				if(td.eq(7).find('select').val() == ''){
					validasiReasson = false;
				}
				matrialNo.push(td.eq(2).text()); 
				matrialDesc.push(td.eq(3).text());
				whsqty.push(td.eq(4).text());
				qty.push(td.eq(5).find('input').val());
				uom.push(td.eq(6).text());
				reason.push(td.eq(7).find('select').val());
				text.push(td.eq(8).find('input').val());
			});

			if(!validasi){
				alert('Quatity Tidak boleh Kosong, Harap isi Quantity');
				return false;
			}
			if(!validasiQty){
				alert('Material Number '+dataValidasi.join()+' Quatity Tidak boleh Lebih Besar dari In Whs Quantity');
				return false;
			}
			if(!validasiReasson){
				alert('Reason Tidak boleh Kosong, Harap Reason');
				return false;
			}

			$('#load').show();
			$("#after-submit").addClass('after-submit');

			setTimeout(() => {
				$.post("<?php echo site_url('transaksi1/spoiled/addDataUpdate')?>", {
					appr: approve, id_waste_header: id_waste_header, posting_date: postDate, Remark:remark, detMatrialNo: matrialNo, detMatrialDesc: matrialDesc, detWhsQty:whsqty, detQty: qty, detUom: uom, detReason:reason, detText: text
				}, function(){
					$('#load').hide();
				})
				.done(function() {
					location.replace("<?php echo site_url('transaksi1/spoiled/')?>");
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