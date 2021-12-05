<?php
/**
 * YITH Auctions Commission core
 *
 * @package yith-auctions-commision
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'YithAcCore' ) ) {
	/**
	 * Yith Auctions Commission Core class
	 *
	 * @class YithAcCore
	 */
	class YithAcCore {

		/**
		 * Plugin version
		 *
		 * @var string
		 */
		public string $version = '0.0.1';

		/**
		 * The single instance of the class
		 *
		 * @var YithAcCore|null
		 */
		protected static ?YithAcCore $_instance = null;

		/**
		 * Yith Auctions Commission Core class
		 *
		 * Uses the Singleton pattern to load 1 instance of this class at maximum
		 *
		 * @static
		 * @return YithAcCore
		 */
		public static function instance(): YithAcCore {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Constructor
		 */
		private function __construct() {
			$this->define_constants();
			$this->init_hooks();
			$this->actions_and_filters();
		}

		/**
		 * Initialise YITH Auctions Commission
		 */
		public function init() {
			$this->initialise_localisation();
			do_action( 'yith_auctions_commission_init' );
		}

		/**
		 * Initialise the localisation of the plugin.
		 */
		private function initialise_localisation() {
			load_plugin_textdomain( 'yith-auctions-commission', false, plugin_basename( dirname( YITHAC_PLUGIN_FILE ) ) . '/languages/' );
		}

		/**
		 * Define constants of the plugin.
		 */
		private function define_constants() {
			$this->define( 'YITHAC_ABSPATH', dirname( YITHAC_PLUGIN_FILE ) . '/' );
			$this->define( 'YITHAC_VERSION', $this->version );
			$this->define( 'YITHAC_FULLNAME', 'yith-auctions-commission' );
		}

		/**
		 * Define if not already set
		 *
		 * @param string $name name of the variable to define.
		 * @param string $value value of the variable to define.
		 */
		private static function define( string $name, string $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}

		/**
		 * Initialise activation and deactivation hooks.
		 */
		private function init_hooks() {
			register_activation_hook( YITHAC_PLUGIN_FILE, array( $this, 'activation' ) );
			register_deactivation_hook( YITHAC_PLUGIN_FILE, array( $this, 'deactivation' ) );
		}

		/**
		 * Activation hook call.
		 */
		public function activation() {
		}

		/**
		 * Deactivation hook call.
		 */
		public function deactivation() {
		}

		/**
		 * Add pluggable support to functions
		 */
		public function pluggable() {
		}

		/**
		 * Alter the settings page plugin options folder.
		 *
		 * @param $args array arguments passed to YITH Admin panel
		 *
		 * @return array arguments for YITH Admin panel
		 */
		public function alter_settings_page( array $args ) {
			$args['options-path'] = YITHAC_ABSPATH . 'plugin-options';
			return $args;
		}

		/**
		 * Add actions and filters.
		 */
		private function actions_and_filters() {
			add_action( 'after_setup_theme', array( $this, 'pluggable' ) );
			add_action( 'init', array( $this, 'init' ) );
			add_filter( 'yit_plugin_fw_wc_panel_option_args', array( $this, 'alter_settings_page' ), 99 );
		}
	}
}
