<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require('./application/third_party/PHPExcel/PHPExcel.php');

class GoodIssue extends CI_Controller {
    public function __construct(){
        parent::__construct();

        $this->load->library('auth');  
		if(!$this->auth->is_logged_in()) {
			redirect(base_url());
        }
        // load model
        $this->load->model('transaksi1/goodissue_model', 'gi_model');
        
        $this->load->library('form_validation');
        $this->load->library('l_general');
    }

    public function index(){
        $max['max_attempt'] = $this->gi_model->get_max_attempt_whs();
        $max['filters'] = $this->gi_model->get_filter_status_desc('report');
        $this->load->view("report/gi_view", $max);
    }

    public function showAllData(){
        $kd_plant = $this->session->userdata['ADMIN']['plant'];
        $type = $this->input->post('type');
        $fromDate = $this->input->post('fromDate');
        $toDate = $this->input->post('toDate');
        
        $max_attempt = $this->gi_model->get_max_attempt_whs();

        $gi = $this->gi_model->getDataGI_Header($fromDate, $toDate, $type, 'report');
        
        $data = array();
        foreach ($gi as $key => $val) {
            $inwhs = $this->gi_model->in_whs_qty($this->session->userdata['ADMIN']['plant'], $val['material_no']);
            $attempts = $this->gi_model->get_attempt($val['id_issue_detail']);

            $giData = array();
            $giData['no'] = (int)($key + 1);
            $giData['id_issue_header'] = $val['id_issue_header'];
            $giData['material_doc_no'] = $val['material_doc_no'];
            $giData['posting_date'] = date("d-m-Y",strtotime($val['posting_date']));
            $giData['material_no'] = $val['material_no'];
            $giData['material_desc'] = $val['material_desc'];
            $giData['plant'] = $val['plant'];
            $giData['on_hand'] = ($val['status']==1 ? ($inwhs['OnHand']!='.000000' ? number_format($inwhs['OnHand'],4,'.','') : '0.0000') : number_format($val['stock'],4,'.',''));
            $giData['quantity'] = number_format($val['quantity'],4,'.','');
            $giData['uom'] = $val['uom'];
            $giData['outstd_qty_to_intgrte'] = number_format($val['outstd_qty_to_intgrte'],4,'.','');
            $giData['total_qty_intgrted'] = number_format($val['total_qty_intgrted'],4,'.','');
            $giData['status_desc'] = $val['status_desc'];
            for ($i=1; $i <= $max_attempt['max_attempt']; $i++) { 
                if (count($attempts) === 0) {
                    $giData["doc_no_{$i}"] = '';
                    $giData["approved_time_{$i}"] = '';
                    $giData["qty_integrate_{$i}"] = '';
                }
                if (count($attempts) > 0) {
                    foreach ($attempts as $attempt) {
                        if ($i == $attempt['id_issue_h_intgrtn']) {
                            $giData["doc_no_{$i}"] = $attempt['doc_no'];
                            $giData["approved_time_{$i}"] = $attempt['doc_date'] ? date("d-m-Y",strtotime($attempt['doc_date'])) : '';
                            $giData["qty_integrate_{$i}"] = number_format($attempt['qty_integrate'],4,'.','');
                        }
                    }
                }
            }
            $data[] = $giData;
        }
 
        $json_data = array(
            "recordsTotal"    => 10, 
            "recordsFiltered" => 12,
            "data"            => $data 
        );
        echo json_encode($json_data);
    }

    function printExcel(){
        parse_str(substr(strrchr($_SERVER['REQUEST_URI'], "?"),1), $_GET);
        $kd_plant = $this->session->userdata['ADMIN']['plant'];
        $plant_name = $this->session->userdata['ADMIN']['plant_name'];

        $fromDate = $_GET['frmDate'];
        $toDate = $_GET['toDate'];
        $type = $_GET['type'];

        $year_from = substr($fromDate,0,4);
        $mounth_from = substr($fromDate,4,2);
        $day_from = substr($fromDate,6,2);

        $year_to = substr($toDate,0,4);
        $mounth_to = substr($toDate,4,2);
        $day_to = substr($toDate,6,2);

        $max_attempt = $this->gi_model->get_max_attempt_whs();
        $gi = $this->gi_model->getDataGI_Header($fromDate, $toDate, $type, 'report');

        /* $object['page_title'] = 'Inventory Audit Report';
        $object['plant1'] = $kd_plant;
        $object['plant_name'] = 'THE HARVEST '. strtoupper($plant_name);
        $object['item_group_code'] = $itemGroup;
        $object['date_from'] = $year_from.'/'.$mounth_from.'/'.$day_from;
        $object['date_to'] = $year_to.'/'.$mounth_to.'/'.$day_to;
        $object['WhsCode'] = $warehouse;
        $object['data'] = $this->inv_model->getDataNew($itemGroup, $fromDate, $toDate, $warehouse, $fromItem, $toItem); */
        
        $excel = new PHPExcel();

        // set config for image
        $imgHead = new PHPExcel_Worksheet_Drawing();
        $imgHead->setName('Logo');
        $imgHead->setDescription('Logo');
        $imgHead->setPath('./files/assets/images/logo.jpeg');
        $imgHead->setHeight(70);
        $imgHead->setCoordinates('A1');
        // $imgHead->setOffsetX(220);
        $imgHead->setWorksheet($excel->getActiveSheet());

        // $excel->getActiveSheet()->getProtection()->setSheet(true);

        //set config for column width
        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(9);
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(65);
        $excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('K')->setWidth(25);
        $excel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('N')->setWidth(13);
        $excel->getActiveSheet()->getColumnDimension('O')->setWidth(13);
        $excel->getActiveSheet()->getColumnDimension('P')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('Q')->setWidth(13);
        $excel->getActiveSheet()->getColumnDimension('R')->setWidth(13);
        $excel->getActiveSheet()->getColumnDimension('S')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('T')->setWidth(13);
        $excel->getActiveSheet()->getColumnDimension('U')->setWidth(13);
        $excel->getActiveSheet()->getColumnDimension('V')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('W')->setWidth(13);
        $excel->getActiveSheet()->getColumnDimension('X')->setWidth(13);
        $excel->getActiveSheet()->getColumnDimension('Y')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('Z')->setWidth(13);
        $excel->getActiveSheet()->getColumnDimension('AA')->setWidth(13);

        // set config for title header file
        /* $excel->getActiveSheet()->getStyle('A5')->getAlignment()
              ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('A6')->getAlignment()
              ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('A7')->getAlignment()
              ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); */

        $excel->getActiveSheet()->getStyle('A5')->getFont()->setBold(true);
        $excel->getActiveSheet()->getStyle('A6')->getFont()->setBold(true);

        $excel->getActiveSheet()->mergeCells('A5:C5');
        $excel->setActiveSheetIndex(0)->setCellValue('A5', 'Good Issue Report'); 
        $excel->getActiveSheet()->mergeCells('A6:C6');
        $excel->setActiveSheetIndex(0)->setCellValue('A6', "Outlet {$kd_plant} {$plant_name}"); 
        $excel->getActiveSheet()->mergeCells('A7:C7');
        $excel->setActiveSheetIndex(0)->setCellValue('A7', "Dari Tanggal {$day_from}/{$mounth_from}/{$year_from} -s/d- {$day_to}/{$mounth_to}/{$year_to}"); 

        //style of border
        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => '00000000'),
                ),
            ),
        );

        // $excel->getActiveSheet()->getProtection()->setPassword('MSI_SO');
        
        $excel->getActiveSheet()->getStyle('A9:AA9')->applyFromArray($styleArray);
        $excel->getActiveSheet()->getStyle('A10:AA10')->applyFromArray($styleArray);

        // set config for title header table 
        $excel->getActiveSheet()->getStyle('A9:AA9')->getAlignment()
              ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('A10:AA10')->getAlignment()
              ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excel->getActiveSheet()->getStyle('A9:AA9')->getAlignment()
              ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $excel->getActiveSheet()->getStyle('A10:AA10')->getAlignment()
              ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $excel->getActiveSheet()->getStyle('A9:AA9')->getFont()->setBold(true);
        
        $excel->getActiveSheet()->getRowDimension('9')->setRowHeight(20);

        $excel->getActiveSheet()->mergeCells('A9:A10');
        $excel->getActiveSheet()->mergeCells('B9:B10');
        $excel->getActiveSheet()->mergeCells('C9:C10');
        $excel->getActiveSheet()->mergeCells('D9:D10');
        $excel->getActiveSheet()->mergeCells('E9:E10');
        $excel->getActiveSheet()->mergeCells('F9:F10');
        $excel->getActiveSheet()->mergeCells('G9:G10');
        $excel->getActiveSheet()->mergeCells('H9:H10');
        $excel->getActiveSheet()->mergeCells('I9:I10');
        $excel->getActiveSheet()->mergeCells('J9:J10');
        $excel->getActiveSheet()->mergeCells('K9:K10');
        $excel->getActiveSheet()->mergeCells('L9:L10');
        
        $excel->setActiveSheetIndex(0)->setCellValue('A9', 'No.'); 
        $excel->setActiveSheetIndex(0)->setCellValue('B9', 'Issue No'); 
        $excel->setActiveSheetIndex(0)->setCellValue('C9', 'Status'); 
        $excel->setActiveSheetIndex(0)->setCellValue('D9', 'Posting Date'); 
        $excel->setActiveSheetIndex(0)->setCellValue('E9', 'Item Code'); 
        $excel->setActiveSheetIndex(0)->setCellValue('F9', 'Item Name'); 
        $excel->setActiveSheetIndex(0)->setCellValue('G9', 'Warehouse'); 
        $excel->setActiveSheetIndex(0)->setCellValue('H9', 'On Hand Qty'); 
        $excel->setActiveSheetIndex(0)->setCellValue('I9', 'Issued Qty'); 
        $excel->setActiveSheetIndex(0)->setCellValue('J9', 'UOM'); 
        $excel->setActiveSheetIndex(0)->setCellValue('K9', 'Outsanding Qty To Integrate'); 
        $excel->setActiveSheetIndex(0)->setCellValue('L9', 'Total Qty Integrated'); 
        $abjadBegin = 'K';
        for ($i=1; $i <= $max_attempt['max_attempt']; $i++) {
            $nextAbjad = ++$abjadBegin;
            $nextTwoAbjad = ++$nextAbjad;
            $nextThreeAbjad = ++$nextTwoAbjad;
            $excel->getActiveSheet()->mergeCells($nextAbjad.'9:'.++$nextThreeAbjad.'9');
            $excel->setActiveSheetIndex(0)->setCellValue($nextAbjad.'9', "Attempt {$i}"); 

            $excel->setActiveSheetIndex(0)->setCellValue($nextAbjad.'10', 'SAP Doc No.');
            $excel->setActiveSheetIndex(0)->setCellValue($nextTwoAbjad.'10', 'Doc Date');
            $excel->setActiveSheetIndex(0)->setCellValue(++$nextTwoAbjad.'10', 'Qty integrate');

            $abjadBegin = ++$nextAbjad;
        }

        $numrow = 11;
        foreach($gi as $key=>$r){ 
            $inwhs = $this->gi_model->in_whs_qty($this->session->userdata['ADMIN']['plant'], $r['material_no']);
            $attempts = $this->gi_model->get_attempt($r['id_issue_detail']);

            // applying border style
            $excel->getActiveSheet()->getStyle('A'.$numrow.':AA'.$numrow)->applyFromArray($styleArray);

            // set config alignment body table
            $excel->getActiveSheet()->getStyle('A'.$numrow.':E'.$numrow)->getAlignment()
              ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $excel->getActiveSheet()->getStyle('G'.$numrow)->getAlignment()
              ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            
            $excel->getActiveSheet()->getStyle('J'.$numrow)->getAlignment()
              ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $excel->getActiveSheet()->getStyle('H'.$numrow.':I'.$numrow)->getAlignment()
              ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            
            $excel->getActiveSheet()->getStyle('K'.$numrow.':L'.$numrow)->getAlignment()
              ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            $excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, (int)($key + 1));
            $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $r['id_issue_header']);
            $excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $r['status_desc']);
            $excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, date("d-m-Y",strtotime($r['posting_date'])));
            $excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $r['material_no']);
            $excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $r['material_desc']);
            $excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $kd_plant);
            $excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, ($r['status']==1 ? ($inwhs['OnHand']!='.000000' ? number_format($inwhs['OnHand'],4,'.','') : '0.0000') : number_format($r['stock'],4,'.','')));
            $excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, number_format($r['quantity'],4,'.',''));
            $excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $r['uom']);
            $excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, number_format($r['outstd_qty_to_intgrte'],4,'.',''));
            $excel->setActiveSheetIndex(0)->setCellValue('L'.$numrow, number_format($r['total_qty_intgrted'],4,'.',''));

            $abjadBegin = 'K';
            for ($i=1; $i <= $max_attempt['max_attempt']; $i++)
            {
                $nextAbjad = ++$abjadBegin;
                $nextTwoAbjad = ++$nextAbjad;
                $nextThreeAbjad = ++$nextTwoAbjad;

                if (count($attempts) === 0) {
                    $excel->setActiveSheetIndex(0)->setCellValue($nextAbjad.$numrow, '');
                    $excel->setActiveSheetIndex(0)->setCellValue($nextTwoAbjad.$numrow, '');
                    $excel->setActiveSheetIndex(0)->setCellValue(++$nextTwoAbjad.$numrow, '');
                }

                if (count($attempts) > 0) 
                {
                    foreach ($attempts as $attempt)
                    {
                        if ($i == $attempt['id_issue_h_intgrtn']) {
                            $excel->setActiveSheetIndex(0)->setCellValue($nextAbjad.$numrow, $attempt['doc_no']);
                            $excel->setActiveSheetIndex(0)->setCellValue($nextTwoAbjad.$numrow, $attempt['doc_date'] ? date("d-m-Y",strtotime($attempt['doc_date'])) : '');
                            $excel->setActiveSheetIndex(0)->setCellValue(++$nextTwoAbjad.$numrow, number_format($attempt['qty_integrate'],4,'.',''));
                        }
                    }
                }

                $abjadBegin = ++$nextAbjad;
            }

            $numrow++;
        }
    
        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);
        // Set orientasi kertas jadi LANDSCAPE
        $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        // Set judul file excel nya
        $excel->getActiveSheet(0)->setTitle("Good Issue Report");
        $excel->setActiveSheetIndex(0);
        // Proses file excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Good Issue Report.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');
        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');
    }
}
?>