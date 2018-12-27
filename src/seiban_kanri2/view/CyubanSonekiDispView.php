<?php
/**
*   VIEW Controller
*
*   @version 160216
*/
namespace seiban_kanri2\view;

use Concerto\standard\ViewStandard;

class CyubanSonekiDispView extends ViewStandard
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
        $this->dt_hakkou_diff_date  = htmlspecialchars_decode(
            $this->dt_hakkou_diff_date,
            ENT_QUOTES
        );
        $this->dt_cyunyu_diff_date  = htmlspecialchars_decode(
            $this->dt_cyunyu_diff_date,
            ENT_QUOTES
        );
        $this->render('template/cyuban_soneki_template.php');
    }
}
