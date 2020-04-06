<?php
/**
 * Segenvita.
 *
 * This file adds the required WooCommerce setup functions to the Segenvita Theme.
 *
 * @package Segenvita
 * @author  Services-Entrepreneurs
 * @license GPL-2.0-or-later
 * @link    https://www.services-entrepreneurs.ch/
 */

// Adds product gallery support.
if ( class_exists( 'WooCommerce' ) ) {

	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
	add_theme_support( 'wc-product-gallery-zoom' );

}

add_filter( 'woocommerce_style_smallscreen_breakpoint', 'segenvita_woocommerce_breakpoint' );
/**
 * Modifies the WooCommerce breakpoints.
 *
 * @since 2.3.0
 *
 * @return string Pixel width of the theme's breakpoint.
 */
function segenvita_woocommerce_breakpoint() {

	$current = genesis_site_layout( false );
	$layouts = [
		'one-sidebar' => [
			'content-sidebar',
			'sidebar-content',
		],
	];

	if ( in_array( $current, $layouts['one-sidebar'], true ) ) {
		return '1200px';
	}

	return '860px';

}

add_filter( 'genesiswooc_products_per_page', 'segenvita_default_products_per_page' );
/**
 * Sets the default products per page.
 *
 * @since 2.3.0
 *
 * @return int Number of products to show per page.
 */
function segenvita_default_products_per_page() {

	return 8;

}

add_filter( 'woocommerce_pagination_args', 'segenvita_woocommerce_pagination' );
/**
 * Updates the next and previous arrows to the default Genesis style.
 *
 * @param array $args The previous and next text arguments.
 * @since 2.3.0
 *
 * @return array New next and previous text arguments.
 */
function segenvita_woocommerce_pagination( $args ) {

	$args['prev_text'] = sprintf( '&laquo; %s', __( 'Previous Page', 'segenvita' ) );
	$args['next_text'] = sprintf( '%s &raquo;', __( 'Next Page', 'segenvita' ) );

	return $args;

}

add_action( 'after_switch_theme', 'segenvita_woocommerce_image_dimensions_after_theme_setup', 1 );
/**
 * Defines WooCommerce image sizes on theme activation.
 *
 * @since 2.3.0
 */
function segenvita_woocommerce_image_dimensions_after_theme_setup() {

	global $pagenow;

	// Checks conditionally to see if we're activating the current theme and that WooCommerce is installed.
	if ( ! isset( $_GET['activated'] ) || 'themes.php' !== $pagenow || ! class_exists( 'WooCommerce' ) ) { // phpcs:ignore WordPress.CSRF.NonceVerification.NoNonceVerification -- low risk, follows official snippet at https://goo.gl/nnHHQa.
		return;
	}

	segenvita_update_woocommerce_image_dimensions();

}

add_action( 'activated_plugin', 'segenvita_woocommerce_image_dimensions_after_woo_activation', 10, 2 );
/**
 * Defines the WooCommerce image sizes on WooCommerce activation.
 *
 * @since 2.3.0
 *
 * @param string $plugin The path of the plugin being activated.
 */
function segenvita_woocommerce_image_dimensions_after_woo_activation( $plugin ) {

	// Checks to see if WooCommerce is being activated.
	if ( 'woocommerce/woocommerce.php' !== $plugin ) {
		return;
	}

	segenvita_update_woocommerce_image_dimensions();

}

/**
 * Updates WooCommerce image dimensions.
 *
 * @since 2.3.0
 */
function segenvita_update_woocommerce_image_dimensions() {

	// Updates image size options.
	update_option( 'woocommerce_single_image_width', 655 );    // Single product image.
	update_option( 'woocommerce_thumbnail_image_width', 500 ); // Catalog image.

	// Updates image cropping option.
	update_option( 'woocommerce_thumbnail_cropping', '1:1' );

}

add_filter( 'woocommerce_get_image_size_gallery_thumbnail', 'segenvita_gallery_image_thumbnail' );
/**
 * Filters the WooCommerce gallery image dimensions.
 *
 * @since 2.6.0
 *
 * @param array $size The gallery image size and crop arguments.
 * @return array The modified gallery image size and crop arguments.
 */
function segenvita_gallery_image_thumbnail( $size ) {

	$size = [
		'width'  => 180,
		'height' => 180,
		'crop'   => 1,
	];

	return $size;

}
