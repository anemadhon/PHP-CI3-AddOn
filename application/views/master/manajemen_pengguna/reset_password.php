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
				<?php if ($this->session->flashdata('success')): ?>
					<div class="alert alert-success" role="alert">
						<?php echo $this->session->flashdata('success'); ?>
					</div>
					<?php elseif($this->session->flashdata('failed')): ?>
						<div class="alert alert-danger" role="alert">
							<?php echo $this->session->flashdata('failed'); ?>
						</div>
				<?php endif; ?>

					
				
                    <div class="card">
                        <div class="card-body">
                            <form action="<?php base_url('master/manajemen/resetpassword')?>" method="POST">
							<input type="hidden" name="admin_id" value="<?=$admin->admin_id?>">
                                <div class="row">
                                    <div class="col-md-12">
                                        <fieldset>
                                            <legend class="font-weight-semibold"><i class="icon-reading mr-2"></i>Ubah Password Pengguna</legend>

                                            <div class="form-group row">
												<label class="col-lg-3 col-form-label">Username:</label>
												<div class="col-lg-9">
													<input type="text" class="form-control"  name="admin_username" autocomplete="off" required value="<?=$admin->admin_username?>" readOnly>
												</div>
											</div>

                                            <div class="form-group row">
												<label class="col-lg-3 col-form-label">Password Baru:</label>
												<div class="col-lg-9">
													<input type="password" class="form-control <?php echo validation_errors() ? 'is-invalid':'' ?>" name="admin_pw_baru" autocomplete="off" required value="">
													<div class="invalid-feedback">
														<?php echo validation_errors() ?>
													</div>
												</div>
											</div>

                                            <div class="form-group row">
												<label class="col-lg-3 col-form-label">Konfirmasi Password Baru:</label>
												<div class="col-lg-9">
													<input type="password" class="form-control <?php echo validation_errors() ? 'is-invalid':'' ?>" name="admin_pw_baru_konfirmasi" autocomplete="off" required value="">
													<div class="invalid-feedback">
														<?php echo validation_errors() ?>
													</div>
												</div>
											</div>

                                            <div class="text-right">
                                                <button type="submit" class="btn btn-primary">Ubah<i class="icon-paperplane ml-2"></i></button>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>                    
				</div>
				<?php  $this->load->view("_template/footer.php")?>
			</div>
		</div>
        <?php  $this->load->view("_template/js.php")?>
	</body>
</html>