<!DOCTYPE html>
<html lang="en">
	<head>
		<?php $this->load->view("_template/head.php")?>
	</head>
	<body>
	<?php $this->load->view("_template/nav.php")?>
		<div class="page-content">
			<?php $this->load->view("_template/sidebar.php")?>
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
                            <legend class="font-weight-semibold"><i class="icon-search4 mr-2"></i>Search of Sentul Entry In</legend>  
                        </div>
                        <div class="card-body">
                        <form action="#" method="POST">
                            <div class="row">
                                <div class="col-md-12">
                                <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Dari Tanggal</label>
                                        <div class="col-lg-3 input-group date">
                                            <input type="text" class="form-control" id="fromDate" name="fromDate">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">
                                                    <i class="icon-calendar"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <label class="col-lg-2 col-form-label">Sampai Tanggal</label>
                                        <div class="col-lg-4 input-group date">
                                            <input type="text" class="form-control" id="toDate" name="toDate">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">
                                                    <i class="icon-calendar"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Status</label>
                                        <div class="col-lg-9">
                                            <select class="form-control form-control-select2" data-live-search="true" name='status' id='status'>
                                                <option value="">-- All --</option>
                                                <option value="1">Created</option>
                                                <option value="0">Canceled</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        <button type="button" class="btn btn-primary" onclick="onSearch()">Search<i class="icon-search4  ml-2"></i></button>
									</div>
                                </div>
                            </div>
                        </form>
                        </div>                        
                    </div> 
                    <div class="card">
                        <div class="card-header">
                            <legend class="font-weight-semibold"><i class="icon-list mr-2"></i>List of Sentul Entry In</legend>
                            <a href="<?php echo site_url('transaksi1/sentulentry_in/add') ?>" class="btn btn-primary"> Add New</a>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12" style="overflow:auto">
                                    <table id="tableWhole" class="table table-striped" style="widht:100%" >
                                        <thead>
                                            <tr>
                                                <th style="text-align: center">Action</th>
                                                <th style="text-align: center">ID</th>
                                                <th style="text-align: center">SAP Doc. Number</th>
                                                <th style="text-align: center">Posting Date</th>
                                                <th style="text-align: center">Created by</th>
                                                <!-- <th style="text-align: center">Updated by</th> -->
                                                <th style="text-align: center">Canceled by</th>
                                                <th style="text-align: center">Last Modified</th>
                                                <th style="text-align: center">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        </div>                   
				</div>
				<?php $this->load->view("_template/footer.php")?>
			</div>
		</div>
        <?php $this->load->view("_template/modal_delete.php")?>
        <?php $this->load->view("_template/js.php")?>
        <script>
            $(document).ready(function(){
                $('#fromDate').datepicker();
                $('#toDate').datepicker();
                
                showListData();
            });

            function onSearch(){
                const fromDate = $('#fromDate').val();
                const toDate = $('#toDate').val();
                const status = $('#status').val();

                showListData();
            }

            function showListData(){
                const obj = $('#tableWhole tbody tr').length;

                if(obj > 0){
                    const dataTable = $('#tableWhole').DataTable();
                    dataTable.destroy();
                    $('#tableWhole > tbody > tr').remove();
                    
                }

                const fromDate = $('#fromDate').val();
                const toDate = $('#toDate').val();
                const status = $('#status').val();

                dataTable = $('#tableWhole').DataTable({
                    "ordering":true,  "paging": true, "searching":true,
                    "ajax": {
                        "url":"<?php echo site_url('transaksi1/sentulentry_in/showListData');?>",
                        "type":"POST",
                        "data":{fDate: fromDate, tDate: toDate, stts: status}
                    },
                    "columns": [
                        {"data":"id_sentul_entry_header", "className":"text-center", render:function(data, type, row, meta){
                            rr = `<a href='<?php echo site_url('transaksi1/sentulentry_in/edit/')?>${data}' ><i class='icon-file-plus2' title="Edit"></i></a>`;
                            return rr;
                        }},
                        {"data":"id_sentul_entry_header"},
                        {"data":"sap_doc_number"},
                        {"data":"posting_date"},
                        {"data":"user_input"},
                        // {"data":"user_update"},
                        {"data":"user_cancel"},
                        {"data":"last_modified"},
                        {"data":"status_string"}
                    ]
                });
            }
        
        </script>
	</body>
</html>