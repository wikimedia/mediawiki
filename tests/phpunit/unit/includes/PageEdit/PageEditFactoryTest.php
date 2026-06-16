<?php

namespace MediaWiki\Test\Unit\PageEdit;

use MediaWiki\Config\HashConfig;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Content\Transform\ContentTransformer;
use MediaWiki\EditPage\Constraint\EditConstraintFactory;
use MediaWiki\EditPage\PageEditingHelper;
use MediaWiki\Language\Language;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\PageEdit\PageEdit;
use MediaWiki\PageEdit\PageEditFactory;
use MediaWiki\PageEdit\PageEditInputs;
use MediaWiki\Permissions\RateLimiter;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\ShadowPage\ShadowPageLoader;
use MediaWiki\Title\TitleFormatter;
use MediaWiki\User\UserOptionsLookup;
use MediaWiki\Watchlist\WatchedItemStoreInterface;
use MediaWiki\Watchlist\WatchlistManager;
use MediaWikiUnitTestCase;
use Psr\Log\LoggerInterface;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * @covers \MediaWiki\PageEdit\PageEditFactory
 */
class PageEditFactoryTest extends MediaWikiUnitTestCase {

	public function testNewPageEdit() {
		$factory = new PageEditFactory(
			new ServiceOptions(
				PageEditFactory::CONSTRUCTOR_OPTIONS,
				new HashConfig( [
					MainConfigNames::EnableWatchlistLabels => false,
					MainConfigNames::UseNPPatrol => true,
					MainConfigNames::UseRCPatrol => true,
				] )
			),
			$this->createMock( IContentHandlerFactory::class ),
			$this->createMock( EditConstraintFactory::class ),
			$this->createMock( IConnectionProvider::class ),
			$this->createMock( Language::class ),
			$this->createMock( ContentTransformer::class ),
			$this->createMock( LoggerInterface::class ),
			$this->createMock( PageEditingHelper::class ),
			$this->createMock( RateLimiter::class ),
			$this->createMock( RevisionStore::class ),
			$this->createMock( ShadowPageLoader::class ),
			$this->createMock( TitleFormatter::class ),
			$this->createMock( UserOptionsLookup::class ),
			$this->createMock( WatchlistManager::class ),
			$this->createMock( WatchedItemStoreInterface::class ),
			$this->createMock( WikiPageFactory::class ),
		);
		$this->assertInstanceOf(
			PageEdit::class,
			$factory->newPageEdit( $this->createMock( PageEditInputs::class ) )
		);
	}

}
