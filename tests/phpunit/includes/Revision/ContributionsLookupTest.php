<?php

namespace MediaWiki\Tests\Revision;

use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\ContributionsLookup;
use MediaWiki\Revision\ContributionsSegment;
use MediaWiki\Revision\RevisionRecord;
use MediaWikiIntegrationTestCase;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @covers \MediaWiki\Revision\ContributionsLookup
 *
 * @group Database
 */
class ContributionsLookupTest extends MediaWikiIntegrationTestCase {

	/**
	 * @covers \MediaWiki\Revision\ContributionsLookup::getRevisionsByUser()
	 */
	public function testGetListOfRevisionsByUserIdentity() {
		$clock = (int)ConvertibleTimestamp::now( TS_UNIX );

		ConvertibleTimestamp::setFakeTime( function () use ( &$clock ) {
			return ++$clock;
		} );

		$user = $this->getTestUser()->getUser();
		$revisionStore = MediaWikiServices::getInstance()->getRevisionStore();
		$contributionsLookup = new ContributionsLookup( $revisionStore );

		// Sanity check
		$contribs = $contributionsLookup->getRevisionsByUser( $user, 10 )->getRevisions();
		$this->assertCount( 0, $contribs );
		/** @var RevisionRecord $rev1 */
		/** @var RevisionRecord $rev2 */
		$rev0 = $this->editPage( __METHOD__, 'Lorem Ipsum 0', 'test', NS_TALK, $user )->getValue()['revision-record'];
		$rev1 = $this->editPage( __METHOD__, 'Lorem Ipsum 1', 'test', NS_MAIN, $user )->getValue()['revision-record'];
		$rev2 = $this->editPage( __METHOD__, 'Lorem Ipsum 2', 'test', NS_TALK, $user )->getValue()['revision-record'];

		$contribs = $contributionsLookup->getRevisionsByUser( $user, 2 )->getRevisions();

		$this->assertCount( 2, $contribs );
		$this->assertSame( $rev2->getId(), $contribs[0]->getId() );
		$this->assertSame( $rev1->getId(), $contribs[1]->getId() );
		$this->assertInstanceOf( RevisionRecord::class, $contribs[0] );
		$this->assertEquals( $user->getName(), $contribs[0]->getUser()->getName() );
		$this->assertEquals(
			$rev2->getPageAsLinkTarget()->getPrefixedDBkey(),
			$contribs[0]->getPageAsLinkTarget()->getPrefixedDBkey()
		);
		$this->assertEquals(
			$rev1->getPageAsLinkTarget()->getPrefixedDBkey(),
			$contribs[1]->getPageAsLinkTarget()->getPrefixedDBkey()
		);
		// Desc order comes back from db query
	}

	/**
	 * @covers \MediaWiki\Revision\ContributionsLookup::getRevisionsByUser()
	 */
	public function testGetSegmentChain() {
		$user = $this->getTestUser()->getUser();
		$revisionStore = MediaWikiServices::getInstance()->getRevisionStore();
		$contributionsLookup = new ContributionsLookup( $revisionStore );

		// Sanity check
		$contribs = $contributionsLookup->getRevisionsByUser( $user, 2 )->getRevisions();
		$this->assertCount( 0, $contribs );

		$clock = (int)ConvertibleTimestamp::now( TS_UNIX );
		ConvertibleTimestamp::setFakeTime( function () use ( &$clock ) {
			return ++$clock;
		} );

		/** @var RevisionRecord $rev1 */
		/** @var RevisionRecord $rev2 */

		$rev1 = $this->editPage( __METHOD__ . '_1', 'Lorem Ipsum 1', 'test', NS_MAIN, $user )
			->getValue()['revision-record'];
		$rev2 = $this->editPage( __METHOD__ . '_2', 'Lorem Ipsum 2', 'test', NS_TALK, $user )
			->getValue()['revision-record'];
		$rev3 = $this->editPage( __METHOD__ . '_2', 'Lorem Ipsum 3', 'test', NS_MAIN, $user )
			->getValue()['revision-record'];
		$rev4 = $this->editPage( __METHOD__ . '_1', 'Lorem Ipsum 4', 'test', NS_TALK, $user )
			->getValue()['revision-record'];

		// TODO Explore using hard-coded rev. here?
		$segment1 = $contributionsLookup->getRevisionsByUser( $user, 2 );
		$this->assertInstanceOf( ContributionsSegment::class, $segment1 );
		$this->assertCount( 2, $segment1->getRevisions() );
		$this->assertNotNull( $segment1->getBefore() );
		$this->assertFalse( $segment1->isOldest() );

		$segment2 = $contributionsLookup->getRevisionsByUser( $user, 2, $segment1->getBefore() );
		$this->assertCount( 2, $segment2->getRevisions() );
		$this->assertNull( $segment2->getBefore() );
		$this->assertTrue( $segment2->isOldest() );

		$expectedSegmentOneRevisions = [ $rev4, $rev3 ];
		$expectedSegmentTwoRevisions = [ $rev2, $rev1 ];

		$this->assertSegmentRevisions( $expectedSegmentOneRevisions, $segment1 );
		$this->assertSegmentRevisions( $expectedSegmentTwoRevisions, $segment2 );
	}

	/**
	 * @param RevisionRecord[] $expectedRevisions
	 * @param ContributionsSegment $segmentObject
	 */
	private function assertSegmentRevisions( $expectedRevisions, $segmentObject ) {
		/** @var RevisionRecord[] $actualSegmentOneRevisions */
		$actualSegmentOneRevisions = array_values( $segmentObject->getRevisions() );
		foreach ( $expectedRevisions as $idx => $rev ) {
			$this->assertSame( $rev->getId(), $actualSegmentOneRevisions[$idx]->getId() );
		}
	}

}
