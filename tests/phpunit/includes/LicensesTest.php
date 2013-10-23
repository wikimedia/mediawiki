<?php

class LicensesTest extends MediaWikiTestCase {

	public function testLicenses() {
		$str = "
* Free licenses:
** GFDL|Debian disagrees
";

		$lc = new Licenses( array(
			'fieldname' => 'FooField',
			'type' => 'select',
			'section' => 'description',
			'id' => 'wpLicense',
			'label' => 'A label text', # Note can't test label-message because $wgOut is not defined
			'name' => 'AnotherName',
			'licenses' => $str,
		) );
		$this->assertThat( $lc, $this->isInstanceOf( 'Licenses' ) );
	}
}
