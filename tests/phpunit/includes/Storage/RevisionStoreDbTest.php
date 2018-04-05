<?php

namespace MediaWiki\Tests\Storage;

use CommentStoreComment;
use Exception;
use HashBagOStuff;
use InvalidArgumentException;
use Language;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\MediaWikiServices;
use MediaWiki\Storage\BlobStoreFactory;
use MediaWiki\Storage\IncompleteRevisionException;
use MediaWiki\Storage\MutableRevisionRecord;
use MediaWiki\Storage\RevisionRecord;
use MediaWiki\Storage\RevisionStore;
use MediaWiki\Storage\SlotRecord;
use MediaWiki\Storage\SqlBlobStore;
use MediaWikiTestCase;
use Revision;
use TestUserRegistry;
use Title;
use WANObjectCache;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\DatabaseSqlite;
use Wikimedia\Rdbms\FakeResultWrapper;
use Wikimedia\Rdbms\LoadBalancer;
use Wikimedia\Rdbms\TransactionProfiler;
use WikiPage;
use WikitextContent;

/**
 * @group Database
 */
class RevisionStoreDbTest extends MediaWikiTestCase {

	public function setUp() {
		parent::setUp();
		$this->tablesUsed[] = 'archive';
		$this->tablesUsed[] = 'page';
		$this->tablesUsed[] = 'revision';
		$this->tablesUsed[] = 'comment';
	}

	/**
	 * @return LoadBalancer
	 */
	private function getLoadBalancerMock( array $server ) {
		$lb = $this->getMockBuilder( LoadBalancer::class )
			->setMethods( [ 'reallyOpenConnection' ] )
			->setConstructorArgs( [ [ 'servers' => [ $server ] ] ] )
			->getMock();

		$lb->method( 'reallyOpenConnection' )->willReturnCallback(
			function ( array $server, $dbNameOverride ) {
				return $this->getDatabaseMock( $server );
			}
		);

		return $lb;
	}

	/**
	 * @return Database
	 */
	private function getDatabaseMock( array $params ) {
		$db = $this->getMockBuilder( DatabaseSqlite::class )
			->setMethods( [ 'select', 'doQuery', 'open', 'closeConnection', 'isOpen' ] )
			->setConstructorArgs( [ $params ] )
			->getMock();

		$db->method( 'select' )->willReturn( new FakeResultWrapper( [] ) );
		$db->method( 'isOpen' )->willReturn( true );

		return $db;
	}

	public function provideDomainCheck() {
		yield [ false, 'test', '' ];
		yield [ 'test', 'test', '' ];

		yield [ false, 'test', 'foo_' ];
		yield [ 'test-foo_', 'test', 'foo_' ];

		yield [ false, 'dash-test', '' ];
		yield [ 'dash-test', 'dash-test', '' ];

		yield [ false, 'underscore_test', 'foo_' ];
		yield [ 'underscore_test-foo_', 'underscore_test', 'foo_' ];
	}

	/**
	 * @dataProvider provideDomainCheck
	 * @covers \MediaWiki\Storage\RevisionStore::checkDatabaseWikiId
	 */
	public function testDomainCheck( $wikiId, $dbName, $dbPrefix ) {
		$this->setMwGlobals(
			[
				'wgDBname' => $dbName,
				'wgDBprefix' => $dbPrefix,
			]
		);

		$loadBalancer = $this->getLoadBalancerMock(
			[
				'host' => '*dummy*',
				'dbDirectory' => '*dummy*',
				'user' => 'test',
				'password' => 'test',
				'flags' => 0,
				'variables' => [],
				'schema' => '',
				'cliMode' => true,
				'agent' => '',
				'load' => 100,
				'profiler' => null,
				'trxProfiler' => new TransactionProfiler(),
				'connLogger' => new \Psr\Log\NullLogger(),
				'queryLogger' => new \Psr\Log\NullLogger(),
				'errorLogger' => function () {
				},
				'deprecationLogger' => function () {
				},
				'type' => 'test',
				'dbname' => $dbName,
				'tablePrefix' => $dbPrefix,
			]
		);
		$db = $loadBalancer->getConnection( DB_REPLICA );

		$blobStore = $this->getMockBuilder( SqlBlobStore::class )
			->disableOriginalConstructor()
			->getMock();

		$store = new RevisionStore(
			$loadBalancer,
			$blobStore,
			new WANObjectCache( [ 'cache' => new HashBagOStuff() ] ),
			MediaWikiServices::getInstance()->getCommentStore(),
			MediaWikiServices::getInstance()->getActorMigration(),
			$wikiId
		);

		$count = $store->countRevisionsByPageId( $db, 0 );

		// Dummy check to make PhpUnit happy. We are really only interested in
		// countRevisionsByPageId not failing due to the DB domain check.
		$this->assertSame( 0, $count );
	}

	private function assertLinkTargetsEqual( LinkTarget $l1, LinkTarget $l2 ) {
		$this->assertEquals( $l1->getDBkey(), $l2->getDBkey() );
		$this->assertEquals( $l1->getNamespace(), $l2->getNamespace() );
		$this->assertEquals( $l1->getFragment(), $l2->getFragment() );
		$this->assertEquals( $l1->getInterwiki(), $l2->getInterwiki() );
	}

	private function assertRevisionRecordsEqual( RevisionRecord $r1, RevisionRecord $r2 ) {
		$this->assertEquals( $r1->getUser()->getName(), $r2->getUser()->getName() );
		$this->assertEquals( $r1->getUser()->getId(), $r2->getUser()->getId() );
		$this->assertEquals( $r1->getComment(), $r2->getComment() );
		$this->assertEquals( $r1->getPageAsLinkTarget(), $r2->getPageAsLinkTarget() );
		$this->assertEquals( $r1->getTimestamp(), $r2->getTimestamp() );
		$this->assertEquals( $r1->getVisibility(), $r2->getVisibility() );
		$this->assertEquals( $r1->getSha1(), $r2->getSha1() );
		$this->assertEquals( $r1->getParentId(), $r2->getParentId() );
		$this->assertEquals( $r1->getSize(), $r2->getSize() );
		$this->assertEquals( $r1->getPageId(), $r2->getPageId() );
		$this->assertEquals( $r1->getSlotRoles(), $r2->getSlotRoles() );
		$this->assertEquals( $r1->getWikiId(), $r2->getWikiId() );
		$this->assertEquals( $r1->isMinor(), $r2->isMinor() );
		foreach ( $r1->getSlotRoles() as $role ) {
			$this->assertSlotRecordsEqual( $r1->getSlot( $role ), $r2->getSlot( $role ) );
			$this->assertTrue( $r1->getContent( $role )->equals( $r2->getContent( $role ) ) );
		}
		foreach ( [
			RevisionRecord::DELETED_TEXT,
			RevisionRecord::DELETED_COMMENT,
			RevisionRecord::DELETED_USER,
			RevisionRecord::DELETED_RESTRICTED,
		] as $field ) {
			$this->assertEquals( $r1->isDeleted( $field ), $r2->isDeleted( $field ) );
		}
	}

	private function assertSlotRecordsEqual( SlotRecord $s1, SlotRecord $s2 ) {
		$this->assertSame( $s1->getRole(), $s2->getRole() );
		$this->assertSame( $s1->getModel(), $s2->getModel() );
		$this->assertSame( $s1->getFormat(), $s2->getFormat() );
		$this->assertSame( $s1->getSha1(), $s2->getSha1() );
		$this->assertSame( $s1->getSize(), $s2->getSize() );
		$this->assertTrue( $s1->getContent()->equals( $s2->getContent() ) );

		$s1->hasRevision() ? $this->assertSame( $s1->getRevision(), $s2->getRevision() ) : null;
		$s1->hasAddress() ? $this->assertSame( $s1->hasAddress(), $s2->hasAddress() ) : null;
	}

	private function assertRevisionCompleteness( RevisionRecord $r ) {
		foreach ( $r->getSlotRoles() as $role ) {
			$this->assertSlotCompleteness( $r, $r->getSlot( $role ) );
		}
	}

	private function assertSlotCompleteness( RevisionRecord $r, SlotRecord $slot ) {
		$this->assertTrue( $slot->hasAddress() );
		$this->assertSame( $r->getId(), $slot->getRevision() );
	}

	/**
	 * @param mixed[] $details
	 *
	 * @return RevisionRecord
	 */
	private function getRevisionRecordFromDetailsArray( $title, $details = [] ) {
		// Convert some values that can't be provided by dataProviders
		$page = WikiPage::factory( $title );
		if ( isset( $details['user'] ) && $details['user'] === true ) {
			$details['user'] = $this->getTestUser()->getUser();
		}
		if ( isset( $details['page'] ) && $details['page'] === true ) {
			$details['page'] = $page->getId();
		}
		if ( isset( $details['parent'] ) && $details['parent'] === true ) {
			$details['parent'] = $page->getLatest();
		}

		// Create the RevisionRecord with any available data
		$rev = new MutableRevisionRecord( $title );
		isset( $details['slot'] ) ? $rev->setSlot( $details['slot'] ) : null;
		isset( $details['parent'] ) ? $rev->setParentId( $details['parent'] ) : null;
		isset( $details['page'] ) ? $rev->setPageId( $details['page'] ) : null;
		isset( $details['size'] ) ? $rev->setSize( $details['size'] ) : null;
		isset( $details['sha1'] ) ? $rev->setSha1( $details['sha1'] ) : null;
		isset( $details['comment'] ) ? $rev->setComment( $details['comment'] ) : null;
		isset( $details['timestamp'] ) ? $rev->setTimestamp( $details['timestamp'] ) : null;
		isset( $details['minor'] ) ? $rev->setMinorEdit( $details['minor'] ) : null;
		isset( $details['user'] ) ? $rev->setUser( $details['user'] ) : null;
		isset( $details['visibility'] ) ? $rev->setVisibility( $details['visibility'] ) : null;
		isset( $details['id'] ) ? $rev->setId( $details['id'] ) : null;

		return $rev;
	}

	private function getRandomCommentStoreComment() {
		return CommentStoreComment::newUnsavedComment( __METHOD__ . '.' . rand( 0, 1000 ) );
	}

	public function provideInsertRevisionOn_successes() {
		yield 'Bare minimum revision insertion' => [
			Title::newFromText( 'UTPage' ),
			[
				'slot' => SlotRecord::newUnsaved( 'main', new WikitextContent( 'Chicken' ) ),
				'parent' => true,
				'comment' => $this->getRandomCommentStoreComment(),
				'timestamp' => '20171117010101',
				'user' => true,
			],
		];
		yield 'Detailed revision insertion' => [
			Title::newFromText( 'UTPage' ),
			[
				'slot' => SlotRecord::newUnsaved( 'main', new WikitextContent( 'Chicken' ) ),
				'parent' => true,
				'page' => true,
				'comment' => $this->getRandomCommentStoreComment(),
				'timestamp' => '20171117010101',
				'user' => true,
				'minor' => true,
				'visibility' => RevisionRecord::DELETED_RESTRICTED,
			],
		];
	}

	/**
	 * @dataProvider provideInsertRevisionOn_successes
	 * @covers \MediaWiki\Storage\RevisionStore::insertRevisionOn
	 */
	public function testInsertRevisionOn_successes( Title $title, array $revDetails = [] ) {
		$rev = $this->getRevisionRecordFromDetailsArray( $title, $revDetails );

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$return = $store->insertRevisionOn( $rev, wfGetDB( DB_MASTER ) );

		$this->assertLinkTargetsEqual( $title, $return->getPageAsLinkTarget() );
		$this->assertRevisionRecordsEqual( $rev, $return );
		$this->assertRevisionCompleteness( $return );
	}

	/**
	 * @covers \MediaWiki\Storage\RevisionStore::insertRevisionOn
	 */
	public function testInsertRevisionOn_blobAddressExists() {
		$title = Title::newFromText( 'UTPage' );
		$revDetails = [
			'slot' => SlotRecord::newUnsaved( 'main', new WikitextContent( 'Chicken' ) ),
			'parent' => true,
			'comment' => $this->getRandomCommentStoreComment(),
			'timestamp' => '20171117010101',
			'user' => true,
		];

		$store = MediaWikiServices::getInstance()->getRevisionStore();

		// Insert the first revision
		$revOne = $this->getRevisionRecordFromDetailsArray( $title, $revDetails );
		$firstReturn = $store->insertRevisionOn( $revOne, wfGetDB( DB_MASTER ) );
		$this->assertLinkTargetsEqual( $title, $firstReturn->getPageAsLinkTarget() );
		$this->assertRevisionRecordsEqual( $revOne, $firstReturn );

		// Insert a second revision inheriting the same blob address
		$revDetails['slot'] = SlotRecord::newInherited( $firstReturn->getSlot( 'main' ) );
		$revTwo = $this->getRevisionRecordFromDetailsArray( $title, $revDetails );
		$secondReturn = $store->insertRevisionOn( $revTwo, wfGetDB( DB_MASTER ) );
		$this->assertLinkTargetsEqual( $title, $secondReturn->getPageAsLinkTarget() );
		$this->assertRevisionRecordsEqual( $revTwo, $secondReturn );

		// Assert that the same blob address has been used.
		$this->assertEquals(
			$firstReturn->getSlot( 'main' )->getAddress(),
			$secondReturn->getSlot( 'main' )->getAddress()
		);
		// And that different revisions have been created.
		$this->assertNotSame(
			$firstReturn->getId(),
			$secondReturn->getId()
		);
	}

	public function provideInsertRevisionOn_failures() {
		yield 'no slot' => [
			Title::newFromText( 'UTPage' ),
			[
				'comment' => $this->getRandomCommentStoreComment(),
				'timestamp' => '20171117010101',
				'user' => true,
			],
			new InvalidArgumentException( 'At least one slot needs to be defined!' )
		];
		yield 'slot that is not main slot' => [
			Title::newFromText( 'UTPage' ),
			[
				'slot' => SlotRecord::newUnsaved( 'lalala', new WikitextContent( 'Chicken' ) ),
				'comment' => $this->getRandomCommentStoreComment(),
				'timestamp' => '20171117010101',
				'user' => true,
			],
			new InvalidArgumentException( 'Only the main slot is supported for now!' )
		];
		yield 'no timestamp' => [
			Title::newFromText( 'UTPage' ),
			[
				'slot' => SlotRecord::newUnsaved( 'main', new WikitextContent( 'Chicken' ) ),
				'comment' => $this->getRandomCommentStoreComment(),
				'user' => true,
			],
			new IncompleteRevisionException( 'timestamp field must not be NULL!' )
		];
		yield 'no comment' => [
			Title::newFromText( 'UTPage' ),
			[
				'slot' => SlotRecord::newUnsaved( 'main', new WikitextContent( 'Chicken' ) ),
				'timestamp' => '20171117010101',
				'user' => true,
			],
			new IncompleteRevisionException( 'comment must not be NULL!' )
		];
		yield 'no user' => [
			Title::newFromText( 'UTPage' ),
			[
				'slot' => SlotRecord::newUnsaved( 'main', new WikitextContent( 'Chicken' ) ),
				'comment' => $this->getRandomCommentStoreComment(),
				'timestamp' => '20171117010101',
			],
			new IncompleteRevisionException( 'user must not be NULL!' )
		];
	}

	/**
	 * @dataProvider provideInsertRevisionOn_failures
	 * @covers \MediaWiki\Storage\RevisionStore::insertRevisionOn
	 */
	public function testInsertRevisionOn_failures(
		Title $title,
		array $revDetails = [],
		Exception $exception ) {
		$rev = $this->getRevisionRecordFromDetailsArray( $title, $revDetails );

		$store = MediaWikiServices::getInstance()->getRevisionStore();

		$this->setExpectedException(
			get_class( $exception ),
			$exception->getMessage(),
			$exception->getCode()
		);
		$store->insertRevisionOn( $rev, wfGetDB( DB_MASTER ) );
	}

	public function provideNewNullRevision() {
		yield [
			Title::newFromText( 'UTPage' ),
			CommentStoreComment::newUnsavedComment( __METHOD__ . ' comment1' ),
			true,
		];
		yield [
			Title::newFromText( 'UTPage' ),
			CommentStoreComment::newUnsavedComment( __METHOD__ . ' comment2', [ 'a' => 1 ] ),
			false,
		];
	}

	/**
	 * @dataProvider provideNewNullRevision
	 * @covers \MediaWiki\Storage\RevisionStore::newNullRevision
	 */
	public function testNewNullRevision( Title $title, $comment, $minor ) {
		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$user = TestUserRegistry::getMutableTestUser( __METHOD__ )->getUser();

		$parent = $store->getRevisionByTitle( $title );
		$record = $store->newNullRevision(
			wfGetDB( DB_MASTER ),
			$title,
			$comment,
			$minor,
			$user
		);

		$this->assertEquals( $title->getNamespace(), $record->getPageAsLinkTarget()->getNamespace() );
		$this->assertEquals( $title->getDBkey(), $record->getPageAsLinkTarget()->getDBkey() );
		$this->assertEquals( $comment, $record->getComment() );
		$this->assertEquals( $minor, $record->isMinor() );
		$this->assertEquals( $user->getName(), $record->getUser()->getName() );
		$this->assertEquals( $parent->getId(), $record->getParentId() );

		$parentSlot = $parent->getSlot( 'main' );
		$slot = $record->getSlot( 'main' );

		$this->assertTrue( $slot->isInherited(), 'isInherited' );
		$this->assertSame( $parentSlot->getOrigin(), $slot->getOrigin(), 'getOrigin' );
		$this->assertSame( $parentSlot->getAddress(), $slot->getAddress(), 'getAddress' );
	}

	/**
	 * @covers \MediaWiki\Storage\RevisionStore::newNullRevision
	 */
	public function testNewNullRevision_nonExistingTitle() {
		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$record = $store->newNullRevision(
			wfGetDB( DB_MASTER ),
			Title::newFromText( __METHOD__ . '.iDontExist!' ),
			CommentStoreComment::newUnsavedComment( __METHOD__ . ' comment' ),
			false,
			TestUserRegistry::getMutableTestUser( __METHOD__ )->getUser()
		);
		$this->assertNull( $record );
	}

	/**
	 * @covers \MediaWiki\Storage\RevisionStore::getRcIdIfUnpatrolled
	 */
	public function testGetRcIdIfUnpatrolled_returnsRecentChangesId() {
		$page = WikiPage::factory( Title::newFromText( 'UTPage' ) );
		$status = $page->doEditContent( new WikitextContent( __METHOD__ ), __METHOD__ );
		/** @var Revision $rev */
		$rev = $status->value['revision'];

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$revisionRecord = $store->getRevisionById( $rev->getId() );
		$result = $store->getRcIdIfUnpatrolled( $revisionRecord );

		$this->assertGreaterThan( 0, $result );
		$this->assertSame(
			$page->getRevision()->getRecentChange()->getAttribute( 'rc_id' ),
			$result
		);
	}

	/**
	 * @covers \MediaWiki\Storage\RevisionStore::getRcIdIfUnpatrolled
	 */
	public function testGetRcIdIfUnpatrolled_returnsZeroIfPatrolled() {
		// This assumes that sysops are auto patrolled
		$sysop = $this->getTestSysop()->getUser();
		$page = WikiPage::factory( Title::newFromText( 'UTPage' ) );
		$status = $page->doEditContent(
			new WikitextContent( __METHOD__ ),
			__METHOD__,
			0,
			false,
			$sysop
		);
		/** @var Revision $rev */
		$rev = $status->value['revision'];

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$revisionRecord = $store->getRevisionById( $rev->getId() );
		$result = $store->getRcIdIfUnpatrolled( $revisionRecord );

		$this->assertSame( 0, $result );
	}

	/**
	 * @covers \MediaWiki\Storage\RevisionStore::getRecentChange
	 */
	public function testGetRecentChange() {
		$page = WikiPage::factory( Title::newFromText( 'UTPage' ) );
		$content = new WikitextContent( __METHOD__ );
		$status = $page->doEditContent( $content, __METHOD__ );
		/** @var Revision $rev */
		$rev = $status->value['revision'];

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$revRecord = $store->getRevisionById( $rev->getId() );
		$recentChange = $store->getRecentChange( $revRecord );

		$this->assertEquals( $rev->getId(), $recentChange->getAttribute( 'rc_this_oldid' ) );
		$this->assertEquals( $rev->getRecentChange(), $recentChange );
	}

	/**
	 * @covers \MediaWiki\Storage\RevisionStore::getRevisionById
	 */
	public function testGetRevisionById() {
		$page = WikiPage::factory( Title::newFromText( 'UTPage' ) );
		$content = new WikitextContent( __METHOD__ );
		$status = $page->doEditContent( $content, __METHOD__ );
		/** @var Revision $rev */
		$rev = $status->value['revision'];

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$revRecord = $store->getRevisionById( $rev->getId() );

		$this->assertSame( $rev->getId(), $revRecord->getId() );
		$this->assertTrue( $revRecord->getSlot( 'main' )->getContent()->equals( $content ) );
		$this->assertSame( __METHOD__, $revRecord->getComment()->text );
	}

	/**
	 * @covers \MediaWiki\Storage\RevisionStore::getRevisionByTitle
	 */
	public function testGetRevisionByTitle() {
		$page = WikiPage::factory( Title::newFromText( 'UTPage' ) );
		$content = new WikitextContent( __METHOD__ );
		$status = $page->doEditContent( $content, __METHOD__ );
		/** @var Revision $rev */
		$rev = $status->value['revision'];

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$revRecord = $store->getRevisionByTitle( $page->getTitle() );

		$this->assertSame( $rev->getId(), $revRecord->getId() );
		$this->assertTrue( $revRecord->getSlot( 'main' )->getContent()->equals( $content ) );
		$this->assertSame( __METHOD__, $revRecord->getComment()->text );
	}

	/**
	 * @covers \MediaWiki\Storage\RevisionStore::getRevisionByPageId
	 */
	public function testGetRevisionByPageId() {
		$page = WikiPage::factory( Title::newFromText( 'UTPage' ) );
		$content = new WikitextContent( __METHOD__ );
		$status = $page->doEditContent( $content, __METHOD__ );
		/** @var Revision $rev */
		$rev = $status->value['revision'];

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$revRecord = $store->getRevisionByPageId( $page->getId() );

		$this->assertSame( $rev->getId(), $revRecord->getId() );
		$this->assertTrue( $revRecord->getSlot( 'main' )->getContent()->equals( $content ) );
		$this->assertSame( __METHOD__, $revRecord->getComment()->text );
	}

	/**
	 * @covers \MediaWiki\Storage\RevisionStore::getRevisionByTimestamp
	 */
	public function testGetRevisionByTimestamp() {
		// Make sure there is 1 second between the last revision and the rev we create...
		// Otherwise we might not get the correct revision and the test may fail...
		// :(
		sleep( 1 );
		$page = WikiPage::factory( Title::newFromText( 'UTPage' ) );
		$content = new WikitextContent( __METHOD__ );
		$status = $page->doEditContent( $content, __METHOD__ );
		/** @var Revision $rev */
		$rev = $status->value['revision'];

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$revRecord = $store->getRevisionByTimestamp(
			$page->getTitle(),
			$rev->getTimestamp()
		);

		$this->assertSame( $rev->getId(), $revRecord->getId() );
		$this->assertTrue( $revRecord->getSlot( 'main' )->getContent()->equals( $content ) );
		$this->assertSame( __METHOD__, $revRecord->getComment()->text );
	}

	private function revisionToRow( Revision $rev ) {
		$page = WikiPage::factory( $rev->getTitle() );

		return (object)[
			'rev_id' => (string)$rev->getId(),
			'rev_page' => (string)$rev->getPage(),
			'rev_text_id' => (string)$rev->getTextId(),
			'rev_timestamp' => (string)$rev->getTimestamp(),
			'rev_user_text' => (string)$rev->getUserText(),
			'rev_user' => (string)$rev->getUser(),
			'rev_minor_edit' => $rev->isMinor() ? '1' : '0',
			'rev_deleted' => (string)$rev->getVisibility(),
			'rev_len' => (string)$rev->getSize(),
			'rev_parent_id' => (string)$rev->getParentId(),
			'rev_sha1' => (string)$rev->getSha1(),
			'rev_comment_text' => $rev->getComment(),
			'rev_comment_data' => null,
			'rev_comment_cid' => null,
			'rev_content_format' => $rev->getContentFormat(),
			'rev_content_model' => $rev->getContentModel(),
			'page_namespace' => (string)$page->getTitle()->getNamespace(),
			'page_title' => $page->getTitle()->getDBkey(),
			'page_id' => (string)$page->getId(),
			'page_latest' => (string)$page->getLatest(),
			'page_is_redirect' => $page->isRedirect() ? '1' : '0',
			'page_len' => (string)$page->getContent()->getSize(),
			'user_name' => (string)$rev->getUserText(),
		];
	}

	private function assertRevisionRecordMatchesRevision(
		Revision $rev,
		RevisionRecord $record
	) {
		$this->assertSame( $rev->getId(), $record->getId() );
		$this->assertSame( $rev->getPage(), $record->getPageId() );
		$this->assertSame( $rev->getTimestamp(), $record->getTimestamp() );
		$this->assertSame( $rev->getUserText(), $record->getUser()->getName() );
		$this->assertSame( $rev->getUser(), $record->getUser()->getId() );
		$this->assertSame( $rev->isMinor(), $record->isMinor() );
		$this->assertSame( $rev->getVisibility(), $record->getVisibility() );
		$this->assertSame( $rev->getSize(), $record->getSize() );
		/**
		 * @note As of MW 1.31, the database schema allows the parent ID to be
		 * NULL to indicate that it is unknown.
		 */
		$expectedParent = $rev->getParentId();
		if ( $expectedParent === null ) {
			$expectedParent = 0;
		}
		$this->assertSame( $expectedParent, $record->getParentId() );
		$this->assertSame( $rev->getSha1(), $record->getSha1() );
		$this->assertSame( $rev->getComment(), $record->getComment()->text );
		$this->assertSame( $rev->getContentFormat(), $record->getContent( 'main' )->getDefaultFormat() );
		$this->assertSame( $rev->getContentModel(), $record->getContent( 'main' )->getModel() );
		$this->assertLinkTargetsEqual( $rev->getTitle(), $record->getPageAsLinkTarget() );
	}

	/**
	 * @covers \MediaWiki\Storage\RevisionStore::newRevisionFromRow
	 * @covers \MediaWiki\Storage\RevisionStore::newRevisionFromRow_1_29
	 */
	public function testNewRevisionFromRow_anonEdit() {
		$this->setMwGlobals( 'wgActorTableSchemaMigrationStage', MIGRATION_WRITE_BOTH );
		$this->overrideMwServices();

		$page = WikiPage::factory( Title::newFromText( 'UTPage' ) );
		$text = __METHOD__ . 'a-ä';
		/** @var Revision $rev */
		$rev = $page->doEditContent(
			new WikitextContent( $text ),
			__METHOD__ . 'a'
		)->value['revision'];

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$record = $store->newRevisionFromRow(
			$this->revisionToRow( $rev ),
			[],
			$page->getTitle()
		);
		$this->assertRevisionRecordMatchesRevision( $rev, $record );
		$this->assertSame( $text, $rev->getContent()->serialize() );
	}

	/**
	 * @covers \MediaWiki\Storage\RevisionStore::newRevisionFromRow
	 * @covers \MediaWiki\Storage\RevisionStore::newRevisionFromRow_1_29
	 */
	public function testNewRevisionFromRow_anonEdit_legacyEncoding() {
		$this->setMwGlobals( 'wgLegacyEncoding', 'windows-1252' );
		$this->overrideMwServices();
		$page = WikiPage::factory( Title::newFromText( 'UTPage' ) );
		$text = __METHOD__ . 'a-ä';
		/** @var Revision $rev */
		$rev = $page->doEditContent(
			new WikitextContent( $text ),
			__METHOD__. 'a'
		)->value['revision'];

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$record = $store->newRevisionFromRow(
			$this->revisionToRow( $rev ),
			[],
			$page->getTitle()
		);
		$this->assertRevisionRecordMatchesRevision( $rev, $record );
		$this->assertSame( $text, $rev->getContent()->serialize() );
	}

	/**
	 * @covers \MediaWiki\Storage\RevisionStore::newRevisionFromRow
	 * @covers \MediaWiki\Storage\RevisionStore::newRevisionFromRow_1_29
	 */
	public function testNewRevisionFromRow_userEdit() {
		$this->setMwGlobals( 'wgActorTableSchemaMigrationStage', MIGRATION_WRITE_BOTH );
		$this->overrideMwServices();

		$page = WikiPage::factory( Title::newFromText( 'UTPage' ) );
		$text = __METHOD__ . 'b-ä';
		/** @var Revision $rev */
		$rev = $page->doEditContent(
			new WikitextContent( $text ),
			__METHOD__ . 'b',
			0,
			false,
			$this->getTestUser()->getUser()
		)->value['revision'];

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$record = $store->newRevisionFromRow(
			$this->revisionToRow( $rev ),
			[],
			$page->getTitle()
		);
		$this->assertRevisionRecordMatchesRevision( $rev, $record );
		$this->assertSame( $text, $rev->getContent()->serialize() );
	}

	/**
	 * @covers \MediaWiki\Storage\RevisionStore::newRevisionFromArchiveRow
	 */
	public function testNewRevisionFromArchiveRow() {
		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$title = Title::newFromText( __METHOD__ );
		$text = __METHOD__ . '-bä';
		$page = WikiPage::factory( $title );
		/** @var Revision $orig */
		$orig = $page->doEditContent( new WikitextContent( $text ), __METHOD__ )
			->value['revision'];
		$page->doDeleteArticle( __METHOD__ );

		$db = wfGetDB( DB_MASTER );
		$arQuery = $store->getArchiveQueryInfo();
		$res = $db->select(
			$arQuery['tables'], $arQuery['fields'], [ 'ar_rev_id' => $orig->getId() ],
			__METHOD__, [], $arQuery['joins']
		);
		$this->assertTrue( is_object( $res ), 'query failed' );

		$row = $res->fetchObject();
		$res->free();
		$record = $store->newRevisionFromArchiveRow( $row );

		$this->assertRevisionRecordMatchesRevision( $orig, $record );
		$this->assertSame( $text, $record->getContent( 'main' )->serialize() );
	}

	/**
	 * @covers \MediaWiki\Storage\RevisionStore::newRevisionFromArchiveRow
	 */
	public function testNewRevisionFromArchiveRow_legacyEncoding() {
		$this->setMwGlobals( 'wgLegacyEncoding', 'windows-1252' );
		$this->overrideMwServices();
		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$title = Title::newFromText( __METHOD__ );
		$text = __METHOD__ . '-bä';
		$page = WikiPage::factory( $title );
		/** @var Revision $orig */
		$orig = $page->doEditContent( new WikitextContent( $text ), __METHOD__ )
			->value['revision'];
		$page->doDeleteArticle( __METHOD__ );

		$db = wfGetDB( DB_MASTER );
		$arQuery = $store->getArchiveQueryInfo();
		$res = $db->select(
			$arQuery['tables'], $arQuery['fields'], [ 'ar_rev_id' => $orig->getId() ],
			__METHOD__, [], $arQuery['joins']
		);
		$this->assertTrue( is_object( $res ), 'query failed' );

		$row = $res->fetchObject();
		$res->free();
		$record = $store->newRevisionFromArchiveRow( $row );

		$this->assertRevisionRecordMatchesRevision( $orig, $record );
		$this->assertSame( $text, $record->getContent( 'main' )->serialize() );
	}

	/**
	 * @covers \MediaWiki\Storage\RevisionStore::loadRevisionFromId
	 */
	public function testLoadRevisionFromId() {
		$title = Title::newFromText( __METHOD__ );
		$page = WikiPage::factory( $title );
		/** @var Revision $rev */
		$rev = $page->doEditContent( new WikitextContent( __METHOD__ ), __METHOD__ )
			->value['revision'];

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$result = $store->loadRevisionFromId( wfGetDB( DB_MASTER ), $rev->getId() );
		$this->assertRevisionRecordMatchesRevision( $rev, $result );
	}

	/**
	 * @covers \MediaWiki\Storage\RevisionStore::loadRevisionFromPageId
	 */
	public function testLoadRevisionFromPageId() {
		$title = Title::newFromText( __METHOD__ );
		$page = WikiPage::factory( $title );
		/** @var Revision $rev */
		$rev = $page->doEditContent( new WikitextContent( __METHOD__ ), __METHOD__ )
			->value['revision'];

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$result = $store->loadRevisionFromPageId( wfGetDB( DB_MASTER ), $page->getId() );
		$this->assertRevisionRecordMatchesRevision( $rev, $result );
	}

	/**
	 * @covers \MediaWiki\Storage\RevisionStore::loadRevisionFromTitle
	 */
	public function testLoadRevisionFromTitle() {
		$title = Title::newFromText( __METHOD__ );
		$page = WikiPage::factory( $title );
		/** @var Revision $rev */
		$rev = $page->doEditContent( new WikitextContent( __METHOD__ ), __METHOD__ )
			->value['revision'];

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$result = $store->loadRevisionFromTitle( wfGetDB( DB_MASTER ), $title );
		$this->assertRevisionRecordMatchesRevision( $rev, $result );
	}

	/**
	 * @covers \MediaWiki\Storage\RevisionStore::loadRevisionFromTimestamp
	 */
	public function testLoadRevisionFromTimestamp() {
		$title = Title::newFromText( __METHOD__ );
		$page = WikiPage::factory( $title );
		/** @var Revision $revOne */
		$revOne = $page->doEditContent( new WikitextContent( __METHOD__ ), __METHOD__ )
			->value['revision'];
		// Sleep to ensure different timestamps... )(evil)
		sleep( 1 );
		/** @var Revision $revTwo */
		$revTwo = $page->doEditContent( new WikitextContent( __METHOD__ . 'a' ), '' )
			->value['revision'];

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$this->assertNull(
			$store->loadRevisionFromTimestamp( wfGetDB( DB_MASTER ), $title, '20150101010101' )
		);
		$this->assertSame(
			$revOne->getId(),
			$store->loadRevisionFromTimestamp(
				wfGetDB( DB_MASTER ),
				$title,
				$revOne->getTimestamp()
			)->getId()
		);
		$this->assertSame(
			$revTwo->getId(),
			$store->loadRevisionFromTimestamp(
				wfGetDB( DB_MASTER ),
				$title,
				$revTwo->getTimestamp()
			)->getId()
		);
	}

	/**
	 * @covers \MediaWiki\Storage\RevisionStore::listRevisionSizes
	 */
	public function testGetParentLengths() {
		$page = WikiPage::factory( Title::newFromText( __METHOD__ ) );
		/** @var Revision $revOne */
		$revOne = $page->doEditContent(
			new WikitextContent( __METHOD__ ), __METHOD__
		)->value['revision'];
		/** @var Revision $revTwo */
		$revTwo = $page->doEditContent(
			new WikitextContent( __METHOD__ . '2' ), __METHOD__
		)->value['revision'];

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$this->assertSame(
			[
				$revOne->getId() => strlen( __METHOD__ ),
			],
			$store->listRevisionSizes(
				wfGetDB( DB_MASTER ),
				[ $revOne->getId() ]
			)
		);
		$this->assertSame(
			[
				$revOne->getId() => strlen( __METHOD__ ),
				$revTwo->getId() => strlen( __METHOD__ ) + 1,
			],
			$store->listRevisionSizes(
				wfGetDB( DB_MASTER ),
				[ $revOne->getId(), $revTwo->getId() ]
			)
		);
	}

	/**
	 * @covers \MediaWiki\Storage\RevisionStore::getPreviousRevision
	 */
	public function testGetPreviousRevision() {
		$page = WikiPage::factory( Title::newFromText( __METHOD__ ) );
		/** @var Revision $revOne */
		$revOne = $page->doEditContent(
			new WikitextContent( __METHOD__ ), __METHOD__
		)->value['revision'];
		/** @var Revision $revTwo */
		$revTwo = $page->doEditContent(
			new WikitextContent( __METHOD__ . '2' ), __METHOD__
		)->value['revision'];

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$this->assertNull(
			$store->getPreviousRevision( $store->getRevisionById( $revOne->getId() ) )
		);
		$this->assertSame(
			$revOne->getId(),
			$store->getPreviousRevision( $store->getRevisionById( $revTwo->getId() ) )->getId()
		);
	}

	/**
	 * @covers \MediaWiki\Storage\RevisionStore::getNextRevision
	 */
	public function testGetNextRevision() {
		$page = WikiPage::factory( Title::newFromText( __METHOD__ ) );
		/** @var Revision $revOne */
		$revOne = $page->doEditContent(
			new WikitextContent( __METHOD__ ), __METHOD__
		)->value['revision'];
		/** @var Revision $revTwo */
		$revTwo = $page->doEditContent(
			new WikitextContent( __METHOD__ . '2' ), __METHOD__
		)->value['revision'];

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$this->assertSame(
			$revTwo->getId(),
			$store->getNextRevision( $store->getRevisionById( $revOne->getId() ) )->getId()
		);
		$this->assertNull(
			$store->getNextRevision( $store->getRevisionById( $revTwo->getId() ) )
		);
	}

	/**
	 * @covers \MediaWiki\Storage\RevisionStore::getTimestampFromId
	 */
	public function testGetTimestampFromId_found() {
		$page = WikiPage::factory( Title::newFromText( 'UTPage' ) );
		/** @var Revision $rev */
		$rev = $page->doEditContent( new WikitextContent( __METHOD__ ), __METHOD__ )
			->value['revision'];

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$result = $store->getTimestampFromId(
			$page->getTitle(),
			$rev->getId()
		);

		$this->assertSame( $rev->getTimestamp(), $result );
	}

	/**
	 * @covers \MediaWiki\Storage\RevisionStore::getTimestampFromId
	 */
	public function testGetTimestampFromId_notFound() {
		$page = WikiPage::factory( Title::newFromText( 'UTPage' ) );
		/** @var Revision $rev */
		$rev = $page->doEditContent( new WikitextContent( __METHOD__ ), __METHOD__ )
			->value['revision'];

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$result = $store->getTimestampFromId(
			$page->getTitle(),
			$rev->getId() + 1
		);

		$this->assertFalse( $result );
	}

	/**
	 * @covers \MediaWiki\Storage\RevisionStore::countRevisionsByPageId
	 */
	public function testCountRevisionsByPageId() {
		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$page = WikiPage::factory( Title::newFromText( __METHOD__ ) );

		$this->assertSame(
			0,
			$store->countRevisionsByPageId( wfGetDB( DB_MASTER ), $page->getId() )
		);
		$page->doEditContent( new WikitextContent( 'a' ), 'a' );
		$this->assertSame(
			1,
			$store->countRevisionsByPageId( wfGetDB( DB_MASTER ), $page->getId() )
		);
		$page->doEditContent( new WikitextContent( 'b' ), 'b' );
		$this->assertSame(
			2,
			$store->countRevisionsByPageId( wfGetDB( DB_MASTER ), $page->getId() )
		);
	}

	/**
	 * @covers \MediaWiki\Storage\RevisionStore::countRevisionsByTitle
	 */
	public function testCountRevisionsByTitle() {
		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$page = WikiPage::factory( Title::newFromText( __METHOD__ ) );

		$this->assertSame(
			0,
			$store->countRevisionsByTitle( wfGetDB( DB_MASTER ), $page->getTitle() )
		);
		$page->doEditContent( new WikitextContent( 'a' ), 'a' );
		$this->assertSame(
			1,
			$store->countRevisionsByTitle( wfGetDB( DB_MASTER ), $page->getTitle() )
		);
		$page->doEditContent( new WikitextContent( 'b' ), 'b' );
		$this->assertSame(
			2,
			$store->countRevisionsByTitle( wfGetDB( DB_MASTER ), $page->getTitle() )
		);
	}

	/**
	 * @covers \MediaWiki\Storage\RevisionStore::userWasLastToEdit
	 */
	public function testUserWasLastToEdit_false() {
		$sysop = $this->getTestSysop()->getUser();
		$page = WikiPage::factory( Title::newFromText( 'UTPage' ) );
		$page->doEditContent( new WikitextContent( __METHOD__ ), __METHOD__ );

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$result = $store->userWasLastToEdit(
			wfGetDB( DB_MASTER ),
			$page->getId(),
			$sysop->getId(),
			'20160101010101'
		);
		$this->assertFalse( $result );
	}

	/**
	 * @covers \MediaWiki\Storage\RevisionStore::userWasLastToEdit
	 */
	public function testUserWasLastToEdit_true() {
		$startTime = wfTimestampNow();
		$sysop = $this->getTestSysop()->getUser();
		$page = WikiPage::factory( Title::newFromText( 'UTPage' ) );
		$page->doEditContent(
			new WikitextContent( __METHOD__ ),
			__METHOD__,
			0,
			false,
			$sysop
		);

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$result = $store->userWasLastToEdit(
			wfGetDB( DB_MASTER ),
			$page->getId(),
			$sysop->getId(),
			$startTime
		);
		$this->assertTrue( $result );
	}

	/**
	 * @covers \MediaWiki\Storage\RevisionStore::getKnownCurrentRevision
	 */
	public function testGetKnownCurrentRevision() {
		$page = WikiPage::factory( Title::newFromText( 'UTPage' ) );
		/** @var Revision $rev */
		$rev = $page->doEditContent(
			new WikitextContent( __METHOD__ . 'b' ),
			__METHOD__ . 'b',
			0,
			false,
			$this->getTestUser()->getUser()
		)->value['revision'];

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$record = $store->getKnownCurrentRevision(
			$page->getTitle(),
			$rev->getId()
		);

		$this->assertRevisionRecordMatchesRevision( $rev, $record );
	}

	public function provideNewMutableRevisionFromArray() {
		yield 'Basic array, with page & id' => [
			[
				'id' => 2,
				'page' => 1,
				'text_id' => 2,
				'timestamp' => '20171017114835',
				'user_text' => '111.0.1.2',
				'user' => 0,
				'minor_edit' => false,
				'deleted' => 0,
				'len' => 46,
				'parent_id' => 1,
				'sha1' => 'rdqbbzs3pkhihgbs8qf2q9jsvheag5z',
				'comment' => 'Goat Comment!',
				'content_format' => 'text/x-wiki',
				'content_model' => 'wikitext',
			]
		];
		yield 'Basic array, content object' => [
			[
				'id' => 2,
				'page' => 1,
				'timestamp' => '20171017114835',
				'user_text' => '111.0.1.2',
				'user' => 0,
				'minor_edit' => false,
				'deleted' => 0,
				'len' => 46,
				'parent_id' => 1,
				'sha1' => 'rdqbbzs3pkhihgbs8qf2q9jsvheag5z',
				'comment' => 'Goat Comment!',
				'content' => new WikitextContent( 'Some Content' ),
			]
		];
		yield 'Basic array, serialized text' => [
			[
				'id' => 2,
				'page' => 1,
				'timestamp' => '20171017114835',
				'user_text' => '111.0.1.2',
				'user' => 0,
				'minor_edit' => false,
				'deleted' => 0,
				'len' => 46,
				'parent_id' => 1,
				'sha1' => 'rdqbbzs3pkhihgbs8qf2q9jsvheag5z',
				'comment' => 'Goat Comment!',
				'text' => ( new WikitextContent( 'Söme Content' ) )->serialize(),
			]
		];
		yield 'Basic array, serialized text, utf-8 flags' => [
			[
				'id' => 2,
				'page' => 1,
				'timestamp' => '20171017114835',
				'user_text' => '111.0.1.2',
				'user' => 0,
				'minor_edit' => false,
				'deleted' => 0,
				'len' => 46,
				'parent_id' => 1,
				'sha1' => 'rdqbbzs3pkhihgbs8qf2q9jsvheag5z',
				'comment' => 'Goat Comment!',
				'text' => ( new WikitextContent( 'Söme Content' ) )->serialize(),
				'flags' => 'utf-8',
			]
		];
		yield 'Basic array, with title' => [
			[
				'title' => Title::newFromText( 'SomeText' ),
				'text_id' => 2,
				'timestamp' => '20171017114835',
				'user_text' => '111.0.1.2',
				'user' => 0,
				'minor_edit' => false,
				'deleted' => 0,
				'len' => 46,
				'parent_id' => 1,
				'sha1' => 'rdqbbzs3pkhihgbs8qf2q9jsvheag5z',
				'comment' => 'Goat Comment!',
				'content_format' => 'text/x-wiki',
				'content_model' => 'wikitext',
			]
		];
		yield 'Basic array, no user field' => [
			[
				'id' => 2,
				'page' => 1,
				'text_id' => 2,
				'timestamp' => '20171017114835',
				'user_text' => '111.0.1.3',
				'minor_edit' => false,
				'deleted' => 0,
				'len' => 46,
				'parent_id' => 1,
				'sha1' => 'rdqbbzs3pkhihgbs8qf2q9jsvheag5z',
				'comment' => 'Goat Comment!',
				'content_format' => 'text/x-wiki',
				'content_model' => 'wikitext',
			]
		];
	}

	/**
	 * @dataProvider provideNewMutableRevisionFromArray
	 * @covers \MediaWiki\Storage\RevisionStore::newMutableRevisionFromArray
	 */
	public function testNewMutableRevisionFromArray( array $array ) {
		$store = MediaWikiServices::getInstance()->getRevisionStore();

		$result = $store->newMutableRevisionFromArray( $array );

		if ( isset( $array['id'] ) ) {
			$this->assertSame( $array['id'], $result->getId() );
		}
		if ( isset( $array['page'] ) ) {
			$this->assertSame( $array['page'], $result->getPageId() );
		}
		$this->assertSame( $array['timestamp'], $result->getTimestamp() );
		$this->assertSame( $array['user_text'], $result->getUser()->getName() );
		if ( isset( $array['user'] ) ) {
			$this->assertSame( $array['user'], $result->getUser()->getId() );
		}
		$this->assertSame( (bool)$array['minor_edit'], $result->isMinor() );
		$this->assertSame( $array['deleted'], $result->getVisibility() );
		$this->assertSame( $array['len'], $result->getSize() );
		$this->assertSame( $array['parent_id'], $result->getParentId() );
		$this->assertSame( $array['sha1'], $result->getSha1() );
		$this->assertSame( $array['comment'], $result->getComment()->text );
		if ( isset( $array['content'] ) ) {
			$this->assertTrue(
				$result->getSlot( 'main' )->getContent()->equals( $array['content'] )
			);
		} elseif ( isset( $array['text'] ) ) {
			$this->assertSame( $array['text'], $result->getSlot( 'main' )->getContent()->serialize() );
		} else {
			$this->assertSame(
				$array['content_format'],
				$result->getSlot( 'main' )->getContent()->getDefaultFormat()
			);
			$this->assertSame( $array['content_model'], $result->getSlot( 'main' )->getModel() );
		}
	}

	/**
	 * @dataProvider provideNewMutableRevisionFromArray
	 * @covers \MediaWiki\Storage\RevisionStore::newMutableRevisionFromArray
	 */
	public function testNewMutableRevisionFromArray_legacyEncoding( array $array ) {
		$cache = new WANObjectCache( [ 'cache' => new HashBagOStuff() ] );
		$blobStore = new SqlBlobStore( wfGetLB(), $cache );
		$blobStore->setLegacyEncoding( 'windows-1252', Language::factory( 'en' ) );

		$factory = $this->getMockBuilder( BlobStoreFactory::class )
			->setMethods( [ 'newBlobStore', 'newSqlBlobStore' ] )
			->disableOriginalConstructor()
			->getMock();
		$factory->expects( $this->any() )
			->method( 'newBlobStore' )
			->willReturn( $blobStore );
		$factory->expects( $this->any() )
			->method( 'newSqlBlobStore' )
			->willReturn( $blobStore );

		$this->setService( 'BlobStoreFactory', $factory );

		$this->testNewMutableRevisionFromArray( $array );
	}

}
