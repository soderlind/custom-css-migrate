<?php
/**
 * Plugin Name: Migrate custom CSS to WP Customizer Additional CSS
 * Version: 0.1
 * Plugin URI:
 * Author: Per Soderlind
 * Author URI: https://soderlind.no
 */

/**
 * Custom CSS settings, check the plugin / theme code to find the values
 * @var array
 */
$ps_custom_css_data_array = array(
	/* Simple Custom CSS, https://wordpress.org/plugins/simple-custom-css/ */
	array(
		'type'    => 'option', // any other value will be considered as theme_mod
		'name'    => 'sccss_settings',
		'setting' => 'sccss-content',
		'strip'   => '/* Enter Your Custom CSS Here */',
	),
	/* home grown custom css plugin */
	array(
		'type'    => 'option',
		'name'    => 'dss_super',
		'setting' => 'css',
		'strip'   => '',
	),
);

/**
 * Do the migration
 *
 * @see https://make.wordpress.org/core/2016/11/26/extending-the-custom-css-editor/
 * @author soderlind
 * @version 0.1
 */
function ps_custom_css_migrate() {
	global $ps_custom_css_data_array;
	if ( function_exists( 'wp_update_custom_css_post' ) && false !== ps_allow_css_migrate() ) {
		$custom_css = get_theme_mod( 'theme_slug_custom_css' );

		foreach ( $ps_custom_css_data_array as $custom_css_data ) {
			$custom_css = ps_get_custom_css( $custom_css_data );
			if ( $custom_css ) {
				$core_css = wp_get_custom_css(); // Preserve any CSS already added to the core option.
				$return = wp_update_custom_css_post( $core_css . "\n" . $custom_css );
				if ( $return && ! is_wp_error( $return ) ) {
					// Remove the old setting, so that the CSS is stored in only one place moving forward.
					if ( 'option' == $custom_css_data['type'] ) {
						delete_option( $custom_css_data['name'] );
					} else {
						remove_theme_mod( $custom_css_data['name'] );
					}
				}
			}
		}
	}
}

/**
 * Get the custom CSS from option / theme mod
 * @author soderlind
 * @version 0.1
 * @param   array    $custom_css_data Setting for the current custom CSS
 * @return  string                    CSS
 */
function ps_get_custom_css( $custom_css_data ) {
	if ( 'option' == $custom_css_data['type'] ) {

		$options = get_option( $custom_css_data['name'] );

		if ( '' === $custom_css_data['setting'] ) {
			$raw_data = ( isset( $options ) ) ? $options : '';
		} elseif ( isset( $options[ $custom_css_data['setting'] ] ) ) {
			$raw_data = $options[ $custom_css_data['setting'] ];
		} else {
			return;
		}
		$raw_content = isset( $raw_data ) ? $raw_data : '';
		if ( '' !== $custom_css_data['strip'] ) {
			$raw_content = str_replace( $custom_css_data['strip'], '', $raw_content );
		}
		$css         = wp_kses( $raw_content, array( '\'', '\"' ) );
		$css         = str_replace( '&gt;', '>', $css );
		return $css;
	} else {
		return get_theme_mod( $custom_css_data['name'] );
	}
}

/**
 * Check if you are allowed to migrate CSS
 * @author soderlind
 * @version 0.1
 * @return  boolean    true if you are allow to migrate the CSS, otherwise false
 */
function ps_allow_css_migrate() {
	// Autosave, do nothing
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return false;
	}
	// AJAX? Not used here
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return false;
	}
	// Check user permissions
	if ( ! current_user_can( 'manage_updates' ) ) {
			return false;
	}
	// Return if it's a post revision
	// if ( false !== wp_is_post_revision( $post_id ) ) {
			// return $post_id; }
	//  a newly created post, with no content
	// if ( 'auto-draft' == get_post_status( $post_id ) ) {
			// return $post_id; }
	if ( isset( $_GET['action'] ) && 'trash' == strtolower( $_GET['action'] ) ) {
			return false;
	}

	return true;
}

add_action( 'init', 'ps_custom_css_migrate' );
