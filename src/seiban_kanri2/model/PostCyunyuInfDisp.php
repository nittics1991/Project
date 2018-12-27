<?php
/**
*   POST
*
*   @version 181018
*/
namespace seiban_kanri2\model;

use Concerto\auth\Csrf;
use Concerto\standard\Post;
use Concerto\Validate;

class PostCyunyuInfDisp extends Post
{
    /**
    *   Columns
    *
    *   @val array
    */
    protected static $schema = [
        'token', 'act', 'fg_lock', 'keikaku',
        'no_cyu', 'no_ko',
        'no_seq', 'kb_nendo', 'cd_genka_yoso', 'cd_bumon',
        'cd_tanto', 'nm_tanto', 'nm_syohin', 'num_data'
    ];
    
    public function isValidToken($val)
    {
        return Csrf::isValid($val, false);
    }
    
    //act
    //keikaku
    
    public function isValidFg_lock($val)
    {
        if (!isset($val)) {
            return true;
        }
        
        if ($val == '') {
            return true;
        }
        return Validate::isTextBool($val);
    }
    
    public function isValidNo_cyu($val)
    {
        if (isset($this->keikaku)) {
            return true;
        }
        return Validate::isCyuban($val);
    }
    
    public function isValidNo_ko($val)
    {
        if (isset($this->keikaku)) {
            return true;
        }
        return Validate::isKoban($val);
    }
    
    public function isValidNo_seq($val)
    {
        if (isset($this->keikaku)) {
            return true;
        }
        return Validate::isTextInt($val, 0);
    }
    
    public function isValidKb_nendo($val)
    {
        if (isset($this->keikaku)) {
            return true;
        }
        
        if (!isset($val)) {
            return true;
        }
        
        if ($val == '') {
            return true;
        }
        return Validate::isNendo($val);
    }
    
    public function isValidCd_genka_yoso($val)
    {
        if (isset($this->keikaku)) {
            return true;
        }
        return Validate::isGenkaYoso($val);
    }
    
    public function isValidCd_bumon($val)
    {
        if (isset($this->keikaku)) {
            return true;
        }
        
        if (!isset($val)) {
            return true;
        }
        
        if ($val == '') {
            return true;
        }
        return Validate::isBumon($val);
    }
    
    public function isValidCd_tanto($val)
    {
        if (isset($this->keikaku)) {
            return true;
        }
        
        if (!isset($val)) {
            return true;
        }
        
        if (($val == '') || ($val == 'XXXXXXXX')) {
            return true;
        }
        return Validate::isTanto($val);
    }
    
    public function isValidNm_tanto($val)
    {
        if (isset($this->keikaku)) {
            return true;
        }
        
        if (!isset($val)) {
            return true;
        }
        
        if ($val == '') {
            return true;
        }
        return Validate::isTextEscape($val, 0, 100)
            && !Validate::hasTextHankaku($val);
    }
    
    public function isValidNm_syohin($val)
    {
        if (isset($this->keikaku)) {
            return true;
        }
        
        if (!isset($val)) {
            return true;
        }
        
        if ($val == '') {
            return true;
        }
        return Validate::isTextEscape($val, 0, 100)
            && !Validate::hasTextHankaku($val);
    }
    
    public function isValidNum_data($val)
    {
        if (isset($this->keikaku)) {
            return true;
        }
        
        $ans = true;
        foreach ((array)$val as $v) {
            if ((!Validate::isTextInt($v) && !Validate::isTextFloat($v))) {
                $ans = false;
            }
        }
        
        if (count((array)$val) != 12) {
            $ans = false;
        }
        return $ans;
    }
}
