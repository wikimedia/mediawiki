<?php

class LicensesTest extends PHPUnit_Framework_TestCase {

	function testLicenses() {
		$str = "
* Free licenses:
** GFDL|Debian disagrees
";

		$lc = new Licenses( array( 'licenses' => $str ) );
		$this->assertThat( $lc, $this->isInstanceOf( 'Licenses' ) );
	}
}
