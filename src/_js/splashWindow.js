/**
*
*	@param string message
*	@param int window width
*	@param int window height
*	@param string window color
**/
function splashView()
{
	message	= (arguments[0] == null)?	'実行中':arguments[0];
	cw		= (arguments[1] == null)?	200:arguments[1];
	ch		= (arguments[2] == null)?	50:arguments[2];
	col		= (arguments[3] == null)?	'#ffff99':arguments[3];
	
	backTarget = document.getElementById("splash-back-window");
	backTarget.style.visibility = "visible";
	
	dialogTarget = document.getElementById("splash-dialog-window");
	
	ww = (window.innerWidth == null)?	document.body.clientWidth:window.innerWidth;
	wh = (window.innerHeight == null)?	document.body.clientHeight:window.innerHeight;
	
	cx = Math.floor((ww - cw) / 2);
	cy = Math.floor((wh - ch) / 2);
	
	dialogTarget.style.left = cx + "px";
	dialogTarget.style.top = cy + "px";
	dialogTarget.style.width = cw + "px";
	dialogTarget.style.height = ch + "px";
	dialogTarget.style.background = col;
	
	messageTarget = document.getElementById("splash-dialog-message");
	messageTarget.innerHTML = message;
	
	dialogTarget.style.visibility = "visible";
}
