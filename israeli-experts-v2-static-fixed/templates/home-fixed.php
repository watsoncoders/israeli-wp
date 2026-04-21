<?php
/**
 * Template Name: Home Page Fixed
 * Description: Traffic Controller - Loads home-mobile.php for phones, otherwise shows Desktop.
 */

// 1. IF MOBILE: Load the mobile file and STOP.
if ( wp_is_mobile() ) {
    get_template_part( 'templates/home', 'mobile' );
    return; // Exit here. Desktop code below won't run.
}

// 2. ELSE: Run Desktop Code
get_header(); 
?>

<style>
    .carousel-inner {
        position: relative;
        overflow: hidden;
        width: 100%;
        height: 649px; /* Fixed height for desktop */
    }
    .slider-img img {
        width: 100%;
        height: auto;
        min-height: 649px;
        object-fit: cover;
    }
    .slider-text { font-size: 36px; line-height: 1.4; text-shadow: 1px 1px 2px rgba(0,0,0,0.5); }
</style>

<div id="content" class="site-content">

    <div id="myCarousel" class="homepage-carousel carousel slide" data-ride="carousel" data-interval="false">
        <div class="carousel-inner" role="listbox">
            <div class="item active">
                <div class="slider-img">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/designFiles/1-slider-image.png" alt="slider1">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="slider-captions">
                                    <h1 class="slider-text">מאגר מומחים משפטי, עזר משפטי יחודי <br> למטרות יעוץ ומתן חוות דעת לבתי משפט ובכלל.</h1>
                                    <form method="post" action="<?php echo home_url('/repository'); ?>" id="searchform">
                                        <div class="search-btn form-group">
                                            <input name="nameText" id="fr" class="input" type="text" placeholder="חפש מומחה">
                                            <label for="fr" class="hidden_content">Search</label>
                                            <input name="submitsearch" id="second" class="button" value="Search" type="submit">
                                        </div>
                                        <div class="boxrlt form-group">
                                            <p><span class="pforSearch">השימוש באתר באחריות המשתמש כמפורט בתנאים <a href="<?php echo home_url('/tos'); ?>" target="_new">הבאים</a></span></p>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="slider-img">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/designFiles/2-slider-image.jpg" alt="slider2">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="slider-captions">
                                    <h1 class="slider-text hpCourse">מאגר השתלמויות משפטי ייחודי למטרות<br>ייעוץ ומתן חוות דעת לבתי משפט ובכלל.</h1>
                                    <a href="#home" class="detils-relate trigger-tab" data-tab="#home">קראו עוד על ההשתלמויות שלנו</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/designFiles/prev.png" class="icon2 icon_color silder-arrow-left img-responsive" alt="prev">
        </a>
        <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/designFiles/next.png" class="icon1 icon_color silder-arrow-right img-responsive" alt="next">
        </a>
    </div>

    <div class="container-fluid BorNaimCon">
        <div class="container postionstr paddXsZ">
            <div class="hm-sent-top col-md-12 col-sm-12 col-xs-12 nav nav-pills paddXsZ">
                <div class="active hm-sent-top-1">
                    <ul class="nav nav-tabs HomeTAbM paddingZ">
                        <li class="active">
                            <a data-toggle="tab" href="#home-1">
                                <div class="jolhsection">
                                    <p class="text-center">
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/designFiles/svg/man.svg" class="sectionclass-1 img-responsive" alt="users">
                                        <span class="TabTextNext">המאגר</span>
                                    </p>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#home">
                                <div class="jolhsection">
                                    <p class="text-center">
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/designFiles/svg/file-icon.svg" class="sectionclass img-responsive" alt="files">
                                        <span class="TabText">השתלמויות</span>
                                    </p>
                                </div>
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div id="home-1" class="tab-pane fade in active hm-sentios">
                            <div class="col-md-12 paddingZ area homepageText">
                                <p>מאגר מומחים משפטי, עזר משפטי יחודי למטרות יעוץ ומתן חוות דעת מומחה לבתי משפט ובכלל.</p>
                                <p>על המאגר נמנית צמרת הפרופסורה המקצועית בישראל...</p>
                            </div>
                            <div class="col-md-5 paddingZ">
                                <ul>
                                    <li class="link-li">עדים מומחים</li>
                                    <a href="<?php echo home_url('/repository'); ?>"><li class="paraplus"><img src="<?php echo get_template_directory_uri(); ?>/assets/designFiles/plus-icon.png" class="img-responsive paraplusimg"> רפואי</li></a>
                                    <a href="<?php echo home_url('/repository'); ?>"><li class="paraplus"><img src="<?php echo get_template_directory_uri(); ?>/assets/designFiles/plus-icon.png" class="img-responsive paraplusimg"> הנדסי</li></a>
                                    </ul>
                            </div>
                        </div>

                        <div id="home" class="tab-pane fade">
                             <div class="col-md-12 paddingZ araea25">
                                 <h3>השתלמויות</h3>
                                 </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container bg-color-2 img-set home-img-set">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/designFiles/img-1.png" alt="images part1" class="imagepart">
        <div class="img-part-details">
            <h2>מאגר המומחים <br>הגדול במדינה</h2>
            <a href="<?php echo home_url('/course-registration'); ?>">
                <button class="detils-relate">הרשם למאגר עכשיו</button>
            </a>
        </div>
    </div>

    <div class="container m-t-20 rtls-bortsa sectionpart">
        <div class="col-md-12 p-t-15 aresect">
            <p>למען הסר ספק! <br>הציבור מוזמן לעיין בהודעת משרד הבריאות... <a href="<?php echo home_url('/%d7%94%d7%95%d7%93%d7%a2%d7%aa-%d7%9e%d7%a9%d7%a8%d7%93-%d7%94%d7%91%d7%a8%d7%99%d7%90%d7%95%d7%aa/'); ?>" class="iop"> לחץ כאן ! </a></p>
        </div>
    </div>

</div>

<script>
jQuery(document).ready(function($) {
    $('.trigger-tab').on('click', function(e) {
        e.preventDefault();
        var tabId = $(this).attr('data-tab');
        $('.nav-tabs a[href="' + tabId + '"]').tab('show');
        $('html, body').animate({ scrollTop: $(tabId).offset().top - 150 }, 800);
    });
});
</script>

<?php get_footer(); ?>