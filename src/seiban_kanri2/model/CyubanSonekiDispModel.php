<?php
/**
*   Model
*
*   @version 1811102
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
use Concerto\FiscalYear;
use seiban_kanri2\model\CyubanSonekiDispGridModel;

class CyubanSonekiDispModel
{
    /**
    *   object
    *
    *   @val object
    */
    private $pdo;
    private $cyokkaMonKeikaku;
    private $cyubanInf;
    private $cyubanInfData;
    private $mstBumon;
    private $mstBumonData;
    private $mstBunyaSeizo;
    private $mstMitumoriBunya;
    private $mstTanto;
    private $tmal0160;
    private $gridModel;
    
    /**
    *   __construct
    *
    *   @param PDO $pdo
    *   @param CyokkaMonKeikaku $cyokkaMonKeikaku
    *   @param CyubanInf $cyubanInf
    *   @param CyubanInfData $cyubanInfData
    *   @param MstBumon $mstBumon
    *   @param MstBumonData $mstBumonData
    *   @param MstBunyaSeizo $mstBunyaSeizo
    *   @param MstMitumoriBunya $mstMitumoriBunya
    *   @param MstTanto $mstTanto
    *   @param Tmal0160 $tmal0160
    *   @param CyubanSonekiDispGridModel $gridModel
    */
    public function __construct(
        PDO $pdo,
        CyokkaMonKeikaku $cyokkaMonKeikaku,
        CyubanInf $cyubanInf,
        CyubanInfData $cyubanInfData,
        MstBumon $mstBumon,
        MstBumonData $mstBumonData,
        MstBunyaSeizo $mstBunyaSeizo,
        MstMitumoriBunya $mstMitumoriBunya,
        MstTanto $mstTanto,
        Tmal0160 $tmal0160,
        CyubanSonekiDispGridModel $gridModel
    ) {
        $this->pdo = $pdo;
        $this->cyokkaMonKeikaku = $cyokkaMonKeikaku;
        $this->cyubanInf = $cyubanInf;
        $this->cyubanInfData = $cyubanInfData;
        $this->cyunyuInf = $cyunyuInf;
        $this->mstBumon = $mstBumon;
        $this->mstBumonData = $mstBumonData;
        $this->mstBunyaSeizo = $mstBunyaSeizo;
        $this->mstMitumoriBunya = $mstMitumoriBunya;
        $this->mstTanto = $mstTanto;
        $this->tmal0160 = $tmal0160;
        $this->gridModel = $gridModel;
    }
    
    /**
    *   年度リスト
    *
    *   @return array [[kb_nendo, nm_nendo]]
    */
    public function getNendoList()
    {
        $result = $this->cyubanInf->getNendoList();
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
    *   @param string $nendo 年度
    *   @return array [[cd_bumin, nm_bumon]]
    */
    public function getBumonList($nendo)
    {
        return $this->mstBumon->getCyubanBumon($nendo);
    }
    
    /**
    *   担当リスト
    *
    *   @param string $cd_bumon 部門コード
    *   @return array array(array(cd_tanto, nm_tanto))
    */
    public function getTantoList($cd_bumon)
    {
        if (!is_null($cd_bumon)) {
            return $this->mstTanto->getTantoListPriotityBumon($cd_bumon);
        }
        return $this->mstTanto->getTantoListPriotityBumon();
    }
    
    /**
    *   予算機種リスト
    *
    *   @return array
    **/
    public function getKisyuList()
    {
        return $this->tmal0160->getKisyuList();
    }
    
    /**
    *   分野リスト(営業)
    *
    *   @return array
    **/
    public function getBunyaEigyoList()
    {
        return $this->mstMitumoriBunya->getBunyaList();
    }
    
    /**
    *   分野リスト(製造)
    *
    *   @return array
    **/
    public function getBunyaSeizoList()
    {
        return $this->mstBunyaSeizo->getBunyaList();
    }
    
    /**
    *   注番リスト
    *
    *   @param string $kb_nendo 年度
    *   @param string $cd_bumon 部門 or all
    *   @param string $chk_nendo_all 1:指定年度以降を含む
    *   @param string $chk_kansei 1:未売上のみ
    *   @param string $cd_tanto 担当
    *   @param string $cd_kisyu 予算機種
    *   @param string $no_bunya_eigyo 分野(営業)
    *   @param string $no_bunya_seizo 分野(製造)
    *   @return array
    */
    public function getCyubanList(
        $kb_nendo,
        $cd_bumon,
        $chk_nendo_all,
        $chk_kansei,
        $cd_tanto,
        $cd_kisyu = null,
        $no_bunya_eigyo = null,
        $no_bunya_seizo = null
    ) {
        return $this->gridModel->getList(
            $kb_nendo,
            $cd_bumon,
            $chk_nendo_all,
            $chk_kansei,
            $cd_tanto,
            $cd_kisyu,
            $no_bunya_eigyo,
            $no_bunya_seizo
        );
    }
    
    /**
    *   注番集計データ
    *
    *   @param string $kb_nendo 年度
    *   @param string $cd_bumon 部門 or all
    *   @param string $kb_cyumon 0:受注/1:A/2:B/3:C
    *   @return array
    */
    public function getCyubanAggregeteList(
        $kb_nendo,
        $cd_bumon,
        $kb_cyumon
    ) {
        return $this->gridModel->getCyubanAggregeteList(
            $kb_nendo,
            $cd_bumon,
            $kb_cyumon
        );
    }
    
    /**
    *   WF管理不要リスト
    *
    *   @param string $kb_nendo 年度
    *   @return array [[no_cyu, fg_fuyo]]
    */
    public function getKanriFuyoList($kb_nendo)
    {
        /**
        *   プリペア
        *
        *   @val resorce
        */
        static $stmt;
        
        if (is_null($stmt)) {
            $sql = "
				SELECT AA.no_cyu
					, BOOL_AND(AA.fg_fuyo) AS fg_fuyo
				FROM 
					(SELECT A.no_cyu, CAST(A.fg_fuyo AS boolean) AS fg_fuyo
					FROM public.wf_new A 
					JOIN 
						(SELECT DISTINCT no_cyu 
						FROM public.cyuban_inf 
						WHERE kb_nendo = :nendo 
						) B
						ON B.no_cyu = A.no_cyu 	
				) AA 
				GROUP BY no_cyu 
				ORDER BY no_cyu 
			";
            
            $stmt = $this->pdo->prepare($sql);
        }
        
        $stmt->bindParam(':nendo', $kb_nendo, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
    *   WFアラームリスト
    *
    *   @return array
    */
    public function getWfCautionList()
    {
        /**
        *   プリペア
        *
        *   @val resorce
        */
        static $stmt;
        
        if (is_null($stmt)) {
            $sql = "
				SELECT no_cyu
					, dt_drsi_p, dt_drsi_r
					, dt_drst_p, dt_drst_r
					, dt_cpi_p, dt_cpi_r
					, dt_cpt_p, dt_cpt_r
				FROM public.wf_new A 
				WHERE fg_fuyo = '0' 
					AND no_rev = 
						(SELECT MAX(no_rev) 
						FROM public.wf_new C 
						WHERE C.no_cyu = A.no_cyu 
						GROUP BY no_cyu 
						)
					AND EXISTS
						(SELECT DISTINCT no_cyu 
							FROM public.cyuban_inf B
							WHERE dt_uriage = ''
								AND B.no_cyu = A.no_cyu
						)
					AND (
						(dt_drsi_p != '' AND dt_drsi_r = '' AND dt_drsi_p 
                            <= TO_CHAR(CURRENT_TIMESTAMP + '+1 DAY', 'YYYYMMDD'))
						OR (dt_drst_p != '' AND dt_drst_r = '' AND dt_drst_p 
                            <= TO_CHAR(CURRENT_TIMESTAMP + '+1 DAY', 'YYYYMMDD'))
						OR (dt_cpi_p != '' AND dt_cpi_r = '' AND dt_cpi_p 
                            <= TO_CHAR(CURRENT_TIMESTAMP + '+1 DAY', 'YYYYMMDD'))
						OR (dt_cpt_p != '' AND dt_cpt_r = '' AND dt_cpt_p 
                            <= TO_CHAR(CURRENT_TIMESTAMP + '+1 DAY', 'YYYYMMDD'))
					)
			";
            
            $stmt = $this->pdo->prepare($sql);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }
        
    /**
    *   注文記号リスト
    *
    *   @return array [cd_code => nm_code]
    */
    public function getCyumonKigoList()
    {
        $cyubanInfData = clone $this->cyubanInfData;
        return $cyubanInfData->getKbCyumon();
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
    *   予算集計リスト
    *
    *   @param string $kb_nendo 年度
    *   @param string $cd_bumon 部門コード
    *   @return array array(
    *       array(dt_kado, tm_zitudo, tm_teizikan, tm_zangyo, tm_cyokka
    *       , tm_zitudo_m, tm_teizikan_m, tm_zangyo_m, tm_cyokka_m
    *       , yn_yosan, y_soneki)
    */
    public function getBudgetAggregete($kb_nendo, $cd_bumon)
    {
        return $this->cyokkaMonKeikaku->getAggregate($kb_nendo, $cd_bumon);
    }
    
    /**
    *   部門コードが発番権限を持つか
    *
    *   @param string $cd_bumon 部門コード
    *   @return bool
    */
    public function bumonHasHatuban($cd_bumon)
    {
        $mstBumonData = clone $this->mstBumonData;
        $mstBumonData->cd_bumon = $cd_bumon;
        $result = $this->mstBumon->select($mstBumonData);
        
        return is_array($result[0]) && $result[0]['fg_hatu'] == '1';
    }
    
    /**
    *   未承認判定
    *
    *   @param string $no_cyu
    *   @param string $kb_cyumon
    *   @param string $approved_by
    *   @return bool
    **/
    public function isUnapproved($no_cyu, $kb_cyumon, $approved_by)
    {
        return $kb_cyumon == '0' &&
            empty($approved_by) &&
            mb_substr($no_cyu, 3, 1) == '0';
    }
}
