function openCloseMenu (menuId, numSubMenus)
{
	for (i=0; i<numSubMenus; i++)
	{
		submenuObj = document.getElementById("level2_" + menuId + "_" + i);

		if (submenuObj != undefined) 
		{
			if (submenuObj.style.display == "none")
			{
				submenuObj.style.display = "";
			}
			else
			{
				submenuObj.style.display = "none";
			}
		}
	}
}


