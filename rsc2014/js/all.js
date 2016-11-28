/**
 * @file
 * Javascript which loads on all pages
 */

// JavaScript should be made compatible with libraries other than jQuery by
// wrapping it with an "anonymous closure". See:
// - https://drupal.org/node/1446420
// - http://www.adequatelygood.com/2010/3/JavaScript-Module-Pattern-In-Depth
(function ($, Drupal, window, document, undefined) {


// To understand behaviors, see https://drupal.org/node/756722#behaviors
Drupal.behaviors.rsc2014_all = {
  attach: function(context, settings) {
    
    // stop addtoany from misbehaving and messing up vertical rhythm:
    $('.a2a_kit_size_32').removeClass();
    
    var respond = function(){
      
      // maximise the width of the audio element
      $("div.rsc-attachment-formatter-audio > audio").width($("div.rsc-attachment-formatter-audio").width() - $("div.rsc-attachment-formatter-audio > a").width() - 50);

      // maximise the width of the search bar on some pages
      $(".front, .page-search").find("#search-block-form input.form-text").width($("#search-block-form").width() - $("#search-block-form #edit-submit").width() - 50);

      var lineheight = 22; // hard not to hardcode

      // make the height of sidebar images a multiple of the line height by cropping it slightly using the surrounding a tag's height property
      var a = $(".block-rsc-library article").find("span.field-name-rscl-featured-image").find("a");
      $.each(a,function(){
        var img = $(this).find("img").first();
        var lines = (img.height() / lineheight >> 0); // integer division
        $(this).height(lines * lineheight);
      });

      // Preserve vertical rhythm for video elements by modifying the surrounding a div's height property
      var videos = $(".rsc-attachment-formatter-video > video");
      $.each(videos,function(){
        var video = $(this);
        var div = video.parent();
        var lines = (video.height() / lineheight >> 0); // integer division
        div.height((lines + 3) * lineheight); // +1 is to add space instead of cropping. +2 for the download button
      });

    };
    
    $(window).load(respond);   // after document ready
    $(window).resize(respond); // on window resize
    
  }
};


})(jQuery, Drupal, this, this.document);
