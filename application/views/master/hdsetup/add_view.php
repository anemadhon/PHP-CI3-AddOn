<!DOCTYPE html>
<html lang="en">
	<head>
		<?php  $this->load->view("_template/head.php")?>
        <style>
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
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <fieldset>
										<legend class="font-weight-semibold"><i class="icon-reading mr-2"></i>Tambah Data Departemen</legend>
										
										<div class="form-group row">
                                            <label class="col-lg-3 col-form-label" for="dept">Departemen</label>
                                            <div class="col-lg-9">
                                                <input type="text" name="dept" id="dept" class="form-control" required>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label" for="dept">Acc Dept Name</label>
                                            <div class="col-lg-9">
                                                <select class="form-control form-control-select2" id="accDept" name="acc_dept">
                                                    <option value="-">Select Departemen</option>
                                                    <?php foreach($divisi as $value){?>
                                                        <option value="<?=$value['PrcCode']?>" desc="<?=$value['PrcName']?>"><?=$value['PrcCode'].' - '.$value['PrcName']?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label" for="deptHead">Kepala Departemen</label>
                                            <div class="col-lg-9">
                                                <select class="form-control form-control-select2" name="dept_head" id="deptHead" required>
                                                    <option value="">Select Name</option>
                                                    <?php foreach($users as $value){?>
                                                        <option value="<?=$value['admin_id']?>" desc="<?=$value['admin_realname']?>"><?=$value['admin_username'].' - '.$value['admin_realname']?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="text-right">
                                            <button type="submit" class="btn btn-primary" id="save">Save<i class="icon-paperplane ml-2"></i></button>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                    </div>  
                    <div id="load" style="display:none"></div>                    
				</div>
				<?php  $this->load->view("_template/footer.php")?>
			</div>
		</div>
        <?php  $this->load->view("_template/js.php")?>
        <script>
            $(document).ready(function(){
                $('#save').click(function(){
                    let deptName = $('#dept').val();
                    let accDeptCode = $('#accDept option:selected').val();
                    let accDeptName = accDeptCode == '-' ? '-' : $('#accDept option:selected').attr('desc');
                    let deptHead = $('#deptHead option:selected').val();

                    let errorMesseges = [];

                    if(deptName == ''){
                        errorMesseges.push('Departemen Harus dipilih. \n');
                    }
                    if(deptHead == ''){
                        errorMesseges.push('Kepala Departemen harus dipilih. \n');
                    }
                    if (errorMesseges.length > 0) {
                        alert(errorMesseges.join(''));
                        return false;
                    }
                    $('#load').show();
                    setTimeout(() => {
                        $.post("<?php echo site_url('master/hdsetup/store')?>",{
                            dept:deptName,
                            accDeptCode:accDeptCode,
                            accDeptName:accDeptName,
                            deptHead:deptHead,
                        }, function(){
                            $('#load').hide();
                        })
                        .done(function() {
                            location.replace("<?php echo site_url('master/hdsetup/')?>");
                        })
                        .fail(function(xhr, status) {
                            alert(`Terjadi Error (${xhr.status} : ${xhr.statusText}), Silahkan Coba Lagi`);
                            location.reload(true);
                        });
                    }, 600);
                });
            });
        </script>
	</body>
</html>