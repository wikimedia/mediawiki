<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @author Ori Livneh <ori@wikimedia.org>
 */

class TimingTest extends PHPUnit_Framework_TestCase {

	/**
	 * @covers Timing::clearMarks
	 * @covers Timing::getEntries
	 */
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

	/**
	 * @covers Timing::mark
	 * @covers Timing::getEntryByName
	 */
	public function testMark() {
		$timing = new Timing;
		$timing->mark( 'a' );

		$entry = $timing->getEntryByName( 'a' );
		$this->assertEquals( 'a', $entry['name'] );
		$this->assertEquals( 'mark', $entry['entryType'] );
		$this->assertArrayHasKey( 'startTime', $entry );
		$this->assertEquals( 0, $entry['duration'] );

		$timing->mark( 'a' );
		$newEntry = $timing->getEntryByName( 'a' );
		$this->assertGreaterThan( $entry['startTime'], $newEntry['startTime'] );
	}

	/**
	 * @covers Timing::measure
	 */
	public function testMeasure() {
		$timing = new Timing;

		$timing->mark( 'a' );
		$a = $timing->getEntryByName( 'a' );

		$timing->mark( 'b' );
		$b = $timing->getEntryByName( 'b' );

		$timing->measure( 'a_to_b', 'a', 'b' );

		$entry = $timing->getEntryByName( 'a_to_b' );
		$this->assertEquals( 'a_to_b', $entry['name'] );
		$this->assertEquals( 'measure', $entry['entryType'] );
		$this->assertEquals( $a['startTime'], $entry['startTime'] );
		$this->assertEquals( $b['startTime'] - $a['startTime'], $entry['duration'] );
	}
}
