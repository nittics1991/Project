<?php
/**
*   POST
*
*   @version 171018
*/
namespace seiban_kanri2\model;

use Concerto\auth\Csrf;
use Concerto\standard\Post;
use Concerto\Validate;

class PostCyubanSonekiDisp extends Post
{
    /**
    *   Columns
    *
    *   @val array
    */
    protected static $schema = [
        'token', 'act'
    ];
    
    public function isValidToken($val)
    {
        return Csrf::isValid($val, false);
    }
    
    public function isValidAct($val)
    {
        return $val == 'setEnv' || $val == 'resetEnv';
    }
}
