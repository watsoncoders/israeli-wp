<?php
/**
 * Custom Login Handler for Israeli Experts
 * Handles POST request from the modal login form.
 */

// 1. Load WordPress Environment (so we can use $wpdb and wp_redirect)
// We assume this file is in /wp-content/themes/your-theme/
require_once( dirname( __FILE__ ) . '/../../../wp-load.php' );

global $wpdb;

// --- CONFIGURATION ---
// Change this to your actual custom users table name
$table_name = 'clubMembers'; 
// ---------------------

// 2. Start Session if not already started
if ( ! session_id() ) {
    session_start();
}

// 3. Get and Sanitize Data
$username = isset($_POST['username']) ? sanitize_text_field($_POST['username']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$returnTo = isset($_POST['returnTo']) && !empty($_POST['returnTo']) ? esc_url($_POST['returnTo']) : home_url();

if ( empty($username) || empty($password) ) {
    wp_redirect( add_query_arg('login_error', 'empty', $returnTo) );
    exit;
}

// 4. Check User in Database
// We select the user row based on username
$user = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE username = %s", $username ) );

if ( $user ) {
    // 5. Verify Password
    // NOTE: Old PHP sites usually used MD5. If your site used plain text, remove the md5().
    // If you want to be more secure in the future, convert to password_verify().
    $password_check = false;

    if ( md5($password) === $user->password ) {
        $password_check = true;
    } elseif ( $password === $user->password ) {
        // Fallback for plain text passwords (common in very old sites)
        $password_check = true;
    }

   if ( $password_check ) {
        // --- LOGIN SUCCESS ---
        $_SESSION['club_user_id'] = $user->id;
        $_SESSION['club_user_name'] = $user->username;
        $_SESSION['club_logged_in'] = true;

        // Force redirect to the registration form
        wp_redirect( home_url('/course-registration/') ); 
        exit;
    }
}

// --- LOGIN FAILED ---
wp_redirect( add_query_arg('login_error', 'invalid', $returnTo) );
exit;