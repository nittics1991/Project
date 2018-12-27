/**
*	HTML INPUT & TEXTAREA 共通一括Valid
*
*	@param callable validate function return bool
*	@return bool
**/

function validFormCom(callback, exclude)
{
	var valid = function(target, exclude) {
		if (target != null) {
			for (var i=0; i<target.length; i++) {
				if ((exclude == null) || (exclude.indexOf(target[i].name) < 0)) {
					if (target[i].value.length == 0) {
						//OK
					} else if (target[i].value.search(/([｡-ﾟ]|[\x00-\x09\x0b-\x0c\x0e-\x1f\x7f]|[\'\"<>%])/) >= 0) {
						return false;
					}
				}
				
				if (typeof(callback) == 'function') {
					if (!callback(target[i].value)) {
						return false;
					}
				}
			}
		}
		return true;
	};

	var input = document.getElementsByTagName("input") || [];
	var textarea = document.getElementsByTagName("textarea") || [];
	
	return (valid(input, exclude) & valid(textarea, exclude))?	true:false;
}
