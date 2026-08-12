<?php

namespace MediaWiki\Tests\Rest\Handler;

use MediaWiki\Rest\Handler\UserBarFragmentHandler;
use MediaWiki\Rest\Module\AudienceDesignation;
use MediaWiki\Rest\RequestData;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\Rest\Handler\FragmentHandler
 * @covers \MediaWiki\Rest\Handler\UserBarFragmentHandler
 */
class UserBarFragmentHandlerTest extends MediaWikiIntegrationTestCase {

	use HandlerTestTrait;

	private function newHandler(): UserBarFragmentHandler {
		return new UserBarFragmentHandler();
	}

	public function testGetUserBarFragment() {
		$req = new RequestData( [] );
		$handler = $this->newHandler();

		$resp = $this->executeHandler( $handler, $req );

		$this->assertSame( 200, $resp->getStatusCode() );
		$this->assertSame( 'text/html; charset=utf-8', $resp->getHeaderLine( 'content-type' ) );
		$this->assertStringContainsString( '<!-- Fragment served by REST API -->', (string)$resp->getBody() );

		// Since it belongs to the -internal Fragment module and it is hidden from production via wmf-config,
		// this test protects against a typo in the suffix silently disabling the module.
		$this->assertSame(
			AudienceDesignation::INTERNAL,
			AudienceDesignation::fromModuleId( 'fragments/v0-internal' )
		);
	}
}
