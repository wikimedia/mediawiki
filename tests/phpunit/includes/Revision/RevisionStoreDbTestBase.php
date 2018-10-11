<?php

namespace MediaWiki\Tests\Revision;

use CommentStoreComment;
use Content;
use Exception;
use HashBagOStuff;
use InvalidArgumentException;
use Language;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\IncompleteRevisionException;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Storage\BlobStoreFactory;
use MediaWiki\Storage\SqlBlobStore;
use MediaWiki\User\UserIdentityValue;
use MediaWikiTestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Revision;
use TestUserRegistry;
use Title;
use WANObjectCache;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\DatabaseDomain;
use Wikimedia\Rdbms\DatabaseSqlite;
use Wikimedia\Rdbms\FakeResultWrapper;
use Wikimedia\Rdbms\LoadBalancer;
use Wikimedia\Rdbms\TransactionProfiler;
use WikiPage;
use WikitextContent;

/**
 * @group Database
 * @group RevisionStore
 */
abstract class RevisionStoreDbTestBase extends MediaWikiTestCase {

	/**
	 * @var Title
	 */
	private $testPageTitle;

	/**
	 * @var WikiPage
	 */
	private $testPage;

	/**
	 * @return int
	 */
	abstract protected function getMcrMigrationStage();

	/**
	 * @return bool
	 */
	protected function getContentHandlerUseDB() {
		return true;
	}

	/**
	 * @return string[]
	 */
	abstract protected function getMcrTablesToReset();

	public function setUp() {
		parent::setUp();
		$this->tablesUsed[] = 'archive';
		$this->tablesUsed[] = 'page';
		$this->tablesUsed[] = 'revision';
		$this->tablesUsed[] = 'comment';

		$this->tablesUsed += $this->getMcrTablesToReset();

		$this->setMwGlobals( [
			'wgMultiContentRevisionSchemaMigrationStage' => $this->getMcrMigrationStage(),
			'wgContentHandlerUseDB' => $this->getContentHandlerUseDB(),
			'wgCommentTableSchemaMigrationStage' => MIGRATION_OLD,
			'wgActorTableSchemaMigrationStage' => SCHEMA_COMPAT_OLD,
		] );

		$this->overrideMwServices();
	}

	protected function addCoreDBData() {
		// Blank out. This would fail with a modified schema, and we don't need it.
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
	 * @return WikiPage
	 */
	protected function getTestPage() {
		if ( $this->testPage ) {
			return $this->testPage;
		}

		$title = $this->getTestPageTitle();
		$this->testPage = WikiPage::factory( $title );

		if ( !$this->testPage->exists() ) {
			// Make sure we don't write to the live db.
			$this->ensureMockDatabaseConnection( wfGetDB( DB_MASTER ) );

			$user = static::getTestSysop()->getUser();

			$this->testPage->doEditContent(
				new WikitextContent( 'UTContent-' . __CLASS__ ),
				'UTPageSummary-' . __CLASS__,
				EDIT_NEW | EDIT_SUPPRESS_RC,
				false,
				$user
			);
		}

		return $this->testPage;
	}

	/**
	 * @return LoadBalancer|PHPUnit_Framework_MockObject_MockObject
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
			function ( array $server, $dbNameOverride ) {
				return $this->getDatabaseMock( $server );
			}
		);

		return $lb;
	}

	/**
	 * @return Database|PHPUnit_Framework_MockObject_MockObject
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
	 * @covers \MediaWiki\Revision\RevisionStore::checkDatabaseWikiId
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
			$this->getMcrMigrationStage(),
			MediaWikiServices::getInstance()->getActorMigration(),
			$wikiId
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

		$this->overrideMwServices();
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
		$row = $this->revisionToRow( new Revision( $rev ), [] );

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
		$this->assertSelect(
			$queryInfo['tables'],
			array_keys( $row ),
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

		$this->overrideMwServices();
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
			new InvalidArgumentException( 'main slot must be provided' )
		];
		yield 'no main slot' => [
			[
				'slot' => SlotRecord::newUnsaved( 'aux', new WikitextContent( 'Turkey' ) ),
				'comment' => $this->getRandomCommentStoreComment(),
				'timestamp' => '20171117010101',
				'user' => true,
			],
			new InvalidArgumentException( 'main slot must be provided' )
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
	}

	/**
	 * @dataProvider provideInsertRevisionOn_failures
	 * @covers \MediaWiki\Revision\RevisionStore::insertRevisionOn
	 */
	public function testInsertRevisionOn_failures(
		array $revDetails = [],
		Exception $exception
	) {
		$rev = $this->getRevisionRecordFromDetailsArray( $revDetails );

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
	 * @covers \MediaWiki\Revision\RevisionStore::findSlotContentId
	 */
	public function testNewNullRevision( Title $title, $revDetails, $comment, $minor = false ) {
		$this->overrideMwServices();

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
		$page->updateRevisionOn( $dbw, new Revision( $baseRev ), $page->getLatest() );

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
		/** @var Revision $rev */
		$rev = $status->value['revision'];

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$revisionRecord = $store->getRevisionById( $rev->getId() );
		$result = $store->getRcIdIfUnpatrolled( $revisionRecord );

		$this->assertGreaterThan( 0, $result );
		$this->assertSame(
			$store->getRecentChange( $revisionRecord )->getAttribute( 'rc_id' ),
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
		/** @var Revision $rev */
		$rev = $status->value['revision'];

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$revisionRecord = $store->getRevisionById( $rev->getId() );
		$result = $store->getRcIdIfUnpatrolled( $revisionRecord );

		$this->assertSame( 0, $result );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::getRecentChange
	 */
	public function testGetRecentChange() {
		$page = $this->getTestPage();
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
	 * @covers \MediaWiki\Revision\RevisionStore::getRevisionById
	 */
	public function testGetRevisionById() {
		$page = $this->getTestPage();
		$content = new WikitextContent( __METHOD__ );
		$status = $page->doEditContent( $content, __METHOD__ );
		/** @var Revision $rev */
		$rev = $status->value['revision'];

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$revRecord = $store->getRevisionById( $rev->getId() );

		$this->assertSame( $rev->getId(), $revRecord->getId() );
		$this->assertTrue( $revRecord->getSlot( SlotRecord::MAIN )->getContent()->equals( $content ) );
		$this->assertSame( __METHOD__, $revRecord->getComment()->text );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::getRevisionByTitle
	 */
	public function testGetRevisionByTitle() {
		$page = $this->getTestPage();
		$content = new WikitextContent( __METHOD__ );
		$status = $page->doEditContent( $content, __METHOD__ );
		/** @var Revision $rev */
		$rev = $status->value['revision'];

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$revRecord = $store->getRevisionByTitle( $page->getTitle() );

		$this->assertSame( $rev->getId(), $revRecord->getId() );
		$this->assertTrue( $revRecord->getSlot( SlotRecord::MAIN )->getContent()->equals( $content ) );
		$this->assertSame( __METHOD__, $revRecord->getComment()->text );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::getRevisionByPageId
	 */
	public function testGetRevisionByPageId() {
		$page = $this->getTestPage();
		$content = new WikitextContent( __METHOD__ );
		$status = $page->doEditContent( $content, __METHOD__ );
		/** @var Revision $rev */
		$rev = $status->value['revision'];

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$revRecord = $store->getRevisionByPageId( $page->getId() );

		$this->assertSame( $rev->getId(), $revRecord->getId() );
		$this->assertTrue( $revRecord->getSlot( SlotRecord::MAIN )->getContent()->equals( $content ) );
		$this->assertSame( __METHOD__, $revRecord->getComment()->text );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::getRevisionByTimestamp
	 */
	public function testGetRevisionByTimestamp() {
		// Make sure there is 1 second between the last revision and the rev we create...
		// Otherwise we might not get the correct revision and the test may fail...
		// :(
		$page = $this->getTestPage();
		sleep( 1 );
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
		$this->assertTrue( $revRecord->getSlot( SlotRecord::MAIN )->getContent()->equals( $content ) );
		$this->assertSame( __METHOD__, $revRecord->getComment()->text );
	}

	protected function revisionToRow( Revision $rev, $options = [ 'page', 'user', 'comment' ] ) {
		// XXX: the WikiPage object loads another RevisionRecord from the database. Not great.
		$page = WikiPage::factory( $rev->getTitle() );

		$fields = [
			'rev_id' => (string)$rev->getId(),
			'rev_page' => (string)$rev->getPage(),
			'rev_timestamp' => $this->db->timestamp( $rev->getTimestamp() ),
			'rev_user_text' => (string)$rev->getUserText(),
			'rev_user' => (string)$rev->getUser(),
			'rev_minor_edit' => $rev->isMinor() ? '1' : '0',
			'rev_deleted' => (string)$rev->getVisibility(),
			'rev_len' => (string)$rev->getSize(),
			'rev_parent_id' => (string)$rev->getParentId(),
			'rev_sha1' => (string)$rev->getSha1(),
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
				'user_name' => (string)$rev->getUserText(),
			];
		}

		if ( in_array( 'comment', $options ) ) {
			$fields += [
				'rev_comment_text' => $rev->getComment(),
				'rev_comment_data' => null,
				'rev_comment_cid' => null,
			];
		}

		if ( $rev->getId() ) {
			$fields += [
				'rev_id' => (string)$rev->getId(),
			];
		}

		return (object)$fields;
	}

	protected function assertRevisionRecordMatchesRevision(
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
		$this->assertSame( $rev->getContentFormat(),
			$record->getContent( SlotRecord::MAIN )->getDefaultFormat() );
		$this->assertSame( $rev->getContentModel(), $record->getContent( SlotRecord::MAIN )->getModel() );
		$this->assertLinkTargetsEqual( $rev->getTitle(), $record->getPageAsLinkTarget() );

		$revRec = $rev->getRevisionRecord();
		$revMain = $revRec->getSlot( SlotRecord::MAIN );
		$recMain = $record->getSlot( SlotRecord::MAIN );

		$this->assertSame( $revMain->hasOrigin(), $recMain->hasOrigin(), 'hasOrigin' );
		$this->assertSame( $revMain->hasAddress(), $recMain->hasAddress(), 'hasAddress' );
		$this->assertSame( $revMain->hasContentId(), $recMain->hasContentId(), 'hasContentId' );

		if ( $revMain->hasOrigin() ) {
			$this->assertSame( $revMain->getOrigin(), $recMain->getOrigin(), 'getOrigin' );
		}

		if ( $revMain->hasAddress() ) {
			$this->assertSame( $revMain->getAddress(), $recMain->getAddress(), 'getAddress' );
		}

		if ( $revMain->hasContentId() ) {
			$this->assertSame( $revMain->getContentId(), $recMain->getContentId(), 'getContentId' );
		}
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::newRevisionFromRow
	 * @covers \MediaWiki\Revision\RevisionStore::getQueryInfo
	 */
	public function testNewRevisionFromRow_getQueryInfo() {
		$page = $this->getTestPage();
		$text = __METHOD__ . 'a-ä';
		/** @var Revision $rev */
		$rev = $page->doEditContent(
			new WikitextContent( $text ),
			__METHOD__ . 'a'
		)->value['revision'];

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$info = $store->getQueryInfo();
		$row = $this->db->selectRow(
			$info['tables'],
			$info['fields'],
			[ 'rev_id' => $rev->getId() ],
			__METHOD__,
			[],
			$info['joins']
		);
		$record = $store->newRevisionFromRow(
			$row,
			[],
			$page->getTitle()
		);
		$this->assertRevisionRecordMatchesRevision( $rev, $record );
		$this->assertSame( $text, $rev->getContent()->serialize() );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::newRevisionFromRow
	 */
	public function testNewRevisionFromRow_anonEdit() {
		$page = $this->getTestPage();
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
	 * @covers \MediaWiki\Revision\RevisionStore::newRevisionFromRow
	 */
	public function testNewRevisionFromRow_anonEdit_legacyEncoding() {
		$this->setMwGlobals( 'wgLegacyEncoding', 'windows-1252' );
		$this->overrideMwServices();
		$page = $this->getTestPage();
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
	 * @covers \MediaWiki\Revision\RevisionStore::newRevisionFromRow
	 */
	public function testNewRevisionFromRow_userEdit() {
		$page = $this->getTestPage();
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
	 * @covers \MediaWiki\Revision\RevisionStore::newRevisionFromArchiveRow
	 * @covers \MediaWiki\Revision\RevisionStore::getArchiveQueryInfo
	 */
	public function testNewRevisionFromArchiveRow_getArchiveQueryInfo() {
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
		$this->assertSame( $text, $record->getContent( SlotRecord::MAIN )->serialize() );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::newRevisionFromArchiveRow
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
		$this->assertSame( $text, $record->getContent( SlotRecord::MAIN )->serialize() );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::newRevisionFromArchiveRow
	 */
	public function testNewRevisionFromArchiveRow_no_user() {
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
			'ar_actor' => null,
			'ar_content_format' => null,
			'ar_content_model' => null,
		];

		\Wikimedia\suppressWarnings();
		$record = $store->newRevisionFromArchiveRow( $row );
		\Wikimedia\suppressWarnings( true );

		$this->assertInstanceOf( RevisionRecord::class, $record );
		$this->assertInstanceOf( UserIdentityValue::class, $record->getUser() );
		$this->assertSame( 'Unknown user', $record->getUser()->getName() );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::newRevisionFromRow
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
		/** @var Revision $origRev */
		$page->doEditContent( new WikitextContent( "First" ), __METHOD__ . '-first' );
		$origRev = $page->doEditContent( new WikitextContent( "Foo" ), __METHOD__ )
			->value['revision'];
		$orig = $origRev->getRevisionRecord();
		$page->doDeleteArticle( __METHOD__ );

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
	 * @covers \MediaWiki\Revision\RevisionStore::loadRevisionFromId
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
	 * @covers \MediaWiki\Revision\RevisionStore::loadRevisionFromPageId
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
	 * @covers \MediaWiki\Revision\RevisionStore::loadRevisionFromTitle
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
	 * @covers \MediaWiki\Revision\RevisionStore::loadRevisionFromTimestamp
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
	 * @covers \MediaWiki\Revision\RevisionStore::listRevisionSizes
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
	 * @covers \MediaWiki\Revision\RevisionStore::getPreviousRevision
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
	 * @covers \MediaWiki\Revision\RevisionStore::getNextRevision
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
	 * @covers \MediaWiki\Revision\RevisionStore::getTimestampFromId
	 */
	public function testGetTimestampFromId_found() {
		$page = $this->getTestPage();
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
	 * @covers \MediaWiki\Revision\RevisionStore::getTimestampFromId
	 */
	public function testGetTimestampFromId_notFound() {
		$page = $this->getTestPage();
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
		} elseif ( isset( $array['content_format'] ) ) {
			$this->assertSame(
				$array['content_format'],
				$result->getSlot( SlotRecord::MAIN )->getContent()->getDefaultFormat()
			);
			$this->assertSame( $array['content_model'], $result->getSlot( SlotRecord::MAIN )->getModel() );
		}
	}

	/**
	 * @dataProvider provideNewMutableRevisionFromArray
	 * @covers \MediaWiki\Revision\RevisionStore::newMutableRevisionFromArray
	 */
	public function testNewMutableRevisionFromArray_legacyEncoding( array $array ) {
		$cache = new WANObjectCache( [ 'cache' => new HashBagOStuff() ] );
		$lb = MediaWikiServices::getInstance()->getDBLoadBalancer();
		$blobStore = new SqlBlobStore( $lb, $cache );
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
