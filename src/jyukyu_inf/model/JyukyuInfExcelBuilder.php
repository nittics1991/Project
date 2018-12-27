<?php
/**
*   excel builder
*
*   @version 180911
*/
namespace jyukyu_inf\model;

use Concerto\excel\excel\ExcelBuilderADO;
use jyukyu_inf\model\JyukyuInfDispControllerModel;
use jyukyu_inf\model\JyukyuInfExcelControllerModel;

class JyukyuInfExcelBuilder extends ExcelBuilderADO
{
    /**
    *   object
    *
    *   @val object
    */
    private $controller;
    private $dispController;
    
    /**
    *   __constructs
    *
    *   @param JyukyuInfExcelControllerModel $controller
    *   @param JyukyuInfExcelModel $dispController
    */
    public function __construct(
        JyukyuInfExcelControllerModel $controller,
        JyukyuInfDispControllerModel $dispController
    ) {
        $this->controller = $controller;
        parent::__construct($controller->temp_dir);
        
        $this->dispController = $dispController;
    }
    
    /**
    *   build
    *
    *   @param EXCEL $excel EXCEL APP
    *   @param Workbook $book EXCEL BOOK
    **/
    public function build($excel, $book)
    {
        //シート1
        $sheet = $book->Worksheets('受給品');
        $this->ado($sheet, [$this, 'makeSheet1']);
        $this->fitColumnWidth($sheet);
    }
    
    /**
    *   データ作成シート1
    *
    *   @param Worksheet $sheet EXCEL SHEET
    *   @return Range EXCEL RANGE
    */
    protected function makeSheet1($sheet)
    {
        $dataset = $this->dispController->buildGridData();
        $all_items_tmp = $dataset['data'];
        
        foreach ($all_items_tmp as $items) {
            unset($items['cd_jyukyu_tanto']);
            $this->write($items);
        }
        return $sheet->range("A2");
    }
}
