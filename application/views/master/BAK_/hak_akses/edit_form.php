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
					
					<div class="card">
						<div class="card-body">
							
							<fieldset class="mb-3">
								
								<legend class="text-uppercase font-size-sm font-weight-bold">
									Edit Group Hak Akses
								</legend>
								<?=form_open('master/akses/update');?>
								<?=form_hidden('group_id', $data['group_id']);?>
									<div class="form-group row">
										<label class="col-form-label col-lg-2">Hak Akses Group</label>
										<div class="col-lg-10">
											<input type="text" class="form-control" name="group_name" id="group_name" value="<?php echo $data['group_name']; ?>">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-form-label col-lg-2"></label>
										<div class="col-lg-10">
											
										<?php 
											
											if($perms !== FALSE):
												$i = 0;
												
												foreach ($perms->result_array() as $perm) {
													$j = $perm['cat_id'];
													
													if($j != $i) {
														if($i != 0) echo "<br />";
														echo "<strong>".$this->lang->line('perm_opt_category_'.$perm['category_name'])."</strong>\n<br />";
													}
														
													if(substr_count($data['group_perms'], $perm['category_code'].sprintf("%02s", $perm['perm_code']))) {
														$perm_content = 1;
													} else {
														$perm_content = 0;
													}

													echo form_checkbox('perm['.$perm['cat_id'].'_'.$perm['perm_order_id'].']', $perm['category_code'].sprintf("%02d", $perm['perm_code']), $perm_content, $this->lang->line('perm_opt_'.$perm['perm_name']), 'id=perm[]'). $this->lang->line('perm_opt_'.$perm['perm_name']) ."<br />";
												
													$i = $j;
												}

											endif;
											?>
											
										</div>
									</div>
									<div class="text-right">
										<button type="submit" class="btn btn-primary">Update<i class="icon-paperplane ml-2"></i></button>
									</div>
								<?=form_close();?>
							</fieldset>
						</div>
					</div>
				</div>
				<?php  $this->load->view("_template/footer.php")?>
			</div>
		</div>
        <?php  $this->load->view("_template/js.php")?>
	</body>
</html>