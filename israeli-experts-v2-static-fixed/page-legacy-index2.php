<?php
/**
 * Template Name: מריץ Legacy (index2.php)
 * Description: מריץ את wp-content/legacy/index2.php בתוך עמוד וורדפרס, ללא תלות ב-URL.
 * PHP 8.3, RTL, עם <base> כדי שכל הקישורים היחסיים יעבדו.
 */
declare(strict_types=1);
defined('ABSPATH') || exit;

get_header(); ?>

<main id="primary" class="site-main" dir="rtl" style="padding:12px">

<?php
// ========= הגדרות =========
$legacyRoot = WP_CONTENT_DIR . '/legacy';
$legacyFile = $legacyRoot . '/index2.php';
$legacyBase = content_url('legacy/') . '/'; // לשימוש ב-<base href="...">

if (!is_file($legacyFile)) {
    echo '<div style="background:#fff3f3;border:1px solid #f5c2c2;padding:12px;color:#900">';
    echo 'קובץ legacy לא נמצא: <code>' . esc_html($legacyFile) . '</code>';
    echo '</div>';
    get_footer(); exit;
}

/**
 * ברירת מחדל לפרמטרים: אם לא מסופק id/lang ב-URL של עמוד ה-WP, נשתמש ב:
 *   id=5122  (עמוד repository לפי התיאור שלך)
 *   lang=HEB
 *   new   – עובר אם קיים ב-URL (למשל ?new=1)
 *
 * את יכולה/ה לשנות כאן את ברירת המחדל ל-5125 אם תרצה:
 * $defaultId = 5125;
 */
$defaultId  = 5122;
$defaultLang = 'HEB';

// נקרא את הפרמטרים מתוך URL העמוד הנוכחי (וורדפרס)
$id   = isset($_GET['id'])   && $_GET['id'] !== ''   ? (string)$_GET['id']   : (string)$defaultId;
$lang = isset($_GET['lang']) && $_GET['lang'] !== '' ? (string)$_GET['lang'] : $defaultLang;
// פרמטר new אופציונלי (אם ב-URL כתוב ?new=1 נחיל אותו)
$new  = isset($_GET['new'])  ? (string)$_GET['new']  : null;

// נשמור מצב ונזריק את ה-GET שהאפליקציה מצפה לו
$oldGet = $_GET;
$_GET['id']   = $id;
$_GET['lang'] = $lang;
if ($new !== null) {
    $_GET['new'] = $new;
} else {
    unset($_GET['new']);
}

// נעבוד בתיקיית legacy כדי שכל include יחסיים יעבדו
$cwd = getcwd();
chdir($legacyRoot);

// --- דיבוג אופציונלי (כבה בפרודקשן) ---
// if (defined('WP_DEBUG') && WP_DEBUG) { @ini_set('display_errors','1'); error_reporting(E_ALL); }

ob_start();

// נזריק <base> כדי שכל CSS/JS/תמונות יחסיים יעבדו גם כשזה רץ מתוך WP
echo '<base href="' . esc_url($legacyBase) . '">', "\n";

// מריץ בפועל את index2.php
require $legacyFile;

$html = ob_get_clean();

// מחזירים מצב
chdir($cwd);
$_GET = $oldGet;

// מציגים את ה-HTML של האפליקציה (לא מסננים כדי לא לשבור עיצוב/סקריפטים)
echo '<div class="legacy-container" style="background:#fff;border:1px solid #e5e5e5;padding:8px">';
echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo '</div>';

?>

</main>
<?php get_footer();
