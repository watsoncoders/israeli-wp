<?php
/**
 * Template Name: Letters
 */
defined('ABSPATH') || exit;

get_header();

$THEME_URI      = get_stylesheet_directory_uri();
$THEME_PATH     = get_stylesheet_directory();

$FILES_DIR_REL  = 'assets/loadedFiles';
$FILES_DIR_PATH = trailingslashit($THEME_PATH) . $FILES_DIR_REL;
$FILES_DIR_URL  = trailingslashit($THEME_URI)  . $FILES_DIR_REL;
$ICON_URL       = trailingslashit($THEME_URI)  . 'assets/designFiles/page-icon.png';

$current_url    = get_permalink(get_queried_object_id());
$home_url       = home_url('/'); // domain-agnostic home

$show_id        = isset($_GET['show']) ? (int)$_GET['show'] : 0;

/** CSV → map */
function iex_build_map_from_csv($csv_path) {
    $map = [];
    if (!file_exists($csv_path) || !is_readable($csv_path)) return $map;
    if (($h = fopen($csv_path, 'r')) === false) return $map;
    $header = fgetcsv($h); if (!$header) { fclose($h); return $map; }
    $cols = array_map('trim', $header);
    $idx = [
        'linkId'             => array_search('linkId', $cols),
        'filename'           => array_search('filename', $cols),
        'original_url'       => array_search('original_url', $cols),
        'suggested_filename' => array_search('suggested_filename', $cols),
        'detected_filename'  => array_search('detected_filename', $cols),
    ];
    while (($row = fgetcsv($h)) !== false) {
        $id = null; $fn = null;
        if ($idx['linkId'] !== false && isset($row[$idx['linkId']])) {
            $id = (int)preg_replace('/\D+/', '', (string)$row[$idx['linkId']]);
        } elseif ($idx['original_url'] !== false && isset($row[$idx['original_url']])) {
            $url = $row[$idx['original_url']];
            if (preg_match('/(?:\?|&)(?:amp;)?linkId=(\d+)/i', $url, $m)) $id = (int)$m[1];
        }
        if ($idx['filename'] !== false && !empty($row[$idx['filename']])) {
            $fn = trim($row[$idx['filename']]);
        } elseif ($idx['suggested_filename'] !== false && !empty($row[$idx['suggested_filename']])) {
            $fn = trim($row[$idx['suggested_filename']]);
        } elseif ($idx['detected_filename'] !== false && !empty($row[$idx['detected_filename']])) {
            $fn = trim($row[$idx['detected_filename']]);
        }
        if ($id && $fn) $map[$id] = $fn;
    }
    fclose($h);
    return $map;
}

/** Resolve file name for a linkId */
function iex_resolve_file_by_linkid($id, $map, $baseDirPath) {
    if (isset($map[$id])) {
        $candidate = $baseDirPath . '/' . ltrim($map[$id], '/');
        if (file_exists($candidate)) return basename($candidate);
    }
    $idnum = preg_replace('/\D+/', '', (string)$id);
    foreach (['jpg','jpeg','png','gif','webp','pdf'] as $ext) {
        foreach (["complement{$idnum}.{$ext}", "complement-{$idnum}.{$ext}", "{$idnum}.{$ext}"] as $guess) {
            $candidate = $baseDirPath . '/' . $guess;
            if (file_exists($candidate)) return $guess;
        }
    }
    if (is_dir($baseDirPath) && ($scan = @scandir($baseDirPath))) {
        foreach ($scan as $fn) {
            if ($fn === '.' || $fn === '..') continue;
            if (preg_match('/\b' . preg_quote($idnum, '/') . '\b/', $fn)) return $fn;
        }
    }
    return null;
}

/** Render viewer */
function iex_render_viewer_block($file_url) {
    $path = parse_url($file_url, PHP_URL_PATH);
    $ext  = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    $is_img = in_array($ext, ['jpg','jpeg','png','gif','webp'], true);
    $is_pdf = ($ext === 'pdf');

    echo '<div class="container-fluid" style="margin:10px 0">';
    if ($is_img) {
        printf('<img class="showImage img-responsive" src="%s" alt="">', esc_url($file_url));
    } elseif ($is_pdf) {
        printf('<div style="min-height:70vh"><embed src="%s#view=FitH" type="application/pdf" width="100%%" height="100%%" /></div>', esc_url($file_url));
        printf('<p style="margin-top:10px"><a class="btn btn-default" target="_blank" rel="noopener" href="%s">פתח/י בחלון חדש</a></p>', esc_url($file_url));
    } else {
        printf('<p><a class="btn btn-default" target="_blank" rel="noopener" href="%s">הורד/י את הקובץ</a></p>', esc_url($file_url));
    }
    echo '</div>';
}

/** Rewrite icon path + link targets to ?show=<id> */
function iex_rewrite_block($html, $icon_url, $current_url) {
    $html = preg_replace(
        '#(<img[^>]+src=")([^"]*/)?designFiles/page-icon\.png(")#i',
        '$1' . esc_url($icon_url) . '$3',
        $html
    );
    $html = preg_replace_callback(
        '#(<a[^>]+href=")([^"]*?)(?:\?|&)(?:amp;)?linkId=(\d+)([^"]*)(")#i',
        function($m) use ($current_url) {
            $id  = (int)$m[3];
            $new = esc_url(add_query_arg(['show' => $id], $current_url));
            return $m[1] . $new . $m[5];
        },
        $html
    );
    return $html;
}

/** Remove scraper highlight classes and absolute domains if any slipped in */
function iex_neutralize_list($html, $home_url) {
    // Replace absolute home link to dynamic home
    $html = preg_replace(
        '#(<a[^>]+href=")https?://[^"/]+/(")#i',
        '$1' . esc_url($home_url) . '$2',
        $html
    );
    // Replace the specific breadcrumb absolute link
    $html = preg_replace(
        '#(<a[^>]+class="[^"]*page3span[^"]*"[^>]*href=")[^"]*(")#i',
        '$1' . esc_url($home_url) . '$2',
        $html
    );
    // Strip highlight classes
    $html = str_replace(['tablescraper-selected-row','tablescraper-selected-table'], '', $html);
    // Clean double spaces in class attributes after removal (FIX: use preg_replace_callback)
    $html = preg_replace_callback('/class="([^"]+)"/', function($m){
        $clean = preg_replace('/\s+/', ' ', trim($m[1]));
        return 'class="' . $clean . '"';
    }, $html);

    return $html;
}

/* ---- Map from CSV ---- */
$csv_map = iex_build_map_from_csv(trailingslashit($FILES_DIR_PATH) . 'found-files.csv');

/* ---- The list HTML you provided (breadcrumb now domain-agnostic) ---- */
$list_html = <<<HTML
<div class="col-md-12 col-sm-12 col-xs-12 paddXsZ">
  <div class="col-md-12 col-sm-12 col-xs-12 paddXsZ">
    <p class="page3">
      <a href="{$home_url}" class="page3span">דף הבית</a> &gt;
      מכתבי הוקרה
    </p>
    <h1 class="page3line">מכתבי הוקרה</h1>
  </div>
  <div class="col-md-12 col-sm-12 col-xs-12 pagespecial tablescraper-selected-table">
    <h2 class="para">מדגם מכתבי הוקרה של משתמשי המאגר</h2>

    <p class="para1 tablescraper-selected-row">
      <a href="index2.php?id=5124&amp;linkId=4&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> כב' מבקר המדינה מיכה לינדנשטראוס, הנשיא ( בדימוס ) בית המשפט המחוזי - חיפה - (מס' 1) </a>
    </p>
    <p class="para1">
							<a href="index2.php?id=5124&amp;linkId=54&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> כב' השופט אלון גילון, סגן נשיא בית משפט שלום, סגן מנהל בתי המשפט (מס' 2)</a>
						</p>
						
						<p class="para1">
							<a href="index2.php?id=5124&amp;linkId=55&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> כב' השופטת אילה פרוקצ'יה, בית המשפט העליון (מס' 3)</a>
						</p>
						
						<p class="para1">
							<a href="index2.php?id=5124&amp;linkId=56&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> כב' השופטת טובה שטרסברג-כהן, בית המשפט העליון (מס' 4)</a>
						</p>
						
						<p class="para1">
							<a href="index2.php?id=5124&amp;linkId=57&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> כב' השופט אליהו מצא, בית המשפט העליון (מס' 5)</a>
						</p>
						
						<p class="para1">
							<a href="index2.php?id=5124&amp;linkId=58&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> כב' השופט בועז אוקון, רשם בית המשפט העליון (מס' 6)</a>
						</p>
						
						<p class="para1">
							<a href="index2.php?id=5124&amp;linkId=59&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> כב' השופט י. טירקל, בית המשפט העליון (מס' 7)</a>
						</p>
						
						<p class="para1">
							<a href="index2.php?id=5124&amp;linkId=60&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> כב' השופט יצחק אנגלרד, בית המשפט העליון (מס' 8)</a>
						</p>
						
						<p class="para1">
							<a href="index2.php?id=5124&amp;linkId=61&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> כב' נשיא בית הדין השרעי העליון לערעורים אחמד נאטור (מס' 9)</a>
						</p>
						
						<p class="para1">
							<a href="index2.php?id=5124&amp;linkId=62&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> כב' השופטת חנה אבנור, נשיאה (בדימוס), בית המשפט המחוזי תל-אביב-יפו (מס' 10)</a>
						</p>
    <p class="para1">
							<a href="index2.php?id=5124&amp;linkId=63&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> כב' השופט א. לרון, נשיא בית המשפט המחוזי באר שבע (מס' 11)</a>
						</p>
						
						<p class="para1">
							<a href="index2.php?id=5124&amp;linkId=64&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> כב' השופט אהרון אמינוף, סגן נשיא בית משפט המשפט המחוזי נצרת (מס' 12)</a>
						</p>
						
						<p class="para1">
							<a href="index2.php?id=5124&amp;linkId=65&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> כב' השופט אהרון אמינוף, בית המשפט המחוזי בנצרת (מס' 13)</a>
						</p>
						
						<p class="para1">
							<a href="index2.php?id=5124&amp;linkId=66&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> כב' השופט ברוך אזולאי, בית משפט מחוזי באר שבע (מס' 14)</a>
						</p>
						
						<p class="para1">
							<a href="index2.php?id=5124&amp;linkId=67&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> כב' השופט ח' עמר, בית המשפט המחוזי בבאר שבע (מס' 15)</a>
						</p>
						
						<p class="para1">
							<a href="index2.php?id=5124&amp;linkId=68&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> כב' השופט צבי כהן, בית המשפט המחוזי בירושלים (מס' 16)</a>
						</p>
						
						<p class="para1">
							<a href="index2.php?id=5124&amp;linkId=69&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> כב' השופט צבי סגל, בית המשפט המחוזי ירושלים (מס' 17)</a>
						</p>
						
						<p class="para1">
							<a href="index2.php?id=5124&amp;linkId=70&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> כב' השופטת יפה הכט, בית המשפט המחוזי בירושלים (מס' 18)</a>
						</p>
						
						<p class="para1">
							<a href="index2.php?id=5124&amp;linkId=71&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> כב' השופטת מיכל רובינשטיין, בית המשפט המחוזי תל-אביב-יפו (מס' 19)</a>
						</p>
						
						<p class="para1">
							<a href="index2.php?id=5124&amp;linkId=72&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> כב' השופטת מלכה אביב, הנהלת בתי המשפט (מס' 20)</a>
						</p>
						
						<p class="para1">
							<a href="index2.php?id=5124&amp;linkId=73&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> אביגיל הררי- סטיר, בית הדין הארצי לעבודה (מס' 21)</a>
						</p>
						
						<p class="para1">
							<a href="index2.php?id=5124&amp;linkId=74&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> כב' השופט מרדכי כליף, בית הדין האזורי לעבודה בבאר שבע (מס' 22)</a>
						</p>
						
						<p class="para1">
							<a href="index2.php?id=5124&amp;linkId=75&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> כב' השופט א' פרקש, בית משפט השלום ירושלים (מס' 23)</a>
						</p>
						
						<p class="para1">
							<a href="index2.php?id=5124&amp;linkId=76&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> כב' השופט אהוד רקם, בית משפט השלום בחיפה (מס' 24)</a>
						</p>
						
						<p class="para1">
							<a href="index2.php?id=5124&amp;linkId=77&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> כב' השופט בנימין ארבל, בית משפט שלום עפולה (מס' 25)</a>
						</p>
						
						<p class="para1">
							<a href="index2.php?id=5124&amp;linkId=78&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> כב' השופט חנוך שילוני, סגן נשיא בית משפט השלום חיפה (מס' 26)</a>
						</p>
						
						<p class="para1">
							<a href="index2.php?id=5124&amp;linkId=79&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> כב' השופט י. עמית, בית משפט שלום עכו (מס' 27)</a>
						</p>
						
						<p class="para1">
							<a href="index2.php?id=5124&amp;linkId=80&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> כב' השופט יוסף בן חמו, בית משפט שלום נצרת (מס' 28)</a>
						</p>
						
						<p class="para1">
							<a href="index2.php?id=5124&amp;linkId=81&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> כב' השופט סברי מוחסן,בית משפט השלום בחדרה (מס' 29)</a>
						</p>
						
						<p class="para1">
							<a href="index2.php?id=5124&amp;linkId=82&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> כב' השופטת רחל ברקאי, סגנית הנשיא בית משפט השלום קרית גת (מס' 30)</a>
						</p>
						
						<p class="para1">
							<a href="index2.php?id=5124&amp;linkId=83&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> דורי פינטו, סגן הסניגור הציבורי הארצי (מס' 31)</a>
						</p>
						
						<p class="para1">
							<a href="index2.php?id=5124&amp;linkId=84&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> עו''ד משה הכהן, הסניגור הציבורי המחוזי ירושלים (מס' 32)</a>
						</p>
						
						<p class="para1">
							<a href="index2.php?id=5124&amp;linkId=85&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> עו''ד אלון בכר, סגן הסניגור הציבורי המחוזי ירושלים (מס' 33)</a>
						</p>
						
						<p class="para1">
							<a href="index2.php?id=5124&amp;linkId=86&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> אברהם קרופ, מזכיר ראשי בית משפט השלום בבאר שבע (מס' 34)</a>
						</p>
						
						<p class="para1">
							<a href="index2.php?id=5124&amp;linkId=87&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> אהוד טל, מזכיר ראשי, בית משפט השלום רמלה (מס' 35)</a>
						</p>
						
						<p class="para1">
							<a href="index2.php?id=5124&amp;linkId=88&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> אינג' פיטר מגנוס, מפקח עבודה ראשי, 6 ימים (מס' 36)</a>
						</p>
						
						<p class="para1">
							<a href="index2.php?id=5124&amp;linkId=89&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> אינג' פיטר מגנוס, מפקח עבודה ראשי, 10 ימים (מס' 37)</a>
						</p>
						
						<p class="para1">
							<a href="index2.php?id=5124&amp;linkId=90&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> שריל מאירוביץ, מנהלת לשכת נשיא בית המשפט העליון (מס' 38)</a>
						</p>
						
						<p class="para1">
							<a href="index2.php?id=5124&amp;linkId=91&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> שמריהו כהן, מזכיר ראשי, בית משפט העליון (מס' 39)</a>
						</p>
						
						<p class="para1">
							<a href="index2.php?id=5124&amp;linkId=133&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> עשהאל בר- נס - עו''ד -  (מס' 40)</a>
						</p>
						
						<p class="para1">
							<a href="index2.php?id=5124&amp;linkId=144&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> ויסאם מוקטרן - עו''ד -  (מס' 41)</a>
						</p>
						
						<p class="para1">
							<a href="index2.php?id=5124&amp;linkId=157&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> עו''ד יורם שלמון - (מס' 42)</a>
						</p>
    <p class="para1 tablescraper-selected-row">
      <a href="index2.php?id=5124&amp;linkId=180&amp;lang=HEB" class="spanpagest"><img src="designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> הסנגוריה הציבורית - עו"ד  גלית זמירי - (מס' 43)</a>
    </p>

  </div>
</div>
HTML;

/* ---- Render ---- */
echo '<main id="primary" class="site-main" dir="rtl"><div class="container">';

if ($show_id > 0) {
    $resolved = iex_resolve_file_by_linkid($show_id, $csv_map, $FILES_DIR_PATH);
    if ($resolved) {
        $file_url = trailingslashit($FILES_DIR_URL) . rawurlencode($resolved);
        iex_render_viewer_block($file_url);
    } else {
        echo '<div class="alert alert-warning">לא נמצא קובץ מתאים לפריט שביקשת.</div>';
    }
}

/* Rewrite icon + links, then neutralize classes + domains */
$block = iex_rewrite_block($list_html, $ICON_URL, $current_url);
$block = iex_neutralize_list($block, $home_url);
echo $block;

echo '</div></main>';

get_footer();
