<?php
/*
 * Template Name: דעת משתמשי המאגר
 * Description: Final page template showing users appreciation list with correct file mapping.
 */

if ( ! defined( 'ABSPATH' ) ) exit; 

get_header(); 

// 1. Paths
$VIEWER_BASE = home_url('/file-viewer/?folder=users&file=');
$ICON_URL    = get_stylesheet_directory_uri() . '/assets/designFiles/page-icon.png';

// 2. FINAL CORRECT FILE MAPPING (from users_images_final.csv)
// **You must upload these files to /wp-content/themes/YOUR_THEME/assets/users/**
$files = [
    4   => 'complement191.jpg',
    54  => 'complement66.gif',
    55  => 'complement67.gif',
    56  => 'complement68.gif',
    57  => 'complement69.gif',
    58  => 'complement70.gif',
    59  => 'complement71.gif',
    60  => 'complement72.gif',
    61  => 'complement73.gif',
    62  => 'complement74.gif',
    63  => 'complement75.gif',
    64  => 'complement76.gif',
    65  => 'complement77.gif',
    66  => 'complement78.gif',
    67  => 'complement79.gif',
    68  => 'complement80.gif',
    69  => 'complement81.gif',
    70  => 'complement82.gif',
    71  => 'complement83.gif',
    72  => 'complement84.gif',
    73  => 'complement85.gif',
    74  => 'complement86.gif',
    75  => 'complement87.gif',
    76  => 'complement88.gif',
    77  => 'complement89.gif',
    78  => 'complement90.gif',
    79  => 'complement91.gif',
    80  => 'complement92.gif',
    81  => 'complement93.gif',
    82  => 'complement94.gif',
    83  => 'complement95.gif',
    84  => 'complement96.gif',
    85  => 'complement97.gif',
    86  => 'complement98.gif',
    87  => 'complement99.gif',
    88  => 'complement100.gif',
    89  => 'complement101.gif',
    90  => 'complement102.gif',
    91  => 'complement103.gif',
    133 => 'complement145.jpg',
    144 => 'complement157.gif',
    157 => 'complement170.gif',
    180 => 'complement197.gif'
];

// 3. Helper function to get the viewer link based on linkId
function get_viewer_link_users($linkId, $files, $viewer_base) {
    $filename = isset($files[$linkId]) ? $files[$linkId] : "";
    
    // Fallback if ID is not in our specific list (e.g. IDs 1, 2, 3, 5-53)
    // We assume they follow the old site's generic pattern and are in the 'bogrim' folder.
    if (empty($filename)) {
         return home_url("/file-viewer/?file=complement{$linkId}.gif&folder=bogrim");
    }
    
    return $viewer_base . $filename;
}

// 4. RAW HTML Content
$raw_html = <<<'HTML'
<div class="col-md-12 col-sm-12 col-xs-12 paddXsZ">
    <div class="col-md-12 col-sm-12 col-xs-12 paddXsZ">
        <p class="page3">
            <a href="/" class="page3span">דף הבית</a> &gt; מכתבי הוקרה
        </p>
        <h1 class="page3line">מכתבי הוקרה</h1>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 pagespecial">
        <h2 class="para">מדגם מכתבי הוקרה של משתמשי המאגר</h2>
        
        <p class="para1"><a href="index2.php?id=5124&amp;linkId=4&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> כב' מבקר המדינה מיכה לינדנשטראוס, הנשיא ( בדימוס ) בית המשפט המחוזי - חיפה - (מס' 1) </a></p>
        <p class="para1"><a href="index2.php?id=5124&amp;linkId=54&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> כב' השופט אלון גילון, סגן נשיא בית משפט שלום, סגן מנהל בתי המשפט (מס' 2)</a></p>
        <p class="para1"><a href="index2.php?id=5124&amp;linkId=55&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> כב' השופטת אילה פרוקצ'יה, בית המשפט העליון (מס' 3)</a></p>
        <p class="para1"><a href="index2.php?id=5124&amp;linkId=56&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> כב' השופטת טובה שטרסברג-כהן, בית המשפט העליון (מס' 4)</a></p>
        <p class="para1"><a href="index2.php?id=5124&amp;linkId=57&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> כב' השופט אליהו מצא, בית המשפט העליון (מס' 5)</a></p>
        <p class="para1"><a href="index2.php?id=5124&amp;linkId=58&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> כב' השופט בועז אוקון, רשם בית המשפט העליון (מס' 6)</a></p>
        <p class="para1"><a href="index2.php?id=5124&amp;linkId=59&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> כב' השופט י. טירקל, בית המשפט העליון (מס' 7)</a></p>
        <p class="para1"><a href="index2.php?id=5124&amp;linkId=60&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> כב' השופט יצחק אנגלרד, בית המשפט העליון (מס' 8)</a></p>
        <p class="para1"><a href="index2.php?id=5124&amp;linkId=61&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> כב' נשיא בית הדין השרעי העליון לערעורים אחמד נאטור (מס' 9)</a></p>
        <p class="para1"><a href="index2.php?id=5124&amp;linkId=62&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> כב' השופטת חנה אבנור, נשיאה (בדימוס), בית המשפט המחוזי תל-אביב-יפו (מס' 10)</a></p>
        <p class="para1"><a href="index2.php?id=5124&amp;linkId=63&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> כב' השופט א. לרון, נשיא בית המשפט המחוזי באר שבע (מס' 11)</a></p>
        <p class="para1"><a href="index2.php?id=5124&amp;linkId=64&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> כב' השופט אהרון אמינוף, סגן נשיא בית משפט המשפט המחוזי נצרת (מס' 12)</a></p>
        <p class="para1"><a href="index2.php?id=5124&amp;linkId=65&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> כב' השופט אהרון אמינוף, בית המשפט המחוזי בנצרת (מס' 13)</a></p>
        <p class="para1"><a href="index2.php?id=5124&amp;linkId=66&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> כב' השופט ברוך אזולאי, בית משפט מחוזי באר שבע (מס' 14)</a></p>
        <p class="para1"><a href="index2.php?id=5124&amp;linkId=67&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> כב' השופט ח' עמר, בית המשפט המחוזי בבאר שבע (מס' 15)</a></p>
        <p class="para1"><a href="index2.php?id=5124&amp;linkId=68&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> כב' השופט צבי כהן, בית המשפט המחוזי בירושלים (מס' 16)</a></p>
        <p class="para1"><a href="index2.php?id=5124&amp;linkId=69&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> כב' השופט צבי סגל, בית המשפט המחוזי ירושלים (מס' 17)</a></p>
        <p class="para1"><a href="index2.php?id=5124&amp;linkId=70&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> כב' השופטת יפה הכט, בית המשפט המחוזי בירושלים (מס' 18)</a></p>
        <p class="para1"><a href="index2.php?id=5124&amp;linkId=71&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> כב' השופטת מיכל רובינשטיין, בית המשפט המחוזי תל-אביב-יפו (מס' 19)</a></p>
        <p class="para1"><a href="index2.php?id=5124&amp;linkId=72&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> כב' השופטת מלכה אביב, הנהלת בתי המשפט (מס' 20)</a></p>
        <p class="para1"><a href="index2.php?id=5124&amp;linkId=73&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> אביגיל הררי- סטיר, בית הדין הארצי לעבודה (מס' 21)</a></p>
        <p class="para1"><a href="index2.php?id=5124&amp;linkId=74&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> כב' השופט מרדכי כליף, בית הדין האזורי לעבודה בבאר שבע (מס' 22)</a></p>
        <p class="para1"><a href="index2.php?id=5124&amp;linkId=75&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> כב' השופט א' פרקש, בית משפט השלום ירושלים (מס' 23)</a></p>
        <p class="para1"><a href="index2.php?id=5124&amp;linkId=76&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> כב' השופט אהוד רקם, בית משפט השלום בחיפה (מס' 24)</a></p>
        <p class="para1"><a href="index2.php?id=5124&amp;linkId=77&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> כב' השופט בנימין ארבל, בית משפט שלום עפולה (מס' 25)</a></p>
        <p class="para1"><a href="index2.php?id=5124&amp;linkId=78&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> כב' השופט חנוך שילוני, סגן נשיא בית משפט השלום חיפה (מס' 26)</a></p>
        <p class="para1"><a href="index2.php?id=5124&amp;linkId=79&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> כב' השופט י. עמית, בית משפט שלום עכו (מס' 27)</a></p>
        <p class="para1"><a href="index2.php?id=5124&amp;linkId=80&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> כב' השופט יוסף בן חמו, בית משפט שלום נצרת (מס' 28)</a></p>
        <p class="para1"><a href="index2.php?id=5124&amp;linkId=81&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> כב' השופט סברי מוחסן,בית משפט השלום בחדרה (מס' 29)</a></p>
        <p class="para1"><a href="index2.php?id=5124&amp;linkId=82&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> כב' השופטת רחל ברקאי, סגנית הנשיא בית משפט השלום קרית גת (מס' 30)</a></p>
        <p class="para1"><a href="index2.php?id=5124&amp;linkId=83&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> דורי פינטו, סגן הסניגור הציבורי הארצי (מס' 31)</a></p>
        <p class="para1"><a href="index2.php?id=5124&amp;linkId=84&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> עו''ד משה הכהן, הסניגור הציבורי המחוזי ירושלים (מס' 32)</a></p>
        <p class="para1"><a href="index2.php?id=5124&amp;linkId=85&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> עו''ד אלון בכר, סגן הסניגור הציבורי המחוזי ירושלים (מס' 33)</a></p>
        <p class="para1"><a href="index2.php?id=5124&amp;linkId=86&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> אברהם קרופ, מזכיר ראשי בית משפט השלום בבאר שבע (מס' 34)</a></p>
        <p class="para1"><a href="index2.php?id=5124&amp;linkId=87&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> אהוד טל, מזכיר ראשי, בית משפט השלום רמלה (מס' 35)</a></p>
        <p class="para1"><a href="index2.php?id=5124&amp;linkId=88&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> אינג' פיטר מגנוס, מפקח עבודה ראשי, 6 ימים (מס' 36)</a></p>
        <p class="para1"><a href="index2.php?id=5124&amp;linkId=89&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> אינג' פיטר מגנוס, מפקח עבודה ראשי, 10 ימים (מס' 37)</a></p>
        <p class="para1"><a href="index2.php?id=5124&amp;linkId=90&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> שריל מאירוביץ, מנהלת לשכת נשיא בית המשפט העליון (מס' 38)</a></p>
        <p class="para1"><a href="index2.php?id=5124&amp;linkId=91&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> שמריהו כהן, מזכיר ראשי, בית משפט העליון (מס' 39)</a></p>
        <p class="para1"><a href="index2.php?id=5124&amp;linkId=133&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> עשהאל בר- נס - עו''ד -  (מס' 40)</a></p>
        <p class="para1"><a href="index2.php?id=5124&amp;linkId=144&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> ויסאם מוקטרן - עו''ד -  (מס' 41)</a></p>
        <p class="para1"><a href="index2.php?id=5124&amp;linkId=157&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> עו''ד יורם שלמון - (מס' 42)</a></p>
        <p class="para1"><a href="index2.php?id=5124&amp;linkId=180&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> הסנגוריה הציבורית - עו"ד  גלית זמירי - (מס' 43)</a></p>
    </div>
</div>
HTML;

// 4. Processing Logic
$processed = $raw_html;

// A. Replace Links
$processed = preg_replace_callback(
    '#(<a[^>]+href=")([^"]*index2\.php\?[^"]*?)(?:&amp;|&)?linkId=(\d+)([^"]*)(")#i',
    function ($m) use ($files, $VIEWER_BASE) {
        $prefix = $m[1];
        $linkId = intval($m[3]);
        $suffix = $m[5];

        // Determine Filename from Map
        $filename = isset($files[$linkId]) ? $files[$linkId] : "";
        
        // Build URL
        $newHref = $VIEWER_BASE . $filename;
        
        return $prefix . esc_url($newHref) . $suffix;
    },
    $processed
);

// 5. Fix Images
$processed = str_replace('src="designFiles/page-icon.png"', 'src="' . esc_url($ICON_URL) . '"', $processed);

?>

<div class="ie-container" dir="rtl" lang="he">
  <div class="container-fluid">
    <div class="container paddXsZ">
      <?php echo $processed; ?>
    </div>
  </div>
</div>

<?php get_footer(); ?>