// author: pablo rotem
(function($){
  // דסקטופ: hover פותח dropdown (כמו אצלך), מובייל: click בתוך collapse
  function isMobile(){
    return window.matchMedia('(max-width: 767px)').matches;
  }

  $(document).on('mouseenter', '.navbar .dropdown', function(){
    if (!isMobile()) {
      $(this).addClass('open');
    }
  }).on('mouseleave', '.navbar .dropdown', function(){
    if (!isMobile()) {
      $(this).removeClass('open');
    }
  });

  // במובייל: קליק על פריט עם ילדים יפתח/יסגור את ה־dropdown-content
  $(document).on('click', '.navbar .dropdown > a', function(e){
    if (isMobile()) {
      e.preventDefault();
      var $li = $(this).closest('.dropdown');
      $li.toggleClass('open');
    }
  });

})(jQuery);
