<?php
declare( strict_types = 1 );

namespace MediaWiki\Tests\Parser\Parsoid;

use MediaWiki\MainConfigNames;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\Parsoid\PageBundleParserOutputConverter;
use MediaWikiIntegrationTestCase;
use Wikimedia\Parsoid\Core\HtmlPageBundle;

/**
 * @covers \MediaWiki\Parser\Parsoid\PageBundleParserOutputConverter
 */
class PageBundleParserOutputConverterIntegrationTest extends MediaWikiIntegrationTestCase {
	public function testParserOutputFromPageBundleShouldPreserveMetadata() {
		$pageBundle = new HtmlPageBundle(
			html: 'html content',
			parsoid: [],
			mw: [],
			version: '1.x',
			headers: [ 'content-language' => null ]
		);

		$defaultExpiration = $this->getServiceContainer()->getMainConfig()->get(
			MainConfigNames::ParserCacheExpireTime
		);

		$original = new ParserOutput();
		$output = PageBundleParserOutputConverter::parserOutputFromPageBundle( $pageBundle, $original );
		$this->assertSame( $defaultExpiration, $output->getCacheExpiry(),
			"Cache expiration doesn't match default expiry." );

		$original->updateCacheExpiry( 100 );
		$output = PageBundleParserOutputConverter::parserOutputFromPageBundle( $pageBundle, $original );
		$this->assertSame( 100, $output->getCacheExpiry(),
			"Cache expiration doesn't matched updated reduced expiry." );
	}

}
