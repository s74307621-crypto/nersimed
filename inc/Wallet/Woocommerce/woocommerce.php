<?php
namespace Sheyda\Wallet; use SheydaWalletUtils as WalletUtils;

use MJ\Whitebox\Utils;
use Sheyda\Wallet\Woocommerce\WCWallet;

class Woocommerce {
	public static function add_wallet_item( $items ) {
		$items['sheyda-wallet'] = esc_html__( 'Wallet', 'sheyda_wallet' );

		Utils::reposition_array_element( $items, 'sheyda-wallet', 2 );

		return $items;
	}

	public static function wallet_page_content() {
		wp_localize_script( 'sheyda-wallet-wc', 'walletWC', [
			'i18n'	=>  [
				'maxWithdrawalError' => esc_html__( 'Your requested amount is more than your wallet balance', 'sheyda_wallet' ),
				'minWithdrawalError' => esc_html__( 'Your requested amount is less than minimum withdrawal request amount', 'sheyda_wallet' ),
			]
		] );
		include_once( SHEYDA_WALLET_DIR . "Woocommerce/MyAccount/class-wc-wallet.php" );
		WCWallet::view();
	}

	public static function add_rewrite_endpoint() {
		add_rewrite_endpoint( 'sheyda-wallet', EP_PAGES );
		if( !empty( $_POST ) ) {
			include_once( SHEYDA_WALLET_DIR . "Woocommerce/MyAccount/class-wc-wallet.php" );
			WCWallet::save();
		}
	}

	public static function enqueue_scripts() {
		if( is_account_page() ) {
			wp_enqueue_style( 'sheyda-wallet', SHEYDA_WALLET_URI . "assets/css/wallet.min.css", [], SHEYDA_WALLET_VERSION );
			wp_enqueue_style( 'sheyda-wallet-wc', SHEYDA_WALLET_URI . "assets/css/wc/my-account/wc-wallet.min.css", [], SHEYDA_WALLET_VERSION );
			if( SHEYDA_WALLET_DEV ) {
				wp_enqueue_script( 'sheyda-wallet-utils', SHEYDA_WALLET_URI . "assets/js/utils.js", ['jquery'], SHEYDA_WALLET_VERSION, true );
				wp_enqueue_script( 'sheyda-wallet', SHEYDA_WALLET_URI . "assets/js/wallet.js", ['jquery'], SHEYDA_WALLET_VERSION, true );
				wp_enqueue_script( 'sheyda-wallet-wc', SHEYDA_WALLET_URI . "assets/js/wc/my-account/wc-wallet.js", ['jquery'], SHEYDA_WALLET_VERSION, true );
			} else {
				wp_enqueue_script( 'sheyda-wallet-utils', SHEYDA_WALLET_URI . "assets/js/utils.min.js", ['jquery'], SHEYDA_WALLET_VERSION, true );
				wp_enqueue_script( 'sheyda-wallet', SHEYDA_WALLET_URI . "assets/js/wallet.min.js", ['jquery'], SHEYDA_WALLET_VERSION, true );
				wp_enqueue_script( 'sheyda-wallet-wc', SHEYDA_WALLET_URI . "assets/js/wc/my-account/wc-wallet.min.js", ['jquery'], SHEYDA_WALLET_VERSION, true );
			}
			include_once( SHEYDA_WALLET_DIR . "Woocommerce/MyAccount/class-wc-wallet.php" );
			WCWallet::enqueue();
		} else if( is_checkout() ) {
			wp_enqueue_style( 'sheyda-wallet-wc-checkout', SHEYDA_WALLET_URI . "assets/css/wc/checkout.min.css", [], SHEYDA_WALLET_VERSION );
			if( SHEYDA_WALLET_DEV ) {
				wp_enqueue_script( 'sheyda-wallet-utils', SHEYDA_WALLET_URI . "assets/js/utils.js", ['jquery'], SHEYDA_WALLET_VERSION, true );
				wp_enqueue_script( 'sheyda-wallet', SHEYDA_WALLET_URI . "assets/js/wallet.js", ['jquery'], SHEYDA_WALLET_VERSION, true );
			} else {
				wp_enqueue_script( 'sheyda-wallet', SHEYDA_WALLET_URI . "assets/js/wallet.min.js", ['jquery'], SHEYDA_WALLET_VERSION, true );
				wp_enqueue_script( 'sheyda-wallet-utils', SHEYDA_WALLET_URI . "assets/js/utils.min.js", ['jquery'], SHEYDA_WALLET_VERSION, true );
			}
		}
	}

	public static function is_purchasable( $is_purchasable, $product ) {
		if( $product->get_id() == WalletUtils::get_wallet_product_id() ) {
			return true;
		}
		return $is_purchasable;
	}

	public static function hide_wallet_product( $query ) {
		static $executed = false;
		if( !$executed ) {
			if( $query->get( 'post_type' ) == 'product' ) {
				$not_in = $query->get( 'post__not_in' );
				if( !is_array( $not_in ) ) {
					$not_in = [];
				}
				$not_in[] = WalletUtils::get_wallet_product_id();

				$query->set( 'post__not_in', $not_in );

				$executed = true;
			}
		}
	}
}
add_filter( 'woocommerce_account_menu_items', [Woocommerce::class, 'add_wallet_item'], 10 );
add_action( "woocommerce_account_sheyda-wallet_endpoint", [Woocommerce::class, 'wallet_page_content'], 10 );
add_action( "init", [Woocommerce::class, 'add_rewrite_endpoint'], 1 );
add_action( 'wp_enqueue_scripts', [Woocommerce::class, 'enqueue_scripts'] );
add_filter( 'woocommerce_is_purchasable', [Woocommerce::class, 'is_purchasable'], 10, 2 );
add_action( 'pre_get_posts', [Woocommerce::class, 'hide_wallet_product'] );