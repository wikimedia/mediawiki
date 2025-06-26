<?php
declare( strict_types = 1 );

namespace MediaWiki\Tests\Parser\Parsoid\Config;

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\Category\TrackingCategories;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Content\Transform\ContentTransformer;
use MediaWiki\FileRepo\RepoGroup;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\File\BadFileLookup;
use MediaWiki\Parser\ParserFactory;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\Parsoid\Config\SiteConfig;
use MediaWiki\Title\TitleValue;
use MediaWikiIntegrationTestCase;
use Wikimedia\Parsoid\Config\PageConfig;
use Wikimedia\Rdbms\ReadOnlyMode;

/**
 * @covers \MediaWiki\Parser\Parsoid\Config\DataAccess
 */
class DataAccessTest extends MediaWikiIntegrationTestCase {

	private const DEFAULT_CONFIG = [
		MainConfigNames::SVGMaxSize => 5120,
	];

	private function createMockOrOverride( string $class, array $overrides ) {
		return $overrides[$class] ?? $this->createNoOpMock( $class );
	}

	/**
	 * TODO it might save code to have this helper always return a
	 * TestingAccessWrapper?
	 *
	 * @param array $configOverrides Configuration options overriding default ServiceOptions config defined in
	 *                               DEFAULT_CONFIG above.
	 * @param array $serviceOverrides
	 *
	 * @return DataAccess
	 */
	private function createDataAccess(
		array $configOverrides = [],
		array $serviceOverrides = []
	): SiteConfig {
		return new DataAccess(
			new ServiceOptions(
				DataAccess::CONSTRUCTOR_OPTIONS,
				array_replace( self::DEFAULT_CONFIG, $configOverrides )
			),
			$this->createMockOrOverride( RepoGroup::class, $serviceOverrides ),
			$this->createMockOrOverride( BadFileLookup::class, $serviceOverrides ),
			$this->createMockOrOverride( HookContainer::class, $serviceOverrides ),
			$this->createMockOrOverride( ContentTransformer::class, $serviceOverrides ),
			$this->createMockOrOverride( TrackingCategories::class, $serviceOverrides ),
			$this->createMockOrOverride( ReadOnlyMode::class, $serviceOverrides ),
			$this->createMockOrOverride( ParserFactory::class, $serviceOverrides ),
			$this->createMockOrOverride( LinkBatchFactory::class, $serviceOverrides )
		);
	}

	public function testAddTrackingCategory() {
		$this->overrideConfigValue( MainConfigNames::LanguageCode, 'qqx' );
		$pageConfig = $this->createMock( PageConfig::class );
		$pageConfig->method( 'getLinkTarget' )->willReturn(
			TitleValue::tryNew( NS_MAIN, 'Main Page' )
		);
		$dataAccess = $this->getServiceContainer()->getParsoidDataAccess();
		$parserOutput = new ParserOutput();
		$dataAccess->addTrackingCategory( $pageConfig, $parserOutput, 'broken-file-category' );
		$this->assertSame(
			[ '(broken-file-category)' ],
			$parserOutput->getCategoryNames()
		);
	}
}
