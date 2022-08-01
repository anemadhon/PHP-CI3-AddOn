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
                        <div class="card-header">
                            <legend class="font-weight-semibold"><i class="icon-list mr-2"></i>List Manajemen Pengguna</legend>
                            <a href="<?php echo site_url('master/manajemen/add') ?>" class="btn btn-primary"> Add New</a>
                        </div>
                        <div class="card-body">
                            <table id="table-manajemen" class="table table-striped " style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="text-align: left">*</th>
                                        <th>Username</th>
                                        <th>Nama Lengkap</th>
                                        <th>Group Hak Akses</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>

                                <?php
                                    $i = 1;
                                    foreach($admins as $value):?>
                                    <tr>
                                        <td><?= $i++?></td>
                                        <td><?= $value['admin_username']?></td>
                                        <td><?= $value['admin_realname']?></td>
                                        <?php
                                            $perm_groups = $this->manajemen_model->showPermGroup($value['admin_id']);
                                        ?>
                                        <td>
                                        <?php foreach($perm_groups as $perm_group): ?>
                                        <?=$perm_group['group_name'];?><br />
                                        <?php endforeach; ?>
                                        </td>
                                        <td>
                                            <a href='<?php echo site_url('master/manajemen/edit/'.$value['admin_id'])?>' ><i class='icon-file-plus2' title="Edit"></i></a>&nbsp;
                                            <a href='<?php echo site_url('master/manajemen/resetpassword/'.$value['admin_id'])?>' ><i class='icon-cog5' title="Ubah Password"></i></a>&nbsp;
                                            <a onClick="deleteConfirm('<?php echo site_url('master/manajemen/delete/'.$value['admin_id'])?>')" href="#!"><i class='icon-cross2' title="Delete"></i></a>
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
                    "ordering":false,  "paging": true, "searching":true
                });

                deleteConfirm = (url)=>{
                    $('#btn-delete').attr('href', url);
	                $('#deleteModal').modal();
                }
            });

        </script>
	</body>
</html>