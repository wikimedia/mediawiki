<?php

/**
 * @group API
 * @group Database
 * @group medium
 * @covers ApiFormatWddx
 */
class ApiFormatWddxTest extends ApiFormatTestBase {

	/**
	 * @requires function wddx_deserialize
	 */
	public function testValidSyntax( ) {
		$data = $this->apiRequest( 'wddx', array( 'action' => 'query', 'meta' => 'siteinfo' ) );

		$this->assertInternalType( 'array', wddx_deserialize( $data ) );
		$this->assertGreaterThan( 0, count( (array)$data ) );
	}
}
