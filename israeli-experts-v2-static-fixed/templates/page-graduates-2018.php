<?php
/**
 * Template Name: דעת בוגרי הקורס האחרון (2018) – רשימה
 * Description: רשימת בוגרי 2018 עם קישורים לעמוד הצפייה בקבצי PDF/מסמכים מתוך assets/loadedFiles של התבנית.
 * Author: Pablo Rotem
 * Author URI: https://pablo-guides.com
 * Version: 1.1 (PHP 8.3)
 */

if (!defined('ABSPATH')) { exit; }

get_header();

// ------------------------------------------------------------
// איתור עמוד ה-Viewer (התבנית: page-view-pdf-embed.php)
// ------------------------------------------------------------
$viewer_page_id = null;
$pages = get_pages([
  'meta_key'   => '_wp_page_template',
  'meta_value' => 'page-view-pdf-embed.php',
  'number'     => 1,
]);
if (!empty($pages)) {
  $viewer_page_id = (int) $pages[0]->ID;
}
$viewer_url = $viewer_page_id ? get_permalink($viewer_page_id) : '';

// ------------------------------------------------------------
// מיפוי linkId => שם קובץ (כפי שסיפקת)
// קבצים צריכים להיות ב: wp-content/themes/your-theme/assets/loadedFiles/
// ------------------------------------------------------------
// ------------------------------------------------------------
// מיפוי linkId => כתובת הקובץ המלאה (עודכן לפי הסריקה)
// ------------------------------------------------------------
// ------------------------------------------------------------
// 2. Mapping: linkId => Filename (Must match files in assets/loadedFiles/)
// ------------------------------------------------------------
$files = [
    253 => '__heb__nkTb_efxye_-sfbdje_abjvfy.pdf',
    254 => '__heb__nzfb_mnkfq.pdf',
    255 => '__heb__nzfb_mxfyr_sdjo_nfnhjo.pdf',
    256 => 'Scan_20180310_010456.pdf',
    257 => '__heb__aid.pdf',
    258 => 'IMG.pdf',
    259 => '__heb__hffT_dsT_xfyr_sdjo_nfnhjo_fbfyyjo.pdf',
    260 => 'Scan_20180310_004747.pdf',
    261 => 'Scan_20180310_000410.pdf',
    263 => '20180311_164253.png',
    262 => '__heb__nkTb_Tfde_msfdd_mfti.pdf',
    264 => 'CCF06032018_00000.jpg',
    265 => 'Scan_20180310_001041.pdf',
    266 => '20180308102354616.pdf',
    267 => '__heb__nkTb_efxye_sbfy_enkfq_ejzyamj_mhffT_dsT.pdf',
    268 => 'Scan_20180310_000603.pdf',
    269 => '20180306120120576.pdf',
    270 => '__heb__Tfde_-_nkfq_jzyamj.docx',
    271 => '__heb__nkTb_Tfde_mvffT_enkfq_ejzyamj._(1).pdf',
    272 => 'Scan_20180310_002542.pdf',
    273 => 'Scan_20180311_192438.889.pdf',
    274 => '__heb__nkTbTb_Tfde_sfdd_mfti.pdf',
    275 => 'Sc_20180310_005059.pdf',
    276 => 'an20180310_003319.pdf',
    277 => '__heb__sfdd_mftigjmbyzijjq.pdf',
    278 => '__heb__nkTb_Tfde_ryfx_(1).pdf',
    279 => 'Scan_20180310_002827.pdf',
    280 => 'Scan_20180310_001338.pdf',
    281 => 'Scan_20180310_003635.pdf',
    282 => 'embed.aspx', // Note: You might want to rename this file to a .doc/.docx if possible
    358 => '__heb__nkTb_enmve_sfdd_mfti.pdf',
    284 => '__heb__hffT_dsT_sdjo_nfnhjo1.jpg',
    285 => 'Scan_20180310_010123.pdf',
    286 => 'Scan_20180310_004004.pdf',
    287 => 'Scan_20180310_003503.pdf',
    288 => '__heb__Tfde_sm_xfyr_sdjo_nfnhjo_-_07.03.2018.pdf',
    289 => 'Scan_20180310_004945.pdf',
    290 => '__heb__nkTb_esyke_-_enykg_msdjo_nfnhjoc.doc_sfdkq_bnsi_bzo,_btjrfx__f.pdf2.pdf',
    291 => 'Scan_20180310_003000.pdf',
    292 => 'Scan_20180310_005751.pdf',
    293 => 'Scan_20180310_000914.pdf',
    294 => '__heb__nzfmo_jsxb-nkTb_esyke_hTfo.pdf',
    295 => 'Scan_20180310_004144.pdf',
    296 => 'Scan_20180310_004621.pdf',
    297 => 'Scan_20180309_234932.pdf',
    298 => 'image2018-03-13-124038.pdf',
    299 => 'Scan_20180310_002411.pdf',
    300 => 'Scan_20180310_000717.pdf',
    301 => 'Scan_20180310_005326.pdf',
    302 => 'SKMBT_C28018031314320.pdf',
    303 => 'Scan_20180310_005234.pdf',
    304 => 'Scan_20180310_005939.pdf',
    305 => '__heb__ebsT_Tfde.pdf',
    306 => 'Scan_20180310_003819.pdf',
    307 => '__heb__nkTb_enmve_-_yfnq_tfbfmfvxj.pdf',
    308 => 'Scan_20180310_005453.pdf',
    309 => '__heb__nkTe_esyke_mnkfq_mhfT_dsT_nfnhjo.docx',
    310 => '__heb__nkTb_efxye_-sfbdje_abjvfy.docx',
    311 => 'Scan_20180309_235957.pdf',
    312 => 'Scan_20180313_233323.pdf',
    313 => 'Scan_20180310_005613.pdf',
    314 => 'Scan_20180310_001150.pdf',
    315 => '__heb__ebsT_Tfde_(1).pdf',
    316 => 'Scan_20180310_004300.pdf',
    317 => '__heb__esyke_-_xfyr_sdjo_nfnhjo_fbfyyjo.pdf',
    318 => 'Scan_20180310_002711.pdf',
    319 => '__heb__nkTb_Tfde_ryfx.pdf',
    320 => '__heb__nkTb_efxye.docx',
    321 => '__heb__nkTb_Tfde.docx',
    322 => '__heb__enmve_mnkfq_ejzyamj_mnfnhjo.pdf',
    324 => '[Untitled].pdf',
    335 => '__heb__nkTb_Tfde_msfdd_(1).pdf',
    326 => '__heb__nkTb_Tfde_mnkfq_msdjo_nfnhjo_fbfyyjo.pdf',
    327 => '__heb__nkTb_esyke.pdf',
    330 => '12.pdf',
    329 => '__heb__nkTb_mxfyr_hffT_dsT.pdf',
    328 => '__heb__hffT_dsT_hTfne.jpg',
    331 => '__heb__nkTb_esyke_fenmve_-_ajmq_kTfbjo.pdf',
    332 => '__heb__enmve.pdf',
    333 => 'D7=>93=>D7=>A2_=>_T_nfnhjo_nkTb_12.03.2018.pdf',
    336 => '__heb__nkfq_ejzyamj_mhffT_dsT_nfnhjo_nkTb_Tfde.pdf',
    337 => 'New_Doc_2018-03-19.pdf',
    338 => '__heb__enmve_msfdd_mfti_sf_d-enkfq_ejzyamj.pdf',
    339 => 'NOT_FOUND',
    340 => '__heb__nkTb_enmve_menkfq_ejzyamj_mhffT_dsT.pdf',
    341 => '__heb__nkTb_Tfde_mnkfq_ejzyamj.pdf',
    342 => 'Applied_Materials_Amos_Hadad.pdf',
    343 => 'Scan_20180310_001707.pdf',
    344 => 'Scan_20180319_175004.pdf',
    345 => '__heb__Tfde.pdf',
    346 => '91.pdf',
    347 => '__heb__nkTb_esyke-enkfq_ejzyamj_mhffT_dsT_nfnhjo_fbfyyjo.pdf',
    362 => 'Scan_0011.pdf',
    348 => '__heb__sfdd_mfti.pdf',
    351 => '__heb__by_snjT.pdf',
    352 => '__heb__rjkfo_xfyr_sdjo_nfnhjo-_jsxb_nhmb.pdf',
    354 => 'Doc4.docx',
    363 => '__heb__nkTb_hjgfx_enkfq_msdjo_nfnhjo.pdf',
    357 => '__heb__esykT_exfyr.pdf',
    359 => '__heb__nkTb_Tfde_fesyke.pdf',
];
// 3. Helper function to get the viewer link based on linkId
function get_viewer_link_users($linkId, $files, $viewer_base) {
    $filename = isset($files[$linkId]) ? $files[$linkId] : "";
    
    // Fallback if ID is not in our specific list (e.g. IDs 1, 2, 3, 5-53)
    // We assume they follow the old site's generic pattern and are in the 'loadedFiles' folder.
    if (empty($filename)) {
         return home_url("/file-viewer/?file=complement{$linkId}.jpg&folder=loadedFiles");
    }
    
    return $viewer_base . $filename;
}
// ------------------------------------------------------------
// רשימת התצוגה (label) לפי הליסט ההיסטורי שלך (id => טקסט קישור)
// ------------------------------------------------------------
$labels = [
  253=>'אביצור עובדיה - 1',
  254=>'אבירם רם - 2',
  255=>'אברהם דב - 3',
  256=>'אהרוני שי - 4',
  257=>'אטד אפריים - 5',
  258=>'איבגי משה - 6',
  259=>'אלדי שלמה - 7',
  260=>'אלימלך ירון - 8',
  261=>'אלקבוי אודי - 9',
  263=>'אסף שרון - 10',
  262=>'אמיר יפתח - 11',
  264=>'נוב ארז - 12',
  265=>'בטיקוף אלברטו - 13',
  266=>'בן זקן מיכאל - 14',
  267=>'בן משה אריה - 15',
  268=>'בן שימול אריה - 16',
  269=>'בר אריה - 17',
  270=>'גולן שלום - 18',
  271=>'גלמנוביץ דניאל - 19',
  272=>'גרידיש עמנואל - 20',
  273=>'דולב גלעד - 21',
  274=>'דיין יוסף - 22',
  275=>'דמגי אברהם - 23',
  276=>'הלמן אליהו - 24',
  277=>'זילברשטיין עדי - 25',
  278=>'נילי זרחין - 26',
  279=>'חסקי רועי - 27',
  280=>'טלקר גדעון - 28',
  281=>'יאביץ אלכסנדר - 29',
  282=>'מרגלית יואב - 30',
  358=>'מוריאל יוסף - 31',
  284=>'לב הארי אהרון - 32',
  285=>'לוי אבי - 33',
  286=>'לוי אמיר - 34',
  287=>'לחמני אבי - 35',
  288=>'מחאמיד אשרף - 36',
  289=>'מלכה דוד - 37',
  290=>'מנשורי איתן - 38',
  291=>'מצר פולינה - 39',
  292=>'מרגלית יואב - 40',
  293=>'מרקו אפרים - 41',
  294=>'משולם יעקב - 42',
  295=>'נגאדי הרצל - 43',
  296=>'נוב ארז - 44',
  297=>'נסטרנקו אנה - 45',
  298=>'סומכי מתתיהו - 46',
  299=>'סלוש אלון - 47',
  300=>'סל-מן יצחק - 48',
  301=>'סמחאת מוחמד - 49',
  302=>'ספורי איבראהים - 50',
  303=>'עופר דרור - 51',
  304=>'עלם טוני - 52',
  305=>'עמוס קרימר - 53',
  306=>'עשרי שאלתיאל - 54',
  307=>'פובולוצקי רומן - 55',
  308=>'פיזנטי שמואל - 56',
  309=>'פלג אליעזר - 57',
  310=>'אביצור עובדיה - 58',
  311=>'צרפתי רפי - 59',
  312=>'קורן - ישראלי חוה - 60',
  313=>'קנטור יורי - 61',
  314=>'קרויטנר גדעון - 62',
  315=>'קרימר עמוס - 63',
  316=>'קריסטל ניצן - 64',
  317=>'ריבקוב מיכאל - 65',
  318=>'שוקרני משה - 66',
  319=>'שזיפי נעם - 67',
  320=>'שלף אורית - 68',
  321=>'אסף שרון - 69',
  322=>'שריף אינס - 70',
  324=>'אדר משה - 71',
  335=>'וסרמן יוסף - 72',
  326=>'אלטמן אברהם - 73',
  327=>'אלימור יעקב - 74',
  330=>'כהן אלי - 75',
  329=>'וקס ראובן - 76',
  328=>'ברוך אליהו - 77',
  331=>'כתובים אילן - 78',
  332=>'לניאדו שאול - 79',
  333=>'צפריר שלמה - 80',
  336=>'אהוד אבידוב - 81',
  337=>'אטיאס אהרון - 82',
  338=>'אלון רוברט - 83',
  339=>'אלקובי משה - 84',
  340=>'גוס טל - 85',
  341=>'גלמנוביץ דניאל - 86',
  342=>'חדד עמוס - 87',
  343=>'יפה ויוסף אריאל - 88',
  344=>'רובין שי - 89',
  345=>'רוזן שמואל - 90',
  346=>'טרכטנברג קרלוס - 91',
  347=>'שילדקראוט נתן - 92',
  362=>'אבירם משה - 93',
  348=>'כהן אורן - 94',
  351=>'בר עמית - 95',
  352=>'מחלב יעקב - 96',
  354=>'יהודית שטרן - 97',
  363=>'אלדור אבישי - 98',
  357=>'פטר מגנוס - 99',
  359=>'צורי טייב - 100',
];

// אייקון
$icon = get_stylesheet_directory_uri() . '/assets/designFiles/page-icon.png';

// פונקציה לחישוב קישור לצפייה
$make_link = function(int $id) use ($files, $viewer_url) : array {
  $file = $files[$id] ?? '';
  if (!$viewer_url) {
    return ['url' => '', 'has' => false, 'file'=>$file];
  }
  if ($file === '') {
    return ['url' => '', 'has' => false, 'file'=>$file];
  }
  // קישור לעמוד התצוגה עם פרמטר file
  $url = add_query_arg(['file' => $file], $viewer_url);
  return ['url' => $url, 'has' => true, 'file'=>$file];
};

?>
<div class="container-fluid">
  <div class="container paddXsZ">
    <div class="col-md-12 col-sm-12 col-xs-12 paddXsZ">
      <div class="col-md-12 col-sm-12 col-xs-12 paddXsZ">
        <p class="page3">דעת בוגרי הקורס האחרון (2018)</p>
        <h1 class="page3line">דעת בוגרי הקורס האחרון (2018)</h1>
      </div>

      <?php if (!$viewer_url): ?>
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="alert alert-warning" role="alert" style="margin:15px 0">
            ⚠️ לא נמצא עמוד Viewer עם התבנית <code>page-view-pdf-embed.php</code>.  
            צור/י עמוד חדש ובחר/י את התבנית הנ״ל — ואז חזור/י לעמוד זה.
          </div>
        </div>
      <?php endif; ?>

      <div class="col-md-12 col-sm-12 col-xs-12 pagespecial">
        <h2 class="para">דעת בוגרי הקורס האחרון (2018)</h2>

        <?php foreach ($labels as $id => $label): 
          $data = $make_link($id);
          $has  = $data['has'];
          $url  = $data['url'];
          $file = $data['file'];
        ?>
          <p class="para1">
            <?php if ($has): ?>
              <a href="<?php echo esc_url($url); ?>" class="spanpagest">
                <img src="<?php echo esc_url($icon); ?>" class="img-responsive imgspecial" alt="link icon">
                <?php echo esc_html($label); ?>
              </a>
            <?php else: ?>
              <span class="spanpagest" style="opacity:0.7; cursor:not-allowed;" title="<?php echo $file === '' ? esc_attr('אין קובץ משויך לפריט זה') : esc_attr('לא נמצא עמוד viewer'); ?>">
                <img src="<?php echo esc_url($icon); ?>" class="img-responsive imgspecial" alt="link icon">
                <?php echo esc_html($label); ?>
                <?php if ($file !== '' && !$viewer_url): ?>
                  (מוכן לשיוך, חסר עמוד Viewer)
                <?php elseif ($file === ''): ?>
                  (קובץ חסר)
                <?php endif; ?>
              </span>
            <?php endif; ?>
          </p>
        <?php endforeach; ?>

      </div>
    </div>
  </div>
</div>

<?php
get_footer();
