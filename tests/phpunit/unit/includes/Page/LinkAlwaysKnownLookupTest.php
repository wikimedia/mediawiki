<?php

use MediaWiki\FileRepo\RepoGroup;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Page\LinkAlwaysKnownLookup;
use MediaWiki\ShadowPage\ShadowPageLoader;
use MediaWiki\SpecialPage\SpecialPageFactory;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleFactory;
use MediaWiki\Title\TitleFormatter;
use Psr\Log\LoggerInterface;
use Wikimedia\TestingAccessWrapper;

/**
 * @group Page
 * @covers \MediaWiki\Page\LinkAlwaysKnownLookup
 */
class LinkAlwaysKnownLookupTest extends MediaWikiUnitTestCase {

	private HookRunner $hookRunner;
	private TitleFactory $titleFactory;
	private TitleFormatter $titleFormatter;
	private ShadowPageLoader $shadowPageLoader;
	private RepoGroup $repoGroup;
	private SpecialPageFactory $specialPageFactory;
	private LoggerInterface $logger;

	protected function setUp(): void {
		parent::setUp();

		$this->hookRunner = $this->createMock( HookRunner::class );

		$this->titleFactory = $this->createMock( TitleFactory::class );
		// The legacy hook path materializes a Title from the link target.
		$this->titleFactory->method( 'newFromLinkTarget' )
			->willReturn( $this->createMock( Title::class ) );

		$this->titleFormatter = $this->createMock( TitleFormatter::class );
		// A cache key that is unique per (interwiki, namespace, dbkey), like the real one.
		$this->titleFormatter->method( 'getPrefixedDBkey' )
			->willReturnCallback( static fn ( LinkTarget $target ): string =>
				$target->getInterwiki() . ':' . $target->getNamespace() . ':' . $target->getDBkey() );

		$this->shadowPageLoader = $this->createMock( ShadowPageLoader::class );
		$this->repoGroup = $this->createMock( RepoGroup::class );
		$this->specialPageFactory = $this->createMock( SpecialPageFactory::class );
		$this->logger = $this->createMock( LoggerInterface::class );
	}

	private function newLookup(): LinkAlwaysKnownLookup {
		return new LinkAlwaysKnownLookup(
			$this->hookRunner,
			$this->titleFactory,
			$this->titleFormatter,
			$this->shadowPageLoader,
			$this->repoGroup,
			$this->specialPageFactory,
			$this->logger
		);
	}

	private function makeLink(
		int $namespace,
		string $dbKey,
		bool $isExternal = false,
		string $interwiki = ''
	): LinkTarget {
		$link = $this->createMock( LinkTarget::class );
		$link->method( 'getNamespace' )->willReturn( $namespace );
		$link->method( 'getDBkey' )->willReturn( $dbKey );
		$link->method( 'isExternal' )->willReturn( $isExternal );
		$link->method( 'getInterwiki' )->willReturn( $interwiki );
		return $link;
	}

	/**
	 * Assert that none of the resolver collaborators are consulted, e.g. because a hook
	 * decided the result or the link short-circuited earlier.
	 */
	private function expectNoResolvers(): void {
		$this->shadowPageLoader->expects( $this->never() )->method( 'existsForLink' );
		$this->repoGroup->expects( $this->never() )->method( 'findFile' );
		$this->specialPageFactory->expects( $this->never() )->method( 'exists' );
	}

	public function testExternalLinkIsAlwaysKnown(): void {
		// isExternal() short-circuits before any resolver is consulted.
		$this->expectNoResolvers();

		$link = $this->makeLink( NS_MAIN, 'Foo', true, 'en' );
		$this->assertTrue( $this->newLookup()->isAlwaysKnown( $link ) );
	}

	public function testShadowPageIsAlwaysKnown(): void {
		$link = $this->makeLink( NS_MEDIAWIKI, 'Sidebar' );
		$this->shadowPageLoader->expects( $this->once() )
			->method( 'existsForLink' )
			->with( $this->identicalTo( $link ) )
			->willReturn( true );
		// A shadow hit wins before the namespace switch runs.
		$this->repoGroup->expects( $this->never() )->method( 'findFile' );
		$this->specialPageFactory->expects( $this->never() )->method( 'exists' );

		$this->assertTrue( $this->newLookup()->isAlwaysKnown( $link ) );
	}

	/**
	 * @dataProvider provideSpecialPage
	 */
	public function testSpecialPage( bool $exists ): void {
		$link = $this->makeLink( NS_SPECIAL, 'Recentchanges' );
		// The shadow loader is always consulted first for non-external links.
		$this->shadowPageLoader->expects( $this->once() )
			->method( 'existsForLink' )
			->with( $this->identicalTo( $link ) )
			->willReturn( false );
		$this->specialPageFactory->expects( $this->once() )
			->method( 'exists' )
			->with( 'Recentchanges' )
			->willReturn( $exists );
		$this->repoGroup->expects( $this->never() )->method( 'findFile' );

		$this->assertSame( $exists, $this->newLookup()->isAlwaysKnown( $link ) );
	}

	public static function provideSpecialPage(): iterable {
		yield 'existing special page' => [ true ];
		yield 'unknown special page' => [ false ];
	}

	/**
	 * @dataProvider provideFileNamespace
	 */
	public function testExistingFileIsAlwaysKnown( int $namespace ): void {
		$link = $this->makeLink( $namespace, 'Example.png' );
		$this->shadowPageLoader->expects( $this->once() )
			->method( 'existsForLink' )
			->with( $this->identicalTo( $link ) )
			->willReturn( false );
		$this->repoGroup->expects( $this->once() )
			->method( 'findFile' )
			->with( $this->identicalTo( $link ) )
			->willReturn( true );
		$this->specialPageFactory->expects( $this->never() )->method( 'exists' );

		$this->assertTrue( $this->newLookup()->isAlwaysKnown( $link ) );
	}

	public static function provideFileNamespace(): iterable {
		yield 'file namespace' => [ NS_FILE ];
		yield 'media namespace' => [ NS_MEDIA ];
	}

	public function testMissingFileIsNotKnown(): void {
		$link = $this->makeLink( NS_FILE, 'Missing.png' );
		$this->shadowPageLoader->expects( $this->once() )
			->method( 'existsForLink' )
			->with( $this->identicalTo( $link ) )
			->willReturn( false );
		$this->repoGroup->expects( $this->once() )
			->method( 'findFile' )
			->with( $this->identicalTo( $link ) )
			->willReturn( false );
		$this->specialPageFactory->expects( $this->never() )->method( 'exists' );

		$this->assertFalse( $this->newLookup()->isAlwaysKnown( $link ) );
	}

	public function testSelfLinkInMainNamespaceIsKnown(): void {
		// An empty DB key represents a fragment-only self link, e.g. [[#Section]].
		$link = $this->makeLink( NS_MAIN, '' );
		$this->shadowPageLoader->expects( $this->once() )
			->method( 'existsForLink' )
			->with( $this->identicalTo( $link ) )
			->willReturn( false );
		// The NS_MAIN branch is a pure db-key check; no file or special-page lookup.
		$this->repoGroup->expects( $this->never() )->method( 'findFile' );
		$this->specialPageFactory->expects( $this->never() )->method( 'exists' );

		$this->assertTrue( $this->newLookup()->isAlwaysKnown( $link ) );
	}

	public function testRegularMissingLinkIsNotKnown(): void {
		$link = $this->makeLink( NS_MAIN, 'Some_article' );
		$this->shadowPageLoader->expects( $this->once() )
			->method( 'existsForLink' )
			->with( $this->identicalTo( $link ) )
			->willReturn( false );
		$this->repoGroup->expects( $this->never() )->method( 'findFile' );
		$this->specialPageFactory->expects( $this->never() )->method( 'exists' );

		$this->assertFalse( $this->newLookup()->isAlwaysKnown( $link ) );
	}

	public function testBatchHookDecisionTakesPrecedence(): void {
		// The link would resolve to an existing special page, but a hook decision
		// wins and none of the resolvers are consulted.
		$this->expectNoResolvers();
		$link = $this->makeLink( NS_SPECIAL, 'Recentchanges' );
		$this->hookRunner->method( 'onLinkTargetIsAlwaysKnownBatch' )
			->willReturnCallback( static function ( array $links, array &$isAlwaysKnown ): void {
				$isAlwaysKnown[0] = false;
			} );

		$this->assertFalse( $this->newLookup()->isAlwaysKnown( $link ) );
	}

	public function testLegacyHookDecisionTakesPrecedence(): void {
		// The built-in logic would return false for this link; the legacy hook overrides
		// it, and no resolver is consulted.
		$this->expectNoResolvers();
		$link = $this->makeLink( NS_MAIN, 'Some_article' );
		$this->hookRunner->method( 'onTitleIsAlwaysKnown' )
			->willReturnCallback( static function ( $title, &$isKnown ): void {
				$isKnown = true;
			} );

		$this->assertTrue( $this->newLookup()->isAlwaysKnown( $link ) );
	}

	public function testResultIsCachedAcrossCalls(): void {
		$link = $this->makeLink( NS_SPECIAL, 'Recentchanges' );
		$this->hookRunner->expects( $this->once() )
			->method( 'onLinkTargetIsAlwaysKnownBatch' );
		// Each resolver is consulted only once despite two isAlwaysKnown() calls: the
		// second call is served from the process cache.
		$this->shadowPageLoader->expects( $this->once() )
			->method( 'existsForLink' )
			->willReturn( false );
		$this->specialPageFactory->expects( $this->once() )
			->method( 'exists' )
			->willReturn( true );

		$lookup = $this->newLookup();
		$this->assertTrue( $lookup->isAlwaysKnown( $link ) );
		$this->assertTrue( $lookup->isAlwaysKnown( $link ) );
	}

	public function testPreloadIssuesASingleBatchHookForAllLinks(): void {
		$links = [
			$this->makeLink( NS_MAIN, 'A' ),
			$this->makeLink( NS_MAIN, 'B' ),
			$this->makeLink( NS_MAIN, 'C' ),
		];
		$this->hookRunner->expects( $this->once() )
			->method( 'onLinkTargetIsAlwaysKnownBatch' )
			->with( $this->countOf( 3 ) );
		// Every non-external link consults the shadow loader once; NS_MAIN needs no
		// file or special-page lookup.
		$this->shadowPageLoader->expects( $this->exactly( 3 ) )
			->method( 'existsForLink' )
			->willReturn( false );
		$this->repoGroup->expects( $this->never() )->method( 'findFile' );
		$this->specialPageFactory->expects( $this->never() )->method( 'exists' );

		$this->newLookup()->preload( $links );
	}

	public function testPreloadWarmsCacheSoIsAlwaysKnownDoesNotRecompute(): void {
		$link = $this->makeLink( NS_SPECIAL, 'Recentchanges' );
		$this->hookRunner->expects( $this->once() )
			->method( 'onLinkTargetIsAlwaysKnownBatch' );
		// Resolved during preload(); isAlwaysKnown() then reads the cache.
		$this->specialPageFactory->expects( $this->once() )
			->method( 'exists' )
			->willReturn( true );

		$lookup = $this->newLookup();
		$lookup->preload( [ $link ] );
		$this->assertTrue( $lookup->isAlwaysKnown( $link ) );
	}

	public function testPreloadWithEmptyListDoesNothing(): void {
		$this->hookRunner->expects( $this->never() )
			->method( 'onLinkTargetIsAlwaysKnownBatch' );
		$this->expectNoResolvers();

		$this->newLookup()->preload( [] );
	}

	public function testWarnsAfterTooManyIndividualLookups(): void {
		$lookup = $this->newLookup();
		TestingAccessWrapper::newFromObject( $lookup )->individualLookupsInRequest = 49;
		$this->logger->expects( $this->once() )->method( 'warning' );

		$lookup->isAlwaysKnown( $this->makeLink( NS_MAIN, 'Trigger' ) );
	}
}
