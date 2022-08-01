<?php
	$menu = $this->l_newmenu->getMenu();
?>
<div class="sidebar sidebar-dark sidebar-main sidebar-expand-md">
	<!-- Sidebar mobile toggler -->
	<div class="sidebar-mobile-toggler text-center">
		<a href="#" class="sidebar-mobile-main-toggle">
			<i class="icon-arrow-left8"></i>
		</a>
		Navigation
		<a href="#" class="sidebar-mobile-expand">
			<i class="icon-screen-full"></i>
			<i class="icon-screen-normal"></i>
		</a>
	</div>
	<!-- /sidebar mobile toggler -->


	<!-- Sidebar content -->
	<div class="sidebar-content">

		<!-- Main navigation -->
		<div class="sidebar-user">
			<div class="card-body">
				<div class="media">

					<div class="media-body">
						<div class="media-title font-weight-semibold">
							<?php echo $this->session->userdata["ADMIN"]["plant_name"]; ?>
						</div>
						<div class="font-size-md opacity-50">
						<?php echo $this->session->userdata["ADMIN"]["plant_name"]; ?> / <?php echo $this->session->userdata["ADMIN"]["plant"]; ?> <a href="#" style="color:white;"><i class="icon-git-compare"></i></a>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="card card-sidebar-mobile">
			<ul class="nav nav-sidebar" data-nav-type="accordion" id="nav">

				<!-- Main -->
				<li class="nav-item-header">
					<div class="text-uppercase font-size-xs line-height-xs">Main</div>
					<i class="icon-menu" title="Main"></i>
				</li>
				
				<?=$this->l_newmenu->create_menu($menu);?>
				<!-- /main -->
			</ul>
		</div>
		<!-- /main navigation -->

	</div>
	<!-- /sidebar content -->
	
</div>