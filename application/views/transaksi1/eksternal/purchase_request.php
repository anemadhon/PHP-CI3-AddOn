
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
						<h5 class="card-title">Purchase Request (PR)</h5>
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
									<label class="col-form-label col-lg-2">Purchase Request (PR) Number </label>
									<div class="col-lg-10">
										<input type="text" class="form-control" readonly value="(Auto Number after Posting to SAP)">
									</div>
								</div>

								<div class="form-group row">
									<label class="col-form-label col-lg-2">Outlet From </label>
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
									<label class="col-form-label col-lg-2">Request Reason</label>
									<div class="col-lg-10">
										<select class="form-control">
			                                <option value="opt1">Pastry</option>
			                                <option value="opt2">Option 2</option>
			                            </select>
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
										<input type="text" class="form-control" readonly value="RM Food Vegetable">
									</div>
								</div>
								
								<div class="form-group row">
									<label class="col-form-label col-lg-2">Delivery Date </label>
									<div class="col-lg-10">
										<input class="form-control" type="date" name="date">
									</div>
								</div>
							</fieldset>

							<div class="text-right">
								<button type="submit" class="btn btn-success">Save <i class="icon-paperplane ml-2"></i></button>
								<button type="submit" class="btn btn-primary">Approve <i class="icon-pencil5 ml-2"></i></button>
							</div>
						</form>
					</div>
				</div>
				<!-- /form inputs -->

			</div>
			<!-- /content area -->
