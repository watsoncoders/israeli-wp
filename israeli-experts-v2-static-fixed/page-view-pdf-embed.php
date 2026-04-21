<?php
/**
 * Template Name: תוצאת חיפוש – הטמעת PDF (ללא PDF.js)
 * Description: מציג קובץ PDF מתוך assets/loadedFiles של התבנית ללא כותרות/ריווח — דומה לאתר המקור.
 * Author: Pablo Rotem
 * Author URI: https://pablo-guides.com
 * Version: 1.3
 */

if (!defined('ABSPATH')) { exit; }

// read ?file=… safely (basename only)
$requested = isset($_GET['file']) ? trim((string) $_GET['file']) : '';
$basename  = $requested !== '' ? basename($requested) : '';
$ext       = strtolower(pathinfo($basename, PATHINFO_EXTENSION));

// only allow common doc types we actually embed; PDF will render in <embed>
$allowed_exts = ['pdf', 'doc', 'docx'];
if (!in_array($ext, $allowed_exts, true)) {
  $basename = ''; // force not found
}

$base_uri  = trailingslashit( get_stylesheet_directory_uri() ) . 'assets/loadedFiles/';
$file_url  = $basename ? $base_uri . rawurlencode($basename) : '';
?>
<?php get_header(); ?>

<style>
  /* Kill default gaps around our viewer area */
  .ix-pdf-wrap {
    margin: 0 !important;
    padding: 0 !important;
    width: 100%;
    /* leave room if your header is sticky; otherwise full height is fine */
    min-height: 90vh;
    background: #1e1e1e;  /* closer to the dark surround of the origin site */
  }
  .ix-pdf-inner {
    margin: 0 auto;
    padding: 0;
    width: 100%;
    /* a bit taller so toolbars don’t force scroll on short pages */
    height: 92vh;
    display: flex;
    align-items: stretch;
    justify-content: center;
    background: #1e1e1e;
  }
  .ix-pdf-inner embed,
  .ix-pdf-inner iframe,
  .ix-pdf-inner object {
    border: 0;
    margin: 0;
    padding: 0;
    width: 100%;
    height: 100%;
    display: block;
    background: #1e1e1e;
  }

  /* Hide any theme content padding on pages using this template */
  .site-content, .content-area, .container, .wrap, .entry-content {
    padding-top: 0 !important;
    padding-bottom: 0 !important;
    margin-top: 0 !important;
    margin-bottom: 0 !important;
  }

  /* compact error style */
  .ix-pdf-error {
    color: #fff;
    background: #c0392b;
    padding: 12px 16px;
    margin: 0;
    font-size: 15px;
    direction: rtl;
  }
</style>

<div class="ix-pdf-wrap">
  <?php if ($file_url): ?>
    <div class="ix-pdf-inner">
      <!-- Using <embed> like the origin. Most browsers render PDF natively. -->
      <embed
        src="<?php echo esc_url($file_url); ?>"
        type="application/pdf"
        width="100%"
        height="100%"
        alt="PDF">
    </div>
  <?php else: ?>
    <div class="ix-pdf-error">
      הקובץ לא נמצא או שסוג הקובץ אינו נתמך. ודא/י שקובץ קיים בתיקייה:
      <code>/wp-content/themes/<?php echo esc_html( wp_get_theme()->get_stylesheet() ); ?>/assets/loadedFiles/</code>
      ושם הקובץ נשלח בפרמטר <code>?file=</code>.
    </div>
  <?php endif; ?>
</div>

<?php get_footer(); ?>
