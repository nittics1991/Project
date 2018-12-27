<?php
/**
*   Controller Model
*
*   @version 171012
*/
namespace seiban_kanri2\model;

use Concerto\standard\ControllerModel;
use Concerto\standard\StringUtil;
use seiban_kanri2\model\CyunyuInfDispFactory;

class CyunyuInfDispControllerModel extends ControllerModel
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
    *   @param CyunyuInfDispFactory $factory
    */
    public function __construct(CyunyuInfDispFactory $factory)
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
            $this->session->no_ko = $query->no_ko??
                $this->session->no_ko;
            $this->session->fg_crt = $query->fg_crt?? '0';
            $this->session->no_seq = $query->no_seq?? '';
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
        $this->keikaku = $post->keikaku?? '';
        $ans = $post->isValid();
        $this->validError[] = $post->getValidError();
        return $ans;
    }
    
    /**
    *   注入確定設定
    *
    */
    public function setCyunyuLock()
    {
        $input_code = $this->globalSession->input_code;
        $no_cyu = $this->session->no_cyu;
        $no_ko = $this->session->no_ko;
        
        $model = $this->factory->getCyunyuInfDispCyunyuLock();
        $fg_lock = $model->isKeikakuSettei($no_cyu, $no_ko);
        $fg_lock = empty($fg_lock);
        $model->setCyunyuLock($input_code, $no_cyu, $no_ko, $fg_lock);
    }
    
    /**
    *   データ保存
    *
    */
    public function setCyunyuData()
    {
        $post = $this->factory->getPost();
        $model = $this->factory->getMntModel();
        $model->setCyunyuData($post);
        $this->no_seq = $this->session->no_seq = '';
        return [
            'no_cyu' => $this->session->no_cyu,
            'no_ko' => $this->session->no_ko
        ];
    }
    
    /**
    *   データ作成
    *
    */
    public function buildData()
    {
        $model = $this->factory->getModel();
        
        $input_code = $this->globalSession->input_code;
        
        $kengen_sm = $this->globalSession->kengen_sm;
        $kengen_db = $this->globalSession->kengen_db;
        
        $no_cyu = $this->session->no_cyu;
        $no_ko = $this->session->no_ko;
        $fg_crt = $this->session->fg_crt;
        $no_seq = $this->session->no_seq;
        $kb_nendo = $this->session->kb_nendo;
        $cd_bumon = $this->session->cd_bumon;
        
        $cyunyuInfDispCyunyuLock =
            $this->factory->getCyunyuInfDispCyunyuLock();
        $fg_lock = $cyunyuInfDispCyunyuLock->isKeikakuSettei($no_cyu, $no_ko);
        
        $cd_genka_yoso = $this->cd_genka_yoso?? 'B';
        $cd_tanto = $this->cd_tanto?? '';
        $nm_tanto = $this->nm_tanto?? '';
        $nm_syohin = $this->nm_syohin?? '';
        $num_data = $this->num_data?? array_fill(0, 12, 0);
        
        $no_seiban = $no_cyu . $no_ko;
        
        //注番情報
        $cyuban_list = $model->getCyubanList($no_cyu);
        $nm_syohin = empty($cyuban_list['nm_syohin'])?
            '':$cyuban_list['nm_syohin'];
        $nm_setti = empty($cyuban_list['nm_setti'])?
            '':$cyuban_list['nm_setti'];
        $nm_user = empty($cyuban_list['nm_user'])?
            '':$cyuban_list['nm_user'];
        $yn_sp = empty($cyuban_list['yn_sp'])?
            0:$cyuban_list['yn_sp'];
        $yn_net = empty($cyuban_list['yn_net'])?
            0:$cyuban_list['yn_net'];
        $yn_arari = $yn_sp - $yn_net;
        
        //項番情報
        $koban_list = $model->getKobanList($no_cyu, $no_ko);
        $nm_syohin2 = empty($koban_list['nm_syohin'])?
            '':$koban_list['nm_syohin'];
        
        //項番集計
        $koban_list2 = $model->getKobanAggregateList($no_cyu, $no_ko);
        
        foreach ((array)$koban_list2 as $list) {
            $yn_psp = $yn_sp;
            $yn_ptov = $list['yn_tov'];
            $yn_parari = $yn_arari;
            $tm_pcyokka = $list['tm_pcyokka'];
            $yn_pcyokka = $list['yn_pcyokka'];
            $yn_pcyokuzai = $list['yn_pcyokuzai'];
            $yn_pryohi = $list['yn_pryohi'];
            $yn_petc = $list['yn_petc'];
            $yn_pcyunyu = $list['yn_pcyunyu'];
            $yn_psoneki = $list['yn_psoneki'];
            $ri_psoneki = $list['ri_psoneki'];
            
            $yn_ysp = $yn_sp;
            $yn_ytov = $list['yn_tov'];
            $yn_yarari = $yn_arari;
            $tm_ycyokka = $list['tm_ycyokka'];
            $yn_ycyokka = $list['yn_ycyokka'];
            $yn_ycyokuzai = $list['yn_ycyokuzai'];
            $yn_yryohi = $list['yn_yryohi'];
            $yn_yetc = $list['yn_yetc'];
            $yn_ycyunyu = $list['yn_ycyunyu'];
            $yn_ysoneki = $list['yn_ysoneki'];
            $ri_ysoneki = $list['ri_ysoneki'];
            
            $yn_rsp = $yn_sp;
            $yn_rtov = $list['yn_tov'];
            $yn_rarari = $yn_arari;
            $tm_rcyokka = $list['tm_rcyokka'];
            $yn_rcyokka = $list['yn_rcyokka'];
            $yn_rcyokuzai = $list['yn_rcyokuzai'];
            $yn_rryohi = $list['yn_rryohi'];
            $yn_retc = $list['yn_retc'];
            $yn_rcyunyu = $list['yn_rcyunyu'];
            $yn_rsoneki = $list['yn_rsoneki'];
            $ri_rsoneki = $list['ri_rsoneki'];
        }
        
        //新規登録の場合
        if (!$no_seq) {
            $dt_yyyymm = $model->getYYYYMMList($kb_nendo);
            $kb_nendo_list = $model->getNendoList($no_cyu, $no_ko);
            $genka_yoso_list = $model->getGenkaYosoList();
            $bumon_list  = $model->getBumonList();
            $tanto_list  = $model->getTantoList($cd_bumon);
        //既登録
        } else {
            $cyunyu_list = $model->getCyunyuData($no_cyu, $no_ko, $no_seq);
            
            $kb_nendo = '2999S';
            foreach ((array)$cyunyu_list as $list) {
                if ($list['kb_nendo'] < $kb_nendo) {
                    $kb_nendo = $list['kb_nendo'];
                }
            }
            
            $dt_yyyymm = $model->getYYYYMMList($kb_nendo);
            
            $i = 0;
            foreach ((array)$cyunyu_list as $list) {
                if ($i == 0) {
                    $nm_tanto = $list['nm_tanto'];
                    $nm_syohin3 = $list['nm_syohin'];
                    $cd_genka_yoso = $list['cd_genka_yoso'];
                    $cd_tanto = $list['cd_tanto'];
                    $kb_nendo = $list['kb_nendo'];
                }
                
                switch ($cd_genka_yoso) {
                    case 'A':
                        for ($i = 0; $i < count($dt_yyyymm); $i++) {
                            if ($dt_yyyymm[$i] == $list['dt_kanjyo']) {
                                $num_data[$i] = $list['yn_cyokuzai'];
                                break;
                            }
                        }
                        break;
                    case 'B':
                        for ($i = 0; $i < count($dt_yyyymm); $i++) {
                            if ($dt_yyyymm[$i] == $list['dt_kanjyo']) {
                                $num_data[$i] = $list['tm_cyokka'];
                                break;
                            }
                        }
                        break;
                    case 'C':
                        for ($i = 0; $i < count($dt_yyyymm); $i++) {
                            if ($dt_yyyymm[$i] == $list['dt_kanjyo']) {
                                //Symphony移行で旅費とその他は同じ扱い
                                if (($list['yn_etc'] != 0)
                                        && empty($list['yn_ryohi'])
                                ) {
                                    $num_data[$i] = $list['yn_etc'];
                                } elseif (($list['yn_ryohi'] != 0)
                                    && empty($list['yn_etc'])
                                ) {
                                    $num_data[$i] = $list['yn_ryohi'];
                                }
                                break;
                            }
                        }
                }
                $i++;
            }
        }
        
        $nm_tanto_list = StringUtil::jsonEncode($model->getNmTantoList());
        $nm_syohin_list = StringUtil::jsonEncode($model->getNmSyohinList());
        
        $this->fromArray(compact(
            'input_code',
            'kengen_sm',
            'kengen_db',
            'cd_bumon',
            'kb_nendo',
            'no_cyu',
            'no_ko',
            'no_seq',
            'no_seiban',
            'fg_crt',
            'fg_lock',
            'yn_psp',
            'yn_ptov',
            'yn_parari',
            'tm_pcyokka',
            'yn_pcyokka',
            'yn_pcyokuzai',
            'yn_pryohi',
            'yn_petc',
            'yn_pcyunyu',
            'yn_psoneki',
            'ri_psoneki',
            'yn_ysp',
            'yn_ytov',
            'yn_yarari',
            'tm_ycyokka',
            'yn_ycyokka',
            'yn_ycyokuzai',
            'yn_yryohi',
            'yn_yetc',
            'yn_ycyunyu',
            'yn_ysoneki',
            'ri_ysoneki',
            'yn_rsp',
            'yn_rtov',
            'yn_rarari',
            'tm_rcyokka',
            'yn_rcyokka',
            'yn_rcyokuzai',
            'yn_rryohi',
            'yn_retc',
            'yn_rcyunyu',
            'yn_rsoneki',
            'ri_rsoneki',
            'kb_nendo_list',
            'genka_yoso_list',
            'bumon_list',
            'tanto_list',
            'cd_genka_yoso',
            'cd_tanto',
            'nm_tanto',
            'nm_syohin',
            'num_data',
            'nm_syohin',
            'nm_setti',
            'nm_user',
            'nm_syohin2',
            'dt_yyyymm',
            'nm_syohin3',
            'nm_tanto_list',
            'nm_syohin_list'
        ));
    }
    
    /**
    *   gridデータ作成(計画)
    *
    *   @return array
    */
    public function buildGridPlanData()
    {
        $model = $this->factory->getModel();
        
        $no_cyu = $this->session->no_cyu;
        $no_ko = $this->session->no_ko;
        
        $cyunyu_list = $model->getCyunyuList($no_cyu, $no_ko, '0');
        
        $all_items = [];
        
        foreach ($cyunyu_list as $list) {
            $dt_kanjyo = $list['dt_kanjyo'];
            $cd_genka_yoso = $list['cd_genka_yoso'];
            $nm_tanto = $list['nm_tanto'];
            $nm_syohin = $list['nm_syohin'];
            $tm_cyokka = $list['tm_cyokka'];
            $yn_cyokka = $list['yn_cyokka'];
            $yn_cyokuzai = $list['yn_cyokuzai'];
            $yn_ryohi = $list['yn_ryohi'];
            $yn_etc  = $list['yn_etc'];
            $no_seq  = $list['no_seq'];
            
            $item = compact(
                'no_cyu',
                'no_ko',
                'no_seq',
                'dt_kanjyo',
                'cd_genka_yoso',
                'nm_tanto',
                'nm_syohin',
                'tm_cyokka',
                'yn_cyokka',
                'yn_cyokuzai',
                'yn_etc',
                'yn_ryohi'
            );
            $all_items[] = $item;
        }
        return ['data' => $all_items];
    }
    
    /**
    *   gridデータ作成(実績)
    *
    *   @return array
    */
    public function buildGridRealData()
    {
        $model = $this->factory->getModel();
        
        $no_cyu = $this->session->no_cyu;
        $no_ko = $this->session->no_ko;
        
        $cyunyu_list = $model->getCyunyuList($no_cyu, $no_ko, '1');
        
        $all_items = [];
        
        foreach ($cyunyu_list as $list) {
            $dt_kanjyo = $list['dt_kanjyo'];
            $cd_genka_yoso = $list['cd_genka_yoso'];
            $nm_tanto = $list['nm_tanto'];
            $nm_syohin = $list['nm_syohin'];
            $tm_cyokka = $list['tm_cyokka'];
            $yn_cyokka = $list['yn_cyokka'];
            $yn_cyokuzai = $list['yn_cyokuzai'];
            $yn_ryohi = $list['yn_ryohi'];
            $yn_etc  = $list['yn_etc'];
            $no_seq  = $list['no_seq'];
            $dt_cyunyu = empty($list['fg_kanjyo'])? $list['dt_cyunyu']:'';
            $no_cyumon = $list['no_cyumon'];
            $dt_noki = $list['dt_noki'];
            
            $item = compact(
                'no_cyu',
                'no_ko',
                'no_seq',
                'dt_kanjyo',
                'dt_noki',
                'dt_cyunyu',
                'no_cyumon',
                'cd_genka_yoso',
                'nm_tanto',
                'nm_syohin',
                'tm_cyokka',
                'yn_cyokka',
                'yn_cyokuzai',
                'yn_etc',
                'yn_ryohi'
            );
            $all_items[] = $item;
        }
        return ['data' => $all_items];
    }
    
    /**
    *   query処理(cd_bumon)
    *
    */
    public function isValidQueryCdBumon()
    {
        $query = $this->factory->getQueryCdBumon();
        $ans = $query->isValid();
        $this->validError[] = $query->getValidError();
        return $ans;
    }
    
    /**
    *   担当リスト
    *
    *   @return array
    */
    public function buildCdTantoList()
    {
        $query = $this->factory->getQueryCdBumon();
        $model = $this->factory->getModel();
        $cd_bumon = $query->cd_bumon;
        return $model->getTantoList($cd_bumon);
    }
}
