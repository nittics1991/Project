<?php
/**
*   JyukyuInfDispUkeireModel
*
*   @version 180911
*/
namespace jyukyu_inf\model;

use \Exception;
use \PDO;
use Concerto\database\JyukyuInf;
use Concerto\database\JyukyuInfData;
use jyukyu_inf\model\PostJyukyuInfDispUkeire;

class JyukyuInfDispUkeireModel
{
    /**
    *   object
    *
    *   @var object
    **/
    protected $pdo;
    protected $jyukyuInf;
    protected $jyukyuInfData;
    
    /**
    *   __construct
    *
    *   @param PDO $pdo
    *   @param JyukyuInf $jyukyuInf
    *   @param JyukyuInfData $jyukyuInfData
    **/
    public function __construct(
        PDO $pdo,
        JyukyuInf $jyukyuInf,
        JyukyuInfData $jyukyuInfData
    ) {
        $this->pdo = $pdo;
        $this->jyukyuInf = $jyukyuInf;
        $this->jyukyuInfData = $jyukyuInfData;
    }
    
    /**
    *   保存
    *
    *   @param PostJyukyuInfDispUkeire $post
    */
    public function setData(PostJyukyuInfDispUkeire $post)
    {
        try {
            $this->pdo->beginTransaction();
            $this->update($post);
            $this->pdo->commit();
        } catch (Exception $e) {
            $this->pdo->rollback();
            throw $e;
        }
    }
    
    /**
    *   update
    *
    *   @param PostJyukyuInfDispUkeire $post
    **/
    protected function update(PostJyukyuInfDispUkeire $post)
    {
        $data = $this->buildData($post);
        $where = $this->buildWhere($post);
        $this->jyukyuInf->update([[$data, $where]]);
    }
    
    /**
    *   データ作成
    *
    *   @param PostJyukyuInfDispUkeire $post
    *   @return jyukyuInfData
    **/
    protected function buildData(PostJyukyuInfDispUkeire $post)
    {
        $data = clone $this->jyukyuInfData;
        $data->cd_jyukyu_tanto = $post->cd_jyukyu_tanto;
        $data->dt_rjyukyu = $post->dt_rjyukyu;
        $data->no_rsuryo = (int)$post->no_rsuryo;
        return $data;
    }
    
    /**
    *   where作成
    *
    *   @param PostJyukyuInfDispUkeire $post
    *   @return jyukyuInfData
    **/
    protected function buildWhere(PostJyukyuInfDispUkeire $post)
    {
        $where = clone $this->jyukyuInfData;
        $where->no_jyukyu = $post->no_jyukyu;
        return $where;
    }
}
