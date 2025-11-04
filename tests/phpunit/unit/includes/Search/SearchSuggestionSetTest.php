<?php

/**
 * @license GPL-2.0-or-later
 */

/**
 * Test for filter utilities.
 */
class SearchSuggestionSetTest extends \MediaWikiUnitTestCase {
	/**
	 * Test that adding a new suggestion at the end
	 * will keep proper score ordering
	 * @covers \SearchSuggestionSet::append
	 */
	public function testAppend() {
		$set = SearchSuggestionSet::emptySuggestionSet();
		$this->assertSame( 0, $set->getSize() );
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
		$this->assertSame( 1, $set->getWorstScore() );
		$this->assertEquals( 3, $set->getBestScore() );
		$this->assertSame( 1, $suggestion->getScore() );

		$scores = $set->map( static function ( $s ) {
			return $s->getScore();
		} );
		$sorted = $scores;
		asort( $sorted );
		$this->assertEquals( $sorted, $scores );
	}

	/**
	 * Test that adding a new best suggestion will keep proper score
	 * ordering
	 * @covers \SearchSuggestionSet::getWorstScore
	 * @covers \SearchSuggestionSet::getBestScore
	 * @covers \SearchSuggestionSet::prepend
	 */
	public function testInsertBest() {
		$set = SearchSuggestionSet::emptySuggestionSet();
		$this->assertSame( 0, $set->getSize() );
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

		$scores = $set->map( static function ( $s ) {
			return $s->getScore();
		} );
		$sorted = $scores;
		asort( $sorted );
		$this->assertEquals( $sorted, $scores );
	}

	/**
	 * @covers \SearchSuggestionSet::shrink
	 */
	public function testShrink() {
		$set = SearchSuggestionSet::emptySuggestionSet();
		for ( $i = 0; $i < 100; $i++ ) {
			$set->append( new SearchSuggestion( 0, 'test', null, $i ) );
		}
		$set->shrink( 10 );
		$this->assertEquals( 10, $set->getSize() );
		$this->assertTrue( $set->hasMoreResults() );
		$set->prepend( new SearchSuggestion( 0, 'test', null, 10 ) );
		$this->assertEquals( 11, $set->getSize() );
		$this->assertEquals( 10, $set->getSuggestions()[0]->getSuggestedTitleID() );

		$set->shrink( 0 );
		$this->assertSame( 0, $set->getSize() );
	}

	/**
	 * @covers \SearchSuggestionSet::remove
	 */
	public function testRemove() {
		$set = SearchSuggestionSet::emptySuggestionSet();
		$sug = new SearchSuggestion( 0.3, 'sugg', null, 1 );
		$set->append( $sug );
		// same text, id
		$this->assertTrue( $set->remove( $sug ) );
		$this->assertSame( 0, $set->getSize() );

		$set->append( $sug );
		// different text, id
		$this->assertFalse( $set->remove( new SearchSuggestion( 0.3, 'something else', null, 2 ) ) );
		$this->assertSame( 1, $set->getSize() );

		// same text, different/missing id
		$this->assertTrue( $set->remove( new SearchSuggestion( 0.3, 'sugg', null, 2 ) ) );
		$this->assertSame( 0, $set->getSize() );
	}

	public static function provideNoTitles(): iterable {
		yield 'Empty Array' => [ [] ];
	}

	/**
	 * @covers \SearchSuggestionSet::fromTitles
	 * @dataProvider provideNoTitles
	 */
	public function testFromNoTitles( array $titles ): void {
		$actual = SearchSuggestionSet::fromTitles( $titles );

		$this->assertSame( 0, $actual->getSize() );
		$this->assertSame( [], $actual->getSuggestions() );
		$this->assertInstanceOf( SearchSuggestionSet::class, $actual );
	}
}
