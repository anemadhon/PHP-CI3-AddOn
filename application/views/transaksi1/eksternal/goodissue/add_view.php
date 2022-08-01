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
                                            <legend class="font-weight-semibold"><i class="icon-reading mr-2"></i>Tambah Good Issue</legend>
                                            <div class="form-group row">
												<label class="col-lg-3 col-form-label">ID Transaksi</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" placeholder="ID Transaksi" name="issue_id" id="issue_id" readOnly>
												</div>
                                            </div>
                                            
                                            <div class="form-group row">
												<label class="col-lg-3 col-form-label">Issue No</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" placeholder="(Auto Number after Posting to SAP)" name="issue_no" id="issue_no" readOnly>
												</div>
                                            </div>
                                            
                                            <div class="form-group row">
												<label class="col-lg-3 col-form-label">Outlet</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" placeholder="Outlet" name="outlet" id="outlet" value="<?php echo $plant.' - '.$plant_name ?>" readOnly>
												</div>
                                            </div>
                                            
                                            <div class="form-group row" hidden>
												<label class="col-lg-3 col-form-label">Storage Location</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" placeholder="Storage Location" name="storage_location" id="storage_location" value="<?php echo $storage_location.' - '.$storage_location_name ?>" readOnly>
												</div>
                                            </div>

											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Cost Center</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" placeholder="Cost Center" name="cost_center" id="cost_center" value="<?php echo $cost_center.' - '.$cost_center_name ?>" readOnly>
												</div>
                                            </div>
                                            
                                            <div class="form-group row">
												<label class="col-lg-3 col-form-label">Status</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" placeholder="Not Approved" readOnly>
													<input type="hidden" name="status" id="status" value="1" readonly>
												</div>
											</div>

                                           	<div class="form-group row">
												<label class="col-lg-3 col-form-label">Material Group</label>
												<div class="col-lg-9">
													<select class="form-control form-control-select2" name="MatrialGroup" id="MatrialGroup" data-live-search="true" onchange="showMatrialDetail(this.value)">
														<option value="">Select Item</option>
														<option value="all">All</option>
														<?php foreach($matrialGroup as $key=>$val):?>
															<option value="<?=$val['ItmsGrpNam']?>"><?=$val['ItmsGrpNam']?></option>
														<?php endforeach;?>
													</select>
												</div>
											</div>

                                            <div class="form-group row hid" style="display:none">
                                                <label class="col-lg-3 col-form-label">Posting Date</label>
                                                <div class="col-lg-9 input-group date">
                                                    <input type="text" class="form-control" id="postDate" autocomplete="off">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1">
                                                            <i class="icon-calendar"></i>
                                                        </span>
                                                    </div>
                                                </div>
											</div>

                                            <div class="form-group row hid" style="display:none">
                                                <label class="col-lg-3 col-form-label">Issue Note</label>
                                                <div class="col-lg-9 input-group date">
                                                    <textarea name="txtIssue" id="txtIssue" cols="5" rows="5" class="form-control"></textarea>
                                                </div>
											</div>

                                            <div class="text-right hid" style="display:none" id="after-submit">
                                                <button type="button" class="btn btn-primary" onclick="addData()">Save<i class="icon-pencil5 ml-2"></i></button>	
                                            </div>

                                        </fieldset>
                                    </div>
                                </div>
								</div>
                                </div>
								<div id="load" style="display:none"></div> 
								<div class="card hid" style="display:none">
                        <div class="card-body">
                            
								<div class="row">
								<legend class="font-weight-semibold"><i class="icon-list mr-2"></i>List Item</legend>
								<div class="col-md-12 mb-2">
										<div class="text-left">
											<input type="button" class="btn btn-primary" value="Add" id="addTable" onclick="addRow()"> 
											<input type="button" value="Delete" class="btn btn-danger" id="deleteRecord"> 
										</div>
									</div>
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
											<tbody>
												<tr>
													<td><input type="checkbox" class="check_delete" id="dt"></td>
													<td>1</td>
													<td width="25%">
														<select class="form-control form-control-select2" data-live-search="true" id="matrialGroupDetail" onchange="setValueTable(this.value,1)">
															<option value="">Select Item</option>
														</select>
													</td>
													<td width="30%"></td>
													<td></td>
													<td><input type="text" class="form-control cv" id="cv" name="qty[]" value="" style="width:100%"></td>
													<td></td>
													<td>
														<input type="text" class="form-control" name="reason[]" value="" style="width:100%">
													</td>
												</tr>
											</tbody>
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
			var table = $("#tblWhole").DataTable({
				"ordering":false,
				"paging":false,
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
			$('#postDate').datepicker(optSimple);
		});

		function addRow() {
			let getTable = $("#tblWhole").DataTable();
			count = getTable.rows().count() + 1;
			let elementSelect = document.getElementsByClassName(`dt_${count}`);
			const matrialGroup = $('#MatrialGroup').val();
			let elementSelectR = document.getElementsByClassName(`r_${count}`);

			getTable.row.add({
				"0":`<input type="checkbox" class="check_delete" value="dt_${count}" id="dt_${count}" onclick="checkcheckbox();">`,
				"1":count,
				"2":`<select class="form-control form-control-select2 dt_${count} testSelect" data-live-search="true" data-count="${count}">
						<option value="">Select Item</option>
						${showMatrialDetailData(matrialGroup,elementSelect)}
					</select>`,
				"3":"",
				"4":"",
				"5":`<input type="text" class="form-control cv qty_${count}" id="gi_qty_${count}" value="" style="width:100%" onchange="checkValue(this.value,${count})">`,
				"6":"",
				"7":`<input type="text" class="form-control" id="reason_${count}" value="" style="width:100%">`
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

		function showMatrialDetail(){
			const matrialGroup = $('#MatrialGroup').val();
			
			showMatrialDetailData( matrialGroup);		

			$('.hid').show();
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
						table[3].innerHTML = val.MAKTX;
						table[4].innerHTML = val.QTYWH.slice(0,-2);
						table[5].innerHTML = `<input type="text" class="form-control cv qty_${no}" id="gi_qty_${no}" value="" style="width:100%" onchange="checkValue(this.value,${no})">`;
						table[6].innerHTML = val.UNIT;
					})
				}
			)
		}

		function checkValue(val,no){
			let cv = $('#cv').val();
			table = document.getElementById("tblWhole").rows[no].cells;
			
			if (val > parseFloat(table[4].innerHTML) || cv > parseFloat(table[4].innerHTML)) {
				document.getElementById("tblWhole").rows[no].classList.add("bg-danger");
				alert('Quantity Harus Lebih Kecil dari atau Sama Dengan WHS QTY');
				return false;
			} else {
				document.getElementById("tblWhole").rows[no].classList.remove("bg-danger");
			}
		}

		function addData(id_approve=''){
			if($('#postDate').val() ==''){
				alert('Tanggal Posting harus di isi');
				return false;
			}
			if($('#txtIssue').val() ==''){
				alert('Issue Note harus di isi');
				return false;
			}
			if($('.cv').val() ==''){
				alert('Quatity harus di isi');
				return false;
			}
			const outlet= document.getElementById('outlet').value;
			const storage_location= document.getElementById('storage_location').value;
			const cost_center= document.getElementById('cost_center').value;
			const status= document.getElementById('status').value;
			const MatrialGroup= document.getElementById('MatrialGroup').value;
			const postDate= document.getElementById('postDate').value;
			const note = document.getElementById('txtIssue').value;
			const approve = id_approve;
			const tbodyTable = $('#tblWhole > tbody');
			let matrialNo =[];
			let matrialDesc =[];
			let stock = [];
			let qty =[];
			let uom =[];
			let text = [];
			let validasi = true;
			let validasiReasson = true;
			let dataItem = [];

			tbodyTable.find('tr').each(function(i, el){
				let td = $(this).find('td');	
				if(td.eq(7).find('input').val() == ''){
					validasiReasson = false;
				}
				matrialNo.push(td.eq(2).find('select').val()); 
				matrialDesc.push(td.eq(3).text());
				stock.push(td.eq(4).text());
				qty.push(td.eq(5).find('input').val());
				if (parseFloat(td.eq(4).text()) < parseFloat(td.eq(5).find('input').val())) {
					dataItem.push(td.eq(2).find('select').val());
					validasi = false;
				}
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

			$('#load').show();
			$("#after-submit").addClass('after-submit');

			setTimeout(() => {
				$.post("<?php echo site_url('transaksi1/goodissue/addDataDb')?>", {
					Plant: outlet, 
					StorageLoc: storage_location, 
					costCenter: cost_center, 
					appr: approve, 
					matGroup: MatrialGroup, 
					stts: status, 
					posting_date: postDate, 
					Note: note,
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
		
		</script>
	</body>
</html>