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
		 * Show commission notification when commission is set for auctions.
		 */
		public function show_commission() {
			if ( get_option( 'yith_wcact_commissions_enabled', false ) && floatval( get_option( 'yith_wcact_commissions_amount', 0 ) ) != 0 ) {
				$commission_percentage = floatval( get_option( 'yith_wcact_commissions_amount', 0 ) );
				?>
					<div class="yithac-commission-text"><?php echo esc_html( sprintf( __( 'This Auction has a commission cost of %.1f%%. These costs will be calculated upon checkout.', 'yith-auctions-commission' ), $commission_percentage ) ); ?></div>
				<?php
			}
		}

		/**
		 * Add a fee for auction items in cart.
		 *
		 * @param WC_Cart $cart the current cart.
		 */
		public function add_fee_for_auction_items( WC_Cart $cart ) {
			if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
				return;
			}

			if ( ! get_option( 'yith_wcact_commissions_enabled', false ) || floatval( get_option( 'yith_wcact_commissions_amount', 0 ) ) == 0 ) {
				return;
			}

			$commission_percentage = floatval( get_option( 'yith_wcact_commissions_amount', 0 ) );

			$auction_items = array_filter(
				array_values( $cart->get_cart() ),
				function( array $cart_item ): bool {
					$product = wc_get_product( $cart_item['product_id'] );
					return $product->get_type() === 'auction';
				}
			);

			$auction_total = array_reduce(
				array_map(
					function( array $cart_item ): float {
						return $cart_item['line_total'];
					},
					$auction_items
				),
				function( float $carry, float $subtotal ): float {
					return $carry + $subtotal;
				},
				0
			);

			$fee = $auction_total * $commission_percentage / 100;

			if ( 0 != $fee ) {
				$cart->add_fee( __( 'Auction commission', 'yith-auctions-commission' ), $fee );
			}
		}

		/**
		 * Add actions and filters.
		 */
		private function actions_and_filters() {
			add_action( 'after_setup_theme', array( $this, 'pluggable' ) );
			add_action( 'init', array( $this, 'init' ) );
			add_filter( 'yit_plugin_fw_wc_panel_option_args', array( $this, 'alter_settings_page' ), 99 );
			add_action( 'yith_wcact_after_add_button_bid', array( $this, 'show_commission' ) );
			add_action( 'woocommerce_cart_calculate_fees', array( $this, 'add_fee_for_auction_items' ) );
		}
	}
}
