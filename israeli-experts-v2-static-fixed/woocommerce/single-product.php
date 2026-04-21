<?php
/**
 * WooCommerce Single Product - Legal Modern + Floating Cart
 * Author: pablo rotem
 */
defined('ABSPATH') || exit;

get_header('shop');
?>
<div class="legal-wrap">
  <div class="legal-container">

    <div class="legal-hero">
      <h1><?php the_title(); ?></h1>
      <p>מסמכים/שירותים משפטיים – תהליך ברור, מקצועי ומכבד.</p>

      <?php if (function_exists('woocommerce_breadcrumb')): ?>
        <div style="margin-top:10px;">
          <?php woocommerce_breadcrumb([
            'delimiter' => ' / ',
            'wrap_before' => '<nav class="woocommerce-breadcrumb" aria-label="breadcrumb">',
            'wrap_after'  => '</nav>',
          ]); ?>
        </div>
      <?php endif; ?>
    </div>

    <?php while (have_posts()) : the_post(); global $product; ?>

      <?php woocommerce_output_all_notices(); ?>

      <div class="legal-single">
        <div class="legal-single__gallery">
          <div class="legal-card">
            <?php do_action('woocommerce_before_single_product_summary'); ?>
          </div>

          <div class="legal-card" style="margin-top:14px;">
            <h3 style="margin:0 0 10px;">מה מקבלים?</h3>
            <div class="legal-badges">
              <span class="legal-badge">תשלום מאובטח</span>
              <span class="legal-badge">מענה מקצועי</span>
              <span class="legal-badge">תהליך ברור</span>
            </div>
            <div class="legal-muted" style="margin-top:10px; line-height:1.7;">
              ניתן להוסיף כאן טקסט אמון קצר (פרטיות, זמני תגובה, תנאים).
            </div>
          </div>
        </div>

        <div class="legal-single__summary">
          <div class="legal-card">
            <div class="legal-muted" style="margin-bottom:10px;">
              <?php echo wp_kses_post(wc_get_product_category_list($product->get_id())); ?>
            </div>

            <?php
            // WooCommerce מציג כבר quantity + add to cart בתוך woocommerce_single_product_summary
            // העיצוב של quantity/button משופר ב-CSS (גדול, מרווח, עגול)
            do_action('woocommerce_single_product_summary');
            ?>
          </div>

          <div class="legal-card" style="margin-top:14px;">
            <?php do_action('woocommerce_after_single_product_summary'); ?>
          </div>
        </div>
      </div>

    <?php endwhile; ?>

  </div>

  <?php
  // Floating cart (only on single product)
  $cart_url = function_exists('wc_get_cart_url') ? wc_get_cart_url() : home_url('/');
  $count = (function_exists('WC') && WC()->cart) ? (int) WC()->cart->get_cart_contents_count() : 0;
  ?>
  <a class="legal-float-cart" href="<?php echo esc_url($cart_url); ?>" aria-label="עגלה">
    <span class="legal-float-cart__btn">
      <svg class="legal-float-cart__icon" viewBox="0 0 24 24" aria-hidden="true">
        <path fill="currentColor" d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2Zm10 0c-1.1 0-1.99.9-1.99 2S15.9 22 17 22s2-.9 2-2-.9-2-2-2ZM7.2 14h9.9c.75 0 1.4-.41 1.74-1.03l3.24-6.02A1 1 0 0 0 21.2 5H6.21L5.27 3H2v2h2l3.6 7.59-1.35 2.44A2 2 0 0 0 8 18h12v-2H8l1.2-2Z"/>
      </svg>
      <span>עגלה</span>
      <span class="legal-float-cart__badge" data-legal-cart-badge><?php echo (int) $count; ?></span>
    </span>
  </a>

</div>
<?php
get_footer('shop');
