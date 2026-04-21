/* Author: pablo rotem
   Purpose: Loop qty -> add-to-cart quantity + floating cart badge updates
*/
(function () {
  function qs(sel, root) { return (root || document).querySelector(sel); }
  function qsa(sel, root) { return Array.prototype.slice.call((root || document).querySelectorAll(sel)); }

  function updateCartBadge(count) {
    var badge = qs('[data-legal-cart-badge]');
    if (!badge) return;
    badge.textContent = String(count || 0);
    badge.style.display = (parseInt(badge.textContent, 10) > 0) ? 'flex' : 'flex';
  }

  // Update badge from fragments (Woo AJAX)
  function tryBindWooEvents() {
    if (!window.jQuery) return;

    jQuery(function ($) {
      // When product added via ajax
      $(document.body).on('added_to_cart', function (event, fragments, cart_hash, $button) {
        // Try to parse mini-cart count from fragments if exists (fallback to endpoint below)
        fetchCountEndpoint();
      });

      // On cart fragments refreshed
      $(document.body).on('wc_fragments_refreshed', function () {
        fetchCountEndpoint();
      });

      // When removing from mini-cart etc.
      $(document.body).on('removed_from_cart', function () {
        fetchCountEndpoint();
      });
    });
  }

  function fetchCountEndpoint() {
    // Uses WooCommerce fragments? We'll call a lightweight endpoint via admin-ajax to get cart count.
    if (!window.legalWoo || !legalWoo.ajaxUrl) return;
    var url = legalWoo.ajaxUrl + '?action=pablo_get_cart_count';
    fetch(url, { credentials: 'same-origin' })
      .then(function (r) { return r.json(); })
      .then(function (data) {
        if (data && typeof data.count !== 'undefined') updateCartBadge(data.count);
      })
      .catch(function () {});
  }

  // Loop quantity: set data-quantity and href (?add-to-cart=ID&quantity=X) on click
  function bindLoopQty() {
    qsa('[data-legal-loop-item]').forEach(function (wrap) {
      var qty = qs('input[data-legal-qty]', wrap);
      var btn = qs('a[data-legal-add]', wrap);
      if (!qty || !btn) return;

      function getQty() {
        var v = parseInt(qty.value, 10);
        if (isNaN(v) || v < 1) v = 1;
        return v;
      }

      btn.addEventListener('click', function () {
        var q = getQty();
        btn.setAttribute('data-quantity', String(q));

        // If it is a link add-to-cart, ensure quantity is in URL
        var href = btn.getAttribute('href') || '';
        if (href.indexOf('add-to-cart=') !== -1) {
          // remove existing quantity= if any
          href = href.replace(/([?&])quantity=\d+/g, '$1').replace(/[?&]$/, '');
          var sep = (href.indexOf('?') === -1) ? '?' : '&';
          btn.setAttribute('href', href + sep + 'quantity=' + encodeURIComponent(String(q)));
        }
      });

      // nice: pressing enter in qty triggers button
      qty.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
          e.preventDefault();
          btn.click();
        }
      });
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    bindLoopQty();
    tryBindWooEvents();
    // Initial load count
    fetchCountEndpoint();
  });
})();
