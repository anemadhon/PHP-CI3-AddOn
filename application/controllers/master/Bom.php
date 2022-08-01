<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Bom extends CI_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('auth');
        //load model
        if(!$this->auth->is_logged_in()) {
			redirect(base_url());
        }
        //load model
        $this->load->model('master/bom_model', 'bom_model');
    }

    public function index(){
        $this->load->view('master/bom/list_view');
    }
	
	public function edit(){
        $this->load->view('master/bom/edit_form');
    }
	
	public function showListData(){
        $itemCode = $this->input->post('bom');

        $rs = $this->bom_model->getDataBom_Header($itemCode);

        $data = array();
        $no = 1;
        foreach($rs as $key=>$val){
            $nestedData = array();
            $nestedData['No'] = $no;
            $nestedData['ItemCode'] = $val['Code'];
            $nestedData['ItemName'] = $val['ItemName'];
            $nestedData['Quantity'] = $val['Qauntity'];
            $data[] = $nestedData;
            $no++ ;
        }

        $json_data = array(
            "recordsTotal"    => 10, 
            "recordsFiltered" => 12,
            "data"            => $data
        );
        echo json_encode($json_data);
    }
	
	public function showListDataDetails(){
        $itemCode = $this->input->post('fatherCode');

        $rs = $this->bom_model->getDataBom_Detail($itemCode);

        $data = array();
        $no = 1;
        foreach($rs as $key=>$val){
            $nestedData = array();
            $nestedData['No'] = $no;
            $nestedData['ItemCode'] = $val['Code'];
            $nestedData['ItemName'] = $val['ItemName'];
            $nestedData['Quantity'] = $val['Quantity'];
            $nestedData['UOM'] = $val['InvntryUom'];
            $data[] = $nestedData;
            $no++ ;
        }

        $json_data = array(
            "recordsTotal"    => 10, 
            "recordsFiltered" => 12,
            "data"            => $data
        );
        echo json_encode($json_data);
    }
	
	public function add(){
        $this->load->view('master/bom/add_form');
    }
}
?>