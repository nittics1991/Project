<?php
/**
*   FacadeModel
*
*   @version 180907
*/
namespace jyukyu_inf\model;

use \PDO;
use Concerto\database\HaraidasiInfData;
use Concerto\database\MstTanto;

class JyukyuInfDispModel
{
    /**
    *   object
    *
    *   @val object
    */
    protected $pdo;
    protected $haraidasiInfData;
    protected $mstTanto;
    
    /**
    *   __construct
    *
    *   @param PDO
    *   @param HaraidasiInfData
    *   @param MstTanto
    */
    public function __construct(
        PDO $pdo,
        HaraidasiInfData $haraidasiInfData,
        MstTanto $mstTanto
    ) {
        $this->pdo = $pdo;
        $this->haraidasiInfData = $haraidasiInfData;
        $this->mstTanto = $mstTanto;
    }
    
    /**
    *   製品選択リストSQL
    *
    */
    private function getSyohinListSql()
    {
        return "
            SELECT nm_syohin || '_' || nm_model AS cd_syohin
                , nm_syohin, nm_model
            FROM public.jyukyu_inf
            GROUP BY nm_syohin || '_' || nm_model, nm_syohin, nm_model
        ";
    }
    
    /**
    *   製品選択リスト
    *
    *   @return array
    */
    public function getSyohinList()
    {
        $sql = $this->getSyohinListSql() .
            "ORDER BY nm_syohin";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
    *   型式選択リスト
    *
    *   @return array
    */
    public function getModelList()
    {
        $sql = $this->getSyohinListSql() .
            "ORDER BY nm_model";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
    *   担当リスト
    *
    *   @param string
    *   @return array
    */
    public function getTantoList($cd_bumon)
    {
        return $this->mstTanto->getTantoListPriotityBumon($cd_bumon);
    }
    
    /**
    *   受給品注番リスト
    *
    *   @param string
    *   @return array
    */
    public function getJyukyuCyubanList($no_cyu)
    {
        $sql = "
            SELECT A.*
                , A.no_psuryo - A.no_rsuryo AS no_zansu
                , B.no_cyu_t
                , C.cd_sts, C.cd_uketori, C.dt_uketori
                , D.tanto_name AS nm_jyukyu_tanto
                , E.tanto_name AS nm_uketori_tanto
                , F.nm_syohin AS nm_daihyo
                , F.nm_setti, F.nm_user
            FROM (
                SELECT *
                FROM public.jyukyu_inf
                WHERE no_cyu = :cyuban
            ) A
            LEFT JOIN (
                SELECT no_cyu, no_cyu_t
                FROM public.wf_new
                WHERE no_cyu = :cyuban
                    AND no_page = '0'
                    AND no_rev = (
                        SELECT MAX(no_rev)
                        FROM public.wf_new
                        WHERE no_cyu = :cyuban
                            AND no_page = '0'
                        GROUP BY no_cyu, no_page
                    )
            ) B
                ON B.no_cyu = A.no_cyu
            LEFT JOIN public.haraidasi_inf C
                ON C.no_cyumon = A.no_jyukyu
            LEFT JOIN public.mst_tanto D
                ON D.tanto_code = A.cd_jyukyu_tanto
            LEFT JOIN public.mst_tanto E
                ON E.tanto_code = C.cd_uketori
            LEFT JOIN (
                SELECT DISTINCT no_cyu, nm_syohin, nm_setti, nm_user
                FROM public.cyuban_inf
            ) F
                ON F.no_cyu = A.no_cyu
            ORDER BY A.no_jyukyu
        ";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':cyuban', $no_cyu, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
    *   払出しステータス
    *
    *   @return array
    */
    public function getHaraidasiStatus()
    {
        return $this->haraidasiInfData->getStatusName();
    }
}
