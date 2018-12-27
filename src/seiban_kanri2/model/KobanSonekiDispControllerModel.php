<?php
/**
*   Controller Model
*
*   @version 171017
*/
namespace seiban_kanri2\model;

use \Exception;
use Concerto\DateTimeUtil;
use Concerto\standard\ArrayUtil;
use Concerto\standard\ControllerModel;
use seiban_kanri2\model\KobanSonekiDispFactory;

class KobanSonekiDispControllerModel extends ControllerModel
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
    *   @param KobanSonekiDispFactory $factory
    */
    public function __construct(KobanSonekiDispFactory $factory)
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
            $this->session->no_cyu = $query->no_cyu?? '';
            $this->session->fg_koban = $query->fg_koban?? '';
            $this->session->fg_project = $query->fg_project?? '';
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
    *   担当設定
    *
    */
    public function setSeibanTanto()
    {
        $input_code = $this->globalSession->input_code;
        $no_cyu = $this->session->no_cyu;
        
        $post = $this->factory->getPost();
        $kb_tanto = (bool)$post->kb_tanto;
        
        $model = $this->factory->getKobanSonekiDispSeibanTanto();
        $model->setSeibanTanto($input_code, $no_cyu, $kb_tanto);
    }
    
    /**
    *   発番確認
    *
    */
    public function setHatubanKakunin()
    {
        $input_code = $this->globalSession->input_code;
        $no_cyu = $this->session->no_cyu;
        
        $post = $this->factory->getPost();
        $dt_hatuban = $post->dt_hatuban;
        $dt_kakunin = $post->dt_kakunin;
        $flg = empty($dt_kakunin);
        
        $model = $this->factory->getKobanSonekiDispHatubanKakunin();
        $model->setHatubanKakunin($input_code, $no_cyu, $dt_hatuban, $flg);
    }
    
    /**
    *   過去月計画値実績置換
    *
    */
    public function replacePerformanceToPlan()
    {
        $input_code = $this->globalSession->input_code;
        $no_cyu = $this->session->no_cyu;
        
        $model = $this->factory->getKobanSonekiDispReplacePlan();
        $model->replace($input_code, $no_cyu);
    }
    
    /**
    *   データ作成
    *
    */
    public function buildData()
    {
        $input_code = $this->globalSession->input_code;
        $no_cyu = $this->session->no_cyu;
        $fg_project = $this->session->fg_project;
        $fg_koban = $this->session->fg_koban;
        
        //担当設定
        $kobanSonekiDispSeibanTanto =
            $this->factory->getKobanSonekiDispSeibanTanto();
        $kb_tanto = (integer)$kobanSonekiDispSeibanTanto->isTantoSettei(
            $input_code,
            $no_cyu
        );
        
        //発番更新日
        $kobanSonekiDispHatubanKakunin =
            $this->factory->getKobanSonekiDispHatubanKakunin();
        $dt_hatuban = $kobanSonekiDispHatubanKakunin->getDtHatuban($no_cyu);
        //発番確認日
        $dt_kakunin = $kobanSonekiDispHatubanKakunin->getDtKakunin(
            $no_cyu,
            $dt_hatuban
        );
        
        $model = $this->factory->getModel($this->session->fg_project);
        $cd_bumon = ($this->session->cd_bumon == 'all')?
            null:$this->session->cd_bumon;
        $no_cyu_tmp = $this->session->no_cyu;
        
        //注番情報
        if ($fg_project) {
            $no_cyu = '';
            $nm_syohin = '';
            $nm_setti = '';
            $nm_user = '';
            
            $cyuban_list = $model->getCyubanList($no_cyu_tmp);
            $yn_sp = $cyuban_list['yn_sp']?? 0;
            $yn_net = $cyuban_list['yn_net']?? 0;
            $yn_arari = $yn_sp - $yn_net;
        } else {
            $cyuban_list = $model->getCyubanList($no_cyu_tmp);
            $no_cyu = empty($cyuban_list['no_cyu'])?
                '':$cyuban_list['no_cyu'];
            $nm_syohin = empty($cyuban_list['nm_syohin'])?
                '':$cyuban_list['nm_syohin'];
            $nm_setti = empty($cyuban_list['nm_setti'])?
                '':$cyuban_list['nm_setti'];
            $nm_user = empty($cyuban_list['nm_user'])?
                '':$cyuban_list['nm_user'];
            
            $yn_sp = $cyuban_list['yn_sp']?? 0;
            $yn_net = $cyuban_list['yn_net']?? 0;
            $yn_arari = $yn_sp - $yn_net;
        }
        
        //製番全体
        $koban_list_all = $model->getKobanAggregateList($no_cyu_tmp);
        foreach ((array)$koban_list_all as $list) {
            $yn_ptov1 = $list['yn_tov'];
            $tm_pcyokka1 = $list['tm_pcyokka'];
            $yn_pcyokka1 = $list['yn_pcyokka'];
            $yn_pcyokuzai1 = $list['yn_pcyokuzai'];
            $yn_pryohi1 = $list['yn_pryohi'];
            $yn_petc1 = $list['yn_petc'];
            $yn_pcyunyu1 = $list['yn_pcyunyu'];
            $yn_psoneki1 = $list['yn_psoneki'];
            $ri_psoneki1 = $list['ri_psoneki'];
            $yn_ytov1 = $list['yn_tov'];
            $tm_ycyokka1 = $list['tm_ycyokka'];
            $yn_ycyokka1 = $list['yn_ycyokka'];
            $yn_ycyokuzai1 = $list['yn_ycyokuzai'];
            $yn_yryohi1 = $list['yn_yryohi'];
            $yn_yetc1 = $list['yn_yetc'];
            $yn_ycyunyu1 = $list['yn_ycyunyu'];
            $yn_ysoneki1 = $list['yn_ysoneki'];
            $ri_ysoneki1 = $list['ri_ysoneki'];
            $yn_rtov1 = $list['yn_tov'];
            $tm_rcyokka1 = $list['tm_rcyokka'];
            $yn_rcyokka1 = $list['yn_rcyokka'];
            $yn_rcyokuzai1 = $list['yn_rcyokuzai'];
            $yn_rryohi1 = $list['yn_rryohi'];
            $yn_retc1 = $list['yn_retc'];
            $yn_rcyunyu1 = $list['yn_rcyunyu'];
            $yn_rsoneki1 = $list['yn_rsoneki'];
            $ri_rsoneki1 = $list['ri_rsoneki'];
        }
        
        //所属課
        $koban_list = $model->getKobanAggregateList($no_cyu_tmp, $cd_bumon);
        foreach ((array)$koban_list as $list) {
            $yn_ptov2 = $list['yn_tov'];
            $tm_pcyokka2 = $list['tm_pcyokka'];
            $yn_pcyokka2 = $list['yn_pcyokka'];
            $yn_pcyokuzai2 = $list['yn_pcyokuzai'];
            $yn_pryohi2 = $list['yn_pryohi'];
            $yn_petc2 = $list['yn_petc'];
            $yn_pcyunyu2 = $list['yn_pcyunyu'];
            $yn_psoneki2 = $list['yn_psoneki'];
            $ri_psoneki2 = $list['ri_psoneki'];
            $yn_ytov2 = $list['yn_tov'];
            $tm_ycyokka2 = $list['tm_ycyokka'];
            $yn_ycyokka2 = $list['yn_ycyokka'];
            $yn_ycyokuzai2 = $list['yn_ycyokuzai'];
            $yn_yryohi2 = $list['yn_yryohi'];
            $yn_yetc2 = $list['yn_yetc'];
            $yn_ycyunyu2 = $list['yn_ycyunyu'];
            $yn_ysoneki2 = $list['yn_ysoneki'];
            $ri_ysoneki2 = $list['ri_ysoneki'];
            $yn_rtov2 = $list['yn_tov'];
            $tm_rcyokka2 = $list['tm_rcyokka'];
            $yn_rcyokka2 = $list['yn_rcyokka'];
            $yn_rcyokuzai2 = $list['yn_rcyokuzai'];
            $yn_rryohi2 = $list['yn_rryohi'];
            $yn_retc2 = $list['yn_retc'];
            $yn_rcyunyu2 = $list['yn_rcyunyu'];
            $yn_rsoneki2 = $list['yn_rsoneki'];
            $ri_rsoneki2 = $list['ri_rsoneki'];
        }
        
        $cyunyu_list = $model->getKobanMonList($no_cyu_tmp);
        
        $dt_min = '299901';
        $dt_max = '';
        
        //日付最大・最小
        foreach ((array)$cyunyu_list as $list) {
            if (!empty($list['dt_kanjyo'])) {
                if ($list['dt_kanjyo'] < $dt_min) {
                    $dt_min = $list['dt_kanjyo'];
                }
                
                if ($list['dt_kanjyo'] > $dt_max) {
                    $dt_max = $list['dt_kanjyo'];
                }
            }
        }
        
        //期間
        try {
            if (!empty($dt_max)) {
                $dt_yyyymm = DateTimeUtil::getIntervalYYYYMM(
                    $dt_min. '01',
                    $dt_max. '01'
                );
            } else {
                $dt_yyyymm = [];
            }
        } catch (Exception $e) {
            $dt_yyyymm = [];
        }
        
        $this->fromArray(compact(
            'no_cyu',
            'fg_project',
            'fg_koban',
            'kb_tanto',
            'no_cyu_tmp',
            'dt_hatuban',
            'dt_kakunin',
            'nm_syohin',
            'nm_setti',
            'nm_user',
            'yn_sp',
            'yn_arari',
            'yn_ptov1',
            'tm_pcyokka1',
            'yn_pcyokka1',
            'yn_pcyokuzai1',
            'yn_pryohi1',
            'yn_petc1',
            'yn_pcyunyu1',
            'yn_psoneki1',
            'ri_psoneki1',
            'yn_ytov1',
            'tm_ycyokka1',
            'yn_ycyokka1',
            'yn_ycyokuzai1',
            'yn_yryohi1',
            'yn_yetc1',
            'yn_ycyunyu1',
            'yn_ysoneki1',
            'ri_ysoneki1',
            'yn_rtov1',
            'tm_rcyokka1',
            'yn_rcyokka1',
            'yn_rcyokuzai1',
            'yn_rryohi1',
            'yn_retc1',
            'yn_rcyunyu1',
            'yn_rsoneki1',
            'ri_rsoneki1',
            'yn_ptov2',
            'tm_pcyokka2',
            'yn_pcyokka2',
            'yn_pcyokuzai2',
            'yn_pryohi2',
            'yn_petc2',
            'yn_pcyunyu2',
            'yn_psoneki2',
            'ri_psoneki2',
            'yn_ytov2',
            'tm_ycyokka2',
            'yn_ycyokka2',
            'yn_ycyokuzai2',
            'yn_yryohi2',
            'yn_yetc2',
            'yn_ycyunyu2',
            'yn_ysoneki2',
            'ri_ysoneki2',
            'yn_rtov2',
            'tm_rcyokka2',
            'yn_rcyokka2',
            'yn_rcyokuzai2',
            'yn_rryohi2',
            'yn_retc2',
            'yn_rcyunyu2',
            'yn_rsoneki2',
            'ri_rsoneki2',
            'dt_yyyymm'
        ));
    }
    
    /**
    *   gridデータ作成(注番表示)
    *
    *   @return array
    */
    public function buildGridCyubanData()
    {
        $model = $this->factory->getModel($this->session->fg_project);
        
        $no_cyu_tmp = $this->session->no_cyu;
        
        $koban_list = $model->getKobanList($no_cyu_tmp);
        
        $all_items = [];
        
        foreach ($koban_list as $list) {
            $no_cyu = $list['no_cyu'];
            $no_ko = $list['no_ko'];
            $nm_syohin = $list['nm_syohin'];
            $cd_bumon = $list['cd_bumon'];
            $dt_pkansei = $list['dt_pkansei'];
            $yn_tov = $list['yn_tov'];
            
            //計画
            $tm_pcyokka = $tm_cyokka = empty($list['tm_pcyokka'])?
                0:$list['tm_pcyokka'];
            $yn_pcyokka = $yn_cyokka = empty($list['yn_pcyokka'])?
                0:$list['yn_pcyokka'];
            $yn_pcyokuzai = $yn_cyokuzai = empty($list['yn_pcyokuzai'])?
                0:$list['yn_pcyokuzai'];
            $yn_petc = $yn_etc = empty($list['yn_petc'])?
                0:$list['yn_petc'];
            $yn_pryohi = $yn_ryohi = empty($list['yn_pryohi'])?
                0:$list['yn_pryohi'];
            
            $yn_pcyunyu = $yn_cyunyu = empty($list['yn_pcyunyu'])?
                0:$list['yn_pcyunyu'];
            $yn_psoneki = $yn_soneki = empty($list['yn_psoneki'])?
                0:$list['yn_psoneki'];
            $ri_psoneki = $ri_soneki = empty($list['ri_psoneki'])?
                0:$list['ri_psoneki'];
            
            $nm_type = '0';
            
            $item1 = compact(
                'no_cyu',
                'no_ko',
                'nm_syohin',
                'cd_bumon',
                'dt_pkansei',
                'nm_type',
                'yn_tov',
                'yn_cyunyu',
                'yn_soneki',
                'ri_soneki',
                'tm_cyokka',
                'yn_cyokka',
                'yn_cyokuzai',
                'yn_etc',
                'yn_ryohi'
            );
            $item1['fg_view'] = true;
            
            $item1['yn_ttov'] = '';
            $item1['yn_tsoneki'] = '';
            $item1['ri_tsoneki'] = '';
            $item1['nm_biko'] = '';
            $item1['fg_tyousei'] = false;
            $all_items[] = $item1;
            
            //予測
            $tm_ycyokka = $tm_cyokka = empty($list['tm_ycyokka'])?
                0:$list['tm_ycyokka'];
            $yn_ycyokka = $yn_cyokka = empty($list['yn_ycyokka'])?
                0:$list['yn_ycyokka'];
            $yn_ycyokuzai = $yn_cyokuzai = empty($list['yn_ycyokuzai'])?
                0:$list['yn_ycyokuzai'];
            $yn_yetc = $yn_etc = empty($list['yn_yetc'])?
                0:$list['yn_yetc'];
            $yn_yryohi = $yn_ryohi = empty($list['yn_yryohi'])?
                0:$list['yn_yryohi'];
                              
            $yn_ycyunyu = $yn_cyunyu = empty($list['yn_ycyunyu'])?
                0:$list['yn_ycyunyu'];
            $yn_ysoneki = $yn_soneki = empty($list['yn_ysoneki'])?
                0:$list['yn_ysoneki'];
            $ri_ysoneki = $ri_soneki = empty($list['ri_ysoneki'])?
                0:$list['ri_ysoneki'];
            
            $nm_type = '2';
            
            $item3 = compact(
                'no_cyu',
                'no_ko',
                'nm_syohin',
                'cd_bumon',
                'dt_pkansei',
                'nm_type',
                'yn_tov',
                'yn_cyunyu',
                'yn_soneki',
                'ri_soneki',
                'tm_cyokka',
                'yn_cyokka',
                'yn_cyokuzai',
                'yn_etc',
                'yn_ryohi'
            );
            $item3['fg_view'] = false;
            
            $item3['yn_ttov'] = $list['yn_ttov']?? '';
            $item3['yn_tsoneki'] = $list['yn_tsoneki']?? '';
            $item3['ri_tsoneki'] = $list['ri_tsoneki'];
            $item3['nm_biko'] = $list['nm_biko'];
            $item3['fg_tyousei'] = true;
            $all_items[] = $item3;
            
            //実績
            $tm_rcyokka = $tm_cyokka = empty($list['tm_rcyokka'])?
                0:$list['tm_rcyokka'];
            $yn_rcyokka = $yn_cyokka = empty($list['yn_rcyokka'])?
                0:$list['yn_rcyokka'];
            $yn_rcyokuzai = $yn_cyokuzai = empty($list['yn_rcyokuzai'])?
                0:$list['yn_rcyokuzai'];
            $yn_retc = $yn_etc = empty($list['yn_retc'])?
                0:$list['yn_retc'];
            $yn_rryohi = $yn_ryohi = empty($list['yn_rryohi'])?
                0:$list['yn_rryohi'];
                              
            $yn_rcyunyu = $yn_cyunyu = empty($list['yn_rcyunyu'])?
                0:$list['yn_rcyunyu'];
            $yn_rsoneki = $yn_soneki = empty($list['yn_rsoneki'])?
                0:$list['yn_rsoneki'];
            $ri_rsoneki = $ri_soneki = empty($list['ri_rsoneki'])?
                0:$list['ri_rsoneki'];
            
            $nm_type = '1';
            
            $item2 = compact(
                'no_cyu',
                'no_ko',
                'nm_syohin',
                'cd_bumon',
                'dt_pkansei',
                'nm_type',
                'yn_tov',
                'yn_cyunyu',
                'yn_soneki',
                'ri_soneki',
                'tm_cyokka',
                'yn_cyokka',
                'yn_cyokuzai',
                'yn_etc',
                'yn_ryohi'
            );
            
            $item2['fg_view'] = false;
            $item2['yn_ttov'] = '';
            $item2['yn_tsoneki'] = '';
            $item2['ri_tsoneki'] = '';
            $item2['nm_biko'] = '';
            $item2['fg_tyousei'] = false;
            $all_items[] = $item2;
        }
        return ['data' => $all_items];
    }
    
    /**
    *   gridデータ作成(プロジェクト表示)
    *
    *   @return array
    */
    public function buildGridProjectData()
    {
        $model = $this->factory->getModel($this->session->fg_project);
        
        $no_cyu_tmp = $this->session->no_cyu;
        $fg_project = $this->session->fg_project;
        
        $model = $this->factory->getModel($fg_project);
        
        $cyunyu_list = $model->getKobanMonList($no_cyu_tmp);
        
        $all_items = [];
        $items = [];
        $dt_min = '299901';
        $dt_max = '';
        $cyunyu_old = [];
        $no_syukei = 0.0;
        
        //行単位データ作成
        foreach ((array)$cyunyu_list as $list) {
            //変化しないカラムを抜き出す(dt_kanjyo, yn_soneki除く)
            $source = array(
                'no_cyu' => $list['no_cyu'],
                'no_ko' => $list['no_ko'],
                'nm_syohin' => $list['nm_syohin'],
                'dt_pkansei' => $list['dt_pkansei_m'],
                'cd_bumon' => $list['cd_bumon'],
                'cd_genka_yoso' => $list['cd_genka_yoso'],
                'cd_tanto' => $list['cd_tanto'],
                'nm_tanto' => $list['nm_tanto'],
                'nm_syohin2' => $list['nm_syohin2'],
                'kb_cyunyu' => $list['kb_cyunyu']
            );
            
            $diff = array_diff_assoc($source, $cyunyu_old);
            
            //変化があれば
            if (count($diff) > 0) {
                //初回以外
                if ($dt_max != '') {
                    $items['no_syukei'] = $no_syukei;
                    $all_items[] = $items;
                    $no_syukei = 0.0;
                }
                $items = $source;
            }
            
            $items[$list['dt_kanjyo']] = $list['yn_cyunyu'];
            
            //データ集計
            $no_syukei += (float)$list['yn_cyunyu'];
            
            if (!empty($list['dt_kanjyo'])) {
                if ($list['dt_kanjyo'] < $dt_min) {
                    $dt_min = $list['dt_kanjyo'];
                }
                
                if ($list['dt_kanjyo'] > $dt_max) {
                    $dt_max = $list['dt_kanjyo'];
                }
            }
            
            $cyunyu_old = $source;
        }
        
        if (!empty($items)) {
            $all_items[] = $items;
        }
        
        //期間
        try {
            if (!empty($dt_max)) {
                $dt_yyyymm = DateTimeUtil::getIntervalYYYYMM(
                    $dt_min. '01',
                    $dt_max. '01'
                );
            } else {
                return ['data' => []];
            }
        } catch (Exception $e) {
            return ['data' => []];
        }
        
        //空日付データカラム
        $empty = [];
        
        foreach ((array)$dt_yyyymm as $val) {
            $empty[$val] = '';
        }
        
        $data_list = [];
        $flg = 0;   //0:計画 1:実績
        $source_old = [];
        
        foreach ((array)$all_items as $list) {
            $source = array(
                'no_cyu' => $list['no_cyu'],
                'no_ko' => $list['no_ko'],
                'nm_syohin' => $list['nm_syohin'],
                'dt_pkansei' => $list['dt_pkansei'],
                'cd_bumon' => $list['cd_bumon'],
                'cd_genka_yoso' => $list['cd_genka_yoso'],
                'cd_tanto' => $list['cd_tanto'],
                'nm_tanto' => $list['nm_tanto']
            );
            
            //日付カラムを調査し、無いカラムを追加
            unset($data);
            foreach ((array)$dt_yyyymm as $val) {
                if (!array_key_exists($val, $list)) {
                    $data[$val] = '';
                } else {
                    $data[$val] = $list[$val];
                }
            }
            
            //計画行処理中に計画データ
            if ((!$flg) && ($list['kb_cyunyu'] == '0')) {
                $items = $source;
                $items['no_syukei'] = $list['no_syukei'];
                $items['kb_cyunyu'] = '0';
                $data_list[] = ArrayUtil::mergeKeepKey($items, $data);
                $flg = 1;
            //計画行処理中に実績データ
            } elseif ((!$flg) && ($list['kb_cyunyu'] == '1')) {
                $items = $source;
                $items['no_syukei'] = 0;
                $items['kb_cyunyu'] = '0';
                $data_list[] = ArrayUtil::mergeKeepKey($items, $empty);
                $items = $source;
                $items['no_syukei'] = $list['no_syukei'];
                $items['kb_cyunyu'] = '1';
                $data_list[] = ArrayUtil::mergeKeepKey($items, $data);
            //計画行処理中に計画実績共になし
            } elseif (!$flg) {
            //実績行処理中に実績データ
            } elseif (($flg) && ($list['kb_cyunyu'] == '1')) {
                if ($source == $source_old) {
                    $items = $source;
                    $items['no_syukei'] = $list['no_syukei'];
                    $items['kb_cyunyu'] = '1';
                    $data_list[] = ArrayUtil::mergeKeepKey($items, $data);
                } else {
                    $items = $source_old;
                    $items['no_syukei'] = 0;
                    $items['kb_cyunyu'] = '1';
                    $data_list[] = ArrayUtil::mergeKeepKey($items, $empty);
                    $items = $source;
                    $items['no_syukei'] = 0;
                    $items['kb_cyunyu'] = '0';
                    $data_list[] = ArrayUtil::mergeKeepKey($items, $empty);
                    $items = $source;
                    $items['no_syukei'] = $list['no_syukei'];
                    $items['kb_cyunyu'] = '1';
                    $data_list[] = ArrayUtil::mergeKeepKey($items, $data);
                }
                $flg = 0;
                
            //実績行処理中に計画データ
            } elseif (($flg) && ($list['kb_cyunyu'] == '0')) {
                $items = $source_old;
                $items['no_syukei'] = 0;
                $items['kb_cyunyu'] = '1';
                $data_list[] = ArrayUtil::mergeKeepKey($items, $empty);
                $items = $source;
                $items['no_syukei'] = $list['no_syukei'];
                $items['kb_cyunyu'] = '0';
                $data_list[] = ArrayUtil::mergeKeepKey($items, $data);
            //実績行処理中に計画実績共になし
            } else {
            }
            
            $source_old = $source;
        }
        
        //最後が実績行で終わる
        if ($flg) {
            $items = $source;
            $items['no_syukei'] = 0;
            $items['kb_cyunyu'] = '1';
            $data_list[] = ArrayUtil::mergeKeepKey($items, $empty);
        }
        return ['data' => $data_list];
    }
}
