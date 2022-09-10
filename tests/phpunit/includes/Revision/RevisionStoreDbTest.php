<?php

namespace MediaWiki\Tests\Revision;

use CommentStoreComment;
use Content;
use Exception;
use FallbackContent;
use HashBagOStuff;
use IDBAccessObject;
use InvalidArgumentException;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Revision\IncompleteRevisionException;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionAccessException;
use MediaWiki\Revision\RevisionArchiveRecord;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionSlots;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\RevisionStoreRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Storage\BlobStore;
use MediaWiki\Storage\SqlBlobStore;
use MediaWiki\User\UserIdentityValue;
use MediaWikiIntegrationTestCase;
use MWTimestamp;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\NullLogger;
use StatusValue;
use TestUserRegistry;
use TextContent;
use Title;
use TitleFactory;
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
use Wikimedia\TestingAccessWrapper;
use WikiPage;
use WikitextContent;

/**
 * @group Database
 * @group RevisionStore
 */
class RevisionStoreDbTest extends MediaWikiIntegrationTestCase {

	/**
	 * @var Title
	 */
	private $testPageTitle;

	/**
	 * @var WikiPage
	 */
	private $testPage;

	protected function setUp(): void {
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
			$this->ensureMockDatabaseConnection( wfGetDB( DB_PRIMARY ) );

			$user = static::getTestSysop()->getUser();

			$page->doUserEditContent(
				new WikitextContent( 'UTContent-' . __CLASS__ ),
				$user,
				'UTPageSummary-' . __CLASS__,
				EDIT_NEW | EDIT_SUPPRESS_RC
			);
		}

		if ( $pageTitle === null ) {
			$this->testPage = $page;
		}
		return $page;
	}

	/**
	 * @param array $server
	 * @return LoadBalancer|MockObject
	 */
	private function getLoadBalancerMock( array $server ) {
		$domain = new DatabaseDomain( $server['dbname'], null, $server['tablePrefix'] );

		$lb = $this->getMockBuilder( LoadBalancer::class )
			->onlyMethods( [ 'reallyOpenConnection' ] )
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
	 * @param array $params
	 * @return Database|MockObject
	 */
	private function getDatabaseMock( array $params ) {
		$db = $this->getMockBuilder( DatabaseSqlite::class )
			->onlyMethods( [
				'select',
				'doSingleStatementQuery',
				'open',
				'closeConnection',
				'isOpen'
			] )->setConstructorArgs( [ $params ] )
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
		$this->overrideConfigValues(
			[
				MainConfigNames::DBname => $dbName,
				MainConfigNames::DBprefix => $dbPrefix,
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
				'agent' => '',
				'serverName' => '*dummy*',
				'load' => 100,
				'srvCache' => new HashBagOStuff(),
				'profiler' => null,
				'trxProfiler' => new TransactionProfiler(),
				'connLogger' => new NullLogger(),
				'queryLogger' => new NullLogger(),
				'replLogger' => new NullLogger(),
				'errorLogger' => static function () {
				},
				'deprecationLogger' => static function () {
				},
				'type' => 'test',
				'dbname' => $dbName,
				'tablePrefix' => $dbPrefix,
			]
		);
		$db = $loadBalancer->getConnection( DB_REPLICA );

		/** @var SqlBlobStore $blobStore */
		$blobStore = $this->createMock( SqlBlobStore::class );

		$store = new RevisionStore(
			$loadBalancer,
			$blobStore,
			new WANObjectCache( [ 'cache' => new HashBagOStuff() ] ),
			new HashBagOStuff(),
			$this->getServiceContainer()->getCommentStore(),
			$this->getServiceContainer()->getContentModelStore(),
			$this->getServiceContainer()->getSlotRoleStore(),
			$this->getServiceContainer()->getSlotRoleRegistry(),
			$this->getServiceContainer()->getActorMigration(),
			$this->getServiceContainer()->getActorStoreFactory()->getActorStore( $dbDomain ),
			$this->getServiceContainer()->getContentHandlerFactory(),
			$this->getServiceContainer()->getPageStore(),
			$this->getServiceContainer()->getTitleFactory(),
			$this->getServiceContainer()->getHookContainer(),
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
		yield 'Multi-slot revision insertion' => [
			[
				'content' => [
					'main' => new WikitextContent( 'Chicken' ),
					'aux' => new TextContent( 'Egg' ),
				],
				'page' => true,
				'comment' => $this->getRandomCommentStoreComment(),
				'timestamp' => '20171117010101',
				'user' => true,
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

		$store = $this->getServiceContainer()->getRevisionStore();
		$return = $store->insertRevisionOn( $rev, wfGetDB( DB_PRIMARY ) );

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
		$numberOfSlots = count( $rev->getSlotRoles() );

		// new schema is written
		$this->assertSelect(
			'slots',
			[ 'count(*)' ],
			[ 'slot_revision_id' => $rev->getId() ],
			[ [ (string)$numberOfSlots ] ]
		);

		$store = $this->getServiceContainer()->getRevisionStore();
		$revQuery = $store->getSlotsQueryInfo( [ 'content' ] );

		$this->assertSelect(
			$revQuery['tables'],
			[ 'count(*)' ],
			[
				'slot_revision_id' => $rev->getId(),
			],
			[ [ (string)$numberOfSlots ] ],
			[],
			$revQuery['joins']
		);

		$row = $this->revisionRecordToRow( $rev, [] );

		// unset nulled fields
		unset( $row->rev_content_model );
		unset( $row->rev_content_format );

		// unset fake fields
		unset( $row->rev_comment_text );
		unset( $row->rev_comment_data );
		unset( $row->rev_comment_cid );
		unset( $row->rev_comment_id );

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
		// Assert that the same content ID has been used
		$this->assertSame( $a->getContentId(), $b->getContentId() );
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

		$store = $this->getServiceContainer()->getRevisionStore();

		// Insert the first revision
		$revOne = $this->getRevisionRecordFromDetailsArray( $revDetails );
		$firstReturn = $store->insertRevisionOn( $revOne, wfGetDB( DB_PRIMARY ) );
		$this->assertLinkTargetsEqual( $title, $firstReturn->getPageAsLinkTarget() );
		$this->assertRevisionRecordsEqual( $revOne, $firstReturn );

		// Insert a second revision inheriting the same blob address
		$revDetails['slot'] = SlotRecord::newInherited( $firstReturn->getSlot( SlotRecord::MAIN ) );
		$revTwo = $this->getRevisionRecordFromDetailsArray( $revDetails );
		$secondReturn = $store->insertRevisionOn( $revTwo, wfGetDB( DB_PRIMARY ) );
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

		$store = $this->getServiceContainer()->getRevisionStore();

		$this->expectException( get_class( $exception ) );
		$this->expectExceptionMessage( $exception->getMessage() );
		$this->expectExceptionCode( $exception->getCode() );
		$store->insertRevisionOn( $rev, wfGetDB( DB_PRIMARY ) );
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
		yield [
			Title::newFromText( 'UTPage_notAutoCreated' ),
			[
				'content' => [
					'main' => new WikitextContent( 'Chicken' ),
					'aux' => new WikitextContent( 'Omelet' ),
				],
			],
			CommentStoreComment::newUnsavedComment( __METHOD__ . ' comment multi' ),
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
			$page->doUserEditContent(
				new WikitextContent( __METHOD__ ),
				$this->getTestSysop()->getUser(),
				__METHOD__,
				EDIT_NEW
			);
		}

		$revDetails['page'] = $page->getId();
		$revDetails['timestamp'] = wfTimestampNow();
		$revDetails['comment'] = CommentStoreComment::newUnsavedComment( 'Base' );
		$revDetails['user'] = $user;

		$baseRev = $this->getRevisionRecordFromDetailsArray( $revDetails );
		$store = $this->getServiceContainer()->getRevisionStore();

		$dbw = wfGetDB( DB_PRIMARY );
		$baseRev = $store->insertRevisionOn( $baseRev, $dbw );
		$page->updateRevisionOn( $dbw, $baseRev, $page->getLatest() );

		$record = $store->newNullRevision(
			wfGetDB( DB_PRIMARY ),
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
		$store = $this->getServiceContainer()->getRevisionStore();
		$record = $store->newNullRevision(
			wfGetDB( DB_PRIMARY ),
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
		$status = $page->doUserEditContent(
			new WikitextContent( __METHOD__ ),
			$this->getTestUser()->getUser(),
			__METHOD__
		);
		/** @var RevisionRecord $revRecord */
		$revRecord = $status->value['revision-record'];

		$store = $this->getServiceContainer()->getRevisionStore();
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
		$status = $page->doUserEditContent(
			new WikitextContent( __METHOD__ ),
			$sysop,
			__METHOD__
		);
		/** @var RevisionRecord $revRecord */
		$revRecord = $status->value['revision-record'];

		$store = $this->getServiceContainer()->getRevisionStore();
		$storeRecord = $store->getRevisionById( $revRecord->getId() );
		$result = $store->getRcIdIfUnpatrolled( $storeRecord );

		$this->assertSame( 0, $result );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::getRecentChange
	 */
	public function testGetRecentChange() {
		$page = $this->getTestPage();
		$content = new WikitextContent( __METHOD__ );
		$status = $page->doUserEditContent(
			$content,
			$this->getTestSysop()->getUser(),
			__METHOD__
		);
		/** @var RevisionRecord $revRecord */
		$revRecord = $status->value['revision-record'];

		$store = $this->getServiceContainer()->getRevisionStore();
		$storeRecord = $store->getRevisionById( $revRecord->getId() );
		$recentChange = $store->getRecentChange( $storeRecord );

		$this->assertEquals( $revRecord->getId(), $recentChange->getAttribute( 'rc_this_oldid' ) );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::getRevisionById
	 */
	public function testGetRevisionById() {
		$page = $this->getTestPage();
		$content = new WikitextContent( __METHOD__ );
		$status = $page->doUserEditContent(
			$content,
			$this->getTestSysop()->getUser(),
			__METHOD__
		);
		/** @var RevisionRecord $revRecord */
		$revRecord = $status->value['revision-record'];

		$store = $this->getServiceContainer()->getRevisionStore();
		$storeRecord = $store->getRevisionById( $revRecord->getId() );

		$this->assertSame( $revRecord->getId(), $storeRecord->getId() );
		$this->assertTrue( $storeRecord->getSlot( SlotRecord::MAIN )->getContent()->equals( $content ) );
		$this->assertSame( __METHOD__, $storeRecord->getComment()->text );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::getRevisionById
	 */
	public function testGetRevisionById_crossWiki_withPage() {
		$page = $this->getTestPage();
		$content = new WikitextContent( __METHOD__ );
		$status = $page->doUserEditContent(
			$content,
			$this->getTestSysop()->getUser(),
			__METHOD__
		);
		/** @var RevisionRecord $revRecord */
		$revRecord = $status->value['revision-record'];
		$revId = $revRecord->getId();

		// Pretend the local test DB is a sister site
		$wikiId = $this->db->getDomainID();
		$store = $this->getServiceContainer()->getRevisionStoreFactory()
			->getRevisionStore( $wikiId );

		// Construct a ProperPageIdentity with the sister site's wiki Id
		$pageIdentity = new PageIdentityValue(
			$page->getId(), $page->getNamespace(), $page->getDBkey(), $wikiId
		);
		$storeRecord = $store->getRevisionById( $revId, 0, $pageIdentity );

		$this->assertSame( $wikiId, $storeRecord->getWikiId() );
		$this->assertSame( $revId, $storeRecord->getId( $wikiId ) );
		$this->assertTrue( $storeRecord->getSlot( SlotRecord::MAIN )->getContent()->equals( $content ) );
		$this->assertSame( __METHOD__, $storeRecord->getComment()->text );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::getRevisionById
	 */
	public function testGetRevisionById_crossWiki() {
		$page = $this->getTestPage();
		$content = new WikitextContent( __METHOD__ );
		$status = $page->doUserEditContent(
			$content,
			$this->getTestSysop()->getUser(),
			__METHOD__
		);
		/** @var RevisionRecord $revRecord */
		$revRecord = $status->value['revision-record'];
		$revId = $revRecord->getId();
		$pageId = $revRecord->getPageId();

		// Make TitleFactory always fail, since it should not be used for the cross-wiki case.
		$noOpTitleFactory = $this->createNoOpMock( TitleFactory::class );
		$this->setService( 'TitleFactory', $noOpTitleFactory );

		// Pretend the local test DB is a sister site
		$wikiId = $this->db->getDomainID();
		$store = $this->getServiceContainer()->getRevisionStoreFactory()
			->getRevisionStore( $wikiId );

		$storeRecord = $store->getRevisionById( $revId );

		$this->assertSame( $wikiId, $storeRecord->getWikiId() );
		$this->assertSame( $wikiId, $storeRecord->getPage()->getWikiId() );
		$this->assertNotInstanceOf( Title::class, $storeRecord->getPage() );
		$this->assertSame( $revId, $storeRecord->getId( $wikiId ) );
		$this->assertSame( $pageId, $storeRecord->getPage()->getId( $wikiId ) );
		$this->assertTrue( $storeRecord->getSlot( SlotRecord::MAIN )->getContent()->equals( $content ) );
		$this->assertSame( __METHOD__, $storeRecord->getComment()->text );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::getRevisionById
	 */
	public function testGetRevisionById_undefinedContentModel() {
		$page = $this->getTestPage();
		$content = new WikitextContent( __METHOD__ );
		$status = $page->doUserEditContent(
			$content,
			$this->getTestSysop()->getUser(),
			__METHOD__
		);
		/** @var RevisionRecord $revRecord */
		$revRecord = $status->value['revision-record'];

		$mockContentHandlerFactory =
			$this->createNoOpMock( IContentHandlerFactory::class, [ 'isDefinedModel' ] );

		$mockContentHandlerFactory->method( 'isDefinedModel' )
			->willReturn( false );

		$this->setService( 'ContentHandlerFactory', $mockContentHandlerFactory );
		$store = $this->getServiceContainer()->getRevisionStore();

		$storeRecord = $store->getRevisionById( $revRecord->getId() );

		$this->assertSame( $revRecord->getId(), $storeRecord->getId() );

		$actualContent = $storeRecord->getSlot( SlotRecord::MAIN )->getContent();
		$this->assertInstanceOf( FallbackContent::class, $actualContent );
		$this->assertSame( __METHOD__, $actualContent->serialize() );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::getRevisionByTitle
	 * @dataProvider provideRevisionByTitle
	 *
	 * @param callable $getTitle
	 */
	public function testGetRevisionByTitle( $getTitle ) {
		$page = $this->getTestPage();
		$title = $getTitle();
		$content = new WikitextContent( __METHOD__ );
		$status = $page->doUserEditContent(
			$content,
			$this->getTestSysop()->getUser(),
			__METHOD__
		);
		/** @var RevisionRecord $revRecord */
		$revRecord = $status->value['revision-record'];

		$store = $this->getServiceContainer()->getRevisionStore();
		$storeRecord = $store->getRevisionByTitle( $title );

		$this->assertSame( $revRecord->getId(), $storeRecord->getId() );
		$this->assertTrue( $storeRecord->getSlot( SlotRecord::MAIN )->getContent()->equals( $content ) );
		$this->assertSame( __METHOD__, $storeRecord->getComment()->text );
	}

	public function provideRevisionByTitle() {
		return [
			[ function () {
				return $this->getTestPage()->getTitle();
			} ],
			[ function () {
				return $this->getTestPage()->getTitle()->toPageIdentity();
			} ]
		];
	}

	private function executeWithForeignStore( string $dbDomain, callable $callback ) {
		$services = $this->getServiceContainer();
		// Configure the load balancer to route queries for the "foreign" domain to the test DB
		$dbLoadBalancer = $services->getDBLoadBalancer();
		$dbLoadBalancer->setDomainAliases( [ $dbDomain => $dbLoadBalancer->getLocalDomainID() ] );
		$store = new RevisionStore(
			$dbLoadBalancer,
			$services->getBlobStore(),
			$services->getMainWANObjectCache(),
			new HashBagOStuff(),
			$services->getCommentStore(),
			$services->getContentModelStore(),
			$services->getSlotRoleStore(),
			$services->getSlotRoleRegistry(),
			$services->getActorMigration(),
			$services->getActorStoreFactory()->getActorStore( $dbDomain ),
			$services->getContentHandlerFactory(),
			$services->getPageStoreFactory()->getPageStore( $dbDomain ),
			$services->getTitleFactory(),
			$services->getHookContainer(),
			$dbDomain
		);

		// Redefine the DBLoadBalancer service to verify Title doesn't attempt to resolve its ID
		// via wfGetDB()
		$localLoadBalancerMock = $this->createMock( ILoadBalancer::class );
		$localLoadBalancerMock->expects( $this->never() )
			->method( $this->anything() );

		try {
			$this->setService( 'DBLoadBalancer', $localLoadBalancerMock );
			$callback( $store );
		} finally {
			// Restore the original load balancer to make test teardown work
			$this->setService( 'DBLoadBalancer', $dbLoadBalancer );
		}
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::getRevisionByTitle
	 */
	public function testGetLatestKnownRevision_foreigh() {
		$page = $this->getTestPage();
		$status = $this->editPage( $page, __METHOD__ );
		$this->assertStatusGood( $status, 'edited a page' );
		/** @var RevisionRecord $revRecord */
		$revRecord = $status->value['revision-record'];
		$dbDomain = 'some_foreign_wiki';
		$this->executeWithForeignStore(
			$dbDomain,
			function ( RevisionStore $store ) use ( $page, $dbDomain, $revRecord ) {
				$storeRecord = $store->getKnownCurrentRevision(
					new PageIdentityValue( $page->getId(), $page->getNamespace(), $page->getDBkey(), $dbDomain )
				);
				$this->assertSame( $dbDomain, $storeRecord->getWikiId() );
				$this->assertSame( $revRecord->getId(), $storeRecord->getId( $dbDomain ) );
			} );
	}

	/**
	 * Test that getRevisionByTitle doesn't try to use the local wiki DB (T248756)
	 * @covers \MediaWiki\Revision\RevisionStore::getRevisionByTitle
	 */
	public function testGetRevisionByTitle_doesNotUseLocalLoadBalancerForForeignWiki() {
		$page = $this->getTestPage();
		$content = new WikitextContent( __METHOD__ );
		$comment = __METHOD__;
		$status = $page->doUserEditContent(
			$content,
			$this->getTestSysop()->getUser(),
			$comment
		);
		/** @var RevisionRecord $revRecord */
		$revRecord = $status->value['revision-record'];
		$dbDomain = 'some_foreign_wiki';
		$this->executeWithForeignStore(
			$dbDomain,
			function ( RevisionStore $store ) use ( $page, $dbDomain, $revRecord, $content, $comment ) {
				$storeRecord = $store->getRevisionByTitle(
					new PageIdentityValue(
						$page->getId(),
						$page->getTitle()->getNamespace(),
						$page->getTitle()->getDBkey(),
						$dbDomain
					)
				);
				$this->assertSame( $revRecord->getId(), $storeRecord->getId( $dbDomain ) );
				$this->assertTrue( $storeRecord->getSlot( SlotRecord::MAIN )->getContent()->equals( $content ) );
				$this->assertSame( $comment, $storeRecord->getComment()->text );
			} );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::getRevisionByPageId
	 */
	public function testGetRevisionByPageId() {
		$page = $this->getTestPage();
		$content = new WikitextContent( __METHOD__ );
		$status = $page->doUserEditContent(
			$content,
			$this->getTestSysop()->getUser(),
			__METHOD__
		);
		/** @var RevisionRecord $revRecord */
		$revRecord = $status->value['revision-record'];

		$store = $this->getServiceContainer()->getRevisionStore();
		$storeRecord = $store->getRevisionByPageId( $page->getId() );

		$this->assertSame( $revRecord->getId(), $storeRecord->getId() );
		$this->assertTrue( $storeRecord->getSlot( SlotRecord::MAIN )->getContent()->equals( $content ) );
		$this->assertSame( __METHOD__, $storeRecord->getComment()->text );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::getRevisionByTimestamp
	 * @dataProvider provideRevisionByTimestamp
	 *
	 * @param callable $getTitle
	 */
	public function testGetRevisionByTimestamp( $getTitle ) {
		// Make sure there is 1 second between the last revision and the rev we create...
		// Otherwise we might not get the correct revision and the test may fail...
		MWTimestamp::setFakeTime( '20110401090000' );
		$page = $this->getTestPage();
		$title = $getTitle();
		MWTimestamp::setFakeTime( '20110401090001' );
		$content = new WikitextContent( __METHOD__ );
		$status = $page->doUserEditContent(
			$content,
			$this->getTestSysop()->getUser(),
			__METHOD__
		);
		/** @var RevisionRecord $revRecord */
		$revRecord = $status->value['revision-record'];

		$store = $this->getServiceContainer()->getRevisionStore();
		$storeRecord = $store->getRevisionByTimestamp(
			$title,
			$revRecord->getTimestamp()
		);

		$this->assertSame( $revRecord->getId(), $storeRecord->getId() );
		$this->assertTrue( $storeRecord->getSlot( SlotRecord::MAIN )->getContent()->equals( $content ) );
		$this->assertSame( __METHOD__, $storeRecord->getComment()->text );
	}

	public function provideRevisionByTimestamp() {
		return [
			[ function () {
				return $this->getTestPage()->getTitle();
			} ],
			[ function () {
				return $this->getTestPage()->getTitle()->toPageIdentity();
			} ]
		];
	}

	protected function revisionRecordToRow( RevisionRecord $revRecord, $options = [ 'page', 'user', 'comment' ] ) {
		// XXX: the WikiPage object loads another RevisionRecord from the database. Not great.
		$page = WikiPage::factory( $revRecord->getPage() );

		$revUser = $revRecord->getUser();
		$actorId = $this->getServiceContainer()
			->getActorNormalization()->findActorId( $revUser, $this->db );

		$fields = [
			'rev_id' => (string)$revRecord->getId(),
			'rev_page' => (string)$revRecord->getPageId(),
			'rev_timestamp' => $this->db->timestamp( $revRecord->getTimestamp() ),
			'rev_actor' => $actorId,
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
		$page = $this->getTestPage();
		$text = __METHOD__ . 'o-ö';
		/** @var RevisionRecord $revRecord */
		$revRecord = $page->doUserEditContent(
			new WikitextContent( $text ),
			$this->getTestSysop()->getUser(),
			__METHOD__ . 'a'
		)->value['revision-record'];

		$store = $this->getServiceContainer()->getRevisionStore();
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
		$page = $this->getTestPage();
		$text = __METHOD__ . 'a-ä';
		/** @var RevisionRecord $revRecord */
		$revRecord = $page->doUserEditContent(
			new WikitextContent( $text ),
			$this->getTestSysop()->getUser(),
			__METHOD__ . 'a'
		)->value['revision-record'];

		$store = $this->getServiceContainer()->getRevisionStore();
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
		$page = $this->getTestPage();
		$text = __METHOD__ . 'a-ä';
		/** @var RevisionRecord $revRecord */
		$revRecord = $page->doUserEditContent(
			new WikitextContent( $text ),
			$this->getTestSysop()->getUser(),
			__METHOD__ . 'a'
		)->value['revision-record'];

		$store = $this->getServiceContainer()->getRevisionStore();
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
		$this->overrideConfigValue( MainConfigNames::LegacyEncoding, 'windows-1252' );
		$page = $this->getTestPage();
		$text = __METHOD__ . 'a-ä';
		/** @var RevisionRecord $revRecord */
		$revRecord = $page->doUserEditContent(
			new WikitextContent( $text ),
			$this->getTestSysop()->getUser(),
			__METHOD__ . 'a'
		)->value['revision-record'];

		$store = $this->getServiceContainer()->getRevisionStore();
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
		$page = $this->getTestPage();
		$text = __METHOD__ . 'b-ä';
		/** @var RevisionRecord $revRecord */
		$revRecord = $page->doUserEditContent(
			new WikitextContent( $text ),
			$this->getTestUser()->getUser(),
			__METHOD__ . 'b'
		)->value['revision-record'];

		$store = $this->getServiceContainer()->getRevisionStore();
		$storeRecord = $store->newRevisionFromRow(
			$this->revisionRecordToRow( $revRecord ),
			0,
			$page->getTitle()
		);
		$this->assertRevisionRecordsEqual( $revRecord, $storeRecord );
		$this->assertSame( $text, $revRecord->getContent( SlotRecord::MAIN )->serialize() );
	}

	private function buildRevisionStore( string $text, PageIdentity $pageIdentity ) {
		$store = $this->getServiceContainer()->getRevisionStore();
		$page = WikiPage::factory( $pageIdentity );
		/** @var RevisionRecord $orig */
		$orig = $page->doUserEditContent(
			new WikitextContent( $text ),
			$this->getTestSysop()->getUser(),
			__METHOD__
		)->value['revision-record'];
		$this->deletePage( $page );

		$db = wfGetDB( DB_PRIMARY );
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
		return [ $store, $row, $slotRows, $orig ];
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::newRevisionFromArchiveRowAndSlots
	 * @covers \MediaWiki\Revision\RevisionStore::getArchiveQueryInfo
	 */
	public function testNewRevisionFromArchiveRowAndSlots_getArchiveQueryInfo() {
		$text = __METHOD__ . '-bä';
		$title = Title::newFromText( __METHOD__ );
		list( $store, $row, $slotRows, $orig ) = $this->buildRevisionStore( $text, $title );
		$storeRecord = $store->newRevisionFromArchiveRowAndSlots(
			$row,
			iterator_to_array( $slotRows )
		);
		$this->assertRevisionRecordsEqual( $orig, $storeRecord );
		$this->assertSame( $text, $storeRecord->getContent( SlotRecord::MAIN )->serialize() );
	}

	public function provideNewRevisionFromArchiveRowAndSlotsTitles() {
		return [
			[ static function () {
				return Title::newFromText( 'Test_NewRevisionFromArchiveRowAndSlotsTitles' );
			} ],
			[ static function () {
				return Title::newFromText( 'Test_NewRevisionFromArchiveRowAndSlotsTitles' )->toPageIdentity();
			} ]
		];
	}

	/**
	 * @dataProvider provideNewRevisionFromArchiveRowAndSlotsTitles
	 * @covers \MediaWiki\Revision\RevisionStore::newRevisionFromArchiveRowAndSlots
	 */
	public function testNewRevisionFromArchiveRowAndSlots_getArchiveQueryInfoWithTitle( $getPageIdentity ) {
		$text = __METHOD__ . '-bä';
		$page = $getPageIdentity();
		list( $store, $row, $slotRows, $orig ) = $this->buildRevisionStore( $text, $page );
		$storeRecord = $store->newRevisionFromArchiveRowAndSlots(
			$row,
			iterator_to_array( $slotRows ),
			0,
			$page
		);

		$this->assertRevisionRecordsEqual( $orig, $storeRecord );
		$this->assertSame( $text, $storeRecord->getContent( SlotRecord::MAIN )->serialize() );
	}

	public function provideNewRevisionFromArchiveRowAndSlotsInArray() {
		return [
			[
				[
					'title' => Title::newFromText( 'Test_NewRevisionFromArchiveRowAndSlotsInArray' )
				]
			],
			[
				[
					'title' => Title::newFromText( 'Test_NewRevisionFromArchiveRowAndSlotsInArray' )->toPageIdentity()
				]
			],
		];
	}

	/**
	 * @dataProvider provideNewRevisionFromArchiveRowAndSlotsInArray
	 * @covers \MediaWiki\Revision\RevisionStore::newRevisionFromArchiveRowAndSlots
	 */
	public function testNewRevisionFromArchiveRowAndSlots_getArchiveQueryInfoWithTitleInArray( $array ) {
		$text = __METHOD__ . '-bä';
		$page = $array[ 'title' ];
		list( $store, $row, $slotRows, $orig ) = $this->buildRevisionStore( $text, $page );
		$storeRecord = $store->newRevisionFromArchiveRowAndSlots(
			$row,
			iterator_to_array( $slotRows ),
			0,
			null,
			$array
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
		$store = $this->getServiceContainer()->getRevisionStore();
		$title = Title::newFromText( __METHOD__ );
		$text = __METHOD__ . '-bä';
		$page = WikiPage::factory( $title );
		/** @var RevisionRecord $orig */
		$orig = $page->doUserEditContent(
			new WikitextContent( $text ),
			$this->getTestSysop()->getUser(),
			__METHOD__
		)->value['revision-record'];
		$this->deletePage( $page );

		$db = wfGetDB( DB_PRIMARY );
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
		$this->overrideConfigValue( MainConfigNames::LegacyEncoding, 'windows-1252' );
		$store = $this->getServiceContainer()->getRevisionStore();
		$title = Title::newFromText( __METHOD__ );
		$text = __METHOD__ . '-bä';
		$page = WikiPage::factory( $title );
		/** @var RevisionRecord $orig */
		$orig = $page->doUserEditContent(
			new WikitextContent( $text ),
			$this->getTestSysop()->getUser(),
			__METHOD__
		)->value['revision-record'];
		$this->deletePage( $page );

		$db = wfGetDB( DB_PRIMARY );
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
		$store = $this->getServiceContainer()->getRevisionStore();

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

		$record = @$store->newRevisionFromArchiveRow( $row );

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
		$store = $this->getServiceContainer()->getRevisionStore();

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

		$record = @$store->newRevisionFromArchiveRow( $row );

		$this->assertInstanceOf( RevisionRecord::class, $record );
		$this->assertInstanceOf( UserIdentityValue::class, $record->getUser() );
		$this->assertSame( 'Unknown user', $record->getUser()->getName() );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::newRevisionFromRow
	 * @covers \MediaWiki\Revision\RevisionStore::newRevisionFromRowAndSlots
	 */
	public function testNewRevisionFromRow_no_user() {
		$store = $this->getServiceContainer()->getRevisionStore();
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

		$record = @$store->newRevisionFromRow( $row, 0, $title );
		$this->assertNotNull( $record );
		$this->assertNotNull( $record->getUser() );
		$this->assertNotEmpty( $record->getUser()->getName() );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::newRevisionFromRow
	 * @covers \MediaWiki\Revision\RevisionStore::getPage
	 * @covers \MediaWiki\Revision\RevisionStore::wrapPage
	 */
	public function testNewRevisionFromRow_noPage() {
		$store = $this->getServiceContainer()->getRevisionStore();
		$page = $this->getExistingTestPage();

		$info = $store->getQueryInfo();
		$row = $this->db->selectRow(
			$info['tables'],
			$info['fields'],
			[ 'rev_page' => $page->getId(), 'rev_id' => $page->getLatest() ],
			__METHOD__,
			[],
			$info['joins']
		);

		$record = $store->newRevisionFromRow( $row );

		$this->assertNotNull( $record );
		$this->assertTrue( $page->isSamePageAs( $record->getPage() ) );

		// NOTE: This should return a Title object for now, until we no longer have a need
		//       to frequently convert to Title.
		$this->assertInstanceOf( Title::class, $record->getPage() );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::newRevisionFromRow
	 * @covers \MediaWiki\Revision\RevisionStore::getPage
	 * @covers \MediaWiki\Revision\RevisionStore::wrapPage
	 */
	public function testNewRevisionFromRow_noPage_crossWiki() {
		// Make TitleFactory always fail, since it should not be used for the cross-wiki case.
		$noOpTitleFactory = $this->createNoOpMock( TitleFactory::class );
		$this->setService( 'TitleFactory', $noOpTitleFactory );

		// Pretend the local test DB is a sister site
		$wikiId = $this->db->getDomainID();
		$store = $this->getServiceContainer()->getRevisionStoreFactory()
			->getRevisionStore( $wikiId );

		$page = $this->getExistingTestPage();

		$info = $store->getQueryInfo();
		$row = $this->db->selectRow(
			$info['tables'],
			$info['fields'],
			[ 'rev_page' => $page->getId(), 'rev_id' => $page->getLatest() ],
			__METHOD__,
			[],
			$info['joins']
		);

		$record = $store->newRevisionFromRow( $row );

		$this->assertNotNull( $record );
		$this->assertSame( $page->getLatest(), $record->getId( $wikiId ) );

		$this->assertNotInstanceOf( Title::class, $record->getPage() );
		$this->assertSame( $page->getId(), $record->getPage()->getId( $wikiId ) );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::insertRevisionOn
	 * @dataProvider provideInsertRevisionOn
	 *
	 * @param callable $getPageIdentity
	 */
	public function testInsertRevisionOn_archive( $getPageIdentity ) {
		// This is a round trip test for deletion and undeletion of a
		// revision row via the archive table.
		list( $title, $pageIdentity ) = $getPageIdentity();
		$store = $this->getServiceContainer()->getRevisionStore();

		$page = WikiPage::factory( $title );
		$user = $this->getTestSysop()->getUser();
		/** @var RevisionRecord $orig */
		$page->doUserEditContent( new WikitextContent( "First" ), $user, __METHOD__ . '-first' );
		$orig = $page->doUserEditContent( new WikitextContent( "Foo" ), $user, __METHOD__ )
			->value['revision-record'];
		$this->deletePage( $page );

		// re-create page, so we can later load revisions for it
		$page->doUserEditContent( new WikitextContent( 'Two' ), $user, __METHOD__ );

		$db = wfGetDB( DB_PRIMARY );
		$arQuery = $store->getArchiveQueryInfo();
		$row = $db->selectRow(
			$arQuery['tables'], $arQuery['fields'], [ 'ar_rev_id' => $orig->getId() ],
			__METHOD__, [], $arQuery['joins']
		);

		$this->assertNotFalse( $row, 'query failed' );

		$record = $store->newRevisionFromArchiveRow(
			$row,
			0,
			$pageIdentity,
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

	public function provideInsertRevisionOn() {
		return [
			[ static function () {
				$pageTitle = Title::newFromText( 'Test_Insert_Revision_On' );
				return [ $pageTitle, $pageTitle ];
			} ],
			[ static function () {
				$pageTitle = Title::newFromText( 'Test_Insert_Revision_On' );
				return [ $pageTitle, $pageTitle->toPageIdentity() ];
			} ]
		];
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::getRevisionSizes
	 */
	public function testGetParentLengths() {
		$page = WikiPage::factory( Title::newFromText( __METHOD__ ) );
		/** @var RevisionRecord $revRecordOne */
		$revRecordOne = $page->doUserEditContent(
			new WikitextContent( __METHOD__ ),
			$this->getTestSysop()->getUser(),
			__METHOD__
		)->value['revision-record'];
		/** @var RevisionRecord $revRecordTwo */
		$revRecordTwo = $page->doUserEditContent(
			new WikitextContent( __METHOD__ . '2' ),
			$this->getTestSysop()->getUser(),
			__METHOD__
		)->value['revision-record'];

		$store = $this->getServiceContainer()->getRevisionStore();
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
		$revRecordOne = $page->doUserEditContent(
			new WikitextContent( __METHOD__ ),
			$this->getTestSysop()->getUser(),
			__METHOD__
		)->value['revision-record'];
		/** @var RevisionRecord $revRecordTwo */
		$revRecordTwo = $page->doUserEditContent(
			new WikitextContent( __METHOD__ . '2' ),
			$this->getTestSysop()->getUser(),
			__METHOD__
		)->value['revision-record'];

		$store = $this->getServiceContainer()->getRevisionStore();
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
		$revRecordOne = $page->doUserEditContent(
			new WikitextContent( __METHOD__ ),
			$this->getTestSysop()->getUser(),
			__METHOD__
		)->value['revision-record'];
		/** @var RevisionRecord $revRecordTwo */
		$revRecordTwo = $page->doUserEditContent(
			new WikitextContent( __METHOD__ . '2' ),
			$this->getTestSysop()->getUser(),
			__METHOD__
		)->value['revision-record'];

		$store = $this->getServiceContainer()->getRevisionStore();
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

		$user = new UserIdentityValue( 7, 'Frank' );
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
		$store = $this->getServiceContainer()->getRevisionStore();
		$this->assertNull( $store->getPreviousRevision( $rev ) );
	}

	/**
	 * @dataProvider provideNonHistoryRevision
	 * @covers \MediaWiki\Revision\RevisionStore::getNextRevision
	 */
	public function testGetNextRevision_bad( RevisionRecord $rev ) {
		$store = $this->getServiceContainer()->getRevisionStore();
		$this->assertNull( $store->getNextRevision( $rev ) );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::getTimestampFromId
	 */
	public function testGetTimestampFromId_found() {
		$page = $this->getTestPage();
		/** @var RevisionRecord $revRecord */
		$revRecord = $page->doUserEditContent(
			new WikitextContent( __METHOD__ ),
			$this->getTestSysop()->getUser(),
			__METHOD__
		)->value['revision-record'];

		$store = $this->getServiceContainer()->getRevisionStore();
		$result = $store->getTimestampFromId( $revRecord->getId() );

		$this->assertSame( $revRecord->getTimestamp(), $result );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::getTimestampFromId
	 */
	public function testGetTimestampFromId_notFound() {
		$page = $this->getTestPage();
		/** @var RevisionRecord $revRecord */
		$revRecord = $page->doUserEditContent(
			new WikitextContent( __METHOD__ ),
			$this->getTestSysop()->getUser(),
			__METHOD__
		)->value['revision-record'];

		$store = $this->getServiceContainer()->getRevisionStore();
		$result = $store->getTimestampFromId( $revRecord->getId() + 1 );

		$this->assertFalse( $result );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::countRevisionsByPageId
	 */
	public function testCountRevisionsByPageId() {
		$store = $this->getServiceContainer()->getRevisionStore();
		$page = WikiPage::factory( Title::newFromText( __METHOD__ ) );
		$user = $this->getTestSysop()->getUser();

		$this->assertSame(
			0,
			$store->countRevisionsByPageId( wfGetDB( DB_PRIMARY ), $page->getId() )
		);
		$page->doUserEditContent( new WikitextContent( 'a' ), $user, 'a' );
		$this->assertSame(
			1,
			$store->countRevisionsByPageId( wfGetDB( DB_PRIMARY ), $page->getId() )
		);
		$page->doUserEditContent( new WikitextContent( 'b' ), $user, 'b' );
		$this->assertSame(
			2,
			$store->countRevisionsByPageId( wfGetDB( DB_PRIMARY ), $page->getId() )
		);
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::countRevisionsByTitle
	 */
	public function testCountRevisionsByTitle() {
		$store = $this->getServiceContainer()->getRevisionStore();
		$page = WikiPage::factory( Title::newFromText( __METHOD__ ) );
		$user = $this->getTestSysop()->getUser();

		$this->assertSame(
			0,
			$store->countRevisionsByTitle( wfGetDB( DB_PRIMARY ), $page->getTitle() )
		);
		$page->doUserEditContent( new WikitextContent( 'a' ), $user, 'a' );
		$this->assertSame(
			1,
			$store->countRevisionsByTitle( wfGetDB( DB_PRIMARY ), $page->getTitle() )
		);
		$page->doUserEditContent( new WikitextContent( 'b' ), $user, 'b' );
		$this->assertSame(
			2,
			$store->countRevisionsByTitle( wfGetDB( DB_PRIMARY ), $page->getTitle() )
		);
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::userWasLastToEdit
	 */
	public function testUserWasLastToEdit_false() {
		$sysop = $this->getTestSysop()->getUser();
		$page = $this->getTestPage();
		$page->doUserEditContent(
			new WikitextContent( __METHOD__ ),
			$this->getTestUser()->getUser(), // not the $sysop
			__METHOD__
		);

		$store = $this->getServiceContainer()->getRevisionStore();
		$result = $store->userWasLastToEdit(
			wfGetDB( DB_PRIMARY ),
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
		$page->doUserEditContent(
			new WikitextContent( __METHOD__ ),
			$sysop,
			__METHOD__
		);

		$store = $this->getServiceContainer()->getRevisionStore();
		$result = $store->userWasLastToEdit(
			wfGetDB( DB_PRIMARY ),
			$page->getId(),
			$sysop->getId(),
			$startTime
		);
		$this->assertTrue( $result );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::getKnownCurrentRevision
	 * @dataProvider provideGetKnownCurrentRevision
	 */
	public function testGetKnownCurrentRevision( $getPageIdentity ) {
		$page = $this->getTestPage();
		/** @var RevisionRecord $revRecord */
		$revRecord = $page->doUserEditContent(
			new WikitextContent( __METHOD__ . 'b' ),
			$this->getTestUser()->getUser(),
			__METHOD__ . 'b'
		)->value['revision-record'];
		$store = $this->getServiceContainer()->getRevisionStore();
		$storeRecord = $store->getKnownCurrentRevision(
			$getPageIdentity(),
			$revRecord->getId()
		);
		$this->assertRevisionRecordsEqual( $revRecord, $storeRecord );
	}

	public function provideGetKnownCurrentRevision() {
		return [
			[ function () {
				return $this->getTestPage()->getTitle();
			} ],
			[ function () {
				return $this->getTestPage()->getTitle()->toPageIdentity();
			} ]
		];
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
		$summary = CommentStoreComment::newUnsavedComment( __METHOD__ );
		$rev = $page->newPageUpdater( $user )
			->setContent( SlotRecord::MAIN, new WikitextContent( __METHOD__ ) )
			->saveRevision( $summary, EDIT_NEW );
		return $store->getKnownCurrentRevision( $page->getTitle(), $rev->getId() );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::getKnownCurrentRevision
	 */
	public function testGetKnownCurrentRevision_userNameChange() {
		$cache = new WANObjectCache( [ 'cache' => new HashBagOStuff() ] );
		$this->setService( 'MainWANObjectCache', $cache );

		$store = $this->getServiceContainer()->getRevisionStore();
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

		$store = $this->getServiceContainer()->getRevisionStore();
		$page = $this->getNonexistingTestPage();
		$rev = $this->createRevisionStoreCacheRecord( $page, $store );

		// Force bad article ID
		$title = $page->getTitle();
		$title->resetArticleID( 886655 );

		$result = $store->getKnownCurrentRevision( $title, $rev->getId() );

		$this->assertSame( $rev->getPageId(), $result->getPageId() );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::getKnownCurrentRevision
	 */
	public function testGetKnownCurrentRevision_wrongTitle() {
		$cache = new WANObjectCache( [ 'cache' => new HashBagOStuff() ] );
		$this->setService( 'MainWANObjectCache', $cache );

		$store = $this->getServiceContainer()->getRevisionStore();
		$page = $this->getNonexistingTestPage();
		$rev = $this->createRevisionStoreCacheRecord( $page, $store );

		// Get title of another page
		$title = $this->getExistingTestPage( __FUNCTION__ )->getTitle();
		$result = $store->getKnownCurrentRevision( $title, $rev->getId() );

		$this->assertSame( $rev->getPageId(), $result->getPageId() );
		$this->assertTrue( $rev->getPage()->isSamePageAs( $result->getPage() ) );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::getKnownCurrentRevision
	 */
	public function testGetKnownCurrentRevision_revDelete() {
		$cache = new WANObjectCache( [ 'cache' => new HashBagOStuff() ] );
		$this->setService( 'MainWANObjectCache', $cache );

		$store = $this->getServiceContainer()->getRevisionStore();
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
		$revRecord = $page->doUserEditContent(
			new WikitextContent( $text ),
			$this->getMutableTestUser()->getUser(),
			__METHOD__
		)->value['revision-record'];

		$store = $this->getServiceContainer()->getRevisionStore();
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
		$revRecord = $page->doUserEditContent(
			new WikitextContent( $text ),
			$this->getTestSysop()->getUser(),
			__METHOD__
		)->value['revision-record'];

		$store = $this->getServiceContainer()->getRevisionStore();
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
	 * @covers \MediaWiki\Revision\RevisionStore::getContentBlobsForBatch
	 */
	public function testGetContentBlobsForBatch( $slots ) {
		$page1 = $this->getTestPage();
		$text = __METHOD__ . 'b-ä';
		$editStatus = $this->editPage( $page1->getTitle()->getPrefixedDBkey(), $text . '1' );
		$this->assertStatusGood( $editStatus, 'must create revision 1' );
		/** @var RevisionRecord $revRecord1 */
		$revRecord1 = $editStatus->getValue()['revision-record'];

		$page2 = $this->getTestPage( $page1->getTitle()->getPrefixedText() . '_other' );
		$editStatus = $this->editPage( $page2->getTitle()->getPrefixedDBkey(), $text . '2' );
		$this->assertStatusGood( $editStatus, 'must create revision 2' );
		/** @var RevisionRecord $revRecord2 */
		$revRecord2 = $editStatus->getValue()['revision-record'];

		$store = $this->getServiceContainer()->getRevisionStore();
		$result = $store->getContentBlobsForBatch(
			[ $revRecord1->getId(), $revRecord2->getId() ],
			$slots
		);
		$this->assertStatusGood( $result );

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

			$this->assertSame( $text . '1', $mainSlotRow1->blob_data );
			$this->assertSame( $text . '2', $mainSlotRow2->blob_data );
		}

		// try again, with objects instead of ids:
		$result2 = $store->getContentBlobsForBatch( [
			(object)[ 'rev_id' => $revRecord1->getId() ],
			(object)[ 'rev_id' => $revRecord2->getId() ],
		], $slots );

		$this->assertStatusGood( $result2 );
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
		$this->assertStatusGood( $editStatus, 'must create revision 1' );
		/** @var RevisionRecord $revRecord1 */
		$revRecord1 = $editStatus->getValue()['revision-record'];
		$this->deletePage( $page1 );

		$page2 = $this->getTestPage( $page1->getTitle()->getPrefixedText() . '_other' );
		$editStatus = $this->editPage( $page2->getTitle()->getPrefixedDBkey(), $text . '2' );
		$this->assertStatusGood( $editStatus, 'must create revision 2' );
		/** @var RevisionRecord $revRecord2 */
		$revRecord2 = $editStatus->getValue()['revision-record'];
		$this->deletePage( $page2 );

		$store = $this->getServiceContainer()->getRevisionStore();
		$result = $store->getContentBlobsForBatch( [
			(object)[ 'ar_rev_id' => $revRecord1->getId() ],
			(object)[ 'ar_rev_id' => $revRecord2->getId() ],
		] );
		$this->assertStatusGood( $result );

		$rowSetsByRevId = $result->getValue();
		$this->assertArrayHasKey( $revRecord1->getId(), $rowSetsByRevId );
		$this->assertArrayHasKey( $revRecord2->getId(), $rowSetsByRevId );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::newRevisionsFromBatch
	 */
	public function testGetContentBlobsForBatch_emptyBatch() {
		$rows = new FakeResultWrapper( [] );
		$result = $this->getServiceContainer()->getRevisionStore()
			->getContentBlobsForBatch( $rows );
		$this->assertStatusGood( $result );
		$this->assertStatusValue( [], $result );
	}

	public function provideNewRevisionsFromBatchOptions() {
		yield 'No preload slots or content, single page' => [
			[ 'comment' ],
			static function () {
				$pageTitle = Title::newFromText( 'Test_New_Revision_From_Batch' );
				return [ $pageTitle, $pageTitle ];
			},
			null,
			[]
		];
		yield 'No preload slots or content, single page and with PageIdentity' => [
			[ 'comment' ],
			static function () {
				$pageTitle = Title::newFromText( 'Test_New_Revision_From_Batch' );
				return [ $pageTitle, $pageTitle->toPageIdentity() ];
			},
			null,
			[]
		];
		yield 'Preload slots and content, single page' => [
			[ 'comment' ],
			static function () {
				$pageTitle = Title::newFromText( 'Test_New_Revision_From_Batch' );
				return [ $pageTitle, $pageTitle ];
			},
			null,
			[
				'slots' => [ SlotRecord::MAIN ],
				'content' => true
			]
		];
		yield 'Ask for no slots' => [
			[ 'comment' ],
			static function () {
				$pageTitle = Title::newFromText( 'Test_New_Revision_From_Batch' );
				return [ $pageTitle, $pageTitle ];
			},
			null,
			[ 'slots' => [] ]
		];
		yield 'No preload slots or content, multiple pages' => [
			[ 'comment' ],
			static function () {
				$pageTitle = Title::newFromText( 'Test_New_Revision_From_Batch' );
				return [ $pageTitle, $pageTitle ];
			},
			'Other_Page',
			[]
		];
		yield 'Preload slots and content, multiple pages' => [
			[ 'comment' ],
			static function () {
				$pageTitle = Title::newFromText( 'Test_New_Revision_From_Batch' );
				return [ $pageTitle, $pageTitle ];
			},
			'Other_Page',
			[
				'slots' => [ SlotRecord::MAIN ],
				'content' => true
			]
		];
		yield 'Preload slots and content, multiple pages, preload page fields' => [
			[ 'page', 'comment' ],
			static function () {
				$pageTitle = Title::newFromText( 'Test_New_Revision_From_Batch' );
				return [ $pageTitle, $pageTitle ];
			},
			'Other_Page',
			[
				'slots' => [ SlotRecord::MAIN ],
				'content' => true
			]
		];
	}

	/**
	 * @dataProvider provideNewRevisionsFromBatchOptions
	 * @covers \MediaWiki\Revision\RevisionStore::newRevisionsFromBatch
	 * @param array|null $queryOptions options to provide to revisionRecordToRow
	 * @param callable $getPageIdentity
	 * @param string|null $otherPageTitle
	 * @param array|null $options
	 */
	public function testNewRevisionsFromBatch_preloadContent(
		$queryOptions,
		$getPageIdentity,
		$otherPageTitle = null,
		array $options = []
	) {
		$page1 = $this->getTestPage();
		$text = __METHOD__ . 'b-ä';
		$editStatus = $this->editPage( $page1->getTitle()->getPrefixedDBkey(), $text . '1' );
		$this->assertStatusGood( $editStatus, 'must create revision 1' );
		/** @var RevisionRecord $revRecord1 */
		$revRecord1 = $editStatus->getValue()['revision-record'];

		$page2 = $this->getTestPage( $otherPageTitle );
		$editStatus = $this->editPage( $page2->getTitle()->getPrefixedDBkey(), $text . '2' );
		$this->assertStatusGood( $editStatus, 'must create revision 2' );
		/** @var RevisionRecord $revRecord2 */
		$revRecord2 = $editStatus->getValue()['revision-record'];

		$store = $this->getServiceContainer()->getRevisionStore();
		$result = $store->newRevisionsFromBatch(
			[
				$this->revisionRecordToRow( $revRecord1, $queryOptions ),
				$this->revisionRecordToRow( $revRecord2, $queryOptions )
			],
			$options,
			0, $otherPageTitle ? null : $page1->getTitle()
		);
		$this->assertStatusGood( $result );
		/** @var RevisionRecord[] $records */
		$records = $result->getValue();
		$this->assertRevisionRecordsEqual( $revRecord1, $records[$revRecord1->getId()] );
		$this->assertRevisionRecordsEqual( $revRecord2, $records[$revRecord2->getId()] );

		$content1 = $records[$revRecord1->getId()]->getContent( SlotRecord::MAIN );
		$this->assertInstanceOf( TextContent::class, $content1 );
		$this->assertSame(
			$text . '1',
			$content1->getText()
		);
		$content2 = $records[$revRecord2->getId()]->getContent( SlotRecord::MAIN );
		$this->assertInstanceOf( TextContent::class, $content2 );
		$this->assertSame(
			$text . '2',
			$content2->getText()
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
	 * @covers \MediaWiki\Revision\RevisionStore::newRevisionsFromBatch
	 * @param array|null $queryOptions options to provide to revisionRecordToRow
	 * @param callable $getPageIdentity
	 * @param string|null $otherPageTitle
	 * @param array|null $options
	 */
	public function testNewRevisionsFromBatch_archive(
		$queryOptions,
		$getPageIdentity,
		$otherPageTitle = null,
		array $options = []
	) {
		list( $title1, $pageIdentity ) = $getPageIdentity();
		$text1 = __METHOD__ . '-bä';
		$page1 = WikiPage::factory( $title1 );

		$title2 = $otherPageTitle ? Title::newFromText( $otherPageTitle ) : $title1;
		$text2 = __METHOD__ . '-bö';
		$page2 = $otherPageTitle ? WikiPage::factory( $title2 ) : $page1;

		$sysop = $this->getTestSysop()->getUser();
		/** @var RevisionRecord $revRecord1 */
		/** @var RevisionRecord $revRecord2 */
		$revRecord1 = $page1->doUserEditContent(
			new WikitextContent( $text1 ),
			$sysop,
			__METHOD__
		)->value['revision-record'];
		$revRecord2 = $page2->doUserEditContent(
			new WikitextContent( $text2 ),
			$sysop,
			__METHOD__
		)->value['revision-record'];
		$this->deletePage( $page1 );

		if ( $page2 !== $page1 ) {
			$this->deletePage( $page2 );
		}

		$store = $this->getServiceContainer()->getRevisionStore();

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
			$rows, $options, 0, $otherPageTitle ? null : $pageIdentity );

		$this->assertStatusGood( $result );
		/** @var RevisionRecord[] $records */
		$records = $result->getValue();
		$this->assertCount( 2, $records );
		$this->assertRevisionRecordsEqual( $revRecord1, $records[$revRecord1->getId()] );
		$this->assertRevisionRecordsEqual( $revRecord2, $records[$revRecord2->getId()] );

		$content1 = $records[$revRecord1->getId()]->getContent( SlotRecord::MAIN );
		$this->assertInstanceOf( TextContent::class, $content1 );
		$this->assertSame(
			$text1,
			$content1->getText()
		);
		$content2 = $records[$revRecord2->getId()]->getContent( SlotRecord::MAIN );
		$this->assertInstanceOf( TextContent::class, $content2 );
		$this->assertSame(
			$text2,
			$content2->getText()
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
		$result = $this->getServiceContainer()->getRevisionStore()
			->newRevisionsFromBatch(
				$rows,
				[
					'slots' => [ SlotRecord::MAIN ],
					'content' => true
				]
			);
		$this->assertStatusGood( $result );
		$this->assertStatusValue( [], $result );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::newRevisionsFromBatch
	 */
	public function testNewRevisionsFromBatch_wrongTitle() {
		$page1 = $this->getTestPage();
		$text = __METHOD__ . 'b-ä';
		$editStatus = $this->editPage( $page1->getTitle()->getPrefixedDBkey(), $text . '1' );
		$this->assertStatusGood( $editStatus, 'must create revision 1' );
		/** @var RevisionRecord $rev1 */
		$revRecord1 = $editStatus->getValue()['revision-record'];

		$this->expectException( InvalidArgumentException::class );
		$this->getServiceContainer()->getRevisionStore()
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
		$this->assertStatusGood( $editStatus, 'must create revision 1' );
		/** @var RevisionRecord $revRecord1 */
		$revRecord1 = $editStatus->getValue()['revision-record'];

		$status = $this->getServiceContainer()->getRevisionStore()
			->newRevisionsFromBatch(
				[
					$this->revisionRecordToRow( $revRecord1 ),
					$this->revisionRecordToRow( $revRecord1 )
				]
			);

		$this->assertStatusWarning( 'internalerror_info', $status );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::getRevisionIdsBetween
	 */
	public function testGetRevisionIdsBetween() {
		$NUM = 5;
		$MAX = 1;
		$page = $this->getTestPage( __METHOD__ );
		$revisions = [];
		$revisionIds = [];
		for ( $revNum = 0; $revNum < $NUM; $revNum++ ) {
			$editStatus = $this->editPage( $page->getTitle()->getPrefixedDBkey(), 'Revision ' . $revNum );
			$this->assertStatusGood( $editStatus, 'must create revision ' . $revNum );
			$newRevision = $editStatus->getValue()['revision-record'];
			/** @var RevisionRecord $newRevision */
			$revisions[] = $newRevision;
			$revisionIds[] = $newRevision->getId();
		}

		$revisionStore = $this->getServiceContainer()->getRevisionStore();
		$this->assertArrayEquals(
			[],
			$revisionStore->getRevisionIdsBetween( $page->getId(), $revisions[0], $revisions[0] ),
			false,
			false,
			'Must return an empty array if the same old and new revisions provided'
		);
		$this->assertArrayEquals(
			[],
			$revisionStore->getRevisionIdsBetween( $page->getId(), $revisions[0], $revisions[1] ),
			false,
			false,
			'Must return an empty array if the consecutive old and new revisions provided'
		);
		$this->assertArrayEquals(
			array_slice( $revisionIds, 1, -2 ),
			$revisionStore->getRevisionIdsBetween( $page->getId(), $revisions[0], $revisions[$NUM - 2] ),
			false,
			false,
			'The result is non-inclusive on both ends if both beginning and end are provided'
		);
		$this->assertArrayEquals(
			array_slice( $revisionIds, 1, -1 ),
			$revisionStore->getRevisionIdsBetween(
				$page->getId(),
				$revisions[0],
				$revisions[$NUM - 2],
				null,
				RevisionStore::INCLUDE_NEW
			),
			'The inclusion string options are respected'
		);
		$this->assertArrayEquals(
			array_slice( $revisionIds, 0, -1 ),
			$revisionStore->getRevisionIdsBetween(
				$page->getId(),
				$revisions[0],
				$revisions[$NUM - 2],
				null,
				[ RevisionStore::INCLUDE_BOTH ]
			),
			false,
			false,
			'The inclusion array options are respected'
		);
		$this->assertArrayEquals(
			array_slice( $revisionIds, 1 ),
			$revisionStore->getRevisionIdsBetween( $page->getId(), $revisions[0] ),
			false,
			false,
			'The result is inclusive on the end if the end is omitted'
		);

		$this->assertArrayEquals(
			array_reverse( array_slice( $revisionIds, 1, -2 ) ),
			$revisionStore->getRevisionIdsBetween(
				$page->getId(),
				$revisions[0],
				$revisions[$NUM - 2],
				null,
				[],
				RevisionStore::ORDER_NEWEST_TO_OLDEST
			),
			true,
			false,
			'$order parameter is respected'
		);
		$this->assertSame(
			$MAX + 1, // Returns array of length $max + 1 to detect truncation.
			count( $revisionStore->getRevisionIdsBetween(
				$page->getId(),
				$revisions[0],
				$revisions[$NUM - 1],
				$MAX
			) ),
			'$max is incremented to detect truncation'
		);
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
			$this->assertStatusGood( $editStatus, 'must create revision ' . $revNum );
			$revisions[] = $editStatus->getValue()['revision-record'];
		}

		$revisionStore = $this->getServiceContainer()->getRevisionStore();
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
				null, RevisionStore::INCLUDE_NEW ),
			'The count string options are respected' );
		$this->assertEquals( $NUM - 1,
			$revisionStore->countRevisionsBetween( $page->getId(), $revisions[0], $revisions[$NUM - 2],
				null, [ RevisionStore::INCLUDE_BOTH ] ),
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
			$this->assertStatusGood( $editStatus, 'must create revision ' . $revNum );
			$revisions[] = $editStatus->getValue()['revision-record'];
		}

		$revisionStore = $this->getServiceContainer()->getRevisionStore();
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
		yield [ 'getRevisionIdsBetween' ];
		yield [ 'countRevisionsBetween' ];
		yield [ 'countAuthorsBetween' ];
		yield [ 'getAuthorsBetween' ];
	}

	/**
	 * @dataProvider provideBetweenMethodNames
	 *
	 * @covers \MediaWiki\Revision\RevisionStore::getRevisionIdsBetween
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
		$this->assertStatusGood( $editStatus, 'must create revision 1' );
		$rev1 = $editStatus->getValue()['revision-record'];
		$editStatus = $this->editPage( $page2->getTitle()->getPrefixedDBkey(), 'Revision 1' );
		$this->assertStatusGood( $editStatus, 'must create revision 1' );
		$rev2 = $editStatus->getValue()['revision-record'];

		$this->expectException( InvalidArgumentException::class );
		$this->getServiceContainer()->getRevisionStore()
			->{$method}( $page1->getId(), $rev1, $rev2 );
	}

	/**
	 * @dataProvider provideBetweenMethodNames
	 *
	 * @covers \MediaWiki\Revision\RevisionStore::getRevisionIdsBetween
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
		$this->getServiceContainer()->getRevisionStore()->{$method}(
			$this->getTestPage()->getId(), $rev1, $rev2 );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::getFirstRevision
	 *
	 * @dataProvider provideGetFirstRevision
	 * @param callable $getPageIdentity
	 */
	public function testGetFirstRevision( $getPageIdentity ) {
		list( $pageTitle, $pageIdentity ) = $getPageIdentity();
		$editStatus = $this->editPage( $pageTitle->getPrefixedDBkey(), 'First Revision' );
		$this->assertStatusGood( $editStatus, 'must create first revision' );
		$firstRevId = $editStatus->getValue()['revision-record']->getID();
		$editStatus = $this->editPage( $pageTitle->getPrefixedText(), 'New Revision' );
		$this->assertStatusGood( $editStatus, 'must create new revision' );
		$this->assertNotSame(
			$firstRevId,
			$editStatus->getValue()['revision-record']->getID(),
			'new revision must have different id'
		);
		$this->assertSame(
			$firstRevId,
			$this->getServiceContainer()
				->getRevisionStore()
				->getFirstRevision( $pageIdentity )
				->getId()
		);
	}

	public function provideGetFirstRevision() {
		return [
			[ static function () {
				$pageTitle = Title::newFromText( 'Test_Get_First_Revision' );
				return [ $pageTitle, $pageTitle ];
			} ],
			[ static function () {
				$pageTitle = Title::newFromText( 'Test_Get_First_Revision' );
				return [ $pageTitle, $pageTitle->toPageIdentity() ];
			} ]
		];
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::getFirstRevision
	 */
	public function testGetFirstRevision_nonexistent_page() {
		$this->assertNull(
			$this->getServiceContainer()
				->getRevisionStore()
				->getFirstRevision( $this->getNonexistingTestPage( __METHOD__ )->getTitle() )
		);
	}

	public function provideInsertRevisionByAnonAssignsNewActor() {
		yield 'User' => [ '127.1.1.0', static function ( MediaWikiServices $services, string $ip ) {
			return $services->getUserFactory()->newAnonymous( $ip );
		} ];
		yield 'User identity, anon' => [ '127.1.1.1', static function ( MediaWikiServices $services, string $ip ) {
			return new UserIdentityValue( 0, $ip );
		} ];
	}

	/**
	 * @dataProvider provideInsertRevisionByAnonAssignsNewActor
	 * @covers \MediaWiki\Revision\RevisionStore::insertRevisionOn
	 */
	public function testInsertRevisionByAnonAssignsNewActor( string $ip, callable $userInitCallback ) {
		$user = $userInitCallback( $this->getServiceContainer(), $ip );

		$actorNormalization = $this->getServiceContainer()->getActorNormalization();
		$actorId = $actorNormalization->findActorId( $user, $this->db );
		$this->assertNull( $actorId, 'New actor has no actor_id' );

		$page = $this->getTestPage();
		$rev = new MutableRevisionRecord( $page->getTitle() );
		$rev->setTimestamp( '20180101000000' )
			->setComment( CommentStoreComment::newUnsavedComment( 'test' ) )
			->setUser( $user )
			->setContent( 'main', new WikitextContent( 'Text' ) )
			->setPageId( $page->getId() );

		$return = $this->getServiceContainer()->getRevisionStore()->insertRevisionOn( $rev, $this->db );
		$this->assertSame( $ip, $return->getUser()->getName() );

		$actorId = $actorNormalization->findActorId( $user, $this->db );
		$this->assertNotNull( $actorId );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::getTitle
	 */
	public function testGetTitle_successFromPageId() {
		$page = $this->getExistingTestPage();

		$store = $this->getServiceContainer()->getRevisionStore();
		$title = $store->getTitle( $page->getId(), 0, RevisionStore::READ_NORMAL );

		$this->assertTrue( $page->isSamePageAs( $title ) );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::getTitle
	 */
	public function testGetTitle_successFromRevId() {
		$page = $this->getExistingTestPage();

		$store = $this->getServiceContainer()->getRevisionStore();
		$title = $store->getTitle( 0, $page->getLatest(), RevisionStore::READ_NORMAL );

		$this->assertTrue( $page->isSamePageAs( $title ) );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::getTitle
	 */
	public function testGetTitle_failure() {
		$store = $this->getServiceContainer()->getRevisionStore();

		$this->expectException( RevisionAccessException::class );
		$store->getTitle( 113349857, 897234779, RevisionStore::READ_NORMAL );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::getQueryInfo
	 */
	public function testGetQueryInfo_NoSlotDataJoin() {
		$store = $this->getServiceContainer()->getRevisionStore();
		$queryInfo = $store->getQueryInfo();

		// with the new schema enabled, query info should not join the main slot info
		$this->assertArrayNotHasKey( 'a_slot_data', $queryInfo['tables'] );
		$this->assertArrayNotHasKey( 'a_slot_data', $queryInfo['joins'] );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::insertRevisionOn
	 * @covers \MediaWiki\Revision\RevisionStore::insertSlotRowOn
	 * @covers \MediaWiki\Revision\RevisionStore::insertContentRowOn
	 */
	public function testInsertRevisionOn_T202032() {
		// This test only makes sense for MySQL
		if ( $this->db->getType() !== 'mysql' ) {
			$this->assertTrue( true );
			return;
		}

		// NOTE: must be done before checking MAX(rev_id)
		$page = $this->getTestPage();

		$maxRevId = $this->db->selectField( 'revision', 'MAX(rev_id)' );

		// Construct a slot row that will conflict with the insertion of the next revision ID,
		// to emulate the failure mode described in T202032. Nothing will ever read this row,
		// we just need it to trigger a primary key conflict.
		$this->db->insert( 'slots', [
			'slot_revision_id' => $maxRevId + 1,
			'slot_role_id' => 1,
			'slot_content_id' => 0,
			'slot_origin' => 0
		], __METHOD__ );

		$rev = new MutableRevisionRecord( $page->getTitle() );
		$rev->setTimestamp( '20180101000000' )
			->setComment( CommentStoreComment::newUnsavedComment( 'test' ) )
			->setUser( $this->getTestUser()->getUser() )
			->setContent( 'main', new WikitextContent( 'Text' ) )
			->setPageId( $page->getId() );

		$store = $this->getServiceContainer()->getRevisionStore();
		$return = $store->insertRevisionOn( $rev, $this->db );

		$this->assertSame( $maxRevId + 2, $return->getId() );

		// is the new revision correct?
		$this->assertRevisionCompleteness( $return );
		$this->assertRevisionRecordsEqual( $rev, $return );

		// can we find it directly in the database?
		$this->assertRevisionExistsInDatabase( $return );

		// can we load it from the store?
		$loaded = $store->getRevisionById( $return->getId() );
		$this->assertRevisionCompleteness( $loaded );
		$this->assertRevisionRecordsEqual( $return, $loaded );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::getContentBlobsForBatch
	 * @throws \MWException
	 */
	public function testGetContentBlobsForBatch_error() {
		$page1 = $this->getTestPage();
		$text = __METHOD__ . 'b-ä';
		$editStatus = $this->editPage( $page1->getTitle()->getPrefixedDBkey(), $text . '1' );
		$this->assertStatusGood( $editStatus, 'must create revision 1' );
		/** @var RevisionRecord $revRecord1 */
		$revRecord1 = $editStatus->getValue()['revision-record'];

		$contentAddress = $revRecord1->getSlot( SlotRecord::MAIN )->getAddress();
		$blobStatus = StatusValue::newGood( [] );
		$blobStatus->warning( 'internalerror_info', 'oops!' );

		$mockBlobStore = $this->createMock( BlobStore::class );
		$mockBlobStore->method( 'getBlobBatch' )
			->willReturn( $blobStatus );

		$revStore = $this->getServiceContainer()
			->getRevisionStoreFactory()
			->getRevisionStore();
		$wrappedRevStore = TestingAccessWrapper::newFromObject( $revStore );
		$wrappedRevStore->blobStore = $mockBlobStore;

		$result = $revStore->getContentBlobsForBatch( [ $revRecord1->getId() ] );
		$this->assertStatusOK( $result );
		$this->assertStatusNotGood( $result );
		$this->assertNotEmpty( $result->getErrors() );

		$records = $result->getValue();
		$this->assertArrayHasKey( $revRecord1->getId(), $records );

		$mainRow = $records[$revRecord1->getId()][SlotRecord::MAIN];
		$this->assertNull( $mainRow->blob_data );
		$this->assertSame( [
			[
				'type' => 'warning',
				'message' => 'internalerror_info',
				'params' => [
					"oops!"
				]
			],
			[
				'type' => 'warning',
				'message' => 'internalerror_info',
				'params' => [
					"Couldn't find blob data for rev " . $revRecord1->getId()
				]
			]
		], $result->getErrors() );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::getContentBlobsForBatch
	 */
	public function testGetContentBlobsForBatchUsesGetBlobBatch() {
		$page1 = $this->getTestPage();
		$text = __METHOD__ . 'b-ä';
		$editStatus = $this->editPage( $page1->getTitle()->getPrefixedDBkey(), $text . '1' );
		$this->assertStatusGood( $editStatus, 'must create revision 1' );
		/** @var RevisionRecord $revRecord1 */
		$revRecord1 = $editStatus->getValue()['revision-record'];

		$contentAddress = $revRecord1->getSlot( SlotRecord::MAIN )->getAddress();
		$mockBlobStore = $this->createMock( SqlBlobStore::class );
		$mockBlobStore
			->expects( $this->once() )
			->method( 'getBlobBatch' )
			->with( [ $contentAddress ], $this->anything() )
			->willReturn( StatusValue::newGood( [
				$contentAddress => 'Content_From_Mock'
			] ) );
		$mockBlobStore
			->expects( $this->never() )
			->method( 'getBlob' );

		$revStore = $this->getServiceContainer()
			->getRevisionStoreFactory()
			->getRevisionStore();
		$wrappedRevStore = TestingAccessWrapper::newFromObject( $revStore );
		$wrappedRevStore->blobStore = $mockBlobStore;

		$result = $revStore->getContentBlobsForBatch(
			[ $revRecord1->getId() ],
			[ SlotRecord::MAIN ]
		);
		$this->assertStatusGood( $result );
		$this->assertSame( 'Content_From_Mock',
			$result->getValue()[$revRecord1->getId()][SlotRecord::MAIN]->blob_data );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::newRevisionsFromBatch
	 * @throws \MWException
	 */
	public function testNewRevisionsFromBatch_error() {
		$page = $this->getTestPage();
		$text = __METHOD__ . 'b-ä';
		/** @var RevisionRecord $revRecord1 */
		$revRecord1 = $page->doUserEditContent(
			new WikitextContent( $text . '1' ),
			$this->getTestUser()->getUser(),
			__METHOD__ . 'b'
		)->value['revision-record'];

		$invalidRow = $this->revisionRecordToRow( $revRecord1 );
		$invalidRow->rev_id = 100500;
		$result = $this->getServiceContainer()->getRevisionStore()
			->newRevisionsFromBatch(
				[ $this->revisionRecordToRow( $revRecord1 ), $invalidRow ],
				[
					'slots' => [ SlotRecord::MAIN ],
					'content' => true
				]
			);
		$this->assertStatusNotGood( $result );
		$this->assertNotEmpty( $result->getErrors() );
		$records = $result->getValue();
		$this->assertRevisionRecordsEqual( $revRecord1, $records[$revRecord1->getId()] );
		$this->assertSame( $text . '1',
			$records[$revRecord1->getId()]->getContent( SlotRecord::MAIN )->serialize() );
		$this->assertEquals( $page->getTitle()->getDBkey(),
			$records[$revRecord1->getId()]->getPageAsLinkTarget()->getDBkey() );
		$this->assertNull( $records[$invalidRow->rev_id] );
		$this->assertSame( [ [
			'type' => 'warning',
			'message' => 'internalerror_info',
			'params' => [
				"Couldn't find slots for rev 100500"
			]
		] ], $result->getErrors() );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::newRevisionsFromBatch
	 */
	public function testNewRevisionFromBatchUsesGetBlobBatch() {
		$page1 = $this->getTestPage();
		$text = __METHOD__ . 'b-ä';
		$editStatus = $this->editPage( $page1->getTitle()->getPrefixedDBkey(), $text . '1' );
		$this->assertStatusGood( $editStatus, 'must create revision 1' );
		/** @var RevisionRecord $revRecord1 */
		$revRecord1 = $editStatus->getValue()['revision-record'];

		$contentAddress = $revRecord1->getSlot( SlotRecord::MAIN )->getAddress();
		$mockBlobStore = $this->createMock( SqlBlobStore::class );
		$mockBlobStore
			->expects( $this->once() )
			->method( 'getBlobBatch' )
			->with( [ $contentAddress ], $this->anything() )
			->willReturn( StatusValue::newGood( [
				$contentAddress => 'Content_From_Mock'
			] ) );
		$mockBlobStore
			->expects( $this->never() )
			->method( 'getBlob' );

		$revStore = $this->getServiceContainer()
			->getRevisionStoreFactory()
			->getRevisionStore();
		$wrappedRevStore = TestingAccessWrapper::newFromObject( $revStore );
		$wrappedRevStore->blobStore = $mockBlobStore;

		$result = $revStore->newRevisionsFromBatch(
			[ $this->revisionRecordToRow( $revRecord1 ) ],
			[
				'slots' => [ SlotRecord::MAIN ],
				'content' => true
			]
		);
		$this->assertStatusGood( $result );
		$content = $result->getValue()[$revRecord1->getId()]->getContent( SlotRecord::MAIN );
		$this->assertInstanceOf( TextContent::class, $content );
		$this->assertSame(
			'Content_From_Mock',
			$content->getText()
		);
	}

	/** @covers \MediaWiki\Revision\RevisionStore::findIdenticalRevision */
	public function testFindIdenticalRevision() {
		// Prepare a page with 3 revisions
		$page = $this->getExistingTestPage( __METHOD__ );
		$status = $this->editPage( $page, 'Content 1' );
		$this->assertStatusGood( $status, 'edit 1' );
		$originalRev = $status->value[ 'revision-record' ];

		$this->assertTrue( $this->editPage( $page, 'Content 2' )->isGood(), 'edit 2' );

		$status = $this->editPage( $page, 'Content 1' );
		$this->assertStatusGood( $status, 'edit 3' );
		$latestRev = $status->value[ 'revision-record' ];

		$store = $this->getServiceContainer()->getRevisionStore();

		$this->assertNull( $store->findIdenticalRevision( $latestRev, 0 ) );
		$this->assertNull( $store->findIdenticalRevision( $latestRev, 1 ) );
		$foundRev = $store->findIdenticalRevision( $latestRev, 1000 );
		$this->assertSame( $originalRev->getId(), $foundRev->getId() );
	}
}
