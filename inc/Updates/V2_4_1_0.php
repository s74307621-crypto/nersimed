<?php
namespace DrPlus\Updates;

class V2_4_1_0 {
	public static function update() {
		global $wpdb;
		$wpdb->query( "ALTER TABLE `{$wpdb->prefix}drplus_specialists` ADD `name` TEXT NULL AFTER `slug`;" );
	}
}