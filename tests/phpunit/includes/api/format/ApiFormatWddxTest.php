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

		if ( wfIsHHVM() && null === wddx_deserialize( wddx_serialize_value("Test for &") )) {
			# Some version of HHVM is miss behaving when deserializing an
			# ampersand, it returns NULL instead of the expected data.
			#
			# https://phabricator.wikimedia.org/T75531
			$this->markTestSkipped( "wddx_deserialize is bugged under this version of HHVM" );
		}

		$data = $this->apiRequest( 'wddx', array( 'action' => 'query', 'meta' => 'siteinfo' ) );

		$this->assertInternalType( 'array', wddx_deserialize( $data ) );
		$this->assertGreaterThan( 0, count( (array)$data ) );
	}
}
