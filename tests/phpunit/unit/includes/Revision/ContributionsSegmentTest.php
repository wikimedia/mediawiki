<?php

namespace MediaWiki\Tests\Revision;

use MediaWiki\Revision\ContributionsSegment;
use MediaWiki\Storage\MutableRevisionRecord;
use Title;

class ContributionsSegmentTest extends \MediaWikiUnitTestCase {

	public function provideFlags() {
		yield [
			[]
		];
		yield [
			[
				'newest' => false,
				'oldest' => true
			]
		];
		yield [
			[
				'newest' => true,
				'oldest' => false,
			]
		];
		yield [
			[
				'newest' => false,
				'oldest' => false
			]
		];
		yield [
			[
				'newest' => true,
				'oldest' => true,
			]
		];
		yield [
			[
				'oldest' => true,
			]
		];
		yield [
			[
				'newest' => true,
			]
		];
		yield [
			[
				'oldest' => false,
			]
		];
		yield [
			[
				'newest' => false,
			]
		];
	}

	/**
	 * @dataProvider provideFlags
	 * @covers \MediaWiki\Revision\ContributionsSegment
	 */
	public function testFlags( $flags ) {
		$contributionsSegment = new ContributionsSegment( [], [], null, null, [], $flags );
		$this->assertSame( $flags['newest'] ?? false, $contributionsSegment->isNewest() );
		$this->assertSame( $flags['oldest'] ?? false, $contributionsSegment->isOldest() );
	}

	/**
	 * @covers \MediaWiki\Revision\ContributionsSegment
	 */
	public function testConstruction() {
		$mockTitle = $this->createNoOpMock( Title::class, [ 'getArticleID' ] );
		$mockTitle->method( 'getArticleID' )->willReturn( 1 );
		$revisionRecords = [ new MutableRevisionRecord( $mockTitle ), new MutableRevisionRecord( $mockTitle ) ];
		$before = 'before';
		$after = 'after';
		$tags = [ 3 => [ 'frob' ] ];
		$deltas = [ 3 => -7, 5 => null ];

		$contributionsSegment =
			new ContributionsSegment( $revisionRecords, $tags, $before, $after, $deltas );
		$this->assertSame( $revisionRecords, $contributionsSegment->getRevisions() );
		$this->assertSame( $tags[3], $contributionsSegment->getTagsForRevision( 3 ) );
		$this->assertSame( [], $contributionsSegment->getTagsForRevision( 17 ) );
		$this->assertSame( $before, $contributionsSegment->getBefore() );
		$this->assertSame( $after, $contributionsSegment->getAfter() );
		$this->assertFalse( $contributionsSegment->isNewest(), 'isNewest' );
		$this->assertFalse( $contributionsSegment->isOldest(), 'isOldest' );
		$this->assertSame( $deltas[3], $contributionsSegment->getDeltaForRevision( 3 ) );
		$this->assertNull( $contributionsSegment->getDeltaForRevision( 5 ) );
		$this->assertNull( $contributionsSegment->getDeltaForRevision( 77 ) );
	}
}
