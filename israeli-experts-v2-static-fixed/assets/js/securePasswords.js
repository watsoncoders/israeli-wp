
function securePasswords_check(usernameFld, passwordFld, oldPasswordFld, afterFunc)
{
	if (usernameFld.value.length < 8)
	{
		alert("אורך שם המשתמש צריך להכיל לפחות 8 תווים");
		return;
	}		
	if (usernameFld.value == passwordFld.value)
	{
		alert("שם המשתמש והסיסמא צריכים להיות שונים");
		return;
	}		
	if (usernameFld.value.indexOf(passwordFld.value) != -1)
	{
		alert("אסור ששם המשתמש יכיל בתוכו את הסיסמא");
		return;
	}		
	if (passwordFld.value.indexOf(usernameFld.value) != -1)
	{
		alert("אסור שהסיסמא תכיל בתוכה את שם המשתמש");
		return;
	}		

	if (!securePasswords_checkPassword(passwordFld))
			return;

	afterFunc();
}

function securePasswords_checkPassword(passwordFld)
{
	if (passwordFld.value.length < 10) // this condition is repeated below
	{
		alert("אורך הסיסמא צריך להכיל לפחות 10 תווים");
		return false;
	}		
	if (!passwordFld.value.match(/^((?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])|(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%&\/=?_.,:;\\-])|(?=.*[a-z])(?=.*[0-9])(?=.*[!@#$%&\/=?_.,:;\\-])|(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%&\/=?_.,:;\\-])).{10,}$/))
	{
		alert("הסיסמא צריכה להכיל תווים מלפחות 3 קבוצות מבין הבאות: אותיות גדולות באנגלית, אותיות קטנות באנגלית, ספרות וסימנים מיוחדים");
		return false;
	}

	return true;
}
