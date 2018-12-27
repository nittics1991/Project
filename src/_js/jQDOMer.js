/**
*	jQDOMer
*   
*	@example
*		schema = [
*			{
*				elem:'input',
*				attr:{
*					'type':'textarea',
*					'id':'div1-1',
*					'name':'div1',
*					val:'追加要素'
*				},
*				ope:[
*						{'$':'#container'},		//'$' method is JQuery
*						{'children':'#sub'},
*						{'append':'$'}			//'$' argument is build DOM
*				]
*			},...
*		];
**/
var jQDOMer = (function () {
	function jQDOMer() {
	}
   
	/**
	*	DOM操作
	*
	*	@param array 操作指令
	**/
	jQDOMer.build = function (schema) {
		$.map(schema, function(obj, key) {
			var keys = function(obj) {
				return $.map(obj, function(var1, key1) {
					return key1;
				});
			};
			  
			if ($.isArray(obj.attr)) {
				target = obj.attr;
			} else {
				target = [obj.attr];
			}
		   
			$.map(target, function(attr, key3) {
				var element = $('<' + obj.elem + '>', attr);
			  
				   var operation;
				$.map(obj.ope, function(obj2, key2) {
					var method = keys(obj2)[0];
					var arg = obj2[method];
				  
					if (method == '$') {
						operation = $(arg);
					} else if (arg == null) {
						operation = $(operation)[method]();
					} else if (arg == '$') {
						operation = $(operation)[method](element);
					} else {
						operation = $(operation)[method](arg);
					}
					return operation;
				});
		   
			});
		});
	};
   
	/**
	*	DOM解析
	*
	*	@param string jQueryセレクタ
	*	@return array スキーマ
	**/
	jQDOMer.parse = function (dom, target) {
		var result = [];
		var schema = {};
		
		schema.elem = $(dom)[0].nodeName;
		
		var attrs = $(dom)[0].attributes;
		var attr = {};
		
		for (var i=0; i<attrs.length; i++) {
			var key = attrs[i].name;
			attr[key] = attrs[i].value;
		}
		schema.attr = attr;
		
		if (target == null) {
			schema.ope = [{'$':dom.selector}];
		} else {
			schema.ope = [{'$':target}];
		}
		
		if (attr.id != null) {
			target = '#' + attr.id;
		}
		
		var child = dom.children();
		if(child.length > 0){
			$.map(child, function(obj, key) {
				result = result.concat(jQDOMer.parse($(obj), target));
			});
		}
		
		if (target != null) {
			result.push(schema);
		}
		return result;
	};
	
	return jQDOMer;
})();
