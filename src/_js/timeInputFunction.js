function time_input(elm)
{
	var inTime = timeInputHelper(elm.value);
	
	if (inTime != '') {
		elm.value =
			(('00' + (inTime.getHours())).slice(-2)) 
			+ (('00' + inTime.getMinutes()).slice(-2));
	} else {
		elm.value = '';
	}
	return;
}
