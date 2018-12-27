<?php
/**
*   factory
*
*   @version 171017
*/
namespace seiban_kanri2\model;

use \PDO;
use Concerto\chart\cpchart\CpChartBuilder;
use Concerto\database\CyokkaMonKeikaku;
use Concerto\database\CyokkaMonKeikakuData;
use Concerto\database\CyunyuInf;
use Concerto\database\KobanInf;
use Concerto\standard\FileOperation;
use Concerto\task\TaskManagerStandard;
use seiban_kanri2\model\CyubanSonekiChartBuilder1;
use seiban_kanri2\model\CyubanSonekiChartBuilder2;
use seiban_kanri2\model\CyubanSonekiChartBuilder3;
use seiban_kanri2\model\CyubanSonekiChartBuilder3a;
use seiban_kanri2\model\CyubanSonekiChartBuilder3b;
use seiban_kanri2\model\CyubanSonekiChartBuilder4;
use seiban_kanri2\model\CyubanSonekiChartModel;
use seiban_kanri2\model\CyubanSonekiDispGridCyubanModel;
use seiban_kanri2\model\QueryCyubanSonekiChartMain;
use seiban_kanri2\model\QueryCyubanSonekiDisp;

class CyubanSonekiChartFactory
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
    
    public function getCyokkaMonKeikakuData()
    {
        return new CyokkaMonKeikakuData();
    }
    
    public function getCyunyuInf()
    {
        return new CyunyuInf($this->pdo);
    }
    
    public function getKobanInf()
    {
        return new KobanInf($this->pdo);
    }
    
    
    public function getFileOperation()
    {
        return new FileOperation();
    }
    
    public function getChartQuery()
    {
        return new QueryCyubanSonekiChartMain();
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
        $chartData = "seiban_kanri2\model\chart\CyubanSonekiChart{$id}";
        return new $chartData(
            $this->getModel(),
            $file,
            $template
        );
    }
    
    
    
    public function getCyubanSonekiDispGridCyubanModel()
    {
        return new CyubanSonekiDispGridCyubanModel(
            $this->getPdo()
        );
    }
    
    
    
    public function getQuery()
    {
        return new QueryCyubanSonekiDisp();
    }
    
    public function getModel()
    {
        return new CyubanSonekiChartModel(
            $this->pdo,
            $this->getCyokkaMonKeikaku(),
            $this->getCyokkaMonKeikakuData(),
            $this->getCyunyuInf(),
            $this->getKobanInf(),
            $this->getCyubanSonekiDispGridCyubanModel()
        );
    }
}
