<?php

namespace MediaWiki\Tests\Integration\Specials\Helpers;

use MediaWiki\Context\RequestContext;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Request\WebRequest;
use MediaWiki\Specials\Helpers\LoginHelper;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\Specials\Helpers\LoginHelper
 */
class LoginHelperTest extends MediaWikiIntegrationTestCase {

	public function testGetPreservedParams() {
		$expectedParams = [
			'display' => 'popup',
			'uselang' => 'de',
			'returnto' => 'Main_Page',
			'hook' => 'x',
		];
		$requestParams = $expectedParams + [
			'foo' => 'bar',
		];
		$expectedReset = false;

		$this->setTemporaryHook( 'AuthPreserveQueryParams',
			function ( &$params, $options ) use ( $expectedParams, &$expectedReset ) {
				$expectedParams = array_diff_key( $expectedParams, [ 'hook' => true ] );
				$this->assertArrayEquals( $expectedParams, $params, ordered: false, named: true );
				$this->assertInstanceOf( WebRequest::class, $options['request'] );
				$this->assertSame( $expectedReset, $options['reset'] ?? false );
				$params['hook'] = $options['request']->getVal( 'hook' );
			}
		);
		$context = new RequestContext();
		$context->setRequest( new FauxRequest( $requestParams ) );
		$loginHelper = new LoginHelper( $context );

		$this->assertEquals( $expectedParams, $loginHelper->getPreservedParams( [] ) );

		$request = $context->getRequest();
		$context->setRequest( new FauxRequest() );
		$this->assertEquals( $expectedParams, $loginHelper->getPreservedParams( [ 'request' => $request ] ) );

		$context->setRequest( $request );
		$expectedReset = true;
		$this->assertEquals( $expectedParams, $loginHelper->getPreservedParams( [ 'reset' => true ] ) );
	}

}
