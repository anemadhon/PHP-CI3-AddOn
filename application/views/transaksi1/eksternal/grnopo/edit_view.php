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
					<input type="hidden" name="status" id="status" value="<?=$grnonpo_header['status']?>">
					<div class="card">
                        <div class="card-body">
                            
                                <div class="row">
                                    <div class="col-md-12">
                                        <fieldset>
                                            <legend class="font-weight-semibold"><i class="icon-reading mr-2"></i>Ubah Goods Receipt Non PO</legend>
                                            <div class="form-group row">
												<label class="col-lg-3 col-form-label">ID Transaksi</label>
												<div class="col-lg-9">
													<input type="text" class="form-control"  value="<?=$grnonpo_header['id_grnonpo_header']?>" id="idgrnonpo" name="idgrnonpo" readOnly>
												</div>
                                            </div>
                                            
                                            <div class="form-group row">
												<label class="col-lg-3 col-form-label">Goods Receipt No.</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" value="<?= $grnonpo_header['status'] == 2 ? $grnonpo_header['grnonpo_no'] :'(Auto Number after Posting to SAP)'?>" id="grNo" nama="grNo" readOnly>
												</div>
                                            </div>
                                            
                                            <div class="form-group row">
												<label class="col-lg-3 col-form-label">Plant</label>
												<div class="col-lg-9">
													<input type="text" class="form-control"  value="<?=$grnonpo_header['plant']?>" id="plant" name="plant" readOnly>
												
												</div>
                                            </div>
                                            
                                            <div class="form-group row" hidden>
												<label class="col-lg-3 col-form-label">Storage Location</label>
												<div class="col-lg-9">
													<input type="text" class="form-control"  value="<?=$grnonpo_header['storage_location']?>" id="storage_location" name="storage_location" readOnly>
												
												</div>
                                            </div>

											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Cost Center</label>
												<div class="col-lg-9">
													<input type="text" class="form-control"  value="<?=$grnonpo_header['cost_center']?>" id="cost_center" name="cost_center" readOnly>
												</div>
											</div>
                                            
                                            <div class="form-group row">
												<label class="col-lg-3 col-form-label">Status</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" value="<?=$grnonpo_header['status_string']?>" id="status_string" name="status_string" readOnly>
												</div>
											</div>

                                           	<div class="form-group row">
												<label class="col-lg-3 col-form-label">Material Group</label>
												<div class="col-lg-9">
												<input type="text" class="form-control" value="<?=$grnonpo_header['item_group_code']?>" name="MatrialGroup" id="MatrialGroup" readonly>
												</div>
											</div>

                                            <div class="form-group row">
                                                <label class="col-lg-3 col-form-label">Posting Date</label>
                                                <div class="col-lg-9 input-group date">
													<input type="text" class="form-control"  value="<?=date("d-m-Y", strtotime($grnonpo_header['posting_date']))?>" id="postingDate" <?= $grnonpo_header['status'] == 2 ? "readonly" :''?>>
													<?php if($grnonpo_header['status'] !='2'): ?>
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
                                                    <textarea id="remark" cols="5" rows="5" class="form-control" <?= $grnonpo_header['status'] == 2 ? "readonly" :''?>><?= $grnonpo_header['remark'];?></textarea>
                                                </div>
											</div>

											<?php if($grnonpo_header['status']=='1'): ?>
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
                        <div class="card-body">
                            
								<div class="row">
								<legend class="font-weight-semibold"><i class="icon-list mr-2"></i>List Item</legend>
									<div class="col-md-12" style="overflow: auto">
										<table class="table table-striped" id="tblWhole">
										<?php if($grnonpo_header['status']!='2'):?>
											<div class="col-md-12 mb-2">
												<div class="text-left">
													<input type="button" class="btn btn-primary" value="Add" id="addTable" onclick="onAddrow()"> 
													<input type="button" value="Delete" class="btn btn-danger" id="deleteRecord"> 
												</div>
											</div>
										<?php endif; ?>
											<thead>
												<tr>
													<th>*</th>
													<th>No</th>
													<th>Material No</th>
													<th>Material Desc</th>
													<th>Quantity</th>
													<th>Unit Price</th>
													<th>Total</th>
													<th>UOM</th>
													<th>Text</th>
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
                let id_grnonpo_header = $('#idgrnonpo').val();
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
							"url":"<?php echo site_url('transaksi1/grnopo/showGistonewOutDetail');?>",
							"data":{ id: id_grnonpo_header, status: stts },
							"type":"POST"
						},
					"columns": [
						
						{"data":"id_grnonpo_detail", "className":"dt-center", render:function(data, type, row, meta){
								rr=(row["status"] == 2) ? '' : `<input type="checkbox" class="check_delete" id="chk_${data}" value="${data}" >`;
								return rr;
						}},
						{"data":"no", "className":"dt-center"},
						{"data":"material_no", "className":"dt-center"},
						{"data":"material_desc"},
						{"data":"gr_quantity", "className":"dt-center",render:function(data, type, row, meta){
							rr=  (row["status"] == 2) ? data : `<input type="text" class="form-control qty" id="gr_qty_${row['no']}" value="${data}">`;
							return rr;
						}},
						{"data":"price", "className":"dt-center",render:function(data, type, row, meta){
							rr=  (row["status"] == 2) ? data : `<input type="text" class="form-control prc" id="gr_prc_${row['no']}" value="${data}">`;
							return rr;
						}},
						{"data":"total"},
						{"data":"uom"},
						{"data":"text", "className":"dt-center",render:function(data, type, row, meta){
							rr= (row["status"] == 2) ? data : `<input type="text" class="form-control" id="text_${row['no']}" value="${data}">`;
							return rr;
						}}
					],
					drawCallback: function() {
						$('.form-control-select2').select2();
					}
				});


				$("#cancelRecord").click(function(){
					const id_grnonpo_header = $('#idgrnonpo').val();
                    let deleteidArr=[];
                    $("input:checkbox[class=check_delete]:checked").each(function(){
                        deleteidArr.push($(this).val());
                    })

                    // mengecek ckeckbox tercheck atau tidak
                    if(deleteidArr.length > 0){
                        var confirmDelete = confirm("Apa Kamu Yakin Akan Membatalkan Goods Receipt Non PO ini?");
                        if(confirmDelete == true){
                            $.ajax({
                                url:"<?php echo site_url('transaksi1/grnopo/cancelGrNonPo');?>", //masukan url untuk delete
                                type: "post",
                                data:{deleteArr: deleteidArr, id_grnonpo_header:id_grnonpo_header},
                                success:function(res) {
									location.reload(true);
                                }
                            });
                        }
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

			});
			
			function onAddrow(){
				let getTable = $("#tblWhole").DataTable();
				count = getTable.rows().count() + 1;
				let elementSelect = document.getElementsByClassName(`dt_${count}`);
				var doNo = $('#idgrnonpo').val();
				const matrialGroup = $('#MatrialGroup').val() ? $('#MatrialGroup').val() : 'all';
				
				getTable.row.add({
					"no":count,
					"material_no":`<select class="form-control form-control-select2 dt_${count} testSelect" data-live-search="true" id="selectDetailMatrial" data-count="${count}">
									<option value="">Select Item</option>
									${showMatrialDetailData(matrialGroup, doNo, elementSelect)}
								</select>`,
					"material_desc":"",
					"gr_quantity":"",
					"price":"",
					"total":"",
					"uom":"",
					"text":""
					}).draw();
					count++;

				tbody = $("#tblWhole tbody");
				tbody.on('change','.testSelect', function(){
					tr = $(this).closest('tr');
					no = tr[0].rowIndex;
					id = $('.dt_'+no).val();
					setValueTable(doNo,id,no);
				});
				tbody.on('change','.qty', function(){
					tr = $(this).closest('tr');
					no = tr[0].rowIndex;
					qty = $('.qty').eq(no-1).val(); 
					prc = $('.prc').eq(no-1).val(); 
					setTotal(qty,prc,no);
				});
				tbody.on('change','.prc', function(){
					tr = $(this).closest('tr');
					no = tr[0].rowIndex;
					qty = $('.qty').eq(no-1).val(); 
					prc = $('.prc').eq(no-1).val(); 
					setTotal(qty,prc,no);
				});
			}

			function showMatrialDetailData(cboMatrialGroup='',do_no='', selectTable){
				
				const select = selectTable ? selectTable : $('#matrialGroupDetail');

				$.post("<?php echo site_url('transaksi1/grnopo/getDetailsTransferOut');?>",{ cboMatrialGroup: cboMatrialGroup},(data)=>{
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
				table = document.getElementById("tblWhole").rows[no].cells;
				$.post(
					"<?php echo site_url('transaksi1/grnopo/getdataDetailMaterialSelect')?>",{ MATNR:id, do_no:doNo },(res)=>{
						matSelect = JSON.parse(res);

						for(let i in matSelect){
							if(matSelect.hasOwnProperty(i)){
								table[2].innerHTML = `<td>${matSelect[i].MATNR}</td>`;
								table[3].innerHTML = matSelect[i].MAKTX;
								table[7].innerHTML = matSelect[i].UNIT;
							}
						}
					}
				)
			}

			function setTotal(qty,prc,no){
				table = document.getElementById("tblWhole").rows[no].cells;
				table[6].innerHTML = (parseFloat(qty)*parseFloat(prc))
			}

			function addDatadb(id_approve=''){
				const id_grnonpo_header = $('#idgrnonpo').val();
				const approve = id_approve;
				const postingDate= $('#postingDate').val();
				const remark= $('#remark').val();
				const tbodyTable = $("#tblWhole > tbody");
				let matrial_no=[];
				let matrialDesc =[];
				let qty =[];
				let prc =[];
				let uom =[];
				let text = [];
				tbodyTable.find('tr').each(function(i,el){
					let td = $(this).find('td');
					matrial_no.push(td.eq(2).text().trim());
					matrialDesc.push(td.eq(3).text());
					qty.push(td.eq(4).find('input').val());
					prc.push(td.eq(5).find('input').val());
					uom.push(td.eq(7).text());
					text.push(td.eq(8).find('input').val());
				})

				$('#load').show();
				$("#after-submit").addClass('after-submit');

				setTimeout(() => {
					$.post("<?php echo site_url('transaksi1/grnopo/addDataUpdate')?>", {
						idgrnonpo_header: id_grnonpo_header, aapr:approve, pstDate: postingDate, Remark:remark, detMatrialNo: matrial_no, detMatrialDesc: matrialDesc, detQty: qty, detPrc: prc, detUom: uom, detText:text
					}, function(){
						$('#load').hide();
					})
					.done(function() {
						location.replace("<?php echo site_url('transaksi1/grnopo/')?>");
					})
					.fail(function(xhr, status) {
						alert(`Terjadi Error (${xhr.status} : ${xhr.statusText}), Silahkan Coba Lagi`);
						location.reload(true);
					});
				}, 600);
			}
        
        </script>
		</script>
	</body>
</html>