<?php
/**
 * Segenvita.
 *
 * This file adds functions to the Segenvita Theme.
 *
 * @package Segenvita
 * @author  Services-Entrepreneurs
 * @license GPL-2.0-or-later
 * @link    https://www.services-entrepreneurs.ch/
 */

// Starts the engine.
require_once get_template_directory() . '/lib/init.php';

// Sets up the Theme.
require_once get_stylesheet_directory() . '/lib/theme-defaults.php';

add_action( 'after_setup_theme', 'segenvita_localization_setup' );
/**
 * Sets localization (do not remove).
 *
 * @since 1.0.0
 */
function segenvita_localization_setup() {

	load_child_theme_textdomain( genesis_get_theme_handle(), get_stylesheet_directory() . '/languages' );

}

// Adds helper functions.
require_once get_stylesheet_directory() . '/lib/helper-functions.php';

// Adds image upload and color select to Customizer.
require_once get_stylesheet_directory() . '/lib/customize.php';

// Includes Customizer CSS.
require_once get_stylesheet_directory() . '/lib/output.php';

// Adds specific footer.
require_once get_stylesheet_directory() . '/lib/site-footer.php';

// Adds WooCommerce support.
require_once get_stylesheet_directory() . '/lib/woocommerce/woocommerce-setup.php';

// Adds the required WooCommerce styles and Customizer CSS.
require_once get_stylesheet_directory() . '/lib/woocommerce/woocommerce-output.php';

// Adds the Genesis Connect WooCommerce notice.
require_once get_stylesheet_directory() . '/lib/woocommerce/woocommerce-notice.php';

add_action( 'after_setup_theme', 'genesis_child_gutenberg_support' );
/**
 * Adds Gutenberg opt-in features and styling.
 *
 * @since 2.7.0
 */
function genesis_child_gutenberg_support() { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- using same in all child themes to allow action to be unhooked.
	require_once get_stylesheet_directory() . '/lib/gutenberg/init.php';
}

// Registers the responsive menus.
if ( function_exists( 'genesis_register_responsive_menus' ) ) {
	genesis_register_responsive_menus( genesis_get_config( 'responsive-menus' ) );
}

add_action( 'wp_enqueue_scripts', 'segenvita_enqueue_scripts_styles' );
/**
 * Enqueues scripts and styles.
 *
 * @since 1.0.0
 */
function segenvita_enqueue_scripts_styles() {

	$appearance = genesis_get_config( 'appearance' );

	wp_enqueue_style(
		genesis_get_theme_handle() . '-fonts',
		$appearance['fonts-url'],
		[],
		genesis_get_theme_version()
	);

	wp_enqueue_style( 'dashicons' );

	if ( genesis_is_amp() ) {
		wp_enqueue_style(
			genesis_get_theme_handle() . '-amp',
			get_stylesheet_directory_uri() . '/lib/amp/amp.css',
			[ genesis_get_theme_handle() ],
			genesis_get_theme_version()
		);
	}

}

add_action( 'after_setup_theme', 'segenvita_theme_support', 9 );
/**
 * Add desired theme supports.
 *
 * See config file at `config/theme-supports.php`.
 *
 * @since 3.0.0
 */
function segenvita_theme_support() {

	$theme_supports = genesis_get_config( 'theme-supports' );

	foreach ( $theme_supports as $feature => $args ) {
		add_theme_support( $feature, $args );
	}

}

add_action( 'after_setup_theme', 'segenvita_post_type_support', 9 );
/**
 * Add desired post type supports.
 *
 * See config file at `config/post-type-supports.php`.
 *
 * @since 3.0.0
 */
function segenvita_post_type_support() {

	$post_type_supports = genesis_get_config( 'post-type-supports' );

	foreach ( $post_type_supports as $post_type => $args ) {
		add_post_type_support( $post_type, $args );
	}

}

// Adds image sizes.
add_image_size( 'sidebar-featured', 75, 75, true );
add_image_size( 'genesis-singular-images', 702, 526, true );

// Removes header right widget area.
unregister_sidebar( 'header-right' );

// Removes secondary sidebar.
unregister_sidebar( 'sidebar-alt' );

// Removes site layouts.
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-content-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );

// Repositions primary navigation menu.
// remove_action( 'genesis_after_header', 'genesis_do_nav' );
// add_action( 'genesis_header', 'genesis_do_nav', 12 );

// Repositions the secondary navigation menu.
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'genesis_header_right', 'genesis_do_subnav', 10 );

add_filter( 'wp_nav_menu_args', 'segenvita_secondary_menu_args' );
/**
 * Reduces secondary navigation menu to one level depth.
 *
 * @since 2.2.3
 *
 * @param array $args Original menu options.
 * @return array Menu options with depth set to 1.
 */
function segenvita_secondary_menu_args( $args ) {

	if ( 'secondary' === $args['theme_location'] ) {
		$args['depth'] = 1;
	}

	return $args;

}

// Position the language menu
add_action( 'genesis_header_right', 'segenvita_do_langnav', 10 );

/**
 * Echo the "Tertiary Navigation" menu.
 *
 * Applies the `segenvita_do_langnav` filter.
 *
 * @since 1.0.0
 */
function segenvita_do_langnav() {

	// Do nothing if menu not supported.
	if ( ! genesis_nav_menu_supported( 'tertiary' ) ) {
		return;
	}

	$class = 'menu genesis-nav-menu menu-tertiary';
	if ( genesis_superfish_enabled() ) {
		$class .= ' js-superfish';
	}

	genesis_nav_menu(
		[
			'theme_location' => 'tertiary',
			'menu_class'     => $class,
		]
	);

}

/* Search menu
---------------------------------------------- */
// Position the search menu
add_action( 'genesis_header_right', 'segenvita_do_search', 50 );

/**
 * Echo the "Quaternary Navigation" menu.
 *
 * Applies the `segenvita_do_search` filter.
 *
 * @since 1.0.0
 */
function segenvita_do_search() {

	// Do nothing if menu not supported.
	if ( ! genesis_nav_menu_supported( 'quaternary' ) ) {
		return;
	}

	$class = 'menu genesis-nav-menu menu-quaternary';
	if ( genesis_superfish_enabled() ) {
		$class .= ' js-superfish';
	}

	genesis_nav_menu(
		[
			'theme_location' => 'quaternary',
			'menu_class'     => $class,
		]
	);

}

/* Search Form in menu
---------------------------------------------- */
add_filter( 'wp_nav_menu_items', 'custom_menu_extras', 10, 2 );
/**
 * Filter menu items, appending a a search icon at the end.
 *
 * @param string   $menu HTML string of list items.
 * @param stdClass $args Menu arguments.
 *
 * @return string Amended HTML string of list items.
 */
function custom_menu_extras( $menu, $args ) {

	if ( 'quaternary' !== $args->theme_location ) {
		return $menu;
	}

	$menu .= '<li class="menu-item">' . get_search_form( false ) . '</li>';

	return $menu;

}

add_filter( 'genesis_markup_search-form-submit_open', 'custom_search_form_submit' );
/**
 * Change Search Form submit button markup.
 *
 * @return string Modified HTML for search forms' submit button.
 */
function custom_search_form_submit() {

	$search_button_text = apply_filters( 'genesis_search_button_text', esc_attr__( 'Search', 'genesis' ) );

	$searchicon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="search-icon"><path d="M505 442.7L405.3 343c-4.5-4.5-10.6-7-17-7H372c27.6-35.3 44-79.7 44-128C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c48.3 0 92.7-16.4 128-44v16.3c0 6.4 2.5 12.5 7 17l99.7 99.7c9.4 9.4 24.6 9.4 33.9 0l28.3-28.3c9.4-9.4 9.4-24.6.1-34zM208 336c-70.7 0-128-57.2-128-128 0-70.7 57.2-128 128-128 70.7 0 128 57.2 128 128 0 70.7-57.2 128-128 128z"></path></svg>';

	return sprintf( '<button type="submit" class="search-form-submit" aria-label="Search">%s<span class="screen-reader-text">%s</span></button>', $searchicon, $search_button_text );

}

add_filter( 'genesis_author_box_gravatar_size', 'segenvita_author_box_gravatar' );
/**
 * Modifies size of the Gravatar in the author box.
 *
 * @since 2.2.3
 *
 * @param int $size Original icon size.
 * @return int Modified icon size.
 */
function segenvita_author_box_gravatar( $size ) {

	return 90;

}

add_filter( 'genesis_comment_list_args', 'segenvita_comments_gravatar' );
/**
 * Modifies size of the Gravatar in the entry comments.
 *
 * @since 2.2.3
 *
 * @param array $args Gravatar settings.
 * @return array Gravatar settings with modified size.
 */
function segenvita_comments_gravatar( $args ) {

	$args['avatar_size'] = 60;
	return $args;

}

/**
 * Search toggle
 *
 */
function ea_search_toggle() {
	$output = '<button' . ea_amp_class( 'search-toggle', 'active', 'searchActive' ) . ea_amp_toggle( 'searchActive', array( 'menuActive', 'mobileFollow' ) ) . '>';
		$output .= ea_icon( array( 'icon' => 'search', 'size' => 24, 'class' => 'open' ) );
		$output .= ea_icon( array( 'icon' => 'close', 'size' => 24, 'class' => 'close' ) );
		$output .= '<span class="screen-reader-text">Search</span>';
	$output .= '</button>';
	return $output;
}

