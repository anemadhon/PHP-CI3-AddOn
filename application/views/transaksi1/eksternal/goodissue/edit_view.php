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
					<div class="card">
                        <div class="card-body">
                            
                                <div class="row">
                                    <div class="col-md-12">
                                        <fieldset>
                                            <legend class="font-weight-semibold"><i class="icon-reading mr-2"></i>Ubah Good Issue</legend>
                                            <div class="form-group row">
												<label class="col-lg-3 col-form-label">ID Transaksi</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" placeholder="ID Transaksi" name="gi_header_id" id="gi_header_id" value="<?=$gi_header['id_gi_header']?>" readOnly>
												</div>
                                            </div>
                                            
                                            <div class="form-group row">
												<label class="col-lg-3 col-form-label">Issue No</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" placeholder="(Auto Number after Posting to SAP)" name="gi_no" id="gi_no" value="<?= $gi_header['status'] == 2 ? $gi_header['gi_no'] :'(Auto Number after Posting to SAP)'?>" readOnly>
												</div>
                                            </div>
                                            
                                            <div class="form-group row">
												<label class="col-lg-3 col-form-label">Outlet</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" placeholder="Outlet" name="plant" id="plant" value="<?=$gi_header['plant']?>" readOnly>
												</div>
                                            </div>
                                            
                                            <div class="form-group row" hidden>
												<label class="col-lg-3 col-form-label">Storage Location</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" placeholder="Storage Location" name="storage_location" id="storage_location" value="<?=$gi_header['storage_location']?>" readOnly>
												</div>
                                            </div>

											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Cost Center</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" placeholder="Cost Center" name="cost_center" id="cost_center" value="<?=$gi_header['cost_center']?>" readOnly>
												</div>
                                            </div>
                                            
                                            <div class="form-group row">
												<label class="col-lg-3 col-form-label">Status</label>
												<div class="col-lg-9">
													<input type="hidden" name="back" id="back" value="<?=$gi_header['back']?>">
													<input type="hidden" name="status" id="status" value="<?=$gi_header['status']?>">
													<input type="text" class="form-control" placeholder="Not Approved" value="<?=$gi_header['status_string']?>" readOnly>
												</div>
											</div>

                                           	<div class="form-group row">
												<label class="col-lg-3 col-form-label">Material Group</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" placeholder="Material Group" name="material_group" id="material_group" value="<?=$gi_header['item_group_code']?>" readOnly>
												</div>
											</div>

                                            <div class="form-group row">
                                                <label class="col-lg-3 col-form-label">Posting Date</label>
                                                <div class="col-lg-9 input-group date">
													<input type="text" class="form-control"  value="<?=date("d-m-Y", strtotime($gi_header['posting_date']))?>" id="postDate" <?= $gi_header['status'] == 2 ? "readonly" :''?>>
													<?php if($gi_header['status'] !='2'): ?>
														<div class="input-group-prepend">
															<span class="input-group-text" id="basic-addon1">
																<i class="icon-calendar"></i>
															</span>
														</div> 
													<?php endif;?>
                                                </div>
											</div>

                                            <div class="form-group row">
                                                <label class="col-lg-3 col-form-label">Issue Note</label>
                                                <div class="col-lg-9 input-group date">
                                                    <textarea name="txtIssue" id="txtIssue" cols="5" rows="5" class="form-control" <?= $gi_header['status'] == 2 ? "readonly" :''?>><?=$gi_header['note']?></textarea>
                                                </div>
											</div>

                                            <?php if($gi_header['status']=='1'): ?>
											<div class="form-group row hide" id="after-submit">
												<div class="col-lg-12 text-right">
													<div class="text-right">
														<button type="button" class="btn btn-primary" id="btn-update" onclick="addDatadb()">Save <i class="icon-pencil5 ml-2"></i></button>
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
                        <div class="card-body">
                            
								<div class="row">
								<legend class="font-weight-semibold"><i class="icon-list mr-2"></i>List Item</legend>
									<?php if($gi_header['status']=='1'): ?>
									<div class="col-md-12 mb-2">
										<div class="text-left">
											<input type="button" class="btn btn-primary" value="Add" id="addTable" onclick="addRow()"> 
											<input type="button" value="Delete" class="btn btn-danger" id="deleteRecord"> 
										</div>
									</div>
									<?php endif;?>
									<div class="col-md-12" style="overflow: auto">
										<table class="table table-striped" id="tblWhole">
											<thead>
												<tr>
													<th></th>
													<th>No</th>
													<th>Issue No</th>
													<th>Material Desc</th>
													<th>In Whs Quantity</th>	
													<th>Quantity</th>
													<th>UOM</th>
													<th>Reason</th>
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
			let id_gi_header = $('#gi_header_id').val();
			let stts = $('#status').val();
			let back = $('#back').val();
			
			const date = new Date();
			const today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
			var optSimple = {
				format: 'dd-mm-yyyy',
				todayHighlight: true,
				orientation: 'bottom right',
				autoclose: true
			};
			$('#postDate').datepicker(optSimple);

			table = $("#tblWhole").DataTable({
				"initComplete": function(settings, json) {
					$("#after-submit").removeClass('hide');
				},
				"ordering":false,
				"paging":false,
				"ajax": {
						"url":"<?php echo site_url('transaksi1/goodissue/showDataDetailOnEdit');?>",
						"data":{ id: id_gi_header, status: stts },
						"type":"POST"
					},
				"columns": [
					
					{"data":"id_gi_detail", "className":"dt-center", render:function(data, type, row, meta){
							rr = ((stts=='1')?`<input type="checkbox" class="check_delete" id="chk_${data}" value="${data}" >`:'');
							return rr;
					}},
					{"data":"no", "className":"dt-center"},
					{"data":"material_no", "className":"dt-center"},
					{"data":"material_desc"},
					{"data":"stock"},
					{"data":"gi_quantity", "className":"dt-center",render:function(data, type, row, meta){
						rr = (stts=='2') ? data : `<input type="text" class="form-control cv" id="gi_qty_${row['no']}" value="${data}" onchange="checkValue(this.value,${row['no']})">`;
						return rr;
					}},
					{"data":"uom"},
					{"data":"reason", "className":"dt-center",render:function(data, type, row, meta){
						rr = ((stts=='1')?`<input type="text" class="form-control" id="reason_${row['no']}" value="${data}">`:data);
						return rr;
					}}
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
		});

		function addRow() {
			let getTable = $("#tblWhole").DataTable();
			count = getTable.rows().count() + 1;
			let elementSelect = document.getElementsByClassName(`dt_${count}`);
			const matrialGroup = $('#material_group').val();
			const tbodyTable = $('#tblWhole > tbody');
			let id_detail = tbodyTable.find('tr').find('td').eq(0).find('input').val();
			
			getTable.row.add({
				"id_gi_detail":`${id_detail}`,
				"no":count,
				"material_no":`<select class="form-control form-control-select2 dt_${count} testSelect" data-live-search="true" data-count="${count}">
									<option value="">Select Item</option>
									${showMatrialDetailData(matrialGroup,elementSelect)}
								</select>`,
				"material_desc":"",
				"stock":"",
				"gi_quantity":"",
				"uom":"",
				"reason":""
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

		function showMatrialDetailData(matrialGroup, selectTable){
			const select = selectTable ? selectTable : $('#matrialGroupDetail');
			$.ajax({
				url: "<?php echo site_url('transaksi1/goodissue/getdataDetailMaterial');?>",
				type: "POST",
				data: {
					matGroup: matrialGroup
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
			table = document.getElementById("tblWhole").rows[no].cells;
			$.post(
				"<?php echo site_url('transaksi1/goodissue/getdataDetailMaterialSelect')?>",{ MATNR:id },(res)=>{
					matSelect = JSON.parse(res);
					matSelect.map((val)=>{
						table[2].innerHTML = val.MATNR;
						table[3].innerHTML = val.MAKTX;
						table[4].innerHTML = val.QTYWH;
						table[5].innerHTML = `<input type="text" class="form-control cv qty_${no}" id="gi_qty_${no}" onchange="checkValue(this.value,${no})">`;
						table[6].innerHTML = val.UNIT;
					})
				}
			)
		}

		function checkValue(val,no){
			table = document.getElementById("tblWhole").rows[no].cells;
			
			if (parseFloat(val) > parseFloat(table[4].innerHTML)) {
				document.getElementById("tblWhole").rows[no].classList.add("bg-danger");
				alert('Quantity Harus Lebih Kecil dari atau Sama Dengan WHS QTY');
				return false;
			} else {
				document.getElementById("tblWhole").rows[no].classList.remove("bg-danger");
			}
		}

		function showReasonDataId(id,value){
			const select = document.getElementById(`r_${id}`);
			$.ajax({
				url: "<?php echo site_url('transaksi1/goodissue/getdataReason');?>",
				success:function(res) {
					optData = JSON.parse(res);
					optData.forEach((val)=>{
						$(`<option value="${val.reason_name}" ${(val.reason_name==value)?'selected':''}>${val.reason_name}</option>`).appendTo(select);
					})
				}
			});			
		}

		function addDatadb(id_approve=''){

			const idGi= document.getElementById('gi_header_id').value;
			const noteGi= document.getElementById('txtIssue').value;
			const datePostGi= document.getElementById('postDate').value;
			const approve = id_approve;
			const tbodyTable = $('#tblWhole > tbody');
			
			let idDetailGi =[];
			let matrialNo =[];
			let matrialDesc =[];
			let stock = [];
			let qty =[];
			let uom =[];
			let text = [];
			let validasi = true;
			let validasiQty = true;
			let dataItem = [];
			let validasiReasson = true;

			tbodyTable.find('tr').each(function(i, el){
				let td = $(this).find('td');	
				if(td.eq(7).find('input').val() == ''){
					validasiReasson = false;
				}
				if (parseFloat(td.eq(4).text()) < parseFloat(td.eq(5).find('input').val())) {
					dataItem.push(td.eq(2).text());
					validasi = false;
				}
				if (td.eq(5).find('input').val()=='') {
					dataItem.push(td.eq(2).text());
					validasiQty = false;
				}
				idDetailGi.push(td.eq(0).find('input').val());
				matrialNo.push(td.eq(2).text()); 
				matrialDesc.push(td.eq(3).text());
				stock.push(td.eq(4).text());
				qty.push(td.eq(5).find('input').val());
				
				uom.push(td.eq(6).text());
				text.push(td.eq(7).find('input').val());
			})
			if(!validasiReasson){
				alert('Reasson Tidak boleh Kosong, Harap di isi');
				return false;
			}

			if(!validasi){
				alert('Quantity Dari Material Number '+dataItem.join()+' Harus Lebih Kecil dari atau Sama Dengan WHS QTY');
				return false;
			}

			if(!validasiQty){
				alert('Quantity Dari Material Number '+dataItem.join()+' Harus Di Isi');
				return false;
			}

			$('#load').show();
			$("#after-submit").addClass('after-submit');

			setTimeout(() => {
				$.post("<?php echo site_url('transaksi1/goodissue/addDataDbUpdate')?>", {
					id_gi: idGi, 
					appr: approve, 
					posting_date: datePostGi, 
					note: noteGi,
					id_detail_gi: idDetailGi, 
					detMatrialNo: matrialNo, 
					detMatrialDesc: matrialDesc, 
					detQty: qty, detUom: uom, 
					detText: text,
					onHand: stock
				}, function(){
					$('#load').hide();
				})
				.done(function() {
					location.replace("<?php echo site_url('transaksi1/goodissue/')?>");
				})
				.fail(function(xhr, status) {
					alert(`Terjadi Error (${xhr.status} : ${xhr.statusText}), Silahkan Coba Lagi`);
					location.reload(true);
				});
			}, 600);
		}

		function onCancel(flag){
			const id_gi = $('#gi_header_id').val();
			const cancel = flag;

			$.post("<?php echo site_url('transaksi1/goodissue/onCancel')?>", {
				idGi: id_gi, Cancel: cancel
			}, function(res){location.reload(true);});
		}

		function changeData(){
			alert('soon..');
		}
		</script>
	</body>
</html>