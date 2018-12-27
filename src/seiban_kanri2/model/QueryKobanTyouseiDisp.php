<?php
/**
*   QUERY
*
*   @version 170404
*/
namespace seiban_kanri2\model;

use Concerto\standard\Query;
use Concerto\Validate;

class QueryKobanTyouseiDisp extends Query
{
    /**
    *   Columns
    *
    *   @val array
    *
    *   no_cyu
    */
    protected static $schema = ['no_cyu'];
    
    public function isValidNo_cyu($val)
    {
        if (!is_null($val)) {
            return Validate::isCyuban($val);
        }
        return true;
    }
}
