<?php

namespace MediaWiki\Tests\Api;

use MediaWiki\Api\ApiEntryPoint;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Tests\MockEnvironment;

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers \MediaWiki\Api\ApiEntryPoint
 */
class ApiEntryPointTest extends ApiTestCase {

	public function testSimpleRequest() {
		$request = new FauxRequest();
		$request->setRequestURL( '/w/api.php' );

		$env = new MockEnvironment( $request );
		$context = $env->makeFauxContext();

		$entryPoint = new ApiEntryPoint(
			$context,
			$env,
			$this->getServiceContainer()
		);

		$entryPoint->enableOutputCapture();
		$entryPoint->run();

		$output = $entryPoint->getCapturedOutput();
		$this->assertStringContainsString( '<!DOCTYPE html>', $output );
		$this->assertStringContainsString( '<title>(pagetitle: (api-help-title))</title>', $output );

		// TODO: Check caching headers and such.
	}

}
