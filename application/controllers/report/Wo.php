<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require('./application/third_party/PHPExcel/PHPExcel.php');

class Wo extends CI_Controller {
    public function __construct(){
        parent::__construct();

        $this->load->library('auth');  
		if(!$this->auth->is_logged_in()) {
			redirect(base_url());
        }
        // load model
        $this->load->model('transaksi1/workorder_model', 'wo_model');
        
        $this->load->library('form_validation');
        $this->load->library('l_general');
    }

    public function index(){
        $max['max_attempt'] = $this->wo_model->get_max_attempt_whs();
        $max['filters'] = $this->wo_model->get_filter_status_desc('report');
        $this->load->view("report/wo_view", $max);
    }

    public function showAllData(){
        $kd_plant = $this->session->userdata['ADMIN']['plant'];
        $type = $this->input->post('type');
        $fromDate = $this->input->post('fromDate');
        $toDate = $this->input->post('toDate');
        
        $max_attempt = $this->wo_model->get_max_attempt_whs();

        $wo = $this->wo_model->getDataWoVendor_Header($fromDate, $toDate, $type, $length=false, $start=false, 'report');
        
        $data = array();
        foreach ($wo as $key => $val) {
            $details = $this->wo_model->wo_details_select($val['id_produksi_header']);
			$attemptsHeader = $this->wo_model->get_attempt_header($val['id_produksi_header']);
			$getonhand = $this->wo_model->wo_detail_onhand($val['material_no']);

            $nestedData = array();
			$nestedData['no'] = (int)($key + 1);
			$nestedData['id_produksi_header'] = $val['id_produksi_header'];
            $nestedData['posting_date'] = date("d-m-Y",strtotime($val['posting_date']));
            $nestedData['material_no'] = $val['material_no'];
            $nestedData['material_desc'] = $val['material_desc'];
            $nestedData['plant'] = $val['plant'];
            $nestedData['on_hand'] = number_format($getonhand[0]['OnHand'],4,'.','');
            $nestedData['quantity'] = number_format($val['qty'],4,'.','');
            $nestedData['uom'] = $val['uom'];
            $nestedData['outstd_qty_to_intgrte'] = number_format($val['outstd_qty_to_intgrte_h'],4,'.','');
            $nestedData['total_qty_intgrted'] = number_format($val['total_qty_intgrted_h'],4,'.','');
            $nestedData['status_desc'] = $val['status_desc'];
            $nestedData['id_produksi_detail'] = '';
            for ($i=1; $i <= $max_attempt['max_attempt']; $i++) { 
                if (count($attemptsHeader) === 0) {
                    $nestedData["doc_no_{$i}"] = '';
                    $nestedData["approved_time_{$i}"] = '';
                    $nestedData["qty_integrate_{$i}"] = '';
                    $nestedData['attempt_header'] = 0;
                }
                if (count($attemptsHeader) > 0) {
                    foreach ($attemptsHeader as $attempt_h) {
                        if ($i == $attempt_h['id_produksi_h_intgrtn']) {
                            $nestedData["doc_no_{$i}"] = $attempt_h['doc_no'];
                            $nestedData["approved_time_{$i}"] = $attempt_h['doc_date'] ? date("d-m-Y",strtotime($attempt_h['doc_date'])) : '';
                            $nestedData["qty_integrate_{$i}"] = number_format($attempt_h['qty_integrate'],4,'.','');
                        }
                    }
					$nestedData['attempt_header'] = $attemptsHeader[0]['id_produksi_h_intgrtn'];
                }
            }
            $data[] = $nestedData;
			
			foreach ($details as $idx => $detail) {
				$getonhand = $this->wo_model->wo_detail_onhand($detail['material_no']);
				$attemptsDetail = $this->wo_model->get_attempt_detail($detail['id_produksi_detail']);

				$nestedData['no'] = '';
				$nestedData['id_produksi_header'] = '';
				$nestedData['posting_date'] = (int)($idx + 1);
				$nestedData['material_no'] = $detail['material_no'];
				$nestedData['material_desc'] = $detail['material_desc'];
				$nestedData['plant'] = $val['plant'];
				$nestedData['on_hand'] = number_format($getonhand[0]['OnHand'],4,'.','');
				$nestedData['quantity'] = number_format($detail['qty'],4,'.','');
				$nestedData['uom'] = $detail['uom'];
				$nestedData['outstd_qty_to_intgrte'] = number_format($detail['outstd_qty_to_intgrte'],4,'.','');
				$nestedData['total_qty_intgrted'] = number_format($detail['total_qty_intgrted'],4,'.','');
				$nestedData['id_produksi_detail'] = $detail['id_produksi_header'];
				for ($i=1; $i <= $max_attempt['max_attempt']; $i++) { 
					if (count($attemptsDetail) === 0) {
						$nestedData["doc_no_{$i}"] = '';
						$nestedData["approved_time_{$i}"] = '';
						$nestedData["qty_integrate_{$i}"] = '';
						$nestedData['attempt_detail'] = 0;
					}
					if (count($attemptsDetail) > 0) {
						foreach ($attemptsDetail as $attempt_d) {
                            if ($i == $attempt_d['id_produksi_h_intgrtn']) {
                                $nestedData["doc_no_{$i}"] = $attempt_d['doc_no'];
                                $nestedData["approved_time_{$i}"] = $attempt_d['doc_date'] ? date("d-m-Y",strtotime($attempt_d['doc_date'])) : '';
                                $nestedData["qty_integrate_{$i}"] = number_format($attempt_d['qty_integrate'],4,'.','');
                            }
						}
						$nestedData['attempt_detail'] = $attemptsDetail[0]['id_produksi_h_intgrtn'];
					}
				}
				$data[] = $nestedData;
			}
        }
 
        $json_data = array(
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

        $max_attempt = $this->wo_model->get_max_attempt_whs();
        $wo = $this->wo_model->getDataWoVendor_Header($fromDate, $toDate, $type, $length=false, $start=false, 'report');

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

        $excel->getActiveSheet()->getStyle('A5')->getFont()->setBold(true);
        $excel->getActiveSheet()->getStyle('A6')->getFont()->setBold(true);

        $excel->getActiveSheet()->mergeCells('A5:C5');
        $excel->setActiveSheetIndex(0)->setCellValue('A5', 'Work Order Report'); 
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
        $excel->setActiveSheetIndex(0)->setCellValue('B9', 'ID'); 
        $excel->setActiveSheetIndex(0)->setCellValue('C9', 'Status'); 
        $excel->setActiveSheetIndex(0)->setCellValue('D9', 'Posting Date'); 
        $excel->setActiveSheetIndex(0)->setCellValue('E9', 'Item Code'); 
        $excel->setActiveSheetIndex(0)->setCellValue('F9', 'Item Name'); 
        $excel->setActiveSheetIndex(0)->setCellValue('G9', 'Warehouse'); 
        $excel->setActiveSheetIndex(0)->setCellValue('H9', 'Quantity'); 
        $excel->setActiveSheetIndex(0)->setCellValue('I9', 'UOM'); 
        $excel->setActiveSheetIndex(0)->setCellValue('J9', 'On Hand Qty'); 
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
        foreach($wo as $key=>$r){ 
            $details = $this->wo_model->wo_details_select($r['id_produksi_header']);
			$attemptsHeader = $this->wo_model->get_attempt_header($r['id_produksi_header']);
			$getonhand = $this->wo_model->wo_detail_onhand($r['material_no']);

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
            $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $r['id_produksi_header']);
            $excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $r['status_desc']);
            $excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, date("d-m-Y",strtotime($r['posting_date'])));
            $excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $r['material_no']);
            $excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $r['material_desc']);
            $excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $kd_plant);
            $excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, number_format($getonhand[0]['OnHand'],4,'.',''));
            $excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, number_format($r['qty'],4,'.',''));
            $excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $r['uom']);
            $excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, number_format($r['outstd_qty_to_intgrte_h'],4,'.',''));
            $excel->setActiveSheetIndex(0)->setCellValue('L'.$numrow, number_format($r['total_qty_intgrted_h'],4,'.',''));

            $abjadBeginHeader = 'K';
            for ($i=1; $i <= $max_attempt['max_attempt']; $i++)
            {
                $nextAbjadHeader = ++$abjadBeginHeader;
                $nextTwoAbjadHeader = ++$nextAbjadHeader;
                $nextThreeAbjad = ++$nextTwoAbjadHeader;

                if (count($attemptsHeader) === 0) {
                    $excel->setActiveSheetIndex(0)->setCellValue($nextAbjadHeader.$numrow, '');
                    $excel->setActiveSheetIndex(0)->setCellValue($nextTwoAbjadHeader.$numrow, '');
                    $excel->setActiveSheetIndex(0)->setCellValue(++$nextTwoAbjadHeader.$numrow, '');
                }

                if (count($attemptsHeader) > 0) 
                {
                    foreach ($attemptsHeader as $attempt_h)
                    {
                        if ($i == $attempt_h['id_produksi_h_intgrtn']) {
                            $excel->setActiveSheetIndex(0)->setCellValue($nextAbjadHeader.$numrow, $attempt_h['doc_no']);
                            $excel->setActiveSheetIndex(0)->setCellValue($nextTwoAbjadHeader.$numrow, $attempt_h['doc_date'] ? date("d-m-Y",strtotime($attempt_h['doc_date'])) : '');
                            $excel->setActiveSheetIndex(0)->setCellValue(++$nextTwoAbjadHeader.$numrow, number_format($attempt_h['qty_integrate'],4,'.',''));
                        }
                    }
                }

                $abjadBeginHeader = ++$nextAbjadHeader;
            }

            $numrowDetail = $numrow + 1;
            foreach ($details as $idx => $detail) {
				$getonhand = $this->wo_model->wo_detail_onhand($detail['material_no']);
				$attemptsDetail = $this->wo_model->get_attempt_detail($detail['id_produksi_detail']);

                // applying border style
                $excel->getActiveSheet()->getStyle('A'.$numrowDetail.':AA'.$numrowDetail)->applyFromArray($styleArray);

                // set config alignment body table
                $excel->getActiveSheet()->getStyle('A'.$numrowDetail.':E'.$numrowDetail)->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                $excel->getActiveSheet()->getStyle('G'.$numrowDetail)->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                
                $excel->getActiveSheet()->getStyle('J'.$numrowDetail)->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                $excel->getActiveSheet()->getStyle('H'.$numrowDetail.':I'.$numrowDetail)->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                
                $excel->getActiveSheet()->getStyle('K'.$numrowDetail.':L'.$numrowDetail)->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                $excel->getActiveSheet()->mergeCells('A'.$numrowDetail.':B'.$numrowDetail);

                $excel->setActiveSheetIndex(0)->setCellValue('C'.$numrowDetail, $r['status_desc']);
                $excel->setActiveSheetIndex(0)->setCellValue('D'.$numrowDetail, (int)($idx + 1));
                $excel->setActiveSheetIndex(0)->setCellValue('E'.$numrowDetail, $detail['material_no']);
                $excel->setActiveSheetIndex(0)->setCellValue('F'.$numrowDetail, $detail['material_desc']);
                $excel->setActiveSheetIndex(0)->setCellValue('G'.$numrowDetail, $kd_plant);
                $excel->setActiveSheetIndex(0)->setCellValue('H'.$numrowDetail, number_format($getonhand[0]['OnHand'],4,'.',''));
                $excel->setActiveSheetIndex(0)->setCellValue('I'.$numrowDetail, number_format($detail['qty'],4,'.',''));
                $excel->setActiveSheetIndex(0)->setCellValue('J'.$numrowDetail, $detail['uom']);
                $excel->setActiveSheetIndex(0)->setCellValue('K'.$numrowDetail, number_format($detail['outstd_qty_to_intgrte'],4,'.',''));
                $excel->setActiveSheetIndex(0)->setCellValue('L'.$numrowDetail, number_format($detail['total_qty_intgrted'],4,'.',''));

                $abjadBeginDetail = 'K';
                for ($i=1; $i <= $max_attempt['max_attempt']; $i++)
                {
                    $nextAbjadDetail = ++$abjadBeginDetail;
                    $nextTwoAbjadDetail = ++$nextAbjadDetail;
                    $nextThreeAbjad = ++$nextTwoAbjadDetail;

                    if (count($attemptsDetail) === 0) {
                        $excel->setActiveSheetIndex(0)->setCellValue($nextAbjadDetail.$numrowDetail, '');
                        $excel->setActiveSheetIndex(0)->setCellValue($nextTwoAbjadDetail.$numrowDetail, '');
                        $excel->setActiveSheetIndex(0)->setCellValue(++$nextTwoAbjadDetail.$numrowDetail, '');
                    }

                    if (count($attemptsDetail) > 0) 
                    {
                        foreach ($attemptsDetail as $attempt_d)
                        {
                            if ($i == $attempt_d['id_produksi_h_intgrtn']) {
                                $excel->setActiveSheetIndex(0)->setCellValue($nextAbjadDetail.$numrowDetail, $attempt_d['doc_no']);
                                $excel->setActiveSheetIndex(0)->setCellValue($nextTwoAbjadDetail.$numrowDetail, $attempt_d['doc_date'] ? date("d-m-Y",strtotime($attempt_d['doc_date'])) : '');
                                $excel->setActiveSheetIndex(0)->setCellValue(++$nextTwoAbjadDetail.$numrowDetail, number_format($attempt_d['qty_integrate'],4,'.',''));
                            }
                        }
                    }

                    $abjadBeginDetail = ++$nextAbjadDetail;
                }

                $numrow = $numrowDetail++;
            }

            $numrow++;
        }
    
        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);
        // Set orientasi kertas jadi LANDSCAPE
        $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        // Set judul file excel nya
        $excel->getActiveSheet(0)->setTitle("Work Order Report");
        $excel->setActiveSheetIndex(0);
        // Proses file excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Work Order Report.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');
        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');
    }
}
?>