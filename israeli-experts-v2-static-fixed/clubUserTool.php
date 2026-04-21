<?php
/**
 * Tool to View and Add Club Members
 * usage: Upload to theme folder and visit via browser.
 */
require_once( dirname( __FILE__ ) . '/../../../wp-load.php' );
global $wpdb;

$table_name = 'clubMembers'; // Make sure this matches your table name

// --- 1. HANDLE "ADD USER" CLICK ---
$message = '';
if ( isset($_POST['create_demo']) ) {
    $username = 'demo_user';
    $password = '123456';
    $hashed_pass = md5($password); // We use MD5 because your login script uses MD5
    
    // Check if exists
    $exists = $wpdb->get_var( $wpdb->prepare("SELECT id FROM $table_name WHERE username = %s", $username) );
    
    if ($exists) {
        // Update password if exists
        $wpdb->update($table_name, array('password' => $hashed_pass), array('username' => $username));
        $message = '<div style="color:green; border:1px solid green; padding:10px;">User updated! Login with: <b>demo_user</b> / <b>123456</b></div>';
    } else {
        // Create new
        $result = $wpdb->insert($table_name, array(
            'username' => $username,
            'password' => $hashed_pass,
            'email'    => 'demo@example.com' // Remove this line if you don't have an email column
        ));
        
        if ($result === false) {
             $message = '<div style="color:red; border:1px solid red; padding:10px;">Error: Could not insert user. Check if your table columns match! MySQL Error: ' . $wpdb->last_error . '</div>';
        } else {
             $message = '<div style="color:green; border:1px solid green; padding:10px;">Success! Login with: <b>demo_user</b> / <b>123456</b></div>';
        }
    }
}

// --- 2. GET EXISTING USERS ---
// Check if table exists
if ( $wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name ) {
    die("<h1>Error: Table '$table_name' does not exist in the database!</h1>");
}

$users = $wpdb->get_results( "SELECT * FROM $table_name LIMIT 10" );
?>

<!DOCTYPE html>
<html dir="rtl">
<head><title>Club User Tool</title></head>
<body style="font-family: sans-serif; padding: 50px;">

    <h1>Club Members Debugger</h1>
    
    <?php echo $message; ?>

    <h3>Existing Users (Last 10):</h3>
    <table border="1" cellpadding="10" style="border-collapse:collapse; width:100%;">
        <tr style="background:#eee;">
            <th>ID</th>
            <th>Username</th>
            <th>Password (Hashed)</th>
        </tr>
        <?php if($users): ?>
            <?php foreach($users as $user): ?>
            <tr>
                <td><?php echo isset($user->id) ? $user->id : 'N/A'; ?></td>
                <td><?php echo isset($user->username) ? $user->username : 'N/A'; ?></td>
                <td><?php echo isset($user->password) ? substr($user->password, 0, 15).'...' : 'N/A'; ?></td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="3">No users found in table.</td></tr>
        <?php endif; ?>
    </table>

    <br><br>
    
    <form method="post">
        <button type="submit" name="create_demo" style="background:blue; color:white; padding:15px 30px; font-size:20px; cursor:pointer;">
            Create/Reset "demo_user" (Password: 123456)
        </button>
    </form>

</body>
</html>