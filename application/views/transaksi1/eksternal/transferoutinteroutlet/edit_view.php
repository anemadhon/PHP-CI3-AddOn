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
					<input type="hidden" name="status" id="status" value="<?=$gistonew_out_header['status']?>">
						<div class="card">
							<div class="card-body">
								<div class="row">
									<div class="col-md-12">
										<fieldset>
											<legend class="font-weight-semibold"><i class="icon-reading mr-2"></i>Transfer Out Inter Outlet</legend>

											<div class="form-group row">
												<label class="col-lg-3 col-form-label">ID Transaksi</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" value="<?=$gistonew_out_header['id_gistonew_out_header']?>" id="id_gistonew_out_header" nama="id_gistonew_out_header" readOnly>
												</div>
                                            </div>
											
											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Store Room Reques(SR) Number</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" value="<?=$gistonew_out_header['po_no1']?>" name="srEntry1" id="srEntry1" readOnly>
													<input type="hidden" value="<?=$gistonew_out_header['po_no']?>" id="srEntry" readOnly>
												</div>
                                            </div>
											
											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Transfer Slip Number</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" value="<?= $gistonew_out_header['status'] == 2 ? $gistonew_out_header['transfer_slip_number1'] :'(Auto Number after Posting to SAP)'?>" id="transfer_slip_number1" nama="transfer_slip_number1" readOnly>
													<input type="hidden" value="<?= $gistonew_out_header['transfer_slip_number']; ?>" id="transfer_slip_number" readOnly>
												</div>
											</div>
											
											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Outlet From</label>
												<div class="col-lg-9">
													<input type="hidden" class="form-control" readonly="" value="<?=$gistonew_out_header['plant']?>" name="outlet" id="outlet">
													<input type="text" class="form-control" readonly="" value="<?=$gistonew_out_header['plant_str']?>" >
												</div>
											</div>
											
											<div class="form-group row" hidden>
												<label class="col-lg-3 col-form-label">Storage Transit Location</label>
												<div class="col-lg-9">
													<input type="hidden" class="form-control" readonly="" value="<?=$gistonew_out_header['storage_location']?>" name="storageLocation" id="storageLocation">
													<input type="text" class="form-control" readonly="" value="<?=$gistonew_out_header['storage_location_str']?>" >
													
												</div>
											</div>
											
											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Request To</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" readonly="" value="<?=$gistonew_out_header['to_plant']?>" name="rto" id="rto">
												</div>
											</div>

											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Status</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" readonly="" value="<?=$gistonew_out_header['status_string']?>">
												</div>
											</div>
											
											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Material Group</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" value="<?=$gistonew_out_header['item_group_code']?>" name="MatrialGroup" id="MatrialGroup" readonly>
												</div>
											</div>
											
											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Posting Date</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" readonly="" value="<?=date("d-m-Y", strtotime($gistonew_out_header['posting_date']))?>" id="postingDate"> 
												</div>
											</div>

											<div class="form-group row">
                                                <label class="col-lg-3 col-form-label">Remarks</label>
                                                <div class="col-lg-9 input-group date">
                                                    <textarea id="remark" cols="30" rows="3" class="form-control" <?php if($gistonew_out_header['status']=='2'):?>readonly=""<?php endif; ?>><?=$gistonew_out_header['remark']; ?></textarea>
                                                </div>
											</div>

											<?php if($gistonew_out_header['status']=='1'): ?>
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
								<legend class="font-weight-semibold"><i class="icon-list mr-2"></i>List Transfer Out Inter Outlet</legend>
							</div>
							<div class="card-body">
								<table id="table-manajemen" class="table table-striped " style="width:100%">
								<?php if($gistonew_out_header['status']!='2'):?>
									<div class="col-md-12 mb-2">
										<div class="text-left">
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
											<th>In WHS Quantity</th>
											<th>Outstanding Qty</th>
											<th>Quantity</th>
											<th>Uom Reg.</th>
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
                let id_gistonew_out_header = $('#id_gistonew_out_header').val();
				let stts = $('#status').val();

				table = $("#table-manajemen").DataTable({
					"initComplete": function(settings, json) {
						$("#after-submit").removeClass('hide');
					},
					"ordering":false,
					"paging":false,
					"ajax": {
							"url":"<?php echo site_url('transaksi1/transferoutinteroutlet/showGistonewOutDetail');?>",
							"data":{ id: id_gistonew_out_header, status: stts },
							"type":"POST"
						},
					"columns": [
						
						{"data":"id_gistonew_out_detail", "className":"dt-center", render:function(data, type, row, meta){
								rr=row['status']==2 ? '' : `<input type="checkbox" class="check_delete" id="chk_${data}" value="${data}" >`;
								return rr;
						}},
						{"data":"no","className":"dt-center" ,render:function(data, type, row, meta){
							rr = `<input type="hidden" value="${row['posnr']}">`;
							return rr+data;
						}},
						{"data":"material_no", "className":"dt-center"},
						{"data":"material_desc"},
						{"data":"in_whs_qty", "className":"dt-center whsQty"},
						{"data":"outstanding_qty", "className":"dt-center"},
						{"data":"gr_quantity", "className":"dt-center",render:function(data, type, row, meta){
							rr=  `<input type="text" class="form-control qty" id="gr_qty_${row['no']}" value="${data}" 
								${row['status']==1 ?'':'readonly'}>`;
							return rr;
						}},
						{"data":"uom"},
						{"data":"uom_req"}
					],
					drawCallback: function() {
						$('.form-control-select2').select2();
					}
				});


				$("#cancelRecord").click(function(){
					const id_gistonew_out_header = $('#id_gistonew_out_header').val();
                    let deleteidArr=[];
                    $("input:checkbox[class=check_delete]:checked").each(function(){
                        deleteidArr.push($(this).val());
                    })

                    // mengecek ckeckbox tercheck atau tidak
                    if(deleteidArr.length > 0){
                        var confirmDelete = confirm("Apa Kamu Yakin Akan Membatalkan TO ini?");
                        if(confirmDelete == true){
                            $.ajax({
                                url:"<?php echo site_url('transaksi1/transferoutinteroutlet/cancelGistonewOut');?>", //masukan url untuk delete
                                type: "post",
                                data:{deleteArr: deleteidArr, id_gistonew_out_header:id_gistonew_out_header},
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

				$.post("<?php echo site_url('transaksi1/Transferoutinteroutlet/getDetailsTransferOutEdit');?>",{ cboMatrialGroup: cboMatrialGroup,doNo: do_no},(data)=>{
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
					"<?php echo site_url('transaksi1/Transferoutinteroutlet/getdataDetailMaterialSelect')?>",{ MATNR:id, do_no:doNo },(res)=>{
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
				if($('.qty').val() ==''){
					alert('Quatity harus di isi');
					return false;
				}

				const id_gistonew_out_header = $('#id_gistonew_out_header').val();
				const srEntry = $('#srEntry').val();
				const srEntry1 = $('#srEntry1').val();
				const approve = id_approve;
				const remark= $('#remark').val();
				const tbodyTable = $("#table-manajemen > tbody");
				let matrial_no=[];
				let detail_qty=[];
				let matrialDesc =[];
				let whsQty = [];
				let out_qty =[];
				let qty =[];
				let uom =[];
				let uom_reg = [];
				let validasi = true;
				tbodyTable.find('tr').each(function(i,el){
					let td = $(this).find('td');
					if(parseInt(td.eq(6).find('input').val(),10) > parseFloat(td.eq(5).text())){
							validasi = false;
					}

					matrial_no.push(td.eq(2).text().trim());
					matrialDesc.push(td.eq(3).text());
					whsQty.push(td.eq(4).text());
					out_qty.push(td.eq(5).text());
					qty.push(td.eq(6).find('input').val());
					uom.push(td.eq(7).text());
					uom_reg.push(td.eq(8).text());
				})

				if(!validasi){
					alert('Quatity Tidak boleh lebih besar dari Outstanding Quantity');
					return false;
				}

				$('#load').show();
				$("#after-submit").addClass('after-submit');

				setTimeout(() => {
					$.post("<?php echo site_url('transaksi1/Transferoutinteroutlet/addDataUpdate')?>", {
						idGistonew_out_header: id_gistonew_out_header, poNo: srEntry, poNo1: srEntry1, aapr:approve, Remark:remark, detMatrialNo: matrial_no, detMatrialDesc: matrialDesc, detOutQty:out_qty, detQty: qty, detUom: uom, detUomReg:uom_reg, detWhsQty: whsQty
					}, function(){
						$('#load').hide();
					})
					.done(function() {
						location.replace("<?php echo site_url('transaksi1/transferoutinteroutlet/')?>");
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