<?php
/*
 * Template Name: טפסי עבודה
 * Description: List of downloadable work forms (Direct Download)
 */

if ( ! defined( 'ABSPATH' ) ) exit; 

get_header(); 

// Define Theme URI for assets
$theme_uri = get_stylesheet_directory_uri();
?>

<div class="ie-container" dir="rtl" lang="he">
  <div class="container-fluid">
    <div class="container paddXsZ">
        <div class="col-md-12 col-sm-12 col-xs-12 paddXsZ">
            
            <div class="col-md-12 col-sm-12 col-xs-12 paddXsZ">
                <p class="page3">
                    טפסי עבודה
                </p>
                <h1 class="page3line">טפסי עבודה</h1>
            </div>

            <div class="col-md-12 col-sm-12 col-xs-12 pagespecial">
                <h2 class="para">טפסי עבודה לבוררים ולמומחים</h2>
                
                <p class="para1">
                    <a href="<?php echo $theme_uri; ?>/assets/loadedFiles/abia.doc" class="spanpagest" download>
                        <img src="<?php echo $theme_uri; ?>/assets/designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> אבעיה
                    </a>
                </p>
                
                <p class="para1">
                    <a href="<?php echo $theme_uri; ?>/assets/loadedFiles/borerot.doc" class="spanpagest" download>
                        <img src="<?php echo $theme_uri; ?>/assets/designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> שטר בוררות
                    </a>
                </p>
                
                <p class="para1">
                    <a href="<?php echo $theme_uri; ?>/assets/loadedFiles/hahlatotbedvarsheelon.doc" class="spanpagest" download>
                        <img src="<?php echo $theme_uri; ?>/assets/designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> חיוב להשיב לשאלון
                    </a>
                </p>
                
                <p class="para1">
                    <a href="<?php echo $theme_uri; ?>/assets/loadedFiles/zhavmaniya.doc" class="spanpagest" download>
                        <img src="<?php echo $theme_uri; ?>/assets/designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> צו מניעה
                    </a>
                </p>
                
                <p class="para1">
                    <a href="<?php echo $theme_uri; ?>/assets/loadedFiles/dugmaotmutkotsada.doc" class="spanpagest" download>
                        <img src="<?php echo $theme_uri; ?>/assets/designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> תקנות סדרי הדין בענייני בוררות
                    </a>
                </p>
                
                <p class="para1">
                    <a href="<?php echo $theme_uri; ?>/assets/loadedFiles/borerotkiyumbedvarazhara.doc" class="spanpagest" download>
                        <img src="<?php echo $theme_uri; ?>/assets/designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> אזהרה
                    </a>
                </p>
                
                <p class="para1">
                    <a href="<?php echo $theme_uri; ?>/assets/loadedFiles/giluymismahih.doc" class="spanpagest" download>
                        <img src="<?php echo $theme_uri; ?>/assets/designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> גילוי מסמכים
                    </a>
                </p>
                
                <p class="para1">
                    <a href="<?php echo $theme_uri; ?>/assets/loadedFiles/ikuvyayziya.doc" class="spanpagest" download>
                        <img src="<?php echo $theme_uri; ?>/assets/designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> עיכוב יציאה
                    </a>
                </p>
                
                <p class="para1">
                    <a href="<?php echo $theme_uri; ?>/assets/loadedFiles/tzavikul.doc" class="spanpagest" download>
                        <img src="<?php echo $theme_uri; ?>/assets/designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> צו עיקול
                    </a>
                </p>
                
                <p class="para1">
                    <a href="<?php echo $theme_uri; ?>/assets/loadedFiles/havatdaatshelhamumhe.doc" class="spanpagest" download>
                        <img src="<?php echo $theme_uri; ?>/assets/designFiles/page-icon.png" class="img-responsive imgspecial" alt="link icon"> טופס חוות דעת מומחה
                    </a>
                </p>
                
            </div>
        </div>
    </div>
  </div>
</div>

<?php get_footer(); ?>