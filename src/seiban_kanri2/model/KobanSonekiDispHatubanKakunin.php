<?php
/**
*   KobanSonekiDispHatubanKakunin
*
*   @version 171012
*/
namespace seiban_kanri2\model;

use \Exception;
use Concerto\database\CyubanInf;
use Concerto\database\CyubanInfData;
use Concerto\database\HatubanInf;
use Concerto\database\HatubanInfData;

class KobanSonekiDispHatubanKakunin
{
    /**
    *   object
    *
    *   @val object
    */
    private $cyubanInf;
    private $cyubanInfData;
    private $seibanTanto;
    private $seibanTantoData;
    
    /**
    *   __construct
    *
    *   @param CyubanInf $cyubanInf
    *   @param CyubanInfData $cyubanInfData
    *   @param HatubanInf $hatubanInf
    *   @param HatubanInfData $hatubanInfData
    */
    public function __construct(
        CyubanInf $cyubanInf,
        CyubanInfData $cyubanInfData,
        HatubanInf $hatubanInf,
        HatubanInfData $hatubanInfData
    ) {
        $this->cyubanInf = $cyubanInf;
        $this->cyubanInfData = $cyubanInfData;
        $this->hatubanInf = $hatubanInf;
        $this->hatubanInfData = $hatubanInfData;
    }
    
    /**
    *   発番確認設定
    *
    *   @param string $cd_tanto 担当コード
    *   @param string $no_cyu 注番
    *   @param string $dt_hatuban 発番日
    *   @param bool $flg true:設定/false:解除
    */
    public function setHatubanKakunin($cd_tanto, $no_cyu, $dt_hatuban, $flg)
    {
        $hatubanInfData = clone $this->hatubanInfData;
        $hatubanInfData->no_cyu = $no_cyu;
        $hatubanInfData->dt_hatuban = $dt_hatuban;
        
        //新規
        if ($flg) {
            $hatubanInfData->dt_kakunin = date('Ymd');
            $hatubanInfData->cd_tanto = $cd_tanto;
            $this->hatubanInf->insert([$hatubanInfData]);
        //削除
        } else {
            $this->hatubanInf->delete([$hatubanInfData]);
        }
    }
    
    /**
    *   発番更新日
    *
    *   @param string $no_cyu 注番
    *   @return string
    */
    public function getDtHatuban($no_cyu)
    {
        $cyubanInfData = clone $this->cyubanInfData;
        $cyubanInfData->no_cyu = $no_cyu;
        $result = $this->cyubanInf->select($cyubanInfData);
        
        if (count($result) > 0) {
            $obj = $result[0];
            return empty($obj->dt_hatuban)? '':$obj->dt_hatuban;
        }
        return '';
    }
    
    /**
    *   発番確認日
    *
    *   @param string $no_cyu 注番
    *   @param string $dt_hatuban 発番更新日
    *   @return string
    */
    public function getDtKakunin($no_cyu, $dt_hatuban)
    {
        $hatubanInfData = clone $this->hatubanInfData;
        $hatubanInfData->no_cyu = $no_cyu;
        $hatubanInfData->dt_hatuban = $dt_hatuban;
        $result = $this->hatubanInf->select($hatubanInfData);
        
        if (count($result) > 0) {
            $obj = $result[0];
            return empty($obj->dt_kakunin)? '':$obj->dt_kakunin;
        }
        return '';
    }
}
