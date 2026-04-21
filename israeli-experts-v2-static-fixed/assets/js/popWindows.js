/* ---------------------------------------------------------------- */
/* popWindows_chooseLang											*/
/* ---------------------------------------------------------------- */
function popWindows_chooseLang (lang)
{
	var href = window.parent.location.href;

	if (href.indexOf("id=") == -1)
	{
		href += "?id=1";
	}
	
	href += "&lang=" + lang;

	window.parent.location.href = href;
}
