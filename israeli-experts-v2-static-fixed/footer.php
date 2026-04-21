<?php
/**
 * footer.php
 * Fixed: Merged helper functions, visual footer, JS fixes, and Modals into one file.
 * Author: Pablo Rotem
 * PHP 8.3 + WordPress
 */

declare(strict_types=1);

if (!defined('ABSPATH')) { exit; }

// --- 1. Helper Functions (Relative Paths) ---
if (!function_exists('hx_rel')) {
    /**
     * מחזירה URL יחסי לנתיב בתוך האתר
     */
    function hx_rel(string $path = '', array $args = []): string {
        $url = home_url('/' . ltrim($path, '/'));
        if (!empty($args)) {
            $url = add_query_arg($args, $url);
        }
        return esc_url( wp_make_link_relative($url) );
    }
}

if (!function_exists('hx_asset')) {
    /**
     * מחזירה URL יחסי לנכס (תמונה/JS/CSS) בתוך התבנית
     */
    function hx_asset(string $rel): string {
        $rel = ltrim($rel, '/');
        
        // מיפוי נתיבים ישנים לנבנה החדש של assets
        $legacy_prefix_map = [
            'designFiles/'   => 'assets/images/',
            'javascripts/'   => 'assets/js/',
            'javascript/'    => 'assets/js/',
            'js/'            => 'assets/js/',
            'styles/'        => 'assets/css/',
            'css/'           => 'assets/css/',
        ];

        $asset_rel = $rel;
        foreach ($legacy_prefix_map as $from => $to) {
            if (str_starts_with($rel, $from)) {
                $asset_rel = $to . substr($rel, strlen($from));
                break;
            }
        }

        // אם לא נמצא ב-assets, מוסיפים את התחילית assets
        if (!str_starts_with($asset_rel, 'assets/')) {
            $asset_rel = 'assets/' . $asset_rel;
        }

        $full_url = trailingslashit( get_template_directory_uri() ) . $asset_rel;
        return esc_url( wp_make_link_relative($full_url) );
    }
}
?>

<footer id="footer" class="m-t-20">
    <div class="container">
        <div class="row">
            <div class="col-sm-3 col-md-3 col-xs-6 footer_div">
                <div class="footer_link">
                    <h3 class="footerlast">
                        <img src="<?php echo hx_asset('designFiles/icon-(three-man).png'); ?>" alt="המכון הישראלי" class="img-responsive footer-img-1">
                        &nbsp;המכון הישראלי<br>&nbsp;לחוות דעת מומחים ובוררים
                    </h3>
                    <ul class="m-t-15">  
                        <li><a href="<?php echo hx_rel('about'); ?>">אודות המכון</a></li>
                        <li><a href="<?php echo hx_rel('regulations'); ?>">תקנון המכון</a></li>
                        <li><a href="<?php echo hx_rel('contact-us-2'); ?>">צור קשר</a></li>
                    </ul>
                </div>
            </div>

            <div class="col-sm-3 col-md-2 col-xs-6 footer_div">
                <div class="footer_link">
                    <h3>המאגר</h3>
                    <ul> 
                        <li><a href="<?php echo hx_rel('repository'); ?>">מאגר המומחים</a></li>  
                        <li><a href="<?php echo hx_rel('how-to-choose-expert'); ?>">עצות לבחירת מומחה</a></li>  
                    </ul>
                </div>
            </div>
            
            <div class="col-sm-3 col-md-2 col-xs-6 footer_div">
                <div class="footer_link">
                    <h3>השתלמויות</h3>
                    <ul> 
                        <li><a href="<?php echo hx_rel('course-arbitrators-online'); ?>">קורס בוררים</a></li>  
                        <li><a href="<?php echo hx_rel('course-expert-witness'); ?>">קורס עדים מומחים</a></li>  
                        <li><a href="<?php echo hx_rel('meshulac-course'); ?>">קורס משולב</a></li>  
                    </ul>
                </div>
            </div>
            
            <div class="col-sm-3 col-md-2 col-xs-6 footer_div">
                <div class="footer_link">
                    <h3>טפסים נוספים</h3>
                    <ul> 
                        <li><a href="<?php echo hx_rel('work-forms'); ?>">טפסי עבודה</a></li>  
                        <li><a href="<?php echo hx_rel('course-registration-forms'); ?>">טפסי הרשמה</a></li>  
                        <li><a href="<?php echo hx_rel('assets/loadedFiles/declaration.doc'); ?>">תצהיר</a></li>  
                    </ul>
                </div>
            </div>

            <div class="col-sm-12 col-md-3 col-xs-12 footer_div">
                <div class="footer_link gtfooter">
                    <h3 class="ptfooter" style="cursor:pointer;">
                        <img src="<?php echo hx_asset('designFiles/icon-footer.png'); ?>" alt="icon-footer" class="img-responsive footer-img">
                        <p class="opter-irt parapfootr">חזור למעלה</p>
                    </h3>
                    <ul class="m-t-82">
                        <li>שד' מוריה 105, חיפה 34616</li>
                        <li>טל' <span class="ffoter-span">04-8244633</span>, פקס <span class="ffoter-span">04-8113444</span></li>
                        <li><a class="ffoter-span" href="mailto:info@israeli-expert.co.il">info@israeli-expert.co.il</a></li>                        
                    </ul>
                </div>
            </div>
        </div>

        <div class="copyright row">
            <div class="col-sm-12 col-xs-12" style="border-top: 1px solid #514b4b; padding-top: 25px; margin-top: 20px;">
                <div class="col-sm-6 col-xs-12 ftr_btm2">
                    <p>© <?php echo date('Y'); ?> כל הזכויות שמורות למכון הישראלי לחוות דעת מומחים ובוררים</p>
                </div>
                <div class="col-sm-6 col-xs-12 text-left ftr_btm1" dir="ltr">
                    <p>
                        <img src="<?php echo hx_asset('designFiles/InteruseAnimation.gif'); ?>" alt="אינטריוז" style="vertical-align: middle;"> בניית אתרים interuse &nbsp;&nbsp;&nbsp; yovdesign.com :עיצוב
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>

<div class="modal fade" id="modal-login" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content" dir="rtl">
            <div class="modal-header" style="text-align:right;">
                <button type="button" class="close" data-bs-dismiss="modal" style="float:left;">&times;</button>
                <h4 class="modal-title">כניסת משתמשים רשומים</h4>
            </div>
            <div class="modal-body" style="text-align:right;">
                <form id="modalLoginForm" method="post" action="<?php echo esc_url( wp_login_url() ); ?>">
                    <input type="hidden" name="redirect_to" value="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>">
                    <div class="form-group" style="margin-bottom:15px;">
                        <label>שם משתמש</label>
                        <input type="text" name="log" class="form-control" placeholder="הזן שם משתמש">
                    </div>
                    <div class="form-group" style="margin-bottom:15px;">
                        <label>סיסמה</label>
                        <input type="password" name="pwd" class="form-control" placeholder="הזן סיסמה">
                    </div>
                    <div class="text-center">
                        <button type="submit" name="wp-submit" class="btn btn-orange" style="background:#FFA801; color:#fff; width:100%; padding:10px; font-weight:bold; border:none;">כניסה</button>
                        <br><br>
                        <a href="javascript:void(0)" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#modal-reset-password">שכחתי את הסיסמה</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-reset-password" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content" dir="rtl">
            <div class="modal-header" style="text-align:right;">
                <button type="button" class="close" data-bs-dismiss="modal" style="float:left;">&times;</button>
                <h4 class="modal-title">איפוס סיסמה</h4>
            </div>
            <div class="modal-body" style="text-align:right;">
                <p>אנא הזן את שם המשתמש או כתובת האימייל שלך לקבלת קישור לאיפוס:</p>
                <form method="post" action="<?php echo esc_url( site_url( 'wp-login.php?action=lostpassword' ) ); ?>">
                    <div class="form-group" style="margin-bottom:15px;">
                        <input type="text" name="user_login" class="form-control" placeholder="שם משתמש או אימייל">
                    </div>
                    <button type="submit" class="btn btn-warning" style="background:#FFA801; color:#fff; width:100%; border:none; padding:10px;">שלח לי קישור</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
/**
 * פתרון שגיאות jQuery וטיפול ב-Bootstrap - Fixed by Pablo Rotem
 */
window.addEventListener('load', function() {
    if (window.jQuery) {
        (function($) {
            $(document).ready(function() {
                
                // 1. תיקון תפריט נייד ו-Bootstrap 5
                var nav = $('#primary-nav');
                var btn = $('.navbar-toggle, .navbar-toggler');

                if (btn.length) {
                    btn.addClass('navbar-toggler');
                    if (!btn.attr('data-bs-toggle')) btn.attr('data-bs-toggle','collapse');
                    if (!btn.attr('data-bs-target')) btn.attr('data-bs-target','#primary-nav');
                }

                // 2. המרת טריגרים של מודאלים מגרסה 3 ל-5
                $('[data-toggle="modal"]').each(function () {
                    $(this).attr('data-bs-toggle','modal');
                    var target = $(this).attr('data-target');
                    if (target && !$(this).attr('data-bs-target')) {
                        $(this).attr('data-bs-target', target);
                    }
                });
                
                $('[data-dismiss="modal"]').each(function () {
                    $(this).attr('data-bs-dismiss','modal');
                });

                // 3. גלילה חלקה למעלה
                $('.ptfooter').on('click', function() {
                    $('html, body').animate({ scrollTop: 0 }, 'slow');
                });

                console.log('Footer scripts initialized successfully.');
            });
        })(jQuery);
    } else {
        console.warn('jQuery not found in Footer, scripts might not run.');
    }
});
</script>

<?php wp_footer(); ?>
</body>
</html>