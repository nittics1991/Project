<?php
/**
*   QUERY
*
*   @version 180913
*/
namespace jyukyu_inf\model;

use Concerto\standard\Query;
use Concerto\Validate;

class QueryJyukyuInfDisp extends Query
{
    /**
    *   Columns
    *
    *   @val array
    */
    protected static $schema = [
        'no_cyu', 'no_page',
    ];
    
    public function isValidNo_cyu($val)
    {
        if (!isset($val) || $val == '') {
            return true;
        }
        return Validate::isCyuban($val);
    }
    
    public function isValidNo_page($val)
    {
        return Validate::isTextInt($val, 0);
    }
}
