(function () {
  var toggle = document.querySelector('.navbar-toggle[data-target="#primary-nav"]');
  var nav = document.getElementById('primary-nav');
  if (!toggle || !nav) return;

  toggle.addEventListener('click', function (e) {
    e.preventDefault();
    // mimic Bootstrap 3's collapse behavior
    if (nav.classList.contains('in')) {
      nav.classList.remove('in');
      nav.setAttribute('aria-expanded', 'false');
      // ensure hidden state if some CSS fights it
      nav.style.display = 'none';
      nav.style.height = '0px';
      nav.style.overflow = 'hidden';
    } else {
      nav.classList.add('in');
      nav.setAttribute('aria-expanded', 'true');
      // ensure visible state even if global .collapse says otherwise
      nav.style.display = 'block';
      nav.style.height = 'auto';
      nav.style.overflow = 'visible';
    }
  });
})();
