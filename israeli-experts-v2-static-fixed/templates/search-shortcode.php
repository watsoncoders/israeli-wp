<?php
/**
 * תבנית חיפוש מומחים
 * Author: pablo rotem
 */

defined('ABSPATH') || exit;
/** @var Pablo_Experts_CPT_Migrator $this */
$roots = $this->get_root_terms();
$total = (int) $query->found_posts;
$current_display = 'לפי התמחות';
if ($cat_id > 0) {
    $selected_term = $this->get_term_by_legacy_category_id($cat_id);
    if ($selected_term instanceof WP_Term) {
        $current_display = $selected_term->name;
    }
}
?>
<style>
.pablo-experts-search-wrapper{direction:rtl;text-align:right;font-family:Arial,sans-serif;background:#fff;padding:20px 0}
.pablo-experts-search-wrapper *{box-sizing:border-box}
.pablo-experts-search-row{display:flex;gap:10px;margin-bottom:20px;flex-wrap:wrap}
.pablo-experts-search-group{flex:1 1 180px;position:relative}
.pablo-experts-search-group input,.pablo-experts-search-group select,.pablo-fake-select{width:100%;height:45px;border:2px solid #0a1b42;padding:0 12px;font-size:16px;color:#0a1b42;background:#fff;border-radius:0;text-align:right;line-height:45px}
.pablo-fake-select{display:block;cursor:pointer;position:relative;padding-left:34px}
.pablo-fake-select:after{content:"▼";font-size:10px;position:absolute;left:12px;top:0;line-height:45px}
.pablo-experts-search-btn-wrap{width:50px;flex:0 0 50px}
.pablo-experts-search-btn{width:100%;height:45px;background:#ffa500;border:0;color:#fff;font-size:20px;cursor:pointer}
.pablo-choose-experties{border:1px solid #ffa500;margin-top:15px;padding:15px;background:#fff;display:none}
.pablo-choose-experties.is-open{display:block}
.tree-columns{display:flex;flex-wrap:wrap}
.tree-col{width:33.333%;padding:0 10px}
ul.DefineTree{list-style:none;padding-right:0;margin:0}
ul.DefineTree ul{list-style:none;padding-right:20px;margin:0;border-right:1px solid #eee}
.pablo-term-toggle{display:inline-flex;align-items:center;justify-content:center;width:18px;height:18px;line-height:18px;text-align:center;color:#ffa500;margin-left:5px;background:#fff;border:1px solid #ffa500;cursor:pointer;font-size:12px;padding:0}
.pablo-term-toggle--empty{border-color:transparent;color:transparent;cursor:default}
.pablo-tree-children{padding-right:18px}
.root-category,.root-span{font-size:15px;color:#333}
.pablo-term-link,.paratext{color:#333;text-decoration:none;font-size:14px}
.pablo-term-link:hover,.paratext:hover{color:#ffa500;text-decoration:underline}
.pablo-results-count{text-align:right;margin:20px 0;color:#777}
.azrhit{background:#03042e;color:#fff;padding:10px 0;font-weight:700;font-size:16px;margin-bottom:5px;width:100%}
.azrit-set{border-bottom:2px solid #ffa500;border-left:1px solid #eee;border-right:1px solid #eee;border-top:1px solid #eee;padding:15px 15px 25px;display:block;color:#333;text-decoration:none!important;margin-bottom:10px;background:#fff;width:100%}
.azrit-set:hover{background:#f9f9f9}
.list-inline{list-style:none;padding:0;margin:0;display:flex;align-items:flex-start;width:100%;flex-wrap:wrap}
.addreswidth{width:40%;padding-right:15px;text-align:right}.anamewidth{width:25%;text-align:right}.phonewidth{width:20%;text-align:right}.sono{width:15%;text-align:center}
.defultname{font-size:24px;font-weight:700;color:#03042e}.phonewidth,.sono{color:#337ab7;font-size:14px}.fromline-1{color:#ffa500;margin-left:5px;font-size:14px}
.pablo-bottom-trees{margin-top:24px}.pablo-pagination{display:flex;gap:8px;justify-content:center;flex-wrap:wrap;margin-top:18px}.pablo-pagination .page-numbers{display:inline-block;padding:8px 12px;border:1px solid #ddd;text-decoration:none;color:#0a1b42}.pablo-pagination .current{background:#0a1b42;color:#fff;border-color:#0a1b42}
@media (max-width:768px){.pablo-experts-search-row{flex-direction:column}.pablo-experts-search-btn-wrap{width:100%;flex-basis:auto}.azrhit{display:none}.list-inline{display:block;width:100%}.list-inline li{width:100%!important;display:block;text-align:right!important;margin-bottom:10px;padding:0}.azrit-set{padding:15px;padding-bottom:30px}.addreswidth{margin-bottom:15px;font-size:16px;line-height:1.6;order:1}.defultname{font-size:26px;margin-top:10px;margin-bottom:5px;display:block;order:-1}.phonewidth{font-size:16px;margin-bottom:5px;order:0}.sono{text-align:right!important;font-weight:700;font-size:16px;float:left;order:2}.tree-col{width:100%;margin-bottom:20px}}
</style>

<div class="pablo-experts-search-wrapper">
    <form method="get" action="" id="pabloExpertsSearchForm">
        <input type="hidden" name="catId" id="pabloExpertsCatId" value="<?php echo $cat_id > 0 ? esc_attr((string) $cat_id) : ''; ?>">

        <div class="pablo-experts-search-row">
            <div class="pablo-experts-search-group">
                <input name="nameText" type="text" placeholder="לפי שם" value="<?php echo esc_attr($name_text); ?>">
            </div>

            <div class="pablo-experts-search-group">
                <input name="profText" type="text" placeholder="לפי מקצוע" value="<?php echo esc_attr($prof_text); ?>">
            </div>

            <div class="pablo-experts-search-group">
                <input name="freeText" type="text" placeholder="לפי ביטוי" value="<?php echo esc_attr($free_text); ?>">
            </div>

            <div class="pablo-experts-search-group">
                <a href="#" class="pablo-fake-select" id="pabloExpertsCategoryToggle"><?php echo esc_html($current_display); ?></a>
            </div>

            <div class="pablo-experts-search-group">
                <select name="dialZone">
                    <option value="" <?php selected($dial_zone, ''); ?>>לפי אזור חיוג</option>
                    <option value="02" <?php selected($dial_zone, '02'); ?>>02</option>
                    <option value="03" <?php selected($dial_zone, '03'); ?>>03</option>
                    <option value="04" <?php selected($dial_zone, '04'); ?>>04</option>
                    <option value="08" <?php selected($dial_zone, '08'); ?>>08</option>
                    <option value="09" <?php selected($dial_zone, '09'); ?>>09</option>
                </select>
            </div>

            <div class="pablo-experts-search-btn-wrap">
                <button type="submit" class="pablo-experts-search-btn" aria-label="חפש">🔍</button>
            </div>
        </div>

        <div class="pablo-choose-experties" id="pabloExpertsCategoriesBox">
            <div class="tree-columns">
                <?php foreach ($roots as $root_term): ?>
                    <div class="tree-col">
                        <ul class="DefineTree">
                            <li>
                                <button type="button" class="pablo-term-toggle" data-target="root-<?php echo (int) $root_term->term_id; ?>">+</button>
                                <span class="root-category"><?php echo esc_html($root_term->name); ?></span>
                                <div id="root-<?php echo (int) $root_term->term_id; ?>" class="pablo-tree-children" hidden>
                                    <?php echo $this->render_term_tree((int) $root_term->term_id); ?>
                                </div>
                            </li>
                        </ul>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </form>

    <div class="pablo-bottom-trees">
        <div class="tree-columns">
            <?php foreach ($roots as $root_term): ?>
                <div class="tree-col">
                    <ul class="DefineTree">
                        <li>
                            <button type="button" class="pablo-term-toggle" data-target="bottom-<?php echo (int) $root_term->term_id; ?>">+</button>
                            <span class="root-span"><?php echo esc_html($root_term->name); ?></span>
                            <div id="bottom-<?php echo (int) $root_term->term_id; ?>" class="pablo-tree-children" hidden>
                                <?php echo $this->render_term_tree((int) $root_term->term_id); ?>
                            </div>
                        </li>
                    </ul>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php if ($name_text !== '' || $prof_text !== '' || $free_text !== '' || $dial_zone !== '' || $cat_id > 0): ?>
        <div class="pablo-results-count"><?php echo esc_html($total === 1 ? 'נמצאה תוצאה 1' : 'נמצאו ' . $total . ' תוצאות'); ?></div>

        <div class="azrhit">
            <ul class="list-inline">
                <li class="addreswidth">מידע ראשוני</li>
                <li class="anamewidth">שם</li>
                <li class="phonewidth">מקצוע</li>
                <li class="sono">אזור חיוג</li>
            </ul>
        </div>

        <?php if ($query->have_posts()): ?>
            <?php while ($query->have_posts()): $query->the_post(); ?>
                <?php
                $post_id = get_the_ID();
                $terms = get_the_terms($post_id, 'expert_category') ?: [];
                $profession = (string) get_post_meta($post_id, '_pablo_profession', true);
                $dial = (string) get_post_meta($post_id, '_pablo_dial_zone', true);
                $cats_html = '';
                foreach ($terms as $term) {
                    if ($term instanceof WP_Term) {
                        $cats_html .= '<span><span class="fromline-1">✓</span> ' . esc_html($term->name) . '</span><br>';
                    }
                }
                ?>
                <a href="<?php the_permalink(); ?>" class="azrit-set">
                    <ul class="list-inline">
                        <li class="addreswidth"><?php echo $cats_html; ?></li>
                        <li class="defultname anamewidth"><?php the_title(); ?></li>
                        <li class="phonewidth"><?php echo esc_html($profession); ?></li>
                        <li class="sono"><?php echo esc_html($dial); ?></li>
                    </ul>
                </a>
            <?php endwhile; wp_reset_postdata(); ?>

            <div class="pablo-pagination">
                <?php
                echo wp_kses_post(paginate_links([
                    'total' => max(1, (int) $query->max_num_pages),
                    'current' => max(1, $paged),
                    'format' => '?pg=%#%',
                    'add_args' => array_filter([
                        'nameText' => $name_text,
                        'profText' => $prof_text,
                        'freeText' => $free_text,
                        'dialZone' => $dial_zone,
                        'catId' => $cat_id > 0 ? $cat_id : null,
                    ], static fn($value) => $value !== null && $value !== ''),
                ]));
                ?>
            </div>
        <?php else: ?>
            <div class="azrit-set"><p style="padding:10px">לא נמצאו תוצאות.</p></div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const box = document.getElementById('pabloExpertsCategoriesBox');
    const toggle = document.getElementById('pabloExpertsCategoryToggle');
    const hiddenInput = document.getElementById('pabloExpertsCatId');
    const form = document.getElementById('pabloExpertsSearchForm');

    if (toggle && box) {
        toggle.addEventListener('click', function (event) {
            event.preventDefault();
            box.classList.toggle('is-open');
        });
    }

    document.querySelectorAll('.pablo-term-toggle').forEach(function (button) {
        button.addEventListener('click', function () {
            const targetId = button.getAttribute('data-target');
            if (!targetId) return;
            const target = document.getElementById(targetId);
            if (!target) return;
            const isHidden = target.hasAttribute('hidden');
            if (isHidden) {
                target.removeAttribute('hidden');
                button.textContent = '-';
            } else {
                target.setAttribute('hidden', 'hidden');
                button.textContent = '+';
            }
        });
    });

    document.querySelectorAll('.pablo-term-link').forEach(function (link) {
        link.addEventListener('click', function (event) {
            event.preventDefault();
            const catId = link.getAttribute('data-cat-id') || '';
            const catName = link.getAttribute('data-cat-name') || 'לפי התמחות';
            if (hiddenInput) hiddenInput.value = catId;
            if (toggle) toggle.textContent = catName;
            if (form) form.submit();
        });
    });
});
</script>
