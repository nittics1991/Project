<?php
/**
*   CyubanSonekiDispGridCyubanSectionModel
*
*   @version 180517
*/
namespace seiban_kanri2\model;

use seiban_kanri2\model\CyubanSonekiDispGridModel;

class CyubanSonekiDispGridCyubanSectionModel extends CyubanSonekiDispGridModel
{
    /**
    *   {inherit}
    **/
    protected function buildGridSql(
        $kb_nendo,
        $cd_bumon,
        $chk_nendo_all,
        $chk_kansei,
        $cd_tanto,
        $cd_kisyu,
        $no_bunya_eigyo,
        $no_bunya_seizo
    ) {
        $sql = "
            SELECT
                D.no_cyu ,D.kb_keikaku 
				, D.yn_tov
				, D.tm_pcyokka, D.yn_pcyokka, D.yn_pcyokuzai, D.yn_pryohi, D.yn_petc 
				, D.tm_rcyokka, D.yn_rcyokka, D.yn_rcyokuzai, D.yn_rryohi, D.yn_retc 
				, D.tm_ycyokka, D.yn_ycyokka, D.yn_ycyokuzai, D.yn_yryohi, D.yn_yetc 
				, E.kb_cyumon, E.dt_puriage, E.nm_syohin, E.nm_setti, E.nm_user
				, E.dt_uriage, E.dt_hatuban, E.nm_tanto, E.dt_hakkou 
                , E.yn_sp, E.yn_net, E.yn_arari
				, F.dt_kakunin 
				, G.tanto_name AS nm_kakunin 
				, H.no_project 
				, M.dt_cyunyu 
				, N.se_tanto AS nm_sien 
				, O.u_chu_no, O.approved_by2, O.u_sei_no, O.mitu_no 
				, P.nm_project
                , Q.nm_kisyu
                , R.nm_bunya AS nm_bunya_eigyo
                , S.nm_bunya AS nm_bunya_seizo
			FROM
				(SELECT no_cyu
					, BOOL_AND(kb_keikaku) AS kb_keikaku
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
					(SELECT no_cyu
						, CAST(kb_keikaku AS boolean) 
						, yn_tov
						, tm_pcyokka, yn_pcyokka, yn_pcyokuzai, yn_pryohi, yn_petc 
						, tm_rcyokka, yn_rcyokka, yn_rcyokuzai, yn_rryohi, yn_retc 
						, tm_ycyokka, yn_ycyokka, yn_ycyokuzai, yn_yryohi, yn_yetc 
					FROM public.koban_inf A 
					WHERE 1 = 1 
		";
        
        if ($cd_bumon != 'all') {
            $sql .= " AND cd_bumon = :cd_bumon ";
        }
        
        if ($chk_nendo_all == '1') {
            $sql .= " AND kb_nendo >= :kb_nendo ";
        } else {
            $sql .= " AND kb_nendo = :kb_nendo ";
        }
        
        if (!is_null($cd_tanto)) {
            if (mb_substr($cd_tanto, 0, 1) == '!') {
                $cd_tanto = mb_substr($cd_tanto, 1, mb_strlen($cd_tanto));
                $sql .= "
                    AND no_cyu NOT IN (
                        SELECT no_cyu
                        FROM public.seiban_tanto
                        WHERE cd_tanto = :cd_tanto
                        ) 
                ";
                $bind['cd_tanto'] = $cd_tanto;
            } elseif ($cd_tanto != '') {
                $sql .= "
                    AND no_cyu IN (
                        SELECT no_cyu
                        FROM public.seiban_tanto
                        WHERE cd_tanto = :cd_tanto
                        )
                ";
                $bind['cd_tanto'] = $cd_tanto;
            }
        }
        
        if ($chk_kansei == '1') {
            $sql .= " AND EXISTS 
						(SELECT DISTINCT no_cyu 
						FROM public.cyuban_inf BB 
						WHERE dt_uriage = '' AND BB.no_cyu = A.no_cyu 
			)";
        }
        
        $sql .= ") C 
				GROUP BY no_cyu 
				) D 
			LEFT JOIN 
				(SELECT DISTINCT no_cyu, kb_cyumon, dt_puriage
                    , nm_syohin, nm_setti, nm_user
					, dt_uriage, dt_hatuban, nm_tanto, dt_hakkou 
                    , yn_sp, yn_net, (yn_sp - yn_net) AS yn_arari
				FROM public.cyuban_inf
				) E 
				ON E.no_cyu = D.no_cyu
			LEFT JOIN public.hatuban_inf F 
				ON F.no_cyu = D.no_cyu AND F.dt_hatuban = E.dt_hatuban 
			LEFT JOIN public.mst_tanto G 
				ON G.tanto_code = F.cd_tanto 
			LEFT JOIN public.project_cyuban H 
				ON H.no_cyu = D.no_cyu 
			LEFT JOIN 
				(SELECT no_cyu, MAX(dt_cyunyu) AS dt_cyunyu 
				FROM public.cyunyu_inf 
				WHERE 1 = 1 
		";
        
        if ($cd_bumon != 'all') {
            $sql .= " AND cd_bumon = :cd_bumon ";
        }
        
        $sql .= "
				GROUP BY no_cyu
				) M 
				ON M.no_cyu = D.no_cyu
			LEFT JOIN symphony.tpal0030 N 
				ON N.chuban = D.no_cyu 
			LEFT JOIN symphony.tpal0010 O 
				ON O.chuban = D.no_cyu
			LEFT JOIN 
				(SELECT DISTINCT no_project, nm_project 
				FROM public.project_inf 
				) P 
				ON P.no_project = H.no_project 
            LEFT JOIN (
                SELECT Q1.chuban, Q1.mitu_no, Q1.kisyu_cd
                    , Q2.kisyu_name AS nm_kisyu
                FROM symphony.tpal0010 Q1
                JOIN symphony.tmal0160 Q2
                    ON Q2.kisyu_cd = Q1.kisyu_cd
                ) Q
                ON Q.chuban = D.no_cyu
            LEFT JOIN (
                SELECT no_mitumori, cd_bunya, nm_bunya
                FROM public.mitumori_inf R1
                JOIN public.mst_mitumori_bunya R2
                    ON R2.id_mitumori_bunya = R1.cd_bunya
                ) R
                ON R.no_mitumori = Q.mitu_no
            LEFT JOIN (
                SELECT S1.no_cyu
                    , S2.no_bunya
                    , S2.nm_bunya
                FROM public.cyuban_bunya S1
                JOIN public.mst_bunya_seizo S2
                    ON S2.no_bunya = S1.no_bunya
                ) S
                ON S.no_cyu = D.no_cyu
            WHERE 1 = 1
                AND E.dt_puriage >= :yyyymm
        ";
        
        if (!empty($cd_kisyu)) {
            $sql .= " AND Q.kisyu_cd = :kisyu";
            $bind['kisyu'] = $cd_kisyu;
        }
        
        if (!is_null($no_bunya_eigyo) && $no_bunya_eigyo != '') {
            $sql .= " AND R.cd_bunya = :bunya_eigyo";
            $bind['bunya_eigyo'] = $no_bunya_eigyo;
        }
        
        if (!is_null($no_bunya_seizo) && $no_bunya_seizo != '') {
            $sql .= " AND S.no_bunya = :bunya_seizo";
            $bindInt['bunya_seizo'] = (int)$no_bunya_seizo;
        }
        
        $sql .= "
            ORDER BY E.dt_puriage, E.kb_cyumon, D.no_cyu 
		";
        return $sql;
    }
}
