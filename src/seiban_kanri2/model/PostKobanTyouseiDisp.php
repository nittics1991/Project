<?php
/**
*   POST
*
*   @version 160927
*/
namespace seiban_kanri2\model;

use Concerto\auth\Csrf;
use Concerto\standard\Post;
use Concerto\Validate;

class PostKobanTyouseiDisp extends Post
{
    /**
    *   Columns
    *
    *   @val array
    */
    protected static $schema = [
        'token', 'act',
        'no_cyu',
        'no_ko', 'nm_syohin', 'yn_ttov', 'yn_tsoneki', 'nm_biko'
    ];
    
    public function isValidToken($val)
    {
        return Csrf::isValid($val, false);
    }
    
    //act
    
    public function isValidNo_ko($val)
    {
        $ans = true;
        foreach ((array)$val as $v) {
            $ans = $ans && Validate::isKoban($v);
        }
        return $ans;
    }
    
    //nm_syohin
    
    public function isValidYn_ttov($val)
    {
        $ans = true;
        foreach ((array)$val as $v) {
            if ((trim($v) != '') && !Validate::isTextInt($v, -100000000, 100000000)) {
                $ans = false;
            }
        }
        return $ans;
    }
    
    public function isValidYn_tsoneki($val)
    {
        $ans = true;
        foreach ((array)$val as $v) {
            if ((trim($v) != '') && !Validate::isTextInt($v, -100000000, 100000000)) {
                $ans = false;
            }
        }
        return $ans;
    }
    
    public function isValidNm_biko($val)
    {
        $ans = true;
        foreach ((array)$val as $v) {
            if ($v == '') {
                //nop
            } else {
                $ans =  Validate::isTextEscape($v, 0, 100)
                    && !Validate::hasTextHankaku($v)
                    && $ans;
            }
        }
        return $ans;
    }
}
