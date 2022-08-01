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
				<?php endif; ?>
				<?php if ($this->session->flashdata('failed')): ?>
					<div class="alert alert-danger" role="alert">
						<?php echo $this->session->flashdata('failed'); ?>
					</div>
				<?php endif; ?>
                    <div class="card">
                        <div class="card-body">
                            <form action="<?php echo site_url('master/manajemen/store')?>" method="POST">
                                <div class="row">
                                    <div class="col-md-12">
                                        <fieldset>
                                            <legend class="font-weight-semibold"><i class="icon-reading mr-2"></i>Tambah Data Pengguna</legend>
                                            <div class="form-group row">
												<label class="col-lg-3 col-form-label" for="data_nik">NIK</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" name="data_nik" autocomplete="off">
													
												</div>
											</div>

                                            <div class="form-group row">
												<label class="col-lg-3 col-form-label" for="admin_username">Username</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" name="admin_username" autocomplete="off" required>
												</div>
											</div>

                                            <div class="form-group row">
												<label class="col-lg-3 col-form-label" for="admin_realname">Nama Lengkap</label>
												<div class="col-lg-9">
													<input type="text" class="form-control" name="admin_realname" autocomplete="off" required>
												</div>
											</div>

                                            <div class="form-group row">
												<label class="col-lg-3 col-form-label" for="admin_email">Email</label>
												<div class="col-lg-9">
													<input type="email" class="form-control" name="admin_email" autocomplete="off" required>
												</div>
											</div>

                                            <div class="form-group row">
												<label class="col-lg-3 col-form-label" for="admin_password">Password</label>
												<div class="col-lg-9">
													<input type="password" class="form-control" name="admin_password" autocomplete="off" required>
												</div>
											</div>

                                            <div class="form-group row">
												<label class="col-lg-3 col-form-label" for="admin_password_confirm">Konfirmasi Password</label>
												<div class="col-lg-9">
													<input type="password" class="form-control <?php echo validation_errors('admin_password_confirm') ? 'is-invalid':'' ?>" name="admin_password_confirm" autocomplete="off" required>
													<div class="invalid-feedback">
														<?php echo validation_errors('admin_password_confirm') ?>
													</div>
												</div>
											</div>

                                            <div class="form-group row">
												<label class="col-lg-3 col-form-label" for="plants">Pilih Plant</label>
												<div class="col-lg-9">
                                                    <select class="form-control multiselect-select-all-filtering" multiple="multiple" data-fouc name="plants[]">
														<?php foreach($outlets as $plant=>$value) :?>
															<option value="<?=$value['OUTLET']?>"><?=$value['OUTLET'] .'-'.$value['OUTLET_NAME1']?></option>
														<?php endforeach;?>
                                                        
                                                    </select>
												</div>
											</div>

                                            <div class="form-group row">
												<label class="col-lg-3 col-form-label"for="perm_group_name">Group Hak Akses</label>
												<div class="col-lg-9">
                                                    <select class="form-control multiselect-select-all-filtering" multiple="multiple" data-fouc name="perm_group_id[]">
													<?php foreach($permGroups as $value) :?>
															<option value="<?=$value['group_id']?>"><?=$value['group_name']?></option>
														<?php endforeach;?>
                                                    </select>
												</div>
											</div>

											<div class="form-group row">
												<label class="col-lg-3 col-form-label">Departemen</label>
												<div class="col-lg-9">
                                                    <select class="form-control form-control-select2" name="dept_manager" id="deptManager" required>
														<option value="">Select Department</option>
														<?php foreach($dept as $value){?>
															<option value="<?=$value['dept_head_id']?>" desc="<?=$value['dept']?>"><?=$value['dept']?></option>
														<?php } ?>
                                                    </select>
												</div>
											</div>

                                            <div class="text-right">
                                                <button type="submit" class="btn btn-primary">Save<i class="icon-paperplane ml-2"></i></button>
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