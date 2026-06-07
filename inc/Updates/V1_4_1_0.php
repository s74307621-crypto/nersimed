<?php
namespace DrPlus\Updates;

class V1_4_1_0 {
	public static function update() {
		flush_rewrite_rules();
	}
}