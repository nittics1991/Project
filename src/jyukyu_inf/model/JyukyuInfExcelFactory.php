<?php
/**
*   factory
*
*   @version 180911
*/
namespace jyukyu_inf\model;

use \PDO;
use Concerto\excel\excel\ExcelManager;
use Concerto\standard\FileOperation;
use jyukyu_inf\model\JyukyuInfDispControllerModel;
use jyukyu_inf\model\JyukyuInfDispFactory;
use jyukyu_inf\model\JyukyuInfExcelBuilder;
use jyukyu_inf\model\JyukyuInfExcelControllerModel;
use jyukyu_inf\model\QueryJyukyuInfDisp;

class JyukyuInfExcelFactory
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
        return new JyukyuInfDispControllerModel(
            (new JyukyuInfDispFactory($this->getPdo()))
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
        return new JyukyuInfExcelBuilder(
            $this->getControllerModel(),
            $this->getDispControllerModel()
        );
    }
    
    
    
    public function getQuery()
    {
        return new QueryJyukyuInfDisp();
    }
    
    public function getControllerModel()
    {
        return new JyukyuInfExcelControllerModel($this);
    }
}
