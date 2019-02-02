<?php

/**
 * Test for filter utilities.
 *
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
 */

class SearchSuggestionSetTest extends \PHPUnit\Framework\TestCase {
	/**
	 * Test that adding a new suggestion at the end
	 * will keep proper score ordering
	 * @covers SearchSuggestionSet::append
	 */
	public function testAppend() {
		$set = SearchSuggestionSet::emptySuggestionSet();
		$this->assertEquals( 0, $set->getSize() );
		$set->append( new SearchSuggestion( 3 ) );
		$this->assertEquals( 3, $set->getWorstScore() );
		$this->assertEquals( 3, $set->getBestScore() );

		$suggestion = new SearchSuggestion( 4 );
		$set->append( $suggestion );
		$this->assertEquals( 2, $set->getWorstScore() );
		$this->assertEquals( 3, $set->getBestScore() );
		$this->assertEquals( 2, $suggestion->getScore() );

		$suggestion = new SearchSuggestion( 2 );
		$set->append( $suggestion );
		$this->assertEquals( 1, $set->getWorstScore() );
		$this->assertEquals( 3, $set->getBestScore() );
		$this->assertEquals( 1, $suggestion->getScore() );

		$scores = $set->map( function ( $s ) {
			return $s->getScore();
		} );
		$sorted = $scores;
		asort( $sorted );
		$this->assertEquals( $sorted, $scores );
	}

	/**
	 * Test that adding a new best suggestion will keep proper score
	 * ordering
	 * @covers SearchSuggestionSet::getWorstScore
	 * @covers SearchSuggestionSet::getBestScore
	 * @covers SearchSuggestionSet::prepend
	 */
	public function testInsertBest() {
		$set = SearchSuggestionSet::emptySuggestionSet();
		$this->assertEquals( 0, $set->getSize() );
		$set->prepend( new SearchSuggestion( 3 ) );
		$this->assertEquals( 3, $set->getWorstScore() );
		$this->assertEquals( 3, $set->getBestScore() );

		$suggestion = new SearchSuggestion( 4 );
		$set->prepend( $suggestion );
		$this->assertEquals( 3, $set->getWorstScore() );
		$this->assertEquals( 4, $set->getBestScore() );
		$this->assertEquals( 4, $suggestion->getScore() );

		$suggestion = new SearchSuggestion( 0 );
		$set->prepend( $suggestion );
		$this->assertEquals( 3, $set->getWorstScore() );
		$this->assertEquals( 5, $set->getBestScore() );
		$this->assertEquals( 5, $suggestion->getScore() );

		$suggestion = new SearchSuggestion( 2 );
		$set->prepend( $suggestion );
		$this->assertEquals( 3, $set->getWorstScore() );
		$this->assertEquals( 6, $set->getBestScore() );
		$this->assertEquals( 6, $suggestion->getScore() );

		$scores = $set->map( function ( $s ) {
			return $s->getScore();
		} );
		$sorted = $scores;
		asort( $sorted );
		$this->assertEquals( $sorted, $scores );
	}

	/**
	 * @covers SearchSuggestionSet::shrink
	 */
	public function testShrink() {
		$set = SearchSuggestionSet::emptySuggestionSet();
		for ( $i = 0; $i < 100; $i++ ) {
			$set->append( new SearchSuggestion( 0 ) );
		}
		$set->shrink( 10 );
		$this->assertEquals( 10, $set->getSize() );

		$set->shrink( 0 );
		$this->assertEquals( 0, $set->getSize() );
	}

	// TODO: test for fromTitles
}
