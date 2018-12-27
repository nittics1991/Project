<?php
/**
*   POST
*
*   @version 171114
*/
namespace seiban_kanri2\model;

use Concerto\auth\Csrf;
use Concerto\standard\Post;
use Concerto\Validate;

class PostKobanSonekiDisp extends Post
{
    /**
    *   Columns
    *
    *   @val array
    */
    protected static $schema = [
        'token', 'act', 'kb_tanto', 'dt_hatuban', 'dt_kakunin'
    ];
    
    public function isValidToken($val)
    {
        return Csrf::isValid($val, false);
    }
    
    public function isValidAct($val)
    {
        return $val == 'tanto' || $val == 'hatuban' || $val == 'replace';
    }
    
    public function isValidKb_tanto($val)
    {
        if (!isset($val)) {
            return true;
        }
        
        if ($val == '') {
            return true;
        }
        
        return Validate::isTextBool($val);
    }
    
    public function isValidDt_hatuban($val)
    {
        if (!isset($val)) {
            return true;
        }
        
        if ($val == '') {
            return true;
        }
        return Validate::isTextDate($val);
    }
    
    public function isValidDt_kakunin($val)
    {
        if (!isset($val)) {
            return true;
        }
        
        if ($val == '') {
            return true;
        }
        return Validate::isTextDate($val);
    }
}
