/**
*	Stylesheet Class
*
*
*
**/
var Concerto = Concerto || {};

Concerto.Stylesheet = function() {
	//private property
	var prop  = {
		styleSheets:document.styleSheets,
		styleSheet:null,
		styleRule:null
	};
	
	/**
	*	getter property
	*	@return mixed
	**/
	getProp = function() {
		return {
			styleSheet:prop.styleSheet,
			styleRule:prop.styleRule
		};
	};
	
	/**
	*	StyleSheet
	*	@param string cssFileName
	*	@return object this
	**/
	getStyleSheet = function(cssFileName) {
		if (prop.styleSheets != null) {
			$.each(prop.styleSheets, function(index, styleSheet) {
				if ((cssFileName == null) && (styleSheet.href == null)) {
					prop.styleSheet = styleSheet;
					return false;
				} else if ((cssFileName != null) && (styleSheet.href.indexOf(cssFileName) > -1)) {
					prop.styleSheet = styleSheet;
					return false;
				}
			});
		}
		return this;
	};
	
	/**
	*	CSS rule
	*	@param string selectorName
	*	@return object this
	**/
	getRule = function(selectorName) {
		prop.styleRule = [];
		selectorName = selectorName.replace(/\s{2,}/, ' ');
				
		if (prop.styleSheet.cssRules != null) {
			$.each(prop.styleSheet.cssRules, function(index, cssStyleRule) {
				if (cssStyleRule.selectorText.indexOf(selectorName) > -1) {
					prop.styleRule.push(cssStyleRule);
					//return false;
				}
			});	
		}
		return this;
	};
	
	/**
	*	shaping style name 
	*	@param string style
	*	@return string 
	**/
	_shapeName = function(styleName) {
		if (styleName.search(/-/) != -1) {
			var split = styleName.split('-');
			var s = split[0];
			for (var i=1; i<split.length; i++) {
				s = s + split[i].substr(0, 1).toUpperCase() + split[i].substr(1);
			}
		} else {
			var s = styleName;
		}
		return s;
	}
	
	/**
	*	style
	*	@param string style
	*	@return string 
	**/
	getStyle = function(styleName) {
		var result;
		if ((styleName != null) && (styleName.search(/[<>]/) != -1)) {
		} else if (prop.styleRule != null) {
			if (styleName == null) {
				result = prop.styleRule[0].style;
			} else {
				var name = _shapeName(styleName);
				var style = eval('prop.styleRule[0].style.' + name);
				if (style != null) {
					result = style;
				}
			}
		}
		return result;
	};
	
	/**
	*	set style
	*	@param string style
	*	@param string value
	*	@return object this
	*	@throws string
	**/
	setStyle = function(styleName, val) {
		if (prop.styleRule != null) {
			var name = _shapeName(styleName);
			var style = eval('prop.styleRule[0].style.' + name);
			if (style != null) {
				try {
					eval('prop.styleRule[0].style.' + name + '= val');
				} catch(e) {
					throw 'setStyle error:' + val;
				}
			}
		}
		return this;
	};
	
	/**
	*	insert rule
	*	@param string rule
	*	@return object this
	*	@throws string
	**/
	insertRule = function(rule, index) {
		index = (index == null)?	prop.styleSheet.cssRules.length:index;
		if (prop.styleSheet != null) {
			try {
				prop.styleSheet.insertRule(rule, index);
			} catch(e) {
				throw 'insertRule error:' + rule;
			}
		}
		return this;
	};
	
	//public method
	return {
		getProp:getProp,
		getStyleSheet:getStyleSheet,
		getRule:getRule,
		getStyle:getStyle,
		setStyle:setStyle,
		insertRule:insertRule
		
	};
};
