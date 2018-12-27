<?php
/**
*   excel builder
*
*   @version 171011
*/
namespace seiban_kanri2\model;

use Concerto\excel\excel\ExcelBuilderADO;
use seiban_kanri2\model\KobanSonekiChartControllerModel;
use seiban_kanri2\model\KobanSonekiDispControllerModel;
use seiban_kanri2\model\KobanSonekiDispCyubanModel;
use seiban_kanri2\model\KobanSonekiExcelControllerModel;
use seiban_kanri2\model\KobanSonekiExcelModel;
use seiban_kanri2\model\chart\KobanSonekiChart1;
use seiban_kanri2\model\chart\KobanSonekiChart2;
use seiban_kanri2\model\chart\KobanSonekiChart2a;
use seiban_kanri2\model\chart\KobanSonekiChart2b;

class KobanSonekiExcelBuilder extends ExcelBuilderADO
{
    /**
    *   object
    *
    *   @val object
    */
    private $model;
    private $controller;
    private $dispController;
    private $dispModel;
    private $chartController;
    private $chartModel;
    
    /**
    *   注番
    *
    *   @val string
    */
    private $no_cyu;
    
    /**
    *   コンストラクタ
    *
    *   @param KobanSonekiExcelModel $model
    *   @param KobanSonekiExcelControllerModel $controller
    *   @param KobanSonekiDispControllerModel $dispController
    *   @param KobanSonekiDispCyubanModel $dispModel
    *   @param KobanSonekiChartControllerModel $chartController
    *   @param KobanSonekiChartModel $chartModel
    *   @param string $no_cyu
    */
    public function __construct(
        KobanSonekiExcelModel $model,
        KobanSonekiExcelControllerModel $controller,
        KobanSonekiDispControllerModel $dispController,
        KobanSonekiDispCyubanModel $dispModel,
        KobanSonekiChartControllerModel $chartController,
        KobanSonekiChartModel $chartModel,
        $no_cyu
    ) {
        $this->model = $model;
        $this->controller = $controller;
        parent::__construct($controller->temp_dir);
        
        $this->dispController = $dispController;
        $this->dispModel = $dispModel;
        $this->chartController = $chartController;
        $this->chartModel = $chartModel;
        $this->no_cyu = $no_cyu;
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
        $sheet = $book->Worksheets('注番情報');
        $this->makeSheet1a($sheet);
        $this->ado($sheet, [$this, 'makeSheet1b']);
        $this->ado($sheet, [$this, 'makeSheet1c']);
        $this->fitColumnWidth($sheet);
        
        //シート2
        $sheet = $book->Worksheets('項番情報');
        $this->ado($sheet, [$this, 'makeSheet2']);
        $this->fitColumnWidth($sheet);
        
        //シート3
        $sheet = $book->Worksheets('計画');
        $this->ado($sheet, [$this, 'makeSheet3']);
        $this->fitColumnWidth($sheet);
        
        //シート4
        $sheet = $book->Worksheets('実績');
        $this->ado($sheet, [$this, 'makeSheet4']);
        $this->fitColumnWidth($sheet);
        
        //シート5
        $sheet = $book->Worksheets('分析グラフ');
        $this->ado($sheet, [$this, 'makeSheet5']);
        $this->fitColumnWidth($sheet);
    }
    
    /**
    *   データ作成シート1a
    *
    *   @param Worksheet $sheet EXCEL SHEET
    *   @return Range EXCEL RANGE
    */
    protected function makeSheet1a($sheet)
    {
        $this->dispController->buildData();
        extract($this->dispController->toArray());
        
        $sheet->Range("B1")->Value = $no_cyu;
        $sheet->Range("N1")->Value = $nm_setti;
        $sheet->Range("B2")->Value = $nm_syohin;
        $sheet->Range("N2")->Value = $nm_user;
    }
    
    /**
    *   データ作成シート1b
    *
    *   @param Worksheet $sheet EXCEL SHEET
    *   @return Range EXCEL RANGE
    */
    protected function makeSheet1b($sheet)
    {
        extract($this->dispController->toArray());
        
        $items = array($yn_sp, $yn_ptov1, $yn_arari,
            $yn_pcyunyu1, $yn_psoneki1, $ri_psoneki1, $tm_pcyokka1,
            $yn_pcyokka1, $yn_pcyokuzai1, $yn_petc1, $yn_pryohi1
        );
        $this->write($items);
        
        $items = array($yn_sp, $yn_ytov1, $yn_arari,
            $yn_ycyunyu1, $yn_ysoneki1, $ri_ysoneki1, $tm_ycyokka1,
            $yn_ycyokka1, $yn_ycyokuzai1, $yn_yetc1, $yn_yryohi1
        );
        $this->write($items);
        
        $items = array($yn_sp, $yn_rtov1, $yn_arari,
            $yn_rcyunyu1, $yn_rsoneki1, $ri_rsoneki1, $tm_rcyokka1,
            $yn_rcyokka1, $yn_rcyokuzai1, $yn_retc1, $yn_rryohi1
        );
        $this->write($items);
        
        return $sheet->range("B4");
    }
    
    /**
    *   データ作成シート1c
    *
    *   @param Worksheet $sheet EXCEL SHEET
    *   @return Range EXCEL RANGE
    */
    protected function makeSheet1c($sheet)
    {
        extract($this->dispController->toArray());
        
        $items = array($yn_sp, $yn_ptov2,  $yn_arari,
            $yn_pcyunyu2, $yn_psoneki2, $ri_psoneki2, $tm_pcyokka2,
            $yn_pcyokka2, $yn_pcyokuzai2, $yn_petc2, $yn_pryohi2
        );
        $this->write($items);
        
        $items = array($yn_sp, $yn_ytov2,  $yn_arari,
            $yn_ycyunyu2, $yn_ysoneki2, $ri_ysoneki2, $tm_ycyokka2,
            $yn_ycyokka2, $yn_ycyokuzai2, $yn_yetc2, $yn_yryohi2
        );
        $this->write($items);
        
        $items = array($yn_sp, $yn_rtov2,  $yn_arari,
            $yn_rcyunyu2, $yn_rsoneki2, $ri_rsoneki2, $tm_rcyokka2,
            $yn_rcyokka2, $yn_rcyokuzai2, $yn_retc2, $yn_rryohi2
        );
        $this->write($items);
        
        return $sheet->range("N4");
    }
    
    /**
    *   データ作成シート2
    *
    *   @param Worksheet $sheet EXCEL SHEET
    *   @return Range EXCEL RANGE
    */
    protected function makeSheet2($sheet)
    {
        $dataset = $this->dispController->buildGridCyubanData();
        $all_items_tmp = $dataset['data'];
        
        $type = array('計画', '実績', '予測');
        
        foreach ($all_items_tmp as $list) {
            $list['nm_type'] = $type[$list['nm_type']];
            unset($list['fg_view']);
            unset($list['fg_tyousei']);
            
            $this->write($list);
        }
        
        return $sheet->range("A2");
    }
    
    /**
    *   データ作成シート3
    *
    *   @param Worksheet $sheet EXCEL SHEET
    *   @return Range EXCEL RANGE
    */
    protected function makeSheet3($sheet)
    {
        $cyunyu_list = $this->model->getCyunyuList($this->no_cyu, 0);
        
        foreach ($cyunyu_list as $list) {
            $no_cyu = $list['no_cyu'];
            $no_ko = $list['no_ko'];
            $dt_kanjyo = $list['dt_kanjyo'];
            $cd_genka_yoso = $list['cd_genka_yoso'];
            $nm_tanto = $list['nm_tanto'];
            $nm_syohin = $list['nm_syohin'];
            $tm_cyokka = $list['tm_cyokka'];
            $yn_cyokka = $list['yn_cyokka'];
            $yn_cyokuzai = $list['yn_cyokuzai'];
            $yn_ryohi = $list['yn_ryohi'];
            $yn_etc = $list['yn_etc'];
            
            $item = [$no_cyu, $no_ko, $dt_kanjyo, $cd_genka_yoso,
                $nm_tanto, $nm_syohin,
                $tm_cyokka, $yn_cyokka ,$yn_cyokuzai, $yn_etc, $yn_ryohi
            ];
            
            $this->write($item);
        }
        
        return $sheet->range("A2");
    }
    
    /**
    *   データ作成シート4
    *
    *   @param Worksheet $sheet EXCEL SHEET
    *   @return Range EXCEL RANGE
    */
    protected function makeSheet4($sheet)
    {
        $cyunyu_list = $this->model->getCyunyuList($this->no_cyu, 1);
        
        foreach ($cyunyu_list as $list) {
            $no_cyu = $list['no_cyu'];
            $no_ko = $list['no_ko'];
            $dt_kanjyo = $list['dt_kanjyo'];
            $cd_genka_yoso = $list['cd_genka_yoso'];
            $nm_tanto = $list['nm_tanto'];
            $nm_syohin = $list['nm_syohin'];
            $tm_cyokka = $list['tm_cyokka'];
            $yn_cyokka = $list['yn_cyokka'];
            $yn_cyokuzai = $list['yn_cyokuzai'];
            $yn_ryohi = $list['yn_ryohi'];
            $yn_etc = $list['yn_etc'];
            $dt_cyunyu = $list['dt_cyunyu'];
            $no_cyumon = $list['no_cyumon'];
            $dt_noki = $list['dt_noki'];
            
            $item = [$no_cyu, $no_ko, $dt_kanjyo, $dt_noki, $dt_cyunyu,
                $no_cyumon, $cd_genka_yoso, $nm_tanto, $nm_syohin,
                $tm_cyokka, $yn_cyokka ,$yn_cyokuzai, $yn_etc, $yn_ryohi
            ];
            
            $this->write($item);
        }
        
        return $sheet->range("A2");
    }
    
    /**
    *   データ作成シート5
    *
    *   @param Worksheet $sheet EXCEL SHEET
    *   @return Range EXCEL RANGE
    */
    protected function makeSheet5($sheet)
    {
        $charts = $this->chartController->buildChart();
        $h = 607;
        
        for ($i = 0; $i < count($charts['images']); $i++) {
            $chart = dirname(__DIR__)
                . mb_ereg_replace('/', '\\', $charts['images'][$i][0]);
            if (file_exists($chart)) {
                $pic = $sheet->Shapes->AddPicture(
                    $chart,
                    false,
                    true,
                    0,
                    $h * $i * 2,
                    0,
                    0
                );
                $pic->ScaleHeight(1, -1);
                $pic->ScaleWidth(1, -1);
            }
            
            $chart = dirname(__DIR__)
                . mb_ereg_replace('/', '\\', $charts['images'][$i][1]);
            if (file_exists($chart)) {
                $pic = $sheet->Shapes->AddPicture(
                    $chart,
                    false,
                    true,
                    0,
                    $h * $i * 2 + $h,
                    0,
                    0
                );
                $pic->ScaleHeight(1, -1);
                $pic->ScaleWidth(1, -1);
            }
        }
        
        $koban_list = $this->dispModel->getKobanList($this->no_cyu);
        
        $this->makeChartData('1');
        $this->makeChartData('2a');
        
        foreach ((array)$koban_list as $list) {
            $this->makeChartData('1', $list['no_ko']);
            $this->makeChartData('2a', $list['no_ko']);
        }
        return $sheet->range("S5");
    }
    
    /**
    *   チャートデータ作成
    *
    *   @param string $id csvファイル
    *   @param string $no_ko
    */
    protected function makeChartData($id, $no_ko = null)
    {
        $template = __DIR__ . '\\chart\\StandardChart.php';
        $file = __DIR__ . '\\tmp\\dummy.bak';
        
        $chartData = $this->getChartDefinition($id, $file, $template);
        $chartData->calcData($this->no_cyu, $no_ko);
        $data_list = $chartData->getTableData();
        
        if (count($data_list) > 0) {
            foreach ($data_list as $list) {
                $this->write($list);
            }
            
            //ダミー行
            $max = ($no == '2')?    38:41;
            for ($i = 0; $i < $max; $i++) {
                $this->write(['']);
            }
        }
    }
    
    /**
    *   getChartDefinition
    *
    *   @param string $id
    *   @param string $file
    *   @param string $template
    *   @return ChartDefinition
    **/
    private function getChartDefinition($id, $file, $template)
    {
        $chartData = "seiban_kanri2\model\chart\KobanSonekiChart{$id}";
        return new $chartData(
            $this->chartModel,
            $file,
            $template
        );
    }
}
