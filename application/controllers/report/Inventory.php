<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inventory extends CI_Controller {
    public function __construct(){
        parent::__construct();

        $this->load->library('auth');  
		if(!$this->auth->is_logged_in()) {
			redirect(base_url());
        }
        // load model
        $this->load->model("report/inventory_model","inv_model");
        
        $this->load->library('form_validation');
        $this->load->library('l_general');
    }

    public function index(){
        $object['warehouse'] = $this->inv_model->warehouse();
        
        $object['itemGroup'] = $this->inv_model->item_group();
        
        $this->load->view("report/inventory_view", $object);
    }

    public function showAllData(){
        $kd_plant = $this->session->userdata['ADMIN']['plant'];
        $itemGroup = $this->input->post('item_Group');
        $fromDate = $this->input->post('fromDate');
        $toDate = $this->input->post('toDate');
        $warehouse = $kd_plant;
        $fromItem = $this->input->post('fItem');
        $toItem = $this->input->post('tItem');
        $draw = intval($this->input->post("draw"));
        $length = intval($this->input->post("length"));
        $start = intval($this->input->post("start"));
        
        $inventoryData = $this->inv_model->getDataNew($itemGroup, $fromDate, $toDate, $warehouse, $fromItem, $toItem);

        $totalInventory = $this->totalData($itemGroup, $fromDate, $toDate, $warehouse);
        $dt = array();
        $no = 1;
        if($inventoryData){
            foreach($inventoryData as $key=>$val){
                $nestedData = array();
                $nestedData['no'] = $no;
                $nestedData['fg'] = $val['fg'];
                $nestedData['ItemCode'] = $val['ItemCode'];
                $nestedData['ItemName'] = $val['itemname'];
                $nestedData['AddOnDocNo'] = $val['AddOnDocNo'];
                $nestedData['SAPDocNo'] = $val['SAPDocNo'];
                $nestedData['docdate'] = $val['docdate'] !='' ? date("d-m-Y",strtotime($val['docdate'])) : '';
                $nestedData['SystemDate'] = $val['SystemDate']!='' ? date("d-m-Y",strtotime($val['SystemDate'])):'';
                $nestedData['quantity'] = ( $val['SAPDocNo'] =='Opening Balance '&& $val['quantity']==NULL) ? '': number_format($val['quantity'], 2,",",".");
              //  $nestedData['cost'] = ( $val['SAPDocNo'] =='Opening Balance '&& $val['cost']==NULL) ? '':number_format($val['cost'], 2,",",".");
              //  $nestedData['transvalue'] = ( $val['SAPDocNo'] =='Opening Balance '&& $val['transvalue']==NULL) ? '':number_format($val['transvalue'], 2,",",".");
                $nestedData['CummulativeQty'] = number_format($val['CummulativeQty'], 2,",",".");
              //  $nestedData['CummulativeValue'] = number_format($val['CummulativeValue'], 2,",",".");
                
                $dt[] = $nestedData;
                $no++;
            }
        }
        $json_data = array(
            "draw" => $draw,
            "recordsTotal" => $totalInventory,
            "recordsFiltered" => $totalInventory,
            "data" => $dt
        );
        echo json_encode($json_data) ;
    }

    function getdataDetailMaterial(){
		$item_group_code = $this->input->post('matGroup');
		
		if($item_group_code == ''){
			$data = $this->inv_model->getDataMaterialGroup();
		}else{
			$data = $this->inv_model->getDataMaterialGroup($item_group_code);
        }
        
        echo json_encode($data);

	}

    function totalData($itemGroup, $fromDate, $toDate, $warehouse){
        $total=$this->inv_model->totalDataInventory($itemGroup, $fromDate, $toDate, $warehouse);
        if($total){
            return $total[0]['num'];
        } else{
            return 0;
        }
    }

    function printExcel(){
        parse_str(substr(strrchr($_SERVER['REQUEST_URI'], "?"),1), $_GET);
        $kd_plant = $this->session->userdata['ADMIN']['plant'];
        $plant_name = $this->session->userdata['ADMIN']['plant_name'];

        $fromDate = $_GET['frmDate'];
        $toDate = $_GET['toDate'];
        $warehouse = $kd_plant;
        $itemGroup = $_GET['itemGroup'];
        $fromItem = $_GET['fromItem'];
        $toItem = $_GET['toItem'];

        $year_from = substr($fromDate,0,4);
        $mounth_from = substr($fromDate,4,2);
        $day_from = substr($fromDate,6,2);

        $year_to = substr($toDate,0,4);
        $mounth_to = substr($toDate,4,2);
        $day_to = substr($toDate,6,2);

        $object['page_title'] = 'Inventory Audit Report';
        $object['plant1'] = $kd_plant;
        $object['plant_name'] = 'THE HARVEST '. strtoupper($plant_name);
        $object['item_group_code'] = $itemGroup;
        $object['date_from'] = $year_from.'/'.$mounth_from.'/'.$day_from;
        $object['date_to'] = $year_to.'/'.$mounth_to.'/'.$day_to;
        $object['WhsCode'] = $warehouse;
        $object['data'] = $this->inv_model->getDataNew($itemGroup, $fromDate, $toDate, $warehouse, $fromItem, $toItem);
        
        $this->load->view("report/excel/inventory_excel",$object);
    }
}
?>