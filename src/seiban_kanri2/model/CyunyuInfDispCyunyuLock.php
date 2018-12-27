<?php
/**
*   CyunyuInfDispCyunyuLock
*
*   @version 171012
*/
namespace seiban_kanri2\model;

use \Exception;
use \PDO;
use Concerto\database\CyunyuLock;
use Concerto\database\CyunyuLockData;
use Concerto\database\OperationHist;
use Concerto\database\OperationHistData;

class CyunyuInfDispCyunyuLock
{
    /**
    *   object
    *
    *   @val object
    */
    private $cyunyuLock;
    private $cyunyuLockData;
    private $operationHist;
    private $operationHistData;
    
    /**
    *   __construct
    *
    *   @param PDO $pdo
    *   @param CyunyuLock $cyunyuLock
    *   @param CyunyuLockData $cyunyuLockData
    *   @param OperationHist $operationHist
    *   @param OperationHistData $operationHistData
    */
    public function __construct(
        PDO $pdo,
        CyunyuLock $cyunyuLock,
        CyunyuLockData $cyunyuLockData,
        OperationHist $operationHist,
        OperationHistData $operationHistData
    ) {
        $this->pdo = $pdo;
        $this->cyunyuLock = $cyunyuLock;
        $this->cyunyuLockData = $cyunyuLockData;
        $this->operationHist = $operationHist;
        $this->operationHistData = $operationHistData;
    }
    
    /**
    *   計画確定状態
    *
    *   @param string $no_cyu 注番
    *   @param string $no_ko 項番
    *   @return bool
    */
    public function isKeikakuSettei($no_cyu, $no_ko)
    {
        $cyunyuLockData = clone $this->cyunyuLockData;
        $cyunyuLockData->no_cyu = $no_cyu;
        $cyunyuLockData->no_ko = $no_ko;
        $result = $this->cyunyuLock->select($cyunyuLockData);
        return count($result) > 0;
    }
    
    /**
    *   計画確定設定
    *
    *   @param string $cd_tanto 担当コード
    *   @param string $no_cyu 注番
    *   @param string $no_ko
    *   @param bool $flg true:設定/false:解除
    */
    public function setCyunyuLock($cd_tanto, $no_cyu, $no_ko, $flg)
    {
        $cyunyuLockData = clone $this->cyunyuLockData;
        $cyunyuLockData->no_cyu = $no_cyu;
        $cyunyuLockData->no_ko = $no_ko;
        
        try {
            $this->pdo->beginTransaction();
            
            if ($flg) {
                $cyunyuLockData->cd_tanto = $cd_tanto;
                $cyunyuLockData->ins_date = date('Ymd His');
                $this->cyunyuLock->insert([$cyunyuLockData]);
                $comment = '【変更後】計画確定:' . $no_cyu . $no_ko;
            } else {
                $this->cyunyuLock->delete([$cyunyuLockData]);
                $comment = '【変更後】確定解除:' . $no_cyu . $no_ko;
            }
            
            $operationHistData = clone $this->operationHistData;
            $operationHistData->ins_date = date('Ymd_His');
            $operationHistData->cd_tanto = $cd_tanto;
            $operationHistData->nm_table = '2';
            $operationHistData->no_cyu = $no_cyu;
            $operationHistData->nm_after = $comment;
            $this->operationHist->insert([$operationHistData]);
            
            $this->pdo->commit();
        } catch (Exception $e) {
            $this->pdo->rollback();
            throw $e;
        }
    }
}
