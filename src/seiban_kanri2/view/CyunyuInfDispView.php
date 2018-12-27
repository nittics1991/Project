<?php
/**
*   VIEW Controller
*
*   @version 160216
*/
namespace seiban_kanri2\view;

use Concerto\standard\ViewStandard;

class CyunyuInfDispView extends ViewStandard
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
        $this->nm_tanto_list    = htmlspecialchars_decode(
            $this->nm_tanto_list,
            ENT_QUOTES
        );
        $this->nm_syohin_list   = htmlspecialchars_decode(
            $this->nm_syohin_list,
            ENT_QUOTES
        );
        $this->render('template/cyunyu_inf_template.php');
    }
}
