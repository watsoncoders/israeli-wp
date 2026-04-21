/* Fixed by pablo rotem - Removed legacy xmlObj that caused crashes */

jQuery.fn.exists = function() { return this.length > 0; }

/* -------------------------------------------------------------------------------------------------------------------- */
/* overwrite alert function	- uses alertify																			*/
/* -------------------------------------------------------------------------------------------------------------------- */
window.alert = function(str) 
{
	if (typeof alertify !== 'undefined') {
		alertify.set({labels: { ok : ""}, buttonFocus: "none"});
		alertify.alert (alertHtml(str));

		jQuery("#alertify-cover").click (function () {
			jQuery("#alertify-cover").remove ();
			jQuery("#alertify").remove ();
			jQuery("#alertify-logs").remove ();
		});
	} else {
		// Fallback if alertify is not loaded
		original_alert(str);
	}
}

var original_alert = window.alert;

function showMsg (str)
{
	alertify.set({labels: { ok : ""}, buttonFocus: "none"});

	var html = "<div class='msgBox'>" +
				  "<div class='msgBoxTop'></div>" +
				  "<div class='msgBoxMsg'>" + str + "</div>" +
		       "</div>";

	alertify.alert (html);

	jQuery("#alertify-cover").click (function () {
		jQuery("#alertify-cover").remove ();
		jQuery("#alertify").remove ();
		jQuery("#alertify-logs").remove ();
	});
}

/* -------------------------------------------------------------------------------------------------------------------- */
/* alertAndFocus																										*/
/* -------------------------------------------------------------------------------------------------------------------- */
function alertAndFocus (str, field)
{
	alertify.set({labels: { ok : ""}, buttonFocus: "none"});

	alertify.alert (alertHtml(str), function (e) { 
		if (e) { 
			if (jQuery(field).exists()) jQuery(field).focus (); 
		} 
	});	

	jQuery("#alertify-cover").click (function () {
		jQuery("#alertify-cover").remove ();
		jQuery("#alertify").remove ();
		jQuery("#alertify-logs").remove ();
		if (jQuery(field).exists()) jQuery(field).focus ();
	});
}

/* -------------------------------------------------------------------------------------------------------------------- */
/* alertHtml																											*/
/* -------------------------------------------------------------------------------------------------------------------- */
function alertHtml (str)
{
	return "<div class='alertBox'>" +
				"<div class='alertBoxTop'></div>" +
				"<div class='alertBoxMsg'>" + str + "</div>" +
		   "</div>";
}
				

/* -------------------------------------------------------------------------------------------------------------------- */
/* israeli_submitRegisterForm																							*/
/* -------------------------------------------------------------------------------------------------------------------- */
function israeli_submitRegisterForm ()
{
	var oForm = document.getElementById("registerCourseForm");
	if(!oForm) return true;

	if (oForm.email.value == "")
	{
		alertAndFocus ("נא להזין כתובת אימייל", "form#registerCourseForm input#email");
		return false;
	}

	// check birth date
	if (oForm.birthYear.value == "")
	{
		alertAndFocus ("נא לבחור שנת לידה", "form#registerCourseForm select#birthYear");
		return false;
	}

	if (oForm.birthMonth.value == "")
	{
		alertAndFocus ("נא לבחור חודש לידה", "form#registerCourseForm select#birthMonth");
		return false;
	}

	if (oForm.birthDay.value == "")
	{
		alertAndFocus ("נא לבחור יום לידה", "form#registerCourseForm select#birthDay");
		return false;
	}

	var birthDate = israeli_getBirthDate (oForm.birthYear.value, oForm.birthMonth.value, oForm.birthDay.value);

	if (birthDate == "")
	{
		alertAndFocus ("תאריך לידה לא תקין", "form#registerCourseForm select#birthYear");
		return false;
	}

	oForm.birthDate.value = birthDate;

	// Validation for mandatory checkboxes
	if (!oForm.check1.checked || !oForm.check2.checked)
	{
		alertAndFocus ("יש לאשר את התנאים והצהרת האמת", "form#registerCourseForm input#check1");
		return false;
	}

    // handle Google reCaptcha v3
    if (typeof grecaptcha !== 'undefined') {
        grecaptcha.ready(function() {
            grecaptcha.execute('6LcLgo8UAAAAAByozjMx1guuOU15JdqAqfCQrxbT', {action: 'submit_register'})
            .then(function(token) {
                jQuery('#registerCourseForm').prepend('<input type="hidden" name="g-recaptcha-response" value="' + token + '">');
                jQuery('#registerCourseForm').prepend('<input type="hidden" name="action" value="submit_register">');
                // submit form now
                jQuery('#registerCourseForm').removeAttr('onsubmit').submit();
            });
        });
        return false; // Prevent default submit until token is ready
    }

	return true;
}

/* -------------------------------------------------------------------------------------------------------------------- */
/* israeli_getBirthDate																								*/
/* -------------------------------------------------------------------------------------------------------------------- */
function israeli_getBirthDate (year, month, day)
{
	var birthDate = "";
	if (year != "" || month != "" || day != "")
	{
		var dayobj = new Date(year, month-1, day);
		if ((dayobj.getMonth()+1 != month) || (dayobj.getDate() != day) || (dayobj.getFullYear() != year))
		{
			return false;
		}
		birthDate = year + "-" + month + "-" + day;
	}
	return birthDate;
}

/* -------------------------------------------------------------------------------------------------------------------- */
/* israeli_submitContactForm (Simple Version for WP)																	*/
/* -------------------------------------------------------------------------------------------------------------------- */
function israeli_submitContactForm ()
{
    if (typeof grecaptcha !== 'undefined') {
        grecaptcha.ready(function() {
            grecaptcha.execute('6LcLgo8UAAAAAByozjMx1guuOU15JdqAqfCQrxbT', {action: 'submit_contact'})
            .then(function(token) {
                jQuery('#contactForm').prepend('<input type="hidden" name="g-recaptcha-response" value="' + token + '">');
                jQuery('#contactForm').removeAttr('onsubmit').submit();
            });
        });
        return false;
    }
	return true;
}