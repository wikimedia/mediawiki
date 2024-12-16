<?php

namespace MediaWiki\Tests\Revision;

use Exception;
use InvalidArgumentException;
use MediaWiki\CommentStore\CommentStoreComment;
use MediaWiki\Content\Content;
use MediaWiki\Content\FallbackContent;
use MediaWiki\Content\TextContent;
use MediaWiki\Content\WikitextContent;
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
use MediaWiki\Revision\SlotRoleRegistry;
use MediaWiki\Storage\BlobStore;
use MediaWiki\Storage\SqlBlobStore;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleFactory;
use MediaWiki\User\User;
use MediaWiki\User\UserIdentityValue;
use MediaWiki\Utils\MWTimestamp;
use MediaWikiIntegrationTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use StatusValue;
use Wikimedia\Assert\PreconditionException;
use Wikimedia\ObjectCache\HashBagOStuff;
use Wikimedia\ObjectCache\WANObjectCache;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\DatabaseDomain;
use Wikimedia\Rdbms\DatabaseSqlite;
use Wikimedia\Rdbms\FakeResultWrapper;
use Wikimedia\Rdbms\IDBAccessObject;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Rdbms\LoadBalancer;
use Wikimedia\Rdbms\TransactionProfiler;
use Wikimedia\TestingAccessWrapper;
use WikiPage;

/**
 * @group Database
 * @group RevisionStore
 * @covers \MediaWiki\Revision\RevisionStore
 */
class RevisionStoreDbTest extends MediaWikiIntegrationTestCase {
	use DummyServicesTrait;
	use TempUserTestTrait;

	/**
	 * @var Title
	 */
	private $testPageTitle;

	/**
	 * @var WikiPage
	 */
	private $testPage;

	/**
	 * @return Title
	 */
	protected function getTestPageTitle() {
		if ( $this->testPageTitle ) {
			return $this->testPageTitle;
		}

		$this->testPageTitle = Title::newFromText( 'TestPage-' . __CLASS__ );
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
		$page = $this->getExistingTestPage( $title );

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
			function ( $i, DatabaseDomain $domain, array $lbInfo ) use ( $server ) {
				$conn = $this->getDatabaseMock( $server );
				foreach ( $lbInfo as $k => $v ) {
					$conn->setLBInfo( $k, $v );
				}

				return $conn;
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

	public static function provideDomainCheck() {
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

		$this->assertEquals( $r1->getUser( RevisionRecord::RAW )->getName(), $r2->getUser( RevisionRecord::RAW )->getName() );
		$this->assertEquals( $r1->getUser( RevisionRecord::RAW )->getId(), $r2->getUser( RevisionRecord::RAW )->getId() );
		$this->assertEquals( $r1->getComment( RevisionRecord::RAW ), $r2->getComment( RevisionRecord::RAW ) );
		$this->assertEquals( $r1->getTimestamp(), $r2->getTimestamp() );
		$this->assertEquals( $r1->getVisibility(), $r2->getVisibility() );
		$this->assertEquals( $r1->getSha1(), $r2->getSha1() );
		$this->assertEquals( $r1->getSize(), $r2->getSize() );
		$this->assertEquals( $r1->getPageId(), $r2->getPageId() );
		$this->assertArrayEquals( $r1->getSlotRoles(), $r2->getSlotRoles() );
		$this->assertEquals( $r1->getWikiId(), $r2->getWikiId() );
		$this->assertEquals( $r1->isMinor(), $r2->isMinor() );
		foreach ( $r1->getSlotRoles() as $role ) {
			$this->assertSlotRecordsEqual( $r1->getSlot( $role, RevisionRecord::RAW ), $r2->getSlot( $role, RevisionRecord::RAW ) );
			$this->assertTrue( $r1->getContent( $role, RevisionRecord::RAW )->equals( $r2->getContent( $role, RevisionRecord::RAW ) ) );
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
		$this->assertInstanceOf( SlotRecord::class, $r->getSlot( SlotRecord::MAIN, RevisionRecord::RAW ) );
		$this->assertInstanceOf( Content::class, $r->getContent( SlotRecord::MAIN, RevisionRecord::RAW ) );

		foreach ( $r->getSlotRoles() as $role ) {
			$this->assertSlotCompleteness( $r, $r->getSlot( $role, RevisionRecord::RAW ) );
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
					SlotRecord::MAIN => new WikitextContent( 'Chicken' ),
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
	 */
	public function testInsertRevisionOn_successes(
		array $revDetails = []
	) {
		$title = $this->getTestPageTitle();
		$rev = $this->getRevisionRecordFromDetailsArray( $revDetails );

		$store = $this->getServiceContainer()->getRevisionStore();
		$return = $store->insertRevisionOn( $rev, $this->getDb() );

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
		$this->newSelectQueryBuilder()
			->select( 'count(*)' )
			->from( 'slots' )
			->where( [ 'slot_revision_id' => $rev->getId() ] )
			->assertFieldValue( $numberOfSlots );

		$store = $this->getServiceContainer()->getRevisionStore();
		$revQuery = $store->getSlotsQueryInfo( [ 'content' ] );

		$this->newSelectQueryBuilder()
			->queryInfo( [
				'tables' => $revQuery['tables'],
				'joins' => $revQuery['joins']
			] )
			->select( 'count(*)' )
			->where( [ 'slot_revision_id' => $rev->getId() ] )
			->assertFieldValue( $numberOfSlots );

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

		$this->newSelectQueryBuilder()
			->select( $fields )
			->queryInfo( [
				'tables' => $queryInfo['tables'],
				'joins' => $queryInfo['joins']
			] )
			->where( [ 'rev_id' => $rev->getId() ] )
			->assertResultSet( [ array_values( $row ) ] );
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
		$firstReturn = $store->insertRevisionOn( $revOne, $this->getDb() );
		$this->assertLinkTargetsEqual( $title, $firstReturn->getPageAsLinkTarget() );
		$this->assertRevisionRecordsEqual( $revOne, $firstReturn );

		// Insert a second revision inheriting the same blob address
		$revDetails['slot'] = SlotRecord::newInherited( $firstReturn->getSlot( SlotRecord::MAIN ) );
		$revTwo = $this->getRevisionRecordFromDetailsArray( $revDetails );
		$secondReturn = $store->insertRevisionOn( $revTwo, $this->getDb() );
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
		$store->insertRevisionOn( $rev, $this->getDb() );
	}

	public static function provideNewNullRevision() {
		yield [
			Title::newFromText( 'NewNullRevision_notAutoCreated' ),
			[ 'content' => [ SlotRecord::MAIN => new WikitextContent( 'Flubber1' ) ] ],
			CommentStoreComment::newUnsavedComment( __METHOD__ . ' comment1' ),
			true,
		];
		yield [
			Title::newFromText( 'NewNullRevision_notAutoCreated' ),
			[ 'content' => [ SlotRecord::MAIN => new WikitextContent( 'Flubber2' ) ] ],
			CommentStoreComment::newUnsavedComment( __METHOD__ . ' comment2', [ 'a' => 1 ] ),
			false,
		];
		yield [
			Title::newFromText( 'NewNullRevision_notAutoCreated' ),
			[
				'content' => [
					SlotRecord::MAIN => new WikitextContent( 'Chicken' ),
					'aux' => new WikitextContent( 'Omelet' ),
				],
			],
			CommentStoreComment::newUnsavedComment( __METHOD__ . ' comment multi' ),
		];
	}

	/**
	 * @dataProvider provideNewNullRevision
	 */
	public function testNewNullRevision( Title $title, $revDetails, $comment, $minor = false ) {
		$user = $this->getMutableTestUser()->getUser();
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );

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

		$dbw = $this->getDb();
		$baseRev = $store->insertRevisionOn( $baseRev, $dbw );
		$page->updateRevisionOn( $dbw, $baseRev, $page->getLatest() );

		$record = $store->newNullRevision(
			$this->getDb(),
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

	public function testNewNullRevision_nonExistingTitle() {
		$store = $this->getServiceContainer()->getRevisionStore();
		$record = $store->newNullRevision(
			$this->getDb(),
			Title::newFromText( __METHOD__ . '.iDontExist!' ),
			CommentStoreComment::newUnsavedComment( __METHOD__ . ' comment' ),
			false,
			$this->getMutableTestUser()->getUser()
		);
		$this->assertNull( $record );
	}

	public function testGetRcIdIfUnpatrolled_returnsRecentChangesId() {
		$page = $this->getTestPage();
		$status = $page->doUserEditContent(
			new WikitextContent( __METHOD__ ),
			$this->getTestUser()->getUser(),
			__METHOD__
		);
		$revRecord = $status->getNewRevision();

		$store = $this->getServiceContainer()->getRevisionStore();
		$storeRecord = $store->getRevisionById( $revRecord->getId() );
		$result = $store->getRcIdIfUnpatrolled( $storeRecord );

		$this->assertGreaterThan( 0, $result );
		$this->assertSame(
			$store->getRecentChange( $storeRecord )->getAttribute( 'rc_id' ),
			$result
		);
	}

	public function testGetRcIdIfUnpatrolled_returnsZeroIfPatrolled() {
		// This assumes that sysops are auto patrolled
		$sysop = $this->getTestSysop()->getUser();
		$page = $this->getTestPage();
		$status = $page->doUserEditContent(
			new WikitextContent( __METHOD__ ),
			$sysop,
			__METHOD__
		);
		$revRecord = $status->getNewRevision();

		$store = $this->getServiceContainer()->getRevisionStore();
		$storeRecord = $store->getRevisionById( $revRecord->getId() );
		$result = $store->getRcIdIfUnpatrolled( $storeRecord );

		$this->assertSame( 0, $result );
	}

	public function testGetRecentChange() {
		$page = $this->getTestPage();
		$content = new WikitextContent( __METHOD__ );
		$status = $page->doUserEditContent(
			$content,
			$this->getTestSysop()->getUser(),
			__METHOD__
		);
		$revRecord = $status->getNewRevision();

		$store = $this->getServiceContainer()->getRevisionStore();
		$storeRecord = $store->getRevisionById( $revRecord->getId() );
		$recentChange = $store->getRecentChange( $storeRecord );

		$this->assertEquals( $revRecord->getId(), $recentChange->getAttribute( 'rc_this_oldid' ) );
	}

	public function testGetRevisionById() {
		$page = $this->getTestPage();
		$content = new WikitextContent( __METHOD__ );
		$status = $page->doUserEditContent(
			$content,
			$this->getTestSysop()->getUser(),
			__METHOD__
		);
		$revRecord = $status->getNewRevision();

		$store = $this->getServiceContainer()->getRevisionStore();
		$storeRecord = $store->getRevisionById( $revRecord->getId() );

		$this->assertSame( $revRecord->getId(), $storeRecord->getId() );
		$this->assertTrue( $storeRecord->getSlot( SlotRecord::MAIN )->getContent()->equals( $content ) );
		$this->assertSame( __METHOD__, $storeRecord->getComment()->text );
	}

	public function testGetRevisionById_crossWiki_withPage() {
		$page = $this->getTestPage();
		$content = new WikitextContent( __METHOD__ );
		$status = $page->doUserEditContent(
			$content,
			$this->getTestSysop()->getUser(),
			__METHOD__
		);
		$revRecord = $status->getNewRevision();
		$revId = $revRecord->getId();

		// Pretend the local test DB is a sister site
		$wikiId = $this->getDb()->getDomainID();
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

	public function testGetRevisionById_crossWiki() {
		$page = $this->getTestPage();
		$content = new WikitextContent( __METHOD__ );
		$status = $page->doUserEditContent(
			$content,
			$this->getTestSysop()->getUser(),
			__METHOD__
		);
		$revRecord = $status->getNewRevision();
		$revId = $revRecord->getId();
		$pageId = $revRecord->getPageId();

		// Make TitleFactory always fail, since it should not be used for the cross-wiki case.
		$noOpTitleFactory = $this->createNoOpMock( TitleFactory::class );
		$this->setService( 'TitleFactory', $noOpTitleFactory );

		// Pretend the local test DB is a sister site
		$wikiId = $this->getDb()->getDomainID();
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

	public function testGetRevisionById_undefinedContentModel() {
		$page = $this->getTestPage();
		$content = new WikitextContent( __METHOD__ );
		$status = $page->doUserEditContent(
			$content,
			$this->getTestSysop()->getUser(),
			__METHOD__
		);
		$revRecord = $status->getNewRevision();

		$mockContentHandlerFactory = $this->getDummyContentHandlerFactory();
		$this->setService( 'ContentHandlerFactory', $mockContentHandlerFactory );
		$store = $this->getServiceContainer()->getRevisionStore();

		$storeRecord = $store->getRevisionById( $revRecord->getId() );

		$this->assertSame( $revRecord->getId(), $storeRecord->getId() );

		$actualContent = $storeRecord->getSlot( SlotRecord::MAIN )->getContent();
		$this->assertInstanceOf( FallbackContent::class, $actualContent );
		$this->assertSame( __METHOD__, $actualContent->serialize() );
	}

	/**
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
		$revRecord = $status->getNewRevision();

		$store = $this->getServiceContainer()->getRevisionStore();
		$storeRecord = $store->getRevisionByTitle( $title );

		$this->assertSame( $revRecord->getId(), $storeRecord->getId() );
		$this->assertTrue( $storeRecord->getSlot( SlotRecord::MAIN )->getContent()->equals( $content ) );
		$this->assertSame( __METHOD__, $storeRecord->getComment()->text );
	}

	public function provideRevisionByTitle() {
		return [
			[ function () {
				return $this->getTestPageTitle();
			} ],
			[ function () {
				return $this->getTestPageTitle()->toPageIdentity();
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
			$services->getActorStoreFactory()->getActorStore( $dbDomain ),
			$services->getContentHandlerFactory(),
			$services->getPageStoreFactory()->getPageStore( $dbDomain ),
			$services->getTitleFactory(),
			$services->getHookContainer(),
			$dbDomain
		);

		// Redefine the DBLoadBalancer service to verify Title doesn't attempt to resolve its ID
		// via getPrimaryDatabase() etc.
		$localLoadBalancerMock = $this->createMock( ILoadBalancer::class );
		$localLoadBalancerMock->expects( $this->never() )
			->method( $this->anything() );

		try {
			$this->setService( 'DBLoadBalancer', $localLoadBalancerMock );
			// There may be other code which indirectly uses the RevisionStore
			// service; make sure it picks up the external store as well.
			$this->setService( 'RevisionStore', $store );
			$callback( $store );
		} finally {
			// Restore the original load balancer to make test teardown work
			$this->setService( 'DBLoadBalancer', $dbLoadBalancer );
		}
	}

	public function testGetLatestKnownRevision_foreigh() {
		$page = $this->getTestPage();
		$status = $this->editPage( $page, __METHOD__ );
		$this->assertStatusGood( $status, 'edited a page' );
		$revRecord = $status->getNewRevision();
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
	 * getRevisionByTitle should not use the local wiki DB (T248756)
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
		$revRecord = $status->getNewRevision();
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

	public function testGetRevisionByPageId() {
		$page = $this->getTestPage();
		$content = new WikitextContent( __METHOD__ );
		$status = $page->doUserEditContent(
			$content,
			$this->getTestSysop()->getUser(),
			__METHOD__
		);
		$revRecord = $status->getNewRevision();

		$store = $this->getServiceContainer()->getRevisionStore();
		$storeRecord = $store->getRevisionByPageId( $page->getId() );

		$this->assertSame( $revRecord->getId(), $storeRecord->getId() );
		$this->assertTrue( $storeRecord->getSlot( SlotRecord::MAIN )->getContent()->equals( $content ) );
		$this->assertSame( __METHOD__, $storeRecord->getComment()->text );

		$storeRecord = $store->getRevisionByPageId( $page->getId(), 0, IDBAccessObject::READ_LOCKING );
		$this->assertSame( $revRecord->getId(), $storeRecord->getId() );
		$this->assertTrue( $storeRecord->getSlot( SlotRecord::MAIN )->getContent()->equals( $content ) );
		$this->assertSame( __METHOD__, $storeRecord->getComment()->text );
	}

	/**
	 * @dataProvider provideRevisionByTitle
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
		$revRecord = $status->getNewRevision();

		$store = $this->getServiceContainer()->getRevisionStore();
		$storeRecord = $store->getRevisionByTimestamp(
			$title,
			$revRecord->getTimestamp()
		);

		$this->assertSame( $revRecord->getId(), $storeRecord->getId() );
		$this->assertTrue( $storeRecord->getSlot( SlotRecord::MAIN )->getContent()->equals( $content ) );
		$this->assertSame( __METHOD__, $storeRecord->getComment()->text );
	}

	protected function revisionRecordToRow( RevisionRecord $revRecord, $options = [ 'page', 'user', 'comment' ] ) {
		// XXX: the WikiPage object loads another RevisionRecord from the database. Not great.
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $revRecord->getPage() );

		$revUser = $revRecord->getUser();
		$actorId = $this->getServiceContainer()
			->getActorNormalization()->findActorId( $revUser, $this->getDb() );

		$fields = [
			'rev_id' => (string)$revRecord->getId(),
			'rev_page' => (string)$revRecord->getPageId(),
			'rev_timestamp' => $this->getDb()->timestamp( $revRecord->getTimestamp() ),
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

	public function testNewRevisionFromRowAndSlots_getQueryInfo() {
		$page = $this->getTestPage();
		$text = __METHOD__ . 'o-ö';
		$revRecord = $page->doUserEditContent(
			new WikitextContent( $text ),
			$this->getTestSysop()->getUser(),
			__METHOD__ . 'a'
		)->getNewRevision();

		$store = $this->getServiceContainer()->getRevisionStore();
		$info = $store->getQueryInfo();
		$row = $this->getDb()->newSelectQueryBuilder()
			->queryInfo( $info )
			->where( [ 'rev_id' => $revRecord->getId() ] )
			->caller( __METHOD__ )
			->fetchRow();

		$info = $store->getSlotsQueryInfo( [ 'content' ] );
		$slotRows = $this->getDb()->newSelectQueryBuilder()
			->queryInfo( $info )
			->where( [ 'slot_revision_id' => $revRecord->getId() ] )
			->caller( __METHOD__ )
			->fetchResultSet();

		$storeRecord = $store->newRevisionFromRowAndSlots(
			$row,
			iterator_to_array( $slotRows ),
			0,
			$page->getTitle()
		);
		$this->assertRevisionRecordsEqual( $revRecord, $storeRecord );
		$this->assertSame( $text, $revRecord->getContent( SlotRecord::MAIN )->serialize() );
	}

	public function testNewRevisionFromRow_getQueryInfo() {
		$page = $this->getTestPage();
		$text = __METHOD__ . 'a-ä';
		$revRecord = $page->doUserEditContent(
			new WikitextContent( $text ),
			$this->getTestSysop()->getUser(),
			__METHOD__ . 'a'
		)->getNewRevision();

		$store = $this->getServiceContainer()->getRevisionStore();
		$info = $store->getQueryInfo();
		$row = $this->getDb()->newSelectQueryBuilder()
			->queryInfo( $info )
			->where( [ 'rev_id' => $revRecord->getId() ] )
			->caller( __METHOD__ )
			->fetchRow();
		$storeRecord = $store->newRevisionFromRow(
			$row,
			0,
			$page->getTitle()
		);
		$this->assertRevisionRecordsEqual( $revRecord, $storeRecord );
		$this->assertSame( $text, $revRecord->getContent( SlotRecord::MAIN )->serialize() );
	}

	public function testNewRevisionFromRow_anonEdit() {
		$page = $this->getTestPage();
		$text = __METHOD__ . 'a-ä';
		$revRecord = $page->doUserEditContent(
			new WikitextContent( $text ),
			$this->getTestSysop()->getUser(),
			__METHOD__ . 'a'
		)->getNewRevision();

		$store = $this->getServiceContainer()->getRevisionStore();
		$storeRecord = $store->newRevisionFromRow(
			$this->revisionRecordToRow( $revRecord ),
			0,
			$page->getTitle()
		);
		$this->assertRevisionRecordsEqual( $revRecord, $storeRecord );
		$this->assertSame( $text, $revRecord->getContent( SlotRecord::MAIN )->serialize() );
	}

	public function testNewRevisionFromRow_anonEdit_legacyEncoding() {
		$this->overrideConfigValue( MainConfigNames::LegacyEncoding, 'windows-1252' );
		$page = $this->getTestPage();
		$text = __METHOD__ . 'a-ä';
		$revRecord = $page->doUserEditContent(
			new WikitextContent( $text ),
			$this->getTestSysop()->getUser(),
			__METHOD__ . 'a'
		)->getNewRevision();

		$store = $this->getServiceContainer()->getRevisionStore();
		$storeRecord = $store->newRevisionFromRow(
			$this->revisionRecordToRow( $revRecord ),
			0,
			$page->getTitle()
		);
		$this->assertRevisionRecordsEqual( $revRecord, $storeRecord );
		$this->assertSame( $text, $revRecord->getContent( SlotRecord::MAIN )->serialize() );
	}

	public function testNewRevisionFromRow_userEdit() {
		$page = $this->getTestPage();
		$text = __METHOD__ . 'b-ä';
		$revRecord = $page->doUserEditContent(
			new WikitextContent( $text ),
			$this->getTestUser()->getUser(),
			__METHOD__ . 'b'
		)->getNewRevision();

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
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $pageIdentity );
		$orig = $page->doUserEditContent(
			new WikitextContent( $text ),
			$this->getTestSysop()->getUser(),
			__METHOD__
		)->getNewRevision();
		$this->deletePage( $page );

		$res = $store->newArchiveSelectQueryBuilder( $this->getDb() )
			->joinComment()
			->where( [ 'ar_rev_id' => $orig->getId() ] )
			->caller( __METHOD__ )->fetchResultSet();
		$this->assertIsObject( $res, 'query failed' );

		$info = $store->getSlotsQueryInfo( [ 'content' ] );
		$slotRows = $this->getDb()->newSelectQueryBuilder()
			->queryInfo( $info )
			->where( [ 'slot_revision_id' => $orig->getId() ] )
			->caller( __METHOD__ )
			->fetchResultSet();

		$row = $res->fetchObject();
		$res->free();
		return [ $store, $row, $slotRows, $orig ];
	}

	public function testNewRevisionFromArchiveRowAndSlots_getArchiveQueryInfo() {
		$text = __METHOD__ . '-bä';
		$title = Title::newFromText( __METHOD__ );
		[ $store, $row, $slotRows, $orig ] = $this->buildRevisionStore( $text, $title );
		$storeRecord = $store->newRevisionFromArchiveRowAndSlots(
			$row,
			iterator_to_array( $slotRows )
		);
		$this->assertRevisionRecordsEqual( $orig, $storeRecord );
		$this->assertSame( $text, $storeRecord->getContent( SlotRecord::MAIN, RevisionRecord::RAW )->serialize() );
	}

	public static function provideNewRevisionFromArchiveRowAndSlotsTitles() {
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
	 */
	public function testNewRevisionFromArchiveRowAndSlots_getArchiveQueryInfoWithTitle( $getPageIdentity ) {
		$text = __METHOD__ . '-bä';
		$page = $getPageIdentity();
		[ $store, $row, $slotRows, $orig ] = $this->buildRevisionStore( $text, $page );
		$storeRecord = $store->newRevisionFromArchiveRowAndSlots(
			$row,
			iterator_to_array( $slotRows ),
			0,
			$page
		);

		$this->assertRevisionRecordsEqual( $orig, $storeRecord );
		$this->assertSame( $text, $storeRecord->getContent( SlotRecord::MAIN, RevisionRecord::RAW )->serialize() );
	}

	public static function provideNewRevisionFromArchiveRowAndSlotsInArray() {
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
	 */
	public function testNewRevisionFromArchiveRowAndSlots_getArchiveQueryInfoWithTitleInArray( $array ) {
		$text = __METHOD__ . '-bä';
		$page = $array[ 'title' ];
		[ $store, $row, $slotRows, $orig ] = $this->buildRevisionStore( $text, $page );
		$storeRecord = $store->newRevisionFromArchiveRowAndSlots(
			$row,
			iterator_to_array( $slotRows ),
			0,
			null,
			$array
		);

		$this->assertRevisionRecordsEqual( $orig, $storeRecord );
		$this->assertSame( $text, $storeRecord->getContent( SlotRecord::MAIN, RevisionRecord::RAW )->serialize() );
	}

	/**
	 * @covers \MediaWiki\Revision\ArchiveSelectQueryBuilder
	 */
	public function testNewRevisionFromArchiveRow_getArchiveQueryInfo() {
		$store = $this->getServiceContainer()->getRevisionStore();
		$title = Title::newFromText( __METHOD__ );
		$text = __METHOD__ . '-bä';
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );
		$orig = $page->doUserEditContent(
			new WikitextContent( $text ),
			$this->getTestSysop()->getUser(),
			__METHOD__
		)->getNewRevision();
		$this->deletePage( $page );

		$res = $store->newArchiveSelectQueryBuilder( $this->getDb() )
			->joinComment()
			->where( [ 'ar_rev_id' => $orig->getId() ] )
			->caller( __METHOD__ )->fetchResultSet();
		$this->assertIsObject( $res, 'query failed' );

		$row = $res->fetchObject();
		$res->free();
		$storeRecord = $store->newRevisionFromArchiveRow( $row );

		$this->assertRevisionRecordsEqual( $orig, $storeRecord );
		$this->assertSame( $text, $storeRecord->getContent( SlotRecord::MAIN, RevisionRecord::RAW )->serialize() );
	}

	public function testNewRevisionFromArchiveRow_legacyEncoding() {
		$this->overrideConfigValue( MainConfigNames::LegacyEncoding, 'windows-1252' );
		$store = $this->getServiceContainer()->getRevisionStore();
		$title = Title::newFromText( __METHOD__ );
		$text = __METHOD__ . '-bä';
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );
		$orig = $page->doUserEditContent(
			new WikitextContent( $text ),
			$this->getTestSysop()->getUser(),
			__METHOD__
		)->getNewRevision();
		$this->deletePage( $page );

		$res = $store->newArchiveSelectQueryBuilder( $this->getDb() )
			->joinComment()
			->where( [ 'ar_rev_id' => $orig->getId() ] )
			->caller( __METHOD__ )->fetchResultSet();
		$this->assertIsObject( $res, 'query failed' );

		$row = $res->fetchObject();
		$res->free();
		$storeRecord = $store->newRevisionFromArchiveRow( $row );

		$this->assertRevisionRecordsEqual( $orig, $storeRecord );
		$this->assertSame( $text, $storeRecord->getContent( SlotRecord::MAIN, RevisionRecord::RAW )->serialize() );
	}

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
		$this->getDb()->newInsertQueryBuilder()
			->insertInto( 'actor' )
			->row( [
				'actor_user' => $row->ar_user,
				'actor_name' => $row->ar_user_text,
			] )
			->caller( __METHOD__ )
			->execute();

		$row->ar_actor = $this->getDb()->insertId();

		$record = @$store->newRevisionFromArchiveRow( $row );

		$this->assertInstanceOf( RevisionRecord::class, $record );
		$this->assertInstanceOf( UserIdentityValue::class, $record->getUser() );
		$this->assertSame( 'Unknown user', $record->getUser()->getName() );
	}

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

	public function testNewRevisionFromRow_noPage() {
		$store = $this->getServiceContainer()->getRevisionStore();
		$page = $this->getExistingTestPage();

		$info = $store->getQueryInfo();
		$row = $this->getDb()->newSelectQueryBuilder()
			->queryInfo( $info )
			->where( [ 'rev_page' => $page->getId(), 'rev_id' => $page->getLatest() ] )
			->caller( __METHOD__ )
			->fetchRow();

		$record = $store->newRevisionFromRow( $row );

		$this->assertNotNull( $record );
		$this->assertTrue( $page->isSamePageAs( $record->getPage() ) );

		// NOTE: This should return a Title object for now, until we no longer have a need
		//       to frequently convert to Title.
		$this->assertInstanceOf( Title::class, $record->getPage() );
	}

	public function testNewRevisionFromRow_noPage_crossWiki() {
		$page = $this->getExistingTestPage();
		// Make TitleFactory always fail, since it should not be used for the cross-wiki case. Note, it's important
		// to do this *after* the test page has been created.
		$noOpTitleFactory = $this->createNoOpMock( TitleFactory::class );
		$this->setService( 'TitleFactory', $noOpTitleFactory );

		// Pretend the local test DB is a sister site
		$wikiId = $this->getDb()->getDomainID();
		$store = $this->getServiceContainer()->getRevisionStoreFactory()
			->getRevisionStore( $wikiId );

		$info = $store->getQueryInfo();
		$row = $this->getDb()->newSelectQueryBuilder()
			->queryInfo( $info )
			->where( [ 'rev_page' => $page->getId(), 'rev_id' => $page->getLatest() ] )
			->caller( __METHOD__ )
			->fetchRow();

		$record = $store->newRevisionFromRow( $row );

		$this->assertNotNull( $record );
		$this->assertSame( $page->getLatest(), $record->getId( $wikiId ) );

		$this->assertNotInstanceOf( Title::class, $record->getPage() );
		$this->assertSame( $page->getId(), $record->getPage()->getId( $wikiId ) );
	}

	/**
	 * @dataProvider provideInsertRevisionOn
	 *
	 * @param callable $getPageIdentity
	 */
	public function testInsertRevisionOn_archive( $getPageIdentity ) {
		// This is a round trip test for deletion and undeletion of a
		// revision row via the archive table.
		[ $title, $pageIdentity ] = $getPageIdentity();
		$store = $this->getServiceContainer()->getRevisionStore();

		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );
		$user = $this->getTestSysop()->getUser();
		$page->doUserEditContent( new WikitextContent( "First" ), $user, __METHOD__ . '-first' );
		$orig = $page->doUserEditContent( new WikitextContent( "Foo" ), $user, __METHOD__ )
			->getNewRevision();
		$this->deletePage( $page );

		// re-create page, so we can later load revisions for it
		$page->doUserEditContent( new WikitextContent( 'Two' ), $user, __METHOD__ );

		$db = $this->getDb();
		$row = $store->newArchiveSelectQueryBuilder( $db )
			->joinComment()
			->where( [ 'ar_rev_id' => $orig->getId() ] )
			->caller( __METHOD__ )->fetchRow();

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
		$recMain = $record->getSlot( SlotRecord::MAIN, RevisionRecord::RAW );
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

	public static function provideInsertRevisionOn() {
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

	public function testGetParentLengths() {
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( Title::newFromText( __METHOD__ ) );
		$revRecordOne = $page->doUserEditContent(
			new WikitextContent( __METHOD__ ),
			$this->getTestSysop()->getUser(),
			__METHOD__
		)->getNewRevision();
		$revRecordTwo = $page->doUserEditContent(
			new WikitextContent( __METHOD__ . '2' ),
			$this->getTestSysop()->getUser(),
			__METHOD__
		)->getNewRevision();

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

	public function testGetPreviousRevision() {
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( Title::newFromText( __METHOD__ ) );
		$revRecordOne = $page->doUserEditContent(
			new WikitextContent( __METHOD__ ),
			$this->getTestSysop()->getUser(),
			__METHOD__
		)->getNewRevision();
		$revRecordTwo = $page->doUserEditContent(
			new WikitextContent( __METHOD__ . '2' ),
			$this->getTestSysop()->getUser(),
			__METHOD__
		)->getNewRevision();

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

	public function testGetNextRevision() {
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( Title::newFromText( __METHOD__ ) );
		$revRecordOne = $page->doUserEditContent(
			new WikitextContent( __METHOD__ ),
			$this->getTestSysop()->getUser(),
			__METHOD__
		)->getNewRevision();
		$revRecordTwo = $page->doUserEditContent(
			new WikitextContent( __METHOD__ . '2' ),
			$this->getTestSysop()->getUser(),
			__METHOD__
		)->getNewRevision();

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

	public static function provideNonHistoryRevision() {
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
	 */
	public function testGetPreviousRevision_bad( RevisionRecord $rev ) {
		$store = $this->getServiceContainer()->getRevisionStore();
		$this->assertNull( $store->getPreviousRevision( $rev ) );
	}

	/**
	 * @dataProvider provideNonHistoryRevision
	 */
	public function testGetNextRevision_bad( RevisionRecord $rev ) {
		$store = $this->getServiceContainer()->getRevisionStore();
		$this->assertNull( $store->getNextRevision( $rev ) );
	}

	public function testGetTimestampFromId_found() {
		$page = $this->getTestPage();
		$revRecord = $page->doUserEditContent(
			new WikitextContent( __METHOD__ ),
			$this->getTestSysop()->getUser(),
			__METHOD__
		)->getNewRevision();

		$store = $this->getServiceContainer()->getRevisionStore();
		$result = $store->getTimestampFromId( $revRecord->getId() );

		$this->assertSame( $revRecord->getTimestamp(), $result );
	}

	public function testGetTimestampFromId_notFound() {
		$page = $this->getTestPage();
		$revRecord = $page->doUserEditContent(
			new WikitextContent( __METHOD__ ),
			$this->getTestSysop()->getUser(),
			__METHOD__
		)->getNewRevision();

		$store = $this->getServiceContainer()->getRevisionStore();
		$result = $store->getTimestampFromId( $revRecord->getId() + 1 );

		$this->assertFalse( $result );
	}

	public function testCountRevisionsByPageId() {
		$store = $this->getServiceContainer()->getRevisionStore();
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( Title::newFromText( __METHOD__ ) );
		$user = $this->getTestSysop()->getUser();

		$this->assertSame(
			0,
			$store->countRevisionsByPageId( $this->getDb(), $page->getId() )
		);
		$page->doUserEditContent( new WikitextContent( 'a' ), $user, 'a' );
		$this->assertSame(
			1,
			$store->countRevisionsByPageId( $this->getDb(), $page->getId() )
		);
		$page->doUserEditContent( new WikitextContent( 'b' ), $user, 'b' );
		$this->assertSame(
			2,
			$store->countRevisionsByPageId( $this->getDb(), $page->getId() )
		);
	}

	public function testCountRevisionsByTitle() {
		$store = $this->getServiceContainer()->getRevisionStore();
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( Title::newFromText( __METHOD__ ) );
		$user = $this->getTestSysop()->getUser();

		$this->assertSame(
			0,
			$store->countRevisionsByTitle( $this->getDb(), $page->getTitle() )
		);
		$page->doUserEditContent( new WikitextContent( 'a' ), $user, 'a' );
		$this->assertSame(
			1,
			$store->countRevisionsByTitle( $this->getDb(), $page->getTitle() )
		);
		$page->doUserEditContent( new WikitextContent( 'b' ), $user, 'b' );
		$this->assertSame(
			2,
			$store->countRevisionsByTitle( $this->getDb(), $page->getTitle() )
		);
	}

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
			$this->getDb(),
			$page->getId(),
			$sysop->getId(),
			'20160101010101'
		);
		$this->assertFalse( $result );
	}

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
			$this->getDb(),
			$page->getId(),
			$sysop->getId(),
			$startTime
		);
		$this->assertTrue( $result );
	}

	/**
	 * @dataProvider provideRevisionByTitle
	 */
	public function testGetKnownCurrentRevision( $getPageIdentity ) {
		$page = $this->getTestPage();
		$revRecord = $page->doUserEditContent(
			new WikitextContent( __METHOD__ . 'b' ),
			$this->getTestUser()->getUser(),
			__METHOD__ . 'b'
		)->getNewRevision();
		$store = $this->getServiceContainer()->getRevisionStore();
		$storeRecord = $store->getKnownCurrentRevision(
			$getPageIdentity(),
			$revRecord->getId()
		);
		$this->assertRevisionRecordsEqual( $revRecord, $storeRecord );
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
		$this->getDb()->newUpdateQueryBuilder()
			->update( 'user' )
			->set( [ 'user_name' => $newUserName ] )
			->where( [ 'user_id' => $rev->getUser()->getId() ] )
			->caller( __METHOD__ )
			->execute();
		$this->getDb()->newUpdateQueryBuilder()
			->update( 'actor' )
			->set( [ 'actor_name' => $newUserName ] )
			->where( [ 'actor_user' => $rev->getUser()->getId() ] )
			->caller( __METHOD__ )
			->execute();

		// Reload the revision and regrab the user name.
		$revAfter = $store->getKnownCurrentRevision( $page->getTitle(), $rev->getId() );
		$userNameAfter = $revAfter->getUser()->getName();

		// The two user names should be different.
		// If they are the same, we are seeing a cached value, which is bad.
		$this->assertNotSame( $userNameBefore, $userNameAfter );

		// This is implied by the above assertion, but explicitly check it, for completeness
		$this->assertSame( $newUserName, $userNameAfter );
	}

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

	public function testGetKnownCurrentRevision_revDelete() {
		$cache = new WANObjectCache( [ 'cache' => new HashBagOStuff() ] );
		$this->setService( 'MainWANObjectCache', $cache );

		$store = $this->getServiceContainer()->getRevisionStore();
		$page = $this->getNonexistingTestPage();
		$rev = $this->createRevisionStoreCacheRecord( $page, $store );

		// Grab the deleted bitmask
		$deletedBefore = $rev->getVisibility();

		// Change the deleted bitmask in the database, "behind the back" of the cache
		$this->getDb()->newUpdateQueryBuilder()
			->update( 'revision' )
			->set( [ 'rev_deleted' => RevisionRecord::DELETED_TEXT ] )
			->where( [ 'rev_id' => $rev->getId() ] )
			->caller( __METHOD__ )
			->execute();

		// Reload the revision and regrab the visibility flag.
		$revAfter = $store->getKnownCurrentRevision( $page->getTitle(), $rev->getId() );
		$deletedAfter = $revAfter->getVisibility();

		// The two deleted flags should be different.
		// If they are the same, we are seeing a cached value, which is bad.
		$this->assertNotSame( $deletedBefore, $deletedAfter );

		// This is implied by the above assertion, but explicitly check it, for completeness
		$this->assertSame( RevisionRecord::DELETED_TEXT, $deletedAfter );
	}

	public function testNewRevisionFromRow_userNameChange() {
		$page = $this->getTestPage();
		$text = __METHOD__;
		$revRecord = $page->doUserEditContent(
			new WikitextContent( $text ),
			$this->getMutableTestUser()->getUser(),
			__METHOD__
		)->getNewRevision();

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
		$this->getDb()->newUpdateQueryBuilder()
			->update( 'user' )
			->set( [ 'user_name' => $newUserName ] )
			->where( [ 'user_id' => $storeRecord->getUser()->getId() ] )
			->caller( __METHOD__ )
			->execute();
		$this->getDb()->newUpdateQueryBuilder()
			->update( 'actor' )
			->set( [ 'actor_name' => $newUserName ] )
			->where( [ 'actor_user' => $storeRecord->getUser()->getId() ] )
			->caller( __METHOD__ )
			->execute();

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

	public function testNewRevisionFromRow_revDelete() {
		$page = $this->getTestPage();
		$text = __METHOD__;
		$revRecord = $page->doUserEditContent(
			new WikitextContent( $text ),
			$this->getTestSysop()->getUser(),
			__METHOD__
		)->getNewRevision();

		$store = $this->getServiceContainer()->getRevisionStore();
		$storeRecord = $store->newRevisionFromRow(
			$this->revisionRecordToRow( $revRecord ),
			0,
			$page->getTitle()
		);

		// Grab the deleted bitmask
		$deletedBefore = $storeRecord->getVisibility();

		// Change the deleted bitmask in the database
		$this->getDb()->newUpdateQueryBuilder()
			->update( 'revision' )
			->set( [ 'rev_deleted' => RevisionRecord::DELETED_TEXT ] )
			->where( [ 'rev_id' => $storeRecord->getId() ] )
			->caller( __METHOD__ )
			->execute();

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

	public static function provideGetContentBlobsForBatchOptions() {
		yield 'all slots' => [ null ];
		yield 'no slots' => [ [] ];
		yield 'main slot' => [ [ SlotRecord::MAIN ] ];
	}

	/**
	 * @dataProvider provideGetContentBlobsForBatchOptions
	 */
	public function testGetContentBlobsForBatch( $slots ) {
		$page1 = $this->getTestPage();
		$text = __METHOD__ . 'b-ä';
		$editStatus = $this->editPage( $page1, $text . '1' );
		$this->assertStatusGood( $editStatus, 'must create revision 1' );
		$revRecord1 = $editStatus->getNewRevision();

		$page2 = $this->getTestPage( $page1->getTitle()->getPrefixedText() . '_other' );
		$editStatus = $this->editPage( $page2, $text . '2' );
		$this->assertStatusGood( $editStatus, 'must create revision 2' );
		$revRecord2 = $editStatus->getNewRevision();

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

	public function testGetContentBlobsForBatch_archive() {
		$page1 = $this->getTestPage( __METHOD__ );
		$text = __METHOD__ . 'b-ä';
		$editStatus = $this->editPage( $page1, $text . '1' );
		$this->assertStatusGood( $editStatus, 'must create revision 1' );
		$revRecord1 = $editStatus->getNewRevision();
		$this->deletePage( $page1 );

		$page2 = $this->getTestPage( $page1->getTitle()->getPrefixedText() . '_other' );
		$editStatus = $this->editPage( $page2, $text . '2' );
		$this->assertStatusGood( $editStatus, 'must create revision 2' );
		$revRecord2 = $editStatus->getNewRevision();
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

	public function testGetContentBlobsForBatch_emptyBatch() {
		$rows = new FakeResultWrapper( [] );
		$result = $this->getServiceContainer()->getRevisionStore()
			->getContentBlobsForBatch( $rows );
		$this->assertStatusGood( $result );
		$this->assertStatusValue( [], $result );
	}

	public static function provideNewRevisionsFromBatchOptions() {
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
		yield 'Ask for only-defined slots' => [
			[ 'comment' ],
			static function () {
				$pageTitle = Title::newFromText( 'Test_New_Revision_From_Batch' );
				return [ $pageTitle, $pageTitle ];
			},
			null,
			[ 'slots' => [ 'unused' ] ]
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
		if ( isset( $options['slots'] ) && in_array( 'unused', $options['slots'] ) ) {
			$this->getServiceContainer()->addServiceManipulator(
				'SlotRoleRegistry',
				static function ( SlotRoleRegistry $registry ) {
					$registry->defineRoleWithModel( 'unused', CONTENT_MODEL_JSON );
				}
			);
		}

		$page1 = $this->getTestPage();
		$text = __METHOD__ . 'b-ä';
		$editStatus = $this->editPage( $page1, $text . '1' );
		$this->assertStatusGood( $editStatus, 'must create revision 1' );
		$revRecord1 = $editStatus->getNewRevision();

		$page2 = $this->getTestPage( $otherPageTitle );
		$editStatus = $this->editPage( $page2, $text . '2' );
		$this->assertStatusGood( $editStatus, 'must create revision 2' );
		$revRecord2 = $editStatus->getNewRevision();

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
		if ( isset( $options['slots'] ) && in_array( 'unused', $options['slots'] ) ) {
			$this->getServiceContainer()->addServiceManipulator(
				'SlotRoleRegistry',
				static function ( SlotRoleRegistry $registry ) {
					$registry->defineRoleWithModel( 'unused', CONTENT_MODEL_JSON );
				}
			);
		}

		[ $title1, $pageIdentity ] = $getPageIdentity();
		$text1 = __METHOD__ . '-bä';
		$page1 = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title1 );

		$title2 = $otherPageTitle ? Title::newFromText( $otherPageTitle ) : $title1;
		$text2 = __METHOD__ . '-bö';
		$page2 = $otherPageTitle ? $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title2 ) : $page1;

		$sysop = $this->getTestSysop()->getUser();
		$revRecord1 = $page1->doUserEditContent(
			new WikitextContent( $text1 ),
			$sysop,
			__METHOD__
		)->getNewRevision();
		$revRecord2 = $page2->doUserEditContent(
			new WikitextContent( $text2 ),
			$sysop,
			__METHOD__
		)->getNewRevision();
		$this->deletePage( $page1 );

		if ( $page2 !== $page1 ) {
			$this->deletePage( $page2 );
		}

		$store = $this->getServiceContainer()->getRevisionStore();

		$rows = $store->newArchiveSelectQueryBuilder( $this->getDb() )
			->joinComment()
			->where( [ 'ar_rev_id' => [ $revRecord1->getId(), $revRecord2->getId() ] ] )
			->caller( __METHOD__ )->fetchResultSet();

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

		$content1 = $records[$revRecord1->getId()]->getContent( SlotRecord::MAIN, RevisionRecord::RAW );
		$this->assertInstanceOf( TextContent::class, $content1 );
		$this->assertSame(
			$text1,
			$content1->getText()
		);
		$content2 = $records[$revRecord2->getId()]->getContent( SlotRecord::MAIN, RevisionRecord::RAW );
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

	public function testNewRevisionsFromBatch_wrongTitle() {
		$page1 = $this->getTestPage();
		$text = __METHOD__ . 'b-ä';
		$editStatus = $this->editPage( $page1, $text . '1' );
		$this->assertStatusGood( $editStatus, 'must create revision 1' );
		$revRecord1 = $editStatus->getNewRevision();

		$this->expectException( InvalidArgumentException::class );
		$this->getServiceContainer()->getRevisionStore()
			->newRevisionsFromBatch(
				[ $this->revisionRecordToRow( $revRecord1 ) ],
				[],
				IDBAccessObject::READ_NORMAL,
				$this->getTestPage( 'Title_Other_Then_The_One_Revision_Belongs_To' )->getTitle()
			);
	}

	public function testNewRevisionsFromBatch_DuplicateRows() {
		$page1 = $this->getTestPage();
		$text = __METHOD__ . 'b-ä';
		$editStatus = $this->editPage( $page1, $text . '1' );
		$this->assertStatusGood( $editStatus, 'must create revision 1' );
		$revRecord1 = $editStatus->getNewRevision();

		$status = $this->getServiceContainer()->getRevisionStore()
			->newRevisionsFromBatch(
				[
					$this->revisionRecordToRow( $revRecord1 ),
					$this->revisionRecordToRow( $revRecord1 )
				]
			);

		$this->assertStatusWarning( 'internalerror_info', $status );
	}

	public function testGetRevisionIdsBetween() {
		$NUM = 5;
		$MAX = 1;
		$page = $this->getTestPage( __METHOD__ );
		$revisions = [];
		$revisionIds = [];
		for ( $revNum = 0; $revNum < $NUM; $revNum++ ) {
			$editStatus = $this->editPage( $page, 'Revision ' . $revNum );
			$this->assertStatusGood( $editStatus, 'must create revision ' . $revNum );
			$newRevision = $editStatus->getNewRevision();
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

	public function testCountRevisionsBetween() {
		$NUM = 5;
		$MAX = 1;
		$page = $this->getTestPage( __METHOD__ );
		$revisions = [];
		for ( $revNum = 0; $revNum < $NUM; $revNum++ ) {
			$editStatus = $this->editPage( $page, 'Revision ' . $revNum );
			$this->assertStatusGood( $editStatus, 'must create revision ' . $revNum );
			$revisions[] = $editStatus->getNewRevision();
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

	public function testAuthorsBetween() {
		$this->disableAutoCreateTempUser();
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
				$page,
				'Revision ' . $revNum,
				'',
				NS_MAIN,
				$users[$revNum] );
			$this->assertStatusGood( $editStatus, 'must create revision ' . $revNum );
			$revisions[] = $editStatus->getNewRevision();
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

	public static function provideBetweenMethodNames() {
		yield [ 'getRevisionIdsBetween' ];
		yield [ 'countRevisionsBetween' ];
		yield [ 'countAuthorsBetween' ];
		yield [ 'getAuthorsBetween' ];
	}

	/**
	 * @dataProvider provideBetweenMethodNames
	 *
	 * @param string $method the name of the method to test
	 */
	public function testBetweenMethod_differentPages( $method ) {
		$page1 = $this->getTestPage( __METHOD__ );
		$page2 = $this->getTestPage( 'Other_Page' );
		$editStatus = $this->editPage( $page1, 'Revision 1' );
		$this->assertStatusGood( $editStatus, 'must create revision 1' );
		$rev1 = $editStatus->getNewRevision();
		$editStatus = $this->editPage( $page2, 'Revision 1' );
		$this->assertStatusGood( $editStatus, 'must create revision 1' );
		$rev2 = $editStatus->getNewRevision();

		$this->expectException( InvalidArgumentException::class );
		$this->getServiceContainer()->getRevisionStore()
			->{$method}( $page1->getId(), $rev1, $rev2 );
	}

	/**
	 * @dataProvider provideBetweenMethodNames
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
	 * @dataProvider provideGetFirstRevision
	 * @param callable $getPageIdentity
	 */
	public function testGetFirstRevision( $getPageIdentity ) {
		[ $pageTitle, $pageIdentity ] = $getPageIdentity();
		$editStatus = $this->editPage( $pageTitle->getPrefixedDBkey(), 'First Revision' );
		$this->assertStatusGood( $editStatus, 'must create first revision' );
		$firstRevId = $editStatus->getNewRevision()->getId();
		$editStatus = $this->editPage( $pageTitle->getPrefixedText(), 'New Revision' );
		$this->assertStatusGood( $editStatus, 'must create new revision' );
		$this->assertNotSame(
			$firstRevId,
			$editStatus->getNewRevision()->getId(),
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

	public static function provideGetFirstRevision() {
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

	public function testGetFirstRevision_nonexistent_page() {
		$this->assertNull(
			$this->getServiceContainer()
				->getRevisionStore()
				->getFirstRevision( $this->getNonexistingTestPage( __METHOD__ )->getTitle() )
		);
	}

	public static function provideInsertRevisionByAnonAssignsNewActor() {
		yield 'User' => [ '127.1.1.0', static function ( MediaWikiServices $services, string $ip ) {
			return $services->getUserFactory()->newAnonymous( $ip );
		} ];
		yield 'User identity, anon' => [ '127.1.1.1', static function ( MediaWikiServices $services, string $ip ) {
			return new UserIdentityValue( 0, $ip );
		} ];
	}

	/**
	 * @dataProvider provideInsertRevisionByAnonAssignsNewActor
	 */
	public function testInsertRevisionByAnonAssignsNewActor( string $ip, callable $userInitCallback ) {
		$this->disableAutoCreateTempUser();
		$user = $userInitCallback( $this->getServiceContainer(), $ip );

		$actorNormalization = $this->getServiceContainer()->getActorNormalization();
		$actorId = $actorNormalization->findActorId( $user, $this->getDb() );
		$this->assertNull( $actorId, 'New actor has no actor_id' );

		$page = $this->getTestPage();
		$rev = new MutableRevisionRecord( $page->getTitle() );
		$rev->setTimestamp( '20180101000000' )
			->setComment( CommentStoreComment::newUnsavedComment( 'test' ) )
			->setUser( $user )
			->setContent( SlotRecord::MAIN, new WikitextContent( 'Text' ) )
			->setPageId( $page->getId() );

		$return = $this->getServiceContainer()->getRevisionStore()->insertRevisionOn( $rev, $this->getDb() );
		$this->assertSame( $ip, $return->getUser()->getName() );

		$actorId = $actorNormalization->findActorId( $user, $this->getDb() );
		$this->assertNotNull( $actorId );
	}

	public function testGetTitle_successFromPageId() {
		$page = $this->getExistingTestPage();

		$store = $this->getServiceContainer()->getRevisionStore();
		$title = $store->getTitle( $page->getId(), 0, IDBAccessObject::READ_NORMAL );

		$this->assertTrue( $page->isSamePageAs( $title ) );
	}

	public function testGetTitle_successFromRevId() {
		$page = $this->getExistingTestPage();

		$store = $this->getServiceContainer()->getRevisionStore();
		$title = $store->getTitle( 0, $page->getLatest(), IDBAccessObject::READ_NORMAL );

		$this->assertTrue( $page->isSamePageAs( $title ) );
	}

	public function testGetTitle_failure() {
		$store = $this->getServiceContainer()->getRevisionStore();

		$this->expectException( RevisionAccessException::class );
		$store->getTitle( 113349857, 897234779, IDBAccessObject::READ_NORMAL );
	}

	public function testGetQueryInfo_NoSlotDataJoin() {
		$store = $this->getServiceContainer()->getRevisionStore();
		$queryInfo = $store->getQueryInfo();

		// with the new schema enabled, query info should not join the main slot info
		$this->assertArrayNotHasKey( 'a_slot_data', $queryInfo['tables'] );
		$this->assertArrayNotHasKey( 'a_slot_data', $queryInfo['joins'] );
	}

	public function testInsertRevisionOn_T202032() {
		// This test only makes sense for MySQL
		if ( $this->getDb()->getType() !== 'mysql' ) {
			$this->assertTrue( true );
			return;
		}

		// NOTE: must be done before checking MAX(rev_id)
		$page = $this->getTestPage();

		$maxRevId = $this->getDb()->newSelectQueryBuilder()
			->select( 'MAX(rev_id)' )
			->from( 'revision' )
			->fetchField();

		// Construct a slot row that will conflict with the insertion of the next revision ID,
		// to emulate the failure mode described in T202032. Nothing will ever read this row,
		// we just need it to trigger a primary key conflict.
		$this->getDb()->newInsertQueryBuilder()
			->insertInto( 'slots' )
			->row( [
				'slot_revision_id' => $maxRevId + 1,
				'slot_role_id' => 1,
				'slot_content_id' => 0,
				'slot_origin' => 0
			] )
			->caller( __METHOD__ )
			->execute();

		$rev = new MutableRevisionRecord( $page->getTitle() );
		$rev->setTimestamp( '20180101000000' )
			->setComment( CommentStoreComment::newUnsavedComment( 'test' ) )
			->setUser( $this->getTestUser()->getUser() )
			->setContent( SlotRecord::MAIN, new WikitextContent( 'Text' ) )
			->setPageId( $page->getId() );

		$store = $this->getServiceContainer()->getRevisionStore();
		$return = $store->insertRevisionOn( $rev, $this->getDb() );

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

	public function testGetContentBlobsForBatch_error() {
		$page1 = $this->getTestPage();
		$text = __METHOD__ . 'b-ä';
		$editStatus = $this->editPage( $page1, $text . '1' );
		$this->assertStatusGood( $editStatus, 'must create revision 1' );
		$revRecord1 = $editStatus->getNewRevision();

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
		$this->assertStatusWarning( 'internalerror_info', $result );

		$records = $result->getValue();
		$this->assertArrayHasKey( $revRecord1->getId(), $records );

		$mainRow = $records[$revRecord1->getId()][SlotRecord::MAIN];
		$this->assertNull( $mainRow->blob_data );
		$this->assertStatusMessagesExactly(
			StatusValue::newGood()
				->warning( 'internalerror_info', 'oops!' )
				->warning( 'internalerror_info', "Couldn't find blob data for rev {$revRecord1->getId()}" ),
			$result
		);
	}

	public function testGetContentBlobsForBatchUsesGetBlobBatch() {
		$page1 = $this->getTestPage();
		$text = __METHOD__ . 'b-ä';
		$editStatus = $this->editPage( $page1, $text . '1' );
		$this->assertStatusGood( $editStatus, 'must create revision 1' );
		$revRecord1 = $editStatus->getNewRevision();

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

	public function testNewRevisionsFromBatch_error() {
		$page = $this->getTestPage();
		$text = __METHOD__ . 'b-ä';
		$revRecord1 = $page->doUserEditContent(
			new WikitextContent( $text . '1' ),
			$this->getTestUser()->getUser(),
			__METHOD__ . 'b'
		)->getNewRevision();

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
		$this->assertStatusWarning( 'internalerror_info', $result );
		$records = $result->getValue();
		$this->assertRevisionRecordsEqual( $revRecord1, $records[$revRecord1->getId()] );
		$this->assertSame( $text . '1',
			$records[$revRecord1->getId()]->getContent( SlotRecord::MAIN )->serialize() );
		$this->assertEquals( $page->getTitle()->getDBkey(),
			$records[$revRecord1->getId()]->getPageAsLinkTarget()->getDBkey() );
		$this->assertNull( $records[$invalidRow->rev_id] );
		$this->assertStatusMessagesExactly(
			StatusValue::newGood()
				->warning( 'internalerror_info', "Couldn't find slots for rev 100500" ),
			$result
		);
	}

	public function testNewRevisionFromBatchUsesGetBlobBatch() {
		$page1 = $this->getTestPage();
		$text = __METHOD__ . 'b-ä';
		$editStatus = $this->editPage( $page1, $text . '1' );
		$this->assertStatusGood( $editStatus, 'must create revision 1' );
		$revRecord1 = $editStatus->getNewRevision();

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

	public function testFindIdenticalRevision() {
		// Prepare a page with 3 revisions
		$page = $this->getExistingTestPage( __METHOD__ );
		$status = $this->editPage( $page, 'Content 1' );
		$this->assertStatusGood( $status, 'edit 1' );
		$originalRev = $status->getNewRevision();

		$this->assertStatusGood( $this->editPage( $page, 'Content 2' ), 'edit 2' );

		$status = $this->editPage( $page, 'Content 1' );
		$this->assertStatusGood( $status, 'edit 3' );
		$latestRev = $status->getNewRevision();

		$store = $this->getServiceContainer()->getRevisionStore();

		$this->assertNull( $store->findIdenticalRevision( $latestRev, 0 ) );
		$this->assertNull( $store->findIdenticalRevision( $latestRev, 1 ) );
		$foundRev = $store->findIdenticalRevision( $latestRev, 1000 );
		$this->assertSame( $originalRev->getId(), $foundRev->getId() );
	}
}
