<?php

namespace MediaWiki\Tests\Search;

use MediaWiki\MainConfigNames;
use MediaWiki\Search\SpecialPageSuggester;
use MediaWiki\Title\Title;
use MediaWikiLangTestCase;

/**
 * Unfortunately Special Pages want the database...
 * @group Database
 * @group Search
 * @covers \MediaWiki\Search\SpecialPageSuggester
 */
class SpecialPageSuggesterTest extends MediaWikiLangTestCase {
	private SpecialPageSuggester $specialPageSuggester;

	protected function setUp(): void {
		parent::setUp();

		// Avoid special pages from extensions interfering with the tests
		$this->overrideConfigValues( [
			MainConfigNames::SpecialPages => [],
			MainConfigNames::Hooks, []
		] );
		$this->specialPageSuggester = new SpecialPageSuggester(
			$this->getServiceContainer()->getSpecialPageFactory(),
			$this->getServiceContainer()->getContentLanguage(),
		);
	}

	public static function provideSearch(): \Generator {
		yield 'empty search' => [
			'',
			[
				'Special:ActiveUsers',
				'Special:AllMessages',
				'Special:AllPages',
			]
		];
		yield 'simple prefix' => [
			'Un',
			[
				'Special:Unblock',
				'Special:UncategorizedCategories',
				'Special:UncategorizedFiles',
			]
		];
		yield 'page name' => [ 'EditWatchlist', [] ];
		yield 'sub pages' => [
			'EditWatchlist/',
			[ 'Special:EditWatchlist/clear', 'Special:EditWatchlist/raw' ]
		];
		yield 'prefix on sub pages' => [
			'EditWatchlist/cl',
			[ 'Special:EditWatchlist/clear' ]
		];
	}

	/**
	 * @dataProvider provideSearch
	 */
	public function testSearch( string $search, array $expected_results ): void {
		$results = $this->specialPageSuggester->suggest( $search, 3, 0 );
		$title_strings = array_map( static fn ( Title $title ): string => $title->getPrefixedText(), $results );
		$this->assertEquals( $expected_results, $title_strings );
	}

	public function testSearchWithOffset(): void {
		$results = $this->specialPageSuggester->suggest( 'Un', 2, 0 );
		$expected_results = [ $results[1] ];
		$results = $this->specialPageSuggester->suggest( 'Un', 1, 1 );
		$this->assertEquals( $expected_results, $results );
	}

}
