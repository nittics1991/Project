<?php
/**
*   FacadeModel
*
*   @version 171012
*/
namespace seiban_kanri2\model;

use \PDO;

class KobanTyouseiDispModel
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
    *   調整値リスト
    *
    *   @param string $no_cyu 注番
    *   @return array
    */
    public function getTyouseiList($no_cyu)
    {
        $sql = "
            SELECT A.no_cyu,  A.no_ko, A.nm_syohin 
                , A.yn_tov
                , A.yn_tov - (A.yn_ycyokka + A.yn_ycyokuzai + A.yn_yetc + A.yn_yryohi) AS yn_soneki 
                , B.yn_ttov, B.yn_tsoneki, B.nm_biko 
            FROM koban_inf A 
            LEFT JOIN koban_tyousei B 
                ON B.no_cyu = A.no_cyu 
                    AND B.no_ko = A.no_ko 
            WHERE A.no_cyu = :no_cyu
            ORDER BY A.no_cyu, A.no_ko 
        ";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':no_cyu', $no_cyu, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
