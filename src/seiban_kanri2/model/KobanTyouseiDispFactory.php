<?php
/**
*   factory
*
*   @version 171018
*/
namespace seiban_kanri2\model;

use \PDO;
use Concerto\database\KobanTyousei;
use Concerto\database\KobanTyouseiData;
use Concerto\database\OperationHist;
use Concerto\database\OperationHistData;
use Concerto\standard\Session;
use seiban_kanri2\model\KobanTyouseiDispMntModel;
use seiban_kanri2\model\KobanTyouseiDispModel;
use seiban_kanri2\model\PostKobanTyouseiDisp;
use seiban_kanri2\model\QueryKobanTyouseiDisp;

class KobanTyouseiDispFactory
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
    
    
    
    public function getSession($global = null)
    {
        return ($global)? new Session():new Session('seiban_kanri');
    }
    
    
    
    public function getKobanTyouseiDispMntModel()
    {
        return new KobanTyouseiDispMntModel(
            $this->getPdo(),
            $this->getKobanTyousei(),
            $this->getKobanTyouseiData(),
            $this->getOperationHist(),
            $this->getOperationHistData(),
            $this->getSession(true),
            $this->getSession()
        );
    }
    
    public function getPost()
    {
        return new PostKobanTyouseiDisp();
    }
    
    public function getQuery()
    {
        return new QueryKobanTyouseiDisp();
    }
    
    public function getModel()
    {
        return new KobanTyouseiDispModel(
            $this->getPdo(),
            $this->getKobanTyousei(),
            $this->getKobanTyouseiData(),
            $this->getOperationHist(),
            $this->getOperationHistData()
        );
    }
}
