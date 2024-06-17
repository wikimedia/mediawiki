<?php

use MediaWiki\CommentStore\CommentStoreComment;
use MediaWiki\MainConfigNames;
use MediaWiki\Message\Message;
use MediaWiki\Page\MergeHistory;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\Title\Title;
use MediaWiki\Utils\MWTimestamp;

/**
 * @group Database
 */
class MergeHistoryTest extends MediaWikiIntegrationTestCase {
	use MockAuthorityTrait;

	/**
	 * Make some pages to work with
	 */
	public function addDBDataOnce() {
		// Pages that won't actually be merged
		$this->insertPage( 'Test' );
		$this->insertPage( 'Test2' );

		// Pages that will be merged
		$this->insertPage( 'Merge1' );
		$this->insertPage( 'Merge2' );

		// Exclusive for testSourceUpdateForNoRedirectSupport()
		$this->insertPage( 'Merge3' );
		$this->insertPage( 'Merge4' );

		// Exclusive for testSourceUpdateWithRedirectSupport()
		$this->insertPage( 'Merge5' );
		$this->insertPage( 'Merge6' );
	}

	/**
	 * @dataProvider provideIsValidMerge
	 * @covers \MediaWiki\Page\MergeHistory::isValidMerge
	 * @param string $source Source page
	 * @param string $dest Destination page
	 * @param string|bool $timestamp Timestamp up to which revisions are merged (or false for all)
	 * @param string|bool $error Expected error for test (or true for no error)
	 */
	public function testIsValidMerge( $source, $dest, $timestamp, $error ) {
		if ( $timestamp === true ) {
			// Although this timestamp is after the latest timestamp of both pages,
			// MergeHistory should select the latest source timestamp up to this which should
			// still work for the merge.
			$timestamp = time() + ( 24 * 3600 );
		}
		$factory = $this->getServiceContainer()->getMergeHistoryFactory();
		$mh = $factory->newMergeHistory(
			Title::newFromText( $source ),
			Title::newFromText( $dest ),
			$timestamp
		);
		$status = $mh->isValidMerge();
		if ( $error === true ) {
			$this->assertStatusGood( $status );
		} else {
			$this->assertStatusError( $error, $status );
		}
	}

	public static function provideIsValidMerge() {
		return [
			// for MergeHistory::isValidMerge
			[ 'Test', 'Test2', false, true ],
			// Timestamp of `true` is a placeholder for "in the future""
			[ 'Test', 'Test2', true, true ],
			[ 'Test', 'Test', false, 'mergehistory-fail-self-merge' ],
			[ 'Nonexistant', 'Test2', false, 'mergehistory-fail-invalid-source' ],
			[ 'Test', 'Nonexistant', false, 'mergehistory-fail-invalid-dest' ],
			[
				'Test',
				'Test2',
				'This is obviously an invalid timestamp',
				'mergehistory-fail-bad-timestamp'
			],
		];
	}

	/**
	 * Test merge revision limit checking
	 * @covers \MediaWiki\Page\MergeHistory::isValidMerge
	 */
	public function testIsValidMergeRevisionLimit() {
		$limit = MergeHistory::REVISION_LIMIT;
		$mh = $this->getMockBuilder( MergeHistory::class )
			->onlyMethods( [ 'getRevisionCount' ] )
			->setConstructorArgs( [
				Title::makeTitle( NS_MAIN, 'Test' ),
				Title::makeTitle( NS_MAIN, 'Test2' ),
				null,
				$this->getServiceContainer()->getConnectionProvider(),
				$this->getServiceContainer()->getContentHandlerFactory(),
				$this->getServiceContainer()->getRevisionStore(),
				$this->getServiceContainer()->getWatchedItemStore(),
				$this->getServiceContainer()->getSpamChecker(),
				$this->getServiceContainer()->getHookContainer(),
				$this->getServiceContainer()->getWikiPageFactory(),
				$this->getServiceContainer()->getTitleFormatter(),
				$this->getServiceContainer()->getTitleFactory(),
				$this->getServiceContainer()->getLinkTargetLookup()
			] )
			->getMock();
		$mh->expects( $this->once() )
			->method( 'getRevisionCount' )
			->willReturn( $limit + 1 );

		$status = $mh->isValidMerge();

		$this->assertStatusNotOK( $status );
		$this->assertStatusMessagesExactly(
			StatusValue::newFatal( 'mergehistory-fail-toobig', Message::numParam( $limit ) ),
			$status
		);
	}

	/**
	 * Test user permission checking
	 * @covers \MediaWiki\Page\MergeHistory::authorizeMerge
	 * @covers \MediaWiki\Page\MergeHistory::probablyCanMerge
	 */
	public function testCheckPermissions() {
		$factory = $this->getServiceContainer()->getMergeHistoryFactory();
		$mh = $factory->newMergeHistory(
			Title::makeTitle( NS_MAIN, 'Test' ),
			Title::makeTitle( NS_MAIN, 'Test2' )
		);

		foreach ( [ 'authorizeMerge', 'probablyCanMerge' ] as $method ) {
			// Sysop with mergehistory permission
			$status = $mh->$method(
				$this->mockRegisteredUltimateAuthority(),
				''
			);
			$this->assertStatusOK( $status );

			$status = $mh->$method(
				$this->mockRegisteredAuthorityWithoutPermissions( [ 'mergehistory' ] ),
				''
			);
			$this->assertStatusError( 'mergehistory-fail-permission', $status );
		}
	}

	/**
	 * Test merged revision count
	 * @covers \MediaWiki\Page\MergeHistory::getMergedRevisionCount
	 */
	public function testGetMergedRevisionCount() {
		$factory = $this->getServiceContainer()->getMergeHistoryFactory();
		$mh = $factory->newMergeHistory(
			Title::makeTitle( NS_MAIN, 'Merge1' ),
			Title::makeTitle( NS_MAIN, 'Merge2' )
		);

		$sysop = static::getTestSysop()->getUser();
		$mh->merge( $sysop );
		$this->assertSame( 1, $mh->getMergedRevisionCount() );
	}

	/**
	 * Test update to source page for pages with
	 * content model that supports redirects
	 *
	 * @covers \MediaWiki\Page\MergeHistory::merge
	 */
	public function testSourceUpdateWithRedirectSupport() {
		$title = Title::makeTitle( NS_MAIN, 'Merge5' );
		$title2 = Title::makeTitle( NS_MAIN, 'Merge6' );

		$factory = $this->getServiceContainer()->getMergeHistoryFactory();
		$mh = $factory->newMergeHistory( $title, $title2 );

		$this->assertTrue( $title->exists() );

		$status = $mh->merge( static::getTestSysop()->getUser() );
		$this->assertStatusOK( $status );

		$this->assertTrue( $title->exists() );
	}

	/**
	 * Test update to source page for pages with
	 * content model that does not support redirects
	 *
	 * @covers \MediaWiki\Page\MergeHistory::merge
	 */
	public function testSourceUpdateForNoRedirectSupport() {
		$this->overrideConfigValues( [
			MainConfigNames::ExtraNamespaces => [
				2030 => 'NoRedirect',
				2031 => 'NoRedirect_talk'
			],

			MainConfigNames::NamespaceContentModels => [
				2030 => 'testing'
			],
			MainConfigNames::ContentHandlers => [
				// Relies on the DummyContentHandlerForTesting not
				// supporting redirects by default. If this ever gets
				// changed this test has to be fixed.
				'testing' => DummyContentHandlerForTesting::class
			]
		] );

		$title = Title::makeTitle( NS_MAIN, 'Merge3' );
		$title->setContentModel( 'testing' );
		$title2 = Title::makeTitle( NS_MAIN, 'Merge4' );
		$title2->setContentModel( 'testing' );

		$factory = $this->getServiceContainer()->getMergeHistoryFactory();
		$mh = $factory->newMergeHistory( $title, $title2 );

		$this->assertTrue( $title->exists() );

		$status = $mh->merge( static::getTestSysop()->getUser() );
		$this->assertStatusOK( $status );

		$this->assertFalse( $title->exists() );
	}

	/**
	 * @covers \MediaWiki\Page\MergeHistory::initTimestampLimits
	 */
	public function testSplitTimestamp() {
		// Create the source page with two revisions with the same timestamp
		$user = static::getTestSysop()->getUser();
		$title1 = $this->insertPage( "Merge7" )["title"];
		$timestamp = MWTimestamp::now( TS_MW );
		$store = $this->getServiceContainer()->getRevisionStore();
		$revision = MutableRevisionRecord::newFromParentRevision( $store->getFirstRevision( $title1 ) );
		$revision->setTimestamp( $timestamp );
		$revision->setComment( CommentStoreComment::newUnsavedComment( "testing" ) );
		$revision->setUser( $user );
		$dbw = $this->getDB();
		$revid1 = $store->insertRevisionOn( $revision, $dbw )->getID();

		$revision2 = MutableRevisionRecord::newFromParentRevision( $store->getFirstRevision( $title1 ) );
		$revision2->setTimestamp( $timestamp );
		$revision2->setComment( CommentStoreComment::newUnsavedComment( "testing" ) );
		$revision2->setUser( $user );
		$revid2 = $store->insertRevisionOn( $revision2, $dbw )->getID();
		// Create the destination page (here to ensure its timestamp is the same or later than the above)
		$title2 = $this->insertPage( "Merge8" )["title"];

		// Now do the merge
		$factory = $this->getServiceContainer()->getMergeHistoryFactory();
		$mh = $factory->newMergeHistory( $title1, $title2, $timestamp . '|' . $revid1 );
		$status = $mh->merge( $user );
		$this->assertStatusOK( $status );

		$this->assertNull( $store->getRevisionByPageId( $title1->getId(), $revid1 ) );
		$this->assertNotNull( $store->getRevisionByPageId( $title1->getId(), $revid2 ) );
		$this->assertNotNull( $store->getRevisionByPageId( $title2->getId(), $revid1 ) );
		$this->assertNull( $store->getRevisionByPageId( $title2->getId(), $revid2 ) );
	}
}
