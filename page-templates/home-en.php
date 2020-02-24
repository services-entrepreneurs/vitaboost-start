<?php
/**
 * Segenvita.
 *
 * This file adds the home page template to the Segenvita Theme.
 *
 * Template Name: Home-En
 *
 * @package Segenvita
 * @author  Joseph Elsener
 * @license GPL-2.0-or-later
 * @link    https://www.studiopress.com/
 */

add_filter( 'body_class', 'segenvita_home_body_class' );
/**
 * Adds home page body class.
 *
 * @since 1.0.0
 *
 * @param array $classes Original body classes.
 * @return array Modified body classes.
 */
function segenvita_home_body_class( $classes ) {

	$classes[] = 'home-page';
	return $classes;

}

// Forces full width content layout.
add_filter( 'genesis_site_layout', '__genesis_return_full_width_content' );

add_action( 'genesis_after_header', 'genesis_add_slider' );

function genesis_add_slider() {
	echo do_shortcode('[rev_slider alias="slider-6-1"]');
}

// Runs the Genesis loop.
genesis();
