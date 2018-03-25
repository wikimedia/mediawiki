<?php

/**
 * @group API
 * @group medium
 *
 * @covers ApiDisabled
 */
class ApiDisabledTest extends ApiTestCase {
	public function testDisabled() {
		$this->mergeMwGlobalArrayValue( 'wgAPIModules',
			[ 'login' => 'ApiDisabled' ] );

		$this->setExpectedException( ApiUsageException::class,
			'The "login" module has been disabled.' );

		$this->doApiRequest( [ 'action' => 'login' ] );
	}
}
