<?php
/**
*   factory
*
*   @version 171018
*/
namespace seiban_kanri2\model;

use \PDO;
use Concerto\database\CyunyuKeikaku;
use Concerto\database\CyunyuKeikakuData;
use Concerto\database\CyunyuInfData;
use Concerto\database\KobanInf;
use Concerto\database\OperationHist;
use Concerto\database\OperationHistData;
use Concerto\excel\ExcelManager;
use Concerto\standard\FileOperation;
use Concerto\standard\HttpUpload;
use Concerto\standard\Session;
use seiban_kanri2\model\CyubanSonekiExcelReadModel;
use seiban_kanri2\model\PostCyubanSonekiExcelRead;
use seiban_kanri2\model\QueryCyubanSonekiDisp;
    
class CyubanSonekiExcelReadFactory
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
    
    
    
    public function getCyunyuKeikaku()
    {
        return new CyunyuKeikaku($this->pdo);
    }
    
    public function getCyunyuKeikakuData()
    {
        return new CyunyuKeikakuData();
    }
    
    public function getCyunyuInfData()
    {
        return new CyunyuInfData();
    }
    
    public function getKobanInf()
    {
        return new KobanInf($this->pdo);
    }
    
    public function getOperationHist()
    {
        return new OperationHist($this->pdo);
    }
    
    public function getOperationHistData()
    {
        return new OperationHistData();
    }
    
    
    public function getExcelManager($template)
    {
        return new ExcelManager($template);
    }
    
    public function getFileOperation()
    {
        return new FileOperation();
    }
    
    public function getHttpUpload()
    {
        return new HttpUpload();
    }
    
    
    
    public function getSession()
    {
        return new Session();
    }
    
    
    
    public function getPost()
    {
        return new PostCyubanSonekiExcelRead();
    }
    
    public function getQuery()
    {
        return new QueryCyubanSonekiDisp();
    }
    
    public function getModel()
    {
        return new CyubanSonekiExcelReadModel(
            $this->getPdo(),
            $this->getCyunyuInfData(),
            $this->getCyunyuKeikaku(),
            $this->getCyunyuKeikakuData(),
            $this->getKobanInf(),
            $this->getOperationHist(),
            $this->getOperationHistData(),
            $this->getSession()
        );
    }
}
