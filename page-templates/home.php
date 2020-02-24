<?php
/**
 * Segenvita.
 *
 * This file adds the home page template to the Segenvita Theme.
 *
 * Template Name: Home
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
	echo do_shortcode('[rev_slider alias="slider-6"]');
}

add_action( 'genesis_after_content', 'segenvita_add_testimonials' );

function segenvita_add_testimonials() {
	 echo '<div class="scene" id="testimonials">';
        echo '<div class="indent clear">';
          // <?php 
          $args = array(
            'posts_per_page' => 3,
            'orderby' => 'rand',
            'category_name' => 'temoignages'
          );
                        
          $query = new WP_Query( $args );
          // The Loop
          if ( $query->have_posts() ) {
            echo '<ul class="testimonials">';
            while ( $query->have_posts() ) {
              $query->the_post();
              $more = 0;
              echo '<li class="clear">';
              echo '<figure class="testimonial-thumb">';
              the_post_thumbnail('testimonial-mug');
              echo '</figure>';
              echo '<aside class="testimonial-text">';
              echo '<h3 class="testimonial-name">' . get_the_title() . '</h3>';
              echo '<div class="testimonial-excerpt">';
              the_content('');
              echo '</div>';
              echo '</aside>';
              echo '</li>';
            }
            echo '</ul>';
          }

          /* Restore original Post Data */
          wp_reset_postdata();
        echo '</div><!-- .indent -->';      
  	echo '</div><!-- testimonials -->';
}

// Runs the Genesis loop.
genesis();
