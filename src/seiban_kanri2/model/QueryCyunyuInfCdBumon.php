<?php
/**
*   QUERY
*
*   @version 160727
*/
namespace seiban_kanri2\model;

use \RuntimeException;
use Concerto\standard\Query;
use Concerto\Validate;

class QueryCyunyuInfCdBumon extends Query
{
    /**
    *   Columns
    *
    *   @val array
    */
    protected static $schema = ['cd_bumon'];
    
    /**
    *   コンストラクタ
    *
    *   @throws RuntimeException
    */
    public function __construct()
    {
        if (!$this->isAjax()) {
            throw new RuntimeException("request AJAX is required");
        }
        parent::__construct();
    }
    
    public function isValidCd_bumon($val)
    {
        if (($val == '') || ($val == 'XXXXXXXX')) {
            return true;
        }
        return Validate::isBumon($val);
    }
}
