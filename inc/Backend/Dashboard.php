<?php
namespace DrPlus\Backend;

use DrPlus\Utils;
use DrPlus\Utils\Options;

class Dashboard {
	public static function enqueue_font() {
		$options = Options::get_options( [
			'wp-dashboard-font-change'	=> true,
			'wp-dashboard-font'			=> ['font-family' => 'IRANYekanX']
		] );
		if( !Utils::to_bool( $options['wp-dashboard-font-change'] ) ) return;

		wp_enqueue_style( 'drplus-wp-dashboard', DRPLUS_URI . "assets/css/backend/dashboard.min.css", [], DRPLUS_VERSION );
		$css_code = ":root{--dashboard-font: {$options['wp-dashboard-font']['font-family']}}";
		if( in_array( $options['wp-dashboard-font']['font-family'], array_keys( Utils::fonts() ) ) ) {
			wp_enqueue_style( 'drplus-wp-font', DRPLUS_URI . "assets/css/fonts/{$options['wp-dashboard-font']['font-family']}.min.css", [], DRPLUS_VERSION );
		}
		wp_add_inline_style( 'drplus-wp-dashboard', $css_code );
	}

	public static function avatar_disabled_notice() {
		if( !Utils::to_bool( get_option( 'show_avatars', true ) ) ) {
			$msg = __( 'Displaying user avatars on your site is disabled. Disabling this option will prevent the display of specialists images. You can enable it from the link below.', 'drplus' );
			?>
			<div class="notice notice-warning" style="padding-top:8px">
				<strong style="display:flex;align-items:center;gap:8px">
					<img src="<?php echo DRPLUS_URI ?>assets/images/logo-d.svg" alt="<?php esc_attr_e( "Doctor Plus", 'drplus' ) ?>" width="32">
					<?php echo $msg ?>
				</strong>
				<p>
					<a href="<?php echo admin_url( 'options-discussion.php#show_avatars' ) ?>" class="button button-primary"><?php esc_html_e( 'Change options', 'drplus' ) ?></a>
				</p>
			</div>
			<?php
		}
	}
}
add_action( 'admin_enqueue_scripts', [Dashboard::class, 'enqueue_font'] );
add_action( 'elementor/editor/after_enqueue_styles', [Dashboard::class, 'enqueue_font'] );
add_action( 'admin_notices', [Dashboard::class, 'avatar_disabled_notice'] );