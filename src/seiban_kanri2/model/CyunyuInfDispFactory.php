<?php
/**
*   factory
*
*   @version 171019
*/
namespace seiban_kanri2\model;

use \PDO;
use Concerto\database\CyokkaKeikaku;
use Concerto\database\CyokkaKeikakuData;
use Concerto\database\CyubanInf;
use Concerto\database\CyubanInfData;
use Concerto\database\CyunyuInf;
use Concerto\database\CyunyuInfData;
use Concerto\database\CyunyuLock;
use Concerto\database\CyunyuLockData;
use Concerto\database\KobanInf;
use Concerto\database\KobanInfData;
use Concerto\database\MstBumon;
use Concerto\database\MstBumonData;
use Concerto\database\MstTanto;
use Concerto\database\MstTantoData;
use Concerto\database\OperationHist;
use Concerto\database\OperationHistData;
use Concerto\database\SeibanTanto;
use Concerto\database\SeibanTantoData;
use Concerto\standard\Session;
use seiban_kanri2\model\CyunyuInfDispCyunyuLock;
use seiban_kanri2\model\CyunyuInfDispMntModel;
use seiban_kanri2\model\CyunyuInfDispModel;
use seiban_kanri2\model\PostCyunyuInfDisp;
use seiban_kanri2\model\QueryCyunyuInfCdBumon;
use seiban_kanri2\model\QueryCyunyuInfDisp;

class CyunyuInfDispFactory
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
    
    
    
    public function getCyokkaKeikaku()
    {
        return new CyokkaKeikaku($this->pdo);
    }
    
    public function getCyokkaKeikakuData()
    {
        return new CyokkaKeikakuData();
    }
    
    public function getCyubanInf()
    {
        return new CyubanInf($this->pdo);
    }
    
    public function getCyubanInfData()
    {
        return new CyubanInfData();
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
    
    public function getCyunyuInfData()
    {
        return new CyunyuInfData();
    }
    
    public function getCyunyuLock()
    {
        return new CyunyuLock($this->pdo);
    }
    
    public function getCyunyuLockData()
    {
        return new CyunyuLockData();
    }
    
    public function getKobanInf()
    {
        return new KobanInf($this->pdo);
    }
    
    public function getKobanInfData()
    {
        return new KobanInfData();
    }
    
    public function getMstBumon()
    {
        return new MstBumon($this->pdo);
    }
    
    public function getMstBumonData()
    {
        return new MstBumonData();
    }
    
    public function getMstTanto()
    {
        return new MstTanto($this->pdo);
    }
    
    public function getMstTantoData()
    {
        return new MstTantoData();
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
    
    
    
    public function getSession($global = null)
    {
        return ($global)? new Session():new Session('seiban_kanri');
    }
    
    
    
    public function getMntModel()
    {
        return new CyunyuInfDispMntModel(
            $this->getPdo(),
            $this->getCyokkaKeikaku(),
            $this->getCyokkaKeikakuData(),
            $this->getCyubanInf(),
            $this->getCyunyuInf(),
            $this->getCyunyuInfData(),
            $this->getKobanInf(),
            $this->getKobanInfData(),
            $this->getMstTanto(),
            $this->getMstTantoData(),
            $this->getOperationHist(),
            $this->getOperationHistData(),
            $this->getSeibanTanto(),
            $this->getSeibanTantoData(),
            $this->getSession(true),
            $this->getSession()
        );
    }
    
    public function getCyunyuInfDispCyunyuLock()
    {
        return new CyunyuInfDispCyunyuLock(
            $this->getPdo(),
            $this->getCyunyuLock(),
            $this->getCyunyuLockData(),
            $this->getOperationHist(),
            $this->getOperationHistData()
        );
    }
    
    public function getQueryCdBumon()
    {
        return new QueryCyunyuInfCdBumon();
    }
    
    public function getPost()
    {
        return new PostCyunyuInfDisp();
    }
    
    public function getQuery()
    {
        return new QueryCyunyuInfDisp();
    }
    
    public function getModel()
    {
        return new CyunyuInfDispModel(
            $this->getPdo(),
            $this->getCyubanInf(),
            $this->getCyubanInfData(),
            $this->getCyunyuInf(),
            $this->getCyunyuInfData(),
            $this->getKobanInf(),
            $this->getKobanInfData(),
            $this->getMstBumon(),
            $this->getMstBumonData(),
            $this->getMstTanto(),
            $this->getSeibanTanto(),
            $this->getSeibanTantoData()
        );
    }
}
