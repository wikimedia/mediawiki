<?php

namespace MediaWiki\Tests\Rest\Handler;

use MediaWiki\Config\HashConfig;
use MediaWiki\MainConfigNames;
use MediaWiki\MainConfigSchema;
use MediaWiki\Rest\Handler\OpenSearchDescriptionHandler;
use MediaWiki\Rest\RequestData;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\Rest\Handler\OpenSearchDescriptionHandler
 */
class OpenSearchDescriptionHandlerTest extends MediaWikiIntegrationTestCase {

	use HandlerTestTrait;

	private function newHandler() {
		$config = new HashConfig( [
			MainConfigNames::Favicon => MainConfigSchema::getDefaultValue(
				MainConfigNames::Favicon
			),
			MainConfigNames::OpenSearchTemplates => MainConfigSchema::getDefaultValue(
				MainConfigNames::OpenSearchTemplates
			),
		] );
		$urlUtils = $this->getServiceContainer()->getUrlUtils();

		$handler = new OpenSearchDescriptionHandler( $config, $urlUtils );
		return $handler;
	}

	public function testOpenSearchDescription() {
		$req = new RequestData( [] );
		$handler = $this->newHandler( $req );

		$resp = $this->executeHandler( $handler, $req );
		$this->assertSame(
			'application/opensearchdescription+xml',
			$resp->getHeaderLine( 'content-type' )
		);

		$xml = (string)$resp->getBody();
		$this->assertMatchesRegularExpression( '!^<\?xml!', $xml );
		$this->assertMatchesRegularExpression( '!<OpenSearchDescription!', $xml );
		$this->assertMatchesRegularExpression( '!<Url type="text/html" method="get" template=!', $xml );
	}

	// TODO: write tests for wgOpenSearchTemplates and the OpenSearchUrls hook.
}
