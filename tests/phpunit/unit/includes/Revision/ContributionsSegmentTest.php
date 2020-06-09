<?php

namespace MediaWiki\Tests\Revision;

use MediaWiki\Revision\ContributionsSegment;
use MediaWiki\Storage\MutableRevisionRecord;
use Title;

class ContributionsSegmentTest extends \MediaWikiUnitTestCase {

	public function provideContruction() {
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
	 * @dataProvider provideContruction
	 * @covers \MediaWiki\Revision\ContributionsSegment
	 */
	public function testConstruction( $flags ) {
		$mockTitle = $this->createNoOpMock( Title::class, [ 'getArticleID' ] );
		$mockTitle->method( 'getArticleID' )->willReturn( 1 );
		$revisionRecords = [ new MutableRevisionRecord( $mockTitle ), new MutableRevisionRecord( $mockTitle ) ];
		$before = 'before';
		$after = 'after';

		$contributionsSegment = new ContributionsSegment( $revisionRecords, $before, $after, $flags );
		$this->assertSame( $revisionRecords, $contributionsSegment->getRevisions() );
		$this->assertSame( $before, $contributionsSegment->getBefore() );
		$this->assertSame( $after, $contributionsSegment->getAfter() );
		$this->assertSame( $flags['newest'] ?? false, $contributionsSegment->isNewest() );
		$this->assertSame( $flags['oldest'] ?? false, $contributionsSegment->isOldest() );
	}
}
