<?php

class LicensesTest extends PHPUnit_Framework_TestCase {

	function testLicenses() {
		$str = "
* Free licenses:
** GFDL|Debian disagrees
";

		$lc = new Licenses( array( 'licenses' => $str ) );
		$this->assertTrue( is_a( $lc, 'Licenses' ), 'Correct class' );
	}
}
