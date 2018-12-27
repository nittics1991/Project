<?php
/**
*   POST
*
*   @version 180907
*/
namespace jyukyu_inf\model;

use Concerto\auth\Csrf;
use Concerto\standard\Post;
use Concerto\Validate;

class PostJyukyuInfDispJyukyu extends Post
{
    /**
    *   Columns
    *
    *   @val array
    */
    protected static $schema = [
        'token', 'act', 'no_cyu', 'no_jyukyu',
        'nm_syohin', 'nm_model', 'dt_pjyukyu', 'no_psuryo', 'nm_biko'
    ];
    
    public function isValidToken($val)
    {
        return Csrf::isValid($val, false);
    }
    
    public function isValidAct($val)
    {
        return $val == 'insert' || $val == 'update' || $val == 'delete';
    }
    
    public function isValidNo_cyu($val)
    {
        return Validate::isCyuban($val);
    }
    
    public function isValidNo_jyukyu($val)
    {
        if ($this->act == 'insert' && $val == '') {
            return true;
        }
        return mb_ereg_match('\AJ[A-Z]{3}\d{5}\z', $val);
    }
    
    public function isValidNm_syohin($val)
    {
        return Validate::isTextEscape($val, 1, 50);
    }
    
    public function isValidNm_model($val)
    {
        return Validate::isTextEscape($val, 1, 50, null, '_');
    }
    
    public function isDt_pjyukyu($val)
    {
        return Validate::isTextDate($val);
    }
    
    public function isNo_psuryo($val)
    {
        return Validate::isTextInt($val, 1, 10000);
    }
}
