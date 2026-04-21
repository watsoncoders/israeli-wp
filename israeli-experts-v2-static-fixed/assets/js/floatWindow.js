
/* ----------------------------------------------------------------------------------------------------------------------------	*/
/* floatWindow																													*/
/* ----------------------------------------------------------------------------------------------------------------------------	*/
floatWindow = function ()
{
	this.obj   			= null;
	this.field			= "";
	this.iframeName		= "";
	this.fadeArray		= "";
	this.withCloseEvent = true;
}

floatWindow.is_ie = ( /msie/i.test(navigator.userAgent) && !/opera/i.test(navigator.userAgent) );

/* ----------------------------------------------------------------------------------------------------------------------------	*/
/* createElement																												*/
/* ----------------------------------------------------------------------------------------------------------------------------	*/
floatWindow.createElement = function (type, parent)
{
	var el = null;
	if (document.createElementNS) 
	{
		// use the XHTML namespace; IE won't normally get here unless
		// _they_ "fix" the DOM2 implementation.
		el = document.createElementNS("http://www.w3.org/1999/xhtml", type);
	} 
	else 
	{
		el = document.createElement(type);
	}

	if (typeof parent != "undefined") 
	{
		parent.appendChild(el);
	}
	return el;
}

/* ----------------------------------------------------------------------------------------------------------------------------	*/
/* setWithClose																													*/
/* ----------------------------------------------------------------------------------------------------------------------------	*/
floatWindow.prototype.setWithClose = function(closeEvent)
{
	this.withCloseEvent = closeEvent;
}

/* ----------------------------------------------------------------------------------------------------------------------------	*/
/* stopEvent																													*/
/* ----------------------------------------------------------------------------------------------------------------------------	*/
floatWindow.stopEvent = function(e) 
{
	e || (e = window.event);

	if (floatWindow.is_ie) 
	{
		e.cancelBubble = true;
		e.returnValue = false;
	} 
	else 
	{
		e.preventDefault();
		e.stopPropagation();
	}
	return false;
}

/* ----------------------------------------------------------------------------------------------------------------------------	*/
/* addEvent																														*/ 
/* ----------------------------------------------------------------------------------------------------------------------------	*/
floatWindow.addEvent = function (el, evname, func) 
{
	if (el.attachEvent) 
	{ 
		// IE
		el.attachEvent("on" + evname, func);
	} 
	else if (el.addEventListener) 
	{ 
		// Gecko / W3C
		el.addEventListener(evname, func, true);
	} 
	else 
	{
		el["on" + evname] = func;
	}
}

/* ----------------------------------------------------------------------------------------------------------------------------	*/
/* removeEvent																													*/
/* ----------------------------------------------------------------------------------------------------------------------------	*/
floatWindow.removeEvent = function (el, evname, func) 
{
	if (el.detachEvent) 
	{ 
		// IE
		el.detachEvent("on" + evname, func);
	} 
	else if (el.removeEventListener) 
	{ 
		// Gecko / W3C
		el.removeEventListener(evname, func, true);
	} 
	else 
	{
		el["on" + evname] = null;
	}
}

/* ----------------------------------------------------------------------------------------------------------------------------	*/
/* getElement																													*/
/* ----------------------------------------------------------------------------------------------------------------------------	*/
floatWindow.getElement = function(e) 
{
	var f = floatWindow.is_ie ? window.event.srcElement : e.currentTarget;
	
	return f;

/* find first div father ??? (copies from calendar)
	while (f.nodeType != 1 || /^div$/i.test(f.tagName))
		f = f.parentNode;
	return f;
*/
}

/* ----------------------------------------------------------------------------------------------------------------------------	*/
/* getTargetElement																												*/
/* ----------------------------------------------------------------------------------------------------------------------------	*/
floatWindow.getTargetElement = function(e) 
{
	var f = floatWindow.is_ie ? window.event.srcElement : e.target;
	return f;
/*	while (f.nodeType != 1)
		f = f.parentNode;
	return f;*/
}

/* ----------------------------------------------------------------------------------------------------------------------------	*/
/* checkWindow																													*/
/* ----------------------------------------------------------------------------------------------------------------------------	*/
floatWindow.checkWindow = function(e) 
{
	var fw = window.globalFloatWindow;

	if (!fw) 
	{
		return false;
	}

	var el = floatWindow.is_ie ? floatWindow.getElement(e) : floatWindow.getTargetElement(e);

	while (el != null && el != fw.obj)
	{
		el = el.parentNode;
	}

	if (el == null) 
	{
		if (window.globalFloatWindow.withCloseEvent)
		{
			window.globalFloatWindow.close();
			return floatWindow.stopEvent(e);
		}
	}
}

/* ----------------------------------------------------------------------------------------------------------------------------	*/
/* create																														*/
/* ----------------------------------------------------------------------------------------------------------------------------	*/
floatWindow.prototype.create = function (parentElement, id, htmlCode, x, y, fieldName)
{
	var oParent = null;
	if (!parentElement)
	{
		oParent = document.getElementsByTagName("body")[0];
	}
	else
	{
		if (typeof parentElement == "string")
			oParent = document.getElementById(parentElement);
		else
			oParent = parentElement;
	}

	if (this.obj == null)
	{
		var div = floatWindow.createElement("div");
		this.obj = div;
	}

	this.obj.id  = id;
	this.obj.style.position = "absolute";
	this.obj.style.zIndex   = "20";
	this.obj.style.top	    = y;
	this.obj.style.left	    = x;
	this.obj.style.display  = "none";	

	this.obj.innerHTML = htmlCode;

	if (this.obj != null)
	{
		oParent.appendChild(this.obj);
	}

	var oIframe 	= document.getElementById("selectblocker");
	this.iframeName	= "selectblocker";

	if (oIframe != undefined && floatWindow.is_ie)
	{
		oIframe.style.top			= y;
		oIframe.style.left			= x;
		oIframe.zIndex				= "19";
	}

	if (fieldName != undefined)
		this.field = fieldName;
}

/* ----------------------------------------------------------------------------------------------------------------------------	*/
/* loadHtml																														*/
/* ----------------------------------------------------------------------------------------------------------------------------	*/
floatWindow.prototype.loadHtml = function (htmlCode)
{
	this.obj.innerHTML = htmlCode;
}

/* ----------------------------------------------------------------------------------------------------------------------------	*/
/* show																															*/
/* ----------------------------------------------------------------------------------------------------------------------------	*/
floatWindow.prototype.show = function (fades)
{
	if (this.obj.style.display == "") return;

	if (fades != undefined)
		this.fadeArray = new Object(fades);

	floatWindow.addEvent(document, "mousedown", floatWindow.checkWindow);
	window.globalFloatWindow = this;

	this.obj.style.display = "";
	var oIframe 				= document.getElementById(this.iframeName);
	if (oIframe != undefined && floatWindow.is_ie)
		document.getElementById(this.iframeName).style.visibility = "visible";
}

/* ----------------------------------------------------------------------------------------------------------------------------	*/
/* close																														*/
/* ----------------------------------------------------------------------------------------------------------------------------	*/
floatWindow.prototype.close = function ()
{
	if (this.fadeArray != "")
	{
		timeToFade = 500.0;

		for (i = 0; i<this.fadeArray.length; i++)
			fade (this.fadeArray[i]);
	}

	floatWindow.removeEvent(document, "mousedown", floatWindow.checkWindow);

	this.obj.style.display = "none";
	var oIframe 				= document.getElementById(this.iframeName);
	if (oIframe != undefined && floatWindow.is_ie)
		document.getElementById(this.iframeName).style.visibility = "hidden";

	if (this.field != undefined)
		try {document.getElementById(this.field).focus(); } catch(e) {}
}

// ----------------------------------------------------------------------------------------------------------------------------

// global object that remembers the float window
window.globalFloatWindow = null;

