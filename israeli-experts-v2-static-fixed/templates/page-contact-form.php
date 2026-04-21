<?php
/*
 * Template Name: צור קשר
 * Description: Exact replica of the contact form and send.php logic
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// --- 1. HANDLE FORM SUBMISSION (send.php Logic) ---
$message = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_nonce'])) {

    if ( ! wp_verify_nonce( $_POST['contact_nonce'], 'submit_contact_form' ) ) {
        die( 'Security check failed' );
    }

    global $wpdb;

   // 1.2 Sanitize Inputs - Fixed by Pablo Rotem to prevent PHP 8 Warnings
    $firstname  = sanitize_text_field($_POST['firstname'] ?? '');
    $lastname   = sanitize_text_field($_POST['lastname'] ?? '');
    $middlename = sanitize_text_field($_POST['middlename'] ?? '');
    $fullname   = trim($firstname . ' ' . $middlename . ' ' . $lastname);
    $email      = sanitize_email($_POST['email'] ?? '');
    $address    = sanitize_text_field($_POST['address'] ?? '');
    
    // Phone Logic - Safe concatenation
    $cp_pre    = $_POST['cellphonePrefix'] ?? '';
    $cp_post   = $_POST['cellphonePostfix'] ?? '';
    $cellphone = ($cp_pre || $cp_post) ? sanitize_text_field($cp_pre) . '-' . sanitize_text_field($cp_post) : '';

    $p_pre     = $_POST['phonePrefix'] ?? '';
    $p_post    = $_POST['phonePostfix'] ?? '';
    $phone     = ($p_pre || $p_post) ? sanitize_text_field($p_pre) . '-' . sanitize_text_field($p_post) : '';

    $f_pre     = $_POST['faxPrefix'] ?? '';
    $f_post    = $_POST['faxPostfix'] ?? '';
    $fax       = ($f_pre || $f_post) ? sanitize_text_field($f_pre) . '-' . sanitize_text_field($f_post) : '';
    $remarks   = sanitize_textarea_field($_POST['remarks']);
    $desc      = sanitize_textarea_field($_POST['desc']);
    $title     = "פנייה מהאתר";

    // 1.3 Generate Contact ID
    $contactId = $wpdb->get_var("SELECT MAX(id) FROM contacts") + 1;

    // 1.4 Handle File Uploads (Using WordPress Uploads Directory)
    $wp_upload_dir = wp_upload_dir();
    $target_dir = $wp_upload_dir['basedir'] . '/uploadedFiles/';
    
    if (!file_exists($target_dir)) {
        wp_mkdir_p($target_dir);
    }

    function handle_contact_upload($file_key, $dir) {
        if (!empty($_FILES[$file_key]['name'])) {
            $file = $_FILES[$file_key];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg','gif','png','bmp','doc','docx','xls','xlsx','pdf','txt'];
            
            if (in_array($ext, $allowed)) {
                $new_filename = time() . "_" . $file_key . "." . $ext;
                if (move_uploaded_file($file['tmp_name'], $dir . $new_filename)) {
                    return $new_filename;
                }
            }
        }
        return "";
    }

    $attachfile1 = handle_contact_upload('attachfile', $target_dir);
    $attachfile2 = handle_contact_upload('attachfile2', $target_dir);

    // 1.5 Insert into Database
    $wpdb->insert('contacts', [
        'id'                 => $contactId,
        'fromPage'           => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '',
        'insertTime'         => current_time('mysql'),
        'fullname'           => $fullname,
        'phone'              => $phone,
        'cellphone'          => $cellphone,
        'fax'                => $fax,
        'email'              => $email,
        'address'            => $address,
        'title'              => $title,
        'msg'                => $desc,
        'moreDetails'        => $remarks,
        'attachfile'         => $attachfile1,
        'attachfile2'        => $attachfile2,
        'isHuman'            => 1
    ]);

    // 1.6 Send Email
    $to = get_option('admin_email');
    $subject = "פנייה חדשה מהאתר #$contactId";
    $headers = array('Content-Type: text/html; charset=UTF-8');
    
    $body = "<html><body dir='rtl'><h2>פנייה חדשה מהאתר</h2><table border='0' cellpadding='5'>
            <tr><td><b>שם מלא:</b></td><td>$fullname</td></tr>
            <tr><td><b>אימייל:</b></td><td>$email</td></tr>
            <tr><td><b>טלפון:</b></td><td>$phone</td></tr>
            <tr><td><b>סלולרי:</b></td><td>$cellphone</td></tr>
            <tr><td><b>כתובת:</b></td><td>$address</td></tr>
            <tr><td><b>תוכן:</b></td><td>$desc</td></tr>
            </table></body></html>";

    wp_mail($to, $subject, $body, $headers);

    $success = true;
}

get_header(); 
$theme_uri = get_template_directory_uri();
?>

<style>
    .ie-container { direction: rtl; text-align: right; font-family: sans-serif; padding-top: 30px; }
    .para { color: #333; font-size: 22px; border-bottom: 3px solid #FFA801; padding-bottom: 10px; margin-bottom: 25px; font-weight: bold; }
    .row-grid { margin-bottom: 15px; position: relative; }
    .form-control { width: 100%; height: 40px; border: 1px solid #ccc; padding: 10px; border-radius: 4px; }
    .option-contact { width: 100%; height: 40px; border: 1px solid #ccc; border-radius: 4px; }
    .star-icon-contat89 { color: red; font-size: 10px; position: absolute; top: 12px; left: 15px; z-index: 10; }
    .from-sumit-1 { background: #FFA801; color: #fff; font-size: 20px; font-weight: bold; padding: 12px 60px; border: none; cursor: pointer; border-radius: 4px; }
    .icon-img { width: 25px; vertical-align: middle; margin-left: 10px; }
    .paddXsZ { max-width: 900px; margin: 0 auto; }
</style>

<div class="ie-container">
    <div class="container paddXsZ">
        <div class="col-md-12 text-center">
            <h1 class="page3line">צור קשר</h1>
        </div>
        
        <?php if ($success): ?>
            <div class='alert alert-success' style='text-align:center; margin:20px; border:2px solid green; padding:40px; background: #f9fff9;'>
                <h3 style="color: green;">תודה רבה!</h3>
                <p>פנייתך התקבלה בהצלחה מס' #<?php echo $contactId; ?>. ניצור עמך קשר בהקדם.</p>
            </div>
        <?php else: ?>

        <div class="col-md-12 pagespecialpara">
            <h2 class="para">פרטי יצירת קשר</h2>
            
            <form method="post" id="contactForm" enctype="multipart/form-data">
                <?php wp_nonce_field('submit_contact_form', 'contact_nonce'); ?>

                <div class="row">
                    <div class="col-md-5 col-xs-12">
                        <div class="row-grid">
                            <i class="fa fa-star star-icon-contat89"></i>
                            <input name="firstname" placeholder="שם פרטי" required type="text" class="form-control"> 
                        </div>
                        <div class="row-grid">
                            <input name="lastname" placeholder="שם משפחה" type="text" class="form-control">
                        </div>
                        <div class="row-grid">
                            <input name="middlename" placeholder="שם אמצעי" type="text" class="form-control"> 
                        </div>
                        <div class="row-grid">
                            <i class="fa fa-star star-icon-contat89"></i>
                            <input name="email" placeholder="דוא&quot;ל" required type="email" class="form-control"> 
                        </div>
                    </div>
                    
                    <div class="col-md-7 col-xs-12">
                        <div class="row row-grid">
                            <div class="col-xs-2 text-center"><img src="<?php echo $theme_uri; ?>/designFiles/message.png" class="icon-img" alt="mail"></div>
                            <div class="col-xs-10"><input name="address" placeholder="כתובת למשלוח דואר" type="text" class="form-control"></div>
                        </div>
                        
                        <div class="row row-grid">
                            <div class="col-xs-2 text-center"><img src="<?php echo $theme_uri; ?>/designFiles/mobile.png" class="icon-img" alt="mobile"></div>
                            <div class="col-xs-6"><input name="cellphonePostfix" placeholder="מס’ טלפון סלולר" required type="text" class="form-control"></div>
                            <div class="col-xs-4">
                                <select name="cellphonePrefix" class="option-contact">
                                    <option value="050">050</option><option value="052">052</option><option value="054">054</option>
                                </select>
                            </div>
                        </div>

                        <div class="row row-grid">
                            <div class="col-xs-2 text-center"><img src="<?php echo $theme_uri; ?>/designFiles/phone.png" class="icon-img" alt="phone"></div>
                            <div class="col-xs-6"><input name="phonePostfix" placeholder="מס’ טלפון בבית" type="text" class="form-control"></div>
                            <div class="col-xs-4">
                                <select name="phonePrefix" class="option-contact">
                                    <option value="02">02</option><option value="03">03</option><option value="04">04</option><option value="08">08</option><option value="09">09</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12 row-grid">
                        <input name="remarks" placeholder="למידע התקשרות משלים" type="text" class="form-control"> 
                    </div>
                    <div class="col-md-12 row-grid">
                        <textarea placeholder="תוכן הפנייה" id="desc" name="desc" class="form-control" style="height: 120px;"></textarea>
                    </div>
                </div>

                <div class="text-center" style="margin-top: 20px;">
                    <button type="submit" class="from-sumit-1">שליחה</button>
                </div>

                <p style="text-align: center; margin-top: 40px;">
                    <a href="mailto:info@israeli-expert.co.il">info@israeli-expert.co.il</a> | שד' מוריה 105, חיפה 34616 | טל' 04-8244633
                </p>
            </form>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
/**
 * פתרון שגיאת jQuery is not defined
 * הקוד ממתין לטעינת הספרייה לפני הרצה
 */
document.addEventListener('DOMContentLoaded', function() {
    if (window.jQuery) {
        jQuery(document).ready(function($) {
            console.log('Contact form ready and jQuery is loaded.');
            
            // ניתן להוסיף כאן ולידציות צד לקוח נוספות
            $('#contactForm').on('submit', function() {
                // בדיקה בסיסית לפני שליחה
                if ($('#firstname').val() === '') {
                    alert('נא למלא שם פרטי');
                    return false;
                }
            });
        });
    }
});
</script>

<?php get_footer(); ?>