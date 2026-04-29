<?php
declare( strict_types = 1 );
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\Parser\Parsoid;

use MediaWiki\Category\TrackingCategories;
use MediaWiki\Language\LanguageConverterFactory;
use MediaWiki\Parser\ParserFactory;
use MediaWiki\Parser\Parsoid\Config\DataAccess;
use MediaWiki\Parser\Parsoid\Config\PageConfigFactory;
use MediaWiki\Parser\Parsoid\ParsoidParser;
use MediaWiki\Parser\Parsoid\ParsoidParserFactory;
use MediaWiki\Title\NamespaceInfo;
use MediaWikiUnitTestCase;
use Wikimedia\Parsoid\Config\SiteConfig;

/**
 * @group Parsoid
 * @covers \MediaWiki\Parser\Parsoid\ParsoidParserFactory
 */
class ParsoidParserFactoryTest extends MediaWikiUnitTestCase {

	protected SiteConfig $siteConfig;
	protected DataAccess $dataAccess;
	protected PageConfigFactory $pageConfigFactory;
	protected LanguageConverterFactory $languageConverterFactory;
	protected ParserFactory $legacyParserFactory;
	protected NamespaceInfo $namespaceInfo;
	protected TrackingCategories $trackingCategories;

	protected function setUp(): void {
		parent::setUp();
		$this->siteConfig = $this->createMock( SiteConfig::class );
		$this->dataAccess = $this->createMock( DataAccess::class );
		$this->pageConfigFactory = $this->createMock( PageConfigFactory::class );
		$this->languageConverterFactory = $this->createMock( LanguageConverterFactory::class );
		$this->legacyParserFactory = $this->createMock( ParserFactory::class );
		$this->namespaceInfo = $this->createMock( NamespaceInfo::class );
		$this->trackingCategories = $this->createMock( TrackingCategories::class );
	}

	public function testCreate() {
		$factory = new ParsoidParserFactory(
			$this->siteConfig,
			$this->dataAccess,
			$this->pageConfigFactory,
			$this->languageConverterFactory,
			$this->namespaceInfo,
			$this->trackingCategories,
			$this->legacyParserFactory,
		);
		$this->assertInstanceOf( ParsoidParser::class, $factory->create() );
	}
}
