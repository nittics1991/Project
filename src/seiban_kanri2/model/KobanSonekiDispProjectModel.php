<?php
/**
*   FacadeModel
*
*   @version 171017
*/
namespace seiban_kanri2\model;

use \Exception;
use \PDO;
use Concerto\database\CyubanInf;
use Concerto\database\CyubanInfData;
use Concerto\database\KobanTyousei;
use Concerto\database\KobanTyouseiData;

class KobanSonekiDispProjectModel
{
    /**
    *   object
    *
    *   @val object
    */
    private $pdo;
    private $cyubanInf;
    private $cyubanInfData;
    private $kobanTyousei;
    private $kobanTyouseiData;
    
    /**
    *   __construct
    *
    *   @param PDO $pdo
    *   @param CyubanInf $cyubanInf
    *   @param CyubanInfData $cyubanInfData
    *   @param KobanTyousei $kobanTyousei
    *   @param KobanTyouseiData $kobanTyouseiData
    */
    public function __construct(
        PDO $pdo,
        CyubanInf $cyubanInf,
        CyubanInfData $cyubanInfData,
        KobanTyousei $kobanTyousei,
        KobanTyouseiData $kobanTyouseiData
    ) {
        $this->pdo = $pdo;
        $this->cyubanInf = $cyubanInf;
        $this->cyubanInfData = $cyubanInfData;
        $this->kobanTyousei = $kobanTyousei;
        $this->kobanTyouseiData = $kobanTyouseiData;
    }

    /**
    *   注番リスト
    *
    *   @param string $project プロジェクト番号
    *   @return array
    */
    public function getCyubanList($project)
    {
        $sql = "
            SELECT 
                SUM(B.sp) AS yn_sp
                , SUM(B.total_net) AS yn_net
            FROM
                (SELECT no_cyu
                FROM public.project_cyuban
                WHERE no_project = :project
                ) A
            JOIN symphony.tpal0010 B
                ON B.chuban = A.no_cyu
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':project', $project, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    /**
    *   項番集計リスト
    *
    *   @param string $no_project プロジェクト番号
    *   @param string $cd_bumon 部門
    *   @return array
    */
    public function getKobanAggregateList($no_project, $cd_bumon = null)
    {
        $sql = "
            SELECT SUM(yn_tov) AS yn_tov 
                , SUM(tm_pcyokka) AS tm_pcyokka 
                , SUM(yn_pcyokka) AS yn_pcyokka 
                , SUM(yn_pcyokuzai) AS yn_pcyokuzai 
                , SUM(yn_pryohi) AS yn_pryohi 
                , SUM(yn_petc) AS yn_petc 
                , SUM(tm_ycyokka) AS tm_ycyokka 
                , SUM(yn_ycyokka) AS yn_ycyokka 
                , SUM(yn_ycyokuzai) AS yn_ycyokuzai 
                , SUM(yn_yryohi) AS yn_yryohi 
                , SUM(yn_yetc) AS yn_yetc 
                , SUM(tm_rcyokka) AS tm_rcyokka 
                , SUM(yn_rcyokka) AS yn_rcyokka 
                , SUM(yn_rcyokuzai) AS yn_rcyokuzai 
                , SUM(yn_rryohi) AS yn_rryohi 
                , SUM(yn_retc) AS yn_retc 
            FROM public.koban_inf A 
            WHERE cd_bumon LIKE :bumon 
                AND EXISTS
                    (SELECT no_cyu 
                    FROM public.project_cyuban B 
                    WHERE no_project = :project
                        AND B.no_cyu = A.no_cyu
                    )
        ";
        
        $stmt = $this->pdo->prepare($sql);
        $bumon = $cd_bumon . '%';
        $project = (int)$no_project;
        $stmt->bindValue(':bumon', $bumon, PDO::PARAM_STR);
        $stmt->bindValue(':project', $project, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll();
        
        if (count($result) == 0) {
            return [];
        }
        
        $aggregate = [];
        
        foreach ($result as $list) {
            $yn_pcyunyu = (string)($list['yn_pcyokka'] +
                $list['yn_pcyokuzai'] +
                $list['yn_pryohi'] +
                $list['yn_petc']
            );
            $yn_psoneki = (string)($list['yn_tov'] - $yn_pcyunyu);
            
            $ri_psoneki = empty($list['yn_tov'])?
                '0.0':sprintf('%4.1f', round(($yn_psoneki / $list['yn_tov']) * 100, 1));
            
            $yn_ycyunyu = (string)($list['yn_ycyokka'] +
                $list['yn_ycyokuzai'] +
                $list['yn_yryohi'] +
                $list['yn_yetc']
            );
            $yn_ysoneki = (string)($list['yn_tov'] - $yn_ycyunyu);
            
            $ri_ysoneki = empty($list['yn_tov'])?
                '0.0':sprintf('%4.1f', round(($yn_ysoneki / $list['yn_tov']) * 100, 1));
            
            $yn_rcyunyu = (string)($list['yn_rcyokka'] +
                $list['yn_rcyokuzai'] +
                $list['yn_rryohi'] +
                $list['yn_retc']
            );
            
            $yn_rsoneki = (string)($list['yn_tov'] - $yn_rcyunyu);
            
            $ri_rsoneki = empty($list['yn_tov'])?
                '0.0':sprintf('%4.1f', round(($yn_rsoneki / $list['yn_tov']) * 100, 1));
            
            $ar = ['yn_pcyunyu' => $yn_pcyunyu,
                'yn_psoneki' => $yn_psoneki,
                'ri_psoneki' => $ri_psoneki,
                'yn_ycyunyu' => $yn_ycyunyu,
                'yn_ysoneki' => $yn_ysoneki,
                'ri_ysoneki' => $ri_ysoneki,
                'yn_rcyunyu' => $yn_rcyunyu,
                'yn_rsoneki' => $yn_rsoneki,
                'ri_rsoneki' => $ri_rsoneki
            ];
            
            $aggregate[] = array_merge($list, $ar);
        }
        return $aggregate;
    }
    
    /**
    *   項番リスト
    *
    *   @param string $no_project プロジェクト番号
    *   @return array
    */
    public function getKobanList($no_project)
    {
        $sql = "
            SELECT * 
            FROM public.koban_inf A 
            WHERE EXISTS 
                (SELECT no_cyu
                FROM public.project_cyuban B
                WHERE no_project = :no_project
                    AND B.no_cyu = A.no_cyu
                )
            ORDER BY no_cyu, no_ko
        ";
        
        $stmt = $this->pdo->prepare($sql);
        $project = (int)$no_project;
        $stmt->bindValue(':no_project', $project, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll();
        
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
        
        foreach ($result as $ar) {
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
                $ar['yn_ttov'] = $list['yn_ttov'];
                $ar['yn_tsoneki'] = $list['yn_tsoneki'];
                $ar['ri_tsoneki'] = empty($list['yn_ttov'])?
                    0.0:$list['yn_tsoneki'] / $list['yn_ttov'] * 100;
                $ar['nm_biko'] = $list['nm_biko'];
            } else {
                $ar['yn_ttov'] = 0;
                $ar['yn_tsoneki'] = 0;
                $ar['ri_tsoneki'] = 0.0;
                $ar['nm_biko'] = '';
            }
            $items[] = $ar;
        }
        return $items;
    }
    
    /**
    *   項番月別リスト
    *
    *   @param string $no_project プロジェクト番号
    *   @return array
    */
    public function getKobanMonList($no_project)
    {
        $sql = "
            SELECT A.no_cyu, A.no_ko, A.cd_bumon
                , A.dt_pkansei_m, A.nm_syohin 
                , B.cd_genka_yoso, B.cd_tanto
                , B.kb_cyunyu, B.dt_kanjyo 
                , (B.tm_cyokka + B.yn_cyokuzai + B.yn_etc + B.yn_ryohi) AS yn_cyunyu
                , C.tanto_name AS nm_tanto 
            FROM 
                (SELECT no_cyu, no_ko, cd_bumon, dt_pkansei_m, nm_syohin 
                FROM public.koban_inf Z
                WHERE EXISTS
                    (SELECT no_cyu 
                    FROM public.project_cyuban Y
                    WHERE no_project = :no_project 
                        AND Y.no_cyu = Z.no_cyu
                    )
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
                WHERE  
                    cd_genka_yoso = 'B' 
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
        $project = (int)$no_project;
        $stmt1->bindValue(':no_project', $project, PDO::PARAM_INT);
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
                (SELECT no_cyu, no_ko, cd_bumon, dt_pkansei_m, nm_syohin 
                FROM public.koban_inf Z
                WHERE EXISTS
                    (SELECT no_cyu 
                    FROM public.project_cyuban Y
                    WHERE no_project = :no_project 
                        AND Y.no_cyu = Z.no_cyu
                    )
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
                WHERE  
                    (cd_genka_yoso = 'A' OR cd_genka_yoso = 'C')
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
        $project = (int)$no_project;
        $stmt2->bindValue(':no_project', $project, PDO::PARAM_INT);
        $stmt2->execute();
        $result2 = $stmt2->fetchAll();
        return array_merge($result1, $result2);
    }
}
