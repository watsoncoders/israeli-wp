<?php
/**
 * מערכת התחברות מודאלית + איפוס סיסמה למערכת הוותיקה
 * תאימות לטבלת clubMembers
 * author: pablo rotem
 * PHP 8.3
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

final class Pablo_Club_Auth
{
    private static bool $session_started = false;

    public static function init(): void
    {
        self::start_session();

        add_action('admin_post_nopriv_pablo_club_login', [self::class, 'handle_login']);
        add_action('admin_post_pablo_club_login', [self::class, 'handle_login']);

        add_action('admin_post_nopriv_pablo_club_reset', [self::class, 'handle_reset_password']);
        add_action('admin_post_pablo_club_reset', [self::class, 'handle_reset_password']);

        add_action('admin_post_pablo_club_logout', [self::class, 'handle_logout']);
        add_action('admin_post_nopriv_pablo_club_logout', [self::class, 'handle_logout']);

        add_action('wp_footer', [self::class, 'render_modals'], 99);
        add_action('template_redirect', [self::class, 'protect_course_registration'], 1);
    }

    private static function start_session(): void
    {
        if (self::$session_started) {
            return;
        }

        if (session_status() !== PHP_SESSION_ACTIVE && !headers_sent()) {
            session_start();
        }

        self::$session_started = (session_status() === PHP_SESSION_ACTIVE);
    }

    public static function is_logged_in(): bool
    {
        self::start_session();
        return !empty($_SESSION['club_logged_in']) && !empty($_SESSION['club_user_id']);
    }

    public static function current_user_id(): int
    {
        self::start_session();
        return isset($_SESSION['club_user_id']) ? (int) $_SESSION['club_user_id'] : 0;
    }

    public static function current_user_name(): string
    {
        self::start_session();
        return isset($_SESSION['club_user_name']) ? (string) $_SESSION['club_user_name'] : '';
    }

    public static function current_return_to(): string
    {
        $uri = isset($_SERVER['REQUEST_URI']) ? (string) $_SERVER['REQUEST_URI'] : '';
        if ($uri !== '') {
            $url = home_url($uri);
            if (is_string($url) && $url !== '') {
                return $url;
            }
        }

        return home_url('/course-registration/');
    }

    private static function resolve_table_name(string $base): string
    {
        global $wpdb;

        $with_prefix = $wpdb->prefix . $base;

        $found = $wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $with_prefix));
        if (!empty($found)) {
            return $with_prefix;
        }

        $found_plain = $wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $base));
        if (!empty($found_plain)) {
            return $base;
        }

        return $with_prefix;
    }

    public static function handle_login(): void
    {
        self::start_session();

        $nonce = isset($_POST['pablo_club_login_nonce']) ? sanitize_text_field(wp_unslash($_POST['pablo_club_login_nonce'])) : '';
        if (!$nonce || !wp_verify_nonce($nonce, 'pablo_club_login')) {
            wp_die('שגיאת אבטחה.');
        }

        $username_or_email = isset($_POST['username']) ? trim((string) wp_unslash($_POST['username'])) : '';
        $password          = isset($_POST['password']) ? (string) wp_unslash($_POST['password']) : '';
        $return_to         = isset($_POST['returnTo']) && !empty($_POST['returnTo'])
            ? esc_url_raw((string) wp_unslash($_POST['returnTo']))
            : home_url('/course-registration/');

        if ($username_or_email === '' || $password === '') {
            wp_safe_redirect(add_query_arg('login_error', 'empty', $return_to));
            exit;
        }

        global $wpdb;
        $table_name = self::resolve_table_name('clubMembers');

        $user = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM {$table_name} WHERE username = %s OR email = %s LIMIT 1",
                $username_or_email,
                $username_or_email
            )
        );

        if ($user) {
            $db_pass = isset($user->password) ? (string) $user->password : '';
            $password_ok = false;

            if ($db_pass !== '') {
                if (md5($password) === $db_pass) {
                    $password_ok = true;
                } elseif ($password === $db_pass) {
                    $password_ok = true;
                }
            }

            if ($password_ok) {
                $_SESSION['club_user_id']   = (int) $user->id;
                $_SESSION['club_user_name'] = isset($user->username) ? (string) $user->username : '';
                $_SESSION['club_logged_in'] = true;

                wp_safe_redirect(home_url('/course-registration/'));
                exit;
            }
        }

        wp_safe_redirect(add_query_arg('login_error', 'invalid', $return_to));
        exit;
    }

    public static function handle_reset_password(): void
    {
        self::start_session();

        $nonce = isset($_POST['pablo_club_reset_nonce']) ? sanitize_text_field(wp_unslash($_POST['pablo_club_reset_nonce'])) : '';
        if (!$nonce || !wp_verify_nonce($nonce, 'pablo_club_reset')) {
            wp_die('שגיאת אבטחה.');
        }

        $username_or_email = isset($_POST['username']) ? sanitize_text_field(wp_unslash($_POST['username'])) : '';
        $new_pass          = isset($_POST['new_password']) ? (string) wp_unslash($_POST['new_password']) : '';
        $redirect          = wp_get_referer() ?: home_url('/');

        if ($username_or_email === '' || $new_pass === '') {
            wp_safe_redirect(add_query_arg('reset_msg', 'empty', $redirect));
            exit;
        }

        global $wpdb;
        $table_name = self::resolve_table_name('clubMembers');

        $user = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT id, username, email FROM {$table_name} WHERE username = %s OR email = %s LIMIT 1",
                $username_or_email,
                $username_or_email
            )
        );

        if (!$user) {
            wp_safe_redirect(add_query_arg('reset_msg', 'notfound', $redirect));
            exit;
        }

        $updated = $wpdb->update(
            $table_name,
            ['password' => md5($new_pass)],
            ['id' => (int) $user->id],
            ['%s'],
            ['%d']
        );

        if ($updated === false) {
            wp_safe_redirect(add_query_arg('reset_msg', 'error', $redirect));
            exit;
        }

        wp_safe_redirect(add_query_arg('reset_msg', 'success', $redirect));
        exit;
    }

    public static function handle_logout(): void
    {
        self::start_session();

        unset($_SESSION['club_user_id'], $_SESSION['club_user_name'], $_SESSION['club_logged_in']);

        $redirect = wp_get_referer() ?: home_url('/');
        wp_safe_redirect($redirect);
        exit;
    }

    public static function protect_course_registration(): void
    {
        if (!is_page('course-registration')) {
            return;
        }

        if (self::is_logged_in()) {
            return;
        }

        $target = add_query_arg(
            [
                'open_login' => '1',
                'returnTo'   => rawurlencode(home_url('/course-registration/')),
            ],
            home_url('/')
        );

        wp_safe_redirect($target);
        exit;
    }

    private static function get_login_message_html(): string
    {
        $error = isset($_GET['login_error']) ? sanitize_key(wp_unslash($_GET['login_error'])) : '';

        return match ($error) {
            'empty'   => '<div class="alert alert-danger" style="margin-bottom:15px;">יש למלא שם משתמש או אימייל, וגם סיסמה.</div>',
            'invalid' => '<div class="alert alert-danger" style="margin-bottom:15px;">שם המשתמש או הסיסמה שגויים.</div>',
            default   => '',
        };
    }

    private static function get_reset_message_html(): string
    {
        $msg = isset($_GET['reset_msg']) ? sanitize_key(wp_unslash($_GET['reset_msg'])) : '';

        return match ($msg) {
            'success'  => '<div class="alert alert-success" style="margin-bottom:15px;">הסיסמה עודכנה בהצלחה. אפשר לחזור ולהתחבר.</div>',
            'notfound' => '<div class="alert alert-danger" style="margin-bottom:15px;">לא נמצא משתמש עם שם המשתמש או האימייל שהוזנו.</div>',
            'empty'    => '<div class="alert alert-danger" style="margin-bottom:15px;">יש למלא שם משתמש או אימייל, וגם סיסמה חדשה.</div>',
            'error'    => '<div class="alert alert-danger" style="margin-bottom:15px;">אירעה שגיאה בעדכון הסיסמה.</div>',
            default    => '',
        };
    }

    private static function esc_current_return_to(): string
    {
        $return = self::current_return_to();

        if (isset($_GET['returnTo']) && is_string($_GET['returnTo']) && $_GET['returnTo'] !== '') {
            $decoded = rawurldecode(wp_unslash($_GET['returnTo']));
            if (is_string($decoded) && $decoded !== '') {
                $return = esc_url_raw($decoded);
            }
        }

        return esc_attr($return);
    }

    public static function render_modals(): void
    {
        if (is_admin()) {
            return;
        }

        static $rendered = false;
        if ($rendered) {
            return;
        }
        $rendered = true;

        $open_login = isset($_GET['open_login']) || isset($_GET['login_error']);
        $open_reset = isset($_GET['reset_msg']);
        $return_to  = self::esc_current_return_to();
        ?>
        <div class="modal fade" id="modal-login" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content" dir="rtl">
                    <div class="modal-header" style="text-align:right;">
                        <button type="button"
                                class="close pablo-modal-close"
                                data-dismiss="modal"
                                data-bs-dismiss="modal"
                                aria-label="Close"
                                style="float:left; background:transparent; border:0; font-size:28px; line-height:1;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">כניסת משתמשים רשומים</h4>
                    </div>
                    <div class="modal-body" style="text-align:right;">
                        <?php echo self::get_login_message_html(); ?>

                        <form id="loginForm" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                            <input type="hidden" name="action" value="pablo_club_login">
                            <input type="hidden" id="returnTo" name="returnTo" value="<?php echo $return_to; ?>">
                            <?php wp_nonce_field('pablo_club_login', 'pablo_club_login_nonce'); ?>

                            <div class="form-group" style="margin-bottom:15px;">
                                <label for="pablo_login_username">שם משתמש</label>
                                <input type="text"
                                       id="pablo_login_username"
                                       name="username"
                                       class="form-control"
                                       placeholder="שם משתמש או אימייל"
                                       autocomplete="username">
                            </div>

                            <div class="form-group" style="margin-bottom:15px;">
                                <label for="pablo_login_password">סיסמה</label>
                                <input type="password"
                                       id="pablo_login_password"
                                       name="password"
                                       class="form-control"
                                       placeholder="סיסמה"
                                       autocomplete="current-password">
                                <br>
                                <a href="#" class="pablo-open-reset">שכחתי את הסיסמה</a>
                            </div>

                            <div class="text-center">
                                <button class="btn btn-orange" type="submit" style="min-width:150px;">כניסה</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-reset-password" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content" dir="rtl">
                    <div class="modal-header" style="text-align:right;">
                        <button type="button"
                                class="close pablo-modal-close"
                                data-dismiss="modal"
                                data-bs-dismiss="modal"
                                aria-label="Close"
                                style="float:left; background:transparent; border:0; font-size:28px; line-height:1;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">שכחתי סיסמה</h4>
                    </div>
                    <div class="modal-body" style="text-align:right;">
                        <?php echo self::get_reset_message_html(); ?>

                        <form id="resetPasswordForm" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                            <input type="hidden" name="action" value="pablo_club_reset">
                            <?php wp_nonce_field('pablo_club_reset', 'pablo_club_reset_nonce'); ?>

                            <div class="form-group" style="margin-bottom:15px;">
                                <label for="pablo_reset_username">שם משתמש או אימייל</label>
                                <input type="text"
                                       id="pablo_reset_username"
                                       name="username"
                                       class="form-control"
                                       placeholder="שם משתמש או אימייל"
                                       autocomplete="username">
                            </div>

                            <div class="form-group" style="margin-bottom:15px;">
                                <label for="pablo_reset_new_password">סיסמה חדשה</label>
                                <input type="password"
                                       id="pablo_reset_new_password"
                                       name="new_password"
                                       class="form-control"
                                       placeholder="סיסמה חדשה"
                                       autocomplete="new-password">
                            </div>

                            <div class="text-center">
                                <button class="btn btn-orange" type="submit" style="min-width:150px;">עדכן סיסמה</button>
                            </div>
                        </form>

                        <div style="margin-top:12px; text-align:center;">
                            <a href="#" class="pablo-open-login">חזרה להתחברות</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
        (function(){
            function onReady(fn) {
                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', fn);
                } else {
                    fn();
                }
            }

            onReady(function(){
                var $ = window.jQuery || null;

                function getElement(selector) {
                    return document.querySelector(selector);
                }

                function bootstrap5Show(selector) {
                    if (!window.bootstrap || !window.bootstrap.Modal) {
                        return false;
                    }
                    var el = getElement(selector);
                    if (!el) {
                        return false;
                    }
                    var instance = window.bootstrap.Modal.getOrCreateInstance(el);
                    instance.show();
                    return true;
                }

                function bootstrap5Hide(selector) {
                    if (!window.bootstrap || !window.bootstrap.Modal) {
                        return false;
                    }
                    var el = getElement(selector);
                    if (!el) {
                        return false;
                    }
                    var instance = window.bootstrap.Modal.getOrCreateInstance(el);
                    instance.hide();
                    return true;
                }

                function jqueryShow(selector) {
                    if (!$ || !$.fn || !$.fn.modal) {
                        return false;
                    }
                    $(selector).modal('show');
                    return true;
                }

                function jqueryHide(selector) {
                    if (!$ || !$.fn || !$.fn.modal) {
                        return false;
                    }
                    $(selector).modal('hide');
                    return true;
                }

                function fallbackShow(selector) {
                    var el = getElement(selector);
                    if (!el) {
                        return;
                    }
                    el.style.display = 'block';
                    el.classList.add('in', 'show');
                    el.setAttribute('aria-hidden', 'false');
                    document.body.classList.add('modal-open');
                }

                function fallbackHide(selector) {
                    var el = getElement(selector);
                    if (!el) {
                        return;
                    }
                    el.style.display = 'none';
                    el.classList.remove('in', 'show');
                    el.setAttribute('aria-hidden', 'true');
                    document.body.classList.remove('modal-open');
                }

                function showModal(selector) {
                    if (bootstrap5Show(selector)) {
                        return;
                    }
                    if (jqueryShow(selector)) {
                        return;
                    }
                    fallbackShow(selector);
                }

                function hideModal(selector) {
                    if (bootstrap5Hide(selector)) {
                        return;
                    }
                    if (jqueryHide(selector)) {
                        return;
                    }
                    fallbackHide(selector);
                }

                document.addEventListener('click', function(e){
                    if (e.target.closest('.pablo-modal-close')) {
                        e.preventDefault();
                        hideModal('#modal-login');
                        hideModal('#modal-reset-password');
                        return;
                    }

                    if (e.target.closest('.pablo-open-reset')) {
                        e.preventDefault();
                        hideModal('#modal-login');
                        setTimeout(function(){
                            showModal('#modal-reset-password');
                        }, 150);
                        return;
                    }

                    if (e.target.closest('.pablo-open-login')) {
                        e.preventDefault();
                        hideModal('#modal-reset-password');
                        setTimeout(function(){
                            showModal('#modal-login');
                        }, 150);
                    }
                });

                <?php if ($open_login) : ?>
                showModal('#modal-login');
                <?php endif; ?>

                <?php if ($open_reset) : ?>
                showModal('#modal-reset-password');
                <?php endif; ?>
            });
        })();
        </script>
        <?php
    }
}
