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

class PostJyukyuInfDispUkeire extends Post
{
    /**
    *   Columns
    *
    *   @val array
    */
    protected static $schema = [
        'token', 'act', 'no_cyu', 'no_jyukyu',
        'cd_jyukyu_tanto', 'dt_rjyukyu', 'no_rsuryo'
    ];
    
    public function isValidToken($val)
    {
        return Csrf::isValid($val, false);
    }
    
    public function isValidAct($val)
    {
        return $val == 'ukeire';
    }
    
    public function isValidNo_cyu($val)
    {
        return Validate::isCyuban($val);
    }
    
    public function isValidNo_jyukyu($val)
    {
        return mb_ereg_match('\AJ[A-Z]{3}\d{5}\z', $val);
    }
    
    public function isValidCd_jyukyu_tanto($val)
    {
        return Validate::isTanto($val);
    }
    
    public function isDt_rjyukyu($val)
    {
        return Validate::isTextDate($val);
    }
    
    public function isNo_rsuryo($val)
    {
        return Validate::isTextInt($val, 1, 10000);
    }
}
