<?php
namespace Sheyda\Wallet;

class PublicScripts {
	public static function main() {
		// wp_enqueue_script( $handle:string, $src:string, $deps:array, $ver:string|boolean|null, $in_footer:boolean )
	}
}
add_action( 'admin_enqueue_scripts', [PublicScripts::class, 'main'] );