<?php
namespace Sheyda\Wallet\Utils;

use MJ\Whitebox\Utils as WhiteboxUtils;
use SheydaWalletUtils as Utils;

class Settings extends Utils {
	public static function default_settings( $section ) {
		$default = [];
		if( $section == 'general' ) {
			$default = [
				'enable'						=> true,
				'enable_wc_purchase'			=> true,
				'wc_purchase_order_status'		=> 'wc-processing',
			];
		} else if( $section == 'withdrawal' ) {
			$default = [
				'enable'						=> true,
				'minimum_withdrawal_request'	=> '0',
				'withdrawal_fee_type'			=> 'none',
				'withdrawal_fixed_fee'			=> '0',
				'withdrawal_percentage_fee'		=> 0
			];
		} else if( $section == 'topup' ) {
			$default = [
				'enable'	=> true,
			];
		}
		return $default;
	}

	public static function get_settings( $section = 'general' ) {
		$settings = WhiteboxUtils::check_default( get_option( "sheyda_wallet_settings_{$section}", [] ), self::default_settings( $section ) );
		if( !WhiteboxUtils::is_wc_active() ) $settings['enable'] = false;
		return $settings;
	}

	public static function save_settings( $section, $settings ) {
		$settings = WhiteboxUtils::check_default( $settings, self::default_settings( $section ) );

		update_option( "sheyda_wallet_settings_{$section}", $settings, false );
	}
}