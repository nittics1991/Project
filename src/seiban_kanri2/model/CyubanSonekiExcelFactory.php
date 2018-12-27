<?php
/**
*   factory
*
*   @version 180406
*/
namespace seiban_kanri2\model;

use \PDO;
use Concerto\database\CyokkaMonKeikaku;
use Concerto\database\CyubanInf;
use Concerto\database\CyubanInfData;
use Concerto\database\MstBumon;
use Concerto\database\MstBumonData;
use Concerto\database\MstBunyaSeizo;
use Concerto\database\MstMitumoriBunya;
use Concerto\database\MstTanto;
use Concerto\database\Tmal0160;
use Concerto\excel\excel\ExcelManager;
use Concerto\standard\FileOperation;
use seiban_kanri2\model\CyubanSonekiChartControllerModel;
use seiban_kanri2\model\CyubanSonekiChartFactory;
use seiban_kanri2\model\CyubanSonekiDispControllerModel;
use seiban_kanri2\model\CyubanSonekiDispFactory;
use seiban_kanri2\model\CyubanSonekiDispGridCyubanModel;
use seiban_kanri2\model\CyubanSonekiDispModel;
use seiban_kanri2\model\CyubanSonekiExcelBuilder;
use seiban_kanri2\model\CyubanSonekiExcelControllerModel;
use seiban_kanri2\model\CyubanSonekiExcelModel;
use seiban_kanri2\model\QueryCyubanSonekiDisp;

class CyubanSonekiExcelFactory
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
    
    
    
    
    
    
    public function getCyokkaMonKeikaku()
    {
        return new CyokkaMonKeikaku($this->pdo);
    }
    
    public function getCyubanInf()
    {
        return new CyubanInf($this->pdo);
    }
    
    public function getCyubanInfData()
    {
        return new CyubanInfData();
    }
    
    public function getMstBumon()
    {
        return new MstBumon($this->pdo);
    }
    
    public function getMstBumonData()
    {
        return new MstBumonData();
    }
    
    public function getMstBunyaSeizo()
    {
        return new MstBunyaSeizo($this->pdo);
    }
    
    public function getMstMitumoriBunya()
    {
        return new MstMitumoriBunya($this->pdo);
    }
    
    public function getMstTanto()
    {
        return new MstTanto($this->pdo);
    }
    
    public function getTmal0160()
    {
        return new Tmal0160($this->pdo);
    }
    
    
    
    public function getCyubanSonekiExcelControllerModel()
    {
        return new CyubanSonekiExcelControllerModel($this);
    }
    
    public function getCyubanSonekiDispControllerModel()
    {
        return new CyubanSonekiDispControllerModel(
            new CyubanSonekiDispFactory($this->pdo)
        );
    }
    
    public function getCyubanSonekiChartControllerModel()
    {
        return new CyubanSonekiChartControllerModel(
            new CyubanSonekiChartFactory($this->pdo)
        );
    }
    
    
    
    
    public function getExcelManager($template)
    {
        return new ExcelManager($template);
    }
    
    public function getExcelBuilder()
    {
        return new CyubanSonekiExcelBuilder(
            $this->getModel(),
            $this->getCyubanSonekiExcelControllerModel(),
            $this->getCyubanSonekiDispControllerModel(),
            $this->getCyubanSonekiChartControllerModel()
        );
    }
    
    public function getFileOperation()
    {
        return new FileOperation();
    }
    
    
    
    public function getCyubanSonekiDispGridCyubanModel()
    {
        return new CyubanSonekiDispGridCyubanModel(
            $this->getPdo()
        );
    }
    
    
    
    public function getCyubanSonekiDispModel()
    {
        return new CyubanSonekiDispModel(
            $this->getPdo(),
            $this->getCyokkaMonKeikaku(),
            $this->getCyubanInf(),
            $this->getCyubanInfData(),
            $this->getMstBumon(),
            $this->getMstBumonData(),
            $this->getMstBunyaSeizo(),
            $this->getMstMitumoriBunya(),
            $this->getMstTanto(),
            $this->getTmal0160(),
            $this->getCyubanSonekiDispGridCyubanModel()
        );
    }
    
    public function getQuery()
    {
        return new QueryCyubanSonekiDisp();
    }
    
    public function getModel()
    {
        return new CyubanSonekiExcelModel(
            $this->getPdo(),
            $this->getCyubanSonekiDispModel()
        );
    }
}
