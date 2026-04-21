<?php
/*
Template Name: File Viewer
*/

get_header();

// 1. Get parameters from URL
$fileName = isset($_GET['file']) ? sanitize_text_field($_GET['file']) : '';
$folderName = isset($_GET['folder']) ? sanitize_text_field($_GET['folder']) : '';

// 2. Construct the direct URL to the file
// Based on your site structure, files seem to be in /wp-content/themes/.../assets/FOLDER/FILE
$fileUrl = get_template_directory_uri() . '/assets/' . $folderName . '/' . $fileName;

// 3. Create the Embed Source URL for Microsoft Office Viewer
// We must URL-encode the file path so MS Viewer can read it
$embedSrc = 'https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode($fileUrl);
?>

<style>
    .viewer-wrapper {
        text-align: center;
        padding: 30px 0;
        background-color: #f5f5f5;
        min-height: 800px;
    }
    .viewer-frame {
        background: #fff;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        border: 1px solid #ddd;
    }
    .viewer-header {
        margin-bottom: 20px;
        font-family: Arial, sans-serif;
    }
    .download-btn {
        margin-top: 20px;
        display: inline-block;
        background: #ffa500; /* Your site's orange */
        color: #fff;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 4px;
        font-weight: bold;
    }
    .download-btn:hover {
        background: #e69500;
        color: #fff;
        text-decoration: none;
    }
</style>

<div class="container-fluid viewer-wrapper">
    <div class="row">
        <div class="col-md-12">
            
            <?php if(!empty($fileName) && !empty($folderName)): ?>
                
                <div class="viewer-header">
                    <h2>צפייה בקובץ: <?php echo esc_html($fileName); ?></h2>
                </div>

                <iframe 
                    class="viewer-frame"
                    width="80%" 
                    height="800px" 
                    frameborder="0" 
                    src="<?php echo esc_url($embedSrc); ?>">
                    This is an embedded <a target="_blank" href="http://office.com">Microsoft Office</a> document, powered by <a target="_blank" href="http://office.com/webapps">Office Online</a>.
                </iframe>

                <br>
                
                <a href="<?php echo esc_url($fileUrl); ?>" class="download-btn">
                    <i class="fa fa-download"></i> הורד את הקובץ למחשב
                </a>

            <?php else: ?>
                
                <div class="alert alert-warning" style="text-align:center; direction:rtl;">
                    <strong>שגיאה:</strong> לא נבחר קובץ להצגה. אנא וודא שהקישור מכיל את שם הקובץ והתיקייה.
                </div>

            <?php endif; ?>

        </div>
    </div>
</div>

<?php get_footer(); ?>