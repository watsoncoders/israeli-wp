
/* ---------------------------------------------------------------- */
/* commonSetCookie                                                  */
/* ---------------------------------------------------------------- */
function commonSetCookie(cookieName, cookieValue)
{
        document.cookie = cookieName + "=" + cookieValue + "; expires=";
}

/* ---------------------------------------------------------------- */
/* commonGetCookie                                                  */
/* ---------------------------------------------------------------- */
function commonGetCookie(cookieName)
{
        var allCookie = document.cookie;
        var theValue;
        var start = allCookie.indexOf(cookieName+"=");
        if (start == -1)
			return "";
			
        var end = allCookie.indexOf(";", start);
        
        if (end == -1) 
			end = allCookie.length;
			
        var name_value = allCookie.substring(start, end);
        theValue = name_value.substring(cookieName.length+1, name_value.length);
        
        return theValue;
}

var showPopup = true;

/* ---------------------------------------------------------------- */
/* commonSetAutoStart                                               */
/* ---------------------------------------------------------------- */
function commonSetAutoStart(trueORfalse)
{
	commonSetCookie("cookieMusicAutoStart", trueORfalse);
}

var blink_speed=1200;
var blink_on=0;

function commonBlink()
{
	for (b=1; b<20; b++)
	{
		obj = eval("window.blink" + b);
	
		if (obj == undefined)
			break;

		if (blink_on==0)
		{
			obj.style.color = obj.parentNode.style.color;
		}
		else
		{
			obj.style.color = "red";
		}
	}

	if (b != 1)
	{
		if (blink_on==0)
		{
			blink_on = 1;
			speed = blink_speed;
		}
		else
		{
			blink_on = 0;
			speed = blink_speed/2;
		}
		setTimeout("commonBlink();",speed);
	}
}

/* ---------------------------------------------------------------- */
/* commonEncode	- moved to LANG.js									*/
/* ---------------------------------------------------------------- */

/* ---------------------------------------------------------------- */
/* commonDecode	- moved to LANG.js									*/
/* ---------------------------------------------------------------- */

/* ---------------------------------------------------------------- */
/* commonSortXml													*/
/* ---------------------------------------------------------------- */
function commonSortXml (xml, sortColumn, sortType, sortDir)
{
	var xslDoc = new ActiveXObject("Microsoft.XMLDOM");
    xslDoc.async = false;

    var xsl =   " <xsl:stylesheet xmlns:xsl='http://www.w3.org/1999/XSL/Transform' " 	+
				" version='1.0'> " 														+
       	        " <xsl:template match='/'> " 											+
           	    " <items> " 															+
               	" <xsl:for-each select='items/item'> " 									+
				" <xsl:sort select='" + sortColumn + "'" 								+
			    "          order='" + sortDir+ "' data-type='" + sortType + "'/>" 		+
   		    	"       <xsl:copy> " 													+
           	    "           <xsl:copy-of select='*'/> " 								+
               	"       </xsl:copy> " 													+
                "   </xsl:for-each> " 													+
   	            " </items> " 															+
       	        " </xsl:template> " 													+
           	    " </xsl:stylesheet> ";

    // loading the xsl sorter
	//--------------------------------------------------------------------
    xslDoc.loadXML (xsl);

	var toSortXml = new ActiveXObject("Microsoft.XMLDOM");
	toSortXml.loadXML (xml.getElementsByTagName("items").item(0).xml);
	
	sortedXml = new ActiveXObject("Microsoft.XMLDOM");
	sortedXml.loadXML ("<data>" + toSortXml.transformNode(xslDoc) + "</data>");

	return sortedXml;	
}

/* ---------------------------------------------------------------- */
/* commonPrintPage													*/
/* ---------------------------------------------------------------- */
function commonPrintPage (type, id)
{
	var xmlRequest = new xmlObj(false);
	xmlRequest.init ("<data>"	+
						"<command>private.getPrintPage</command>"	+
						"<type>" + type + "</type>"	+
						"<id>" + id + "</id>"	+
					 "</data>");
	xmlRequest.sendRequest ('server.php', xmlRequest.obj);

	responseXml = xmlRequest.obj;

	var printFileName = responseXml.getElementsByTagName("printFileName").item(0).text;

	var height = screen.availHeight - 100;
	var width  = screen.availWidth  - 100;

	var y = 0;
	var x = (screen.availWidth  - width)  / 2;

	window.open ("tempPrints/" + printFileName, "",  "status=no,toolbar=no,menubar=yes,height=" + height + ", "	+
					 "								  width =" + width  + ", left="+x+", top="+y);

}

/* ---------------------------------------------------------------- */
/* commonAddLoadEvent												*/
/* ---------------------------------------------------------------- */
function commonAddLoadEvent(func)
{
	  var oldonload = window.onload;
	  if (typeof window.onload != 'function') {
	    window.onload = func;
	  } else {
	    window.onload = function() {
	      if (oldonload) {
	        oldonload();
	      }
	      func();
	    }
	  }
}

function common_clientWidth() {
	return common_filterResults (
		window.innerWidth ? window.innerWidth : 0,
		document.documentElement ? document.documentElement.clientWidth : 0,
		document.body ? document.body.clientWidth : 0
	);
}
function common_clientHeight() {
	return common_filterResults (
		window.innerHeight ? window.innerHeight : 0,
		document.documentElement ? document.documentElement.clientHeight : 0,
		document.body ? document.body.clientHeight : 0
	);
}
function common_scrollLeft() {
	return common_filterResults (
		window.pageXOffset ? window.pageXOffset : 0,
		document.documentElement ? document.documentElement.scrollLeft : 0,
		document.body ? document.body.scrollLeft : 0
	);
}
function common_scrollTop() {
	return common_filterResults (
		window.pageYOffset ? window.pageYOffset : 0,
		document.documentElement ? document.documentElement.scrollTop : 0,
		document.body ? document.body.scrollTop : 0
	);
}
function common_filterResults(n_win, n_docel, n_body) {
	var n_result = n_win ? n_win : 0;
	if (n_docel && (!n_result || (n_result > n_docel)))
		n_result = n_docel;
	return n_body && (!n_result || (n_result > n_body)) ? n_body : n_result;
}

var commonLateBanners = new Array();

/* ---------------------------------------------------------------- */
/* commonLateBannerHtml												*/
/* ---------------------------------------------------------------- */
function commonLateBannerHtml(middleWidth, bannerWidth, divId, htmlSource)
{
		if (((common_clientWidth() - middleWidth) / 2) - 10 > bannerWidth)
		{
			document.getElementById(divId).innerHTML = unescape(htmlSource);

			if (commonLateBanners.length == 0) // first late banner show up
			{
				window.onresize = function () {commonLateBannerResize();}
			}

			commonLateBanners.push(middleWidth);
			commonLateBanners.push(bannerWidth);
			commonLateBanners.push(divId);
		}
}

/* ---------------------------------------------------------------- */
/* commonLateBannerResize												*/
/* ---------------------------------------------------------------- */
function commonLateBannerResize()
{
		divId = null;
		bannerWidth = null;
		middleWidth = null;

		for (x in commonLateBanners)
		{
			if (middleWidth == null)
			{
					middleWidth = commonLateBanners[x];
					continue;
			}
			if (bannerWidth == null)
			{
					bannerWidth = commonLateBanners[x];
					continue;
			}
			if (divId == null)
			{
					divId = commonLateBanners[x];
			}
			if (((common_clientWidth() - middleWidth) / 2) - 10 > bannerWidth)
				document.getElementById(divId).style.display = '';
			else
				document.getElementById(divId).style.display = 'none';

			divId = null;
			bannerWidth = null;
			middleWidth = null;
		}
}

/**
 * [Amir 25/9/2011] included from tinymce/plugins/media/js/embed.js
 * This script contains embed functions for common plugins. This scripts are complety free to use for any purpose.
 */

function writeFlash(p) {
	writeEmbed(
		'D27CDB6E-AE6D-11cf-96B8-444553540000',
		'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0',
		'application/x-shockwave-flash',
		p
	);
}

function writeShockWave(p) {
	writeEmbed(
	'166B1BCA-3F9C-11CF-8075-444553540000',
	'http://download.macromedia.com/pub/shockwave/cabs/director/sw.cab#version=8,5,1,0',
	'application/x-director',
		p
	);
}

function writeQuickTime(p) {
	writeEmbed(
		'02BF25D5-8C17-4B23-BC80-D3488ABDDC6B',
		'http://www.apple.com/qtactivex/qtplugin.cab#version=6,0,2,0',
		'video/quicktime',
		p
	);
}

function writeRealMedia(p) {
	writeEmbed(
		'CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA',
		'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0',
		'audio/x-pn-realaudio-plugin',
		p
	);
}

function writeWindowsMedia(p) {
	p.url = p.src;
	writeEmbed(
		'6BF52A52-394A-11D3-B153-00C04F79FAA6',
		'http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=5,1,52,701',
		'application/x-mplayer2',
		p
	);
}

function writeEmbed(cls, cb, mt, p) {
	var h = '', n;

	h += '<object classid="clsid:' + cls + '" codebase="' + cb + '"';
	h += typeof(p.id) != "undefined" ? 'id="' + p.id + '"' : '';
	h += typeof(p.name) != "undefined" ? 'name="' + p.name + '"' : '';
	h += typeof(p.width) != "undefined" ? 'width="' + p.width + '"' : '';
	h += typeof(p.height) != "undefined" ? 'height="' + p.height + '"' : '';
	h += typeof(p.align) != "undefined" ? 'align="' + p.align + '"' : '';
	h += '>';

	for (n in p)
		h += '<param name="' + n + '" value="' + p[n] + '">';

	h += '<embed type="' + mt + '"';

	for (n in p)
		h += n + '="' + p[n] + '" ';

	h += '></embed></object>';

	document.write(h);
}
