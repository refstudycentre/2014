/**
 * @file
 * Javascript which loads on the front page.
 */
(function ($, Drupal, window, document, undefined) {

Drupal.behaviors.rsc2014_front = {
  attach: function(context, settings) {
    // remove the "display: none" set by css, to display these blocks only on browsers with js:
    $('#block-views-rsc-slider-view-block').slideDown();
    
    var respond = function(){
      
      // js for flexslider classes
      $('.flexslider .overlay-text .fs-vcenter').each(function() {
        var $e = $(this);
        var m = ($('.flexslider li.flex-active-slide img').outerHeight() - $e.outerHeight()) / 2;
        $e.css('margin-top',m);
      });
      $('.flexslider .overlay-text .fs-bottom').each(function() {
        var $e = $(this);
        var m = ($('.flexslider li.flex-active-slide img').outerHeight() - $e.outerHeight() - parseInt($('.flexslider .overlay-text > div').css('margin-bottom')));
        $e.css('margin-top',m);
      });
      
    };
    
    $(window).load(respond);   // after document ready
    $(window).resize(respond); // on window resize
    
  }
};


})(jQuery, Drupal, this, this.document);
