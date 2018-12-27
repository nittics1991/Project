<?php
/**
*   FacadeModel
*
*   @version 180918
*/
namespace jyukyu_inf\model;

use \PDO;
use Concerto\database\HaraidasiInfData;
use Concerto\database\JyukyuInf;
use Concerto\FiscalYear;

class JyukyuKanriDispModel
{
    /**
    *   object
    *
    *   @val object
    */
    protected $pdo;
    protected $haraidasiInfData;
    protected $jyukyuInf;
    
    /**
    *   __construct
    *
    *   @param PDO $pdo
    *   @param HaraidasiInfData $haraidasiInfData
    *   @param JyukyuInf $jyukyuInf
    */
    public function __construct(
        PDO $pdo,
        HaraidasiInfData $haraidasiInfData,
        JyukyuInf $jyukyuInf
    ) {
        $this->pdo = $pdo;
        $this->haraidasiInfData = $haraidasiInfData;
        $this->jyukyuInf = $jyukyuInf;
    }
    
    /**
    *   年度リスト
    *
    *   @return array
    */
    public function getNendoList()
    {
        $result = $this->jyukyuInf->getNendoList();
        $nendo_list = [];
        
        foreach ((array)$result as $list) {
            $nendo_list[] = [
                'kb_nendo' => $list['kb_nendo'],
                'nm_nendo' => FiscalYear::nendoCodeToZn($list['kb_nendo'])
            ];
        }
        return $nendo_list;
    }
    
    /**
    *   部門リスト
    *
    *   @param string $kb_nendo
    *   @return array
    */
    public function getBumonList($kb_nendo)
    {
        $sql = "
            SELECT Y.bumon_code AS cd_bumon
                , Y.bumon_name AS nm_bumon
            FROM (
                SELECT DISTINCT cd_bumon
                FROM (
                    SELECT no_cyu
                    FROM public.jyukyu_inf
                    WHERE dt_pjyukyu >= :start
                        AND dt_pjyukyu <= :end
                ) A
                JOIN public.koban_inf B
                    ON B.no_cyu = A.no_cyu
            ) Z
            JOIN public.mst_bumon Y
                ON Y.bumon_code = Z.cd_bumon
            ORDER BY Z.cd_bumon
        ";
        
        $stmt = $this->pdo->prepare($sql);
        $yyyymm = FiscalYear::getNendoyyyymm($kb_nendo);
        $stmt->bindValue(':start', "{$yyyymm[0]}01", PDO::PARAM_STR);
        $stmt->bindValue(':end', "{$yyyymm[5]}31", PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
    *   受給品リスト
    *
    *   @param string $kb_nendo
    *   @param string $cd_bumon
    *   @param string $chk_nendo_all
    *   @param string $chk_kansei
    *   @return array
    */
    public function getJyukyuList(
        $kb_nendo,
        $cd_bumon,
        $chk_nendo_all,
        $chk_kansei
    ) {
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
                SELECT A1.*
                FROM public.jyukyu_inf A1
        ";
        
        if (!is_null($cd_bumon)) {
            $sql .= "
                JOIN (
                    SELECT DISTINCT no_cyu
                    FROM public.koban_inf
                    WHERE cd_bumon = :bumon
                ) A2
                    ON A2.no_cyu = A1.no_cyu
            ";
        }
        
        if ($chk_kansei) {
            $sql .= "
                JOIN (
                    SELECT DISTINCT no_cyu
                    FROM public.cyuban_inf
                    WHERE dt_uriage = ''
                ) A3
                    ON A3.no_cyu = A1.no_cyu
            ";
        }
        
        $sql .= "
            WHERE 1 = 1
        ";
        
        if (!$chk_nendo_all) {
            $sql .= "
                AND dt_pjyukyu <= :end
            ";
        }
        
        $sql .= "
                AND dt_pjyukyu >= :start
            ) A
            LEFT JOIN (
                SELECT no_cyu, no_cyu_t
                FROM public.wf_new B1
                WHERE 1 = 1
                    AND no_page = '0'
                    AND no_rev = (
                        SELECT MAX(no_rev)
                        FROM public.wf_new B2
                        WHERE B2.no_cyu = B1.no_cyu
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
            ORDER BY A.dt_pjyukyu, A.dt_rjyukyu, A.no_jyukyu
        ";
        
        $stmt = $this->pdo->prepare($sql);
        $yyyymm = FiscalYear::getNendoyyyymm($kb_nendo);
        $stmt->bindValue(':start', "{$yyyymm[0]}01", PDO::PARAM_STR);
        
        if (!is_null($cd_bumon)) {
            $stmt->bindValue(':bumon', $cd_bumon, PDO::PARAM_STR);
        }
        
        if (!$chk_nendo_all) {
            $stmt->bindValue(':end', "{$yyyymm[5]}31", PDO::PARAM_STR);
        }
        
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
