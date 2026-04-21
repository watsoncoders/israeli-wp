
var hideByQuestion 	= new Array();

/* ------------------------------------------------------------------------------------------------------------	*/
/* questionnaire_optionClicked																					*/
/* ------------------------------------------------------------------------------------------------------------	*/
function questionnaire_optionClicked (oField, questionId, radioType, hideOnSelect)
{
	var checked = true;

	if (oField.type == "checkbox")
	{
		checked = oField.checked;
	}

	oInput = document.getElementById("answerText" + questionId);

	if (oInput != undefined)
	{
		if (radioType == "chooseWithText" && checked)
		{
			oInput.disabled = false;
			oInput.focus ();
		}
		else
		{
			oInput.value    = "";
			oInput.disabled = true;
		}

	}

	if (hideByQuestion[questionId] != hideOnSelect)
	{
		if (hideByQuestion[questionId] != undefined)
		{
			questionnaire_handleHideOnSelect (hideByQuestion[questionId], "");
		}

		if (checked)
		{
			hideByQuestion[questionId] = hideOnSelect;

			questionnaire_handleHideOnSelect (hideByQuestion[questionId], "none");
		}
	}
}

/* ------------------------------------------------------------------------------------------------------------	*/
/* questionnaire_handleHideOnSelect																				*/
/* ------------------------------------------------------------------------------------------------------------	*/
function questionnaire_handleHideOnSelect (hideOnSelect, display)
{
	if (hideOnSelect == "") return;

	var ids = hideOnSelect.split(",");

	for (var i = 0; i < ids.length; i++)
	{
		var minId = ids[i];
		var maxId = ids[i];

		if (minId.indexOf("-") != -1)
		{
			rangeIds = minId.split("-");

			minId = rangeIds[0];
			maxId = rangeIds[1];
		}

		for (var j = minId; parseInt(j) <= parseInt(maxId); j++)
		{
			oQuestion = document.getElementById ("questionnaireQuestion" + j);

			if (oQuestion != undefined)
			{
				oQuestion.style.display = display;

				if (display == "none")	
				{
					// clean selected radio
					var radioId = 1;

					while ((oRadio = document.getElementById("radio" + j + "_" + radioId)) != undefined)
					{
						oRadio.checked = false;
						radioId++
					}

					// clean input fields
					if ((oInput = document.getElementById("answerText" + j)) != undefined)
					{
						oInput.value = "";
						oInput.disabled = true;
					}

					if ((oInput = document.getElementById("answer" + j)) != undefined)
						oInput.value = "";
				}
			}
		}
	}
}

function checkMandatory(id)
{
		var oForm = document.getElementById("questionnaireForm");

		var oQuestion = document.getElementById ("questionnaireQuestion" + id);
		if (oQuestion == null || oQuestion.style.display == "")
		{
			if ((oInput = document.getElementById("answer" + id)) != undefined)
			{
				if (oInput.value == "")
				{
					oInput.focus ();

					if (document.getElementById("questionnaireQuestion" + id + "_number").style.display != "")
					{
						alert (tailJS["questionnaire_mandatoryText"] + document.getElementById("questionnaireQuestion" + id + "_number").innerHTML);
					}
					else
					{
						if (prevErrorNo != 0)
						{
							document.getElementById("questionnaireQuestion_text" + prevErrorNo).className = "";
						}
						
						prevErrorNo = id;
						document.getElementById("questionnaireQuestion_text" + prevErrorNo).className = "selected";

						alert (tailJS["questionnaire_mandatoryText2"]);
					}

					return false;
				}
			}
			else if ((oRadio = oForm.elements["answer" + id]) != undefined && oRadio.length > 0)
			{
				var isChecked = false;
	
				for (var r = 0; r < oRadio.length; r++) 
				{
					if (oRadio[r].checked) 
					{
						isChecked = true;
						break;
					}
				}

				if (!isChecked)
				{
					oRadio[0].focus ();
					if (document.getElementById("questionnaireQuestion" + id + "_number").style.display != "")
					{
						alert (tailJS["questionnaire_mandatoryRadio"] + document.getElementById("questionnaireQuestion" + id + "_number").innerHTML);
					}
					else
					{
						if (prevErrorNo != 0)
						{
							document.getElementById("questionnaireQuestion_text" + prevErrorNo).className = "";
						}

						prevErrorNo = id;
						document.getElementById("questionnaireQuestion_text" + prevErrorNo).className = "selected";

						alert (tailJS["questionnaire_mandatoryRadio2"]);
					}

					return false;
				}
			}
			else if (document.getElementById("radio" + id + "_1") != undefined)		// checkbox
			{
				var isChecked = false;
				var a = 1;
				while ((oCheckbox = document.getElementById("radio" + id + "_" + a)) != undefined)
				{
					if (oCheckbox.checked)
					{
						isChecked = true;
						break;
					}
					a++;
				}

				if (!isChecked)
				{
					document.getElementById("radio" + id + "_1").focus ();

					if (document.getElementById("questionnaireQuestion" + id + "_number").style.display != "")
					{
						alert (tailJS["questionnaire_mandatoryRadio"] + document.getElementById("questionnaireQuestion" + id + "_number").innerHTML);
					}
					else
					{
						if (prevErrorNo != 0)
						{
							document.getElementById("questionnaireQuestion_text" + prevErrorNo).className = "";
						}

						prevErrorNo = id;
						document.getElementById("questionnaireQuestion_text" + prevErrorNo).className = "selected";

						alert (tailJS["questionnaire_mandatoryRadio2"]);
					}

					return false;
				}
			}
		}

		return true;
}

var prevErrorNo = 0;

/* ------------------------------------------------------------------------------------------------------------	*/
/* questionnaire_submit																							*/
/* ------------------------------------------------------------------------------------------------------------	*/
function questionnaire_submit (mandatoryQuestions)
{
	var mandatories = mandatoryQuestions.split(",");

	var oForm = document.getElementById("questionnaireForm");

	for (var i = 0; i < mandatories.length; i++)
	{
		var id = mandatories[i];

		if (!checkMandatory(id))
			return false;
	}

	oForm.submit ();

	return false;
}

function questionnairePrev(qid, cnt, fldId)
{
	document.getElementById("question" + qid + "_" + cnt).style.display = "none";

	for (cnt--, qq = document.getElementById("question" + qid + "_" + cnt);
		 qq.getElementsByTagName('div')[0] != undefined && qq.getElementsByTagName('div')[0].style.display == 'none';
	     cnt--, qq = document.getElementById("question" + qid + "_" + cnt));

	document.getElementById("question" + qid + "_" + cnt).style.display = "";
}

function questionnaireNext(qid, cnt, isManadory, fldId)
{
	// don't skip mandatory fields
	if (fldId != null && isManadory && checkMandatory(fldId) == false)
		return;

	document.getElementById("question" + qid + "_" + cnt).style.display = "none";

	for (cnt++, qq = document.getElementById("question" + qid + "_" + cnt);
		 qq.getElementsByTagName('div')[0] != undefined && qq.getElementsByTagName('div')[0].style.display == 'none';
	     cnt++, qq = document.getElementById("question" + qid + "_" + cnt));
	
/*	for (cnt++, fldId++;
		 document.getElementById ("questionnaireQuestion" + fldId) != undefined && document.getElementById ("questionnaireQuestion" + fldId).style.display == 'none';
	     cnt++, fldId++);*/

	document.getElementById("question" + qid + "_" + cnt).style.display = "";
}
