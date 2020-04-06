<?php
/**
 * Genesis Framework.
 *
 * WARNING: This file is part of the core Genesis Framework. DO NOT edit this file under any circumstances.
 * Please do all modifications in the form of a child theme.
 *
 * @package StudioPress\Genesis
 * @author  Services-Entrepreneurs
 * @license GPL-2.0-or-later
 * @link    https://services-entrepreneurs.ch/
 */

/**
 * Genesis Menu Handler - creates a menu component for either a non-AMP or AMP endpoint.
 *
 * @since 3.0.0
 */
class Genesis_Menu_Handler {

	/**
	 * Name of the active theme.
	 *
	 * @var string
	 */
	protected $theme_name;

	/**
	 * Array of script configurations parameters.
	 *
	 * @var array
	 */
	protected $script_config;

	/**
	 * Array of extra configuration parameters.
	 *
	 * @var array
	 */
	protected $extras_config;

	/**
	 * Instance of the menu.
	 *
	 * @var Genesis_Menu|Genesis_AMP_Menu
	 */
	protected $menu;

	/**
	 * Flag indicating push_single_combine_onto_others method ran.
	 *
	 * @var bool
	 */
	protected $did_push_single_combine_onto_others = false;

	/**
	 * Genesis_Responsive_Menu_Handler constructor.
	 *
	 * @since 3.0.0
	 *
	 * @param string $theme_name Name of the active theme.
	 * @param array  $config     Array of configurations parameters.
	 */
	public function __construct( $theme_name, array $config ) {

		$this->theme_name = $theme_name;

		$config              = self::init_config( $config );
		$this->script_config = $config['script'];
		$this->extras_config = $config['extras'];

	}

	/**
	 * Initialize the configuration parameters by merging with defaults.
	 *
	 * @since 3.0.0
	 *
	 * @param array $config Array of configurations parameters.
	 *
	 * @return array
	 */
	public static function init_config( array $config ) {

		// Initialize the script configuration.
		$script = [
			'mainMenu'            => __( 'Mu', 'genesis' ),
			'menuIconClass'       => 'dashicons-before dashicons-menu',
			'menuIconOpenedClass' => 'dashicons-before dashicons-no-alt',
			'subMenu'             => __( 'Submenu', 'genesis' ),
			'subMenuIconClass'    => 'dashicons-before dashicons-arrow-down-alt2',
			'menuClasses'         => [
				'combine' => [],
				'others'  => [],
			],
		];
		if ( isset( $config['script'] ) ) {
			$script = array_merge( $script, $config['script'] );
		}

		// Initialize the extras configuration.
		$extras = [
			'media_query_width' => '1023px',
			'css'               => '',
			'enable_AMP'        => true,
			'enable_non_AMP'    => true,
		];
		if ( isset( $config['extras'] ) ) {
			$extras = array_merge( $extras, $config['extras'] );
		}

		return compact( 'script', 'extras' );

	}

	/**
	 * Hook into WordPress.
	 *
	 * @since 3.0.0
	 */
	public function add_hooks() {

		add_action( 'genesis_meta', [ $this, 'create_nonamp_or_amp_menu' ], 5 );
		add_filter( 'amp_content_sanitizers', [ $this, 'add_amp_menu_combiner' ] );

	}

	/**
	 * Create the AMP or non-AMP menu.
	 *
	 * @since 3.0.0
	 */
	public function create_nonamp_or_amp_menu() {

		if ( genesis_is_amp() ) {
			$this->create_amp_menu();
		} else {
			$this->create_menu();
		}

		$this->menu->add_hooks();

	}

	/**
	 * Create an instance of the Genesis_Menu.
	 *
	 * @since 3.0.0
	 */
	private function create_menu() {

		if ( ! $this->extras_config['enable_non_AMP'] ) {
			return;
		}

		require_once __DIR__ . '/class-genesis-menu.php';

		$this->menu = new Genesis_Menu( $this->theme_name, $this->script_config );

	}

	/**
	 * Create an instance of the Genesis_AMP_Menu.
	 *
	 * @since 3.0.0
	 */
	private function create_amp_menu() {

		if ( ! $this->extras_config['enable_AMP'] ) {
			return;
		}

		require_once __DIR__ . '/class-genesis-amp-menu.php';

		$this->push_single_combine_onto_others();

		$this->menu = new Genesis_AMP_Menu(
			$this->theme_name,
			$this->script_config,
			$this->extras_config
		);

	}

	/**
	 * Add the AMP Menu Combiner to the list of content sanitizers.
	 *
	 * @since 3.0.0
	 *
	 * @param array $sanitizers Array of content sanitizers.
	 *
	 * @return array amended array of content sanitizers.
	 */
	public function add_amp_menu_combiner( $sanitizers ) {

		$this->push_single_combine_onto_others();

		// Bail out if there are no menus to combine.
		if (
			! isset( $this->script_config['menuClasses']['combine'] )
			||
			empty( $this->script_config['menuClasses']['combine'] )
		) {
			return $sanitizers;
		}

		// Let's make sure the class is loaded into memory.
		require_once __DIR__ . '/class-genesis-amp-menu-combiner.php';

		$sanitizers['Genesis_AMP_Menu_Combiner'] = [
			'combine' => $this->script_config['menuClasses']['combine'],
		];

		return $sanitizers;
	}

	/**
	 * Push a single 'combine' menu onto the 'others' array.
	 */
	protected function push_single_combine_onto_others() {

		if ( $this->did_push_single_combine_onto_others ) {
			return;
		}

		// Bail out if there are 0 or more than 1 menu(s) in 'combine'.
		if (
			empty( $this->script_config['menuClasses']['combine'] ) ||
			count( $this->script_config['menuClasses']['combine'] ) > 1
		) {
			$this->did_push_single_combine_onto_others = true;

			return;
		}

		$this->script_config['menuClasses']['others']  = array_unique(
			array_merge(
				$this->script_config['menuClasses']['others'],
				$this->script_config['menuClasses']['combine']
			)
		);
		$this->script_config['menuClasses']['combine'] = [];

		$this->did_push_single_combine_onto_others = true;

	}

}
