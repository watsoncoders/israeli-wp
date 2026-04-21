<?php
/*
 * Template Name: טפסים והרשמה לקורסים
 * Description: Page with links to online registration and manual doc forms
 */

if ( ! defined( 'ABSPATH' ) ) exit; 

get_header(); 
?>

<div class="ie-container" dir="rtl" lang="he">
  <div class="container-fluid">
    <div class="container paddXsZ">
        <div class="col-md-12 col-sm-12 col-xs-12 paddXsZ">
            
            <div class="col-md-12 col-sm-12 col-xs-12 paddXsZ">
                <p class="page3">
                    טפסים והרשמה לקורסים
                </p>
                <h1 class="page3line">טפסים והרשמה לקורסים</h1>
            </div>

            <div class="col-md-12 col-sm-12 col-xs-12 pagespecial-1 paddXsZ">
                <div class="col-md-10 col-md-offset-1 col-sm-10 col-offset-1 col-xs-12 piortl">
                    <br>
                    
                    <p><strong><u>קורס עדים מומחים ובוררים משולב </u></strong></p>
                    <p>
                        <a href="/%d7%98%d7%95%d7%a4%d7%a1-%d7%94%d7%a8%d7%a9%d7%9e%d7%94-%d7%9c%d7%a7%d7%95%d7%a8%d7%a1-%d7%9e%d7%a9%d7%95%d7%9c%d7%91/'">
                            טופס הרשמה מקוון (גם שאלון וגם טופס הזמנה)
                        </a>
                    </p>
                    <p>
                        <a href="<?php echo home_url('/file-viewer/?file=kurs_meshulac_order_(1).docx&folder=loadedFiles'); ?>">
                            שאלון הרשמה ידני
                        </a>
                    </p>
                    <p>
                        <a href="<?php echo home_url('/file-viewer/?file=kurs_meshulac_order_(1).doc&folder=loadedFiles'); ?>">
                            טופס הזמנה/ תשלום ידני
                        </a>
                    </p>
                    <p>&nbsp;</p>

                    <p><strong><u>קורס עדים מומחים</u></strong></p>
                    <p>
                        <a href="<?php echo home_url('/טופס-הרשמה-לקורס-עדים-מומחים/'); ?>">
                            טופס הרשמה מקוון (גם שאלון וגם טופס הזמנה)
                        </a>
                    </p>
                    <p>
                        <a href="<?php echo home_url('/file-viewer/?file=shelon_harshama_(1).doc&folder=loadedFiles'); ?>">
                            שאלון הרשמה ידני
                        </a>
                    </p>
                    <p>
                        <a href="<?php echo home_url('/file-viewer/?file=kurs_edim_mumchim_only_(1).doc&folder=loadedFiles'); ?>">
                            טופס הזמנה/תשלום ידני
                        </a>
                    </p>
                    <p>&nbsp;</p>

                    <p><strong><u>קורס בוררים </u></strong></p>
                    <p>
                        <a href="<?php echo home_url('/טופס-הרשמה-לקורס-בוררים/'); ?>">
                            טופס הרשמה מקוון (גם שאלון וגם טופס הזמנה)
                        </a>
                    </p>
                    <p>
                        <a href="<?php echo home_url('/file-viewer/?file=shelon_harshama_(1).doc&folder=loadedFiles'); ?>">
                            שאלון הרשמה ידני
                        </a>
                    </p>
                    <p>
                        <a href="<?php echo home_url('/file-viewer/?file=kurs_borerim_order.doc&folder=loadedFiles'); ?>">
                            טופס הזמנה/תשלום ידני
                        </a>
                    </p>
                    
                </div>
            </div>
        </div>
    </div>
  </div>
</div>

<?php get_footer(); ?>