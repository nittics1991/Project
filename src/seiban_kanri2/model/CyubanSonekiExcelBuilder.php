<?php
/**
*   excel builder
*
*   @version 180807
*/
namespace seiban_kanri2\model;

use Concerto\excel\excel\ExcelBuilderADO;
use seiban_kanri2\model\CyubanSonekiExcelModel;
use seiban_kanri2\model\CyubanSonekiExcelControllerModel;
use seiban_kanri2\model\CyubanSonekiDispControllerModel;
use seiban_kanri2\model\CyubanSonekiChartControllerModel;

class CyubanSonekiExcelBuilder extends ExcelBuilderADO
{
    /**
    *   object
    *
    *   @val object
    */
    private $model;
    private $controller;
    private $dispController;
    private $chartController;
    
    /**
    *   コンストラクタ
    *
    *   @param CyubanSonekiExcelModel $model
    *   @param CyubanSonekiExcelControllerModel $controller
    *   @param CyubanSonekiDispControllerModel $dispController
    *   @param CyubanSonekiChartControllerModel $chartController
    */
    public function __construct(
        CyubanSonekiExcelModel $model,
        CyubanSonekiExcelControllerModel $controller,
        CyubanSonekiDispControllerModel $dispController,
        CyubanSonekiChartControllerModel $chartController
    ) {
        $this->model = $model;
        $this->controller = $controller;
        parent::__construct($controller->temp_dir);
        
        $this->dispController = $dispController;
        $this->chartController = $chartController;
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
        $sheet = $book->Worksheets('集計');
        $this->ado($sheet, [$this, 'makeSheet1a']);
        $this->ado($sheet, [$this, 'makeSheet1b']);
        $this->fitColumnWidth($sheet);
        
        //シート2
        $sheet = $book->Worksheets('注番別 (予測)');
        $this->ado($sheet, [$this, 'makeSheet2']);
        $this->setRowAttribute($sheet);
        $this->fitColumnWidth($sheet);
        
        //シート3
        $sheet = $book->Worksheets('項番別 (予測)');
        $this->ado($sheet, [$this, 'makeSheet3']);
        $this->setRowAttribute($sheet);
        $this->fitColumnWidth($sheet);
        
        //シート4
        $sheet = $book->Worksheets('注番別 (実績)');
        $this->ado($sheet, [$this, 'makeSheet4']);
        $this->setRowAttribute($sheet);
        $this->fitColumnWidth($sheet);
        
        //シート5
        $sheet = $book->Worksheets('項番別 (実績)');
        $this->ado($sheet, [$this, 'makeSheet5']);
        $this->setRowAttribute($sheet);
        $this->fitColumnWidth($sheet);
        
        //シート6
        $sheet = $book->Worksheets('製番情報');
        $this->ado($sheet, [$this, 'makeSheet6']);
        $this->fitColumnWidth($sheet);
        
        //シート7
        $sheet = $book->Worksheets('分析グラフ');
        $this->ado($sheet, [$this, 'makeSheet7']);
        $this->fitColumnWidth($sheet);
        
        //シート8
        $sheet = $book->Worksheets('注番別 (計画)');
        $this->ado($sheet, [$this, 'makeSheet8']);
        $this->setRowAttribute($sheet);
        $this->fitColumnWidth($sheet);
        
        //シート9
        $sheet = $book->Worksheets('項番別 (計画)');
        $this->ado($sheet, [$this, 'makeSheet9']);
        $this->setRowAttribute($sheet);
        $this->fitColumnWidth($sheet);
        
        //シート10
        $sheet = $book->Worksheets('計画');
        $this->ado($sheet, [$this, 'makeSheet10']);
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
        
        $items = [
            $yn_psp, $yn_ptov, $yn_parari, $yn_pcyunyu,
            $yn_psoneki, $ri_psoneki,
            $tm_pcyokka, $yn_pcyokka, $yn_pcyokuzai,
            $yn_petc, $yn_pryohi
        ];
        $this->write($items);
        
        $items = [
            $yn_ysp, $yn_ytov, $yn_yarari, $yn_ycyunyu,
            $yn_ysoneki, $ri_ysoneki,
            $tm_ycyokka, $yn_ycyokka, $yn_ycyokuzai,
            $yn_yetc, $yn_yryohi
        ];
        $this->write($items);
        
        $items = [
            $yn_rsp, $yn_rtov, $yn_rarari, $yn_rcyunyu,
            $yn_rsoneki, $ri_rsoneki,
            $tm_rcyokka, $yn_rcyokka, $yn_rcyokuzai,
            $yn_retc, $yn_rryohi
        ];
        $this->write($items);
        
        $items = [
            '', $yn_atov, '', $yn_acyunyu,
            $yn_asoneki, $ri_asoneki,
            $tm_acyokka, $yn_acyokka, $yn_acyokuzai,
            $yn_aetc, $yn_aryohi
        ];
        $this->write($items);
        
        $items = [
            '', $yn_btov, '', $yn_bcyunyu,
            $yn_bsoneki, $ri_bsoneki,
            $tm_bcyokka, $yn_bcyokka, $yn_bcyokuzai,
            $yn_betc, $yn_bryohi
        ];
        $this->write($items);
        
        $items = [
            '', $yn_ctov, '', $yn_ccyunyu,
            $yn_csoneki, $ri_csoneki,
            $tm_ccyokka, $yn_ccyokka, $yn_ccyokuzai,
            $yn_cetc, $yn_cryohi
        ];
        $this->write($items);
        
        return $sheet->range("B2");
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
        
        $items = [$yn_yosan, $yn_soneki, $ri_soneki];
        $this->write($items);
        
        $items = [$yn_yosan_sub, $yn_soneki_sub, $ri_soneki_sub];
        $this->write($items);
        
        $items = [$yn_yosan_rt, $yn_soneki_rt, $ri_soneki_rt];
        $this->write($items);
        
        return $sheet->range("O2");
    }
    
    /**
    *   データ作成シート2
    *
    *   @param Worksheet $sheet EXCEL SHEET
    *   @return Range EXCEL RANGE
    */
    protected function makeSheet2($sheet)
    {
        return $this->makeCyubanSheet($sheet, '2');
    }
    
    /**
    *   データ作成シート3
    *
    *   @param Worksheet $sheet EXCEL SHEET
    *   @return Range EXCEL RANGE
    */
    protected function makeSheet3($sheet)
    {
        return $this->makeKobanSheet($sheet, 2);
    }
    
    /**
    *   データ作成シート4
    *
    *   @param Worksheet $sheet EXCEL SHEET
    *   @return Range EXCEL RANGE
    */
    protected function makeSheet4($sheet)
    {
        return $this->makeCyubanSheet($sheet, 1);
    }
    
    /**
    *   データ作成シート5
    *
    *   @param Worksheet $sheet EXCEL SHEET
    *   @return Range EXCEL RANGE
    */
    protected function makeSheet5($sheet)
    {
        return $this->makeKobanSheet($sheet, 1);
    }
    
    /**
    *   データ作成シート6
    *
    *   @param Worksheet $sheet EXCEL SHEET
    *   @return Range EXCEL RANGE
    */
    protected function makeSheet6($sheet)
    {
        $dataset = $this->dispController->buildGridData();
        $all_items = $dataset['data'];
        
        foreach ($all_items as $items) {
            unset($items['cd_url']);
            unset($items['kb_uriage']);
            unset($items['kb_keikaku']);
            unset($items['fg_caution']);
            unset($items['fg_unapproved']);
            $this->write($items);
        }
        return $sheet->range("A2");
    }
    
    /**
    *   データ作成シート7
    *
    *   @param Worksheet $sheet EXCEL SHEET
    *   @return Range EXCEL RANGE
    */
    protected function makeSheet7($sheet)
    {
        $charts = $this->chartController->buildChart();
        
        $chart = dirname(__DIR__)
            . mb_ereg_replace('/', '\\', $charts['image1']);
        if (file_exists($chart)) {
            $pic = $sheet->Shapes->AddPicture(
                $chart,
                false,
                true,
                0,
                0,
                0,
                0
            );
            $pic->ScaleHeight(1, -1);
            $pic->ScaleWidth(1, -1);
        }
        
        $chart = dirname(__DIR__)
            . mb_ereg_replace('/', '\\', $charts['image2']);
        if (file_exists($chart)) {
            $pic = $sheet->Shapes->AddPicture(
                $chart,
                false,
                true,
                0,
                620,
                0,
                0
            );
            $pic->ScaleHeight(1, -1);
            $pic->ScaleWidth(1, -1);
        }
        
        $chart =dirname(__DIR__)
            .  mb_ereg_replace('/', '\\', $charts['image3']);
        if (file_exists($chart)) {
            $pic = $sheet->Shapes->AddPicture(
                $chart,
                false,
                true,
                0,
                1240,
                0,
                0
            );
            $pic->ScaleHeight(1, -1);
            $pic->ScaleWidth(1, -1);
        }
        
        $chart = dirname(__DIR__)
            . mb_ereg_replace('/', '\\', $charts['image4']);
        if (file_exists($chart)) {
            $pic = $sheet->Shapes->AddPicture(
                $chart,
                false,
                true,
                0,
                1860,
                0,
                0
            );
            $pic->ScaleHeight(1, -1);
            $pic->ScaleWidth(1, -1);
        }
    }
    
    /**
    *   データ作成シート8
    *
    *   @param Worksheet $sheet EXCEL SHEET
    *   @return Range EXCEL RANGE
    */
    protected function makeSheet8($sheet)
    {
        return $this->makeCyubanSheet($sheet, 0);
    }
    
    /**
    *   データ作成シート9
    *
    *   @param Worksheet $sheet EXCEL SHEET
    *   @return Range EXCEL RANGE
    */
    protected function makeSheet9($sheet)
    {
        return $this->makeKobanSheet($sheet, 0);
    }
    
    /**
    *   データ作成シート10
    *
    *   @param Worksheet $sheet EXCEL SHEET
    *   @return Range EXCEL RANGE
    */
    protected function makeSheet10($sheet)
    {
        $dataset = $this->controller->getKeikakuList();
        
        foreach ($dataset as $items) {
            $this->write($items);
        }
        return $sheet->range("B2");
    }
    
    /**
    *   注番シート作成
    *
    *   @param Workbook $sheet EXCEL SHEET
    *   @param integer $type 0:計画 1:実績 2:予測
    *   @return Range ワークブックRange
    */
    protected function makeCyubanSheet($sheet, $type)
    {
        $kb_nendo  = $this->dispController->kb_nendo;
        $cd_bumon  = $this->dispController->cd_bumon;
        
        $cyuban_list = $this->model->getCyubanList2(
            $kb_nendo,
            $cd_bumon,
            null,
            null,
            null,
            null
        );
        $cyumon_list = $this->model->getCyumonKigoList();
        
        $tyousei_list = $this->model->getTyouseiCyubanList(
            $kb_nendo,
            $cd_bumon
        );
        $key_map = array();
        
        array_walk($tyousei_list, function (&$list, $index) use (&$key_map) {
            $key_map[] = $list['id'] = $list['no_cyu'];
        });
        
        $dt_puriage_old = '';
        
        for ($i = 0; $i < 13; $i++) {
            $subtotal[$i]  = 0;
            $total[$i] = 0;
        }
        
        foreach ((array)$cyuban_list as $list) {
            if (($dt_puriage_old != '')
                && ($dt_puriage_old != $list['dt_puriage'])
            ) {
                $items = ['', '', '', '', '',
                    '', '', '小計',
                    $subtotal[12], $subtotal[0], $subtotal[1],
                    $subtotal[2], $subtotal[3],
                    $subtotal[4], $subtotal[5], $subtotal[6],
                    $subtotal[7], $subtotal[8],
                    '',
                    '',
                    $subtotal[9],
                    '',
                    $subtotal[10], $subtotal[11]
                ];
                
                $this->write($items);
                
                for ($i = 0; $i < 13; $i++) {
                    $subtotal[$i]  = 0;
                }
            }
            
            $no_cyu = $list['no_cyu'];
            $nm_syohin = $list['nm_syohin'];
            $nm_user = $list['nm_user'];
            $nm_setti  = $list['nm_setti'];
            $dt_puriage = $list['dt_puriage'];
            $kb_cyumon = $cyumon_list[$list['kb_cyumon']];
            $yn_sp = ($list['yn_sp'] == null)?  0:($list['yn_sp']);
            $yn_tov = ($list['yn_tov'] == null)?  0:($list['yn_tov']);
            $no_cyumon = $list['u_chu_no'];
            $no_mitumori = $list['mitu_no'];
            $no_seizo = $list['u_sei_no'];
            $nm_kisyu = $list['nm_kisyu'];
            $nm_bunya_eigyo = $list['nm_bunya_eigyo'];
            $nm_bunya_seizo = $list['nm_bunya_seizo'];
            
            switch ($type) {
                case 0:
                    $tm_cyokka = $list['tm_pcyokka']?? 0.00;
                    $yn_cyokka = $list['yn_pcyokka']?? 0;
                    $yn_cyokuzai = $list['yn_pcyokuzai']?? 0;
                    $yn_ryohi = $list['yn_pryohi']??  0;
                    $yn_etc = $list['yn_petc']?? 0;
                    $yn_cyunyu = $list['yn_pcyunyu']?? 0;
                    $yn_soneki = $list['yn_psoneki']?? 0;
                    $ri_soneki = $list['ri_psoneki']?? 0.0;
                    break;
                case 1:
                    $tm_cyokka = $list['tm_rcyokka']?? 0.00;
                    $yn_cyokka = $list['yn_rcyokka']?? 0;
                    $yn_cyokuzai = $list['yn_rcyokuzai']?? 0;
                    $yn_ryohi = $list['yn_rryohi']??  0;
                    $yn_etc = $list['yn_retc']?? 0;
                    $yn_cyunyu = $list['yn_rcyunyu']?? 0;
                    $yn_soneki = $list['yn_rsoneki']?? 0;
                    $ri_soneki = $list['ri_rsoneki']?? 0.0;
                    break;
                case 2:
                    $tm_cyokka = $list['tm_ycyokka']?? 0.00;
                    $yn_cyokka = $list['yn_ycyokka']?? 0;
                    $yn_cyokuzai = $list['yn_ycyokuzai']?? 0;
                    $yn_ryohi = $list['yn_yryohi']??  0;
                    $yn_etc = $list['yn_yetc']?? 0;
                    $yn_cyunyu = $list['yn_ycyunyu']?? 0;
                    $yn_soneki = $list['yn_ysoneki']?? 0;
                    $ri_soneki = $list['ri_ysoneki']?? 0.0;
                    break;
            }
            
            $fg_ttov = '';
            $fg_tsoneki = '';
            $yn_ttov = $yn_tov;
            $yn_tsoneki = $yn_soneki;
            
            if (($pos = array_search($no_cyu, $key_map)) !== false) {
                if ($tyousei_list[$pos]['yn_ttov'] != null) {
                    $fg_ttov = '○';
                    $yn_ttov = $tyousei_list[$pos]['cal_ttov'];
                }
                
                if ($tyousei_list[$pos]['yn_tsoneki'] != null) {
                    $fg_tsoneki = '○';
                    
                    switch ($type) {
                        case 0:
                            $yn_tsoneki = $tyousei_list[$pos]['cal_tpsoneki'];
                            break;
                        case 1:
                            $yn_tsoneki = $tyousei_list[$pos]['cal_trsoneki'];
                            break;
                        case 2:
                            $yn_tsoneki = $tyousei_list[$pos]['cal_tysoneki'];
                            break;
                    }
                }
            }
            
            $ri_tsoneki = (empty($yn_ttov))?
                0.0:sprintf('%4.1f', round(($yn_tsoneki / $yn_ttov) * 100, 1));
            
            $items = [$no_cyu, $kb_cyumon,
                $nm_syohin, $nm_setti ,$nm_user,
                $no_mitumori, $no_cyumon, $no_seizo,
                $yn_sp, $yn_tov, $yn_cyunyu ,$yn_soneki, $ri_soneki,
                $tm_cyokka, $yn_cyokka, $yn_cyokuzai, $yn_etc, $yn_ryohi,
                $dt_puriage,
                $fg_ttov,
                $yn_ttov,
                $fg_tsoneki,
                $yn_tsoneki, $ri_tsoneki,
                $nm_kisyu, $nm_bunya_eigyo, $nm_bunya_seizo
            ];
            
            $this->write($items);
            
            $dt_puriage_old = $dt_puriage;
            $subtotal[0] += $yn_tov;
            $subtotal[1] += $yn_cyunyu;
            $subtotal[2] += $yn_soneki;
            $subtotal[3] = (empty($subtotal[0]))?
                0:($subtotal[2] / $subtotal[0]) * 100;
            $subtotal[4] += $tm_cyokka;
            $subtotal[5] += $yn_cyokka;
            $subtotal[6] += $yn_cyokuzai;
            $subtotal[7] += $yn_etc;
            $subtotal[8] += $yn_ryohi;
            $subtotal[9] += $yn_ttov;
            $subtotal[10]   += $yn_tsoneki;
            $subtotal[11]  = (empty($subtotal[9]))?
                0:($subtotal[10] / $subtotal[9]) * 100;
            $subtotal[12]   += $yn_sp;
            
            $total[0]   += $yn_tov;
            $total[1]   += $yn_cyunyu;
            $total[2]   += $yn_soneki;
            $total[3]  = (empty($subtotal[0]))?
                0:($total[2] / $total[0]) * 100;
            $total[4]   += $tm_cyokka;
            $total[5]   += $yn_cyokka;
            $total[6]   += $yn_cyokuzai;
            $total[7]   += $yn_etc;
            $total[8]   += $yn_ryohi;
            $total[9]   += $yn_ttov;
            $total[10]  += $yn_tsoneki;
            $total[11] = (empty($total[9]))?
                0:($total[10] / $total[9]) * 100;
            $total[12]  += $yn_sp;
        }
        
        $items = ['', '', '', '', '',
            '', '' , '小計',
            $subtotal[12], $subtotal[0], $subtotal[1],
            $subtotal[2], $subtotal[3],
            $subtotal[4], $subtotal[5], $subtotal[6],
            $subtotal[7], $subtotal[8],
            '',
            '',
            $subtotal[9],
            '',
            $subtotal[10] ,$subtotal[11]
        ];
        
        $this->write($items);
        
        $items = ['', '', '', '', '',
            '', '' , '合計',
            $total[12], $total[0], $total[1] ,$total[2], $total[3],
            $total[4], $total[5], $total[6], $total[7], $total[8],
            '',
            '',
            $total[9],
            '',
            $total[10] ,$total[11]
        ];
        
        $this->write($items);
        return $sheet->range("A2");
    }
    
    /**
    *   項番シート作成
    *
    *   @param Workbook $sheet EXCEL SHEET
    *   @param integer $type 0:計画 1:実績 2:予測
    *   @return Range ワークブックRange
    */
    protected function makeKobanSheet($sheet, $type)
    {
        $kb_nendo  = $this->dispController->kb_nendo;
        $cd_bumon  = ($this->dispController->cd_bumon == 'all')?
            null:$this->dispController->cd_bumon;
        
        $cyuban_list = $this->model->getKobanList2(
            $kb_nendo,
            $cd_bumon,
            null,
            null,
            null,
            null
        );
        $cyumon_list = $this->model->getCyumonKigoList();
        
        $tyousei_list = $this->model->getTyouseiKobanList(
            $kb_nendo,
            $cd_bumon
        );
        $key_map = array();
        
        array_walk($tyousei_list, function (&$list, $index) use (&$key_map) {
            $key_map[] = $list['id'] = "{$list['no_cyu']}{$list['no_ko']}";
        });
        
        $dt_puriage_old = '';
        
        for ($i = 0; $i < 13; $i++) {
            $subtotal[$i]  = 0;
            $total[$i] = 0;
        }
        
        foreach ((array)$cyuban_list as $list) {
            if (($dt_puriage_old != '')
                && ($dt_puriage_old != $list['dt_puriage'])
            ) {
                $items = ['', '', '', '', '',
                    '',
                    '', '' , '小計',
                    $subtotal[12], $subtotal[0], $subtotal[1],
                    $subtotal[2], $subtotal[3],
                    $subtotal[4], $subtotal[5], $subtotal[6],
                    $subtotal[7], $subtotal[8],
                    '',
                    '',
                    $subtotal[9],
                    '',
                    $subtotal[10] ,$subtotal[11]
                ];
                
                $this->write($items);
                
                for ($i = 0; $i < 13; $i++) {
                    $subtotal[$i]  = 0;
                }
            }
            
            $no_cyu = $list['no_cyu'];
            $no_ko = $list['no_ko'];
            $nm_syohin = $list['nm_syohin'];
            $nm_user = $list['nm_user'];
            $nm_setti  = $list['nm_setti'];
            $dt_puriage = $list['dt_puriage'];
            $kb_cyumon = $cyumon_list[$list['kb_cyumon']];
            $yn_sp = ($list['sp'] == null)? 0:($list['sp']);
            $yn_tov = ($list['yn_tov'] == null)?  0:($list['yn_tov']);
            $no_cyumon = $list['u_chu_no'];
            $no_mitumori = $list['mitu_no'];
            $no_seizo = $list['u_sei_no'];
            $nm_kisyu = $list['nm_kisyu'];
            $nm_bunya_eigyo = $list['nm_bunya_eigyo'];
            $nm_bunya_seizo = $list['nm_bunya_seizo'];
            
            switch ($type) {
                case 0:
                    $tm_cyokka = $list['tm_pcyokka']?? 0.00;
                    $yn_cyokka = $list['yn_pcyokka']?? 0;
                    $yn_cyokuzai = $list['yn_pcyokuzai']?? 0;
                    $yn_ryohi = $list['yn_pryohi']??  0;
                    $yn_etc = $list['yn_petc']?? 0;
                    $yn_cyunyu = $list['yn_pcyunyu']?? 0;
                    $yn_soneki = $list['yn_psoneki']?? 0;
                    $ri_soneki = $list['ri_psoneki']?? 0.0;
                    break;
                case 1:
                    $tm_cyokka = $list['tm_rcyokka']?? 0.00;
                    $yn_cyokka = $list['yn_rcyokka']?? 0;
                    $yn_cyokuzai = $list['yn_rcyokuzai']?? 0;
                    $yn_ryohi = $list['yn_rryohi']??  0;
                    $yn_etc = $list['yn_retc']?? 0;
                    $yn_cyunyu = $list['yn_rcyunyu']?? 0;
                    $yn_soneki = $list['yn_rsoneki']?? 0;
                    $ri_soneki = $list['ri_rsoneki']?? 0.0;
                    break;
                case 2:
                    $tm_cyokka = $list['tm_ycyokka']?? 0.00;
                    $yn_cyokka = $list['yn_ycyokka']?? 0;
                    $yn_cyokuzai = $list['yn_ycyokuzai']?? 0;
                    $yn_ryohi = $list['yn_yryohi']??  0;
                    $yn_etc = $list['yn_yetc']?? 0;
                    $yn_cyunyu = $list['yn_ycyunyu']?? 0;
                    $yn_soneki = $list['yn_ysoneki']?? 0;
                    $ri_soneki = $list['ri_ysoneki']?? 0.0;
                    break;
            }
            
            $nm_syohin2 = $list['nm_syohin2'];
            $no_seiban = $no_cyu . $no_ko;
            
            $fg_ttov = '';
            $fg_tsoneki = '';
            $yn_ttov = $yn_tov;
            $yn_tsoneki = $yn_soneki;
            $nm_biko = '';
            
            if (($pos = array_search($no_seiban, $key_map)) !== false) {
                if ($tyousei_list[$pos]['yn_ttov'] != null) {
                    $fg_ttov = '○';
                    $yn_ttov = $tyousei_list[$pos]['yn_ttov'];
                }
                
                if ($tyousei_list[$pos]['yn_tsoneki'] != null) {
                    $fg_tsoneki = '○';
                    $yn_tsoneki = $tyousei_list[$pos]['yn_tsoneki'];
                }
                
                $nm_biko = $tyousei_list[$pos]['nm_biko'];
            }
            
            $ri_tsoneki = (empty($yn_ttov))?
                0.0:sprintf('%4.1f', round(($yn_tsoneki / $yn_ttov) * 100, 1));
            
            $items = array($no_seiban, $kb_cyumon,
                $nm_syohin, $nm_syohin2, $nm_setti ,$nm_user,
                $no_mitumori, $no_cyumon, $no_seizo,
                $yn_sp, $yn_tov, $yn_cyunyu ,$yn_soneki, $ri_soneki,
                $tm_cyokka, $yn_cyokka, $yn_cyokuzai, $yn_etc, $yn_ryohi,
                $dt_puriage,
                $fg_ttov,
                $yn_ttov,
                $fg_tsoneki,
                $yn_tsoneki, $ri_tsoneki, $nm_biko,
                $nm_kisyu, $nm_bunya_eigyo, $nm_bunya_seizo
            );
            
            $this->write($items);
            
            $dt_puriage_old = $dt_puriage;
            $subtotal[0] += $yn_tov;
            $subtotal[1] += $yn_cyunyu;
            $subtotal[2] += $yn_soneki;
            $subtotal[3] = (empty($subtotal[0]))?
                0:($subtotal[2] / $subtotal[0]) * 100;
            $subtotal[4] += $tm_cyokka;
            $subtotal[5] += $yn_cyokka;
            $subtotal[6] += $yn_cyokuzai;
            $subtotal[7] += $yn_etc;
            $subtotal[8] += $yn_ryohi;
            $subtotal[9] += $yn_ttov;
            $subtotal[10]   += $yn_tsoneki;
            $subtotal[11]  = (empty($subtotal[9]))?
                0:($subtotal[10] / $subtotal[9]) * 100;
            $subtotal[12]   += $yn_sp;
            
            $total[0]   += $yn_tov;
            $total[1]   += $yn_cyunyu;
            $total[2]   += $yn_soneki;
            $total[3]  = (empty($subtotal[0]))?
                0:($total[2] / $total[0]) * 100;
            $total[4]   += $tm_cyokka;
            $total[5]   += $yn_cyokka;
            $total[6]   += $yn_cyokuzai;
            $total[7]   += $yn_etc;
            $total[8]   += $yn_ryohi;
            $total[9]   += $yn_ttov;
            $total[10]  += $yn_tsoneki;
            $total[11] = (empty($total[9]))?
                0:($total[10] / $total[9]) * 100;
            $total[12]  += $yn_sp;
        }
        
        $items = ['', '', '', '', '',
            '',
            '', '' , '小計',
            $subtotal[12], $subtotal[0], $subtotal[1],
            $subtotal[2], $subtotal[3],
            $subtotal[4], $subtotal[5], $subtotal[6],
            $subtotal[7], $subtotal[8],
            '',
            '',
            $subtotal[9],
            '',
            $subtotal[10] ,$subtotal[11]
        ];
        
        $this->write($items);
        
        $items = ['', '', '', '', '',
            '',
            '', '' , '合計',
            $total[12], $total[0], $total[1] ,$total[2], $total[3],
            $total[4], $total[5], $total[6], $total[7], $total[8],
            '',
            '',
            $total[9],
            '',
            $total[10] ,$total[11]
        ];
        
        $this->write($items);
        
        return $sheet->range("A2");
    }
    
    /**
    *   行書式設定
    *
    *   @param Workbook $sheet EXCEL SHEET
    */
    protected function setRowAttribute($sheet)
    {
        $sheet->rows(2)->copy();
        $maxRow = $sheet->range("A1")->SpecialCells(11)->row;
        $sheet->rows("2:{$maxRow}")->PasteSpecial(-4122);
    }
}
