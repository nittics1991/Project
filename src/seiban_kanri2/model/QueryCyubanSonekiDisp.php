<?php
/**
*   QUERY
*
*   @version 180406
*/
namespace seiban_kanri2\model;

use Concerto\standard\Query;
use Concerto\Validate;

class QueryCyubanSonekiDisp extends Query
{
    /**
    *   Columns
    *
    *   @val array
    */
    protected static $schema = [
        'kb_nendo', 'cd_bumon', 'chk_nendo_all', 'chk_kansei',
        'chk_job', 'narrow_tanto', 'fg_cyuban',
        'cd_kisyu', 'no_bunya_seizo'
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
    
    public function isValidChk_job($val)
    {
        if (!isset($val) || $val == '') {
            return true;
        }
        return Validate::isTextBool($val);
    }
    
    public function isValidNarrow_tanto($val)
    {
        if (!isset($val) || $val == '') {
            return true;
        }
        
        if (mb_substr($val, 0, 1) == '!') {
            return Validate::isTanto(mb_substr($val, 1));
        }
        return Validate::isTanto($val);
    }
    
    public function isValidFg_cyuban($val)
    {
        if (!isset($val) || $val == '') {
            return true;
        }
        return Validate::isTextInt($val, 0, 1);
    }
    
    public function isValidCd_kisyu($val)
    {
        if (!isset($val) || $val == '') {
            return true;
        }
        return Validate::isText($val, 2, 2);
    }
    
    public function isValidNo_bunya_eigyo($val)
    {
        if (!isset($val) || $val == '') {
            return true;
        }
        return Validate::isTextInt($val, 0);
    }
    
    public function isValidNo_bunya_seizo($val)
    {
        if (!isset($val) || $val == '') {
            return true;
        }
        return Validate::isTextInt($val, 0);
    }
}
