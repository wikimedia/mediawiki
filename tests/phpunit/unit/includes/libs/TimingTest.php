<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @author Ori Livneh <ori@wikimedia.org>
 */

namespace Wikimedia\Tests;

use MediaWikiCoversValidator;
use PHPUnit\Framework\TestCase;
use Wikimedia\Timing\Timing;

/**
 * @covers \Wikimedia\Timing\Timing
 */
class TimingTest extends TestCase {

	use MediaWikiCoversValidator;

	public function testClearMarks() {
		$timing = new Timing;
		$this->assertCount( 1, $timing->getEntries() );

		$timing->mark( 'a' );
		$timing->mark( 'b' );
		$this->assertCount( 3, $timing->getEntries() );

		$timing->clearMarks( 'a' );
		$this->assertNull( $timing->getEntryByName( 'a' ) );
		$this->assertNotNull( $timing->getEntryByName( 'b' ) );

		$timing->clearMarks();
		$this->assertCount( 1, $timing->getEntries() );
	}

	public function testMark() {
		$timing = new Timing;
		$timing->mark( 'a' );

		$entry = $timing->getEntryByName( 'a' );
		$this->assertEquals( 'a', $entry['name'] );
		$this->assertEquals( 'mark', $entry['entryType'] );
		$this->assertArrayHasKey( 'startTime', $entry );
		$this->assertSame( 0, $entry['duration'] );

		usleep( 100 );
		$timing->mark( 'a' );
		$newEntry = $timing->getEntryByName( 'a' );
		$this->assertGreaterThan( $entry['startTime'], $newEntry['startTime'] );
	}

	public function testMeasure() {
		$timing = new Timing;

		$timing->mark( 'a' );
		usleep( 100 );
		$timing->mark( 'b' );

		$a = $timing->getEntryByName( 'a' );
		$b = $timing->getEntryByName( 'b' );

		$timing->measure( 'a_to_b', 'a', 'b' );

		$entry = $timing->getEntryByName( 'a_to_b' );
		$this->assertEquals( 'a_to_b', $entry['name'] );
		$this->assertEquals( 'measure', $entry['entryType'] );
		$this->assertEquals( $a['startTime'], $entry['startTime'] );
		$this->assertEquals( $b['startTime'] - $a['startTime'], $entry['duration'] );
	}

	public function testGetEntriesByType() {
		$timing = new Timing;

		$timing->mark( 'mark_a' );
		usleep( 100 );
		$timing->mark( 'mark_b' );
		usleep( 100 );
		$timing->mark( 'mark_c' );

		$timing->measure( 'measure_a', 'mark_a', 'mark_b' );
		$timing->measure( 'measure_b', 'mark_b', 'mark_c' );

		$marks = array_column( $timing->getEntriesByType( 'mark' ), 'name' );

		$this->assertEquals( [ 'requestStart', 'mark_a', 'mark_b', 'mark_c' ], $marks );

		$measures = array_column( $timing->getEntriesByType( 'measure' ), 'name' );

		$this->assertEquals( [ 'measure_a', 'measure_b' ], $measures );
	}
}
