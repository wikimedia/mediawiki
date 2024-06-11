<?php
namespace MediaWiki\Tests\Unit\Page;

use File;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Page\PageLookup;
use MediaWiki\Page\PageStoreRecord;
use MediaWiki\Page\RedirectStore;
use MediaWiki\Title\TitleParser;
use MediaWiki\Title\TitleValue;
use MediaWikiUnitTestCase;
use Psr\Log\NullLogger;
use RepoGroup;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * @covers \MediaWiki\Page\RedirectStore
 */
class RedirectStoreUnitTest extends MediaWikiUnitTestCase {

	private IConnectionProvider $connectionProvider;

	private PageLookup $pageLookup;

	private RepoGroup $repoGroup;

	private RedirectStore $redirectStore;

	protected function setUp(): void {
		parent::setUp();

		$this->connectionProvider = $this->createMock( IConnectionProvider::class );
		$this->pageLookup = $this->createMock( PageLookup::class );
		$this->repoGroup = $this->createMock( RepoGroup::class );

		$this->redirectStore = new RedirectStore(
			$this->connectionProvider,
			$this->pageLookup,
			$this->createMock( TitleParser::class ),
			$this->repoGroup,
			new NullLogger()
		);
	}

	public function testShouldCacheLookupResultWhenPageDoesNotExist(): void {
		$page = PageIdentityValue::localIdentity( 1, NS_MAIN, 'Test' );

		$this->pageLookup->expects( $this->once() )
			->method( 'getPageByReference' )
			->with( $page )
			->willReturn( null );

		$this->connectionProvider->expects( $this->never() )
			->method( $this->anything() );

		$this->redirectStore->getRedirectTarget( $page );
		$target = $this->redirectStore->getRedirectTarget( $page );

		$this->assertNull( $target );
	}

	public function testShouldCacheLookupResultWhenPageIsNotARedirect(): void {
		$page = PageIdentityValue::localIdentity( 1, NS_MAIN, 'Test' );

		$this->pageLookup->expects( $this->once() )
			->method( 'getPageByReference' )
			->with( $page )
			->willReturn( new PageStoreRecord( (object)[
				'page_id' => 1,
				'page_namespace' => NS_MAIN,
				'page_title' => 'Test',
				'page_is_redirect' => 0,
				'page_is_new' => 0,
				'page_latest' => 1,
				'page_touched' => '20210101000000',
			], PageStoreRecord::LOCAL ) );

		$this->connectionProvider->expects( $this->never() )
			->method( $this->anything() );

		$this->redirectStore->getRedirectTarget( $page );
		$target = $this->redirectStore->getRedirectTarget( $page );

		$this->assertNull( $target );
	}

	public function testShouldCacheLookupResultForFileWhenFileDoesNotExist(): void {
		$page = PageIdentityValue::localIdentity( 1, NS_FILE, 'Test.png' );

		$this->repoGroup->expects( $this->once() )
			->method( 'findFile' )
			->with( $page )
			->willReturn( false );

		$this->pageLookup->expects( $this->once() )
			->method( 'getPageByReference' )
			->with( $page )
			->willReturn( null );

		$this->connectionProvider->expects( $this->never() )
			->method( $this->anything() );

		$this->redirectStore->getRedirectTarget( $page );
		$target = $this->redirectStore->getRedirectTarget( $page );

		$this->assertNull( $target );
	}

	public function testShouldCacheLookupResultForLocalFileWhenPageDoesNotExist(): void {
		$page = PageIdentityValue::localIdentity( 1, NS_FILE, 'Test.png' );

		$file = $this->createMock( File::class );
		$file->method( 'isLocal' )
			->willReturn( true );

		$this->repoGroup->expects( $this->once() )
			->method( 'findFile' )
			->with( $page )
			->willReturn( $file );

		$this->pageLookup->expects( $this->once() )
			->method( 'getPageByReference' )
			->with( $page )
			->willReturn( null );

		$this->connectionProvider->expects( $this->never() )
			->method( $this->anything() );

		$this->redirectStore->getRedirectTarget( $page );
		$target = $this->redirectStore->getRedirectTarget( $page );

		$this->assertNull( $target );
	}

	public function testShouldCacheLookupResultForForeignFileWhenItIsNotARedirect(): void {
		$page = PageIdentityValue::localIdentity( 1, NS_FILE, 'Test.png' );

		$file = $this->createMock( File::class );
		$file->method( 'isLocal' )
			->willReturn( false );
		$file->method( 'getRedirected' )
			->willReturn( null );
		$file->method( 'getName' )
			->willReturn( 'Test.png' );

		$this->repoGroup->expects( $this->once() )
			->method( 'findFile' )
			->with( $page )
			->willReturn( $file );

		$this->pageLookup->expects( $this->never() )
			->method( $this->anything() );

		$this->connectionProvider->expects( $this->never() )
			->method( $this->anything() );

		$this->redirectStore->getRedirectTarget( $page );
		$target = $this->redirectStore->getRedirectTarget( $page );

		$this->assertNull( $target );
	}

	public function testShouldCacheLookupResultForForeignFileWithInvalidTarget(): void {
		$page = PageIdentityValue::localIdentity( 1, NS_FILE, 'Test.png' );

		$file = $this->createMock( File::class );
		$file->method( 'isLocal' )
			->willReturn( false );
		$file->method( 'getRedirected' )
			->willReturn( 'Test.png' );
		$file->method( 'getName' )
			->willReturn( 'Test.png' );

		$this->repoGroup->expects( $this->once() )
			->method( 'findFile' )
			->with( $page )
			->willReturn( $file );

		$this->pageLookup->expects( $this->never() )
			->method( $this->anything() );

		$this->connectionProvider->expects( $this->never() )
			->method( $this->anything() );

		$this->redirectStore->getRedirectTarget( $page );
		$target = $this->redirectStore->getRedirectTarget( $page );

		$this->assertNull( $target );
	}

	public function testShouldCacheLookupResultForForeignFileWithValidTarget(): void {
		$page = PageIdentityValue::localIdentity( 1, NS_FILE, 'Test.png' );

		$file = $this->createMock( File::class );
		$file->method( 'isLocal' )
			->willReturn( false );
		$file->method( 'getRedirected' )
			->willReturn( 'Test.png' );
		$file->method( 'getName' )
			->willReturn( 'Target.png' );

		$this->repoGroup->expects( $this->once() )
			->method( 'findFile' )
			->with( $page )
			->willReturn( $file );

		$this->pageLookup->expects( $this->never() )
			->method( $this->anything() );

		$this->connectionProvider->expects( $this->never() )
			->method( $this->anything() );

		$this->redirectStore->getRedirectTarget( $page );

		$expected = new TitleValue( NS_FILE, 'Target.png' );
		$target = $this->redirectStore->getRedirectTarget( $page );

		$this->assertNotNull( $target );
		$this->assertTrue( $expected->isSameLinkAs( $target ) );
	}

	public function testShouldVaryLookupResultCacheByPage(): void {
		$firstPage = PageIdentityValue::localIdentity( 1, NS_MAIN, 'Test' );
		$secondPage = PageIdentityValue::localIdentity( 2, NS_MAIN, 'Test2' );

		$this->pageLookup->expects( $this->exactly( 2 ) )
			->method( 'getPageByReference' )
			->willReturnMap( [
				[ $firstPage, null ],
				[ $secondPage, null ],
			] );

		$this->connectionProvider->expects( $this->never() )
			->method( $this->anything() );

		$this->redirectStore->getRedirectTarget( $firstPage );
		$firstTarget = $this->redirectStore->getRedirectTarget( $firstPage );

		$this->redirectStore->getRedirectTarget( $secondPage );
		$secondTarget = $this->redirectStore->getRedirectTarget( $secondPage );

		$this->assertNull( $firstTarget );
		$this->assertNull( $secondTarget );
	}

	public function testShouldInvalidateCachedLookupResultForPage(): void {
		$page = PageIdentityValue::localIdentity( 1, NS_MAIN, 'Test' );

		$this->pageLookup->expects( $this->exactly( 2 ) )
			->method( 'getPageByReference' )
			->with( $page )
			->willReturnOnConsecutiveCalls(
				null,
				new PageStoreRecord( (object)[
					'page_id' => 1,
					'page_namespace' => NS_MAIN,
					'page_title' => 'Test',
					'page_is_redirect' => 0,
					'page_is_new' => 0,
					'page_latest' => 1,
					'page_touched' => '20210101000000',
				], PageStoreRecord::LOCAL )
			);

		$this->connectionProvider->expects( $this->never() )
			->method( $this->anything() );

		$this->redirectStore->getRedirectTarget( $page );
		$firstTarget = $this->redirectStore->getRedirectTarget( $page );

		$this->redirectStore->clearCache( $page );

		$this->redirectStore->getRedirectTarget( $page );
		$secondTarget = $this->redirectStore->getRedirectTarget( $page );

		$this->assertNull( $firstTarget );
		$this->assertNull( $secondTarget );
	}
}
