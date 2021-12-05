<?php
/**
 * Settings options
 *
 * @package yith-auctions-commission
 */

$settings = include( YITH_WCACT_OPTIONS_PATH . '/settings-options.php' );

$new_settings = array();
foreach ( $settings['settings'] as $key => $value ) {
	if ( 'settings_options_end' === $key ) {
		$new_settings['settings_commission_amount_onoff'] = array(
			'title'     => esc_html__( 'Compute commissions over completed auctions.', 'yith-auctions-commission' ),
			'type'      => 'yith-field',
			'yith-type' => 'onoff',
			'desc'      => esc_html__( 'Enable to compute commissions over completed auctions.', 'yith-auctions-commision' ),
			'id'        => 'yith_wcact_commissions_enabled',
			'default'   => 'no',
		);
		$new_settings['settings_commission_amount'] = array(
			'title'             => esc_html__( 'Commission amount (%)', 'yith-auctions-commission' ),
			'type'              => 'yith-field',
			'yith-type'         => 'number',
			'class'             => 'ywcact-input-text',
			'desc'              => esc_html__( 'Set to compute commission percentage over completed auctions', 'yith-auctions-for-woocommerce' ),
			'id'                => 'yith_wcact_commissions_amount',
			'step' => 0.1,
			'min'  => 0,
			'default'           => 0,
			'deps'              => array(
				'id'    => 'yith_wcact_commissions_enabled',
				'value' => 'yes',
				'type'  => 'hide',
			),
		);
	}
	$new_settings[ $key ] = $value;
}

$settings['settings'] = $new_settings;
return $settings;
