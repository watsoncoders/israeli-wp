<?php
/*
 * Template Name: File Viewer
 * Description: מציג קבצים מתוך assets בתבנית, בצורה מאובטחת.
 */
// author: pablo rotem

if (!defined('ABSPATH')) {
    exit;
}

get_header();

/**
 * author: pablo rotem
 * הגדרות בסיס
 */
$theme_assets_dir = trailingslashit(get_stylesheet_directory()) . 'assets/';
$theme_assets_url = trailingslashit(get_stylesheet_directory_uri()) . 'assets/';

/**
 * author: pablo rotem
 * תיקיות מותרות בלבד
 */
$allowed_folders = [
    'users',
    'bogrim',
    'designFiles',
];

/**
 * author: pablo rotem
 * סיומות מותרות בלבד
 */
$allowed_extensions = [
    'jpg',
    'jpeg',
    'png',
    'gif',
    'webp',
    'pdf',
    'txt',
];

/**
 * author: pablo rotem
 * קלט מה-URL
 */
$folder = isset($_GET['folder']) ? sanitize_key(wp_unslash($_GET['folder'])) : '';
$file   = isset($_GET['file']) ? wp_unslash($_GET['file']) : '';

/**
 * author: pablo rotem
 * ניקוי שם קובץ
 * שומר רק על שם בסיסי בלי נתיבים
 */
$file = basename($file);

/**
 * author: pablo rotem
 * אימותים
 */
$error_message = '';
$file_exists   = false;
$file_url      = '';
$file_path     = '';
$file_ext      = '';

if ($folder === '' || $file === '') {
    $error_message = 'לא נבחר קובץ להצגה.';
} elseif (!in_array($folder, $allowed_folders, true)) {
    $error_message = 'התיקייה המבוקשת אינה מורשית.';
} else {
    $file_ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

    if (!in_array($file_ext, $allowed_extensions, true)) {
        $error_message = 'סוג הקובץ אינו נתמך.';
    } else {
        $base_folder_path = realpath($theme_assets_dir . $folder);
        $requested_path   = realpath($theme_assets_dir . $folder . '/' . $file);

        if ($base_folder_path === false || $requested_path === false) {
            $error_message = 'הקובץ לא נמצא.';
        } else {
            /**
             * author: pablo rotem
             * מוודא שהקובץ באמת נמצא בתוך התיקייה המורשית
             */
            if (strpos($requested_path, $base_folder_path) !== 0) {
                $error_message = 'גישה לא מורשית לקובץ.';
            } elseif (!is_file($requested_path)) {
                $error_message = 'הקובץ לא נמצא.';
            } else {
                $file_exists = true;
                $file_path   = $requested_path;
                $file_url    = $theme_assets_url . rawurlencode($folder) . '/' . rawurlencode($file);
            }
        }
    }
}
?>

<div class="pablo-file-viewer" dir="rtl" lang="he">
    <div class="container" style="max-width: 1100px; margin: 0 auto; padding: 30px 15px;">
        <div style="margin-bottom: 20px;">
            <a href="<?php echo esc_url(home_url('/')); ?>">דף הבית</a>
            <span> &gt; </span>
            <span>צפייה בקובץ</span>
        </div>

        <h1 style="margin-bottom: 20px;">צפייה בקובץ</h1>

        <?php if ($error_message !== '') : ?>
            <div style="padding:16px; border:1px solid #d9534f; background:#fff5f5; color:#a94442; border-radius:8px;">
                <?php echo esc_html($error_message); ?>
            </div>
        <?php else : ?>
            <div style="padding:16px; border:1px solid #e5e7eb; background:#fafafa; border-radius:8px; margin-bottom:20px;">
                <div><strong>תיקייה:</strong> <?php echo esc_html($folder); ?></div>
                <div><strong>קובץ:</strong> <?php echo esc_html($file); ?></div>
                <div style="margin-top:10px;">
                    <a href="<?php echo esc_url($file_url); ?>" target="_blank" rel="noopener noreferrer">פתח בקובץ ישיר</a>
                    <span> | </span>
                </div>
            </div>

            <div style="border:1px solid #ddd; background:#fff; padding:20px; border-radius:8px;">
                <?php if (in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true)) : ?>
                    <div style="text-align:center;">
                        <img
                            src="<?php echo esc_url($file_url); ?>"
                            alt="<?php echo esc_attr($file); ?>"
                            style="max-width:100%; height:auto; border:0;"
                        >
                    </div>

                <?php elseif ($file_ext === 'pdf') : ?>
                    <iframe
                        src="<?php echo esc_url($file_url); ?>"
                        style="width:100%; min-height:900px; border:0;"
                        title="<?php echo esc_attr($file); ?>"
                    ></iframe>

                <?php elseif ($file_ext === 'txt') : ?>
                    <?php
                    // author: pablo rotem
                    $txt_content = file_get_contents($file_path);
                    if ($txt_content === false) {
                        $txt_content = 'לא ניתן לקרוא את תוכן הקובץ.';
                    }
                    ?>
                    <pre style="white-space:pre-wrap; word-break:break-word; direction:rtl; text-align:right;"><?php echo esc_html($txt_content); ?></pre>

                <?php else : ?>
                    <div>
                        סוג הקובץ נתמך להורדה, אך אין תצוגה מקדימה מובנית עבורו.
                        <div style="margin-top:10px;">
                            <a href="<?php echo esc_url($file_url); ?>" target="_blank" rel="noopener noreferrer">פתח קובץ</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>