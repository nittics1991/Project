<?php
/**
*   VIEW Controller
*
*   @version 160802
*/
namespace seiban_kanri2\view;

use Concerto\standard\ViewStandard;

class KobanTyouseiDispView extends ViewStandard
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
        $this->render('template/koban_tyousei_template.php');
    }
}
