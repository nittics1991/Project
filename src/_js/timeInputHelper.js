
function timeInputHelper(time_str)
{
	//空欄ならばそのままreturn;
	if (time_str == '')	return '';
	
	today = new Date();
	yyyy = today.getFullYear();
	mm = today.getMonth();
	dd = today.getDate();
	hh = today.getHours();
	ii = 0;
	ss = 0;
	
	//hh:ii:ssはそのままreturn
	if (time_str.match(/^\d{2}:\d{2}:\d{2}$/)) {
		return new Date(yyyy, mm, dd, time_str.substr(0, 2), time_str.substr(3, 2), time_str.substr(6, 2));
	}
	
	//半角数値以外で分割
	reg = /[\u0000-\u002F\u003A-\uFFFF]/;
	ar = time_str.split(reg);
	
	//分割数が時分秒にならなければ終わり
	len = ar.length;
	if ((len > 3) || (len == 0))	return '';
	
	//時分秒入力
	if (len == 3) {
		h = ar[0] * 1;
		i = ar[1] * 1;
		s = ar[2] * 1;
 		
		if ((h >= 0) && (h <= 23))	hh = h;
		if ((i >= 0) && (i <= 59))	ii = i;
		if ((s >= 0) && (s <= 59))	ss = s;
	//時分秒入力
	} else if (len == 2) {
		h = ar[0] * 1;
		i = ar[1] * 1;
		
		if ((h >= 0) && (h <= 23))	hh = h;
		if ((i >= 0) && (i <= 59))	ii = i;
	//時分秒入力
	} else {
		switch (ar[0].length) {
		case 6:
			h = (ar[0].substr(0,2)) * 1;
			i = (ar[0].substr(2,2)) * 1;
			s = (ar[0].substr(4,2)) * 1;
			
			if ((h >= 0) && (h <= 23))	hh = h;
			if ((i >= 0) && (i <= 59))	ii = i;
			if ((s >= 0) && (s <= 59))	ss = s;
			break;	
		case 5:
			h = ar[0].substr(0,1) *1;
			i = (ar[0].substr(1,2)) * 1;
			s = (ar[0].substr(3,2)) * 1;
			
			if ((h >= 0) && (h <= 23))	hh = h;
			if ((i >= 0) && (i <= 59))	ii = i;
			if ((s >= 0) && (s <= 59))	ss = s;
			break;	
		case 4:
			h = ar[0].substr(0,2) *1;
			i = (ar[0].substr(2,2)) * 1;
			
			if ((h >= 0) && (h <= 23))	hh = h;
			if ((i >= 0) && (i <= 59))	ii = i;
			break;	
		case 3:
			h = ar[0].substr(0,1) *1;
			i = (ar[0].substr(1,2)) * 1;
			
			if ((h >= 0) && (h <= 23))	hh = h;
			if ((i >= 0) && (i <= 59))	ii = i;
			break;	
		case 2:
		case 1:
			h = ar[0] *1;
			
			if ((h >= 0) && (h <= 23))	hh = h;
			break;	
		}
	}
	
	return new Date(yyyy, mm, dd, hh, ii, ss);
}
