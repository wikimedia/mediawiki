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
			'html content',
			[],
			[],
			'1.x',
			[ 'content-language' => null ]
		);

		$defaultExpiration = $this->getServiceContainer()->getMainConfig()->get(
			MainConfigNames::ParserCacheExpireTime
		);

		$original = new ParserOutput();
		$output = PageBundleParserOutputConverter::parserOutputFromPageBundle( $pageBundle, $original );
		$this->assertSame( $defaultExpiration, $output->getCacheExpiry(), "aa" );

		$original->updateCacheExpiry( 100 );
		$output = PageBundleParserOutputConverter::parserOutputFromPageBundle( $pageBundle, $original );
		$this->assertSame( 100, $output->getCacheExpiry(), "bb" );
	}

}
