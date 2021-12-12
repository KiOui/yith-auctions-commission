<?php
/**
 * YITH Auctions Commission functions
 *
 * @package yith-auctions-commision
 */

if ( ! function_exists( 'yithac_format_commission_percentage' ) ) {
	/**
	 * Get formatted commission percentage.
	 *
	 * @param float $commission_percentage commission percentage.
	 *
	 * @return string formatted commission percentage (either with one decimal or zero)
	 */
	function yithac_format_commission_percentage( float $commission_percentage ): string {
		if ( (int) $commission_percentage == $commission_percentage ) {
			return sprintf( '%.0f', $commission_percentage );
		} else {
			return sprintf( '%.1f', $commission_percentage );
		}
	}
}
