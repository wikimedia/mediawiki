<?php

class SeleniumLoader {
	static function load() {
		require_once( 'Testing/Selenium.php' );
		require_once( 'PHPUnit/Framework.php' );
		require_once( 'PHPUnit/Extensions/SeleniumTestCase.php' );
	}
}
