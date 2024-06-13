<?php

use MediaWiki\HTMLForm\HTMLForm;

/**
 * @covers \Licenses
 */
class LicensesTest extends MediaWikiUnitTestCase {

	public function testLicenses() {
		$str = "
* Free licenses:
** GFDL|Debian disagrees
";

		$htmlform = $this->createMock( HTMLForm::class );

		$lc = new Licenses( [
			'fieldname' => 'FooField',
			'type' => 'select',
			'section' => 'description',
			'id' => 'wpLicense',
			'label' => 'A label text', # Note can't test label-message because $wgOut is not defined
			'name' => 'AnotherName',
			'licenses' => $str,
			'parent' => $htmlform
		] );
		$this->assertInstanceOf( Licenses::class, $lc );
	}
}
