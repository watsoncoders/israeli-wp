var forumDocFileDoUpload = false;
var docFileDir;

var forumPicSWFUpload;

/* ------------------------------------------------------------------------------------------------------------	*/
/* forumDocFileUploadInit																						*/
/* ------------------------------------------------------------------------------------------------------------	*/
function forumDocFileUploadInit (thisSiteUrl, btnWidth, btnHeight, btnImg)
{
	fileTypes  	= "*.doc;*.pdf;*.pps;*.docx;*.rtf;*.ppt;*.txt;*.xls;*.xlsx";
	fileSize   	= "5 MB";
	docFileDir 	= document.getElementById("addNewMsgForm").filesDir.value;

	var uploadParms =  {debug 						 : false,
						upload_url	 				 : thisSiteUrl + "/SWFUpload/uploadForumDocFile.php?filesDir=" + docFileDir,
						button_placeholder_id		 : "addNewMsgForm_docFile_browse",
						button_width 				 : btnWidth,
						button_height 				 : btnHeight,
						button_image_url			 : btnImg,
						flash_url					 : "SWFUpload/swfupload.swf",
						file_types		 			 : fileTypes,
						file_size_limit				 : fileSize,
						file_dialog_complete_handler : forumDocFileDialogComplete,
						upload_start_handler 		 : forumDocFileUploadStart,
						upload_progress_handler 	 : forumDocFileUploadProgress,
						upload_complete_handler 	 : forumDocFileUploadComplete}

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

function forumDocFileDialogComplete ()
{
	   this.startUpload();
}


/* ------------------------------------------------------------------------------------------------------------	*/
/* forumDocFileUploadStart																						*/
/* ------------------------------------------------------------------------------------------------------------	*/
function forumDocFileUploadStart (fileObj)
{
	forumDocFileDoUpload = true;

	oSpan = document.getElementById("addNewMsgForm_fileName2_spn");
	oSpan.className = "uploading";
	oSpan.innerHTML = fileObj.name + "&nbsp;&nbsp;&nbsp;" + Math.ceil(fileObj.size / 1000) + " kb";;

	document.getElementById("addNewMsgForm_docFileName_spn").innerHTML = fileObj.name;

	oSpan = document.getElementById("addNewMsgForm_docProgress_spn");
	//oSpan.className = "progressBar"
	oSpan.style.display = "";
}

/* ------------------------------------------------------------------------------------------------------------	*/
/* forumDocFileUploadProgress																					*/
/* ------------------------------------------------------------------------------------------------------------	*/
function forumDocFileUploadProgress (fileObj, bytesLoaded)
{
	var progress = document.getElementById("addNewMsgForm_docProgress_spn");

	var percent = Math.ceil((bytesLoaded / fileObj.size) * 100)
	progress.style.background = "url(SWFUpload/images/progressbar.png) no-repeat -" + (100 - percent) + "px 0";
}

/* ------------------------------------------------------------------------------------------------------------	*/
/* forumDocFileUploadComplete																					*/
/* ------------------------------------------------------------------------------------------------------------	*/
function forumDocFileUploadComplete (fileObj) 
{
	forumDocFileDoUpload = false;

	document.getElementById("addNewMsgForm_docProgress_spn").style.display = "none";

	if (document.getElementById("addNewMsgForm_docFile_preview"))
		document.getElementById("addNewMsgForm_docFile_preview").innerHTML =
		   "<img src='SWFUpload/files/doc_" + docFileDir + "/" + fileObj.name + "' width='120' height='120' />";

	document.getElementById("addNewMsgForm").docFileName.value = fileObj.name.replace(/ /g,"_");

	document.getElementById("addNewMsgForm_fileName2_spn").style.display = "";
}

/* ------------------------------------------------------------------------------------------------------------	*/
/* forumDocFileUploadCancel																						*/
/* ------------------------------------------------------------------------------------------------------------	*/
function forumDocFileUploadCancel () 
{
}

/* ------------------------------------------------------------------------------------------------------------	*/
/* forumDocFileDelete																							*/
/* ------------------------------------------------------------------------------------------------------------	*/
function forumDocFileDelete (e)
{
	if (forumDocFileDoUpload) 
	{
		alert ("יש להמתין לסיום טעינת התמונות");
		return false;
	}

	document.getElementById("addNewMsgForm_fileName2_spn").innerHTML = "";
}

