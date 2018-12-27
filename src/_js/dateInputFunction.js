function date_input(elm)
{
	var inDate = dateInputHelper(elm.value);
	
	if (inDate != '') {
		elm.value = inDate.getFullYear()  
			+ (('00' + (inDate.getMonth() + 1)).slice(-2)) 
			+ (('00' + inDate.getDate()).slice(-2));
	} else {
		elm.value = '';
	}
	return;
}
