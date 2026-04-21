/* ---------------------------------------------------------------- */
/* 																	*/
/*							xmlObj.js								*/
/*							---------								*/
/*																	*/
/* ---------------------------------------------------------------- */

/* ---------------------------------------------------------------- */
/* xmlObj constructor												*/
/* ---------------------------------------------------------------- */
function xmlObj (async) 
{
	// data memebers

	// code for IE
	if (window.ActiveXObject)
	{
		this.obj = new ActiveXObject("Microsoft.XMLDOM");
  	}
	this.async = async; 							  	// a boolean telling whether the process is async or not

	// methods
	this.init 		 		= xmlObj_init;					  	// initializing the xml object
	this.sendRequest 		= xmlObj_sendRequest;				// sending a request to the server
	this.sendAsyncRequest 	= xmlObj_sendAsyncRequest;				// sending a request to the server

	this.getXMLRequestObject= xmlObj_getXMLRequestObject;

	this.isEmpty	 		= xmlObj_isEmpty;					// checking if we got any xml response

	this.isSuccess 	 		= xmlObj_isSuccess;			  	// checking if we got information back 
	this.isError	 		= xmlObj_isError;				  	// checking if we got an error back
	this.isInfo	 	 		= xmlObj_isInfo;				  	// checking if we got an info back
	this.isRequire	 		= xmlObj_isRequire;			  	// checking if we got a require command back
	this.isReLogin	 		= xmlObj_isReLogin;				// checking if we got session expire back

	this.reLogin	 		= xmlObj_reLogin;			
	this.getErrorMsg 		= xmlObj_getMessage;				// returning message text
	this.commandNode 		= xmlObj_commandNode; 				// getting the actual command node
	this.resultCode  		= xmlObj_resultCode;				// parsing the result code

	this.getNode     		= xmlObj_getNode;
	this.getValue    		= xmlObj_getValue;
	this.setValue    		= xmlObj_setValue;

	this.loadTableData		= xmlObj_loadTableData;

	this.countNodes	 		= xmlObj_countNodes;
}

/* ---------------------------------------------------------------- */
/* xmlObj_init														*/
/*																	*/
/*		Initializing an xml object with the appropriate xml.		*/
/* ---------------------------------------------------------------- */
function xmlObj_init(xmlData) 
{
	var xmlPrefix="<?xml version='1.0' encoding='ISO-8859-8' ?>";
	xmlPrefix="";

	// code for IE
	if (window.ActiveXObject)
  	{
	  	this.obj.loadXML(xmlData);
  	}
	// code for Mozilla, Firefox, Opera, etc.
	else
  	{
// 		var parser=new DOMParser();
//		this.obj=parser.parseFromString(xmlData,"text/xml");
//		alert (this.obj.getElementsByTagName("essayId")[0].firstChild.nodeValue);
		this.obj = xmlData;
  	}
}

/* ---------------------------------------------------------------- */
/* xmlObj_getXMLRequestObject										*/
/* ---------------------------------------------------------------- */
function xmlObj_getXMLRequestObject () 
{
	/* if native support, create & return object */
	if (window.XMLHttpRequest != null) 
	{
		return new XMLHttpRequest (); // for Mozilla/Opera/Safari/Konqueror
	} 
	else 
	{
		/* Build MS XMLHTTP version list - newest first */
		var MSXML_XMLHTTP_PROGIDS = new Array  ('MSXML2.XMLHTTP.4.0',
												'MSXML2.XMLHTTP.3.0',
												'MSXML2.XMLHTTP',
												'Microsoft.XMLHTTP');

		/* Look for supported IE version */
		for (i = 0; MSXML_XMLHTTP_PROGIDS.length > i; i++) 
		{
			try 
			{
				return new ActiveXObject (MSXML_XMLHTTP_PROGIDS[i]);
			}
			catch (e) {}
		}
	}
}


/* ---------------------------------------------------------------- */
/* xmlObj_sendRequest												*/
/*																	*/
/*		params : url - the url to send the request to				*/
/*				 requestObj - an XML object to be sent if needed	*/
/*																	*/
/* ---------------------------------------------------------------- */
function xmlObj_sendRequest(url, requestObj) 
{
	try 
	{
//		var httpObj = new ActiveXObject("Microsoft.XMLHTTP");
		var httpObj = this.getXMLRequestObject ();

		httpObj.open("POST", url, this.async);
		// The previous mime type used was "application/x-www-form-urlencoded", which is 
		// used to pass FORM DATA in HTTP. The correct content type for passing pure XML
		// data is "text/xml"
		httpObj.setRequestHeader("Content-type","text/xml");
		//httpObj.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		httpObj.send(requestObj.xml);

		// Note that "200" means "good" and everything else generally means "bad"...
		// TODO: Change to standard dealing with HTTP statuses (i.e. create constants
		// for the statuses in use)

		//alert (requestObj.xml);
		//alert (httpObj.responseText.substr(0,800));
		//alert (httpObj.responseText);

		if (httpObj.status == 200) 
		{
			this.obj.loadXML(httpObj.responseText);

			if (this.obj.parseError.errorCode != 0)
			{
				alert ("Xml Parse error in line " + this.obj.parseError.line + " : " + this.obj.parseError.reason +
					   "\n >> " + this.obj.parseError.srcText);
			}
		}
		else {
			this.init (
				" <message> " +
					" <responseType>Error</responseType>" +
					" <message>" + httpObj.status + "</message>" +
				" </message> "
			);
		}
	}
	catch (error) 
	{
		this.init (	" <local> " +
						"<response> " +
							"<responseType>Error</responseType> " +
							"<result>3000</result> " +
							"<message>Communication problem (" + error.description + ")</message> " +
						"</response> " +
					" </local> ");
	}
//alert (httpObj.status);
//	this.obj = httpObj.responseXML;
}

var asyncHttpObj;
var asyncHttpObjs = new Array();
var currHttp = -1;

/* ---------------------------------------------------------------- */
/* xmlObj_sendAsyncRequest											*/
/* ---------------------------------------------------------------- */
function xmlObj_sendAsyncRequest(url, requestObj, functionName, args) 
{
	try 
	{
		if (currHttp == 20)
			currHttp = 0;
		else
			currHttp++;

		var myCurrHttp = currHttp;

		if (asyncHttpObjs[currHttp] == undefined)
			asyncHttpObjs[currHttp] = xmlObj_getXMLRequestObject();

		var currAsyncHttpObj = asyncHttpObjs[currHttp];

		currAsyncHttpObj.open("POST", url, true);
		currAsyncHttpObj.setRequestHeader("Content-Type","text/xml");

		currAsyncHttpObj.onreadystatechange= function() 
		{
		  	if (currAsyncHttpObj.readyState==4) 
		  	{
				asyncHttpObj = asyncHttpObjs[currHttp];

				if (args == undefined)
					window.setTimeout (functionName + "(" + myCurrHttp + ")",0);	
				else
					window.setTimeout (functionName + "(args," + myCurrHttp + ")",0);
			}
		}

		if (window.ActiveXObject)
			currAsyncHttpObj.send(requestObj.xml);
		else
			currAsyncHttpObj.send(requestObj);
	}
	catch (error) 
	{
		this.init (	" <local> " +
						"<response> " +
							"<responseType>Error</responseType> " +
							"<result>3000</result> " +
							"<message>Communication problem (" + error.description + ")</message> " +
						"</response> " +
					" </local> ");
	}
//alert (httpObj.status);
//	this.obj = httpObj.responseXML;
}
/* ---------------------------------------------------------------- */
/* xmlObj_isEmpty													*/
/* ---------------------------------------------------------------- */
function xmlObj_isEmpty ()
{
	if (this.obj == null || this.obj.xml == "") 
	{
		this.init(	" <local> " +
						" <response> "+
							" <responseType>Error</responseType> " +
							" <result>3000</result> " +
							" <message>Error 3000</message> " +
						" </response> "+
					" </local> " );
		return true;
	}
	return false;
}

/* ---------------------------------------------------------------- */
/* xmlObj_isSuccess													*/
/* ---------------------------------------------------------------- */
function xmlObj_isSuccess (command)
{
	var isSuccess;

	if (this.isEmpty ())
		isSuccess = false;
	else
	{
		var responseType = this.getValue ("responseType");

		if (responseType == "Success")
		{
			var requestCommand = this.getValue ("command");
			
			isSuccess = (requestCommand == command);
		}
		else
			isSuccess = false;
	}
	return isSuccess;
}

/* ---------------------------------------------------------------- */
/* xmlObj_isError													*/
/* ---------------------------------------------------------------- */
function xmlObj_isError ()
{
	var isError;

	if (this.isEmpty ())
		isError = false;
	else
	{
		var responseType = this.getValue ("responseType");

		isError = (responseType == "Error");
	}
	return isError;
}

/* ---------------------------------------------------------------- */
/* xmlObj_isInfo													*/
/* ---------------------------------------------------------------- */
function xmlObj_isInfo ()
{
	var isInfo;

	if (this.isEmpty ())
		isInfo = false;
	else
	{
		var responseType = this.getValue ("responseType");

		isInfo = (responseType == "Info");
	}
	return isInfo;
}

/* ---------------------------------------------------------------- */
/* xmlObj_isRequire													*/
/* ---------------------------------------------------------------- */
function xmlObj_isRequire ()
{
	var isRequire;

	if (this.isEmpty ())
		isRequire = false;
	else
	{
		var responseType = this.getValue ("responseType");

		isRequire = (responseType == "Require");
	}
	return isRequire;
}

/* ---------------------------------------------------------------- */
/* xmlObj_isReLogin													*/
/* ---------------------------------------------------------------- */
function xmlObj_isReLogin ()
{
	var isReLogin;

	if (this.isEmpty ())
		isReLogin = false;
	else
	{
		var responseType = this.getValue ("responseType");

		isReLogin = (responseType == "SessionExpired");
	}
	return isReLogin;
}

/* ---------------------------------------------------------------- */
/* reLogin															*/
/* ---------------------------------------------------------------- */
function xmlObj_reLogin ()
{
	if (this.isReLogin ())
	{
		this.init(	" <local> " +
						" <response> "+
							" <responseType>Error</responseType> " +
							" <result>4000</result> " +
							" <message>Session Expired</message> " +
						" </response> "+
					" </local> " );
		return false;
	}

    return false;
}

/* ---------------------------------------------------------------- */
/* xmlObj_getMessage												*/
/* ---------------------------------------------------------------- */
function xmlObj_getMessage() 
{
	var msgText = this.getValue ("message");

	if (msgText == "")
		msgText = "Internal Error";

	if (msgText.indexOf ("500") != -1) 
		return "No Server response";

	return msgText;
	
}

/* ---------------------------------------------------------------- */
/* xmlObj_commandNode												*/
/* ---------------------------------------------------------------- */
function xmlObj_commandNode() 
{
	var commandName = this.getValue("command");
	
	if (commandName == "") return;
	
	var commandNode = this.obj.getElementsByTagName(commandName).item(0);

	return commandNode;
}

/* ---------------------------------------------------------------- */
/* xmlObj_resultCode												*/
/* ---------------------------------------------------------------- */
function xmlObj_resultCode() 
{
	return this.getValue("result");
}

/* ---------------------------------------------------------------- */
/* xmlObj_getValue													*/
/* ---------------------------------------------------------------- */
function xmlObj_getValue (tagName)
{
	var theNode = this.getNode (tagName);

	if (navigator.appName == "Netscape" || navigator.appName == "Opera")
	{
		if (theNode == null)
			return "";
		else if (theNode[0].firstChild == null)
			return "";
		else
			return theNode[0].firstChild.nodeValue;
	}
	else
	{
		if (theNode == null) 
			return "";
		else
			return theNode.text;
	}
}

/* ---------------------------------------------------------------- */
/* xmlObj_getNode													*/
/* ---------------------------------------------------------------- */
function xmlObj_getNode (tagName)
{
	if (navigator.appName == "Netscape" || navigator.appName == "Opera")
	{
		var xmlObject = (new DOMParser()).parseFromString(this.obj, "text/xml");
	
		return xmlObject.getElementsByTagName(tagName);
	}
	else
	{
		return this.obj.getElementsByTagName(tagName).item(0);
	}
}

/* ---------------------------------------------------------------- */
/* xmlObj_setValue													*/
/* ---------------------------------------------------------------- */
function xmlObj_setValue (tagName,value)
{
	var theNode = this.obj.getElementsByTagName(tagName).item(0);
    if (theNode != null) 
		theNode.text = value;
}

/* ---------------------------------------------------------------- */
/* xmlObj_countNodes												*/
/* ---------------------------------------------------------------- */
function xmlObj_countNodes () 
{
    if (this.obj != null && this.obj.xml != "") 
		return this.obj.documentElement.childNodes.length;
	else
		return 0;
}
																		
/* ---------------------------------------------------------------- */
/* xmlObj_loadTableData												*/
/* ---------------------------------------------------------------- */
function xmlObj_loadTableData (xmlName, tagName)
{
	if (navigator.appName == "Netscape" || navigator.appName == "Opera")
	{
		var xmlData = this.obj;

	  	if (!document.all) 
		{
    		var i;
	    	var xmlNodes = document.getElementsByTagName("xml");
	    	for (i=0; i< xmlNodes.length; i++) 
			{
				var xml = xmlNodes[i];
				var id = xml.getAttribute("id");

				if (id == xmlName)
				{
					var table  = findTable (id);
		    	  	var fields = getDataFieldNames (table);
      				var srcs   = getSrcFieldValues (xmlData, fields, tagName);

	      			fillTable(table, srcs);
	
					break;
				}
	    	}
  		}
	}
	else
	{
		this.init (asyncHttpObj.responseText);

		var itemsNodes = this.getNode(tagName);
	
		document.getElementById(xmlName).loadXML (itemsNodes.xml);
	}
}

/* ---------------------------------------------------------------- */
/* findTable														*/
/* ---------------------------------------------------------------- */
function findTable (id)
{
	id = "#" + id;
  	var tables = document.getElementsByTagName("table");
  	var i;
  	for (i=0; i < tables.length; i++) 
	{
   		var table = tables[i];
    
		if (table.getAttribute("datasrc") == id)
	      return table;
  	}
  	return null;
}

/* ---------------------------------------------------------------- */
/* getDataFieldNames												*/
/* ---------------------------------------------------------------- */
function getDataFieldNames (table)
{
  	var array = new Array();
  	var divs = table.getElementsByTagName("div");
  	var i;
  
	for (i=0; i < divs.length; i++) 
	{
	    var div = divs[i];
    	var datafld = div.getAttribute("datafld");

    	if (datafld == null)
		{
			datafld = "";
		}
      	array[i] = datafld;
  	}

  	if (array.length < 1) 
	{
    	return null;
  	}

  	return array;
}

/* ---------------------------------------------------------------- */
/* getSrcFieldValues												*/
/* ---------------------------------------------------------------- */
function getSrcFieldValues(xmlData, fieldNames, tagName)
{
	var xmlObject = (new DOMParser()).parseFromString(xmlData, "text/xml");

	elements = xmlObject.getElementsByTagName(tagName);

  	var srcs = new Array();
  	var i;
  
	for (i=0; i < fieldNames.length; i++) 
	{
    	var field = fieldNames[i];
    	srcs[field] = elements[0].getElementsByTagName(field);
  	}

  	return srcs;
}

/* ---------------------------------------------------------------- */
/* fillTable														*/
/* ---------------------------------------------------------------- */
function fillTable(table, srcs)
{
	var trtemplate = table.rows[0];

	trtemplate.style.display = "";

  	var tdtemplate = trtemplate.cells[0];

	var cols = 0;
	for (field in srcs) 
	{
    	srcs[cols++] = field;
	}  

	while (table.childNodes.length > 2)
	{
		table.removeChild(table.childNodes[2]);
	}

  	var row;
  	for (row = 0; row < srcs[srcs[0]].length; row++) 
	{
		var tr = trtemplate.cloneNode(false);
    	var col;
    	for (col=0; col < cols; col++ ) 
		{
			tdtemplate = trtemplate.cells[col];
      		var td = tdtemplate.cloneNode(true);
      		var div = td.getElementsByTagName("div");     
			if (srcs[col] != "")
			{
	      		div[0].setAttribute("datafld", srcs[col]);
	      		var textNode = document.createTextNode(srcs[srcs[col]][row].firstChild.nodeValue);
	      		div[0].appendChild(textNode);
			}
      		tr.appendChild(td);
    	}
		table.appendChild(tr);
  	}
	trtemplate.style.display = "none";
}

