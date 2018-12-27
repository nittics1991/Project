<?php
/**
*   MementoOriginator
*
*   @version 180918
*/
namespace jyukyu_inf\model;

use Concerto\pattern\MementoOriginator;
use Concerto\Validate;

final class JyukyuKanriOriginator extends MementoOriginator
{
    /**
    *   バリデート
    *
    *   @return bool 結果
    */
    public function isValid()
    {
        if (is_array($this->local_storage)) {
            $ans = true;
            
            foreach ((array)$this->local_storage as $key => $val) {
                if (isset($val)) {
                    switch ($key) {
                        case 'cd_bumon':
                            if (!empty($val) && ($val != 'all')) {
                                $ans = $ans & Validate::isBumon($val);
                            }
                            break;
                        case 'kb_nendo':
                            $ans = $ans & Validate::isNendo($val);
                            break;
                        case 'chk_kansei':
                        case 'chk_nendo_all':
                            if (!empty($val)) {
                                $ans = $ans & Validate::isTextBool($val);
                            }
                            break;
                        default:
                            $ans = false;
                    }
                }
            }
            return $ans;
        }
        return false;
    }
}
