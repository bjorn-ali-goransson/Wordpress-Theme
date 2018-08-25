var $ = jQuery;



/* RESPONSIVE BACKGROUND IMAGE */

$(function () {
  $('[responsive-background-image]').each(function () {
    var element = $(this);
    var image = element.children('.responsive-background-image');

    if (!image.length) {
      return;
    }

    var src = null;
    var update = throttle(function () {
      var newSrc = image[0].currentSrc ? image[0].currentSrc : image[0].src;

      if (src != newSrc) {
        src = newSrc;
        element.css({ backgroundImage: 'url("' + src + '")' });
      }
    }, 100);

    image.load(update);
    setTimeout(update, 1000);
    $(window).resize(update);
  });
});



/* THROTTLE */

function throttle(func, delay) {
  var timer = null;

  return function () {
    var context = this,
      args = arguments;

    if (timer === null) {
      timer = setTimeout(function () {
        func.apply(context, args);
        timer = null;
      }, delay);
    }
  };
}