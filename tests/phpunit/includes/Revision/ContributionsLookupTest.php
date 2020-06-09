<?php

namespace MediaWiki\Tests\Revision;

use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\ContributionsLookup;
use MediaWiki\Revision\ContributionsSegment;
use MediaWiki\Revision\RevisionRecord;
use MediaWikiIntegrationTestCase;
use User;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @covers \MediaWiki\Revision\ContributionsLookup
 *
 * @group Database
 */
class ContributionsLookupTest extends MediaWikiIntegrationTestCase {

	/**
	 * @var array Associative array mapping revision number (e.g. 1 for first revision made) to revision record
	 */
	private static $storedRevisions;

	/**
	 * @var \User
	 */
	private static $testUser;

	public function addDBDataOnce() {
		$user = $this->getTestUser()->getUser();

		$clock = (int)ConvertibleTimestamp::now( TS_UNIX );
		ConvertibleTimestamp::setFakeTime( function () use ( &$clock ) {
			return ++$clock;
		} );

		self::$testUser = $user;
		self::$storedRevisions[1] = $this->editPage( __METHOD__ . '_1', 'Lorem Ipsum 1', 'test', NS_MAIN, $user )
			->getValue()['revision-record'];
		self::$storedRevisions[2] = $this->editPage( __METHOD__ . '_2', 'Lorem Ipsum 2', 'test', NS_TALK, $user )
			->getValue()['revision-record'];
		self::$storedRevisions[3] = $this->editPage( __METHOD__ . '_2', 'Lorem Ipsum 3', 'test', NS_MAIN, $user )
			->getValue()['revision-record'];
		self::$storedRevisions[4] = $this->editPage( __METHOD__ . '_1', 'Lorem Ipsum 4', 'test', NS_TALK, $user )
			->getValue()['revision-record'];

		ConvertibleTimestamp::setFakeTime( false );
	}

	/**
	 * @covers \MediaWiki\Revision\ContributionsLookup::getRevisionsByUser()
	 */
	public function testGetListOfRevisionsByUserIdentity() {
		$clock = (int)ConvertibleTimestamp::now( TS_UNIX );

		ConvertibleTimestamp::setFakeTime( function () use ( &$clock ) {
			return ++$clock;
		} );

		$revisionStore = MediaWikiServices::getInstance()->getRevisionStore();
		$contributionsLookup = new ContributionsLookup( $revisionStore );
		$performer = self::$testUser;

		$contribs =
			$contributionsLookup->getRevisionsByUser( self::$testUser, 2, $performer )->getRevisions();

		$this->assertCount( 2, $contribs );
		$this->assertSame( self::$storedRevisions[4]->getId(), $contribs[0]->getId() );
		$this->assertSame( self::$storedRevisions[3]->getId(), $contribs[1]->getId() );
		$this->assertInstanceOf( RevisionRecord::class, $contribs[0] );
		$this->assertEquals( self::$testUser->getName(), $contribs[0]->getUser()->getName() );
		$this->assertEquals(
			self::$storedRevisions[4]->getPageAsLinkTarget()->getPrefixedDBkey(),
			$contribs[0]->getPageAsLinkTarget()->getPrefixedDBkey()
		);
		$this->assertEquals(
			self::$storedRevisions[3]->getPageAsLinkTarget()->getPrefixedDBkey(),
			$contribs[1]->getPageAsLinkTarget()->getPrefixedDBkey()
		);
		// Desc order comes back from db query
	}

	/**
	 * @covers \MediaWiki\Revision\ContributionsLookup::getRevisionsByUser()
	 */
	public function testGetSegmentChain() {
		$revisionStore = MediaWikiServices::getInstance()->getRevisionStore();
		$contributionsLookup = new ContributionsLookup( $revisionStore );

		$segment1 = $contributionsLookup->getRevisionsByUser( self::$testUser, 2, self::$testUser );
		$this->assertInstanceOf( ContributionsSegment::class, $segment1 );
		$this->assertCount( 2, $segment1->getRevisions() );
		$this->assertNotNull( $segment1->getAfter() );
		$this->assertNotNull( $segment1->getBefore() );
		$this->assertFalse( $segment1->isOldest() );

		$segment2 =
			$contributionsLookup->getRevisionsByUser( self::$testUser, 2, self::$testUser, $segment1->getBefore() );
		$this->assertCount( 2, $segment2->getRevisions() );
		$this->assertNotNull( $segment2->getAfter() );
		$this->assertNull( $segment2->getBefore() );
		$this->assertTrue( $segment2->isOldest() );

		$segment3 =
			$contributionsLookup->getRevisionsByUser( self::$testUser, 2, self::$testUser, $segment2->getAfter() );
		$this->assertInstanceOf( ContributionsSegment::class, $segment3 );
		$this->assertCount( 2, $segment3->getRevisions() );
		$this->assertNotNull( $segment3->getAfter() );
		$this->assertNotNull( $segment3->getBefore() );
		$this->assertFalse( $segment3->isOldest() );

		$expectedSegmentOneRevisions = [ self::$storedRevisions[4], self::$storedRevisions[3] ];
		$expectedSegmentTwoRevisions = [ self::$storedRevisions[2], self::$storedRevisions[1] ];

		$this->assertSegmentRevisions( $expectedSegmentOneRevisions, $segment1 );
		$this->assertSegmentRevisions( $expectedSegmentTwoRevisions, $segment2 );
		$this->assertSegmentRevisions( $expectedSegmentOneRevisions, $segment3 );
	}

	/**
	 * @param RevisionRecord[] $expectedRevisions
	 * @param ContributionsSegment $segmentObject
	 */
	private function assertSegmentRevisions( $expectedRevisions, $segmentObject ) {
		/** @var RevisionRecord[] $actualRevisions */
		$actualRevisions = array_values( $segmentObject->getRevisions() );
		$this->assertSameSize( $expectedRevisions, $actualRevisions );
		foreach ( $expectedRevisions as $idx => $rev ) {
			$this->assertSame( $rev->getId(), $actualRevisions[$idx]->getId() );
		}
	}

	public function provideBadSegmentMarker() {
	 yield [ '' ];
	 yield [ '|' ];
	 yield [ '0' ];
	 yield [ '9' ];
	 yield [ 'x|0' ];
	 yield [ 'x|9' ];
	 yield [ 'x|x' ];
	}

	/**
	 * @dataProvider provideBadSegmentMarker
	 */
	public function testBadSegmentMarkerReturnsLatestSegment( $segment ) {
		$revisionStore = MediaWikiServices::getInstance()->getRevisionStore();
		$contributionsLookup = new ContributionsLookup( $revisionStore );
		$expectedRevisions = [ self::$storedRevisions[4], self::$storedRevisions[3] ];
		$segment = $contributionsLookup->getRevisionsByUser( self::$testUser, 2, self::$testUser, $segment );
		$this->assertSegmentRevisions( $expectedRevisions, $segment );
	}

	public function testPermissionChecksAreApplied() {
		$editingUser = self::$testUser;
		$sysop = $this->getTestUser( [ 'sysop', 'suppress' ] )->getUser();
		$anon = User::newFromId( 0 );

		$revisionStore = MediaWikiServices::getInstance()->getRevisionStore();
		$contributionsLookup = new ContributionsLookup( $revisionStore );

		$revIds = [ self::$storedRevisions[1]->getId(), self::$storedRevisions[2]->getId() ];
		$this->db->update(
			'revision',
			[ 'rev_deleted' => RevisionRecord::DELETED_USER ],
			[ 'rev_id' => $revIds ],
			__METHOD__
		);

		// sanity
		$this->assertSame( 2, $this->db->affectedRows() );

		// anons should not see suppressed contribs
		$contribs = $contributionsLookup->getRevisionsByUser( $editingUser, 10, $anon );
		$this->assertCount( 2, $contribs->getRevisions() );

		// sysop also gets suppressed contribs
		$contribs = $contributionsLookup->getRevisionsByUser( $editingUser, 10, $sysop );
		$this->assertCount( 4, $contribs->getRevisions() );
	}

}
