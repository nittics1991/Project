<?php
/**
*   Controller Model
*
*   @version 171006
*/
namespace seiban_kanri2\model;

use Concerto\standard\ControllerModel;
use seiban_kanri2\model\CyubanSonekiExcelReadFactory;

class CyubanSonekiExcelReadControllerModel extends ControllerModel
{
    /**
    *   名前空間
    *
    *   @val string
    */
    protected $namespace = 'seiban_kanri';
    
    /**
    *   コンストラクタ
    *
    *   @param CyubanSonekiExcelReadFactory $factory
    */
    public function __construct(CyubanSonekiExcelReadFactory $factory)
    {
        parent::__construct($factory);
    }
    
    /**
    *   query処理
    *
    */
    public function setQuery()
    {
        $query = $this->factory->getQuery();
        
        if ($query->isValid()) {
            $this->session->kb_nendo = $query->kb_nendo??
                $this->session->kb_nendo;
            
            $this->session->cd_bumon= !$query->isEmpty('cd_bumon')?
                $query->cd_bumon:$this->session->cd_bumon;
        }
    }
    
    /**
    *   post処理
    *
    */
    public function isValidPost()
    {
        $post = $this->factory->getPost();
        $this->act = $post->act;
        $ans = $post->isValid();
        $this->validError[] = $post->getValidError();
        return $ans;
    }
    
    /**
    *   データ作成
    *
    */
    public function buildData()
    {
        $model = $this->factory->getModel();
        $this->kb_nendo = $this->session->kb_nendo;
        $this->cd_bumon = $this->session->cd_bumon;
    }
    
    /**
    *   EXCEL読込
    *
    *   @param string $tagname fileタグname
    *   @return string ファイル名
    */
    public function readExcel($tagname)
    {
        $model = $this->factory->getModel();
        
        $tmp = dirname(__DIR__) . '\\tmp';
        
        $fileOperation = $this->factory->getFileOperation();
        $fileOperation->createTempDir($tmp, 1);
        
        $params = ['mime' =>
            [
                'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            ]
        ];
        $uploader = $this->factory->getHttpUpload();
        $template = $uploader->load($tagname, $tmp);
        
        $excel = $this->factory->getExcelManager($template[0]);
        $csv = $tmp . '\\' . uniqid() . '.csv';
        $excel->toCSV($csv);
        
        $this->history = $model->importCSV($csv);
        return $template[0];
    }
}
