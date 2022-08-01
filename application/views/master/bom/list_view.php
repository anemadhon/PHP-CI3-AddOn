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
                            <legend class="font-weight-semibold"><i class="icon-list mr-2"></i>List of BOM</legend>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12" style="overflow:auto">
                                    <table id="tableWhole" class="table table-striped" style="width:100%" >
                                        <thead>
                                            <tr>
												<th style="text-align: center">No</th>
                                                <th style="text-align: center">Item Number</th>
                                                <th style="text-align: center">Item Description</th>
                                                <th style="text-align: center">Item Quantity</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        </div>                   
				</div>
				<?php  $this->load->view("_template/footer.php")?>
			</div>
		</div>
        <!-- start modal -->
        <div id="DetailModalBOM" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                    <div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <table id="rwCollDetail" class="table table-striped">
                            <thead>
                                <tr>
                                    <th style="text-align: center">No</th> 
                                    <th style="text-align: center">Item Number</th> 
                                    <th style="text-align: center">Item Description</th> 
                                    <th style="text-align: center">Item Quantity</th> 
                                    <th style="text-align: center">UOM</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
            
                </div>
            </div>
        </div>
        <!-- end modal -->
        <?php  $this->load->view("_template/js.php")?>
        <script>
            function search(){
                const txtBom = $('#txt_kode_item').val();
                showDataList();
            };
            function showDataList(){
                const obj = $('#tableWhole tbody tr').length;

                if(obj > 0){
                    const dataTable = $('#tableWhole').DataTable();
                    dataTable.destroy();
                    $('#tableWhole > tbody > tr').remove();
                    
                }
                
                const txtBom = $('#txt_kode_item').val(); 

                $('#tableWhole').dataTable({
                    "ordering":true,  
                    "paging": true, 
                    "searching":true,
                    "ajax": {
                        "url": "<?php echo site_url('master/bom/showListData');?>",
                        "type": "POST",
                        "data":{bom: txtBom}
                    },
                    "columnDefs": [
			 			{"className": "dt-center", "targets": "_all"}
			 		],
                     "columns": [
						{"data":"No", "className":"dt-center"},
                        {"data":"ItemCode", "className":"dt-center", render: function(data, type, row, meta){
           					rr = '<a href="javascript:void(0)"  onclick="openWin(\''+data+'\');">'+data+'</a>';
                			return rr;
						}},
                        {"data":"ItemName", "className":"dt-center"},
                        {"data":"Quantity", "className":"dt-center"},
                    ]
                })
			}
			
			function openWin (itemCode){
				var objx = $('#rwCollDetail tbody tr').length;
				if(objx > 0){
				// destroy dataTable
					var tabless = $("#rwCollDetail").DataTable();         
					tabless.destroy();
					$("#rwCollDetail > tbody > tr").remove();
					}

				$("#rwCollDetail").DataTable({
				"ajax" : {
					url:"<?php echo base_Url()?>master/bom/showListDataDetails", 
					type:"POST", 
					data:{fatherCode: itemCode}
				},
				"columns" : [
					{"data":"No", "className":"dt-center"},
					{"data":"ItemCode", "className":"dt-center"},
					{"data":"ItemName", "className":"dt-center"},
					{"data":"Quantity", "className":"dt-center"},
					{"data":"UOM", "className":"dt-center"}
				]		
				});
				$("#DetailModalBOM").modal("show");
			}	
            $(function(){
               showDataList();
            });
        </script>
	</body>
</html>