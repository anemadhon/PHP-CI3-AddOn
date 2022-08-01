<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Limitless - Responsive Web Application Kit by Eugene Kopyov</title>

	<!-- Global stylesheets -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link href="../global_assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
	<link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<link href="assets/css/bootstrap_limitless.min.css" rel="stylesheet" type="text/css">
	<link href="assets/css/layout.min.css" rel="stylesheet" type="text/css">
	<link href="assets/css/components.min.css" rel="stylesheet" type="text/css">
	<link href="assets/css/colors.min.css" rel="stylesheet" type="text/css">
	<!-- /global stylesheets -->

	<!-- Core JS files -->
	<script src="../global_assets/js/main/jquery.min.js"></script>
	<script src="../global_assets/js/main/bootstrap.bundle.min.js"></script>
	<script src="../global_assets/js/plugins/loaders/blockui.min.js"></script>
	<!-- /core JS files -->

	<!-- Theme JS files -->
	<script src="../global_assets/js/plugins/visualization/d3/d3.min.js"></script>
	<script src="../global_assets/js/plugins/visualization/d3/d3_tooltip.js"></script>
	<script src="../global_assets/js/plugins/forms/styling/switchery.min.js"></script>
	<script src="../global_assets/js/plugins/forms/selects/bootstrap_multiselect.js"></script>
	<script src="../global_assets/js/plugins/ui/moment/moment.min.js"></script>
	<script src="../global_assets/js/plugins/pickers/daterangepicker.js"></script>

	<script src="assets/js/app.js"></script>
	<script src="../global_assets/js/demo_pages/dashboard.js"></script>
	<script src="../global_assets/js/plugins/tables/datatables/datatables.min.js"></script>
	<script src="../global_assets/js/plugins/forms/selects/select2.min.js"></script>

	<script src="../global_assets/js/demo_pages/datatables_basic.js"></script>
	<!-- /theme JS files -->

</head>

<body>

	<!-- Main navbar -->
	<div class="navbar navbar-expand-md navbar-dark">
		<div class="navbar-brand">
			<a href="index.html" class="d-inline-block">
				<h2>YBC SAP Portal</h2>
			</a>
		</div>

		<div class="d-md-none">
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
				<i class="icon-tree5"></i>
			</button>
			<button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
				<i class="icon-paragraph-justify3"></i>
			</button>
		</div>

		<div class="collapse navbar-collapse" id="navbar-mobile">
			<span class="navbar-text ml-md-3 mr-md-auto">	
			</span>

			<ul class="navbar-nav">
				<li class="nav-item dropdown dropdown-user">
					<a href="#" class="navbar-nav-link dropdown-toggle" data-toggle="dropdown">
						<img src="../global_assets/images/placeholders/placeholder.jpg" class="rounded-circle" alt="">
						<span>Alam Sutra Manager (SX Alam)</span>
					</a>

					<div class="dropdown-menu dropdown-menu-right">
						<a href="#" class="dropdown-item"><i class="icon-user-plus"></i> My profile</a>
						<a href="#" class="dropdown-item"><i class="icon-coins"></i> My balance</a>
						<a href="#" class="dropdown-item"><i class="icon-comment-discussion"></i> Messages <span class="badge badge-pill bg-blue ml-auto">58</span></a>
						<div class="dropdown-divider"></div>
						<a href="#" class="dropdown-item"><i class="icon-cog5"></i> Account settings</a>
						<a href="#" class="dropdown-item"><i class="icon-switch2"></i> Logout</a>
					</div>
				</li>
			</ul>
		</div>
	</div>
	<!-- /main navbar -->


	<!-- Page content -->
	<div class="page-content">

		<!-- Main sidebar -->
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

				<!-- User menu -->
				<div class="sidebar-user">
					<div class="card-body">
						<div class="media">
							<div class="mr-3">
								<a href="#"><img src="../global_assets/images/placeholders/placeholder.jpg" width="38" height="38" class="rounded-circle" alt=""></a>
							</div>

							<div class="media-body">
								<div class="media-title font-weight-semibold">Alam Sutra Manager (SX Alam)</div>
							</div>

							<div class="ml-3 align-self-center">
								<a href="#" class="text-white"><i class="icon-cog3"></i></a>
							</div>
						</div>
					</div>
				</div>
				<!-- /user menu -->


				<!-- Main navigation -->
				<div class="card card-sidebar-mobile">
					<ul class="nav nav-sidebar" data-nav-type="accordion">

						<!-- Main -->
						<li class="nav-item-header"><div class="text-uppercase font-size-xs line-height-xs">Main</div> <i class="icon-menu" title="Main"></i></li>
						<li class="nav-item">
							<a href="index.html" class="nav-link active">
								<i class="icon-home4"></i>
								<span>
									Open
								</span>
							</a>
						</li>
						<li class="nav-item nav-item-submenu">
							<a href="#" class="nav-link"><i class="icon-copy"></i> <span>Transaksi 1</span></a>

							<ul class="nav nav-group-sub" data-submenu-title="Layouts">
								<li class="nav-item nav-item-submenu">
									<a href="#" class="nav-link">Input Data Transaksi 1</a>
									<ul class="nav nav-group-sub">
										<li class="nav-item"><a href="#" class="nav-link active">EKSTERNAL</a></li>
										<li class="nav-item"><a href="http://localhost/msi-201/full/form_inputs.html" class="nav-link">In PO from Vendor</a></li>
										<li class="nav-item"><a href="#" class="nav-link">Good Receipt from Central Kitchen Sentul</a></li>
										<li class="nav-item"><a href="#" class="nav-link">Transfer Out Inter Outlet</a></li>
										<li class="nav-item"><a href="#" class="nav-link">Transfer In Inter Outlet</a></li>
										<li class="nav-item"><a href="#" class="nav-link">Purchase Request (PR)</a></li>
										<li class="nav-item"><a href="../seed/sidebar_right.html" class="nav-link">Retur Out</a></li>
										<li class="nav-item"><a href="../seed/sidebar_right.html" class="nav-link">Retur In</a></li>
										<li class="nav-item"><a href="../seed/sidebar_right.html" class="nav-link">Good Issue</a></li>
										<li class="nav-item"><a href="../seed/sidebar_right.html" class="nav-link">Good Receipt Non PO</a></li>
										<li class="nav-item"><a href="#" class="nav-link active">PRODUKSI</a></li>
										<li class="nav-item"><a href="#" class="nav-link">Work Order</a></li>
										<li class="nav-item"><a href="#" class="nav-link active">STOCK OUTLET</a></li>
										<li class="nav-item"><a href="#" class="nav-link">Stock Opname</a></li>
									</ul>
								</li>
								<li class="nav-item nav-item-submenu">
									<a href="#" class="nav-link">List Data Transaksi 1</a>
									<ul class="nav nav-group-sub">
										<li class="nav-item"><a href="../seed/sidebar_secondary.html" class="nav-link">Secondary sidebar</a></li>
										<li class="nav-item"><a href="../seed/sidebar_right.html" class="nav-link">Right sidebar</a></li>
									</ul>
								</li>
							</ul>
						</li>
						<li class="nav-item nav-item-submenu">
							<a href="#" class="nav-link"><i class="icon-color-sampler"></i> <span>Transaksi 2</span></a>

							<ul class="nav nav-group-sub" data-submenu-title="Layouts">
								<li class="nav-item nav-item-submenu">
									<a href="#" class="nav-link">Input Data Transaksi 2</a>
									<ul class="nav nav-group-sub">
										<li class="nav-item"><a href="../seed/sidebar_secondary.html" class="nav-link">Secondary sidebar</a></li>
										<li class="nav-item"><a href="../seed/sidebar_right.html" class="nav-link">Right sidebar</a></li>
									</ul>
								</li>
								<li class="nav-item nav-item-submenu">
									<a href="#" class="nav-link">List Data Transaksi 2</a>
									<ul class="nav nav-group-sub">
										<li class="nav-item"><a href="../seed/sidebar_secondary.html" class="nav-link">Secondary sidebar</a></li>
										<li class="nav-item"><a href="../seed/sidebar_right.html" class="nav-link">Right sidebar</a></li>
									</ul>
								</li>
							</ul>
						</li>
						<li class="nav-item nav-item-submenu">
							<a href="#" class="nav-link"><i class="icon-stack"></i> <span>Master Data</span></a>

							<ul class="nav nav-group-sub" data-submenu-title="Starter kit">
								<li class="nav-item"><a href="../seed/layout_nav_horizontal.html" class="nav-link">Horizontal navigation</a></li>
								<li class="nav-item"><a href="../seed/sidebar_none.html" class="nav-link">No sidebar</a></li>
								<li class="nav-item"><a href="../seed/sidebar_main.html" class="nav-link">1 sidebar</a></li>
								<li class="nav-item nav-item-submenu">
									<a href="#" class="nav-link">3 sidebars</a>
									<ul class="nav nav-group-sub">
										<li class="nav-item"><a href="../seed/sidebar_right_hidden.html" class="nav-link">Right sidebar hidden</a></li>
										<li class="nav-item"><a href="../seed/sidebar_right_visible.html" class="nav-link">Right sidebar visible</a></li>
									</ul>
								</li>
								<li class="nav-item nav-item-submenu">
									<a href="#" class="nav-link">Content sidebars</a>
									<ul class="nav nav-group-sub">
										<li class="nav-item"><a href="../seed/sidebar_content_left.html" class="nav-link">Left sidebar</a></li>
										<li class="nav-item"><a href="../seed/sidebar_content_right.html" class="nav-link">Right sidebar</a></li>
									</ul>
								</li>
								<li class="nav-item"><a href="../seed/layout_boxed.html" class="nav-link">Boxed layout</a></li>
								<li class="nav-item-divider"></li>
								<li class="nav-item"><a href="../seed/navbar_fixed_main.html" class="nav-link">Fixed main navbar</a></li>
								<li class="nav-item"><a href="../seed/navbar_fixed_secondary.html" class="nav-link">Fixed secondary navbar</a></li>
								<li class="nav-item"><a href="../seed/navbar_fixed_both.html" class="nav-link">Both navbars fixed</a></li>
								<li class="nav-item"><a href="../seed/layout_fixed.html" class="nav-link">Fixed layout</a></li>
							</ul>
						</li>
						<li class="nav-item"><a href="../../../RTL/default/full/index.html" class="nav-link"><i class="icon-width"></i> <span>Report Summary</span></a></li>
						<!-- /main -->
						
					</ul>
				</div>
				<!-- /main navigation -->

			</div>
			<!-- /sidebar content -->
			
		</div>
		<!-- /main sidebar -->


		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header page-header-light">
				<div class="page-header-content header-elements-md-inline">
					<div class="page-title d-flex">
						<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Home</span> - Dashboard</h4>
						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>

				</div>

				<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="index.html" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
							<span class="breadcrumb-item active">Dashboard</span>
						</div>

						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>
				</div>
			</div>
			<!-- /page header -->


			<!-- Content area -->
			<div class="content">

				<!-- Form inputs -->
				<div class="card">
					<div class="card-header header-elements-inline">
						<h5 class="card-title">Good Receipt Non PO</h5>
						<div class="header-elements">
							<div class="list-icons">
		                		<a class="list-icons-item" data-action="collapse"></a>
		                		<a class="list-icons-item" data-action="reload"></a>
		                		<a class="list-icons-item" data-action="remove"></a>
		                	</div>
	                	</div>
					</div>

					<div class="card-body">
						<form action="#">
							<fieldset class="mb-3">

								<div class="form-group row">
									<label class="col-form-label col-lg-2">Good Receipt No. </label>
									<div class="col-lg-10">
										<input type="text" class="form-control" readonly value="(Auto Number after Posting to SAP)">
									</div>
								</div>

								<div class="form-group row">
									<label class="col-form-label col-lg-2">Plant </label>
									<div class="col-lg-10">
										<input type="text" class="form-control" readonly value="WMSIPIST - Pondok Indah">
									</div>
								</div>
								
								<div class="form-group row">
									<label class="col-form-label col-lg-2">Storage Location </label>
									<div class="col-lg-10">
										<input type="text" class="form-control" readonly value="WMSIPIST - NSI Pondok Indah">
									</div>
								</div>

								<div class="form-group row">
									<label class="col-form-label col-lg-2">Cost Center </label>
									<div class="col-lg-10">
										<input type="text" class="form-control" readonly value="MSI0106 - MSI OPERASIONAL">
									</div>
								</div>
								
								<div class="form-group row">
									<label class="col-form-label col-lg-2">Status </label>
									<div class="col-lg-10">
										<input type="text" class="form-control" readonly value="Not Approved">
									</div>
								</div>
								
								<div class="form-group row">
									<label class="col-form-label col-lg-2">Material Group </label>
									<div class="col-lg-10">
										<input type="text" class="form-control" readonly value="All">
									</div>
								</div>
								
								<div class="form-group row">
									<label class="col-form-label col-lg-2">Posting Date </label>
									<div class="col-lg-10">
										<input class="form-control" type="date" name="date">
									</div>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
				<!-- /form inputs -->
				
				<!-- Basic datatable -->
				<div class="card">

					<table class="table datatable-basic">
						<thead>
							<tr>
								<th>Material No</th>
								<th>Material Desc</th>
								<th>Quantity</th>
								<th>Uom</th>
								<th>Text</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>FFR0005</td>
								<td>Kwi</td>
								<td>100</td>
								<td><input type="text" class="form-control" value="5.00"></td>
								<td>5060.00</td>
								<td><input type="text" class="form-control"></td>
							</tr>
							<tr>
								<td>
									<select class="form-control">
										<option value="opt1">FFR0002 - Anggur Merah (gr)</option>
										<option value="opt1">FFR0003 - Apel Malang (gr)</option>
									</select></td>
								<td>Kwi</td>
								<td>100</td>
								<td><input type="text" class="form-control"></td>
								<td>5060.00</td>
								<td><input type="text" class="form-control"></td>
							</tr>
						</tbody>
					</table>
					
					<div class="text-center" style="margin-bottom:10px;">
						<button type="submit" class="btn btn-success">Save <i class="icon-paperplane ml-2"></i></button>
						<button type="submit" class="btn btn-primary">Approve <i class="icon-pencil5 ml-2"></i></button>
					</div>
				</div>
				<!-- /basic datatable -->

			</div>
			<!-- /content area -->


			<!-- Footer -->
			<div class="navbar navbar-expand-lg navbar-light">
				<div class="text-center d-lg-none w-100">
					<button type="button" class="navbar-toggler dropdown-toggle" data-toggle="collapse" data-target="#navbar-footer">
						<i class="icon-unfold mr-2"></i>
						Footer
					</button>
				</div>

				<div class="navbar-collapse collapse" id="navbar-footer">
					<span class="navbar-text">
						&copy; 2015 - 2018. <a href="#">Limitless Web App Kit</a> by <a href="http://themeforest.net/user/Kopyov" target="_blank">Eugene Kopyov</a>
					</span>

					<ul class="navbar-nav ml-lg-auto">
						<li class="nav-item"><a href="https://kopyov.ticksy.com/" class="navbar-nav-link" target="_blank"><i class="icon-lifebuoy mr-2"></i> Support</a></li>
						<li class="nav-item"><a href="http://demo.interface.club/limitless/docs/" class="navbar-nav-link" target="_blank"><i class="icon-file-text2 mr-2"></i> Docs</a></li>
						<li class="nav-item"><a href="https://themeforest.net/item/limitless-responsive-web-application-kit/13080328?ref=kopyov" class="navbar-nav-link font-weight-semibold"><span class="text-pink-400"><i class="icon-cart2 mr-2"></i> Purchase</span></a></li>
					</ul>
				</div>
			</div>
			<!-- /footer -->

		</div>
		<!-- /main content -->

	</div>
	<!-- /page content -->

</body>
</html>
