<?php

if (file_exists(dirname(__FILE__)."/private.php"))
	include dirname(__FILE__)."/private.php";
else
	include dirname(__FILE__)."/../private.php";

include "privateCommon.php";

extract($_POST, EXTR_SKIP);
extract($_GET, EXTR_SKIP);
extract($_COOKIE, EXTR_SKIP);
/*
function safeToEval($val)
{
		return $val;
		if (strpos($val, chr(10)) !== false)
				return ""; // do not permit newline
		$meta = array("$", "{", "}", "[", "]", "`", ";");
		$escaped = array("&#36;", "&#123;", "&#125;", "&#91;", "&#93;", "&#96;", "&#59;");
		return str_replace($meta, $escaped, addslashes($val));
}

ob_start();

// [21/4/2008 AMIR] Making GET prior to COOKIE
foreach ($_COOKIE as $key => $val)
{
	if (preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $key))
	{
		$val	= str_replace("'", "\'", stripslashes($val));
		eval("$$key = '".safeToEval($val)."';");
	}
}

foreach ($_GET as $key => $val)
{
	if (preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $key))
	{
		$val	= str_replace("'", "\'", $val);
		eval("$$key = '".safeToEval($val)."';");
	}
}

foreach ($_POST as $key => $val)
{
	if (preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $key) && is_string($val))
	{
//		$sapi_type = php_sapi_name();
//		if (substr($sapi_type, 0, 3) == 'cgi')
		$val	= str_replace("'", "\'", stripslashes($val));

		eval("$$key = '".safeToEval($val)."';");
	}
}

ob_end_clean();
*/
$queriesLog = array();

/* -------------------------------------------------------------------------------------------------------------------- */
/* commonConnectToDB																									*/
/* -------------------------------------------------------------------------------------------------------------------- */
function commonConnectToDB ()
{
	global $privateDbUser, $privateDbPass, $privateDbName, $privateDbHostname, $isUTF8;

	$GLOBALS['conn'] = mysqli_connect($privateDbHostname, $privateDbUser, $privateDbPass, $privateDbName) or die(mysqli_connect_errno()); 

	if ($isUTF8 == 1)
		mysqli_query($GLOBALS['conn'], "set names 'utf8mb4'") or die(mysqli_error($GLOBALS['conn']));
	else
		header('Content-Type: text/html; charset=windows-1255');

	mysqli_query($GLOBALS['conn'], "SET SESSION sql_mode = ''") or die(mysqli_error($GLOBALS['conn']));

	return $GLOBALS['conn'];
}

/* -------------------------------------------------------------------------------------------------------------------- */
/* commonDisconnect																										*/
/* -------------------------------------------------------------------------------------------------------------------- */
function commonDisconnect ($mysqlHandle = null)
{
	if ($mysqlHandle == null)
		$mysqlHandle = $GLOBALS['conn'];
	
	mysqli_close ($mysqlHandle);
}

/* -------------------------------------------------------------------------------------------------------------------- */
/* commonDoQuery																										*/
/* -------------------------------------------------------------------------------------------------------------------- */
function commonDoQuery ($queryStr)
{
	global $isDebug, $queriesLog;

	if ($isDebug)
		$startTime = microtime(true);

	try 
	{
		$result = mysqli_query ($GLOBALS['conn'], $queryStr);
	} 
	catch (mysqli_sql_exception $e) 
	{
		// from php 8.1 mysql error throws an exception
		$result = false;
	}
	
	if ($isDebug)
	{
		$key = round(100000 * (microtime(true) - $startTime));
		$queriesLog[$key] = $queryStr;
	}

	if ($result === false && isset($isDebug) && $isDebug == "1")
	{
		$url = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

		$trace 	 	= var_export (debug_backtrace(), true);
		$post  	 	= var_export ($_POST, true);
		$get   	 	= var_export ($_GET, true);
		$cookies 	= var_export ($_COOKIE, true);

		$sqlError	= mysqli_error($GLOBALS['conn']);

		// report an error
		$msg	= "<html dir='ltr'>
					<head></head>
					<body style='text-align:left'>
						<strong>Error: </strong>$sqlError<br/><br/>
					  	<strong>Sql: </strong>$queryStr<br/>
						<strong>Site: </strong><a href='http://$url'>$url</a><br/><br/>
						<strong>Referer: </strong>$_COOKIE[cookie_referer]<br/><br/>
						<strong>Back Trace: </strong><br/>$trace<br/><br/> 
						<strong>Post: </strong><br/>$post<br/><br/>
						<strong>Get: </strong><br/>$get 
						<strong>Cookies: </strong><br/>$cookies  
					</body>
				   </html>";

		//commonSendHtmlEmail ("Interuse", "info@interuse.co.il", "liat@interuse.com", "Site Sql Error", $msg);
	}

	return $result;
}

/* -------------------------------------------------------------------------------------------------------------------- */
/* commonQuery_fetchRow																									*/
/* -------------------------------------------------------------------------------------------------------------------- */
function commonQuery_fetchRow ($result, $mode = MYSQLI_BOTH)
{
	return mysqli_fetch_array($result, $mode);
}

/* -------------------------------------------------------------------------------------------------------------------- */
/* commonQuery_numRows																									*/
/* -------------------------------------------------------------------------------------------------------------------- */
function commonQuery_numRows ($result)
{
	return mysqli_num_rows($result);
}

/* -------------------------------------------------------------------------------------------------------------------- */
/* commonQuery_affectedRows																								*/
/* -------------------------------------------------------------------------------------------------------------------- */
function commonQuery_affectedRows ()
{
	return mysqli_affected_rows($GLOBALS['conn']);
}

/* -------------------------------------------------------------------------------------------------------------------- */
/* commonQuery_dataSeek																									*/
/* -------------------------------------------------------------------------------------------------------------------- */
function commonQuery_dataSeek ($result, $from)
{
	return mysqli_data_seek($result, $from);
}

/* -------------------------------------------------------------------------------------------------------------------- */
/* commonQuery_escapeStr																								*/
/* -------------------------------------------------------------------------------------------------------------------- */
function commonQuery_escapeStr ($result)
{
	return mysqli_real_escape_string($GLOBALS['conn'], $result);
}

/* -------------------------------------------------------------------------------------------------------------------- */
/* commonQuery_lastInsertId																								*/
/* -------------------------------------------------------------------------------------------------------------------- */
function commonQuery_insertId ()
{
	return mysqli_insert_id($GLOBALS['conn']);
}

/* -------------------------------------------------------------------------------------------------------------------- */
/* commonCData																											*/
/* -------------------------------------------------------------------------------------------------------------------- */
function commonCData ($str)
{
	return "<![CDATA[$str]]>";
}


/* -------------------------------------------------------------------------------------------------------------------- */
/* commonGetClubConfig																									*/
/* -------------------------------------------------------------------------------------------------------------------- */
function commonGetClubConfig()
{
	static $clubRowCache = "";
	
	if ($clubRowCache)
		return $clubRowCache;
	
	$sql     = "select * from clubConfig";
	$result  = commonDoQuery($sql);
	$clubRowCache = commonQuery_fetchRow($result);
	return $clubRowCache;
}

/* -------------------------------------------------------------------------------------------------------------------- */
/* commonGetClubMemberRow																								*/
/* -------------------------------------------------------------------------------------------------------------------- */
function commonGetClubMemberRow($useCache = true)
{
	global $password, $username, $sessionCode;
	static $lastSeesionCond = "", $lastReturnedRow;

	$username		= commonQuery_escapeStr($username);
	$password		= commonQuery_escapeStr($password);
	$sessionCode	= commonQuery_escapeStr($sessionCode);
	
	$configRow = commonGetClubConfig();

	if ($configRow['withSessions'] == "1")
	{
		if ($sessionCode == $lastSeesionCond && $useCache)
				return $lastReturnedRow;
		else
				$lastSeesionCond = $sessionCode;

		// get memberId
		$sql	= "select memberId, isSuper from sessions where code = '$sessionCode'";
		$result	= commonDoQuery($sql);

		if (commonQuery_numRows($result) == 0)
		{
			$lastReturnedRow = null;
			return null;
		}

		$row	= commonQuery_fetchRow($result);

		$isSuper	= $row['isSuper'];

		$cond = "id = $row[memberId]";
	}
	else
	{
		if ($username == "" || $password == "")
			return null;

		if ($username."~!~!~".$password == $lastSeesionCond)
				return $lastReturnedRow;
		else
				$lastSeesionCond = $username."~!~!~".$password;

		$cond     = "password = '$password' and username = '$username'";
	}

	if (!$isSuper)
	{
		// check expire time only for not super enter
		$cond .= " and (expireTime = 0 or expireTime > now())";
	}

	$sql     = "select cm.*, cm2.*,
		   			   cm.memberType as memberType, cm2.memberType as memberType_web2,
					   cm.extraData1 as extraData1, cm2.extraData1 as extraData1_web2,
					   cm.extraData2 as extraData2, cm2.extraData2 as extraData2_web2,
					   cm.extraData3 as extraData3, cm2.extraData3 as extraData3_web2,
					   cm.extraData4 as extraData4, cm2.extraData4 as extraData4_web2,
					   cm.extraData5 as extraData5, cm2.extraData5 as extraData5_web2,
					   cm.extraData6 as extraData6, cm2.extraData6 as extraData6_web2,
					   cm.extraData7 as extraData7, cm2.extraData7 as extraData7_web2,
					   cm.extraData8 as extraData8, cm2.extraData8 as extraData8_web2,
					   cm.extraData9 as extraData9, cm2.extraData9 as extraData9_web2,
					   cm.extraData10 as extraData10, cm2.extraData10 as extraData10_web2
				from clubMembers as cm left join clubMembersWeb2 as cm2 on id=memberId
				where $cond";

	$result  = commonDoQuery($sql);
	$row 	 = commonQuery_fetchRow($result); 
	$lastReturnedRow = $row;

	return $row;
}

/* -------------------------------------------------------------------------------------------------------------------- */
/* commonValidMember																									*/
/* -------------------------------------------------------------------------------------------------------------------- */
function commonValidMember ()
{
	global $password, $username;

	$row = commonGetClubMemberRow ();

	return ($row != null);
}

/* -------------------------------------------------------------------------------------------------------------------- */
/* commonValidXml																										*/
/* -------------------------------------------------------------------------------------------------------------------- */
function commonValidXml ($text, $removeBR = false)
{
	$text = str_replace(chr(153),"{TM}",$text);
	$text = str_replace(chr(150),"-",$text);

	$text = stripslashes($text);
	return commonCData(commonEncode($text));
}

/* -------------------------------------------------------------------------------------------------------------------- */
/* commonEncode																											*/
/* -------------------------------------------------------------------------------------------------------------------- */
function commonEncode ($text)
{
	return $text;
}

/* -------------------------------------------------------------------------------------------------------------------- */
/* commonDecode																											*/
/* -------------------------------------------------------------------------------------------------------------------- */
function commonDecode ($text)
{
	return $text;
}

if ( !function_exists( 'quoted_printable_encode' ) ) {
function quoted_printable_encode($str, $wrap=true)
{
    $return = '';
    $iL = strlen($str);
    for($i=0; $i<$iL; $i++)
    {
        $char = $str[$i];
        if(ctype_print($char) && !ctype_punct($char)) $return .= $char;
        else $return .= sprintf('=%02X', ord($char));
    }
    return ($wrap === true)
        ? wordwrap($return, 74, " =\n")
        : $return;
}
}

function commonPhpMail($toEmail, $subject, $message)
{
		$siteUrl	= $_SERVER['SERVER_NAME'] . preg_replace("/\/(\w+)\.php/","",strtolower($_SERVER['SCRIPT_NAME']));
		if ($siteUrl[0] == '/') // run from cron
		{
			$domain		= commonGetLayoutSwitchHtml ("domain");
			$siteUrl 	= substr($domain, strpos($domain, '.')+1);
		}
		$fromEmail 	= "donotreply@$siteUrl";
		$globalRow = commonGetGlobalParams("HEB");
		$siteName	= $globalRow['siteName'];
		
		commonSendHtmlEmail($siteName, $fromEmail, $toEmail, $subject, nl2br($message));
}

function commonSendHtmlEmail($fromName, $fromEmail, $toEmail, $subject, $message, $lang = "HEB", $bcc = "", $cc = "")
{
	if (function_exists("privateCommon_SendMail"))
	{
			privateCommon_SendMail($fromName, $fromEmail, $toEmail, $subject, $message, $lang, $bcc, $cc);
			return;
	}

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,"https://www.i-bos.co.il/sendMailBySMTP.php");
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
					'fromName'		=> $fromName,
					'fromEmail'		=> $fromEmail,
					'toEmail'		=> $toEmail,
					'subject'		=> $subject,
					'message'		=> $message,
					'bcc'			=> $bcc,
					'cc'			=> $cc
				)));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$server_output = curl_exec($ch);
	curl_close ($ch);

	return;

	/*
	global $isUTF8;

	switch ($lang)
	{
		case "HEB": 
			if ($isUTF8)
				$charset = "utf-8";
			else
				$charset = "windows-1255"; 
			break;
		default: $charset = "ISO-8859-1"; break;
	}

	$encodedSubject = "=?$charset?B?".base64_encode($subject)."?=";
	$encodedMsg = quoted_printable_encode($message);
	$encodedFrom = $fromEmail;
	if ($fromName)
		$encodedFrom = "=?$charset?B?".base64_encode($fromName)."?="." <".$fromEmail.">";

	// Header lines should end with \r\n but some servers do not comply with the standards

	if ($bcc != "")
		$bcc = "Bcc: $bcc\n";

	$headers = "From: $encodedFrom\n" . $bcc .
			   "Reply-To: $encodedFrom\n" .
			   "Content-type: text/html; charset=$charset\n".
			   "MIME-Version: 1.0\n".
			   "X-Mailer: PHP/" . phpversion() . "\n".
			   "Content-Transfer-Encoding: quoted-printable\n\n";

	mail($toEmail,$encodedSubject,$encodedMsg,$headers);
	*/
}

/* -------------------------------------------------------------------------------------------------------------------- */
/* commonSendEmailFromWebsite																							*/
/* -------------------------------------------------------------------------------------------------------------------- */
function commonSendEmailFromWebsite($toEmail, $subject, $message, $lang)
{
		if ($lang == "") $lang = commonQuery_escapeStr($_COOKIE['cookie_lang']);
		if ($lang == "") $lang = "HEB";

		$globalRow = commonGetGlobalParams($lang);

		switch ($lang)
		{
			case "HEB" :
			case "HB2" :
				$dir	= "rtl";
				$html	= "<html dir='$dir'>";
				$align  = "right";
				break;

			case "ENG" :
				$dir	= "ltr";
				$html	= "<html dir='$dir'>";
				$align  = "left";
				break;
		}

		$siteUrl	= $_SERVER['SERVER_NAME'] . preg_replace("/\/(\w+)\.php/","",strtolower($_SERVER['SCRIPT_NAME']));
		$fromEmail 	= "donotreply@$siteUrl";
		$siteName	= $globalRow['siteName'];

		$message 	= str_replace("#siteName#", $siteName, $message);
		$message 	= str_replace("#siteUrl#", $siteUrl, $message);
		
		$topTr 		= "";
		$bottomTr	= "";

		if (file_exists("loadedFiles/emailForm_top.png"))
			$topTr	= "<tr id='emailTopRow'><td align='center'><img src='$siteUrl/loadedFiles/emailForm_top.png'></td></tr>\n";

		if (file_exists("loadedFiles/emailForm_bottom.png"))
			$bottomTr	= "<tr id='emailBottomRow'><td align='center'><img src='$siteUrl/loadedFiles/emailForm_bottom.png'></td></tr>\n";

		$html  = "<head>\n" .
					"<link rel='stylesheet' href='$siteUrl/css/layouts.css' type='text/css'/>\n" .
				 "</head>\n" .
				 "<body style='margin-top:5px;background:white;text-align:$align;'>\n" .
					"<table cellpadding='0' cellspacing='0' border='0' dir='$dir' width='610px' align='center' id='sendPasswordTbl'>\n" .
					$topTr .
					"<tr>\n" .
						"<td align='center'>\n" .
							"<table cellpadding='0' cellspacing='0' border='0' dir='$dir' align='center' id='sendPasswordInTbl'>\n" .
							"<tr>\n" .
								"<td align='center'>\n" .
						   			"$message\n".
								"</td>\n" .
							"</tr>\n" .
							"</table>\n" .
						"</td>\n" .
					"</tr>\n" .
					$bottomTr .
					"</table>\n" .
				"</body></html>";

		commonSendHtmlEmail ($siteName, $fromEmail, $toEmail, $subject, $html);
}

/* -------------------------------------------------------------------------------------------------------------------- */
/* commonSendEmailAsNewsletter																							*/
/* -------------------------------------------------------------------------------------------------------------------- */
function commonSendEmailAsNewsletter($toEmail, $subject, $message, $lang)
{
		$globalRow	= commonGetGlobalParams($lang);

		$html		= commonGetLayoutHtml ($globalRow['emailLayout'], $lang);
		$html		= commonGetHtmlAfterSwitches($html, $lang);

		$subject	= str_replace("#siteName#", 		$globalRow['siteName'], $subject);
		$html		= str_replace("#emailSubject#" ,	$subject, 				$html);
		$html		= str_replace("#emailContent#" ,	$message,				$html);
		$html 		= str_replace("#siteName#", 		$globalRow['siteName'], $html);

		$siteEmail = $globalRow['siteEmail'];
		if ($p = strpos($siteEmail, ';'))
				$siteEmail = substr($siteEmail, 0, $p);

		commonSendHtmlEmail ($globalRow['siteName'], $siteEmail, $toEmail, $subject, $html);
}

/* -------------------------------------------------------------------------------------------------------------------- */
/* commonGetIP																		 									*/
/* -------------------------------------------------------------------------------------------------------------------- */
function commonGetIP ()
{
	if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
	{
        $ip = getenv("HTTP_CLIENT_IP");
	}
	else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
	{
		$ip = getenv("HTTP_X_FORWARDED_FOR");
	}
    else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
	{
        $ip = getenv("REMOTE_ADDR");
	}
    else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && 
			 strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
	{
		$ip = $_SERVER['REMOTE_ADDR'];
	}
    else
	{
    	$ip = "";
	}
    return($ip);
}

/* -------------------------------------------------------------------------------------------------------------------- */
/* commonRandomCode																										*/
/* -------------------------------------------------------------------------------------------------------------------- */
function commonRandomCode ($len = 25, $exceptChars = "")
{
	$codeLength = 0;
	$code = "";
	
	$codeEnd = "";
	if ($len >= 15)
	{
		$codeEnd = substr(time(), 2);
		$len -= strlen($codeEnd);
	}

	// generate confirm code
	mt_srand ((double) microtime() * 1000000);
	while ($codeLength < $len)
	{ 
		$c = chr(mt_rand (0,255)); 

		if (strpos($exceptChars, $c) !== false)
				continue;

		if (preg_match('/^[A-Z0-9]$/', $c))
		{
				$code .= $c;
				$codeLength++;
		}
	} 	

	return $code.$codeEnd;
}

/* -------------------------------------------------------------------------------------------------------------------- */
/* commonGetDay																											*/
/* -------------------------------------------------------------------------------------------------------------------- */
function commonGetDay ($day, $lang)
{
	global $TIAL;

	switch ($day)
	{
		case "0"	: return $TIAL['sunday'];
		case "1"	: return $TIAL['monday'];
		case "2"	: return $TIAL['tuesday'];
		case "3"	: return $TIAL['wednesday'];
		case "4"	: return $TIAL['thursday'];
		case "5"	: return $TIAL['friday'];
		case "6"	: return $TIAL['saturday'];
	}

	return "";
}

/* -------------------------------------------------------------------------------------------------------------------- */
/* commonGetShortDay																									*/
/* -------------------------------------------------------------------------------------------------------------------- */
function commonGetShortDay ($day)
{
	global $TIAL;

	switch ($day)
	{
		case "0"	: return $TIAL['sun'];
		case "1"	: return $TIAL['mon'];
		case "2"	: return $TIAL['tue'];
		case "3"	: return $TIAL['wed'];
		case "4"	: return $TIAL['thu'];
		case "5"	: return $TIAL['fri'];
		case "6"	: return $TIAL['sat'];
	}

	return "";
}

/* -------------------------------------------------------------------------------------------------------------------- */
/* commonGetMonth																										*/
/* -------------------------------------------------------------------------------------------------------------------- */
function commonGetMonth ($month)
{
	global $TIAL;

	switch ($month)
	{
		case "1"	: return $TIAL['january'];
		case "2"	: return $TIAL['february'];
		case "3"	: return $TIAL['march'];
		case "4"	: return $TIAL['april'];
		case "5"	: return $TIAL['may'];
		case "6"	: return $TIAL['june'];
		case "7"	: return $TIAL['july'];
		case "8"	: return $TIAL['august'];
		case "9"	: return $TIAL['september'];
		case "10"	: return $TIAL['october'];
		case "11"	: return $TIAL['november'];
		case "12"	: return $TIAL['december'];
	}

	return "";
}

/* -------------------------------------------------------------------------------------------------------------------- */
/* commonDateByLang																										*/
/* -------------------------------------------------------------------------------------------------------------------- */
function commonDateByLang ($date, $lang)
{
	global $TIAL;

	if ($lang == "HEB" || $lang == "HB2" || $lang == "ARB")
	{
		$date = str_replace("January", 		$TIAL['january'], 	$date);
		$date = str_replace("February", 	$TIAL['february'],	$date);
		$date = str_replace("March", 		$TIAL['march'],		$date);
		$date = str_replace("April", 		$TIAL['april'],		$date);
		$date = str_replace("May", 			$TIAL['may'],		$date);
		$date = str_replace("June", 		$TIAL['june'],		$date);
		$date = str_replace("July", 		$TIAL['july'],		$date);
		$date = str_replace("August", 		$TIAL['august'],	$date);
		$date = str_replace("September", 	$TIAL['september'],	$date);
		$date = str_replace("October", 		$TIAL['october'],	$date);
		$date = str_replace("November", 	$TIAL['november'],	$date);
		$date = str_replace("December", 	$TIAL['december'],	$date);
		$date = str_replace("Sunday", 		$TIAL['sun-day'], 	$date);
		$date = str_replace("Sun", 			$TIAL['sun'], 		$date);
		$date = str_replace("Monday", 		$TIAL['mon-day'], 	$date);
		$date = str_replace("Mon", 			$TIAL['mon'], 		$date);
		$date = str_replace("Tuesday", 		$TIAL['tues-day'],	$date);
		$date = str_replace("Tue", 			$TIAL['tue'], 		$date);
		$date = str_replace("Wednesday", 	$TIAL['wednes-day'],$date);
		$date = str_replace("Wed", 			$TIAL['wed'], 		$date);
		$date = str_replace("Thursday",		$TIAL['thurs-day'],	$date);
		$date = str_replace("Thu", 			$TIAL['thu'], 		$date);
		$date = str_replace("Friday", 		$TIAL['fri-day'],	$date);
		$date = str_replace("Fri", 			$TIAL['fri'], 		$date);
		$date = str_replace("Saturday",		$TIAL['satur-day'], $date);
		$date = str_replace("Sat", 			$TIAL['sat'], 		$date);
	}

	return $date;
}

/* -------------------------------------------------------------------------------------------------------------------- */
/* commonGetWebTwoDate																									*/
/* -------------------------------------------------------------------------------------------------------------------- */
function commonGetWebTwoDate($date, $lang, $fullFormat = "j/m/Y")
{
		global $TIAL;

		$strDate	= strtotime($date);
  	 	$today		= date("F j");
        	$yesterday	= date("F j", strtotime("yesterday"));

		$newDate	= date("F j", $strDate);
   
		$timediff = time() - $strDate;
		if ($timediff > 0 && $timediff < 60*60)
			if ($lang == 'HEB' || $lang == 'HB2' || $lang == 'ARB')
				$newDate = $TIAL['before']." ".ceil(abs($timediff) / 60)." ".$TIAL['minutes'];
			else
				$newDate = ceil(abs($timediff) / 60)." ".$TIAL['minutes']." ".$TIAL['before'];
		else
			if ($newDate == $today)
					$newDate = $TIAL['today']." ".date("H:i", $strDate);
	   		elseif ($newDate == $yesterday)
				$newDate = $TIAL['yesterday']." ".date("H:i", $strDate);
			else
				$newDate = date($fullFormat, $strDate);

		return $newDate;
}

/* -------------------------------------------------------------------------------------------------------------------- */
/* commonGetDbDate																										*/
/* -------------------------------------------------------------------------------------------------------------------- */
function commonGetDbDate ($datetime)
{
	if ($datetime == "") return "";

	if (strlen($datetime) < 11)
		$datetime = "$datetime 00:00";

	$datetime = preg_replace("/^([0-9]{1,2})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,4})\s([0-9]{1,2}):([0-9]{2})/", "\\2/\\1/\\3 \\4:\\5", 
						     $datetime);

	$datetime = strtotime($datetime);
	$datetime = date("Y-m-d H:i:00", $datetime);

	return ($datetime);
}

/* -------------------------------------------------------------------------------------------------------------------- */
/* commonCutText																										*/
/* -------------------------------------------------------------------------------------------------------------------- */
function commonCutText ($text, $len, $ending="...")
{
	global $isUTF8;

	if ($isUTF8)
	{
		if (mb_strlen($text, "utf8") > $len)
		{
			$text = mb_substr($text,0,$len, "utf8") ;
	
			$matchpoint = mb_strrpos($text, " ", 0, "utf8");
			if ($matchpoint === false)
				$text = mb_substr($text,0,$len, "utf8") . $ending;
			else
				$text = mb_substr($text,0,$matchpoint, "utf8") . $ending;
		}
	}
	else
	{
		if (strlen($text) > $len)
		{
			$text = substr($text,0,$len) ;
	
			$matchpoint = strrpos($text, " ");
			if ($matchpoint === false)
				$text = substr($text,0,$len-strlen($ending)). $ending;
			else
				$text = substr($text,0,$matchpoint). $ending;
		}
	}
	return $text;
}

/* -------------------------------------------------------------------------------------------------------------------- */
/* commonGetEnumValueText																								*/
/* -------------------------------------------------------------------------------------------------------------------- */
function commonGetEnumValueText ($enumId, $valueId, $language)
{
	$valueId = trim($valueId);

	if ($enumId == "")  return "";
	if ($valueId == "") return "";

	$valueText = "";

	$valueIds = explode(" ", $valueId);

	for ($i=0; $i<count($valueIds); $i++)
	{
		$valueIds[$i] = addslashes($valueIds[$i]);

		$queryStr	= "select text from enumsValues, enumsValues_byLang 
			   		   where id = valueId and language = '$language'
					   and   enumId = $enumId
					   and 	 id = '$valueIds[$i]'";
		$result	= commonDoQuery($queryStr);
		$row	= commonQuery_fetchRow($result);
	
		$valueText .= " " . stripslashes($row[0]);
	}

	return trim($valueText);
}

/* -------------------------------------------------------------------------------------------------------------------- */
/* commonGetEnumValuesArray																								*/
/* -------------------------------------------------------------------------------------------------------------------- */
function commonGetEnumValuesArray ($enumId, $language)
{
	$valueText = array();

	$queryStr	= "select text from enumsValues, enumsValues_byLang 
		   		   where id = valueId and language = '$language'
				   and   enumId = $enumId order by pos";
	$result	= commonDoQuery($queryStr);
	while ($row	= commonQuery_fetchRow($result))
		array_push($valueText, $row['text']);

	return $valueText;
}

/* -------------------------------------------------------------------------------------------------------------------- */
/* commonGetEnumValuesUsableArray																						*/
/* -------------------------------------------------------------------------------------------------------------------- */
function commonGetEnumValuesUsableArray ($enumId, $language)
{
	$valueText = array();

	$queryStr	= "select id, text from enumsValues, enumsValues_byLang 
		   		   where id = valueId and language = '$language'
				   and   enumId = $enumId order by pos";
	$result	= commonDoQuery($queryStr);
	while ($row	= commonQuery_fetchRow($result))
		$valueText[$row['id']] = $row['text'];

	return $valueText;
}

/* -------------------------------------------------------------------------------------------------------------------- */
/* commonGetOptionsFromEnum																								*/
/* -------------------------------------------------------------------------------------------------------------------- */
function commonGetOptionsFromEnum ($enumId, $language, $selected = "", $parentId = "", $orderBy = "pos")
{
	$optionsText = "";

	$queryStr	= "select id, text from enumsValues, enumsValues_byLang 
		   		   where id = valueId and language = '$language'
				   and   enumId = $enumId " . (($parentId !== "") ? " and parentValueId like '$parentId'" : "") . " order by $orderBy";
	$result	= commonDoQuery($queryStr);
	while ($row	= commonQuery_fetchRow($result))
	{
		$optionSelected = "";
		if ($selected == $row['id'] || (is_array($selected) && in_array($row['id'], $selected)))
			$optionSelected = "selected='true'";
		$optionsText .= "<option value='$row[id]' $optionSelected>$row[text]</option>";
	}

	return $optionsText;
}

/* -------------------------------------------------------------------------------------------------------------------- */
/* commonHyperlink																										*/
/* -------------------------------------------------------------------------------------------------------------------- */
function commonHyperlink (&$text, $target = "_new")
{
	// found at http://stackoverflow.com/questions/1960461/convert-plain-text-hyperlinks-into-html-hyperlinks-in-php
	return preg_replace('@(http(s)?)?(://)?(([a-zA-Z])([-\w]+\.)+([^\s\.]+[^\s]*)+[^,.\s])@', "<a href=\"http$2://$4\" target=\"$target\">$0</a>", $text);
}

/* -------------------------------------------------------------------------------------------------------------------- */
/* commonGetBoxContent																									*/
/* -------------------------------------------------------------------------------------------------------------------- */
function commonGetBoxContent ($boxName, $lang = "HEB")
{
	static $allBoxesCache = null;
	
	if (isset($allBoxesCache))
		return $allBoxesCache[$boxName][$lang];

	$sql			= "select boxName, language, content from boxes, boxes_byLang where boxes.id = boxes_byLang.boxId";
	$result			= commonDoQuery($sql);
	while ($boxRow = commonQuery_fetchRow($result))
		$allBoxesCache[$boxRow['boxName']][$boxRow['language']] = stripslashes($boxRow['content']);

	return $allBoxesCache[$boxName][$lang];
}

/* -------------------------------------------------------------------------------------------------------------------- */
/* commonGetLayoutHtml																									*/
/* -------------------------------------------------------------------------------------------------------------------- */
function commonGetLayoutHtml ($layoutId, $lang = "HEB")
{
	$sql	 = "select text from layouts_byLang where layoutId = $layoutId and language = '$lang'";
	$result  = commonDoQuery($sql);
	$row	 = commonQuery_fetchRow($result);

	return stripslashes($row['text']);
}

/* -------------------------------------------------------------------------------------------------------------------- */
/* commonGetallSwitches																									*/
/* -------------------------------------------------------------------------------------------------------------------- */
function commonGetallSwitches ($lang = "HEB")
{
	static $allLayoutSwitchesCache = null;
	
	if (isset($allLayoutSwitchesCache))
		return $allLayoutSwitchesCache[$lang];

	$sql	 = "select text, language, name from layoutSwitches_byLang";
	$result  = commonDoQuery($sql);
	while ($row  = commonQuery_fetchRow($result))
		$allLayoutSwitchesCache[$row['language']][$row['name']] = stripslashes($row['text']);
	
	return $allLayoutSwitchesCache[$lang];
}

/* -------------------------------------------------------------------------------------------------------------------- */
/* commonGetLayoutSwitchHtml																							*/
/* -------------------------------------------------------------------------------------------------------------------- */
function commonGetLayoutSwitchHtml ($switchName, $lang = "HEB")
{
	$allSwitchs = commonGetallSwitches($lang);
	
	return $allSwitchs[$switchName];
}

/* -------------------------------------------------------------------------------------------------------------------- */
/* commonGetHtmlAfterSwitches																							*/
/* -------------------------------------------------------------------------------------------------------------------- */
function commonGetHtmlAfterSwitches($webpagetxt, $lang)
{
	$allSwitchs = commonGetallSwitches($lang);

	while (preg_match("/@([a-zA-Z_0-9]+)@/",$webpagetxt,$regs))
	{
		if (array_key_exists($regs[1], $allSwitchs))
			$webpagetxt = str_replace("@$regs[1]@", $allSwitchs[$regs[1]], $webpagetxt);
		else
			$webpagetxt = str_replace("@$regs[1]@", "", $webpagetxt);
	}
	
	return $webpagetxt;
}

/* -------------------------------------------------------------------------------------------------------------------- */
/* commonVefiticationImage                                                                                              */
/* -------------------------------------------------------------------------------------------------------------------- */
function commonVefiticationImage ($width, $height, $fontHexColor, $bgHexColor, $type = "regular")
{
    $img = ImageCreate($width, $height);

	// font color
	$fontHexColor = str_replace("#", '', $fontHexColor);
        
	$r = hexdec(substr($fontHexColor, 0, 2));
	$g = hexdec(substr($fontHexColor, 2, 2));
	$b = hexdec(substr($fontHexColor, 4, 2)); 

    $fontColor = ImageColorAllocate($img, $r,  $g,  $b);

	// bg color
	$bgHexColor   = str_replace("#", '', $bgHexColor);

	$r = hexdec(substr($bgHexColor, 0, 2));
	$g = hexdec(substr($bgHexColor, 2, 2));
	$b = hexdec(substr($bgHexColor, 4, 2)); 

    $bgColor   = ImageColorAllocate($img, $r, $g, $b);
   
	$maxLen = 6;

	if (strpos($type, "num") !== false || strpos($type, "sayLetters") !== false)
	{
		$typeSplit = explode("_", $type);

		$type	   = $typeSplit[0];

		if (count($typeSplit) == 2)
			$maxLen = $typeSplit[1];
	}

	switch ($type)
	{
		case "num"	: $eregStr = '/^[0-9]$/';
					  break;

		case "sayLetters" : $eregStr = '/^[a-z]$/';
					  break;

		default		: $maxLen = 6;
				  	  $eregStr = '/^[a-z0-9]$/';
	}

    // random string generator
    srand((double)microtime()*1000000);
    $verifyCode = "";
    $codeLength = 0;
        while ($codeLength < $maxLen)
        {
                $c = chr(mt_rand (0,255));

                if (strpos("0o", $c) !== false)
                                continue;

				if ($type == "sayLetters" && strpos($verifyCode, $c) !== false)
						continue;

                if (preg_match($eregStr, $c))
                {
                                $verifyCode .= $c;
                                $codeLength++;
                }
        }

    // fill image with bg color
    ImageFill($img, 0, 0, $bgColor);

    // writes string
 	if (file_exists('arial.ttf'))
 		imagettftext($img, 18, 0, 4, 22, $fontColor, 'arial.ttf', $verifyCode);
 	else
	    ImageString($img, 5, 5, 0, $verifyCode, $fontColor);

    // get file name
    $fileName = "verify_" . date("mdHis")  . "_" . commonRandomCode(2) . ".png";

    ImagePNG($img, "verificationImgs/$fileName");
    ImageDestroy($img);

    // delete old ones
    $dayBefore = date("Y-m-d 00:00:00", strtotime("-1 hours"));
    $sql    = "delete from verificationImgs where insertTime < '$dayBefore'";
    commonDoQuery ($sql);
   
    // delete old files
    $time0 = time();
    $cacheTime = 3600; // 1 hour
    if ($dir = opendir("verificationImgs/"))
    {
        while ($fn = readdir($dir))
        {
            if ($time0 - filemtime("verificationImgs/$fn") >= $cacheTime)
            {
                if (!($fn == '.' or $fn == '..'))
                   unlink("verificationImgs/$fn");   // remove it
            }
          }
          closedir($dir);
    }

    // insert to DB
    $sql    = "insert into verificationImgs (verifyCode, imgFileName, insertTime) values ('$verifyCode', '$fileName', now())";
    commonDoQuery ($sql);

	// now - find the id
    $sql    = "select id from verificationImgs where verifyCode = '$verifyCode' and imgFileName = '$fileName'";
    $result = commonDoQuery($sql);
    $row    = commonQuery_fetchRow($result);
    $imgId  = $row[0];

	return array($imgId, $fileName);
}
	
/* -------------------------------------------------------------------------------------------------------------------- */
/* commonVefiticationHtml                                                                                               */
/* -------------------------------------------------------------------------------------------------------------------- */
function commonVefiticationHtml ($formName, $width, $height, $fontHexColor, $bgHexColor, $type = "regular")
{
	global $TIAL;

	$result   = commonVefiticationImage ($width, $height, $fontHexColor, $bgHexColor, $type);

	$imgId 	  = $result[0];
	$fileName = $result[1];

	$html = "<table><tr><td>";
	if (strpos($type, "sayLetters") !== false)
			$html .= "<img src='designFiles/captchaSpeaker.gif' title='".$TIAL['readTheLetters']."' alt='".$TIAL['readTheLetters']."'
				     	onclick='sayCaptchaLetters($imgId)' /> ";

	$html .= "<img id='${formName}_verificationImg' src='verificationImgs/$fileName' alt='Verification Image' />
		  </td><td>&nbsp;
			  <input id='${formName}_verification' name='verification' type='text' dir='ltr' maxLength='6' />
			  <input type='hidden' id='${formName}_verificationImgId' name='verificationImgId' value='$imgId' />
			  <input type='hidden' id='${formName}_verificationParms' value='$width, $height, \"$fontHexColor\", \"$bgHexColor\", \"$type\"' />
		  </td></tr></table>";

	return $html;
}

/* -------------------------------------------------------------------------------------------------------------------- */
/* commonGetDimensionDetails																							*/
/* -------------------------------------------------------------------------------------------------------------------- */
function commonGetDimensionDetails ($dimensionId)
{
	if ($dimensionId == 0)
	{
		$picWidth  		= 0;
		$picHeight 		= 0;
		$bgColor   		= "#FFFFFF";
		$forceSize 		= 0;
		$watermarkFile	= "";
		$allowCrop		= false;
		$quality		= 98;
	}
	else if ($dimensionId != "")
	{
		$queryStr   	= "select * from dimensions where id = $dimensionId";
		$result			= commonDoQuery ($queryStr);
		$row			= commonQuery_fetchRow ($result);

		$picWidth 		= $row['width'];
		$picHeight 		= $row['height'];
		$bgColor 		= ($row['color'] ? $row['color'] : "#FFFFFF");
		$forceSize  	= $row['forceSize'];
		$watermarkFile	= $row['watermarkFile'];
		$allowCrop		= $row['allowCrop'];
		$quality		= ($row['quality'] ? $row['quality'] : 98) ;
	}
	else
	{
		$picWidth  		= "200";
		$picHeight 		= "100";
		$bgColor   		= "#FFFFFF";
		$forceSize 		= 0;
		$watermarkFile	= "";
		$allowCrop		= false;
		$quality		= 98;
	}

	return Array($picWidth, $picHeight, $bgColor, $forceSize, $watermarkFile, $allowCrop, $quality);
}

/* -------------------------------------------------------------------------------------------------------------------- */
/* commonInqureSearch																									*/
/* -------------------------------------------------------------------------------------------------------------------- */
function commonInqureSearch ()
{
	global $pageNumber, $startPage, $searchId;

	$searchRow = null;

	if ($searchId != "")
	{
		// check if search exists
		$sql	= "select details from searches where id = $searchId";
		$result	= commonDoQuery($sql);

		if (commonQuery_numRows($result) == 0)
		{
			// reset search
			$searchId = "";
			$pageNumber = 1;
			$startPage	= 1;
		}
		else
		{
			$searchRow = commonQuery_fetchRow($result);
		}
	}

	return $searchRow;

}

/* -------------------------------------------------------------------------------------------------------------------- */
/* commonAddNewSearch																									*/
/* -------------------------------------------------------------------------------------------------------------------- */
function commonAddNewSearch ($details)
{
	$sql	= "select max(id) from searches";
	$result	= commonDoQuery($sql);
	$row	= commonQuery_fetchRow($result);

	$searchId = $row[0] + 1;

	$sql	= "insert into searches (id, insertTime, details) values ($searchId, now(), '$details')";
	commonDoQuery($sql);

	// delete old ones
    $timeBefore = date("Y-m-d 00:00:00", strtotime("-1 days"));
    $sql    = "delete from searches where insertTime < '$timeBefore'";
    commonDoQuery ($sql);
	
	return $searchId;
}

/* -------------------------------------------------------------------------------------------------------------------- */
/* commonFtpConnect																										*/
/* -------------------------------------------------------------------------------------------------------------------- */
function commonFtpConnect ()
{
	global $privateFtpName,$privateFtpPass,$privateFtpHome;

	$connId = ftp_connect("127.0.0.1");

	if ($connId)
	{
		ftp_login($connId, $privateFtpName, $privateFtpPass) or die();
		if ($privateFtpHome != "")
			ftp_chdir($connId, $privateFtpHome);
	}

	return ($connId);
}

/* -------------------------------------------------------------------------------------------------------------------- */
/* commonFtpDisconnect																									*/
/* -------------------------------------------------------------------------------------------------------------------- */
function commonFtpDisconnect ($connId)
{
	ftp_close($connId);
}

$globalS3Bucket	 		= "";

/* -------------------------------------------------------------------------------------------------------------------- */
/* commonS3BucketOpen																									*/
/* -------------------------------------------------------------------------------------------------------------------- */
function commonS3BucketOpen ()
{
	global $privateS3BucketName, $privateS3BucketRegion, $privateS3Credentials; 
	global $globalS3Bucket;

	if ($privateS3BucketName != "" && !$globalS3Bucket)
	{
		require_once 'vendor/autoload.php';

		$globalS3Bucket = new Aws\S3\S3Client(['region'  	 => $privateS3BucketRegion,
											   'version' 	 => 'latest',
											   'credentials' => $privateS3Credentials]);
	}
}

/* --------------------------------------------------------------------------------------------	*/
/* commonS3BucketPut																			*/
/* --------------------------------------------------------------------------------------------	*/
function commonS3BucketPut ($remoteFile, $localFile)
{
	global $globalS3Bucket;
	global $privateS3BucketName;

	$g = fopen($localFile, 'r');

	if (!$g)
		return false;
		
	// save at AWS
	$globalS3Bucket->putObject(['Bucket' => $privateS3BucketName,
		   						'Key'    => $remoteFile,
								'Body'   => $g,
								'ACL'    => 'public-read']);
	
	fclose($g);

	return true;
}

/* --------------------------------------------------------------------------------------------	*/
/* commonS3BucketDelete																			*/
/* --------------------------------------------------------------------------------------------	*/
function commonS3BucketDelete ($file)
{
	global $globalS3Bucket;

	global $privateS3BucketName;
	
	$globalS3Bucket->deleteObject(array('Bucket' => $privateS3BucketName,
										'Key'    => $file));
		
	return true;
}

/* --------------------------------------------------------------------------------------------	*/
/* commonS3BucketFileExists																		*/
/* --------------------------------------------------------------------------------------------	*/
function commonS3BucketFileExists ($file)
{
	global $globalS3Bucket;
	global $privateS3BucketName, $privateS3BucketRegion, $privateS3Credentials; 

	if (!$globalS3Bucket)
	{
		require_once 'vendor/autoload.php';

		$globalS3Bucket = new Aws\S3\S3Client(['region'  	 => $privateS3BucketRegion,
											   'version' 	 => 'latest',
											   'credentials' => $privateS3Credentials]);
	}
	
	return ($globalS3Bucket->doesObjectExist($privateS3BucketName, $file));
}

/* --------------------------------------------------------------------------------------------	*/
/* commonFtpChdir																				*/
/* --------------------------------------------------------------------------------------------	*/
function commonFtpChdir ($connId, $dir)
{
	global $globalS3Bucket;
	global $globalFtpRelativePath;

	global $privateS3BucketName;
	
	if ($privateS3BucketName)
		$globalFtpRelativePath .= $dir."/";
	
	ftp_chdir($connId, $dir);
}

/* -------------------------------------------------------------------------------------------------------------------- */
/* commonAddIbosTag																										*/
/* -------------------------------------------------------------------------------------------------------------------- */
function commonAddIbosTag ($tag)
{
	global $ibosTags;

	if (!in_array($tag, $ibosTags))
	{
		array_push ($ibosTags, $tag);
	}
}

/* -------------------------------------------------------------------------------------------------------------------- */
/* commonReplaceIbosTag																									*/
/* -------------------------------------------------------------------------------------------------------------------- */
function commonReplaceIbosTag ($html, $tag, $value, $ind = 0)
{
	if (strpos($tag, "#display-") !== false)
	{
		$value	= (($value == "") ? " style='display:none' " : "");
	}

	// replace until find divType
	$matchnextpoint   = strpos($html, "divType=", $ind);
	while (1)
	{
		$matchpoint 	  = strpos($html, $tag, $ind);

		if ($matchpoint != false && ($matchpoint < $matchnextpoint || $matchnextpoint == false))
		{
			$html = substr_replace($html, $value, $matchpoint, strlen($tag));
		}
		else
		{
			break;
		}
	}

	return $html;
}

/* -------------------------------------------------------------------------------------------------------------------- */
/* commonCheckUserAgent																									*/
/* -------------------------------------------------------------------------------------------------------------------- */
function commonCheckUserAgent ()
{
	if ($_SERVER['HTTP_USER_AGENT']									!==	""	  &&
		strpos($_SERVER['HTTP_USER_AGENT'], "facebook") 			=== false && 
		strpos($_SERVER['HTTP_USER_AGENT'], "Yahoo! Slurp") 		=== false &&
		strpos($_SERVER['HTTP_USER_AGENT'], "abby") 				=== false &&
		strpos($_SERVER['HTTP_USER_AGENT'], "PycURL") 				=== false &&
		strpos($_SERVER['HTTP_USER_AGENT'], "Twingly Recon") 		=== false &&
		strpos($_SERVER['HTTP_USER_AGENT'], "kmbot")				=== false &&
		strpos($_SERVER['HTTP_USER_AGENT'], "PostRank") 			=== false &&
		strpos($_SERVER['HTTP_USER_AGENT'], "Voyager") 				=== false &&
		strpos($_SERVER['HTTP_USER_AGENT'], "JS-Kit") 				=== false &&
		strpos($_SERVER['HTTP_USER_AGENT'], "AppEngine-Google")		=== false &&
		strpos($_SERVER['HTTP_USER_AGENT'], "Twitterbot") 			=== false &&
		strpos($_SERVER['HTTP_USER_AGENT'], "Butterfly") 			=== false &&
		strpos($_SERVER['HTTP_USER_AGENT'], "mxbot") 				=== false &&
		strpos($_SERVER['HTTP_USER_AGENT'], "spbot") 				=== false &&
		strpos($_SERVER['HTTP_USER_AGENT'], "YandexBot") 			=== false &&
		strpos($_SERVER['HTTP_USER_AGENT'], "Ask Jeeves") 			=== false &&
		strpos($_SERVER['HTTP_USER_AGENT'], "Julpanbot") 			=== false &&
		strpos($_SERVER['HTTP_USER_AGENT'], "msnbot") 				=== false &&
		strpos($_SERVER['HTTP_USER_AGENT'], "unspecified.mail")		=== false &&
		strpos($_SERVER['HTTP_USER_AGENT'], "AddThis.com robot")	=== false &&
		strpos($_SERVER['HTTP_USER_AGENT'], "AdsBot")				=== false &&
		strpos($_SERVER['HTTP_USER_AGENT'], "TwengaBot")			=== false &&
		strpos($_SERVER['HTTP_USER_AGENT'], "ia_archiver")			=== false &&
		strpos($_SERVER['HTTP_USER_AGENT'], "bingbot")				=== false &&
		strpos($_SERVER['HTTP_USER_AGENT'], "Hailoobot")			=== false &&
		strpos($_SERVER['HTTP_USER_AGENT'], "Crawler")				=== false &&
		strpos($_SERVER['HTTP_USER_AGENT'], "LinkedInBot")			=== false &&
		strpos($_SERVER['HTTP_USER_AGENT'], "Purebot")				=== false &&
		strpos($_SERVER['HTTP_USER_AGENT'], "Jaxified Bot")			=== false &&
		strpos($_SERVER['HTTP_USER_AGENT'], "MJ12bot")				=== false &&
		strpos($_SERVER['HTTP_USER_AGENT'], "DotBot")				=== false &&
		strpos($_SERVER['HTTP_USER_AGENT'], "SolomonoBot")			=== false &&
		strpos($_SERVER['HTTP_USER_AGENT'], "SiteBot")				=== false &&
		strpos($_SERVER['HTTP_USER_AGENT'], "lssbot")				=== false &&
		strpos($_SERVER['HTTP_USER_AGENT'], "ezooms.bot")			=== false &&
		strpos($_SERVER['HTTP_USER_AGENT'], "Googlebot") 			=== false)
		return true;
	else
		return false;
}

/* -------------------------------------------------------------------------------------------------------------------- */
/* commonIsMobile																										*/
/* -------------------------------------------------------------------------------------------------------------------- */
function commonIsMobile ()
{
	global $setMobile;

	if (isset($setMobile))
	{
		setcookie ("setMobile", $setMobile, 0);

		return ($setMobile == "1");
	}

	return commonIsMobileAgent();
}

/* -------------------------------------------------------------------------------------------------------------------- */
/* commonIsMobileAgent																									*/
/* -------------------------------------------------------------------------------------------------------------------- */
function commonIsMobileAgent ()
{
	$op = strtolower($_SERVER['HTTP_X_OPERAMINI_PHONE']);
	$ua = strtolower($_SERVER['HTTP_USER_AGENT']);
	$ac = strtolower($_SERVER['HTTP_ACCEPT']);

	$isMobile = strpos($ac, 'application/vnd.wap.xhtml+xml') !== false || 
					   $op != '' 									   || 
					   strpos($ua, 'sony') 					 !== false || 
					   strpos($ua, 'symbian') 				 !== false || 
					   strpos($ua, 'nokia') 				 !== false || 
					   strpos($ua, 'samsung') 				 !== false || 
					   strpos($ua, 'mobile') 				 !== false || 
					   strpos($ua, 'windows ce') 			 !== false || 
					   strpos($ua, 'epoc') 					 !== false || 
					   strpos($ua, 'opera mini') 			 !== false || 
					   strpos($ua, 'nitro')  				 !== false || 
					   strpos($ua, 'j2me') 				 	 !== false || 
					   strpos($ua, 'midp-') 				 !== false || 
					   strpos($ua, 'cldc-') 				 !== false || 
					   strpos($ua, 'netfront') 				 !== false || 
					   strpos($ua, 'mot') 				 	 !== false || 
					   strpos($ua, 'up.browser') 			 !== false || 
					   strpos($ua, 'up.link') 				 !== false || 
					   strpos($ua, 'audiovox') 				 !== false || 
					   strpos($ua, 'blackberry') 			 !== false || 
					   strpos($ua, 'ericsson,') 			 !== false || 
					   strpos($ua, 'panasonic') 			 !== false || 
					   strpos($ua, 'philips') 				 !== false || 
					   strpos($ua, 'sanyo') 				 !== false || 
					   strpos($ua, 'sharp') 				 !== false || 
					   strpos($ua, 'sie-') 					 !== false || 
					   strpos($ua, 'portalmmm') 			 !== false || 
					   strpos($ua, 'blazer') 				 !== false || 
					   strpos($ua, 'avantgo') 				 !== false || 
					   strpos($ua, 'danger') 				 !== false || 
					   strpos($ua, 'palm') 				 	 !== false || 
					   strpos($ua, 'series60') 				 !== false || 
					   strpos($ua, 'palmsource') 			 !== false || 
					   strpos($ua, 'pocketpc') 				 !== false || 
					   strpos($ua, 'smartphone') 			 !== false || 
					   strpos($ua, 'rover') 				 !== false || 
					   strpos($ua, 'ipaq') 				 	 !== false || 
					   strpos($ua, 'au-mic,') 				 !== false || 
					   strpos($ua, 'alcatel') 				 !== false || 
					   strpos($ua, 'ericy') 				 !== false || 
					   strpos($ua, 'up.link') 				 !== false || 
					   strpos($ua, 'vodafone/') 			 !== false || 
					   strpos($ua, 'wap1.') 				 !== false || 
					   strpos($ua, 'wap2.') 				 !== false;

	return $isMobile;
}

/* -------------------------------------------------------------------------------------------------------------------- */
/* commonDailyCache																										*/
/* -------------------------------------------------------------------------------------------------------------------- */
function commonDailyCache($name, $queryStr, &$cacheSize, $from = NULL, $limit = NULL, $compress = true)
{
		$sql = "select * from dailyCacheHead where name='$name' order by id asc";
		$res = commonDoQuery($sql);
		$num = commonQuery_numRows($res);
		$cacheRow = commonQuery_fetchRow($res);

		$today = date("Y-m-d");
		if ($num == 0 || ($num == 1 && $cacheRow['since'] != $today))
		{
				// build new cache
				// if $num==2 then new cache is just being built

				// 1. prepare new cache
				commonDoQuery("INSERT INTO dailyCacheHead (id, name) values(NULL, '$name')");
				$res = commonDoQuery("select max(id) from dailyCacheHead");
				$row = commonQuery_fetchRow($res);
				$newCacheId = $row[0];
				commonDoQuery("CREATE TABLE dailyCacheBody$newCacheId (pos INT NOT NULL , line TEXT NOT NULL , PRIMARY KEY ( pos ))");

				// 2. perform the query that needs to be cached
				$res = commonDoQuery($queryStr);

				// 3. store the results of that query in the cache
				for ($pos=1; $row = commonQuery_fetchRow($res); $pos++)
				{
						$serRow = serialize($row);
						if ($compress)
								$serRow = gzdeflate($serRow);
						commonDoQuery("insert into dailyCacheBody$newCacheId (pos, line) values($pos, '".addslashes($serRow)."')");
				}

				// 4. update the cache head
				$sql = "replace dailyCacheHead (id, name, since, size) values($newCacheId, '$name', '$today', ".commonQuery_numRows($res).")";
				commonDoQuery($sql);

				// 5. delete old cache lines
				if ($num != 0)
				{
					commonDoQuery("drop table dailyCacheBody$cacheRow[id]");
					commonDoQuery("delete from dailyCacheHead where id=$cacheRow[id]");
				}

				// 6. read the new cache head in order to continue
				$res = commonDoQuery("select * from dailyCacheHead where name='$name'");
				$cacheRow = commonQuery_fetchRow($res);
		}

		// retrieve from cache
		$cacheSize = $cacheRow['size'];
		$sql = "select line from dailyCacheBody$cacheRow[id] ";
		if ($limit)
				$sql .= "where pos > $from and pos <= ".($from + $limit);
		$sql .= " order by pos asc";

		$res = commonDoQuery($sql);

		return $res;
}

/* -------------------------------------------------------------------------------------------------------------------- */
/* commonDailyCacheFetchRow																								*/
/* -------------------------------------------------------------------------------------------------------------------- */
function commonDailyCacheFetchRow($res, $compress = true)
{
		$row = commonQuery_fetchRow($res);

		if (!$row)
				return false; // No more records

		$serRow = current($row); 
		if ($compress)
				$serRow = gzinflate($serRow);

		return unserialize($serRow);
}

/* -------------------------------------------------------------------------------------------------------------------- */
/* commonCheckLogin																										*/
/* -------------------------------------------------------------------------------------------------------------------- */
function commonCheckLogin ($memberRow, $configRow, $isSuper = false)
{
	if ($memberRow['status'] == "disabled")
	{
		return "disabledMember";
	}
	else
	{
		if ($configRow['confirmRegistration'] == "1" && 
			($memberRow['status'] != "active" || ($memberRow['expireTime'] != "0000-00-00 00:00:00" && 
												  strtotime($memberRow['expireTime']) < strtotime("now"))))
			 return "needToVerfied";

		if ($memberRow['superMember'] == 0 && $memberRow['expireTime'] != "0000-00-00 00:00:00" && strtotime($memberRow['expireTime']) < strtotime("now"))
			 return "expiredMember";
	}	

	if ($configRow['maxCookiesPerMember']   && 
		$memberRow['superMember'] == 0 		&&	// not super member (no limited)
		!$isSuper							&&  // not super password
		(!function_exists("commonMembersWaiver") || !commonMembersWaiver($memberRow)))
	{
		// check cookie
		global $compCookie;

//		echo "latestCookies = $memberRow[latestCookies]<br/>compCookie = $compCookie";
//		exit;

		if (!$compCookie || strpos($memberRow['latestCookies'], $compCookie) === false) // login from a new computer
		{
			$maxCookies	= $configRow['maxCookiesPerMember'];

			if ($memberRow['maxCookies'] != "")
				$maxCookies = $memberRow['maxCookies'];		// overwrite max cookies from member details

			if (substr_count($memberRow['latestCookies'], ',') >= $maxCookies)
			{
				// check if we can switch cookies
				$maxSwitch	= $configRow['maxSwitchCookiesPerMember'];

				if ($memberRow['maxSwitchCookies'] != "")
					$maxSwitch = $memberRow['maxSwitchCookies'];

				if ($maxSwitch == "") $maxSwitch = 0;
						
				if ($maxSwitch == 0)
					return "exceedLoginCookies";

				// remove first-in cookie
				$maxSwitch--;

				$latest	= explode(",", $memberRow['latestCookies']);
				unset($latest[1]);
				$memberRow['latestCookies'] = join(",",$latest);

				$sql	= "update clubMembers set maxSwitchCookies = $maxSwitch, latestCookies = '$memberRow[latestCookies]'
					       where id = $memberRow[id]";
				commonDoQuery($sql);
			}
		}
	}

	if (function_exists("privateCommon_checkLogin"))
	{
		return privateCommon_checkLogin ($memberRow, $configRow, $isSuper);
	}

	return "none";
}

/* -------------------------------------------------------------------------------------------------------------------- */
/* commonDoLogin																										*/
/* -------------------------------------------------------------------------------------------------------------------- */
function commonDoLogin($memberRow, $configRow, $rememberMe, $isSuper = false)
{
	$status = commonCheckLogin ($memberRow, $configRow, $isSuper);

	if ($status != "none")
	{
		if ($status == "exceedLoginCookies")
		{
			if (function_exists("commonExceedLoginCookies"))
				return commonExceedLoginCookies($memberRow);
		}

		return $status;
	}

	// ----------------------------------------------------------------------------------------------------------------

	if (!$isSuper)
	{
		$loginIP	= commonGetIP();
		$sql 		= "insert into clubMembersLogins (memberId, loginIP) values ($memberRow[id], '$loginIP')";
		commonDoQuery($sql);
	}

	if ($configRow['maxCookiesPerMember']   && 
		$memberRow['superMember'] == 0 		&&	// not super member (no limited)
		!$isSuper							&&  // not super password
		(!function_exists("commonMembersWaiver") || !commonMembersWaiver($memberRow)))
	{
		global $compCookie;

		if ($compCookie == null || $compCookie == "")
		{
			$compCookie	= commonRandomCode (15) . "-" . date("dmyhi");
		
			setcookie ("compCookie", $compCookie, ['expires' => time()+60*60*24*2000, 'path' => '/', 'samesite' => 'None', 'secure' => true]);
		}

		if (strpos($memberRow['latestCookies'], $compCookie) === false) // login from a new computer
		{
			$sql	= "update clubMembers set latestCookies = concat(latestCookies, ',', '$compCookie') where id = $memberRow[id]";
			commonDoQuery($sql);
		}
	}

	// send a cookie if allowed	
	// ----------------------------------------------------------------------------------------------------------------
	$exp = 0;
	if ($configRow['allowRememberPassword'] && $rememberMe == "on")
	{
		$exp = time()+60*60*24*$configRow['passwordExpiredDays'];
	}

	if ($configRow['withSessions'] == "1")
	{
		// delete old sessions
		$sql	= "delete from sessions where creationTime < curdate() - interval $configRow[passwordExpiredDays] day";
		commonDoQuery ($sql);

		$maxSessions	= $configRow['maxSessionsPerMember'];

		if ($memberRow['maxSessions'] != 0)
			$maxSessions = $memberRow['maxSessions'];		// overwrite max sessions from member details

		if ($configRow['maxSessionsPerMember'] && 
		    $memberRow['superMember'] == 0 	   &&	// not super member (no limited)
			!$isSuper						   &&   // not super password
			(!function_exists("commonMembersWaiver") || !commonMembersWaiver($memberRow)))
		{
			$sql	= "select id from sessions where memberId = $memberRow[id] order by id desc limit ".($maxSessions-1).", 1";
			$res	= commonDoQuery($sql);

			if (commonQuery_numRows($res) > 0)
			{
				$row	= commonQuery_fetchRow($res);
				$sql	= "delete from sessions where memberId = $memberRow[id] and id <= $row[id]";
				commonDoQuery ($sql);
			}
		}

		// create new session
		$code 	= commonRandomCode(50);

		$memberId	= $memberRow['id'];

		$super		= ($isSuper) ? "1" : "0";

		// add new session for the member
		$sql  	= "insert into sessions (code, memberId, creationTime, lastCheck, isSuper) values('$code', $memberId, now(), now(), $super)";
		commonDoQuery ($sql);

		setcookie("sessionCode", $code, $exp, '/');
	}
	else
	{
		setcookie("username", $memberRow['username'], $exp, '/');
		setcookie("password", $memberRow['password'], $exp, '/');
	}
	
	// delete surfer code and reset it by member verify code
	// ----------------------------------------------------------------------------------------------------------------
	setcookie ("surferCode", "", time() - 3600, '/');
	setcookie ("surferCode", $memberRow['verifyCode'],  $exp, '/');

	require_once("shopCommon.php");
	shopCommon_setCartMembership($memberRow['id']);

	if (function_exists("commonDoAfterLogin"))
	{
		commonDoAfterLogin ($memberRow);
	}

	return "none";
}

/* -------------------------------------------------------------------------------------------------------------------- */
/* commonSanitize																										*/
/* -------------------------------------------------------------------------------------------------------------------- */
function commonSanitize($string)
{
	// make file name fit Linux filesystem
		
    $strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "=", "+", "[", "{", "]",
                   "}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;",
                   ",", "<", ">", "/", "?");
    $clean = trim(str_replace($strip, "_", strip_tags($string)));
    $clean = preg_replace('/\s+/', "_", $clean);
    return $clean;
}

/* -------------------------------------------------------------------------------------------------------------------- */
/* commonGetSiteName																									*/
/* -------------------------------------------------------------------------------------------------------------------- */
function commonGetSiteName ($lang)
{
	$row		= commonGetGlobalParams($lang);

	return ($row['siteName']);
}

/* -------------------------------------------------------------------------------------------------------------------- */
/* commonGetGlobalParams																								*/
/* -------------------------------------------------------------------------------------------------------------------- */
function commonGetGlobalParams ($lang)
{
	static $globalParamsCache = array();
	
	if (isset($globalParamsCache[$lang]))
		return $globalParamsCache[$lang];
	
	$sql 	= "select * from globalParms, globalParms_byLang where language='$lang'";
	$result = commonDoQuery($sql);
	$globalParamsCache[$lang] = commonQuery_fetchRow($result);

	return $globalParamsCache[$lang];
}

/* -------------------------------------------------------------------------------------------------------------------- */
/* commonResizePic																										*/
/* -------------------------------------------------------------------------------------------------------------------- */
function commonResizePic($origFile, $destFile, $dimensionId)
{
	list ($newW, $newH, $bgColor, $forceSize, $watermarkFile, $allowCrop, $quality) = commonGetDimensionDetails($dimensionId);
	
	list($width_orig, $height_orig, $imageType) = getimagesize($origFile);

	if ($newW == 0 && $newH == 0)
	{
		echo "New Image dimensions are all zeros";
		return false;
	}
	$Rcolor = hexdec(substr($bgColor,1,2));
	$Gcolor = hexdec(substr($bgColor,3,2));
	$Bcolor = hexdec(substr($bgColor,5,2));

	switch ($imageType) {
			case IMAGETYPE_JPEG:
				ini_set ('gd.jpeg_ignore_warning', 1);
				$image = @imagecreatefromjpeg($origFile);
				if (!$image)
					$image= imagecreatefromstring(file_get_contents($origFile));
			   	break;
			case IMAGETYPE_GIF:
				$image = imagecreatefromgif($origFile);
				break;
			case IMAGETYPE_PNG:
				$origImage = imagecreatefrompng($origFile);
				// replace transparency by bgColor
				$image = imagecreatetruecolor($width_orig, $height_orig);
				imagefill($image, 0, 0, imagecolorallocate($image, $Rcolor, $Gcolor, $Bcolor));
				imagealphablending($image, TRUE);
				imagecopy($image, $origImage, 0, 0, 0, 0, $width_orig, $height_orig);
				imagedestroy($origImage);
				break;
			case IMAGETYPE_BMP:
				$image = imagecreatefrombmp($origFile);
				break;
			case IMAGETYPE_WEBP:
				$image = imagecreatefromwebp($origFile);
				break;
	}
	if (!$image) {
		echo "image file $origFile is not recognized";
		return false;
	}

	// Fix orientation
	if (is_callable("exif_read_data"))
	{
	    $exif = exif_read_data($origFile);

		if (!empty($exif['Orientation']))
		{
			switch ($exif['Orientation'])
			{
				case 3:
					$image = imagerotate($image, 180, 0);
					break;
				case 6:
					$image = imagerotate($image, -90, 0);
					list($width_orig, $height_orig) = array($height_orig, $width_orig);
					break;
				case 8:
					$image = imagerotate($image, 90, 0);
					list($width_orig, $height_orig) = array($height_orig, $width_orig);
					break;
			}
		}
	}

	// one freedom degree
	if ($newW == 0)
		$newW = round(1.0 * $newH * $width_orig / $height_orig);
	if ($newH == 0)
		$newH = round(1.0 * $newW * $height_orig / $width_orig);

	if ($newH >= $height_orig && $newW >= $width_orig) { // no need to shrink
		$image_r = $image;
		$crop_x = ($width_orig - $newW) / 2;
		$crop_y = ($height_orig - $newH) / 2;
	}
	else if ($forceSize)
	{
		$image_r = imagecreatetruecolor($newW, $newH);
		$resamp = imagecopyresampled($image_r, $image, 0, 0, 0, 0, $newW, $newH, $width_orig, $height_orig);
		if (! $resamp) {
			echo "Image resampling failed";
			return false;
		}
		$crop_x = 0;
		$crop_y = 0;
	}
	else if ($newW >= $width_orig || ( $newH / $height_orig <= $newW / $width_orig)) { // resize by width
		$width_fake = round(($newH / $height_orig) * $width_orig);
		if ($allowCrop)
		{
			$height2Crop = round(($height_orig - ($width_orig / $newW * $newH)) / 2);
			imagecopy($image, $image, 0, 0, 0, 0, $width_orig, $height_orig-2*$height2Crop);// take top part
			$crop_x = 0;
			$crop_y = 0;
			$width_fake = $newW;
			$height_orig -= 2*$height2Crop;
		} else {
			$crop_x = ($width_fake - $newW) / 2;
			$crop_y = 0;
		}
		$image_r = imagecreatetruecolor($width_fake, $newH);
		$resamp = imagecopyresampled($image_r, $image, 0, 0, 0, 0, $width_fake, $newH, $width_orig, $height_orig);
		if (! $resamp) {
			echo "Image resampling failed";
			return false;
		}
	}
	else if ($newH >= $height_orig || ( $newW / $width_orig < $newH / $height_orig)) { // resize by height
		$height_fake = round(($newW / $width_orig) * $height_orig);
		if ($allowCrop)
		{
			$width2Crop = round(($width_orig - ($height_orig / $newH * $newW)) / 2);
			imagecopy($image, $image, 0, 0, $width2Crop, 0, $width_orig-$width2Crop, $height_orig);
			$crop_x = 0;
			$crop_y = 0;
			$height_fake = $newH;
			$width_orig -= 2*$width2Crop;
		} else {
			$crop_x = 0;
			$crop_y = ($height_fake - $newH) / 2;
		}
		$image_r = imagecreatetruecolor($newW, $height_fake);
		$resamp = imagecopyresampled($image_r, $image, 0, 0, 0, 0, $newW, $height_fake, $width_orig, $height_orig);
		if (! $resamp) {
			echo "Image resampling failed";
			return false;
		}
	}

	// Crop
	$image_c = imagecreatetruecolor($newW, $newH);
	imagecopy($image_c, $image_r, 0, 0, $crop_x, $crop_y, $newW, $newH);

	// Set Background Color
	if ( $crop_x < 0 || $crop_y < 0)
	{
	   $bgc = imagecolorallocate( $image_c, $Rcolor, $Gcolor, $Bcolor );
	   //fill the background with white (not sure why it has to be in this order)
	   $absX = abs($crop_x);
	   $absY = abs($crop_y);
	   imagefilledpolygon($image_c, array(0,0,$absX,0,$absX,$newH-1,0,$newH-1), 4, $bgc);
	   imagefilledpolygon($image_c, array($newW-1,0,$newW-1-$absX,0,$newW-1-$absX,$newH-1,$newW-1,$newH-1), 4, $bgc);
	   imagefilledpolygon($image_c, array(0,0,0,$absY,$newW-1,$absY,$newW-1,0), 4, $bgc);
	   imagefilledpolygon($image_c, array(0,$newH-1,0,$newH-1-$absY,$newW-1,$newH-1-$absY,$newW-1,$newH-1), 4, $bgc);
	}

	// Output according to extenstion
	$ext = strtolower(substr($destFile, strrpos($destFile, '.')+1));
	switch ($ext)
	{
		case 'jpg':
		case 'jpeg':
			imagejpeg($image_c, $destFile, $quality);
			break;
		case 'png':
			imagepng($image_c, $destFile);
			break;
		case 'webp':
			imagewebp($image_c, $destFile, $quality);
			break;
	}

	return true;
}
?>
