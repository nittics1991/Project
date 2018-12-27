<?php
/**
*   POST
*
*   @version 160830
*/
namespace seiban_kanri2\model;

use Concerto\auth\Csrf;
use Concerto\standard\Post;
use Concerto\Validate;

class PostCyubanSonekiExcelRead extends Post
{
    /**
    *   Columns
    *
    *   @val array
    */
    protected static $schema = [
        'token', 'act', 'kb_nendo', 'cd_bumon', 'MAX_FILE_SIZE'
    ];
    
    public function isValidToken($val)
    {
        return Csrf::isValid($val, false);
    }
    
    //act
    
    public function isValidKb_nendo($val)
    {
        return Validate::isNendo($val);
    }
    
    public function isValidCd_bumon($val)
    {
        return Validate::isBumon($val);
    }
    
    public function isValidMAX_FILE_SIZE($val)
    {
        return Validate::isTextInt($val) && ($val <= 1000000) && ($val > 0);
    }
}
