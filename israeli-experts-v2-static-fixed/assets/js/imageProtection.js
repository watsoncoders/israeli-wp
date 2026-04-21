if(window.addEventListener)
{ 
	// Mozilla, Netscape, Firefox
	window.addEventListener("load", function () { trap() }, false);
} 
else 
{ 
	// IE
	window.attachEvent("onload", function () { trap() });
}

function right(e) 
{
	if (navigator.appName == 'Netscape' && e.which == 3) 
	{
		alert(tailJS["notCopy"]);
		return false;
	}
	if (navigator.appName == 'Microsoft Internet Explorer' && event.button==2) 
	{
		alert(tailJS["notCopy"]);
		return false;
	}
	else 
		return true;
}

function trap()
{
 	if(document.images)
 	{
 		for(i=0;i<document.images.length;i++)
		{
			document.images[i].onmousedown = right;
 			document.images[i].onmouseup = right;
		}
	}
}

