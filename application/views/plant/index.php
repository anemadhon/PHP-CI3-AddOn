<!DOCTYPE html>
<html lang="en">
	<head>
		<?php  $this->load->view("_template/head.php")?>
	</head>
	<body>
	<?php  $this->load->view("_template/nav.php")?>
		<div class="page-content">
			<?php  $this->load->view("_template/sidebar.php")?>
			<div class="content-wrapper">
				<div class="content">

					<div class="alert alert-danger errMsgPlant" style="display:none"></div>
					<div class="alert alert-success errMsgSuccessPlant" style="display:none"></div>

					<div class="card">

						<div class="card-body">
						
							<div class="row">

								<div class="col-md-12">
									
									<fieldset>

										<legend class="font-weight-semibold"><i class="icon-reading mr-2"></i>Ganti Outlet</legend>
										<div class="form-group row">
											<label class="col-lg-3 col-form-label">Outlet Aktif saat ini:</label>
											<div class="col-lg-9">
												<input type="text" class="form-control" value="<?php echo $plant['OUTLET_NAME1']; ?> / <?php echo $plant['OUTLET']; ?>" readonly>
											</div>
										</div>
										
										<div class="form-group row">
											<label class="col-lg-3 col-form-label">Pilihan Outlet:</label>
											<div class="col-lg-9">

												<select class="form-control form-control-select2" data-live-search="true" name="f_outlet" id="f_outlet">

													<?php 
													if(is_array($plant['MSI'])) {
														foreach($plant['MSI'] as $value):
															$x1 = explode("(",$value);
														?>
															<option value="<?php echo str_replace(")","", $x1[1]); ?>">
																<?php echo $value; ?>
															</option>
													<?php 
														endforeach;
													}
													?>
												</select>
											</div>
										</div>
										
										<div class="text-right">
											<button type="button" class="btn btn-primary" id="BtnSubmit">Submit<i class="icon-paperplane ml-2"></i></button>
										</div>

									</fieldset>
								</div>
							</div>
						</div>
					</div> 

				</div>
				<?php  $this->load->view("_template/footer.php")?>
			</div>
		</div>
        <?php  $this->load->view("_template/js.php")?>
		<script>
            $(document).ready(function(){
				
				$("#BtnSubmit").click(function(){ 
					
					$('#BtnSubmit').prop('disabled',true);
					$("#BtnSubmit").html("loading ..");

					let outlet = $('#f_outlet').val();

					if(outlet != "" || outlet != undefined) {

						$.post("<?php echo site_url('plant/change_plant'); ?>",{
								outlet: outlet.trim()
							},
							(res)=>{

								if(res){
									
									let r = JSON.parse(res);
									if(r.success) {

										$(".errMsgSuccessPlant").html(r.message);
										$(".errMsgSuccessPlant").show();

										setTimeout(function(){ 
											window.location.href = '<?php echo site_url('msi/dashboard'); ?>'; 
										}, 750);

									} else {
										
										$(".errMsgPlant").html(r.message);
										$(".errMsgPlant").show();
										
										$('#BtnSubmit').prop('disabled',false);
										$("#BtnSubmit").html("Submit <i class='icon-paperplane ml-2'></i>");
							
									}
									
								}

							}
						)

					} else {
						alert("Outlet tidak boleh kosong")
						setTimeout(function(){ 
							$('#BtnSubmit').prop('disabled',false);
							$("#BtnSubmit").html("Submit <i class='icon-paperplane ml-2'></i>");
						}, 750);
						return;
					}
					
				});
			});
			
        </script>
	</body>
</html>