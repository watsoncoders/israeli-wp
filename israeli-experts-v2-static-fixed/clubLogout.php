<?php
/**
 * Legacy-style logout endpoint for club session
 * author: pablo rotem
 */

declare(strict_types=1);

require_once dirname(__FILE__, 4) . '/wp-load.php';

defined('ABSPATH') || exit;

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

unset($_SESSION['club_user_id'], $_SESSION['club_user_name'], $_SESSION['club_logged_in']);

wp_safe_redirect(home_url('/course-registration/'));
exit;
