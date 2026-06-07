<?php
namespace DrPlus\Backend;

class UpdateChecker {
	public static function enqueue() {
		if( !current_user_can( 'update_themes' ) ) return;

		wp_enqueue_style( 'drplus-update-checker', DRPLUS_URI . "assets/css/backend/update_checker.min.css", [], DRPLUS_VERSION );

		if( DRPLUS_DEV ) {
			wp_enqueue_script( 'drplus-update-checker', DRPLUS_URI . "assets/js/backend/update-checker.js", ['jquery'], DRPLUS_VERSION, true );
		} else {
			wp_enqueue_script( 'drplus-update-checker', DRPLUS_URI . "assets/js/backend/update-checker.min.js", ['jquery'], DRPLUS_VERSION, true );
		}
	}

	public static function notice_wrap() {
		if( !current_user_can( 'update_themes' ) ) return;
		?>
		<div id="drplus-update-notices" class="notice" style="display:none"></div>
		<?php
	}
}
add_action( 'admin_enqueue_scripts', [UpdateChecker::class, 'enqueue'] );
add_action( 'admin_notices', [UpdateChecker::class, 'notice_wrap'] );