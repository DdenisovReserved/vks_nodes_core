<?php

class Reporter_controller extends Controller
{

    public function index()
    {
        $this->render('reporter/index');
    }

    public function collect()
    {
        $vc = new Vks_controller();
        $request = $this->request->request;
//        dump($request);
        $this->validator->validate([
            'Начало' => [$request->get('dates')['start'], 'required|date'],
            'Окончание' => [$request->get('dates')['end'], 'required|date'],
        ]);
        if (!$this->validator->passes()) {
            $this->putUserDataAtBackPack($this->request);
            App::$instance->MQ->setMessage($this->validator->errors()->all(), 'danger');
            ST::redirect("back");
        }


        $start = date_create($request->get('dates')['start']);
        $end = date_create($request->get('dates')['end']);
        if ($start > $end) {
            $this->putUserDataAtBackPack($this->request);
            App::$instance->MQ->setMessage("Дата начала больше даты окончания", 'danger');
            ST::redirect("back");
        }
        if (date_diff($end, $start)->days > 90) {
            $this->putUserDataAtBackPack($this->request);
            App::$instance->MQ->setMessage("Задан слишком большой диапазон для выборки, максимум 60 дней", 'danger');
            ST::redirect("back");
        }

        //prepare filter
        $statuses = [];
        if ($request->has('filter')
            && isset($request->get('filter')['status'])
            && count($request->get('filter')['status'])
            ) {
            foreach ($request->get('filter')['status'] as $status=>$indicator)
            $statuses[] = $status;
        }
//        dump($statuses);
        $collectVkses = [];
        $collectVksesRaw = Vks::where('start_date_time', '>=', $start)
            ->where('start_date_time', "<=", $end)
            ->whereIn('status', $statuses)
            ->full()->get();
        if (count($collectVksesRaw)) {
            foreach ($collectVksesRaw as $vks)
                $collectVkses[] = $vc->humanize($vks);
        } else {
            $this->putUserDataAtBackPack($this->request);
            App::$instance->MQ->setMessage("ВКС в запрашиваемом периоде не найдено, попробуйте изменить запрос", 'info');
            ST::redirect("back");
        }
//        dump($collectVkses);
        $this->makeExcel($collectVkses);



    }


    public function makeExcel(array $collectVkses)
    {
        /** Error reporting */
        error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);

        if (PHP_SAPI == 'cli')
            die('This example should only be run from a Web Browser');

        /** Include PHPExcel */
        require_once CORE_REPOSITORY_REAL_PATH . 'phpExcel/PHPExcel.php';


// Create new PHPExcel object
        $objPHPExcel = new PHPExcel();

// Set document properties
        $objPHPExcel->getProperties()->setCreator("Vks")
            ->setLastModifiedBy("Vks")
            ->setTitle("Отчет о созданных ВКС")
            ->setSubject("Отчет о созданных ВКС")
            ->setDescription("")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("VKS REPORT");

// Add some data
        $number = '1';
        $letter = 'A';
        $sheet = $objPHPExcel->getActiveSheet();
        $sheet->getRowDimension($number)->setRowHeight(40);

        $sheet->getColumnDimension($letter)->setWidth(5);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letter++.$number, 'id');
        $sheet->getColumnDimension($letter)->setWidth(25);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letter++.$number, 'Название');
        $sheet->getColumnDimension($letter)->setWidth(15);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letter++.$number, 'Дата');
        $sheet->getColumnDimension($letter)->setWidth(15);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letter++.$number, 'Начало');
        $sheet->getColumnDimension($letter)->setWidth(15);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letter++.$number, 'Окончание');
        $sheet->getColumnDimension($letter)->setWidth(25);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letter++.$number, 'Подразделение');
        $sheet->getColumnDimension($letter)->setWidth(25);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letter++.$number, 'Заказчик ФИО');
        $sheet->getColumnDimension($letter)->setWidth(25);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letter++.$number, 'Заказчик тел.');
        $sheet->getColumnDimension($letter)->setWidth(25);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letter++.$number, 'Заказчик почта');
        $sheet->getColumnDimension($letter)->setWidth(25);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letter++.$number, 'Статус');
        $sheet->getColumnDimension($letter)->setWidth(25);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letter++.$number, 'Тип');
        $sheet->getColumnDimension($letter)->setWidth(15);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letter++.$number, 'Участники с раб. мест');
        $sheet->getColumnDimension($letter)->setWidth(5);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letter++.$number, 'Участники из справочника');
        $sheet->getColumnDimension($letter)->setWidth(5);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letter++.$number, 'Владелец');
        $sheet->getColumnDimension($letter)->setWidth(25);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letter++.$number, 'Утвердил админ');
        $sheet->getColumnDimension($letter)->setWidth(5);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letter++.$number, 'Запись ВКС');
        $sheet->getColumnDimension($letter)->setWidth(5);

        $style = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            )
        );

        $sheet->getStyle("A1:Z500")->applyFromArray($style);




        $number = '2';
        foreach($collectVkses as $vks) {
            $letter = 'A'; //always start at A
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letter++.$number, $vks->id);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letter++.$number, $vks->title);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letter++.$number, $vks->humanized->date);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letter++.$number, $vks->humanized->startTime);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letter++.$number, $vks->humanized->endTime);
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue($letter++.$number, $vks->department_rel ?
                    $vks->department_rel->prefix.". ".$vks->department_rel->name : '-'
                );
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letter++.$number, $vks->init_customer_fio);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letter++.$number, $vks->init_customer_phone);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letter++.$number, $vks->init_customer_mail);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letter++.$number, $vks->humanized->status);

            $type = '';
                $type .= $vks->is_simple ? 'Простая': 'Стандартная';
                $type .= $vks->other_tb_required ? '+ТБ-ТБ' : '';
                $type .= !$vks->other_tb_required && $vks->link_ca_vks_id ? '+По приглашению ЦА' : '';


            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letter++.$number,$type);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letter++.$number, $vks->in_place_participants_count);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letter++.$number, count($vks->participants));
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letter++.$number, $vks->owner ? $vks->owner->login : '-');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letter++.$number,
                $vks->approver ? $vks->approver->login : "-");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letter++.$number, $vks->record_required ? 'да': 'нет');

            $number++;
        }

// Miscellaneous glyphs, UTF-8
//        $objPHPExcel->setActiveSheetIndex(0)
//            ->setCellValue('A4', 'Miscellaneous glyphs')
//            ->setCellValue('A5', 'Что насчет русских букв!');

// Rename worksheet
//        $objPHPExcel->getActiveSheet()->setTitle('Simple');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="vks_report'.date_create()->getTimestamp().'.xlsx"');
        header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        ob_end_clean();
        $objWriter->save('php://output');
        ST::redirect('Report/index');
    }
}