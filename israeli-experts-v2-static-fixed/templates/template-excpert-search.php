<?php
/*
Template Name: חיפוש מאגר
*/

get_header(); 

// 1. SETTINGS & DB LOGIC
$lang = 'HEB'; 
global $wpdb;
global $idUniq; 
$idUniq = 0; 
$disabledCats = array(3,4,5,6,191,253,288,814,815);

// 2. RECURSIVE TREE FUNCTION 
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
        $html .= '<ul class="DefineTree treeplant line-tree">'; 
    } else {
         $currId = ++$idUniq;
         $html .= '<ul id="collapse'.$currId.'" class="panel-collapse collapse line-tree">'; 
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
            $itemClass = 'clickable-category paratext';
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

// 3. SEARCH PROCESSING
$results = [];
$has_searched = false;
$cnt = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_REQUEST['catId'])) {
    $has_searched = true;
    
    $nameText = isset($_POST['nameText']) ? esc_sql($_POST['nameText']) : '';
    $profText = isset($_POST['profText']) ? esc_sql($_POST['profText']) : '';
    $freeText = isset($_POST['freeText']) ? esc_sql($_POST['freeText']) : '';
    $dialZone = isset($_POST['dialZone']) ? esc_sql($_POST['dialZone']) : '';
    $catId    = isset($_REQUEST['catId']) ? esc_sql($_REQUEST['catId']) : '';

    $cond = "";

    if ($nameText) {
        $nameText2 = join("%%", explode(" ", $nameText));
        $cond .= " AND (concat(clubMembers.firstname, ' ', clubMembers.lastname) LIKE '%$nameText%' OR concat(clubMembers.lastname, ' ', clubMembers.firstname) LIKE '%$nameText%')";
    }
    if ($profText) {
        $cond .= " AND (israeli_experts.fldProfession LIKE '%$profText%' OR israeli_experts.fldSpecialization LIKE '%$profText%')";
    }
    if ($freeText) {
        $cond .= " AND (concat(clubMembers.firstname, ' ', clubMembers.lastname) LIKE '%$freeText%' OR israeli_experts.fldProfession LIKE '%$freeText%')";
    }
    if ($dialZone && is_numeric($dialZone)) {
        $cond .= " AND israeli_experts.fldDialZone = '$dialZone' ";
    }
    
    $join_cat = "";
    if ($catId) {
        $join_cat = " JOIN categoriesItems ON clubMembers.id = categoriesItems.itemId ";
        $cond .= " AND categoriesItems.categoryId = '$catId' ";
    }

    $sql = "SELECT DISTINCT clubMembers.*, israeli_experts.* FROM clubMembers 
            LEFT JOIN israeli_experts ON clubMembers.id = israeli_experts.memberId 
            $join_cat
            WHERE clubMembers.status = 'active' AND clubMembers.extraData4 = 'expert' AND israeli_experts.memberLanguage = '$lang' 
            $cond
            ORDER BY clubMembers.extraData6 DESC, binary clubMembers.lastname";

    $results = $wpdb->get_results($sql, ARRAY_A);
    $cnt = count($results);
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<style>
    .expert-search-wrapper { direction: rtl; text-align: right; font-family: Arial, sans-serif; background: #fff; padding: 20px 0; }
    
    /* --- Search Bar --- */
    .search-row { display: flex; flex-direction: row; gap: 10px; margin-bottom: 20px;}
    .search-input-group { flex: 1; position: relative; }
    .search-input-group input, .search-input-group select, .search-input-group .fake-select { width: 100%; height: 45px; border: 2px solid #0a1b42; padding: 0 10px; font-size: 16px; color: #0a1b42; background: #fff; border-radius: 0; box-sizing: border-box; text-align: right; line-height: 45px; cursor: pointer;}
    .search-input-group .fake-select::after { content: "▼"; font-size: 10px; position: absolute; left: 10px; top: 0; line-height: 45px; }
    .search-button-group { width: 50px; flex: 0 0 50px; }
    .search-button-group button { width: 100%; height: 45px; background-color: #ffa500; border: none; color: white; font-size: 20px; cursor: pointer; }
    
    /* --- Tree Logic --- */
    #chooseExperties { border: 1px solid #ffa500; margin-top: 15px; padding: 15px; background: #fff; }
    .tree-columns { display: flex; flex-wrap: wrap; }
    .tree-col { width: 33.33%; padding: 0 10px; box-sizing: border-box; }
    ul.DefineTree { list-style: none; padding-right: 0; margin: 0; }
    ul.DefineTree ul { list-style: none; padding-right: 20px; margin: 0; border-right: 1px solid #eee; }
    .insExtra { display: inline-block; width: 14px; height: 14px; line-height: 12px; text-align: center; color: #ffa500; margin-left: 5px; background: #fff; z-index: 2; position: relative; text-decoration: none; cursor: pointer;}
    .insExtra::after { content: "-"; font-weight: bold; font-size: 16px; }
    .insExtra.collapsed::after { content: "+"; }
    .paratext { color: #333; text-decoration: none; font-size: 14px; }
    .paratext:hover { color: #ffa500; text-decoration: underline; }
    .root-span { font-size: 15px; color: #333; }

    /* --- Results Styles --- */
    .paddingZ { padding: 0; }
    .m-t-30 { margin-top: 30px; }
    
    /* Header Row */
    .azrhit { background-color: #03042e; color: #fff; padding: 10px 0; font-weight: bold; font-size: 16px; margin-bottom: 5px; width: 100%;}
    
    /* Result Row Container - UPDATED FOR SPACING AND BORDER */
    .azrit-set { 
        border-bottom: 2px solid #ffa500; /* Use border bottom for the line */
        border-left: 1px solid #eee; 
        border-right: 1px solid #eee; 
        border-top: 1px solid #eee;
        
        padding: 15px 15px 25px 15px; /* Added 25px bottom padding to push content up from line, 15px side padding */
        display: block; 
        color: #333; 
        text-decoration: none !important; 
        margin-bottom: 10px; /* Space between cards */
        background: #fff; 
        width: 100%; 
        box-sizing: border-box;
    }
    .azrit-set:hover { background-color: #f9f9f9; }
    
    /* List Layout */
    .list-inline { list-style: none; padding: 0; margin: 0; display: flex; align-items: flex-start; width: 100%; flex-wrap: wrap;}
    
    /* Columns - Desktop */
    .addreswidth { width: 40%; padding-right: 15px; box-sizing: border-box; text-align: right; } 
    .anamewidth  { width: 25%; text-align: right; } 
    .phonewidth  { width: 20%; text-align: right; } 
    .sono        { width: 15%; text-align: center; } 
    
    .defultname { font-size: 24px; font-weight: bold; color: #03042e; } 
    .phonewidth, .sono { color: #337ab7; font-size: 14px; }
    
    /* Orange Checkmark */
    .fromline-1 { color: #ffa500; margin-left: 5px; font-size: 14px; }
    
    a.result-link, a.result-link:hover { text-decoration: none; color: inherit; display: block; width: 100%;}

    /* --- Responsive Mobile Styles --- */
    @media (max-width: 768px) {
        .search-row { flex-direction: column; }
        .search-button-group { width: 100%; }
        
        /* Hide the Table Header on Mobile */
        .azrhit { display: none; }
        
        /* Force Stack on Mobile */
        .list-inline { display: block; width: 100%; }
        
        /* Force Items to be Wide */
        .list-inline li { 
            width: 100% !important; 
            display: block; 
            text-align: right !important; 
            margin-bottom: 10px;
            padding: 0;
        }

        .azrit-set {
             /* Increased padding specifically for mobile to fix the cutoff text */
             padding: 15px; 
             padding-bottom: 30px; /* Extra space at bottom for the orange line */
        }

        .addreswidth { 
            /* Checkmarks section */
            margin-bottom: 15px;
            font-size: 16px;
            line-height: 1.6;
            order: 1;
        }

        .defultname { 
            /* Name - Make it big and bottom */
            font-size: 26px; 
            margin-top: 10px;
            margin-bottom: 5px;
            display: block;
            order: -1;
        }
        
        .phonewidth {
            /* Profession */
            font-size: 16px;
            color: #337ab7;
            margin-bottom: 5px;
            order: 0;
        }

        .sono {
            /* 04 Area Code */
            text-align: right !important; 
            color: #337ab7;
            font-weight: bold;
            font-size: 16px;
            float: left; /* Keep area code to the left */
            order: 2;
        }
    }
</style>

<script>
    function mygetvaluetodicv(name, id) {
        document.getElementById('catIdInput').value = id;
        document.getElementById('searchExpertyDisplay').innerHTML = name; 
        document.getElementById('searchExp').submit();
    }
</script>

<div class="container expert-search-wrapper">
    
    <form method="post" id="searchExp" action="">
        <input type="hidden" name="catId" id="catIdInput" value="<?php echo isset($_REQUEST['catId']) ? esc_attr($_REQUEST['catId']) : ''; ?>">
        
        <div class="search-row">
            <div class="search-input-group"><input name="nameText" type="text" placeholder="לפי שם" value="<?php echo isset($_POST['nameText']) ? esc_attr($_POST['nameText']) : ''; ?>"></div>
            <div class="search-input-group"><input name="profText" type="text" placeholder="לפי מקצוע" value="<?php echo isset($_POST['profText']) ? esc_attr($_POST['profText']) : ''; ?>"></div>
            <div class="search-input-group"><input name="freeText" type="text" placeholder="לפי ביטוי" value="<?php echo isset($_POST['freeText']) ? esc_attr($_POST['freeText']) : ''; ?>"></div>
            
            <div class="search-input-group">
                <div class="fake-select" data-toggle="collapse" data-target="#chooseExperties" id="searchExpertyDisplay">לפי התמחות</div>
            </div>
            
            <div class="search-input-group"><select name="dialZone"><option value="" selected>לפי אזור חיוג</option><option value='02'>02</option><option value='03'>03</option><option value='04'>04</option><option value='08'>08</option><option value='09'>09</option></select></div>
            <div class="search-button-group"><button type="submit" name="submitsearch"><i class="fa fa-search"></i></button></div>
        </div>
        
        <div class="collapse" id="chooseExperties">
            <div class="tree-columns">
                <div class="tree-col">
                    <ul class="DefineTree"><li><a data-toggle="collapse" href="#root3" class="insExtra"></a><span class="root-category">עדים מומחים</span><div id="root3" class="collapse in"><?php echo build_wp_category_tree(3, true); ?></div></li></ul>
                </div>
                <div class="tree-col">
                    <ul class="DefineTree"><li><a data-toggle="collapse" href="#root4" class="insExtra"></a><span class="root-category">בוררים</span><div id="root4" class="collapse in"><?php echo build_wp_category_tree(4, true); ?></div></li></ul>
                </div>
                <div class="tree-col">
                    <ul class="DefineTree"><li><a data-toggle="collapse" href="#root5" class="insExtra"></a><a href="javascript:void(0)" onclick="mygetvaluetodicv('מגשרים', 5)" class="root-category">מגשרים</a><div id="root5" class="collapse in"><?php echo build_wp_category_tree(5, true); ?></div></li></ul>
                </div>
            </div>
        </div>

        <div class="imgytrt3">
            <div class="col-md-12 col-sm-12 col-xs-12 tree-top tree-star pding0p">
                <div class="col-md-4 col-sm-4 col-xs-12 ywidt">
                    <ul class="DefineTree treeplant">
                        <li><a data-toggle="collapse" href="#bottomCollapse3" class="fromtree insExtra collapsed"></a><span class="root-span">עדים מומחים</span><div id="bottomCollapse3" class="panel-collapse collapse"><?php echo build_wp_category_tree(3, true); ?></div></li>
                    </ul>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12 ywidt">
                    <ul class="DefineTree treeplant">
                        <li><a data-toggle="collapse" href="#bottomCollapse4" class="fromtree insExtra collapsed"></a><span class="root-span">בוררים</span><div id="bottomCollapse4" class="panel-collapse collapse"><?php echo build_wp_category_tree(4, true); ?></div></li>
                    </ul>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12 ywidt">
                    <ul class="DefineTree treeplant">
                        <li><a data-toggle="collapse" href="#bottomCollapse5" class="fromtree insExtra collapsed"></a><span class="root-span">מגשרים</span><div id="bottomCollapse5" class="panel-collapse collapse"><?php echo build_wp_category_tree(5, true); ?></div></li>
                    </ul>
                    <div style="margin-top: 20px;">
                        <a href="http://www.expertsearch.co.uk/" target="_new"><img src="<?php echo get_template_directory_uri(); ?>/assets//images/expert.png" class="img-responsive try-top" alt="UK experts"></a>
                    </div>
                </div>
            </div>
        </div>
        
    </form>

    <?php if ($has_searched): ?>
    
    <div style="text-align: right; margin: 20px 0; color: #aaa;">
        <?php echo ($cnt == 1 ? 'נמצאה תוצאה 1' : "נמצאו $cnt תוצאות"); ?>
    </div>

    <div class="col-md-12 col-sm-12 col-xs-12 paddingZ">
        <div class="col-md-12 col-sm-12 col-xs-12 m-t-30 paddingZ">
            
            <div class="azrhit">
                <ul class="list-inline">
                    <li class="addreswidth">מידע ראשוני</li>
                    <li class="anamewidth">שם</li>
                    <li class="phonewidth">מקצוע</li>
                    <li class="sono araseta">אזור חיוג</li>
                </ul>
            </div>

            <?php if ($results): foreach ($results as $exRow): 
                $link = 'https://2.hashuk.net/expert-profile/?id=' . $exRow['id'];
                
                // Get Categories
                $cat_sql = "SELECT name FROM categoriesItems 
                            LEFT JOIN categories_byLang ON categoriesItems.categoryId = categories_byLang.categoryId 
                            WHERE itemId = {$exRow['id']} AND type = 'specific' AND language = '$lang' 
                            ORDER BY pos ASC";
                $expert_cats = $wpdb->get_results($cat_sql, ARRAY_A);
                $cat_html = "";
                if($expert_cats) {
                    foreach($expert_cats as $ec) {
                        $cat_html .= '<i class="fa fa-check fromline-1"></i> ' . $ec['name'] . '<br>';
                    }
                }

                // Format Name
                $title = isset($exRow['fldExtentName']) ? $exRow['fldExtentName'] : '';
                $expertName = $exRow['lastname'] . ' ' . $exRow['firstname'];
                if(!empty($title)) {
                    $expertName .= ', ' . $title;
                }
            ?>
            
            <a href="<?php echo $link; ?>" class="result-link">
                <div class="azrit-set">
                    <ul class="list-inline artiset">
                        <li class="fromtop addreswidth">
                            <?php echo $cat_html; ?>
                        </li>
                        <li class="defultname fromtop anamewidth">
                            <?php echo $expertName; ?>
                        </li>
                        <li class="fromtop phonewidth">
                            <?php echo $exRow['fldProfession']; ?><br>
                        </li>
                        <li class="fromtop sono sono-para">
                            <?php echo $exRow['fldDialZone']; ?>
                        </li>
                    </ul>
                </div>
            </a>

            <?php endforeach; else: ?>
                <div class="azrit-set">
                    <p style="padding: 10px;">לא נמצאו תוצאות.</p>
                </div>
            <?php endif; ?>
            
        </div>
    </div>
    
    <?php endif; ?>

</div>

<?php get_footer(); ?>