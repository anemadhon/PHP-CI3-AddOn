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
				<li class="nav-item-header"><div class="text-uppercase font-size-xs line-height-xs">Main</div> <i class="icon-menu" title="Main"></i></li>
				
				<li class="nav-item nav-item-submenu">
					<a href="#" class="nav-link"><i class="icon-copy"></i> <span>Transaksi 1</span></a>
					<ul class="nav nav-group-sub" data-submenu-title="Transaksi 1">
						<li class="nav-item nav-item-submenu"><a href="#" class="nav-link active">EKSTERNAL</a>
							<ul class="nav nav-group-sub">
								<li class="nav-item"><a href="<?php echo base_url('/transaksi1/pofromvendor');?>" class="nav-link">In PO from Vendor</a></li>
								<li class="nav-item">
									<a href="<?php echo base_url('/transaksi1/grfromkitchensentul');?>" class="nav-link">Good Receipt from Central Kitchen Sentul</a>
								</li>
								<li class="nav-item"><a href="<?php echo base_url('/transaksi1/transferoutinteroutlet');?>" class="nav-link">Transfer Out Inter Outlet</a></li>
                				<li class="nav-item"><a href="<?php echo base_url('/transaksi1/transferininteroutlet');?>" class="nav-link">Transfer In Inter Outlet</a></li>
								<li class="nav-item"><a href="<?php echo base_url('/transaksi1/purchase_request');?>" class="nav-link">Purchase Request (PR)</a></li>
								<li class="nav-item"><a href="<?php echo site_url('/transaksi1/returnout');?>" class="nav-link">Retur Out</a></li>
								<li class="nav-item"><a href="<?php echo site_url('/transaksi1/returnin');?>" class="nav-link">Retur In</a></li>
								<li class="nav-item"><a href="<?php echo site_url('/transaksi1/goodissue');?>" class="nav-link">Good Issue</a></li>
								<li class="nav-item"><a href="<?php echo site_url('/transaksi1/grnopo');?>" class="nav-link">Good Receipt Non PO</a></li>
							</ul>
						</li>
						<li class="nav-item nav-item-submenu"><a href="#" class="nav-link active">PRODUKSI</a>
							<ul class="nav nav-group-sub">
								<li class="nav-item"><a href="<?php echo site_url('/transaksi1/wo');?>" class="nav-link">Work Order</a></li>
								<li class="nav-item"><a href="<?php echo site_url('/transaksi1/disassembly');?>" class="nav-link">Disassembly</a></li>	
							</ul>
						</li>
						<li class="nav-item nav-item-submenu"><a href="#" class="nav-link active">STOCK OUTLET</a>
							<ul class="nav nav-group-sub">
								<li class="nav-item"><a href="<?php echo site_url('/transaksi1/stock');?>" class="nav-link">Stock Opname</a></li>
								<li class="nav-item"><a href="<?php echo site_url('/transaksi1/spoiled');?>" class="nav-link">Spoiled/Breakage/lost</a></li>
							</ul>
						</li>
					</ul>
				</li>

				<li class="nav-item nav-item-submenu">
					<a href="#" class="nav-link"><i class="icon-color-sampler"></i> <span>Transaksi 2</span></a>
					<ul class="nav nav-group-sub" data-submenu-title="Transaksi 2">
						<li class="nav-item"><a href="<?php echo site_url('/transaksi2/sr');?>" class="nav-link">Store Room Request(SR)</a></li>
						<li class="nav-item"><a href="<?php echo site_url('/transaksi2/whole');?>" class="nav-link">Transaksi Pemotongan Whole di Outlet</a></li>
					</ul>
				</li>

				<li class="nav-item nav-item-submenu">
					<a href="#" class="nav-link"><i class="icon-stack"></i> <span>Master Data</span></a>

					<ul class="nav nav-group-sub" data-submenu-title="Master Data">
						<li class="nav-item"><a href="<?php echo base_url('/master/manajemen');?>" class="nav-link">Manajeman Pengguna</a></li>
						<li class="nav-item"><a href="<?php echo base_url('/master/akses');?>" class="nav-link">Hak Akses</a></li>
						<li class="nav-item"><a href="<?php echo base_url('/master/integration');?>" class="nav-link">Integration Log</a></li>
						<li class="nav-item"><a href="<?php echo base_url('/master/bom');?>" class="nav-link">Master Bill Of Materials</a></li>
						
					</ul>
				</li>

				<li class="nav-item nav-item-submenu">
					<a href="#" class="nav-link"><i class="icon-width"></i> <span>Report Summary</span></a>
					<ul class="nav nav-group-sub" data-submenu-title="Report Summary">
						<li class="nav-item"><a href="<?php echo site_url('/report/inventory');?>" class="nav-link">Inventory Audit</a></li>
						<li class="nav-item"><a href="<?php echo site_url('/report/bincard');?>" class="nav-link">Bincard Report</a></li>
						<li class="nav-item"><a href="<?php echo site_url('/report/onhand');?>" class="nav-link">Onhand Report</a></li>
					</ul>
				</li>
				<!-- /main -->
			</ul>
		</div>
		<!-- /main navigation -->

	</div>
	<!-- /sidebar content -->
	
</div>