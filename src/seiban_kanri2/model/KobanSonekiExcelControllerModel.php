<?php
/**
*   Controller Model
*
*   @version 171011
*/
namespace seiban_kanri2\model;

use Concerto\CookieEnv;
use Concerto\FiscalYear;
use Concerto\standard\ControllerModel;
use seiban_kanri2\model\KobanSonekiExcelFactory;

class KobanSonekiExcelControllerModel extends ControllerModel
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
    *   @param KobanSonekiExcelFactory $factory
    */
    public function __construct(KobanSonekiExcelFactory $factory)
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
            $this->session->no_cyu = $query->no_cyu?? '';
            $this->session->fg_koban = $query->fg_koban?? '';
            $this->session->fg_project = $query->fg_project?? '';
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
        
        $builder = $this->factory->getExcelBuilder(
            $this->session->no_cyu
        );
        $excel = $this->factory->getExcelManager($template);
        
        $input_code = $this->globalSession->input_code;
        $file = "{$tmp}\\{$input_code}_{$this->session->no_cyu}"
            . pathinfo($template, PATHINFO_FILENAME)
            . date('Ymd_His') . '.'. pathinfo($template, PATHINFO_EXTENSION);
        $excel->rename($file);
        
        $excel->report($builder);
        
        return $file;
    }
}
