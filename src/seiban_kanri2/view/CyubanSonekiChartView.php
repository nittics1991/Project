<?php
/**
*   VIEW Controller
*
*   @version 160218
*/
namespace seiban_kanri2\view;

use Concerto\standard\ViewStandard;

class CyubanSonekiChartView extends ViewStandard
{
    /**
    *   コンストラクタ
    *
    *   @param array $data データ
    */
    public function __construct($data = [])
    {
        parent::__construct($data);
        $this->render('template/cyuban_soneki_chart_template.php');
    }
}
