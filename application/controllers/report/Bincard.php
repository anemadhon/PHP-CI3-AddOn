<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Bincard extends CI_Controller {
    public function __construct(){
        parent::__construct();

        $this->load->library('auth');  
		if(!$this->auth->is_logged_in()) {
			redirect(base_url());
        }
        // load model
        $this->load->model("report/bincard_model","bin_model");
        
        $this->load->library('form_validation');
        $this->load->library('l_general');
    }

    public function index(){
        $object['warehouse'] = $this->bin_model->warehouse();
        $object['itemGroup'] = $this->bin_model->item_group();
        $this->load->view("report/bincard_view", $object);
    }

    public function showAllData(){
        $itemGroup = $this->input->post('item_Group');
        $itemGroup2 = $this->input->post('item_Group2');
        $fromDate = $this->input->post('fromDate');
        $toDate = $this->input->post('toDate');
        $warehouse = $this->input->post('whs');
        $draw = intval($this->input->post("draw"));
        $length = intval($this->input->post("length"));
        $start = intval($this->input->post("start"));

        $bincardData = $this->bin_model->getData($fromDate, $itemGroup, $itemGroup2, $warehouse,$toDate,  $length, $start);

        $totalBincard = $this->totalData($fromDate, $itemGroup, $itemGroup2, $warehouse,$toDate);
        $dt = array();
        $no = 1;
        if($bincardData){
            foreach($bincardData as $key=>$val){
                $item = $val['ItemCode'];
                $nestedData = array();
                $nestedData['no'] = $no;
                $nestedData['itemcode'] = $val['ItemCode'];
                $nestedData['ItemName'] = $val['ItemName'];

                $qty_ck = $this->bin_model->qty_ck($item, $fromDate, $toDate);
                $qty_fo = $this->bin_model->qty_fo($item, $fromDate, $toDate);
                $qty_retin = $this->bin_model->qty_retin($item, $fromDate, $toDate);
                $total_in = number_format($qty_ck[0]['qty_ck'] + $qty_fo[0]['qty_fo'] + $qty_retin[0]['qty_retin']);

                $nestedData['qty_ck'] = $qty_ck ? $qty_ck[0]['qty_ck'] : '';
                $nestedData['qty_fo'] = $qty_fo ? $qty_fo[0]['qty_fo'] : '';
                $nestedData['qty_retin'] = $qty_retin ? $qty_retin[0]['qty_retin'] : '';
                $nestedData['total_in'] = $total_in;

                $qty_to = $this->bin_model->qty_to($item, $fromDate, $toDate);
                $qty_ro = $this->bin_model->qty_ro($item, $fromDate, $toDate);
                $total_out = number_format($qty_to[0]['qty_to'] + $qty_ro[0]['qty_ro']);

                $nestedData['qty_to'] = $qty_to ? $qty_to[0]['qty_to'] : '';
                $nestedData['qty_ro'] = $qty_ro ? $qty_ro[0]['qty_ro'] : '';
                $nestedData['total_out'] = $total_out;    

                $dt[] = $nestedData;
                $no++;
            }
        }
        $json_data = array(
            "draw" => $draw,
            "recordsTotal" => $totalBincard,
            "recordsFiltered" => $totalBincard,
            "data" => $dt
        );
        echo json_encode($json_data) ;
    }

    function totalData($fromDate, $itemGroup, $itemGroup2, $warehouse,$toDate){
        $total=$this->bin_model->totalDataBincard($fromDate, $itemGroup, $itemGroup2, $warehouse,$toDate);
        if($total){
            return $total[0]['num'];
        } else{
            return 0;
        }
    }
}
?>