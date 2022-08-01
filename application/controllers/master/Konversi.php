<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Konversi extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->library('form_validation');
    }

    public function index(){
        $this->load->view('master/konversi_item/list_view');
    }

    public function add(){
        $this->load->view('master/konversi_item/add_form');
    }

    public function edit(){
        $this->load->view('master/konversi_item/edit_form');
    }
}
?>