<?php
/**
*   KobanSonekiDispSeibanTanto
*
*   @version 171012
*/
namespace seiban_kanri2\model;

use \Exception;
use Concerto\database\SeibanTanto;
use Concerto\database\SeibanTantoData;

class KobanSonekiDispSeibanTanto
{
    /**
    *   object
    *
    *   @val object
    */
    private $seibanTanto;
    private $seibanTantoData;
    
    /**
    *   __construct
    *
    *   @param SeibanTanto $seibanTanto
    *   @param SeibanTantoData $seibanTantoData
    */
    public function __construct(
        SeibanTanto $seibanTanto,
        SeibanTantoData $seibanTantoData
    ) {
        $this->seibanTanto = $seibanTanto;
        $this->seibanTantoData = $seibanTantoData;
    }
    
    /**
    *   製番担当設定
    *
    *   @param string $cd_tanto 担当コード
    *   @param string $no_cyu 注番
    *   @param bool $flg true:設定/false:解除
    */
    public function setSeibanTanto($cd_tanto, $no_cyu, $flg)
    {
        $seibanTantoData = clone $this->seibanTantoData;
        $seibanTantoData->cd_tanto = $cd_tanto;
        $seibanTantoData->no_cyu = $no_cyu;
        $seibanTantoData->no_ko = '';
        $seibanTantoData->no_seq = 'M';
        
        //新規
        if ($flg) {
            $seibanTantoData->ins_date = date('Ymd His');
            $this->seibanTanto->insert(array($seibanTantoData));
        //削除
        } else {
            $this->seibanTanto->delete(array($seibanTantoData));
        }
    }
    
    /**
    *   担当設定状態
    *
    *   @param string $cd_tanto 担当コード
    *   @param string $no_cyu 注番
    *   @return bool
    */
    public function isTantoSettei($cd_tanto, $no_cyu)
    {
        $seibanTantoData = clone $this->seibanTantoData;
        $seibanTantoData->cd_tanto = $cd_tanto;
        $seibanTantoData->no_cyu = $no_cyu;
        $seibanTantoData->no_ko = '';
        $seibanTantoData->no_seq = 'M';
        $result = $this->seibanTanto->select($seibanTantoData);
        return count($result) > 0;
    }
}
