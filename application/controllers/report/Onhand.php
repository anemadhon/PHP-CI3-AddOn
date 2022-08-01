<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Onhand extends CI_Controller {
    public function __construct(){
        parent::__construct();

        $this->load->library('auth');  
		if(!$this->auth->is_logged_in()) {
			redirect(base_url());
        }
        // load model
        $this->load->model("report/onhand_model","onh_model");
        
        $this->load->library('form_validation');
        $this->load->library('l_general');
    }

    public function index(){
        $object['itemGroup'] = $this->onh_model->item_group();
        $this->load->view("report/onhand_view",$object);
    }

    public function showAllData(){
        $itemGroup = $this->input->post('item_Group');
        $onHandData = $this->onh_model->getData($itemGroup);

        $dt = array();
        $no = 1;
        if($onHandData){
            foreach($onHandData as $key=>$val){
                $Amount = $val['OnHand'] * $val['ItemCost'];
                $netedData = [];
                $nestedData['no'] = $no;
                $nestedData['code'] = $val['ItemCode'];
                $nestedData['Description'] = $val['ItemName'];
                $nestedData['OnHand'] = number_format($val['OnHand'],2,",",".");
                $nestedData['ItmsGrpNam'] = $val['ItmsGrpNam'];
                $nestedData['Uom'] = $val['Uom'];
                $nestedData['ItemCost'] = number_format($val['ItemCost'],2,",",".");
                $nestedData['Amount'] = number_format($Amount,2,",",".");
                $dt[] = $nestedData;
                $no++;
            }
        }
        $json_data = array(
            "data" => $dt
        );
        echo json_encode($json_data) ;
    }

    function printExcel(){
        parse_str(substr(strrchr($_SERVER['REQUEST_URI'], "?"),1), $_GET);
        $kd_plant = $this->session->userdata['ADMIN']['plant'];
        $plant_name = $this->session->userdata['ADMIN']['plant_name'];

        $itemGroup = $_GET['item_group'];
        $object['page_title'] = 'Onhand Report';
        $object['plant1'] = $kd_plant;
        $object['plant_name'] = 'THE HARVEST '. strtoupper($plant_name);
        $object['item_group_code'] = $itemGroup;
        $object['dataOnHand'] = $this->onh_model->getData($itemGroup);
        
        $this->load->view("report/excel/onhand_excel",$object);
    }
}
?>