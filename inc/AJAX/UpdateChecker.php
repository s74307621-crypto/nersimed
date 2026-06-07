<?php
namespace DrPlus\AJAX;

use DrPlus\AJAX;

class UpdateChecker extends AJAX {
	public static function get_instance() {
		static $instance = null;
		if( $instance === null ) {
			$instance = new self;
		}
		return $instance;
	}

	public function __construct() {
		return $this;
	}

	public function check() {
		if( !current_user_can( 'update_themes' ) ) die;

		$endpoint = "https://sheydateam.ir/api/sheyda/v2/product/update?product=drplus";
		$alt_endpoint = "https://sheydateam.com/api/sheyda/v2/product/update?product=drplus";
		$request_args = [
			'user-agent'	=> 'Mozilla/5.0 (X11; Linux x86_64; rv:109.0) Gecko/20100101 Firefox/115.0',
			'timeout'		=> 60,
			'sslverify'		=> false,
			'headers'	=> [
				'Accept'		=> 'application/json',
				'Content-Type'	=> 'application/json',
			],
			'httpversion'	=> '1.1',
		];

		$last_update_check = get_option( 'drplus_last_update_check', 0 );
		if( ( $last_update_check + 6*HOUR_IN_SECONDS ) <= current_time( 'U' ) ) {
			$request = wp_remote_get( $endpoint, $request_args );
			if( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) !== 200 ) {
				$request = wp_remote_get( $alt_endpoint, $request_args );
				if( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) !== 200 ) {
					die;
				}
			}

			$json = json_decode( wp_remote_retrieve_body( $request ), true );
			if( version_compare( DRPLUS_VERSION, $json['last_version'], '<' ) ) {
				$transient = get_site_transient( 'update_themes' );
				if( !$transient ) {
					$transient = new stdClass();
					$transient->last_checked = time();
					$transient->checked = [];
					$transient->response = [];
				}

				$theme_slug = get_stylesheet();

				$transient->response[$theme_slug] = array(
					'theme'			=> $theme_slug,
					'new_version'	=> $json['last_version'],
					'url'			=> $json['wp_changelog'],
					'package'		=> $json['file_url'],
				);

				$update_url = wp_nonce_url(
					self_admin_url( 'update.php?action=upgrade-theme&theme=' . urlencode( $theme_slug ) ),
					'upgrade-theme_' . $theme_slug
				);

				$logo = DRPLUS_URI . "assets/images/logo.svg";
				?>
				<div class="drplus_update_notice notice notice-success">
					<p><?php _e( "A new version of <strong>Doctor Plus</strong> is available.", 'drplus' ) ?></p>
					<p><?php printf( "<strong>Installed version:</strong> %s", DRPLUS_VERSION ) ?></p>
					<p><?php printf( "<strong>Last version:</strong> %s", $json['last_version'] ) ?></p>
					<a href="<?php echo esc_url( $update_url ) ?>" class="button button-primary"><?php esc_html_e( 'Install new version', 'drplus' ) ?></a>

					<a href="<?php echo $json['rtl_url'] ?>" target="_blank" class="drplus_update_notice-logo"><img src="<?php echo $logo ?>" alt=""></a>
				</div>
				<?php
			}

			update_option( 'drplus_last_update_check', current_time( 'U' ), false );
		}
		die;
	}
}