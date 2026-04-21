var isNS = (navigator.appName == "Netscape") ? 1 : 0;
var EnableRightClick = 0;

if(isNS)
document.captureEvents(Event.MOUSEDOWN||Event.MOUSEUP);

function mischandler() {
  if(EnableRightClick==1) {
    return true;
  } else {
    return false;
  }
}

function mousehandler(e) {
	/*
  if (document.form.selection) {
    return true;
  } else {
	  */
//    try {document.selection.empty();} catch (e) {}
    if(EnableRightClick==1) { return true; }
    var myevent = (isNS) ? e : event;
    var eventbutton = (isNS) ? myevent.which : myevent.button;
    if((eventbutton==2)||(eventbutton==3)) return false;
	return true;
  //}
}

function keyhandler(e) {
  var myevent = (isNS) ? e : window.event;
  if (myevent.keyCode==96) {
    EnableRightClick = 1;
  } else if (myevent.metaKey || myevent.ctrlKey || myevent.ctrlLeft) //|| myevent.keyCode == 67) {
	{
    alert(tailJS["notCopy"]);
    return false;
  } else {
    return true;
  }
}

document.oncontextmenu = mischandler;
document.onkeypress = keyhandler;
document.onkeydown = keyhandler;
document.onmousedown = mousehandler;
document.onmouseup = mousehandler;
