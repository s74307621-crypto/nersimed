<?php
/*
Plugin Name: Sheyda wallet
Version: 1.0.0.0
Author: Sheyda Team
Text Domain: sheyda_wallet
Domain Path: /languages
*/

// Define constants
if( !defined( 'SHEYDA_WALLET_DIR' ) ) {
	define( 'SHEYDA_WALLET_DIR', trailingslashit( get_template_directory() . "/inc/Wallet" ) );
}

if( !defined( 'SHEYDA_WALLET_URI' ) ) {
	define( 'SHEYDA_WALLET_URI', trailingslashit( get_template_directory_uri() . "/inc/Wallet" ) );
}

if( !defined( 'SHEYDA_WALLET_VERSION' ) ) {
	define( 'SHEYDA_WALLET_VERSION', "1.1.0.0" );
}

if( !defined( 'SHEYDA_WALLET_DEV' ) ) {
	define( 'SHEYDA_WALLET_DEV', false );
}

if( !defined( 'SHEYDA_WALLET_IS_LOCAL' ) ) {
	define( 'SHEYDA_WALLET_IS_LOCAL', !empty( $_SERVER['SERVER_NAME'] ) && $_SERVER['SERVER_NAME'] == 'localhost' );
}

load_textdomain( 'sheyda_wallet',  SHEYDA_WALLET_DIR . '/languages/sheyda_wallet-' . get_locale() . '.mo' );

include( SHEYDA_WALLET_DIR . "Includes.php" );