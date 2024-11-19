<?php

namespace MediaWiki\Tests\Api\Query;

use MediaWiki\Tests\Api\ApiTestCase;

/**
 * @group API
 * @group medium
 *
 * @covers \MediaWiki\Api\ApiQueryDisabled
 */
class ApiQueryDisabledTest extends ApiTestCase {
	public function testDisabled() {
		$this->mergeMwGlobalArrayValue( 'wgAPIPropModules',
			[ 'categories' => 'ApiQueryDisabled' ] );

		$data = $this->doApiRequest( [
			'action' => 'query',
			'prop' => 'categories',
		] );

		$this->assertArrayHasKey( 'warnings', $data[0] );
		$this->assertArrayHasKey( 'categories', $data[0]['warnings'] );
		$this->assertArrayHasKey( 'warnings', $data[0]['warnings']['categories'] );

		$this->assertEquals( 'The "categories" module has been disabled.',
			$data[0]['warnings']['categories']['warnings'] );
	}
}
