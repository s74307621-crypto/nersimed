<?php
namespace Sheyda\Wallet\Backend;

use DrPlus\PublicScripts;
use MJ\Whitebox\Utils as WhiteboxUtils;
use MJ\Whitebox\Utils\Formatters;
use MJ\Whitebox\Utils\Sanitizers;
use Sheyda\Wallet\AdminScripts;
use Sheyda\Wallet\Backend\Settings;
use Sheyda\Wallet\Utils\AdminUI;
use Sheyda\Wallet\Utils\Settings as UtilsSettings;
use SheydaWalletUtils as Utils;

class WithdrawalSettings extends Settings {
	public static function view() {
		$woocommerce_currency = get_woocommerce_currency();
		if( in_array( $woocommerce_currency, ['IRR', 'IRT', 'IRHR', 'IRHT'] ) ) {
			$price_symbol = esc_html__( 'Toman', 'drplus' );
		} else {
			$price_symbol = get_woocommerce_currency_symbol();
		}
		$prefix = parent::$PREFIX;

		$settings = UtilsSettings::get_settings( 'withdrawal' );

		?>
		<form method="post" action="" class="<?php echo $prefix ?>section-wrap">
			<?php parent::create_nonce(); ?>
			<table class="form-table">
				<tbody>
					<tr>
						<th>
							<label for="<?php echo $prefix ?>enable_withdrawal_request"><?php esc_html_e( 'Withdrawal status', 'sheyda_wallet' ) ?></label>
						</th>
						<td>
							<?php
							AdminUI::switch( [
								'label'		=> esc_html__( 'Allow user to request withdrawal', 'sheyda_wallet' ),
								'name'		=> $prefix . "enable",
								'id'		=> $prefix . "enable_withdrawal_request",
								'value'		=> 1,
								'active'	=> WhiteboxUtils::to_bool( $settings['enable'] )
							] );
							?>
						</td>
					</tr>
					<tr>
						<th>
							<label for="<?php echo $prefix ?>minimum_withdrawal_request"><?php printf( esc_html__( 'Minimum withdrawal amount (%s)', 'sheyda_wallet' ), $price_symbol ) ?></label>
						</th>
						<td>							
							<?php
							AdminUI::input_with_label( [
								'name'				=> $prefix . "minimum_withdrawal_request",
								'id'				=> $prefix . "minimum_withdrawal_request",
								'value'				=> Formatters::price( $settings['minimum_withdrawal_request'] ),
								'type'				=> 'text',
								'input_classes'		=> ['regular-text', 'drplus-price-input']
							] );
							?>
						</td>
					</tr>
					<tr>
						<th>
							<label for="<?php echo $prefix ?>"><?php esc_html_e( 'withdrawal fee type', 'sheyda_wallet' ) ?></label>
						</th>
						<td>
							<?php
							AdminUI::select_with_label( [
								'label'		=> esc_html__( 'Fee type', 'sheyda_wallet' ),
								'name'		=> $prefix . "withdrawal_fee_type",
								'id'		=> $prefix . "withdrawal_fee_type",
								'classes'	=> ['drplus-select2'],
								'options'	=> [
									'none'			=> esc_html__( 'Without fee', 'sheyda_wallet' ),
									'percentage'	=> esc_html__( 'Percentage', 'sheyda_wallet' ),
									'fixed'			=> esc_html__( 'Fixed', 'sheyda_wallet' ),
								],
								'value'		=> $settings['withdrawal_fee_type']
							] )
							?>
						</td>
					</tr>
					<tr class="<?php echo $prefix ?>withdrawal_fixed_fee-row"<?php echo $settings['withdrawal_fee_type'] != 'fixed' ? ' style="display:none"' : "" ?>>
						<th>
							<label for="<?php echo $prefix ?>withdrawal_fixed_fee"><?php printf( esc_html__( 'withdrawal fee (%s)', 'sheyda_wallet' ), $price_symbol ) ?></label>
						</th>
						<td>							
							<?php
							AdminUI::input_with_label( [
								'name'				=> $prefix . "withdrawal_fixed_fee",
								'id'				=> $prefix . "withdrawal_fixed_fee",
								'value'				=> Formatters::price( $settings['withdrawal_fixed_fee'] ),
								'type'				=> 'text',
								'input_classes'		=> ['regular-text', 'drplus-price-input']
							] );
							?>
						</td>
					</tr>
					<tr class="<?php echo $prefix ?>withdrawal_percentage_fee-row"<?php echo $settings['withdrawal_fee_type'] != 'percentage' ? ' style="display:none"' : "" ?>>
						<th>
							<label for="<?php echo $prefix ?>withdrawal_percentage_fee"><?php esc_html_e( 'withdrawal fee (%)', 'sheyda_wallet' ) ?></label>
						</th>
						<td>							
							<?php
							AdminUI::input_with_label( [
								'name'				=> $prefix . "withdrawal_percentage_fee",
								'id'				=> $prefix . "withdrawal_percentage_fee",
								'value'				=> Formatters::price( $settings['withdrawal_percentage_fee'] ),
								'type'				=> 'number',
								'input_classes'		=> ['regular-text', 'drplus-numeric-input'],
								'max'				=> 100
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
			'enable'						=> Utils::to_bool( $_POST[$prefix . "enable"] ?? false ),
			'minimum_withdrawal_request'	=> Sanitizers::price( $_POST[$prefix . "minimum_withdrawal_request"] ),
			'withdrawal_fee_type'			=> Utils::ensure_values_in_array( Utils::convert_chars( $_POST[$prefix . "withdrawal_fee_type"] ), ['none', 'percentage', 'fixed'], 'none' ),
			'withdrawal_fixed_fee'			=> Sanitizers::price( $_POST[$prefix . "withdrawal_fixed_fee"] ),
			'withdrawal_percentage_fee'		=> Utils::convert_chars( $_POST[$prefix . "withdrawal_percentage_fee"], 'absint' ),
		];

		UtilsSettings::save_settings( 'withdrawal', $settings );
	}

	public static function enqueue() {
		AdminScripts::form_group();
		AdminScripts::switch();
		PublicScripts::select2();
	}
}