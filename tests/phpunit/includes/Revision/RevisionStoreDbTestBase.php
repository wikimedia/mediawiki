<?php

namespace MediaWiki\Tests\Revision;

use CommentStoreComment;
use Content;
use ContentHandler;
use Exception;
use HashBagOStuff;
use IDBAccessObject;
use InvalidArgumentException;
use JavaScriptContent;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\IncompleteRevisionException;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionArchiveRecord;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionSlots;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\RevisionStoreRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Storage\BlobStoreFactory;
use MediaWiki\Storage\SqlBlobStore;
use MediaWiki\User\UserIdentityValue;
use MediaWikiIntegrationTestCase;
use MWTimestamp;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\NullLogger;
use Revision;
use TestUserRegistry;
use Title;
use TitleValue;
use User;
use WANObjectCache;
use Wikimedia\Assert\PreconditionException;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\DatabaseDomain;
use Wikimedia\Rdbms\DatabaseSqlite;
use Wikimedia\Rdbms\FakeResultWrapper;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Rdbms\LoadBalancer;
use Wikimedia\Rdbms\TransactionProfiler;
use WikiPage;
use WikitextContent;

/**
 * @group Database
 * @group RevisionStore
 */
abstract class RevisionStoreDbTestBase extends MediaWikiIntegrationTestCase {

	/**
	 * @var Title
	 */
	private $testPageTitle;

	/**
	 * @var WikiPage
	 */
	private $testPage;

	protected function setUp() : void {
		parent::setUp();
		$this->tablesUsed[] = 'archive';
		$this->tablesUsed[] = 'page';
		$this->tablesUsed[] = 'revision';
		$this->tablesUsed[] = 'comment';
		$this->tablesUsed[] = 'actor';
		$this->tablesUsed[] = 'recentchanges';
		$this->tablesUsed[] = 'content';
		$this->tablesUsed[] = 'slots';
		$this->tablesUsed[] = 'content_models';
		$this->tablesUsed[] = 'slot_roles';
	}

	/**
	 * @return Title
	 */
	protected function getTestPageTitle() {
		if ( $this->testPageTitle ) {
			return $this->testPageTitle;
		}

		$this->testPageTitle = Title::newFromText( 'UTPage-' . __CLASS__ );
		return $this->testPageTitle;
	}

	/**
	 * @param string|null $pageTitle whether to force-create a new page
	 * @return WikiPage
	 */
	protected function getTestPage( $pageTitle = null ) {
		if ( $pageTitle === null && $this->testPage ) {
			return $this->testPage;
		}

		$title = $pageTitle === null ? $this->getTestPageTitle() : Title::newFromText( $pageTitle );
		$page = WikiPage::factory( $title );

		if ( !$page->exists() ) {
			// Make sure we don't write to the live db.
			$this->ensureMockDatabaseConnection( wfGetDB( DB_MASTER ) );

			$user = static::getTestSysop()->getUser();

			$page->doEditContent(
				new WikitextContent( 'UTContent-' . __CLASS__ ),
				'UTPageSummary-' . __CLASS__,
				EDIT_NEW | EDIT_SUPPRESS_RC,
				false,
				$user
			);
		}

		if ( $pageTitle === null ) {
			$this->testPage = $page;
		}
		return $page;
	}

	/**
	 * @return LoadBalancer|MockObject
	 */
	private function getLoadBalancerMock( array $server ) {
		$domain = new DatabaseDomain( $server['dbname'], null, $server['tablePrefix'] );

		$lb = $this->getMockBuilder( LoadBalancer::class )
			->setMethods( [ 'reallyOpenConnection' ] )
			->setConstructorArgs( [
				[ 'servers' => [ $server ], 'localDomain' => $domain ]
			] )
			->getMock();

		$lb->method( 'reallyOpenConnection' )->willReturnCallback(
			function () use ( $server ) {
				return $this->getDatabaseMock( $server );
			}
		);

		return $lb;
	}

	/**
	 * @return Database|MockObject
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
		yield [ 'dash?htest', 'dash-test', '' ];

		yield [ false, 'underscore_test', 'foo_' ];
		yield [ 'underscore_test-foo_', 'underscore_test', 'foo_' ];
	}

	/**
	 * @dataProvider provideDomainCheck
	 * @covers \MediaWiki\Revision\RevisionStore::checkDatabaseDomain
	 */
	public function testDomainCheck( $dbDomain, $dbName, $dbPrefix ) {
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
				'topologyRole' => Database::ROLE_STREAMING_MASTER,
				'topologicalMaster' => null,
				'agent' => '',
				'load' => 100,
				'srvCache' => new HashBagOStuff(),
				'profiler' => null,
				'trxProfiler' => new TransactionProfiler(),
				'connLogger' => new NullLogger(),
				'queryLogger' => new NullLogger(),
				'replLogger' => new NullLogger(),
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

		/** @var SqlBlobStore $blobStore */
		$blobStore = $this->getMockBuilder( SqlBlobStore::class )
			->disableOriginalConstructor()
			->getMock();

		$store = new RevisionStore(
			$loadBalancer,
			$blobStore,
			new WANObjectCache( [ 'cache' => new HashBagOStuff() ] ),
			MediaWikiServices::getInstance()->getCommentStore(),
			MediaWikiServices::getInstance()->getContentModelStore(),
			MediaWikiServices::getInstance()->getSlotRoleStore(),
			MediaWikiServices::getInstance()->getSlotRoleRegistry(),
			MediaWikiServices::getInstance()->getActorMigration(),
			MediaWikiServices::getInstance()->getContentHandlerFactory(),
			MediaWikiServices::getInstance()->getHookContainer(),
			$dbDomain
		);

		$count = $store->countRevisionsByPageId( $db, 0 );

		// Dummy check to make PhpUnit happy. We are really only interested in
		// countRevisionsByPageId not failing due to the DB domain check.
		$this->assertSame( 0, $count );
	}

	protected function assertLinkTargetsEqual( LinkTarget $l1, LinkTarget $l2 ) {
		$this->assertEquals( $l1->getDBkey(), $l2->getDBkey() );
		$this->assertEquals( $l1->getNamespace(), $l2->getNamespace() );
		$this->assertEquals( $l1->getFragment(), $l2->getFragment() );
		$this->assertEquals( $l1->getInterwiki(), $l2->getInterwiki() );
	}

	protected function assertRevisionRecordsEqual( RevisionRecord $r1, RevisionRecord $r2 ) {
		$this->assertEquals(
			$r1->getPageAsLinkTarget()->getNamespace(),
			$r2->getPageAsLinkTarget()->getNamespace()
		);

		$this->assertEquals(
			$r1->getPageAsLinkTarget()->getText(),
			$r2->getPageAsLinkTarget()->getText()
		);

		if ( $r1->getParentId() ) {
			$this->assertEquals( $r1->getParentId(), $r2->getParentId() );
		}

		$this->assertEquals( $r1->getUser()->getName(), $r2->getUser()->getName() );
		$this->assertEquals( $r1->getUser()->getId(), $r2->getUser()->getId() );
		$this->assertEquals( $r1->getComment(), $r2->getComment() );
		$this->assertEquals( $r1->getTimestamp(), $r2->getTimestamp() );
		$this->assertEquals( $r1->getVisibility(), $r2->getVisibility() );
		$this->assertEquals( $r1->getSha1(), $r2->getSha1() );
		$this->assertEquals( $r1->getSize(), $r2->getSize() );
		$this->assertEquals( $r1->getPageId(), $r2->getPageId() );
		$this->assertArrayEquals( $r1->getSlotRoles(), $r2->getSlotRoles() );
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

	protected function assertSlotRecordsEqual( SlotRecord $s1, SlotRecord $s2 ) {
		$this->assertSame( $s1->getRole(), $s2->getRole() );
		$this->assertSame( $s1->getModel(), $s2->getModel() );
		$this->assertSame( $s1->getFormat(), $s2->getFormat() );
		$this->assertSame( $s1->getSha1(), $s2->getSha1() );
		$this->assertSame( $s1->getSize(), $s2->getSize() );
		$this->assertTrue( $s1->getContent()->equals( $s2->getContent() ) );

		$s1->hasRevision() ? $this->assertSame( $s1->getRevision(), $s2->getRevision() ) : null;
		$s1->hasAddress() ? $this->assertSame( $s1->hasAddress(), $s2->hasAddress() ) : null;
	}

	protected function assertRevisionCompleteness( RevisionRecord $r ) {
		$this->assertTrue( $r->hasSlot( SlotRecord::MAIN ) );
		$this->assertInstanceOf( SlotRecord::class, $r->getSlot( SlotRecord::MAIN ) );
		$this->assertInstanceOf( Content::class, $r->getContent( SlotRecord::MAIN ) );

		foreach ( $r->getSlotRoles() as $role ) {
			$this->assertSlotCompleteness( $r, $r->getSlot( $role ) );
		}
	}

	protected function assertSlotCompleteness( RevisionRecord $r, SlotRecord $slot ) {
		$this->assertTrue( $slot->hasAddress() );
		$this->assertSame( $r->getId(), $slot->getRevision() );

		$this->assertInstanceOf( Content::class, $slot->getContent() );
	}

	/**
	 * @param mixed[] $details
	 *
	 * @return RevisionRecord
	 */
	private function getRevisionRecordFromDetailsArray( $details = [] ) {
		// Convert some values that can't be provided by dataProviders
		if ( isset( $details['user'] ) && $details['user'] === true ) {
			$details['user'] = $this->getTestUser()->getUser();
		}
		if ( isset( $details['page'] ) && $details['page'] === true ) {
			$details['page'] = $this->getTestPage()->getId();
		}
		if ( isset( $details['parent'] ) && $details['parent'] === true ) {
			$details['parent'] = $this->getTestPage()->getLatest();
		}

		// Create the RevisionRecord with any available data
		$rev = new MutableRevisionRecord( $this->getTestPageTitle() );
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

		if ( isset( $details['content'] ) ) {
			foreach ( $details['content'] as $role => $content ) {
				$rev->setContent( $role, $content );
			}
		}

		return $rev;
	}

	public function provideInsertRevisionOn_successes() {
		yield 'Bare minimum revision insertion' => [
			[
				'slot' => SlotRecord::newUnsaved( SlotRecord::MAIN, new WikitextContent( 'Chicken' ) ),
				'page' => true,
				'comment' => $this->getRandomCommentStoreComment(),
				'timestamp' => '20171117010101',
				'user' => true,
			],
		];
		yield 'Detailed revision insertion' => [
			[
				'slot' => SlotRecord::newUnsaved( SlotRecord::MAIN, new WikitextContent( 'Chicken' ) ),
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

	protected function getRandomCommentStoreComment() {
		return CommentStoreComment::newUnsavedComment( __METHOD__ . '.' . rand( 0, 1000 ) );
	}

	/**
	 * @dataProvider provideInsertRevisionOn_successes
	 * @covers \MediaWiki\Revision\RevisionStore::insertRevisionOn
	 * @covers \MediaWiki\Revision\RevisionStore::insertSlotRowOn
	 * @covers \MediaWiki\Revision\RevisionStore::insertContentRowOn
	 */
	public function testInsertRevisionOn_successes(
		array $revDetails = []
	) {
		$title = $this->getTestPageTitle();
		$rev = $this->getRevisionRecordFromDetailsArray( $revDetails );

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$return = $store->insertRevisionOn( $rev, wfGetDB( DB_MASTER ) );

		// is the new revision correct?
		$this->assertRevisionCompleteness( $return );
		$this->assertLinkTargetsEqual( $title, $return->getPageAsLinkTarget() );
		$this->assertRevisionRecordsEqual( $rev, $return );

		// can we load it from the store?
		$loaded = $store->getRevisionById( $return->getId() );
		$this->assertRevisionCompleteness( $loaded );
		$this->assertRevisionRecordsEqual( $return, $loaded );

		// can we find it directly in the database?
		$this->assertRevisionExistsInDatabase( $return );
	}

	protected function assertRevisionExistsInDatabase( RevisionRecord $rev ) {
		$row = $this->revisionRecordToRow( $rev, [] );

		// unset nulled fields
		unset( $row->rev_content_model );
		unset( $row->rev_content_format );

		// unset fake fields
		unset( $row->rev_comment_text );
		unset( $row->rev_comment_data );
		unset( $row->rev_comment_cid );
		unset( $row->rev_comment_id );

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$queryInfo = $store->getQueryInfo( [ 'user' ] );

		$row = get_object_vars( $row );

		// Use aliased fields from $queryInfo, e.g. rev_user
		$keys = array_keys( $row );
		$keys = array_combine( $keys, $keys );
		$fields = array_intersect_key( $queryInfo['fields'], $keys ) + $keys;

		// assertSelect() fails unless the orders match.
		ksort( $fields );
		ksort( $row );

		$this->assertSelect(
			$queryInfo['tables'],
			$fields,
			[ 'rev_id' => $rev->getId() ],
			[ array_values( $row ) ],
			[],
			$queryInfo['joins']
		);
	}

	/**
	 * @param SlotRecord $a
	 * @param SlotRecord $b
	 */
	protected function assertSameSlotContent( SlotRecord $a, SlotRecord $b ) {
		// Assert that the same blob address has been used.
		$this->assertSame( $a->getAddress(), $b->getAddress() );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::insertRevisionOn
	 */
	public function testInsertRevisionOn_blobAddressExists() {
		$title = $this->getTestPageTitle();
		$revDetails = [
			'slot' => SlotRecord::newUnsaved( SlotRecord::MAIN, new WikitextContent( 'Chicken' ) ),
			'parent' => true,
			'comment' => $this->getRandomCommentStoreComment(),
			'timestamp' => '20171117010101',
			'user' => true,
		];

		$store = MediaWikiServices::getInstance()->getRevisionStore();

		// Insert the first revision
		$revOne = $this->getRevisionRecordFromDetailsArray( $revDetails );
		$firstReturn = $store->insertRevisionOn( $revOne, wfGetDB( DB_MASTER ) );
		$this->assertLinkTargetsEqual( $title, $firstReturn->getPageAsLinkTarget() );
		$this->assertRevisionRecordsEqual( $revOne, $firstReturn );

		// Insert a second revision inheriting the same blob address
		$revDetails['slot'] = SlotRecord::newInherited( $firstReturn->getSlot( SlotRecord::MAIN ) );
		$revTwo = $this->getRevisionRecordFromDetailsArray( $revDetails );
		$secondReturn = $store->insertRevisionOn( $revTwo, wfGetDB( DB_MASTER ) );
		$this->assertLinkTargetsEqual( $title, $secondReturn->getPageAsLinkTarget() );
		$this->assertRevisionRecordsEqual( $revTwo, $secondReturn );

		$firstMainSlot = $firstReturn->getSlot( SlotRecord::MAIN );
		$secondMainSlot = $secondReturn->getSlot( SlotRecord::MAIN );

		$this->assertSameSlotContent( $firstMainSlot, $secondMainSlot );

		// And that different revisions have been created.
		$this->assertNotSame( $firstReturn->getId(), $secondReturn->getId() );

		// Make sure the slot rows reference the correct revision
		$this->assertSame( $firstReturn->getId(), $firstMainSlot->getRevision() );
		$this->assertSame( $secondReturn->getId(), $secondMainSlot->getRevision() );
	}

	public function provideInsertRevisionOn_failures() {
		yield 'no slot' => [
			[
				'comment' => $this->getRandomCommentStoreComment(),
				'timestamp' => '20171117010101',
				'user' => true,
			],
			new IncompleteRevisionException( 'main slot must be provided' )
		];
		yield 'no main slot' => [
			[
				'slot' => SlotRecord::newUnsaved( 'aux', new WikitextContent( 'Turkey' ) ),
				'comment' => $this->getRandomCommentStoreComment(),
				'timestamp' => '20171117010101',
				'user' => true,
			],
			new IncompleteRevisionException( 'main slot must be provided' )
		];
		yield 'no timestamp' => [
			[
				'slot' => SlotRecord::newUnsaved( SlotRecord::MAIN, new WikitextContent( 'Chicken' ) ),
				'comment' => $this->getRandomCommentStoreComment(),
				'user' => true,
			],
			new IncompleteRevisionException( 'timestamp field must not be NULL!' )
		];
		yield 'no comment' => [
			[
				'slot' => SlotRecord::newUnsaved( SlotRecord::MAIN, new WikitextContent( 'Chicken' ) ),
				'timestamp' => '20171117010101',
				'user' => true,
			],
			new IncompleteRevisionException( 'comment must not be NULL!' )
		];
		yield 'no user' => [
			[
				'slot' => SlotRecord::newUnsaved( SlotRecord::MAIN, new WikitextContent( 'Chicken' ) ),
				'comment' => $this->getRandomCommentStoreComment(),
				'timestamp' => '20171117010101',
			],
			new IncompleteRevisionException( 'user must not be NULL!' )
		];
		yield 'size mismatch' => [
			[
				'slot' => SlotRecord::newUnsaved( SlotRecord::MAIN, new WikitextContent( 'Chicken' ) ),
				'comment' => $this->getRandomCommentStoreComment(),
				'timestamp' => '20171117010101',
				'user' => true,
				'size' => 123456
			],
			new PreconditionException( 'T239717' )
		];
		yield 'sha1 mismatch' => [
			[
				'slot' => SlotRecord::newUnsaved( SlotRecord::MAIN, new WikitextContent( 'Chicken' ) ),
				'comment' => $this->getRandomCommentStoreComment(),
				'timestamp' => '20171117010101',
				'user' => true,
				'sha1' => 'DEADBEEF',
			],
			new PreconditionException( 'T239717' )
		];
	}

	/**
	 * @dataProvider provideInsertRevisionOn_failures
	 * @covers \MediaWiki\Revision\RevisionStore::insertRevisionOn
	 */
	public function testInsertRevisionOn_failures(
		array $revDetails,
		Exception $exception
	) {
		$rev = $this->getRevisionRecordFromDetailsArray( $revDetails );

		$store = MediaWikiServices::getInstance()->getRevisionStore();

		$this->expectException( get_class( $exception ) );
		$this->expectExceptionMessage( $exception->getMessage() );
		$this->expectExceptionCode( $exception->getCode() );
		$store->insertRevisionOn( $rev, wfGetDB( DB_MASTER ) );
	}

	public function provideNewNullRevision() {
		yield [
			Title::newFromText( 'UTPage_notAutoCreated' ),
			[ 'content' => [ 'main' => new WikitextContent( 'Flubber1' ) ] ],
			CommentStoreComment::newUnsavedComment( __METHOD__ . ' comment1' ),
			true,
		];
		yield [
			Title::newFromText( 'UTPage_notAutoCreated' ),
			[ 'content' => [ 'main' => new WikitextContent( 'Flubber2' ) ] ],
			CommentStoreComment::newUnsavedComment( __METHOD__ . ' comment2', [ 'a' => 1 ] ),
			false,
		];
	}

	/**
	 * @dataProvider provideNewNullRevision
	 * @covers \MediaWiki\Revision\RevisionStore::newNullRevision
	 */
	public function testNewNullRevision( Title $title, $revDetails, $comment, $minor = false ) {
		$user = TestUserRegistry::getMutableTestUser( __METHOD__ )->getUser();
		$page = WikiPage::factory( $title );

		if ( !$page->exists() ) {
			$page->doEditContent( new WikitextContent( __METHOD__ ), __METHOD__, EDIT_NEW );
		}

		$revDetails['page'] = $page->getId();
		$revDetails['timestamp'] = wfTimestampNow();
		$revDetails['comment'] = CommentStoreComment::newUnsavedComment( 'Base' );
		$revDetails['user'] = $user;

		$baseRev = $this->getRevisionRecordFromDetailsArray( $revDetails );
		$store = MediaWikiServices::getInstance()->getRevisionStore();

		$dbw = wfGetDB( DB_MASTER );
		$baseRev = $store->insertRevisionOn( $baseRev, $dbw );
		$page->updateRevisionOn( $dbw, $baseRev, $page->getLatest() );

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
		$this->assertEquals( $baseRev->getId(), $record->getParentId() );

		$this->assertArrayEquals(
			$baseRev->getSlotRoles(),
			$record->getSlotRoles()
		);

		foreach ( $baseRev->getSlotRoles() as $role ) {
			$parentSlot = $baseRev->getSlot( $role );
			$slot = $record->getSlot( $role );

			$this->assertTrue( $slot->isInherited(), 'isInherited' );
			$this->assertSame( $parentSlot->getOrigin(), $slot->getOrigin(), 'getOrigin' );
			$this->assertSameSlotContent( $parentSlot, $slot );
		}
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::newNullRevision
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
	 * @covers \MediaWiki\Revision\RevisionStore::getRcIdIfUnpatrolled
	 */
	public function testGetRcIdIfUnpatrolled_returnsRecentChangesId() {
		$page = $this->getTestPage();
		$status = $page->doEditContent( new WikitextContent( __METHOD__ ), __METHOD__ );
		/** @var RevisionRecord $revRecord */
		$revRecord = $status->value['revision-record'];

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$storeRecord = $store->getRevisionById( $revRecord->getId() );
		$result = $store->getRcIdIfUnpatrolled( $storeRecord );

		$this->assertGreaterThan( 0, $result );
		$this->assertSame(
			$store->getRecentChange( $storeRecord )->getAttribute( 'rc_id' ),
			$result
		);
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::getRcIdIfUnpatrolled
	 */
	public function testGetRcIdIfUnpatrolled_returnsZeroIfPatrolled() {
		// This assumes that sysops are auto patrolled
		$sysop = $this->getTestSysop()->getUser();
		$page = $this->getTestPage();
		$status = $page->doEditContent(
			new WikitextContent( __METHOD__ ),
			__METHOD__,
			0,
			false,
			$sysop
		);
		/** @var RevisionRecord $revRecord */
		$revRecord = $status->value['revision-record'];

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$storeRecord = $store->getRevisionById( $revRecord->getId() );
		$result = $store->getRcIdIfUnpatrolled( $storeRecord );

		$this->assertSame( 0, $result );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::getRecentChange
	 */
	public function testGetRecentChange() {
		$this->hideDeprecated( 'Revision::getRecentChange' );
		$this->hideDeprecated( 'Revision::__construct' );

		$page = $this->getTestPage();
		$content = new WikitextContent( __METHOD__ );
		$status = $page->doEditContent( $content, __METHOD__ );
		/** @var RevisionRecord $revRecord */
		$revRecord = $status->value['revision-record'];

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$storeRecord = $store->getRevisionById( $revRecord->getId() );
		$recentChange = $store->getRecentChange( $storeRecord );

		$this->assertEquals( $revRecord->getId(), $recentChange->getAttribute( 'rc_this_oldid' ) );
		$this->assertEquals(
			( new Revision( $revRecord ) )->getRecentChange(),
			$recentChange
		);
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::getRevisionById
	 */
	public function testGetRevisionById() {
		$page = $this->getTestPage();
		$content = new WikitextContent( __METHOD__ );
		$status = $page->doEditContent( $content, __METHOD__ );
		/** @var RevisionRecord $revRecord */
		$revRecord = $status->value['revision-record'];

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$storeRecord = $store->getRevisionById( $revRecord->getId() );

		$this->assertSame( $revRecord->getId(), $storeRecord->getId() );
		$this->assertTrue( $storeRecord->getSlot( SlotRecord::MAIN )->getContent()->equals( $content ) );
		$this->assertSame( __METHOD__, $storeRecord->getComment()->text );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::getRevisionByTitle
	 */
	public function testGetRevisionByTitle() {
		$page = $this->getTestPage();
		$content = new WikitextContent( __METHOD__ );
		$status = $page->doEditContent( $content, __METHOD__ );
		/** @var RevisionRecord $revRecord */
		$revRecord = $status->value['revision-record'];

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$storeRecord = $store->getRevisionByTitle( $page->getTitle() );

		$this->assertSame( $revRecord->getId(), $storeRecord->getId() );
		$this->assertTrue( $storeRecord->getSlot( SlotRecord::MAIN )->getContent()->equals( $content ) );
		$this->assertSame( __METHOD__, $storeRecord->getComment()->text );
	}

	/**
	 * Test that getRevisionByTitle doesn't try to use the local wiki DB (T248756)
	 * @covers \MediaWiki\Revision\RevisionStore::getRevisionByTitle
	 */
	public function testGetRevisionByTitle_doesNotUseLocalLoadBalancerForForeignWiki() {
		$page = $this->getTestPage();
		$content = new WikitextContent( __METHOD__ );
		$status = $page->doEditContent( $content, __METHOD__ );
		/** @var RevisionRecord $revRecord */
		$revRecord = $status->value['revision-record'];

		$dbDomain = 'some_foreign_wiki';

		$services = MediaWikiServices::getInstance();

		// Configure the load balancer to route queries for the "foreign" domain to the test DB
		$dbLoadBalancer = $services->getDBLoadBalancer();
		$dbLoadBalancer->setDomainAliases( [ $dbDomain => $dbLoadBalancer->getLocalDomainID() ] );

		$store = new RevisionStore(
			$dbLoadBalancer,
			$services->getBlobStore(),
			$services->getMainWANObjectCache(),
			$services->getCommentStore(),
			$services->getContentModelStore(),
			$services->getSlotRoleStore(),
			$services->getSlotRoleRegistry(),
			$services->getActorMigration(),
			$services->getContentHandlerFactory(),
			$services->getHookContainer(),
			$dbDomain
		);

		// Redefine the DBLoadBalancer service to verify Title doesn't attempt to resolve its ID
		// via wfGetDB()
		$localLoadBalancerMock = $this->createMock( ILoadBalancer::class );
		$localLoadBalancerMock->expects( $this->never() )
			->method( $this->anything() );

		$this->setService( 'DBLoadBalancer', $localLoadBalancerMock );

		$storeRecord = $store->getRevisionByTitle(
			new TitleValue( $page->getTitle()->getNamespace(), $page->getTitle()->getDBkey() )
		);

		$this->assertSame( $revRecord->getId(), $storeRecord->getId() );
		$this->assertTrue( $storeRecord->getSlot( SlotRecord::MAIN )->getContent()->equals( $content ) );
		$this->assertSame( __METHOD__, $storeRecord->getComment()->text );

		// Restore the original load balancer to make test teardown work
		$this->setService( 'DBLoadBalancer', $dbLoadBalancer );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::getRevisionByPageId
	 */
	public function testGetRevisionByPageId() {
		$page = $this->getTestPage();
		$content = new WikitextContent( __METHOD__ );
		$status = $page->doEditContent( $content, __METHOD__ );
		/** @var RevisionRecord $revRecord */
		$revRecord = $status->value['revision-record'];

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$storeRecord = $store->getRevisionByPageId( $page->getId() );

		$this->assertSame( $revRecord->getId(), $storeRecord->getId() );
		$this->assertTrue( $storeRecord->getSlot( SlotRecord::MAIN )->getContent()->equals( $content ) );
		$this->assertSame( __METHOD__, $storeRecord->getComment()->text );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::getRevisionByTimestamp
	 */
	public function testGetRevisionByTimestamp() {
		// Make sure there is 1 second between the last revision and the rev we create...
		// Otherwise we might not get the correct revision and the test may fail...
		MWTimestamp::setFakeTime( '20110401090000' );
		$page = $this->getTestPage();
		MWTimestamp::setFakeTime( '20110401090001' );
		$content = new WikitextContent( __METHOD__ );
		$status = $page->doEditContent( $content, __METHOD__ );
		/** @var RevisionRecord $revRecord */
		$revRecord = $status->value['revision-record'];

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$storeRecord = $store->getRevisionByTimestamp(
			$page->getTitle(),
			$revRecord->getTimestamp()
		);

		$this->assertSame( $revRecord->getId(), $storeRecord->getId() );
		$this->assertTrue( $storeRecord->getSlot( SlotRecord::MAIN )->getContent()->equals( $content ) );
		$this->assertSame( __METHOD__, $storeRecord->getComment()->text );
	}

	protected function revisionRecordToRow( RevisionRecord $revRecord, $options = [ 'page', 'user', 'comment' ] ) {
		// XXX: the WikiPage object loads another RevisionRecord from the database. Not great.
		$page = WikiPage::factory(
			Title::newFromLinkTarget( $revRecord->getPageAsLinkTarget() )
		);

		$revUser = $revRecord->getUser();

		$fields = [
			'rev_id' => (string)$revRecord->getId(),
			'rev_page' => (string)$revRecord->getPageId(),
			'rev_timestamp' => $this->db->timestamp( $revRecord->getTimestamp() ),
			'rev_user_text' => $revUser ? $revUser->getName() : '',
			'rev_user' => (string)( $revUser ? $revUser->getId() : 0 ) ?: null,
			'rev_minor_edit' => $revRecord->isMinor() ? '1' : '0',
			'rev_deleted' => (string)$revRecord->getVisibility(),
			'rev_len' => (string)$revRecord->getSize(),
			'rev_parent_id' => (string)$revRecord->getParentId(),
			'rev_sha1' => (string)$revRecord->getSha1(),
		];

		if ( in_array( 'page', $options ) ) {
			$fields += [
				'page_namespace' => (string)$page->getTitle()->getNamespace(),
				'page_title' => $page->getTitle()->getDBkey(),
				'page_id' => (string)$page->getId(),
				'page_latest' => (string)$page->getLatest(),
				'page_is_redirect' => $page->isRedirect() ? '1' : '0',
				'page_len' => (string)$page->getContent()->getSize(),
			];
		}

		if ( in_array( 'user', $options ) ) {
			$fields += [
				'user_name' => $revUser ? $revUser->getName() : ''
			];
		}

		if ( in_array( 'comment', $options ) ) {
			$revComment = $revRecord->getComment();
			$fields += [
				'rev_comment_text' => $revComment ? $revComment->text : null,
				'rev_comment_data' => $revComment ? $revComment->data : null,
				'rev_comment_cid' => $revComment ? $revComment->id : null,
			];
		}

		if ( $revRecord->getId() ) {
			$fields += [
				'rev_id' => (string)$revRecord->getId(),
			];
		}

		return (object)$fields;
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::newRevisionFromRowAndSlots
	 * @covers \MediaWiki\Revision\RevisionStore::getQueryInfo
	 */
	public function testNewRevisionFromRowAndSlots_getQueryInfo() {
		$this->hideDeprecated( 'Revision::getContent' );

		$page = $this->getTestPage();
		$text = __METHOD__ . 'o-ö';
		/** @var RevisionRecord $revRecord */
		$revRecord = $page->doEditContent(
			new WikitextContent( $text ),
			__METHOD__ . 'a'
		)->value['revision-record'];

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$info = $store->getQueryInfo();
		$row = $this->db->selectRow(
			$info['tables'],
			$info['fields'],
			[ 'rev_id' => $revRecord->getId() ],
			__METHOD__,
			[],
			$info['joins']
		);

		$info = $store->getSlotsQueryInfo( [ 'content' ] );
		$slotRows = $this->db->select(
			$info['tables'],
			$info['fields'],
			 [ 'slot_revision_id' => $revRecord->getId() ],
			__METHOD__,
			[],
			$info['joins']
		);

		$storeRecord = $store->newRevisionFromRowAndSlots(
			$row,
			iterator_to_array( $slotRows ),
			0,
			$page->getTitle()
		);
		$this->assertRevisionRecordsEqual( $revRecord, $storeRecord );
		$this->assertSame( $text, $revRecord->getContent( SlotRecord::MAIN )->serialize() );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::newRevisionFromRow
	 * @covers \MediaWiki\Revision\RevisionStore::newRevisionFromRowAndSlots
	 * @covers \MediaWiki\Revision\RevisionStore::getQueryInfo
	 */
	public function testNewRevisionFromRow_getQueryInfo() {
		$this->hideDeprecated( 'Revision::getContent' );

		$page = $this->getTestPage();
		$text = __METHOD__ . 'a-ä';
		/** @var RevisionRecord $revRecord */
		$revRecord = $page->doEditContent(
			new WikitextContent( $text ),
			__METHOD__ . 'a'
		)->value['revision-record'];

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$info = $store->getQueryInfo();
		$row = $this->db->selectRow(
			$info['tables'],
			$info['fields'],
			[ 'rev_id' => $revRecord->getId() ],
			__METHOD__,
			[],
			$info['joins']
		);
		$storeRecord = $store->newRevisionFromRow(
			$row,
			0,
			$page->getTitle()
		);
		$this->assertRevisionRecordsEqual( $revRecord, $storeRecord );
		$this->assertSame( $text, $revRecord->getContent( SlotRecord::MAIN )->serialize() );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::newRevisionFromRow
	 * @covers \MediaWiki\Revision\RevisionStore::newRevisionFromRowAndSlots
	 */
	public function testNewRevisionFromRow_anonEdit() {
		$this->hideDeprecated( 'Revision::getContent' );

		$page = $this->getTestPage();
		$text = __METHOD__ . 'a-ä';
		/** @var RevisionRecord $revRecord */
		$revRecord = $page->doEditContent(
			new WikitextContent( $text ),
			__METHOD__ . 'a'
		)->value['revision-record'];

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$storeRecord = $store->newRevisionFromRow(
			$this->revisionRecordToRow( $revRecord ),
			0,
			$page->getTitle()
		);
		$this->assertRevisionRecordsEqual( $revRecord, $storeRecord );
		$this->assertSame( $text, $revRecord->getContent( SlotRecord::MAIN )->serialize() );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::newRevisionFromRow
	 * @covers \MediaWiki\Revision\RevisionStore::newRevisionFromRowAndSlots
	 */
	public function testNewRevisionFromRow_anonEdit_legacyEncoding() {
		$this->hideDeprecated( 'Revision::getContent' );

		$this->setMwGlobals( 'wgLegacyEncoding', 'windows-1252' );
		$page = $this->getTestPage();
		$text = __METHOD__ . 'a-ä';
		/** @var RevisionRecord $revRecord */
		$revRecord = $page->doEditContent(
			new WikitextContent( $text ),
			__METHOD__ . 'a'
		)->value['revision-record'];

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$storeRecord = $store->newRevisionFromRow(
			$this->revisionRecordToRow( $revRecord ),
			0,
			$page->getTitle()
		);
		$this->assertRevisionRecordsEqual( $revRecord, $storeRecord );
		$this->assertSame( $text, $revRecord->getContent( SlotRecord::MAIN )->serialize() );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::newRevisionFromRow
	 * @covers \MediaWiki\Revision\RevisionStore::newRevisionFromRowAndSlots
	 */
	public function testNewRevisionFromRow_userEdit() {
		$this->hideDeprecated( 'Revision::getContent' );

		$page = $this->getTestPage();
		$text = __METHOD__ . 'b-ä';
		/** @var RevisionRecord $revRecord */
		$revRecord = $page->doEditContent(
			new WikitextContent( $text ),
			__METHOD__ . 'b',
			0,
			false,
			$this->getTestUser()->getUser()
		)->value['revision-record'];

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$storeRecord = $store->newRevisionFromRow(
			$this->revisionRecordToRow( $revRecord ),
			0,
			$page->getTitle()
		);
		$this->assertRevisionRecordsEqual( $revRecord, $storeRecord );
		$this->assertSame( $text, $revRecord->getContent( SlotRecord::MAIN )->serialize() );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::newRevisionFromArchiveRowAndSlots
	 * @covers \MediaWiki\Revision\RevisionStore::getArchiveQueryInfo
	 */
	public function testNewRevisionFromArchiveRowAndSlots_getArchiveQueryInfo() {
		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$title = Title::newFromText( __METHOD__ );
		$text = __METHOD__ . '-bä';
		$page = WikiPage::factory( $title );
		/** @var RevisionRecord $orig */
		$orig = $page->doEditContent( new WikitextContent( $text ), __METHOD__ )
			->value['revision-record'];
		$page->doDeleteArticleReal( __METHOD__, $this->getTestSysop()->getUser() );

		$db = wfGetDB( DB_MASTER );
		$arQuery = $store->getArchiveQueryInfo();
		$res = $db->select(
			$arQuery['tables'], $arQuery['fields'], [ 'ar_rev_id' => $orig->getId() ],
			__METHOD__, [], $arQuery['joins']
		);
		$this->assertIsObject( $res, 'query failed' );

		$info = $store->getSlotsQueryInfo( [ 'content' ] );
		$slotRows = $this->db->select(
			$info['tables'],
			$info['fields'],
			[ 'slot_revision_id' => $orig->getId() ],
			__METHOD__,
			[],
			$info['joins']
		);

		$row = $res->fetchObject();
		$res->free();
		$storeRecord = $store->newRevisionFromArchiveRowAndSlots(
			$row,
			iterator_to_array( $slotRows )
		);

		$this->assertRevisionRecordsEqual( $orig, $storeRecord );
		$this->assertSame( $text, $storeRecord->getContent( SlotRecord::MAIN )->serialize() );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::newRevisionFromArchiveRow
	 * @covers \MediaWiki\Revision\RevisionStore::newRevisionFromArchiveRowAndSlots
	 * @covers \MediaWiki\Revision\RevisionStore::getArchiveQueryInfo
	 */
	public function testNewRevisionFromArchiveRow_getArchiveQueryInfo() {
		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$title = Title::newFromText( __METHOD__ );
		$text = __METHOD__ . '-bä';
		$page = WikiPage::factory( $title );
		/** @var RevisionRecord $orig */
		$orig = $page->doEditContent( new WikitextContent( $text ), __METHOD__ )
			->value['revision-record'];
		$page->doDeleteArticleReal( __METHOD__, $this->getTestSysop()->getUser() );

		$db = wfGetDB( DB_MASTER );
		$arQuery = $store->getArchiveQueryInfo();
		$res = $db->select(
			$arQuery['tables'], $arQuery['fields'], [ 'ar_rev_id' => $orig->getId() ],
			__METHOD__, [], $arQuery['joins']
		);
		$this->assertIsObject( $res, 'query failed' );

		$row = $res->fetchObject();
		$res->free();
		$storeRecord = $store->newRevisionFromArchiveRow( $row );

		$this->assertRevisionRecordsEqual( $orig, $storeRecord );
		$this->assertSame( $text, $storeRecord->getContent( SlotRecord::MAIN )->serialize() );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::newRevisionFromArchiveRow
	 * @covers \MediaWiki\Revision\RevisionStore::newRevisionFromArchiveRowAndSlots
	 */
	public function testNewRevisionFromArchiveRow_legacyEncoding() {
		$this->setMwGlobals( 'wgLegacyEncoding', 'windows-1252' );
		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$title = Title::newFromText( __METHOD__ );
		$text = __METHOD__ . '-bä';
		$page = WikiPage::factory( $title );
		/** @var RevisionRecord $orig */
		$orig = $page->doEditContent( new WikitextContent( $text ), __METHOD__ )
			->value['revision-record'];
		$page->doDeleteArticleReal( __METHOD__, $this->getTestSysop()->getUser() );

		$db = wfGetDB( DB_MASTER );
		$arQuery = $store->getArchiveQueryInfo();
		$res = $db->select(
			$arQuery['tables'], $arQuery['fields'], [ 'ar_rev_id' => $orig->getId() ],
			__METHOD__, [], $arQuery['joins']
		);
		$this->assertIsObject( $res, 'query failed' );

		$row = $res->fetchObject();
		$res->free();
		$storeRecord = $store->newRevisionFromArchiveRow( $row );

		$this->assertRevisionRecordsEqual( $orig, $storeRecord );
		$this->assertSame( $text, $storeRecord->getContent( SlotRecord::MAIN )->serialize() );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::newRevisionFromArchiveRow
	 * @covers \MediaWiki\Revision\RevisionStore::newRevisionFromArchiveRowAndSlots
	 */
	public function testNewRevisionFromArchiveRow_no_user() {
		$store = MediaWikiServices::getInstance()->getRevisionStore();

		$row = (object)[
			'ar_id' => '1',
			'ar_page_id' => '2',
			'ar_namespace' => '0',
			'ar_title' => 'Something',
			'ar_rev_id' => '2',
			'ar_timestamp' => '20180528192356',
			'ar_minor_edit' => '0',
			'ar_deleted' => '0',
			'ar_len' => '78',
			'ar_parent_id' => '0',
			'ar_sha1' => 'deadbeef',
			'ar_comment_text' => 'whatever',
			'ar_comment_data' => null,
			'ar_comment_cid' => null,
			'ar_user' => '0',
			'ar_user_text' => '', // this is the important bit
			'ar_actor' => null,
		];

		\Wikimedia\suppressWarnings();
		$record = $store->newRevisionFromArchiveRow( $row );
		\Wikimedia\suppressWarnings( true );

		$this->assertInstanceOf( RevisionRecord::class, $record );
		$this->assertInstanceOf( UserIdentityValue::class, $record->getUser() );
		$this->assertSame( 'Unknown user', $record->getUser()->getName() );
	}

	/**
	 * Test for T236624.
	 *
	 * @covers \MediaWiki\Revision\RevisionStore::newRevisionFromArchiveRow
	 * @covers \MediaWiki\Revision\RevisionStore::newRevisionFromArchiveRowAndSlots
	 */
	public function testNewRevisionFromArchiveRow_empty_actor() {
		$store = MediaWikiServices::getInstance()->getRevisionStore();

		$row = (object)[
			'ar_id' => '1',
			'ar_page_id' => '2',
			'ar_namespace' => '0',
			'ar_title' => 'Something',
			'ar_rev_id' => '2',
			'ar_text_id' => '47',
			'ar_timestamp' => '20180528192356',
			'ar_minor_edit' => '0',
			'ar_deleted' => '0',
			'ar_len' => '78',
			'ar_parent_id' => '0',
			'ar_sha1' => 'deadbeef',
			'ar_comment_text' => 'whatever',
			'ar_comment_data' => null,
			'ar_comment_cid' => null,
			'ar_user' => '0',
			'ar_user_text' => '', // this is the important bit
			'ar_actor' => null, // we will fill this in below
			'ar_content_format' => null,
			'ar_content_model' => null,
		];

		// create an actor row for the empty user name (see also T225469)
		$this->db->insert( 'actor', [ [
			'actor_user' => $row->ar_user,
			'actor_name' => $row->ar_user_text,
		] ] );

		$row->ar_actor = $this->db->insertId();

		\Wikimedia\suppressWarnings();
		$record = $store->newRevisionFromArchiveRow( $row );
		\Wikimedia\suppressWarnings( true );

		$this->assertInstanceOf( RevisionRecord::class, $record );
		$this->assertInstanceOf( UserIdentityValue::class, $record->getUser() );
		$this->assertSame( 'Unknown user', $record->getUser()->getName() );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::newRevisionFromRow
	 * @covers \MediaWiki\Revision\RevisionStore::newRevisionFromRowAndSlots
	 */
	public function testNewRevisionFromRow_no_user() {
		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$title = Title::newFromText( __METHOD__ );

		$row = (object)[
			'rev_id' => '2',
			'rev_page' => '2',
			'page_namespace' => '0',
			'page_title' => $title->getText(),
			'rev_text_id' => '47',
			'rev_timestamp' => '20180528192356',
			'rev_minor_edit' => '0',
			'rev_deleted' => '0',
			'rev_len' => '78',
			'rev_parent_id' => '0',
			'rev_sha1' => 'deadbeef',
			'rev_comment_text' => 'whatever',
			'rev_comment_data' => null,
			'rev_comment_cid' => null,
			'rev_user' => '0',
			'rev_user_text' => '', // this is the important bit
			'rev_actor' => null,
			'rev_content_format' => null,
			'rev_content_model' => null,
		];

		\Wikimedia\suppressWarnings();
		$record = $store->newRevisionFromRow( $row, 0, $title );
		\Wikimedia\suppressWarnings( true );

		$this->assertNotNull( $record );
		$this->assertNotNull( $record->getUser() );
		$this->assertNotEmpty( $record->getUser()->getName() );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::insertRevisionOn
	 */
	public function testInsertRevisionOn_archive() {
		// This is a round trip test for deletion and undeletion of a
		// revision row via the archive table.

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$title = Title::newFromText( __METHOD__ );

		$page = WikiPage::factory( $title );
		/** @var RevisionRecord $orig */
		$page->doEditContent( new WikitextContent( "First" ), __METHOD__ . '-first' );
		$orig = $page->doEditContent( new WikitextContent( "Foo" ), __METHOD__ )
			->value['revision-record'];
		$page->doDeleteArticleReal( __METHOD__, $this->getTestSysop()->getUser() );

		// re-create page, so we can later load revisions for it
		$page->doEditContent( new WikitextContent( 'Two' ), __METHOD__ );

		$db = wfGetDB( DB_MASTER );
		$arQuery = $store->getArchiveQueryInfo();
		$row = $db->selectRow(
			$arQuery['tables'], $arQuery['fields'], [ 'ar_rev_id' => $orig->getId() ],
			__METHOD__, [], $arQuery['joins']
		);

		$this->assertNotFalse( $row, 'query failed' );

		$record = $store->newRevisionFromArchiveRow(
			$row,
			0,
			$title,
			[ 'page_id' => $title->getArticleID() ]
		);

		$restored = $store->insertRevisionOn( $record, $db );

		// is the new revision correct?
		$this->assertRevisionCompleteness( $restored );
		$this->assertRevisionRecordsEqual( $record, $restored );

		// does the new revision use the original slot?
		$recMain = $record->getSlot( SlotRecord::MAIN );
		$restMain = $restored->getSlot( SlotRecord::MAIN );
		$this->assertSame( $recMain->getAddress(), $restMain->getAddress() );
		$this->assertSame( $recMain->getContentId(), $restMain->getContentId() );
		$this->assertSame( $recMain->getOrigin(), $restMain->getOrigin() );
		$this->assertSame( 'Foo', $restMain->getContent()->serialize() );

		// can we load it from the store?
		$loaded = $store->getRevisionById( $restored->getId() );
		$this->assertNotNull( $loaded );
		$this->assertRevisionCompleteness( $loaded );
		$this->assertRevisionRecordsEqual( $restored, $loaded );

		// can we find it directly in the database?
		$this->assertRevisionExistsInDatabase( $restored );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::loadRevisionFromPageId
	 */
	public function testLoadRevisionFromPageId() {
		$title = Title::newFromText( __METHOD__ );
		$page = WikiPage::factory( $title );
		/** @var RevisionRecord $revRecord */
		$revRecord = $page->doEditContent( new WikitextContent( __METHOD__ ), __METHOD__ )
			->value['revision-record'];

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$this->hideDeprecated( RevisionStore::class . '::loadRevisionFromPageId' );
		$result = $store->loadRevisionFromPageId( wfGetDB( DB_MASTER ), $page->getId() );
		$this->assertRevisionRecordsEqual( $revRecord, $result );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::loadRevisionFromTitle
	 */
	public function testLoadRevisionFromTitle() {
		$this->hideDeprecated( RevisionStore::class . '::loadRevisionFromTitle' );
		$title = Title::newFromText( __METHOD__ );
		$page = WikiPage::factory( $title );
		/** @var RevisionRecord $revRecord */
		$revRecord = $page->doEditContent( new WikitextContent( __METHOD__ ), __METHOD__ )
			->value['revision-record'];

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$result = $store->loadRevisionFromTitle( wfGetDB( DB_MASTER ), $title );
		$this->assertRevisionRecordsEqual( $revRecord, $result );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::loadRevisionFromTimestamp
	 */
	public function testLoadRevisionFromTimestamp() {
		MWTimestamp::setFakeTime( '20110401090000' );
		$title = Title::newFromText( __METHOD__ );
		$page = WikiPage::factory( $title );
		/** @var RevisionRecord $revRecordOne */
		$revRecordOne = $page->doEditContent( new WikitextContent( __METHOD__ ), __METHOD__ )
			->value['revision-record'];
		// Ensure different timestamps...
		MWTimestamp::setFakeTime( '20110401090001' );
		/** @var RevisionRecord $revRecordTwo */
		$revRecordTwo = $page->doEditContent( new WikitextContent( __METHOD__ . 'a' ), '' )
			->value['revision-record'];

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$this->hideDeprecated( RevisionStore::class . '::loadRevisionFromTimestamp' );
		$this->assertNull(
			$store->loadRevisionFromTimestamp( wfGetDB( DB_MASTER ), $title, '20150101010101' )
		);
		$this->assertSame(
			$revRecordOne->getId(),
			$store->loadRevisionFromTimestamp(
				wfGetDB( DB_MASTER ),
				$title,
				$revRecordOne->getTimestamp()
			)->getId()
		);
		$this->assertSame(
			$revRecordTwo->getId(),
			$store->loadRevisionFromTimestamp(
				wfGetDB( DB_MASTER ),
				$title,
				$revRecordTwo->getTimestamp()
			)->getId()
		);
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::getRevisionSizes
	 */
	public function testGetParentLengths() {
		$page = WikiPage::factory( Title::newFromText( __METHOD__ ) );
		/** @var RevisionRecord $revRecordOne */
		$revRecordOne = $page->doEditContent(
			new WikitextContent( __METHOD__ ), __METHOD__
		)->value['revision-record'];
		/** @var RevisionRecord $revRecordTwo */
		$revRecordTwo = $page->doEditContent(
			new WikitextContent( __METHOD__ . '2' ), __METHOD__
		)->value['revision-record'];

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$this->assertSame(
			[
				$revRecordOne->getId() => strlen( __METHOD__ ),
			],
			$store->getRevisionSizes( [ $revRecordOne->getId() ] )
		);
		$this->assertSame(
			[
				$revRecordOne->getId() => strlen( __METHOD__ ),
				$revRecordTwo->getId() => strlen( __METHOD__ ) + 1,
			],
			$store->getRevisionSizes(
				[ $revRecordOne->getId(), $revRecordTwo->getId() ]
			)
		);
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::getPreviousRevision
	 */
	public function testGetPreviousRevision() {
		$page = WikiPage::factory( Title::newFromText( __METHOD__ ) );
		/** @var RevisionRecord $revRecordOne */
		$revRecordOne = $page->doEditContent(
			new WikitextContent( __METHOD__ ), __METHOD__
		)->value['revision-record'];
		/** @var RevisionRecord $revRecordTwo */
		$revRecordTwo = $page->doEditContent(
			new WikitextContent( __METHOD__ . '2' ), __METHOD__
		)->value['revision-record'];

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$this->assertNull(
			$store->getPreviousRevision(
				$store->getRevisionById( $revRecordOne->getId() )
			)
		);
		$this->assertSame(
			$revRecordOne->getId(),
			$store->getPreviousRevision(
				$store->getRevisionById( $revRecordTwo->getId() )
			)->getId()
		);
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::getNextRevision
	 */
	public function testGetNextRevision() {
		$page = WikiPage::factory( Title::newFromText( __METHOD__ ) );
		/** @var RevisionRecord $revRecordOne */
		$revRecordOne = $page->doEditContent(
			new WikitextContent( __METHOD__ ), __METHOD__
		)->value['revision-record'];
		/** @var RevisionRecord $revRecordTwo */
		$revRecordTwo = $page->doEditContent(
			new WikitextContent( __METHOD__ . '2' ), __METHOD__
		)->value['revision-record'];

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$this->assertSame(
			$revRecordTwo->getId(),
			$store->getNextRevision(
				$store->getRevisionById( $revRecordOne->getId() )
		)->getId()
		);
		$this->assertNull(
			$store->getNextRevision( $store->getRevisionById( $revRecordTwo->getId() ) )
		);
	}

	public function provideNonHistoryRevision() {
		$title = Title::newFromText( __METHOD__ );
		$rev = new MutableRevisionRecord( $title );
		yield [ $rev ];

		$user = new UserIdentityValue( 7, 'Frank', 0 );
		$comment = CommentStoreComment::newUnsavedComment( 'Test' );
		$row = (object)[
			'ar_id' => 3,
			'ar_rev_id' => 34567,
			'ar_page_id' => 5,
			'ar_deleted' => 0,
			'ar_minor_edit' => 0,
			'ar_timestamp' => '20180101020202',
		];
		$slots = new RevisionSlots( [] );
		$rev = new RevisionArchiveRecord( $title, $user, $comment, $row, $slots );
		yield [ $rev ];
	}

	/**
	 * @dataProvider provideNonHistoryRevision
	 * @covers \MediaWiki\Revision\RevisionStore::getPreviousRevision
	 */
	public function testGetPreviousRevision_bad( RevisionRecord $rev ) {
		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$this->assertNull( $store->getPreviousRevision( $rev ) );
	}

	/**
	 * @dataProvider provideNonHistoryRevision
	 * @covers \MediaWiki\Revision\RevisionStore::getNextRevision
	 */
	public function testGetNextRevision_bad( RevisionRecord $rev ) {
		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$this->assertNull( $store->getNextRevision( $rev ) );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::getTimestampFromId
	 */
	public function testGetTimestampFromId_found() {
		$page = $this->getTestPage();
		/** @var RevisionRecord $revRecord */
		$revRecord = $page->doEditContent( new WikitextContent( __METHOD__ ), __METHOD__ )
			->value['revision-record'];

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$result = $store->getTimestampFromId( $revRecord->getId() );

		$this->assertSame( $revRecord->getTimestamp(), $result );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::getTimestampFromId
	 */
	public function testGetTimestampFromId_notFound() {
		$page = $this->getTestPage();
		/** @var RevisionRecord $revRecord */
		$revRecord = $page->doEditContent( new WikitextContent( __METHOD__ ), __METHOD__ )
			->value['revision-record'];

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$result = $store->getTimestampFromId( $revRecord->getId() + 1 );

		$this->assertFalse( $result );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::countRevisionsByPageId
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
	 * @covers \MediaWiki\Revision\RevisionStore::countRevisionsByTitle
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
	 * @covers \MediaWiki\Revision\RevisionStore::userWasLastToEdit
	 */
	public function testUserWasLastToEdit_false() {
		$sysop = $this->getTestSysop()->getUser();
		$page = $this->getTestPage();
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
	 * @covers \MediaWiki\Revision\RevisionStore::userWasLastToEdit
	 */
	public function testUserWasLastToEdit_true() {
		$startTime = wfTimestampNow();
		$sysop = $this->getTestSysop()->getUser();
		$page = $this->getTestPage();
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
	 * @covers \MediaWiki\Revision\RevisionStore::getKnownCurrentRevision
	 */
	public function testGetKnownCurrentRevision() {
		$page = $this->getTestPage();
		/** @var RevisionRecord $revRecord */
		$revRecord = $page->doEditContent(
			new WikitextContent( __METHOD__ . 'b' ),
			__METHOD__ . 'b',
			0,
			false,
			$this->getTestUser()->getUser()
		)->value['revision-record'];

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$storeRecord = $store->getKnownCurrentRevision(
			$page->getTitle(),
			$revRecord->getId()
		);

		$this->assertRevisionRecordsEqual( $revRecord, $storeRecord );
	}

	public function provideNewMutableRevisionFromArray() {
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
		yield 'Basic array, serialized text and model' => [
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
				'text' => ( new JavaScriptContent( 'alert("test")' ) )->serialize(),
				'content_model' => CONTENT_MODEL_JAVASCRIPT
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
		yield 'Basic array, no user field' => [
			[
				'id' => 2,
				'page' => 1,
				'timestamp' => '20171017114835',
				'user_text' => '111.0.1.3',
				'minor_edit' => false,
				'deleted' => 0,
				'len' => 46,
				'parent_id' => 1,
				'sha1' => 'rdqbbzs3pkhihgbs8qf2q9jsvheag5z',
				'comment' => 'Goat Comment!',
				'content' => new WikitextContent( 'Some Content' ),
			]
		];
	}

	/**
	 * @dataProvider provideNewMutableRevisionFromArray
	 * @covers \MediaWiki\Revision\RevisionStore::newMutableRevisionFromArray
	 */
	public function testNewMutableRevisionFromArray( array $array ) {
		$store = MediaWikiServices::getInstance()->getRevisionStore();

		// HACK: if $array['page'] is given, make sure that the page exists
		if ( isset( $array['page'] ) ) {
			$t = Title::newFromID( $array['page'] );
			if ( !$t || !$t->exists() ) {
				$t = Title::makeTitle( NS_MAIN, __METHOD__ );
				$info = $this->insertPage( $t );
				$array['page'] = $info['id'];
			}
		}

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
			foreach ( $array['content'] as $role => $content ) {
				$this->assertTrue(
					$result->getContent( $role )->equals( $content )
				);
			}
		} elseif ( isset( $array['text'] ) ) {
			$this->assertSame( $array['text'],
				$result->getSlot( SlotRecord::MAIN )->getContent()->serialize() );

			if ( isset( $array['content_format'] ) ) {
				$this->assertSame(
					$array['content_format'],
					$result->getSlot( SlotRecord::MAIN )->getContent()->getDefaultFormat()
				);
			}
			if ( isset( $array['content_model'] ) ) {
				$this->assertSame(
					$array['content_model'],
					$result->getSlot( SlotRecord::MAIN )->getModel()
				);
			}
		}
	}

	/**
	 * @dataProvider provideNewMutableRevisionFromArray
	 * @covers \MediaWiki\Revision\RevisionStore::newMutableRevisionFromArray
	 */
	public function testNewMutableRevisionFromArray_legacyEncoding( array $array ) {
		$cache = new WANObjectCache( [ 'cache' => new HashBagOStuff() ] );
		$services = MediaWikiServices::getInstance();
		$lb = $services->getDBLoadBalancer();
		$access = $services->getExternalStoreAccess();
		$blobStore = new SqlBlobStore( $lb, $access, $cache );
		$blobStore->setLegacyEncoding( 'windows-1252' );

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

	/**
	 * Creates a new revision for testing caching behavior
	 *
	 * @param WikiPage $page the page for the new revision
	 * @param RevisionStore $store store object to use for creating the revision
	 * @return bool|RevisionStoreRecord the revision created, or false if missing
	 */
	private function createRevisionStoreCacheRecord( $page, $store ) {
		$user = MediaWikiIntegrationTestCase::getMutableTestUser()->getUser();
		$updater = $page->newPageUpdater( $user );
		$updater->setContent( SlotRecord::MAIN, new WikitextContent( __METHOD__ ) );
		$summary = CommentStoreComment::newUnsavedComment( __METHOD__ );
		$rev = $updater->saveRevision( $summary, EDIT_NEW );
		return $store->getKnownCurrentRevision( $page->getTitle(), $rev->getId() );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::getKnownCurrentRevision
	 */
	public function testGetKnownCurrentRevision_userNameChange() {
		$cache = new WANObjectCache( [ 'cache' => new HashBagOStuff() ] );
		$this->setService( 'MainWANObjectCache', $cache );

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$page = $this->getNonexistingTestPage();
		$rev = $this->createRevisionStoreCacheRecord( $page, $store );

		// Grab the user name
		$userNameBefore = $rev->getUser()->getName();

		// Change the user name in the database, "behind the back" of the cache
		$newUserName = "Renamed $userNameBefore";
		$this->db->update( 'user',
			[ 'user_name' => $newUserName ],
			[ 'user_id' => $rev->getUser()->getId() ] );
		$this->db->update( 'actor',
			[ 'actor_name' => $newUserName ],
			[ 'actor_user' => $rev->getUser()->getId() ] );

		// Reload the revision and regrab the user name.
		$revAfter = $store->getKnownCurrentRevision( $page->getTitle(), $rev->getId() );
		$userNameAfter = $revAfter->getUser()->getName();

		// The two user names should be different.
		// If they are the same, we are seeing a cached value, which is bad.
		$this->assertNotSame( $userNameBefore, $userNameAfter );

		// This is implied by the above assertion, but explicitly check it, for completeness
		$this->assertSame( $newUserName, $userNameAfter );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::getKnownCurrentRevision
	 */
	public function testGetKnownCurrentRevision_stalePageId() {
		$cache = new WANObjectCache( [ 'cache' => new HashBagOStuff() ] );
		$this->setService( 'MainWANObjectCache', $cache );

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$page = $this->getNonexistingTestPage();
		$rev = $this->createRevisionStoreCacheRecord( $page, $store );

		// Fore bad article ID
		$title = $page->getTitle();
		$title->resetArticleID( 886655 );

		$result = $store->getKnownCurrentRevision( $page->getTitle(), $rev->getId() );

		// Redundant, we really only care that no exception is thrown.
		$this->assertSame( $rev->getId(), $result->getId() );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::getKnownCurrentRevision
	 */
	public function testGetKnownCurrentRevision_revDelete() {
		$cache = new WANObjectCache( [ 'cache' => new HashBagOStuff() ] );
		$this->setService( 'MainWANObjectCache', $cache );

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$page = $this->getNonexistingTestPage();
		$rev = $this->createRevisionStoreCacheRecord( $page, $store );

		// Grab the deleted bitmask
		$deletedBefore = $rev->getVisibility();

		// Change the deleted bitmask in the database, "behind the back" of the cache
		$this->db->update( 'revision',
			[ 'rev_deleted' => RevisionRecord::DELETED_TEXT ],
			[ 'rev_id' => $rev->getId() ] );

		// Reload the revision and regrab the visibility flag.
		$revAfter = $store->getKnownCurrentRevision( $page->getTitle(), $rev->getId() );
		$deletedAfter = $revAfter->getVisibility();

		// The two deleted flags should be different.
		// If they are the same, we are seeing a cached value, which is bad.
		$this->assertNotSame( $deletedBefore, $deletedAfter );

		// This is implied by the above assertion, but explicitly check it, for completeness
		$this->assertSame( RevisionRecord::DELETED_TEXT, $deletedAfter );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::newRevisionFromRow
	 */
	public function testNewRevisionFromRow_userNameChange() {
		$page = $this->getTestPage();
		$text = __METHOD__;
		/** @var RevisionRecord $revRecord */
		$revRecord = $page->doEditContent(
			new WikitextContent( $text ),
			__METHOD__,
			0,
			false,
			$this->getMutableTestUser()->getUser()
		)->value['revision-record'];

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$storeRecord = $store->newRevisionFromRow(
			$this->revisionRecordToRow( $revRecord ),
			0,
			$page->getTitle()
		);

		// Grab the user name
		$userNameBefore = $storeRecord->getUser()->getName();

		// Change the user name in the database
		$newUserName = "Renamed $userNameBefore";
		$this->db->update( 'user',
			[ 'user_name' => $newUserName ],
			[ 'user_id' => $storeRecord->getUser()->getId() ] );
		$this->db->update( 'actor',
			[ 'actor_name' => $newUserName ],
			[ 'actor_user' => $storeRecord->getUser()->getId() ] );

		// Reload the record, passing $fromCache as true to force fresh info from the db,
		// and regrab the user name
		$recordAfter = $store->newRevisionFromRow(
			$this->revisionRecordToRow( $revRecord ),
			0,
			$page->getTitle(),
			true
		);
		$userNameAfter = $recordAfter->getUser()->getName();

		// The two user names should be different.
		// If they are the same, we are seeing a cached value, which is bad.
		$this->assertNotSame( $userNameBefore, $userNameAfter );

		// This is implied by the above assertion, but explicitly check it, for completeness
		$this->assertSame( $newUserName, $userNameAfter );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::newRevisionFromRow
	 */
	public function testNewRevisionFromRow_revDelete() {
		$page = $this->getTestPage();
		$text = __METHOD__;
		/** @var RevisionRecord $revRecord */
		$revRecord = $page->doEditContent(
			new WikitextContent( $text ),
			__METHOD__
		)->value['revision-record'];

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$storeRecord = $store->newRevisionFromRow(
			$this->revisionRecordToRow( $revRecord ),
			0,
			$page->getTitle()
		);

		// Grab the deleted bitmask
		$deletedBefore = $storeRecord->getVisibility();

		// Change the deleted bitmask in the database
		$this->db->update( 'revision',
			[ 'rev_deleted' => RevisionRecord::DELETED_TEXT ],
			[ 'rev_id' => $storeRecord->getId() ] );

		// Reload the record, passing $fromCache as true to force fresh info from the db,
		// and regrab the deleted bitmask
		$recordAfter = $store->newRevisionFromRow(
			$this->revisionRecordToRow( $revRecord ),
			0,
			$page->getTitle(),
			true
		);
		$deletedAfter = $recordAfter->getVisibility();

		// The two deleted flags should be different, because we modified the database.
		$this->assertNotSame( $deletedBefore, $deletedAfter );

		// This is implied by the above assertion, but explicitly check it, for completeness
		$this->assertSame( RevisionRecord::DELETED_TEXT, $deletedAfter );
	}

	public function provideGetContentBlobsForBatchOptions() {
		yield 'all slots' => [ null ];
		yield 'no slots' => [ [] ];
		yield 'main slot' => [ [ SlotRecord::MAIN ] ];
	}

	/**
	 * @dataProvider provideGetContentBlobsForBatchOptions
	 * @covers       \MediaWiki\Revision\RevisionStore::getContentBlobsForBatch
	 * @param array|null $slots
	 * @throws \MWException
	 */
	public function testGetContentBlobsForBatch( $slots ) {
		$this->hideDeprecated( 'Revision::getContentModel' );
		$this->hideDeprecated( 'Revision::__construct' );

		$page1 = $this->getTestPage();
		$text = __METHOD__ . 'b-ä';
		$editStatus = $this->editPage( $page1->getTitle()->getPrefixedDBkey(), $text . '1' );
		$this->assertTrue( $editStatus->isGood(), 'Sanity: must create revision 1' );
		/** @var RevisionRecord $revRecord1 */
		$revRecord1 = $editStatus->getValue()['revision-record'];

		$page2 = $this->getTestPage( $page1->getTitle()->getPrefixedText() . '_other' );
		$editStatus = $this->editPage( $page2->getTitle()->getPrefixedDBkey(), $text . '2' );
		$this->assertTrue( $editStatus->isGood(), 'Sanity: must create revision 2' );
		/** @var RevisionRecord $revRecord2 */
		$revRecord2 = $editStatus->getValue()['revision-record'];

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$result = $store->getContentBlobsForBatch(
			[ $revRecord1->getId(), $revRecord2->getId() ],
			$slots
		);
		$this->assertTrue( $result->isGood() );
		$this->assertSame( [], $result->getErrors() );

		$rowSetsByRevId = $result->getValue();
		$this->assertArrayHasKey( $revRecord1->getId(), $rowSetsByRevId );
		$this->assertArrayHasKey( $revRecord2->getId(), $rowSetsByRevId );

		$rev1rows = $rowSetsByRevId[$revRecord1->getId()];
		$rev2rows = $rowSetsByRevId[$revRecord2->getId()];

		if ( is_array( $slots ) && !in_array( SlotRecord::MAIN, $slots ) ) {
			$this->assertArrayNotHasKey( SlotRecord::MAIN, $rev1rows );
			$this->assertArrayNotHasKey( SlotRecord::MAIN, $rev2rows );
		} else {
			$this->assertArrayHasKey( SlotRecord::MAIN, $rev1rows );
			$this->assertArrayHasKey( SlotRecord::MAIN, $rev2rows );

			$mainSlotRow1 = $rev1rows[ SlotRecord::MAIN ];
			$mainSlotRow2 = $rev2rows[ SlotRecord::MAIN ];

			if ( $mainSlotRow1->model_name ) {
				$this->assertSame(
					( new Revision( $revRecord1 ) )->getContentModel(),
					$mainSlotRow1->model_name
				);
				$this->assertSame(
					( new Revision( $revRecord2 ) )->getContentModel(),
					$mainSlotRow2->model_name
				);
			}

			$this->assertSame( $text . '1', $mainSlotRow1->blob_data );
			$this->assertSame( $text . '2', $mainSlotRow2->blob_data );
		}

		// try again, with objects instead of ids:
		$result2 = $store->getContentBlobsForBatch( [
			(object)[ 'rev_id' => $revRecord1->getId() ],
			(object)[ 'rev_id' => $revRecord2->getId() ],
		], $slots );

		$this->assertTrue( $result2->isGood() );
		$exp1 = var_export( $result->getValue(), true );
		$exp2 = var_export( $result2->getValue(), true );
		$this->assertSame( $exp1, $exp2 );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::getContentBlobsForBatch
	 * @throws \MWException
	 */
	public function testGetContentBlobsForBatch_archive() {
		$page1 = $this->getTestPage( __METHOD__ );
		$text = __METHOD__ . 'b-ä';
		$editStatus = $this->editPage( $page1->getTitle()->getPrefixedDBkey(), $text . '1' );
		$this->assertTrue( $editStatus->isGood(), 'Sanity: must create revision 1' );
		/** @var RevisionRecord $revRecord1 */
		$revRecord1 = $editStatus->getValue()['revision-record'];
		$page1->doDeleteArticleReal( __METHOD__, $this->getTestSysop()->getUser() );

		$page2 = $this->getTestPage( $page1->getTitle()->getPrefixedText() . '_other' );
		$editStatus = $this->editPage( $page2->getTitle()->getPrefixedDBkey(), $text . '2' );
		$this->assertTrue( $editStatus->isGood(), 'Sanity: must create revision 2' );
		/** @var RevisionRecord $revRecord2 */
		$revRecord2 = $editStatus->getValue()['revision-record'];
		$page2->doDeleteArticleReal( __METHOD__, $this->getTestSysop()->getUser() );

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$result = $store->getContentBlobsForBatch( [
			(object)[ 'ar_rev_id' => $revRecord1->getId() ],
			(object)[ 'ar_rev_id' => $revRecord2->getId() ],
		] );
		$this->assertTrue( $result->isGood() );
		$this->assertSame( [], $result->getErrors() );

		$rowSetsByRevId = $result->getValue();
		$this->assertArrayHasKey( $revRecord1->getId(), $rowSetsByRevId );
		$this->assertArrayHasKey( $revRecord2->getId(), $rowSetsByRevId );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::newRevisionsFromBatch
	 */
	public function testGetContentBlobsForBatch_emptyBatch() {
		$rows = new FakeResultWrapper( [] );
		$result = MediaWikiServices::getInstance()->getRevisionStore()
			->getContentBlobsForBatch( $rows );
		$this->assertTrue( $result->isGood() );
		$this->assertSame( [], $result->getValue() );
		$this->assertSame( [], $result->getErrors() );
	}

	public function provideNewRevisionsFromBatchOptions() {
		yield 'No preload slots or content, single page' => [
			[ 'comment' ],
			null,
			[]
		];
		yield 'Preload slots and content, single page' => [
			[ 'comment' ],
			null,
			[
				'slots' => [ SlotRecord::MAIN ],
				'content' => true
			]
		];
		yield 'Ask for no slots' => [
			[ 'comment' ],
			null,
			[ 'slots' => [] ]
		];
		yield 'No preload slots or content, multiple pages' => [
			[ 'comment' ],
			'Other_Page',
			[]
		];
		yield 'Preload slots and content, multiple pages' => [
			[ 'comment' ],
			'Other_Page',
			[
				'slots' => [ SlotRecord::MAIN ],
				'content' => true
			]
		];
		yield 'Preload slots and content, multiple pages, preload page fields' => [
			[ 'page', 'comment' ],
			'Other_Page',
			[
				'slots' => [ SlotRecord::MAIN ],
				'content' => true
			]
		];
	}

	/**
	 * @dataProvider provideNewRevisionsFromBatchOptions
	 * @covers       \MediaWiki\Revision\RevisionStore::newRevisionsFromBatch
	 * @param array|null $queryOptions options to provide to revisionRecordToRow
	 * @param string|null $otherPageTitle
	 * @param array|null $options
	 * @throws \MWException
	 */
	public function testNewRevisionsFromBatch_preloadContent(
		$queryOptions,
		$otherPageTitle = null,
		array $options = []
	) {
		$page1 = $this->getTestPage();
		$text = __METHOD__ . 'b-ä';
		$editStatus = $this->editPage( $page1->getTitle()->getPrefixedDBkey(), $text . '1' );
		$this->assertTrue( $editStatus->isGood(), 'Sanity: must create revision 1' );
		/** @var RevisionRecord $revRecord1 */
		$revRecord1 = $editStatus->getValue()['revision-record'];

		$page2 = $this->getTestPage( $otherPageTitle );
		$editStatus = $this->editPage( $page2->getTitle()->getPrefixedDBkey(), $text . '2' );
		$this->assertTrue( $editStatus->isGood(), 'Sanity: must create revision 2' );
		/** @var RevisionRecord $revRecord2 */
		$revRecord2 = $editStatus->getValue()['revision-record'];

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$result = $store->newRevisionsFromBatch(
			[
				$this->revisionRecordToRow( $revRecord1, $queryOptions ),
				$this->revisionRecordToRow( $revRecord2, $queryOptions )
			],
			$options,
			0, $otherPageTitle ? null : $page1->getTitle()
		);
		$this->assertTrue( $result->isGood() );
		$this->assertSame( [], $result->getErrors() );
		/** @var RevisionRecord[] $records */
		$records = $result->getValue();
		$this->assertRevisionRecordsEqual( $revRecord1, $records[$revRecord1->getId()] );
		$this->assertRevisionRecordsEqual( $revRecord2, $records[$revRecord2->getId()] );

		$this->assertSame(
			$text . '1',
			ContentHandler::getContentText(
				$records[$revRecord1->getId()]->getContent( SlotRecord::MAIN )
			)
		);
		$this->assertSame(
			$text . '2',
			ContentHandler::getContentText(
				$records[$revRecord2->getId()]->getContent( SlotRecord::MAIN )
			)
		);
		$this->assertEquals(
			$page1->getTitle()->getDBkey(),
			$records[$revRecord1->getId()]->getPageAsLinkTarget()->getDBkey()
		);
		$this->assertEquals(
			$page2->getTitle()->getDBkey(),
			$records[$revRecord2->getId()]->getPageAsLinkTarget()->getDBkey()
		);
	}

	/**
	 * @dataProvider provideNewRevisionsFromBatchOptions
	 * @covers       \MediaWiki\Revision\RevisionStore::newRevisionsFromBatch
	 * @param array|null $queryOptions options to provide to revisionRecordToRow
	 * @param string|null $otherPageTitle
	 * @param array|null $options
	 * @throws \MWException
	 */
	public function testNewRevisionsFromBatch_archive(
		$queryOptions,
		$otherPageTitle = null,
		array $options = []
	) {
		$title1 = Title::newFromText( __METHOD__ );
		$text1 = __METHOD__ . '-bä';
		$page1 = WikiPage::factory( $title1 );

		$title2 = $otherPageTitle ? Title::newFromText( $otherPageTitle ) : $title1;
		$text2 = __METHOD__ . '-bö';
		$page2 = $otherPageTitle ? WikiPage::factory( $title2 ) : $page1;

		/** @var RevisionRecord $revRecord1 */
		/** @var RevisionRecord $revRecord2 */
		$revRecord1 = $page1->doEditContent( new WikitextContent( $text1 ), __METHOD__ )
			->value['revision-record'];
		$revRecord2 = $page2->doEditContent( new WikitextContent( $text2 ), __METHOD__ )
			->value['revision-record'];
		$page1->doDeleteArticleReal( __METHOD__, $this->getTestSysop()->getUser() );

		if ( $page2 !== $page1 ) {
			$page2->doDeleteArticleReal( __METHOD__, $this->getTestSysop()->getUser() );
		}

		$store = MediaWikiServices::getInstance()->getRevisionStore();

		$queryInfo = $store->getArchiveQueryInfo();
		$rows = $this->db->select(
			$queryInfo['tables'],
			$queryInfo['fields'],
			[ 'ar_rev_id' => [ $revRecord1->getId(), $revRecord2->getId() ] ],
			__METHOD__,
			[],
			$queryInfo['joins']
		);

		$options['archive'] = true;
		$rows = iterator_to_array( $rows );
		$result = $store->newRevisionsFromBatch(
			$rows, $options, 0, $otherPageTitle ? null : $title1 );

		$this->assertTrue( $result->isGood() );
		$this->assertSame( [], $result->getErrors() );
		/** @var RevisionRecord[] $records */
		$records = $result->getValue();
		$this->assertCount( 2, $records );
		$this->assertRevisionRecordsEqual( $revRecord1, $records[$revRecord1->getId()] );
		$this->assertRevisionRecordsEqual( $revRecord2, $records[$revRecord2->getId()] );

		$this->assertSame(
			$text1,
			ContentHandler::getContentText(
				$records[$revRecord1->getId()]->getContent( SlotRecord::MAIN )
			)
		);
		$this->assertSame(
			$text2,
			ContentHandler::getContentText(
				$records[$revRecord2->getId()]->getContent( SlotRecord::MAIN )
			)
		);
		$this->assertEquals(
			$page1->getTitle()->getDBkey(),
			$records[$revRecord1->getId()]->getPageAsLinkTarget()->getDBkey()
		);
		$this->assertEquals(
			$page2->getTitle()->getDBkey(),
			$records[$revRecord2->getId()]->getPageAsLinkTarget()->getDBkey()
		);
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::newRevisionsFromBatch
	 */
	public function testNewRevisionsFromBatch_emptyBatch() {
		$rows = new FakeResultWrapper( [] );
		$result = MediaWikiServices::getInstance()->getRevisionStore()
			->newRevisionsFromBatch(
				$rows,
				[
					'slots' => [ SlotRecord::MAIN ],
					'content' => true
				]
			);
		$this->assertTrue( $result->isGood() );
		$this->assertSame( [], $result->getValue() );
		$this->assertSame( [], $result->getErrors() );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::newRevisionsFromBatch
	 */
	public function testNewRevisionsFromBatch_wrongTitle() {
		$page1 = $this->getTestPage();
		$text = __METHOD__ . 'b-ä';
		$editStatus = $this->editPage( $page1->getTitle()->getPrefixedDBkey(), $text . '1' );
		$this->assertTrue( $editStatus->isGood(), 'Sanity: must create revision 1' );
		/** @var RevisionRecord $rev1 */
		$revRecord1 = $editStatus->getValue()['revision-record'];

		$this->expectException( InvalidArgumentException::class );
		MediaWikiServices::getInstance()->getRevisionStore()
			->newRevisionsFromBatch(
				[ $this->revisionRecordToRow( $revRecord1 ) ],
				[],
				IDBAccessObject::READ_NORMAL,
				$this->getTestPage( 'Title_Other_Then_The_One_Revision_Belongs_To' )->getTitle()
			);
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::newRevisionsFromBatch
	 */
	public function testNewRevisionsFromBatch_DuplicateRows() {
		$page1 = $this->getTestPage();
		$text = __METHOD__ . 'b-ä';
		$editStatus = $this->editPage( $page1->getTitle()->getPrefixedDBkey(), $text . '1' );
		$this->assertTrue( $editStatus->isGood(), 'Sanity: must create revision 1' );
		/** @var RevisionRecord $revRecord1 */
		$revRecord1 = $editStatus->getValue()['revision-record'];

		$status = MediaWikiServices::getInstance()->getRevisionStore()
			->newRevisionsFromBatch(
				[
					$this->revisionRecordToRow( $revRecord1 ),
					$this->revisionRecordToRow( $revRecord1 )
				]
			);

		$this->assertFalse( $status->isGood() );
		$this->assertTrue( $status->hasMessage( 'internalerror_info' ) );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::countRevisionsBetween
	 */
	public function testCountRevisionsBetween() {
		$NUM = 5;
		$MAX = 1;
		$page = $this->getTestPage( __METHOD__ );
		$revisions = [];
		for ( $revNum = 0; $revNum < $NUM; $revNum++ ) {
			$editStatus = $this->editPage( $page->getTitle()->getPrefixedDBkey(), 'Revision ' . $revNum );
			$this->assertTrue( $editStatus->isGood(), 'Sanity: must create revision ' . $revNum );
			$revisions[] = $editStatus->getValue()['revision-record'];
		}

		$revisionStore = MediaWikiServices::getInstance()->getRevisionStore();
		$this->assertSame( 0,
			$revisionStore->countRevisionsBetween( $page->getId(), $revisions[0], $revisions[0] ),
			'Must return 0 if the same old and new revisions provided' );
		$this->assertSame( 0,
			$revisionStore->countRevisionsBetween( $page->getId(), $revisions[0], $revisions[1] ),
			'Must return 0 if the consecutive old and new revisions provided' );
		$this->assertEquals( $NUM - 3,
			$revisionStore->countRevisionsBetween( $page->getId(), $revisions[0], $revisions[$NUM - 2] ),
			'The count is non-inclusive on both ends if both beginning and end are provided' );
		$this->assertEquals( $NUM - 2,
			$revisionStore->countRevisionsBetween( $page->getId(), $revisions[0], $revisions[$NUM - 2],
				null, 'include_new' ),
			'The count string options are respected' );
		$this->assertEquals( $NUM - 1,
			$revisionStore->countRevisionsBetween( $page->getId(), $revisions[0], $revisions[$NUM - 2],
				null, [ 'include_both' ] ),
			'The count array options are respected' );
		$this->assertEquals( $NUM - 1,
			$revisionStore->countRevisionsBetween( $page->getId(), $revisions[0] ),
			'The count is inclusive on the end if the end is omitted' );
		$this->assertEquals( $NUM + 1, // There was one revision from creating a page, thus NUM + 1
			$revisionStore->countRevisionsBetween( $page->getId() ),
			'The count is inclusive if both beginning and end are omitted' );
		$this->assertEquals( $MAX + 1, // Returns $max + 1 to detect truncation.
			$revisionStore->countRevisionsBetween( $page->getId(), $revisions[0],
				$revisions[$NUM - 1], $MAX ),
			'The $max is incremented to detect truncation' );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::getAuthorsBetween
	 * @covers \MediaWiki\Revision\RevisionStore::countAuthorsBetween
	 */
	public function testAuthorsBetween() {
		$NUM = 5;
		$page = $this->getTestPage( __METHOD__ );
		$users = [
			$this->getTestUser()->getUser(),
			$this->getTestUser()->getUser(),
			$this->getTestSysop()->getUser(),
			new User(),
			$this->getMutableTestUser()->getUser()
		];
		$revisions = [];
		for ( $revNum = 0; $revNum < $NUM; $revNum++ ) {
			$editStatus = $this->editPage(
				$page->getTitle()->getPrefixedDBkey(),
				'Revision ' . $revNum,
				'',
				NS_MAIN,
				$users[$revNum] );
			$this->assertTrue( $editStatus->isGood(), 'Sanity: must create revision ' . $revNum );
			$revisions[] = $editStatus->getValue()['revision-record'];
		}

		$revisionStore = MediaWikiServices::getInstance()->getRevisionStore();
		$this->assertSame( 0,
			$revisionStore->countAuthorsBetween( $page->getId(), $revisions[0], $revisions[0] ),
			'countAuthorsBetween must return 0 if the same old and new revisions provided' );
		$this->assertArrayEquals( [],
			$revisionStore->getAuthorsBetween( $page->getId(), $revisions[0], $revisions[0] ),
			'getAuthorsBetween must return [] if the same old and new revisions provided' );

		$this->assertSame( 0,
			$revisionStore->countAuthorsBetween( $page->getId(), $revisions[0], $revisions[1] ),
			'countAuthorsBetween must return 0 if the consecutive old and new revisions provided' );
		$this->assertArrayEquals( [],
			$revisionStore->getAuthorsBetween( $page->getId(), $revisions[0], $revisions[1] ),
			'getAuthorsBetween must return [] if the consecutive old and new revisions provided' );

		$this->assertEquals( 2,
			$revisionStore->countAuthorsBetween( $page->getId(), $revisions[0], $revisions[$NUM - 2] ),
			'countAuthorsBetween is non-inclusive on both ends if both beginning and end are provided' );
		$result = $revisionStore->getAuthorsBetween( $page->getId(),
			$revisions[0], $revisions[$NUM - 2] );
		$this->assertCount( 2, $result,
			'getAuthorsBetween provides right number of users' );
	}

	public function provideBetweenMethodNames() {
		yield [ 'countRevisionsBetween' ];
		yield [ 'countAuthorsBetween' ];
		yield [ 'getAuthorsBetween' ];
	}

	/**
	 * @dataProvider provideBetweenMethodNames
	 *
	 * @covers \MediaWiki\Revision\RevisionStore::countRevisionsBetween
	 * @covers \MediaWiki\Revision\RevisionStore::countAuthorsBetween
	 * @covers \MediaWiki\Revision\RevisionStore::getAuthorsBetween
	 *
	 * @param string $method the name of the method to test
	 */
	public function testBetweenMethod_differentPages( $method ) {
		$page1 = $this->getTestPage( __METHOD__ );
		$page2 = $this->getTestPage( 'Other_Page' );
		$editStatus = $this->editPage( $page1->getTitle()->getPrefixedDBkey(), 'Revision 1' );
		$this->assertTrue( $editStatus->isGood(), 'Sanity: must create revision 1' );
		$rev1 = $editStatus->getValue()['revision-record'];
		$editStatus = $this->editPage( $page2->getTitle()->getPrefixedDBkey(), 'Revision 1' );
		$this->assertTrue( $editStatus->isGood(), 'Sanity: must create revision 1' );
		$rev2 = $editStatus->getValue()['revision-record'];

		$this->expectException( InvalidArgumentException::class );
		MediaWikiServices::getInstance()->getRevisionStore()
			->{$method}( $page1->getId(), $rev1, $rev2 );
	}

	/**
	 * @dataProvider provideBetweenMethodNames
	 *
	 * @covers \MediaWiki\Revision\RevisionStore::countRevisionsBetween
	 * @covers \MediaWiki\Revision\RevisionStore::countAuthorsBetween
	 * @covers \MediaWiki\Revision\RevisionStore::getAuthorsBetween
	 *
	 * @param string $method the name of the method to test
	 */
	public function testBetweenMethod_unsavedRevision( $method ) {
		$rev1 = new MutableRevisionRecord( $this->getTestPageTitle() );
		$rev2 = new MutableRevisionRecord( $this->getTestPageTitle() );

		$this->expectException( InvalidArgumentException::class );
		MediaWikiServices::getInstance()->getRevisionStore()->{$method}(
			$this->getTestPage()->getId(), $rev1, $rev2 );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::getFirstRevision
	 */
	public function testGetFirstRevision() {
		$pageTitle = Title::newFromText( 'Test_Get_First_Revision' );
		$editStatus = $this->editPage( $pageTitle->getPrefixedDBkey(), 'First Revision' );
		$this->assertTrue( $editStatus->isGood(), 'Sanity: must create first revision' );
		$firstRevId = $editStatus->getValue()['revision-record']->getID();
		$editStatus = $this->editPage( $pageTitle->getPrefixedText(), 'New Revision' );
		$this->assertTrue( $editStatus->isGood(), 'Sanity: must create new revision' );
		$this->assertNotSame(
			$firstRevId,
			$editStatus->getValue()['revision-record']->getID(),
			'Sanity: new revision must have different id'
		);
		$this->assertSame(
			$firstRevId,
			MediaWikiServices::getInstance()
				->getRevisionStore()
				->getFirstRevision( $pageTitle )
				->getId()
		);
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::getFirstRevision
	 */
	public function testGetFirstRevision_nonexistent_page() {
		$this->assertNull(
			MediaWikiServices::getInstance()
				->getRevisionStore()
				->getFirstRevision( $this->getNonexistingTestPage( __METHOD__ )->getTitle() )
		);
	}
}
