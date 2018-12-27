<?php
/**
*   QUERY
*
*   @version 180918
*/
namespace jyukyu_inf\model;

use Concerto\standard\Query;
use Concerto\Validate;

class QueryJyukyuKanriDisp extends Query
{
    /**
    *   Columns
    *
    *   @val array
    */
    protected static $schema = [
        'kb_nendo', 'cd_bumon', 'chk_nendo_all', 'chk_kansei',
    ];
    
    public function isValidKb_nendo($val)
    {
        if (!isset($val) || $val == '') {
            return true;
        }
        return Validate::isNendo($val);
    }
    
    public function isValidCd_bumon($val)
    {
        if (!isset($val) || $val == '' || $val == 'all') {
            return true;
        }
        return Validate::isBumon($val);
    }
    
    public function isValidChk_nendo_all($val)
    {
        if (!isset($val) || $val == '') {
            return true;
        }
        return Validate::isTextBool($val);
    }
    
    public function isValidChk_kansei($val)
    {
        if (!isset($val) || $val == '') {
            return true;
        }
        return Validate::isTextBool($val);
    }
}
