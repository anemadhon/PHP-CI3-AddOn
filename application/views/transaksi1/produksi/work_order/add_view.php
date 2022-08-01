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
														<input type="text" id="qtyProduksi" class="form-control"> <!--placeholder="(Suggest Qty : 1.0000)" -->
														<p class="txtQtyDefault"></p>
														<input type="hidden" id="woQtyDefault">
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

												<div class="text-right hide" id="after-submit">
													<button type="button" class="btn btn-primary" name="save" id="save" onclick="addDatadb()">Save <i class="icon-pencil5 ml-2"></i></button>
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
										<div class="col-md-12 mb-2 hide" id="btnAddListItem">
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
														<th>No</th>
														<th>Material No</th>
														<th></th>
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
			document.onkeydown = function(e) {
				if(e.keyCode == 123) {
					e.preventDefault();
					return false;
				}
				if(e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)) {
					e.preventDefault();
					return false;
				}
				if(e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0)) {
					e.preventDefault();
					return false;
				}
				if(e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)) {
					e.preventDefault();
					return false;
				}
				if(e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)) {
					e.preventDefault();
					return false;
				}
			} 
			// prevents right clicking

			document.addEventListener('contextmenu', e => e.preventDefault());

			$(document).ready(function(){
				var element = new Image;
				var devtoolsOpen = false;
				element.__defineGetter__("id", function() 
				{
					devtoolsOpen = true;
					
					window.location.replace(window.location.href);
					
					// This only executes when devtools is open.
				});
				setInterval(function() {
					devtoolsOpen = false;
					console.log(element);
					
				}, 1000);
				
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
					let tbody = $("#tblWhole tbody");
					tbody.on('click','.check_delete', function(){
						$(this).closest('tr').next().find('.check_delete').prop('checked', true)
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
					trEven = $(this).closest('tr').next();
					no = tr[0].rowIndex;
					noEven = trEven[0].rowIndex;
					const qty = $("option:selected", this).attr("matqty");
					const matrial_no = $("option:selected", this).val();
					const rel = $("option:selected", this).attr("rel");
					const onHand = $("option:selected", this).attr("onhand");
					const minStock = $("option:selected", this).attr("minstock");
					const uOm = $("option:selected", this).attr("uOm");
					const qtyHeader = $("#qtyProduksi").val();
					let qtyDefault = $("#woQtyDefault").val();
					table = document.getElementById("tblWhole").rows[no].cells;
					tableExpand = document.getElementById("tblWhole").rows[noEven].cells;
					let tdd = tableExpand[3].querySelectorAll('.expand-parent-code')
					table[2].innerHTML = matrial_no;
					tableExpand[3].innerHTML = '';
					for (let index = 0; index < tdd.length; index++) {
						tableExpand[3].innerHTML += '<p class="expand-parent-code hide">'+matrial_no+'</p>';
						
					}
					if ($("#wolocked").val() == 'N' && rel == 'Y') {
						table[5].innerHTML = `<input type="text" id="editqty_${no}" class="form-control" value="${qty}">`;
					} else {
						table[5].innerHTML = `<span>${qty}</span><input type="hidden" id="editqty_${no}" class="form-control" value="${qty*9*5*2}" readonly>`;
					}
					table[6].innerHTML = uOm;
					table[7].innerHTML = onHand;
				});
				tbody.on('change','.can-edit', function(){
					let removedRow = $(this).closest('tr').next().find('input.expand-input').length - ($(this).val() / $(this).closest('td').prev().find('select#descmat option:selected').attr('ratio'))
					
					$(this).closest('tr').next().find(`input.expand-input:lt(${removedRow})`).remove()
					$(this).closest('tr').next().find(`div.mt-2:lt(${removedRow})`).remove()
					$(this).closest('tr').next().find(`p.expand-parent-code:lt(${removedRow})`).remove()
				});
				tbody.on('change','.expand-select', function(){
					if ($(this).val() === 'N/A')
					{
						$(this).closest('td').next().find('input').eq($(this).parent().index()).val('0.0000')
					}
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
				
				$.post("<?php echo site_url('transaksi1/wo/wo_header_uom');?>",{material_no: woNumber},(data)=>{
					const value = JSON.parse(data);
					if (value.data) {
						if(value.data[0]['U_Locked'] == 'N'){
							$("#btnAddListItem").removeClass('hide');
						}
					}
					$("#wolocked").val(value.data ? value.data[0]['U_Locked'] : 'Y');
					$('.txtQtyDefault').html(`Suggest Qty : ${value.data[0]['Qauntity'].slice(0,-2)}`);
					$("#woQtyDefault").val(value.data[0]['Qauntity'].slice(0,-2));
					$("#uomProduksi").val(value.data[0]['InvntryUom']);
				});
			}
			
			$('#qtyProduksi').keypress(function(event){
				var keycode = (event.keyCode ? event.keyCode : event.which);
				if(keycode == '13'){
					event.preventDefault();
					const qty = $("#qtyProduksi").val();
					if(qty == '' || qty == 0){
						alert('Qty Produksi Harus Diisi atau tidak boleh 0')
						return false
					} else {
						$("#form2").removeClass('hide');
						$("#form3").removeClass('hide');
						$(this).attr('readonly', true);
					}
					
					let kode_paket = $("#wonumber").val();
					let qtyDefault = $("#woQtyDefault").val();

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
						"initComplete": function(settings, json) {
							$("#after-submit").removeClass('hide')

							$("#tblWhole > tbody").find("tr:odd").hide()

							$("#tblWhole > tbody").find("tr").each(function()
							{
								let td = $(this).find('td')

								td.eq(3).find('.expand-btn').each(function()
								{
									$(this).click(function()
									{
										$(this).parents('tr:even').next().toggle()
									})
								})
							})
						},
						"ajax": {
							"url":"<?php echo site_url('transaksi1/wo/showDetailInput');?>",
							"data":{  
								kode_paket:kode_paket,
								Qty:qty,
								qtyDefault: qtyDefault

							},
							"type":"POST"
						},
						"columns": [
							{"data":"no", "className":"dt-center", render:function(data, type, row, meta){
								rr=`<input type="checkbox"  value="${data}" class="check_delete" id="dt_${row['no']}" onclick="checkcheckbox();">`;
								return row['no'] ? rr : '';
							}},
							{"data":"no", "className":"dt-center"},
							{"data":"material_no", "className":"dt-center"},
							{"data":"expand", "className":"mr-0 pr-0"},
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
				count = getTable.rows().count() / 2 + 1;
				let elementSelect = document.getElementsByClassName(`dt_${count}`);
				
				getTable.row.add({
					"no":`<input type="checkbox" class="check_delete" id="chk_${count}" value="${count}">`,
					"no":count,
					"material_no":"",
					"expand":"",
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

				const hiddenRow = getTable.row.add({
					"no":`<input type="checkbox" class="check_delete" id="chk_${count}" value="${count}">`,
					"no":count,
					"material_no":"",
					"expand":"",
					"descolumn":"",
					"qty":"",
					"uom":"",
					"OnHand":"",
					"MinStock":"",
					"OpenQty":""
					}).draw().node();

					$(hiddenRow).css( 'display', 'none' );

					count++;

				tbody = $("#tblWhole tbody");
				tbody.on('change','.testSelect', function(){
					tr = $(this).closest('tr');
					no = tr[0].rowIndex;
					id = $('.dt_'+no+' option:selected').val();
					setValueTable(id,no);
				});
			}

			function setValueTable(id,no){
				table = document.getElementById("tblWhole").rows[no].cells;
				$.post(
					"<?php echo site_url('transaksi1/wo/getdataDetailMaterialSelect')?>",{ MATNR:id },(res)=>{
						matSelect = JSON.parse(res);
						matSelect.map((val)=>{
							table[2].innerHTML = val.MATNR;
							table[6].innerHTML = val.UNIT;
							table[7].innerHTML = val.OnHand.toFixed(4);
						})
					}
				)
			}

			function showMatrialDetailData(select){
				$.ajax({
					url: "<?php echo site_url('transaksi1/wo/addItemRow');?>",
					type: "POST",
					success:function(res) {
						optData = JSON.parse(res);
						optData.forEach((val)=>{						
							$("<option />", {value:val.MATNR, text:val.MAKTX, rel:'Y', matdesc:val.MAKTX}).appendTo(select);
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
				let validasiChange = true;
				let dataValidasi = [];
				let dataValidasiChange = [];
				table.find('tr:even').each(function(i, el){
					let td = $(this).find('td');
					if((td.eq(5).find('input').val()/2/5/9) === ''){
						validasi = false;
					}
					if(parseFloat(td.eq(5).find('input').val()/2/5/9,10) > parseFloat(td.eq(7).text())){
						dataValidasi.push(td.eq(2).text());
						validasiQty = false;
					}
					matrialNo.push(td.eq(2).text()); 
					matrialDesc.push(td.eq(4).find('select option:selected').text());
					if ($("#wolocked").val() == 'N' && td.eq(4).find('select option:selected').attr('rel') == 'Y') {
						qty.push(td.eq(5).find('input').val());
					} else {
						qty.push(td.eq(5).find('input').val()/2/5/9);
						if (parseFloat(td.eq(5).find('span').text()) != (td.eq(5).find('input').val()/2/5/9))
						{
							dataValidasiChange.push(td.eq(2).text());
							validasiChange = false;
						}
					}
					uom.push(td.eq(6).text());	
					onHand.push(td.eq(7).text());	
					minStock.push('');	
					outStandTot.push('');
				});
				let matrialNoParentExpand =[];
				let matrialNoParentExpandTemp =[];
				let matrialNoExpand =[];
				let matrialNoExpandTemp =[];
				let matrialDescExpand =[];
				let matrialDescExpandTemp =[];
				let qtyExpand =[];
				let qtyExpandTemp =[];
				table.find('tr:odd').each(function(i, el){
					let td = $(this).find('td');

					for (let idx = 0; idx < td.eq(3).find('p').length; idx++) {
						matrialNoParentExpandTemp.push(td.eq(3).find('p')[idx].innerHTML);
						matrialNoExpandTemp.push(td.eq(4).find('select option:selected')[idx].value);
						matrialDescExpandTemp.push(td.eq(4).find('select option:selected')[idx].innerHTML);
						qtyExpandTemp.push(td.eq(5).find('input')[idx].value);
					}

					matrialNoParentExpand.push(matrialNoParentExpandTemp);
					matrialNoExpand.push(matrialNoExpandTemp);
					matrialDescExpand.push(matrialDescExpandTemp);
					qtyExpand.push(qtyExpandTemp);

					matrialNoParentExpandTemp = []
					matrialNoExpandTemp = []
					matrialDescExpandTemp = []
					qtyExpandTemp = []
				});
				if(!validasi){
					alert('Quatity Tidak boleh Kosong, Harap isi Quantity');
					return false;
				}
				if(!validasiQty){
					alert('Material Number '+dataValidasi.join()+' Quatity Tidak boleh Lebih Besar dari OnHand');
					return false;
				}
				if(!validasiChange){
					alert('Tolong jangan ganti nilai dari data '+dataValidasiChange.join());
					return false;
				}
				$('#load').show();
				$("#after-submit").addClass('after-submit');
				
				setTimeout(() => {
					$.post("<?php echo site_url('transaksi1/wo/addData')?>",{
						woDesc:arr[1], woNumber:woNumber, qtyProd:qtyProd, uomProd:uomProd, postDate:postDate, approve:approve, matrialNo:matrialNo, matrialDesc:matrialDesc, qty:qty, uom:uom, onHand:onHand, minStock:minStock, outStandTot:outStandTot,
						parentCodeExpand:matrialNoParentExpand, matrialNoExpand:matrialNoExpand, matrialDescExpand:matrialDescExpand, qtyExpand:qtyExpand
					}, function(){
						$('#load').hide();
					})
					.done(function() {
						location.replace("<?php echo site_url('transaksi1/wo/')?>");
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