<?php

/**
 * @group API
 * @group Database
 * @group medium
 * @covers ApiFormatWddx
 */
class ApiFormatWddxTest extends ApiFormatTestBase {

	public function testValidSyntax( ) {
		if ( !function_exists( 'wddx_deserialize' ) ) {
			$this->markTestSkipped( "Function 'wddx_deserialize' not exist, skipping." );
		}

		if ( wfIsHHVM() && false === strpos( wddx_serialize_value( "Test for &" ), '&amp;' ) ) {
			# Some version of HHVM fails to escape the ampersand
			#
			# https://phabricator.wikimedia.org/T75531
			$this->markTestSkipped( "wddx_deserialize is bugged under this version of HHVM" );
		}

		$data = $this->apiRequest( 'wddx', array( 'action' => 'query', 'meta' => 'siteinfo' ) );

		$this->assertInternalType( 'array', wddx_deserialize( $data ) );
		$this->assertGreaterThan( 0, count( (array)$data ) );
	}
}
