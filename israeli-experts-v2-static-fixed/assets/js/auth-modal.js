/* assets/js/auth-modal.js */
(function($){
  function showMsg($el, type, text){
    $el
      .removeClass('alert-success alert-danger')
      .addClass('alert alert-' + type)
      .text(text)
      .show();
  }

  $(document).on('submit', '#form-login', function(e){
    e.preventDefault();
    var $form = $(this), $msg = $('#login-msg');
    $.post(IEXP_AUTH.ajaxurl, $form.serialize(), function(res){
      if(res && res.success){
        showMsg($msg, 'success', res.data && res.data.message ? res.data.message : 'התחברת בהצלחה.');
        if(res.data && res.data.redirect){
          window.location.href = res.data.redirect;
        }else{
          window.location.reload();
        }
      }else{
        var err = (res && res.data && res.data.message) ? res.data.message : 'שגיאה בהתחברות.';
        showMsg($msg, 'danger', err);
      }
    });
  });

  $(document).on('submit', '#form-register', function(e){
    e.preventDefault();
    var $form = $(this), $msg = $('#register-msg');
    $.post(IEXP_AUTH.ajaxurl, $form.serialize(), function(res){
      if(res && res.success){
        showMsg($msg, 'success', res.data && res.data.message ? res.data.message : 'נרשמת בהצלחה. מחברים אותך...');
        if(res.data && res.data.redirect){
          window.location.href = res.data.redirect;
        }else{
          window.location.reload();
        }
      }else{
        var err = (res && res.data && res.data.message) ? res.data.message : 'שגיאה בהרשמה.';
        showMsg($msg, 'danger', err);
      }
    });
  });
})(jQuery);
