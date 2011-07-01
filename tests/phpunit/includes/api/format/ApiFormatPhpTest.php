<?php

/**
 * @group API
 * @group Database
 */
class ApiFormatPhpTest extends ApiFormatTestBase {
	function testValidPHPSyntax() {
		
		$data = $this->apiRequest( 'php', array( 'action' => 'query', 'meta' => 'siteinfo' ) );
		
		$this->assertInternalType( 'array', unserialize( $data ) );
		$this->assertGreaterThan( 0, count( (array) $data ) );
		
		
	}

}
