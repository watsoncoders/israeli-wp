
// defined on php
// -----------------------
// albumPics
// maxPages
// numSmallPics

var continueAuto = true;

/* ---------------------------------------------------------------- */
/* album_showBigPic													*/
/* ---------------------------------------------------------------- */
function album_showBigPic (i, auto)
{
	if (auto == undefined) 
	{
		continueAuto = false;
	}

	if (document.getElementById("album_bigPic") != undefined)
	{
		index = (currPage-1) * numSmallPics + i;

		var album_bigPic = document.getElementById("album_bigPic");
		var video_bigPic = document.getElementById("video_bigPic_Obj");
		if (video_bigPic == undefined)
		{
			video_bigPic = document.getElementById("video_bigPic_Embed");
			if (video_bigPic != undefined)
				document.getElementById("video_bigPic_Obj").style.display = ""; // EMBED is inside OBJECT so OBJECT must be visible
		}

		if (albumPics[index].type == 'pic')
		{
			if (typeof jQuery != 'undefined')
					$("#album_bigPic").fadeOut('slow', function(){album_completeShowAfterFadeOut(index,album_bigPic,video_bigPic);});
			else
			{
				album_bigPic.src    				= albumPics[index].img;
				album_bigPic.title  				= albumPics[index].title;

				if (album_bigPic.style.display == "none")
				{
					album_bigPic.style.display = "";
					if (video_bigPic != undefined)
						video_bigPic.style.display = "none";
				}
			}
		}

		if (albumPics[index].type == 'video')
		{
			if (video_bigPic.src)
				video_bigPic.src = albumPics[index].img;
			else
				video_bigPic.fileName = albumPics[index].img; 

			if (video_bigPic.style.display == "none")
			{
				album_bigPic.style.display = "none";
				video_bigPic.style.display = "";
			}

		}

		if (document.getElementById("display_album_bigPic") != undefined)
			document.getElementById("display_album_bigPic").className 	= albumPics[index].bigClass;
	}

	if (document.getElementById("album_picTitle") != undefined)
	{
		document.getElementById("album_picTitle").innerHTML = albumPics[index].title;
	}

	album_switchTo (i);
}

function album_completeShowAfterFadeOut(index,album_bigPic,video_bigPic)
{
	album_bigPic.src    	   = albumPics[index].img;
	album_bigPic.title  	   = albumPics[index].title;
	if (video_bigPic != undefined)
			video_bigPic.style.display = "none";

	$("#album_bigPic").fadeIn(2000);
}

var currPage = 1;

/* ---------------------------------------------------------------- */
/* album_goPage														*/
/* ---------------------------------------------------------------- */
function album_goPage (page)
{
	if (currPage != page)
	{
		continueAuto = false;

		document.getElementById("page" + currPage).className = "pageNumber";
		currPage = page;
		document.getElementById("page" + currPage).className = "thisPageNumber";

		index = (page-1) * numSmallPics;

		for (i=0; i<numSmallPics; i++)
		{
			if (index < albumPics.length)
			{
				bigPic	 = albumPics[index].img;
				smallPic = bigPic.replace(/big/g, "small");
	
				if (i == 0)
				{
					// replace big pic
//					album_showBigPic (0);
					var album_bigPic 	= document.getElementById("album_bigPic");
					album_bigPic.src    = albumPics[index].img;
					album_bigPic.title  = albumPics[index].title;

					if (document.getElementById("album_picTitle") != undefined)
						document.getElementById("album_picTitle").innerHTML = albumPics[index].title;

					var video_bigPic = document.getElementById("video_bigPic_Obj");
					if (video_bigPic != undefined)
						video_bigPic.style.display = "none";

					var sp = document.getElementById("album_smallBox0");
					if (sp != undefined && sp.className != "")
					{
						if (sp.className.substring(sp.className.length-8) != "Selected")
							sp.className += "Selected";
					}
				}
				else
				{
					var sp = document.getElementById("album_smallBox" + i);
					if (sp != undefined && sp.className != "")
					{
						if (sp.className.substring(sp.className.length-8) == "Selected")
							sp.className = sp.className.substring(0,sp.className.length-8);
					}
				}
	
				var sp = document.getElementById("album_smallPic" + i);

				if (sp != null)
						sp.style.display   = "";

				if (document.getElementById("display_album_smallPic" + i) != undefined)
					document.getElementById("display_album_smallPic" + i).style.display   = "";

				if (sp != null)
				{
					sp.src   	= smallPic;
					sp.title 	= albumPics[index].title;
				}
				document.getElementById("display_album_smallPic" + i).className = albumPics[index].smallClass;

				if (document.getElementById("album_smallPicTitle" + i) != undefined)
					document.getElementById("album_smallPicTitle" + i).innerHTML   = albumPics[index].title;

	
				index++;
			}
			else
			{
				document.getElementById("album_smallPic" + i).style.display   = "none";

				if (document.getElementById("display_album_smallPic" + i) != undefined)
					document.getElementById("display_album_smallPic" + i).style.display   = "none";
			}
		}
	}
}

/* ---------------------------------------------------------------- */
/* album_prevPage													*/
/* ---------------------------------------------------------------- */
function album_prevPage ()
{
	if (currPage != 1)
	{
		album_goPage (currPage-1);

		if (currPage == 1)
		{
			if (document.getElementById("prevPageImg") != undefined)
				document.getElementById("prevPageImg").style.display = "none";
			if (document.getElementById("noPrevPageImg") != undefined)
				document.getElementById("noPrevPageImg").style.display = "";
		}

		if (currPage != maxPages)
		{
			if (document.getElementById("nextPageImg") != undefined)
				document.getElementById("nextPageImg").style.display = "";
			if (document.getElementById("noNextPageImg") != undefined)
				document.getElementById("noNextPageImg").style.display = "none";
		}
	}
}

/* ---------------------------------------------------------------- */
/* album_nextPage													*/
/* ---------------------------------------------------------------- */
function album_nextPage ()
{
	if (currPage != maxPages)
	{
		album_goPage (currPage+1);

		if (currPage != 1)
		{
			if (document.getElementById("prevPageImg") != undefined)
				document.getElementById("prevPageImg").style.display = "";
			if (document.getElementById("noPrevPageImg") != undefined)
				document.getElementById("noPrevPageImg").style.display = "none";
		}

		if (currPage == maxPages)
		{
			if (document.getElementById("nextPageImg") != undefined)
				document.getElementById("nextPageImg").style.display = "none";
			if (document.getElementById("noNextPageImg") != undefined)
				document.getElementById("noNextPageImg").style.display = "";
		}
	}
}

/* ---------------------------------------------------------------- */
/* album_autoSwitch													*/
/* ---------------------------------------------------------------- */
function album_autoSwitch (interval)
{
	if (!continueAuto) return;

	if (albumPics.length == 1) return;

	album_showBigPic ((currAlbumPic+1) % albumPics.length, true);

	setTimeout('album_autoSwitch(' + interval + ')', interval);
}

/* ---------------------------------------------------------------- */
/* album_switchTo													*/
/* ---------------------------------------------------------------- */
function album_switchTo (picNo)
{
	var sp = document.getElementById("album_smallBox" + currAlbumPic);
	if (sp != undefined && sp.className != "")
	{
			if (sp.className.substring(sp.className.length-8) == "Selected")
					sp.className = sp.className.substring(0,sp.className.length-8);
	}

	currAlbumPic = picNo;

	sp = document.getElementById("album_smallBox" + currAlbumPic);
	if (sp != undefined && sp.className != "")
	{
			if (sp.className.substring(sp.className.length-8) != "Selected")
					sp.className += "Selected";

			if (typeof jQuery != 'undefined')
			{
				var diff = 
						$("#album_smallBox" + currAlbumPic).position().top + $("#album_smallBox" + currAlbumPic).outerHeight() - $("#albumIcons").height();
				if (diff > 0)
						$("#albumIcons").scrollTop($("#albumIcons").scrollTop() + diff);
				if (diff < -$("#albumIcons").height())
						$("#albumIcons").scrollTop(0);
			}
	}

}
