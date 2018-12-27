function numberInputHelper(input)
{
	var val = input.replace(/^\s+|\s+$/g, "");
	val= val.replace(/,/g, "");
	var pre = val.slice(-1);
	var data = val.substr(0, val.length -1);
	var target = 0;
	
	switch (pre) {
	case 'G':
		target = data * 1000 * 1000 * 1000;
		break;
	case 'M':
		target = data * 1000 * 1000;
		break;
	case 'k':
		target = data * 1000;
		break;
	case 'h':
		target = data * 100;
		break;
	case 'd':
		target = data * 0.1;
		break;
	case 'c':
		target = data * 0.01;
		break;
	case 'm':
		target = data * 0.001;
		break;
	case '':
		target = 0;
		break;
	default:
		target = val;
	}
	
	if (!isFinite(target)) {
		return null;
	} else {
		return target;
	}
}
