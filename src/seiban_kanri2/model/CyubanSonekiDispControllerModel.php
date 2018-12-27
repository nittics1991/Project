<?php
/**
*   Controller Model
*
*   @version 181102
*/
namespace seiban_kanri2\model;

use Concerto\CookieEnv;
use Concerto\FiscalYear;
use Concerto\standard\ControllerModel;
use seiban_kanri2\model\CyubanSonekiDispFactory;

class CyubanSonekiDispControllerModel extends ControllerModel
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
    *   @param CyubanSonekiDispFactory $factory
    */
    public function __construct(CyubanSonekiDispFactory $factory)
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
            $this->session->kb_nendo =$query->kb_nendo??
                $this->session->kb_nendo;
            $this->session->cd_bumon = $query->cd_bumon??
                $this->session->cd_bumon;
            $this->session->narrow_tanto = $query->narrow_tanto??
                $this->session->narrow_tanto;
            $this->session->cd_kisyu = $query->cd_kisyu??
                $this->session->cd_kisyu;
            $this->session->no_bunya_eigyo = $query->no_bunya_eigyo??
                $this->session->no_bunya_eigyo;
            $this->session->no_bunya_seizo = $query->no_bunya_seizo??
                $this->session->no_bunya_seizo;
            
            $this->session->chk_nendo_all = is_null($query->chk_nendo_all)?
                $this->session->chk_nendo_all
                :($query->chk_nendo_all)? '1':'';
            $this->session->chk_kansei = is_null($query->chk_kansei)?
                $this->session->chk_kansei
                :($query->chk_kansei)? '1':'';
            $this->session->fg_cyuban = is_null($query->fg_cyuban)?
                $this->session->fg_cyuban
                :($query->fg_cyuban)? '1':'';
            $this->session->chk_job = is_null($query->chk_job)?
                $this->session->chk_job
                :($query->chk_job)? '1':'';
            
            //過去年度なら当期以降を無効
            if ($this->session->kb_nendo < FiscalYear::getPresentNendo()) {
                $this->session->chk_nendo_all = '';
            }
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
        
        $env = array();
        $env['cd_bumon'] = $this->session->cd_bumon;
        $env['kb_nendo'] = $this->session->kb_nendo;
        $env['chk_kansei'] = $this->session->chk_kansei;
        $env['chk_job'] = $this->session->chk_job;
        $env['narrow_tanto'] = $this->session->narrow_tanto;
        $env['chk_nendo_all'] = $this->session->chk_nendo_all;
        $env['fg_cyuban'] = $this->session->fg_cyuban;
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
    *   @param array $config 画面config
    */
    public function buildData($config = null)
    {
        $model = $this->factory->getModel();
        
        $cd_bumon = $this->session->cd_bumon;
        $kb_nendo = $this->session->kb_nendo;
        $chk_nendo_all = $this->session->chk_nendo_all;
        $chk_kansei = $this->session->chk_kansei;
        $chk_job = $this->session->chk_job;
        $narrow_tanto = $this->session->narrow_tanto;
        $fg_cyuban = $this->session->fg_cyuban;
        
        $this->cd_kisyu = $this->session->cd_kisyu;
        $this->no_bunya_eigyo = ($this->session->no_bunya_eigyo == '')?
            '':(int)$this->session->no_bunya_eigyo;
        $this->no_bunya_seizo = $this->session->no_bunya_seizo;
        
        $this->nendo_list = $model->getNendoList();
        $this->bumon_list = $model->getBumonList($kb_nendo);
        
        $cd_bumon_tmp = ($cd_bumon == 'all')? null:$cd_bumon;
        $kb_nendo_tmp = ($kb_nendo == 'all')? null:$kb_nendo;
        
        $this->tanto_list = $model->getTantoList($cd_bumon_tmp);
        $input_code = $this->globalSession->input_code;
        array_unshift(
            $this->tanto_list,
            [
                'cd_tanto' => "!{$input_code}",
                'nm_tanto' => '担当外'
            ]
        );
        
        $cd_tanto = '';
        if (!empty($chk_job)) {
            $cd_tanto = $input_code;
        }
        
        if ($narrow_tanto != '') {
            $cd_tanto = $narrow_tanto;
        }
        
        $this->kisyu_list = $model->getKisyuList();
        $this->bunya_eigyo_list = $model->getBunyaEigyoList();
        $this->bunya_seizo_list = $model->getBunyaSeizoList();
        
        $this->setJyutyuData($kb_nendo, $cd_bumon);
        $this->setRankAData($kb_nendo, $cd_bumon);
        $this->setRankBData($kb_nendo, $cd_bumon);
        $this->setRankCData($kb_nendo, $cd_bumon);
        $this->setYosanData(
            $kb_nendo,
            $cd_bumon,
            $this->yn_ycyunyu,
            $this->yn_ysoneki,
            $this->ri_ysoneki
        );
        
        $this->dt_hakkou_diff_date = is_null($config)?
            0:$config['grid']['dt_hakkou_diff_date'];
        $this->dt_cyunyu_diff_date = is_null($config)?
            0:$config['grid']['dt_cyunyu_diff_date'];
        
        $this->isPastFiscalYear = ($kb_nendo < FiscalYear::getPresentNendo());
        
        $this->fromArray(compact(
            'cd_bumon',
            'kb_nendo',
            'chk_nendo_all',
            'chk_kansei',
            'chk_job',
            'narrow_tanto',
            'fg_cyuban'
        ));
    }
    
    /**
    *   集計データ(受注)
    *
    *   @param string $kb_nendo
    *   @param string $cd_bumon
    **/
    private function setJyutyuData($kb_nendo, $cd_bumon)
    {
        $model = $this->factory->getModel();
        
        $list = $model->getCyubanAggregeteList(
            $kb_nendo,
            $cd_bumon,
            '0'
        );
        
        $this->yn_psp = $list['yn_sp'];
        $this->yn_ptov = $list['yn_tov'];
        $this->yn_parari = $list['yn_arari'];
        $this->tm_pcyokka = $list['tm_pcyokka'];
        $this->yn_pcyokka = $list['yn_pcyokka'];
        $this->yn_pcyokuzai = $list['yn_pcyokuzai'];
        $this->yn_pryohi = $list['yn_pryohi'];
        $this->yn_petc = $list['yn_petc'];
        $this->yn_pcyunyu = $list['yn_pcyunyu'];
        $this->yn_psoneki = $list['yn_psoneki'];
        $this->ri_psoneki = round($list['ri_psoneki'], 1);
        
        $this->yn_ysp = $list['yn_sp'];
        $this->yn_ytov = $list['yn_tov'];
        $this->yn_yarari = $list['yn_arari'];
        $this->tm_ycyokka = $list['tm_ycyokka'];
        $this->yn_ycyokka = $list['yn_ycyokka'];
        $this->yn_ycyokuzai = $list['yn_ycyokuzai'];
        $this->yn_yryohi = $list['yn_yryohi'];
        $this->yn_yetc = $list['yn_yetc'];
        $this->yn_ycyunyu = $list['yn_ycyunyu'];
        $this->yn_ysoneki = $list['yn_ysoneki'];
        $this->ri_ysoneki = round($list['ri_ysoneki'], 1);
        
        $this->yn_rsp = $list['yn_sp'];
        $this->yn_rtov = $list['yn_tov'];
        $this->yn_rarari = $list['yn_arari'];
        $this->tm_rcyokka = $list['tm_rcyokka'];
        $this->yn_rcyokka = $list['yn_rcyokka'];
        $this->yn_rcyokuzai = $list['yn_rcyokuzai'];
        $this->yn_rryohi = $list['yn_rryohi'];
        $this->yn_retc = $list['yn_retc'];
        $this->yn_rcyunyu = $list['yn_rcyunyu'];
        $this->yn_rsoneki = $list['yn_rsoneki'];
        $this->ri_rsoneki = round($list['ri_rsoneki'], 1);
    }
    
    /**
    *   集計データ(ランクA)
    *
    *   @param string $kb_nendo
    *   @param string $cd_bumon
    **/
    private function setRankAData($kb_nendo, $cd_bumon)
    {
        $model = $this->factory->getModel();
        
        $list = $model->getCyubanAggregeteList(
            $kb_nendo,
            $cd_bumon,
            '1'
        );
        
        $this->yn_atov = $list['yn_tov'];
        $this->tm_acyokka = $list['tm_ycyokka'];
        $this->yn_acyokka = $list['yn_ycyokka'];
        $this->yn_acyokuzai = $list['yn_ycyokuzai'];
        $this->yn_aryohi = $list['yn_yryohi'];
        $this->yn_aetc = $list['yn_yetc'];
        $this->yn_acyunyu = $list['yn_ycyunyu'];
        $this->yn_asoneki = $list['yn_ysoneki'];
        $this->ri_asoneki = round($list['ri_ysoneki'], 1);
    }
    
    /**
    *   集計データ(ランクB)
    *
    *   @param string $kb_nendo
    *   @param string $cd_bumon
    **/
    private function setRankBData($kb_nendo, $cd_bumon)
    {
        $model = $this->factory->getModel();
        
        $list = $model->getCyubanAggregeteList(
            $kb_nendo,
            $cd_bumon,
            '2'
        );
        
        $this->yn_btov = $list['yn_tov'];
        $this->tm_bcyokka = $list['tm_ycyokka'];
        $this->yn_bcyokka = $list['yn_ycyokka'];
        $this->yn_bcyokuzai = $list['yn_ycyokuzai'];
        $this->yn_bryohi = $list['yn_yryohi'];
        $this->yn_betc = $list['yn_yetc'];
        $this->yn_bcyunyu = $list['yn_ycyunyu'];
        $this->yn_bsoneki = $list['yn_ysoneki'];
        $this->ri_bsoneki = round($list['ri_ysoneki'], 1);
    }
    
    /**
    *   集計データ(ランクC)
    *
    *   @param string $kb_nendo
    *   @param string $cd_bumon
    **/
    private function setRankCData($kb_nendo, $cd_bumon)
    {
        $model = $this->factory->getModel();
        
        $list = $model->getCyubanAggregeteList(
            $kb_nendo,
            $cd_bumon,
            '3'
        );
        
        $this->yn_ctov = $list['yn_tov'];
        $this->tm_ccyokka = $list['tm_ycyokka'];
        $this->yn_ccyokka = $list['yn_ycyokka'];
        $this->yn_ccyokuzai = $list['yn_ycyokuzai'];
        $this->yn_cryohi = $list['yn_yryohi'];
        $this->yn_cetc = $list['yn_yetc'];
        $this->yn_ccyunyu = $list['yn_ycyunyu'];
        $this->yn_csoneki = $list['yn_ysoneki'];
        $this->ri_csoneki = round($list['ri_ysoneki'], 1);
    }
    
    /**
    *   集計データ(予算)
    *
    *   @param string $kb_nendo
    *   @param string $cd_bumon
    *   @param int $yn_ycyunyu
    *   @param int $yn_ysoneki
    *   @param int $ri_ysoneki
    **/
    private function setYosanData(
        $kb_nendo,
        $cd_bumon,
        $yn_ycyunyu,
        $yn_ysoneki,
        $ri_ysoneki
    ) {
        $model = $this->factory->getModel();
        
        $cd_bumon_tmp = ($cd_bumon == 'all')? null:$cd_bumon;
        $kb_nendo_tmp = ($kb_nendo == 'all')? null:$kb_nendo;
        
        $ylist = $model->getBudgetAggregete($kb_nendo_tmp, $cd_bumon_tmp);
        $bumonHasHatuban = $model->bumonHasHatuban($cd_bumon_tmp);
        
        if (!$bumonHasHatuban || is_null($cd_bumon)) {
            foreach ((array)$ylist as $list) {
                $yn_yosan   += empty($list['yn_yosan'])? 0:$list['yn_yosan'];
                $yn_soneki  += empty($list['yn_soneki'])?  0:$list['yn_soneki'];
            }
            
            $ri_soneki = empty($yn_yosan)?
                0:$yn_soneki / $yn_yosan * 100;
            
            $this->yn_yosan_sub = empty($yn_yosan)?
                0:$yn_ycyunyu - $yn_yosan;
            $this->yn_soneki_sub = empty($yn_soneki)?
                0:$yn_ysoneki - $yn_soneki;
            $this->ri_soneki_sub = empty($yn_yosan)?
                0:$ri_ysoneki - $ri_soneki;
            
            $this->yn_yosan_rt = empty($yn_yosan)?
                0:$yn_ycyunyu / $yn_yosan * 100;
            $this->yn_soneki_rt = empty($yn_soneki)?
                0:$yn_ysoneki / $yn_soneki * 100;
            $this->ri_soneki_rt = empty($ri_soneki)?
                0:$ri_ysoneki / $ri_soneki * 100;
            
            $this->yn_yosan = $yn_yosan;
            $this->yn_soneki = $yn_soneki;
            $this->ri_soneki = $ri_soneki;
        }
    }
    
    /**
    *   gridデータ作成
    *
    *   @return array
    */
    public function buildGridData()
    {
        $input_code = $this->globalSession->input_code;
        
        $cd_bumon = $this->session->cd_bumon;
        $kb_nendo = $this->session->kb_nendo;
        $chk_nendo_all = $this->session->chk_nendo_all;
        $chk_kansei = $this->session->chk_kansei;
        $chk_job = $this->session->chk_job;
        $narrow_tanto = $this->session->narrow_tanto;
        $fg_cyuban = $this->session->fg_cyuban;
        
        $cd_kisyu = $this->session->cd_kisyu;
        $no_bunya_eigyo = ($this->session->no_bunya_eigyo == '')?
            '':(int)$this->session->no_bunya_eigyo;
        $no_bunya_seizo = $this->session->no_bunya_seizo;
        
        $cd_tanto = '';
        if (!empty($chk_job)) {
            $cd_tanto = $input_code;
        }
        
        if ($narrow_tanto != '') {
            $cd_tanto = $narrow_tanto;
        }
        
        $fg_cyuban_tmp = ($fg_cyuban == '1')? '1':null;
        $model = $this->factory->getModel($fg_cyuban_tmp);
        
        $cyuban_list = $model->getCyubanList(
            $kb_nendo,
            $cd_bumon,
            $chk_nendo_all,
            $chk_kansei,
            $cd_tanto,
            $cd_kisyu,
            $no_bunya_eigyo,
            $no_bunya_seizo
        );
        
        $fuyo_list = $model->getKanriFuyoList($kb_nendo);
        $caution_list = $model->getWfCautionList();
        $cyumon_list = $model->getCyumonKigoList();
        
        $cd_bumon_tyousei = ($fg_cyuban)? $cd_bumon:null;
        $tyousei_list = $model->getTyouseiCyubanList(
            $kb_nendo,
            $cd_bumon_tyousei
        );
        $key_map = array();
        
        array_walk($tyousei_list, function (&$list, $index) use (&$key_map) {
            $key_map[] = $list['id'] = $list['no_cyu'];
        });
        
        $all_items = array();
        
        foreach ((array)$cyuban_list as $list) {
            $no_project = empty($list['no_project'])?  0:$list['no_project'];
            $nm_project = empty($list['nm_project'])?  '':$list['nm_project'];
            
            $no_cyu = $list['no_cyu'];
            $nm_syohin = $list['nm_syohin'];
            $nm_user = $list['nm_user'];
            $nm_setti = $list['nm_setti'];
            $dt_puriage = $list['dt_puriage'];
            $kb_keikaku = $list['kb_keikaku'];
            
            $kb_cyumon = $cyumon_list[$list['kb_cyumon']];
            
            $kb_uriage = ($list['dt_uriage'] == '')? '0':'1';
            $yn_tov = is_null($list['yn_tov'])?
                0:(integer)($list['yn_tov']);
            
            $yn_sp = is_null($list['yn_sp'])?
                0:(integer)($list['yn_sp']);
            $yn_arari = is_null($list['yn_arari'])?
                0:(integer)($list['yn_arari']);
            
            $dt_hakkou = $list['dt_hakkou'];
            $dt_hatuban = empty($list['dt_hatuban'])?
                $dt_hakkou:$list['dt_hatuban'];
            $nm_tanto = $list['nm_tanto'];
            $dt_kakunin = $list['dt_kakunin'];
            $nm_kakunin = $list['nm_kakunin'];
            $dt_cyunyu = is_null($list['dt_cyunyu'])?
                '':$list['dt_cyunyu'];
            $nm_sien = is_null($list['nm_sien'])?
                '':$list['nm_sien'];
            $no_cyumon = is_null($list['u_chu_no'])?
                '':$list['u_chu_no'];
            
            $no_mitumori = is_null($list['mitu_no'])?
                '':$list['mitu_no'];
            $no_seizo = is_null($list['u_sei_no'])?
                '':$list['u_sei_no'];
            
            $approved_by2 = $list['approved_by2'];
            $fg_unapproved = $model->isUnapproved(
                $no_cyu,
                $list['kb_cyumon'],
                $approved_by2
            );
            
            $nm_kisyu = $list['nm_kisyu'];
            $nm_bunya_eigyo = $list['nm_bunya_eigyo'];
            $nm_bunya_seizo = $list['nm_bunya_seizo'];
            
            $cd_url =
                MAIN_URL . "/wf_new2/wf_seiban_disp.php?no_cyu={$no_cyu}&no_page=0";
            foreach ((array)$fuyo_list as $list1) {
                if ($list1['no_cyu'] == $no_cyu) {
                    if ($list1['fg_fuyo']) {
                        $cd_url = '';
                        break;
                    }
                }
            }
            
            $fg_caution = '0';
            foreach ((array)$caution_list as $list2) {
                if ($list2['no_cyu'] == $no_cyu) {
                    $fg_caution = '1';
                    break;
                }
            }
            
            //未売上
            if ($list['dt_uriage'] == '') {
                $tm_cyokka = is_null($list['tm_ycyokka'])?
                    0.00:(integer)($list['tm_ycyokka']);
                $yn_cyokka = is_null($list['yn_ycyokka'])?
                    0:(integer)($list['yn_ycyokka']);
                $yn_cyokuzai = is_null($list['yn_ycyokuzai'])?
                    0:(integer)($list['yn_ycyokuzai']);
                $yn_ryohi = is_null($list['yn_yryohi'])?
                    0:(integer)($list['yn_yryohi']);
                $yn_etc = is_null($list['yn_yetc'])?
                    0:(integer)($list['yn_yetc']);
                $yn_cyunyu = is_null($list['yn_ycyunyu'])?
                    0:(integer)($list['yn_ycyunyu']);
                $yn_soneki = is_null($list['yn_ysoneki'])?
                    0:(integer)($list['yn_ysoneki']);
                $ri_soneki = is_null($list['ri_ysoneki'])?
                    0.0:(integer)($list['ri_ysoneki']);
            //売上済み
            } else {
                $tm_cyokka = is_null($list['tm_rcyokka'])?
                    0:(integer)($list['tm_rcyokka']);
                $yn_cyokka = is_null($list['yn_rcyokka'])?
                    0:(integer)($list['yn_rcyokka']);
                $yn_cyokuzai = is_null($list['yn_rcyokuzai'])?
                    0:(integer)($list['yn_rcyokuzai']);
                $yn_ryohi = is_null($list['yn_rryohi'])?
                    0:(integer)($list['yn_rryohi']);
                $yn_etc = is_null($list['yn_retc'])?
                    0:(integer)($list['yn_retc']);
                $yn_cyunyu = is_null($list['yn_rcyunyu'])?
                    0:(integer)($list['yn_rcyunyu']);
                $yn_soneki = is_null($list['yn_rsoneki'])?
                    0:(integer)($list['yn_rsoneki']);
                $ri_soneki = is_null($list['ri_rsoneki'])?
                    0.0:(integer)($list['ri_rsoneki']);
            }
            
            $fg_ttov = null;
            $fg_tsoneki = null;
            $yn_ttov = null;
            $yn_tsoneki = null;
            $ri_tsoneki = null;
            
            if (($pos = array_search($no_cyu, $key_map)) !== false) {
                if (($tyousei_list[$pos]['yn_ttov'] != null)
                    || ($tyousei_list[$pos]['yn_tsoneki'] != null)
                ) {
                    if ($tyousei_list[$pos]['yn_ttov'] != null) {
                        $fg_ttov = '○';
                        $yn_ttov = $tyousei_list[$pos]['yn_ttov'];
                    }
                    $cal_ttov = $tyousei_list[$pos]['cal_ttov'];
                    
                    if ($tyousei_list[$pos]['yn_tsoneki'] != null) {
                        $fg_tsoneki = '○';
                        $yn_tsoneki = $tyousei_list[$pos]['yn_tsoneki'];
                    }
                    $cal_tsoneki = $tyousei_list[$pos]['cal_tysoneki'];
                    
                    $ri_tsoneki = empty($cal_ttov)?
                        0.0:
                        sprintf('%4.1f', round(($cal_tsoneki / $cal_ttov) * 100, 1));
                }
            }
            
            $item = compact(
                'no_project',
                'nm_project',
                'no_cyu',
                'approved_by2',
                'kb_cyumon',
                'cd_url',
                'nm_syohin',
                'nm_setti',
                'nm_user',
                'no_mitumori',
                'no_cyumon',
                'no_seizo',
                'yn_sp',
                'yn_tov',
                'yn_arari',
                'yn_cyunyu',
                'yn_soneki',
                'ri_soneki',
                'tm_cyokka',
                'yn_cyokka',
                'yn_cyokuzai',
                'yn_etc',
                'yn_ryohi',
                'dt_puriage',
                'nm_tanto',
                'nm_sien',
                'dt_hakkou',
                'dt_hatuban',
                'dt_kakunin',
                'nm_kakunin',
                'kb_uriage',
                'kb_keikaku',
                'fg_caution',
                'dt_cyunyu',
                'yn_ttov',
                'yn_tsoneki',
                'ri_tsoneki',
                'nm_kisyu',
                'nm_bunya_eigyo',
                'nm_bunya_seizo',
                'fg_unapproved'
            );
            $all_items[] = $item;
        }
        return ['data' => $all_items];
    }
}
