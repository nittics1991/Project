<?php
/**
*   FacadeModel
*
*   @version 171011
*/
namespace seiban_kanri2\model;

use \PDO;

class KobanSonekiExcelModel
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
    *   注入リスト
    *
    *   @param string $no_cyu 注番
    *   @param string $flg 0:計画/1:実績
    *   @return array
    */
    public function getCyunyuList($no_cyu, $flg)
    {
        $sql = "
            SELECT A.*
                , B. noki_day AS dt_noki 
            FROM public.cyunyu_inf A 
            LEFT JOIN 
                (SELECT * 
                FROM symphony.tsal0050 
                WHERE eda_no = '00' 
                    AND ko_no = '00' 
                ) B 
                ON B.irai_no = A.no_cyumon 
            WHERE A.no_cyu = :no_cyu 
                AND A.kb_cyunyu = :flg 
            ORDER BY A.dt_kanjyo, A.dt_cyunyu, A.cd_genka_yoso
                , A.cd_tanto, A.no_seq
        ";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':no_cyu', $no_cyu, PDO::PARAM_STR);
        $stmt->bindValue(':flg', $flg, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
