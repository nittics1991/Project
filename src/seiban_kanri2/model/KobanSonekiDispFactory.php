<?php
/**
*   factory
*
*   @version 171115
*/
namespace seiban_kanri2\model;

use \PDO;
use Concerto\database\CyubanInf;
use Concerto\database\CyubanInfData;
use Concerto\database\HatubanInf;
use Concerto\database\HatubanInfData;
use Concerto\database\KobanInf;
use Concerto\database\KobanInfData;
use Concerto\database\KobanTyousei;
use Concerto\database\KobanTyouseiData;
use Concerto\database\OperationHist;
use Concerto\database\OperationHistData;
use Concerto\database\SeibanTanto;
use Concerto\database\SeibanTantoData;
use seiban_kanri2\model\KobanSonekiDispCyubanModel;
use seiban_kanri2\model\KobanSonekiDispHatubanKakunin;
use seiban_kanri2\model\KobanSonekiDispSeibanTanto;
use seiban_kanri2\model\KobanSonekiDispProjectModel;
use seiban_kanri2\model\KobanSonekiDispReplacePlan;
use seiban_kanri2\model\KobanSonekiSeibanTanto;
use seiban_kanri2\model\PostKobanSonekiDisp;
use seiban_kanri2\model\QueryKobanSonekiDisp;

class KobanSonekiDispFactory
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
    
    public function getHatubanInf()
    {
        return new HatubanInf($this->pdo);
    }
    
    public function getHatubanInfData()
    {
        return new HatubanInfData();
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
    
    public function getOperationHist()
    {
        return new OperationHist($this->pdo);
    }
    
    public function getOperationHistData()
    {
        return new OperationHistData();
    }
    
    public function getSeibanTanto()
    {
        return new SeibanTanto($this->pdo);
    }
    
    public function getSeibanTantoData()
    {
        return new SeibanTantoData();
    }
    
    
    
    public function getKobanSonekiDispHatubanKakunin()
    {
        return new KobanSonekiDispHatubanKakunin(
            $this->getCyubanInf(),
            $this->getCyubanInfData(),
            $this->getHatubanInf(),
            $this->getHatubanInfData()
        );
    }
    
    public function getKobanSonekiDispSeibanTanto()
    {
        return new KobanSonekiDispSeibanTanto(
            $this->getSeibanTanto(),
            $this->getSeibanTantoData()
        );
    }
    
    public function getKobanSonekiDispReplacePlan()
    {
        return new KobanSonekiDispReplacePlan(
            $this->getPdo(),
            $this->getCyubanInf(),
            $this->getKobanInf(),
            $this->getOperationHist(),
            $this->getOperationHistData()
        );
    }
    
    
    
    public function getPost()
    {
        return new PostKobanSonekiDisp();
    }
    
    public function getQuery()
    {
        return new QueryKobanSonekiDisp();
    }
    
    public function getModel($fg_project)
    {
        if ($fg_project) {
            return new KobanSonekiDispProjectModel(
                $this->getPdo(),
                $this->getCyubanInf(),
                $this->getCyubanInfData(),
                $this->getKobanTyousei(),
                $this->getKobanTyouseiData()
            );
        } else {
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
    }
}
