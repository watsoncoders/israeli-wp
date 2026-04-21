<?php
/**
 * Template Name: Legacy Israeli Expert Form22
 * Description: Renders the original Israeli-Expert PHP form app inside WordPress while using theme assets.
 */

defined('ABSPATH') || exit;

add_action('wp_enqueue_scripts', function () {
    // Load WP’s jQuery early so legacy scripts have "$" available.
    wp_enqueue_script('jquery');
}, 1);

get_header();

// --- CONFIG: where the legacy project lives and where assets are ---
$theme_dir       = get_stylesheet_directory();
$theme_url       = get_stylesheet_directory_uri();
$assets_base_url = $theme_url . '/assets';
$legacy_root     = $theme_dir . '/assets/legacy-app';       // <-- put the original site here
$legacy_entry    = $legacy_root . '/index2.php';            // <-- change if your entry file differs

// Default GET params to mimic the original URL, can be overridden via page URL.
$legacy_query_defaults = [
    'id'       => '5127',
    'courseId' => '103',
    'lang'     => 'HEB',
];
// Merge defaults without clobbering existing GET values
foreach ($legacy_query_defaults as $k => $v) {
    if (!isset($_GET[$k])) {
        $_GET[$k] = $v;
    }
}

// Safety checks
if (!file_exists($legacy_entry)) {
    echo '<div class="wrap"><div class="notice notice-error" style="padding:16px;margin:24px 0;">'.
         'Legacy entry file not found at: <code>' . esc_html($legacy_entry) . '</code><br>'.
         'Place your original project under <code>/assets/legacy-app/</code> and ensure the entry file name is correct.'.
         '</div></div>';
    get_footer();
    return;
}

// Some legacy apps expect sessions
if (function_exists('session_status') && session_status() === PHP_SESSION_NONE) {
    @session_start();
}

// Capture legacy app output
$cwd = getcwd();
@chdir($legacy_root);
ob_start();
    // If the legacy app expects DOCUMENT_ROOT to be itself:
    $old_docroot = $_SERVER['DOCUMENT_ROOT'] ?? null;
    $_SERVER['DOCUMENT_ROOT'] = $legacy_root;

    // Include the original entry point (this may "exit"; that's okay — content is already buffered)
    include $legacy_entry;

$legacy_html = ob_get_clean();
@chdir($cwd);
// Restore docroot
if ($old_docroot !== null) {
    $_SERVER['DOCUMENT_ROOT'] = $old_docroot;
}

// --- Rewrite asset URLs in the legacy HTML so everything loads from your theme /assets ---
if (!empty($legacy_html)) {
    // 1) Absolute to legacy domain => theme assets
    $legacy_html = preg_replace(
        '#((?:src|href)=["\'])(?:https?:)?//(?:www\.)?israeli-expert\.co\.il/((?:designFiles|css|js|images)/[^"\']+)#i',
        '$1' . $assets_base_url . '/$2',
        $legacy_html
    );

    // 2) Root-relative => theme assets (e.g. /css/style.css -> /assets/css/style.css)
    $legacy_html = preg_replace(
        '#((?:src|href)=["\'])/((?:designFiles|css|js|images)/[^"\']+)#i',
        '$1' . $assets_base_url . '/$2',
        $legacy_html
    );

    // 3) Remove legacy jQuery includes to avoid conflicts; WP jQuery is already enqueued.
    $legacy_html = preg_replace(
        '#<script[^>]+src="[^"]*jquery[^"]*\.js"[^>]*>\s*</script>#i',
        '',
        $legacy_html
    );

    // 4) Make sure "$" is defined for any legacy inline scripts
    //    We inject just after the opening <body>, otherwise we prepend it.
    $jq_alias = '<script>(function(){if(window.jQuery){window.$ = window.jQuery;}})();</script>';
    if (preg_match('#<body[^>]*>#i', $legacy_html)) {
        $legacy_html = preg_replace('#<body[^>]*>#i', '$0' . $jq_alias, $legacy_html, 1);
    } else {
        $legacy_html = $jq_alias . $legacy_html;
    }

    // 5) If the legacy prints a full HTML document, keep only the body to avoid double <head>/<html> inside WP
    if (preg_match('#<body[^>]*>(.*)</body>#is', $legacy_html, $m)) {
        $legacy_html = $m[1];
    }
}

// Render within WP container (avoids "no posts found" — we bypass The Loop)
echo '<div class="legacy-iex-container" dir="rtl">';
// Optional: a minimal wrapper style if the legacy expects full-width
// echo '<style>.legacy-iex-container{max-width:1200px;margin:0 auto}</style>';
echo $legacy_html ?: '<p style="padding:16px">לא נטען שום תוכן מהאפליקציה הישנה.</p>';
echo '</div>';

get_footer();
