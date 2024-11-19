<?php

namespace MediaWiki\Tests\Api;

/**
 * @group API
 * @group medium
 *
 * @covers \MediaWiki\Api\ApiDisabled
 */
class ApiDisabledTest extends ApiTestCase {
	public function testDisabled() {
		$this->mergeMwGlobalArrayValue( 'wgAPIModules',
			[ 'login' => 'ApiDisabled' ] );

		$this->expectApiErrorCode( 'moduledisabled' );

		$this->doApiRequest( [ 'action' => 'login' ] );
	}
}
