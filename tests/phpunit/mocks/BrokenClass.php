<?php

namespace MediaWiki\Tests;

/**
 * A class that exists but cannot be instantiated because
 * its base class does not exist. For testing logic related
 * to class_exists and is_callable checks.
 */
class BrokenClass extends \Some\Thing\ThatDoesNotExist_8723465 {
	public function aMethod() {
		// noop
	}
}
