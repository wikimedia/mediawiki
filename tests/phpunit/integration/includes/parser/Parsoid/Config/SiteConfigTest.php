<?php

namespace MediaWiki\Tests\Parser\Parsoid\Config;

use MediaWiki\Content\TextContentHandler;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\Parser\Parsoid\Config\SiteConfig
 */
class SiteConfigTest extends MediaWikiIntegrationTestCase {

	public static function provideSupportsContentModels() {
		yield [ CONTENT_MODEL_WIKITEXT, true ];
		yield [ CONTENT_MODEL_JSON, true ];
		yield [ CONTENT_MODEL_JAVASCRIPT, false ];
		yield [ 'with-text', true ];
		yield [ 'xyzzy', false ];
	}

	/**
	 * @dataProvider provideSupportsContentModels
	 */
	public function testSupportsContentModel( $model, $expected ) {
		$contentHandlers = $this->getConfVar( 'ContentHandlers' );
		$this->overrideConfigValue( 'ContentHandlers', [
			'with-text' => [ 'factory' => static function () {
				return new TextContentHandler( 'with-text', [ CONTENT_FORMAT_WIKITEXT, 'plain/test' ] );
			} ],
		] + $contentHandlers );

		$this->resetServices();
		$siteConfig = $this->getServiceContainer()->getParsoidSiteConfig();
		$this->assertSame( $expected, $siteConfig->supportsContentModel( $model ) );
	}
}
