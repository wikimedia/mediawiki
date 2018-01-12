<?php

namespace MediaWiki\Tests\Storage;

use CommentStoreComment;
use Exception;
use InvalidArgumentException;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\MediaWikiServices;
use MediaWiki\Storage\IncompleteRevisionException;
use MediaWiki\Storage\MutableRevisionRecord;
use MediaWiki\Storage\RevisionRecord;
use MediaWiki\Storage\RevisionTitleLookup;
use MediaWiki\Storage\SingleContentRevisionStore;
use MediaWiki\Storage\SlotRecord;
use MediaWiki\Storage\SqlBlobStore;
use MediaWikiTestCase;
use Revision;
use Title;
use Wikimedia\Rdbms\LoadBalancer;
use WikiPage;
use WikitextContent;

class SingleContentRevisionStoreTest extends MediaWikiTestCase {

	/**
	 * @param LoadBalancer $loadBalancer
	 * @param SqlBlobStore $blobStore
	 *
	 * @return SingleContentRevisionStore
	 */
	private function getRevisionStore(
		$loadBalancer = null,
		$blobStore = null
	) {
		$loadBalancer = $loadBalancer ? $loadBalancer : $this->getMockLoadBalancer();
		return new SingleContentRevisionStore(
			$loadBalancer,
			$blobStore ? $blobStore : $this->getMockSqlBlobStore(),
			new RevisionTitleLookup( $loadBalancer )
		);
	}

	/**
	 * @return \PHPUnit_Framework_MockObject_MockObject|LoadBalancer
	 */
	private function getMockLoadBalancer() {
		return $this->getMockBuilder( LoadBalancer::class )
			->disableOriginalConstructor()->getMock();
	}

	/**
	 * @return \PHPUnit_Framework_MockObject_MockObject|SqlBlobStore
	 */
	private function getMockSqlBlobStore() {
		return $this->getMockBuilder( SqlBlobStore::class )
			->disableOriginalConstructor()->getMock();
	}

	private function assertLinkTargetsEqual( LinkTarget $l1, LinkTarget $l2 ) {
		$this->assertEquals( $l1->getDBkey(), $l2->getDBkey() );
		$this->assertEquals( $l1->getNamespace(), $l2->getNamespace() );
		$this->assertEquals( $l1->getFragment(), $l2->getFragment() );
		$this->assertEquals( $l1->getInterwiki(), $l2->getInterwiki() );
	}

	/**
	 * @covers \MediaWiki\Storage\SingleContentRevisionStore::getContentHandlerUseDB
	 * @covers \MediaWiki\Storage\SingleContentRevisionStore::setContentHandlerUseDB
	 */
	public function testGetSetContentHandlerDb() {
		$store = $this->getRevisionStore();
		$this->assertTrue( $store->getContentHandlerUseDB() );
		$store->setContentHandlerUseDB( false );
		$this->assertFalse( $store->getContentHandlerUseDB() );
		$store->setContentHandlerUseDB( true );
		$this->assertTrue( $store->getContentHandlerUseDB() );
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
	 * @covers \MediaWiki\Storage\SingleContentRevisionStore::insertRevisionOn
	 */
	public function testInsertRevisionOn_successes( Title $title, array $revDetails = [] ) {
		$rev = $this->getRevisionRecordFromDetailsArray( $title, $revDetails );

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$return = $store->insertRevisionOn( $rev, wfGetDB( DB_MASTER ) );

		$this->assertLinkTargetsEqual( $title, $return->getPageAsLinkTarget() );
		$this->assertRevisionRecordsEqual( $rev, $return );
	}

	/**
	 * @covers \MediaWiki\Storage\SingleContentRevisionStore::insertRevisionOn
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
	 * @covers \MediaWiki\Storage\SingleContentRevisionStore::insertRevisionOn
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
			$this->assertEquals( $r1->getSlot( $role ), $r2->getSlot( $role ) );
			$this->assertEquals( $r1->getContent( $role ), $r2->getContent( $role ) );
		}
		foreach ( [
			RevisionRecord::DELETED_TEXT,
			RevisionRecord::DELETED_COMMENT,
			RevisionRecord::DELETED_USER,
			RevisionRecord::DELETED_RESTRICTED,
		] as $field
		) {
			$this->assertEquals( $r1->isDeleted( $field ), $r2->isDeleted( $field ) );
		}
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

	/**
	 * @covers \MediaWiki\Storage\SingleContentRevisionStore::getRcIdIfUnpatrolled
	 */
	public function testGetRcIdIfUnpatrolled_returnsRecentChangesId() {
		$page = WikiPage::factory( Title::newFromText( 'UTPage' ) );
		$status = $page->doEditContent( new WikitextContent( __METHOD__ ), __METHOD__ );
		/** @var Revision $rev */
		$rev = $status->value['revision'];

		$lookup = MediaWikiServices::getInstance()->getRevisionLookup();
		$revisionRecord = $lookup->getRevisionById( $rev->getId() );

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$result = $store->getRcIdIfUnpatrolled( $revisionRecord );

		$this->assertGreaterThan( 0, $result );
		$this->assertSame(
			$page->getRevision()->getRecentChange()->getAttribute( 'rc_id' ),
			$result
		);
	}

	/**
	 * @covers \MediaWiki\Storage\SingleContentRevisionStore::getRcIdIfUnpatrolled
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

		$lookup = MediaWikiServices::getInstance()->getRevisionLookup();
		$revisionRecord = $lookup->getRevisionById( $rev->getId() );
		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$result = $store->getRcIdIfUnpatrolled( $revisionRecord );

		$this->assertSame( 0, $result );
	}

	/**
	 * @covers \MediaWiki\Storage\SingleContentRevisionStore::getRecentChange
	 */
	public function testGetRecentChange() {
		$page = WikiPage::factory( Title::newFromText( 'UTPage' ) );
		$content = new WikitextContent( __METHOD__ );
		$status = $page->doEditContent( $content, __METHOD__ );
		/** @var Revision $rev */
		$rev = $status->value['revision'];

		$lookup = MediaWikiServices::getInstance()->getRevisionLookup();
		$revRecord = $lookup->getRevisionById( $rev->getId() );

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$recentChange = $store->getRecentChange( $revRecord );

		$this->assertEquals( $rev->getId(), $recentChange->getAttribute( 'rc_this_oldid' ) );
		$this->assertEquals( $rev->getRecentChange(), $recentChange );
	}

	/**
	 * @covers \MediaWiki\Storage\SingleContentRevisionStore::listRevisionSizes
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
	 * @covers \MediaWiki\Storage\SingleContentRevisionStore::getTimestampFromId
	 */
	public function testGetTimestampFromId_found() {
		$page = WikiPage::factory( Title::newFromText( 'UTPage' ) );
		/** @var Revision $rev */
		$rev = $page->doEditContent(
			new WikitextContent( __METHOD__ ), __METHOD__
		)->value['revision'];

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$result = $store->getTimestampFromId(
			$page->getTitle(),
			$rev->getId()
		);

		$this->assertSame( $rev->getTimestamp(), $result );
	}

	/**
	 * @covers \MediaWiki\Storage\SingleContentRevisionStore::getTimestampFromId
	 */
	public function testGetTimestampFromId_notFound() {
		$page = WikiPage::factory( Title::newFromText( 'UTPage' ) );
		/** @var Revision $rev */
		$rev = $page->doEditContent(
			new WikitextContent( __METHOD__ ), __METHOD__
		)->value['revision'];

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$result = $store->getTimestampFromId(
			$page->getTitle(),
			$rev->getId() + 1
		);

		$this->assertFalse( $result );
	}

	/**
	 * @covers \MediaWiki\Storage\SingleContentRevisionStore::countRevisionsByPageId
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
	 * @covers \MediaWiki\Storage\SingleContentRevisionStore::countRevisionsByTitle
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
	 * @covers \MediaWiki\Storage\SingleContentRevisionStore::userWasLastToEdit
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
	 * @covers \MediaWiki\Storage\SingleContentRevisionStore::userWasLastToEdit
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

}
