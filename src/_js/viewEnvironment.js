function setEnv()
{
	if (confirm('設定を保存しますか')) {
		document.form1.act.value = 'setEnv';
		document.form1.submit();
	}
}

function resetEnv()
{
	if (confirm('設定を削除しますか')) {
		document.form1.act.value = 'resetEnv';
		document.form1.submit();
	}
}
