<?php
/**
*   factory
*
*   @version 180918
*/
namespace jyukyu_inf\model;

use \PDO;
use Concerto\database\HaraidasiInfData;
use Concerto\database\JyukyuInf;
use Concerto\pattern\MementoCookieManager;
use jyukyu_inf\model\JyukyuKanriDispModel;
use jyukyu_inf\model\JyukyuKanriOriginator;
use jyukyu_inf\model\PostJyukyuKanriDisp;
use jyukyu_inf\model\QueryJyukyuKanriDisp;

class JyukyuKanriDispFactory
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
    
    
    
    public function getMementoCookieManager($namespace, $config, $originator)
    {
        return new MementoCookieManager($namespace, $config, $originator);
    }
    
    public function getMementoOriginator()
    {
        return new JyukyuKanriOriginator();
    }
    
    
    
    public function getPost()
    {
        return new PostJyukyuKanriDisp();
    }
    
    public function getQuery()
    {
        return new QueryJyukyuKanriDisp();
    }
    
    public function getModel()
    {
        return new JyukyuKanriDispModel(
            $this->getPdo(),
            $this->getHaraidasiInfData(),
            $this->getJyukyuInf()
        );
    }
}
