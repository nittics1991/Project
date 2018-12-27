<?php
/**
*   factory
*
*   @version 180911
*/
namespace jyukyu_inf\model;

use \PDO;
use Concerto\database\HaraidasiInfData;
use Concerto\database\JyukyuInf;
use Concerto\database\JyukyuInfData;
use Concerto\database\MstTanto;
use Concerto\standard\Session;
use jyukyu_inf\model\JyukyuInfDispModel;
use jyukyu_inf\model\JyukyuInfDispJyukyuModel;
use jyukyu_inf\model\JyukyuInfDispUkeireModel;
use jyukyu_inf\model\JyukyuInfDispAllUkeireModel;
use jyukyu_inf\model\PostJyukyuInfDispJyukyu;
use jyukyu_inf\model\PostJyukyuInfDispUkeire;
use jyukyu_inf\model\PostJyukyuInfDispAllUkeire;
use jyukyu_inf\model\QueryJyukyuInfDisp;

class JyukyuInfDispFactory
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
    
    
    
    public function getHaraidasiInfData()
    {
        return new HaraidasiInfData();
    }
    
    public function getJyukyuInf()
    {
        return new JyukyuInf($this->pdo);
    }
    
    public function getJyukyuInfData()
    {
        return new JyukyuInfData();
    }
    
    public function getMstTanto()
    {
        return new MstTanto($this->pdo);
    }
    
    
    
    public function getQuery()
    {
        return new QueryJyukyuInfDisp();
    }
    
    public function getModel()
    {
        return new JyukyuInfDispModel(
            $this->getPdo(),
            $this->getHaraidasiInfData(),
            $this->getMstTanto()
        );
    }
    
    
    
    public function getSession($global = null)
    {
        return ($global)? new Session():new Session('jyukyu_inf');
    }
    
    public function getJyukyuPost()
    {
        return new PostJyukyuInfDispJyukyu();
    }
    
    public function getJyukyuModel()
    {
        return new JyukyuInfDispJyukyuModel(
            $this->getPdo(),
            $this->getJyukyuInf(),
            $this->getJyukyuInfData(),
            $this->getSession(true)
        );
    }
    
    public function getUkeirePost()
    {
        return new PostJyukyuInfDispUkeire();
    }
    
    public function getUkeireModel()
    {
        return new JyukyuInfDispUkeireModel(
            $this->getPdo(),
            $this->getJyukyuInf(),
            $this->getJyukyuInfData()
        );
    }
    
    public function getAllUkeirePost()
    {
        return new PostJyukyuInfDispAllUkeire();
    }
    
    public function getAllUkeireModel()
    {
        return new JyukyuInfDispAllUkeireModel(
            $this->getPdo(),
            $this->getJyukyuInf(),
            $this->getJyukyuInfData()
        );
    }
}
