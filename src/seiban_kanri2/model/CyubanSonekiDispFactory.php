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
use Concerto\pattern\MementoCookieManager;
use seiban_kanri2\model\CyubanSonekiDispGridCyubanModel;
use seiban_kanri2\model\CyubanSonekiDispGridCyubanSectionModel;
use seiban_kanri2\model\CyubanSonekiDispModel;
use seiban_kanri2\model\CyubanSonekiOriginator;
use seiban_kanri2\model\PostCyubanSonekiDisp;
use seiban_kanri2\model\QueryCyubanSonekiDisp;

class CyubanSonekiDispFactory
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
    
    
    
    public function getMementoCookieManager($namespace, $config, $originator)
    {
        return new MementoCookieManager($namespace, $config, $originator);
    }
    
    public function getMementoOriginator()
    {
        return new CyubanSonekiOriginator();
    }
    
    
    
    public function getCyubanSonekiDispGridCyubanModel()
    {
        return new CyubanSonekiDispGridCyubanModel(
            $this->getPdo()
        );
    }
    
    public function getCyubanSonekiDispGridCyubanSectionModel()
    {
        return new CyubanSonekiDispGridCyubanSectionModel(
            $this->getPdo()
        );
    }
    
    
    
    public function getPost()
    {
        return new PostCyubanSonekiDisp();
    }
    
    public function getQuery()
    {
        return new QueryCyubanSonekiDisp();
    }
    
    public function getModel($fg_cyuban = null)
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
            ($fg_cyuban == '1')?
                $this->getCyubanSonekiDispGridCyubanSectionModel()
                :$this->getCyubanSonekiDispGridCyubanModel()
        );
    }
}
