<?php
/**
*   VIEW Controller
*
*   @version 180918
*/
namespace jyukyu_inf\view;

use Concerto\standard\ViewStandard;

class JyukyuInfDispView extends ViewStandard
{
    /**
    *   コンストラクタ
    *
    *   @param array $data データ
    */
    public function __construct($data = [])
    {
        parent::__construct($data);
        
        $this->toHTML();
        $this->render('template/jyukyu_inf_template.php');
    }
}
