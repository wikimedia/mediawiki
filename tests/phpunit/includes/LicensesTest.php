<?php

class LicensesTest extends PHPUnit_Framework_TestCase {

	function testLicenses() {
		$str = "
* Free licenses:
** GFDL|Debian disagrees
";

		$lc = new Licenses( array(
			'fieldname' => 'FooField',
			'type' => 'select',
			'section' => 'description',
			'id' => 'wpLicense',
			'label-message' => 'license',
			'name' => 'AnotherName', 
			'licenses' => $str,		
		) );
		$this->assertThat( $lc, $this->isInstanceOf( 'Licenses' ) );
	}
}
