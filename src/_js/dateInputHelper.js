
function dateInputHelper(date_str)
{
	//空欄ならばそのままreturn;
	if (date_str == '')	return '';
	
	//yyyy/mm/ddはそのままreturn
	if (date_str.match(/^\d{4}\/\d{2}\/\d{2}$/)) {
		return new Date(date_str.substr(0, 4), (date_str.substr(5, 2) - 1), date_str.substr(8, 2));
	}
	
	//半角数値以外で分割
	reg = /[\u0000-\u002F\u003A-\uFFFF]/;
	ar = date_str.split(reg);
	
	//分割数が年月日にならなければ終わり
	len = ar.length;
	if ((len > 3) || (len == 0))	return '';
	
	today = new Date();
	yyyy = today.getFullYear();
	mm = today.getMonth();
	dd = today.getDate();
	
	//年月日入力
	if (len == 3) {
		y = ar[0] * 1;
		m = ar[1] * 1;
		d = ar[2] * 1;
 		
		if ((y > 2000) && (y < 2100))	yyyy = y;
		else if ((y > 0) && (y < 100))	yyyy = 2000 + y;
		
		if ((m >= 1) && (m <= 12))	mm = --m;
		if ((d >= 1) && (d <= 31))	dd = d;
	//月日入力
	} else if (len == 2) {
		m = ar[0] * 1;
		d = ar[1] * 1;
		
		if ((m >= 1) && (m <= 12))	mm = --m;
		if ((d >= 1) && (d <= 31))	dd = d;
	//年月日入力
	} else {
		switch (ar[0].length) {
		case 8:
			y = (ar[0].substr(0,4)) * 1;
			m = (ar[0].substr(4,2)) * 1;
			d = (ar[0].substr(6,2)) * 1;
		
			if ((y > 2000) && (y < 2100))	yyyy = y;
			if ((m >= 1) && (m <= 12))		mm = --m;
			if ((d >= 1) && (d <= 31))		dd = d;
			break;
		case 7:
			y = (ar[0].substr(0,4)) * 1;
			if ((y > 2000) && (y < 2100))	yyyy = y;
			
			m1 = ar[0].substr(4,2) *1;
			d1 = ar[0].substr(6,1) *1;
			if ((m1 >= 10) && (m1 <= 12)) {
				mm = --m1;
				dd = d1;
			} else {
				m2 = ar[0].substr(4,1) *1;
				d2 = ar[0].substr(5,2) *1;
				if ((d2 >= 1) && (d2 <= 31)) {
					mm = --m2;
					dd = d2;
				}
			}
			break;	
		case 6:
			y = (ar[0].substr(0,2)) * 1;
			m = (ar[0].substr(2,2)) * 1;
			d = (ar[0].substr(4,2)) * 1;
			
			if ((y > 0) && (y < 100))		yyyy = 2000 + y;
			if ((m >= 1) && (m <= 12))	mm = --m;
			if ((d >= 1) && (d <= 31))	dd = d;
			break;	
		case 5:
			y = (ar[0].substr(0,2)) * 1;
			if ((y > 0) && (y < 100))		yyyy = 2000 + y;
			
			m1 = ar[0].substr(2,2) *1;
			d1 = ar[0].substr(4,1) *1;
			if ((m1 >= 10) && (m1 <= 12)) {
				mm = --m1;
				dd = d1;
			} else {
				m2 = ar[0].substr(2,1) *1;
				d2 = ar[0].substr(3,2) *1;
				if ((d2 >= 1) && (d2 <= 31)) {
					mm = --m2;
					dd = d2;
				}
			}
			break;	
		case 4:
			m = (ar[0].substr(0,2)) * 1;
			d = (ar[0].substr(2,2)) * 1;
			
			if ((m >= 1) && (m <= 12))	mm = --m;
			if ((d >= 1) && (d <= 31))	dd = d;
			break;	
		case 3:
			m1 = ar[0].substr(0,2) *1;
			d1 = ar[0].substr(2,1) *1;
			if ((m1 >= 10) && (m1 <= 12)) {
				mm = --m1;
				dd = d1;
			} else {
				m2 = ar[0].substr(0,1) *1;
				d2 = ar[0].substr(1,2) *1;
				if ((d2 >= 1) && (d2 <= 31)) {
					mm = --m2;
					dd = d2;
				}
			}
			break;	
		case 2:
		case 1:
			d = ar[0];
			if ((d >= 1) && (d <= 31))	dd = d;
			break;	
		}
	}
	
	return new Date(yyyy, mm, dd);
}
