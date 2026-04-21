<?php
/**
 * Template Name: טופס הרשמה לקורס תמא 38 בסיסי
 * Description: WordPress template for Basic TAMA 38 / Pinui Binui (Course 104) using Pablo Forms Engine
 * Author: pablo rotem
 * Url: pablo-guides.net
 */

defined('ABSPATH') || exit;

$lang = 'HEB';
$courseId = 104;
$msg_success = isset($_GET['pablo_form_success']) ? 'ההרשמה התקבלה בהצלחה! מספר פנייה: ' . intval($_GET['submission_id'] ?? 0) : '';
$msg_error = isset($_GET['pablo_form_error']) ? sanitize_text_field(wp_unslash($_GET['pablo_form_error'])) : '';

get_header();
?>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<style>
/* CSS ראשי – מחבר: Pablo Rotem */

body {
    direction: rtl;
    text-align: right;
    background: #fff;
    font-family: Arial, sans-serif;
    font-size: 22px;
}

.form-main-wrapper {
    max-width: 800px;
    margin: 0 auto;
    background: #fff;
    padding: 10px;
}

/* גריד RTL */
.form-main-wrapper .col-md-1, .form-main-wrapper .col-md-2, .form-main-wrapper .col-md-3,
.form-main-wrapper .col-md-4, .form-main-wrapper .col-md-5, .form-main-wrapper .col-md-6,
.form-main-wrapper .col-md-7, .form-main-wrapper .col-md-8, .form-main-wrapper .col-md-9,
.form-main-wrapper .col-md-10, .form-main-wrapper .col-md-11, .form-main-wrapper .col-md-12,
.form-main-wrapper .col-sm-1, .form-main-wrapper .col-sm-2, .form-main-wrapper .col-sm-3,
.form-main-wrapper .col-sm-4, .form-main-wrapper .col-sm-5, .form-main-wrapper .col-sm-6,
.form-main-wrapper .col-sm-7, .form-main-wrapper .col-sm-8, .form-main-wrapper .col-sm-9,
.form-main-wrapper .col-sm-10, .form-main-wrapper .col-sm-11, .form-main-wrapper .col-sm-12,
.form-main-wrapper .col-xs-1, .form-main-wrapper .col-xs-2, .form-main-wrapper .col-xs-3,
.form-main-wrapper .col-xs-4, .form-main-wrapper .col-xs-5, .form-main-wrapper .col-xs-6,
.form-main-wrapper .col-xs-7, .form-main-wrapper .col-xs-8, .form-main-wrapper .col-xs-9,
.form-main-wrapper .col-xs-10, .form-main-wrapper .col-xs-11, .form-main-wrapper .col-xs-12 {
    float: right;
}

/* === עץ קטגוריות – מחבר: Pablo Rotem === */

/* הקופסה הגוללת של העץ */
.tree-star .rltdivp {
    border: 1px solid #FFA801;
    max-height: 450px;
    overflow-y: auto;
    padding: 20px 25px 20px 10px;
    direction: rtl;
    background: #ffffff;
}

/* רשימת העץ הראשית */
.tree-star ul.DefineTree {
    list-style: none;
    margin: 0;
    padding: 0 30px 0 0; /* מקום לסוגר הכתום */
    direction: rtl;
}

.tree-star ul.DefineTree > li {
    position: relative;
    margin-bottom: 25px;
    font-size: 20px;
    font-weight: bold;
    color: #333333;
    white-space: nowrap;
}

.tree-star ul.DefineTree > li > span {
    display: inline-block;
    padding-right: 6px;
}

/* תתי-קטגוריות – מחוברות בסוגר כתום בצד ימין */
.tree-star ul.DefineTree > li > ul {
    list-style: none;
    margin: 10px 0 0 0;
    padding: 0 25px 0 0;
    position: relative;
}

/* הקו האנכי הכתום */
.tree-star ul.DefineTree > li > ul::before {
    content: "";
    position: absolute;
    top: -8px;
    bottom: 8px;
    right: 8px;
    border-right: 3px solid #FFA801;
}

/* שורה של תת-קטגוריה */
.tree-star ul.DefineTree > li > ul > li {
    position: relative;
    margin-bottom: 8px;
    padding-right: 26px; /* רווח בין הקו האופקי לטקסט */
    font-size: 18px;
    font-weight: normal;
    white-space: nowrap;
}

/* הקו האופקי המחבר */
.tree-star ul.DefineTree > li > ul > li::before {
    content: "";
    position: absolute;
    top: 0.9em;
    right: 8px;
    width: 18px;
    border-top: 3px solid #FFA801;
}

/* כפתור הפלוס/מינוס - UPDATED WITH USER FIX */
.tree-star .insExtra {
    display: inline-block;
    width: 16px;
    height: 16px;
    line-height: 14px;
    text-align: center;
    border: 0px solid #FFA801; /* User requested 0px border */
    background-color: #ffffff;
    color: #FFA801;
    font-size: 18px; /* User requested bigger font */
    cursor: pointer;
    float: right;
    margin-left: 6px;
    box-sizing: border-box;
    
    /* מנקים הגדרות ישנות מה-CSS המקורי */
    text-indent: 0 !important;
    overflow: visible !important;
    font-family: Arial, sans-serif !important;
}

/* ברירת מחדל – פתוח (מינוס) */
.tree-star .insExtra:before {
    content: "−";
    font-weight: bold;
}

/* כאשר Bootstrap מוסיף collapsed – מציג פלוס */
.tree-star .insExtra.collapsed:before {
    content: "+";
}

/* === הוספת הכלל שביקשת === */
.DefineTree li a, .treeplant li span {
    color: #000;
    font-size: 22px;
    cursor: pointer;
}

/* קישורי טקסט */
.tree-star .paraplus {
    text-decoration: none;
    color: #333;
    cursor: pointer;
    display: inline-block;
    vertical-align: middle;
    font-size: 18px;
}

/* קופסת קטגוריות שנבחרו – כמו באתר המקורי */
.clRmvse {
    border: 1px solid #c7c7c7;   /* אפור עדין */
    min-height: 380px;            /* גובה דומה לצילום */
    padding: 15px 20px;
    background: #ffffff;         /* רקע לבן */
}

/* כל שורה של קטגוריה נבחרת */
.clRmvse p {
    margin: 0 0 6px 0;            /* רווח קטן בין שורות */
    padding: 0;
    border: 0;                    /* בלי קו מקווקו */
    font-size: 18px;
    font-weight: bold;
    color: #FFA801;               /* כתום */
    text-decoration: underline;   /* קו תחתון כמו בצילום */
}

/* איקס הסרה */
.deleteRmv {
    color: #FFA801;               /* כתום */
    cursor: pointer;
    margin-left: 4px;
    vertical-align: middle;
    font-size: 16px;
}

/* חץ באמצע – מצביע שמאלה */
.rformimg {
    width: 100%;
    max-width: 40px;
    margin: 80px auto;
    display: block;
    transform: none;   /* אם פתאום יוצא הפוך – מחק שורה זו */
}

/* שאר עיצובי הטופס */
.hidden-section { display: none; }
.page3 { font-size: 24px; font-weight: bold; text-align: center; margin-top: 20px; }
.page3line { border-bottom: 3px solid #FFA801; padding-bottom: 10px; margin-bottom: 30px; text-align: center; }
.para { background: #f7f7f7; padding: 10px; border-right: 5px solid #FFA801; font-size: 18px; margin: 20px 0; }
.para-num {
    position: absolute;
    right: 8px;
    top: 8px;
    font-size: 20px;
    width: 27px;          /* גודל 27x27 כמו שביקשת */
    height: 27px;
    line-height: 27px;    /* ממרכז את הספרה אנכית */
    text-align: center;
    border-radius: 4px;
    border: 1px solid #000046;
    background: #ffa801;  /* אם את רוצה שיתמזג עם הפס הכתום */
    color: #000046;       /* אפשר להחליף ללבן אם עדיף: #ffffff */
}
.pagespecialpara {
    margin-bottom: 30px;
    padding: 15px;
    border: 1px solid #eee;
    overflow: hidden;
    position: relative; /* חשוב בשביל ה-absolute של .para-num */
}
.formpara { font-size: 16px; font-weight: bold; color: #333; margin-bottom: 10px; display: block; }

input[type=text], input[type=number], input[type=email], select, textarea {
    width: 100%;
    height: 40px;
    padding: 6px 12px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.row-grid { margin-bottom: 15px; }

.pablo-edu-note {
    font-size: 14px !important;
    line-height: 14px !important;
    font-weight: 400 !important;
    color: #333 !important;
}

.pablo-total-years-label {
    font-size: 14px !important;
    line-height: 40px !important;
    font-weight: 400 !important;
    color: #333 !important;
}

@media (max-width: 767px) {
    .pablo-edu-note,
    .pablo-total-years-label {
        font-size: 13px !important;
    }

    .pablo-total-years-label {
        line-height: 22px !important;
        margin-top: 6px;
    }
}

.fromsent { background: #FFA801; color: white; border: none; padding: 10px 50px; font-size: 20px; margin-top: 20px; }
.paddingZ { padding-left: 0; padding-right: 0; }
.pdht15 { padding-top: 15px; }
.rowpadding { padding-left: 15px; padding-right: 15px; margin-bottom: 15px; }
.rowpaading-1 { padding-left: 15px; padding-right: 15px; margin-bottom: 15px; }
.pdL0 { padding-left: 0 !important; }
.font-icon { color: red; font-size: 10px; position: absolute; top: 10px; left: 25px; z-index: 99; }
.squaredTwo { position: relative; float: right; width: 20px; margin-left: 10px; }

/* NEW: SquaredThree CSS for Terms */
.squaredthree { position: relative; display: block; margin-bottom: 10px; }
.squaredthree fieldset { border: none; padding: 0; margin: 0; }
.squaredthree input[type="checkbox"] { width: 20px; height: 20px; float: right; margin-left: 10px; }

.hide-label-fix { float: right; line-height: 40px; font-size: 14px; margin-top: 0; }
.checkbox-row { min-height: 40px; display: flex; align-items: center; margin-bottom: 10px; }

/* עיצוב נוסף לחלק התשלום והחשבונית (Sections L + M + N) */
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

/* CSS עבור סעיף החשבונית (M) */
.rcontaine {
    width: 100%;
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 3px;
    background-color: #fff;
}
.from1detail {
    font-weight: bold;
    margin-bottom: 5px;
    display: block;
}
.receipts-block p { margin-bottom: 5px; }

/* CSS עבור סעיף התמונה (N) */
.paddXsZ { padding-left: 0; padding-right: 0; }
.frm-sect {
    display: inline-block;
    background-color: #FFA801;
    color: #fff;
    padding: 8px 15px;
    border-radius: 4px;
    cursor: pointer;
    font-weight: bold;
    margin-top: 5px;
    text-align: center;
}
.registr-para-234, .registr-para-2 {
    font-size: 14px;
    line-height: 1.6;
    margin-bottom: 15px;
}

/* CSS עבור סעיף חבר מביא חבר (Ayin) */
.IconPlusEfr {
    margin-top: 10px;
    font-size: 18px;
}
#addddagain {
    color: #FFA801;
    cursor: pointer;
    margin-left: 10px;
}
.deleteEfr {
    color: red;
    cursor: pointer;
}

/* התאמה למסכים צרים */
@media (max-width: 768px) {
    .tree-star .rltdivp {
        max-height: 350px;
        padding: 15px 15px 15px 5px;
    }

    .tree-star ul.DefineTree > li {
        font-size: 18px;
    }

    .tree-star ul.DefineTree > li > ul > li {
        font-size: 16px;
    }
}
</style>




<div class="container form-main-wrapper">
    
    <?php if($msg_success): ?>
        <div class="alert alert-success text-center"><h3><?php echo esc_html($msg_success); ?></h3></div>
    <?php endif; ?>

    <?php if($msg_error): ?>
        <div class="alert alert-danger text-center"><h3><?php echo esc_html($msg_error); ?></h3></div>
    <?php endif; ?>

    <div class="col-md-12 paddingZ">
        <div class="imgytrt3">
            <p class="page3">טופס הרשמה לקורס</p>
            <h1 class="page3line pagtop">טופס הרשמה לקורס תמ''א 38 /פינוי בינוי בסיסי</h1>
        </div>
    </div>

    <form id="registerCourseForm" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" enctype="multipart/form-data" onsubmit="return israeli_submitRegisterForm()">
        <?php wp_nonce_field('pablo_forms_engine_submit', 'pablo_forms_engine_nonce'); ?>
        <input type="hidden" name="action" value="pablo_forms_engine_submit">
        <input type="hidden" name="form_slug" value="course-104">
        <input type="hidden" name="courseName" value="קורס תמ''א 38 /פינוי בינוי בסיסי">
        <input type="hidden" name="courseId" value="104"> 
        <input type="hidden" id="birthDate" name="birthDate">
        <input type="hidden" id="catIds" name="catIds" value="">
        <input type="hidden" id="updating" name="updating" value="0">
        <input type="hidden" name="lang" value="HEB">
        <input type="hidden" id="totalPay" name="totalPay" value="">

        <div class="col-md-12 pagespecialpara br-botom">
            <h2 class="para"><span class="para-num">א</span>פרטים אישיים</h2>
            <div class="contcactfrom">
                <div class="contact-right">
                    <div class="row">
                        <div class="col-md-6 row-grid rowpadding"><i class="fa fa-star font-icon"></i><input name="firstname" id="firstname" placeholder="שם פרטי" required type="text"></div>
                        <div class="col-md-6 row-grid rowpaading-1"><i class="fa fa-star font-icon"></i><input name="lastname" placeholder="שם משפחה" id="lastname" required type="text"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 row-grid rowpadding"><input name="fldExtentName" placeholder="תואר לפני השם" id="fldExtentName" type="text"></div>
                        <div class="col-md-3 row-grid rowpadding"><select name="gender" id="gender" class="option-form"><option value="m">זכר</option><option value="f">נקבה</option></select></div>
                        <div class="col-md-6 row-grid rowpaading-1"><input name="identityNumber" placeholder="מס’ ת.ז." id="identityNumber" type="text"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 row-grid pdht15"><h2 class="formpara">תאריך לידה</h2></div>
                        <div class="col-md-3 row-grid rowpadding pdht15"><select name="birthYear" id="birthYear" class="option-form"><option value="">שנה</option><?php for($i=2007; $i>=1936; $i--) echo "<option value='$i'>$i</option>"; ?></select></div>
                        <div class="col-md-3 row-grid rowpaading-1 pdht15"><select name="birthMonth" id="birthMonth" class="option-form"><option value="">חודש</option><option value="01">01</option><option value="02">02</option><option value="03">03</option><option value="04">04</option><option value="05">05</option><option value="06">06</option><option value="07">07</option><option value="08">08</option><option value="09">09</option><option value="10">10</option><option value="11">11</option><option value="12">12</option></select></div>
                        <div class="col-md-3 row-grid pdht15"><select name="birthDay" id="birthDay" class="option-form"><option value="">יום</option><?php for($i=1; $i<=31; $i++) echo "<option value='".str_pad($i,2,"0",STR_PAD_LEFT)."'>$i</option>"; ?></select></div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 row-grid rowpadding"><h2 class="formpara">אזור השירות</h2></div>
                        <div class="col-md-3 row-grid rowpadding"><select name="fldDialZone" id="fldDialZone" class="option-form"><option value="">אזור חיוג</option><option value="02">02</option><option value="03">03</option><option value="04">04</option><option value="08">08</option><option value="09">09</option></select></div>
                        <div class="col-md-6 row-grid rowpaading-1"><input name="moreDetails" id="moreDetails" placeholder="פרטי מידע משלים אחר" type="text"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 pagespecialpara brtop">
            <h2 class="para"><span class="para-num">ב</span>פרטי יצירת קשר</h2>
            <p class="editing mty center resigter-margin"><i class="fa fa-star font-icon-1"></i>פרטי מידע יצירת קשר המפורטים להלן יופיעו באתר אלא אם כן תבחר לסמן שדה/ות כמוסתר/ים.</p>
            <div class="contcactfrom">
                <div class="contact-right">
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12 row-grid rowpadding"><h2 class="formpara">כתובת מגורים</h2></div>
                        <div class="col-md-6 col-sm-6 col-xs-12 row-grid rowpaading-1"><h2 class="formpara">טלפונים, דוא"ל ואתר</h2></div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 col-sm-4 col-xs-12 row-grid rowpadding">
                            <input name="address" placeholder="רחוב" id="address" type="text">
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-12 row-grid rowpadding">
                            <input name="streetNo" placeholder="מס' בית" id="streetNo" type="text">
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12 row-grid rowpaading-1">
                            <i class="fa fa-star font-icon"></i>
                            <input name="cellphone" placeholder="מס' טלפון סלולרי" id="cellphone" required type="text">
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-12 row-grid pdht15 checkbox-row">
                            <input type="checkbox" name="hideCellphone" class="squaredTwo">
                            <span class="hide-label-fix">הסתר</span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12 row-grid rowpadding">
                            <input name="city" placeholder="ישוב" id="city" type="text">
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12 row-grid rowpaading-1">
                            <input name="phone" placeholder="מס' טלפון בבית" id="phone" type="text">
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-12 row-grid pdht15 checkbox-row">
                            <input type="checkbox" name="hidePhone" class="squaredTwo">
                            <span class="hide-label-fix">הסתר</span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12 row-grid rowpadding">
                            <input name="zipcode" placeholder="מיקוד" id="zipcode" type="text">
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12 row-grid rowpaading-1">
                            <input name="phone2" placeholder="מס' טלפון נוסף" id="phone2" type="text">
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-12 row-grid pdht15 checkbox-row">
                            <input type="checkbox" name="hidePhone2" class="squaredTwo">
                            <span class="hide-label-fix">הסתר</span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12 row-grid rowpadding">
                            <input name="country" placeholder="מדינה" id="country" type="text">
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12 row-grid rowpaading-1">
                            <input name="fax" placeholder="פקס בבית" id="fax" type="text">
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-12 row-grid pdht15 checkbox-row">
                            <input type="checkbox" name="hideFax" class="squaredTwo">
                            <span class="hide-label-fix">הסתר</span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12 row-grid rowpadding">
                            <input name="mailAddress" placeholder="כתובת למשלוח דואר (אם היא שונה מהכתובת הנ&quot;ל)" id="mailAddress" type="text">
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12 row-grid rowpaading-1">
                            <i class="fa fa-star font-icon"></i>
                            <input name="email" placeholder="דוא&quot;ל" required id="email" type="text">
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-12 row-grid pdht15 checkbox-row">
                            <input type="checkbox" name="hideEmail" class="squaredTwo">
                            <span class="hide-label-fix">הסתר</span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12 row-grid rowpadding checkbox-row">
                            <input type="checkbox" name="hideAddress" class="squaredTwo" style="float:right; margin-left:10px;">
                            <span class="hide-label-fix" style="float:right; width: auto;">האם להסתיר את הכתובת?</span>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12 row-grid rowpaading-1">
                            <input name="mySite" placeholder="אתר אינטרנט" id="mySite" type="text">
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-md-12 pagespecialpara brtop">
            <h2 class="para"><span class="para-num">ג</span>פרטי רשתות חברתיות</h2>
            <div class="row">
                <div class="col-md-6 row-grid"><input name="linkedinPage" placeholder="לינקדאין" type="text"></div>
                <div class="col-md-6 row-grid"><input name="skype" placeholder="סקייפ" type="text"></div>
            </div>
            <div class="row">
                <div class="col-md-6 row-grid"><input name="facebookPage" placeholder="פייסבוק" type="text"></div>
                <div class="col-md-6 row-grid"><input name="twitterPage" placeholder="טוויטר" type="text"></div>
            </div>
        </div>

        <div class=" col-md-12  col-sm-12  col-xs-12 pagespecialpara brtop">
					<h2 class="para"><span class="para-num">ד</span>פרטי השכלה</h2>
							 <div class="contcactfrom">
                <div class="contact-right">
                         <div class="row">
                            <div class="col-md-3 col-sm-3  col-xs-12 row-grid rowpadding">
                                <label for="elementarySchool" class="hidden-section">שם בי"ס יסודי</label>
                                <input name="elementarySchool" id="elementarySchool" type="text" placeholder="שם בי&quot;ס יסודי">
                            </div>
                            <div class="col-md-3 col-sm-3  col-xs-12 row-grid rowpadding">
                                <label for="elementarySchoolCity" class="hidden-section">עיר/מדינה</label>
                                <input name="elementarySchoolCity" id="elementarySchoolCity" type="text" placeholder="עיר/מדינה">
                            </div>
                            <div class="col-md-2 col-sm-2  col-xs-12 row-grid rowpadding pablo-edu-note" style="padding-top: 5px; line-height: 14px; ">
                                פרטי בי"ס יסודי<br>לא יופיעו באתר
                            </div>
                            <div class="col-md-4 col-sm-4  col-xs-12 row-grid rowpaading-1">
                                <label for="elementarySchoolYears" class="hidden-section">מס שנות לימוד</label>
                                <input name="elementarySchoolYears" id="elementarySchoolYears" type="text" class="updateYears" placeholder="מס שנות לימוד">
                            </div>

                            <div class="clearfix"></div>
                        </div>
						
                         <div class="row">
                            <div class="col-md-3 col-sm-3  col-xs-12 row-grid rowpadding">
                                <label for="highSchool" class="hidden-section">שם בי"ס תיכון/מקצועי</label>
                                <input name="highSchool" id="highSchool" type="text" placeholder="שם בי&quot;ס תיכון/מקצועי">
                            </div>
                            <div class="col-md-3 col-sm-3  col-xs-12 row-grid rowpadding">
                                <label for="highSchoolCity" class="hidden-section">עיר/מדינה</label>
                                <input name="highSchoolCity" id="highSchoolCity" type="text" placeholder="עיר/מדינה">
                            </div>
                            <div class="col-md-2 col-sm-2  col-xs-12 row-grid rowpadding">
                                <label for="highSchoolFaculty" class="hidden-section">מגמה</label>
                                <input name="highSchoolFaculty" id="highSchoolFaculty" type="text" placeholder="מגמה">
                            </div>
                            <div class="col-md-4 col-sm-4  col-xs-12 row-grid rowpaading-1">
                                <label for="schoolYears" class="hidden-section">מס שנות לימוד</label>
                                <input name="schoolYears" id="schoolYears" type="text" class="updateYears" placeholder="מס שנות לימוד">
                            </div>

                            <div class="clearfix"></div>
                        </div>
						
                        <div class="row">
                            <div class="col-md-3 col-sm-3  col-xs-12 row-grid rowpadding">
                                <label for="university1" class="hidden-section">מוסד להשכלה גבוהה</label>
                                <input name="university1" id="university1" type="text" placeholder="מוסד להשכלה גבוהה">
                            </div>
                            <div class="col-md-3 col-sm-3  col-xs-12 row-grid rowpadding">
                                <label for="university1City" class="hidden-section">עיר/מדינה</label>
                                <input name="university1City" id="university1City" type="text" placeholder="עיר/מדינה">
                            </div>
                            <div class="col-md-2 col-sm-2  col-xs-12 row-grid rowpadding">
                                <label for="university1Faculty" class="hidden-section">פקולטה</label>
                                <input name="university1Faculty" id="university1Faculty" type="text" placeholder="פקולטה">
                            </div>
                            <div class="col-md-1  col-sm-1  col-xs-12 row-grid rowpaading-1 pdL0">
                                <label for="university1Years" class="hidden-section">מס שנות לימוד</label>
                                <input name="university1Years" id="university1Years" type="text" class="updateYears" placeholder="שנה">

                            </div>
                            <div class="col-md-2  col-sm-2  col-xs-12 row-grid pdL0">
                                <label for="university1Degree" class="hidden-section">תואר</label>
                                <input name="university1Degree" id="university1Degree" type="text" placeholder="תואר">
                            </div>

                            <div class="col-md-1  col-sm-1  col-xs-12 row-grid pdht15">
                                <i class="fa fa-plus icoNPlsS" id="addsasa"></i>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="row" id="itemsaa"></div>

						<script>
							jQuery(document).ready(function() {
								var nextSchoolId = 2;
								jQuery("#addsasa").click(function (e) {
									jQuery("#itemsaa").append('<div class="col-md-12 col-sm-12" style="padding:0px;margin-top:10px;"><div class="col-md-3 col-sm-3  col-xs-12 row-grid rowpadding"><label for="university'+nextSchoolId+'" class="hidden-section">מוסד להשכלה גבוהה</label><input name="university'+nextSchoolId+'" id="university'+nextSchoolId+'" type="text" placeholder="מוסד להשכלה גבוהה"></div><div class="col-md-3 col-sm-3  col-xs-12 row-grid rowpadding"><label for="university'+nextSchoolId+'City" class="hidden-section">עיר/מדינה</label><input name="university'+nextSchoolId+'City" id="university'+nextSchoolId+'City" type="text" placeholder="עיר/מדינה"></div><div class="col-md-2 col-sm-2  col-xs-12 row-grid rowpadding"><label for="university'+nextSchoolId+'Faculty" class="hidden-section">פקולטה</label><input name="university'+nextSchoolId+'Faculty" id="university'+nextSchoolId+'Faculty" type="text" placeholder="פקולטה"></div><div class="col-md-1  col-sm-1  col-xs-12 row-grid rowpaading-1"><label for="university'+nextSchoolId+'Years" class="hidden-section">מס שנות לימוד</label><input name="university'+nextSchoolId+'Years" id="university'+nextSchoolId+'Years" type="text" class="updateYears" placeholder="שנה"></div><div class="col-md-2  col-sm-2  col-xs-12 row-grid pdht15"><label for="university'+nextSchoolId+'Degree" class="hidden-section">תואר</label><input name="university'+nextSchoolId+'Degree" id="university'+nextSchoolId+'Degree" type="text" placeholder="תואר"></div><div class="col-md-1  col-sm-1  col-xs-12 row-grid pdht15 delete remove-text-field"><i class="fa fa-minus icoNPlsSec111" ></i></div></div>');
									jQuery("#university"+nextSchoolId+"Years").on("change", updateTotalYears);
									nextSchoolId++;
								});
								jQuery("body").on("click", ".delete", function (e) {
									jQuery(this).parent("div").remove();
									updateTotalYears();
								});
								jQuery(".updateYears").on("change", updateTotalYears);
							});
							function updateTotalYears()
							{
									var count = 0;
									if (isNumeric(jQuery("#elementarySchoolYears").val()))
											count += parseInt(jQuery("#elementarySchoolYears").val(), 10);
									if (isNumeric(jQuery("#schoolYears").val()))
											count += parseInt(jQuery("#schoolYears").val(), 10);
									for (i=1; i<20; i++)
											if (isNumeric(jQuery("#university"+i+"Years").val()))
													count += parseInt(jQuery("#university"+i+"Years").val(), 10);
									jQuery("#totalYears").val(count);
							}
							function isNumeric(n) {
								return !isNaN(parseFloat(n)) && isFinite(n);
							}
                        </script>
                        
                        <div class="row">
                            <div class="col-md-6 col-sm-6  col-xs-12 row-grid rowpadding">
                                <label for="moreSchoolDetails" class="hidden-section">פרטי מידע משלים אחר</label>
                                <input name="moreSchoolDetails" id="moreSchoolDetails" type="text" placeholder="פרטי מידע משלים אחר">
                            </div>
                            <div class="col-md-2 col-sm-2  col-xs-12 row-grid rowpadding pablo-total-years-label">
                                סה"כ שנות לימוד:
                            </div>
                            <div class="col-md-4 col-sm-4  col-xs-12 row-grid rowpaading-1">
                                <label for="totalYears" class="hidden-section">סה"כ שנות לימוד</label>
                                <input name="totalYears" id="totalYears" type="text" placeholder="סה&quot;כ שנות לימוד" disabled="">
                            </div>

                            <div class="clearfix"></div>
                        </div>
                        <div class="row">
                            <p class="frm-pargrph col-md-12">פרט את ראשי התיבות של התואר והיכן נרכש יש לפרט את שם המוסד, העיר והמדינה. אם ציינת 
                      			       תארים, עליך לפרט ולהסביר אותם באופן שכל אדם ממוצע ושאינו קשור לתחום יבין אותם. למשל
			                           תואר DOCTOR of MEDICINE : MD ד"ר - דוקטורט ברפואה - למדתי בבולוניה, איטליה.</p>
                        </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-sm-12 col-xs-12 pagespecialpara brtop">
            <h2 class="para"><span class="para-num">ה</span>ידיעת שפות</h2>
            <div class="contcactfrom">
                <div class="contact-right">
                    <div id="formsdivs">
                        <div class="row" id="formfields1"><div class="col-md-3 col-sm-3 col-xs-12 row-grid pdht15"><span class="spanparalastN formpara"> שפה</span><label for="lang1" class="hidden-section">עברית</label><input name="lang1" id="lang1" value="עברית" type="text" readonly></div><div class="col-md-3 col-sm-3 col-xs-12 row-grid pdht15"><span class="spanparalastN formpara">דיבור</span><label for="lang1speak" class="hidden-section">רמת הדיבור בעברית</label><select name="lang1speak" id="lang1speak" class="option-form" type="text"><option value="">כלל לא</option><option value="1">שפת אם</option><option value="2">טובה</option><option value="3">בסיסית</option></select></div><div class="col-md-3 col-sm-3 col-xs-12 row-grid pdht15"><span class="spanparalastN formpara">קריאה</span><label for="lang1read" class="hidden-section">רמת הקריאה בעברית</label><select name="lang1read" id="lang1read" class="option-form" type="text"><option value="">כלל לא</option><option value="1">שפת אם</option><option value="2">טובה</option><option value="3">בסיסית</option></select></div><div class="col-md-3 col-sm-3 col-xs-12 row-grid pdht15"><span class="spanparalastN formpara">כתיבה</span><label for="lang1write" class="hidden-section">רמת הכתיבה בעברית</label><select name="lang1write" id="lang1write" class="option-form" type="text"><option value="">כלל לא</option><option value="1">שפת אם</option><option value="2">טובה</option><option value="3">בסיסית</option></select></div><div class="clearfix"></div></div>
                        <div class="row" id="formfields2"><div class="col-md-3 col-sm-3 col-xs-12 row-grid pdht15"><input name="lang2" id="lang2" value="אנגלית" type="text" readonly><label for="lang2" class="hidden-section">אנגלית</label></div><div class="col-md-3 col-sm-3 col-xs-12 row-grid pdht15"><label for="lang2speak" class="hidden-section">רמת הדיבור באנגלית</label><select id="lang2speak" name="lang2speak" class="option-form" type="text"><option value="">כלל לא</option><option value="1">שפת אם</option><option value="2">טובה</option><option value="3">בסיסית</option></select></div><div class="col-md-3 col-sm-3 col-xs-12 row-grid pdht15"><label for="lang2read" class="hidden-section">רמת הקריאה באנגלית</label><select name="lang2read" id="lang2read" class="option-form" type="text"><option value="">כלל לא</option><option value="1">שפת אם</option><option value="2">טובה</option><option value="3">בסיסית</option></select></div><div class="col-md-3 col-sm-3 col-xs-12 row-grid pdht15"><label for="lang2write" class="hidden-section">רמת הכתיבה באנגלית</label><select name="lang2write" id="lang2write" class="option-form" type="text"><option value="">כלל לא</option><option value="1">שפת אם</option><option value="2">טובה</option><option value="3">בסיסית</option></select></div><div class="clearfix"></div></div>
                        <div class="row" id="formfields3"><div class="col-md-3 col-sm-3 col-xs-12 row-grid pdht15"><input name="lang3" id="lang3" value="ערבית" type="text" readonly><label for="lang3" class="hidden-section">ערבית</label></div><div class="col-md-3 col-sm-3 col-xs-12 row-grid pdht15"><label for="lang3speak" class="hidden-section">רמת הדיבור בערבית</label><select name="lang3speak" id="lang3speak" class="option-form" type="text"><option value="">כלל לא</option><option value="1">שפת אם</option><option value="2">טובה</option><option value="3">בסיסית</option></select></div><div class="col-md-3 col-sm-3 col-xs-12 row-grid pdht15"><label for="lang3read" class="hidden-section">רמת הקריאה בערבית</label><select name="lang3read" id="lang3read" class="option-form" type="text"><option value="">כלל לא</option><option value="1">שפת אם</option><option value="2">טובה</option><option value="3">בסיסית</option></select></div><div class="col-md-3 col-sm-3 col-xs-12 row-grid pdht15"><label for="lang3write" class="hidden-section">רמת הכתיבה בערבית</label><select name="lang3write" id="lang3write" class="option-form" type="text"><option value="">כלל לא</option><option value="1">שפת אם</option><option value="2">טובה</option><option value="3">בסיסית</option></select></div><div class="clearfix"></div></div>
                    </div>
                    <div class="row IconPlus">
                        <div class="col-md-3 col-sm-3 col-xs-12 row-grid pdht15">
                            <i id="addnewdivfield" onclick="adddd();" class="fa fa-plus" style="cursor: pointer; color: #FFA801;"></i>
                            <i id="delete12" class="fa fa-minus delete12" onclick="removefield();" style="display:none; cursor: pointer; color: red; margin-right: 10px;"></i>
                        </div>
                    </div>
                    <script>
                        var defaultvalue = 3;
                        function adddd() {
                            defaultvalue++;
                            var newRow = '<div class="row" id="formfields' + defaultvalue + '"><div class="col-md-3 col-sm-3 col-xs-12 row-grid pdht15"><input name="lang' + defaultvalue + '" id="lang' + defaultvalue + '" value="" required type="text" placeholder="שפה נוספת"><label for="lang' + defaultvalue + '" class="hidden-section">שם השפה הנוספת</label></div><div class="col-md-3 col-sm-3 col-xs-12 row-grid pdht15"><label for="lang' + defaultvalue + 'speak" class="hidden-section">רמת הדיבור בשפה הנוספת</label><select name="lang' + defaultvalue + 'speak" id="lang' + defaultvalue + 'speak" class="option-form" type="text"><option value="">כלל לא</option><option value="1">שפת אם</option><option value="2">טובה</option><option value="3">בסיסית</option></select></div><div class="col-md-3 col-sm-3 col-xs-12 row-grid pdht15"><label for="lang' + defaultvalue + 'read" class="hidden-section">רמת הקריאה בשפה הנוספת</label><select name="lang' + defaultvalue + 'read" id="lang' + defaultvalue + 'read" class="option-form" type="text"><option value="">כלל לא</option><option value="1">שפת אם</option><option value="2">טובה</option><option value="3">בסיסית</option></select></div><div class="col-md-3 col-sm-3 col-xs-12 row-grid pdht15"><label for="lang' + defaultvalue + 'write" class="hidden-section">רמת הכתיבה בשפה הנוספת</label><select name="lang' + defaultvalue + 'write" id="lang' + defaultvalue + 'write" class="option-form" type="text"><option value="">כלל לא</option><option value="1">שפת אם</option><option value="2">טובה</option><option value="3">בסיסית</option></select></div><div class="clearfix"></div></div>';
                            jQuery("#formsdivs").append(newRow);
                            if (defaultvalue > 3) jQuery("#delete12").show();
                        }
                        function removefield() {
                            if (defaultvalue > 3) { jQuery("#formfields" + defaultvalue).remove(); defaultvalue--; }
                            if (defaultvalue <= 3) jQuery("#delete12").hide();
                        }
                    </script>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-sm-12 col-xs-12 pagespecialpara brtop">
            <h2 class="para"><span class="para-num">ו</span>פרטי מקצוע ועיסוק</h2>
            <div class="contcactfrom"><div class="contact-right"><div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12 row-grid pdht15 extra_mb_field">
                    <p><input name="fldProfession" placeholder="מקצועך" id="fldProfession" type="text"><label for="fldProfession" class="hidden-section">מקצועך</label></p>
                    <p><input name="fldGeneralLongevity" placeholder="מספר שנות עבודה במקצוע" id="fldGeneralLongevity" type="number"><label for="fldGeneralLongevity" class="hidden-section">מספר שנות עבודה במקצוע</label></p>
                    <p><input name="currBiz" placeholder="עיסוקך הנוכחי והגדרת תפקידך" id="currBiz" type="text"><label for="currBiz" class="hidden-section">עיסוקך הנוכחי והגדרת תפקידך</label></p>
                    <p><input name="fldSpecialization" placeholder="תחום התמחות מקצועית" id="fldSpecialization" type="text"><label for="fldSpecialization" class="hidden-section">תחום התמחות מקצועית</label></p>
                    <p><input name="licenseNo" placeholder="עו&quot;ד/רופא – מספר הרשיון" id="licenseNo" type="text"><label for="licenseNo" class="hidden-section">עו"ד/רופא – מספר הרשיון</label></p>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12 row-grid pdht15">
                    <div class="m-t-35"><div class="squaredTwo checkboxset"><fieldset><legend class="hidden-section">checkbox </legend><input value="None" id="experience1" name="experience1" type="checkbox"><label for="experience1" class="hidden-section">האם הכנת חוות דעת מקצועית בחמש השנים האחרונות?</label></fieldset></div><label class="lablSec">האם הכנת חוות דעת מקצועית בחמש השנים האחרונות?</label><textarea id="experience2" name="experience2" class="contmil90" placeholder="ככל שהתשובה חיובית אנא פרט בשתיים שלוש שורות"></textarea></div>
                    <div class=""><div class="squaredTwo checkboxset"><fieldset><legend class="hidden-section">checkbox </legend><input value="None" id="experience3" name="experience3" type="checkbox"><label for="experience3" class="hidden-section">האם הופעת כעד/ה מומחה/ית בבית המשפט בחמש השנים האחרונות?</label></fieldset></div><label class="lablSec">האם הופעת כעד/ה מומחה/ית בבית המשפט בחמש השנים האחרונות?</label><textarea id="experience4" name="experience4" class="contmil909" placeholder="ככל שהתשובה חיובית אנא פרט בשתיים שלוש שורות"></textarea></div>
                </div>
                <div class="clearfix"></div>
            </div></div></div>
        </div>

        <div class="col-md-12 col-sm-12 col-xs-12 pagespecialpara brtop">
            <h2 class="para"><span class="para-num">ז</span>פרטי מקום העבודה</h2>
            <div class="contcactfrom"><div class="contact-right">
                <div class="row"><div class="col-md-6 col-sm-6 col-xs-12 row-grid rowpadding"><input name="workplace" placeholder="שם המקום בו הינך מועסק" id="workplace" type="text"><label for="workplace" class="hidden-section">שם המקום בו הינך מועסק</label></div><div class="col-md-6 col-sm-6 col-xs-12 row-grid rowpaading-1"><input name="workphone" placeholder="מס טלפון בעבודה" id="workphone" type="text"><label for="workphone" class="hidden-section">מס טלפון בעבודה</label></div><div class="clearfix"></div></div>
                <div class="row"><div class="col-md-6 col-sm-6 col-xs-12 row-grid rowpadding"><input name="fldLongevity" placeholder="מספר שנות העבודה בעיסוקך הנוכחי" id="fldLongevity" type="number"><label for="fldLongevity" class="hidden-section">מספר שנות העבודה בעיסוקך הנוכחי</label></div><div class="col-md-6 col-sm-6 col-xs-12 row-grid rowpaading-1"><input name="workfax" placeholder="פקס בעבודה" id="workfax" type="text"><label for="workfax" class="hidden-section">פקס בעבודה</label></div><div class="clearfix"></div></div>
                <div class="row"><div class="col-md-6 col-sm-6 col-xs-12 row-grid rowpadding"><input name="workweb" placeholder="לינק לאתר מקום העבודה" id="workweb" type="text"><label for="workweb" class="hidden-section">לינק לאתר מקום העבודה</label></div><div class="col-md-6 col-sm-6 col-xs-12 row-grid rowpaading-1"><input name="workaddress" placeholder="כתובת מקום העבודה" id="workaddress" type="text"><label for="workaddress" class="hidden-section">כתובת מקום העבודה</label></div></div>      
                <div class="row"><div class="col-md-6 col-sm-6 col-xs-12 row-grid rowpadding"><input name="workaddress1" placeholder="כתובת סניף נוסף" id="workaddress1" type="text"><label for="workaddress1" class="hidden-section">כתובת סניף נוסף</label></div><div class="col-md-6 col-sm-6 col-xs-12 row-grid rowpaading-1"><input name="workaddress2" placeholder="כתובת סניף נוסף" id="workaddress2" type="text"><label for="workaddress2" class="hidden-section">כתובת סניף נוסף</label></div></div>      
            </div></div>
        </div>

        <div class="col-md-12 col-sm-12 col-xs-12 pagespecialpara brtop">
            <h2 class="para"><span class="para-num">ח</span>נגישות מקום העבודה</h2>
            <div class="contcactfrom"><div class="contact-right">
                <div class="row"><div class="col-md-6 col-sm-6 col-xs-12 row-grid rowpadding icon-input-block"><i class="fa fa-bus ii-icon"></i><input name="accessBuses" placeholder="מספרי קווי אוטובוס" id="accessBuses" type="text"><label for="accessBuses" class="hidden-section">מספרי קווי אוטובוס</label></div><div class="col-md-6 col-sm-6 col-xs-12 row-grid rowpaading-1 icon-input-block"><i class="fa fa-train ii-icon"></i><input name="accessTrain" placeholder="תחנת רכבת קרובה" id="accessTrain" type="text"><label for="accessTrain" class="hidden-section">תחנת רכבת קרובה</label></div></div>      
                <div class="row"><div class="col-md-6 col-sm-6 col-xs-12 row-grid rowpadding icon-input-block"><span class="fa-stack fa-3x ii-icon" style="font-size: 10px"><i class="fa fa-square-o fa-stack-2x" style="font-size:22px"></i><strong class="fa-stack-1x icon-text" style="font-size: 12px; margin-top:1px">P</strong></span><input name="accessPark" placeholder="חניון קרוב" id="accessPark" type="text"><label for="accessPark" class="hidden-section">חניון קרוב</label></div><div class="col-md-6 col-sm-6 col-xs-12 row-grid rowpaading-1 icon-input-block"><i class="fa fa-coffee ii-icon"></i><input name="accessCoffee" placeholder="בית קפה קרוב" id="accessCoffee" type="text"><label for="accessCoffee" class="hidden-section">בית קפה קרוב</label></div></div>      
                <div class="row"><div class="col-md-6 col-sm-6 col-xs-12 row-grid rowpadding"><div class="squaredTwo checkboxset"><fieldset><legend class="hidden-section">checkbox </legend><input value="None" id="accessDisabled" name="accessDisabled" type="checkbox"><label for="accessDisabled" class="hidden-section">האם הבניין מונגש לנכים?</label></fieldset></div><label class="lablSec">האם הבניין מונגש לנכים?</label></div><div class="col-md-6 col-sm-6 col-xs-12 row-grid rowpaading-1"><div class="squaredTwo checkboxset"><fieldset><legend class="hidden-section">checkbox </legend><input value="None" id="accessElevator" name="accessElevator" type="checkbox"><label for="accessElevator" class="hidden-section">האם יש מעלית בבניין?</label></fieldset></div><label class="lablSec">האם יש מעלית בבניין?</label></div></div>
            </div></div>
        </div>

        <div class="col-md-12 col-sm-12 col-xs-12 pagespecialpara brtop">
            <h2 class="para"><span class="para-num">ט</span>חברות בארגונים מקצועיים</h2>
            <div class="contcactfrom"><div class="contact-right"><div id="orgsdivs"><div class="row" id="orgfields1"><div class="col-md-6 col-sm-6 col-xs-12 row-grid rowpadding"><input name="org1" placeholder="חבר בארגון" id="org1" type="text"><label for="org1" class="hidden-section">חבר בארגון</label></div><div class="col-md-6 col-sm-6 col-xs-12 row-grid rowpaading-1"><input name="org1link" id="org1link" placeholder="לינק לאתר הארגון" type="text"><label for="org1link" class="hidden-section">לינק לאתר הארגון</label></div><div class="clearfix"></div></div></div></div><div class="row IconPlus"><div class="col-md-3 col-sm-3 col-xs-12 row-grid pdht15"><i id="addneworgfield" onclick="addOrg();" class="fa fa-plus" style="cursor: pointer; color: #FFA801;"></i><i id="delete13" class="fa fa-minus delete12" onclick="removeOrg();" style="display:none; cursor: pointer; color: red; margin-right: 10px;"></i></div></div><script>var orgvalue = 1;function addOrg(){orgvalue++;var newRow = '<div class="row" id="orgfields'+orgvalue+'"><div class="col-md-6 col-sm-6 col-xs-12 row-grid rowpadding"><input name="org'+orgvalue+'" placeholder="חבר בארגון" id="org'+orgvalue+'" type="text"><label for="org'+orgvalue+'" class="hidden-section">חבר בארגון</label></div><div class="col-md-6 col-sm-6 col-xs-12 row-grid rowpaading-1"><input name="org'+orgvalue+'link" id="org'+orgvalue+'link" placeholder="לינק לאתר הארגון" type="text"><label for="org'+orgvalue+'link" class="hidden-section">לינק לאתר הארגון</label></div><div class="clearfix"></div></div>';jQuery("#orgsdivs").append(newRow);if(orgvalue > 1){jQuery("#delete13").show();}}function removeOrg(){jQuery("#orgfields"+orgvalue).remove();orgvalue--;if(orgvalue == 1)jQuery("#delete13").hide();}</script></div>
        </div>

        <div class="col-md-12 col-sm-12 col-xs-12 pagespecialpara brtop">
            <h2 class="para"><span class="para-num">י</span>אבקש לצרף אותי לקטגוריות הבאות</h2>
            <div class="contcactfrom">
                <div class="contact-right">
                    <div class="row">
                        <div class="col-md-7 col-sm-7 col-xs-12 row-grid pdht15">
                            <?php echo function_exists('pablo_forms_engine_render_category_tree') ? pablo_forms_engine_render_category_tree() : ''; ?>
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-12 row-grid "><img src="<?php echo esc_url(get_template_directory_uri()); ?>/designFiles/arrow-iconsilde.png" class="rformimg" alt="arrow-iconsilde"></div>
                        <div class="col-md-4 col-sm-4 col-xs-12 row-grid pdht15">
                            <div class="rltdivp widthrldiv"><div class="col-md-12 col-sm-12 col-xs-12 paddingZ clRmvse"></div></div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class=" col-md-12  col-sm-12  col-xs-12 pagespecialpara brtop">
            <h2 class="para"><span class="para-num">כ</span>הגשת בקשות ליצירת סיווג שלא נכלל בעץ הסיווגים</h2>
            <div class="contcactfrom"><div class="contact-right"><div class="row"><div class="col-md-12  col-sm-12  col-xs-12 row-grid pdht15"><input name="catsExtraDetails" placeholder="פרט כאן בקשתך להוספה והיא תועבר לעיון הנהלת המאגר" type="text" id="catsExtraDetails"><label for="catsExtraDetails" class="hidden-section">פרט כאן בקשתך להוספה והיא תועבר לעיון הנהלת המאגר</label></div></div></div></div>
        </div>

        <div class=" col-md-12  col-sm-12  col-xs-12 pagespecialpara brtop">
            <h2 class="para"><span class="para-num">ל</span>תשלום בכרטיס אשראי</h2> 
            <div class="contcactfrom">
                <div class="row"><div class="col-xs-12 row-grid pdht15"><p class="from1detail"></p></div></div>
                <p class="editing resigter-margin"><i class="fa fa-star font-icon-1"></i>הסכומים הנקובים אינם כוללים מע"מ</p>
                <div class="row">
                    <p class="registr-para">
                        <label for="paymentOption1" class="hidden-section">12 תשלומים</label>
                        <input type="radio" name="paymentOption" id="paymentOption1" class="registr-radio" value="12" onclick="$('#totalSum').val('4764 + מע&quot;מ');$('#totalPay').val(4764)">
                        12 תשלומים שווים ע"ס - 397 ₪ בתוספת מע"מ כחוק.
                    </p>
                    <p class="registr-para">
                        <label for="paymentOption2" class="hidden-section">5 תשלומים</label>
                        <input type="radio" name="paymentOption" id="paymentOption2" class="registr-radio" value="5" onclick="$('#totalSum').val('4275 + מע&quot;מ');$('#totalPay').val(4275)">
                        5 תשלומים שווים ע"ס - 855 ₪ בתוספת מע"מ כחוק.
                    </p>
                    <p class="registr-para">
                        <label for="paymentOption3" class="hidden-section">3 תשלומים</label>
                        <input type="radio" name="paymentOption" id="paymentOption3" class="registr-radio" value="3" onclick="$('#totalSum').val('4185 + מע&quot;מ');$('#totalPay').val(4185)">
                        3 תשלומים שווים ע"ס - 1395 ₪ בתוספת מע"מ כחוק.
                    </p>
                    <p class="registr-para">
                        <label for="paymentOption4" class="hidden-section">1 תשלומים</label>
                        <input type="radio" name="paymentOption" id="paymentOption4" class="registr-radio" value="1" onclick="$('#totalSum').val('3970 + מע&quot;מ');$('#totalPay').val(3970)">
                        1 תשלומים שווים ע"ס - 3970 ₪ בתוספת מע"מ כחוק.
                    </p>
                </div>
            </div>
            <div class="contcactfrom"><div class="contact-right"><div class="row"><div class="col-md-6 col-sm-6 col-xs-12 row-grid pdht15"><input id="totalSum" disabled="" placeholder="סה&quot;כ לתשלום" type="text"><label for="totalSum" class="hidden-section">סה"כ לתשלום</label></div><div class="col-md-6 col-sm-6 col-xs-12 row-grid pdht15"><input name="creditCardHolder" placeholder="שם בעל הכרטיס" id="creditCardHolder" type="text"><label for="creditCardHolder" class="hidden-section">שם בעל הכרטיס</label></div><div class="clearfix"></div></div><div class="row"><div class="col-md-6 col-sm-6 col-xs-12 row-grid pdht15"><input name="creditCardNumber" placeholder="מספר הכרטיס" type="text" id="creditCardNumber"><label for="creditCardNumber" class="hidden-section">מספר הכרטיס</label></div><div class="col-md-6 col-sm-6 col-xs-12 row-grid pdht15"><div class="col-xs-6 fix-pad wid60"><select id="course_creditType" name="course_creditType" class="option-element"><option selected="" disabled="">סוג הכרטיס</option><option value="01">ויזה</option><option value="02">מאסטר קארד</option><option value="03">דיינרס</option><option value="04">ישראכארד</option></select><label for="course_creditType" class="hidden-section">סוג הכרטיס</label></div><div class="col-xs-6 pdht15 fix-pad wid40 paddingZ"><input name="creditCardCVV" type="text" id="creditCardCVV" maxlength="4"><label for="creditCardCVV" class="cvvlabel ">שלוש ספרות בגב הכרטיס</label></div></div><div class="clearfix"></div></div><div class="row"><div class="col-md-6 col-sm-6 col-xs-12 row-grid pdht15"><input name="creditCardTz" placeholder="מספר ת.ז. של בעל הכרטיס" id="creditCardTz" type="text"><label for="creditCardTz" class="hidden-section">מספר ת.ז. של בעל הכרטיס</label></div><div class="col-md-6 col-sm-6 col-xs-12 pdht15"><div class="elementfrm-2 row-grid "><p class="formpara-1">תוקף הכרטיס:</p></div><div class="elementfrm-1 row-grid pdht15"><select id="creditCardMonth" name="creditCardMonth" class="option-element"><option value="01">01</option><option value="02">02</option><option value="03">03</option><option value="04">04</option><option value="05">05</option><option value="06">06</option><option value="07">07</option><option value="08">08</option><option value="09">09</option><option value="10">10</option><option value="11">11</option><option value="12">12</option></select><label for="creditCardMonth" class="hidden-section">תוקף הכרטיס - חודש</label></div><div class="elementfrm row-grid pdht15"><select name="creditCardYear" id="creditCardYear" class="option-element" type="text"><?php $curYear=(int)date('Y');for($i=0;$i<=12;$i++){$y=$curYear+$i;$val=substr($y,-2);echo "<option value='$val'>$y</option>";}?></select><label for="creditCardYear" class="hidden-section">תוקף הכרטיס - שנה</label></div></div></div></div></div>
        </div>

        <div class=" col-md-12  col-sm-12  col-xs-12 pagespecialpara brtop">
            <h2 class="para"><span class="para-num">מ</span>מידע חשבונית</h2>
            <div class="contcactfrom"><div class="contact-right receipts-block"><div class="row"><p class="from1detail">אני מבקש להוציא את החשבונית על שם:</p><input name="name4invoice" id="name4invoice" type="text" class="rcontaine"><label for="name4invoice" class="hidden-section">חשבונית על שם</label><p>&nbsp;</p><div><input value="בית" id="invoiceToHome" name="invoiceTo" checked="" type="radio" class="registr-radio"> אני מבקש לשלוח את החשבונית לכתובת ביתי<label for="invoiceToHome" class="hidden-section">חשבונית לכתובת ביתי</label></div><div><input value="משרד" id="invoiceToOffice" name="invoiceTo" type="radio" class="registr-radio"> אני מבקש לשלוח את החשבונית לכתובת משרדי<label for="invoice2office" class="hidden-section">חשבונית לכתובת משרדי</label></div><p>&nbsp;</p><p class="from1detail">אם לכתובת אחרת, נא פרט להיכן:</p><input name="invoice2addr" id="invoice2addr" type="text" class="rcontaine"><label for="invoice2addr" class="hidden-section">כתובת אחרת למשלוח חשבונית</label></div></div></div>
        </div>

        <div class=" col-md-12  col-sm-12  col-xs-12 pagespecialpara brtop">
            <h2 class="para"><span class="para-num">נ</span>העלאת תמונה</h2>
            <div class="contcactfrom"><div class="contact-right"><div class="row "><p class="from1detail col-md-12 paddXsZ">באפשרותך להעלות תמונה כבר בשלב זה או לאחר שבקשתך תאושר וישלחו אליך שם משתמש וסיסמא.</p><div class="form-1 col-md-12 paddXsZ"><input id="picFileName" type="text" class="rcontaine" disabled=""><input name="picFile" id="picFile" type="file" class="retailrtl" style="display:none" accept="image/*"><label for="picFile" class="frm-sect">בחר קובץ</label></div><p class="col-md-12 paddXsZ">מוכח : כמות המינויים גדולה פי כמה וכמה למי שמתקינים תמונה בפרופיל שלהם, אנו ממליצים על התקנת תמונה , עדיפות לתמונה מקצועית, גברים: מומלצת תמונה מעונבת במקטורן , נשים תמונה בכסות מלאה.</p></div></div></div>
        </div>

        <div class=" col-md-12  col-sm-12  col-xs-12 pagespecialpara brtop ">
            <h2 class="para"><span class="para-num">ס</span>עבר פלילי</h2>
            <div class="contcactfrom"><div class="row"><p class="registr-para-234 poit-mrt col-md-12 paddXsZ">בשל מעמדו/ה המיוחד של הבורר/ת ו/או העד/ה המומחה/ית ושל השירות שעליו/ה לספק, יידרש/תידרש המועמד/ת לחתום על תצהיר המאמת את הפרטים בשאלון הרשמה זה, לרבות העובדה כי אין למועמד/ת הרשעות קודמות בפלילים, וכי למיטב ידיעתו/ה אין כל כוונה להעמידו/ה לדין פלילי. החתימה על התצהיר תהא בפני עו"ד, והיא מהווה חלק בלתי נפרד מתנאי הקבלה והרישום במאגר.</p><p class="registr-para-2 col-md-12 paddXsZ">בכל מקרה של ספק, בעניין מחיקת עבר פלילי, התיישנות עבירות, סוגי עבירות ( כגון: במיוחד תכנון ובניה, תעבורה וכד\' ) תיק פתוח שלא הבשיל כדי העמדה לדין, כל הליך פלילי אחר , אנא פנה אלינו ליעוץ טלפוני מול עו"ד מטעם המכון, הייעוץ אינו כרוך בתשלום ואנו מחויבים ומתחייבים לדיסקרטיות מלאה.</p></div></div>
        </div>

        <div class=" col-md-12  col-sm-12  col-xs-12 pagespecialpara brtop ">
            <h2 class="para"><span class="para-num">ע</span>הנחת מומחה מביא מומחה</h2>
            <div class="contcactfrom">
                <div class="row">
                    <p class="registr-para-234 poit-mrt col-md-12 paddXsZ"> נודה לציון שמות שני מומחים הראויים לדעתך להצטרף להשתלמות/למאגר. ככל שהמלצתך תביא לרישומם 
                    (ולא קדמה להמלצתך פניה מהם/אליהם) אנו נזכה את חשבונך בהנחה בת 5% בהתאם להנחיותיך. </p>
                </div>
            </div>
            <div class="contcactfrom">
                <div class="contact-right">
                    
                    <div id="formsdivsagain">
                        <div class="row" id="formfiele1">
                            <div class="col-md-3 col-sm-3  col-xs-12 row-grid pdht15">
                                <input name="lead1firstname" placeholder="שם פרטי" id="lead1firstname" type="text">
                                <label for="lead1firstname" class="hidden-section">שם פרטי</label>
                            </div>
                            <div class="col-md-3 col-sm-3  col-xs-12 row-grid pdht15">
                                <input name="lead1lastname" placeholder="שם משפחה" id="lead1lastname" type="text">
                                <label for="lead1lastname" class="hidden-section">שם משפחה</label>
                            </div>
                            <div class="col-md-3 col-sm-3  col-xs-12 row-grid pdht15">
                                <input name="lead1cellphone" placeholder="מס נייד"  id="lead1cellphone" type="text">
                                <label for="lead1cellphone" class="hidden-section">מס נייד</label>
                            </div>
                            <div class="col-md-3 col-sm-3  col-xs-12 row-grid pdht15">
                                <input name="lead1profession" placeholder="מקצוע" id="lead1profession" type="text">
                                <label for="lead1profession" class="hidden-section">מקצוע</label>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <div class="row IconPlusEfr">
                        <div class="col-md-3  col-sm-3  col-xs-12 row-grid pdht15">
                            <i id="addddagain" class="fa fa-plus" onclick="addddagain();"></i>
                            <i class="fa fa-minus deleteEfr" id="delete12again" onclick="removefieldagain();" style="display: none;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-sm-12 col-xs-12 pagespecialpara brtop brbotom">
            <h2 class="para"><span class="para-num">פ</span>יש לאשר ההערות והתנאים</h2>
            <div class="contcactfrom">
                <div class="contact-right">
                    <p class="editing mty resigter-margin"><i class="fa fa-star font-icon-1"></i>הנהלת המכון שומרת על זכותה לדחות רישומו ו/או הצטרפותו של כל מגיש בקשה ללא צורך בהנמקה.</p>
                    <p class="editing resigter-margin"><i class="fa fa-star font-icon-1"></i>הנהלת המכון שומרת על זכותה הבלעדית לערוך שינויים בתוכנית ההרצאות ו/או במרצים ו/או באתר <br>הלימודים ו/או במועדים.</p>
                    
                    <p class="editing resigter-margin"><i class="fa fa-star font-icon-1"></i>הנהלת המכון שומרת על זכותה לא להעניק תעודת סיום למי שנמצאו בלתי מתאימים במהלך הקורס,  <br>  ו/או עד למועד הענקת התעודה.</p>
                    <p class="editing resigter-margin"><i class="fa fa-star font-icon-1"></i>בטול השתתפותך בהשתלמות החל מ - 14 יום לפני מועד תחילתה המשוער יחייבך בדמי בטול בסך 15%, <br>ובתוספת של 1% לכל יום נוסף.</p>
                    <p class="editing resigter-margin"><i class="fa fa-star font-icon-1"></i>מחיר הקורס כולל סך של 175 ₪ המהווים דמי חבר לשנה ראשונה.</p>
                    
                    <p class="editing resigter-margin"><i class="fa fa-star font-icon-1"></i>חשבונית תישלח בנפרד לאחר פירעון כל תקבול ו/או זיכוי חשבוננו.</p>
                    <p class="editing resigter-margin"><i class="fa fa-star font-icon-1"></i>קבלה תישלח מיד עם קבלת התשלום.</p>
                    
                    <div class="squaredthree">
                        <fieldset><i class="fa fa-star font-icon-1"></i>
                             <legend class="hidden-section">checkbox </legend>
                             <input value="None" id="squaredthree4" name="check1" type="checkbox">
                             <label for="squaredthree4" class="hidden-section">checkbox</label>
                        </fieldset>
                    </div> 
                    <p class="editing resigter-margin htpad45 sentform">אין לי עבר פלילי ולמיטב ידיעתי אין כל כוונה להעמידני לדין פלילי, (יאומת בתצהיר). </p>
                    
                    <div class="squaredthree">
                        <fieldset><i class="fa fa-star font-icon-1"></i>
                             <legend class="hidden-section">checkbox </legend>
                             <input value="None" id="squaredthree5" name="check2" type="checkbox">
                             <label for="squaredthree5" class="hidden-section">checkbox</label>
                        </fieldset>
                    </div>      
                    <p class="editing resigter-margin htpad45 sentform">כל הפרטים שמסרתי בשאלון הרשמה זה, אמת (יאומת בתצהיר).</p>
                    
                    <div class="squaredthree">
                        <fieldset><i class="fa fa-star font-icon-1"></i>
                             <legend class="hidden-section">checkbox </legend>
                             <input value="None" id="squaredthree6" name="check3" type="checkbox">
                             <label for="squaredthree6" class="hidden-section">checkbox </label>
                        </fieldset>
                    </div>  
                    <p class="editing resigter-margin htpad45">הובא לידיעתי כי יהא עלי לאמת את כל האמור בשאלון זה בתצהיר חתום בפני עורך דין.</p>
                    
                    <div class="squaredthree">
                        <fieldset><i class="fa fa-star font-icon-1"></i>
                             <legend class="hidden-section">checkbox </legend>
                             <input value="None" id="squaredthree60" name="check8" type="checkbox">
                             <label for="squaredthree60" class="hidden-section">checkbox </label>
                        </fieldset>
                    </div>  
                    <p class="editing resigter-margin htpad45">הובא לידיעתי כי פרק הזמן לביצוע הקורס כולל השלמות חיסורים הינו עד 120 יום.</p>               
                    
                    <div class="squaredthree">
                        <fieldset>
                             <legend class="hidden-section">checkbox </legend>
                             <input value="None" id="squaredthree8" name="check5" type="checkbox">
                             <label for="squaredthree8" class="hidden-section">checkbox </label>
                        </fieldset>
                    </div> 
                    <p class="editing resigter-margin htpad45 sentform">במידה ואדרש, אמציא לכם כל תעודה ומסמך הקשורים להשכלתי, מקצועי ועיסוקי.</p>
                    
                    <div class="squaredthree">
                        <fieldset>
                             <legend class="hidden-section">checkbox </legend>
                             <input value="None" id="squaredthree9" name="check6" type="checkbox">
                             <label for="squaredthree9" class="hidden-section">checkbox </label>
                        </fieldset>
                    </div> 
                    <p class="editing resigter-margin htpad45 sentform">קראתי את <a href="index2.php?id=5157&amp;lang=HEB" target="_new">כללי האתיקה</a> של המומחה ואת <a href="index2.php?id=5158&amp;lang=HEB" target="_new">תקנון האתר</a> ואני מתחייב לפעול על פיהם במידה ואצורף.</p>
                    
                    <div class="squaredthree">
                        <fieldset>
                             <legend class="hidden-section">checkbox </legend>
                             <input value="None" id="squaredthree7" name="check4" type="checkbox">
                             <label for="squaredthree7" class="hidden-section">checkbox </label>
                        </fieldset>
                    </div> 
                    <p class="editing resigter-margin htpad45 sentform">ברמת העיקרון אני מוכן להשתתף בהשתלמויות העשרה משפטיות שעורך המכון.</p>
                    
                    <div class="squaredthree">
                        <fieldset>
                             <legend class="hidden-section">checkbox </legend>
                             <input value="None" id="squaredthree10" name="check7" type="checkbox">
                             <label for="squaredthree10" class="hidden-section">checkbox </label>
                        </fieldset>
                    </div> 
                    <p class="editing resigter-margin htpad45 sentform">קראתי את ההערות והתנאים ואני מסכים להם.</p>
                    
                    <div class="row">   
                        <input value="שליחה" class="fromsent " type="submit">
                    </div>  
                </div>
            </div>
        </div>

    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<script>
// Code section written by pablo rotem
jQuery(document).ready(function($) {
    /* * Logic for handling Category Selection from the tree.
     * Appends selected categories to the visual list and hidden input field.
     * - by pablo rotem
     */
    window.mygetvaluetodicv = function(name, id) {
        var currentIds = $("#catIds").val();
        if(currentIds.indexOf("," + id + ",") === -1) {
            $(".clRmvse").append("<p class='pargrph' catId='"+id+"'><i class='fa fa-times iconrtl deleteRmv'></i> <span>"+name+"</span></p>");
            if(currentIds === "") currentIds = ",";
            $("#catIds").val(currentIds + id + ",");
        }
    };
    $("body").on("click", ".deleteRmv", function() {
        var id = $(this).parent("p").attr("catId");
        var cur = $("#catIds").val();
        $("#catIds").val(cur.replace("," + id + ",", ","));
        $(this).parent("p").remove();
    });
    
    /* * Bootstrap Collapse Toggle Logic for Tree View.
     * Handles the plus/minus icons and opening/closing sub-menus.
     * - by pablo rotem
     */
    $('.tree-star').on('click', '.insExtra', function(e) {
        e.preventDefault();
        e.stopPropagation();

        var $btn    = $(this);
        var target  = $btn.attr('href') || $btn.data('target'); 
        if (!target) { return; }

        $(target).collapse('toggle');
    });

    $('.tree-star').on('show.bs.collapse', '.panel-collapse', function () {
        var id = this.id;
        $('.tree-star .insExtra[href="#' + id + '"], .tree-star .insExtra[data-target="#' + id + '"]')
            .removeClass('collapsed');
    });

    $('.tree-star').on('hide.bs.collapse', '.panel-collapse', function () {
        var id = this.id;
        $('.tree-star .insExtra[href="#' + id + '"], .tree-star .insExtra[data-target="#' + id + '"]')
            .addClass('collapsed');
    });

    /* * Dynamic University Rows Logic.
     * Adds new rows for university education details and attaches calculation listeners.
     * - by pablo rotem
     */
    var nextSchoolId = 2;
    $("#addsasa").click(function (e) {
        var newRow = 
        '<div class="col-md-12 col-sm-12" style="padding:0px;margin-top:10px;">' +
            '<div class="col-md-3 col-sm-3 col-xs-12 row-grid rowpadding">' +
                '<label for="university'+nextSchoolId+'" class="hidden-section">מוסד להשכלה גבוהה</label>' +
                '<input name="university'+nextSchoolId+'" id="university'+nextSchoolId+'" type="text" placeholder="מוסד להשכלה גבוהה">' +
            '</div>' +
            '<div class="col-md-3 col-sm-3 col-xs-12 row-grid rowpadding">' +
                '<label for="university'+nextSchoolId+'City" class="hidden-section">עיר/מדינה</label>' +
                '<input name="university'+nextSchoolId+'City" id="university'+nextSchoolId+'City" type="text" placeholder="עיר/מדינה">' +
            '</div>' +
            '<div class="col-md-2 col-sm-2 col-xs-12 row-grid rowpadding">' +
                '<label for="university'+nextSchoolId+'Faculty" class="hidden-section">פקולטה</label>' +
                '<input name="university'+nextSchoolId+'Faculty" id="university'+nextSchoolId+'Faculty" type="text" placeholder="פקולטה">' +
            '</div>' +
            '<div class="col-md-1 col-sm-1 col-xs-12 row-grid rowpaading-1 pdL0">' +
                '<label for="university'+nextSchoolId+'Years" class="hidden-section">מס שנות לימוד</label>' +
                '<input name="university'+nextSchoolId+'Years" id="university'+nextSchoolId+'Years" type="text" class="updateYears" placeholder="שנה">' +
            '</div>' +
            '<div class="col-md-2 col-sm-2 col-xs-12 row-grid pdL0">' +
                '<label for="university'+nextSchoolId+'Degree" class="hidden-section">תואר</label>' +
                '<input name="university'+nextSchoolId+'Degree" id="university'+nextSchoolId+'Degree" type="text" placeholder="תואר">' +
            '</div>' +
            '<div class="col-md-1 col-sm-1 col-xs-12 row-grid pdht15 delete remove-text-field">' +
                '<i class="fa fa-minus icoNPlsSec111"></i>' +
            '</div>' +
        '</div>';

        $("#itemsaa").append(newRow);
        
        $("#university"+nextSchoolId+"Years").on("change", updateTotalYears);
        nextSchoolId++;
    });

    $("body").on("click", ".delete", function (e) {
        $(this).parent("div").remove();
        updateTotalYears();
    });

    /* * Total Years Calculator.
     * Sums up years from elementary, high school, and university inputs.
     * - by pablo rotem
     */
    $(".updateYears").on("change", updateTotalYears);
    
    function updateTotalYears() {
        var count = 0;
        if (isNumeric($("#elementarySchoolYears").val())) count += parseInt($("#elementarySchoolYears").val(), 10);
        if (isNumeric($("#schoolYears").val())) count += parseInt($("#schoolYears").val(), 10);
        for (i=1; i<20; i++) {
            if (isNumeric($("#university"+i+"Years").val())) {
                count += parseInt($("#university"+i+"Years").val(), 10);
            }
        }
        $("#totalYears").val(count);
    }
    function isNumeric(n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
    }

    /* * CVV Field Validation (Visual only).
     * Adds class 'not-empty' or 'empty' based on content.
     * - by pablo rotem
     */
    $("#creditCardCVV").blur(function(){
        var tmpval = $(this).val();
        if(tmpval == "") {
            $(this).addClass("empty").removeClass("not-empty");
        } else {
            $(this).addClass("not-empty").removeClass("empty");
        }
    });
    
    /* * Image Upload Filename Display.
     * Updates the text input when a file is selected.
     * - by pablo rotem
     */
    $('#picFile').change(function() {
        var filename = $(this).val().split('\\').pop();
        $('#picFileName').val(filename);
    });

    /* * Date Builder Logic.
     * Combines Year, Month, and Day selects into a single YYYY-MM-DD hidden input.
     * - by pablo rotem
     */
    function israeli_buildBirthDate() {
        var y = document.getElementById('birthYear') ? document.getElementById('birthYear').value : '';
        var m = document.getElementById('birthMonth') ? document.getElementById('birthMonth').value : '';
        var d = document.getElementById('birthDay') ? document.getElementById('birthDay').value : '';
        if (y && m && d) { document.getElementById('birthDate').value = y + '-' + m + '-' + d; }
    }
    $('#birthYear, #birthMonth, #birthDay').change(israeli_buildBirthDate);
});
function israeli_submitRegisterForm() { return true; }

/* * Friend Referral Dynamic Rows.
 * Adds additional rows for referring friends (Firstname, Lastname, Phone, Profession).
 * - by pablo rotem
 */
var defaultvalueagan=1;
function addddagain(){
    defaultvalueagan++;
    jQuery("#formsdivsagain").append('<div class="row" id="formfiele'+defaultvalueagan+'"><div class="col-md-3 col-sm-3  col-xs-12 row-grid pdht15"><input name="lead'+defaultvalueagan+'firstname" placeholder="שם פרטי" id="lead'+defaultvalueagan+'firstname" type="text"><label for="lead'+defaultvalueagan+'firstname" class="hidden-section">שם פרטי</label></div><div class="col-md-3 col-sm-3  col-xs-12 row-grid pdht15"><input name="lead'+defaultvalueagan+'lastname" placeholder="שם משפחה" id="lead'+defaultvalueagan+'lastname" type="text"><label for="lead'+defaultvalueagan+'lastname" class="hidden-section">שם משפחה</label></div><div class="col-md-3 col-sm-3  col-xs-12 row-grid pdht15"><input name="lead'+defaultvalueagan+'cellphone" placeholder="מס נייד"  id="lead'+defaultvalueagan+'cellphone" type="text"><label for="lead'+defaultvalueagan+'cellphone" class="hidden-section">מס נייד</label></div><div class="col-md-3 col-sm-3  col-xs-12 row-grid pdht15"><input name="lead'+defaultvalueagan+'profession" placeholder="מקצוע" id="lead'+defaultvalueagan+'profession" type="text"><label for="lead'+defaultvalueagan+'profession" class="hidden-section">מקצוע</label></div><div class="clearfix"></div></div>');
    if(defaultvalueagan>1){
        jQuery("#delete12again").show();
    }
}
function removefieldagain(){
    if(defaultvalueagan==2){
        jQuery("#formfiele"+defaultvalueagan).remove();
        defaultvalueagan--;
        jQuery("#delete12again").hide();
    
    }else{
        jQuery("#formfiele"+defaultvalueagan).remove();
        defaultvalueagan--;
    }
}
</script>

<?php 
// =================================================================
// == 8. FOOTER
// =================================================================

/* * Load WordPress Footer.
 * This function pulls in footer.php, which contains standard WP hook wp_footer(),
 * scripts, and the closing body/html tags.
 * - by pablo rotem
 */
get_footer(); 
?>