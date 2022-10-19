jQuery(document).ready(function($) {
  var $hamburger = $('.hamburger');

  $hamburger.on('click', function(e) {
    $hamburger.toggleClass('is-active');

    // Do something else, like open/close menu
  });

  var myLazyLoad = new LazyLoad({
    elements_selector: '.lazy'
  });

  $(function() {
    $('#datepicker').datepicker({ dateFormat: 'dd/mm/yy' });
  });
});
