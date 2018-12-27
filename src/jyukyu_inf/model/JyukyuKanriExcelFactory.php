<?php
/**
*   factory
*
*   @version 180918
*/
namespace jyukyu_inf\model;

use \PDO;
use Concerto\excel\excel\ExcelManager;
use Concerto\standard\FileOperation;
use jyukyu_inf\model\JyukyuKanriDispControllerModel;
use jyukyu_inf\model\JyukyuKanriDispFactory;
use jyukyu_inf\model\JyukyuKanriExcelBuilder;
use jyukyu_inf\model\JyukyuKanriExcelControllerModel;
use jyukyu_inf\model\QueryJyukyuKanriDisp;

class JyukyuKanriExcelFactory
{
    /**
    *   pdo
    *
    *   @val PDO
    */
    private $pdo;
    
    /**
    *   __construct
    *
    *   @param PDO $pdo
    */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    
    
    
    public function getPdo()
    {
        return $this->pdo;
    }
    
    
    
    public function getDispControllerModel()
    {
        return new JyukyuKanriDispControllerModel(
            (new JyukyuKanriDispFactory($this->getPdo()))
        );
    }
    
    
    
    public function getExcelManager($template)
    {
        return new ExcelManager($template);
    }
    
    public function getFileOperation()
    {
        return new FileOperation();
    }
    
    public function getExcelBuilder()
    {
        return new JyukyuKanriExcelBuilder(
            $this->getControllerModel(),
            $this->getDispControllerModel()
        );
    }
    
    
    
    public function getQuery()
    {
        return new QueryJyukyuKanriDisp();
    }
    
    public function getControllerModel()
    {
        return new JyukyuKanriExcelControllerModel($this);
    }
}
