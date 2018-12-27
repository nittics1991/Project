/**
*   リアルタイムvalidate
*
*   @caution 入力値変化で判定。ロード時に不正文字があっても判定できない
*   @example Concerto.FOrmValidator.attatch('input[name="user"]', 'ユーザ名は4-6文字です');
*       <input name="user" minlength="4" maxlength="6">
**/
var Concerto = Concerto || {};

Concerto.FormValidator = (function () {
	function FormValidator() {
	}
    
    /**
    *   対象属性
    *
    **/
    FormValidator._attributs = ['required', 'min', 'max', 'step', 'minlength', 'maxlength', 'pattern'];
    
    /**
    *   メッセージ初期値
    *
    **/
    FormValidator._message = '入力値が不正です';
    
	/**
	*   attatch
    *
    *   @param string|HTMLElement|NodeList
    *   @param string
	**/
	FormValidator.attatch = function (target, message) {
        var elms = FormValidator._isNodeList(target)?
            [].slice.call(target):
            FormValidator._isHtmlElement(target)?
            [target]:
            [].slice.call(document.querySelectorAll(target));
        
        //要素にイベント追加
        elms.forEach(function(elm, index, array) {
            //定義した属性があるか
            var hasAttributes = FormValidator._attributs.filter(function(attr) {
                return elm.hasAttribute(attr);
            });
            
            if (hasAttributes.length==0) return;
            
            var callback = function(event) {
                var value = event.target.value;
                
                var isValid = hasAttributes.reduce(function(previous, attr) {
                        var func = '_' + attr;
                        var condition = event.target.getAttribute(attr);
                        return previous && FormValidator[func](value, condition);
                }, true);
                
                if (isValid) {
                    elm.setCustomValidity('');
                } else {
                    elm.setCustomValidity(message? message:FormValidator._message);
                }
                return isValid;
            };
            elm.addEventListener('input', callback);
        });
	};
	
    /**
    *   _isHtmlElement
    **/
    FormValidator._isHtmlElement = function(node) {
        return !!(node && (node.nodeName || (node.prop && node.attr && node.find)));
    };
    
    /**
    *   _isNodeList
    **/
    FormValidator._isNodeList = function(node) {
        return node instanceof NodeList;
    };
    
    /**
    *   検査処理定義
    **/
    FormValidator._required = function(value, condition) {
        return value != '';
    };
    
    FormValidator._min = function(value, condition) {
        return +value >= condition;
    };
    
    FormValidator._max = function(value, condition) {
        return +value <= condition;
    };
    
    FormValidator._step = function(value, condition) {
        var splited = String(value).split('.');
        
        if (splited[1] == undefined) {
            return  condition == 0;
        }
        return splited[1].length <= condition;
    };
    
    FormValidator._minlength = function(value, condition) {
        return (value + '').length >= condition;
    };
    
    FormValidator._maxlength = function(value, condition) {
        return (value + '').length <= condition;
    };
        
    FormValidator._pattern = function(value, condition) {
        return (new RegExp(condition)).test(value);
    };
    
	return FormValidator;
})();
