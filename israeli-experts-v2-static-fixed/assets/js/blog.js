/* --------------------------------------------------------------------------------------------------------------------	*/
/* collapseExpand																										*/
/* --------------------------------------------------------------------------------------------------------------------	*/
function collapseExpand (oDiv)
{
	if (oDiv.className.indexOf("_expand") == -1)
	{
		oDiv.className += "_expand";

		oDiv.nextSibling.style.display = "";
	}
	else
	{
		oDiv.className = oDiv.className.substr(0,oDiv.className.length-7)

		oDiv.nextSibling.style.display = "none";
	}
}

/* --------------------------------------------------------------------------------------------------------------------	*/
/* highlight																											*/
/* --------------------------------------------------------------------------------------------------------------------	*/
function highlight (oDiv) 
{
	oDiv.className =  "over_" + oDiv.className
}

/* --------------------------------------------------------------------------------------------------------------------	*/
/* turnOff																												*/
/* --------------------------------------------------------------------------------------------------------------------	*/
function turnOff (oDiv) 
{
	oDiv.className = oDiv.className.substr(5,oDiv.className.length-1)
}

/* --------------------------------------------------------------------------------------------------------------------	*/
/* showHideComments																										*/
/* --------------------------------------------------------------------------------------------------------------------	*/
function showHideComments (col,i)
{
	oComments = document.getElementById("postComments" + i);

	if (oComments.style.display == "")
	{
		oComments.style.display 	= "none";
		col.childNodes[0].innerHTML = tailJS["blog_showComments"];
	}
	else
	{
		oComments.style.display 	= "";
		col.childNodes[0].innerHTML = tailJS["blog_hideComments"];
	}
}

/* --------------------------------------------------------------------------------------------------------------------	*/
/* openCloseComment																										*/
/* --------------------------------------------------------------------------------------------------------------------	*/
function openCloseComment (postId, i)
{
	var commentBody = document.getElementById("commentBody" + postId + "_" + i); 

   	if (commentBody.style.display == "") 
	   	commentBody.style.display = "none";
   	else
		commentBody.style.display = "";
}

/* --------------------------------------------------------------------------------------------------------------------	*/
/* showHideCommentForm																									*/
/* --------------------------------------------------------------------------------------------------------------------	*/
function showHideCommentForm (i)
{
	var addComment  = document.getElementById("addComment" + i); 
	var forwardPost = document.getElementById("forwardPost" + i); 

	if (forwardPost != undefined)
		forwardPost.style.display = "none";

	if (addComment.style.display == "")
		addComment.style.display = "none";
	else
	{
		addComment.style.display = "";

		// reset form
		var form = document.getElementById("addCommentForm" + i);
		form.reset ();
	}
}

/* --------------------------------------------------------------------------------------------------------------------	*/
/* showHideForwardForm																									*/
/* --------------------------------------------------------------------------------------------------------------------	*/
function showHideForwardForm (i)
{
	var forwardPost = document.getElementById("forwardPost" + i); 
	var addComment  = document.getElementById("addComment" + i); 

	if (addComment != undefined)
		addComment.style.display = "none";

	if (forwardPost.style.display == "")
		forwardPost.style.display = "none";
	else
	{
		forwardPost.style.display = "";

		// reset form
		var form = document.getElementById("forwardPostForm" + i);
		form.reset ();
	}
}

/* --------------------------------------------------------------------------------------------------------------------	*/
/* printPost																											*/
/* --------------------------------------------------------------------------------------------------------------------	*/
function printPost (i)
{
	var postTop  = document.getElementById("postTop"  + i);
	var postBody = document.getElementById("postBody" + i);

	html = 	"<html dir='rtl'>\n" +
		   	"<head>\n" +
				"<meta http-equiv='content-type' content='text/html; charset=windows-1255'>\n" +
				"<title>Print</title>\n" +
				"<link type='text/css' rel='stylesheet' href='css/layouts.css'>\n" +
				"<link type='text/css' rel='stylesheet' href='css/blogs.css'>\n" +
				"<link type='text/css' rel='stylesheet' href='common.css'>\n" +
				"<script type='text/javascript'>\n" +
				"	function doPrint ()\n" +
				"	{\n" +
				"		window.print();\n" +
				"		window.close();\n" +
				"	}\n" +
				"   setTimeout(\"doPrint()\",150);\n" + 
				"</script>\n" +
			"</head>\n" +
			"<body>\n" +
				"<div id='printPost'>\n" +
					postTop.innerHTML + "<br/><br/>\n" +
					postBody.innerHTML + "</br/>\n" +
				"</div>\n" +
			"</body>\n" +
			"</html>";
			
	win = window.open  ("", "_blank");
	win.document.open  ();
	win.document.write (html);
	win.document.close ();
}

/* --------------------------------------------------------------------------------------------------------------------	*/
/* addNewComment																										*/
/* --------------------------------------------------------------------------------------------------------------------	*/
function addNewComment (postId)
{
	var form 		  = document.getElementById("addCommentForm" + postId);

	var formValidator = new Validator("addCommentForm" + postId);
	formValidator.clearAllValidations ();

	if (form.username.type != "hidden")
		formValidator.addValidation('username',			'required',		tailJS["blog_enterName"]);

	if (form.email.type != "hidden")
	{
		formValidator.addValidation('email',			'required',		tailJS["blog_enterEmail"]);
		formValidator.addValidation('email',			'email',		tailJS["blog_enterValidEmail"]);
	}

	formValidator.addValidation('title',				'required',		tailJS["blog_enterTitle"]);
	formValidator.addValidation('txt',					'required',		tailJS["blog_enterContent"]);
				
	if (formValidator.validate ())
		form.submit();
	else
		return false;
}

/* --------------------------------------------------------------------------------------------------------------------	*/
/* forwardPost																											*/
/* --------------------------------------------------------------------------------------------------------------------	*/
function forwardPost (postId)
{
	var form 		  = document.getElementById("forwardPostForm" + postId);

	var formValidator = new Validator("forwardPostForm" + postId);
	formValidator.clearAllValidations ();

	if (form.myName.type != "hidden")
		formValidator.addValidation('myName',			'required',		tailJS["blog_enterYourName"]);

	if (form.myEmail.type != "hidden")
	{
		formValidator.addValidation('myEmail',			'required',		tailJS["blog_enterYourEmail"]);
		formValidator.addValidation('myEmail',			'email',		tailJS["blog_enterValidEmail"]);
	}

	formValidator.addValidation('toName',				'required',		tailJS["blog_enterFName"]);
	formValidator.addValidation('toEmail',				'required',		tailJS["blog_enterFEmail"]);
	formValidator.addValidation('toEmail',				'email',		tailJS["blog_enterValidEmail"]);
				
	if (formValidator.validate ())
		form.submit();
	else
		return false;
}

/* --------------------------------------------------------------------------------------------------------------------	*/
/* validateNewBlog																										*/
/* --------------------------------------------------------------------------------------------------------------------	*/
function validateNewBlog ()
{
	var formValidator = new Validator("newBlogForm");
	formValidator.clearAllValidations ();

	formValidator.addValidation('blogName',				'required',		tailJS["blog_enterBlogName"]);
				
	return (formValidator.validate ());
}

/* --------------------------------------------------------------------------------------------------------------------	*/
/* validatePost																											*/
/* --------------------------------------------------------------------------------------------------------------------	*/
function validatePost (isReady)
{
	var form 		  = document.getElementById("newPostForm");

	if (form.postTitle.value == "")
	{
		alert (tailJS["blog_enterPostTitle"]);
		form.postTitle.focus ();
		return false;
	}

	editorValue = document.getElementById("editorContent").value
	if (editorValue == "")
	{
		alert (tailJS["blog_enterPostText"]);
		return false;
	}

	form.isReady.value  = isReady;	// init isReady field by passed parameter
	form.postText.value	= editorValue;

	form.submit ();

	return true;
}

/* --------------------------------------------------------------------------------------------------------------------	*/
/* changeCommentImg																										*/
/* --------------------------------------------------------------------------------------------------------------------	*/
function changeCommentImg (uniqueId)
{
	if (document.getElementById("commentBody" + uniqueId).style.display == "")
		document.getElementById("postCommentImg" + uniqueId).src = "loadedFiles/postComment_img_open.png";
	else
		document.getElementById("postCommentImg" + uniqueId).src = "loadedFiles/postComment_img.png";
}

/* --------------------------------------------------------------------------------------------------------------------	*/
/* confirmDeletePost																									*/
/* --------------------------------------------------------------------------------------------------------------------	*/
function confirmDeletePost (pageId, postId)
{
	if (confirm(tailJS["blog_confirmDeletePost"]))
		window.location.href = "blogActions.php?action=deletePost&postId=" + postId + "&redirect=" + pageId;
}

/* --------------------------------------------------------------------------------------------------------------------	*/
/* deleteComment																										*/
/* --------------------------------------------------------------------------------------------------------------------	*/
function deleteComment (pageId, commentId)
{
	if (confirm(tailJS["blog_confirmDeleteComment"]))
		window.location.replace("blogActions.php?action=deleteComment&commentId=" + commentId + "&redirect=" + pageId);
}
