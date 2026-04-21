var xmlRequest = new xmlObj(false);

/* ----------------------------------------------------------------------------------------------------------------------------	*/
/* verification_check																											*/
/* ----------------------------------------------------------------------------------------------------------------------------	*/
function verification_check (formName, lang)
{
	// verification
	var oVerification	= document.getElementById(formName + "_verification");
	if (oVerification.value == "")
	{
		alert(tailJS["typeTheCode"]);
		oVerification.focus();
		return false;
	}

	if (lang == undefined)
		lang = "HEB";

	var oVerificationImg	= document.getElementById(formName + "_verificationImgId");
	var oParms				= document.getElementById(formName + "_verificationParms");

	xml  = 	"<request>" +
				"<command>verification.checkVerification</command>" 			+
				"<verification>" 		+ oVerification.value 			+ "</verification>"			+
				"<verificationImgId>" 	+ oVerificationImg.value 		+ "</verificationImgId>"  	+
				"<formName>"			+ formName						+ "</formName>" 			+
				"<lang>"				+ lang							+ "</lang>"					+
				"<parms>"				+ oParms.value					+ "</parms>"				+
			"</request>";
			
	xmlRequest.init (xml);
	xmlRequest.sendAsyncRequest ("server.php", xmlRequest.obj, "verification_check_response");
}

/* ----------------------------------------------------------------------------------------------------------------------------	*/
/* verification_check_response																									*/
/* ----------------------------------------------------------------------------------------------------------------------------	*/
function verification_check_response (i)
{
	try {
		xmlRequest.init(commonDecode(asyncHttpObjs[i].responseText));
	} catch(e) {
		xmlRequest.init(commonDecode(asyncHttpObj.responseText));
	}

	try
	{
		var success  = xmlRequest.getValue("success");
		var formName = xmlRequest.getValue("formName");
		var lang 	 = xmlRequest.getValue("lang");
	}
	catch (e)
	{
		alert ("AJAX Error");
		return false;
	}
	
	if (success != "0")
	{
		newImgId = xmlRequest.getValue("newImgId");
		newImg   = xmlRequest.getValue("newImg");

		if (newImg != "")
		{
			document.getElementById(formName + "_verificationImgId").value  	= newImgId;
			document.getElementById(formName + "_verificationImg").src  		= newImg;
			document.getElementById(formName + "_verification").value   		= "";
		}

		alert (tailJS["typeTheCodeError"]);
		document.getElementById(formName + "_verification").focus();
	}
	else
	{
		document.getElementById(formName).submit();
	}

	return false;
}

var desoundEmbed = null;
function sayCaptchaLetters(verId)
{
		var d = new Date();
		if (!desoundEmbed) {
			desoundEmbed = document.createElement("embed");
			desoundEmbed.setAttribute("src", "sayCaptcha.php?verId=" + verId + "&d=" + d.getTime());
			desoundEmbed.setAttribute("hidden", true);
			desoundEmbed.setAttribute("autostart", true);
			desoundEmbed.setAttribute("type", "audio/x-mpeg");
		} else {
			document.body.removeChild(desoundEmbed);
			desoundEmbed.removed = true;
			desoundEmbed = null;
			desoundEmbed = document.createElement("embed");
			desoundEmbed.setAttribute("src", "sayCaptcha.php?verId=" + verId + "&d=" + d.getTime());
			desoundEmbed.setAttribute("hidden", true);
			desoundEmbed.setAttribute("autostart", true);
			desoundEmbed.setAttribute("type", "audio/x-mpeg");
        }
		desoundEmbed.removed = false;
		document.body.appendChild(desoundEmbed);
}
