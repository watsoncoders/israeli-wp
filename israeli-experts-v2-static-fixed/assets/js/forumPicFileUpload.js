var forumPicFileDoUpload = false;
var picFileDir;

var forumPicSWFUpload;

/* ------------------------------------------------------------------------------------------------------------	*/
/* forumPicFileUploadInit																						*/
/* ------------------------------------------------------------------------------------------------------------	*/
function forumPicFileUploadInit (thisSiteUrl, btnWidth, btnHeight)
{
	fileTypes  	= "*.gif;*.jpg;*.png;";
	fileSize   	= "1 MB";
	picFileDir 	= document.getElementById("addNewMsgForm").filesDir.value;

	var uploadParms =  {debug 						 : false,
						upload_url	 				 : thisSiteUrl + "/SWFUpload/uploadPicFile.php?filesDir=" + picFileDir,
						button_placeholder_id		 : "addNewMsgForm_picFile_browse",
						button_width 				 : btnWidth,
						button_height 				 : btnHeight,
						button_image_url			 : "forumImages/uploadPic.png",
						flash_url					 : "SWFUpload/swfupload.swf",
						file_types		 			 : fileTypes,
						file_size_limit				 : fileSize,
						file_dialog_complete_handler : forumPicFileDialogComplete,
						upload_start_handler 		 : forumPicFileUploadStart,
						upload_progress_handler 	 : forumPicFileUploadProgress,
						upload_complete_handler 	 : forumPicFileUploadComplete}

	try
	{
		forumPicSWFUpload = new SWFUpload(uploadParms);
	}
	catch (e)
	{
		alert ("נא להמתין לסיום טעינת הדף");
		return false;
	}

	return true;
}

/* ------------------------------------------------------------------------------------------------------------	*/
/* id																											*/
/* ------------------------------------------------------------------------------------------------------------	*/
function $(id) 
{
	return document.getElementById(id);
}

function forumPicFileDialogComplete ()
{
	   this.startUpload();
}


/* ------------------------------------------------------------------------------------------------------------	*/
/* forumPicFileUploadStart																						*/
/* ------------------------------------------------------------------------------------------------------------	*/
function forumPicFileUploadStart (fileObj)
{
	forumPicFileDoUpload = true;

	oSpan = document.getElementById("addNewMsgForm_fileName_spn");
	oSpan.className = "uploading";
	oSpan.innerHTML = fileObj.name + "&nbsp;&nbsp;&nbsp;" + Math.ceil(fileObj.size / 1000) + " kb";;

	document.getElementById("addNewMsgForm_picFileName_spn").innerHTML = fileObj.name;

	oSpan = document.getElementById("addNewMsgForm_progress_spn");
	//oSpan.className = "progressBar"
	oSpan.style.display = "";
}

/* ------------------------------------------------------------------------------------------------------------	*/
/* forumPicFileUploadProgress																					*/
/* ------------------------------------------------------------------------------------------------------------	*/
function forumPicFileUploadProgress (fileObj, bytesLoaded)
{
	var progress = document.getElementById("addNewMsgForm_progress_spn");

	var percent = Math.ceil((bytesLoaded / fileObj.size) * 100)
	progress.style.background = "url(SWFUpload/images/progressbar.png) no-repeat -" + (100 - percent) + "px 0";
}

/* ------------------------------------------------------------------------------------------------------------	*/
/* forumPicFileUploadComplete																					*/
/* ------------------------------------------------------------------------------------------------------------	*/
function forumPicFileUploadComplete (fileObj) 
{
	forumPicFileDoUpload = false;

	document.getElementById("addNewMsgForm_progress_spn").style.display = "none";

	if (document.getElementById("addNewMsgForm_picFile_preview"))
		document.getElementById("addNewMsgForm_picFile_preview").innerHTML =
		   "<img src='SWFUpload/files/pic_" + picFileDir + "/" + fileObj.name + "' width='120' height='120' />";

	document.getElementById("addNewMsgForm").fileName.value = fileObj.name.replace(/ /g,"_");
}

/* ------------------------------------------------------------------------------------------------------------	*/
/* forumPicFileUploadCancel																						*/
/* ------------------------------------------------------------------------------------------------------------	*/
function forumPicFileUploadCancel () 
{
}

/* ------------------------------------------------------------------------------------------------------------	*/
/* forumPicFileDelete																							*/
/* ------------------------------------------------------------------------------------------------------------	*/
function forumPicFileDelete (e)
{
	if (forumPicFileDoUpload) 
	{
		alert ("יש להמתין לסיום טעינת התמונות");
		return false;
	}

	document.getElementById("addNewMsgForm_fileName_spn").innerHTML = "";
}

