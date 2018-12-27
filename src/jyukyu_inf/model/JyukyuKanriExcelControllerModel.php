<?php
/**
*   Controller Model
*
*   @version 180918
*/
namespace jyukyu_inf\model;

use Concerto\standard\ControllerModel;
use jyukyu_inf\model\JyukyuKanriExcelFactory;

class JyukyuKanriExcelControllerModel extends ControllerModel
{
    /**
    *   名前空間
    *
    *   @val string
    */
    protected $namespace = 'jyukyu_inf';
    
    /**
    *   コンストラクタ
    *
    *   @param JyukyuKanriExcelFactory $factory
    */
    public function __construct(JyukyuKanriExcelFactory $factory)
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
            
            $this->session->chk_nendo_all = is_null($query->chk_nendo_all)?
                $this->session->chk_nendo_all
                :($query->chk_nendo_all)? '1':'';
            $this->session->chk_kansei = is_null($query->chk_kansei)?
                $this->session->chk_kansei
                :($query->chk_kansei)? '1':'';
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
        
        $input_code    = $this->globalSession->input_code;
        $file = "{$tmp}\\{$input_code}"
            . pathinfo($template, PATHINFO_FILENAME)
            . date('Ymd_His') . '.'. pathinfo($template, PATHINFO_EXTENSION);
        $excel->rename($file);
        
        $excel->report($builder);
        
        return $file;
    }
}
