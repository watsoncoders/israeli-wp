<?php
/**
 * Template Name: Expert Registration / Update Profile
 */
get_header(); 

global $wpdb;
$current_user = wp_get_current_user();
$is_logged_in = is_user_logged_in();
$expert_data = null;
$expert_details = null;

// Only try to fetch data if user is logged in
if ($is_logged_in) {
    $email = $current_user->user_email;
    
    // Fetch personal data
    $expert_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM clubMembers WHERE email = %s", $email));
    
    // Fetch professional data (only if personal data exists)
    if ($expert_data) {
        $expert_details = $wpdb->get_row($wpdb->prepare("SELECT * FROM israeli_experts WHERE memberId = %d", $expert_data->id));
    }
}

// Helper function: safely get property or return empty string
function get_val($obj, $prop) {
    return (isset($obj) && isset($obj->$prop)) ? esc_attr($obj->$prop) : '';
}

// Check if we actually found the user in the custom DB
$has_expert_data = ($is_logged_in && $expert_data);
?>

<div class="container" style="padding: 50px 15px; direction: rtl; text-align: right;">
    
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            
            <h1 class="page-title" style="color: #000046; font-weight: bold; margin-bottom: 30px; text-align: center;">
                <?php echo $has_expert_data ? 'עדכון פרופיל מומחה' : 'טופס הרשמה למאגר המומחים'; ?>
            </h1>

            <?php if ($has_expert_data): ?>
                <div class="alert alert-info text-center">
                    שלום <strong><?php echo esc_html($expert_data->firstname); ?></strong>, כאן תוכל לעדכן את פרטיך במאגר.
                </div>
            <?php endif; ?>

            <form id="expert-form" method="post" action="" enctype="multipart/form-data">
                
                <input type="hidden" name="form_action" value="<?php echo $has_expert_data ? 'update_profile' : 'new_registration'; ?>">
                
                <?php if($has_expert_data): ?>
                    <input type="hidden" name="member_id" value="<?php echo esc_attr($expert_data->id); ?>">
                <?php endif; ?>

                <div class="panel panel-default">
                    <div class="panel-heading" style="background:#FFA801; color:#fff;">פרטים אישיים</div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>שם פרטי *</label>
                                <input type="text" name="firstname" class="form-control" required value="<?php echo get_val($expert_data, 'firstname'); ?>">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>שם משפחה *</label>
                                <input type="text" name="lastname" class="form-control" required value="<?php echo get_val($expert_data, 'lastname'); ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>דואר אלקטרוני (שם משתמש) *</label>
                                <input type="email" name="email" class="form-control" required value="<?php echo $is_logged_in ? esc_attr($current_user->user_email) : get_val($expert_data, 'email'); ?>" <?php echo $is_logged_in ? 'readonly' : ''; ?>>
                                <?php if($is_logged_in): ?><small class="text-muted">לא ניתן לשנות אימייל</small><?php endif; ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>תעודת זהות</label>
                                <input type="text" name="identityNumber" class="form-control" value="<?php echo get_val($expert_data, 'identityNumber'); ?>">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>טלפון נייד</label>
                                <input type="text" name="cellphone" class="form-control" value="<?php echo get_val($expert_data, 'cellphone'); ?>">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>עיר מגורים</label>
                                <input type="text" name="city" class="form-control" value="<?php echo get_val($expert_data, 'city'); ?>">
                            </div>
                        </div>

                         <div class="row">
                            <div class="col-md-12 form-group">
                                <label>כתובת מלאה</label>
                                <input type="text" name="address" class="form-control" value="<?php echo get_val($expert_data, 'address'); ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading" style="background:#000046; color:#fff;">פרטים מקצועיים</div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>מקצוע (לדוגמה: מהנדס בניין)</label>
                                <input type="text" name="fldProfession" class="form-control" value="<?php echo get_val($expert_details, 'fldProfession'); ?>">
                            </div>
                             <div class="col-md-6 form-group">
                                <label>מספר רישיון</label>
                                <input type="text" name="licenseNo" class="form-control" value="<?php echo get_val($expert_details, 'licenseNo'); ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>תחומי התמחות (מופרדים בפסיקים)</label>
                            <textarea name="fldSpecialization" class="form-control" rows="3"><?php echo get_val($expert_details, 'fldSpecialization'); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label>ניסיון מקצועי ופרטים נוספים</label>
                            <textarea name="moreDetails" class="form-control" rows="5"><?php echo get_val($expert_details, 'moreDetails'); ?></textarea>
                        </div>

                         <div class="form-group">
                            <label>מקום עבודה</label>
                            <input type="text" name="workplace" class="form-control" value="<?php echo get_val($expert_details, 'workplace'); ?>">
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">אבטחה</div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label><?php echo $has_expert_data ? 'סיסמה חדשה (השאר ריק אם אין שינוי)' : 'סיסמה *'; ?></label>
                                <input type="password" name="password" class="form-control" <?php echo $has_expert_data ? '' : 'required'; ?>>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" name="submit_expert" class="btn btn-lg" style="background: #FFA801; color: #fff; padding: 10px 50px; font-weight: bold;">
                        <?php echo $has_expert_data ? 'שמור שינויים' : 'שלח טופס הרשמה'; ?>
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<?php
// --- טיפול בשליחת הטופס (Backend Logic) ---
if (isset($_POST['submit_expert'])) {
    
    // איסוף נתונים
    $firstname = sanitize_text_field($_POST['firstname']);
    $lastname  = sanitize_text_field($_POST['lastname']);
    $email     = sanitize_email($_POST['email']);
    $pass      = $_POST['password']; 
    $city      = sanitize_text_field($_POST['city']);
    $address   = sanitize_text_field($_POST['address']);
    $cellphone = sanitize_text_field($_POST['cellphone']);
    $identity  = sanitize_text_field($_POST['identityNumber']);
    
    $profession = sanitize_text_field($_POST['fldProfession']);
    $specialization = sanitize_textarea_field($_POST['fldSpecialization']);
    $license    = sanitize_text_field($_POST['licenseNo']);
    $details    = wp_kses_post($_POST['moreDetails']);
    $workplace  = sanitize_text_field($_POST['workplace']);

    if ($_POST['form_action'] == 'update_profile' && $is_logged_in) {
        // --- עדכון ---
        $mid = intval($_POST['member_id']);
        
        if ($mid > 0) {
            // 1. עדכון clubMembers
            $update_data = [
                'firstname' => $firstname, 'lastname' => $lastname, 
                'city' => $city, 'address' => $address, 
                'cellphone' => $cellphone, 'identityNumber' => $identity
            ];
            if (!empty($pass)) { $update_data['password'] = $pass; }

            $wpdb->update('clubMembers', $update_data, ['id' => $mid]);

            // 2. עדכון israeli_experts
            $exists = $wpdb->get_var($wpdb->prepare("SELECT memberId FROM israeli_experts WHERE memberId = %d", $mid));
            
            $expert_fields = [
                'fldProfession' => $profession, 'fldSpecialization' => $specialization,
                'licenseNo' => $license, 'moreDetails' => $details, 'workplace' => $workplace
            ];

            if ($exists) {
                $wpdb->update('israeli_experts', $expert_fields, ['memberId' => $mid]);
            } else {
                $expert_fields['memberId'] = $mid;
                $wpdb->insert('israeli_experts', $expert_fields);
            }

            echo "<script>alert('הפרופיל עודכן בהצלחה!'); window.location.href = window.location.href;</script>";
        }
    } else {
        // --- הרשמה חדשה ---
        // (Add INSERT logic here if needed)
    }
}

get_footer(); 
?>