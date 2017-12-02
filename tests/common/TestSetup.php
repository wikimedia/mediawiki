<?php

class TestSetup {
	public static function applyInitialConfig() {
		// Bug T116683 serialize_precision of 100
		// may break testing against floating point values
		// treated with PHP's serialize()
		ini_set( 'serialize_precision', 17 );
	}
}
