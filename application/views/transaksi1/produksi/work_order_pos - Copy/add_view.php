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
			.hide{
				display: none;
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
											<legend class="font-weight-semibold"><i class="icon-reading mr-2"></i>Produksi</legend>
											<div class="form-group row">
												<label class="col-lg-3 col-form-label">ID Transaksi</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" placeholder="ID Transaksi" readOnly>
												</div>
											</div>

											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Outlet</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" placeholder="Outlet" value="<?=$plant.' - '.$plant_name?>" readOnly>
												</div>
											</div>

											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Item Produksi</label>
												<div class="col-lg-9">
													<div id="item1">
														<select class="form-control form-control-select2" data-live-search="true" id="selectItem" onchange="getDataHeader(this.value)">
															<option value="">Select Item</option>
															<?php foreach($wo_code as $key=>$value):?>
																<option value="<?=$key?>" desc="<?=$value?>"><?=$value?></option>
															<?php endforeach;?>
														</select>
													</div>
													<div id="item2" class="hide">
														<input type="text" id="itemSelected" class="form-control" placeholder="" readOnly>
														<input type="hidden" id="wonumber">
														<input type="hidden" id="wodesc">
														<input type="hidden" id="wolocked">
													</div>
												</div>
											</div>

											<div id='form1' class="hide">
												<div class="form-group row">
													<label class="col-lg-3 col-form-label">Qty Produksi</label>
													<div class="col-lg-9">
														<input type="text" id="qtyProduksi" class="form-control" placeholder="(Suggest Qty : 1.0000)" >
													</div>
												</div>
											</div>
											
											<div id='form2' class="hide">
												<div class="form-group row">
													<label class="col-lg-3 col-form-label">UOM</label>
													<div class="col-lg-9">
														<input type="text" id="uomProduksi" class="form-control" readOnly>
													</div>
												</div>

												<div class="form-group row">
													<label class="col-lg-3 col-form-label">Posting Date</label>
													<div class="col-lg-9 input-group date">
														<input type="text" class="form-control" id="postDate" readonly autocomplate="off">
														<div class="input-group-prepend">
															<span class="input-group-text" id="basic-addon1">
																<i class="icon-calendar"></i>
															</span>
														</div>
													</div>
												</div>

												<div class="text-right" id="after-submit">
													<button type="button" class="btn btn-primary" name="save" id="save" onclick="addDatadb()">Save <i class="icon-pencil5 ml-2"></i></button>
													<?php if ($this->auth->is_have_perm('auth_approve')) : ?>
													<button type="button" class="btn btn-success" name="approve" id="approve" onclick="addDatadb(2)" >Approve <i class="icon-paperplane ml-2" ></input></i>
													<?php endif; ?>
												</div>
											</div>
											
										</fieldset>
									</div>
								</div>
							</div>
						</div> 
						<div id="load" style="display:none"></div>  
						<div class='hide' id="form3">
							<div class="card">
								<div class="card-body">
									<div class="row">
										<legend class="font-weight-semibold"><i class="icon-list mr-2"></i>List Item</legend>
										<div class="col-md-12 mb-2" id="btnAddListItem">
											<div class="text-left">
												<input type="button" class="btn btn-primary" value="Add" id="addTable" onclick="onAddrow()"> 
												<input type="button" value="Delete" class="btn btn-danger" id="deleteRecord"> 
											</div>
										</div>
										<div class="col-md-12" style="overflow: auto" >
											<table class="table table-striped" id="tblWhole">
												<thead>
													<tr>
														<th><input type="checkbox" name="checkall" id="checkall"></th>
														<th>Material No</th>
														<th>Material Desc</th>
														<th>Quantity</th>
														<th>UOM</th>
														<th>On Hand</th>
													</tr>
												</thead>
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
		$(document).ready(function(){
			table = $("#tblWhole").DataTable({
				"ordering":false,
				"paging":false,
				drawCallback: function() {
					$('.form-control-select2').select2();
				}
			});
			
			// untuk check all
			$("#checkall").click(function(){
				if($(this).is(':checked')){
					$(".check_delete").prop('checked', true);
				}else{
					$(".check_delete").prop('checked', false);
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
			$('#postDate').datepicker(optSimple);

			tbody = $("#tblWhole tbody");
			tbody.on('change','#descmat', function(){
				tr = $(this).closest('tr');
				no = tr[0].rowIndex;
				const qty = $("option:selected", this).attr("matqty");
				const matrial_no = $("option:selected", this).val();
				const rel = $("option:selected", this).attr("rel");
				const onHand = $("option:selected", this).attr("onhand");
				const minStock = $("option:selected", this).attr("minstock");
				const uOm = $("option:selected", this).attr("uOm");
				table = document.getElementById("tblWhole").rows[no].cells;
				table[1].innerHTML = matrial_no;
				table[3].innerHTML = `<input type="text" id="editqty" class="form-control" value="${qty}" ${rel == "N" ? "readonly": ""}>`;
				table[4].innerHTML = uOm;
				table[5].innerHTML = onHand;
				table[6].innerHTML = minStock;
			});
			
		});
		
		function getDataHeader(woNumber){
			var selectedText = $("#selectItem option:selected").val();
			var desc = $('#selectItem option:selected').attr('desc');
			$("#form1").removeClass('hide');
			$("#item1").addClass('hide');
			$("#item2").removeClass('hide');
			$("#itemSelected").attr('placeholder',desc);
			$("#wonumber").val(woNumber);
			$("#wodesc").val(desc);
			
			$.post("<?php echo site_url('transaksi1/wopos/wo_header_uom');?>",{material_no: woNumber},(data)=>{
				const value = JSON.parse(data);
				if(value.data[0]['U_Locked'] == 'Y'){
					$("#btnAddListItem").addClass('hide');
				}
				$("#uomProduksi").val(value.data[0]['InvntryUom']);
			});
		}
		
		$('#qtyProduksi').keypress(function(event){
			var keycode = (event.keyCode ? event.keyCode : event.which);
			if(keycode == '13'){
				event.preventDefault();
				$("#form2").removeClass('hide');
				$("#form3").removeClass('hide');
				const qty = $("#qtyProduksi").val();
				$(this).attr('readonly', true);
				
				let kode_paket = $("#wonumber").val();

				var obj = $('#tblWhole tbody tr').length;

				if(obj>0){
					const tables = $('#tblWhole').DataTable();

					tables.destroy();
					$("#tblWhole > tbody > tr").remove();
				}
				
                dataTable = $("#tblWhole").DataTable({
                    "ordering":false,  "paging": false, "searching":true,
					drawCallback: function() {
					$('.form-control-select2').select2();
					},
                    "ajax": {
                        "url":"<?php echo site_url('transaksi1/wopos/showDetailInput');?>",
						"data":{  
							kode_paket:kode_paket,
							Qty:qty

						},
                        "type":"POST"
                    },
                    "columns": [
                        {"data":"no", "className":"dt-center", render:function(data, type, row, meta){
                            rr=`<input type="checkbox"  value="${data}" class="check_delete" id="dt_${row['no']}" onclick="checkcheckbox();">`;
                            return rr;
                        }},
                        {"data":"material_no", "className":"dt-center"},
                        {"data":"descolumn"},
                        {"data":"qty", "className":"dt-center"},
                        {"data":"uom", "className":"dt-center"},
                        {"data":"OnHand", "className":"dt-center"}
                    ]
                });
			}
		});

		function onAddrow(){
			let getTable = $("#tblWhole").DataTable();
			count = getTable.rows().count() + 1;
			let elementSelect = document.getElementsByClassName(`dt_${count}`);
			
			getTable.row.add({
				"0":`<input type="checkbox" class="check_delete" id="chk_${count}" value="${count}">`,
				"material_no":"",
				"descolumn":`<select class="form-control form-control-select2 dt_${count} testSelect" data-live-search="true" id="selectDetailMatrial" data-count="${count}">
								<option value="">Select Item</option>
								${showMatrialDetailData(elementSelect)}
							</select>`,
				"qty":`<input type="text" class="form-control qty" id="editqty_${count}" value="" style="width:100%" autocomplete="off">`,
				"uom":"",
				"OnHand":"",
				"MinStock":"",
				"OpenQty":""
				}).draw();
				count++;

			tbody = $("#tblWhole tbody");
			tbody.on('change','.testSelect', function(){
				tr = $(this).closest('tr');
				no = tr[0].rowIndex;
				id = $('.dt_'+no+' option:selected').attr('rel');
				setValueTable(id,no);
			});
		}

		function setValueTable(id,no){
			table = document.getElementById("tblWhole").rows[no].cells;
			$.post(
				"<?php echo site_url('transaksi1/wopos/getdataDetailMaterialSelect')?>",{ MATNR:id },(res)=>{
					matSelect = JSON.parse(res);
					matSelect.map((val)=>{
						table[1].innerHTML = val.MATNR;
						table[4].innerHTML = val.UNIT;
						table[5].innerHTML = val.OnHand;
					})
				}
			)
		}

		function showMatrialDetailData(select){
			$.ajax({
				url: "<?php echo site_url('transaksi1/wopos/addItemRow');?>",
				type: "POST",
				success:function(res) {
					optData = JSON.parse(res);
					optData.forEach((val)=>{						
						$("<option />", {value:val.MAKTX, text:val.MAKTX, rel:val.MATNR}).appendTo(select);
					})
				}
			});			
		}

		function addDatadb(id_approve = ''){
			if($('#postDate').val() ==''){
				alert('Posting date harus di isi');
				return false;
			}
			
			woNumber 	= $('#wonumber').val();
			woDesc 		= $('#wodesc').val();
			qtyProd 	= $('#qtyProduksi').val();
			uomProd 	= $('#uomProduksi').val();
			postDate 	= $('#postDate').val();
			approve		= id_approve;

			arr = woDesc.split(' - ');

			table = $('#tblWhole > tbody');
			let matrialNo =[];
			let matrialDesc =[];
			let qty =[];
			let uom =[];
			let onHand =[];
			let minStock =[];
			let outStandTot =[];
			let validasi = true;
			let validasiQty = true;
			let dataValidasi = [];
			table.find('tr').each(function(i, el){
				let td = $(this).find('td');
				if(td.eq(3).find('input').val() == ''){
					validasi = false;
				}
				if(parseInt(td.eq(3).find('input').val(),10) > parseFloat(td.eq(5).text())){
					dataValidasi.push(td.eq(1).text());
					validasiQty = false;
				}
				matrialNo.push(td.eq(1).text()); 
				matrialDesc.push(td.eq(2).find('select option:selected').text());
				qty.push(td.eq(3).find('input').val());
				uom.push(td.eq(4).text());	
				onHand.push(td.eq(5).text());	
				minStock.push(td.eq(6).text());	
				outStandTot.push(td.eq(7).text());
			});
			if(!validasi){
				alert('Quatity Tidak boleh Kosong, Harap isi Quantity');
				return false;
			}
			if(!validasiQty){
				alert('Material Number '+dataValidasi.join()+' Quatity Tidak boleh Lebih Besar dari OnHand');
				return false;
			}
			$('#load').show();
			$("#after-submit").addClass('after-submit');

			setTimeout(() => {
				$.post("<?php echo site_url('transaksi1/wopos/addData')?>",{
					woDesc:arr[1], woNumber:woNumber, qtyProd:qtyProd, uomProd:uomProd, postDate:postDate, approve:approve, matrialNo:matrialNo, matrialDesc:matrialDesc, qty:qty, uom:uom, onHand:onHand, minStock:minStock, outStandTot:outStandTot
				}, function(){
					$('#load').hide();
				})
				.done(function() {
					location.replace("<?php echo site_url('transaksi1/wopos/')?>");
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