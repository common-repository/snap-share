/**
 * Our jQuery wrapper to avoid conflicts
 */
jQuery( function($) {
  /**
   * Find each .snapshare-fullwidth and apply styles
   */
  ( function() {
    $( '.snapshare-list.snapshare-fullwidth' ).each( function() {
      // Find the <li> element in our list
      var $liEl = $( this ).find( 'li' );
      // Get the amount of <li> elements in our list
      var $count = $liEl.length;

      /**
       * Divide the total amount of <li> elements
       * by 100 to get a percentage for each
       * <li> element
       */
      var $liWidth = 100 / $count;

      // add the width to css
      $liEl.css({
        'width' : $liWidth + '%',
      });

      /**
       * Finally, once the styles have been applied
       * add a visiblity visible css property to show the
       * buttons. Visiblity hidden is set by default on fullwidth
       * because it js css loads slower than stylesheets, causing
       * jittering on first load
       */
      $( this ).css( 'visibility', 'visible' );
    });
  } )();
});
