<?php

/**
 * @group API
 * @group Database
 * @group medium
 * @covers ApiFormatJson
 */
class ApiFormatJsonTest extends ApiFormatTestBase {

	public function testValidSyntax( ) {
		$data = $this->apiRequest( 'json', array( 'action' => 'query', 'meta' => 'siteinfo' ) );

		$this->assertInternalType( 'array', json_decode( $data, true ) );
		$this->assertGreaterThan( 0, count( (array)$data ) );
	}
}
