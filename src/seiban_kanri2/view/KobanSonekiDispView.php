<?php
/**
*   VIEW Controller
*
*   @version 160216
*/
namespace seiban_kanri2\view;

use Concerto\standard\ViewStandard;

class KobanSonekiDispView extends ViewStandard
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
        $this->render('template/koban_soneki_template.php');
    }
}
