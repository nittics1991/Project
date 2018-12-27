<?php
/**
*   Controller Model
*
*   @version 180913
*/
namespace jyukyu_inf\model;

use Concerto\standard\ControllerModel;
use jyukyu_inf\model\JyukyuInfDispFactory;

class JyukyuInfDispControllerModel extends ControllerModel
{
    /**
    *   名前空間
    *
    *   @val string
    */
    protected $namespace = 'jyukyu_inf';
    
    /**
    *   コンストラクタ
    *
    *   @param JyukyuInfDispFactory $factory
    */
    public function __construct(JyukyuInfDispFactory $factory)
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
            $this->session->no_cyu = $query->no_cyu??
                $this->session->no_cyu;
            $this->session->no_page = $query->no_page??
                $this->session->no_page;
        }
    }
    
    /**
    *   データ作成
    *
    */
    public function buildData()
    {
        $model = $this->factory->getModel();
        
        $this->no_cyu = $this->session->no_cyu;
        $this->no_page = $this->session->no_page;
        
        $this->syohin_list = $model->getSyohinList();
        $this->model_list = $model->getModelList();
        $this->dt_pjyukyu = date('Ymd');
        $this->dt_rjyukyu = date('Ymd');
        $this->cd_jyukyu_tanto = $this->globalSession->input_code;
        ;
        $this->tanto_list = $model->getTantoList(
            $this->globalSession->input_group
        );
    }
    
    /**
    *   gridデータ作成
    *
    *   @return array
    */
    public function buildGridData()
    {
        $model = $this->factory->getModel();
        
        $no_cyu = $this->session->no_cyu;
        
        $jyukyu_list = $model->getJyukyuCyubanList($no_cyu);
        $haraidasi = $model->getHaraidasiStatus();
        $all_items = [];
        
        foreach ($jyukyu_list as $list) {
            $items['no_jyukyu'] = $list['no_jyukyu'];
            $items['nm_syohin'] = $list['nm_syohin'];
            $items['nm_model'] = $list['nm_model'];
            $items['no_cyu'] = $list['no_cyu'];
            $items['no_cyu_t'] = $list['no_cyu_t'];
            $items['nm_daihyo'] = $list['nm_daihyo'];
            $items['nm_setti'] = $list['nm_setti'];
            $items['nm_user'] = $list['nm_user'];
            $items['dt_pjyukyu'] = $list['dt_pjyukyu'];
            $items['no_psuryo'] = $list['no_psuryo'];
            $items['cd_jyukyu_tanto'] = $list['cd_jyukyu_tanto'];
            $items['nm_jyukyu_tanto'] = $list['nm_jyukyu_tanto'];
            $items['dt_rjyukyu'] = $list['dt_rjyukyu'];
            $items['no_rsuryo'] = $list['no_rsuryo'];
            $items['no_zansu'] = $list['no_zansu'];
            
            $items['nm_sts'] = isset($haraidasi[$list['cd_sts']])?
                $haraidasi[$list['cd_sts']]:'';
            
            $items['nm_uketori_tanto'] = $list['nm_uketori_tanto'];
            $items['dt_uketori'] = mb_substr($list['dt_uketori'], 0, 8);
            $items['nm_biko'] = $list['nm_biko'];
            $items['fg_warn'] = $list['no_zansu'] > 0;
            
            $all_items[] = $items;
        }
        return ['data' => $all_items];
    }
    
    /**
    *   post情報設定
    *
    */
    public function setAct()
    {
        $this->act = $_POST['act'];
    }
    
    /**
    *   post処理(受給品)
    *
    */
    public function isValidJyukyuPost()
    {
        $post = $this->factory->getJyukyuPost();
        $this->act = $post->act;
        $ans = $post->isValid();
        $this->validError[] = $post->getValidError();
        return $ans;
    }
    
    /**
    *   受給品設定
    *
    **/
    public function setJyukyu()
    {
        $model = $this->factory->getJyukyuModel();
        $post = $this->factory->getJyukyuPost();
        $model->setData($post);
    }
    
    /**
    *   post処理(受入)
    *
    */
    public function isValidUkeirePost()
    {
        $post = $this->factory->getUkeirePost();
        $this->act = $post->act;
        $ans = $post->isValid();
        $this->validError[] = $post->getValidError();
        return $ans;
    }
    
    /**
    *   受入設定
    *
    **/
    public function setUkeire()
    {
        $model = $this->factory->getUkeireModel();
        $post = $this->factory->getUkeirePost();
        $model->setData($post);
    }
    
    /**
    *   post処理(一括受入)
    *
    */
    public function isValidAllUkeirePost()
    {
        $post = $this->factory->getAllUkeirePost();
        $this->act = $post->act;
        $ans = $post->isValid();
        $this->validError[] = $post->getValidError();
        return $ans;
    }
    
    /**
    *   一括受入設定
    *
    **/
    public function setAllUkeire()
    {
        $model = $this->factory->getAllUkeireModel();
        $post = $this->factory->getAllUkeirePost();
        $model->setData($post);
    }
}
