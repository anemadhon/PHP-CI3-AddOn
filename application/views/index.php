<!DOCTYPE html>
<?php
	
	$jagwarning=false;
	for($i=101; $i<=111; $i++){
	   if($data[$i] > 0) { 
		   $jagwarning=true;
			break; 
		}
	}

	$isFreeze = $this->auth->is_freeze()['is_freeze'];
	$isReject = $this->auth->is_freeze()['is_reject'];
	$isMgr = $this->auth->is_freeze()['is_mgr'];
?>
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
					<!-- Dashboard content -->
					<div class="row">
						<div class="col-xl-8"></div>
						<div class="col-xl-4">
							<!-- Daily sales -->
							<div class="card">
								<div class="card-header header-elements-inline" style="margin-bottom:-10px; display: block;">
									<div class="header-elements" style="position: relative;">
									<h4>Perhatian</h4>
										<span class="font-weight-bold text-danger-600 ml-2" style="position: absolute; right: 0;"><?= $tglterkini ?></span>
									</div>
									<div class="header-elements">
										<h5>Harap Segera Tindak Lanjuti</h5>
									</div>
								</div>
								<div class="table-responsive">
									<?php if($jagwarning):?>
									<table class="table text-nowrap">
										<thead>
											<tr>
												<th class="w-100">Data Baru</th>
												<th>Jumlah</th>
											</tr>
										</thead>
										<tbody>
											<?php for($i=101; $i<=104; $i++):?>
											<tr>
												<td>
												<?php if (($isFreeze == 0 && $isMgr == 0) || $isReject == 1):?>
												<i class="icon-checkmark3 font-size-sm mr-1" style="color: #2196f3;"></i><a href="<?php echo site_url($link[$i])?>" class="font-size-sm mr-1"><?=$nama[$i]?> </a> 
												<?php else: ?>
												<i class="icon-checkmark3 font-size-sm mr-1" style="color: #2196f3;"></i><span><?=$nama[$i]?> </span> 
												<?php endif; ?>
												</td>
												<td class="text-center">
													<h6 class="font-weight-semibold mb-0"><?=$data[$i]['Total']?></h6>
												</td>
											</tr>
											<?php endfor;?>
											<tr>
												<td>
												<?php if (($isFreeze == 0 && $isMgr == 0) || $isReject == 1):?>
												<i class="icon-checkmark3 font-size-sm mr-1" style="color: #2196f3;"></i><a href="<?php echo site_url($link[105])?>" class="font-size-sm mr-1"><?=$nama[105]?> </a> 
												<?php else: ?>
												<i class="icon-checkmark3 font-size-sm mr-1" style="color: #2196f3;"></i><span><?=$nama[105]?> </span>
												<?php endif; ?>
												</td>
												<td class="text-center">
													<h6 class="font-weight-semibold mb-0"><?=$data[105]?></h6>
												</td>
											</tr>
										</tbody>
									</table>
									<?php endif;?>
								</div>
							</div>
							<!-- /daily sales -->
							<!-- Stock Opname info -->
							<div class="card">
								<div class="table-responsive">
									<table class="table text-nowrap">
										<thead>
											<tr>
												<th class="w-100">Stock Opname Status</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>
												<?php
												if ($so_date) {
													if ($so_status['posting_date'] != '') {
														if ($so_status['status'] == 1 && $so_status['am_approved'] == 0 && $so_status['rm_approved'] == 0 && $so_status['freeze'] == 'Y') {
												?>
															<i class="icon-checkmark3 font-size-sm mr-1" style="color: #2196f3;"></i><a href="<?php echo site_url('transaksi1/stock/')?>" class="font-size-sm mr-1">Menunggu Approval dari Outlet  </a>
												<?php
														} elseif ($so_status['status'] == 2 && $so_status['am_approved'] == 0 && $so_status['rm_approved'] == 0 && $so_status['freeze'] == 'Y') {
												?>
															<i class="icon-checkmark3 font-size-sm mr-1" style="color: #2196f3;"></i><a href="<?php echo site_url('transaksi1/stock/')?>" class="font-size-sm mr-1">Menunggu Approval dari Area Manager  </a>
												<?php
														} elseif ($so_status['status'] == 2 && $so_status['am_approved'] == 2 && $so_status['rm_approved'] == 0 && $so_status['freeze'] == 'Y') {
												?>
															<i class="icon-checkmark3 font-size-sm mr-1" style="color: #2196f3;"></i><a href="<?php echo site_url('transaksi1/stock/')?>" class="font-size-sm mr-1">Menunggu Approval dari Regional Manager </a>
												<?php
														} elseif ($so_status['status'] == 2 && $so_status['am_approved'] == 2 && $so_status['rm_approved'] == 2 && $so_status['freeze'] == 'Y') {
												?>
															<i class="icon-checkmark3 font-size-sm mr-1" style="color: #2196f3;"></i><a href="<?php echo site_url('transaksi1/stock/')?>" class="font-size-sm mr-1">Menunggu Posting dari Cost Controll </a>
												<?php
														} elseif ($so_status['status'] == 2 && $so_status['am_approved'] == 1 && $so_status['rm_approved'] == 0 && $so_status['freeze'] == 'Y') {
												?>
															<i class="icon-checkmark3 font-size-sm mr-1" style="color: #2196f3;"></i><a href="<?php echo site_url('transaksi1/stock/')?>" class="font-size-sm mr-1">Data Ditolak oleh Area Manager </a>
												<?php
														} elseif ($so_status['status'] == 2 && $so_status['am_approved'] == 2 && $so_status['rm_approved'] == 1 && $so_status['freeze'] == 'Y') {
												?>
															<i class="icon-checkmark3 font-size-sm mr-1" style="color: #2196f3;"></i><a href="<?php echo site_url('transaksi1/stock/')?>" class="font-size-sm mr-1">Data Ditolak oleh Regional Manager </a>
												<?php
														} elseif ($so_status['posting_date'] < date('Y-m-d 00:00:00.000')) {
												?>
															<p><i class="icon-checkmark3 font-size-sm mr-1"></i><span><?php echo 'Stock Opname pada : '.date_format(date_create(substr($so_status['posting_date'],0,-9)), "d-m-Y").' belum selesai' ?></span></p>
												<?php
														}
													} else {
														if ($so_last['posting_date'] != '') {
												?>
															<p><i class="icon-checkmark3 font-size-sm mr-1"></i><span><?php echo 'Stock Opname Terakhir Selesai pada : '.date_format(date_create(substr($so_last['posting_date'],0,-9)), "d-m-Y") ?></span></p>
												<?php
														}
														if (date('Y-m-d 00:00:00.000') > $so_next['U_SODate']) {
												?>
															<p><i class="icon-checkmark3 font-size-sm mr-1"></i><span><?php echo 'Jadwal Stock Opname Telah Terlewat ('.date_format(date_create(substr($so_next['U_SODate'],0,-13)), "d-m-Y").')'?> </span></p>
												<?php
														} else {
												?>
															<i class="icon-checkmark3 font-size-sm mr-1" style="color: #2196f3;"></i><a href="<?php echo site_url('transaksi1/stock/')?>" class="font-size-sm mr-1"><?php echo 'Stock Opname Selanjutnya pada : '.date_format(date_create(substr($so_next['U_SODate'],0,-13)), "d-m-Y") ?> </a>
												<?php
														}
													}
												} else {
												?>
													<p><i class="icon-checkmark3 font-size-sm mr-1"></i><span>Tanggal Stock Opname belum Diatur</span></p>
												<?php
												}
												?>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<!-- /Stock Opname info -->
							<!-- Product Costing info -->
							<?php  
							if ($this->auth->is_have_perm('inv_productcosting')) {
							?>
								<div class="card">
									<div class="table-responsive">
										<table class="table text-nowrap">
											<thead>
												<tr>
													<th class="w-100">Product Costing Status</th>
													<th>Jumlah</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>
													<i class="icon-checkmark3 font-size-sm mr-1" style="color: #2196f3;"></i><a href="<?php echo site_url('transaksi1/productcosting/fromdashboard/01')?>" class="font-size-sm mr-1">Menunggu Approval Admin </a> 
													</td>
													<td class="text-center">
														<h6 class="font-weight-semibold mb-0"><?=$prod_cost_data?></h6>
													</td>
												</tr>
												<tr>
													<td>
													<i class="icon-checkmark3 font-size-sm mr-1" style="color: #2196f3;"></i><a href="<?php echo site_url('transaksi1/productcosting/fromdashboard/02')?>" class="font-size-sm mr-1">Menunggu Approval Head Dept </a> 
													</td>
													<td class="text-center">
														<h6 class="font-weight-semibold mb-0"><?=$prod_cost_data_head?></h6>
													</td>
												</tr>
												<tr>
													<td>
													<i class="icon-checkmark3 font-size-sm mr-1" style="color: #2196f3;"></i><a href="<?php echo site_url('transaksi1/productcosting/fromdashboard/03')?>" class="font-size-sm mr-1">Menunggu Approval Category Approver </a> 
													</td>
													<td class="text-center">
														<h6 class="font-weight-semibold mb-0"><?=$prod_cost_data_ca?></h6>
													</td>
												</tr>
												<tr>
													<td>
													<i class="icon-checkmark3 font-size-sm mr-1" style="color: #2196f3;"></i><a href="<?php echo site_url('transaksi1/productcosting/fromdashboard/04')?>" class="font-size-sm mr-1">Menunggu Approval Cost Control </a> 
													</td>
													<td class="text-center">
														<h6 class="font-weight-semibold mb-0"><?=$prod_cost_data_cc?></h6>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							<?php
							}
							?>
							<!-- /Product Costing info -->
							<!-- BOM Sub Alt info (index.php)-->
							<?php  
							if ($this->auth->is_have_perm('inv_productcosting')) {
							?>
								<div class="card">
									<div class="table-responsive">
										<table class="table text-nowrap">
											<thead>
												<tr>
													<th class="w-100">BOM Sub Alt Status</th>
													<th>Jumlah</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>
													<i class="icon-checkmark3 font-size-sm mr-1" style="color: #2196f3;"></i><a href="<?php echo site_url('transaksi1/bomsubalt/')?>" class="font-size-sm mr-1">Menunggu Approval Admin </a>
													</td>
													<td class="text-center">
														<h6 class="font-weight-semibold mb-0"><?=$bom_sub_alt_data?></h6>
													</td>
												</tr>
												<tr>
													<td>
													<i class="icon-checkmark3 font-size-sm mr-1" style="color: #2196f3;"></i><a href="<?php echo site_url('transaksi1/bomsubalt/')?>" class="font-size-sm mr-1">Menunggu Approval Head Dept </a>
													</td>
													<td class="text-center">
														<h6 class="font-weight-semibold mb-0"><?=$bom_sub_alt_data_head?></h6>
													</td>
												</tr>
												<tr>
													<td>
													<i class="icon-checkmark3 font-size-sm mr-1" style="color: #2196f3;"></i><a href="<?php echo site_url('transaksi1/bomsubalt/')?>" class="font-size-sm mr-1">Menunggu Approval Category Approver </a>
													</td>
													<td class="text-center">
														<h6 class="font-weight-semibold mb-0"><?=$bom_sub_alt_data_ca?></h6>
													</td>
												</tr>
												<tr>
													<td>
													<i class="icon-checkmark3 font-size-sm mr-1" style="color: #2196f3;"></i><a href="<?php echo site_url('transaksi1/bomsubalt/')?>" class="font-size-sm mr-1">Menunggu Approval Cost Control </a>
													</td>
													<td class="text-center">
														<h6 class="font-weight-semibold mb-0"><?=$bom_sub_alt_data_cc?></h6>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							<?php
							}
							?>
							<!-- /BOM Sub Alt info -->
						</div>
					</div>
					<!-- /dashboard content -->
				</div>
				<?php $this->load->view("_template/footer.php")?>
			</div>
		</div>
		<?php  $this->load->view("_template/js.php")?>
	</body>
</html>
