<?php
/*
Template Name: Contact – Existing Table Mapping
Template Post Type: page
*/
defined('ABSPATH') || exit;

// Capture "firstArrivedAtPage" (first referrer) *before* output
if (!headers_sent() && empty($_COOKIE['iei_first_arrived_url'])) {
  $first_ref = isset($_SERVER['HTTP_REFERER']) ? esc_url_raw($_SERVER['HTTP_REFERER']) : '';
  // 30 days, site-wide cookie
  $cookie_path = defined('COOKIEPATH') ? COOKIEPATH : '/';
  $cookie_domain = defined('COOKIE_DOMAIN') && COOKIE_DOMAIN ? COOKIE_DOMAIN : $_SERVER['HTTP_HOST'];
  setcookie('iei_first_arrived_url', $first_ref, time() + 30 * DAY_IN_SECONDS, $cookie_path, $cookie_domain, is_ssl(), true);
}

get_header();

global $wpdb;
$table_name = $wpdb->prefix . 'contacts';

// helpers
function iei_post($key, $default = '') { return isset($_POST[$key]) ? wp_unslash($_POST[$key]) : $default; }
function iei_clean_phone($val) {
  $val = (string)$val;
  $val = preg_replace('/[^0-9+\-\s()]/', '', $val);
  return trim($val);
}

$messages = [];
$submitted = ($_SERVER['REQUEST_METHOD'] === 'POST');

if ($submitted && isset($_POST['iei_contact_nonce']) && wp_verify_nonce($_POST['iei_contact_nonce'], 'iei_save_contact_existing')) {

  // Gather & sanitize inputs
  $first_name = sanitize_text_field(iei_post('firstname'));
  $middle_name = sanitize_text_field(iei_post('middlename'));
  $last_name  = sanitize_text_field(iei_post('lastname'));
  $email      = sanitize_email(iei_post('email'));
  $address    = sanitize_text_field(iei_post('address'));

  $cellphone_visible = iei_clean_phone(iei_post('cellphonePostfix'));
  $phone_visible     = iei_clean_phone(iei_post('phonePostfix'));
  $fax_visible       = iei_clean_phone(iei_post('faxPostfix'));

  // Hidden fallbacks (kept from original HTML)
  $cellphone_hidden = iei_clean_phone(iei_post('cellphone'));
  $phone_hidden     = iei_clean_phone(iei_post('phone'));
  $fax_hidden       = iei_clean_phone(iei_post('fax'));

  $remarks    = sanitize_text_field(iei_post('remarks'));      // maps to moreDetails
  $desc       = wp_kses_post(iei_post('desc'));                // maps to msg

  // Basic validation
  if ($first_name === '') { $messages[] = ['type'=>'error','text'=>'First Name is required.']; }
  if ($email === '' || !is_email($email)) { $messages[] = ['type'=>'error','text'=>'Please enter a valid email address.']; }
  if ($cellphone_visible === '' && $cellphone_hidden === '') { $messages[] = ['type'=>'error','text'=>'Cellular No. is required.']; }

  if (!array_filter($messages, fn($m)=>$m['type']==='error')) {

    // Build mapping for existing schema
    $fullname = trim($first_name . ' ' . $middle_name . ' ' . $last_name);
    $from_page_title = get_the_title();
    $from_page_url   = get_permalink();
    $referer_url     = wp_get_referer() ?: '';
    $first_arrived   = isset($_COOKIE['iei_first_arrived_url']) ? sanitize_text_field($_COOKIE['iei_first_arrived_url']) : '';

    $data = [
      'insertTime'         => current_time('mysql'),                // datetime
      'status'             => 'new',                                // string status you prefer
      'fromPage'           => $from_page_title . ' (' . $from_page_url . ')',
      'referer'            => $referer_url,
      'firstArrivedAtPage' => $first_arrived,                       // first referrer captured in cookie

      'fullname'           => $fullname,
      'age'                => '',                                   // not in form
      'phone'              => $phone_visible ?: $phone_hidden,
      'phone2'             => '',                                   // not in form (keep spare)
      'cellphone'          => $cellphone_visible ?: $cellphone_hidden,
      'fax'                => $fax_visible ?: $fax_hidden,
      'email'              => $email,
      'address'            => $address,
      'country'            => '',                                   // not in form
      'zipcode'            => '',                                   // not in form

      'title'              => $from_page_title,                     // page title as subject
      'msg'                => $desc,                                // textarea "Description"
      'company'            => '',                                   // not in form
      'companyRole'        => '',                                   // not in form
      'moreDetails'        => $remarks,                             // "More contact info"
      'followup'           => 0,                                    // default
      'attachfile'         => '',                                   // no upload in this HTML
      'attachfile2'        => '',                                   // no upload in this HTML
      'isHuman'            => 1,                                    // passed nonce -> treat as human
    ];

    $format = [
      '%s','%s','%s','%s','%s',
      '%s','%s','%s','%s','%s','%s','%s','%s','%s','%s',
      '%s','%s','%s','%s','%s','%d','%s','%s','%d'
    ];

    $inserted = $wpdb->insert($table_name, $data, $format);

    if ($inserted) {
      $messages[] = ['type'=>'success','text'=>'Thanks! Your message was sent successfully.'];
      $_POST = []; // reset form
    } else {
      $messages[] = ['type'=>'error','text'=>'Sorry, saving to the contacts table failed.'];
    }
  }
} elseif ($submitted) {
  $messages[] = ['type'=>'error','text'=>'Security check failed. Please refresh and try again.'];
}

// Asset helpers (works on any domain)
$asset = function($file) { return esc_url( get_theme_file_uri('assets/images/' . $file) ); };
$message_png = $asset('message.png');
$mobile_png  = $asset('mobile.png');
$phone_png   = $asset('phone.png');
$printer_png = $asset('printer.png');
?>

<div class="container-fluid">
  <div class="container paddXsZ">
    <div class="col-md-12 col-sm-12 col-xs-12 paddingZ">
      <p class="page3">Contact Us</p>
      <h1 class="page3line">Contact Us</h1>
    </div>

    <?php if (!empty($messages)): ?>
      <div class="col-md-12" style="margin-bottom:15px;">
        <?php foreach ($messages as $m): ?>
          <div class="<?php echo ($m['type']==='success' ? 'updated' : 'error'); ?>" style="padding:10px;border:1px solid #ddd;border-radius:4px;<?php echo $m['type']==='success' ? 'background:#f6ffed;' : 'background:#fff1f0;'; ?>">
            <?php echo esc_html($m['text']); ?>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <div class="col-md-12 pagespecialpara brbotom">
      <h2 class="para">Contact details</h2>
      <div class="contct-details contact-detail-34">
        <div class="contact-right">

          <form action="<?php echo esc_url( get_permalink() ); ?>" method="post" id="contactForm">
            <?php wp_nonce_field('iei_save_contact_existing', 'iei_contact_nonce'); ?>

            <!-- keep the original hidden inputs (not used for saving unless visible fields are empty) -->
            <input type="hidden" id="phone" name="phone" value="<?php echo esc_attr(iei_post('phone')); ?>">
            <input type="hidden" id="cellphone" name="cellphone" value="<?php echo esc_attr(iei_post('cellphone')); ?>">
            <input type="hidden" id="fax" name="fax" value="<?php echo esc_attr(iei_post('fax')); ?>">
            <input type="hidden" name="returnContactId" value="1">

            <div class="" style="direction:ltr">
              <div class="col-md-5 col-sm-5 col-xs-12 row-grid">
                <div class="row-grid">
                  <i class="fa fa-star star-icon-contat89 star-icon-eng"></i>
                  <input name="firstname" id="firstname" placeholder="First Name" required type="text" dir="ltr" value="<?php echo esc_attr(iei_post('firstname')); ?>">
                  <label for="firstname" class="hidden-section">First Name</label>
                </div>
                <div class="row-grid">
                  <input name="lastname" id="lastname" placeholder="Last Name" type="text" dir="ltr" value="<?php echo esc_attr(iei_post('lastname')); ?>">
                  <label for="lastname" class="hidden-section">Last Name</label>
                </div>
                <div class="row-grid">
                  <input name="middlename" id="middlename" placeholder="Middle Name" type="text" dir="ltr" value="<?php echo esc_attr(iei_post('middlename')); ?>">
                  <label for="middlename" class="hidden-section">Middle Name</label>
                </div>
                <div class="row-grid">
                  <i class="fa fa-star star-icon-contat89 star-icon-eng"></i>
                  <input name="email" id="email" placeholder="Email" required type="text" dir="ltr" value="<?php echo esc_attr(iei_post('email')); ?>">
                  <label for="email" class="hidden-section">Email</label>
                </div>
              </div>

              <div class="col-md-7 col-sm-7 col-xs-12 row-grid contwidth">
                <div class="row">
                  <div class="col-md-2   col-sm-2 col-xs-2 "><img src="<?php echo $message_png; ?>" class="con-mail" alt="mail"></div>
                  <div class="col-md-10  col-sm-10 col-xs-10 row-grid contwidth">
                    <input name="address" id="address" placeholder="Address" type="text" dir="ltr" value="<?php echo esc_attr(iei_post('address')); ?>">
                    <label for="address" class="hidden-section">Address</label>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-2   col-sm-2 col-xs-2 "><img src="<?php echo $mobile_png; ?>" class="mobile-icon" alt="mobile"></div>
                  <div class="col-md-10  col-sm-10 col-xs-10 row-grid contwidth">
                    <i class="fa fa-star star-icon-contat89 star-icon-eng"></i>
                    <input name="cellphonePostfix" id="cellphonePostfix" placeholder="Cellular No." required type="text" dir="ltr" value="<?php echo esc_attr(iei_post('cellphonePostfix')); ?>">
                    <label for="cellphonePostfix" class="hidden-section">Cellular No.</label>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-2  col-sm-2 col-xs-2 "><img src="<?php echo $phone_png; ?>" class="phone-icon" alt="phone"></div>
                  <div class="col-md-10  col-sm-10 col-xs-10 row-grid contwidth">
                    <input name="phonePostfix" id="phonePostfix" placeholder="Home phone" type="text" dir="ltr" value="<?php echo esc_attr(iei_post('phonePostfix')); ?>">
                    <label for="phonePostfix" class="hidden-section">Home phone</label>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-2  col-sm-2 col-xs-2 "><img src="<?php echo $printer_png; ?>" class="printer-icon" alt="printer"></div>
                  <div class="col-md-10  col-sm-10 col-xs-10 row-grid contwidth">
                    <input name="faxPostfix" id="faxPostfix" placeholder="Fax no." type="text" dir="ltr" value="<?php echo esc_attr(iei_post('faxPostfix')); ?>">
                    <label for="faxPostfix" class="hidden-section">Fax no.</label>
                  </div>
                </div>
              </div>

              <div class="clearfix"></div>
            </div>

            <div class="">
              <div class="col-md-12  col-sm-12  col-xs-12  row-grid">
                <input name="remarks" id="remarks" placeholder="More contact info" type="text" class="contmil-1" dir="ltr" value="<?php echo esc_attr(iei_post('remarks')); ?>">
                <label for="remarks" class="hidden-section">More contact info</label>
              </div>
            </div>

            <div class="">
              <div class="col-md-12  col-sm-12  col-xs-12  row-grid">
                <label for="desc" class="hidden-section">Description</label>
                <textarea placeholder="Description" id="desc" name="desc" class="contmil" dir="ltr"><?php echo esc_textarea(iei_post('desc')); ?></textarea>
              </div>
            </div>

            <div class="text-center">
              <button class="from-sumit-1" type="submit">Send</button>
            </div>

            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p style="text-align: center;"><a href="mailto:info@israeli-expert.com">info@israeli-expert.com</a> Moriya Av. 105, Haifa 34616 Phone: 04-8244633, Fax: 04-8113444</p>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>

<?php get_footer(); ?>
