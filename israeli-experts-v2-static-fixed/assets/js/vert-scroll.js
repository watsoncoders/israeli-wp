//-------------------------------------------------------------------------
// Vertical Scrolling News Ticker
// XHTML Compat
// Version 2.0
// Copyright 2006 DevDude.com
//-------------------------------------------------------------------------
	
//scroller width
var scroll_width=250;

//scroller height
var scroll_height=100;

//background color 
var scroll_color="#FFFFFF";

//scroller's speed
var scroll_speed=1;
var scroll_timeout = 60;

var scroll_msg = '';

var oldonload = window.onload;
if (typeof window.onload != 'function') {
      window.onload = function() {start_scrolling()};
}
else {
    window.onload = function() {
	      oldonload();
    	  start_scrolling();
	}
}

var resumesspeed=scroll_speed
function start_scrolling() {
	if (document.getElementById('ticker') == undefined)
			return;
	scroll_msg = document.getElementById('ticker').innerHTML;
	if (document.all) iemarquee(ticker);
	else if (document.getElementById)
		ns6marquee(document.getElementById('ticker'));
}

function iemarquee(whichdiv){
	iediv=eval(whichdiv)
	scroll_height += 50;
	iediv.style.pixelTop=scroll_height
	iediv.innerHTML=scroll_msg 
	sizeup=iediv.offsetHeight
	ieslide()
}

function ieslide(){
	if (iediv.style.pixelTop>=sizeup*(-1)){
		iediv.style.pixelTop-=scroll_speed
		setTimeout("ieslide()",scroll_timeout)
	}
	else{
		iediv.style.pixelTop=scroll_height
		ieslide()
	}
}

function ns6marquee(whichdiv){
	ns6div=eval(whichdiv)
	scroll_height += 50;
	ns6div.style.top=scroll_height + "px";
	ns6div.innerHTML=scroll_msg
	sizeup=ns6div.offsetHeight
	ns6slide()
}
function ns6slide(){
	if (parseInt(ns6div.style.top)>=sizeup*(-1)){
		theTop = parseInt(ns6div.style.top)-scroll_speed
		ns6div.style.top = theTop + "px";
		setTimeout("ns6slide()",scroll_timeout)
	}
	else {
		ns6div.style.top = scroll_height + "px";
		ns6slide()
	}
}

