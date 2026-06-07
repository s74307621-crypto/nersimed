<?php
namespace DrPlus\Updates;

class V1_2_9_1 {
	public static function update() {
		flush_rewrite_rules(); // for modify hospital category slug
	}
}