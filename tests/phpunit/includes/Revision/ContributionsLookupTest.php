<?php

namespace MediaWiki\Tests\Revision;

use ChangeTags;
use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\SimpleAuthority;
use MediaWiki\Permissions\UltimateAuthority;
use MediaWiki\Revision\ContributionsLookup;
use MediaWiki\Revision\ContributionsSegment;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\User\UserIdentityValue;
use MediaWikiIntegrationTestCase;
use Message;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @covers \MediaWiki\Revision\ContributionsLookup
 *
 * @group Database
 */
class ContributionsLookupTest extends MediaWikiIntegrationTestCase {

	/**
	 * @var RevisionRecord[] Associative array mapping revision number (e.g. 1 for first
	 *      revision made) to revision record
	 */
	private static $storedRevisions;

	/**
	 * @var \User
	 */
	private static $testUser;

	/**
	 * @var Authority
	 */
	private static $performer;

	/**
	 * @var int[][]
	 */
	private static $storedDeltas;

	/**
	 * @var string[][]
	 */
	private static $storedTags;

	private const TAG1 = 'test-ContributionsLookup-1';
	private const TAG2 = 'test-ContributionsLookup-2';
	private const TAG3 = 'test-ContributionsLookup-3';

	private const TAG_DISPLAY = 'ContributionsLookup Tag Display Text';

	protected function setUp(): void {
		parent::setUp();

		// Work around T256006
		ChangeTags::$avoidReopeningTablesForTesting = true;

		// MessageCache needs to be explicitly enabled to load changetag display text.
		$this->getServiceContainer()->getMessageCache()->enable();
	}

	protected function tearDown(): void {
		ChangeTags::$avoidReopeningTablesForTesting = false;
		$this->getServiceContainer()->getMessageCache()->disable();

		parent::tearDown();
	}

	public function addDBDataOnce() {
		$user = $this->getTestUser()->getUser();

		$clock = (int)ConvertibleTimestamp::now( TS_UNIX );
		ConvertibleTimestamp::setFakeTime( static function () use ( &$clock ) {
			return ++$clock;
		} );

		self::$testUser = $user;
		self::$performer = new UltimateAuthority( $user );

		$revisionText = [ 1 => 'Lorem', 2 => 'Lorem Ipsum', 3 => 'Lor', 4 => 'Lorem' ];
		// maps $storedRevision revision number to delta of revision length from its parent
		self::$storedDeltas = [ 1 => 5, 2 => 11, 3 => -2, 4 => -6 ];

		self::$storedRevisions[1] = $this->editPage( __METHOD__ . '_1', $revisionText[1], 'test', NS_MAIN, $user )
			->getNewRevision();
		self::$storedRevisions[2] = $this->editPage( __METHOD__ . '_2', $revisionText[2], 'test', NS_TALK, $user )
			->getNewRevision();
		self::$storedRevisions[3] = $this->editPage( __METHOD__ . '_1', $revisionText[3], 'test', NS_MAIN, $user )
			->getNewRevision();
		self::$storedRevisions[4] = $this->editPage( __METHOD__ . '_2', $revisionText[4], 'test', NS_TALK, $user )
			->getNewRevision();

		ChangeTags::defineTag( self::TAG1 );
		ChangeTags::defineTag( self::TAG2 );

		self::$storedTags = [
			1 => [],
			2 => [ self::TAG1, self::TAG2 ],
			3 => [ self::TAG2 ],
			4 => [],
		];

		foreach ( self::$storedTags as $idx => $tags ) {
			if ( !$tags ) {
				continue;
			}
			$revId = self::$storedRevisions[$idx]->getId();
			ChangeTags::addTags( $tags, null, $revId );
		}

		// Pages at MediaWiki:tag-{tag_name} override any tag display text
		$this->insertPage( "tag-" . self::TAG1, "''" . self::TAG_DISPLAY . "''", NS_MEDIAWIKI );
		$this->insertPage( "tag-" . self::TAG2, "''" . self::TAG_DISPLAY . "''", NS_MEDIAWIKI );

		// Add a 3rd disabled tag (empty string as display name equates to disabled tag)
		ChangeTags::addTags( [ self::TAG3 ], null, $revId );
		$this->insertPage( "tag-" . self::TAG3, '', NS_MEDIAWIKI );

		ConvertibleTimestamp::setFakeTime( false );
	}

	/**
	 * @return ContributionsLookup
	 */
	private function getContributionsLookup() {
		// Helper to simplify potential switch to unit tests
		return $this->getServiceContainer()->getContributionsLookup();
	}

	/**
	 * @covers \MediaWiki\Revision\ContributionsLookup::getContributions()
	 */
	public function testGetDeltas() {
		$contributionsLookup = $this->getContributionsLookup();

		$segment =
			$contributionsLookup->getContributions( self::$testUser, 10, self::$performer );

		// Contributions are returned in descending order.
		$revIds = array_map(
			static function ( RevisionRecord $rev ) {
				return $rev->getId();
			},
			$segment->getRevisions()
		);

		$this->assertEquals( self::$storedDeltas[ 4 ], $segment->getDeltaForRevision( $revIds[0] ) );
		$this->assertEquals( self::$storedDeltas[ 3 ], $segment->getDeltaForRevision( $revIds[1] ) );
		$this->assertEquals( self::$storedDeltas[ 2 ], $segment->getDeltaForRevision( $revIds[2] ) );
		$this->assertEquals( self::$storedDeltas[ 1 ], $segment->getDeltaForRevision( $revIds[3] ) );
	}

	/**
	 * @covers \MediaWiki\Revision\ContributionsLookup::getContributions()
	 */
	public function testGetUnknownDeltas() {
		$user = $this->getTestUser()->getUser();

		$rev1 = $this->editPage( __METHOD__ . '_1', 'foo', 'test', NS_MAIN, $user )
			->getNewRevision();
		$rev2 = $this->editPage( __METHOD__ . '_2', 'bar', 'test', NS_TALK, $user )
			->getNewRevision();

		// Parent of revision 1 is not in revision table (deleted)
		$this->db->update(
			'revision',
			[ 'rev_parent_id' => $rev2->getId() + 100 ],
			[ 'rev_id' => $rev1->getId() ],
			__METHOD__
		);
		// Parent of revision 2 is unknown
		$this->db->update(
			'revision',
			[ 'rev_parent_id' => null ],
			[ 'rev_id' => $rev2->getId() ],
			__METHOD__
		);

		$contributionsLookup = $this->getContributionsLookup();

		$segment =
			$contributionsLookup->getContributions( $user, 10, self::$performer );

		$this->assertNull( $segment->getDeltaForRevision( $rev1->getId() ) );
		$this->assertNull( $segment->getDeltaForRevision( $rev2->getId() ) );
	}

	/**
	 * @covers \MediaWiki\Revision\ContributionsLookup::getContributions()
	 */
	public function testGetContributionsByUserIdentity() {
		$contributionsLookup = $this->getContributionsLookup();

		$segment =
			$contributionsLookup->getContributions( self::$testUser, 2, self::$performer );

		// Desc order comes back from db query
		$this->assertSegmentRevisions( [ 4, 3 ], $segment );
	}

	/**
	 * @covers \MediaWiki\Revision\ContributionsLookup::getContributions()
	 */
	public function testGetListOfContributions_revisionOnly() {
		$this->setTemporaryHook( 'ContribsPager::reallyDoQuery', function ( &$data ) {
			$this->fail( 'ContribsPager::reallyDoQuery was not expected to be called' );
		} );

		$contributionsLookup = $this->getContributionsLookup();

		$segment =
			$contributionsLookup->getContributions( self::$testUser, 2, self::$performer );

		// Desc order comes back from db query
		$this->assertSegmentRevisions( [ 4, 3 ], $segment );
	}

	/**
	 * @covers \MediaWiki\Revision\ContributionsLookup::getContributions()
	 */
	public function testGetContributionsFilteredByTag() {
		$contributionsLookup = $this->getContributionsLookup();

		$target = self::$testUser;

		$segment1 =
			$contributionsLookup->getContributions( $target, 10, self::$performer, '', self::TAG1 );

		$this->assertSegmentRevisions( [ 2 ], $segment1 );
		$this->assertTrue( $segment1->isOldest() );

		$segment2 =
			$contributionsLookup->getContributions( $target, 10, self::$performer, '', self::TAG2 );

		$this->assertSegmentRevisions( [ 3, 2 ], $segment2 );
		$this->assertTrue( $segment1->isOldest() );

		$segment0 =
			$contributionsLookup->getContributions( $target, 10, self::$performer, '', 'not-a-tag' );

		$this->assertSame( [], $segment0->getRevisions() );
	}

	/**
	 * @covers \MediaWiki\Revision\ContributionsLookup::getContributions()
	 */
	public function testGetSegmentChain() {
		$contributionsLookup = $this->getContributionsLookup();

		$segment1 = $contributionsLookup->getContributions( self::$testUser, 2, self::$performer );
		$this->assertInstanceOf( ContributionsSegment::class, $segment1 );
		$this->assertCount( 2, $segment1->getRevisions() );
		$this->assertNotNull( $segment1->getAfter() );
		$this->assertNotNull( $segment1->getBefore() );
		$this->assertFalse( $segment1->isOldest() );

		$segment2 =
			$contributionsLookup->getContributions( self::$testUser, 2, self::$performer, $segment1->getBefore() );
		$this->assertCount( 2, $segment2->getRevisions() );
		$this->assertNotNull( $segment2->getAfter() );
		$this->assertNull( $segment2->getBefore() );
		$this->assertTrue( $segment2->isOldest() );

		$segment3 =
			$contributionsLookup->getContributions( self::$testUser, 2, self::$performer, $segment2->getAfter() );
		$this->assertInstanceOf( ContributionsSegment::class, $segment3 );
		$this->assertCount( 2, $segment3->getRevisions() );
		$this->assertNotNull( $segment3->getAfter() );
		$this->assertNotNull( $segment3->getBefore() );
		$this->assertFalse( $segment3->isOldest() );

		$this->assertSegmentRevisions( [ 4, 3 ], $segment1 );
		$this->assertSegmentRevisions( [ 2, 1 ], $segment2 );
		$this->assertSegmentRevisions( [ 4, 3 ], $segment3 );
	}

	/**
	 * @param int[] $expectedRevisions A list of indexes into self::$storedRevisions
	 * @param ContributionsSegment $segmentObject
	 */
	private function assertSegmentRevisions( $expectedRevisions, $segmentObject ) {
		$revisions = $segmentObject->getRevisions();

		$this->assertSameSize( $expectedRevisions, $revisions );

		foreach ( $expectedRevisions as $idx => $editNumber ) {
			$expected = self::$storedRevisions[$editNumber];
			$actual = $revisions[$idx];
			$this->assertSame( $expected->getId(), $actual->getId(), 'rev_id' );
			$this->assertSame( $expected->getPageId(), $actual->getPageId(), 'rev_page_id' );
			$this->assertTrue(
				$expected->getPageAsLinkTarget()->isSameLinkAs( $actual->getPageAsLinkTarget() ),
				'getPageAsLinkTarget'
			);

			$expectedUser = $expected->getUser( RevisionRecord::RAW )->getName();
			$actualUser = $actual->getUser( RevisionRecord::RAW )->getName();
			$this->assertSame( $expectedUser, $actualUser );

			$expectedTags = self::$storedTags[ $editNumber ];
			$this->assertRevisionTags( $expectedTags, $segmentObject, $actual );

			$expectedDelta = self::$storedDeltas[$editNumber];
			$actualDelta = $segmentObject->getDeltaForRevision( $actual->getId() );
			$this->assertSame( $expectedDelta, $actualDelta, 'delta' );
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
		$contributionsLookup = $this->getContributionsLookup();

		$segment = $contributionsLookup->getContributions( self::$testUser, 2, self::$performer, $segment );
		$this->assertSegmentRevisions( [ 4, 3 ], $segment );
	}

	/**
	 * @covers \MediaWiki\Revision\ContributionsLookup::getContributionCount()
	 */
	public function testGetCountOfContributionsByUserIdentity() {
		$contributionsLookup = $this->getContributionsLookup();

		$count = $contributionsLookup->getContributionCount( self::$testUser, self::$performer );
		$this->assertSame( count( self::$storedRevisions ), $count );
	}

	/**
	 * @covers \MediaWiki\Revision\ContributionsLookup::getContributionCount()
	 */
	public function testGetCountOfContributionsByUserAndTag() {
		$contributionsLookup = $this->getContributionsLookup();
		$count = $contributionsLookup->getContributionCount(
			self::$testUser,
			self::$performer,
			self::TAG2
		);
		$this->assertSame( 2, $count );
	}

	public function testPermissionChecksAreApplied() {
		$editingUser = self::$testUser;
		$dummyUser = new UserIdentityValue( 0, 'test' );

		$contributionsLookup = $this->getContributionsLookup();

		$revIds = [ self::$storedRevisions[1]->getId(), self::$storedRevisions[2]->getId() ];
		$this->db->update(
			'revision',
			[ 'rev_deleted' => RevisionRecord::DELETED_USER ],
			[ 'rev_id' => $revIds ],
			__METHOD__
		);

		$this->assertSame( 2, $this->db->affectedRows() );

		// anons should not see suppressed contribs
		$segment = $contributionsLookup->getContributions( $editingUser, 10,
			new SimpleAuthority( $dummyUser, [] ) );
		$this->assertSegmentRevisions( [ 4, 3 ], $segment );

		$count = $contributionsLookup->getContributionCount( $editingUser,
			new SimpleAuthority( $dummyUser, [] ) );
		$this->assertSame( 2, $count );

		// Actor with suppressrevision right should
		$segment = $contributionsLookup->getContributions( $editingUser, 10,
			new SimpleAuthority( $dummyUser, [ 'deletedhistory', 'suppressrevision' ] ) );
		$this->assertSegmentRevisions( [ 4, 3, 2, 1 ], $segment );

		$count = $contributionsLookup->getContributionCount( $editingUser,
			new SimpleAuthority( $dummyUser, [ 'deletedhistory', 'suppressrevision' ] ) );
		$this->assertSame( 4, $count );
	}

	/**
	 * @param array $expectedTags
	 * @param ContributionsSegment $segmentObject
	 * @param RevisionRecord $actual
	 */
	private function assertRevisionTags(
		array $expectedTags,
		ContributionsSegment $segmentObject,
		RevisionRecord $actual
	): void {
		$actualTags = $segmentObject->getTagsForRevision( $actual->getId() );

		// Tag 3 was disabled and should not be included in results
		$this->assertArrayNotHasKey( self::TAG3, $actualTags );
		foreach ( $actualTags as $tagName => $actualTag ) {
			$this->assertContains( $tagName, $expectedTags );
			$this->assertInstanceOf( Message::class, $actualTag );
			$this->assertEquals( "<i>" . self::TAG_DISPLAY . "</i>", $actualTag->parse() );
			$this->assertEquals( "''" . self::TAG_DISPLAY . "''", $actualTag->text() );
		}
	}
}
