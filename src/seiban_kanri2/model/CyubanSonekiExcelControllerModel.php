<?php
/**
*   Controller Model
*
*   @version 171020
*/
namespace seiban_kanri2\model;

use Concerto\CookieEnv;
use Concerto\FiscalYear;
use Concerto\standard\ControllerModel;
use seiban_kanri2\model\CyubanSonekiExcelFactory;

class CyubanSonekiExcelControllerModel extends ControllerModel
{
    /**
    *   名前空間
    *
    *   @val string
    */
    protected $namespace = 'seiban_kanri';
    
    /**
    *   コンストラクタ
    *
    *   @param CyubanSonekiExcelFactory $factory
    */
    public function __construct(CyubanSonekiExcelFactory $factory)
    {
        parent::__construct($factory);
    }
    
    /**
    *   query処理
    *
    */
    public function setQuery()
    {
        $query = $this->factory->getQuery();
        
        if ($query->isValid()) {
            $this->session->kb_nendo = $query->kb_nendo??
                $this->session->kb_nendo;
            $this->session->cd_bumon = $query->cd_bumon??
                $this->session->cd_bumon;
            $this->session->fg_cyuban = $query->fg_cyuban??
                $this->session->fg_cyuban;
        }
    }
    
    /**
    *   EXCEL作成
    *
    *   @param string $template テンプレートファイル
    *   @return string ファイル名
    */
    public function buildExcel($template)
    {
        $tmp = dirname(__DIR__) . '\\tmp';
        
        $fileOperation = $this->factory->getFileOperation();
        $fileOperation->createTempDir($tmp, 1);
        
        $builder = $this->factory->getExcelBuilder();
        $excel = $this->factory->getExcelManager($template);
        
        $input_code = $this->globalSession->input_code;
        $file = "{$tmp}\\{$input_code}_{$this->session->cd_bumon}"
            . pathinfo($template, PATHINFO_FILENAME)
            . date('Ymd_His') . '.'. pathinfo($template, PATHINFO_EXTENSION);
        $excel->rename($file);
        
        $excel->report($builder);
        
        return $file;
    }
    
    /**
    *   計画リスト
    *
    *   @return array
    **/
    public function getKeikakuList()
    {
        $model = $this->factory->getModel();
        
        $cd_bumon = ($this->session->cd_bumon == 'all')?
            null:$this->session->cd_bumon;
        $kb_nendo = $this->session->kb_nendo;
        
        $keikaku_list = $model->getKeikakuList($kb_nendo, $cd_bumon);
        $all_items = [];
        
        foreach ((array)$keikaku_list as $list) {
            $no_cyu = $list['no_cyu'];
            $no_ko = $list['no_ko'];
            $dt_kanjyo = $list['dt_kanjyo'];
            $cd_genka_yoso = $list['cd_genka_yoso'];
            $cd_bumon = $list['cd_bumon'];
            $nm_syohin = $list['nm_syohin'];
            
            if ($cd_genka_yoso == 'B') {
                $nm_cyunyu = $list['cd_tanto'];
                $tm_cyokka = $list['tm_cyokka'];
                $yn_money = $list['yn_cyokka'];
            } else {
                $nm_cyunyu = $list['nm_tanto'];
                $tm_cyokka = 0;
                if ($cd_genka_yoso == 'A') {
                    $yn_money = $list['yn_cyokuzai'];
                } else {
                    $yn_money = $list['yn_etc'];
                }
            }
            $items = compact(
                'no_cyu',
                'no_ko',
                'dt_kanjyo',
                'cd_genka_yoso',
                'cd_bumon',
                'nm_cyunyu',
                'nm_syohin',
                'tm_cyokka',
                'yn_money'
            );
            $all_items[] = $items;
        }
        return $all_items;
    }
}
