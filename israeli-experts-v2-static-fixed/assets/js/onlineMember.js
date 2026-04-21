
var xmlMemberRequest = new xmlObj(false);

/* ------------------------------------------------------------------------------------------------------------ */
/* reportOnline																									*/
/* ------------------------------------------------------------------------------------------------------------ */
function reportOnline (withSessions, code)
{
	var xml = "<data>" +
					"<command>online.reportOnline</command>"   	+
					"<withSessions>" + withSessions 			+ "</withSessions>" +
					"<code>"	 	 + code 		 			+ "</code>" 		+
			  "</data>";

	xmlMemberRequest.init (xml);
	xmlMemberRequest.sendAsyncRequest ("server.php", xmlMemberRequest.obj, "reportOnline_response");
}

function reportOnline_response ()
{
}
