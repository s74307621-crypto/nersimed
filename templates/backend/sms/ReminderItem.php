<?php

use DrPlus\Utils;
use DrPlus\Utils\SMS;

$args = Utils::check_default( $args, [
	'type'				=> 'specialist', // specialist | patient
	'index'				=> '1',
	'id'				=> '',
	'message'			=> '',
	'pattern'			=> '',
	'timing'			=> '-1',
	'status'			=> false,
	'show_remove_btn'	=> true,
	'prefix'			=> 'drplus_sms_',
] );

$prefix = $args['prefix'];
$settings = [];

?>
<table class="form-table <?php echo $prefix ?>settings-reminder-notif-repeater" data-index="<?php echo $args['index'] ?>" data-type="<?php echo $args['type'] ?>">
	<tr>
		<th>
			<span><?php echo esc_html__( 'Item', 'drplus' ) ?></span>
			<span class="<?php echo $prefix ?>settings-reminder-notif-repeater-index"><?php echo $args['index'] ?></span>
			<input type="hidden" name="<?php echo $prefix ?>settings[reserve_notification][<?php echo $args['type'] ?>][reminder][<?php echo $args['index'] ?>][id]" value="<?php echo $args['id'] ?>">
		</th>
		<?php if( $args['show_remove_btn'] ) { ?>
			<td>
				<div class="<?php echo $prefix ?>settings-repeater-remove">
					<i class="drplus-icon-trash"></i>
				</div>
			</td>
		<?php } ?>
	</tr>
	<tr class="<?php echo $prefix ?>settings-gateway-pattern-row">
		<th>
			<label for="<?php echo $prefix ?>settings-notif-<?php echo $args['type'] ?>-before-app-pattern-<?php echo $args['index'] ?>"><?php esc_html_e( 'Pattern code', 'drplus' ) ?></label>
		</th>

		<td>
			<input
				type="text"
				name="<?php echo $prefix ?>settings[reserve_notification][<?php echo $args['type'] ?>][reminder][<?php echo $args['index'] ?>][pattern]"
				id="<?php echo $prefix ?>settings-notif-<?php echo $args['type'] ?>-before-app-pattern-<?php echo $args['index'] ?>"
				class="regular-text ltr <?php echo $prefix ?>settings-pattern"
				value="<?php echo esc_attr( $args['pattern'] ?? '' ) ?>"
			>
		</td>
	</tr>
	<tr>
		<th>
			<label for="<?php echo $prefix ?>settings-notif-<?php echo $args['type'] ?>-before-app-timing-<?php echo $args['index'] ?>"><?php esc_html_e( 'Timing', 'drplus' ) ?></label>
		</th>

		<td>
			<select
			name="<?php echo $prefix ?>settings[reserve_notification][<?php echo $args['type'] ?>][reminder][<?php echo $args['index'] ?>][timing]"
			id="<?php echo $prefix ?>settings-notif-<?php echo $args['type'] ?>-before-app-timing-<?php echo $args['index'] ?>"
			class="drplus-select2 <?php echo $prefix ?>settings-notif-select"
			>
				<?php foreach( SMS::reminder_timing_options() as $key => $label ) { ?>
					<option value="<?php echo esc_attr( $key ) ?>" <?php selected( $args['timing'] ?? '-1', esc_attr( $key ) ) ?>><?php echo esc_html( $label ) ?></option>
				<?php } ?>
			</select>
		</td>
	</tr>
	<tr>
		<th>
			<label for="<?php echo $prefix ?>settings-notif-<?php echo $args['type'] ?>before-app-message-<?php echo $args['index'] ?>"><?php esc_html_e( 'Notification message', 'drplus' ) ?></label>				
		</th>

		<td>
			<textarea
				name="<?php echo $prefix ?>settings[reserve_notification][<?php echo $args['type'] ?>][reminder][<?php echo $args['index'] ?>][message]"
				id="<?php echo $prefix ?>settings-notif-<?php echo $args['type'] ?>before-app-message-<?php echo $args['index'] ?>"
				class="large-text <?php echo $prefix ?>settings-message"
				rows="5"
			><?php echo esc_textarea( $args['message'] ?? '' ) ?> </textarea>
			<p class="description"><?php echo esc_html__( 'You can use these variables in the message', 'drplus' ) ?>:</p>
			<?php Utils::variables_html( SMS::reserve_variables(), true ) ?>
		</td>
	</tr>
</table>