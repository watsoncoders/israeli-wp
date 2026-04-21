
function searchSite_submitForm (lang, emptyStr)
{
	if (lang 	 == undefined) lang 	= "HEB";
	if (emptyStr == undefined) emptyStr = "";

	var oSearchForm = document.getElementById("searchForm");

	if (oSearchForm.queryText.value == "" || oSearchForm.queryText.value == emptyStr)
	{
		alert (tailJS["enterQueryString"]);
		return false;
	}
	return true;
}

function searchSite_onFocus (oField, emptyValue)
{
	if (emptyValue == undefined)
	{
		oEmpty = document.getElementById(oField.id + "_empty");
		if (oEmpty != undefined)
			emptyValue = oEmpty.innerHTML;
	}

	if (oField.value == emptyValue)
	{
			oField.value = "";
	}
}

function searchSite_onBlur (oField, emptyValue)
{
	if (emptyValue == undefined)
	{
		oEmpty = document.getElementById(oField.id + "_empty");
		if (oEmpty != undefined)
			emptyValue = oEmpty.innerHTML;
	}

	if (oField.value == "")
	{
		oField.value = emptyValue;
	}
}

