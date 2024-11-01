<?php
add_action( 'admin_menu', 'snapshare_add_admin_menu' );
add_action( 'admin_init', 'snapshare_settings_init' );

/**
 * Add the options page
 *
 * @since 1.0.0
 */
function snapshare_add_admin_menu() {
	add_options_page( 'Snap Share', 'Snap Share', 'manage_options', 'snap_share', 'snapshare_options_page' );
}

/**
 * Add the settings section and settings fields
 *
 * @since 1.0.0
 */
function snapshare_settings_init(  ) {
	register_setting( 'pluginPage', 'snapshare_settings' );

	add_settings_section(
		'snapshare_pluginPage_section',
		__( 'Settings', 'snap-share' ),
		'snapshare_settings_section_callback',
		'pluginPage'
	);

  add_settings_field(
		'snapshare_content_placement',
		__( 'Snap Share Placement', 'snap-share' ),
		'snapshare_content_placement_render',
		'pluginPage',
		'snapshare_pluginPage_section'
	);

	add_settings_field(
		'snapshare_style',
		__( 'Button Style', 'snap-share' ),
		'snapshare_style_render',
		'pluginPage',
		'snapshare_pluginPage_section'
	);

	add_settings_field(
		'snapshare_size',
		__( 'Button Size', 'snap-share' ),
		'snapshare_size_render',
		'pluginPage',
		'snapshare_pluginPage_section'
	);

	add_settings_field(
		'snapshare_icon_placement',
		__( 'Icon Placement', 'snap-share' ),
		'snapshare_icon_placement_render',
		'pluginPage',
		'snapshare_pluginPage_section'
	);

	add_settings_field(
		'snapshare_is_fullwidth',
		__( 'Make buttons stretch fullwidth?', 'snap-share' ),
		'snapshare_is_fullwidth_render',
		'pluginPage',
		'snapshare_pluginPage_section'
	);
}

/**
 * The content placement field
 *
 * @since 1.0.0
 */
function snapshare_content_placement_render() {
	$options = get_option( 'snapshare_settings' );
	?>
	<select name='snapshare_settings[snapshare_content_placement]'>
    <option value='after-content' <?php selected( $options['snapshare_content_placement'], 'after-content' ); ?>><?php _e( 'After Content', 'snap-share' ); ?></option>
		<option value='before-content' <?php selected( $options['snapshare_content_placement'], 'before-content' ); ?>><?php _e( 'Before Content', 'snap-share' ); ?></option>
	</select>
  <?php
}

/**
 * The style field
 *
 * @since 1.0.0
 */
function snapshare_style_render() {
	$options = get_option( 'snapshare_settings' );
	?>
	<select name='snapshare_settings[snapshare_style]'>
		<option value='flat' <?php selected( $options['snapshare_style'], 'flat' ); ?>><?php _e( 'Flat', 'snap-share' ); ?></option>
		<option value='inverse' <?php selected( $options['snapshare_style'], 'inverse' ); ?>><?php _e( 'Inverse', 'snap-share' ); ?></option>
	</select>
  <?php
}

/**
 * The size field
 *
 * @since 1.0.0
 */
function snapshare_size_render() {
	$options = get_option( 'snapshare_settings' );
	?>
	<select name='snapshare_settings[snapshare_size]'>
		<option value='normal' <?php selected( $options['snapshare_size'], 'normal' ); ?>><?php _e( 'Normal', 'snap-share' ); ?></option>
		<option value='small' <?php selected( $options['snapshare_size'], 'small' ); ?>><?php _e( 'Small', 'snap-share' ); ?></option>
	</select>
  <?php
}

/**
 * The icon placement field
 *
 * @since 1.0.0
 */
function snapshare_icon_placement_render() {
	$options = get_option( 'snapshare_settings' );
	?>
	<select name='snapshare_settings[snapshare_icon_placement]'>
		<option value='left' <?php selected( $options['snapshare_icon_placement'], 'left' ); ?>><?php _e( 'Left', 'snap-share' ); ?></option>
		<option value='center' <?php selected( $options['snapshare_icon_placement'], 'center' ); ?>><?php _e( 'Center', 'snap-share' ); ?></option>
	</select>
  <?php
}

/**
 * The fullwidth field
 *
 * @since 1.0.0
 */
function snapshare_is_fullwidth_render() {
	$options = get_option( 'snapshare_settings' );
	?>
	<input type='checkbox' name='snapshare_settings[snapshare_is_fullwidth]' <?php checked( isset( $options['snapshare_is_fullwidth'] ), 1 ); ?> value='1'>
	<?php
}

/**
 * The settings section callback
 *
 * @since 1.0.0
 */
function snapshare_settings_section_callback() {
	// echo __( 'This section description', 'snap-share' );
}

/**
 * The options page output
 *
 * @since 1.0.0
 */
function snapshare_options_page() {
	?>
	<form action='options.php' method='post'>
		<h2>Snap Share</h2>
		<?php
		settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
		submit_button();
		?>
	</form>
	<?php
}
