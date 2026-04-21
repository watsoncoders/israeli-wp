<?php
/**
 * Header - Final Mobile Menu Fix (No Float, Full Width, Perfect RTL)
 * Author: Pablo Rotem
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> dir="rtl">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <?php wp_head(); ?>

    <style>
        /* --- General Reset --- */
        html, body { margin: 0; padding: 0; overflow-x: hidden; font-family: 'Assistant', 'Open Sans', sans-serif; }
        #wpadminbar { position: fixed !important; }
        
        /* --- Navbar Base --- */
        .bg-color { background-color: #000046; border: none; min-height: 80px; position: relative; z-index: 1000; margin-bottom: 0;}
        
        /* ========================================
           DESKTOP VIEW (1200px+)
           ======================================== */
        @media (min-width: 1200px) {
            .navbar-grid { display: flex; justify-content: space-between; align-items: center; padding: 0 15px; height: 90px; }
            .logo-wrapper svg { height: 65px; width: auto; }
            .navbar-center { display: flex !important; justify-content: center; flex-grow: 1; }
            .navbar-nav { display: flex; flex-direction: row; }
            .navbar-nav > li > a { color: #fff !important; font-size: 16px; padding: 10px 12px; font-weight: bold; }
            .navbar-nav > li > a:hover { color: #FFA801 !important; }
            .right-icons { display: flex; gap: 10px; align-items: center; }
            .navbar-toggle { display: none !important; }
            
            /* Desktop Dropdown */
            .dropdown-content { display: none; position: absolute; background: #fff; top: 100%; right: 0; min-width: 200px; z-index: 999; border-top: 3px solid #FFA801; box-shadow: 0 5px 10px rgba(0,0,0,0.1); }
            .dropdown:hover .dropdown-content { display: block; }
            .dropdowncon { list-style: none; padding: 0; margin: 0; }
            .dropdowncon li a { display: block; padding: 10px 15px; color: #333 !important; text-align: right; text-decoration: none; border-bottom: 1px solid #eee; }
            .dropdowncon li a:hover { background: #f5f5f5; }
        }

        /* ========================================
           MOBILE & TABLET VIEW (Max 1199px)
           Fixed: Float None, Full Width Alignment
           ======================================== */
        @media (max-width: 1199px) {
            .navbar-grid { display: flex; justify-content: space-between; align-items: center; height: 70px; padding: 0 15px; position: relative; }
            .logo-wrapper svg { height: 45px; width: auto; }
            .right-icons { display: flex; gap: 12px; align-items: center; }

            /* Hamburger Button */
            .navbar-toggle {
                display: flex !important; flex-direction: column; justify-content: center; align-items: center;
                width: 40px; height: 35px; padding: 0; margin: 0; background: transparent;
                border: 1px solid rgba(255,255,255,0.5); border-radius: 4px; cursor: pointer;
            }
            .navbar-toggle .icon-bar { display: block; width: 22px; height: 2px; background-color: #fff; margin: 2px 0; }

            /* --- MENU CONTAINER --- */
            .navbar-collapse {
                position: absolute; top: 70px; left: 0; right: 0;
                background-color: #000046;
                z-index: 9999; border-top: 1px solid rgba(255,255,255,0.1);
                display: none; padding: 0;
                max-height: 85vh; overflow-y: auto;
            }
            .navbar-collapse.in { display: block !important; }

            /* --- TOP LEVEL ITEMS --- */
            /* Force float none to fix "left" issue */
            .navbar-nav { margin: 0; padding: 0; width: 100%; list-style: none; float: none !important; }
            
            .navbar-nav > li { 
                float: none !important; /* CRITICAL FIX */
                display: block !important;
                border-bottom: 1px solid rgba(255,255,255,0.15); 
                width: 100%; 
            }
            
            .navbar-nav > li > a {
                color: #fff !important;
                font-size: 22px !important;
                padding: 20px 20px 20px 10px !important; 
                display: block;
                text-decoration: none;
                font-weight: normal;
                text-align: right;
            }

            .navbar-nav > li.dropdown > a:after { content: none !important; }

            /* --- SUBMENU CONTAINER --- */
            .dropdown-content {
                display: none;
                position: relative !important; /* Reset absolute from desktop */
                top: auto !important; left: auto !important; right: auto !important;
                background-color: #ffffff !important;
                width: 100% !important;
                padding: 0;
                float: none !important;
            }
            .dropdown.open .dropdown-content { display: block; }

            /* --- SUBMENU ITEMS --- */
            ul.dropdowncon { list-style: none; padding: 0; margin: 0; width: 100%; }
            ul.dropdowncon li { border-bottom: 1px solid #eee; width: 100%; float: none; display: block; }
            
            ul.dropdowncon li a {
                color: #000046 !important;
                font-size: 18px !important;
                padding: 15px 20px 15px 10px !important;
                background-color: #fff;
                font-weight: normal;
                display: block;
                text-align: right !important;
            }
            
            ul.dropdowncon li a:hover { background-color: #f5f5f5; }
        }

        /* Icons */
        .key-link, .home-icon-link { width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; border-radius: 4px; }
        .key-link { background: #FFA801; }
        .key-link img { height: 18px; }
        .home-icon-link img { height: 26px; }
    </style>
</head>

<body <?php body_class(); ?>>

<div class="header">
    <nav class="navbar navbar-default bg-color">
        <div class="navbar-grid">

            <a href="<?php echo hx_rel('/'); ?>" class="logo-wrapper">
                <svg version="1.1" id="logo-image" xmlns="http://www.w3.org/2000/svg" viewBox="-341.9 245.1 277.4 71" style="enable-background:new -341.9 245.1 277.4 71;">
                    <style>.st0{fill:#FFFFFF;}.st1{fill:#FFA801;}</style>
                    <g><path class="st0" d="M-107.6,297.5h-10.7c0,0,0-4.7,0-10c0-6.4,4.4-13,4.4-16.2h11.2c0,5.2-4.9,11.6-4.9,18C-107.6,294.6-107.6,297.5-107.6,297.5"/><path class="st0" d="M-93.6,297.5h-10.7c0,0,0-4.7,0-10c0-6.4,4.4-13,4.4-16.2h11.2c0,5.2-4.9,11.6-4.9,18C-93.6,294.6-93.6,297.5-93.6,297.5"/><path class="st0" d="M-79.7,297.5h-10.7c0,0,0-4.7,0-10c0-6.4,4.4-13,4.4-16.2h11.2c0,5.2-4.9,11.6-4.9,18C-79.7,294.6-79.7,297.5-79.7,297.5"/><path class="st0" d="M-100.9,263.2c0,3.3-2.7,6-6,6s-6-2.7-6-6c0-3.3,2.7-6,6-6C-103.6,257.2-100.9,259.9-100.9,263.2z"/><path class="st0" d="M-87.4,263.2c0,3.3-2.7,6-6,6s-6-2.7-6-6c0-3.3,2.7-6,6-6C-90.1,257.2-87.4,259.9-87.4,263.2z"/><path class="st0" d="M-74,263.2c0,3.3-2.7,6-6,6s-6-2.7-6-6c0-3.3,2.7-6,6-6S-74,259.9-74,263.2z"/></g>
                    <path class="st1" d="M-69.6,247.1c1.7,0,3.1,1.4,3.1,3.1v53.9c0,1.7-1.4,3.1-3.1,3.1h-55.3c-1.7,0-3.1-1.4-3.1-3.1v-53.9c0-1.7,1.4-3.1,3.1-3.1H-69.6 M-69.6,245.1h-55.3c-2.8,0-5.1,2.3-5.1,5.1v53.9c0,2.8,2.3,5.1,5.1,5.1h55.3c2.8,0,5.1-2.3,5.1-5.1v-53.9C-64.5,247.4-66.8,245.1-69.6,245.1z"/>
                    <g><path class="st0" d="M-337.2,267.6h-3.8V259h3.8V267.6z"/><path class="st0" d="M-319.5,272.1c0,0.5-0.2,0.9-0.5,1.2c-0.3,0.4-0.7,0.6-1.2,0.6h-5.8v-2.5h3.7v-9.9h-6c-0.3,0-0.5-0.1-0.7-0.3c-0.2-0.2-0.3-0.4-0.3-0.7v-5h3.8v3.6h6.1c0.6,0,1,0.4,1,1.2v11.9H-319.5z"/><path class="st0" d="M-299,273.6c0,0.2-0.1,0.4-0.4,0.4h-3.4l-5.4-7.8l-0.4,0.4v4.9h2v2.5h-4.7c-0.7,0-1.1-0.3-1.1-1v-5.5c0-0.4,0.2-0.8,0.6-1.1l2.4-1.9l-3.4-4.8c-0.1-0.1-0.1-0.2-0.1-0.3c0-0.3,0.1-0.4,0.4-0.4h3.5l5.6,8l0.3-0.4v-5h-2.1V259h4.7c0.7,0,1.1,0.3,1.1,1v5.7c0,0.5-0.2,0.8-0.6,1.1l-2.3,1.9l3.2,4.6C-299,273.3-299,273.4-299,273.6z"/><path class="st0" d="M-282.8,273.9h-3.8v-12.3h-6V259h8.7c0.7,0,1,0.4,1,1.2v13.6H-282.8z"/><path class="st0" d="M-260,272.7c0,0.8-0.3,1.2-1,1.2h-13.5c-0.7,0-1-0.4-1-1.2V259h3.8v12.3h8.1V259h3.6V272.7z"/><path class="st0" d="M-249,267.6h-3.8V259h3.8V267.6z"/><path class="st0" d="M-230.4,273.9h-3.8v-12.3h-7.7V259h10.4c0.7,0,1,0.4,1,1.2v13.6H-230.4z"/><path class="st0" d="M-208,277.4h-3.8V259h3.8V277.4z"/><path class="st0" d="M-197.1,273.9h-3.8V259h3.8V273.9z"/><path class="st0" d="M-179.5,272.7c0,0.8-0.3,1.2-1,1.2h-9.5v-2.5h6.8v-9.9h-6.8v-2.5h9.5c0.7,0,1,0.4,1,1.2V272.7z"/><path class="st0" d="M-159.5,272.7c0,0.8-0.3,1.2-1,1.2h-6.5v-2.5h3.7v-9.8h-3.9l-1.8,12.2h-3.8l2.1-12.3h-1.8v-2.6h11.8c0.8,0,1.1,0.4,1.1,1.3L-159.5,272.7z"/><path class="st0" d="M-140.9,273.9h-3.8v-12.3h-7.3V259h10c0.7,0,1,0.4,1,1.2v13.6H-140.9z"/></g>
                </svg>
            </a>

            <div class="navbar-center collapse navbar-collapse" id="menu-collapse">
                <ul class="nav navbar-nav">
                    
                    <li class="dropdown navv">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">English</a>
                        <div class="dropdown-content">
                            <ul class="dropdowncon">
                                <li><a href="<?php echo hx_rel('about-en'); ?>">About us</a></li>
                                <li><a href="<?php echo hx_rel('contact-us-en'); ?>">Contact us</a></li>
                            </ul>
                        </div>
                    </li>

                    <li class="dropdown navv">
                        <a href="<?php echo hx_rel('about'); ?>" class="dropdown-toggle" data-toggle="dropdown">אודות המכון</a>
                        <div class="dropdown-content">
                            <ul class="dropdowncon">
                                <li><a href="<?php echo hx_rel('regulations'); ?>">תקנון המכון </a></li>
                                <li><a href="<?php echo hx_rel('ethics'); ?>">כללי האתיקה</a></li>
                                <li><a href="<?php echo hx_rel('tos'); ?>">תנאי השימוש</a></li>
                                <li><a href="<?php echo hx_rel('how-to-choose-expert'); ?>">כיצד בוחרים מומחה</a></li>
                            </ul>
                        </div>
                    </li>

                    <li><a href="<?php echo hx_rel('repository'); ?>">חיפוש במאגר</a></li>

                    <li class="dropdown navv">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">קורסים</a>
                        <div class="dropdown-content">
                            <ul class="dropdowncon">
                                <li><a href="<?php echo hx_rel('course-expert-witness'); ?>">קורס עדים מומחים</a></li>
                                <li><a href="<?php echo hx_rel('course-arbitrators-online'); ?>">קורס בוררים</a></li>
                                <li><a href="<?php echo hx_rel('meshulac-course'); ?>">קורס משולב</a></li>
                                <li><a href="<?php echo hx_rel('basic-tama-38'); ?>">קורס תמ"א 38 בסיסי</a></li>
                                <li><a href="<?php echo hx_rel('course-tama38-advanced'); ?>">קורס תמ"א 38 מורחב</a></li>
                            </ul>
                        </div>
                    </li>

                    <li class="dropdown navv">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">טפסים והרשמה</a>
                        <div class="dropdown-content">
                            <ul class="dropdowncon">
                                <li><a href="<?php echo hx_rel('courses-list'); ?>">טפסי הרשמה לקורסים</a></li>
                                <li><a href="<?php echo hx_rel('work-forms'); ?>">טפסי עבודה</a></li>
                            </ul>
                        </div>
                    </li>

                    <li><a href="<?php echo hx_rel('contact-us-2'); ?>">צור קשר</a></li>

                    <li class="dropdown navv">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">“יהללך זר”</a>
                        <div class="dropdown-content">
                            <ul class="dropdowncon">
                                <li><a href="<?php echo hx_rel('lastcourse'); ?>">דעת בוגרי הקורס האחרון (2018)</a></li>
                                <li><a href="<?php echo hx_rel('bogrim'); ?>">דעת בוגרי קורסים קודמים</a></li>
                                <li><a href="<?php echo hx_rel('users'); ?>">דעת משתמשי המאגר</a></li>
                            </ul>
                        </div>
                    </li>

                    <li><a href="<?php echo hx_rel('course-registration'); ?>">הרשמה למאגר</a></li>
                </ul>
            </div>

            <div class="right-icons">
                <a class="key-link" href="#" data-bs-toggle="modal" data-bs-target="#modal-login">
                    <img src="<?php echo hx_asset('designFiles/key.png'); ?>" alt="login">
                </a>

                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#menu-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <a class="home-icon-link" href="<?php echo home_url('/'); ?>">
                    <img src="<?php echo hx_asset('designFiles/home-icon.png'); ?>" alt="home">
                </a>
            </div>

        </div>
    </nav>
</div>

<script type="text/javascript">
document.addEventListener("DOMContentLoaded", function () {
    var menuBtn = document.querySelector('.navbar-toggle');
    var menuContent = document.querySelector('#menu-collapse');
    
    if(menuBtn && menuContent) {
        menuBtn.addEventListener('click', function(e) {
            e.preventDefault();
            menuContent.classList.toggle('in');
        });
    }

    var dropdowns = document.querySelectorAll('.navbar-nav .dropdown > a.dropdown-toggle');
    dropdowns.forEach(function (link) {
        link.addEventListener('click', function (e) {
            if (window.innerWidth < 1200) {
                e.preventDefault();
                var parentLi = this.parentElement;
                parentLi.classList.toggle('open');
                
                dropdowns.forEach(function(otherLink){
                    if(otherLink !== link) otherLink.parentElement.classList.remove('open');
                });
            }
        });
    });
});
</script>

<div id="content" class="site-content">