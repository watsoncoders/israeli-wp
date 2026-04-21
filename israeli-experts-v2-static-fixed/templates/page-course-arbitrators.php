<?php
/**
 * Template Name: עמוד תבנית של קורס בוררים
 * Description: Arbitrators Course Page Template (מרצים + סילבוס + תיאור)
 * Author: pablo rotem
 * Author URI: https://pablo-guides.com
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

// Theme URI
$theme_uri = get_stylesheet_directory_uri();
?>

<style>
    html {
        font-family: sans-serif;
        -ms-text-size-adjust: 100%;
        -webkit-text-size-adjust: 100%;
    }
    *, *:before, *:after {
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
    }

    /* --- התאמות מובייל כלליות --- */
    @media only screen and (max-width: 768px) {

        /* כפתור הרשמה */
        .nbtn {
            width: 96% !important;
            margin: 15px auto 20px auto !important;
            display: block !important;
            float: none !important;
            height: auto !important;
        }

        /* טאבים */
        .pagerows {
            width: 100% !important;
            margin: 0 auto !important;
            padding: 0 !important;
            border-bottom: 3px solid #000046;
        }
        .pagerows .nav.nav-tabs.nav-justified {
            display: flex !important;
            flex-direction: row !important;
            flex-wrap: nowrap !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        .pagerows .nav.nav-tabs.nav-justified > li {
            flex: 1 1 0 !important;
            width: auto !important;
            float: none !important;
        }
        .pagerows .nav.nav-tabs.nav-justified > li > a {
            display: block !important;
            width: 100% !important;
            text-align: center !important;
            font-size: 11px !important;
            padding: 10px 2px !important;
            white-space: nowrap !important;
            margin-bottom: 0 !important;
        }
        .pagerows .nav.nav-tabs.nav-justified > li > a i {
            margin-left: 3px !important;
            display: inline-block !important;
        }

        /* ===== מרצים – כל שורה: [תמונה מימין] [טקסט באמצע] [אייקון משמאל] ===== */

        #section3 .rtl5div-1 {
            display: flex !important;
            flex-direction: row !important; /* ימין לשמאל כי ה-container RTL */
            align-items: center !important;
            justify-content: space-between !important;
            height: auto !important;
            padding: 15px 10px !important;
            margin: 0 0 15px 0 !important;
        }

        /* לבטל float של bootstrap בעמודות בתוך rtl5div-1 */
        #section3 .rtl5div-1 > .col-md-7,
        #section3 .rtl5div-1 > .col-md-5,
        #section3 .rtl5div-1 > .col-sm-7,
        #section3 .rtl5div-1 > .col-sm-5,
        #section3 .rtl5div-1 > .col-xs-12 {
            float: none !important;
            width: auto !important;
            padding: 0 5px !important;
        }

        /* עמודת התמונה + הטקסט – באמצע, תמונה בצד ימין */
        #section3 .rtl5div-1 .lecturer-info-col {
            flex: 1 1 auto !important;
            display: flex !important;
            flex-direction: row-reverse !important; /* תמונה מימין, טקסט משמאל */
            align-items: center !important;
            text-align: right !important;
        }

        /* עמודת האייקון – משמאל */
        #section3 .rtl5div-1 .icon-col {
            flex: 0 0 70px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            text-align: left !important;
        }

        /* תמונת מרצה – מימין, בלי float */
        #section3 .rtl5div-1 img.rtl5img {
            float: none !important;
            display: block !important;
            padding: 0 !important;
            margin-left: 10px !important;
            height: auto !important;
        }

        /* טקסט המרצה – בלי order בכלל */
        #section3 .rtlcources {
            text-align: right !important;
            margin-bottom: 0 !important;
        }

        #section3 .rtl5div-1 .rtl5span-1 {
            font-size: 16px !important;
            font-weight: bold;
        }
        #section3 .rtl5div-1 .rtlcourcespara {
            font-size: 12px !important;
            color: #555;
        }

        /* אייקון כובע */
        #section3 .rtl5div-1 .courseimg,
        #section3 .rtl5div-1 .rtlnewimg-1 {
            float: none !important;
            max-width: 55px !important;
            margin: 0 auto !important;
            display: block !important;
            padding-left: 0 !important;
        }
    }

    /* override כללי – לוודא שאין order על rtlcources בשום מצב */
    #section3 .rtlcources {
        order: 0 !important;
    }
</style>

<div class="ie-container" dir="rtl" lang="he">
  <div class="container-fluid">

    <div class="col-md-12 col-sm-12 col-xs-12 paddingZ">
      <div class="imgytrt5">
        <p class="page3">
          <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="page3span">דף הבית</a> &gt; קורס בוררים
        </p>
        <h1 class="page3line pagtop">קורס בוררים</h1>
      </div>

      <button class="nbtn" onclick="location.href='<?php echo esc_url( home_url( '/טופס-הרשמה-לקורס-בוררים' ) ); ?>'">
        להרשמה לקורס לחץ כאן
        <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/pencil.png" alt="pencilimg" class="img-responsive fltimg">
      </button>
    </div>

    <div class="col-md-12 col-sm-12 col-xs-12 paddingZ">
      <div class="row pagerows">
        <ul class="nav nav-tabs nav-justified rtlpnav page5Tabs">
          <li class="tabcls"><a data-toggle="tab" href="#section1"><i class="fa fa-bookmark"></i> תיאור</a></li>
          <li class="tabcls pding0"><a data-toggle="tab" href="#section2"><i class="fa fa-cube"></i> סילבוס </a></li>
          <li class="active tabcls"><a data-toggle="tab" href="#section3"><i class="fa fa-user"></i> מרצים</a></li>
        </ul>
      </div>

      <div class="tab-content">

        <!-- ===================== תיאור הקורס ===================== -->
        <div id="section1" class="tab-pane fade">
          <div class="col-md-8 col-sm-8 col-xs-12 sectionfont pdrigth">
            <h2 class="rltnewsecton">קורס בוררים</h2>
            <div class="section1cls">
              <p class="bold">הקורס הבא צפוי להתחיל ביום - 24/04/2025</p>
              <p class="bold">&nbsp;</p>
              <p class="bold">עלות הקורס: 2,950 בתוספת מע"מ (תיתכן עליה של עד 10%).</p>
              <p class="bold">&nbsp;</p>

              <p class="normal">
                <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/cube.gif" alt="">
                <span class="bold"> למי מיועד הקורס</span><br>
                אם את/ה בר/ת סמכא בתחום השכלתך-עיסוקך, בעל/ת ידע מיוחד-ניסיון מקצועי-התמחות-כישורים מיוחדים או תחביב, אנו זקוקים לך ומעונינים להכשירך כבורר/ת.
              </p>
              <p class="normal">&nbsp;</p>

              <p class="normal">
                <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/cube.gif" alt="">
                <span class="bold">חיוניות הליך הבוררות בחברה המודרנית</span><br>
                ככל שנוקף הזמן מוצעדת האנושות כולה בקצב התפתחותם המסחרר של עולם המדע, הטכנולוגיה והתקשורת. כחלק בלתי נפרד מתהליך זה השתנה יחס אוכלוסיית כדור הארץ למושג "זמן" ומושג הזמן השתנה בפני עצמו. זמן הנו מצרך יקר המציאות וככל שהוא חולף, מאמירים מחיריו.
              </p>

              <p class="normal">בניגוד לתמורות שחוללו פלאי המדע והטכנולוגיה בכל תחומי חיינו, פסחו שינויים אלו על מערכת המשפט ומטבעה אין היא יכולה להדביק את קצב הזמן. מחמת מצב דברים זה, בחברה המודרנית, בעידודה של מערכת המשפט, החלו להתפתח חלופות לבר משפטיות ליישוב מהיר של סכסוכים ומחלוקות.</p>
              <p class="normal">&nbsp;</p>

              <p class="normal">
                <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/cube.gif" alt="">
                <span class="bold">יתרונות הבוררות</span><br>
                העניין הרב שמגלה החברה המודרנית במוסד הבוררות ככלי רב עוצמה ליישוב סכסוכים בדרכים לבר משפטיות נובע, בין היתר, מיתרונות ייחודיים שמאפיינים הליך זה לעומת התדיינות משפטית רגילה. הליכי הבוררות יעילים משום שהבורר לא נכפה על בעלי הדין, אלא מתמנה בהסכמתם ההדדית ; הבורר אמון על הצדדים היות ולרוב הוא נבחר כמי שמהווה סמכות בלתי מעורערת בתחום ידע ספציפי או מחמת תכונותיו האישיות; בניגוד לדעה הרווחת אין חובה כי הבורר יהא משפטן, עו"ד או שופט בדימוס; משך הליך הבוררות הנו קצר ביותר ומתייחסים אליו על פי רוב כפסק סופי וחלוט, בין שהוסכם על ידי הצדדים להסכם הבוררות על אפשרות ערעור על פסק הבורר לפי סעיף 29 ב' לחוק שהערעור נסוב ע"פ רשימת העילות הסגורה לפי סעיף 24 לחוק. למעט מקרה קיצון בעליל, בימ"ש של ערעור על פסק לעולם בורר יפרש ע"פ רוב את עילות הערעור על דרך הצמצום במטרה להותיר את פסק הבורר על כנו.
              </p>
              <p class="normal">&nbsp;</p>

              <p class="normal">
                <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/cube.gif" alt="">
                <span class="bold">תחומי הבוררות</span><br>
                תחומי הבוררות רבים ומגוונים ומקיפים, בין השאר, את עולם העסקים, הרפואה, ההנדסה, המקרקעין, המדעים, הבניה, הארכיטקטורה, החקלאות, האלקטרוניקה, התקשורת, הצרכנות, האמנות, הספורט, ועוד עשרות תחומים נוספים.
              </p>
              <p class="normal">&nbsp;</p>

              <p class="normal">
                <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/cube.gif" alt="">
                <span class="bold">שכר הבוררים</span><br>
                בשל הביקוש ההולך והגובר לפתרון סכסוכים במסגרת הליכי הבוררות, מתוגמלים הבוררים היטב עבור שירותיהם. שכר טרחת הבורר תלוי במומחיותו, השכלתו, ניסיונו המקצועי בתחום הרלוונטי, מהיקף התיק, שוויו הכספי, והמוניטין שצבר הבורר במהלך תקופת פעילותו.
              </p>
              <p class="normal">&nbsp;</p>

              <p class="normal">
                <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/cube.gif" alt="">
                <span class="bold">מה יעניק לך הקורס </span><br>
                בקורס להכשרת הבוררים שעורך המכון הישראלי המתפרש על פני 4 מפגשים, בני שלוש עד ארבע שעות (סה"כ כ-14 שעות לימוד), תלמד את עקרונות יישוב הסכסוך בדרך בוררות ובכלל זה : ניהול הבוררות וסמכויות הבורר, כתיבת פסקי בורר, ותרכוש לך ידע ומיומנות אותם תוכל ליישם באופן מקצועי ויעיל בפתרון ויישוב סכסוכים בכל תחום.
              </p>
              <p class="normal">&nbsp;</p>

              <p class="normal">המכון הישראלי לחוות דעת מומחים ובוררים צבר ניסיון עשיר ומוניטין רב בהכשרת בוררים הפועלים בהצלחה לצידה של המערכת המשפטית בישראל.</p>
              <p class="normal">
                <br>· למסיימי הקורס תוענק תעודה המאשרת את הכשרתם והחתומה בידי אישיות משפטית בכירה.
                <br>· רישום במאגר ההתמחות המלא למקצועות המשפטיים והעזר משפטיים.
              </p>
            </div>
          </div>

          <div class="col-md-4 col-sm-4 col-xs-12 paddXsZ">
            <div class="newrtltab">
              <h3 class="para">מאפייני הקורס</h3>
              <p><span>משך הקורס: שש (6) הרצאות מתוכן ארבע (4) בני שעתיים ( 5 שעות אקדמיות ) ועוד שתיים (2) בנות שעה. המשתתף יתבקש להקדיש מספר שעות לקריאת חומר הכנה לקראת חלק מן ההרצאות. </span></p>
              <p><span>הרצאות: 25</span></p>
              <p><span>שפה: עברית</span></p>
            </div>
          </div>
        </div><!-- /#section1 -->

        <!-- ===================== סילבוס ===================== -->
        <div id="section2" class="tab-pane fade">
          <div class="newrtl5">

            <h2 class="pnewrtl sylabusDay" data-toggle="collapse" href="#day1">
              <i class="fa fa-plus"></i> &nbsp; יום 1: מבוא לבוררות
            </h2>

            <div id="day1" class="panel panel-collapse collapse">
              <button class="accordion csection1" data-toggle="collapse" href="#lesson38">
                <p>
                  <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/page-icon4.png" alt="" class="img-responsive">
                  <span>הרצאה 1.1</span> המגמות והטעמים לפתרון סכסוכים באמצעות הליכי בוררות
                </p>
              </button>
              <div class="panel panel-collapse collapse" id="lesson38">
                <div class="rtl5para">
                  <div class="urtlproject"></div>
                  <div class="rtl5div">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <img src="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/5333_size1.jpg" alt="" class="img-responsive lessonLecturer">
                      <p class="rtl5span">כב' השופט (בדימ.) משה גל</p>
                      <span>מנהל בתי המשפט לשעבר ונשיא בית משפט (בדימ.)</span>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12 syllabusLesson">
                      <a href="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/5333_link2.docx" target="_blank">
                        <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/psd-icon.png" alt="" class="img-responsive rtlnewimg rtyuimg page56margi-1">
                      </a>
                      <p class="syllabusDuration">משך ההרצאה: 40/45 דקות</p>
                    </div>
                  </div>
                </div>
              </div>

              <button class="accordion csection1" data-toggle="collapse" href="#lesson40">
                <p>
                  <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/page-icon4.png" alt="" class="img-responsive">
                  <span>הרצאה 1.2</span> נושאים הראויים להידון מחוץ לכותלי בית המשפט לעומת נושאים שאינם ראויים להידון מחוץ לבית המשפט
                </p>
              </button>
              <div class="panel panel-collapse collapse" id="lesson40">
                <div class="rtl5para">
                  <div class="urtlproject"></div>
                  <div class="rtl5div">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <img src="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/5133_size1.jpg" alt="" class="img-responsive lessonLecturer">
                      <p class="rtl5span">כב' השופטת ד"ר  דרורה פלפל</p>
                      <span>סגנית ( בדימוס ) נשיא ביהמ"ש המחוזי ת"א, מרצה בפקולטה למשפטים אוני' ת"א</span>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12 syllabusLesson">
                      <a href="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/5133_link2.docx" target="_blank">
                        <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/psd-icon.png" alt="" class="img-responsive rtlnewimg rtyuimg page56margi-1">
                      </a>
                      <p class="syllabusDuration">משך ההרצאה: 40/45 דקות</p>
                    </div>
                  </div>
                </div>
              </div>

              <button class="accordion csection1" data-toggle="collapse" href="#lesson42">
                <p>
                  <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/page-icon4.png" alt="" class="img-responsive">
                  <span>הרצאה 1.3</span> יתרונות הבוררות על הגישור
                </p>
              </button>
              <div class="panel panel-collapse collapse" id="lesson42">
                <div class="rtl5para">
                  <div class="urtlproject"></div>
                  <div class="rtl5div">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <img src="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/5133_size1.jpg" alt="" class="img-responsive lessonLecturer">
                      <p class="rtl5span">כב' השופטת ד"ר  דרורה פלפל</p>
                      <span>סגנית ( בדימוס ) נשיא ביהמ"ש המחוזי ת"א, מרצה בפקולטה למשפטים אוני' ת"א</span>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12 syllabusLesson">
                      <a href="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/5133_link2.docx" target="_blank">
                        <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/psd-icon.png" alt="" class="img-responsive rtlnewimg rtyuimg page56margi-1">
                      </a>
                      <p class="syllabusDuration">משך ההרצאה: 40/45 דקות</p>
                    </div>
                  </div>
                </div>
              </div>

              <button class="accordion csection1" data-toggle="collapse" href="#lesson43">
                <p>
                  <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/page-icon4.png" alt="" class="img-responsive">
                  <span>הרצאה 1.4</span> חוק הבוררות ותקנותיו
                </p>
              </button>
              <div class="panel panel-collapse collapse" id="lesson43">
                <div class="rtl5para">
                  <div class="urtlproject"></div>
                  <div class="rtl5div">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <img src="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/5133_size1.jpg" alt="" class="img-responsive lessonLecturer">
                      <p class="rtl5span">כב' השופטת ד"ר  דרורה פלפל</p>
                      <span>סגנית ( בדימוס ) נשיא ביהמ"ש המחוזי ת"א, מרצה בפקולטה למשפטים אוני' ת"א</span>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12 syllabusLesson">
                      <a href="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/5133_link2.docx" target="_blank">
                        <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/psd-icon.png" alt="" class="img-responsive rtlnewimg rtyuimg page56margi-1">
                      </a>
                      <p class="syllabusDuration">משך ההרצאה: 40/45 דקות</p>
                    </div>
                  </div>
                </div>
              </div>

              <button class="accordion csection1" data-toggle="collapse" href="#lesson78">
                <p>
                  <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/page-icon4.png" alt="" class="img-responsive">
                  <span>הרצאה 1.5</span> מעלות הבוררות על ההליך השיפוטי
                </p>
              </button>
              <div class="panel panel-collapse collapse" id="lesson78">
                <div class="rtl5para">
                  <div class="urtlproject"></div>
                  <div class="rtl5div">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <img src="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/5133_size1.jpg" alt="" class="img-responsive lessonLecturer">
                      <p class="rtl5span">כב' השופטת ד"ר  דרורה פלפל</p>
                      <span>סגנית ( בדימוס ) נשיא ביהמ"ש המחוזי ת"א, מרצה בפקולטה למשפטים אוני' ת"א</span>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12 syllabusLesson">
                      <a href="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/5133_link2.docx" target="_blank">
                        <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/psd-icon.png" alt="" class="img-responsive rtlnewimg rtyuimg page56margi-1">
                      </a>
                      <p class="syllabusDuration">משך ההרצאה: 40/45 דקות</p>
                    </div>
                  </div>
                </div>
              </div>
            </div><!-- /#day1 -->

            <h2 class="pnewrtl sylabusDay" data-toggle="collapse" href="#day2">
              <i class="fa fa-plus"></i> &nbsp; יום 2: הסכם הבוררות
            </h2>

            <div id="day2" class="panel panel-collapse collapse">
              <button class="accordion csection1" data-toggle="collapse" href="#lesson60">
                <p>
                  <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/page-icon4.png" alt="" class="img-responsive">
                  <span>הרצאה 2.1</span> חובות הבורר כלפי בעלי הדין
                </p>
              </button>
              <div class="panel panel-collapse collapse" id="lesson60">
                <div class="rtl5para">
                  <div class="urtlproject"></div>
                  <div class="rtl5div">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <img src="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/5333_size1.jpg" alt="" class="img-responsive lessonLecturer">
                      <p class="rtl5span">כב' השופט (בדימ.) משה גל</p>
                      <span>מנהל בתי המשפט לשעבר ונשיא בית משפט (בדימ.)</span>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12 syllabusLesson">
                      <a href="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/5333_link2.docx" target="_blank">
                        <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/psd-icon.png" alt="" class="img-responsive rtlnewimg rtyuimg page56margi-1">
                      </a>
                      <p class="syllabusDuration">משך ההרצאה: 40/45 דקות</p>
                    </div>
                  </div>
                </div>
              </div>

              <button class="accordion csection1" data-toggle="collapse" href="#lesson47">
                <p>
                  <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/page-icon4.png" alt="" class="img-responsive">
                  <span>הרצאה 2.2</span> ההתקשרות בהסכם הבוררות : על פי חוק החוזים ? רצון חפשי ?  הסכם שנכרת כדין ?
                </p>
              </button>
              <div class="panel panel-collapse collapse" id="lesson47">
                <div class="rtl5para">
                  <div class="urtlproject"></div>
                  <div class="rtl5div">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <img src="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/5333_size1.jpg" alt="" class="img-responsive lessonLecturer">
                      <p class="rtl5span">כב' השופט (בדימ.) משה גל</p>
                      <span>מנהל בתי המשפט לשעבר ונשיא בית משפט (בדימ.)</span>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12 syllabusLesson">
                      <a href="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/5333_link2.docx" target="_blank">
                        <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/psd-icon.png" alt="" class="img-responsive rtlnewimg rtyuimg page56margi-1">
                      </a>
                      <p class="syllabusDuration">משך ההרצאה: 40/45 דקות</p>
                    </div>
                  </div>
                </div>
              </div>

              <button class="accordion csection1" data-toggle="collapse" href="#lesson48">
                <p>
                  <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/page-icon4.png" alt="" class="img-responsive">
                  <span>הרצאה 2.3</span> הרחבה בע"פ של הסכם הבוררות, תנאי הבוררות, תוספת להסכם הבוררות
                </p>
              </button>
              <div class="panel panel-collapse collapse" id="lesson48">
                <div class="rtl5para">
                  <div class="urtlproject"></div>
                  <div class="rtl5div">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <img src="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/5333_size1.jpg" alt="" class="img-responsive lessonLecturer">
                      <p class="rtl5span">כב' השופט (בדימ.) משה גל</p>
                      <span>מנהל בתי המשפט לשעבר ונשיא בית משפט (בדימ.)</span>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12 syllabusLesson">
                      <a href="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/5333_link2.docx" target="_blank">
                        <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/psd-icon.png" alt="" class="img-responsive rtlnewimg rtyuimg page56margi-1">
                      </a>
                      <p class="syllabusDuration">משך ההרצאה: 40/45 דקות</p>
                    </div>
                  </div>
                </div>
              </div>

              <button class="accordion csection1" data-toggle="collapse" href="#lesson44">
                <p>
                  <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/page-icon4.png" alt="" class="img-responsive">
                  <span>הרצאה 2.4</span> תיקון מס' 2 לחוק הבוררות.
                </p>
              </button>
              <div class="panel panel-collapse collapse" id="lesson44">
                <div class="rtl5para">
                  <div class="urtlproject"></div>
                  <div class="rtl5div">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <img src="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/5333_size1.jpg" alt="" class="img-responsive lessonLecturer">
                      <p class="rtl5span">כב' השופט (בדימ.) משה גל</p>
                      <span>מנהל בתי המשפט לשעבר ונשיא בית משפט (בדימ.)</span>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12 syllabusLesson">
                      <a href="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/5333_link2.docx" target="_blank">
                        <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/psd-icon.png" alt="" class="img-responsive rtlnewimg rtyuimg page56margi-1">
                      </a>
                      <p class="syllabusDuration">משך ההרצאה: 40/45 דקות</p>
                    </div>
                  </div>
                </div>
              </div>

              <button class="accordion csection1" data-toggle="collapse" href="#lesson49">
                <p>
                  <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/page-icon4.png" alt="" class="img-responsive">
                  <span>הרצאה 2.5</span> פטור מן הדין המהותי, תוחלת ההסכם, פטור מדין מהותי, סדרי דין ודיני ראיות
                </p>
              </button>
              <div class="panel panel-collapse collapse" id="lesson49">
                <div class="rtl5para">
                  <div class="urtlproject"></div>
                  <div class="rtl5div">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <img src="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/5333_size1.jpg" alt="" class="img-responsive lessonLecturer">
                      <p class="rtl5span">כב' השופט (בדימ.) משה גל</p>
                      <span>מנהל בתי המשפט לשעבר ונשיא בית משפט (בדימ.)</span>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12 syllabusLesson">
                      <a href="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/5333_link2.docx" target="_blank">
                        <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/psd-icon.png" alt="" class="img-responsive rtlnewimg rtyuimg page56margi-1">
                      </a>
                      <p class="syllabusDuration">משך ההרצאה: 40/45 דקות</p>
                    </div>
                  </div>
                </div>
              </div>
            </div><!-- /#day2 -->

            <h2 class="pnewrtl sylabusDay" data-toggle="collapse" href="#day3">
              <i class="fa fa-plus"></i> &nbsp; יום 3: ניהול הבוררות
            </h2>

            <div id="day3" class="panel panel-collapse collapse">
              <button class="accordion csection1" data-toggle="collapse" href="#lesson50">
                <p>
                  <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/page-icon4.png" alt="" class="img-responsive">
                  <span>הרצאה 3.1</span> שיטות ניהול הבוררות , הליכים מקדמיים, סדר הטיעון
                </p>
              </button>
              <div class="panel panel-collapse collapse" id="lesson50">
                <div class="rtl5para">
                  <div class="urtlproject"></div>
                  <div class="rtl5div">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <img src="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/115_size1.jpg" alt="" class="img-responsive lessonLecturer">
                      <p class="rtl5span">כב' השופט ( בדימ.) אמנון סטרשנוב</p>
                      <span>שופט ( לשעבר ) בית המשפט המחוזי תל אביב.</span>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12 syllabusLesson">
                      <a href="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/115_link1.docx" target="_blank">
                        <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/psd-icon.png" alt="" class="img-responsive rtlnewimg rtyuimg page56margi-1">
                      </a>
                      <p class="syllabusDuration">משך ההרצאה: 40/45 דקות</p>
                    </div>
                  </div>
                </div>
              </div>

              <button class="accordion csection1" data-toggle="collapse" href="#lesson51">
                <p>
                  <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/page-icon4.png" alt="" class="img-responsive">
                  <span>הרצאה 3.2</span> סדרי הבוררות (מוצגים, קבילות, איסורים, עדויות, תצהירים ראשיים)
                </p>
              </button>
              <div class="panel panel-collapse collapse" id="lesson51">
                <div class="rtl5para">
                  <div class="urtlproject"></div>
                  <div class="rtl5div">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <img src="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/115_size1.jpg" alt="" class="img-responsive lessonLecturer">
                      <p class="rtl5span">כב' השופט ( בדימ.) אמנון סטרשנוב</p>
                      <span>שופט ( לשעבר ) בית המשפט המחוזי תל אביב.</span>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12 syllabusLesson">
                      <a href="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/115_link1.docx" target="_blank">
                        <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/psd-icon.png" alt="" class="img-responsive rtlnewimg rtyuimg page56margi-1">
                      </a>
                      <p class="syllabusDuration">משך ההרצאה: 40/45 דקות</p>
                    </div>
                  </div>
                </div>
              </div>

              <button class="accordion csection1" data-toggle="collapse" href="#lesson52">
                <p>
                  <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/page-icon4.png" alt="" class="img-responsive">
                  <span>הרצאה 3.3</span> דיני ראיות, חסיון, גילוי עברות תוך כדי מהלך הבוררות, הזכות להימנע מהפללה עצמית
                </p>
              </button>
              <div class="panel panel-collapse collapse" id="lesson52">
                <div class="rtl5para">
                  <div class="urtlproject"></div>
                  <div class="rtl5div">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <img src="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/115_size1.jpg" alt="" class="img-responsive lessonLecturer">
                      <p class="rtl5span">כב' השופט ( בדימ.) אמנון סטרשנוב</p>
                      <span>שופט ( לשעבר ) בית המשפט המחוזי תל אביב.</span>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12 syllabusLesson">
                      <a href="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/115_link1.docx" target="_blank">
                        <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/psd-icon.png" alt="" class="img-responsive rtlnewimg rtyuimg page56margi-1">
                      </a>
                      <p class="syllabusDuration">משך ההרצאה: 40/45 דקות</p>
                    </div>
                  </div>
                </div>
              </div>

              <button class="accordion csection1" data-toggle="collapse" href="#lesson54">
                <p>
                  <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/page-icon4.png" alt="" class="img-responsive">
                  <span>הרצאה 3.4</span> חסיון על מסמכים שהוכנו לקראת משפט וחסיון על עדויות ומסמכים שהוכנו במו"מ לפשרה.
                </p>
              </button>
              <div class="panel panel-collapse collapse" id="lesson54">
                <div class="rtl5para">
                  <div class="urtlproject"></div>
                  <div class="rtl5div">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <img src="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/115_size1.jpg" alt="" class="img-responsive lessonLecturer">
                      <p class="rtl5span">כב' השופט ( בדימ.) אמנון סטרשנוב</p>
                      <span>שופט ( לשעבר ) בית המשפט המחוזי תל אביב.</span>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12 syllabusLesson">
                      <a href="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/115_link1.docx" target="_blank">
                        <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/psd-icon.png" alt="" class="img-responsive rtlnewimg rtyuimg page56margi-1">
                      </a>
                      <p class="syllabusDuration">משך ההרצאה: 40/45 דקות</p>
                    </div>
                  </div>
                </div>
              </div>

              <button class="accordion csection1" data-toggle="collapse" href="#lesson53">
                <p>
                  <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/page-icon4.png" alt="" class="img-responsive">
                  <span>הרצאה 3.5</span> מינוי מומחה לעזרת הבורר, ביקור במקום, סיכומים, שמירת התיק.
                </p>
              </button>
              <div class="panel panel-collapse collapse" id="lesson53">
                <div class="rtl5para">
                  <div class="urtlproject"></div>
                  <div class="rtl5div">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <img src="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/115_size1.jpg" alt="" class="img-responsive lessonLecturer">
                      <p class="rtl5span">כב' השופט ( בדימ.) אמנון סטרשנוב</p>
                      <span>שופט ( לשעבר ) בית המשפט המחוזי תל אביב.</span>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12 syllabusLesson">
                      <a href="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/115_link1.docx" target="_blank">
                        <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/psd-icon.png" alt="" class="img-responsive rtlnewimg rtyuimg page56margi-1">
                      </a>
                      <p class="syllabusDuration">משך ההרצאה: 40/45 דקות</p>
                    </div>
                  </div>
                </div>
              </div>
            </div><!-- /#day3 -->

            <h2 class="pnewrtl sylabusDay" data-toggle="collapse" href="#day4">
              <i class="fa fa-plus"></i> &nbsp; יום 4: פסק הבורר
            </h2>

            <div id="day4" class="panel panel-collapse collapse">
              <button class="accordion csection1" data-toggle="collapse" href="#lesson55">
                <p>
                  <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/page-icon4.png" alt="" class="img-responsive">
                  <span>הרצאה 4.1</span> סוגי החלטות, החלטה, פסק ביניים, פסק חלקי, פסק סופי, פשרה
                </p>
              </button>
              <div class="panel panel-collapse collapse" id="lesson55">
                <div class="rtl5para">
                  <div class="urtlproject"></div>
                  <div class="rtl5div">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <img src="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/115_size1.jpg" alt="" class="img-responsive lessonLecturer">
                      <p class="rtl5span">כב' השופט ( בדימ.) אמנון סטרשנוב</p>
                      <span>שופט ( לשעבר ) בית המשפט המחוזי תל אביב.</span>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12 syllabusLesson">
                      <a href="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/115_link1.docx" target="_blank">
                        <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/psd-icon.png" alt="" class="img-responsive rtlnewimg rtyuimg page56margi-1">
                      </a>
                      <p class="syllabusDuration">משך ההרצאה: 40/45 דקות</p>
                    </div>
                  </div>
                </div>
              </div>

              <button class="accordion csection1" data-toggle="collapse" href="#lesson56">
                <p>
                  <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/page-icon4.png" alt="" class="img-responsive">
                  <span>הרצאה 4.2</span> הכנת הפסק (ההנמקה, ניתוח ראיות, טענת התיישנות)
                </p>
              </button>
              <div class="panel panel-collapse collapse" id="lesson56">
                <div class="rtl5para">
                  <div class="urtlproject"></div>
                  <div class="rtl5div">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <img src="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/115_size1.jpg" alt="" class="img-responsive lessonLecturer">
                      <p class="rtl5span">כב' השופט ( בדימ.) אמנון סטרשנוב</p>
                      <span>שופט ( לשעבר ) בית המשפט המחוזי תל אביב.</span>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12 syllabusLesson">
                      <a href="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/115_link1.docx" target="_blank">
                        <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/psd-icon.png" alt="" class="img-responsive rtlnewimg rtyuimg page56margi-1">
                      </a>
                      <p class="syllabusDuration">משך ההרצאה: 40/45 דקות</p>
                    </div>
                  </div>
                </div>
              </div>

              <button class="accordion csection1" data-toggle="collapse" href="#lesson57">
                <p>
                  <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/page-icon4.png" alt="" class="img-responsive">
                  <span>הרצאה 4.3</span> לקראת סיום: (הצורך בבהירות {פקיד הוצל"פ} ), פסיקת ריבית והצמדה, ריבית פיגורים, פסיקת הוצאות ושכ"ט
                </p>
              </button>
              <div class="panel panel-collapse collapse" id="lesson57">
                <div class="rtl5para">
                  <div class="urtlproject"></div>
                  <div class="rtl5div">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <img src="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/115_size1.jpg" alt="" class="img-responsive lessonLecturer">
                      <p class="rtl5span">כב' השופט ( בדימ.) אמנון סטרשנוב</p>
                      <span>שופט ( לשעבר ) בית המשפט המחוזי תל אביב.</span>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12 syllabusLesson">
                      <a href="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/115_link1.docx" target="_blank">
                        <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/psd-icon.png" alt="" class="img-responsive rtlnewimg rtyuimg page56margi-1">
                      </a>
                      <p class="syllabusDuration">משך ההרצאה: 40/45 דקות</p>
                    </div>
                  </div>
                </div>
              </div>

              <button class="accordion csection1" data-toggle="collapse" href="#lesson59">
                <p>
                  <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/page-icon4.png" alt="" class="img-responsive">
                  <span>הרצאה 4.4</span> אישור הפסק ,("טעות סופר"), השלמה בשל היסח הדעת לצורך העמדה על דיוק, תיקון בידי בימ"ש
                </p>
              </button>
              <div class="panel panel-collapse collapse" id="lesson59">
                <div class="rtl5para">
                  <div class="urtlproject"></div>
                  <div class="rtl5div">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <img src="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/115_size1.jpg" alt="" class="img-responsive lessonLecturer">
                      <p class="rtl5span">כב' השופט ( בדימ.) אמנון סטרשנוב</p>
                      <span>שופט ( לשעבר ) בית המשפט המחוזי תל אביב.</span>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12 syllabusLesson">
                      <a href="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/115_link1.docx" target="_blank">
                        <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/psd-icon.png" alt="" class="img-responsive rtlnewimg rtyuimg page56margi-1">
                      </a>
                      <p class="syllabusDuration">משך ההרצאה: 40/45 דקות</p>
                    </div>
                  </div>
                </div>
              </div>

              <button class="accordion csection1" data-toggle="collapse" href="#lesson58">
                <p>
                  <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/page-icon4.png" alt="" class="img-responsive">
                  <span>הרצאה 4.5</span> סטטוס הפסק, ביטול הפסק/עילות
                </p>
              </button>
              <div class="panel panel-collapse collapse" id="lesson58">
                <div class="rtl5para">
                  <div class="urtlproject"></div>
                  <div class="rtl5div">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <img src="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/115_size1.jpg" alt="" class="img-responsive lessonLecturer">
                      <p class="rtl5span">כב' השופט ( בדימ.) אמנון סטרשנוב</p>
                      <span>שופט ( לשעבר ) בית המשפט המחוזי תל אביב.</span>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12 syllabusLesson">
                      <a href="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/115_link1.docx" target="_blank">
                        <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/psd-icon.png" alt="" class="img-responsive rtlnewimg rtyuimg page56margi-1">
                      </a>
                      <p class="syllabusDuration">משך ההרצאה: 40/45 דקות</p>
                    </div>
                  </div>
                </div>
              </div>
            </div><!-- /#day4 -->

            <h2 class="pnewrtl sylabusDay" data-toggle="collapse" href="#day5">
              <i class="fa fa-plus"></i> &nbsp; יום 5: מידענות ומיצוי פוטנצייאל
            </h2>

            <div id="day5" class="panel panel-collapse collapse">
              <button class="accordion csection1" data-toggle="collapse" href="#lesson31">
                <p>
                  <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/page-icon4.png" alt="" class="img-responsive">
                  <span>הרצאה 5.1</span> מידענות ואינטרנט
                </p>
              </button>
              <div class="panel panel-collapse collapse" id="lesson31">
                <div class="rtl5para">
                  <div class="urtlproject"></div>
                  <div class="rtl5div">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <img src="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/5362_size1.jpg" alt="" class="img-responsive lessonLecturer">
                      <p class="rtl5span">פרופ'. יהודית בר אילן</p>
                      <span>המחלקה למדעי המידע , אוניברסיטת בר אילן</span>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12 syllabusLesson">
                      <a href="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/5362_link2.docx" target="_blank">
                        <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/psd-icon.png" alt="" class="img-responsive rtlnewimg rtyuimg page56margi-1">
                      </a>
                      <p class="syllabusDuration">משך ההרצאה: 40/45 דקות</p>
                    </div>
                  </div>
                </div>
              </div>

              <button class="accordion csection1" data-toggle="collapse" href="#lesson32">
                <p>
                  <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/page-icon4.png" alt="" class="img-responsive">
                  <span>הרצאה 5.2</span> טכניקות איתור מידע
                </p>
              </button>
              <div class="panel panel-collapse collapse" id="lesson32">
                <div class="rtl5para">
                  <div class="urtlproject"></div>
                  <div class="rtl5div">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <img src="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/5362_size1.jpg" alt="" class="img-responsive lessonLecturer">
                      <p class="rtl5span">פרופ'. יהודית בר אילן</p>
                      <span>המחלקה למדעי המידע , אוניברסיטת בר אילן</span>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12 syllabusLesson">
                      <a href="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/5362_link2.docx" target="_blank">
                        <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/psd-icon.png" alt="" class="img-responsive rtlnewimg rtyuimg page56margi-1">
                      </a>
                      <p class="syllabusDuration">משך ההרצאה: 40/45 דקות</p>
                    </div>
                  </div>
                </div>
              </div>

              <button class="accordion csection1" data-toggle="collapse" href="#lesson33">
                <p>
                  <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/page-icon4.png" alt="" class="img-responsive">
                  <span>הרצאה 5.3</span> מיומנות במיקוד השליטה העצמית, המקורות, גורמי וביטויי הלחץ, מנגנוני ייחוס והעברה
                </p>
              </button>
              <div class="panel panel-collapse collapse" id="lesson33">
                <div class="rtl5para">
                  <div class="urtlproject"></div>
                  <div class="rtl5div">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <img src="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/5163_size1.jpg" alt="" class="img-responsive lessonLecturer">
                      <p class="rtl5span">פרופ' מרואן דוירי</p>
                      <span>פרופ' מן המניין, המכללה האקדמית אורנים </span>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12 syllabusLesson">
                      <a href="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/5163_link2.docx" target="_blank">
                        <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/psd-icon.png" alt="" class="img-responsive rtlnewimg rtyuimg page56margi-1">
                      </a>
                      <p class="syllabusDuration">משך ההרצאה: 40/45 דקות</p>
                    </div>
                  </div>
                </div>
              </div>

              <button class="accordion csection1" data-toggle="collapse" href="#lesson34">
                <p>
                  <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/page-icon4.png" alt="" class="img-responsive">
                  <span>הרצאה 5.4</span> מיצוי ומיקסום פוטנצייאל  (א)
                </p>
              </button>
              <div class="panel panel-collapse collapse" id="lesson34">
                <div class="rtl5para">
                  <div class="urtlproject"></div>
                  <div class="rtl5div">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <img src="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/5130_size1.jpg" alt="" class="img-responsive lessonLecturer">
                      <p class="rtl5span">ד"ר צבי ברק</p>
                      <span>מנהל מכון גישות למצוינות</span>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12 syllabusLesson">
                      <a href="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/5130_link2.docx" target="_blank">
                        <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/psd-icon.png" alt="" class="img-responsive rtlnewimg rtyuimg page56margi-1">
                      </a>
                      <p class="syllabusDuration">משך ההרצאה: 40/45 דקות</p>
                    </div>
                  </div>
                </div>
              </div>

              <button class="accordion csection1" data-toggle="collapse" href="#lesson35">
                <p>
                  <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/page-icon4.png" alt="" class="img-responsive">
                  <span>הרצאה 5.5</span> מיצוי ומיקסום פוטנצייאל (א) : מיומנות נרכשת,  סימון היעדים וחתירה מתמדת לשיפור
                </p>
              </button>
              <div class="panel panel-collapse collapse" id="lesson35">
                <div class="rtl5para">
                  <div class="urtlproject"></div>
                  <div class="rtl5div">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <img src="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/5130_size1.jpg" alt="" class="img-responsive lessonLecturer">
                      <p class="rtl5span">ד"ר צבי ברק</p>
                      <span>מנהל מכון גישות למצוינות</span>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12 syllabusLesson">
                      <a href="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/5130_link2.docx" target="_blank">
                        <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/psd-icon.png" alt="" class="img-responsive rtlnewimg rtyuimg page56margi-1">
                      </a>
                      <p class="syllabusDuration">משך ההרצאה: 40/45 דקות</p>
                    </div>
                  </div>
                </div>
              </div>
            </div><!-- /#day5 -->

          </div>
        </div><!-- /#section2 -->

        <!-- ===================== מרצים ===================== -->
        <div id="section3" class="tab-pane fade in active">

            <!-- כרטיס מרצה 1 -->
            <div class="rtl5div-1">
                <div class="col-md-7 col-sm-7 col-xs-12 lecturer-info-col paddXsZ">
                    <img src="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/5151_size1.jpg" alt="פרופ` אהרון אנקר" class="img-responsive rtl5img">
                    <h2 class="rtlcources">
                        <span class="rtl5span-1">פרופ' אהרון אנקר</span><br>
                        <span class="rtlcourcespara">יו"ר סגל ההוראה במכון, דיקן ( לשעבר) הפקולטה למשפטים, אוני' בר-אילן</span>
                    </h2>
                </div>
                <div class="col-md-5 col-sm-5 col-xs-12 icon-col paddXsZ">
                    <p>
                        <a href="<?php echo esc_url( home_url( '/file-viewer/?file=5151_link1.docx&folder=lecturersFiles' ) ); ?>">
                            <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/college-cap-icon.png" alt="" class="img-responsive rtlnewimg-1 courseimg">
                        </a>
                    </p>
                </div>
            </div>

            <!-- כרטיס מרצה 2 -->
            <div class="rtl5div-1">
                <div class="col-md-7 col-sm-7 col-xs-12 lecturer-info-col paddXsZ">
                    <img src="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/5133_size1.jpg" alt="כב` השופטת ד&quot;ר  דרורה פלפל" class="img-responsive rtl5img">
                    <h2 class="rtlcources">
                        <span class="rtl5span-1">כב' השופטת ד"ר  דרורה פלפל</span><br>
                        <span class="rtlcourcespara">סגנית ( בדימוס ) נשיא ביהמ"ש המחוזי ת"א, מרצה בפקולטה למשפטים אוני' ת"א</span>
                    </h2>
                </div>
                <div class="col-md-5 col-sm-5 col-xs-12 icon-col paddXsZ">
                    <p>
                        <a href="<?php echo esc_url( home_url( '/file-viewer/?file=5133_link2.docx&folder=lecturersFiles' ) ); ?>">
                            <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/college-cap-icon.png" alt="" class="img-responsive rtlnewimg-1 courseimg">
                        </a>
                    </p>
                </div>
            </div>

            <!-- כרטיס מרצה 3 -->
            <div class="rtl5div-1">
                <div class="col-md-7 col-sm-7 col-xs-12 lecturer-info-col paddXsZ">
                    <img src="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/115_size1.jpg" alt="כב` השופט ( בדימ.) אמנון סטרשנוב" class="img-responsive rtl5img">
                    <h2 class="rtlcources">
                        <span class="rtl5span-1">כב' השופט ( בדימ.) אמנון סטרשנוב</span><br>
                        <span class="rtlcourcespara">שופט ( לשעבר ) בית המשפט המחוזי תל אביב.</span>
                    </h2>
                </div>
                <div class="col-md-5 col-sm-5 col-xs-12 icon-col paddXsZ">
                    <p>
                        <a href="<?php echo esc_url( home_url( '/file-viewer/?file=115_link1.docx&folder=lecturersFiles' ) ); ?>">
                            <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/college-cap-icon.png" alt="" class="img-responsive rtlnewimg-1 courseimg">
                        </a>
                    </p>
                </div>
            </div>

            <!-- כרטיס מרצה 4 -->
            <div class="rtl5div-1">
                <div class="col-md-7 col-sm-7 col-xs-12 lecturer-info-col paddXsZ">
                    <img src="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/5162_size1.jpg" alt="פרופ`, עו&quot;ד  קנת  מן" class="img-responsive rtl5img">
                    <h2 class="rtlcources">
                        <span class="rtl5span-1">פרופ', עו"ד  קנת  מן</span><br>
                        <span class="rtlcourcespara">הוגה, יוזם, ומקים הסנגוריה הציבורית בישראל </span>
                    </h2>
                </div>
                <div class="col-md-5 col-sm-5 col-xs-12 icon-col paddXsZ">
                    <p>
                        <a href="<?php echo esc_url( home_url( '/file-viewer/?file=5162_link2.docx&folder=lecturersFiles' ) ); ?>">
                            <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/college-cap-icon.png" alt="" class="img-responsive rtlnewimg-1 courseimg">
                        </a>
                    </p>
                </div>
            </div>

            <!-- כרטיס מרצה 5 -->
            <div class="rtl5div-1">
                <div class="col-md-7 col-sm-7 col-xs-12 lecturer-info-col paddXsZ">
                    <img src="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/5129_size1.jpg" alt="פרופ`, עו&quot;ד מיגל  דויטש" class="img-responsive rtl5img">
                    <h2 class="rtlcources">
                        <span class="rtl5span-1">פרופ', עו"ד מיגל  דויטש</span><br>
                        <span class="rtlcourcespara">פרופסור מן המניין, הפקולטה למשפטים, אוניברסיטת תל אביב.</span>
                    </h2>
                </div>
                <div class="col-md-5 col-sm-5 col-xs-12 icon-col paddXsZ">
                    <p>
                        <a href="<?php echo esc_url( home_url( '/file-viewer/?file=5129_link1.docx&folder=lecturersFiles' ) ); ?>">
                            <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/college-cap-icon.png" alt="" class="img-responsive rtlnewimg-1 courseimg">
                        </a>
                    </p>
                </div>
            </div>

            <!-- כרטיס מרצה 6 -->
            <div class="rtl5div-1">
                <div class="col-md-7 col-sm-7 col-xs-12 lecturer-info-col paddXsZ">
                    <img src="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/5362_size1.jpg" alt="פרופ`. יהודית בר אילן" class="img-responsive rtl5img">
                    <h2 class="rtlcources">
                        <span class="rtl5span-1">פרופ'. יהודית בר אילן</span><br>
                        <span class="rtlcourcespara">המחלקה למדעי המידע , אוניברסיטת בר אילן</span>
                    </h2>
                </div>
                <div class="col-md-5 col-sm-5 col-xs-12 icon-col paddXsZ">
                    <p>
                        <a href="<?php echo esc_url( home_url( '/file-viewer/?file=5362_link2.docx&folder=lecturersFiles' ) ); ?>">
                            <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/college-cap-icon.png" alt="" class="img-responsive rtlnewimg-1 courseimg">
                        </a>
                    </p>
                </div>
            </div>

            <!-- כרטיס מרצה 7 -->
            <div class="rtl5div-1">
                <div class="col-md-7 col-sm-7 col-xs-12 lecturer-info-col paddXsZ">
                    <img src="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/5163_size1.jpg" alt="פרופ` מרואן דוירי" class="img-responsive rtl5img">
                    <h2 class="rtlcources">
                        <span class="rtl5span-1">פרופ' מרואן דוירי</span><br>
                        <span class="rtlcourcespara">פרופ' מן המניין, המכללה האקדמית אורנים </span>
                    </h2>
                </div>
                <div class="col-md-5 col-sm-5 col-xs-12 icon-col paddXsZ">
                    <p>
                        <a href="<?php echo esc_url( home_url( '/file-viewer/?file=5163_link2.docx&folder=lecturersFiles' ) ); ?>">
                            <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/college-cap-icon.png" alt="" class="img-responsive rtlnewimg-1 courseimg">
                        </a>
                    </p>
                </div>
            </div>

            <!-- כרטיס מרצה 8 -->
            <div class="rtl5div-1">
                <div class="col-md-7 col-sm-7 col-xs-12 lecturer-info-col paddXsZ">
                    <img src="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/5166_size1.jpg" alt="ד&quot;ר עו&quot;ד  חן קוגל" class="img-responsive rtl5img">
                    <h2 class="rtlcources">
                        <span class="rtl5span-1">ד"ר עו"ד  חן קוגל</span><br>
                        <span class="rtlcourcespara">המרכז הלאומי לרפואה משפטית ( אבו כביר ) </span>
                    </h2>
                </div>
                <div class="col-md-5 col-sm-5 col-xs-12 icon-col paddXsZ">
                    <p>
                        <a href="<?php echo esc_url( home_url( '/file-viewer/?file=5166_link2.docx&folder=lecturersFiles' ) ); ?>">
                            <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/college-cap-icon.png" alt="" class="img-responsive rtlnewimg-1 courseimg">
                        </a>
                    </p>
                </div>
            </div>

            <!-- כרטיס מרצה 9 -->
            <div class="rtl5div-1">
                <div class="col-md-7 col-sm-7 col-xs-12 lecturer-info-col paddXsZ">
                    <img src="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/5165_size1.jpg" alt="ד&quot;ר עו&quot;ד  שמואל ילינק" class="img-responsive rtl5img">
                    <h2 class="rtlcources">
                        <span class="rtl5span-1">ד"ר עו"ד  שמואל ילינק</span><br>
                        <span class="rtlcourcespara">עו"ד ומרצה בפקולטה למשפטים באוניברסיטת בר אילן</span>
                    </h2>
                </div>
                <div class="col-md-5 col-sm-5 col-xs-12 icon-col paddXsZ">
                    <p>
                        <a href="<?php echo esc_url( home_url( '/file-viewer/?file=5165_link2.docx&folder=lecturersFiles' ) ); ?>">
                            <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/college-cap-icon.png" alt="" class="img-responsive rtlnewimg-1 courseimg">
                        </a>
                    </p>
                </div>
            </div>

            <!-- כרטיס מרצה 10 -->
            <div class="rtl5div-1">
                <div class="col-md-7 col-sm-7 col-xs-12 lecturer-info-col paddXsZ">
                    <img src="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/5154_size1.jpg" alt="ד&quot;ר עו&quot;ד עמוס הרמן" class="img-responsive rtl5img">
                    <h2 class="rtlcources">
                        <span class="rtl5span-1">ד"ר עו"ד עמוס הרמן</span><br>
                        <span class="rtlcourcespara">מרצה מן המניין, הפקולטה למשפטים, המרכז האקדמי שערי מדע ומשפט </span>
                    </h2>
                </div>
                <div class="col-md-5 col-sm-5 col-xs-12 icon-col paddXsZ">
                    <p>
                        <a href="<?php echo esc_url( home_url( '/file-viewer/?file=5154_link1.docx&folder=lecturersFiles' ) ); ?>">
                            <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/college-cap-icon.png" alt="" class="img-responsive rtlnewimg-1 courseimg">
                        </a>
                    </p>
                </div>
            </div>

            <!-- כרטיס מרצה 11 -->
            <div class="rtl5div-1">
                <div class="col-md-7 col-sm-7 col-xs-12 lecturer-info-col paddXsZ">
                    <img src="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/5130_size1.jpg" alt="ד&quot;ר צבי ברק" class="img-responsive rtl5img">
                    <h2 class="rtlcources">
                        <span class="rtl5span-1">ד"ר צבי ברק</span><br>
                        <span class="rtlcourcespara">מנהל מכון גישות למצוינות</span>
                    </h2>
                </div>
                <div class="col-md-5 col-sm-5 col-xs-12 icon-col paddXsZ">
                    <p>
                        <a href="<?php echo esc_url( home_url( '/file-viewer/?file=5130_link2.docx&folder=lecturersFiles' ) ); ?>">
                            <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/college-cap-icon.png" alt="" class="img-responsive rtlnewimg-1 courseimg">
                        </a>
                    </p>
                </div>
            </div>

            <!-- כרטיס מרצה 12 -->
            <div class="rtl5div-1">
                <div class="col-md-7 col-sm-7 col-xs-12 lecturer-info-col paddXsZ">
                    <img src="<?php echo esc_url( $theme_uri ); ?>/assets/lecturersFiles/5164_size1.jpg" alt="מהנדס מחשבים אמיר שקד" class="img-responsive rtl5img">
                    <h2 class="rtlcources">
                        <span class="rtl5span-1">מהנדס מחשבים אמיר שקד</span><br>
                        <span class="rtlcourcespara">תכנון והקמת תשתיות מידע </span>
                    </h2>
                </div>
                <div class="col-md-5 col-sm-5 col-xs-12 icon-col paddXsZ">
                    <p>
                        <a href="<?php echo esc_url( home_url( '/file-viewer/?file=5164_link2.docx&folder=lecturersFiles' ) ); ?>">
                            <img src="<?php echo esc_url( $theme_uri ); ?>/assets/designFiles/college-cap-icon.png" alt="" class="img-responsive rtlnewimg-1 courseimg">
                        </a>
                    </p>
                </div>
            </div>

        </div><!-- /#section3 -->

      </div><!-- /.tab-content -->
    </div><!-- /.col-12 -->
  </div><!-- /.container-fluid -->
</div><!-- /.ie-container -->

<?php
get_footer();
