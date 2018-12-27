<?php
/**
*   QUERY
*
*   @version 170404
*/
namespace seiban_kanri2\model;

use Concerto\standard\Query;
use Concerto\Validate;

class QueryCyunyuInfDisp extends Query
{
    /**
    *   Columns
    *
    *   @val array
    *
    *   no_cyu, no_ko, fg_crt, no_seq
    */
    protected static $schema = [
        'no_cyu', 'no_ko', 'fg_crt', 'no_seq'
    ];
    
    public function isValidNo_cyu($val)
    {
        if (!is_null($val)) {
            return Validate::isCyuban($val);
        }
        return true;
    }
    
    public function isValidNo_ko($val)
    {
        if (!is_null($val)) {
            return Validate::isKoban($val);
        }
        return true;
    }
    
    public function isValidFg_crt($val)
    {
        $ans = true;
        if (!is_null($val) && ($val != '')) {
            if ((!mb_check_encoding($val)) || !preg_match('/^[0-2]$/', $val)) {
                $ans = false;
            }
        }
        return $ans;
    }
    
    public function isValidNo_seq($val)
    {
        if (!is_null($val)) {
            return Validate::isTextInt($val);
        }
        return true;
    }
}
