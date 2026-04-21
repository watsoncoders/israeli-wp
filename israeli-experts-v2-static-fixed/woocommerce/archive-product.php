<?php
/**
 * WooCommerce Archive Product - Legal Modern FULL
 * Filters: category + subcategory + tag + search + price range
 * Includes: qty + hover + sticky filters
 * Author: pablo rotem
 */
defined('ABSPATH') || exit;

get_header('shop');

$shop_url = wc_get_page_permalink('shop');
if (!$shop_url) $shop_url = get_post_type_archive_link('product');

// GET filters
$search = isset($_GET['s']) ? sanitize_text_field(wp_unslash($_GET['s'])) : '';
$cat    = isset($_GET['product_cat']) ? sanitize_text_field(wp_unslash($_GET['product_cat'])) : '';
$subcat = isset($_GET['product_subcat']) ? sanitize_text_field(wp_unslash($_GET['product_subcat'])) : '';
$tag    = isset($_GET['product_tag']) ? sanitize_text_field(wp_unslash($_GET['product_tag'])) : '';
$minp   = isset($_GET['min_price']) ? sanitize_text_field(wp_unslash($_GET['min_price'])) : '';
$maxp   = isset($_GET['max_price']) ? sanitize_text_field(wp_unslash($_GET['max_price'])) : '';

$all_cats = get_terms(['taxonomy'=>'product_cat','hide_empty'=>false,'parent'=>0]);
$all_tags = get_terms(['taxonomy'=>'product_tag','hide_empty'=>false]);

$child_cats = [];
if ($cat) {
  $cat_term = get_term_by('slug', $cat, 'product_cat');
  if ($cat_term && !is_wp_error($cat_term)) {
    $child_cats = get_terms(['taxonomy'=>'product_cat','hide_empty'=>false,'parent'=>(int)$cat_term->term_id]);
  }
}

?>
<div class="legal-wrap">
  <div class="legal-container">

    <div class="legal-hero">
      <h1><?php woocommerce_page_title(); ?></h1>
      <p>חיפוש וסינון מהיר – מותאם לניידים ולסגנון אתר משפטי.</p>
    </div>

    <?php woocommerce_output_all_notices(); ?>

    <!-- Sticky filters -->
    <div class="legal-filters-wrap" style="margin-top:14px;">
      <div class="legal-card">
        <form class="legal-filters" method="get" action="<?php echo esc_url($shop_url); ?>">

          <div class="field field-wide">
            <label for="legal_s">חיפוש בעברית</label>
            <input class="legal-input" id="legal_s" name="s" value="<?php echo esc_attr($search); ?>" placeholder="לדוגמה: תיק עור, עניבה, עט..." />
          </div>

          <div class="field">
            <label for="legal_cat">קטגוריה</label>
            <select class="legal-select" id="legal_cat" name="product_cat" onchange="this.form.submit()">
              <option value="">כל הקטגוריות</option>
              <?php if (!is_wp_error($all_cats)) foreach ($all_cats as $c): ?>
                <option value="<?php echo esc_attr($c->slug); ?>" <?php selected($cat, $c->slug); ?>>
                  <?php echo esc_html($c->name); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="field">
            <label for="legal_subcat">תת־קטגוריה</label>
            <select class="legal-select" id="legal_subcat" name="product_subcat" <?php echo $cat ? '' : 'disabled'; ?>>
              <option value=""><?php echo $cat ? 'כל תתי־הקטגוריות' : 'בחר קטגוריה קודם'; ?></option>
              <?php if (!empty($child_cats) && !is_wp_error($child_cats)) foreach ($child_cats as $cc): ?>
                <option value="<?php echo esc_attr($cc->slug); ?>" <?php selected($subcat, $cc->slug); ?>>
                  <?php echo esc_html($cc->name); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="field">
            <label for="legal_tag">תגית</label>
            <select class="legal-select" id="legal_tag" name="product_tag">
              <option value="">כל התגיות</option>
              <?php if (!is_wp_error($all_tags)) foreach ($all_tags as $t): ?>
                <option value="<?php echo esc_attr($t->slug); ?>" <?php selected($tag, $t->slug); ?>>
                  <?php echo esc_html($t->name); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="field">
            <label>טווח מחיר</label>
            <div class="legal-range">
              <div><input class="legal-input" name="min_price" inputmode="decimal" value="<?php echo esc_attr($minp); ?>" placeholder="מינ׳" /></div>
              <div><input class="legal-input" name="max_price" inputmode="decimal" value="<?php echo esc_attr($maxp); ?>" placeholder="מקס׳" /></div>
            </div>
          </div>

          <div class="field field-actions">
            <button class="legal-btn legal-btn--primary" type="submit">סנן</button>
            <a class="legal-btn" href="<?php echo esc_url($shop_url); ?>">איפוס</a>
          </div>

          <?php if (isset($_GET['orderby'])): ?>
            <input type="hidden" name="orderby" value="<?php echo esc_attr(sanitize_text_field(wp_unslash($_GET['orderby']))); ?>">
          <?php endif; ?>

        </form>
      </div>
    </div>

    <!-- fallback hover+sticky אם CSS לא נטען -->
    <style>
      /* Author: pablo rotem */
      .legal-filters-wrap{position:sticky;top:12px;z-index:50}
      body.admin-bar .legal-filters-wrap{top:calc(12px + 32px)}
      @media(hover:hover){
        .legal-product{transition:transform .18s ease, box-shadow .18s ease}
        .legal-product:hover{transform:translateY(-3px)}
        .legal-product__media{overflow:hidden}
        .legal-product__media img{transition:transform .22s ease}
        .legal-product:hover .legal-product__media img{transform:scale(1.06)}
      }
    </style>

    <div style="margin-top:12px;">
      <?php do_action('woocommerce_before_shop_loop'); ?>
    </div>

    <?php
    // אם המשתמש בחר subcat, Woo לא מכיר product_subcat => נבצע התאמה קלה ע"י שינוי product_cat בפועל
    if ($subcat) {
      // נאלץ את לולאת Woo להציג את תת-הקטגוריה ע"י סינון query vars
      add_filter('woocommerce_product_query_tax_query', function($tax_query) use ($subcat){
        $tax_query[] = [
          'taxonomy' => 'product_cat',
          'field'    => 'slug',
          'terms'    => [$subcat],
        ];
        return $tax_query;
      }, 20, 1);
    }
    ?>

    <?php if (woocommerce_product_loop()) : ?>

      <div class="legal-products" style="margin-top:12px;">
        <?php while (have_posts()) : the_post(); global $product; if (!$product || !$product->is_visible()) continue; ?>

          <article <?php wc_product_class('legal-product'); ?> data-legal-loop-item>
            <div class="legal-product__media">
              <a href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
                <?php echo woocommerce_get_product_thumbnail('woocommerce_thumbnail'); ?>
              </a>
            </div>

            <div class="legal-product__body">
              <h3 class="legal-product__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

              <div class="legal-muted" style="font-size:13px;">
                <?php echo wp_kses_post(wc_get_product_category_list($product->get_id())); ?>
              </div>

              <div class="legal-price"><?php echo wp_kses_post($product->get_price_html()); ?></div>

              <div class="legal-actions">
                <div class="legal-qty" title="כמות">
                  <input data-legal-qty type="number" min="1" step="1" value="1" inputmode="numeric" aria-label="כמות">
                </div>

                <a class="legal-btn" href="<?php the_permalink(); ?>">לפרטים</a>

                <?php if ($product->is_purchasable() && $product->is_in_stock()) : ?>
                  <?php
                  $add_classes = implode(' ', array_filter([
                    'legal-btn','legal-btn--primary','add_to_cart_button',
                    'product_type_' . $product->get_type(),
                    $product->supports('ajax_add_to_cart') ? 'ajax_add_to_cart' : '',
                  ]));

                  echo sprintf(
                    '<a data-legal-add href="%s" class="%s" %s>%s</a>',
                    esc_url($product->add_to_cart_url()),
                    esc_attr($add_classes),
                    wc_implode_html_attributes([
                      'data-product_id' => $product->get_id(),
                      'data-product_sku'=> $product->get_sku(),
                      'rel'             => 'nofollow',
                    ]),
                    esc_html($product->add_to_cart_text())
                  );
                  ?>
                <?php else : ?>
                  <span class="legal-btn legal-btn--gold">לא זמין</span>
                <?php endif; ?>
              </div>

            </div>
          </article>

        <?php endwhile; ?>
      </div>

      <div class="legal-card" style="margin-top:14px;">
        <?php do_action('woocommerce_after_shop_loop'); ?>
      </div>

    <?php else : ?>
      <?php do_action('woocommerce_no_products_found'); ?>
    <?php endif; ?>

  </div>
</div>
<?php get_footer('shop'); ?>
