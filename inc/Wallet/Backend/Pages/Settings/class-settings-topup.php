<?php
namespace Sheyda\Wallet\Backend;

use MJ\Whitebox\Utils;
use Sheyda\Wallet\AdminScripts;
use Sheyda\Wallet\Backend\Settings;
use Sheyda\Wallet\Utils\AdminUI;
use Sheyda\Wallet\Utils\Settings as UtilsSettings;

class TopUpSettings extends Settings {
	public static function view() {
		$prefix = parent::$PREFIX;
		$settings = UtilsSettings::get_settings( 'topup' );

		?>
		<form method="post" action="" class="<?php echo $prefix ?>section-wrap">
			<?php parent::create_nonce(); ?>
			<table class="form-table">
				<tbody>
					<tr>
						<th>
							<label for="<?php echo $prefix ?>enable_topup"><?php esc_html_e( 'Enable Top-up', 'sheyda_wallet' ) ?></label>
						</th>
						<td>
							<?php
							AdminUI::switch( [
								'label'		=> esc_html__( 'Allow Users to Deposit Funds', 'sheyda_wallet' ),
								'name'		=> $prefix . "enable",
								'id'		=> $prefix . "enable_topup_request",
								'value'		=> 1,
								'active'	=> Utils::to_bool( $settings['enable'] )
							] );
							?>
						</td>
					</tr>
				</tbody>
			</table>

			<button type="submit" id="<?php echo $prefix ?>submit"><?php esc_html_e( 'Save changes', 'sheyda_wallet' ) ?></button>
		</form>
		<?php
	}

	public static function save() {
		$prefix = parent::$PREFIX;
		$settings = [
			'enable'	=> Utils::to_bool( $_POST[$prefix . "enable"] ?? false ),
		];

		UtilsSettings::save_settings( 'topup', $settings );
	}

	public static function enqueue() {
		AdminScripts::switch();
	}
}