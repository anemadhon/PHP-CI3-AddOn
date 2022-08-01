<div class="navbar navbar-expand-md navbar-dark">
    <div class="navbar-brand" style="padding:0px;">
        <a href="<?php echo site_url('msi/dashboard'); ?>" class="d-inline-block">
            <h4 style="margin-top: .625rem;"><?php echo SITE_NAME ?></h4>
        </a>
    </div>
	
	<div class="collapse navbar-collapse text-right" id="navbar-mobile">
		<span class="ml-md-6 mr-md-auto"></span>
		<ul class="navbar-nav">
			<li class="nav-item dropdown dropdown-user">
				<a href="#" class="navbar-nav-link d-flex align-items-center dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
					<i class="icon-reading mr-2"></i><span><?php echo $this->session->userdata['ADMIN']['admin_realname']; ?></span>
				</a>

				<div class="dropdown-menu dropdown-menu-right">
					<a class="dropdown-item" onClick="updateConfirm()"><i class="icon-cog5"></i> Ubah Password</a>
          <a href="<?php echo site_url('plant'); ?>" class="dropdown-item"><i class="icon-git-compare"></i> Ganti Plant</a>
					<a href="<?php echo site_url('login/logout'); ?>" class="dropdown-item"><i class="icon-switch2"></i> Logout</a>
				</div>
			</li>
		</ul>
	</div>

    <div class="d-md-none">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
            <i class="icon-tree5"></i>
        </button>
        <button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
            <i class="icon-paragraph-justify3"></i>
        </button>
    </div>
</div>

<div class="modal fade" id="openMdlChangePW" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Ubah Password</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>

      <div class="modal-body">
        
	  	<div class="alert alert-danger" id="errMsgFailNav" style="display:none;"></div>

		<div class="alert alert-success" id="errMsgSuccessNav" style="display:none;"></div>

        <div class="form-group">
          <label for="exampleInputEmail1">Username</label>
          <input type="text" class="form-control" id="f_username_nav" value="<?php echo $this->session->userdata['ADMIN']['admin_username']; ?>" readonly>
        </div>

        <div class="form-group">
          <label for="exampleInputPassword1">Password</label>
          <input type="password" class="form-control" id="f_password_nav" placeholder="Password">
        </div>

        <div class="form-group">
          <label for="exampleInputPassword1">Konfirmasi Password</label>
          <input type="password" class="form-control" id="f_confpassword_nav" placeholder="Konfirmasi Password">
        </div>
        
      </div>

      <div class="modal-footer">
        <button class="btn btn-light" type="button" data-dismiss="modal">Cancel</button>
        <button id="btn-update-password" style="color:#fff" class="btn btn-primary">Ubah</button>
      </div>

    </div>
  </div>
</div>