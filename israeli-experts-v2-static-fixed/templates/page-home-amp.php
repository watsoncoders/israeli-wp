<?php
/**
 * Template Name: Home Page Fixed AMP
 * Description: עמוד בית אחיד (דסקטופ + מובייל) עם סליידר רספונסיבי + תמיכת AMP.
 * Author: pablo rotem
 */

$is_amp    = function_exists('amp_is_request') && amp_is_request();
$theme_uri = get_template_directory_uri();

if (!function_exists('pablo_home_image')) {
    /**
     * הדפסת תמונה רגילה / AMP
     * Author: pablo rotem
     */
    function pablo_home_image(array $args = []): void
    {
        $defaults = [
            'src'           => '',
            'alt'           => '',
            'class'         => '',
            'width'         => 100,
            'height'        => 100,
            'layout'        => 'responsive',
            'object_fit'    => '',
            'fetchpriority' => '',
            'loading'       => '',
            'decoding'      => '',
            'is_amp'        => false,
            'aria_hidden'   => '',
        ];

        $args = array_merge($defaults, $args);

        $src           = esc_url($args['src']);
        $alt           = esc_attr($args['alt']);
        $class         = esc_attr($args['class']);
        $width         = (int) $args['width'];
        $height        = (int) $args['height'];
        $layout        = esc_attr($args['layout']);
        $object_fit    = trim((string) $args['object_fit']);
        $fetchpriority = trim((string) $args['fetchpriority']);
        $loading       = trim((string) $args['loading']);
        $decoding      = trim((string) $args['decoding']);
        $aria_hidden   = trim((string) $args['aria_hidden']);
        $is_amp        = !empty($args['is_amp']);

        $style_attr = '';
        if ($object_fit !== '') {
            $style_attr = ' style="object-fit:' . esc_attr($object_fit) . ';"';
        }

        $aria_hidden_attr = $aria_hidden !== '' ? ' aria-hidden="' . esc_attr($aria_hidden) . '"' : '';

        if ($is_amp) {
            echo '<amp-img'
                . ' src="' . $src . '"'
                . ' alt="' . $alt . '"'
                . ' class="' . $class . '"'
                . ' width="' . $width . '"'
                . ' height="' . $height . '"'
                . ' layout="' . $layout . '"'
                . $style_attr
                . $aria_hidden_attr
                . '></amp-img>';
            return;
        }

        $fetch_attr   = $fetchpriority !== '' ? ' fetchpriority="' . esc_attr($fetchpriority) . '"' : '';
        $loading_attr = $loading !== '' ? ' loading="' . esc_attr($loading) . '"' : '';
        $decode_attr  = $decoding !== '' ? ' decoding="' . esc_attr($decoding) . '"' : '';

        echo '<img'
            . ' src="' . $src . '"'
            . ' alt="' . $alt . '"'
            . ' class="' . $class . '"'
            . ' width="' . $width . '"'
            . ' height="' . $height . '"'
            . $fetch_attr
            . $loading_attr
            . $decode_attr
            . $style_attr
            . $aria_hidden_attr
            . '>';
    }
}

get_header();
?>

<style>
/* =========================================================
   Home Page Fixed - AMP / Hero / Tabs / Mobile
   Author: pablo rotem
   ========================================================= */

.carousel-inner,
.homepage-carousel,
.homepage-carousel .item,
.homepage-carousel .slider-img {
    position: relative;
}

.carousel-inner {
    overflow: hidden;
    width: 100%;
    height: 649px;
}

.slider-img {
    width: 100%;
    height: 649px;
}

.slider-img > img,
.slider-img > amp-img {
    display: block;
    width: 100%;
    height: 649px;
    object-fit: cover;
}

.slider-img amp-img img {
    object-fit: cover;
}

.slider-overlay {
    position: absolute;
    inset: 0;
    z-index: 2;
    background: rgba(8, 16, 78, 0.45);
}

.slider-captions {
    position: absolute;
    inset: 0;
    z-index: 3;
    display: flex;
    align-items: center;
    justify-content: center;
    pointer-events: none;
}

.slider-captions-inner {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    padding-right: 15px;
    padding-left: 15px;
    box-sizing: border-box;
    display: flex;
    justify-content: center;
}

.slider-captions-box {
    width: 100%;
    max-width: 760px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 18px;
    text-align: center;
    pointer-events: auto;
}

.slider-text {
    display: block;
    margin: 0;
    font-size: 36px;
    line-height: 1.4;
    color: #ffb300;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
    text-align: center;
    width: 100%;
}

#searchform {
    display: block;
    width: 100%;
    max-width: 760px;
    margin: 0 auto;
}

#searchform .search-btn.form-group {
    display: flex;
    align-items: stretch;
    width: 100%;
    margin: 0;
}

#searchform .search-btn .input {
    flex: 1 1 auto;
    min-width: 0;
}

#searchform .boxrlt {
    margin-top: 10px;
    text-align: center;
}

#searchform .boxrlt p {
    margin: 0;
}

#searchform .pforSearch {
    display: inline-block;
    color: #ffffff;
    font-size: 14px;
    line-height: 1.6;
}

#searchform .pforSearch a {
    color: #ffffff;
    text-decoration: underline;
}

.homepage-carousel .carousel-control {
    z-index: 10;
}

.homepage-carousel .carousel-control img,
.homepage-carousel .carousel-control amp-img {
    display: block;
}

.homepage-carousel .amp-carousel-button {
    background-color: rgba(0,0,0,.35);
    border-radius: 50%;
    width: 46px;
    height: 46px;
    background-size: 18px 18px;
}

.homepage-carousel .amp-carousel-button-prev {
    left: 18px;
}

.homepage-carousel .amp-carousel-button-next {
    right: 18px;
}

/* =========================================================
   Radio tabs
   Author: pablo rotem
   ========================================================= */

.pablo-home-tabs {
    width: 100%;
    clear: both;
}

.pablo-tab-radio {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

.pablo-tabs-nav {
    display: flex;
    flex-wrap: nowrap;
    align-items: flex-end;
    justify-content: center;
    width: 100%;
    margin: 0;
    padding: 0;
}

.pablo-tabs-nav > li {
    float: none;
    display: block;
    list-style: none;
}

.pablo-tab-label {
    display: block;
    cursor: pointer;
}

.pablo-tabs-panels {
    clear: both;
    width: 100%;
    display: block;
}

.pablo-tab-panel {
    display: none;
    width: 100%;
    clear: both;
}

#pablo-tab-home-1:checked ~ .pablo-tabs-nav li:nth-child(1),
#pablo-tab-home:checked ~ .pablo-tabs-nav li:nth-child(2) {
    position: relative;
    z-index: 2;
}

#pablo-tab-home-1:checked ~ .pablo-tabs-panels .pablo-panel-home-1 {
    display: block;
}

#pablo-tab-home:checked ~ .pablo-tabs-panels .pablo-panel-home {
    display: block;
}

/* תמונת הבאנר התחתונה */
.home-img-set {
    position: relative;
}

.home-img-set .imagepart,
.home-img-set amp-img.imagepart {
    display: block;
    width: 100%;
    height: auto;
}

.home-img-set .img-part-details {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    z-index: 2;
}

/* קישורים */
.paraplus-link {
    display: block;
    text-decoration: none;
    color: inherit;
}

.paraplus-link:hover,
.paraplus-link:focus {
    text-decoration: none;
    color: inherit;
}

.manageicon,
.paraplusimg {
    display: inline-block;
    vertical-align: middle;
}

.newrltpara-1 img,
.newrltpara-1 amp-img {
    display: inline-block;
    vertical-align: middle;
    margin-right: 8px;
}

@media (max-width: 1199px) {
    .carousel-inner,
    .slider-img,
    .slider-img > img,
    .slider-img > amp-img {
        height: 520px;
        min-height: 520px;
    }

    .slider-text {
        font-size: 26px;
        line-height: 1.35;
    }

    .slider-captions-inner {
        padding-right: 20px;
        padding-left: 20px;
    }

    .homepage-carousel .carousel-control {
        width: 48px;
    }

    .homepage-carousel .carousel-control img,
    .homepage-carousel .carousel-control amp-img {
        max-width: 32px;
        height: auto;
    }
}

@media (max-width: 767px) {
    .carousel-inner,
    .slider-img,
    .slider-img > img,
    .slider-img > amp-img {
        height: 460px;
        min-height: 460px;
    }

    .slider-captions {
        align-items: center;
    }

    .slider-captions-inner {
        padding-right: 16px;
        padding-left: 16px;
    }

    .slider-captions-box {
        max-width: 100%;
        gap: 14px;
    }

    .slider-text {
        font-size: 22px;
        line-height: 1.3;
        text-align: center;
    }

    #searchform {
        max-width: 100%;
    }

    #searchform .search-btn.form-group {
        width: 100%;
    }

    #searchform .boxrlt {
        margin-top: 8px;
    }

    #searchform .pforSearch {
        font-size: 13px;
    }

    .pablo-tabs-nav {
        justify-content: center;
    }
}
</style>

<div id="content" class="site-content">

    <?php if ($is_amp) : ?>
        <div id="myCarousel" class="homepage-carousel">
            <amp-carousel
                width="1600"
                height="649"
                layout="responsive"
                type="slides"
                controls
                class="carousel-inner"
            >
                <div class="item active">
                    <div class="slider-img">
                        <?php
                        pablo_home_image([
                            'src'           => $theme_uri . '/assets/designFiles/1-slider-image.png',
                            'alt'           => 'slider1',
                            'class'         => '',
                            'width'         => 1600,
                            'height'        => 649,
                            'layout'        => 'responsive',
                            'object_fit'    => 'cover',
                            'fetchpriority' => 'high',
                            'loading'       => 'eager',
                            'decoding'      => 'async',
                            'is_amp'        => true,
                        ]);
                        ?>
                        <div class="slider-overlay"></div>

                        <div class="container">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="slider-captions">
                                        <div class="slider-captions-inner">
                                            <div class="slider-captions-box">
                                                <h1 class="slider-text">מאגר מומחים משפטי עזר משפטי יחודי <br> למטרות יעוץ ומתן חוות דעת לבתי משפט ובכלל.</h1>

                                                <form method="get" action="<?php echo esc_url(home_url('/repository/')); ?>" id="searchform" target="_top">
                                                    <div class="search-btn form-group">
                                                        <input
                                                            name="home_search_name"
                                                            id="fr"
                                                            class="input"
                                                            type="text"
                                                            placeholder="חפש מומחה"
                                                            value="<?php echo isset($_GET['home_search_name']) ? esc_attr((string) $_GET['home_search_name']) : ''; ?>"
                                                        >
                                                        <label for="fr" class="hidden_content">Search</label>
                                                        <button class="button" type="submit">Search</button>
                                                    </div>

                                                    <input type="hidden" name="lang" value="HEB">

                                                    <div class="boxrlt form-group">
                                                        <p>
                                                            <span class="pforSearch">
                                                                השימוש באתר באחריות המשתמש כמפורט בתנאים
                                                                <a href="<?php echo esc_url(home_url('/tos')); ?>" target="_top">הבאים</a>
                                                            </span>
                                                        </p>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="item">
                    <div class="slider-img">
                        <?php
                        pablo_home_image([
                            'src'        => $theme_uri . '/assets/designFiles/2-slider-image.jpg',
                            'alt'        => 'slider2',
                            'class'      => '',
                            'width'      => 1600,
                            'height'     => 649,
                            'layout'     => 'responsive',
                            'object_fit' => 'cover',
                            'is_amp'     => true,
                        ]);
                        ?>
                        <div class="slider-overlay"></div>

                        <div class="container">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="slider-captions">
                                        <div class="slider-captions-inner">
                                            <div class="slider-captions-box">
                                                <h1 class="slider-text hpCourse">מאגר השתלמויות משפטי ייחודי למטרות<br>ייעוץ ומתן חוות דעת לבתי משפט ובכלל.</h1>
                                                <a href="#home-sections-anchor" class="detils-relate" target="_top">קראו עוד על ההשתלמויות שלנו</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </amp-carousel>
        </div>
    <?php else : ?>
        <div id="myCarousel" class="homepage-carousel carousel slide" data-ride="carousel" data-interval="false">
            <div class="carousel-inner" role="listbox">
                <div class="item active">
                    <div class="slider-img">
                        <?php
                        pablo_home_image([
                            'src'           => $theme_uri . '/assets/designFiles/1-slider-image.png',
                            'alt'           => 'slider1',
                            'class'         => '',
                            'width'         => 1600,
                            'height'        => 649,
                            'object_fit'    => 'cover',
                            'fetchpriority' => 'high',
                            'loading'       => 'eager',
                            'decoding'      => 'async',
                            'is_amp'        => false,
                        ]);
                        ?>
                        <div class="slider-overlay"></div>

                        <div class="container">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="slider-captions">
                                        <div class="slider-captions-inner">
                                            <div class="slider-captions-box">
                                                <h1 class="slider-text">מאגר מומחים משפטי עזר משפטי יחודי <br> למטרות יעוץ ומתן חוות דעת לבתי משפט ובכלל.</h1>

                                                <form method="get" action="<?php echo esc_url(home_url('/repository/')); ?>" id="searchform">
                                                    <div class="search-btn form-group">
                                                        <input
                                                            name="home_search_name"
                                                            id="fr"
                                                            class="input"
                                                            type="text"
                                                            placeholder="חפש מומחה"
                                                            value="<?php echo isset($_GET['home_search_name']) ? esc_attr((string) $_GET['home_search_name']) : ''; ?>"
                                                        >
                                                        <label for="fr" class="hidden_content">Search</label>
                                                        <button class="button" type="submit">Search</button>
                                                    </div>

                                                    <input type="hidden" name="lang" value="HEB">

                                                    <div class="boxrlt form-group">
                                                        <p>
                                                            <span class="pforSearch">
                                                                השימוש באתר באחריות המשתמש כמפורט בתנאים
                                                                <a href="<?php echo esc_url(home_url('/tos')); ?>" target="_blank" rel="noopener">הבאים</a>
                                                            </span>
                                                        </p>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="item">
                    <div class="slider-img">
                        <?php
                        pablo_home_image([
                            'src'        => $theme_uri . '/assets/designFiles/2-slider-image.jpg',
                            'alt'        => 'slider2',
                            'class'      => '',
                            'width'      => 1600,
                            'height'     => 649,
                            'object_fit' => 'cover',
                            'is_amp'     => false,
                        ]);
                        ?>
                        <div class="slider-overlay"></div>

                        <div class="container">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="slider-captions">
                                        <div class="slider-captions-inner">
                                            <div class="slider-captions-box">
                                                <h1 class="slider-text hpCourse">מאגר השתלמויות משפטי ייחודי למטרות<br>ייעוץ ומתן חוות דעת לבתי משפט ובכלל.</h1>
                                                <a href="#" class="detils-relate trigger-tab" data-tab="#home">קראו עוד על ההשתלמויות שלנו</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
                    <?php
                    pablo_home_image([
                        'src'         => $theme_uri . '/assets/designFiles/prev.png',
                        'alt'         => 'slider pre',
                        'class'       => 'icon2 icon_color silder-arrow-left img-responsive',
                        'width'       => 32,
                        'height'      => 32,
                        'loading'     => 'lazy',
                        'decoding'    => 'async',
                        'aria_hidden' => 'true',
                        'is_amp'      => false,
                    ]);
                    ?>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
                    <?php
                    pablo_home_image([
                        'src'         => $theme_uri . '/assets/designFiles/next.png',
                        'alt'         => 'slider next',
                        'class'       => 'icon1 icon_color silder-arrow-right img-responsive',
                        'width'       => 32,
                        'height'      => 32,
                        'loading'     => 'lazy',
                        'decoding'    => 'async',
                        'aria_hidden' => 'true',
                        'is_amp'      => false,
                    ]);
                    ?>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>
    <?php endif; ?>

    <div id="home-sections-anchor"></div>

    <div class="container-fluid BorNaimCon">
        <div class="container postionstr paddXsZ">
            <div class="hm-sent-top col-md-12 col-sm-12 col-xs-12 nav nav-pills paddXsZ">
                <div class="active hm-sent-top-1">

                    <div class="pablo-home-tabs">

                        <input type="radio" name="pablo_home_tabs" id="pablo-tab-home-1" class="pablo-tab-radio" checked>
                        <input type="radio" name="pablo_home_tabs" id="pablo-tab-home" class="pablo-tab-radio">

                        <ul class="nav nav-tabs HomeTAbM paddingZ pablo-tabs-nav">
                            <li class="pablo-tab-nav-item">
                                <label for="pablo-tab-home-1" class="pablo-tab-label">
                                    <div class="jolhsection">
                                        <p class="text-center">
                                            <?php
                                            pablo_home_image([
                                                'src'      => $theme_uri . '/assets/designFiles/svg/man.svg',
                                                'alt'      => 'users icon',
                                                'class'    => 'sectionclass-1 img-responsive',
                                                'width'    => 64,
                                                'height'   => 64,
                                                'layout'   => $is_amp ? 'fixed' : 'intrinsic',
                                                'loading'  => 'lazy',
                                                'decoding' => 'async',
                                                'is_amp'   => $is_amp,
                                            ]);
                                            ?>
                                            <span class="TabTextNext">המאגר</span>
                                        </p>
                                    </div>
                                </label>
                            </li>

                            <li class="pablo-tab-nav-item">
                                <label for="pablo-tab-home" class="pablo-tab-label">
                                    <div class="jolhsection">
                                        <p class="text-center">
                                            <?php
                                            pablo_home_image([
                                                'src'      => $theme_uri . '/assets/designFiles/svg/file-icon.svg',
                                                'alt'      => 'file icon',
                                                'class'    => 'sectionclass img-responsive',
                                                'width'    => 64,
                                                'height'   => 64,
                                                'layout'   => $is_amp ? 'fixed' : 'intrinsic',
                                                'loading'  => 'lazy',
                                                'decoding' => 'async',
                                                'is_amp'   => $is_amp,
                                            ]);
                                            ?>
                                            <span class="TabText">השתלמויות</span>
                                        </p>
                                    </div>
                                </label>
                            </li>
                        </ul>

                        <div class="pablo-tabs-panels">

                            <div id="home-1" class="pablo-tab-panel pablo-panel-home-1">
                                <div class="col-md-12 col-sm-12 col-xs-12 paddingZ area homepageText">
                                    <p>מאגר מומחים משפטי, עזר משפטי יחודי למטרות יעוץ ומתן חוות דעת מומחה לבתי משפט ובכלל.</p>
                                    <p>על המאגר נמנית צמרת הפרופסורה המקצועית בישראל מאות פרופסורים ומנהלי מחלקות, מאות בעלי מקצועות המובילים בתחום עיסוקם והתמקצעותם מסווגים ע"פ מפתח סיווגים חדשני.</p>
                                    <p>מאגר המומחים הגדול במדינה, אלפי עדים מומחים תת קורת גג אחת.</p><br>
                                </div>

                                <div class="col-md-5 col-sm-4 col-xs-12 paddingZ">
                                    <ul>
                                        <li class="link-li">עדים מומחים</li>
                                        <li class="paraplus"><a class="paraplus-link" href="<?php echo esc_url(home_url('/repository')); ?>"><?php pablo_home_image(['src' => $theme_uri . '/assets/designFiles/plus-icon.png', 'alt' => 'plus11', 'class' => 'img-responsive paraplusimg', 'width' => 14, 'height' => 14, 'layout' => $is_amp ? 'fixed' : 'intrinsic', 'loading' => 'lazy', 'decoding' => 'async', 'is_amp' => $is_amp]); ?> רפואי</a></li>
                                        <li class="paraplus"><a class="paraplus-link" href="<?php echo esc_url(home_url('/repository')); ?>"><?php pablo_home_image(['src' => $theme_uri . '/assets/designFiles/plus-icon.png', 'alt' => 'plus12', 'class' => 'img-responsive paraplusimg', 'width' => 14, 'height' => 14, 'layout' => $is_amp ? 'fixed' : 'intrinsic', 'loading' => 'lazy', 'decoding' => 'async', 'is_amp' => $is_amp]); ?> הנדסי</a></li>
                                        <li class="paraplus"><a class="paraplus-link" href="<?php echo esc_url(home_url('/repository')); ?>"><?php pablo_home_image(['src' => $theme_uri . '/assets/designFiles/plus-icon.png', 'alt' => 'plus13', 'class' => 'img-responsive paraplusimg', 'width' => 14, 'height' => 14, 'layout' => $is_amp ? 'fixed' : 'intrinsic', 'loading' => 'lazy', 'decoding' => 'async', 'is_amp' => $is_amp]); ?> משפטי / עורכי דין</a></li>
                                        <li class="paraplus"><a class="paraplus-link" href="<?php echo esc_url(home_url('/repository')); ?>"><?php pablo_home_image(['src' => $theme_uri . '/assets/designFiles/plus-icon.png', 'alt' => 'plus14', 'class' => 'img-responsive paraplusimg', 'width' => 14, 'height' => 14, 'layout' => $is_amp ? 'fixed' : 'intrinsic', 'loading' => 'lazy', 'decoding' => 'async', 'is_amp' => $is_amp]); ?> אחר</a></li>
                                    </ul>
                                    <a href="http://www.expertsearch.co.uk/" target="_blank" rel="noopener"><p class="newrltpara">למאגר המומחים האנגלי</p></a>
                                    <a href="<?php echo esc_url(home_url('/repository')); ?>"><p class="newrltpara-1">למאגר המלא<?php pablo_home_image(['src' => $theme_uri . '/assets/designFiles/arrow-icon.png', 'alt' => 'arrow icon', 'class' => 'img-responsive', 'width' => 18, 'height' => 18, 'layout' => $is_amp ? 'fixed' : 'intrinsic', 'loading' => 'lazy', 'decoding' => 'async', 'aria_hidden' => 'true', 'is_amp' => $is_amp]); ?></p></a>
                                </div>

                                <div class="col-md-4 col-sm-4 col-xs-12 paddingZ">
                                    <ul>
                                        <li class="link-li">בוררים</li>
                                        <li class="paraplus"><a class="paraplus-link" href="<?php echo esc_url(home_url('/index2.php?id=5125&catId=811&lang=HEB')); ?>"><?php pablo_home_image(['src' => $theme_uri . '/assets/designFiles/plus-icon.png', 'alt' => 'plus15', 'class' => 'img-responsive paraplusimg', 'width' => 14, 'height' => 14, 'layout' => $is_amp ? 'fixed' : 'intrinsic', 'loading' => 'lazy', 'decoding' => 'async', 'is_amp' => $is_amp]); ?> שופטים בדימוס / עורכי דין</a></li>
                                        <li class="paraplus"><a class="paraplus-link" href="<?php echo esc_url(home_url('/index2.php?id=5125&catId=812&lang=HEB')); ?>"><?php pablo_home_image(['src' => $theme_uri . '/assets/designFiles/plus-icon.png', 'alt' => 'plus16', 'class' => 'img-responsive paraplusimg', 'width' => 14, 'height' => 14, 'layout' => $is_amp ? 'fixed' : 'intrinsic', 'loading' => 'lazy', 'decoding' => 'async', 'is_amp' => $is_amp]); ?> משפטנים / טוענים משפטיים</a></li>
                                        <li class="paraplus"><a class="paraplus-link" href="<?php echo esc_url(home_url('/index2.php?id=5125&catId=813&lang=HEB')); ?>"><?php pablo_home_image(['src' => $theme_uri . '/assets/designFiles/plus-icon.png', 'alt' => 'plus17', 'class' => 'img-responsive paraplusimg', 'width' => 14, 'height' => 14, 'layout' => $is_amp ? 'fixed' : 'intrinsic', 'loading' => 'lazy', 'decoding' => 'async', 'is_amp' => $is_amp]); ?> נבחרי ציבור</a></li>
                                        <li class="paraplus"><a class="paraplus-link" href="<?php echo esc_url(home_url('/repository')); ?>"><?php pablo_home_image(['src' => $theme_uri . '/assets/designFiles/plus-icon.png', 'alt' => 'plus18', 'class' => 'img-responsive paraplusimg', 'width' => 14, 'height' => 14, 'layout' => $is_amp ? 'fixed' : 'intrinsic', 'loading' => 'lazy', 'decoding' => 'async', 'is_amp' => $is_amp]); ?> סקטוריאלי: רו"ח / מקצועות הנדסיים</a></li>
                                        <li class="paraplus"><a class="paraplus-link" href="<?php echo esc_url(home_url('/index2.php?id=5125&catId=815&lang=HEB')); ?>"><?php pablo_home_image(['src' => $theme_uri . '/assets/designFiles/plus-icon.png', 'alt' => 'plus19', 'class' => 'img-responsive paraplusimg', 'width' => 14, 'height' => 14, 'layout' => $is_amp ? 'fixed' : 'intrinsic', 'loading' => 'lazy', 'decoding' => 'async', 'is_amp' => $is_amp]); ?> א-סקטוריאלי</a></li>
                                    </ul>
                                </div>

                                <div class="col-md-3 col-sm-4 col-xs-12 paddingZ">
                                    <ul>
                                        <li class="link-li">בוררים</li>
                                        <li class="paraplus"><a class="paraplus-link" href="<?php echo esc_url(home_url('/index2.php?id=5125&catId=819&lang=HEB')); ?>"><?php pablo_home_image(['src' => $theme_uri . '/assets/designFiles/plus-icon.png', 'alt' => 'plus20', 'class' => 'img-responsive paraplusimg', 'width' => 14, 'height' => 14, 'layout' => $is_amp ? 'fixed' : 'intrinsic', 'loading' => 'lazy', 'decoding' => 'async', 'is_amp' => $is_amp]); ?> מגשרים</a></li>
                                        <li class="paraplus"><a class="paraplus-link" href="<?php echo esc_url(home_url('/index2.php?id=5125&catId=820&lang=HEB')); ?>"><?php pablo_home_image(['src' => $theme_uri . '/assets/designFiles/plus-icon.png', 'alt' => 'plus21', 'class' => 'img-responsive paraplusimg', 'width' => 14, 'height' => 14, 'layout' => $is_amp ? 'fixed' : 'intrinsic', 'loading' => 'lazy', 'decoding' => 'async', 'is_amp' => $is_amp]); ?> מגשרים – בוררים</a></li>
                                    </ul>
                                </div>
                            </div>

                            <div id="home" class="pablo-tab-panel pablo-panel-home">
                                <div class="col-md-12 col-sm-12 col-xs-12 paddingZ araea25">

                                    <div class="col-md-6 col-sm-12 col-xs-12 paddingZ">
                                        <div class="col-md-12 col-sm-12 col-xs-12 paddingZ">
                                            <div class="col-md-6 col-sm-6 col-xs-12 mangentsec">
                                                <p class="mangementpara">השתלמות</p>
                                                <a href="<?php echo esc_url(home_url('/index2.php?id=101&lang=HEB')); ?>"><h3 class="sectionheading msetmargin">עדים מומחים</h3></a>
                                                <ul class="list-inline managementline">
                                                    <li><?php pablo_home_image(['src' => $theme_uri . '/assets/designFiles/plus-icon-yellow.png', 'alt' => 'plus-img-11', 'class' => 'manageicon', 'width' => 14, 'height' => 14, 'layout' => $is_amp ? 'fixed' : 'intrinsic', 'loading' => 'lazy', 'decoding' => 'async', 'is_amp' => $is_amp]); ?>תכנים</li>
                                                    <li><?php pablo_home_image(['src' => $theme_uri . '/assets/designFiles/plus-icon-yellow.png', 'alt' => 'plus-img-11', 'class' => 'manageicon', 'width' => 14, 'height' => 14, 'layout' => $is_amp ? 'fixed' : 'intrinsic', 'loading' => 'lazy', 'decoding' => 'async', 'is_amp' => $is_amp]); ?>הרשמה</li>
                                                </ul>
                                            </div>

                                            <div class="col-md-6 col-sm-6 col-xs-12 padleftsec padsetiion">
                                                <p class="mangementpara">השתלמות</p>
                                                <a href="<?php echo esc_url(home_url('/index2.php?id=102&lang=HEB')); ?>"><h3 class="sectionheading msetmargin">בוררים</h3></a>
                                                <ul class="list-inline managementline">
                                                    <li><?php pablo_home_image(['src' => $theme_uri . '/assets/designFiles/plus-icon-yellow.png', 'alt' => 'plus-img-11', 'class' => 'manageicon', 'width' => 14, 'height' => 14, 'layout' => $is_amp ? 'fixed' : 'intrinsic', 'loading' => 'lazy', 'decoding' => 'async', 'is_amp' => $is_amp]); ?>תכנים</li>
                                                    <li><?php pablo_home_image(['src' => $theme_uri . '/assets/designFiles/plus-icon-yellow.png', 'alt' => 'plus-img-11', 'class' => 'manageicon', 'width' => 14, 'height' => 14, 'layout' => $is_amp ? 'fixed' : 'intrinsic', 'loading' => 'lazy', 'decoding' => 'async', 'is_amp' => $is_amp]); ?>הרשמה</li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="col-md-12 col-sm-12 col-xs-12 mangentsec11">
                                            <p class="mangementpara">השתלמות</p>
                                            <a href="<?php echo esc_url(home_url('/index2.php?id=103&lang=HEB')); ?>"><h3 class="sectionheading">משולב</h3></a>
                                            <div class="col-md-6 col-sm-6 col-xs-12 paddingZ">
                                                <p class="headmange1">משלב את השתלמות העדים המומחים<br>יחד עם השתלמות הבוררים</p>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-xs-12 paddingZ">
                                                <ul class="list-inline managementline othersret">
                                                    <li><?php pablo_home_image(['src' => $theme_uri . '/assets/designFiles/plus-icon-yellow.png', 'alt' => 'plus-img-11', 'class' => 'manageicon', 'width' => 14, 'height' => 14, 'layout' => $is_amp ? 'fixed' : 'intrinsic', 'loading' => 'lazy', 'decoding' => 'async', 'is_amp' => $is_amp]); ?>תכנים</li>
                                                    <li><?php pablo_home_image(['src' => $theme_uri . '/assets/designFiles/plus-icon-yellow.png', 'alt' => 'plus-img-11', 'class' => 'manageicon', 'width' => 14, 'height' => 14, 'layout' => $is_amp ? 'fixed' : 'intrinsic', 'loading' => 'lazy', 'decoding' => 'async', 'is_amp' => $is_amp]); ?>הרשמה</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-12 col-xs-12 paddXsZ secsm25 padsm0">
                                        <div class="col-md-6 col-sm-6 col-xs-12 mangentsec">
                                            <p class="mangementpara">השתלמות</p>
                                            <a href="<?php echo esc_url(home_url('/index2.php?id=104&lang=HEB')); ?>"><h3 class="sectionheading msetmargin">תמ"א 38 בסיסי</h3></a>
                                            <ul class="list-inline managementline">
                                                <li><?php pablo_home_image(['src' => $theme_uri . '/assets/designFiles/plus-icon-yellow.png', 'alt' => 'plus-img-11', 'class' => 'manageicon', 'width' => 14, 'height' => 14, 'layout' => $is_amp ? 'fixed' : 'intrinsic', 'loading' => 'lazy', 'decoding' => 'async', 'is_amp' => $is_amp]); ?>תכנים</li>
                                                <li><?php pablo_home_image(['src' => $theme_uri . '/assets/designFiles/plus-icon-yellow.png', 'alt' => 'plus-img-11', 'class' => 'manageicon', 'width' => 14, 'height' => 14, 'layout' => $is_amp ? 'fixed' : 'intrinsic', 'loading' => 'lazy', 'decoding' => 'async', 'is_amp' => $is_amp]); ?>הרשמה</li>
                                            </ul>
                                        </div>

                                        <div class="col-md-6 col-sm-6 col-xs-12 mangentsec">
                                            <p class="mangementpara">השתלמות</p>
                                            <a href="<?php echo esc_url(home_url('/index2.php?id=105&lang=HEB')); ?>"><h3 class="sectionheading msetmargin">תמ"א 38 מורחב</h3></a>
                                            <ul class="list-inline managementline">
                                                <li><?php pablo_home_image(['src' => $theme_uri . '/assets/designFiles/plus-icon-yellow.png', 'alt' => 'plus-img-11', 'class' => 'manageicon', 'width' => 14, 'height' => 14, 'layout' => $is_amp ? 'fixed' : 'intrinsic', 'loading' => 'lazy', 'decoding' => 'async', 'is_amp' => $is_amp]); ?>תכנים</li>
                                                <li><?php pablo_home_image(['src' => $theme_uri . '/assets/designFiles/plus-icon-yellow.png', 'alt' => 'plus-img-11', 'class' => 'manageicon', 'width' => 14, 'height' => 14, 'layout' => $is_amp ? 'fixed' : 'intrinsic', 'loading' => 'lazy', 'decoding' => 'async', 'is_amp' => $is_amp]); ?>הרשמה</li>
                                            </ul>
                                        </div>

                                        <div class="col-md-12 col-sm-12 col-xs-12 mangentsec11">
                                            <p class="mangementpara">השתלמות</p>
                                            <h3 class="sectionheading">בקרוב...</h3>
                                            <div class="col-md-6 col-sm-6 col-xs-12 paddingZ">
                                                <p class="headmange1">קורס חדש<br>DMM</p>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-xs-12 paddingZ">
                                                <ul class="list-inline managementline othersret">
                                                    <li><?php pablo_home_image(['src' => $theme_uri . '/assets/designFiles/plus-icon-yellow.png', 'alt' => 'plus-img-11', 'class' => 'manageicon', 'width' => 14, 'height' => 14, 'layout' => $is_amp ? 'fixed' : 'intrinsic', 'loading' => 'lazy', 'decoding' => 'async', 'is_amp' => $is_amp]); ?>תכנים</li>
                                                    <li><?php pablo_home_image(['src' => $theme_uri . '/assets/designFiles/plus-icon-yellow.png', 'alt' => 'plus-img-11', 'class' => 'manageicon', 'width' => 14, 'height' => 14, 'layout' => $is_amp ? 'fixed' : 'intrinsic', 'loading' => 'lazy', 'decoding' => 'async', 'is_amp' => $is_amp]); ?>הרשמה</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="container bg-color-2 img-set home-img-set">
        <?php
        pablo_home_image([
            'src'      => $theme_uri . '/assets/designFiles/img-1.png',
            'alt'      => 'images part1',
            'class'    => 'imagepart imagepart',
            'width'    => 1200,
            'height'   => 420,
            'layout'   => $is_amp ? 'responsive' : 'intrinsic',
            'loading'  => 'lazy',
            'decoding' => 'async',
            'is_amp'   => $is_amp,
        ]);
        ?>
        <div class="img-part-details">
            <h2>מאגר המומחים<br>הגדול במדינה</h2>
            <a href="<?php echo esc_url(home_url('/course-registration/')); ?>">
                <button class="detils-relate" type="button">הרשם למאגר עכשיו</button>
            </a>
        </div>
    </div>

    <div class="container m-t-20 rtls-bortsa sectionpart">
        <div class="col-md-12 col-sm-12 col-xs-12 p-t-15 aresect">
            <p>
                למען הסר ספק! <br>
                הציבור מוזמן לעיין בהודעת משרד הבריאות המיועדת למבקרי האתר ביחס להגדרת התואר "מומחה" ושימושיו ע"פ פקודת רופאי השיניים 1977 !
                לקבלת הנוסח המלא של הודעת משרד הבריאות,<br>
                <a href="<?php echo esc_url(home_url('/%d7%94%d7%95%d7%93%d7%a2%d7%aa-%d7%9e%d7%a9%d7%a8%d7%93-%d7%94%d7%91%d7%a8%d7%99%d7%90%d7%95%d7%aa/')); ?>" class="iop"> לחץ כאן ! </a>
            </p>
        </div>
    </div>

</div>

<?php if (!$is_amp) : ?>
<script>
/* Author: pablo rotem */
document.addEventListener('DOMContentLoaded', function () {
    var triggers = document.querySelectorAll('.trigger-tab');

    triggers.forEach(function (trigger) {
        trigger.addEventListener('click', function (e) {
            var tabId = trigger.getAttribute('data-tab');

            if (tabId === '#home') {
                e.preventDefault();

                var homeTab = document.getElementById('pablo-tab-home');
                var homePanel = document.getElementById('home');

                if (homeTab) {
                    homeTab.checked = true;
                }

                if (homePanel) {
                    window.scrollTo({
                        top: homePanel.getBoundingClientRect().top + window.scrollY - 150,
                        behavior: 'smooth'
                    });
                }
            }
        });
    });
});
</script>
<?php endif; ?>

<?php get_footer(); ?>