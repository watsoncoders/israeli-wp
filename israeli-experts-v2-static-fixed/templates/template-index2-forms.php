<?php
/**
 * Template Name: Israeli Legacy Forms (index2.php router)
 * Description: Renders legacy forms by id/courseId/lang, writes to custom tables.
 */

defined('ABSPATH') || exit;
get_header();

$id       = get_query_var('id');
$courseId = get_query_var('courseId');
$lang     = get_query_var('lang');

// Basic RTL support
$rtl = ( strtolower($lang) === 'heb' || is_rtl() ) ? 'dir="rtl" lang="he"' : '';

?>
<div class="container" <?php echo $rtl; ?>>
  <?php
  /**
   * Route: id=5127 + courseId=103 => Course combined registration
   * You can add more routes for other forms (id=XXXX) by including another partial.
   */
  if ((string)$id === '5127' && (string)$courseId === '103') {
      locate_template(['templates/forms/form-5127-course.php'], true, false);
  } else {
      echo '<div class="alert alert-warning" style="margin-top:20px">'
         . esc_html__('לא נמצא טופס עבור הפרמטרים שסופקו.', 'your-textdomain')
         . '</div>';
  }
  ?>
</div>
<?php get_footer();
