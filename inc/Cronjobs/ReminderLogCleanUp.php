<?php

use DrPlus\Model\ReminderLog;
use DrPlus\Utils;
use DrPlus\Utils\Date;

if ( !wp_next_scheduled( 'drplus_reminder_log_clean_up_hook' ) ) {
	wp_schedule_event( time(), 'twicedaily', 'drplus_reminder_log_clean_up_hook' );
}

function drplus_reminder_log_clean_up_exec() {
	// Delete reminders sent or failed over 30 days ago
	$date = Date::maybe_j2g( date_i18n( 'Y-m-d', strtotime( '-1 days' ) ) );
	$time = date_i18n( 'H:i:s' );
	ReminderLog::query()->where( 'send_time', '<', Utils::convert_chars( "{$date} {$time}" ) )->whereIn( 'status', ['not_sent', 'cancelled'] )->delete();
}
add_action( 'drplus_reminder_log_clean_up_hook', 'drplus_reminder_log_clean_up_exec' );