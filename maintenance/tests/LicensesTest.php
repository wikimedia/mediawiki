<?php

/**
 * @group Broken
 */
class LicensesTest extends PHPUnit_Framework_TestCase {

	function testLicenses() {
		$str = "
* Free licenses:
** GFLD|Debian disagrees
";

		$lc = new Licenses( $str );
		$this->assertTrue( is_a( $lc, 'Licenses' ), 'Correct class' );
	}
}