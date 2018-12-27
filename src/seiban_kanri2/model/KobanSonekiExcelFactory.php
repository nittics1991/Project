<?php
/**
*   factory
*
*   @version 171011
*/
namespace seiban_kanri2\model;

use \PDO;
use Concerto\database\CyubanInf;
use Concerto\database\CyubanInfData;
use Concerto\database\CyunyuInf;
use Concerto\database\CyunyuInfData;
use Concerto\database\KobanInf;
use Concerto\database\KobanInfData;
use Concerto\database\KobanTyousei;
use Concerto\database\KobanTyouseiData;
use Concerto\excel\excel\ExcelManager;
use Concerto\standard\FileOperation;
use seiban_kanri2\model\KobanSonekiChartControllerModel;
use seiban_kanri2\model\KobanSonekiDispControllerModel;
use seiban_kanri2\model\KobanSonekiDispCyubanModel;
use seiban_kanri2\model\KobanSonekiDispFactory;
use seiban_kanri2\model\KobanSonekiExcelBuilder;
use seiban_kanri2\model\KobanSonekiExcelControllerModel;
use seiban_kanri2\model\KobanSonekiExcelModel;
use seiban_kanri2\model\QueryKobanSonekiDisp;

class KobanSonekiExcelFactory
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
    
    
    public function getCyubanInf()
    {
        return new CyubanInf($this->pdo);
    }
    
    public function getCyubanInfData()
    {
        return new CyubanInfData();
    }
    
    public function getCyunyuInf()
    {
        return new CyunyuInf($this->pdo);
    }
    
    public function getCyunyuInfData()
    {
        return new CyunyuInfData();
    }
    
    public function getKobanInf()
    {
        return new KobanInf($this->pdo);
    }
    
    public function getKobanInfData()
    {
        return new KobanInfData();
    }
    
    public function getKobanTyousei()
    {
        return new KobanTyousei($this->pdo);
    }
    
    public function getKobanTyouseiData()
    {
        return new KobanTyouseiData();
    }
    
    
    
    public function getKobanSonekiChartControllerModel()
    {
        return new KobanSonekiChartControllerModel(
            new KobanSonekiChartFactory($this->pdo)
        );
    }
    
    public function getKobanSonekiChartModel()
    {
        return new KobanSonekiChartModel(
            $this->getPdo(),
            $this->getCyubanInf(),
            $this->getCyubanInfData(),
            $this->getCyunyuInf(),
            $this->getCyunyuInfData(),
            $this->getKobanInf(),
            $this->getKobanInfData(),
            $this->getKobanSonekiDispCyubanModel()
        );
    }
    
    public function getKobanSonekiDispControllerModel()
    {
        return new KobanSonekiDispControllerModel(
            new KobanSonekiDispFactory($this->pdo)
        );
    }
    
    public function getKobanSonekiDispCyubanModel()
    {
        return new KobanSonekiDispCyubanModel(
            $this->getPdo(),
            $this->getCyubanInf(),
            $this->getCyubanInfData(),
            $this->getKobanInf(),
            $this->getKobanInfData(),
            $this->getKobanTyousei(),
            $this->getKobanTyouseiData()
        );
    }
    
    public function getKobanSonekiExcelControllerModel()
    {
        return new KobanSonekiExcelControllerModel(
            $this
        );
    }
    
    
    
    public function getExcelManager($template)
    {
        return new ExcelManager($template);
    }
    
    public function getExcelBuilder($no_cyu)
    {
        return new KobanSonekiExcelBuilder(
            $this->getModel(),
            $this->getKobanSonekiExcelControllerModel(),
            $this->getKobanSonekiDispControllerModel(),
            $this->getKobanSonekiDispCyubanModel(),
            $this->getKobanSonekiChartControllerModel(),
            $this->getKobanSonekiChartModel(),
            $no_cyu
        );
    }
    
    public function getFileOperation()
    {
        return new FileOperation();
    }
    
    
    
    public function getQuery()
    {
        return new QueryKobanSonekiDisp();
    }
    
    public function getModel()
    {
        return new KobanSonekiExcelModel(
            $this->getPdo()
        );
    }
}
