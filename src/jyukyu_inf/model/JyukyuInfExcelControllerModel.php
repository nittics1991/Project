<?php
/**
*   Controller Model
*
*   @version 180911
*/
namespace jyukyu_inf\model;

use Concerto\standard\ControllerModel;
use jyukyu_inf\model\JyukyuInfExcelFactory;

class JyukyuInfExcelControllerModel extends ControllerModel
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
    *   @param JyukyuInfExcelFactory $factory
    */
    public function __construct(JyukyuInfExcelFactory $factory)
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
            $this->session->no_cyu = $query->no_cyu??
                $this->session->no_cyu;
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
