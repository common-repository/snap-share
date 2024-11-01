<?php
/**
 * Snap Share
 * @author Blake Wilson
 * @copyright 2016 Blake Wilson, Push Labs
 * @license GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: Snap Share
 * Plugin URI: http://blakewilson.me/projects/snap-share/
 * Description: A simple sharing plugin for WordPress.
 * Author: Blake Wilson
 * Version: 1.0.0
 * Author URI: http://blakewilson.me
 * Text Domain: snap-share
 */


/**
 * Define our constants
 */
define( 'SNAPSHARE_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'SNAPSHARE_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'SNAPSHARE_BASE', plugin_basename(__FILE__) );
define( 'SNAPSHARE_VERSION', '1.0.0' );

/**
 * Load the plugin text domain for localization
 *
 * @since 1.0.0
 */
function snapshare_load_textdomain() {
  // load the text domain for localization
  load_plugin_textdomain( 'snap-share', false, SNAPSHARE_BASE . '/languages' );
}
add_action( 'plugins_loaded', 'snapshare_load_textdomain' );

/**
 * Snap Share default post types
 *
 * @since 1.0.0
 */
function snapshare_post_types() {
  // array of post types Snap Share will appear on
  $post_types = array( 'post' );

  /**
   * Use the snapshare_post_types() filter to add
   * your own post types to Snap Share
   */
  $post_types = apply_filters( 'snapshare_post_types', $post_types );

  // return the post types
  return $post_types;
}

/**
 * Enable or disable Font Awesome
 * If user's theme already has Font Awesome they can
 * disable Snap Share's version with this filter
 *
 * @since 1.0.0
 */
function snapshare_enable_fontawesome() {
  // default set to true to enable Font Awesome
  $enable_fontawesome = true;

  // Apply filter so users can turn off Font Awesome
  $enable_fontawesome = apply_filters( 'snapshare_enable_fontawesome', $enable_fontawesome );

  // return our value
  return $enable_fontawesome;
}

/**
 * Enqueue scripts and styles
 *
 * @since 1.0.0
 */
function snapshare_register_scripts() {
  // If were not on a singular post, bail
  if ( ! is_singular() ) {
    return;
  }

  wp_enqueue_style( 'snapshare-styles', SNAPSHARE_DIR_URL . 'assets/css/snapshare-styles.css', array(), SNAPSHARE_VERSION );

  // Conditionally enqueue Font Awesome based on filter
  if ( snapshare_enable_fontawesome() ) {
    wp_enqueue_style( 'snapshare-font-awesome', SNAPSHARE_DIR_URL . 'assets/css/vendor/font-awesome/css/font-awesome.min.css', array(), '4.6.3' );
  }

  wp_enqueue_script( 'snapshare-functions', SNAPSHARE_DIR_URL . 'assets/js/snapshare-functions.js', array( 'jquery' ), SNAPSHARE_VERSION );
}
add_action( 'wp_enqueue_scripts', 'snapshare_register_scripts' );

/**
 * The array of Snap Share profiles
 *
 * @since 1.0.0
 */
function snapshare_get_profiles_array() {
  global $post;

  // Get the post data
  $permalink = get_permalink( $post->ID );
  $title = get_the_title();

  // Create the $profiles array
  $profiles = array();

  // The Twitter share button
  $profiles['twitter'] = '
    <li class="snapshare-profile-button snapshare-twitter-button"><a href="http://twitter.com/share?text=' . $title . '&amp;url=' . $permalink . '"
    onclick="window.open(this.href, \'twitter-share\', \'width=550,height=235\');return false;">
      <span>' . __( 'Share on Twitter', 'snap-share' ) . '</span>
    </a></li>
  ';

  // The Facebook share button
  $profiles['facebook'] = '
    <li class="snapshare-profile-button snapshare-facebook-button"><a href="https://www.facebook.com/sharer/sharer.php?u=' . $permalink . '"
    onclick="window.open(this.href, \'facebook-share\',\'width=580,height=296\');return false;">
      <span>' . __( 'Share on Facebook', 'snap-share' ) . '</span>
    </a></li>
  ';

  // The Google Plus share button
  $profiles['gplus'] = '
    <li class="snapshare-profile-button snapshare-gplus-button"><a href="https://plus.google.com/share?url=' . $permalink . '"
    onclick="window.open(this.href, \'google-plus-share\', \'width=490,height=530\');return false;">
      <span>' . __( 'Share on Google+', 'snap-share' ) . '</span>
    </a></li>
  ';

  /**
   * Apply a filter so users can add their own profiles
   */
  $profiles = apply_filters( 'snapshare_profiles', $profiles, $permalink, $title );

  // return the $profiles array
  return $profiles;
}

/**
 * Output the Snap Share profiles
 *
 * @since 1.0.0
 */
function snapshare_get_profiles( $style = 'flat', $size = 'normal', $icon_placement = 'left', $fullwidth = true ) {

  // If not a post type, bail
  if ( ! is_singular() ) {
    return;
  }

  // Join the array together
  $profiles = join( ' ', snapshare_get_profiles_array() );

  // Build our $args array
  $args = array();

  // Add our arguments strings as classes
  $args[] = 'snapshare-style-' . $style;
  $args[] = 'snapshare-size-' . $size;
  $args[] = 'snapshare-icons-' . $icon_placement;

  // If the $fullwidth arg is true, add a class
  if ( $fullwidth === true ) {
    $args[] = 'snapshare-fullwidth';
  }

  // Join our args together as classes
  $classes = join( ' ', $args );

  // Output the Snap Share list
  $output = '';
  $output .= '<ul class="snapshare-list ' . $classes . '">';
  $output .= $profiles;
  $output .= '</ul>';

  // return the $output
  return $output;
}

/**
 * Add the Snap Share social buttons to either the bottom or top of the post
 *
 * @since 1.0.0
 */
function snapshare_output_profiles( $content ) {

  // If not in our snapshare_post_types() array, bail
  if ( ! is_singular( snapshare_post_types() ) ) {
    return $content;
  }

  // Get the options
  $options = get_option( 'snapshare_settings' );

  // Set our variables
  $before = '';
  $after = '';

  // Get the default arguments from snapshare_get_profiles()
  $style = 'flat';
  $size = 'normal';
  $icon_placement = 'left';
  $fullwidth = false;

  // Get the style option if set and exists
  if ( isset( $options['snapshare_style'] ) && ! empty( $options['snapshare_style'] ) ) {
    $style = $options['snapshare_style'];
  }

  // Get the size option if set and exists
  if ( isset( $options['snapshare_size'] ) && ! empty( $options['snapshare_size'] ) ) {
    $size = $options['snapshare_size'];
  }

  // Get the icon placement option if set and exists
  if ( isset( $options['snapshare_icon_placement'] ) && ! empty( $options['snapshare_icon_placement'] ) ) {
    $icon_placement = $options['snapshare_icon_placement'];
  }

  // Get the fullwidth option if set and exists
  if ( isset( $options['snapshare_is_fullwidth'] ) && $options['snapshare_is_fullwidth'] == 1 ) {
    $fullwidth = true;
  }

  // apply the arguments to the function
  $profiles = snapshare_get_profiles( $style, $size, $icon_placement, $fullwidth );

  // Place snapshare_get_profiles() either below or above content
  if ( ! isset( $options['snapshare_style'] ) || $options['snapshare_content_placement'] === 'after-content') {
    $after = $profiles;
  } else {
    $before = $profiles;
  }

  // Finally, throw together the $before, $content, and $after
  $full_content = $before . $content . $after;

  // return the $full_content
  return $full_content;
}
add_filter( 'the_content', 'snapshare_output_profiles' );

/**
 * Require the settings page
 */
require_once SNAPSHARE_DIR_PATH . 'inc/snapshare-settings.php';

/**
 * Require our shortcode
 */
require_once SNAPSHARE_DIR_PATH . 'inc/snapshare-shortcode.php';
