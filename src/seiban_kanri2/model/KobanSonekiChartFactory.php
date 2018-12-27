<?php
/**
*   factory
*
*   @version 171011
*/
namespace seiban_kanri2\model;

use \PDO;
use Concerto\chart\cpchart\CpChartBuilder;
use Concerto\database\CyubanInf;
use Concerto\database\CyubanInfData;
use Concerto\database\CyunyuInf;
use Concerto\database\CyunyuInfData;
use Concerto\database\KobanInf;
use Concerto\database\KobanInfData;
use Concerto\database\KobanTyousei;
use Concerto\database\KobanTyouseiData;
use Concerto\standard\FileOperation;
use Concerto\task\TaskManagerStandard;
use seiban_kanri2\model\KobanSonekiDispCyubanModel;
use seiban_kanri2\model\KobanSonekiChartBuilder1;
use seiban_kanri2\model\KobanSonekiChartBuilder2;
use seiban_kanri2\model\KobanSonekiChartBuilder2a;
use seiban_kanri2\model\KobanSonekiChartBuilder2b;
use seiban_kanri2\model\KobanSonekiChartModel;
use seiban_kanri2\model\QueryKobanSonekiChartMain;
use seiban_kanri2\model\QueryKobanSonekiDisp;

class KobanSonekiChartFactory
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
    
    
    
    public function getFileOperation()
    {
        return new FileOperation();
    }
    
    public function getChartQuery()
    {
        return new QueryKobanSonekiChartMain();
    }
    
    public function getTaskManager($timeout)
    {
        return new TaskManagerStandard($timeout);
    }
    
    public function getChartBuilder()
    {
        return new CpChartBuilder();
    }
    
    public function getChartDefinition($id, $file, $template)
    {
        $chartData = "seiban_kanri2\model\chart\KobanSonekiChart{$id}";
        return new $chartData(
            $this->getModel(''),
            $file,
            $template
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
    
    
    
    public function getQuery()
    {
        return new QueryKobanSonekiDisp();
    }
    
    public function getModel($fg_project = null)
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
}
