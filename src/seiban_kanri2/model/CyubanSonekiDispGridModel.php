<?php
/**
*   CyubanSonekiDispGridModel
*
*   @version 180517
*/
namespace seiban_kanri2\model;

use \PDO;
use Concerto\FiscalYear;

abstract class CyubanSonekiDispGridModel
{
    /**
    *   object
    *
    *   @val object
    */
    private $pdo;
    
    /**
    *   __construct
    *
    *   @param PDO $pdo
    */
    public function __construct(
        PDO $pdo
    ) {
        $this->pdo = $pdo;
    }
    
    /**
    *   buildGridSql
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
    **/
    abstract protected function buildGridSql(
        $kb_nendo,
        $cd_bumon,
        $chk_nendo_all,
        $chk_kansei,
        $cd_tanto,
        $cd_kisyu,
        $no_bunya_eigyo,
        $no_bunya_seizo
    );
    
    /**
    *   buildCyunyuSql
    *
    *   @param string $tablename
    *   @return string
    **/
    protected function buildCyunyuSql($tablename)
    {
        return "
            SELECT *
                , yn_pcyokka + yn_pcyokuzai + yn_pryohi + yn_petc AS yn_pcyunyu
                , yn_ycyokka + yn_ycyokuzai + yn_yryohi + yn_yetc AS yn_ycyunyu
                , yn_rcyokka + yn_rcyokuzai + yn_rryohi + yn_retc AS yn_rcyunyu
                FROM {$tablename}
        ";
    }
    
    /**
    *   buildProfitAndLossSql
    *
    *   @param string $tablename
    *   @return string
    **/
    protected function buildProfitAndLossSql($tablename)
    {
        return "
            SELECT *
                , yn_tov - yn_pcyunyu AS yn_psoneki
                , CASE WHEN yn_tov = 0
                    THEN 0
                    ELSE (CAST(yn_tov AS FLOAT) - CAST(yn_pcyunyu AS FLOAT))
                        / CAST(yn_tov AS FLOAT) * 100
                    END AS ri_psoneki
                , yn_tov - yn_ycyunyu AS yn_ysoneki
                , CASE WHEN yn_tov = 0
                    THEN 0
                    ELSE (CAST(yn_tov AS FLOAT) - CAST(yn_ycyunyu AS FLOAT))
                        / CAST(yn_tov AS FLOAT) * 100
                    END AS ri_ysoneki
                , yn_tov - yn_rcyunyu AS yn_rsoneki
                , CASE WHEN yn_tov = 0
                    THEN 0
                    ELSE (CAST(yn_tov AS FLOAT) - CAST(yn_rcyunyu AS FLOAT))
                        / CAST(yn_tov AS FLOAT) * 100
                    END AS ri_rsoneki
            FROM {$tablename}
        ";
    }
    
    /**
    *   注番リスト(注番単位)
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
    public function getList(
        $kb_nendo,
        $cd_bumon,
        $chk_nendo_all,
        $chk_kansei,
        $cd_tanto,
        $cd_kisyu,
        $no_bunya_eigyo,
        $no_bunya_seizo
    ) {
        static $stmt;
        static $args;
        
        if (!isset($stmt) || func_get_args() != $args) {
            $listSql = $this->buildGridSql(
                $kb_nendo,
                $cd_bumon,
                $chk_nendo_all,
                $chk_kansei,
                $cd_tanto,
                $cd_kisyu,
                $no_bunya_eigyo,
                $no_bunya_seizo
            );
            $cyunuySql = $this->buildCyunyuSql("list");
            $plSql = $this->buildProfitAndLossSql("cyunyu");
            
            $sql = "
                WITH list AS (
                    {$listSql}
                ), cyunyu AS (
                    {$cyunuySql}
                )
                {$plSql}
            ";
            
            $stmt = $this->pdo->prepare($sql);
            $args = func_get_args();
        }
        $stmt->bindParam(':kb_nendo', $kb_nendo, PDO::PARAM_STR);
        
        if ($cd_bumon != 'all') {
            $stmt->bindParam(':cd_bumon', $cd_bumon, PDO::PARAM_STR);
        }
        
        if (!empty($cd_tanto)) {
            if (mb_substr($cd_tanto, 0, 1) == '!') {
                $cd_tanto = mb_substr($cd_tanto, 1, mb_strlen($cd_tanto));
            }
            $stmt->bindParam(':cd_tanto', $cd_tanto, PDO::PARAM_STR);
        }
        
        if (!empty($cd_kisyu)) {
            $stmt->bindParam(':kisyu', $cd_kisyu, PDO::PARAM_STR);
        }
        
        if (!is_null($no_bunya_eigyo) && $no_bunya_eigyo != '') {
            $stmt->bindParam(':bunya_eigyo', $no_bunya_eigyo, PDO::PARAM_STR);
        }
        
        if (!is_null($no_bunya_seizo) && $no_bunya_seizo != '') {
            $stmt->bindValue(':bunya_seizo', (int)$no_bunya_seizo, PDO::PARAM_INT);
        }
        
        $yyyymm = FiscalYear::getNendoyyyymm($kb_nendo);
        $stmt->bindParam(':yyyymm', $yyyymm[0], PDO::PARAM_STR);
        
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
    *   注番集計リスト
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
        static $stmt;
        static $args;
        
        $args2 = func_get_args();
        array_pop($args2);
        
        if (!isset($stmt) || $argv2 != $args) {
            $listSql = $this->buildGridSql(
                $kb_nendo,
                $cd_bumon,
                '',
                '',
                '',
                null,
                null,
                null,
                null
            );
            $cyunyuSql = $this->buildCyunyuSql("agg");
            $plSql = $this->buildProfitAndLossSql("cyunyu");
            
            $sql = "
                WITH list AS (
                    {$listSql}
                ), agg AS (
                    SELECT
                        SUM(yn_tov) AS yn_tov
                        , SUM(yn_sp) AS yn_sp
                        , SUM(yn_arari) AS yn_arari
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
                    FROM list
                    WHERE (kb_cyumon = :cyumon) IS NOT FALSE
                ), cyunyu AS (
                    {$cyunyuSql}
                )
                {$plSql}
            ";
            $stmt = $this->pdo->prepare($sql);
            $args = $args2;
        }
        
        $stmt->bindParam(':kb_nendo', $kb_nendo, PDO::PARAM_STR);
        
        if ($cd_bumon != 'all') {
            $stmt->bindParam(':cd_bumon', $cd_bumon, PDO::PARAM_STR);
        }
        
        $stmt->bindParam(':cyumon', $kb_cyumon, PDO::PARAM_STR);
        
        $yyyymm = FiscalYear::getNendoyyyymm($kb_nendo);
        $stmt->bindParam(':yyyymm', $yyyymm[0], PDO::PARAM_STR);
        
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result[0];
    }
    
    /**
    *   注番集計リスト(月別)
    *
    *   @param string $kb_nendo 年度
    *   @param string $cd_bumon 部門 or all
    *   @return array
    */
    public function getCyubanMonAggregeteList(
        $kb_nendo,
        $cd_bumon
    ) {
        static $stmt;
        static $args;
        
        $args2 = func_get_args();
        array_pop($args2);
        
        if (!isset($stmt) || $argv2 != $args) {
            $listSql = $this->buildGridSql(
                $kb_nendo,
                $cd_bumon,
                '',
                '',
                '',
                null,
                null,
                null,
                null
            );
            $cyunyuSql = $this->buildCyunyuSql("agg");
            $plSql = $this->buildProfitAndLossSql("cyunyu");
            
            $sql = "
                WITH list AS (
                    {$listSql}
                ), agg AS (
                    SELECT
                        dt_puriage
                        , SUM(yn_tov) AS yn_tov
                        , SUM(yn_sp) AS yn_sp
                        , SUM(yn_arari) AS yn_arari
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
                    FROM list
                    GROUP BY dt_puriage
                ), cyunyu AS (
                    {$cyunyuSql}
                )
                {$plSql}
            ";
            $stmt = $this->pdo->prepare($sql);
            $args = $args2;
        }
        
        $stmt->bindParam(':kb_nendo', $kb_nendo, PDO::PARAM_STR);
        
        if ($cd_bumon != 'all') {
            $stmt->bindParam(':cd_bumon', $cd_bumon, PDO::PARAM_STR);
        }
        
        $yyyymm = FiscalYear::getNendoyyyymm($kb_nendo);
        $stmt->bindParam(':yyyymm', $yyyymm[0], PDO::PARAM_STR);
        
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }
}
