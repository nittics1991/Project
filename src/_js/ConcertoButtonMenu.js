/**
*	Button Menu
*
*
*
**/
var Concerto = Concerto || {};

Concerto.ButtonMenu = function(target) {
	/**
	*	properties
	**/
	var prop  = {
		root:'',
		element:null,
		remember_length:20
	};
	
	/**
	*	
	**/
	_buildUrl = function() {
		var elm = document.createElement('a');
		elm.href = location.href;
		var path = elm.pathname.match(/itc_.+\/itc_main.files/);
		prop.root = elm.protocol + '//' + elm.hostname + ':' + elm.port + '/' + path[0];
	};
	
	/**
	*	
	**/
	_buildMenu = function() {
		prop.template = '\
			<div>\
				<div>\
					<a class="menu_button ui-state-default" href="javascript:void(0);" title="ドロップダウンメニューを表示します">▼</a>\
				</div>\
				<ul class="menu_menu" style="position:absolute; z-index:50;">\
					<li><a href="' + prop.root + '/index.php" target="_blank">トップページ</a></li>\
					<li><a href="' + prop.root + '/my_page2/index.php" target="_blank">マイページ</a></li>\
					<li class="menu_remember">\
						<span>自動記録</span>\
						<ul>\
						</ul>\
					</li>\
					<li>\
						<a href="' + prop.root + '/alarm_kanri2/index.php" target="_blank">アラーム管理</a>\
						<ul>\
							<li><a href="' + prop.root + '/alarm_kanri2/alarm_kanri_disp.php?cd_type=1" target="_blank">発番未承認</a></li>\
							<li><a href="' + prop.root + '/alarm_kanri2/alarm_kanri_disp.php?cd_type=2" target="_blank">注入計画未登録</a></li>\
							<li><a href="' + prop.root + '/alarm_kanri2/alarm_kanri_disp.php?cd_type=3" target="_blank">製番実績未注入</a></li>\
							<li><a href="' + prop.root + '/alarm_kanri2/alarm_kanri_disp.php?cd_type=4" target="_blank">製番実績値>計画値</a></li>\
							<li><a href="' + prop.root + '/alarm_kanri2/alarm_kanri_disp.php?cd_type=5" target="_blank">製番損益マイナス</a></li>\
							<li><a href="' + prop.root + '/alarm_kanri2/alarm_kanri_disp.php?cd_type=6" target="_blank">製番未完成</a></li>\
							<li><a href="' + prop.root + '/alarm_kanri2/alarm_kanri_disp.php?cd_type=7" target="_blank">WF未登録</a></li>\
							<li><a href="' + prop.root + '/alarm_kanri2/alarm_kanri_disp.php?cd_type=8" target="_blank">WF進捗遅れ</a></li>\
							<li><a href="' + prop.root + '/alarm_kanri2/alarm_kanri_disp.php?cd_type=9" target="_blank">WF実績未登録</a></li>\
							<li><a href="' + prop.root + '/alarm_kanri2/alarm_kanri_disp.php?cd_type=10" target="_blank">クレーム未更新</a></li>\
						</ul>\
					</li>\
					<li>\
						<a href="' + prop.root + '/mitumori_inf2/index.php" target="_blank">見積台帳</a>\
						<ul>\
							<li><a href="' + prop.root + '/mitumori_inf2/index.php" target="_blank">見積台帳</a></li>\
							<li><a href="' + prop.root + '/prospect_inf/eigyo_prospect_disp.php" target="_blank">営業別プロスペクト</a></li>\
							<li><a href="' + prop.root + '/prospect_inf/seizo_prospect_disp.php" target="_blank">製造別プロスペクト</a></li>\
							<li><a href="' + prop.root + '/prospect_cyuban/index.php" target="_blank">プロスペクト注番</a></li>\
							<li><a href="' + prop.root + '/seiban_furikae2/index.php" target="_blank">実製番昇格</a></li>\
							<li><a href="' + prop.root + '/hatuban_hattyu/index.php" target="_blank">発番発注管理</a></li>\
						</ul>\
					</li>\
					<li>\
						<a href="' + prop.root + '/seiban_kanri2/index.php" target="_blank">製番管理</a>\
						<ul>\
							<li><a href="' + prop.root + '/seiban_kanri2/index.php" target="_blank">製番管理</a></li>\
							<li><a href="' + prop.root + '/project_inf/index.php" target="_blank">プロジェクト管理</a></li>\
							<li><a href="' + prop.root + '/seiban_keikaku2/index.php" target="_blank">製番計画</a></li>\
							<li><a href="' + prop.root + '/cyokka_rituan2/index.php" target="_blank">直課計画立案</a></li>\
						</ul>\
					</li>\
					<li>\
						<a href="' + prop.root + '/fukayama_kanri2/index.php" target="_blank">負荷山管理</a>\
						<ul>\
							<li><a href="' + prop.root + '/fukayama_kanri2/index.php" target="_blank">負荷山管理</a></li>\
							<li><a href="' + prop.root + '/fukayama_kanri2/fukayama_inf_disp.php?fg_type=1" target="_blank">負荷山直近6ヶ月</a></li>\
							<li><a href="' + prop.root + '/seiban_keikaku2/cyokka_tanto_disp.php" target="_blank">直課計画</a></li>\
							<li><a href="' + prop.root + '/cyokka_jisseki2/index.php" target="_blank">直課実績</a></li>\
							<li><a href="' + prop.root + '/cyokka_jisseki2/tougetu_cyokka_disp.php" target="_blank">当月直課</a></li>\
						</ul>\
					</li>\
					<li>\
						<a href="' + prop.root + '/wf_new2/index.php" target="_blank">ワークフロー</a>\
						<ul>\
							<li><a href="' + prop.root + '/wf_new2/wf_new_disp.php?fg_wf=0" target="_blank">ワークフロー</a></li>\
							<li><a href="' + prop.root + '/wf_new2/wf_new_disp.php?fg_wf=3" target="_blank">WFカレンダー</a></li>\
							<li><a href="' + prop.root + '/syukka_kanri/index.php" target="_blank">出荷管理</a></li>\
							<li><a href="' + prop.root + '/claim_inf/login.php" target="_blank">クレーム情報</a></li>\
							<li><a href="' + prop.root + '/mondaiten_inf2/index.php" target="_blank">問題点分析</a></li>\
						</ul>\
					</li>\
					<li>\
						<a href="' + prop.root + '/tyotatu_inf/index.php" target="_blank">注文情報</a>\
						<ul>\
							<li><a href="' + prop.root + '/tyotatu_inf/index.php" target="_blank">注文情報</a></li>\
							<li><a href="' + prop.root + '/tyotatu_inf/tyotatu_inf_disp.php?fg_crt=1" target="_blank">調達スケジュール</a></li>\
							<li><a href="' + prop.root + '/tyotatu_inf/tyotatu_syukei_disp.php" target="_blank">調達集計</a></li>\
							<li><a href="' + prop.root + '/jyukyu_inf/index.php" target="_blank">受給品管理</a></li>\
							<li><a href="' + prop.root + '/konyu_haraidasi2/index.php" target="_blank">購入払出管理</a></li>\
							\
						</ul>\
					</li>\
					<li>\
						<a href="' + prop.root + '/kanri_data/login.php" target="_blank">管理データ作成</a>\
						<ul>\
							<li><a href="' + prop.root + '/kanri_data/login.php" target="_blank">管理データ作成</a></li>\
							\
							<li><a href="' + prop.root + '/uriage_kanri/uriage_kanri_disp.php" target="_blank">管理表</a></li>\
							<li><a href="' + prop.root + '/uriage_kanri/uriage_jyusoku_disp.php" target="_blank">充足表</a></li>\
							<li><a href="' + prop.root + '/seisan_syukei/login.php" target="_blank">生産集計</a></li>\
							<li><a href="' + prop.root + '/gyosya_hattyu/login.php" target="_blank">業者別発注高</a></li>\
						</ul>\
					</li>\
					<li><a href="' + prop.root + '/setubi_yoyaku/login.php" target="_blank">設備予約</a></li>\
					<li><a href="' + prop.root + '/skill_inf/skill_inf_disp.php" target="_blank">スキル・教育</a></li>\
				</ul>\
			</div>\
		';
		
		$(prop.element).append(prop.template);
	};
	
	/**
	*	rebuildRememberMenu
	**/
	_buildRememberMenu = function() {
		obj = {};
		obj.title = document.title;
		obj.url = location.href;
		
		var json = localStorage.getItem('com_menu_button');
		var collection = JSON.parse(json);
		
		if (!$.isArray(collection)) {
			collection = [];
		}
		
		if (collection.length >= prop.remember_length) {
			collection.shift();
		}
		collection.push(obj);
		
		localStorage.setItem('com_menu_button', JSON.stringify(collection));
		
		var template = '';
		$.each(collection.reverse(), function(index, obj) {
			template += '<li><a href="' + obj.url + '" target="_blank" title="' + obj.url + '">' + obj.title + '</a></li>\r';
		});
		template += '';
		
		$(prop.element)
			.find('.menu_remember')
			.children('ul')
			.append(template);
	};

	/**
	*	rebuildRememberMenu
	**/
	_attach = function() {
		$(prop.element)
			.find('.menu_button')
			.click(function() {
				$(this).parent().next().toggle();
				return false;
			})
			.parent()
			.next()
			.hide()
			.menu()
		;
		
		$(document).click(function() {
			$(prop.element)
				.find('.menu_button')
				.parent()
				.next()
				.hide();
		});
	}
	
	/**
	*	construct
	*	@param string target element
	**/
	try {
		prop.element = target;
		_buildUrl();
		_buildMenu();
		_buildRememberMenu();
		_attach();
	} catch (e) {
		console.log(e.message);
	}
	
	/**
	*	public method
	**/
	return {
	};
};
