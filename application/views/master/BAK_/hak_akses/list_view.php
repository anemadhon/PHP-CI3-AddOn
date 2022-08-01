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
                            <a href="<?php echo site_url('master/akses/add') ?>" class="btn btn-primary"> Add New </a>
                        </div>
                        <div class="card-body">
                            <table id="table-akses" class="table table-striped " style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="text-align: left">*</th>
                                        <th>Group Hak Akses</th>
                                        <th>Admin Terkait</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
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
                $('#table-akses').DataTable({
                    "ordering":false,  "paging": true, "searching":true,
                    "ajax": {
                        "url":"<?php echo site_url('master/akses/showAllData');?>",
                        "type":"POST"
                    },
                    "columns": [
                        {"data":"no"},
                        {"data":"grup_hak_akses"},
						{"data":"admin_terkait","className":"dt-center", render:function(data, type, row, meta){
								rr = (data=="true")?`<a href='<?php echo site_url('master/akses/check')?>' ><i class='icon-file-check' title="List Admin Terkait"></i></a>`:``;
								return rr;
                            }
                        },
                        {"data":"no","className":"dt-center", render:function(data, type, row, meta){
                                rr = `<a href='<?php echo site_url('master/akses/edit')?>' ><i class='icon-file-check2' title="Edit"></i></a>&nbsp;<a href='<?php echo site_url('master/akses/check')?>' ><i class='icon-file-text3' title="Check"></i></a>&nbsp;<a onClick="deleteConfirm('<?php echo site_url('master/akses/delete')?>')" href="#!"><i class='icon-cross2' title="Delete"></i></a>`;
                                return rr;
                            }
                        }
                    ]
                });
				
				deleteConfirm = (url)=>{
                    $('#btn-delete').attr('href', url);
	                $('#deleteModal').modal();
                }
            });
        
        </script>
	</body>
</html>