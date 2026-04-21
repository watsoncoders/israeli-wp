function showPopup (menuId, itemId, side, lang) 
{
	if (lang == undefined) lang = "HEB";

	var submenuObj = document.getElementById("submenu" + menuId + "_" + itemId);
	if (submenuObj != undefined) 
	{
		var menuObj = document.getElementById("menu" + menuId + "_" + itemId);

		index = 0;
		while (index < 10 && menuObj.childNodes[index].nodeType == 3)
			index++;

		width = private_getSubmenuWidth (menuId, itemId);

		if (width == 0)
			width = menuObj.childNodes[index].offsetParent.offsetWidth;
 
		submenuObj.style.width = width + "px";

		width  += private_moveLeft(menuId, itemId);
		height =  private_moveTop (menuId, itemId);

		if (side == "")
		{
			var leftPoint = menuObj.childNodes[index].offsetParent.offsetLeft;

			if (lang == "HEB")
				leftPoint += menuObj.childNodes[index].offsetParent.offsetWidth - width;

			submenuObj.style.left  		= leftPoint + "px";
			submenuObj.style.top   		= menuObj.childNodes[index].offsetParent.offsetTop + menuObj.childNodes[index].offsetHeight + height + "px";
		}
		else if (side == "LEFT")
		{
			submenuObj.style.left  		= menuObj.childNodes[index].offsetParent.offsetLeft - width + "px";
			submenuObj.style.top   		= menuObj.childNodes[index].offsetParent.offsetTop + height + "px";
		}
		else if (side == "RIGHT")
		{
			submenuObj.style.left 		= menuObj.childNodes[index].offsetParent.offsetLeft + menuObj.childNodes[index].offsetParent.offsetWidth + "px";
			submenuObj.style.top   		= menuObj.childNodes[index].offsetParent.offsetTop + height + "px";
		}
		submenuObj.style.display	= ""; //visibility	= "visible";
		submenuObj.style.zIndex		= 100;

		var iFrameTag 				= document.getElementById("selectblocker" + menuId + "_" + itemId);

		iFrameTag.style.left    	= submenuObj.style.left;
		iFrameTag.style.top     	= submenuObj.style.top;
		iFrameTag.style.width   	= submenuObj.offsetWidth;
		iFrameTag.style.height  	= submenuObj.offsetHeight;
		iFrameTag.style.zIndex  	= submenuObj.style.zIndex-1;
		//iFrameTag.style.visibility  = "visible";

		try
		{
			private_showPopup (menuId, itemId);
		}
		catch (e)
		{
		}
	}
}

function hidePopup (menuId, itemId, which) 
{
	var submenuObj = document.getElementById("submenu" + menuId + "_" + itemId);
	if (submenuObj != undefined) 
	{
		submenuObj.style.display = "none"; //visibility	 	= "hidden";

		var iFrameTag = document.getElementById("selectblocker" + menuId + "_" + itemId);
		iFrameTag.style.display	= "none"; //visibility  = "hidden";

		try
		{
			private_hidePopup (menuId, itemId);
		}
		catch (e)
		{
		}
	}
}

function showHidePopdown (menuId, itemId)
{
	var menuObj    = document.getElementById("menu" + menuId + "_" + itemId);
	var submenuObj = document.getElementById("submenu" + menuId + "_" + itemId);

	if (submenuObj != undefined) 
	{
		if (submenuObj.style.display == "none")
		{
			submenuObj.style.display = "";
			menuObj.className 		 = menuObj.className + "_open";
		}
		else
		{
			submenuObj.style.display = "none";
			menuObj.className 		 = menuObj.className.substr(0,menuObj.className.length-5);
		}
	}
}


