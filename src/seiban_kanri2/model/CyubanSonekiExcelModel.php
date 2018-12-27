<?php
/**
*   Model
*
*   @version 180522
*/
namespace seiban_kanri2\model;

use \PDO;
use Concerto\FiscalYear;
use seiban_kanri2\model\CyubanSonekiModel;

class CyubanSonekiExcelModel
{
    /**
    *   object
    *
    *   @val object
    */
    private $pdo;
    private $cyubanSonekiDispModel;
    
    /**
    *   __construct
    *
    *   @param PDO $pdo
    *   @param CyubanSonekiDispModel $cyubanSonekiDispModel
    */
    public function __construct(
        PDO $pdo,
        CyubanSonekiDispModel $cyubanSonekiDispModel
    ) {
        $this->pdo = $pdo;
        $this->cyubanSonekiDispModel = $cyubanSonekiDispModel;
    }
    
    /**
    *   注番リスト(課内のみ集計)
    *
    *   @param string $kb_nendo 年度
    *   @param string $cd_bumon 部門 or all
    *   @return array
    */
    public function getKobanList2($kb_nendo, $cd_bumon)
    {
        /**
        *   プリペア
        *
        *   @val resorce
        */
        static $stmt;
        
        if (is_null($stmt)) {
            $sql = "
				SELECT D.no_cyu ,D.no_ko, D.nm_syohin2 
				, D.yn_tov
				, D.tm_pcyokka, D.yn_pcyokka, D.yn_pcyokuzai, D.yn_pryohi, D.yn_petc 
				, D.tm_rcyokka, D.yn_rcyokka, D.yn_rcyokuzai, D.yn_rryohi, D.yn_retc 
				, D.tm_ycyokka, D.yn_ycyokka, D.yn_ycyokuzai, D.yn_yryohi, D.yn_yetc 
				, E.kb_cyumon, E.dt_puriage, E.nm_syohin, E.nm_setti, E.nm_user
				, F.u_chu_no , F.u_sei_no, F.mitu_no, F.sp 
                , G.nm_kisyu
                , H.nm_bunya AS nm_bunya_eigyo
                , J.nm_bunya AS nm_bunya_seizo
				FROM
					(SELECT no_cyu, no_ko, nm_syohin2
						, SUM(yn_tov) AS yn_tov 
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
					FROM
						(SELECT no_cyu, no_ko, nm_syohin AS nm_syohin2
							, yn_tov
							, tm_pcyokka, yn_pcyokka, yn_pcyokuzai, yn_pryohi, yn_petc 
							, tm_rcyokka, yn_rcyokka, yn_rcyokuzai, yn_rryohi, yn_retc 
							, tm_ycyokka, yn_ycyokka, yn_ycyokuzai, yn_yryohi, yn_yetc 
						FROM public.koban_inf 
						WHERE cd_bumon LIKE :bumon
							AND kb_nendo LIKE :nendo
							) A
					GROUP BY no_cyu, no_ko, nm_syohin2 
					) D
				LEFT JOIN 
					(SELECT DISTINCT no_cyu, kb_cyumon, dt_puriage
                        , nm_syohin, nm_setti, nm_user
					FROM public.cyuban_inf
					) E 
					ON E.no_cyu = D.no_cyu
				LEFT JOIN symphony.tpal0010 F 
					ON F.chuban = D.no_cyu
                LEFT JOIN (
                    SELECT G1.chuban, G1.mitu_no, G1.kisyu_cd
                        , G2.kisyu_name AS nm_kisyu
                    FROM symphony.tpal0010 G1
                    JOIN symphony.tmal0160 G2
                        ON G2.kisyu_cd = G1.kisyu_cd
                    ) G
                    ON G.chuban = D.no_cyu
                LEFT JOIN (
                    SELECT no_mitumori, cd_bunya, nm_bunya
                    FROM public.mitumori_inf H1
                    JOIN public.mst_mitumori_bunya H2
                        ON H2.id_mitumori_bunya = H1.cd_bunya
                    ) H
                    ON H.no_mitumori = G.mitu_no
                LEFT JOIN (
                    SELECT J1.no_cyu
                        , J2.no_bunya
                        , J2.nm_bunya
                    FROM public.cyuban_bunya J1
                    JOIN public.mst_bunya_seizo J2
                        ON J2.no_bunya = J1.no_bunya
                    ) J
                    ON J.no_cyu = D.no_cyu
                WHERE E.dt_puriage >= :yyyymm
				ORDER BY E.dt_puriage, E.kb_cyumon, D.no_cyu, D.no_ko 
			";
        
            $stmt = $this->pdo->prepare($sql);
        }
        
        $bumon = $cd_bumon . '%';
        $nendo = $kb_nendo . '%';
        
        $stmt->bindParam(':bumon', $bumon, PDO::PARAM_STR);
        $stmt->bindParam(':nendo', $nendo, PDO::PARAM_STR);
        
        $yyyymm = FiscalYear::getNendoyyyymm($kb_nendo);
        $stmt->bindParam(':yyyymm', $yyyymm[0], PDO::PARAM_STR);
        
        $stmt->execute();
        $result = $stmt->fetchAll();
        
        $data_list = [];
        
        if (count($result) == 0) {
            return [];
        }
        
        foreach ($result as $list) {
            if (!empty($list)) {
                $ar = $this->calProfitAndLossFromKobanList($list);
                $data_list[] = array_merge($list, $ar);
            }
        }
        return $data_list;
    }
        
    /**
    *   項番データから損益計算
    *
    *   @param array $list sql result dataset
    *   @return array
    **/
    private function calProfitAndLossFromKobanList(array $list)
    {
        $yn_pcyunyu = $list['yn_pcyokka'] + $list['yn_pcyokuzai']
            + $list['yn_pryohi'] + $list['yn_petc'];
        $yn_psoneki = $list['yn_tov'] - $yn_pcyunyu;
        $ri_psoneki = (empty($list['yn_tov']))?
            0.0:sprintf('%4.1f', round(($yn_psoneki / $list['yn_tov']) * 100, 1));
        
        $yn_ycyunyu = $list['yn_ycyokka'] + $list['yn_ycyokuzai']
            + $list['yn_yryohi'] + $list['yn_yetc'];
        $yn_ysoneki = $list['yn_tov'] - $yn_ycyunyu;
        $ri_ysoneki = (empty($list['yn_tov']))?
            0.0:sprintf('%4.1f', round(($yn_ysoneki / $list['yn_tov']) * 100, 1));
        
        $yn_rcyunyu = $list['yn_rcyokka'] + $list['yn_rcyokuzai']
            + $list['yn_rryohi'] + $list['yn_retc'];
        $yn_rsoneki = $list['yn_tov'] - $yn_rcyunyu;
        $ri_rsoneki = (empty($list['yn_tov']))?
            0.0:sprintf('%4.1f', round(($yn_rsoneki / $list['yn_tov']) * 100, 1));
        
        return [
            'yn_pcyunyu' => $yn_pcyunyu,
            'yn_psoneki' => $yn_psoneki,
            'ri_psoneki' => $ri_psoneki,
            'yn_ycyunyu' => $yn_ycyunyu,
            'yn_ysoneki' => $yn_ysoneki,
            'ri_ysoneki' => $ri_ysoneki,
            'yn_rcyunyu' => $yn_rcyunyu,
            'yn_rsoneki' => $yn_rsoneki,
            'ri_rsoneki' => $ri_rsoneki
        ];
    }
    
    /**
    *   調整値リスト(注番別)
    *
    *   @param string $kb_nendo
    *   @param string $cd_bumon
    *   @return array
    **/
    public function getTyouseiCyubanList($kb_nendo, $cd_bumon)
    {
        /**
        *   プリペア
        *
        *   @val resorce
        */
        static $stmt;
        
        if (is_null($stmt)) {
            $sql = "
				SELECT B.no_cyu
					, SUM(B.yn_ttov) AS  yn_ttov
					, SUM(B.yn_tsoneki) AS  yn_tsoneki
					
					, SUM(
						CASE WHEN B.yn_ttov IS NULL THEN A.yn_tov ELSE B.yn_ttov END 
						) AS  cal_ttov
					, SUM(
						CASE WHEN B.yn_tsoneki IS NULL THEN A.yn_psoneki ELSE B.yn_tsoneki END 
						) AS  cal_tpsoneki
					, SUM(
						CASE WHEN B.yn_tsoneki IS NULL THEN A.yn_rsoneki ELSE B.yn_tsoneki END 
						) AS  cal_trsoneki
					, SUM(
						CASE WHEN B.yn_tsoneki IS NULL THEN A.yn_ysoneki ELSE B.yn_tsoneki END 
						) AS  cal_tysoneki
				FROM 
					(SELECT * 
						, (yn_tov - yn_pcyokka - yn_pcyokuzai - yn_petc - yn_pryohi) AS yn_psoneki
						, (yn_tov - yn_rcyokka - yn_rcyokuzai - yn_retc - yn_rryohi) AS yn_rsoneki
						, (yn_tov - yn_ycyokka - yn_ycyokuzai - yn_yetc - yn_yryohi) AS yn_ysoneki
					FROM public.koban_inf
					WHERE (kb_nendo = :nendo
						AND cd_bumon = :bumon 
						) IS NOT FALSE
					) A 
				LEFT JOIN 
					(SELECT no_cyu, no_ko 
						, CASE yn_ttov WHEN '' THEN null ELSE CAST(yn_ttov AS INTEGER) END AS yn_ttov
						, CASE yn_tsoneki WHEN '' THEN null ELSE CAST(yn_tsoneki AS INTEGER) END AS yn_tsoneki
					FROM public.koban_tyousei 
					) B
					ON B.no_cyu = A.no_cyu 
						AND B.no_ko = A.no_ko 
				GROUP BY B.no_cyu
				ORDER BY B.no_cyu
			";
            
            $stmt = $this->pdo->prepare($sql);
        }
        
        $stmt->bindParam(':nendo', $kb_nendo, PDO::PARAM_STR);
        $stmt->bindParam(':bumon', $cd_bumon, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
    *   調整値リスト(項番別)
    *
    *   @param string $kb_nendo
    *   @param string $cd_bumon
    *   @return array
    **/
    public function getTyouseiKobanList($kb_nendo, $cd_bumon)
    {
        /**
        *   プリペア
        *
        *   @val resorce
        */
        static $stmt;
        
        if (is_null($stmt)) {
            $sql = "
				SELECT B.no_cyu, B.no_ko
					, CASE yn_ttov WHEN '' THEN null ELSE yn_ttov END AS yn_ttov
					, CASE yn_tsoneki WHEN '' THEN null ELSE yn_tsoneki END AS yn_tsoneki
					, nm_biko
				FROM 
					(SELECT * 
					FROM public.koban_inf
					WHERE (kb_nendo = :nendo
						AND cd_bumon = :bumon 
                        ) IS NOT FALSE
					) A 
				JOIN public.koban_tyousei B 
					ON B.no_cyu = A.no_cyu 
						AND B.no_ko = A.no_ko 
				ORDER BY B.no_cyu, A.no_ko 
			";
            
            $stmt = $this->pdo->prepare($sql);
        }
        
        $stmt->bindParam(':nendo', $kb_nendo, PDO::PARAM_STR);
        $stmt->bindParam(':bumon', $cd_bumon, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
    *   計画リスト
    *
    *   @param string $kb_nendo
    *   @param string $cd_bumon
    *   @return array
    **/
    public function getKeikakuList($kb_nendo, $cd_bumon)
    {
        /**
        *   プリペア
        *
        *   @val resorce
        */
        static $stmt;
        
        if (is_null($stmt)) {
            $sql = "
				SELECT B.* 
				FROM public.koban_inf A
				JOIN public.cyunyu_inf B 
					ON B.no_cyu = A.no_cyu 
						AND B.no_ko = A.no_ko 
				WHERE (A.kb_nendo = :nendo 
					AND A.cd_bumon = :bumon 
					AND B.kb_cyunyu = '0' 
                    ) IS NOT FALSE
				ORDER BY B.no_cyu, B.no_ko, B.cd_genka_yoso, B.dt_kanjyo 
			";
            
            $stmt = $this->pdo->prepare($sql);
        }
        
        $stmt->bindParam(':nendo', $kb_nendo, PDO::PARAM_STR);
        $stmt->bindParam(':bumon', $cd_bumon, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
    *   {inherit}
    */
    public function getCyubanList2(
        $kb_nendo,
        $cd_bumon,
        $chk_nendo_all,
        $chk_kansei,
        $chk_job,
        $cd_tanto
    ) {
        return $this->cyubanSonekiDispModel->getCyubanList(
            $kb_nendo,
            $cd_bumon,
            $chk_nendo_all,
            $chk_kansei,
            $chk_job,
            $cd_tanto
        );
    }
    
    /**
    *   注文記号リスト
    *
    *   @return array [cd_code => nm_code]
    */
    public function getCyumonKigoList()
    {
        return $this->cyubanSonekiDispModel->getCyumonKigoList();
    }
}
