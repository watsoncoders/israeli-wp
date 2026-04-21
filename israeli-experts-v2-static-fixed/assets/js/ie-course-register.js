(function($){
  'use strict';

  // =========== Birthdate composer ===========
  function setBirthDate(){
    var y = $('#birthYear').val() || '';
    var m = $('#birthMonth').val() || '';
    var d = $('#birthDay').val() || '';
    if (y && m && d) {
      $('#birthDate').val( y + '-' + m.padStart(2,'0') + '-' + d.padStart(2,'0') );
    } else {
      $('#birthDate').val('');
    }
  }
  $(document).on('change', '#birthYear,#birthMonth,#birthDay', setBirthDate);
  setBirthDate();

  // =========== Category chips & catIds ===========
  var selectedCats = new Map(); // id -> name

  // public API used by legacy onclick in the tree
  window.mygetvaluetodicv = function(name, id){
    id = parseInt(id,10);
    if (!id || selectedCats.has(id)) return;
    selectedCats.set(id, name);

    var $wrap = $('#selected-categories');
    if (!$wrap.length) {
      $wrap = $('<div id="selected-categories" class="ie-chip-wrap"></div>').insertBefore('#categories-tree');
    }

    var $chip = $('<span class="ie-chip" data-id="'+id+'">'+
                   $('<div>').text(name).html()+
                   '<button type="button" class="ie-chip-x" aria-label="הסר">&times;</button>'+
                  '</span>');
    $wrap.append($chip);
    syncCatIds();
  };

  function syncCatIds(){
    var ids = Array.from(selectedCats.keys());
    $('#catIds').val(ids.join(','));
  }

  $(document).on('click', '.ie-chip-x', function(){
    var $chip = $(this).closest('.ie-chip');
    var id = parseInt($chip.data('id'), 10);
    if (selectedCats.has(id)) {
      selectedCats.delete(id);
      $chip.remove();
      syncCatIds();
    }
  });

  // =========== Load categories via AJAX ===========
  function loadCategories(){
    var $tree = $('#categories-tree');
    if (!$tree.length) return;

    $tree.html('<div class="ie-tree-loading">טוען קטגוריות…</div>');
    $.post(ieForm.ajaxUrl, {
      action: 'ie_load_categories',
      nonce:  ieForm.nonce
    }).done(function(resp){
      if (resp && resp.success && resp.data && resp.data.html) {
        $tree.html(resp.data.html);
      } else {
        var msg = (resp && resp.data && resp.data.message) ? resp.data.message : 'שגיאה בטעינת קטגוריות.';
        $tree.html('<div class="alert alert-warning">'+ msg +'</div>');
      }
    }).fail(function(){
      $tree.html('<div class="alert alert-danger">לא ניתן לטעון קטגוריות (AJAX).</div>');
    });
  }
  loadCategories();

  // =========== On submit, ensure birthDate is set ===========
  $(document).on('submit', '#registerCourseForm', function(){
    setBirthDate();
  });

  // =========== Mobile navbar toggle (moved from inline) ===========
  $(document).on('click', '.navbar-toggle', function(){
    $('#bs-example-navbar-collapse-1').toggleClass('collapseTAp');
  });

  // =========== Match legacy tab link behavior, optional ===========
  $(function(){
    var url = document.location.toString();
    if (url.match('#')) {
      $('.nav-tabs a[href="#' + url.split('#')[1] + '"]').tab('show');
    }
    $('.nav-tabs a, a[href="#home"]').on('shown.bs.tab', function () {
      var $t = $($(this).attr('href'));
      if ($t.length) {
        $('html, body').animate({scrollTop: $t.offset().top - 80}, 500, 'linear');
      }
    });
  });

})(jQuery);
