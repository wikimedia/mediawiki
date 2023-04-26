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

		$this->expectApiErrorCode( 'moduledisabled' );

		$this->doApiRequest( [ 'action' => 'login' ] );
	}
}
