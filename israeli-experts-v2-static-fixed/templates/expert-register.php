<?php
/**
 * Template Name: טופס הרשמה למאגר (Fixed & Full)
 * Description: טופס הרשמה מקורי עם תיקוני שמירה, טעינת נתונים וצ'קבוקסים תקינים
 * Author: pablo rotem
 */

defined('ABSPATH') || exit;

// =================================================================
// == 1. DATABASE & SESSION SETUP
// =================================================================

global $wpdb; 
if ( ! session_id() ) { session_start(); }

$lang = 'HEB'; 
$courseId = 0; 

// --- LOGIC: CHECK IF USER IS LOGGED IN ---
$is_update_mode = false;
$userData = null;
$memberId = 0;
$existing_cats_str = "";

if ( isset($_SESSION['club_logged_in']) && $_SESSION['club_logged_in'] == true ) {
    $is_update_mode = true;
    $current_user_id = $_SESSION['club_user_id'];
    
    // 1. Get Main User Data
    $userData = $wpdb->get_row("
        SELECT * FROM clubMembers 
        LEFT JOIN israeli_experts ON clubMembers.id = israeli_experts.memberId 
        WHERE clubMembers.id = $current_user_id
    ");

    // 2. Get Existing Categories (for the hidden input & tree)
    $cats = $wpdb->get_col("SELECT categoryId FROM categoriesItems WHERE itemId = $current_user_id");
    if ($cats) { 
        $existing_cats_str = "," . implode(",", $cats) . ","; 
    }
}

// --- HELPER FUNCTIONS FOR PRE-FILLING ---

function getPVal($field) {
    global $userData;
    if (isset($_POST[$field])) { return esc_attr($_POST[$field]); }
    if ($userData && isset($userData->$field)) { return esc_attr($userData->$field); }
    return '';
}

function isSel($field, $value) {
    global $userData;
    if (isset($_POST[$field]) && $_POST[$field] == $value) return 'selected';
    if ($userData && isset($userData->$field) && $userData->$field == $value) return 'selected';
    return '';
}

function isChk($field) {
    global $userData;
    if (isset($_POST[$field]) && ($_POST[$field] == '1' || $_POST[$field] == 'on')) return 'checked';
    if ($userData && isset($userData->$field) && ($userData->$field == 1 || $userData->$field == 'true')) return 'checked';
    return '';
}

// =================================================================
// == 2. TRANSLATIONS & CONFIG
// =================================================================

$TIAL = [
    'course_listings' => 'רשימת קורסים',
    'course_registerFor' => 'טופס הרשמה לקורס: ',
    'course_personalDetails' => 'פרטים אישיים', 'paraNum1' => 'א',
    'course_education' => 'פרטי השכלה', 'paraNum4' => 'ד',
    'course_elementary' => 'שם בי"ס יסודי', 'course_place' => 'עיר/מדינה', 'course_noPublish' => 'פרטי בי"ס יסודי<br>לא יופיעו באתר', 'course_years' => 'מס שנות לימוד', 'course_years_short' => 'שנה',
    'course_highschool' => 'שם בי"ס תיכון/מקצועי', 'course_study' => 'מגמה',
    'course_institute' => 'מוסד להשכלה גבוהה', 'course_faculty' => 'פקולטה', 'course_degree' => 'תואר',
    'course_other' => 'פרטים מידע משלים אחר', 'course_totalYears' => 'סה"כ שנות לימוד', 
    'course_instructions' => 'נא לפרט את כל המוסדות והתארים בסדר כרונולוגי.',
];

function commonQuery_escapeStr($str) { return esc_sql($str); }
function commonGetCategoryPath($catId) { global $wpdb; return $wpdb->get_var($wpdb->prepare("SELECT name FROM categories_byLang WHERE categoryId = %d AND language = 'HEB'", intval($catId))) ?: ''; }
function commonRandomCode($length) { return substr(str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/62))), 1, $length); }

/**
 * Core function to handle field updates using $wpdb
 */
function checking($dbField, $dbTable, $title) {
    global $wpdb, $memberId, $mailMsg;
    
    // Checkboxes and hidden fields handling
    if (!isset($_POST[$dbField])) {
        if (strpos($dbField, 'hide') === 0 || strpos($dbField, 'check') === 0 || strpos($dbField, 'experience') === 0) { 
            $safeData = 0; 
        } else { 
            return; // Skip if text field is missing
        }
    } else {
        $safeData = stripslashes($_POST[$dbField]);
    }

    if ($safeData == 'on' || $safeData == 'None' || $safeData === '1') {
        $safeData = 1; $displayVal = 'כן';
    } elseif ($safeData === 0 || $safeData === '0') {
        $safeData = 0; $displayVal = 'לא';
    } else {
        $displayVal = esc_html($safeData);
    }

    if($title != '') { 
        $mailMsg .= "<tr><td>$title</td><td>$displayVal</td></tr>"; 
    }
    
    $whereKey = ($dbTable === 'clubMembers') ? 'id' : 'memberId';
    
    $wpdb->update($dbTable, array($dbField => $safeData), array($whereKey => $memberId));
}

// =================================================================
// == 3. FORM PROCESSING (POST)
// =================================================================
$msg_success = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['firstname'])) {
    
    $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
    $allow_continue = true;
    
    if (!$is_update_mode) {
        $check = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM clubMembers WHERE email = %s", $email));
        if ($check > 0) {
            echo "<script>alert('משתמש זה כבר קיים במערכת (אימייל תפוס)');</script>";
            $allow_continue = false;
        }
    }

    if ($allow_continue) {
        
        if ($is_update_mode) {
            $memberId = $_SESSION['club_user_id'];
        } else {
            $memberId = (int)$wpdb->get_var("SELECT max(id) FROM clubMembers") + 1;
            $pageId = (int)$wpdb->get_var("SELECT max(id) FROM pages") + 1;
            $title = sanitize_text_field(trim($_POST['lastname'] . ' ' . $_POST['firstname']));
            $code = commonRandomCode(25);

            $wpdb->insert('clubMembers', array('id' => $memberId, 'status' => 'new', 'joinTime' => current_time('mysql'), 'verifyCode' => $code, 'memberLanguage' => $lang, 'extraData3' => $courseId, 'extraData4' => 'expert', 'email' => $email));
            $wpdb->insert('israeli_experts', array('memberId' => $memberId, 'memberPageId' => $pageId, 'memberLanguage' => $lang));
            $wpdb->insert('pages', array('id' => $pageId, 'type' => 'specific', 'typeText' => 'expert', 'layoutId' => 4, 'navParentId' => 1));
            $wpdb->insert('pages_byLang', array('pageId' => $pageId, 'language' => $lang, 'title' => $title, 'isReady' => 0));
        }
        
        $mailMsg = "";
        
        // 1. Personal
        checking('firstname', 'clubMembers', 'שם פרטי'); checking('lastname', 'clubMembers', 'שם משפחה');
        checking('fldExtentName', 'israeli_experts', 'תואר'); checking('gender', 'clubMembers', 'מין');
        checking('identityNumber', 'clubMembers', 'ת.ז.');
        
        if(isset($_POST['birthYear'])) {
            $_POST['birthDate'] = $_POST['birthYear']."-".$_POST['birthMonth']."-".$_POST['birthDay'];
            checking('birthDate', 'clubMembers', 'תאריך לידה');
        }
        
        // 2. Contact
        checking('fldDialZone', 'israeli_experts', 'אזור חיוג'); checking('moreDetails', 'israeli_experts', 'מידע משלים');
        checking('address', 'clubMembers', 'רחוב'); checking('streetNo', 'clubMembers', 'מספר בית');
        checking('cellphone', 'clubMembers', 'נייד'); checking('hideCellphone', 'israeli_experts', 'הסתר נייד');
        checking('city', 'clubMembers', 'ישוב'); checking('phone', 'clubMembers', 'טלפון'); checking('hidePhone', 'israeli_experts', 'הסתר טלפון');
        checking('zipcode', 'clubMembers', 'מיקוד'); checking('phone2', 'clubMembers', 'טלפון נוסף'); checking('hidePhone2', 'israeli_experts', 'הסתר טלפון נוסף');
        checking('country', 'clubMembers', 'מדינה'); checking('fax', 'clubMembers', 'פקס'); checking('hideFax', 'israeli_experts', 'הסתר פקס');
        checking('mailAddress', 'israeli_experts', 'כתובת למשלוח דואר'); checking('email', 'clubMembers', 'דוא"ל'); checking('hideEmail', 'israeli_experts', 'הסתר דוא"ל');
        checking('hideAddress', 'israeli_experts', 'הסתר כתובת'); checking('mySite', 'clubMembers', 'אתר אינטרנט');
        
        // 3. Social Media (Fixed Saving)
        checking('linkedinPage', 'israeli_experts', 'לינקדאין');
        checking('skype', 'israeli_experts', 'סקייפ');
        checking('facebookPage', 'israeli_experts', 'פייסבוק');
        checking('twitterPage', 'israeli_experts', 'טוויטר');
        
        // 4. Profession
        checking('fldProfession', 'israeli_experts', 'מקצוע'); checking('fldGeneralLongevity', 'israeli_experts', 'ותק כללי');
        checking('currBiz', 'israeli_experts', 'עיסוק נוכחי'); checking('fldSpecialization', 'israeli_experts', 'התמחות');
        checking('licenseNo', 'israeli_experts', 'מספר רישיון'); checking('experience1', 'israeli_experts', 'חוו"ד לאחרונה');
        checking('experience2', 'israeli_experts', 'פירוט חוו"ד'); checking('experience3', 'israeli_experts', 'עדות בבימ"ש');
        checking('experience4', 'israeli_experts', 'פירוט עדות');
        
        // 5. Work
        checking('workplace', 'israeli_experts', 'מקום עבודה'); checking('workphone', 'israeli_experts', 'טלפון בעבודה');
        checking('fldLongevity', 'israeli_experts', 'ותק בעבודה'); checking('workfax', 'israeli_experts', 'פקס בעבודה');
        checking('workweb', 'israeli_experts', 'אתר עבודה'); checking('workaddress', 'israeli_experts', 'כתובת עבודה');
        checking('workaddress1', 'israeli_experts', 'סניף נוסף 1'); checking('workaddress2', 'israeli_experts', 'סניף נוסף 2');
        
        // 6. Access
        checking('accessBuses', 'israeli_experts', 'אוטובוסים'); checking('accessTrain', 'israeli_experts', 'רכבת');
        checking('accessPark', 'israeli_experts', 'חניה'); checking('accessCoffee', 'israeli_experts', 'קפה');
        checking('accessDisabled', 'israeli_experts', 'נגישות נכים'); checking('accessElevator', 'israeli_experts', 'מעלית');
        
        // 7. Organizations
        checking('org1', 'israeli_experts', 'ארגון 1'); checking('org1link', 'israeli_experts', 'לינק לארגון 1');
        
        // 8. Checks (TERMS - Fixed Saving)
        checking('check1', 'israeli_experts', 'אישור פרטים');
        checking('check2', 'israeli_experts', 'הסכמה לתקנון');
        checking('check3', 'israeli_experts', 'אישור דיוור');
        checking('check4', 'israeli_experts', 'אישור תנאי שימוש');
        checking('check5', 'israeli_experts', 'המצאת תעודות');
        checking('check6', 'israeli_experts', 'אישור אתיקה');
        checking('check7', 'israeli_experts', 'השתתפות בהשתלמויות');
        checking('check8', 'israeli_experts', 'הצהרת עורך דין');

        // 9. Languages (Fixed Saving)
        for ($i=1; $i<=10; $i++) {
            if(isset($_POST['lang'.$i]) || isset($_POST['lang'.$i.'speak'])) {
                checking('lang'.$i, 'israeli_experts', 'שפה '.$i);
                checking('lang'.$i.'speak', 'israeli_experts', 'דיבור');
                checking('lang'.$i.'read', 'israeli_experts', 'קריאה');
                checking('lang'.$i.'write', 'israeli_experts', 'כתיבה');
            }
        }

        // Education
        $_POST['fldStructuredQualifications'] = "";
        if(isset($_POST['elementarySchool'])) {
             $_POST['fldStructuredQualifications'] = $_POST['elementarySchool']."|".$_POST['elementarySchoolCity']."|".$_POST['elementarySchoolYears']."|".
                                                     $_POST['highSchool']."|".$_POST['highSchoolCity']."|".$_POST['highSchoolFaculty']."|".$_POST['schoolYears']."|";
        }
        
        $_POST['fldQualifications'] = "";
        for ($i = 1; isset($_POST['university' . $i]); $i++) {
            $_POST['fldStructuredQualifications'] .= $_POST["university".$i]."|".$_POST["university".$i."City"]."|".$_POST["university".$i."Faculty"]."|".
                                                     $_POST["university".$i."Years"]."|".$_POST["university".$i."Degree"]."|";
            $_POST['fldQualifications'] .= $_POST['university' . $i . 'Degree']; 
        }
        checking('fldStructuredQualifications', 'israeli_experts', 'השכלה');

        // Categories
        if(isset($_POST['catIds']) && !empty($_POST['catIds'])) {
            $newCats = trim(commonQuery_escapeStr($_POST['catIds']), ",");
            if ($newCats) {
                if ($is_update_mode) { $wpdb->delete('categoriesItems', array('itemId' => $memberId)); }
                $pos = 1; $nextCats = ""; 
                foreach(explode(",", $newCats) as $c) {
                    if(intval($c) > 0) {
                        $wpdb->insert('categoriesItems', array('itemId' => $memberId, 'categoryId' => $c, 'type' => 'specific', 'pos' => $pos++));
                        $nextCats .= commonGetCategoryPath($c) . "<br>";
                    }
                }
                $mailMsg .= "<tr><td>קטגוריות</td><td>$nextCats</td></tr>";
            }
        }
        checking('catsExtraDetails', 'israeli_experts', 'בקשת קטגוריה נוספת');

        // Image
        if (isset($_FILES["picFile"]) && is_uploaded_file($_FILES["picFile"]["tmp_name"])) {
            $nameParts = explode('.', $_FILES["picFile"]["name"]); $ext = strtolower(end($nameParts));
            if (in_array($ext, ['jpg', 'gif', 'png', 'bmp', 'jpeg'])) {
                $wp_upload_dir = wp_upload_dir(); $target_dir = $wp_upload_dir['basedir'] . "/membersFiles/";
                if (!file_exists($target_dir)) { wp_mkdir_p($target_dir); }
                $filename = $memberId . "_size1.jpg"; move_uploaded_file($_FILES["picFile"]["tmp_name"], $target_dir . $filename);
                $wpdb->update('clubMembers', array('picFile' => $filename), array('id' => $memberId));
            }
        }

        $posted = base64_encode(serialize($_POST));
        $wpdb->insert('israeli_profileUpdates', array('memberId' => $memberId, 'isNew' => $is_update_mode?0:1, 'posted' => $posted, 'insertTime' => current_time('mysql')));
        $pniyaNo = $wpdb->insert_id;
        $msg_success = "הפרטים נשמרו בהצלחה! מספר פנייה: " . $pniyaNo;
    }
}

// =================================================================
// == 4. HEADER & STYLE
// =================================================================

get_header(); 
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<style>
/* CSS ראשי – מחבר: Pablo Rotem */
body { direction: rtl; text-align: right; background: #fff; font-family: Arial, sans-serif; font-size: 22px; }
.form-main-wrapper { max-width: 800px; margin: 0 auto; background: #fff; padding: 10px; }
.form-main-wrapper .col-md-1, .form-main-wrapper .col-md-2, .form-main-wrapper .col-md-3, .form-main-wrapper .col-md-4, .form-main-wrapper .col-md-5, .form-main-wrapper .col-md-6, .form-main-wrapper .col-md-7, .form-main-wrapper .col-md-8, .form-main-wrapper .col-md-9, .form-main-wrapper .col-md-10, .form-main-wrapper .col-md-11, .form-main-wrapper .col-md-12, .form-main-wrapper .col-sm-1, .form-main-wrapper .col-sm-2, .form-main-wrapper .col-sm-3, .form-main-wrapper .col-sm-4, .form-main-wrapper .col-sm-5, .form-main-wrapper .col-sm-6, .form-main-wrapper .col-sm-7, .form-main-wrapper .col-sm-8, .form-main-wrapper .col-sm-9, .form-main-wrapper .col-sm-10, .form-main-wrapper .col-sm-11, .form-main-wrapper .col-sm-12, .form-main-wrapper .col-xs-1, .form-main-wrapper .col-xs-2, .form-main-wrapper .col-xs-3, .form-main-wrapper .col-xs-4, .form-main-wrapper .col-xs-5, .form-main-wrapper .col-xs-6, .form-main-wrapper .col-xs-7, .form-main-wrapper .col-xs-8, .form-main-wrapper .col-xs-9, .form-main-wrapper .col-xs-10, .form-main-wrapper .col-xs-11, .form-main-wrapper .col-xs-12 { float: right; }

/* === עץ קטגוריות === */
.tree-star .rltdivp { border: 1px solid #FFA801; max-height: 450px; overflow-y: auto; padding: 20px 25px 20px 10px; direction: rtl; background: #ffffff; }
.tree-star ul.DefineTree { list-style: none; margin: 0; padding: 0 30px 0 0; direction: rtl; }
.tree-star ul.DefineTree > li { position: relative; margin-bottom: 25px; font-size: 20px; font-weight: bold; color: #333333; white-space: nowrap; }
.tree-star ul.DefineTree > li > span { display: inline-block; padding-right: 6px; }
.tree-star ul.DefineTree > li > ul { list-style: none; margin: 10px 0 0 0; padding: 0 25px 0 0; position: relative; }
.tree-star ul.DefineTree > li > ul::before { content: ""; position: absolute; top: -8px; bottom: 8px; right: 8px; border-right: 3px solid #FFA801; }
.tree-star ul.DefineTree > li > ul > li { position: relative; margin-bottom: 8px; padding-right: 26px; font-size: 18px; font-weight: normal; white-space: nowrap; }
.tree-star ul.DefineTree > li > ul > li::before { content: ""; position: absolute; top: 0.9em; right: 8px; width: 18px; border-top: 3px solid #FFA801; }
.tree-star .insExtra { display: inline-block; width: 16px; height: 16px; line-height: 14px; text-align: center; border: 0px solid #FFA801; background-color: #ffffff; color: #FFA801; font-size: 18px; cursor: pointer; float: right; margin-left: 6px; box-sizing: border-box; }
.tree-star .insExtra:before { content: "−"; font-weight: bold; }
.tree-star .insExtra.collapsed:before { content: "+"; }
.DefineTree li a, .treeplant li span { color: #000; font-size: 22px; cursor: pointer; }
.tree-star .paraplus { text-decoration: none; color: #333; cursor: pointer; display: inline-block; vertical-align: middle; font-size: 18px; }
.clRmvse { border: 1px solid #c7c7c7; min-height: 380px; padding: 15px 20px; background: #ffffff; }
.clRmvse p { margin: 0 0 6px 0; padding: 0; border: 0; font-size: 18px; font-weight: bold; color: #FFA801; text-decoration: underline; }
.deleteRmv { color: #FFA801; cursor: pointer; margin-left: 4px; vertical-align: middle; font-size: 16px; }
.rformimg { width: 100%; max-width: 40px; margin: 80px auto; display: block; transform: none; }

/* שאר עיצובי הטופס */
.hidden-section { display: none; }
.page3 { font-size: 24px; font-weight: bold; text-align: center; margin-top: 20px; }
.page3line { border-bottom: 3px solid #FFA801; padding-bottom: 10px; margin-bottom: 30px; text-align: center; }
.para { background: #f7f7f7; padding: 10px; border-right: 5px solid #FFA801; font-size: 18px; margin: 20px 0; }
.para-num { position: absolute; right: 8px; top: 8px; font-size: 20px; width: 27px; height: 27px; line-height: 27px; text-align: center; border-radius: 4px; border: 1px solid #000046; background: #ffa801; color: #000046; }
.pagespecialpara { margin-bottom: 30px; padding: 15px; border: 1px solid #eee; overflow: hidden; position: relative; }
.formpara { font-size: 16px; font-weight: bold; color: #333; margin-bottom: 10px; display: block; }
input[type=text], input[type=number], input[type=email], select, textarea { width: 100%; height: 40px; padding: 6px 12px; border: 1px solid #ccc; border-radius: 4px; }
.row-grid { margin-bottom: 15px; }
.fromsent { background: #FFA801; color: white; border: none; padding: 10px 50px; font-size: 20px; margin-top: 20px; }
.paddingZ { padding-left: 0; padding-right: 0; }
.pdht15 { padding-top: 15px; }
.rowpadding { padding-left: 15px; padding-right: 15px; margin-bottom: 15px; }
.rowpaading-1 { padding-left: 15px; padding-right: 15px; margin-bottom: 15px; }
.pdL0 { padding-left: 0 !important; }
.font-icon { color: red; font-size: 10px; position: absolute; top: 10px; left: 25px; z-index: 99; }
.squaredTwo { position: relative; float: right; width: 20px; margin-left: 10px; }
.hide-label-fix { float: right; line-height: 40px; font-size: 14px; margin-top: 0; }
.checkbox-row { min-height: 40px; display: flex; align-items: center; margin-bottom: 10px; }
.wid60 { width: 60% !important; float: right; }
.wid40 { width: 40% !important; float: right; }
.fix-pad { padding-left: 5px; padding-right: 5px; }
.cvvlabel { font-size: 11px; color: #666; white-space: nowrap; line-height: 40px; }
.elementfrm-2 { width: 30%; float: right; line-height: 40px; font-weight: bold; }
.elementfrm-1 { width: 35%; float: right; padding-left: 5px; }
.elementfrm { width: 35%; float: right; }
.registr-para { font-size: 16px; margin-bottom: 10px; color: #333; }
.resigter-margin { margin: 10px 0; font-weight: bold; }
.not-empty { border-color: green; }
.empty { border-color: red; }
.rcontaine { width: 100%; padding: 5px; border: 1px solid #ccc; border-radius: 3px; background-color: #fff; }
.from1detail { font-weight: bold; margin-bottom: 5px; display: block; }
.receipts-block p { margin-bottom: 5px; }
.paddXsZ { padding-left: 0; padding-right: 0; }
.frm-sect { display: inline-block; background-color: #FFA801; color: #fff; padding: 8px 15px; border-radius: 4px; cursor: pointer; font-weight: bold; margin-top: 5px; text-align: center; }
.registr-para-234, .registr-para-2 { font-size: 14px; line-height: 1.6; margin-bottom: 15px; }
.IconPlusEfr { margin-top: 10px; font-size: 18px; }
#addddagain { color: #FFA801; cursor: pointer; margin-left: 10px; }
.deleteEfr { color: red; cursor: pointer; }

/* FIX FOR TERMS CHECKBOXES */
.checkbox-fix-row {
    display: flex;
    align-items: flex-start;
    margin-bottom: 10px;
}
.checkbox-fix-row input[type="checkbox"] {
    width: 25px;
    height: 25px;
    margin-left: 15px;
    flex-shrink: 0;
}
.checkbox-fix-row label {
    font-size: 18px;
    font-weight: normal;
    line-height: 1.4;
    cursor: pointer;
    margin-top: 3px;
}

@media (max-width: 768px) {
    .tree-star .rltdivp { max-height: 350px; padding: 15px 15px 15px 5px; }
    .tree-star ul.DefineTree > li { font-size: 18px; }
    .tree-star ul.DefineTree > li > ul > li { font-size: 16px; }
}
</style>

<div class="container form-main-wrapper">
    
    <?php if($msg_success): ?>
        <div class="alert alert-success text-center"><h3><?php echo $msg_success; ?></h3></div>
        <?php if($is_update_mode): ?> <div class="text-center"><a href="<?php echo home_url(); ?>" class="btn btn-primary">חזרה לדף הבית</a></div> <?php endif; ?>
    <?php else: ?>

    <div class="col-md-12 paddingZ">
        <div class="imgytrt3">
            <?php if($is_update_mode): ?>
                <div style="background:#dff0d8; color:#3c763d; padding:10px; margin-bottom:15px; border:1px solid #d6e9c6; text-align:center;">
                    שלום <b><?php echo getPVal('firstname'); ?></b>, הטופס במצב עריכה.
                </div>
                <h1 class="page3line pagtop">עדכון פרטי מומחה</h1>
            <?php else: ?>
                <p class="page3">טופס הרשמה לקורס</p>
                <h1 class="page3line pagtop">טופס הרשמה למאגר</h1>
            <?php endif; ?>
        </div>
    </div>

    <form id="registerCourseForm" action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="courseName" value="מאגר">
        <input type="hidden" name="courseId" value="0"> 
        <input type="hidden" id="birthDate" name="birthDate" value="<?php echo getPVal('birthDate'); ?>">
        <input type="hidden" id="catIds" name="catIds" value="<?php echo $existing_cats_str; ?>">
        <input type="hidden" id="updating" name="updating" value="<?php echo $is_update_mode ? '1' : '0'; ?>">
        <input type="hidden" name="lang" value="HEB">
        <input type="hidden" id="totalPay" name="totalPay" value="">

        <div class="col-md-12 pagespecialpara br-botom">
            <h2 class="para"><span class="para-num">א</span>פרטים אישיים</h2>
            <div class="contcactfrom">
                <div class="contact-right">
                    <div class="row">
                        <div class="col-md-6 row-grid rowpadding"><i class="fa fa-star font-icon"></i><input name="firstname" id="firstname" value="<?php echo getPVal('firstname'); ?>" placeholder="שם פרטי" required type="text"></div>
                        <div class="col-md-6 row-grid rowpaading-1"><i class="fa fa-star font-icon"></i><input name="lastname" value="<?php echo getPVal('lastname'); ?>" placeholder="שם משפחה" id="lastname" required type="text"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 row-grid rowpadding"><input name="fldExtentName" value="<?php echo getPVal('fldExtentName'); ?>" placeholder="תואר לפני השם" id="fldExtentName" type="text"></div>
                        <div class="col-md-3 row-grid rowpadding"><select name="gender" id="gender" class="option-form"><option value="m" <?php echo isSel('gender','m'); ?>>זכר</option><option value="f" <?php echo isSel('gender','f'); ?>>נקבה</option></select></div>
                        <div class="col-md-6 row-grid rowpaading-1"><input name="identityNumber" value="<?php echo getPVal('identityNumber'); ?>" placeholder="מס’ ת.ז." id="identityNumber" type="text"></div>
                    </div>
                    <?php 
                        $bd = getPVal('birthDate'); $by = ''; $bm = ''; $bdd = '';
                        if($bd) { list($by,$bm,$bdd) = explode('-', $bd); }
                    ?>
                    <div class="row">
                        <div class="col-md-3 row-grid pdht15"><h2 class="formpara">תאריך לידה</h2></div>
                        <div class="col-md-3 row-grid rowpadding pdht15"><select name="birthYear" id="birthYear" class="option-form"><option value="">שנה</option><?php for($i=2007; $i>=1936; $i--) { $sel=($by==$i)?'selected':''; echo "<option value='$i' $sel>$i</option>"; } ?></select></div>
                        <div class="col-md-3 row-grid rowpaading-1 pdht15"><select name="birthMonth" id="birthMonth" class="option-form"><option value="">חודש</option><?php for($i=1;$i<=12;$i++){ $v=str_pad($i,2,"0",STR_PAD_LEFT); $sel=($bm==$v)?'selected':''; echo "<option value='$v' $sel>$v</option>"; } ?></select></div>
                        <div class="col-md-3 row-grid pdht15"><select name="birthDay" id="birthDay" class="option-form"><option value="">יום</option><?php for($i=1; $i<=31; $i++) { $v=str_pad($i,2,"0",STR_PAD_LEFT); $sel=($bdd==$v)?'selected':''; echo "<option value='$v' $sel>$i</option>"; } ?></select></div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 row-grid rowpadding"><h2 class="formpara">אזור השירות</h2></div>
                        <div class="col-md-3 row-grid rowpadding"><select name="fldDialZone" id="fldDialZone" class="option-form"><option value="">אזור חיוג</option><?php foreach(['02','03','04','08','09'] as $z) { $sel=(getPVal('fldDialZone')==$z)?'selected':''; echo "<option value='$z' $sel>$z</option>"; } ?></select></div>
                        <div class="col-md-6 row-grid rowpaading-1"><input name="moreDetails" value="<?php echo getPVal('moreDetails'); ?>" id="moreDetails" placeholder="פרטי מידע משלים אחר" type="text"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 pagespecialpara brtop">
            <h2 class="para"><span class="para-num">ב</span>פרטי יצירת קשר</h2>
            <div class="contcactfrom">
                <div class="contact-right">
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12 row-grid rowpadding"><h2 class="formpara">כתובת מגורים</h2></div>
                        <div class="col-md-6 col-sm-6 col-xs-12 row-grid rowpaading-1"><h2 class="formpara">טלפונים, דוא"ל ואתר</h2></div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-sm-4 col-xs-12 row-grid rowpadding"><input name="address" value="<?php echo getPVal('address'); ?>" placeholder="רחוב" id="address" type="text"></div>
                        <div class="col-md-2 col-sm-2 col-xs-12 row-grid rowpadding"><input name="streetNo" value="<?php echo getPVal('streetNo'); ?>" placeholder="מס' בית" id="streetNo" type="text"></div>
                        <div class="col-md-4 col-sm-4 col-xs-12 row-grid rowpaading-1"><i class="fa fa-star font-icon"></i><input name="cellphone" value="<?php echo getPVal('cellphone'); ?>" placeholder="מס' טלפון סלולרי" id="cellphone" required type="text"></div>
                        <div class="col-md-2 col-sm-2 col-xs-12 row-grid pdht15 checkbox-row"><input type="checkbox" name="hideCellphone" class="squaredTwo" <?php echo isChk('hideCellphone'); ?>><span class="hide-label-fix">הסתר</span></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12 row-grid rowpadding"><input name="city" value="<?php echo getPVal('city'); ?>" placeholder="ישוב" id="city" type="text"></div>
                        <div class="col-md-4 col-sm-4 col-xs-12 row-grid rowpaading-1"><input name="phone" value="<?php echo getPVal('phone'); ?>" placeholder="מס' טלפון בבית" id="phone" type="text"></div>
                        <div class="col-md-2 col-sm-2 col-xs-12 row-grid pdht15 checkbox-row"><input type="checkbox" name="hidePhone" class="squaredTwo" <?php echo isChk('hidePhone'); ?>><span class="hide-label-fix">הסתר</span></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12 row-grid rowpadding"><input name="zipcode" value="<?php echo getPVal('zipcode'); ?>" placeholder="מיקוד" id="zipcode" type="text"></div>
                        <div class="col-md-4 col-sm-4 col-xs-12 row-grid rowpaading-1"><input name="phone2" value="<?php echo getPVal('phone2'); ?>" placeholder="מס' טלפון נוסף" id="phone2" type="text"></div>
                        <div class="col-md-2 col-sm-2 col-xs-12 row-grid pdht15 checkbox-row"><input type="checkbox" name="hidePhone2" class="squaredTwo" <?php echo isChk('hidePhone2'); ?>><span class="hide-label-fix">הסתר</span></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12 row-grid rowpadding"><input name="country" value="<?php echo getPVal('country'); ?>" placeholder="מדינה" id="country" type="text"></div>
                        <div class="col-md-4 col-sm-4 col-xs-12 row-grid rowpaading-1"><input name="fax" value="<?php echo getPVal('fax'); ?>" placeholder="פקס בבית" id="fax" type="text"></div>
                        <div class="col-md-2 col-sm-2 col-xs-12 row-grid pdht15 checkbox-row"><input type="checkbox" name="hideFax" class="squaredTwo" <?php echo isChk('hideFax'); ?>><span class="hide-label-fix">הסתר</span></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12 row-grid rowpadding"><input name="mailAddress" value="<?php echo getPVal('mailAddress'); ?>" placeholder="כתובת למשלוח דואר (אם היא שונה מהכתובת הנ&quot;ל)" id="mailAddress" type="text"></div>
                        <div class="col-md-4 col-sm-4 col-xs-12 row-grid rowpaading-1"><i class="fa fa-star font-icon"></i><input name="email" value="<?php echo getPVal('email'); ?>" placeholder="דוא&quot;ל" required id="email" type="text"></div>
                        <div class="col-md-2 col-sm-2 col-xs-12 row-grid pdht15 checkbox-row"><input type="checkbox" name="hideEmail" class="squaredTwo" <?php echo isChk('hideEmail'); ?>><span class="hide-label-fix">הסתר</span></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12 row-grid rowpadding checkbox-row"><input type="checkbox" name="hideAddress" class="squaredTwo" style="float:right; margin-left:10px;" <?php echo isChk('hideAddress'); ?>><span class="hide-label-fix" style="float:right; width: auto;">האם להסתיר את הכתובת?</span></div>
                        <div class="col-md-4 col-sm-4 col-xs-12 row-grid rowpaading-1"><input name="mySite" value="<?php echo getPVal('mySite'); ?>" placeholder="אתר אינטרנט" id="mySite" type="text"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 pagespecialpara brtop">
            <h2 class="para"><span class="para-num">ג</span>פרטי רשתות חברתיות</h2>
            <div class="row">
                <div class="col-md-6 row-grid"><input name="linkedinPage" value="<?php echo getPVal('linkedinPage'); ?>" placeholder="לינקדאין" type="text"></div>
                <div class="col-md-6 row-grid"><input name="skype" value="<?php echo getPVal('skype'); ?>" placeholder="סקייפ" type="text"></div>
            </div>
            <div class="row">
                <div class="col-md-6 row-grid"><input name="facebookPage" value="<?php echo getPVal('facebookPage'); ?>" placeholder="פייסבוק" type="text"></div>
                <div class="col-md-6 row-grid"><input name="twitterPage" value="<?php echo getPVal('twitterPage'); ?>" placeholder="טוויטר" type="text"></div>
            </div>
        </div>

        <div class="col-md-12 pagespecialpara brtop">
            <h2 class="para"><span class="para-num">ד</span><?php echo $TIAL['course_education']; ?></h2>
            <div class="contcactfrom">
                <div class="contact-right">
                    <div class="row">
                        <div class="col-md-3 col-sm-3 col-xs-12 row-grid rowpadding">
                            <label for="elementarySchool" class="hidden-section"><?php echo $TIAL['course_elementary']; ?></label>
                            <input name="elementarySchool" id="elementarySchool" type="text" placeholder="<?php echo $TIAL['course_elementary']; ?>">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12 row-grid rowpadding">
                            <label for="elementarySchoolCity" class="hidden-section"><?php echo $TIAL['course_place']; ?></label>
                            <input name="elementarySchoolCity" id="elementarySchoolCity" type="text" placeholder="<?php echo $TIAL['course_place']; ?>">
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-12 row-grid rowpadding" style="padding-top: 5px; line-height: 14px; font-size: 14px;">
                            <?php echo $TIAL['course_noPublish']; ?>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12 row-grid rowpaading-1">
                            <label for="elementarySchoolYears" class="hidden-section"><?php echo $TIAL['course_years']; ?></label>
                            <input name="elementarySchoolYears" id="elementarySchoolYears" type="text" class="updateYears" placeholder="<?php echo $TIAL['course_years']; ?>">
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-sm-3 col-xs-12 row-grid rowpadding">
                            <label for="highSchool" class="hidden-section"><?php echo $TIAL['course_highschool']; ?></label>
                            <input name="highSchool" id="highSchool" type="text" placeholder="<?php echo $TIAL['course_highschool']; ?>">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12 row-grid rowpadding">
                            <label for="highSchoolCity" class="hidden-section"><?php echo $TIAL['course_place']; ?></label>
                            <input name="highSchoolCity" id="highSchoolCity" type="text" placeholder="<?php echo $TIAL['course_place']; ?>">
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-12 row-grid rowpadding">
                            <label for="highSchoolFaculty" class="hidden-section"><?php echo $TIAL['course_study']; ?></label>
                            <input name="highSchoolFaculty" id="highSchoolFaculty" type="text" placeholder="<?php echo $TIAL['course_study']; ?>">
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12 row-grid rowpaading-1">
                            <label for="schoolYears" class="hidden-section"><?php echo $TIAL['course_years']; ?></label>
                            <input name="schoolYears" id="schoolYears" type="text" class="updateYears" placeholder="<?php echo $TIAL['course_years']; ?>">
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-sm-3 col-xs-12 row-grid rowpadding">
                            <label for="university1" class="hidden-section"><?php echo $TIAL['course_institute']; ?></label>
                            <input name="university1" id="university1" type="text" placeholder="<?php echo $TIAL['course_institute']; ?>">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12 row-grid rowpadding">
                            <label for="university1City" class="hidden-section"><?php echo $TIAL['course_place']; ?></label>
                            <input name="university1City" id="university1City" type="text" placeholder="<?php echo $TIAL['course_place']; ?>">
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-12 row-grid rowpadding">
                            <label for="university1Faculty" class="hidden-section"><?php echo $TIAL['course_faculty']; ?></label>
                            <input name="university1Faculty" id="university1Faculty" type="text" placeholder="<?php echo $TIAL['course_faculty']; ?>">
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-12 row-grid rowpaading-1 pdL0">
                            <label for="university1Years" class="hidden-section"><?php echo $TIAL['course_years']; ?></label>
                            <input name="university1Years" id="university1Years" type="text" class="updateYears" placeholder="<?php echo $TIAL['course_years_short']; ?>">
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-12 row-grid pdL0">
                            <label for="university1Degree" class="hidden-section"><?php echo $TIAL['course_degree']; ?></label>
                            <input name="university1Degree" id="university1Degree" type="text" placeholder="<?php echo $TIAL['course_degree']; ?>">
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-12 row-grid pdht15">
                            <i class="fa fa-plus icoNPlsS" id="addsasa"></i>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    
                    <div class="row" id="itemsaa"></div>

                    <div class="row">
                        <div class="col-md-2 col-sm-2 col-xs-12 row-grid rowpadding" style="line-height: 40px;">
                            סה&quot;כ שנות לימוד:
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12 row-grid rowpaading-1">
                            <label for="totalYears" class="hidden-section">סה&quot;כ שנות לימוד</label>
                            <input name="totalYears" id="totalYears" type="text" placeholder="סה&quot;כ שנות לימוד" disabled="">
                        </div>
                        
                        <div class="col-md-6 col-sm-6 col-xs-12 row-grid rowpadding">
                            <label for="moreSchoolDetails" class="hidden-section"><?php echo $TIAL['course_other']; ?></label>
                            <input name="moreSchoolDetails" id="moreSchoolDetails" type="text" placeholder="<?php echo $TIAL['course_other']; ?>">
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-sm-12 col-xs-12 pagespecialpara brtop">
            <h2 class="para"><span class="para-num">ה</span>ידיעת שפות</h2>
            <div class="contcactfrom">
                <div class="contact-right">
                    <div id="formsdivs">
                        <?php 
                        // Loop to pre-fill languages (1-3 + custom)
                        for ($i = 1; $i <= 5; $i++) {
                            $lName = getPVal('lang'.$i);
                            $lSpeak = getPVal('lang'.$i.'speak');
                            $lRead = getPVal('lang'.$i.'read');
                            $lWrite = getPVal('lang'.$i.'write');
                            
                            // Defaults
                            if($i==1 && empty($lName)) $lName = "עברית";
                            if($i==2 && empty($lName)) $lName = "אנגלית";
                            if($i==3 && empty($lName)) $lName = "ערבית";
                            
                            // Show row if default or has saved data
                            if ($i <= 3 || !empty($lName)) {
                        ?>
                        <div class="row" id="formfields<?php echo $i; ?>">
                            <div class="col-md-3 col-sm-3 col-xs-12 row-grid pdht15">
                                <?php if($i<=3): ?><span class="spanparalastN formpara"> שפה</span><?php endif; ?>
                                <input name="lang<?php echo $i; ?>" id="lang<?php echo $i; ?>" value="<?php echo $lName; ?>" type="text" <?php if($i<=3) echo 'readonly'; else echo 'placeholder="שפה נוספת"'; ?>>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12 row-grid pdht15">
                                <?php if($i<=3): ?><span class="spanparalastN formpara">דיבור</span><?php endif; ?>
                                <select name="lang<?php echo $i; ?>speak" class="option-form">
                                    <option value="">כלל לא</option>
                                    <option value="1" <?php if($lSpeak=='1') echo 'selected'; ?>>שפת אם</option>
                                    <option value="2" <?php if($lSpeak=='2') echo 'selected'; ?>>טובה</option>
                                    <option value="3" <?php if($lSpeak=='3') echo 'selected'; ?>>בסיסית</option>
                                </select>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12 row-grid pdht15">
                                <?php if($i<=3): ?><span class="spanparalastN formpara">קריאה</span><?php endif; ?>
                                <select name="lang<?php echo $i; ?>read" class="option-form">
                                    <option value="">כלל לא</option>
                                    <option value="1" <?php if($lRead=='1') echo 'selected'; ?>>שפת אם</option>
                                    <option value="2" <?php if($lRead=='2') echo 'selected'; ?>>טובה</option>
                                    <option value="3" <?php if($lRead=='3') echo 'selected'; ?>>בסיסית</option>
                                </select>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12 row-grid pdht15">
                                <?php if($i<=3): ?><span class="spanparalastN formpara">כתיבה</span><?php endif; ?>
                                <select name="lang<?php echo $i; ?>write" class="option-form">
                                    <option value="">כלל לא</option>
                                    <option value="1" <?php if($lWrite=='1') echo 'selected'; ?>>שפת אם</option>
                                    <option value="2" <?php if($lWrite=='2') echo 'selected'; ?>>טובה</option>
                                    <option value="3" <?php if($lWrite=='3') echo 'selected'; ?>>בסיסית</option>
                                </select>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <?php 
                            }
                        }
                        ?>
                    </div>
                    
                    <div class="row IconPlus">
                        <div class="col-md-3 col-sm-3 col-xs-12 row-grid pdht15">
                            <i id="addnewdivfield" onclick="adddd();" class="fa fa-plus" style="cursor: pointer; color: #FFA801;"></i>
                            <i id="delete12" class="fa fa-minus delete12" onclick="removefield();" style="display:none; cursor: pointer; color: red; margin-right: 10px;"></i>
                        </div>
                    </div>
                    <script>
                        var defaultvalue = <?php echo ($i > 3 ? $i-1 : 3); ?>;
                        function adddd() { defaultvalue++; var newRow = '<div class="row" id="formfields' + defaultvalue + '"><div class="col-md-3 col-sm-3 col-xs-12 row-grid pdht15"><input name="lang' + defaultvalue + '" id="lang' + defaultvalue + '" value="" required type="text" placeholder="שפה נוספת"></div><div class="col-md-3 col-sm-3 col-xs-12 row-grid pdht15"><select name="lang' + defaultvalue + 'speak" class="option-form"><option value="">כלל לא</option><option value="1">שפת אם</option><option value="2">טובה</option><option value="3">בסיסית</option></select></div><div class="col-md-3 col-sm-3 col-xs-12 row-grid pdht15"><select name="lang' + defaultvalue + 'read" class="option-form"><option value="">כלל לא</option><option value="1">שפת אם</option><option value="2">טובה</option><option value="3">בסיסית</option></select></div><div class="col-md-3 col-sm-3 col-xs-12 row-grid pdht15"><select name="lang' + defaultvalue + 'write" class="option-form"><option value="">כלל לא</option><option value="1">שפת אם</option><option value="2">טובה</option><option value="3">בסיסית</option></select></div><div class="clearfix"></div></div>'; jQuery("#formsdivs").append(newRow); if (defaultvalue > 3) { jQuery("#delete12").show(); } }
                        function removefield() { if (defaultvalue > 3) { jQuery("#formfields" + defaultvalue).remove(); defaultvalue--; } if (defaultvalue <= 3) { jQuery("#delete12").hide(); } }
                    </script>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-sm-12 col-xs-12 pagespecialpara brtop">
            <h2 class="para"><span class="para-num">ו</span>פרטי מקצוע ועיסוק</h2>
            <div class="contcactfrom">
                <div class="contact-right">
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12 row-grid pdht15 extra_mb_field">
                            <p><input name="fldProfession" value="<?php echo getPVal('fldProfession'); ?>" placeholder="מקצועך" id="fldProfession" type="text"></p>
                            <p><input name="fldGeneralLongevity" value="<?php echo getPVal('fldGeneralLongevity'); ?>" placeholder="מספר שנות עבודה במקצוע" id="fldGeneralLongevity" type="number"></p>
                            <p><input name="currBiz" value="<?php echo getPVal('currBiz'); ?>" placeholder="עיסוקך הנוכחי והגדרת תפקידך" id="currBiz" type="text"></p>
                            <p><input name="fldSpecialization" value="<?php echo getPVal('fldSpecialization'); ?>" placeholder="תחום התמחות מקצועית" id="fldSpecialization" type="text"></p>
                            <p><input name="licenseNo" value="<?php echo getPVal('licenseNo'); ?>" placeholder="עו&quot;ד/רופא – מספר הרשיון" id="licenseNo" type="text"></p>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12 row-grid pdht15">
                            <div class="m-t-35">
                                <div class="squaredTwo checkboxset">
                                    <fieldset><input value="1" id="experience1" name="experience1" type="checkbox" <?php echo isChk('experience1'); ?>></fieldset>
                                </div>
                                <label class="lablSec">האם הכנת חוות דעת מקצועית בחמש השנים האחרונות?</label>
                                <textarea id="experience2" name="experience2" class="contmil90" placeholder="ככל שהתשובה חיובית אנא פרט בשתיים שלוש שורות"><?php echo getPVal('experience2'); ?></textarea>            
                            </div>
                            <div class="">
                                <div class="squaredTwo checkboxset">
                                    <fieldset><input value="1" id="experience3" name="experience3" type="checkbox" <?php echo isChk('experience3'); ?>></fieldset>
                                </div>
                                <label class="lablSec">האם הופעת כעד/ה מומחה/ית בבית המשפט בחמש השנים האחרונות?</label>
                                <textarea id="experience4" name="experience4" class="contmil909" placeholder="ככל שהתשובה חיובית אנא פרט בשתיים שלוש שורות"><?php echo getPVal('experience4'); ?></textarea>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-sm-12 col-xs-12 pagespecialpara brtop">
            <h2 class="para"><span class="para-num">ז</span>פרטי מקום העבודה</h2>
            <div class="contcactfrom">
                <div class="contact-right">
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12 row-grid rowpadding"><input name="workplace" value="<?php echo getPVal('workplace'); ?>" placeholder="שם המקום בו הינך מועסק" id="workplace" type="text"></div>
                        <div class="col-md-6 col-sm-6 col-xs-12 row-grid rowpaading-1"><input name="workphone" value="<?php echo getPVal('workphone'); ?>" placeholder="מס טלפון בעבודה" id="workphone" type="text"></div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12 row-grid rowpadding"><input name="fldLongevity" value="<?php echo getPVal('fldLongevity'); ?>" placeholder="מספר שנות העבודה בעיסוקך הנוכחי" id="fldLongevity" type="number"></div>
                        <div class="col-md-6 col-sm-6 col-xs-12 row-grid rowpaading-1"><input name="workfax" value="<?php echo getPVal('workfax'); ?>" placeholder="פקס בעבודה" id="workfax" type="text"></div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12 row-grid rowpadding"><input name="workweb" value="<?php echo getPVal('workweb'); ?>" placeholder="לינק לאתר מקום העבודה" id="workweb" type="text"></div>
                        <div class="col-md-6 col-sm-6 col-xs-12 row-grid rowpaading-1"><input name="workaddress" value="<?php echo getPVal('workaddress'); ?>" placeholder="כתובת מקום העבודה" id="workaddress" type="text"></div>
                    </div>      
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12 row-grid rowpadding"><input name="workaddress1" value="<?php echo getPVal('workaddress1'); ?>" placeholder="כתובת סניף נוסף" id="workaddress1" type="text"></div>
                        <div class="col-md-6 col-sm-6 col-xs-12 row-grid rowpaading-1"><input name="workaddress2" value="<?php echo getPVal('workaddress2'); ?>" placeholder="כתובת סניף נוסף" id="workaddress2" type="text"></div>
                    </div>      
                </div>
            </div>
        </div>

        <div class="col-md-12 col-sm-12 col-xs-12 pagespecialpara brtop">
            <h2 class="para"><span class="para-num">ח</span>נגישות מקום העבודה</h2>
            <div class="contcactfrom">
                <div class="contact-right">
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12 row-grid rowpadding icon-input-block"><i class="fa fa-bus ii-icon"></i><input name="accessBuses" value="<?php echo getPVal('accessBuses'); ?>" placeholder="מספרי קווי אוטובוס" id="accessBuses" type="text"></div>
                        <div class="col-md-6 col-sm-6 col-xs-12 row-grid rowpaading-1 icon-input-block"><i class="fa fa-train ii-icon"></i><input name="accessTrain" value="<?php echo getPVal('accessTrain'); ?>" placeholder="תחנת רכבת קרובה" id="accessTrain" type="text"></div>
                    </div>      
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12 row-grid rowpadding icon-input-block"><span class="fa-stack fa-3x ii-icon" style="font-size: 10px"><i class="fa fa-square-o fa-stack-2x" style="font-size:22px"></i><strong class="fa-stack-1x icon-text" style="font-size: 12px; margin-top:1px">P</strong></span><input name="accessPark" value="<?php echo getPVal('accessPark'); ?>" placeholder="חניון קרוב" id="accessPark" type="text"></div>
                        <div class="col-md-6 col-sm-6 col-xs-12 row-grid rowpaading-1 icon-input-block"><i class="fa fa-coffee ii-icon"></i><input name="accessCoffee" value="<?php echo getPVal('accessCoffee'); ?>" placeholder="בית קפה קרוב" id="accessCoffee" type="text"></div>
                    </div>      
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12 row-grid rowpadding"><div class="squaredTwo checkboxset"><fieldset><input value="1" id="accessDisabled" name="accessDisabled" type="checkbox" <?php echo isChk('accessDisabled'); ?>></fieldset></div><label class="lablSec">האם הבניין מונגש לנכים?</label></div>
                        <div class="col-md-6 col-sm-6 col-xs-12 row-grid rowpaading-1"><div class="squaredTwo checkboxset"><fieldset><input value="1" id="accessElevator" name="accessElevator" type="checkbox" <?php echo isChk('accessElevator'); ?>></fieldset></div><label class="lablSec">האם יש מעלית בבניין?</label></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-sm-12 col-xs-12 pagespecialpara brtop">
            <h2 class="para"><span class="para-num">ט</span>חברות בארגונים מקצועיים</h2>
            <div class="contcactfrom">
                <div class="contact-right">
                    <div id="orgsdivs">
                        <div class="row" id="orgfields1">
                            <div class="col-md-6 col-sm-6 col-xs-12 row-grid rowpadding"><input name="org1" value="<?php echo getPVal('org1'); ?>" placeholder="חבר בארגון" id="org1" type="text"></div>
                            <div class="col-md-6 col-sm-6 col-xs-12 row-grid rowpaading-1"><input name="org1link" value="<?php echo getPVal('org1link'); ?>" id="org1link" placeholder="לינק לאתר הארגון" type="text"></div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <div class="row IconPlus">
                    <div class="col-md-3 col-sm-3 col-xs-12 row-grid pdht15">
                        <i id="addneworgfield" onclick="addOrg();" class="fa fa-plus" style="cursor: pointer; color: #FFA801;"></i>
                        <i id="delete13" class="fa fa-minus delete12" onclick="removeOrg();" style="display:none; cursor: pointer; color: red; margin-right: 10px;"></i>
                    </div>
                </div>
                <script>
                    var orgvalue = 1;
                    function addOrg(){ orgvalue++; jQuery("#orgsdivs").append('<div class="row" id="orgfields'+orgvalue+'"><div class="col-md-6 col-sm-6 col-xs-12 row-grid rowpadding"><input name="org'+orgvalue+'" placeholder="חבר בארגון" id="org'+orgvalue+'" type="text"></div><div class="col-md-6 col-sm-6 col-xs-12 row-grid rowpaading-1"><input name="org'+orgvalue+'link" id="org'+orgvalue+'link" placeholder="לינק לאתר הארגון" type="text"></div><div class="clearfix"></div></div>'); if(orgvalue > 1){ jQuery("#delete13").show(); } }
                    function removeOrg(){ jQuery("#orgfields"+orgvalue).remove(); orgvalue--; if(orgvalue == 1) jQuery("#delete13").hide(); }
                </script>
            </div>
        </div>

        <div class="col-md-12 col-sm-12 col-xs-12 pagespecialpara brtop">
            <h2 class="para"><span class="para-num">י</span>אבקש לצרף אותי לקטגוריות הבאות</h2>
            <div class="contcactfrom">
                <div class="contact-right">
                    <div class="row">
                        <div class="col-md-7 col-sm-7 col-xs-12 row-grid pdht15">
                            <div class="rltdivp">
                                <ul class="DefineTree tree-star">
                                    <li><a data-toggle="collapse" href="#collapse1" class="fromtree insExtra"></a><span>עדים מומחים</span>
                                        <ul id="collapse1" class="panel-collapse line-tree collapse in">
                                            <li><a class="insExtra collapsed" data-toggle="collapse" href="#collapse2"></a><a href="javascript:void(0)" class="paraplus" data-toggle="collapse" data-target="#collapse2">משפטי/עורכי דין</a>
                                                <ul id="collapse2" class="panel-collapse line-tree collapse">
                                                    <li><a href="javascript:mygetvaluetodicv('אזרחי',254)" class="paraplus">אזרחי</a></li>
                                                    <li><a href="javascript:mygetvaluetodicv('אישות',255)" class="paraplus">אישות</a></li>
                                                    <li><a href="javascript:mygetvaluetodicv('אינטרנט',256)" class="paraplus">אינטרנט</a></li>
                                                    <li><a href="javascript:mygetvaluetodicv('אמנת האג להחזרת ילדים חטופים',257)" class="paraplus">אמנת האג להחזרת ילדים חטופים</a></li>
                                                    <li><a href="javascript:mygetvaluetodicv('תקשורת',287)" class="paraplus">תקשורת</a></li>
                                                </ul>
                                            </li>
                                            <li><a class="insExtra collapsed" data-toggle="collapse" href="#collapse37"></a><a href="javascript:void(0)" class="paraplus" data-toggle="collapse" data-target="#collapse37">רפואי</a>
                                                <ul id="collapse37" class="panel-collapse line-tree collapse">
                                                    <li><a href="javascript:mygetvaluetodicv('אונקולוגיה',7)" class="paraplus">אונקולוגיה</a></li>
                                                    <li><a href="javascript:mygetvaluetodicv('אורטופדיה',13)" class="paraplus">אורטופדיה</a></li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                                <ul class="DefineTree tree-star">
                                    <li><a data-toggle="collapse" href="#collapse823" class="fromtree insExtra"></a><span>בוררים</span>
                                        <ul id="collapse823" class="panel-collapse line-tree collapse in">
                                            <li><a href="javascript:mygetvaluetodicv('שופטים בדימוס',830)" class="paraplus">שופטים בדימוס</a></li>
                                            <li><a href="javascript:mygetvaluetodicv('עורכי דין',831)" class="paraplus">עורכי דין</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-12 row-grid "><img src="<?php echo get_template_directory_uri(); ?>/designFiles/arrow-iconsilde.png" class="rformimg" alt="arrow-iconsilde"></div>
                        <div class="col-md-4 col-sm-4 col-xs-12 row-grid pdht15"><div class="rltdivp widthrldiv"><div class="col-md-12 col-sm-12 col-xs-12 paddingZ clRmvse"></div></div></div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class=" col-md-12  col-sm-12  col-xs-12 pagespecialpara brtop">
            <h2 class="para"><span class="para-num">כ</span>הגשת בקשות ליצירת סיווג שלא נכלל בעץ הסיווגים</h2>
            <div class="contcactfrom"><div class="contact-right"><div class="row"><div class="col-md-12  col-sm-12  col-xs-12 row-grid pdht15"><input name="catsExtraDetails" value="<?php echo getPVal('catsExtraDetails'); ?>" placeholder="פרט כאן בקשתך להוספה והיא תועבר לעיון הנהלת המאגר" type="text" id="catsExtraDetails"></div></div></div></div>
        </div>

        <div class=" col-md-12  col-sm-12  col-xs-12 pagespecialpara brtop">
            <h2 class="para"><span class="para-num">ל</span>דמי חבר שנתיים</h2> 
            <div class="contcactfrom">
                <div class="contact-right">
                    <div class="row">
                        <div class="col-md-6 col-sm-6  col-xs-12 row-grid message-box-1 message-box pdht15">
                            <p class="rtailbox"><input type="radio" name="registrationKind" id="searchsubmit15" class="registr-radio09" onclick="calcSum()">מומחה מוסמך המכון</p>
                            <p class="frm-retail retaildetail"><span class="fweight">175.00 </span>ש"ח</p>
                        </div>
                        <div class="col-md-6 col-sm-6  col-xs-12 row-grid message-box pdht15">
                            <p class="rtailbox"><input type="radio" id="searchsubmit111" name="registrationKind" class="registr-radio09" onclick="calcSum()">מומחה חוץ</p>
                            <p class="frm-retail retaildetail"><span class="fweight">1250</span> ש"ח</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="contcactfrom">
                <div class="contact-right">
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12 row-grid pdht15"><input id="totalSum" disabled="" placeholder="סה&quot;כ לתשלום" type="text"></div>
                        <div class="col-md-6 col-sm-6 col-xs-12 row-grid pdht15"><input name="creditCardHolder" placeholder="שם בעל הכרטיס" id="creditCardHolder" type="text"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12 row-grid pdht15"><input name="creditCardNumber" placeholder="מספר הכרטיס" type="text" id="creditCardNumber"></div>
                        <div class="col-md-6 col-sm-6 col-xs-12 row-grid pdht15">
                            <div class="col-xs-6 fix-pad wid60">
                                <select id="course_creditType" name="course_creditType" class="option-element"><option selected="" disabled="">סוג הכרטיס</option><option value="01">ויזה</option><option value="02">מאסטר קארד</option><option value="03">דיינרס</option><option value="04">ישראכארד</option></select>
                            </div>
                            <div class="col-xs-6 pdht15 fix-pad wid40 paddingZ"><input name="creditCardCVV" type="text" id="creditCardCVV" maxlength="4"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12 row-grid pdht15"><input name="creditCardTz" placeholder="מספר ת.ז. של בעל הכרטיס" id="creditCardTz" type="text"></div>
                        <div class="col-md-6 col-sm-6 col-xs-12 pdht15">
                            <div class="elementfrm-1 row-grid pdht15"><select id="creditCardMonth" name="creditCardMonth" class="option-element"><option value="01">01</option><option value="02">02</option><option value="03">03</option><option value="04">04</option><option value="05">05</option><option value="06">06</option><option value="07">07</option><option value="08">08</option><option value="09">09</option><option value="10">10</option><option value="11">11</option><option value="12">12</option></select></div>
                            <div class="elementfrm row-grid pdht15"><select name="creditCardYear" id="creditCardYear" class="option-element" type="text"><?php $curYear=(int)date('Y');for($i=0;$i<=12;$i++){$y=$curYear+$i;$val=substr($y,-2);echo "<option value='$val'>$y</option>";}?></select></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class=" col-md-12  col-sm-12  col-xs-12 pagespecialpara brtop">
            <h2 class="para"><span class="para-num">מ</span>מידע חשבונית</h2>
            <div class="contcactfrom"><div class="contact-right receipts-block"><div class="row"><p class="from1detail">אני מבקש להוציא את החשבונית על שם:</p><input name="name4invoice" id="name4invoice" type="text" class="rcontaine"><p>&nbsp;</p><div><input value="בית" id="invoiceToHome" name="invoiceTo" checked="" type="radio" class="registr-radio"> אני מבקש לשלוח את החשבונית לכתובת ביתי</div><div><input value="משרד" id="invoiceToOffice" name="invoiceTo" type="radio" class="registr-radio"> אני מבקש לשלוח את החשבונית לכתובת משרדי</div><p>&nbsp;</p><p class="from1detail">אם לכתובת אחרת, נא פרט להיכן:</p><input name="invoice2addr" id="invoice2addr" type="text" class="rcontaine"></div></div></div>
        </div>

        <div class=" col-md-12  col-sm-12  col-xs-12 pagespecialpara brtop">
            <h2 class="para"><span class="para-num">נ</span>העלאת תמונה</h2>
            <div class="contcactfrom"><div class="contact-right"><div class="row "><p class="from1detail col-md-12 paddXsZ">באפשרותך להעלות תמונה כבר בשלב זה או לאחר שבקשתך תאושר וישלחו אליך שם משתמש וסיסמא.</p><div class="form-1 col-md-12 paddXsZ"><input id="picFileName" type="text" class="rcontaine" disabled=""><input name="picFile" id="picFile" type="file" class="retailrtl" style="display:none" accept="image/*"><label for="picFile" class="frm-sect">בחר קובץ</label></div></div></div></div>
        </div>

        <div class=" col-md-12  col-sm-12  col-xs-12 pagespecialpara brtop ">
            <h2 class="para"><span class="para-num">ס</span>עבר פלילי</h2>
            <div class="contcactfrom"><div class="row"><p class="registr-para-234 poit-mrt col-md-12 paddXsZ">אני מצהיר כי אין לי עבר פלילי.</p></div></div>
        </div>

        <div class="col-md-12 col-sm-12 col-xs-12 pagespecialpara brtop brbotom">
            <h2 class="para"><span class="para-num">פ</span>יש לאשר ההערות והתנאים</h2>
            <div class="contcactfrom">
                <div class="contact-right">
                    
                    <div class="checkbox-fix-row">
                        <input type="checkbox" id="check1" name="check1" value="1" <?php echo isChk('check1'); ?>>
                        <label for="check1">אין לי עבר פלילי ולמיטב ידיעתי אין כל כוונה להעמידני לדין פלילי.</label>
                    </div>
                    
                    <div class="checkbox-fix-row">
                        <input type="checkbox" id="check2" name="check2" value="1" <?php echo isChk('check2'); ?>>
                        <label for="check2">כל הפרטים שמסרתי בשאלון הרשמה זה, אמת.</label>
                    </div>

                    <div class="checkbox-fix-row">
                        <input type="checkbox" id="check3" name="check3" value="1" <?php echo isChk('check3'); ?>>
                        <label for="check3">הובא לידיעתי כי יהא עלי לאמת את כל האמור בתצהיר.</label>
                    </div>

                    <div class="checkbox-fix-row">
                        <input type="checkbox" id="check4" name="check4" value="1" <?php echo isChk('check4'); ?>>
                        <label for="check4">אני מוכן להשתתף בהשתלמויות העשרה.</label>
                    </div>
                    
                    <div class="checkbox-fix-row">
                        <input type="checkbox" id="check5" name="check5" value="1" <?php echo isChk('check5'); ?>>
                        <label for="check5">במידה ואדרש, אמציא לכם כל תעודה.</label>
                    </div>
                    
                    <div class="checkbox-fix-row">
                        <input type="checkbox" id="check6" name="check6" value="1" <?php echo isChk('check6'); ?>>
                        <label for="check6">קראתי את כללי האתיקה ואת תקנון האתר.</label>
                    </div>
                    
                    <div class="checkbox-fix-row">
                        <input type="checkbox" id="check7" name="check7" value="1" <?php echo isChk('check7'); ?>>
                        <label for="check7">קראתי את ההערות והתנאים ואני מסכים להם.</label>
                    </div>
                    
                    <div class="row">   
                        <input value="שמירה ועדכון" class="fromsent " type="submit">
                    </div>  
                </div>
            </div>
        </div>

    </form>
    <?php endif; ?>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<script>
jQuery(document).ready(function($) {
    
    // Auto-fill Categories from Saved Data
    var savedCats = "<?php echo $existing_cats_str; ?>";
    if (savedCats) {
        var arr = savedCats.split(',');
        arr.forEach(function(catId) {
            if (catId && catId !== "") {
                // Try to find the name in the tree links
                var link = $('a[href^="javascript:mygetvaluetodicv"][href*=",' + catId + ')"]');
                if (link.length > 0) {
                    var text = link.text();
                    // Manually append the visual element
                    $(".clRmvse").append("<p class='pargrph' catId='"+catId+"'><i class='fa fa-times iconrtl deleteRmv'></i> <span>"+text+"</span></p>");
                }
            }
        });
    }

    // Original Logic
    window.calcSum = function() {
        var total = 0; var basePrice = 0; var extraPrice = 0;
        if ($("#searchsubmit15").is(":checked")) { basePrice = 175; extraPrice = 15; } 
        else if ($("#searchsubmit111").is(":checked")) { basePrice = 1250; extraPrice = 45; } 
        else { return; }
        var selectedCatsCount = $(".clRmvse").children().length;
        var extraCount = Math.max(0, selectedCatsCount - 3);
        total = basePrice + (extraCount * extraPrice);
        if ($("#89").is(":checked")) { total *= 2; } else if ($("#90").is(":checked")) { total *= 3; }
        $('#totalSum').val(total + " + מע\"מ"); $('#totalPay').val(total);
    };

    window.mygetvaluetodicv = function(name, id) {
        var currentIds = $("#catIds").val();
        if(currentIds.indexOf("," + id + ",") === -1) {
            $(".clRmvse").append("<p class='pargrph' catId='"+id+"'><i class='fa fa-times iconrtl deleteRmv'></i> <span>"+name+"</span></p>");
            if(currentIds === "") currentIds = ",";
            $("#catIds").val(currentIds + id + ",");
            calcSum();
        }
    };
    $("body").on("click", ".deleteRmv", function() {
        var id = $(this).parent("p").attr("catId");
        var cur = $("#catIds").val();
        $("#catIds").val(cur.replace("," + id + ",", ","));
        $(this).parent("p").remove();
        calcSum();
    });
    
    $('.tree-star').on('click', '.insExtra', function(e) {
        e.preventDefault(); e.stopPropagation();
        var target = $(this).attr('href');
        $(target).collapse('toggle');
    });
    $('.tree-star').on('show.bs.collapse', '.collapse', function () {
        $(this).parent().find('.insExtra').first().removeClass('collapsed');
    });
    $('.tree-star').on('hide.bs.collapse', '.collapse', function () {
        $(this).parent().find('.insExtra').first().addClass('collapsed');
    });

    var nextSchoolId = 2;
    $("#addsasa").click(function (e) {
        var newRow = '<div class="col-md-12 col-sm-12" style="padding:0px;margin-top:10px;"><div class="col-md-3 col-sm-3 col-xs-12 row-grid rowpadding"><label for="university'+nextSchoolId+'" class="hidden-section">מוסד</label><input name="university'+nextSchoolId+'" id="university'+nextSchoolId+'" type="text" placeholder="מוסד להשכלה גבוהה"></div><div class="col-md-3 col-sm-3 col-xs-12 row-grid rowpadding"><label for="university'+nextSchoolId+'City" class="hidden-section">עיר</label><input name="university'+nextSchoolId+'City" id="university'+nextSchoolId+'City" type="text" placeholder="עיר/מדינה"></div><div class="col-md-2 col-sm-2 col-xs-12 row-grid rowpadding"><label for="university'+nextSchoolId+'Faculty" class="hidden-section">פקולטה</label><input name="university'+nextSchoolId+'Faculty" id="university'+nextSchoolId+'Faculty" type="text" placeholder="פקולטה"></div><div class="col-md-1 col-sm-1 col-xs-12 row-grid rowpaading-1 pdL0"><label for="university'+nextSchoolId+'Years" class="hidden-section">שנים</label><input name="university'+nextSchoolId+'Years" id="university'+nextSchoolId+'Years" type="text" class="updateYears" placeholder="שנה"></div><div class="col-md-2 col-sm-2 col-xs-12 row-grid pdL0"><label for="university'+nextSchoolId+'Degree" class="hidden-section">תואר</label><input name="university'+nextSchoolId+'Degree" id="university'+nextSchoolId+'Degree" type="text" placeholder="תואר"></div><div class="col-md-1 col-sm-1 col-xs-12 row-grid pdht15 delete remove-text-field"><i class="fa fa-minus icoNPlsSec111"></i></div></div>';
        $("#itemsaa").append(newRow); $("#university"+nextSchoolId+"Years").on("change", updateTotalYears); nextSchoolId++;
    });
    $("body").on("click", ".delete", function (e) { $(this).parent("div").remove(); updateTotalYears(); });
    $(".updateYears").on("change", updateTotalYears);
    function updateTotalYears() {
        var count = 0;
        if (isNumeric($("#elementarySchoolYears").val())) count += parseInt($("#elementarySchoolYears").val(), 10);
        if (isNumeric($("#schoolYears").val())) count += parseInt($("#schoolYears").val(), 10);
        for (i=1; i<20; i++) { if (isNumeric($("#university"+i+"Years").val())) { count += parseInt($("#university"+i+"Years").val(), 10); } }
        $("#totalYears").val(count);
    }
    function isNumeric(n) { return !isNaN(parseFloat(n)) && isFinite(n); }
    $("#creditCardCVV").blur(function(){ var tmpval = $(this).val(); if(tmpval == "") { $(this).addClass("empty").removeClass("not-empty"); } else { $(this).addClass("not-empty").removeClass("empty"); } });
    $('#picFile').change(function() { var filename = $(this).val().split('\\').pop(); $('#picFileName').val(filename); });
    function israeli_buildBirthDate() {
        var y = document.getElementById('birthYear') ? document.getElementById('birthYear').value : '';
        var m = document.getElementById('birthMonth') ? document.getElementById('birthMonth').value : '';
        var d = document.getElementById('birthDay') ? document.getElementById('birthDay').value : '';
        if (y && m && d) { document.getElementById('birthDate').value = y + '-' + m + '-' + d; }
    }
    $('#birthYear, #birthMonth, #birthDay').change(israeli_buildBirthDate);
});
var defaultvalueagan=1;
function addddagain(){ defaultvalueagan++; jQuery("#formsdivsagain").append('<div class="row" id="formfiele'+defaultvalueagan+'"><div class="col-md-3 col-sm-3  col-xs-12 row-grid pdht15"><input name="lead'+defaultvalueagan+'firstname" placeholder="שם פרטי" id="lead'+defaultvalueagan+'firstname" type="text"></div><div class="col-md-3 col-sm-3  col-xs-12 row-grid pdht15"><input name="lead'+defaultvalueagan+'lastname" placeholder="שם משפחה" id="lead'+defaultvalueagan+'lastname" type="text"></div><div class="col-md-3 col-sm-3  col-xs-12 row-grid pdht15"><input name="lead'+defaultvalueagan+'cellphone" placeholder="מס נייד"  id="lead'+defaultvalueagan+'cellphone" type="text"></div><div class="col-md-3 col-sm-3  col-xs-12 row-grid pdht15"><input name="lead'+defaultvalueagan+'profession" placeholder="מקצוע" id="lead'+defaultvalueagan+'profession" type="text"></div><div class="clearfix"></div></div>'); }
</script>

<?php get_footer(); ?>