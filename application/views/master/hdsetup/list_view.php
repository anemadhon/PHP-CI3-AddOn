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
                        <div class="card-header">
                            <legend class="font-weight-semibold"><i class="icon-list mr-2"></i>List of Department Heads</legend>
                            <a href="<?php echo site_url('master/hdsetup/add') ?>" class="btn btn-primary"> Add New</a>
                        </div>
                        <div class="card-body">
                            <table id="table-manajemen" class="table table-striped " style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="text-align: left">*</th>
                                        <th>Department</th>
                                        <th>Acc Dept Name</th>
                                        <th>Head of Department</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                <?php
                                    $i = 1;
                                    foreach($dept as $divisi):?>
                                    <tr>
                                        <td><?= $i++?></td>
                                        <td><?= $divisi['dept']?></td>
                                        <td><?= $divisi['dept_code'] == '-' ? '' : $divisi['dept_code'].' - '.$divisi['dept_name']?></td>
                                        <td><?= $divisi['dept_head_name']?></td>
                                        <td>
                                            <a href='<?php echo site_url('master/hdsetup/edit/'.$divisi['id_dept'])?>' ><i class='icon-file-plus2' title="Edit"></i></a>&nbsp;
                                            <a onClick="deleteConfirm('<?php echo site_url('master/hdsetup/delete/'.$divisi['id_dept'])?>')" href="#!"><i class='icon-cross2' title="Delete"></i></a>
                                        </td>

                                    </tr>
                                <?php endforeach;?>

                                </tbody>
                            </table>
                        </div>
                    </div>
				</div>
				<?php  $this->load->view("_template/footer.php")?>
			</div>
		</div>
        <?php  $this->load->view("_template/modal_delete.php")?>
        <?php  $this->load->view("_template/js.php")?>
        <script>
            $(document).ready(function(){
                $('#table-manajemen').DataTable({
                    "ordering":false, "paging": true, "searching":true
                });

                deleteConfirm = (url)=>{
                    $('#btn-delete').attr('href', url);
	                $('#deleteModal').modal();
                }
            });

        </script>
	</body>
</html>