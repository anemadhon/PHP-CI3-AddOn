<!DOCTYPE html>
<html lang="en">
	<head>
		<?php  $this->load->view("_template/head.php")?>
	</head>
	<body>
		<div class="page-content">
			<div class="content-wrapper">
				<div class="content">
					
					<!-- Vertical form options -->
					<div class="row">

						<div class="col-md-6 offset-md-4" style="margin-top:100px;">

							<div class="alert alert-danger errMsg" style="width:550px; display:none"></div>

							<div class="alert alert-success errMsgSuccess" style="width:550px; display:none"></div>
							<!-- Basic layout-->
							<div class="card" style="width:550px;">

								<div class="card-body" id="signinPage">
									<legend class="font-weight-semibold"><i class="icon-reading mr-2"></i> Sign in ke YBC SAP Portal</legend>
					
										<div class="form-group">
											<label>Name:</label>
											<input type="text" class="form-control" name="f_name" id="f_name">
										</div>

										<div class="form-group">
											<label>Password:</label>
											<input type="password" class="form-control" name="f_password" id="f_password">
										</div>

										<div class="text-right">
											<button type="button" class="btn btn-primary" id="BtnLogin">Masuk <i class="icon-user ml-2"></i></button>
										</div>
										<br>
										
								</div>
							</div>
							<!-- /basic layout -->
						</div>
					</div>
				</div>
			</div>
		</div>
		<script>

			var input_name = document.getElementById("f_name");
			var input_password = document.getElementById("f_password");

			function ProcessLogin(){
				$(".errMsg").hide();
				$('#BtnLogin').prop('disabled',true);
				$("#BtnLogin").html("loading ..");

				if($("#f_name").val() == "" || $("#f_password").val() == ""){
					
					alert("Username atau Password tidak boleh kosong");
					$('#BtnLogin').prop('disabled',false);
					$('#BtnLogin').html('Masuk');
					
					return;
				}
				let data = {
					username : $("#f_name").val(),
					password : $("#f_password").val()
				}	

				$.post("<?php echo site_url('login/userLogin'); ?>",{
						data: data
					},
					(res)=>{
						
						if(res){
							
							let r = JSON.parse(res);
							
							if(r.success){
								
								$(".errMsgSuccess").html(r.message);
								$(".errMsgSuccess").show();
								$('#BtnLogin').prop('disabled',false);
								$('#BtnLogin').html('Masuk');

								setTimeout(function(){ 
									window.location.href = '<?php echo site_url('msi/dashboard'); ?>'; 
								}, 750);

							} else {
								$(".errMsg").html(r.message);
								$(".errMsg").show();
								$('#BtnLogin').prop('disabled',false);
								$('#BtnLogin').html('Masuk');
							}

							
						}
						

					}
				)
			}

			input_name.addEventListener("keyup", function(event) {
				if (event.keyCode === 13) {
					event.preventDefault();
					document.getElementById("BtnLogin").click();
				}
			});

			input_password.addEventListener("keyup", function(event) {
				if (event.keyCode === 13) {
					event.preventDefault();
					document.getElementById("BtnLogin").click();
				}
			});

            $(document).ready(function(){

				$(".errMsg").hide();

				$("#BtnLogin").click(function(){ 					
					ProcessLogin();
				});
            });
        
        </script>
	</body>
</html>
