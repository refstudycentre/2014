/**
 * @file
 * Javascript for Preaching Library
 */

(function ($, Drupal, window, document, undefined) {

Drupal.behaviors.rsc2014_pl = {
  attach: function(context, settings) {
    
    $left=$(".page-pl .panel-flexible-inside>div:nth-child(1)");
    $middle=$(".page-pl .panel-flexible-inside>div:nth-child(2)");
    
    var respond = function(){
      // check if the css has reacted to the media query:
      if(parseInt($middle.css("marginLeft"),10) == 0) {
        // move the left div below the middle div on the front page
        $middle.insertBefore($left);
      } else {
        // move the middle div below the left div on the front page
        $left.insertBefore($middle);
      }
    };
    
    $(window).load(respond);   // after document ready
    $(window).resize(respond); // on window resize
    
  }
};


})(jQuery, Drupal, this, this.document);
