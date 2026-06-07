<?php
namespace Sheyda\Wallet\Backend;

use DrPlus\PublicScripts;
use MJ\Whitebox\Utils;
use Sheyda\Wallet\AdminScripts;
use Sheyda\Wallet\Backend\Settings;
use Sheyda\Wallet\Utils\AdminUI;
use Sheyda\Wallet\Utils\Settings as UtilsSettings;

class GeneralSettings extends Settings {
	public static function view() {
		$prefix = parent::$PREFIX;

		// Get wc orders
		$order_statuses = wc_get_order_statuses();

		$settings = UtilsSettings::get_settings( 'general' );

		?>
		<form method="post" action="" class="<?php echo $prefix ?>section-wrap">
			<?php parent::create_nonce(); ?>
			<table class="form-table">
				<tbody>
					<tr>
						<th>
							<label for="<?php echo $prefix ?>enable"><?php esc_html_e( 'Enable/Disable', 'sheyda_wallet' ) ?></label>
						</th>
						<td>
							<?php
							AdminUI::switch( [
								'label'		=> esc_html__( 'Enable wallet', 'sheyda_wallet' ),
								'name'		=> $prefix . "enable",
								'id'		=> $prefix . "enable",
								'value'		=> 1,
								'active'	=> Utils::to_bool( $settings['enable'] )
							] );
							?>
						</td>
					</tr>
					<tr>
						<th>
							<label for="<?php echo $prefix ?>enable_wc_purchase"><?php esc_html_e( 'Enable Payment', 'sheyda_wallet' ) ?></label>
						</th>
						<td>
							<?php
							AdminUI::switch( [
								'label'		=> esc_html__( 'Allow Partial Payments using Wallet', 'sheyda_wallet' ),
								'name'		=> $prefix . "enable_wc_purchase",
								'id'		=> $prefix . "enable_wc_purchase",
								'value'		=> 1,
								'active'	=> Utils::to_bool( $settings['enable_wc_purchase'] )
							] );
							?>
						</td>
					</tr>
					<tr>
						<th>
							<label for="<?php echo $prefix ?>"><?php esc_html_e( 'Order Status for Wallet Payment Gateway', 'sheyda_wallet' ) ?></label>
						</th>
						<td>
							<?php
							AdminUI::select_with_label( [
								'label'		=> esc_html__( 'Order status', 'sheyda_wallet' ),
								'name'		=> $prefix . "wc_purchase_order_status",
								'id'		=> $prefix . "wc_purchase_order_status",
								'classes'	=> ['drplus-select2'],
								'options'	=> $order_statuses,
								'value'		=> $settings['wc_purchase_order_status']
							] )
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
			'enable'						=> Utils::to_bool( $_POST[$prefix . "enable"] ?? false ),
			'enable_wc_purchase'			=> Utils::to_bool( $_POST[$prefix . "enable_wc_purchase"] ?? false ),
			'wc_purchase_order_status'		=> Utils::convert_chars( $_POST[$prefix . "wc_purchase_order_status"] ),
		];

		UtilsSettings::save_settings( 'general', $settings );
	}

	public static function enqueue() {
		AdminScripts::form_group();
		AdminScripts::switch();
		PublicScripts::select2();
	}
}