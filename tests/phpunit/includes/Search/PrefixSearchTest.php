<?php

use MediaWiki\MainConfigNames;
use MediaWiki\Search\StringPrefixSearch;
use MediaWiki\Title\Title;

/**
 * @group Search
 * @group Database
 * @covers \MediaWiki\Search\PrefixSearch
 */
class PrefixSearchTest extends MediaWikiLangTestCase {
	private const NS_NONCAP = 12346;

	public function addDBDataOnce() {
		if ( !$this->isWikitextNS( NS_MAIN ) ) {
			// tests are skipped if NS_MAIN is not wikitext
			return;
		}

		$this->insertPage( 'Sandbox' );
		$this->insertPage( 'Bar' );
		$this->insertPage( 'Example' );
		$this->insertPage( 'Example Bar' );
		$this->insertPage( 'Example Foo' );
		$this->insertPage( 'Example Foo/Bar' );
		$this->insertPage( 'Example/Baz' );
		$this->insertPage( 'Redirect test', '#REDIRECT [[Redirect Test]]' );
		$this->insertPage( 'Redirect Test' );
		$this->insertPage( 'Redirect Test Worse Result' );
		$this->insertPage( 'Redirect test2', '#REDIRECT [[Redirect Test2]]' );
		$this->insertPage( 'Redirect TEST2', '#REDIRECT [[Redirect Test2]]' );
		$this->insertPage( 'Redirect Test2' );
		$this->insertPage( 'Redirect Test2 Worse Result' );

		$this->insertPage( 'Talk:Sandbox' );
		$this->insertPage( 'Talk:Example' );

		$this->insertPage( 'User:Example' );

		$this->overrideConfigValues( [
			MainConfigNames::ExtraNamespaces => [ self::NS_NONCAP => 'NonCap' ],
			MainConfigNames::CapitalLinkOverrides => [ self::NS_NONCAP => false ],
		] );

		$this->insertPage( Title::makeTitle( self::NS_NONCAP, 'Bar' ) );
		$this->insertPage( Title::makeTitle( self::NS_NONCAP, 'Upper' ) );
		$this->insertPage( Title::makeTitle( self::NS_NONCAP, 'sandbox' ) );
	}

	protected function setUp(): void {
		parent::setUp();

		if ( !$this->isWikitextNS( NS_MAIN ) ) {
			$this->markTestSkipped( 'Main namespace does not support wikitext.' );
		}

		// Avoid special pages from extensions interfering with the tests
		$this->overrideConfigValues( [
			MainConfigNames::SpecialPages => [],
			MainConfigNames::Hooks => [],
			MainConfigNames::ExtraNamespaces => [ self::NS_NONCAP => 'NonCap' ],
			MainConfigNames::CapitalLinkOverrides => [ self::NS_NONCAP => false ],
		] );
	}

	public static function provideSearch() {
		return [
			[ [
				'Empty string',
				'query' => '',
				'results' => [],
			] ],
			[ [
				'Main namespace with title prefix',
				'query' => 'Ex',
				'results' => [
					'Example',
					'Example/Baz',
					'Example Bar',
				],
				// Third result when testing offset
				'offsetresult' => [
					'Example Foo',
				],
			] ],
			[ [
				'Talk namespace prefix',
				'query' => 'Talk:',
				'results' => [
					'Talk:Example',
					'Talk:Sandbox',
				],
			] ],
			[ [
				'User namespace prefix',
				'query' => 'User:',
				'results' => [
					'User:Example',
				],
			] ],
			[ [
				'Special page name',
				'query' => 'Special:EditWatchlist',
				'results' => [],
			] ],
			[ [
				'Namespace with case sensitive first letter',
				'query' => 'NonCap:upper',
				'results' => []
			] ],
			[ [
				'Multinamespace search',
				'query' => 'B',
				'results' => [
					'Bar',
					'NonCap:Bar',
				],
				'namespaces' => [ NS_MAIN, self::NS_NONCAP ],
			] ],
			[ [
				'Multinamespace search with lowercase first letter',
				'query' => 'sand',
				'results' => [
					'Sandbox',
					'NonCap:sandbox',
				],
				'namespaces' => [ NS_MAIN, self::NS_NONCAP ],
			] ],
		];
	}

	/**
	 * @dataProvider provideSearch
	 * @covers \MediaWiki\Search\PrefixSearch::search
	 * @covers \MediaWiki\Search\PrefixSearch::searchBackend
	 */
	public function testSearch( array $case ) {
		$this->overrideConfigValue( MainConfigNames::Hooks, [] );

		$namespaces = $case['namespaces'] ?? [];

		$searcher = new StringPrefixSearch;
		$results = $searcher->search( $case['query'], 3, $namespaces );
		$this->assertEquals(
			$case['results'],
			$results,
			$case[0]
		);
	}

	/**
	 * @dataProvider provideSearch
	 * @covers \MediaWiki\Search\PrefixSearch::search
	 * @covers \MediaWiki\Search\PrefixSearch::searchBackend
	 */
	public function testSearchWithOffset( array $case ) {
		$this->overrideConfigValue( MainConfigNames::Hooks, [] );

		$namespaces = $case['namespaces'] ?? [];

		$searcher = new StringPrefixSearch;
		$results = $searcher->search( $case['query'], 3, $namespaces, 1 );

		// We don't expect the first result when offsetting
		array_shift( $case['results'] );
		// And sometimes we expect a different last result
		$expected = isset( $case['offsetresult'] ) ?
			array_merge( $case['results'], $case['offsetresult'] ) :
			$case['results'];

		$this->assertEquals(
			$expected,
			$results,
			$case[0]
		);
	}

}
