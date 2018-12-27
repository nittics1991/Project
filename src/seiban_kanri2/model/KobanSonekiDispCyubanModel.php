<?php
/**
*   FacadeModel
*
*   @version 171012
*/
namespace seiban_kanri2\model;

use \Exception;
use \PDO;
use Concerto\database\CyubanInf;
use Concerto\database\CyubanInfData;
use Concerto\database\KobanInf;
use Concerto\database\KobanInfData;
use Concerto\database\KobanTyousei;
use Concerto\database\KobanTyouseiData;

class KobanSonekiDispCyubanModel
{
    /**
    *   object
    *
    *   @val object
    */
    private $pdo;
    private $cyubanInf;
    private $cyubanInfData;
    private $kobanInf;
    private $kobanInfData;
    private $kobanTyousei;
    private $kobanTyouseiData;
    
    /**
    *   __construct
    *
    *   @param PDO $pdo
    *   @param CyubanInf $cyubanInf
    *   @param CyubanInfData $cyubanInfData
    *   @param KobanInf $kobanInf
    *   @param KobanInfData $kobanInfData
    *   @param KobanTyousei $kobanTyousei
    *   @param KobanTyouseiData $kobanTyouseiData
    */
    public function __construct(
        PDO $pdo,
        CyubanInf $cyubanInf,
        CyubanInfData $cyubanInfData,
        KobanInf $kobanInf,
        KobanInfData $kobanInfData,
        KobanTyousei $kobanTyousei,
        KobanTyouseiData $kobanTyouseiData
    ) {
        $this->pdo = $pdo;
        $this->cyubanInf = $cyubanInf;
        $this->cyubanInfData = $cyubanInfData;
        $this->kobanInf = $kobanInf;
        $this->kobanInfData = $kobanInfData;
        $this->kobanTyousei = $kobanTyousei;
        $this->kobanTyouseiData = $kobanTyouseiData;
    }
    
    /**
    *   注番リスト
    *
    *   @param string $no_cyu 注番
    *   @return array
    */
    public function getCyubanList($no_cyu)
    {
        $cyubanInfData = clone $this->cyubanInfData;
        $cyubanInfData->no_cyu = $no_cyu;
        $result = $this->cyubanInf->select($cyubanInfData);
        
        if (count($result) > 0) {
            $obj = $result[0];
            return $obj->toArray();
        }
        return [];
    }
    
    /**
    *   項番集計リスト
    *
    *   @param string $no_cyu 注番
    *   @param string $cd_bumon 部門
    *   @param string $no_ko 項番
    *   @return array
    */
    public function getKobanAggregateList(
        $no_cyu,
        $cd_bumon = null,
        $no_ko = null
    ) {
        return $this->kobanInf->getAggregate(
            null,
            $cd_bumon,
            null,
            $no_cyu,
            $no_ko
        );
    }
    
    /**
    *   項番リスト
    *
    *   @param string $no_cyu 注番
    *   @return array
    */
    public function getKobanList($no_cyu)
    {
        $kobanInfData = clone $this->kobanInfData;
        $kobanInfData->no_cyu = $no_cyu;
        $result = $this->kobanInf->select($kobanInfData, 'no_cyu, no_ko');
        
        if (count($result) == 0) {
            return [];
        }
        
        $kobanTyouseiData = clone $this->kobanTyouseiData;
        $kobanTyouseiData->no_cyu = $no_cyu;
        $result2 = $this->kobanTyousei->select(
            $kobanTyouseiData,
            'no_cyu, no_ko'
        );
        
        $hasKeys = function ($table, $no_cyu, $no_ko) {
            foreach ($table as $obj) {
                if (($obj['no_cyu'] == $no_cyu) && ($obj['no_ko'] == $no_ko)) {
                    return $obj->toArray();
                }
            }
            return [];
        };
        
        $items = [];
        
        foreach ($result as $obj) {
            $ar = $obj->toArray();
            $ar['yn_pcyunyu'] = $ar['yn_pcyokka'] + $ar['yn_pcyokuzai']
                + $ar['yn_petc'] + $ar['yn_pryohi'];
            $ar['yn_psoneki'] = $ar['yn_tov'] - $ar['yn_pcyunyu'];
            $ar['ri_psoneki'] = empty($ar['yn_tov'])?
                0.0:$ar['yn_psoneki'] / $ar['yn_tov'] * 100;
                           
            $ar['yn_rcyunyu'] = $ar['yn_rcyokka'] + $ar['yn_rcyokuzai']
                + $ar['yn_retc'] + $ar['yn_rryohi'];
            $ar['yn_rsoneki'] = $ar['yn_tov'] - $ar['yn_rcyunyu'];
            $ar['ri_rsoneki'] = empty($ar['yn_tov'])?
                0.0:$ar['yn_rsoneki'] / $ar['yn_tov'] * 100;
                           
            $ar['yn_ycyunyu'] = $ar['yn_ycyokka'] + $ar['yn_ycyokuzai']
                + $ar['yn_yetc'] + $ar['yn_yryohi'];
            $ar['yn_ysoneki'] = $ar['yn_tov'] - $ar['yn_ycyunyu'];
            $ar['ri_ysoneki'] = empty($ar['yn_tov'])?
                0.0:$ar['yn_ysoneki'] / $ar['yn_tov'] * 100;
            
            $list = $hasKeys($result2, $ar['no_cyu'], $ar['no_ko']);
            
            if (!empty($list)) {
                $ar['yn_ttov']  = $list['yn_ttov'];
                $ar['yn_tsoneki'] = $list['yn_tsoneki'];
                
                $yn_ttov = (is_null($list['yn_ttov']) || ($list['yn_ttov'] == ''))?
                    $ar['yn_tov']:$list['yn_ttov'];
                $yn_tsoneki = (is_null($list['yn_tsoneki']) || ($list['yn_tsoneki'] == ''))?
                    $ar['yn_ysoneki']:$list['yn_tsoneki'];
                $ar['ri_tsoneki'] = empty($yn_ttov)?
                    0.0:$yn_tsoneki / $yn_ttov * 100;
                
                $ar['nm_biko']  = $list['nm_biko'];
            } else {
                $ar['yn_ttov']  = '';
                $ar['yn_tsoneki'] = '';
                $ar['ri_tsoneki'] = '';
                $ar['nm_biko']  = '';
            }
            $items[] = $ar;
        }
        return $items;
    }
    
    /**
    *   項番月別リスト
    *
    *   @param string $no_cyu 注番
    *   @return array
    */
    public function getKobanMonList($no_cyu)
    {
        $sql = "
            SELECT A.no_cyu, A.no_ko, A.cd_bumon, A.dt_pkansei_m, A.nm_syohin 
                , B.cd_genka_yoso, B.cd_tanto
                , B.kb_cyunyu, B.dt_kanjyo 
                , (B.tm_cyokka + B.yn_cyokuzai + B.yn_etc + B.yn_ryohi) AS yn_cyunyu
                , C.tanto_name AS nm_tanto 
            FROM 
                (SELECT no_cyu, no_ko, cd_bumon, dt_pkansei_m, nm_syohin 
                FROM public.koban_inf 
                WHERE no_cyu = :no_cyu 
                ) A 
            LEFT JOIN 
                (SELECT no_cyu, no_ko, cd_genka_yoso, cd_tanto 
                    , kb_cyunyu, dt_kanjyo 
                    , SUM(tm_cyokka) AS tm_cyokka
                    , SUM(yn_cyokka) AS yn_cyokka
                    , SUM(yn_cyokuzai) AS yn_cyokuzai
                    , SUM(yn_etc) AS yn_etc
                    , SUM(yn_ryohi) AS yn_ryohi
                FROM public.cyunyu_inf 
                WHERE no_cyu = :no_cyu 
                    AND cd_genka_yoso = 'B' 
                GROUP BY no_cyu, no_ko, cd_genka_yoso, cd_tanto 
                    , kb_cyunyu, dt_kanjyo 
                ) B
                ON B.no_cyu = A.no_cyu AND B.no_ko = A.no_ko 
            LEFT JOIN public.mst_tanto C 
                ON C.tanto_code = B.cd_tanto 
            ORDER BY A.no_cyu, A.no_ko, B.cd_genka_yoso, B.cd_tanto 
                , B.kb_cyunyu, B.dt_kanjyo 
        ";
        
        $stmt1 = $this->pdo->prepare($sql);
        $stmt1->bindValue(':no_cyu', $no_cyu, PDO::PARAM_STR);
        $stmt1->execute();
        $result1 = $stmt1->fetchAll();
        
        $sql2 = "
            SELECT A.no_cyu, A.no_ko, A.cd_bumon
                , A.dt_pkansei_m, A.nm_syohin 
                , B.cd_genka_yoso, '' AS cd_tanto
                , B.kb_cyunyu, B.dt_kanjyo 
                , (B.tm_cyokka + B.yn_cyokuzai + B.yn_etc + B.yn_ryohi) AS yn_cyunyu
                , C.tanto_name AS nm_tanto 
            FROM 
                (SELECT no_cyu, no_ko, cd_bumon
                    , dt_pkansei_m, nm_syohin 
                FROM public.koban_inf 
                WHERE no_cyu = :no_cyu 
                ) A 
            LEFT JOIN 
                (SELECT no_cyu, no_ko, cd_genka_yoso, cd_tanto 
                    , kb_cyunyu, dt_kanjyo 
                    , SUM(tm_cyokka) AS tm_cyokka
                    , SUM(yn_cyokka) AS yn_cyokka
                    , SUM(yn_cyokuzai) AS yn_cyokuzai
                    , SUM(yn_etc) AS yn_etc
                    , SUM(yn_ryohi) AS yn_ryohi
                FROM public.cyunyu_inf 
                WHERE no_cyu = :no_cyu 
                    AND (cd_genka_yoso = 'A' OR cd_genka_yoso = 'C')
                GROUP BY no_cyu, no_ko, cd_genka_yoso, cd_tanto 
                    , kb_cyunyu, dt_kanjyo 
                ) B
                ON B.no_cyu = A.no_cyu AND B.no_ko = A.no_ko 
            LEFT JOIN public.mst_tanto C 
                ON C.tanto_code = B.cd_tanto 
            ORDER BY A.no_cyu, A.no_ko, B.cd_genka_yoso
                , B.kb_cyunyu, B.dt_kanjyo 
        ";
        
        $stmt2 = $this->pdo->prepare($sql2);
        $stmt2->bindValue(':no_cyu', $no_cyu, PDO::PARAM_STR);
        $stmt2->execute();
        $result2 = $stmt2->fetchAll();
        return array_merge($result1, $result2);
    }
}
