<?php
/*
Template Name: עמוד תבנית לפרופיל יחיד
Author : pablo Rotem
*/

get_header(); 

// ==============================================================================
// 1. SETTINGS & TRANSLATIONS
// ==============================================================================
$lang = 'HEB'; 
global $wpdb;
global $idUniq; 
$idUniq = 0; 
$disabledCats = array(3,4,5,6,191,253,288,814,815);

$TIAL = [
    // Search Translations
    'searchEngine_byName'     => 'לפי שם',
    'searchEngine_byProf'     => 'לפי מקצוע',
    'searchEngine_byText'     => 'לפי ביטוי',
    'searchEngine_byExperty'  => 'לפי התמחות',
    'searchEngine_byDialZone' => 'לפי אזור חיוג',
    'search_button'           => 'חפש',
    'experts'                 => 'עדים מומחים',
    'borerim'                 => 'בוררים',
    'megashrim'               => 'מגשרים',
    
    // Profile Translations
    'expert_name' => 'שם המומחה',
    'expert_main_spec' => 'התמחות ראשית',
    'expert_profession' => 'מקצוע',
    'expert_longevity' => 'ותק',
    'years' => 'שנים',
    'expert_qualifications' => 'פירוט תארים והשכלה',
    'expert_memberInOrgs' => 'חבר בארגון/ים',
    'expert_experties' => 'התמחויות ומידע מקצועי משלים',
    'expert_occupations' => 'תחומי עיסוק',
    'expert_curriculum' => 'קורות חיים מקצועיים',
    'expert_contactWith' => 'ליצירת קשר עם המומחה',
    'expert_sendToFriend' => 'שליחת פרטי מומחה לחבר',
    'expert_watchDetails' => 'לצפיה בפרטי התקשרות',
    'expert_moreDetails' => 'פרטים נוספים',
    'expert_close' => 'סגור',
    'expert_not_found' => 'מומחה לא נמצא',
    'degree' => 'תואר',
    'expert_cellphone' => 'נייד',
    'expert_phone' => 'טלפון',
    'expert_fax' => 'פקס',
    'expert_email' => 'דוא"ל',
    'expert_address' => 'כתובת',
    'expert_homepage' => 'אתר הבית'
];

// ==============================================================================
// 2. SHARED FUNCTION: CATEGORY TREE
// ==============================================================================
if (!function_exists('build_wp_category_tree')) {
    function build_wp_category_tree($parentCatId, $isOpen = false) {
        global $wpdb, $idUniq, $disabledCats, $lang;

        $sql = "SELECT t1.id, t2.name 
                FROM categories t1 
                LEFT JOIN categories_byLang t2 ON t1.id = t2.categoryId 
                WHERE t2.language = '$lang' 
                AND t1.type = 'specific' 
                AND t1.parentId = '$parentCatId'
                ORDER BY t1.pos ASC";

        $results = $wpdb->get_results($sql, ARRAY_A);
        if (!$results) return "";

        $html = "";
        if ($isOpen) {
            $html .= '<ul class="DefineTree treeplant show">'; 
        } else {
             $currId = ++$idUniq;
             $html .= '<ul id="collapse'.$currId.'" class="panel-collapse collapse">'; 
        }

        foreach ($results as $row) {
            $currId = ++$idUniq;
            $sons = build_wp_category_tree($row['id'], false);
            $hasSons = ($sons != "");
            
            $toggleHtml = $hasSons 
                ? '<a data-toggle="collapse" href="#collapse'.($currId+1).'" class="insExtra collapsed"></a>' 
                : '<span class="insExtraSpacer"></span>';

            if (in_array($row['id'], $disabledCats)) {
                $link = 'javascript:void(0);';
                $clickAttr = '';
                $itemClass = 'root-category';
            } else {
                $link = 'javascript:void(0);';
                $clickAttr = 'onclick="mygetvaluetodicv(\''.esc_js($row['name']).'\', '.$row['id'].')"';
                $itemClass = 'clickable-category';
            }

            $html .= '<li>';
            $html .= $toggleHtml;
            $html .= '<a href="'.$link.'" '.$clickAttr.' class="'.$itemClass.'">' . $row['name'] . '</a>';
            $html .= $sons;
            $html .= '</li>';
        }
        $html .= '</ul>';
        return $html;
    }
}

// ==============================================================================
// 3. FETCH PROFILE DATA & HANDLE IMAGE
// ==============================================================================
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql = "SELECT * FROM clubMembers, israeli_experts 
        WHERE clubMembers.id = israeli_experts.memberId 
        AND clubMembers.id = $id";

$exRow = $wpdb->get_row($sql, ARRAY_A);

// If not found, show error but keep header/footer
if (!$exRow) {
    echo '<div class="container"><h2 style="text-align:center; padding:50px;">'.$TIAL['expert_not_found'].'</h2></div>';
    get_footer();
    exit;
}

// Prepare Data for View
$exName = $exRow['lastname'] . ' ' . $exRow['firstname'];
if (!empty($exRow['fldExtentName'])) {
    $exName .= ', ' . $exRow['fldExtentName'];
}

// --- IMAGE HANDLING FIX ---
$image_filename = $exRow['id'] . '_size1.jpg';
// Path to check if file exists on server (internal path)
$image_server_path = get_stylesheet_directory() . '/assets/membersFiles/' . $image_filename;
// URL to display the image (web path)
$image_url = get_stylesheet_directory_uri() . '/assets/membersFiles/' . $image_filename;

$hasImage = false;
if ( file_exists( $image_server_path ) ) {
    $hasImage = true;
}
// --------------------------

$cats_sql = "SELECT name FROM categoriesItems 
             LEFT JOIN categories_byLang ON categoriesItems.categoryId = categories_byLang.categoryId 
             WHERE itemId = {$exRow['id']} AND type = 'specific' AND language = 'HEB' 
             ORDER BY pos ASC";
$categories = $wpdb->get_results($cats_sql, ARRAY_A);

$exUl = [[], []]; 
$cnt = 0;
foreach ($categories as $cat) {
    $exUl[$cnt % 2][] = '<li class="paraplus"><i class="fa fa-check fromline-1"></i> ' . $cat['name'] . '</li>';
    $cnt++;
}

// Main Specialization (for header)
$mainSpec = isset($categories[0]['name']) ? $categories[0]['name'] : '';
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
    .expert-wrapper { direction: rtl; text-align: right; font-family: 'Open Sans', Arial, sans-serif; background: #fff; }
    .paddingZ { padding: 0 !important; }
    
    /* --- SEARCH BAR STYLES --- */
    .search-section { background: #fff; padding: 20px 0; border-bottom: 1px solid #eee; margin-bottom: 0; }
    .search-row { display: flex; flex-direction: row; gap: 10px; }
    .search-input-group { flex: 1; position: relative; }
    .search-input-group input, .search-input-group select, .search-input-group .fake-select { 
        width: 100%; height: 45px; border: 2px solid #0a1b42; padding: 0 10px; font-size: 16px; 
        color: #0a1b42; background: #fff; border-radius: 0; box-sizing: border-box; 
        text-align: right; line-height: 45px; cursor: pointer;
    }
    .search-input-group .fake-select::after { content: "▼"; font-size: 10px; position: absolute; left: 10px; top: 0; line-height: 45px; }
    .search-button-group { width: 50px; flex: 0 0 50px; }
    .search-button-group button { 
        width: 100%; height: 45px; background-color: #ffa500; border: none; 
        color: white; font-size: 20px; cursor: pointer; 
    }
    
    /* Tree Styling */
    #chooseExperties { border: 1px solid #ffa500; margin-top: 15px; padding: 15px; background: #fff; }
    .tree-columns { display: flex; flex-wrap: wrap; }
    .tree-col { width: 33.33%; padding: 0 10px; box-sizing: border-box; }
    ul.DefineTree { list-style: none; padding-right: 0; margin: 0; }
    ul.DefineTree ul { list-style: none; padding-right: 20px; margin: 0; border-right: 1px solid #ffa500; }
    .insExtra { display: inline-block; width: 14px; height: 14px; line-height: 12px; text-align: center; border: 1px solid #ffa500; color: #ffa500; margin-left: 5px; background: #fff; z-index: 2; position: relative; text-decoration: none;}
    .insExtra::after { content: "-"; }
    .insExtra.collapsed::after { content: "+"; }
    .insExtraSpacer { display: inline-block; width: 14px; margin-left: 5px; }
    ul.DefineTree ul li::before { content: ""; position: absolute; top: 15px; right: -20px; width: 20px; height: 1px; background: #ffa500; }
    a.clickable-category { color: #333; text-decoration: none; }

    /* --- PROFILE HEADER STYLES --- */
    .bgcoloimprt { background-color: #f5f5f5; border-bottom: 4px solid #ffa500; padding-bottom: 20px; padding-top: 20px; }
    .rtlcolor { color: #999; font-size: 14px; margin-bottom: 0; font-weight: normal; }
    .rtl-size { font-size: 30px; color: #03042e; font-weight: bold; margin-top: 0; margin-bottom: 15px; }
    .paddingR { padding-right: 15px; }
    .rtlbrdertop { border-top: 1px solid #ddd; padding-top: 15px; margin-top: 10px; clear: both; }
    
    /* --- PROFILE BODY STYLES --- */
    .cont-rtl { background: #fff; padding-top: 30px; }
    .rtlstart, .rtlstrt1 { margin-bottom: 30px; border-bottom: 1px solid #eee; padding-bottom: 20px; }
    .para1 { color: #03042e; font-size: 22px; font-weight: bold; border-bottom: 2px solid #ffa500; display: inline-block; padding-bottom: 5px; margin-bottom: 15px; }
    .para2 { font-weight: bold; color: #555; margin-bottom: 5px; font-size: 16px; }
    .para3 { color: #333; margin-bottom: 15px; white-space: pre-line; font-size: 15px; line-height: 1.6; }
    
    /* Occupations */
    .linkfron { font-size: 22px; color: #03042e; font-weight: bold; margin-bottom: 20px; }
    .uilty { list-style: none; padding: 0; }
    .uilty li.paraplus { margin-bottom: 10px; border-bottom: 1px dotted #ccc; padding-bottom: 8px; font-size: 16px; color: #333; }
    .uilty li i { font-size: 10px; color: #ffa500; vertical-align: middle; margin-left: 5px; }
    
    /* Image */
    .imgempoyee { border: 5px solid #fff; box-shadow: 0 0 5px rgba(0,0,0,0.2); max-width: 100%; height: auto; display: block; }
    .padleft { padding-left: 15px; }

    /* Top Categories */
    .pading15 li.paraplus { list-style: none; font-size: 15px; margin-bottom: 8px; color: #333; }
    .fromline-1 { color: #ffa500; margin-left: 8px; }

    /* Sidebar Details */
    .details { background: #f9f9f9; padding: 0; border: 1px solid #eee; margin-top: 0; }
    .details button { width: 100%; background: #ffa500; color: #fff; border: none; padding: 15px; text-align: center; font-size: 18px; font-weight: bold; outline: none; }
    .details button i { float: left; margin-top: 5px; }
    .expertDetails { list-style: none; padding: 15px; margin: 0; }
    .htop { border-bottom: 1px solid #ddd; padding: 10px 0; display: flex; align-items: flex-start; }
    .htop i, .htop img { width: 25px; text-align: center; margin-left: 15px; color: #03042e; font-size: 20px; }
    .padleft p { margin: 0; font-size: 15px; line-height: 1.4; }
    .prtspan { font-weight: bold; color: #03042e; font-size: 16px; }
    .details1 { padding: 0 15px 15px 15px; }
    .para6 { font-size: 18px; color: #03042e; margin-bottom: 10px; margin-top: 10px; }
    .expertMoreDetals { list-style: none; padding: 0; }
    .expertMoreDetals li { margin-bottom: 8px; font-size: 14px; }
    .para7 { color: #777; cursor: pointer; text-align: left; margin-top: 10px; font-size: 14px; }

    /* Tabs & Forms */
    .nav-tabs { border-bottom: 1px solid #ddd; margin-top: 30px; margin-bottom: 0; padding-right: 0;}
    .nav-tabs>li { float: right; margin-bottom: -1px; }
    .nav-tabs>li>a { margin-right: 2px; line-height: 1.42857143; border: 1px solid transparent; border-radius: 4px 4px 0 0; color: #555; background: #eee; font-weight: bold; }
    .nav-tabs>li.active>a { color: #fff; background-color: #03042e; border: 1px solid #03042e; border-bottom-color: transparent; }
    .tab-content { border: 1px solid #ddd; border-top: none; padding: 20px; background: #fff; }
    .contact_left_input { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; background: #f9f9f9; }
    .from-sumit { background: #ffa500; color: #fff; border: none; padding: 10px 30px; font-size: 16px; font-weight: bold; }

    /* Nav Path / Breadcrumb */
    .breadcrumb-row { padding: 10px 0; font-size: 14px; color: #555; }
    .breadcrumb-row a { color: #555; text-decoration: none; }

    @media (max-width: 992px) {
        .col-md-3.padleft { margin-top: 20px; text-align: center; }
        .imgempoyee { display: inline-block; }
        .search-row { flex-direction: column; }
    }
</style>

<script>
    function mygetvaluetodicv(name, id) {
        document.getElementById('catIdInput').value = id;
        document.getElementById('searchExpertyDisplay').innerHTML = name; 
        document.getElementById('searchExp').submit();
    }
</script>

<div class="expert-wrapper">

    <div class="container search-section">
        <form method="post" id="searchExp" action="<?php echo home_url('/expert-search/'); ?>">
            <input type="hidden" name="catId" id="catIdInput" value="">
            
            <div class="search-row">
                <div class="search-input-group"><input name="nameText" type="text" placeholder="<?php echo $TIAL['searchEngine_byName']; ?>"></div>
                <div class="search-input-group"><input name="profText" type="text" placeholder="<?php echo $TIAL['searchEngine_byProf']; ?>"></div>
                <div class="search-input-group"><input name="freeText" type="text" placeholder="<?php echo $TIAL['searchEngine_byText']; ?>"></div>
                
                <div class="search-input-group">
                    <div class="fake-select" data-toggle="collapse" data-target="#chooseExperties" id="searchExpertyDisplay">
                        <?php echo $TIAL['searchEngine_byExperty']; ?>
                    </div>
                </div>
                
                <div class="search-input-group">
                    <select name="dialZone">
                        <option value="" selected><?php echo $TIAL['searchEngine_byDialZone']; ?></option>
                        <option value='02'>02</option>
                        <option value='03'>03</option>
                        <option value='04'>04</option>
                        <option value='08'>08</option>
                        <option value='09'>09</option>
                    </select>
                </div>
                
                <div class="search-button-group">
                    <button type="submit" name="submitsearch"><i class="fa fa-search"></i></button>
                </div>
            </div>

            <div class="collapse" id="chooseExperties">
                <div class="tree-columns">
                    <div class="tree-col">
                        <ul class="DefineTree"><li><a data-toggle="collapse" href="#root3" class="insExtra"></a><span class="root-category"><?php echo $TIAL['experts']; ?></span><div id="root3" class="collapse in"><?php echo build_wp_category_tree(3, true); ?></div></li></ul>
                    </div>
                    <div class="tree-col">
                        <ul class="DefineTree"><li><a data-toggle="collapse" href="#root4" class="insExtra"></a><span class="root-category"><?php echo $TIAL['borerim']; ?></span><div id="root4" class="collapse in"><?php echo build_wp_category_tree(4, true); ?></div></li></ul>
                    </div>
                    <div class="tree-col">
                        <ul class="DefineTree"><li><a data-toggle="collapse" href="#root5" class="insExtra"></a><a href="javascript:void(0)" onclick="mygetvaluetodicv('<?php echo $TIAL['megashrim']; ?>', 5)" class="root-category"><?php echo $TIAL['megashrim']; ?></a><div id="root5" class="collapse in"><?php echo build_wp_category_tree(5, true); ?></div></li></ul>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="container breadcrumb-row">
        <div class="col-md-12 paddingZ">
            <a href="<?php echo home_url(); ?>">דף הבית</a> > <?php echo $exName; ?>
        </div>
    </div>

    <div class="container-fluid paddingZ bgcoloimprt">
        <div class="container paddingZ">
            <div class="row">
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12 paddingR">
                            <p class="rtlcolor"><?php echo $TIAL['expert_name']; ?></p>
                            <h1 class="rtl-size"><?php echo $exName; ?></h1>
                            
                            <?php if($mainSpec): ?>
                            <p class="rtlcolor"><?php echo $TIAL['expert_main_spec']; ?></p>
                            <h2 class="rtl-size"><?php echo $mainSpec; ?></h2>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12 paddingR">
                            <p class="rtlcolor"><?php echo $TIAL['expert_profession']; ?></p>
                            <h2 class="rtl-size"><?php echo $exRow['fldProfession']; ?></h2>
                            
                            <?php if ($exRow['fldGeneralLongevity']): ?>
                                <p class="rtlcolor"><?php echo $TIAL['expert_longevity']; ?></p>
                                <h2 class="rtl-size"><?php echo $exRow['fldGeneralLongevity'] . ' ' . $TIAL['years']; ?></h2>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="col-md-12 col-sm-12 col-xs-12 paddingZ rtlbrdertop">
                        <div class="col-md-6 col-sm-6 col-xs-12 paddingZ">
                            <ul class="pading15 padright6 psdingright" style="padding:0; margin:0;">
                                <?php echo implode('', $exUl[0]); ?>
                            </ul>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12 paddingZ">
                            <ul class="pading15 psdingright" style="padding:0; margin:0;">
                                <?php echo implode('', $exUl[1]); ?>
                            </ul>
                        </div>
                    </div>
                </div>

                <?php if ( $hasImage ) : ?>
                <div class="col-md-3 col-sm-3 col-xs-12 padleft">
                    <img src="<?php echo esc_url($image_url); ?>" class="img-responsive imgempoyee" alt="<?php echo esc_attr($exName); ?>">
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="container-fluid cont-rtl">
        <div class="container">
            <div class="row">
                <div class="col-md-7 col-sm-7 col-xs-12">
                    
                    <div class="rtlstart">
                        <h2 class="para1"><?php echo $TIAL['expert_qualifications']; ?></h2>
                        <?php if ( ! empty( $exRow['university1Degree'] ) ): ?>
                            <p class="para2"><?php echo $TIAL['degree']; ?></p>
                            <p class="para3"><?php echo $exRow['university1Degree']; ?></p>
                        <?php endif; ?>
                        
                        <?php if ($exRow['fldQualifications'] || $exRow['fldQualificationsAdditionalNotes']): ?>
                            <p class="para3">
                                <?php 
                                    echo nl2br($exRow['fldQualifications']); 
                                    echo nl2br($exRow['fldQualificationsAdditionalNotes']);
                                ?>
                            </p>
                        <?php endif; ?>

                        <?php if ($exRow['fldOrgenizations']): ?>
                            <p class="para2"><?php echo $TIAL['expert_memberInOrgs']; ?></p>
                            <p class="para3"><?php echo nl2br($exRow['fldOrgenizations']); ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="rtlstrt1">
                        <h2 class="para1"><?php echo $TIAL['expert_experties']; ?></h2>
                        <p class="para3"><?php echo nl2br($exRow['fldAdditionalNotes']); ?></p>
                    </div>

                    <div class="col-md-12 col-sm-12 col-xs-12 paddingZ" style="margin-bottom: 30px;">
                        <h2 class="linkfron"><?php echo $TIAL['expert_occupations']; ?></h2>
                        <div class="row">
                            <?php 
                            $chunks = array_chunk($categories, ceil(count($categories) / 3));
                            foreach($chunks as $chunk) {
                                echo '<div class="col-md-4 col-sm-4 col-xs-12"><ul class="uilty">';
                                foreach($chunk as $cat) {
                                    echo '<li class="paraplus"><i class="fa fa-plus"></i> ' . $cat['name'] . '</li>';
                                }
                                echo '</ul></div>';
                            }
                            ?>
                        </div>
                    </div>

                    <div class="rtlstrt1">
                        <h2 class="para1"><?php echo $TIAL['expert_curriculum']; ?></h2>
                        <p class="para3"><?php echo nl2br($exRow['fldPublicAdditionalNotes']); ?></p>
                    </div>

                    <div class="col-md-12 paddingZ">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#form2"><?php echo $TIAL['expert_contactWith']; ?></a></li>
                            <li><a data-toggle="tab" href="#form1"><?php echo $TIAL['expert_sendToFriend']; ?></a></li>
                        </ul>
                        <div class="tab-content">
                            <div id="form2" class="tab-pane fade in active">
                                <br>
                                <p class="heading-frm">אנא מלאו את הפרטים הבאים:</p>
                                <form> 
                                    <div class="row">
                                        <div class="col-md-6"><input type="text" class="contact_left_input" placeholder="שם פרטי"></div>
                                        <div class="col-md-6"><input type="text" class="contact_left_input" placeholder="שם משפחה"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6"><input type="text" class="contact_left_input" placeholder='דוא"ל'></div>
                                        <div class="col-md-6"><input type="text" class="contact_left_input" placeholder="טלפון"></div>
                                    </div>
                                    <textarea class="contact_left_input" placeholder="תוכן ההודעה"></textarea>
                                    <button class="from-sumit">שליחה</button>
                                </form>
                            </div>
                            <div id="form1" class="tab-pane fade">
                                <br><p>טופס שליחה לחבר...</p>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-md-4 col-sm-4 col-xs-12">
                    <div class="details">
                        <button type="button" data-toggle="collapse" data-target="#collapse11">
                            <?php echo $TIAL['expert_watchDetails']; ?> <i class="fa fa-angle-down"></i>
                        </button>
                        
                        <div id="collapse11" class="collapse in">
                            <ul class="expertDetails">
                                <?php if ($exRow['fldPublicMobil']): ?>
                                <li class="htop">
                                    <div class=""><img src="/wp-content/themes/israeli-experts-v2-static-fixed/assets/images/mobile.png" class="img-responsive"></div>
                                    <div class="padleft"><p><span class="prtspan"><?php echo $TIAL['expert_cellphone']; ?></span><br><?php echo $exRow['fldPublicMobil']; ?></p></div>
                                </li>
                                <?php endif; ?>

                                <?php if ($exRow['fldPublicPhone']): ?>
                                <li class="htop">
                                    <div class=""><img src="/wp-content/themes/israeli-experts-v2-static-fixed/assets/images/phone.png" class="img-responsive"></div>
                                    <div class="padleft"><p><span class="prtspan"><?php echo $TIAL['expert_phone']; ?></span><br><?php echo $exRow['fldPublicPhone']; ?></p></div>
                                </li>
                                <?php endif; ?>
                                
                                <?php if ($exRow['fldPublicFax']): ?>
                                <li class="htop">
                                    <div class=""><img src="/wp-content/themes/israeli-experts-v2-static-fixed/assets/images/printer.png" class="img-responsive"></div>
                                    <div class="padleft"><p><span class="prtspan"><?php echo $TIAL['expert_fax']; ?></span><br><?php echo $exRow['fldPublicFax']; ?></p></div>
                                </li>
                                <?php endif; ?>

                                <?php if ($exRow['fldPublicEmail']): ?>
                                <li class="htop">
                                    <div class=""><img src="/wp-content/themes/israeli-experts-v2-static-fixed/assets/images/message-2.png" class="img-responsive"></div>
                                    <div class="padleft"><p><span class="prtspan"><?php echo $TIAL['expert_email']; ?></span><br><a href="mailto:<?php echo $exRow['fldPublicEmail']; ?>"><?php echo $exRow['fldPublicEmail']; ?></a></p></div>
                                </li>
                                <?php endif; ?>

                                <?php if ($exRow['fldPublicAddress']): ?>
                                <li class="htop">
                                    <div class=""><img src="/wp-content/themes/israeli-experts-v2-static-fixed/assets/images/linkdin.png" class="img-responsive"></div>
                                    <div class="padleft"><p><span class="prtspan"><?php echo $TIAL['expert_address']; ?></span><br><?php echo $exRow['fldPublicAddress']; ?></p></div>
                                </li>
                                <?php endif; ?>
                                
                                 <?php if ($exRow['mySite']): ?>
                                <li class="htop">
                                    <div class=""><i class="fa fa-globe"></i></div>
                                    <div class="padleft"><p><span class="prtspan"><?php echo $TIAL['expert_homepage']; ?></span><br><a href="<?php echo $exRow['mySite']; ?>" target="_blank"><?php echo $exRow['mySite']; ?></a></p></div>
                                </li>
                                <?php endif; ?>
                            </ul>

                            <div class="details1">
                                <p class="para6" style="margin-top:20px; font-weight:bold;"><?php echo $TIAL['expert_moreDetails']; ?></p>
                                <ul class="expertMoreDetals">
                                    <?php if ($exRow['accessDisabled']): ?>
                                        <li><img src="/wp-content/themes/israeli-experts-v2-static-fixed/assets/images/right.png"> נגישות לנכים</li>
                                    <?php endif; ?>
                                    <?php if ($exRow['accessElevator']): ?>
                                        <li><img src="/wp-content/themes/israeli-experts-v2-static-fixed/assets/images/right.png"> מעלית בבניין</li>
                                    <?php endif; ?>
                                    <?php if ($exRow['accessBuses']): ?>
                                    <li class="htop">
                                        <i class="fa fa-bus"></i>
                                        <div class="padleft"><p><span class="prtspan">הגעה באוטובוס</span><br><?php echo $exRow['accessBuses']; ?></p></div>
                                    </li>
                                    <?php endif; ?>
                                    <?php if ($exRow['accessPark']): ?>
                                    <li class="htop">
                                        <span class="fa-stack"><i class="fa fa-square-o fa-stack-2x"></i><strong class="fa-stack-1x icon-text">P</strong></span>
                                        <div class="padleft"><p><span class="prtspan">חניון קרוב</span><br><?php echo $exRow['accessPark']; ?></p></div>
                                    </li>
                                    <?php endif; ?>
                                </ul>
                                
                                <?php if ($exRow['fldPublicAddress']): 
                                    $mapAddr = urlencode(str_replace('מיקוד', '', preg_replace('/(\d{5,})/', '', $exRow['fldPublicAddress'])));
                                ?>
                                <iframe width="100%" height="250" frameborder="0" style="border:0; margin-top:15px;"
                                    src="https://maps.google.com/maps?q=<?php echo $mapAddr; ?>&output=embed">
                                </iframe>
                                <?php endif; ?>
                                
                                <div class="col-md-12 col-sm-12 col-xs-12 detailspecial">
                                    <p class="para7" data-toggle="collapse" href="#collapse11">סגור</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<?php get_footer(); ?>