<?php
/**
*   KobanTyouseiDispMntModel
*
*   @version 171018
*/
namespace seiban_kanri2\model;

use \Exception;
use \PDO;
use Concerto\database\KobanTyousei;
use Concerto\database\KobanTyouseiData;
use Concerto\database\OperationHist;
use Concerto\database\OperationHistData;
use Concerto\standard\Session;

class KobanTyouseiDispMntModel
{
    /**
    *   object
    *
    *   @val object
    */
    private $pdo;
    private $kobanTyousei;
    private $kobanTyouseiData;
    private $operationHist;
    private $operationHistData;
    private $globalSession;
    private $session;
    
    /**
    *   __construct
    *
    *   @param PDO $pdo
    *   @param KobanTyousei $kobanTyousei
    *   @param KobanTyouseiData $kobanTyouseiData
    *   @param OperationHist $operationHist
    *   @param OperationHistData $operationHistData
    *   @param Session $globalSession
    *   @param Session $session
    */
    public function __construct(
        PDO $pdo,
        KobanTyousei $kobanTyousei,
        KobanTyouseiData $kobanTyouseiData,
        OperationHist $operationHist,
        OperationHistData $operationHistData,
        Session $globalSession,
        Session $session
    ) {
        $this->pdo = $pdo;
        $this->kobanTyousei = $kobanTyousei;
        $this->kobanTyouseiData = $kobanTyouseiData;
        $this->operationHist = $operationHist;
        $this->operationHistData = $operationHistData;
        $this->globalSession = $globalSession;
        $this->session = $session;
    }
    
    /**
    *   データ更新
    *
    *   @param PostKobanTyouseiDisp $post
    */
    public function setTyouseiData(PostKobanTyouseiDisp $post)
    {
        $input_code = $this->globalSession->input_code;
        $no_cyu = $this->session->no_cyu;
        
        $kobanTyouseiData = clone $this->kobanTyouseiData;
        $kobanTyouseiData->no_cyu = $post->no_cyu?? $no_cyu;
        
        $data = clone $kobanTyouseiData;
        $data->update = date('Ymd His');
        $data->editor = $input_code;
        
        $operationHistData = clone $this->operationHistData;
        $operationHistData->ins_date = date('Ymd_His');
        $operationHistData->cd_tanto = $input_code;
        $operationHistData->nm_table = '1';
        $operationHistData->no_cyu = $no_cyu;
        
        $insert_list = [];
        $update_list = [];
        
        for ($i=0; $i < count($post->no_ko); $i++) {
            $obj = clone $data;
            $obj->no_ko  = $post['no_ko'][$i];
            
            $yn_ttov = trim($post['yn_ttov'][$i]);
            $yn_tsoneki = trim($post['yn_tsoneki'][$i]);
            
            $obj->yn_ttov = ($yn_ttov == '')?  '':$post['yn_ttov'][$i];
            $obj->yn_tsoneki = ($yn_tsoneki == '')? '':$post['yn_tsoneki'][$i];
            $obj->nm_biko = $post['nm_biko'][$i];
            
            $where = clone $kobanTyouseiData;
            $where->no_ko = $post['no_ko'][$i];
            $result = $this->kobanTyousei->select($where);
            
            if (count($result) == 0) {
                $insert_list[] = $obj;
            } else {
                $update_list[] = [$obj, $where];
            }
            
            $operationHistData->nm_after =
                "【調整値更新】{$no_cyu}";
        }
        
        try {
            $this->pdo->beginTransaction();
            
            if (count($update_list) > 0) {
                $this->kobanTyousei->update($update_list);
            }
            
            if (count($insert_list) > 0) {
                $this->kobanTyousei->insert($insert_list);
            }
            
            if (!empty($operationHistData->nm_after)) {
                $this->operationHist->insert([$operationHistData]);
            }
            $this->pdo->commit();
        } catch (Exception $e) {
            $this->pdo->rollback();
            throw $e;
        }
    }
}
