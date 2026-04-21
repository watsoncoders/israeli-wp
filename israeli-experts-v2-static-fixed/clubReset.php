<?php
/**
 * Custom Password Reset Handler
 * Updates the password in the custom table.
 */

require_once( dirname( __FILE__ ) . '/../../../wp-load.php' );

global $wpdb;

// --- CONFIGURATION ---
$table_name = 'clubMembers'; 
// ---------------------

$username = isset($_POST['username']) ? sanitize_text_field($_POST['username']) : '';
$new_pass = isset($_POST['new_password']) ? $_POST['new_password'] : '';

// Redirect back to home by default
$redirect_url = home_url();

if ( !empty($username) && !empty($new_pass) ) {
    
    // 1. Check if user exists
    $user_exists = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM $table_name WHERE username = %s", $username ) );

    if ( $user_exists ) {
        // 2. Hash the new password (Using MD5 to match the Login logic)
        $hashed_password = md5($new_pass);

        // 3. Update the database
        $wpdb->update(
            $table_name,
            array( 'password' => $hashed_password ), // Data to update
            array( 'username' => $username ),        // Where clause
            array( '%s' ),                           // Format of data
            array( '%s' )                            // Format of where
        );

        // Success redirect
        wp_redirect( add_query_arg('reset_msg', 'success', $redirect_url) );
        exit;
    } else {
        // User not found
        wp_redirect( add_query_arg('reset_msg', 'notfound', $redirect_url) );
        exit;
    }
}

// Empty fields
wp_redirect( add_query_arg('reset_msg', 'empty', $redirect_url) );
exit;