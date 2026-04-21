<?php
/**
 * Template Name: אודות המכון
 * Description: Fully static template for the About/Regulations page. No Loop, no DB content.
 * Fixed by Pablo Rotem: domain-agnostic assets, Bootstrap grid fixes, external links hardened, scoped CSS helpers.
 */

if (!defined('ABSPATH')) { exit; }

get_header();

// Fixed by Pablo Rotem: local path for the small cube bullet image (put cube.gif in /assets/images/)
$cube_uri = esc_url( get_stylesheet_directory_uri() . '/assets/images/cube.gif' );
?>
<main id="main" class="site-main" role="main">

  <!-- Fixed by Pablo Rotem: scoped minimal helpers for this static template only -->
  <style>
    .ix-page-wrap p.normal { margin-bottom: 12px; line-height: 1.9; }
    .ix-page-wrap .bold { font-weight: 700; }
    .ix-page-wrap .page3 { font-size: 14px; letter-spacing: .02em; opacity:.85; margin: 10px 0 6px; }
    .ix-page-wrap .page3line { margin-top: 0; margin-bottom: 22px; }
    .ix-page-wrap .para { margin: 12px 0 16px; }
    .ix-page-wrap .piortl img.ix-cube { width: 10px; height: 10px; vertical-align: baseline; margin-inline-end: 6px; }
    /* Fixed by Pablo Rotem: small RTL tweak so bullets sit nicely with Hebrew text */
    html[dir="rtl"] .ix-page-wrap .piortl img.ix-cube { margin-inline-start: 6px; margin-inline-end: 0; }
  </style>

  <div class="container-fluid ix-page-wrap">
    <div class="container paddXsZ">

      <!-- Fixed by Pablo Rotem: add .row wrappers for proper Bootstrap grid flow -->
      <div class="row paddXsZ">
        <div class="col-md-12 col-sm-12 col-xs-12 paddXsZ">
          <p class="page3">אודות המכון</p>
          <h1 class="page3line">על המכון הישראלי לחוות דעת מומחים ובוררים</h1>
        </div>
      </div>

      <div class="row paddXsZ">
        <div class="col-md-12 col-sm-12 col-xs-12 pagespecial-1 paddXsZ">
          <h2 class="para">אודות המכון</h2>

          <!-- Fixed by Pablo Rotem: corrected Bootstrap offset class (col-offset-1 ➜ col-sm-offset-1/col-md-offset-1) -->
          <div class="col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-12 piortl">

            <p class="normal">
              המכון הישראלי לחוות דעת מומחים ובוררים מונה רבבות חברים, רובם עדים מומחים ובוררים, מקצתם מגשרים.
            </p>

            <p class="normal">
              על בסיס חוות דעת מומחי המכון נפסקו אלפי פסקי דין והוכרעו אין ספור סכסוכים, מומחי המכון משרתים בין השאר גם את מערכת המשפט על כל קשת גווניה, חלקם נבחר לשמש כמומחים ממונים מטעם בתי המשפט אחרים כמומחים מטעם צד , קהילת עורכי הדין עושה שימוש בחוות דעת מומחי המכון לצורך ביסוס וחיזוק כתבי טענות.
            </p>

            <p class="normal">
              על חברי המכון נימנת צמרת הפרופסורה המקצועית בישראל: אלפי מומחים, שמאים, מהנדסים, רואי חשבון, אדריכלים, אקטוארים, פסיכולוגים, מדענים וחוקרים, בלשנים משפטיים, טוקסולוגים, מיקרוביולוגים, מנעולנים, נטורופטים, גרפולוגים, סקסולוגים, מפענחי תצלומי אויר, קרקע וביסוס, שמאים, משפטנים, חוקרי תאונות, מבקרי איכות, בוחני בטיחות, מוהלים, ייננים, תוקנים, ועוד מאות תחומי עיסוק ועשיה כמפורט במפתח הסיווגים במאגר.
            </p>

            <!-- Fixed by Pablo Rotem: hardened external link (https, target+rel) -->
            <p class="normal">
              <span style="font-weight: 400;">למידע נוסף על המאגר: </span>
              <a href="https://www.israelbar.org.il/article_inner.asp?pgId=6674&amp;catId=287" target="_blank" rel="noopener">
                <span style="font-weight: 400;">https://www.israelbar.org.il/article_inner.asp?pgId=6674&amp;catId=287</span>
              </a>
            </p>

            <p class="normal">
              <img class="ix-cube" src="<?php echo $cube_uri; ?>" alt="">
              <span class="bold">ישוב סכסוכים</span><br>
              המכון משמש כמרכז לישוב סכסוכים מחוץ לכותלי בית המשפט, בהליכי בוררות וגישור.
            </p>

            <p class="normal">
              <img class="ix-cube" src="<?php echo $cube_uri; ?>" alt="">
              <span class="bold">בוררות:</span><br>
              בוררי המכון כוללים שופטי בתי משפט בדימוס ובוררים סקטורייאליים הפועלים ליישוב סכסוכים בפרקי זמן קצרים.
            </p>

            <p class="normal">
              <img class="ix-cube" src="<?php echo $cube_uri; ?>" alt="">
              <span class="bold">גישור:</span><br>
              המכון מפעיל שרותי גישור ופישור לישוב סכסוכים באמצעות צוותים מקצועיים.
            </p>

            <p class="normal">
              <img class="ix-cube" src="<?php echo $cube_uri; ?>" alt="">
              <span class="bold">הכשרת חברים חדשים</span><br>
              שערי המכון נפתחים מידי מספר שנים לקליטת והכשרת מכסת חברים חדשים.
            </p>

            <p class="normal">
              <img class="ix-cube" src="<?php echo $cube_uri; ?>" alt="">
              <span class="bold">התנאים להרשמת עדים מומחים הינם:</span>
            </p>

            <ol class="normal" style="margin-top:0;padding-top:0;">
              <li>ע"פ השכלתו ו/או עיסקו ו/או מקצועו ו/או תחביבו, נחזה המועמד כבעל ידע יחודי.</li>
              <li>יש בהכללת המועמד במאגר המכון כדי לתרום לשיפור מגוון תחומי המומחיות או לשיפור השרות.</li>
              <li>המועמד חתם על תצהיר בדבר העדר עבר פלילי לרבות העובדה כי למיטב ידיעתו אין תלויים ועומדים כנגדו תביעות פליליות.</li>
              <li>המועמד קיבל על עצמו להשתתף השתתפות פעילה במסגרת ההכשרה העזר משפטית הנערכת מטעם המכון תוך התחייבות לקיים נוכחות שלא תפחת מ- 75% מההרצאות בפועל, כתנאי לקבלתו.</li>
              <li>המועמד לא נפסל במהלך הקורס ו/או עד ליום הענקת התעודה (בדר"כ 120 יום ממועד סיום הקורס).</li>
            </ol>

            <p class="normal">
              <img class="ix-cube" src="<?php echo $cube_uri; ?>" alt="">
              <span class="bold">התנאים להרשמת בוררים:</span>
            </p>

            <ol class="normal" style="margin-top:0;padding-top:0;">
              <li>העדר עבר פלילי.</li>
              <li>אין תנאים אחרים.</li>
            </ol>

            <p class="normal">
              <img class="ix-cube" src="<?php echo $cube_uri; ?>" alt="">
              <span class="bold">סילבוס הקורס וסגל ההוראה</span><br>
              בראש סגל ההוראה ניצב פרופסור אהרן אנקר - פרופסור מן המניין באוניברסיטת בר-אילן והדיקן לשעבר בפקולטה למשפטים של האוניברסיטה.
            </p>

            <p class="normal">
              במכון הישראלי לחוות דעת מומחים מרצים והרצו בכירי מערכת המשפט בישראל, שופטים, פרופסורים למשפטים, עורכי דין ומומחים בעלי ותק מקצועי וניסיון עשיר, בראש סגל ההוראה של המכון מכהן פרופסור אהרון אנקר, פרופ' חבר באוניברסיטת בר-אילן והדיקן (לשעבר) בפקולטה למשפטים של האוניברסיטה.
            </p>

            <p class="normal">
              הנהלת המכון חבה תודה לכל מי שסייעו להקמת המכון והמאגר וכן תודה מיוחדת לשופטי בתי משפט השלום, המחוזי והעליון, פרופסורים ודקני הפקולטות למשפטים אשר סייעו בהכנת סילבוס הקורס ושימשו כמרצים.
            </p>

          </div><!-- /.col -->
        </div><!-- /.col -->
      </div><!-- /.row -->

    </div><!-- /.container -->
  </div><!-- /.container-fluid -->
</main>

<?php get_footer(); ?>
