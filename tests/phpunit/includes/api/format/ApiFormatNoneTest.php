<?php

/**
 * @group API
 * @group Database
 * @group medium
 * @covers ApiFormatNone
 */
class ApiFormatNoneTest extends ApiFormatTestBase {

	public function testValidSyntax( ) {
		$data = $this->apiRequest( 'none', array( 'action' => 'query', 'meta' => 'siteinfo' ) );

		$this->assertEquals( '', $data ); // No output!
	}
}
