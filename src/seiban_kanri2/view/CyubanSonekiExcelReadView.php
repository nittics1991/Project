<?php
/**
*   VIEW Controller
*
*   @version 160804
*/
namespace seiban_kanri2\view;

use Concerto\standard\ViewStandard;

class CyubanSonekiExcelReadView extends ViewStandard
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
        $this->render('template/cyuban_soneki_excel_read_template.php');
    }
}
