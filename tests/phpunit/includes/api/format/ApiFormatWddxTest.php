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
		if ( wfIsHHVM() ) {
			# https://phabricator.wikimedia.org/T75531
			# Remove the skip whenever a new HHVM is released
			$this->markTestSkipped( "wddx_deserialize is bugged under HHVM" );
		}

		$data = $this->apiRequest( 'wddx', array( 'action' => 'query', 'meta' => 'siteinfo' ) );

		$this->assertInternalType( 'array', wddx_deserialize( $data ) );
		$this->assertGreaterThan( 0, count( (array)$data ) );
	}
}
