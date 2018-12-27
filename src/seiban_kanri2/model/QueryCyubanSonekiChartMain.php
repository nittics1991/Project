<?php
/**
*   QUERY
*
*   @version 170404
*/
namespace seiban_kanri2\model;

use Concerto\standard\Query;
use Concerto\Validate;

class QueryCyubanSonekiChartMain extends Query
{
    /**
    *   Columns
    *
    *   @val array
    *
    *   kb_nendo, cd_bumon, id, file
    */
    protected static $schema = [
        'kb_nendo', 'cd_bumon', 'id', 'file'
    ];
    
    public function isValidKb_nendo($val)
    {
        if (!is_null($val)) {
            return Validate::isNendo($val);
        }
        return false;
    }
    
    public function isValidCd_bumon($val)
    {
        if ($val == 'all') {
            return true;
        }
        return Validate::isBumon($val);
    }
    
    public function isValidId($val)
    {
        if (empty($val)) {
            return false;
        }
        return Validate::isTextInt($val, 1, 4);
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
