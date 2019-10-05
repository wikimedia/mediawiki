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

		$this->expectException( ApiUsageException::class );
		$this->expectExceptionMessage( 'The "login" module has been disabled.' );

		$this->doApiRequest( [ 'action' => 'login' ] );
	}
}
