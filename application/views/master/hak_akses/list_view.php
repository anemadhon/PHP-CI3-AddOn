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
                            <legend class="font-weight-semibold"><i class="icon-list mr-2"></i>List Manajemen Pengguna </legend>

                            <a href="<?php echo site_url('master/akses/add') ?>" class="btn btn-primary"> 
                                Add New 
                            </a>

                        </div>
                        <div class="card-body">
                            <table id="table-akses" class="table table-striped " style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="text-align: left">#</th>
                                        <th>Group Hak Akses</th>
                                        <th>Admin Terkait</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    if($data['perm_groups'] !== FALSE) :
                                        $i = 1;
                                        foreach ($data['perm_groups']->result_array() as $perm_group):
                                          
                                    ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        
                                        <td>
                                            <?php if( ($perm_group['group_id'] == 1) || ($perm_group['group_id'] == 2) ) : echo ''; 
                                            else : ?>
                                                <a href="#header[<?=$i;?>]" onclick="toggle_visibility('detail[<?=$i;?>]');">
                                                <i class='icon-arrow-down5'></i>
                                                </a>
                                               
                                            <?php endif; ?> 
                                                <?=$perm_group['group_name'];?>
                                        </td>
                                        <td>
                                        
                                            <?php 
                                            if($admins = $this->m_perm->perm_group_admins_select($perm_group['group_id'])) {
                                                                                                
                                                ?>
                                                <a href="#header[<?=$i;?>]" onclick="toggle_visibility('admins[<?=$i;?>]');">
                                                    <i class='icon-arrow-down5' title='<?=$this->lang->line('show_detail');?>'></i>
                                                </a>

                                                <span id="admins[<?=$i;?>]" style="display:none">

                                            <?php
                                                    $c=1;
                                                    foreach($admins->result_array() as $admin) {
                                                        if($c==1){
                                                            echo '<br>';
                                                        }
                                                        echo $admin['admin_username']. '<br>';
                                                        $c++;
                                                    }
                                            ?>
                                                </span>
                                            <?php

                                            } else { echo "&nbsp;"; }
                                            
                                            ?>
                                                
                                        
                                        
                                        </td>

                                        <td width="*">
                                            
                                            <?php 

                                                if( ($perm_group['group_id'] != 1) && ($perm_group['group_id'] != 2) ) {
                                                    ?>
                                                    <a href="<?php echo base_url(); ?>master/akses/edit/<?php echo $perm_group['group_id']; ?>">

                                                        <i class='icon-file-plus2' title='<?=$this->lang->line('view');?>'></i>
                                                        
                                                    </a>
                                                    <?php
                                                }

                                                if( ($perm_group['group_id'] != 1) && ($perm_group['group_id'] != 2) && (!$this->m_perm->check_group_id_exist($perm_group['group_id'])) ) {
                                            ?>

                                                    <a href="#header[<?=($i-1);?>]" onClick='confirm_perm_group_delete("<?=site_url('master/akses/delete/'.$perm_group['group_id']);?>", "<?=$perm_group['group_name'];?>")'>
                                                        <i class='icon-cross2' title='<?=$this->lang->line('view');?>'></i>
                                                    </a>
                                                
                                                <?php	
                                            }

                                            ?>
                                        </td>
                                    </tr>
                                    
                                        <?php
                                        if ( ($perm_group['group_id'] != 1) && ($perm_group['group_id'] != 2) ) :
                                        ?>
                                        <tr id="detail[<?=$i;?>]" style="display:none">
                                            <td class="table_content_1" width="10" align="right">&nbsp;</td>
                                            <td>

                                                <?=$this->auth->perm_group_detail_show($perm_group['group_id']);?>

                                            </td>
                                            <td></td>
                                            <td></td>
                                        </tr>	

                                        <?php 
                                        endif;

                                        $i++;
                                        endforeach;
                                    endif;
                                    ?>
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

            function toggle_visibility(id) {
                var e = document.getElementById(id);
                
                if(e.style.display == '')
                    e.style.display = 'none';
                else
                    e.style.display = '';
            }

            function confirm_perm_group_delete(url, group_name) {
                var m = confirm('<?=$this->lang->line('perm_group_delete_confirm');?> "' + group_name + '"?');
                if(m) {
                    location.href=url;
                }
            }

            $(document).ready(function(){
				
				deleteConfirm = (url)=>{
                    $('#btn-delete').attr('href', url);
	                $('#deleteModal').modal();
                }
            });
        
        </script>
	</body>
</html>