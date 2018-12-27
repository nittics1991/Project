<?php
/**
*   QUERY
*
*   @version 161102
*/
namespace seiban_kanri2\model;

use Concerto\standard\Query;
use Concerto\Validate;

class QueryKobanSonekiChartMain extends Query
{
    /**
    *   Columns
    *
    *   @val array
    *
    *   no_cyu, no_ko, id, file
    */
    protected static $schema = [
        'no_cyu', 'no_ko', 'id', 'file'
    ];
    
    public function isValidNo_cyu($val)
    {
        if (!is_null($val)) {
            return Validate::isCyuban($val);
        }
        return false;
    }
    
    public function isValidNo_ko($val)
    {
        if (!is_null($val)) {
            return Validate::isKoban($val);
        }
        return true;
    }
    
    public function isValidId($val)
    {
        if (empty($val)) {
            return false;
        }
        return Validate::isTextInt($val, 1, 2);
    }
    
    public function isValidFile($val)
    {
        if (empty($val)) {
            return false;
        }
        
        if (mb_strpos($val, './tmp/') !== 0) {
            return false;
        }
        
        if (!mb_ereg_match('.+[a-z0-9]+\.pngx?$', $val)) {
            return false;
        }
        return true;
    }
}
