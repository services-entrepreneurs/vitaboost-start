<?php
/**
 * Site Footer
 *
 * @package Segenvita
 * @author  Services-Entrepreneurs
 * @license GPL-2.0-or-later
 * @link    https://www.services-entrepreneurs.ch/
**/

/**
 * Site Footer
 *
 */
function segenvita_site_footer() {
	echo '<div class="footer-container">';
		echo '<div class="footer-left">';
			echo '<p>' . esc_attr__( 'Contact information:', 'segenvita' ) . '</p>';
			echo '<p>VitaBoost SA</p>';
			echo '<p>Chemin d\'Entre-Bois 23</p>';
			echo '<p>1018 Lausanne</p>';
			echo '<p>' . esc_attr__( 'Switzerland', 'segenvita' ) . '</p>';
		echo '</div>';
		echo '<div class="footer-middle">';
			echo '<p class="copyright">' . esc_attr__( 'Copyright', 'segenvita' ) . ' &copy; ' . date( 'Y' ) . ' ' . get_bloginfo( 'name' ) . '®. ' . esc_attr__( 'All Rights Reserved.', 'segenvita' ) . '</p>';
			echo '<p class="footer-links"><a href="' . home_url( esc_attr__( 'privacy-policy' , 'segenvita' ) ) . '">' . esc_attr__( 'Privacy Policy', 'segenvita' ) . '</a> <a href="' . home_url( esc_attr__( 'terms' , 'segenvita' ) ) . '">' . esc_attr__( 'Terms', 'segenvita' ) . '</a></p>';
			echo '<p class="designer">' . esc_attr__( 'Web design by ', 'segenvita' ) . '<a href="https://www.services-entrepreneurs.ch">Services-Entrepreneurs</a></p>';
			echo '<a class="backtotop" href="#genesis-content">Back to top' . segenvita_icon( array( 'icon' => 'arrow-up' ) ) . '</a>';
		echo '</div>';
		echo '<div class="footer-right">';
			echo '<p>' . esc_attr__( 'Our other sites:', 'segenvita' ) . '</p>';
			echo '<p><a href="http://nova-med.ch/">' . esc_attr__( 'Medical Center ', 'segenvita' ) . 'Novamed</a></p>';
		echo '</div>';
	echo '</div>';
}
add_action( 'genesis_footer', 'segenvita_site_footer' );
remove_action( 'genesis_footer', 'genesis_do_footer' );