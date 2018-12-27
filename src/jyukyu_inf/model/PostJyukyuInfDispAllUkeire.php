<?php
/**
*   POST
*
*   @version 180911
*/
namespace jyukyu_inf\model;

use Concerto\Validate;
use jyukyu_inf\model\PostJyukyuInfDispUkeire;

class PostJyukyuInfDispAllUkeire extends PostJyukyuInfDispUkeire
{
    /**
    *   Columns
    *
    *   @val array
    */
    protected static $schema = [
        'token', 'act', 'no_cyu', 'no_jyukyu',
        'cd_jyukyu_tanto', 'dt_rjyukyu', 'no_rsuryo',
        'nm_target', 'nm_target_suryo',
    ];
    
    public function isValidAct($val)
    {
        return $val == 'allukeire';
    }
    
    public function isValidNo_jyukyu($val)
    {
        return true;
    }
    
    private function validNo_jyukyu($val)
    {
        return mb_ereg_match('\AJ[A-Z]{3}\d{5}\z', $val);
    }
    
    public function isValidNm_target($val)
    {
        foreach ((array)explode(',', $val) as $no_jyukyu) {
            if (!$this->validNo_jyukyu($no_jyukyu)) {
                return false;
            }
        }
        return true;
    }
    
    public function isValidNm_target_suryo($val)
    {
        foreach ((array)explode(',', $val) as $suryo) {
            if (!$this->isNo_rsuryo($suryo)) {
                return false;
            }
        }
        return true;
    }
}
