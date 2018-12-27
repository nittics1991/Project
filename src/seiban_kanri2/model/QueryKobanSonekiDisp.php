<?php
/**
*   QUERY
*
*   @version 170404
*/
namespace seiban_kanri2\model;

use Concerto\standard\Query;
use Concerto\Validate;

class QueryKobanSonekiDisp extends Query
{
    /**
    *   Columns
    *
    *   @val array
    *
    *   no_cyu, fg_koban, fg_project
    */
    protected static $schema = [
        'no_cyu', 'fg_koban', 'fg_project'
    ];
    
    public function isValidNo_cyu($val)
    {
        if (!is_null($val)) {
            if ($this->fg_project) {
                return Validate::isTextInt($val, 0);
            }
            return Validate::isCyuban($val);
        }
        return true;
    }
    
    public function isValidFg_koban($val)
    {
        if (!is_null($val)) {
            return Validate::isTextInt($val, 0, 1);
        }
        return true;
    }
    
    public function isValidFg_project($val)
    {
        if (!is_null($val)) {
            return Validate::isTextInt($val, 0, 1);
        }
        return true;
    }
}
