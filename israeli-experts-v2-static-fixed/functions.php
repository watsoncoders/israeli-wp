<?php
/**
 * Theme: Israeli Experts
 * Author: Pablo Rotem
 * Version: 18.0 (Auto Content Clone from XML + Clean Core + Expert System)
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// =================================================================
// 1. טעינת סקריפטים ועיצובים (FRONTEND ONLY)
// =================================================================
add_action('wp_enqueue_scripts', function () {
    $theme_uri = get_template_directory_uri();

    // CSS
    wp_enqueue_style('ie-bootstrap', $theme_uri . '/assets/css/bootstrap.min.css', array(), '3.3.6');
    wp_enqueue_style('ie-cast', $theme_uri . '/assets/css/cast.css', array('ie-bootstrap'), '1.0');
    wp_enqueue_style('ie-style', get_stylesheet_uri(), array('ie-bootstrap', 'ie-cast'), '1.0');

    // jQuery (Downgrade to 1.12.4 for Bootstrap 3 compatibility)
    wp_deregister_script('jquery');
    wp_register_script('jquery', 'https://code.jquery.com/jquery-1.12.4.min.js', array(), '1.12.4', false);
    wp_enqueue_script('jquery');

    // Bootstrap JS
    wp_enqueue_script('ie-bootstrap', $theme_uri . '/assets/js/bootstrap.min.js', array('jquery'), '3.3.6', true);
});
// הפעלת תמיכה בתמונות ראשית (Featured Images)
add_action('after_setup_theme', function() {
    add_theme_support('post-thumbnails');
});
// =================================================================
// 2. מנוע שכפול תוכן אוטומטי (XML Parser)
// =================================================================
add_action('after_switch_theme', 'pablo_install_content_from_xml');

function pablo_install_content_from_xml() {
    // נתיב לקובץ ה-XML בתיקיית התבנית
    $xml_file = get_template_directory() . '/sitetitle.WordPress.2025-12-28.xml';

    if (!file_exists($xml_file)) {
        error_log('Pablo Theme: XML import file not found.');
        return;
    }

    $xml = simplexml_load_file($xml_file);
    if (!$xml) return;

    // הגדרת Namespaces לקריאת תגיות וורדפרס
    $namespaces = $xml->getNamespaces(true);
    $wp_ns = $namespaces['wp'] ?? 'http://wordpress.org/export/1.2/';
    $content_ns = $namespaces['content'] ?? 'http://purl.org/rss/1.0/modules/content/';

    foreach ($xml->channel->item as $item) {
        $wp = $item->children($wp_ns);
        $content = $item->children($content_ns);

        // יצירת עמודים (Pages) מפורסמים בלבד
        if ((string)$wp->post_type !== 'page' || (string)$wp->status !== 'publish') continue;

        $slug = (string)$wp->post_name;
        $title = (string)$item->title;
        $page_html = (string)$content->encoded; // כאן נמצא כל ה-HTML הגדול

        // בדיקה אם העמוד כבר קיים
        $existing = get_page_by_path($slug);

        if (!$existing) {
            $post_data = [
                'post_title'    => $title,
                'post_name'     => $slug,
                'post_content'  => $page_html, // הזרקת התוכן המקורי
                'post_status'   => 'publish',
                'post_type'     => 'page',
                'post_author'   => 1
            ];

            $post_id = wp_insert_post($post_data);

            if ($post_id && !is_wp_error($post_id)) {
                // שחזור הגדרת ה-Page Template
                foreach ($wp->postmeta as $meta) {
                    if ((string)$meta->meta_key === '_wp_page_template') {
                        update_post_meta($post_id, '_wp_page_template', (string)$meta->meta_value);
                    }
                }

                // הגדרת דף הבית
                if ($slug === 'home') {
                    update_option('show_on_front', 'page');
                    update_option('page_on_front', $post_id);
                }
            }
        }
    }
}

// =================================================================
// 3. פונקציות עזר (Helpers)
// =================================================================
if (!function_exists('hx_rel')) {
    function hx_rel($path = '', $args = []) {
        $url = home_url('/' . ltrim($path, '/'));
        if ($args) $url = add_query_arg($args, $url);
        return esc_url(wp_make_link_relative($url));
    }
}
if (!function_exists('hx_asset')) {
    function hx_asset($rel) {
        $u = trailingslashit(get_template_directory_uri()) . 'assets/' . ltrim($rel, '/');
        return esc_url(wp_make_link_relative($u));
    }
}

// =================================================================
// 4. מודאל התחברות חכם (Login Modals)
// =================================================================
function add_custom_login_modals() {
    $theme_url = get_template_directory_uri(); 
    ?>
    
    <style>
        .custom-modal-overlay { display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4); }
        .custom-modal-content { background-color: #fefefe; margin: 10% auto; padding: 0; border: 1px solid #888; width: 100%; max-width: 600px; border-radius: 6px; position: relative; direction: rtl; text-align: right; }
        .custom-modal-header { padding: 15px; border-bottom: 1px solid #e5e5e5; display: flex; justify-content: space-between; align-items: center; }
        .custom-modal-header .close-modal { background: none; border: none; font-size: 24px; font-weight: bold; color: #aaa; cursor: pointer; }
        .custom-modal-title { margin: 0; font-size: 18px; font-weight: 500; }
        .custom-modal-body { padding: 20px 30px; }
        .form-group { margin-bottom: 15px; }
        .form-control { width: 100%; padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .fbuttons { text-align: center; margin-top: 20px; }
        .btn-orange { background-color: #eee; color: #333; border: 1px solid #ccc; padding: 8px 20px; border-radius: 4px; cursor: pointer; font-size: 16px; }
        .forgot-link { color: #337ab7; text-decoration: none; font-size: 14px; margin-top: 5px; display: inline-block; }
        .header-controls { display: flex; align-items: center; gap: 10px; }
    </style>

    <div id="modal-login-custom" class="custom-modal-overlay">
        <div class="custom-modal-content">
            <div class="custom-modal-header">
                <div class="header-controls">
                    <span class="custom-modal-title">כניסת משתמשים רשומים</span>
                    <button type="button" class="close-modal">&times;</button>
                </div>
            </div>
            <div class="custom-modal-body">
                <form id="loginForm" method="post" action="<?php echo $theme_url; ?>/clubLogin.php">
                    <input type="hidden" id="returnTo" name="returnTo" value="">
                    
                    <div class="form-group">
                        <label for="username">שם משתמש</label>
                        <input type="text" id="username" name="username" class="form-control" placeholder="שם משתמש">
                    </div>

                    <div class="form-group">
                        <label for="password">סיסמה</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="סיסמה">
                        <a href="#" class="forgot-link" id="open-reset-modal">שכחתי את הסיסמה</a>
                    </div>

                    <div class="fbuttons">
                        <button type="submit" class="btn-orange">כניסה</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="modal-reset-custom" class="custom-modal-overlay">
        <div class="custom-modal-content">
            <div class="custom-modal-header">
                <div class="header-controls">
                    <span class="custom-modal-title">איפוס סיסמה</span>
                    <button type="button" class="close-modal">&times;</button>
                </div>
            </div>
            <div class="custom-modal-body">
                <form id="resetForm" method="post" action="<?php echo $theme_url; ?>/clubReset.php"> 
                    <div class="form-group">
                        <label for="reset_username">שם משתמש</label>
                        <input type="text" id="reset_username" name="username" class="form-control" placeholder="שם משתמש">
                    </div>

                    <div class="form-group">
                        <label for="new_password">סיסמה חדשה</label>
                        <input type="password" id="new_password" name="new_password" class="form-control" placeholder="סיסמה חדשה">
                    </div>

                    <div class="fbuttons">
                        <button type="submit" class="btn-orange">שליחה</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    jQuery(document).ready(function($) {
        $('.key-link').on('click', function(e) {
            e.preventDefault();
            $('#modal-login-custom').fadeIn(300);
        });

        $('#open-reset-modal').on('click', function(e) {
            e.preventDefault();
            $('#modal-login-custom').hide();
            $('#modal-reset-custom').fadeIn(300);
        });

        $('.close-modal').on('click', function() {
            $('.custom-modal-overlay').fadeOut(300);
        });

        $(window).on('click', function(e) {
            if ($(e.target).hasClass('custom-modal-overlay')) {
                $('.custom-modal-overlay').fadeOut(300);
            }
        });
    });
    </script>
    
<?php
}
add_action('wp_footer', 'add_custom_login_modals');


// =================================================================
// 1. REGISTER CPTs & TAXONOMY
// =================================================================
function register_cpts_init() {
    // 1. Expert
    register_post_type('expert', array(
        'labels' => array('name' => 'מומחים', 'singular_name' => 'מומחה'),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-businessperson',
        'supports' => array('title', 'thumbnail'),
    ));

    // 2. Borer (Arbitrator) - Using Gavel (closest to scales in WP)
    register_post_type('borer', array(
        'labels' => array('name' => 'בוררים', 'singular_name' => 'בורר'),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-gavel', 
        'supports' => array('title', 'thumbnail'),
    ));

    // 3. Combined (Meshulav)
    register_post_type('combined', array(
        'labels' => array('name' => 'נרשמים למשולב', 'singular_name' => 'נרשם משולב'),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-networking', // Icon for combined/network
        'supports' => array('title', 'thumbnail'),
    ));

    // Shared Taxonomy
    register_taxonomy('expert_category', array('expert', 'borer', 'combined'), array(
        'labels' => array('name' => 'קטגוריות', 'singular_name' => 'קטגוריה'),
        'hierarchical' => true,
        'show_admin_column' => true,
    ));
}
add_action('init', 'register_cpts_init');

// =================================================================
// 2. ADMIN ASSETS
// =================================================================
function enqueue_cpt_admin_scripts($hook) {
    global $post;
    $types = ['expert', 'borer', 'combined'];
    if ( ($hook == 'post-new.php' || $hook == 'post.php') && in_array($post->post_type, $types) ) {     
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_style('jquery-ui-style', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css');
        ?>
        <style>
            .expert-box { padding: 15px; background: #fff; border: 1px solid #ccd0d4; margin-bottom: 15px; }
            .expert-repeater-row { display: flex; flex-wrap: wrap; gap: 10px; align-items: flex-end; background: #f9f9f9; border: 1px solid #ddd; padding: 10px; margin-bottom: 10px; }
            .expert-field-group { display: flex; flex-direction: column; }
            .expert-field-group label { font-weight: bold; font-size: 12px; margin-bottom: 3px; }
            .expert-field-group input, .expert-field-group select { min-width: 120px; }
            .remove-row { color: #a00; cursor: pointer; font-size: 20px; margin-right: auto; align-self: center; }
            .add-row-btn { background: #ffa801 !important; color: #fff !important; border: none !important; font-weight: bold !important; }
            .wide-input { width: 100%; min-width: 400px; }
            hr { border: 0; border-top: 1px solid #ddd; margin: 15px 0; }
            .fixed-lang-row { background: #e5f5fa; border-color: #b8e6f5; }
        </style>
        <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('.expert-datepicker').datepicker({ dateFormat : 'yy-mm-dd', changeMonth: true, changeYear: true, yearRange: "-100:+0" });
            
            // Dynamic Repeater
            $('.add-row-btn').on('click', function(e) {
                e.preventDefault();
                var wrapper = $(this).prev('.repeater-container');
                var template = $(this).next('.repeater-template').html();
                // Find highest index
                var count = wrapper.find('.expert-repeater-row').length + 5; // offset
                var newRow = template.replace(/{index}/g, count); 
                wrapper.append(newRow);
            });

            $(document).on('click', '.remove-row', function() {
                if(confirm('האם למחוק שורה זו?')) $(this).closest('.expert-repeater-row').remove();
            });
        });
        </script>
        <?php
    }
}
add_action('admin_enqueue_scripts', 'enqueue_cpt_admin_scripts');

// =================================================================
// 3. META BOXES
// =================================================================
function add_shared_meta_boxes() {
    $screens = ['expert', 'borer', 'combined']; 
    foreach ($screens as $screen) {
        add_meta_box('exp_personal', 'פרטים אישיים (א)', 'render_exp_personal', $screen, 'normal', 'high');
        add_meta_box('exp_contact', 'פרטי התקשרות (ב)', 'render_exp_contact', $screen, 'normal', 'high');
        add_meta_box('exp_social', 'רשתות חברתיות (ג)', 'render_exp_social', $screen, 'normal', 'default');
        add_meta_box('exp_education', 'השכלה (ד)', 'render_exp_education', $screen, 'normal', 'default');
        add_meta_box('exp_languages', 'ידיעת שפות (ה)', 'render_exp_languages', $screen, 'normal', 'default');
        add_meta_box('exp_work', 'עיסוק, מקצוע ונגישות (ו-ח)', 'render_exp_work', $screen, 'normal', 'default');
        add_meta_box('exp_orgs', 'ארגונים (ט)', 'render_exp_orgs', $screen, 'normal', 'default');
        add_meta_box('exp_friend', 'חבר מביא חבר (ע)', 'render_exp_friends', $screen, 'normal', 'default');
        
        // Moved to Normal (Center) as requested
        add_meta_box('exp_invoice', 'מידע חשבונית (מ)', 'render_exp_invoice', $screen, 'normal', 'low');
        add_meta_box('exp_payment', 'תשלום וכרטיס אשראי (ל)', 'render_exp_payment', $screen, 'normal', 'low');
        
        add_meta_box('exp_declarations', 'הצהרות (פ)', 'render_exp_declarations', $screen, 'side', 'low');
    }
}
add_action('add_meta_boxes', 'add_shared_meta_boxes');

// Helpers
function get_val($pid, $key) { return esc_attr(get_post_meta($pid, $key, true)); }
function get_check($pid, $key) { return get_post_meta($pid, $key, true) ? 'checked' : ''; }
function lang_select($name, $val) {
    $opts = ['' => 'כלל לא', '1' => 'שפת אם', '2' => 'טובה', '3' => 'בסיסית'];
    $out = '<select name="'.$name.'">';
    foreach($opts as $k => $v) { $sel = ($val == $k) ? 'selected' : ''; $out .= "<option value='$k' $sel>$v</option>"; }
    $out .= '</select>';
    return $out;
}

// =================================================================
// 4. RENDER FUNCTIONS
// =================================================================

function render_exp_personal($post) {
    $y = get_val($post->ID, 'birthYear'); $m = get_val($post->ID, 'birthMonth'); $d = get_val($post->ID, 'birthDay');
    $full_date = ($y && $m && $d) ? "$y-$m-$d" : '';
    ?>
    <table class="form-table">
        <tr><th>שם פרטי</th><td><input type="text" name="firstname" value="<?php echo get_val($post->ID, 'firstname'); ?>"></td>
            <th>שם משפחה</th><td><input type="text" name="lastname" value="<?php echo get_val($post->ID, 'lastname'); ?>"></td></tr>
        <tr><th>תואר</th><td><input type="text" name="fldExtentName" value="<?php echo get_val($post->ID, 'fldExtentName'); ?>"></td>
            <th>מגדר</th><td><select name="gender"><option value="m" <?php selected(get_val($post->ID, 'gender'), 'm'); ?>>זכר</option><option value="f" <?php selected(get_val($post->ID, 'gender'), 'f'); ?>>נקבה</option></select></td></tr>
        <tr><th>ת.ז.</th><td><input type="text" name="identityNumber" value="<?php echo get_val($post->ID, 'identityNumber'); ?>"></td>
            <th>תאריך לידה</th><td><input type="text" name="birth_date_full" value="<?php echo $full_date; ?>" class="expert-datepicker"></td></tr>
        <tr><th>אזור חיוג</th><td><input type="text" name="fldDialZone" value="<?php echo get_val($post->ID, 'fldDialZone'); ?>"></td>
            <th>מידע נוסף</th><td><input type="text" name="moreDetails" value="<?php echo get_val($post->ID, 'moreDetails'); ?>" class="wide-input"></td></tr>
    </table>
    <?php
}

function render_exp_contact($post) {
    ?>
    <table class="form-table">
        <tr><th>נייד</th><td><input type="text" name="cellphone" value="<?php echo get_val($post->ID, 'cellphone'); ?>"> <label><input type="checkbox" name="hideCellphone" value="1" <?php echo get_check($post->ID, 'hideCellphone'); ?>> הסתר</label></td></tr>
        <tr><th>טלפון</th><td><input type="text" name="phone" value="<?php echo get_val($post->ID, 'phone'); ?>"> <label><input type="checkbox" name="hidePhone" value="1" <?php echo get_check($post->ID, 'hidePhone'); ?>> הסתר</label></td></tr>
        <tr><th>טלפון נוסף</th><td><input type="text" name="phone2" value="<?php echo get_val($post->ID, 'phone2'); ?>"> <label><input type="checkbox" name="hidePhone2" value="1" <?php echo get_check($post->ID, 'hidePhone2'); ?>> הסתר</label></td></tr>
        <tr><th>פקס</th><td><input type="text" name="fax" value="<?php echo get_val($post->ID, 'fax'); ?>"> <label><input type="checkbox" name="hideFax" value="1" <?php echo get_check($post->ID, 'hideFax'); ?>> הסתר</label></td></tr>
        <tr><th>אימייל</th><td><input type="text" name="email" value="<?php echo get_val($post->ID, 'email'); ?>"> <label><input type="checkbox" name="hideEmail" value="1" <?php echo get_check($post->ID, 'hideEmail'); ?>> הסתר</label></td></tr>
        <tr><th>כתובת</th><td><input type="text" name="address" placeholder="רחוב" value="<?php echo get_val($post->ID, 'address'); ?>"> <input type="text" name="streetNo" placeholder="מס" value="<?php echo get_val($post->ID, 'streetNo'); ?>" style="width:50px;"> <input type="text" name="city" placeholder="עיר" value="<?php echo get_val($post->ID, 'city'); ?>"></td></tr>
        <tr><th>מדינה/מיקוד</th><td><input type="text" name="country" placeholder="מדינה" value="<?php echo get_val($post->ID, 'country'); ?>"> <input type="text" name="zipcode" placeholder="מיקוד" value="<?php echo get_val($post->ID, 'zipcode'); ?>"></td></tr>
        <tr><th>דואר למשלוח</th><td><input type="text" name="mailAddress" value="<?php echo get_val($post->ID, 'mailAddress'); ?>" class="wide-input"></td></tr>
        <tr><th>אתר אינטרנט</th><td><input type="text" name="mySite" value="<?php echo get_val($post->ID, 'mySite'); ?>" class="wide-input"></td></tr>
        <tr><th>פרטיות</th><td><label><input type="checkbox" name="hideAddress" value="1" <?php echo get_check($post->ID, 'hideAddress'); ?>> הסתר כתובת באתר?</label></td></tr>
    </table>
    <?php
}

function render_exp_social($post) {
    ?>
    <p>לינקדאין: <input type="text" name="linkedinPage" value="<?php echo get_val($post->ID, 'linkedinPage'); ?>" class="wide-input"></p>
    <p>פייסבוק: <input type="text" name="facebookPage" value="<?php echo get_val($post->ID, 'facebookPage'); ?>" class="wide-input"></p>
    <p>טוויטר: <input type="text" name="twitterPage" value="<?php echo get_val($post->ID, 'twitterPage'); ?>" class="wide-input"></p>
    <p>סקייפ: <input type="text" name="skype" value="<?php echo get_val($post->ID, 'skype'); ?>" class="wide-input"></p>
    <?php
}

function render_exp_education($post) {
    ?>
    <div class="expert-box">
        <h3>השכלה בסיסית</h3>
        <div class="expert-repeater-row">
            <div class="expert-field-group"><label>יסודי</label><input type="text" name="elementarySchool" value="<?php echo get_val($post->ID, 'elementarySchool'); ?>"></div>
            <div class="expert-field-group"><label>עיר</label><input type="text" name="elementarySchoolCity" value="<?php echo get_val($post->ID, 'elementarySchoolCity'); ?>"></div>
            <div class="expert-field-group"><label>שנים</label><input type="text" name="elementarySchoolYears" value="<?php echo get_val($post->ID, 'elementarySchoolYears'); ?>" style="width:50px;"></div>
        </div>
        <div class="expert-repeater-row">
            <div class="expert-field-group"><label>תיכון</label><input type="text" name="highSchool" value="<?php echo get_val($post->ID, 'highSchool'); ?>"></div>
            <div class="expert-field-group"><label>עיר</label><input type="text" name="highSchoolCity" value="<?php echo get_val($post->ID, 'highSchoolCity'); ?>"></div>
            <div class="expert-field-group"><label>מגמה</label><input type="text" name="highSchoolFaculty" value="<?php echo get_val($post->ID, 'highSchoolFaculty'); ?>"></div>
            <div class="expert-field-group"><label>שנים</label><input type="text" name="schoolYears" value="<?php echo get_val($post->ID, 'schoolYears'); ?>" style="width:50px;"></div>
        </div>
        <p><strong>סה"כ שנות לימוד:</strong> <?php echo get_val($post->ID, 'totalYears'); ?></p>
        <p><strong>מידע משלים:</strong> <input type="text" name="moreSchoolDetails" value="<?php echo get_val($post->ID, 'moreSchoolDetails'); ?>" class="wide-input"></p>
    </div>

    <h3>השכלה גבוהה (אוניברסיטאות)</h3>
    <?php
    echo '<div class="repeater-container">';
    $i=1; while(true){
        $uni = get_post_meta($post->ID, 'university'.$i, true);
        if(!$uni && $i>1) break; if($i>20) break; if(!$uni) $uni='';
        $city = get_post_meta($post->ID, 'university'.$i.'City', true);
        $fac  = get_post_meta($post->ID, 'university'.$i.'Faculty', true);
        $deg  = get_post_meta($post->ID, 'university'.$i.'Degree', true);
        $yrs  = get_post_meta($post->ID, 'university'.$i.'Years', true);
        ?>
        <div class="expert-repeater-row">
            <div class="expert-field-group"><label>מוסד</label><input type="text" name="uni_rep[<?php echo $i?>][name]" value="<?php echo esc_attr($uni); ?>"></div>
            <div class="expert-field-group"><label>עיר</label><input type="text" name="uni_rep[<?php echo $i?>][city]" value="<?php echo esc_attr($city); ?>"></div>
            <div class="expert-field-group"><label>פקולטה</label><input type="text" name="uni_rep[<?php echo $i?>][fac]" value="<?php echo esc_attr($fac); ?>"></div>
            <div class="expert-field-group"><label>תואר</label><input type="text" name="uni_rep[<?php echo $i?>][degree]" value="<?php echo esc_attr($deg); ?>"></div>
            <div class="expert-field-group"><label>שנים</label><input type="text" name="uni_rep[<?php echo $i?>][years]" value="<?php echo esc_attr($yrs); ?>" style="width:50px"></div>
            <span class="remove-row dashicons dashicons-trash"></span>
        </div>
        <?php $i++;
    }
    echo '</div><button class="button add-row-btn">הוסף מוסד לימודים</button>';
    ?>
    <div class="repeater-template" style="display:none">
        <div class="expert-repeater-row">
            <div class="expert-field-group"><label>מוסד</label><input type="text" name="uni_rep[{index}][name]"></div>
            <div class="expert-field-group"><label>עיר</label><input type="text" name="uni_rep[{index}][city]"></div>
            <div class="expert-field-group"><label>פקולטה</label><input type="text" name="uni_rep[{index}][fac]"></div>
            <div class="expert-field-group"><label>תואר</label><input type="text" name="uni_rep[{index}][degree]"></div>
            <div class="expert-field-group"><label>שנים</label><input type="text" name="uni_rep[{index}][years]" style="width:50px"></div>
            <span class="remove-row dashicons dashicons-trash"></span>
        </div>
    </div>
    <?php
}

function render_exp_languages($post) {
    ?>
    <div class="expert-repeater-row fixed-lang-row">
        <div class="expert-field-group"><label>שפה</label><input type="text" value="עברית" readonly></div>
        <div class="expert-field-group"><label>דיבור</label><?php echo lang_select("lang1speak", get_val($post->ID, 'lang1speak')); ?></div>
        <div class="expert-field-group"><label>קריאה</label><?php echo lang_select("lang1read", get_val($post->ID, 'lang1read')); ?></div>
        <div class="expert-field-group"><label>כתיבה</label><?php echo lang_select("lang1write", get_val($post->ID, 'lang1write')); ?></div>
    </div>
    <div class="expert-repeater-row fixed-lang-row">
        <div class="expert-field-group"><label>שפה</label><input type="text" value="אנגלית" readonly></div>
        <div class="expert-field-group"><label>דיבור</label><?php echo lang_select("lang2speak", get_val($post->ID, 'lang2speak')); ?></div>
        <div class="expert-field-group"><label>קריאה</label><?php echo lang_select("lang2read", get_val($post->ID, 'lang2read')); ?></div>
        <div class="expert-field-group"><label>כתיבה</label><?php echo lang_select("lang2write", get_val($post->ID, 'lang2write')); ?></div>
    </div>
    <div class="expert-repeater-row fixed-lang-row">
        <div class="expert-field-group"><label>שפה</label><input type="text" value="ערבית" readonly></div>
        <div class="expert-field-group"><label>דיבור</label><?php echo lang_select("lang3speak", get_val($post->ID, 'lang3speak')); ?></div>
        <div class="expert-field-group"><label>קריאה</label><?php echo lang_select("lang3read", get_val($post->ID, 'lang3read')); ?></div>
        <div class="expert-field-group"><label>כתיבה</label><?php echo lang_select("lang3write", get_val($post->ID, 'lang3write')); ?></div>
    </div>

    <h4>שפות נוספות</h4>
    <?php
    echo '<div class="repeater-container">';
    $i=4; while(true){
        $nm = get_post_meta($post->ID, 'lang'.$i, true);
        if(!$nm && $i>4) break; if($i>20) break; if(!$nm) $nm='';
        
        $sp = get_post_meta($post->ID, 'lang'.$i.'speak', true);
        $rd = get_post_meta($post->ID, 'lang'.$i.'read', true);
        $wr = get_post_meta($post->ID, 'lang'.$i.'write', true);
        ?>
        <div class="expert-repeater-row">
            <div class="expert-field-group"><label>שפה</label><input type="text" name="lng_rep[<?php echo $i?>][name]" value="<?php echo esc_attr($nm); ?>"></div>
            <div class="expert-field-group"><label>דיבור</label><?php echo lang_select("lng_rep[$i][speak]", $sp); ?></div>
            <div class="expert-field-group"><label>קריאה</label><?php echo lang_select("lng_rep[$i][read]", $rd); ?></div>
            <div class="expert-field-group"><label>כתיבה</label><?php echo lang_select("lng_rep[$i][write]", $wr); ?></div>
            <span class="remove-row dashicons dashicons-trash"></span>
        </div>
        <?php $i++;
    }
    echo '</div><button class="button add-row-btn">הוסף שפה נוספת</button>';
    ?>
    <div class="repeater-template" style="display:none">
        <div class="expert-repeater-row">
            <div class="expert-field-group"><label>שפה</label><input type="text" name="lng_rep[{index}][name]"></div>
            <div class="expert-field-group"><label>דיבור</label><?php echo lang_select("lng_rep[{index}][speak]", ''); ?></div>
            <div class="expert-field-group"><label>קריאה</label><?php echo lang_select("lng_rep[{index}][read]", ''); ?></div>
            <div class="expert-field-group"><label>כתיבה</label><?php echo lang_select("lng_rep[{index}][write]", ''); ?></div>
            <span class="remove-row dashicons dashicons-trash"></span>
        </div>
    </div>
    <?php
}

function render_exp_work($post) {
    ?>
    <table class="form-table">
        <tr><th>מקצוע</th><td><input type="text" name="fldProfession" value="<?php echo get_val($post->ID, 'fldProfession'); ?>"></td></tr>
        <tr><th>ותק (שנים)</th><td><input type="number" name="fldGeneralLongevity" value="<?php echo get_val($post->ID, 'fldGeneralLongevity'); ?>"></td></tr>
        <tr><th>עיסוק נוכחי</th><td><input type="text" name="currBiz" value="<?php echo get_val($post->ID, 'currBiz'); ?>" class="wide-input"></td></tr>
        <tr><th>התמחות</th><td><input type="text" name="fldSpecialization" value="<?php echo get_val($post->ID, 'fldSpecialization'); ?>"></td></tr>
        <tr><th>מס' רישיון</th><td><input type="text" name="licenseNo" value="<?php echo get_val($post->ID, 'licenseNo'); ?>"></td></tr>
        
        <tr><td colspan="2"><hr><strong>ניסיון קודם</strong></td></tr>
        <tr><th>חוו"ד (5 שנים)</th><td><textarea name="experience2" class="wide-input" rows="2"><?php echo get_val($post->ID, 'experience2'); ?></textarea><br><label><input type="checkbox" name="experience1" value="1" <?php echo get_check($post->ID, 'experience1'); ?>> סימון V</label></td></tr>
        <tr><th>הופעה בבימ"ש</th><td><textarea name="experience4" class="wide-input" rows="2"><?php echo get_val($post->ID, 'experience4'); ?></textarea><br><label><input type="checkbox" name="experience3" value="1" <?php echo get_check($post->ID, 'experience3'); ?>> סימון V</label></td></tr>

        <tr><td colspan="2"><hr><strong>מקום עבודה</strong></td></tr>
        <tr><th>שם מקום</th><td><input type="text" name="workplace" value="<?php echo get_val($post->ID, 'workplace'); ?>"></td></tr>
        <tr><th>טלפון עבודה</th><td><input type="text" name="workphone" value="<?php echo get_val($post->ID, 'workphone'); ?>"></td></tr>
        <tr><th>ותק בעבודה</th><td><input type="text" name="fldLongevity" value="<?php echo get_val($post->ID, 'fldLongevity'); ?>"></td></tr>
        <tr><th>פקס בעבודה</th><td><input type="text" name="workfax" value="<?php echo get_val($post->ID, 'workfax'); ?>"></td></tr>
        <tr><th>אתר עבודה</th><td><input type="text" name="workweb" value="<?php echo get_val($post->ID, 'workweb'); ?>" class="wide-input"></td></tr>
        <tr><th>כתובת ראשית</th><td><input type="text" name="workaddress" value="<?php echo get_val($post->ID, 'workaddress'); ?>" class="wide-input"></td></tr>
        <tr><th>סניף 1</th><td><input type="text" name="workaddress1" value="<?php echo get_val($post->ID, 'workaddress1'); ?>" class="wide-input"></td></tr>
        <tr><th>סניף 2</th><td><input type="text" name="workaddress2" value="<?php echo get_val($post->ID, 'workaddress2'); ?>" class="wide-input"></td></tr>

        <tr><td colspan="2"><hr><strong>נגישות</strong></td></tr>
        <tr><th>אוטובוסים</th><td><input type="text" name="accessBuses" value="<?php echo get_val($post->ID, 'accessBuses'); ?>"></td></tr>
        <tr><th>רכבת</th><td><input type="text" name="accessTrain" value="<?php echo get_val($post->ID, 'accessTrain'); ?>"></td></tr>
        <tr><th>חנייה</th><td><input type="text" name="accessPark" value="<?php echo get_val($post->ID, 'accessPark'); ?>"></td></tr>
        <tr><th>בית קפה</th><td><input type="text" name="accessCoffee" value="<?php echo get_val($post->ID, 'accessCoffee'); ?>"></td></tr>
        <tr><th>צ'קבוקסים</th><td>
            <label><input type="checkbox" name="accessDisabled" value="1" <?php echo get_check($post->ID, 'accessDisabled'); ?>> נגיש לנכים</label>
            <label><input type="checkbox" name="accessElevator" value="1" <?php echo get_check($post->ID, 'accessElevator'); ?>> מעלית</label>
        </td></tr>
    </table>
    <?php
}

function render_exp_orgs($post) {
    echo '<div class="repeater-container">';
    $i=1; while(true){
        $nm = get_post_meta($post->ID, 'org'.$i, true);
        if(!$nm && $i>1) break; if($i>15) break; if(!$nm) $nm='';
        $ln = get_post_meta($post->ID, 'org'.$i.'link', true);
        ?>
        <div class="expert-repeater-row">
            <div class="expert-field-group"><label>ארגון</label><input type="text" name="org_rep[<?php echo $i?>][name]" value="<?php echo esc_attr($nm); ?>"></div>
            <div class="expert-field-group"><label>לינק</label><input type="text" name="org_rep[<?php echo $i?>][link]" value="<?php echo esc_attr($ln); ?>"></div>
            <span class="remove-row dashicons dashicons-trash"></span>
        </div>
        <?php $i++; }
    echo '</div><button class="button add-row-btn">הוסף ארגון</button>';
    ?>
    <div class="repeater-template" style="display:none">
        <div class="expert-repeater-row">
            <div class="expert-field-group"><label>ארגון</label><input type="text" name="org_rep[{index}][name]"></div>
            <div class="expert-field-group"><label>לינק</label><input type="text" name="org_rep[{index}][link]"></div>
            <span class="remove-row dashicons dashicons-trash"></span>
        </div>
    </div>
    <?php
}

function render_exp_friends($post) {
    echo '<div class="repeater-container">';
    $i=1; while(true){
        $fn = get_post_meta($post->ID, 'lead'.$i.'firstname', true);
        if(!$fn && $i>1) break; if($i>15) break; if(!$fn) $fn='';
        $ln = get_post_meta($post->ID, 'lead'.$i.'lastname', true);
        $cl = get_post_meta($post->ID, 'lead'.$i.'cellphone', true);
        $pr = get_post_meta($post->ID, 'lead'.$i.'profession', true);
        ?>
        <div class="expert-repeater-row">
            <div class="expert-field-group"><label>שם</label><input type="text" name="fr_rep[<?php echo $i?>][fn]" value="<?php echo esc_attr($fn); ?>"></div>
            <div class="expert-field-group"><label>משפחה</label><input type="text" name="fr_rep[<?php echo $i?>][ln]" value="<?php echo esc_attr($ln); ?>"></div>
            <div class="expert-field-group"><label>נייד</label><input type="text" name="fr_rep[<?php echo $i?>][cl]" value="<?php echo esc_attr($cl); ?>"></div>
            <div class="expert-field-group"><label>מקצוע</label><input type="text" name="fr_rep[<?php echo $i?>][pr]" value="<?php echo esc_attr($pr); ?>"></div>
            <span class="remove-row dashicons dashicons-trash"></span>
        </div>
        <?php $i++; }
    echo '</div><button class="button add-row-btn">הוסף חבר</button>';
    ?>
    <div class="repeater-template" style="display:none">
        <div class="expert-repeater-row">
            <div class="expert-field-group"><label>שם</label><input type="text" name="fr_rep[{index}][fn]"></div>
            <div class="expert-field-group"><label>משפחה</label><input type="text" name="fr_rep[{index}][ln]"></div>
            <div class="expert-field-group"><label>נייד</label><input type="text" name="fr_rep[{index}][cl]"></div>
            <div class="expert-field-group"><label>מקצוע</label><input type="text" name="fr_rep[{index}][pr]"></div>
            <span class="remove-row dashicons dashicons-trash"></span>
        </div>
    </div>
    <?php
}

function render_exp_invoice($post) {
    $invTo = get_val($post->ID, 'invoiceTo');
    ?>
    <table class="form-table">
    <tr>
        <th>חשבונית על שם:</th>
        <td><input type="text" name="name4invoice" value="<?php echo get_val($post->ID, 'name4invoice'); ?>" class="wide-input"></td>
    </tr>
    <tr>
        <th>לשלוח ל:</th>
        <td>
            <label><input type="radio" name="invoiceTo" value="בית" <?php checked($invTo, 'בית'); ?>> בית</label> 
            <label style="margin-right:20px;"><input type="radio" name="invoiceTo" value="משרד" <?php checked($invTo, 'משרד'); ?>> משרד</label>
        </td>
    </tr>
    <tr>
        <th>כתובת אחרת:</th>
        <td><input type="text" name="invoice2addr" value="<?php echo get_val($post->ID, 'invoice2addr'); ?>" class="wide-input"></td>
    </tr>
    </table>
    <?php
}

function render_exp_payment($post) {
    ?>
    <table class="form-table">
    <tr>
        <th>סיכום תשלום:</th>
        <td>
            <p><strong>סכום לתשלום:</strong> <?php echo get_val($post->ID, 'totalPay'); ?> ₪</p>
            <p><strong>מספר תשלומים:</strong> <?php echo get_val($post->ID, 'paymentOption'); ?></p>
        </td>
    </tr>
    <tr>
        <th>פרטי אשראי:</th>
        <td>
            <p><strong>בעל כרטיס:</strong> <input type="text" name="creditCardHolder" value="<?php echo get_val($post->ID, 'creditCardHolder'); ?>"></p>
            <p><strong>מספר כרטיס:</strong> <input type="text" name="creditCardNumber" value="<?php echo get_val($post->ID, 'creditCardNumber'); ?>" class="wide-input"></p>
            <p><strong>CVV:</strong> <input type="text" name="creditCardCVV" value="<?php echo get_val($post->ID, 'creditCardCVV'); ?>" style="width:50px;"></p>
            <p><strong>סוג:</strong> <input type="text" name="course_creditType" value="<?php echo get_val($post->ID, 'course_creditType'); ?>"></p>
            <p><strong>ת.ז. בעל כרטיס:</strong> <input type="text" name="creditCardTz" value="<?php echo get_val($post->ID, 'creditCardTz'); ?>"></p>
            <p><strong>תוקף:</strong> <input type="text" name="creditCardMonth" value="<?php echo get_val($post->ID, 'creditCardMonth'); ?>" style="width:40px"> / <input type="text" name="creditCardYear" value="<?php echo get_val($post->ID, 'creditCardYear'); ?>" style="width:50px"></p>
        </td>
    </tr>
    </table>
    <?php
}

function render_exp_declarations($post) {
    ?>
    <div class="checkbox-list">
    <label><input type="checkbox" name="check1" value="1" <?php echo get_check($post->ID, 'check1'); ?>> אין עבר פלילי</label>
    <label><input type="checkbox" name="check2" value="1" <?php echo get_check($post->ID, 'check2'); ?>> פרטים אמת</label>
    <label><input type="checkbox" name="check3" value="1" <?php echo get_check($post->ID, 'check3'); ?>> אימות אצל עו"ד</label>
    <label><input type="checkbox" name="check8" value="1" <?php echo get_check($post->ID, 'check8'); ?>> הצהרת עו"ד</label>
    <label><input type="checkbox" name="check5" value="1" <?php echo get_check($post->ID, 'check5'); ?>> המצאת מסמכים</label>
    <label><input type="checkbox" name="check6" value="1" <?php echo get_check($post->ID, 'check6'); ?>> אתיקה ותקנון</label>
    <label><input type="checkbox" name="check4" value="1" <?php echo get_check($post->ID, 'check4'); ?>> אישור תקנון</label>
    <label><input type="checkbox" name="check7" value="1" <?php echo get_check($post->ID, 'check7'); ?>> השתלמויות</label>
    <hr>
    <label><strong><input type="checkbox" name="checkTermsAccept" value="1" <?php echo get_check($post->ID, 'checkTermsAccept'); ?>> אישר תנאים והערות</strong></label>
    </div>
    <?php
}


// =================================================================
// 5. SAVE FUNCTION
// =================================================================
function shared_save_meta_final($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $post_type = get_post_type($post_id);
    if(!in_array($post_type, ['expert', 'borer', 'combined'])) return;

    // Dates
    if(!empty($_POST['birth_date_full'])) {
        $p = explode('-', $_POST['birth_date_full']);
        if(count($p)==3) { update_post_meta($post_id, 'birthYear', $p[0]); update_post_meta($post_id, 'birthMonth', $p[1]); update_post_meta($post_id, 'birthDay', $p[2]); }
    }

    // Fixed Languages (1-3)
    $fixed_langs = ['lang1', 'lang1speak', 'lang1read', 'lang1write', 
                    'lang2', 'lang2speak', 'lang2read', 'lang2write', 
                    'lang3', 'lang3speak', 'lang3read', 'lang3write'];
    foreach($fixed_langs as $fl) {
        if(isset($_POST[$fl])) update_post_meta($post_id, $fl, sanitize_text_field($_POST[$fl]));
    }

    // Standard Fields
    $fields = [
        'firstname','lastname','fldExtentName','gender','identityNumber','fldDialZone','moreDetails',
        'cellphone','hideCellphone','phone','hidePhone','phone2','hidePhone2','fax','hideFax','email','hideEmail','mailAddress','mySite',
        'address','streetNo','city','zipcode','country','hideAddress','linkedinPage','facebookPage','twitterPage','skype',
        'elementarySchool','elementarySchoolCity','elementarySchoolYears','highSchool','highSchoolCity','highSchoolFaculty','schoolYears','moreSchoolDetails','totalYears',
        'fldProfession','fldGeneralLongevity','currBiz','fldSpecialization','licenseNo','fldLongevity',
        'experience1','experience2','experience3','experience4',
        'workplace','workaddress','workaddress1','workaddress2','workphone','workfax','workweb',
        'accessBuses','accessTrain','accessPark','accessCoffee','accessDisabled','accessElevator',
        'name4invoice','invoiceTo','invoice2addr','creditCardHolder','creditCardNumber','creditCardCVV','course_creditType','creditCardTz','creditCardMonth','creditCardYear','paymentOption','totalPay',
        'check1','check2','check3','check4','check5','check6','check7','check8','checkTermsAccept'
    ];
    foreach($fields as $f) {
        if(isset($_POST[$f])) update_post_meta($post_id, $f, sanitize_text_field($_POST[$f]));
        else {
            // Uncheck booleans
            if(in_array($f, ['hideCellphone','hidePhone','hidePhone2','hideFax','hideEmail','hideAddress','accessDisabled','accessElevator','check1','check2','check3','check4','check5','check6','check7','check8','checkTermsAccept','experience1','experience3'])) delete_post_meta($post_id, $f);
        }
    }

    // Repeaters Logic 
    // 1. Education
    if(isset($_POST['uni_rep'])) {
        $c=1; foreach($_POST['uni_rep'] as $r) {
            if(!empty($r['name'])) {
                update_post_meta($post_id, 'university'.$c, sanitize_text_field($r['name']));
                update_post_meta($post_id, 'university'.$c.'City', sanitize_text_field($r['city']));
                update_post_meta($post_id, 'university'.$c.'Faculty', sanitize_text_field($r['fac']));
                update_post_meta($post_id, 'university'.$c.'Degree', sanitize_text_field($r['degree']));
                update_post_meta($post_id, 'university'.$c.'Years', sanitize_text_field($r['years']));
                $c++;
            }
        }
        for($k=$c; $k<20; $k++) delete_post_meta($post_id, 'university'.$k);
    }
    // 2. Languages (Dynamic part starting from 4)
    if(isset($_POST['lng_rep'])) {
        $c=4; foreach($_POST['lng_rep'] as $r) {
            if(!empty($r['name'])) {
                update_post_meta($post_id, 'lang'.$c, sanitize_text_field($r['name']));
                update_post_meta($post_id, 'lang'.$c.'speak', sanitize_text_field($r['speak']));
                update_post_meta($post_id, 'lang'.$c.'read', sanitize_text_field($r['read']));
                update_post_meta($post_id, 'lang'.$c.'write', sanitize_text_field($r['write']));
                $c++;
            }
        }
        for($k=$c; $k<20; $k++) delete_post_meta($post_id, 'lang'.$k);
    }
    // 3. Orgs
    if(isset($_POST['org_rep'])) {
        $c=1; foreach($_POST['org_rep'] as $r) {
            if(!empty($r['name'])) {
                update_post_meta($post_id, 'org'.$c, sanitize_text_field($r['name']));
                update_post_meta($post_id, 'org'.$c.'link', sanitize_text_field($r['link']));
                $c++;
            }
        }
        for($k=$c; $k<20; $k++) delete_post_meta($post_id, 'org'.$k);
    }
    // 4. Friends
    if(isset($_POST['fr_rep'])) {
        $c=1; foreach($_POST['fr_rep'] as $r) {
            if(!empty($r['fn'])) {
                update_post_meta($post_id, 'lead'.$c.'firstname', sanitize_text_field($r['fn']));
                update_post_meta($post_id, 'lead'.$c.'lastname', sanitize_text_field($r['ln']));
                update_post_meta($post_id, 'lead'.$c.'cellphone', sanitize_text_field($r['cl']));
                update_post_meta($post_id, 'lead'.$c.'profession', sanitize_text_field($r['pr']));
                $c++;
            }
        }
        for($k=$c; $k<20; $k++) delete_post_meta($post_id, 'lead'.$k.'firstname');
    }
}
add_action('save_post', 'shared_save_meta_final');
?>