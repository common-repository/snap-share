<?php
/**
 * Create our shortcode
 *
 * @since 1.0.0
 */
function snapshare_shortcode( $atts ) {

	// Our Attributes
	$atts = extract( shortcode_atts(
		array(
			'style' => 'flat',
			'size' => 'normal',
			'icon_placement' => 'left',
      'fullwidth' => 'false',
		),
		$atts
	) );

  /**
   * get the $fullwidth attribute and convert
   * the string to boolean
   */
  $is_fullwidth = false;
  if ( $fullwidth === 'true' ) {
    $is_fullwidth = true;
  }

  /**
   * Create our $output variable with our
   * snapshare_get_profiles() function and args
   */
  $output = snapshare_get_profiles( $style, $size, $icon_placement, $is_fullwidth );

  // return the $output
  return $output;
}
add_shortcode( 'snapshare', 'snapshare_shortcode' );
