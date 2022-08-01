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
								<legend class="text-uppercase font-size-sm font-weight-bold">Edit Group Hak Akses</legend>
								
								<div class="form-group row">
									<label class="col-form-label col-lg-2">Hak Akses Group</label>
									<div class="col-lg-10">
										<input type="text" class="form-control" value="HQ - Manager">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-form-label col-lg-2"></label>
									<div class="col-lg-10">
										<select class="form-control multiselect" multiple="multiple" data-fouc>
											<option value="lab" selected>Lab Course</option>
											<option value="proseminar">Proseminar</option>
											<optgroup label="Mathematics">
												<option value="analysis" selected>Analysis</option>
												<option value="algebra">Linear Algebra</option>
												<option value="probability" selected>Probability Theory</option>
											</optgroup>
											<optgroup label="Computer Science">
												<option value="programming">Introduction to Programming</option>
												<option value="complexity" selected>Complexity Theory</option>
												<option value="software">Software Engineering</option>
											</optgroup>
										</select>
									</div>
								</div>
								<div class="text-right">
									<button type="submit" class="btn btn-primary">Update<i class="icon-paperplane ml-2"></i></button>
								</div>
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