<?php

namespace MediaWiki\Tests\Api;

use MediaWiki\Api\ApiUsageException;
use MediaWiki\MainConfigNames;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use Wikimedia\Rdbms\IDBAccessObject;

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers \MediaWiki\Api\ApiMove
 */
class ApiMoveTest extends ApiTestCase {

	protected function setUp(): void {
		parent::setUp();

		$this->overrideConfigValue( MainConfigNames::WatchlistExpiry, true );
	}

	/**
	 * @param Title $fromTitle
	 * @param Title $toTitle
	 * @param string $id Page id of the page to move
	 * @param array|string|null $opts Options: 'noredirect' to expect no redirect
	 */
	protected function assertMoved( $fromTitle, $toTitle, $id, $opts = null ) {
		$opts = (array)$opts;

		$this->assertTrue( $toTitle->exists( IDBAccessObject::READ_LATEST ),
			"Destination {$toTitle->getPrefixedText()} does not exist" );

		if ( in_array( 'noredirect', $opts ) ) {
			$this->assertFalse( $fromTitle->exists( IDBAccessObject::READ_LATEST ),
				"Source {$fromTitle->getPrefixedText()} exists" );
		} else {
			$this->assertTrue( $fromTitle->exists( IDBAccessObject::READ_LATEST ),
				"Source {$fromTitle->getPrefixedText()} does not exist" );
			$this->assertTrue( $fromTitle->isRedirect( IDBAccessObject::READ_LATEST ),
				"Source {$fromTitle->getPrefixedText()} is not a redirect" );

			$target = $this->getServiceContainer()
				->getRevisionLookup()
				->getRevisionByTitle( $fromTitle )
				->getContent( SlotRecord::MAIN )
				->getRedirectTarget();
			$this->assertSame( $toTitle->getPrefixedText(), $target->getPrefixedText() );
		}

		$this->assertSame( $id, $toTitle->getArticleID( IDBAccessObject::READ_LATEST ) );
	}

	/**
	 * Shortcut function to create a page and return its id.
	 *
	 * @param Title $name Page to create
	 * @return int ID of created page
	 */
	protected function createPage( $name ) {
		return $this->editPage( $name, 'Content' )->getNewRevision()->getPageId();
	}

	public function testFromWithFromid() {
		$this->expectApiErrorCode( 'invalidparammix' );

		$this->doApiRequestWithToken( [
			'action' => 'move',
			'from' => 'Some page',
			'fromid' => 123,
			'to' => 'Some other page',
		] );
	}

	public function testMove() {
		$title = Title::makeTitle( NS_MAIN, 'TestMove' );
		$title2 = Title::makeTitle( NS_MAIN, 'TestMove 2' );

		$id = $this->createPage( $title );

		$res = $this->doApiRequestWithToken( [
			'action' => 'move',
			'from' => $title->getPrefixedText(),
			'to' => $title2->getPrefixedText(),
		] );

		$this->assertMoved( $title, $title2, $id );
		$this->assertArrayNotHasKey( 'warnings', $res[0] );
	}

	public function testMoveById() {
		$title = Title::makeTitle( NS_MAIN, 'TestMoveById' );
		$title2 = Title::makeTitle( NS_MAIN, 'TestMoveById 2' );

		$id = $this->createPage( $title );

		$res = $this->doApiRequestWithToken( [
			'action' => 'move',
			'fromid' => $id,
			'to' => $title2->getPrefixedText(),
		] );

		$this->assertMoved( $title, $title2, $id );
		$this->assertArrayNotHasKey( 'warnings', $res[0] );
	}

	public function testMoveAndWatch(): void {
		$title = Title::makeTitle( NS_MAIN, 'TestMoveAndWatch' );
		$title2 = Title::makeTitle( NS_MAIN, 'TestMoveAndWatch 2' );
		$this->createPage( $title );

		$this->doApiRequestWithToken( [
			'action' => 'move',
			'from' => $title->getPrefixedText(),
			'to' => $title2->getPrefixedText(),
			'watchlist' => 'watch',
			'watchlistexpiry' => '99990123000000',
		] );

		$user = $this->getTestSysop()->getUser();
		$watchlistManager = $this->getServiceContainer()->getWatchlistManager();
		$this->assertTrue( $watchlistManager->isTempWatched( $user, $title ) );
		$this->assertTrue( $watchlistManager->isTempWatched( $user, $title2 ) );
	}

	public function testMoveWithWatchUnchanged(): void {
		$title = Title::makeTitle( NS_MAIN, 'TestMoveWithWatchUnchanged' );
		$this->createPage( $title );
		$title2 = Title::makeTitle( NS_MAIN, 'TestMoveWithWatchUnchanged 2' );
		$user = $this->getTestSysop()->getUser();

		// Temporarily watch the page.
		$this->doApiRequestWithToken( [
			'action' => 'watch',
			'titles' => $title->getDBkey(),
			'expiry' => '99990123000000',
		] );

		// Fetched stored expiry (maximum duration may override '99990123000000').
		$store = $this->getServiceContainer()->getWatchedItemStore();
		$expiry = $store->getWatchedItem( $user, $title )->getExpiry();

		// Move to new location, without changing the watched state.
		$this->doApiRequestWithToken( [
			'action' => 'move',
			'from' => $title->getDBkey(),
			'to' => $title2->getDBkey(),
		] );

		// New page should have the same expiry.
		$expiry2 = $store->getWatchedItem( $user, $title2 )->getExpiry();
		$this->assertSame( wfTimestamp( TS_MW, $expiry ), $expiry2 );
	}

	public function testMoveNonexistent() {
		$this->expectApiErrorCode( 'missingtitle' );

		$this->doApiRequestWithToken( [
			'action' => 'move',
			'from' => 'Nonexistent page',
			'to' => 'Different page'
		] );
	}

	public function testMoveNonexistentId() {
		$this->expectApiErrorCode( 'nosuchpageid' );

		$this->doApiRequestWithToken( [
			'action' => 'move',
			'fromid' => pow( 2, 31 ) - 1,
			'to' => 'Different page',
		] );
	}

	public function testMoveToInvalidPageName() {
		$this->expectApiErrorCode( 'invalidtitle' );

		$title = Title::makeTitle( NS_MAIN, 'TestMoveToInvalidPageName' );
		$id = $this->createPage( $title );

		try {
			$this->doApiRequestWithToken( [
				'action' => 'move',
				'from' => $title->getPrefixedText(),
				'to' => '[',
			] );
		} finally {
			$this->assertSame( $id, $title->getArticleID( IDBAccessObject::READ_LATEST ) );
		}
	}

	public function testMoveWhileBlocked() {
		$blockStore = $this->getServiceContainer()->getDatabaseBlockStore();
		$this->assertNull( $blockStore->newFromTarget( '127.0.0.1' ) );

		$user = $this->getTestSysop()->getUser();
		$blockStore->insertBlockWithParams( [
			'address' => $user->getName(),
			'by' => $user,
			'reason' => 'Capriciousness',
			'timestamp' => '19370101000000',
			'expiry' => 'infinity',
			'enableAutoblock' => true,
		] );

		$title = Title::makeTitle( NS_MAIN, 'TestMoveWhileBlocked' );
		$title2 = Title::makeTitle( NS_MAIN, 'TestMoveWhileBlocked 2' );
		$id = $this->createPage( $title );

		try {
			$this->doApiRequestWithToken( [
				'action' => 'move',
				'from' => $title->getPrefixedText(),
				'to' => $title2->getPrefixedText(),
			] );
			$this->fail( 'Expected exception not thrown' );
		} catch ( ApiUsageException $ex ) {
			$this->assertApiErrorCode( 'blocked', $ex );
			$this->assertNotNull( $blockStore->newFromTarget( '127.0.0.1' ), 'Autoblock spread' );
			$this->assertSame( $id, $title->getArticleID( IDBAccessObject::READ_LATEST ) );
		}
	}

	// @todo File moving

	public function testRateLimit() {
		$name1 = 'TestPingLimiter 1';
		$name2 = 'TestPingLimiter 2';
		$name3 = 'TestPingLimiter 3';

		$title1 = Title::makeTitle( NS_MAIN, $name1 );
		$title2 = Title::makeTitle( NS_MAIN, $name2 );

		$this->overrideConfigValue( MainConfigNames::RateLimits,
			[ 'move' => [ '&can-bypass' => false, 'user' => [ 1, 60 ] ] ]
		);

		$id = $this->createPage( $title1 );
		$res = $this->doApiRequestWithToken( [
			'action' => 'move',
			'from' => $name1,
			'to' => $name2,
		] );
		$this->assertMoved( $title1, $title2, $id );
		$this->assertArrayNotHasKey( 'warnings', $res[0] );

		try {
			$this->doApiRequestWithToken( [
				'action' => 'move',
				'from' => $name2,
				'to' => $name3,
			] );

			$this->fail( 'Rate limit was expected to trigger an exception' );
		} catch ( ApiUsageException $ex ) {
			$this->assertStatusError( 'apierror-ratelimited', $ex->getStatusValue() );
		} finally {
			$title3 = Title::makeTitle( NS_MAIN, $name3 );
			$this->assertSame( $id, $title2->getArticleID( IDBAccessObject::READ_LATEST ) );
			$this->assertFalse( $title3->exists( IDBAccessObject::READ_LATEST ),
				"\"{$title3->getPrefixedText()}\" should not exist" );
		}
	}

	public function testTagsNoPermission() {
		$this->expectApiErrorCode( 'tags-apply-no-permission' );

		$title = Title::makeTitle( NS_MAIN, 'TestTagsNoPermission' );
		$title2 = Title::makeTitle( NS_MAIN, 'TestTagsNoPermission 2' );

		$this->getServiceContainer()->getChangeTagsStore()->defineTag( 'custom tag' );

		$this->setGroupPermissions( 'user', 'applychangetags', false );

		$id = $this->createPage( $title );

		try {
			$this->doApiRequestWithToken( [
				'action' => 'move',
				'from' => $title->getPrefixedText(),
				'to' => $title2->getPrefixedText(),
				'tags' => 'custom tag',
			] );
		} finally {
			$this->assertSame( $id, $title->getArticleID( IDBAccessObject::READ_LATEST ) );
			$this->assertFalse( $title2->exists( IDBAccessObject::READ_LATEST ),
				"\"{$title2->getPrefixedText()}\" should not exist" );
		}
	}

	public function testSelfMove() {
		$this->expectApiErrorCode( 'selfmove' );

		$title = Title::makeTitle( NS_MAIN, 'TestSelfMove' );
		$this->createPage( $title );

		$this->doApiRequestWithToken( [
			'action' => 'move',
			'from' => $title->getPrefixedText(),
			'to' => $title->getPrefixedText(),
		] );
	}

	public function testMoveTalk() {
		$title = Title::makeTitle( NS_MAIN, 'TestMoveTalk' );
		$title2 = Title::makeTitle( NS_MAIN, 'TestMoveTalk 2' );
		$talkTitle = $title->getTalkPageIfDefined();
		$talkTitle2 = $title2->getTalkPageIfDefined();

		$id = $this->createPage( $title );
		$talkId = $this->createPage( $talkTitle );

		$res = $this->doApiRequestWithToken( [
			'action' => 'move',
			'from' => $title->getPrefixedText(),
			'to' => $title2->getPrefixedText(),
			'movetalk' => '',
		] );

		$this->assertMoved( $title, $title2, $id );
		$this->assertMoved( $talkTitle, $talkTitle2, $talkId );

		$this->assertArrayNotHasKey( 'warnings', $res[0] );
	}

	public function testMoveTalkFailed() {
		$title = Title::makeTitle( NS_MAIN, 'TestMoveTalkFailed' );
		$title2 = Title::makeTitle( NS_MAIN, 'TestMoveTalkFailed 2' );
		$talkTitle = $title->getTalkPageIfDefined();
		$talkDestinationTitle = $title2->getTalkPageIfDefined();

		$id = $this->createPage( $title );
		$talkId = $this->createPage( $talkTitle );
		$talkDestinationId = $this->createPage( $talkDestinationTitle );

		$res = $this->doApiRequestWithToken( [
			'action' => 'move',
			'from' => $title->getPrefixedText(),
			'to' => $title2->getPrefixedText(),
			'movetalk' => '',
		] );

		$this->assertMoved( $title, $title2, $id );
		$this->assertSame( $talkId, $talkTitle->getArticleID( IDBAccessObject::READ_LATEST ) );
		$this->assertSame( $talkDestinationId,
			$talkDestinationTitle->getArticleID( IDBAccessObject::READ_LATEST ) );
		$this->assertSame( [ [
			'message' => 'articleexists',
			'params' => [ $talkDestinationTitle->getPrefixedText() ],
			'code' => 'articleexists',
			'type' => 'error',
		] ], $res[0]['move']['talkmove-errors'] );

		$this->assertArrayNotHasKey( 'warnings', $res[0] );
	}

	public function testMoveSubpages() {
		$title = Title::makeTitle( NS_MAIN, 'TestMoveSubpages' );
		$title2 = Title::makeTitle( NS_MAIN, 'TestMoveSubpages 2' );

		$this->mergeMwGlobalArrayValue( 'wgNamespacesWithSubpages', [ NS_MAIN => true ] );

		$titleError = Title::makeTitle( NS_MAIN, 'TestMoveSubpages/error' );
		$idError = $this->createPage( $titleError );
		$title2Error = Title::makeTitle( NS_MAIN, 'TestMoveSubpages 2/error' );
		$id2Error = $this->createPage( $title2Error );

		$titles = [
			[ $title, $title2 ],
			[ $title->getTalkPageIfDefined(), $title2->getTalkPageIfDefined() ],
			[ Title::makeTitle( NS_MAIN, 'TestMoveSubpages/1' ), Title::makeTitle( NS_MAIN, 'TestMoveSubpages 2/1' ) ],
			[ Title::makeTitle( NS_MAIN, 'TestMoveSubpages/2' ), Title::makeTitle( NS_MAIN, 'TestMoveSubpages 2/2' ) ],
			[ Title::makeTitle( NS_TALK, 'TestMoveSubpages/1' ), Title::makeTitle( NS_TALK, 'TestMoveSubpages 2/1' ) ],
			[ Title::makeTitle( NS_TALK, 'TestMoveSubpages/3' ), Title::makeTitle( NS_TALK, 'TestMoveSubpages 2/3' ) ],
		];
		$ids = [];
		$mapTitles = [];
		foreach ( $titles as [ $from, $to ] ) {
			$ids[$from->getPrefixedText()] = $this->createPage( $from );
			$mapTitles[$from->getPrefixedText()] = $to->getPrefixedText();
		}

		$res = $this->doApiRequestWithToken( [
			'action' => 'move',
			'from' => $title->getPrefixedText(),
			'to' => $title2->getPrefixedText(),
			'movetalk' => '',
			'movesubpages' => '',
		] );

		foreach ( $titles as [ $from, $to ] ) {
			$this->assertMoved( $from, $to, $ids[$from->getPrefixedText()] );
		}

		$this->assertSame( $idError, $titleError->getArticleID( IDBAccessObject::READ_LATEST ) );
		$this->assertSame( $id2Error, $title2Error->getArticleID( IDBAccessObject::READ_LATEST ) );

		$results = array_merge( $res[0]['move']['subpages'], $res[0]['move']['subpages-talk'] );
		foreach ( $results as $arr ) {
			if ( $arr['from'] === $titleError->getPrefixedText() ) {
				$this->assertSame( [ [
					'message' => 'articleexists',
					'params' => [ $title2Error->getPrefixedText() ],
					'code' => 'articleexists',
					'type' => 'error'
				] ], $arr['errors'] );
			} else {
				$this->assertSame( $mapTitles[$arr['from']], $arr['to'] );
			}
			$this->assertCount( 2, $arr );
		}

		$this->assertArrayNotHasKey( 'warnings', $res[0] );
	}

	public function testMoveNoPermission() {
		$title = Title::makeTitle( NS_MAIN, 'TestMoveNoPermission' );
		$title2 = Title::makeTitle( NS_MAIN, 'TestMoveNoPermission 2' );

		$id = $this->createPage( $title );

		$user = new User();

		try {
			$this->doApiRequestWithToken( [
				'action' => 'move',
				'from' => $title->getPrefixedText(),
				'to' => $title2->getPrefixedText(),
			], null, $user );
		} catch ( ApiUsageException $ex ) {
			// This one has two errors! So weird
			$this->assertTrue( ApiTestCase::apiExceptionHasCode( $ex, 'cantmove-anon' ) );
			$this->assertTrue( ApiTestCase::apiExceptionHasCode( $ex, 'cantmove' ) );
		} finally {
			$this->assertSame( $id, $title->getArticleID( IDBAccessObject::READ_LATEST ) );
			$this->assertFalse( $title2->exists( IDBAccessObject::READ_LATEST ),
				"\"{$title2->getPrefixedText()}\" should not exist" );
		}
	}

	public function testSuppressRedirect() {
		$title = Title::makeTitle( NS_MAIN, 'TestSuppressRedirect' );
		$title2 = Title::makeTitle( NS_MAIN, 'TestSuppressRedirect 2' );

		$id = $this->createPage( $title );

		$res = $this->doApiRequestWithToken( [
			'action' => 'move',
			'from' => $title->getPrefixedText(),
			'to' => $title2->getPrefixedText(),
			'noredirect' => '',
		] );

		$this->assertMoved( $title, $title2, $id, 'noredirect' );
		$this->assertArrayNotHasKey( 'warnings', $res[0] );
	}

	public function testSuppressRedirectNoPermission() {
		$title = Title::makeTitle( NS_MAIN, 'TestSuppressRedirectNoPermission' );
		$title2 = Title::makeTitle( NS_MAIN, 'TestSuppressRedirectNoPermission 2' );

		$this->setGroupPermissions( 'sysop', 'suppressredirect', false );
		$id = $this->createPage( $title );

		$res = $this->doApiRequestWithToken( [
			'action' => 'move',
			'from' => $title->getPrefixedText(),
			'to' => $title2->getPrefixedText(),
			'noredirect' => '',
		] );

		$this->assertMoved( $title, $title2, $id );
		$this->assertArrayNotHasKey( 'warnings', $res[0] );
	}

	public function testMoveSubpagesError() {
		$title = Title::makeTitle( NS_MAIN, 'TestMoveSubpagesError' );
		$titleBase = $title->getTalkPageIfDefined();
		$titleSub = Title::makeTitle( NS_MAIN, 'TestMoveSubpagesError/1' );
		$talkTitleSub = $titleSub->getTalkPageIfDefined();

		// Subpages are allowed in talk but not main
		$idBase = $this->createPage( $titleBase );
		$idSub = $this->createPage( $talkTitleSub );

		$res = $this->doApiRequestWithToken( [
			'action' => 'move',
			'from' => $titleBase->getPrefixedText(),
			'to' => $title->getPrefixedText(),
			'movesubpages' => '',
		] );

		$this->assertMoved( $titleBase, $title, $idBase );
		$this->assertSame( $idSub, $talkTitleSub->getArticleID( IDBAccessObject::READ_LATEST ) );
		$this->assertFalse( $titleSub->exists( IDBAccessObject::READ_LATEST ),
			"\"{$titleSub->getPrefixedText()}\" should not exist" );

		$this->assertSame( [ 'errors' => [ [
			'message' => 'namespace-nosubpages',
			'params' => [ '' ],
			'code' => 'namespace-nosubpages',
			'type' => 'error',
		] ] ], $res[0]['move']['subpages'] );

		$this->assertArrayNotHasKey( 'warnings', $res[0] );
	}
}
