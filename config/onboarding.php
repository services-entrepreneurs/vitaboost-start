<?php
/**
 * Segenvita.
 *
 * Onboarding config to load plugins and homepage content on theme activation.
 *
 * @package Segenvita
 * @author  StudioPress
 * @license GPL-2.0-or-later
 * @link    https://www.studiopress.com/
 */

$segenvita_shared_content = genesis_get_config( 'onboarding-shared' );

return [
	'starter_packs' => [
		'black-white' => [
			'title'       => __( 'Black & White', 'segenvita' ),
			'description' => __( 'A pack with a homepage designed with black and white images.', 'segenvita' ),
			'thumbnail'   => get_stylesheet_directory_uri() . '/config/import/images/thumbnails/home-black-white.jpg',
			'demo_url'    => 'https://demo.studiopress.com/segenvita/',
			'config'      => [
				'dependencies'     => [
					'plugins' => $segenvita_shared_content['plugins'],
				],
				'content'          => array_merge(
					[
						'homepage' => [
							'post_title'     => 'Homepage',
							'post_content'   => require dirname( __FILE__ ) . '/import/content/home-black-white.php',
							'post_type'      => 'page',
							'post_status'    => 'publish',
							'comment_status' => 'closed',
							'ping_status'    => 'closed',
							'meta_input'     => [
								'_genesis_layout'     => 'full-width-content',
								'_genesis_hide_title' => true,
								'_genesis_hide_breadcrumbs' => true,
								'_genesis_hide_singular_image' => true,
							],
						],
					],
					$segenvita_shared_content['content']
				),
				'navigation_menus' => $segenvita_shared_content['navigation_menus'],
				'widgets'          => $segenvita_shared_content['widgets'],
			],
		],
		'color'       => [
			'title'       => __( 'Color', 'segenvita' ),
			'description' => __( 'A pack with a homepage designed with color images.', 'segenvita' ),
			'thumbnail'   => get_stylesheet_directory_uri() . '/config/import/images/thumbnails/home-color.jpg',
			'demo_url'    => 'https://demo.studiopress.com/segenvita/home-color/',
			'config'      => [
				'dependencies'     => [
					'plugins' => $segenvita_shared_content['plugins'],
				],
				'content'          => array_merge(
					[
						'homepage' => [
							'post_title'     => 'Homepage',
							'post_content'   => require dirname( __FILE__ ) . '/import/content/home-color.php',
							'post_type'      => 'page',
							'post_status'    => 'publish',
							'comment_status' => 'closed',
							'ping_status'    => 'closed',
							'meta_input'     => [
								'_genesis_layout'     => 'full-width-content',
								'_genesis_hide_title' => true,
								'_genesis_hide_breadcrumbs' => true,
								'_genesis_hide_singular_image' => true,
							],
						],
					],
					$segenvita_shared_content['content']
				),
				'navigation_menus' => $segenvita_shared_content['navigation_menus'],
				'widgets'          => $segenvita_shared_content['widgets'],
			],
		],
	],
];
