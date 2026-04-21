var blinkSpeed = 500;

var blinkObjs = new Array();

/* ---------------------------------------------------------------- */
/* doBlink															*/
/* ---------------------------------------------------------------- */
function doBlink ()
{
	for (i=0; i<blinkObjs.length; i++)
	{
		obj = document.getElementById("blink" + blinkObjs[i]);
	
		if (obj == undefined)
			break;
		
		blinkIndex = obj.className.indexOf("_blink");
		if (blinkIndex == -1)
		{
			obj.className += "_blink";
		}
		else
		{
			obj.className = obj.className.substring(0,blinkIndex);
		}
	}

	setTimeout("doBlink();",blinkSpeed);
}
