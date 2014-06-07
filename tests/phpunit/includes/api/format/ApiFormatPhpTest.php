<?php

/**
 * @group API
 * @group Database
 * @group medium
 * @covers ApiFormatPhp
 */
class ApiFormatPhpTest extends ApiFormatTestBase {

	public function testValidSyntax( ) {
		$data = $this->apiRequest( 'php', array( 'action' => 'query', 'meta' => 'siteinfo' ) );

		$this->assertInternalType( 'array', unserialize( $data ) );
		$this->assertGreaterThan( 0, count( (array)$data ) );
	}
}
