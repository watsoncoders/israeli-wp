     /* You may use this code for free on any web page provided that 
      these comment lines and the following credit remain in the code.
      Floating Div from http://www.javascript-fx.com
	 */
var ns = (navigator.appName.indexOf("Netscape") != -1);
var d = document;

function JSFX_FloatDiv(id, sx, sy)
{
	var el=d.getElementById?d.getElementById(id):d.all?d.all[id]:d.layers[id];
	var px = document.layers ? "" : "px";
	window[id + "_obj"] = el;
	if(d.layers)el.style=el;
	el.cx = el.sx = sx;
	el.cy = el.sy = sy;
	el.sP=function(x,y){
	//this.style.left=x+px;
	this.style.top=y+px;};

	el.floatIt=function()
	{
		if (typeof(window.innerHeight) == 'number')
		{
			// Non-IE
			height = window.innerHeight;
		}
		else if (document.documentElement && (document.documentElement.clientHeight))
		{
			// IE 6+ in 'standards compliant mode'
			height = document.documentElement.clientWidth;
		}
		else if (document.body && (document.body.clientHeight))
		{
			// IE 4 compatible
			height = document.body.clientHeight;
		}
		
		if (height < 800)
		{
			this.sP(0,0);
		}
		else
		{
			var pX, pY;
			pX = (this.sx >= 0) ? 0 : ns ? innerWidth : 
			document.documentElement && document.documentElement.clientWidth ? 
			document.documentElement.clientWidth : document.body.clientWidth;
			pY = ns ? pageYOffset : document.documentElement && document.documentElement.scrollTop ? 
			document.documentElement.scrollTop : document.body.scrollTop;
			if(this.sy<0) 
				pY += ns ? innerHeight : document.documentElement && document.documentElement.clientHeight ? 
										 document.documentElement.clientHeight : document.body.clientHeight;
			//this.cx += (pX + this.sx - this.cx)/8;
			this.cy += (pY + this.sy - this.cy)/8;
			this.sP(this.cx, this.cy);
		}
		setTimeout(this.id + "_obj.floatIt()", 1);
	}
	return el;
}


/*
Created by Randy Bennet http://home.thezone.net/~rbennett/utility/javahead.htm
Featured on JavaScript Kit (http://javascriptkit.com)
For this and over 400+ free scripts, visit http://javascriptkit.com
*/

/*function setVariables() {
if (document.layers) {
v=".top=";
dS="document.";
sD="";
y="window.pageYOffset";
}
else if (document.all){
v=".pixelTop=";
dS="";
sD=".style";
y="document.body.scrollTop";
}
else if (document.getElementById){
y="window.pageYOffset";
}
}
function checkLocation() {
object="object1";
yy=eval(y);
if (document.getElementById)
document.getElementById("object1").style.top=yy
else
eval(dS+object+sD+v+yy)
setTimeout("checkLocation()",10);
}*/


