<?php
/**
 * Template Name: תבנית עמוד קורס תמא 38 מורחב - טקסט מתוקן
 * Description: עמוד קורס עם לשוניות תיאור, סילבוס ומרצים בעיצוב אחיד לכל הקורסים.
 * Author: pablo rotem
 */

defined('ABSPATH') || exit;

get_header();

function pablo_course_tama_extended_meta_text(int $post_id, string $key, string $fallback = ''): string
{
    $value = get_post_meta($post_id, $key, true);
    return is_string($value) && trim($value) !== '' ? $value : $fallback;
}

function pablo_course_tama_extended_meta_html(int $post_id, string $key, string $fallback = ''): string
{
    $value = get_post_meta($post_id, $key, true);
    return is_string($value) && trim($value) !== '' ? $value : $fallback;
}

function pablo_course_tama_extended_get_placeholder_image(): string
{
    $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="400" height="400"><rect width="100%" height="100%" fill="#f5f5f5"/><text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" font-family="Arial" font-size="26" fill="#777">אין תמונה</text></svg>';
    return 'data:image/svg+xml;charset=UTF-8,' . rawurlencode($svg);
}

function pablo_course_tama_extended_get_lecturer_image_url(int $lecturer_id): string
{
    $image_id = (int) get_post_meta($lecturer_id, '_pablo_card_image_id', true);
    if ($image_id > 0) {
        $url = wp_get_attachment_image_url($image_id, 'large');
        if ($url) {
            return $url;
        }
    }

    if (has_post_thumbnail($lecturer_id)) {
        $url = get_the_post_thumbnail_url($lecturer_id, 'large');
        if (is_string($url) && $url !== '') {
            return $url;
        }
    }

    $image_url = (string) get_post_meta($lecturer_id, '_pablo_card_image_url', true);
    if ($image_url !== '') {
        return $image_url;
    }

    return pablo_course_tama_extended_get_placeholder_image();
}

function pablo_course_tama_extended_get_lecturer_doc_url(int $lecturer_id): string
{
    $doc_id = (int) get_post_meta($lecturer_id, '_pablo_card_doc_id', true);
    if ($doc_id > 0) {
        $url = wp_get_attachment_url($doc_id);
        if ($url) {
            return $url;
        }
    }

    $doc_url = (string) get_post_meta($lecturer_id, '_pablo_card_doc_url', true);
    if ($doc_url !== '') {
        return $doc_url;
    }

    return '';
}

function pablo_course_tama_extended_get_lecturer_viewer_url(int $lecturer_id): string
{
    $doc_url = pablo_course_tama_extended_get_lecturer_doc_url($lecturer_id);
    if ($doc_url === '') {
        return '#';
    }

    return add_query_arg([
        'pablo_doc_view' => '1',
        'doc'            => rawurlencode($doc_url),
    ], home_url('/'));
}

$post_id        = get_the_ID();
$page_title     = get_the_title($post_id);
$home_url       = home_url('/');
$content_html   = apply_filters('the_content', (string) get_post_field('post_content', $post_id));
$register_url   = pablo_course_tama_extended_meta_text($post_id, 'pablo_register_url', home_url('/טופס-הרשמה-לקורס-תמא-38-מורחב/'));
$register_label = pablo_course_tama_extended_meta_text($post_id, 'pablo_register_label', 'להרשמה לקורס לחץ כאן');

$features_html = pablo_course_tama_extended_meta_html(
    $post_id,
    'pablo_tama_extended_features_html',
    '<p><span>אפשר להזין כאן מאפייני קורס מותאמים דרך meta key: pablo_tama_extended_features_html</span></p>'
);

$syllabus_html = pablo_course_tama_extended_meta_html(
    $post_id,
    'pablo_tama_extended_syllabus_html',
    '<div class="pablo-course-empty">לא הוזן עדיין סילבוס לעמוד זה. אפשר להדביק את כל ה-HTML הישן לשדה המטא pablo_tama_extended_syllabus_html.</div>'
);

$lecturers_query = new WP_Query([
    'post_type'      => 'pbl_lect105',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'orderby'        => [
        'menu_order' => 'ASC',
        'date'       => 'ASC',
    ],
    'order'          => 'ASC',
]);
?>

<style>
/* Author: pablo rotem */
.pablo-course-page{
    direction:rtl;
    text-align:right;
    max-width:1460px;
    margin:0 auto;
    padding:28px 18px 60px;
    color:#333;
}
.pablo-course-page,
.pablo-course-page *{
    box-sizing:border-box;
}
.pablo-course-header-top{
    display:flex;
    align-items:flex-start;
    justify-content:space-between;
    gap:24px;
    margin-bottom:28px;
}
.pablo-course-title-wrap{
    flex:1 1 auto;
}
.pablo-course-breadcrumbs{
    font-size:16px;
    color:#777;
    margin-bottom:16px;
}
.pablo-course-breadcrumbs a{
    color:#ffa801;
    text-decoration:none;
}
.pablo-course-title{
    margin:0;
    font-size:56px;
    line-height:1.1;
    color:#000046;
    font-weight:500;
}
.pablo-course-register{
    flex:0 0 auto;
    display:inline-flex;
    align-items:center;
    gap:12px;
    background:#ffa801;
    color:#000046;
    text-decoration:none;
    font-size:24px;
    font-weight:700;
    padding:18px 34px;
    border-radius:4px;
    white-space:nowrap;
    box-shadow:0 1px 0 rgba(0,0,0,.06);
}
.pablo-course-register:hover{
    color:#000046;
    opacity:.94;
}
.pablo-course-register .dashicons{
    font-size:28px;
    width:28px;
    height:28px;
}
.pablo-course-tabs{
    display:grid;
    grid-template-columns:repeat(3,minmax(0,1fr));
    gap:28px;
    margin:0 0 22px;
    padding:0;
    list-style:none;
    position:relative;
}
.pablo-course-tabs::after{
    content:"";
    position:absolute;
    right:0;
    left:0;
    bottom:-2px;
    border-bottom:3px solid #000046;
}
.pablo-course-tab-btn{
    position:relative;
    z-index:1;
    width:100%;
    appearance:none;
    border:0;
    background:#000046;
    color:#fff;
    min-height:68px;
    padding:14px 18px;
    font-size:22px;
    font-weight:700;
    cursor:pointer;
    border-radius:0;
}
.pablo-course-tab-btn .dashicons{
    vertical-align:middle;
    margin-right:6px;
}
.pablo-course-tab-btn.is-active{
    background:#fff;
    color:#000046;
    border:4px solid #000046;
    border-bottom-color:#fff;
}
.pablo-course-panel{
    display:none;
}
.pablo-course-panel.is-active{
    display:block;
}
.pablo-course-desc-grid{
    display:grid;
    grid-template-columns:320px minmax(0,1fr);
    gap:24px;
    align-items:start;
}
.pablo-course-features,
.pablo-course-text,
.pablo-course-syllabus-wrap,
.pablo-course-lecturers-wrap{
    background:#fff;
}
.pablo-course-features{
    border:1px solid #e1e1e1;
    border-radius:3px;
    padding:24px 28px;
    box-shadow:0 1px 2px rgba(0,0,0,.06);
}
.pablo-course-features h3{
    margin:0 0 16px;
    color:#333;
    font-size:24px;
    font-weight:400;
}
.pablo-course-features p{
    margin:0 0 18px;
    color:#4a4a4a;
    font-size:17px;
    line-height:1.7;
}
.pablo-course-text{
    border:1px solid #ececec;
    min-height:126px;
    padding:26px 30px;
}
.pablo-course-text h1,
.pablo-course-text h2,
.pablo-course-text h3,
.pablo-course-text h4,
.pablo-course-syllabus-wrap h1,
.pablo-course-syllabus-wrap h2,
.pablo-course-syllabus-wrap h3,
.pablo-course-syllabus-wrap h4{
    color:#000046;
}
.pablo-course-text,
.pablo-course-text p,
.pablo-course-text li,
.pablo-course-syllabus-wrap,
.pablo-course-syllabus-wrap p,
.pablo-course-syllabus-wrap li{
    font-size:19px;
    line-height:1.8;
    color:#333;
}
.pablo-course-text ul,
.pablo-course-syllabus-wrap ul{
    padding-right:20px;
}
.pablo-course-syllabus-wrap{
    border:1px solid #ececec;
    padding:26px 30px;
}
.pablo-course-empty{
    padding:28px;
    border:1px dashed #bbb;
    color:#666;
    font-size:18px;
}
.pablo-course-lecturers-wrap{
    border:1px solid #ececec;
    padding:10px 22px 24px;
}
.pablo-course-lecturer{
    display:grid;
    direction:ltr;
    grid-template-columns:110px minmax(0,1fr) 140px;
    grid-template-areas:'icon content image';
    gap:24px;
    align-items:center;
    min-height:150px;
    padding:20px 0;
    border-bottom:3px solid #ffa801;
}
.pablo-course-lecturer:last-child{
    border-bottom:0;
}
.pablo-course-lecturer-icon{
    grid-area:icon;
    display:flex;
    align-items:center;
    justify-content:flex-start;
}
.pablo-course-lecturer-icon-link{
    text-decoration:none;
}
.pablo-course-lecturer-icon-circle{
    width:96px;
    height:96px;
    border-radius:50%;
    border:2px solid #ffa801;
    display:flex;
    align-items:center;
    justify-content:center;
    color:#ffa801;
    transition:.2s ease;
}
.pablo-course-lecturer-icon-link:hover .pablo-course-lecturer-icon-circle{
    background:#fff7df;
    transform:scale(1.03);
}
.pablo-course-lecturer-icon-circle .dashicons{
    font-size:54px;
    width:54px;
    height:54px;
}
.pablo-course-lecturer-content{
    grid-area:content;
    direction:rtl;
    text-align:right;
    display:flex;
    flex-direction:column;
    align-items:flex-start;
    justify-content:center;
}
.rtl5span-1{
    color:#000046;
    font-size:24px;
    font-weight:600;
}
.rtlcourcespara{
    font-size:17px;
    font-weight:500;
    color:#555;
}
.pablo-course-lecturer-name{
    margin:0 0 10px;
    line-height:1.25;
    text-align:right;
    width:100%;
}
.pablo-course-lecturer-subtitle{
    margin:0;
    line-height:1.7;
    text-align:right;
    width:100%;
}
.pablo-course-lecturer-image-wrap{
    grid-area:image;
    text-align:right;
}
.pablo-course-lecturer-image{
    width:120px;
    height:120px;
    object-fit:cover;
    border:1px solid #ddd;
    background:#fff;
    display:inline-block;
}
@media (max-width:1100px){
    .pablo-course-title{font-size:42px;}
    .pablo-course-desc-grid{grid-template-columns:1fr;}
    .pablo-course-features{order:2;}
    .pablo-course-text{order:1;}
}
@media (max-width:900px){
    .pablo-course-header-top{flex-direction:column;align-items:stretch;}
    .pablo-course-register{width:100%;justify-content:center;}
    .pablo-course-tabs{grid-template-columns:1fr;gap:12px;}
    .pablo-course-tabs::after{display:none;}
    .pablo-course-tab-btn.is-active{border-bottom-color:#000046;}
    .pablo-course-lecturer{
        grid-template-columns:1fr;
        grid-template-areas:'image' 'content' 'icon';
        text-align:right;
    }
    .pablo-course-lecturer-image-wrap{text-align:center;}
    .pablo-course-lecturer-icon{justify-content:center;}
    .pablo-course-lecturer-content{align-items:flex-start;text-align:right;}
}
</style>

<div class="pablo-course-page">
    <div class="pablo-course-header-top">
        <div class="pablo-course-title-wrap">
            <div class="pablo-course-breadcrumbs">
                <a href="<?php echo esc_url($home_url); ?>">דף הבית</a>
                <span> &gt; </span>
                <span><?php echo esc_html($page_title); ?></span>
            </div>
            <h1 class="pablo-course-title"><?php echo esc_html($page_title); ?></h1>
        </div>

        <a class="pablo-course-register" href="<?php echo esc_url($register_url); ?>">
            <span><?php echo esc_html($register_label); ?></span>
            <span class="dashicons dashicons-edit"></span>
        </a>
    </div>

    <ul class="pablo-course-tabs" role="tablist">
        <li><button type="button" class="pablo-course-tab-btn" data-tab-target="pablo-course-tab-description-105"><span class="dashicons dashicons-book-alt"></span> תיאור</button></li>
        <li><button type="button" class="pablo-course-tab-btn" data-tab-target="pablo-course-tab-syllabus-105"><span class="dashicons dashicons-archive"></span> סילבוס</button></li>
        <li><button type="button" class="pablo-course-tab-btn is-active" data-tab-target="pablo-course-tab-lecturers-105"><span class="dashicons dashicons-businessperson"></span> מרצים</button></li>
    </ul>

    <section id="pablo-course-tab-description-105" class="pablo-course-panel">
        <div class="pablo-course-desc-grid">
            <aside class="pablo-course-features">
                <h3>מאפייני הקורס</h3>
                <?php echo wp_kses_post($features_html); ?>
            </aside>

            <div class="pablo-course-text">
                <?php echo wp_kses_post($content_html); ?>
            </div>
        </div>
    </section>

    <section id="pablo-course-tab-syllabus-105" class="pablo-course-panel">
        <div class="pablo-course-syllabus-wrap">
            <?php echo wp_kses_post($syllabus_html); ?>
        </div>
    </section>

    <section id="pablo-course-tab-lecturers-105" class="pablo-course-panel is-active">
        <div class="pablo-course-lecturers-wrap">
            <?php if ($lecturers_query->have_posts()) : ?>
                <?php while ($lecturers_query->have_posts()) : $lecturers_query->the_post(); ?>
                    <?php
                    $lecturer_id    = get_the_ID();
                    $card_subtitle  = (string) get_post_meta($lecturer_id, '_pablo_card_subtitle', true);
                    $viewer_url     = pablo_course_tama_extended_get_lecturer_viewer_url($lecturer_id);
                    $card_image_url = pablo_course_tama_extended_get_lecturer_image_url($lecturer_id);
                    ?>
                    <article class="pablo-course-lecturer">
                        <div class="pablo-course-lecturer-image-wrap">
                            <img class="pablo-course-lecturer-image" src="<?php echo esc_url($card_image_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                        </div>

                        <div class="pablo-course-lecturer-content">
                            <h2 class="pablo-course-lecturer-name"><span class="rtl5span-1"><?php echo esc_html(get_the_title()); ?></span></h2>
                            <p class="pablo-course-lecturer-subtitle"><span class="rtlcourcespara"><?php echo esc_html($card_subtitle); ?></span></p>
                        </div>

                        <div class="pablo-course-lecturer-icon">
                            <?php if ($viewer_url !== '#') : ?>
                                <a class="pablo-course-lecturer-icon-link" href="<?php echo esc_url($viewer_url); ?>" aria-label="פתח מסמך">
                                    <div class="pablo-course-lecturer-icon-circle">
                                        <span class="dashicons dashicons-welcome-learn-more"></span>
                                    </div>
                                </a>
                            <?php else : ?>
                                <div class="pablo-course-lecturer-icon-circle" aria-hidden="true">
                                    <span class="dashicons dashicons-welcome-learn-more"></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endwhile; wp_reset_postdata(); ?>
            <?php else : ?>
                <div class="pablo-course-empty">עדיין לא יובאו מרצים ל־CPT המתאים. ברגע שתייבא CSV הרשימה תופיע כאן אוטומטית.</div>
            <?php endif; ?>
        </div>
    </section>
</div>

<script>
// Author: pablo rotem
(function () {
    var buttons = document.querySelectorAll('.pablo-course-tab-btn');
    var panels = document.querySelectorAll('.pablo-course-panel');

    function activateTab(targetId) {
        buttons.forEach(function (button) {
            button.classList.toggle('is-active', button.getAttribute('data-tab-target') === targetId);
        });

        panels.forEach(function (panel) {
            panel.classList.toggle('is-active', panel.id === targetId);
        });
    }

    buttons.forEach(function (button) {
        button.addEventListener('click', function () {
            activateTab(button.getAttribute('data-tab-target'));
        });
    });
})();
</script>

<?php get_footer(); ?>
