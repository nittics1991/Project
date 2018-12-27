<?php
/**
*   POST
*
*   @version 180918
*/
namespace jyukyu_inf\model;

use Concerto\auth\Csrf;
use Concerto\standard\Post;
use Concerto\Validate;

class PostJyukyuKanriDisp extends Post
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
