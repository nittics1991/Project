<?php
/**
*   Controller Model
*
*   @version 180918
*/
namespace jyukyu_inf\model;

use Concerto\standard\ControllerModel;
use jyukyu_inf\model\JyukyuKanriDispFactory;

class JyukyuKanriDispControllerModel extends ControllerModel
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
    *   @param JyukyuKanriDispFactory $factory
    */
    public function __construct(JyukyuKanriDispFactory $factory)
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
            $this->session->cd_bumon = $query->cd_bumon??
                $this->session->cd_bumon;
            
            $this->session->chk_nendo_all = is_null($query->chk_nendo_all)?
                $this->session->chk_nendo_all
                :($query->chk_nendo_all)? '1':'';
            $this->session->chk_kansei = is_null($query->chk_kansei)?
                $this->session->chk_kansei
                :($query->chk_kansei)? '1':'';
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
    *   env設定
    *
    *   @param array $config cookie設定
    */
    public function setEnv($config)
    {
        $originator = $this->factory->getMementoOriginator();
        
        $env = [];
        $env['cd_bumon'] = $this->session->cd_bumon;
        $env['kb_nendo'] = $this->session->kb_nendo;
        $env['chk_kansei'] = $this->session->chk_kansei;
        $env['chk_nendo_all'] = $this->session->chk_nendo_all;
        $originator->setOriginator($env);
        
        $manager = $this->factory->getMementoCookieManager(
            $this->namespace,
            $config,
            $originator
        );
        $manager->setStorage();
    }
    
    /**
    *   envリセット
    *
    *   @param array $config cookie設定
    */
    public function resetEnv($config)
    {
        $originator = $this->factory->getMementoOriginator();
        
        $manager = $this->factory->getMementoCookieManager(
            $this->namespace,
            $config,
            $originator
        );
        $manager->removeStorage();
    }
    
    /**
    *   データ作成
    *
    */
    public function buildData()
    {
        $model = $this->factory->getModel();
        
        $this->cd_bumon = $this->session->cd_bumon;
        $this->kb_nendo = $this->session->kb_nendo;
        $this->chk_nendo_all = $this->session->chk_nendo_all;
        $this->chk_kansei = $this->session->chk_kansei;
        
        $this->nendo_list = $model->getNendoList();
        $this->bumon_list = $model->getBumonList($this->kb_nendo);
    }
    
    /**
    *   gridデータ作成
    *
    *   @return array
    */
    public function buildGridData()
    {
        $model = $this->factory->getModel();
        
        $cd_bumon = ($this->session->cd_bumon == 'all')?
            null:$this->session->cd_bumon;
        $kb_nendo = $this->session->kb_nendo;
        $chk_nendo_all = $this->session->chk_nendo_all;
        $chk_kansei = $this->session->chk_kansei;
        
        $jyukyu_list = $model->getJyukyuList(
            $kb_nendo,
            $cd_bumon,
            $chk_nendo_all,
            $chk_kansei
        );
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
            
            $items['nm_sts'] = $haraidasi[$list['cd_sts']]?? '';
            
            $items['nm_uketori_tanto'] = $list['nm_uketori_tanto'];
            $items['dt_uketori'] = mb_substr($list['dt_uketori'], 0, 8);
            $items['nm_biko'] = $list['nm_biko'];
            $items['fg_warn'] = $list['no_zansu'] > 0;
            
            $all_items[] = $items;
        }
        return ['data' => $all_items];
    }
}
