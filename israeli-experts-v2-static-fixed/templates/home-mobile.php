<?php
/**
 * Template Part: Home Page MOBILE (Content Only)
 * Description: דף בית מובייל – משתמש בתפריט הראשי מה־header.php בלבד.
 * Author: pablo rotem
 */

get_header();
?>

<style>
    /* --- 1. SLIDER CSS --- */
    .mobile-carousel .carousel-inner {
        position: relative;
        width: 100%;
        height: 450px !important;
        overflow: hidden;
    }
    .mobile-carousel .item {
        height: 450px !important;
        display: block !important;
    }
    .slider-img {
        height: 100%;
        width: 100%;
        position: relative;
    }
    .slider-img img {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important;
        display: block;
        opacity: 0.9;
    }
    .slider-captions {
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        transform: translateY(-50%);
        padding: 20px;
        text-align: center;
        z-index: 10;
        width: 100%;
    }
    .slider-text {
        color: #fff;
        font-size: 22px !important;
        font-weight: bold;
        line-height: 1.3;
        margin-bottom: 20px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.7);
    }
    .search-btn .input {
        width: 100%;
        height: 45px;
        margin-bottom: 10px;
        padding: 0 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        color: #333;
    }
    .search-btn .button,
    .btn-slider-cta {
        width: 100%;
        height: 45px;
        background: #FFA801;
        color: #fff !important;
        border: none;
        font-weight: bold;
        font-size: 18px;
        border-radius: 4px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.3);
        display: inline-block;
        text-decoration: none;
        padding-top: 10px;
    }
    .boxrlt p {
        color: #fff;
        font-size: 12px;
        margin-top: 10px;
        text-shadow: 0 1px 2px rgba(0,0,0,0.8);
    }
    .boxrlt a {
        color: #FFA801;
        text-decoration: underline;
    }

    /* --- 2. קישורים נוספים במובייל --- */
    .mobile-links-section {
        padding: 20px 15px;
    }
    .mobile-link-item {
        display: block;
        background: #f4f4f4;
        padding: 15px;
        margin-bottom: 8px;
        color: #333;
        font-weight: bold;
        text-decoration: none;
        border-right: 5px solid #000046;
        font-size: 16px;
    }
    .mobile-link-item img {
        height: 18px;
        margin-left: 10px;
        vertical-align: middle;
    }
    .mobile-h3 {
        color: #000046;
        font-size: 22px;
        font-weight: bold;
        margin: 20px 0 15px 0;
        text-align: center;
    }
</style>

<div class="mobile-home-container">

    <!-- אין כאן NAV בכלל – התפריט מגיע רק מ־header.php -->

    <div id="mobileCarousel" class="carousel slide mobile-carousel" data-ride="carousel" data-interval="false">
        <div class="carousel-inner" role="listbox">

            <div class="item active">
                <div class="slider-img">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/designFiles/1-slider-image.png" alt="slider1">
                    <div class="slider-captions">
                        <h1 class="slider-text">
                            מאגר מומחים משפטי, עזר משפטי יחודי <br>
                            למטרות יעוץ ומתן חוות דעת לבתי משפט ובכלל.
                        </h1>
                        <form method="post" action="<?php echo home_url('/repository'); ?>" id="searchform">
                            <div class="search-btn form-group">
                                <input name="nameText" id="fr" class="input" type="text" placeholder="חפש מומחה">
                                <input name="submitsearch" id="second" class="button" value="Search" type="submit">
                            </div>
                            <div class="boxrlt form-group">
                                <p>
                                    <span class="pforSearch">
                                        השימוש באתר באחריות המשתמש כמפורט בתנאים
                                        <a href="<?php echo home_url('/tos'); ?>">הבאים</a>
                                    </span>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="item">
                <div class="slider-img">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/designFiles/2-slider-image.jpg" alt="slider2">
                    <div class="slider-captions">
                        <h1 class="slider-text hpCourse">
                            מאגר השתלמויות משפטי ייחודי למטרות<br>
                            ייעוץ ומתן חוות דעת לבתי משפט ובכלל.
                        </h1>
                        <a href="<?php echo home_url('/courses-list'); ?>" class="btn-slider-cta">
                            קראו עוד על ההשתלמויות שלנו
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <a class="left carousel-control" href="#mobileCarousel" role="button" data-slide="prev" style="background:none;">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/designFiles/prev.png"
                 class="icon2 img-responsive" alt="prev" style="width:30px; margin-top: 200px;">
        </a>
        <a class="right carousel-control" href="#mobileCarousel" role="button" data-slide="next" style="background:none;">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/designFiles/next.png"
                 class="icon1 img-responsive" alt="next" style="width:30px; margin-top: 200px;">
        </a>
    </div>

    <div class="mobile-links-section">
        <h3 class="mobile-h3">המאגר</h3>
        <a href="<?php echo home_url('/repository'); ?>" class="mobile-link-item">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/designFiles/plus-icon.png"> רפואי
        </a>
        <a href="<?php echo home_url('/repository'); ?>" class="mobile-link-item">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/designFiles/plus-icon.png"> הנדסי
        </a>
        <a href="<?php echo home_url('/repository'); ?>" class="mobile-link-item">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/designFiles/plus-icon.png"> משפטי / עורכי דין
        </a>
        <a href="<?php echo home_url('/repository'); ?>" class="mobile-link-item">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/designFiles/plus-icon.png"> בוררים
        </a>

        <h3 class="mobile-h3">השתלמויות</h3>
        <a href="<?php echo home_url('/course-expert-witness'); ?>" class="mobile-link-item" style="border-right-color:#FFA801;">
            קורס עדים מומחים
        </a>
        <a href="<?php echo home_url('/course-arbitrators-online'); ?>" class="mobile-link-item" style="border-right-color:#FFA801;">
            קורס בוררים
        </a>
        <a href="<?php echo home_url('/meshulac-course'); ?>" class="mobile-link-item" style="border-right-color:#FFA801;">
            קורס משולב
        </a>
    </div>

    <div class="container" style="background-color:#eee; padding:30px 20px; text-align:center; margin-top:10px;">
        <h2 style="color:#000046; font-size:24px; font-weight:bold;">מאגר המומחים הגדול במדינה</h2>
        <a href="<?php echo home_url('/course-registration'); ?>"
           class="btn btn-warning btn-lg"
           style="width:100%; background-color:#FFA801; border:none; margin-top:15px; font-size:20px; padding:10px;">
            הרשם למאגר עכשיו
        </a>
    </div>

</div>

<?php get_footer(); ?>
